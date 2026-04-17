<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\InvoiceResource;
use App\Actions\V1\ResolveInvoicePaymentTokenAction;

class InvoiceController extends Controller
{
    public function show(ResolveInvoicePaymentTokenAction $resolveInvoicePaymentToken, string $paymentToken)
    {
        $invoice = $resolveInvoicePaymentToken->execute(paymentToken: $paymentToken);

        return response()->json([
            'invoice' => new InvoiceResource($invoice),
        ]);
    }
}
