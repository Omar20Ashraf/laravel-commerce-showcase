<?php

namespace App\Actions\V1;

use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class SendPaymentHttpRequestAction
{
    public function execute(string $url, array $headers,array $payload): Response
    {
        try {
            $response = Http::withHeaders($headers)
                ->timeout(config('payment.request_time_out_in_seconds'))
                ->acceptJson()
                ->post($url, $payload);

            if ($response->failed()) {
                throw new Exception(
                    'Payment gateway request failed. Status: '.$response->status().'. Body: '.$response->body()
                );
            }

            return $response;
        } catch (Exception $exception) {
            throw new Exception(
                'Payment gateway HTTP error: '.$exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }
}
