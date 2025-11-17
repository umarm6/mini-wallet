<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransferRequest;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    public function __construct(private TransactionService $transactionService)
    {
    }

    /**
     * Get transaction history and current balance.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 50);
        $perPage = min($perPage, 100); // Max 100 per page

        $data = $this->transactionService->getTransactionHistory($request->user(), $perPage);

        return response()->json([
            'data' => $data['transactions'],
            'balance' => $data['balance'],
        ]);
    }

    /**
     * Execute a money transfer.
     */
    public function store(TransferRequest $request): JsonResponse
    {
        try {
            $result = $this->transactionService->transfer(
                $request->user(),
                $request->input('receiver_id'),
                $request->input('amount')
            );

            return response()->json($result, 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred during the transfer.',
            ], 500);
        }
    }
}
