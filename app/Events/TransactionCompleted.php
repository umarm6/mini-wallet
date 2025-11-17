<?php

namespace App\Events;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithBroadcasting;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TransactionCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels,InteractsWithBroadcasting;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Transaction $transaction,
        public array $senderUpdate,
        public array $receiverUpdate
    ) {
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel(sprintf('transaction_user.%s', $this->transaction->sender_id)),
            new PrivateChannel(sprintf('transaction_user.%s', $this->transaction->receiver_id)),

        ];
    }

    public function broadcastAs(): string{
        return 'transactionCompleted';
    }

    public function broadcastWith(): array{
        $receiver = User::find($this->transaction->receiver_id);
        $sender = User::find($this->transaction->sender_id);

        return [
            'transaction' => [
                'id' => $this->transaction->id,
                'sender_id' => $this->transaction->sender_id,
                'receiver_id' => $this->transaction->receiver_id,
                'amount' => $this->transaction->amount,
                'commission_fee' => $this->transaction->commission_fee,
                'created_at' => $this->transaction->created_at->toIso8601String(),
            ],
            'sender_update' => $this->senderUpdate,
            'receiver_update' => $this->receiverUpdate,
            'sender_balance' => $sender?->balance,
            'receiver_balance' => $receiver?->balance,

        ];
    }

}
