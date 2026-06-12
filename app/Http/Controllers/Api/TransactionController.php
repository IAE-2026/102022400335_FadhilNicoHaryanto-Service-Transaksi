<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class TransactionController extends Controller
{
    #[OA\Get(
        path: "/api/v1/transactions",
        summary: "Get all transactions",
        security: [["ApiKeyAuth" => []]],
        tags: ["Transactions"]
    )]
    #[OA\Response(
        response: 200,
        description: "Success"
    )]
    public function index()
    {
        $transactions = Transaction::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Transactions retrieved successfully',
            'data' => $transactions,
            'meta' => [
                'service_name' => 'Transaction-Service',
                'api_version' => 'v1'
            ]
        ], 200);
    }

    #[OA\Get(
        path: "/api/v1/transactions/{id}",
        summary: "Get transaction by ID",
        security: [["ApiKeyAuth" => []]],
        tags: ["Transactions"]
    )]
    #[OA\Parameter(
        name: "id",
        in: "path",
        required: true,
        description: "Transaction ID"
    )]
    #[OA\Response(
        response: 200,
        description: "Success"
    )]
    public function show($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaction not found',
                'errors' => null
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Transaction retrieved successfully',
            'data' => $transaction,
            'meta' => [
                'service_name' => 'Transaction-Service',
                'api_version' => 'v1'
            ]
        ], 200);
    }

    #[OA\Post(
        path: "/api/v1/transactions",
        summary: "Create new transaction",
        security: [["ApiKeyAuth" => []]],
        tags: ["Transactions"]
    )]

    #[OA\RequestBody(
    required: true,
    content: new OA\JsonContent(
        required: ["sender", "receiver", "amount"],
        properties: [
            new OA\Property(
                property: "sender",
                type: "string",
                example: "Fadhil"
            ),
            new OA\Property(
                property: "receiver",
                type: "string",
                example: "Budi"
            ),
            new OA\Property(
                property: "amount",
                type: "integer",
                example: 50000
            )
        ]
    )
)]
    #[OA\Response(
        response: 201,
        description: "Transaction created"
    )]
    public function store(Request $request)
    {
        $transaction = Transaction::create([
            'sender' => $request->sender,
            'receiver' => $request->receiver,
            'amount' => $request->amount,
            'status' => 'success',
            'transaction_date' => now()
        ]);

<<<<<<< HEAD
        // Integration with IAE Central
        $iaeService = new \App\Services\IaeIntegrationService();
        
        // 1. SOAP Audit
        $receipt = $iaeService->sendAudit($transaction);
        if ($receipt) {
            $transaction->audit_receipt = $receipt;
            $transaction->save();
        }

        // 2. AMQP Publisher (via HTTP Bridge)
        $iaeService->publishMessage($transaction);

=======
>>>>>>> 2d3a04638b2499e38ca6897529c1c4a8fa88b97a
        return response()->json([
            'status' => 'success',
            'message' => 'Transaction created successfully',
            'data' => $transaction,
            'meta' => [
                'service_name' => 'Transaction-Service',
                'api_version' => 'v1'
            ]
        ], 201);
    }

    public function filterByDate($startDate, $endDate)
    {
        $transactions = Transaction::whereBetween(
            'transaction_date',
            [$startDate, $endDate]
        )->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Transactions retrieved successfully',
            'data' => $transactions,
            'meta' => [
                'service_name' => 'Transaction-Service',
                'api_version' => 'v1'
            ]
        ], 200);
    }
}