<?php

use App\Http\Controllers\Api\V1\CallbackController;
use App\Http\Controllers\Api\V1\InvoiceController;
use App\Http\Controllers\Api\V1\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('invoices/{paymentToken}', [InvoiceController::class, 'show'])->name('invoices.show');

Route::apiResource('transactions', TransactionController::class)->only('store');

Route::apiResource('callback', CallbackController::class)->only('store');
