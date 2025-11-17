<?php

namespace App\Services;

use App\Events\TransactionCompleted;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Random\RandomException;

class TransactionService
{

    /**
     * Execute a money transfer with pessimistic locking for race condition prevention.
     *
     * @param User $sender
     * @param int $receiverId
     * @param string $amount
     * @return array
     * @throws ValidationException
     * @throws RandomException
     */
    public function transfer(User $sender, int $receiverId, string $amount): array
    {
        $amount = (float) $amount;
        $receiver = User::find($receiverId);

        // Initial validation
        if (!$receiver) {
            throw ValidationException::withMessages([
                'receiver_id' => 'Receiver not found.',
            ]);
        }

        $commissionFee = round($amount *   config('transaction.commission_rate'), 2);
        $totalDebit = $amount + $commissionFee;

        if ($sender->balance < $totalDebit) {
            throw ValidationException::withMessages([
                'amount' => 'Insufficient balance for this transfer.',
            ]);
        }

        $reference = Str::uuid()->toString();
        $transaction = null;

        // Retry logic for deadlock handling
        for ($attempt = 0; $attempt < config('transaction.max_retry'); $attempt++) {
            try {
                $transaction = DB::transaction(function () use (
                    $sender,
                    $receiver,
                    $amount,
                    $commissionFee,
                    $reference
                ) {
                    // Lock sender for update (pessimistic locking)
                    $lockedSender = User::where('id', $sender->id)
                        ->lockForUpdate()
                        ->first();

                    // Re-validate balance after acquiring lock
                    $totalDebit = $amount + $commissionFee;
                    if ($lockedSender->balance < $totalDebit) {
                        throw ValidationException::withMessages([
                            'amount' => 'Insufficient balance. Concurrent transfer detected.',
                        ]);
                    }

                    // Lock receiver for update
                    $lockedReceiver = User::where('id', $receiver->id)
                        ->lockForUpdate()
                        ->first();

                    // Update balances atomically
                    $lockedSender->decrement('balance', $totalDebit);
                    $lockedReceiver->increment('balance', (float) $amount);

                    // Create transaction record
                    $transaction = Transaction::create([
                        'sender_id' => $lockedSender->id,
                        'receiver_id' => $lockedReceiver->id,
                        'amount' => $amount,
                        'commission_fee' => $commissionFee,
                        'status' => 'completed',
                        'reference' => $reference,
                    ]);

                    // Refresh models to get updated balances
                    $lockedSender->refresh();
                    $lockedReceiver->refresh();

                    return [
                        'transaction' => $transaction,
                        'sender' => $lockedSender,
                        'receiver' => $lockedReceiver,
                    ];
                });

                // Success - break retry loop
                break;
            } catch (\Exception $e) {
                if ($attempt === config('transaction.max_retry') - 1) {
                    throw $e;
                }

                // Exponential backoff with jitter
                $backoffMs = (2 ** $attempt) * 100 + random_int(0, 50);
                usleep($backoffMs * 1000);
            }
        }

        if (!$transaction) {
            throw new \Exception('Transaction failed after maximum retries.');
        }

        // Broadcast real-time event
        broadcast(new TransactionCompleted(
            $transaction['transaction'],
            [
                'balance' => $transaction['sender']->balance,
                'user_id' => $transaction['sender']->id,
            ],
            [
                'balance' => $transaction['receiver']->balance,
                'user_id' => $transaction['receiver']->id,
            ]
        ))->toOthers();

        return [
            'status' => 'success',
            'transaction' => $transaction['transaction']->load(['sender', 'receiver']),
            'new_balance' => $transaction['sender']->balance,
        ];
    }

    /**
     * Get transaction history for authenticated user.
     *
     * @param User $user
     * @param int $perPage
     * @return array
     */
    public function getTransactionHistory(User $user, int $perPage = 50): array
    {
        // Use UNION for efficient query
        $sentTransactions = Transaction::where('sender_id', $user->id)
            ->select(['id', 'sender_id as from_id', 'receiver_id as to_id', 'amount',
                'commission_fee', 'status', 'created_at'])
            ->addSelect(DB::raw("'sent' as type"));

        $receivedTransactions = Transaction::where('receiver_id', $user->id)
            ->select(['id', 'sender_id as from_id', 'receiver_id as to_id', 'amount',
                'commission_fee', 'status', 'created_at'])
            ->addSelect(DB::raw("'received' as type"));

        $transactions = $sentTransactions->union($receivedTransactions)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return [
            'transactions' => $transactions,
            'balance' => $user->balance,
        ];
    }
}

