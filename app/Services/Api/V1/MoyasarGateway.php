<?php

namespace App\Services\Api\V1;

use App\Actions\V1\PaymentLogAction;
use App\Actions\V1\SendPaymentHttpRequestAction;
use App\Contracts\PaymentGatewayContract;
use App\Models\Transaction;
use Exception;

class MoyasarGateway implements PaymentGatewayContract
{
    public function __construct(
        private readonly SendPaymentHttpRequestAction $sendPaymentHttpRequest,
        private readonly PaymentLogAction $paymentLogAction,
    ) {}

    public function initiate(Transaction $transaction): array
    {
        $payload = $this->prepareRequestData(transaction: $transaction);

        $payload['callback_url'] = url(route('api.' . app('current_api_version') . 'callback'));

        try {
            $response = $this->sendPaymentHttpRequest->execute(
                url: config('payment.gateways.moyasar.base_url').'/payments',
                headers: [
                    'Authorization' => 'Basic '.base64_encode(config('payment.gateways.moyasar.api_key').':'),
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                payload: $payload
            );

            $body = $response->json();
        } catch (Exception $exception) {
            $this->paymentLogAction->execute(
                message: 'Moyasar payment initiation failed',
                data: ['transaction_id' => $transaction->id, 'error' => $exception->getMessage()]
            );

            throw $exception;
        }

        $this->paymentLogAction->execute(
            message: 'Moyasar payment initiated successfully',
            data: [
                'transaction_id' => $transaction->id,
                'amount' => $transaction->amount,
                'gateway_reference' => $body['id'] ?? null,
            ]
        );

        return [
            'redirect_url' => $body['source']['transaction_url'] ?? $body['url'] ?? '',
            'gateway_reference' => $body['id'],
            'due_date_minutes' => config('payment.gateways.moyasar.due_date_in_minutes'),
        ];
    }

    public function prepareRequestData(Transaction $transaction): array
    {
        return [
            'amount' => (int) round($transaction->amount * 100), // Moyasar expects amount in halalas
            'currency' => 'SAR',
            'description' => 'Invoice payment — '.$transaction->reference_number,
            'source' => [
                'type' => 'creditcard',
            ],
            'callback_url' => config('payment.return_url'),
        ];
    }
}
