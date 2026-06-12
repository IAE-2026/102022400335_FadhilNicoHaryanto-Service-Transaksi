<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TransactionController;

<<<<<<< HEAD
Route::middleware('sso')->group(function () {
=======
Route::middleware('apikey')->group(function () {
>>>>>>> 2d3a04638b2499e38ca6897529c1c4a8fa88b97a

    Route::get('/v1/transactions', [TransactionController::class, 'index']);

    Route::get('/v1/transactions/{id}', [TransactionController::class, 'show']);

    Route::post('/v1/transactions', [TransactionController::class, 'store']);

    Route::get(
        '/v1/transactions/startDate/{startDate}/endDate/{endDate}',
        [TransactionController::class, 'filterByDate']
    );

});