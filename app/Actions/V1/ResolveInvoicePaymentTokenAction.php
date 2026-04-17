<?php

namespace App\Actions\V1;

use App\Models\Invoice;
use Illuminate\Support\Facades\Gate;

class ResolveInvoicePaymentTokenAction
{
    public function __construct(
        private readonly PaymentLogAction $paymentLogAction
    ) {
    }

    public function execute(string $paymentToken): Invoice
    {
        $invoice = Invoice::with('lines')->where('payment_token', $paymentToken)->first();

        if (! Gate::allows('pay', $invoice)) {
            abort(403);
        }

        $this->paymentLogAction->execute(
            message: 'Retrieving available gateways',
            data: ['invoice_id' => $invoice->id]
        );

        return $invoice;
    }
}
