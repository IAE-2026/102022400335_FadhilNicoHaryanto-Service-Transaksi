<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
        description: "Success",
        content: new OA\JsonContent()
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
            ],
            'errors' => null
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
        description: "Success",
        content: new OA\JsonContent()
    )]
    public function show($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaction not found',
                'data' => null,
                'meta' => null,
                'errors' => ['Transaction not found']
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Transaction retrieved successfully',
            'data' => $transaction,
            'meta' => [
                'service_name' => 'Transaction-Service',
                'api_version' => 'v1'
            ],
            'errors' => null
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
        description: "Transaction created",
        content: new OA\JsonContent()
    )]
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sender' => 'required|string',
            'receiver' => 'required|string',
            'amount' => 'required|numeric|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'data' => null,
                'meta' => null,
                'errors' => $validator->errors()->all()
            ], 422);
        }

        $transaction = Transaction::create([
            'sender' => $request->sender,
            'receiver' => $request->receiver,
            'amount' => $request->amount,
            'status' => 'success',
            'transaction_date' => now()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Transaction created successfully',
            'data' => $transaction,
            'meta' => [
                'service_name' => 'Transaction-Service',
                'api_version' => 'v1'
            ],
            'errors' => null
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
            ],
            'errors' => null
        ], 200);
    }
}