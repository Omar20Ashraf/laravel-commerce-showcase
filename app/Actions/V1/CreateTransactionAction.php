<?php

namespace App\Actions\V1;

use App\Models\Gateway;
use App\Models\Invoice;
use App\Models\Status;
use App\Services\Api\V1\PaymentGatewayManager;

class CreateTransactionAction
{
    public function __construct(
        private readonly PaymentGatewayManager $paymentGatewayManager,
        private readonly PaymentLogAction $paymentLogAction,
    ) {}

    public function execute(Invoice $invoice, int $gatewayId): string
    {
        $gateway = Gateway::findOrFail($gatewayId);

        $pendingStatus = Status::transactionStatus()->pending()->first();

        $transaction = $invoice->transactions()->create([
            'gateway_id' => $gateway->id,
            'current_status_id' => $pendingStatus->id,
        ]);

        $this->paymentLogAction->execute(
            message: 'Transaction created — initiating payment',
            data: [
                'invoice_id' => $invoice->id,
                'transaction_id' => $transaction->id,
                'gateway_type' => $gateway->type,
            ]
        );

        $adapter = $this->paymentGatewayManager->resolve($gateway->type);

        $result = $adapter->initiate(transaction: $transaction);

        $transaction->update([
            'trans_reference_number' => $result['gateway_reference'],
            'due_date_at' => now()->addMinutes($result['due_date_minutes'])
        ]);

        return $result['redirect_url'];
    }
}
