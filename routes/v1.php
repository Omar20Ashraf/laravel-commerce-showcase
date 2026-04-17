<?php

use App\Http\Controllers\Api\V1\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::get('invoices/{paymentToken}', [InvoiceController::class, 'show'])->name('invoices.show');
