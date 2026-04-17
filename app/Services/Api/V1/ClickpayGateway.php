<?php

namespace App\Services\Api\V1;

use App\Actions\V1\PaymentLogAction;
use App\Actions\V1\SendPaymentHttpRequestAction;
use App\Contracts\PaymentGatewayContract;
use App\Models\Transaction;
use Exception;

class ClickpayGateway implements PaymentGatewayContract
{
    public function __construct(
        private readonly SendPaymentHttpRequestAction $sendPaymentHttpRequest,
        private readonly PaymentLogAction $paymentLogAction,
    ) {}

    public function initiate(Transaction $transaction): array
    {
        $payload = $this->prepareRequestData(transaction: $transaction);

        $payload['callback_url'] = $transaction->callbackUrl;

        try {
            $response = $this->sendPaymentHttpRequest->execute(
                url: config('payment.gateways.clickpay.base_url').'/payment/request',
                headers: [
                    'authorization' => config('payment.gateways.clickpay.server_key'),
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                payload: $payload
            );

            $body = $response->json();
        } catch (Exception $exception) {
            $this->paymentLogAction->execute(
                message: 'Clickpay payment initiation failed',
                data: ['transaction_id' => $transaction->id, 'error' => $exception->getMessage()]
            );

            throw $exception;
        }

        $this->paymentLogAction->execute(
            message: 'Clickpay payment initiated successfully',
            data: [
                'transaction_id' => $transaction->id,
                'amount' => $transaction->amount,
                'gateway_reference' => $body['tran_ref'] ?? null,
            ]
        );

        return [
            'redirect_url' => $body['redirect_url'],
            'gateway_reference' => $body['tran_ref'],
            'due_date_minutes' => config('payment.gateways.clickpay.due_date_in_minutes'),
        ];
    }

    public function prepareRequestData(Transaction $transaction): array
    {
        return [
            'profile_id' => config('payment.gateways.clickpay.profile_id'),
            'tran_type' => 'sale',
            'tran_class' => 'ecom',
            'cart_id' => $transaction->invoice->reference_number,
            'cart_description' => 'Invoice payment',
            'cart_currency' => 'SAR',
            'cart_amount' => $transaction->amount,
            'return' => config('payment.return_url'),
        ];
    }
}
