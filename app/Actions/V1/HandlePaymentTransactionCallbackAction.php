<?php

namespace App\Actions\V1;

use App\Models\Transaction;
use App\Services\Api\V1\PaymentGatewayManager;

class HandlePaymentTransactionCallbackAction
{
    public function __construct(
        private readonly PaymentGatewayManager $paymentGatewayManager,
        private readonly PaymentLogAction $paymentLogAction,
    ) {}

    public function execute()
    {
        // [TO DO] check if request is valid using hash

        $callbackBody = \json_decode(\json_encode(request()->all()));

        $transaction = Transaction::where('trans_reference_number', $callbackBody->callbackBody?->tran_ref)->first();

        if (!$transaction) {
            //{TO DO} fire exception
            return;
        }

        $this->paymentLogAction->execute(
            message: 'Transaction callback received',
            data: [
                'transaction_id' => $transaction->id,
            ]
        );


        //{TO DO} create status for transaction

        //{TO DO} Mark Invoice as paid if success and create status for the invoice, mark order also as paid
    }
}
