<?php

namespace App\Services\Api\V1;

use App\Contracts\PaymentGatewayContract;
use App\Exceptions\UnsupportedGatewayException;

class PaymentGatewayManager
{
    private const GATEWAY_MAP = [
        'clickpay' => ClickpayGateway::class,
        'moyasar' => MoyasarGateway::class,
        'pay_tabs' => PayTabsGateway::class,
    ];

    public function resolve(string $type): PaymentGatewayContract
    {
        $class = self::GATEWAY_MAP[$type] ?? null;

        if (! $class) {
            throw new UnsupportedGatewayException(__('payment.errors.unsupported_gateway'));
        }

        return app($class);
    }
}
