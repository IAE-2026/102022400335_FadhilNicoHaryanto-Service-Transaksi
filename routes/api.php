<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TransactionController;

Route::middleware('apikey')->group(function () {

    Route::get('/v1', [TransactionController::class, 'index']);

    Route::post('/v1', [TransactionController::class, 'store']);

    Route::get('/v1/transactions', [TransactionController::class, 'index']);

    Route::post('/v1/transactions', [TransactionController::class, 'store']);

    Route::get(
        '/v1/transactions/startDate/{startDate}/endDate/{endDate}',
        [TransactionController::class, 'filterByDate']
    );

    Route::get('/v1/transactions/{id}', [TransactionController::class, 'show']);

    Route::get('/v1/{id}', [TransactionController::class, 'show']);

});
