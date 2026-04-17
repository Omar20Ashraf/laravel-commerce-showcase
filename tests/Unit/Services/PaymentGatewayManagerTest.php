<?php

namespace Tests\Unit\Services;

use App\Exceptions\UnsupportedGatewayException;
use App\Services\Api\V1\ClickpayGateway;
use App\Services\Api\V1\MoyasarGateway;
use App\Services\Api\V1\PaymentGatewayManager;
use App\Services\Api\V1\PayTabsGateway;
use Tests\TestCase;

class PaymentGatewayManagerTest extends TestCase
{
    private PaymentGatewayManager $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = $this->app->make(PaymentGatewayManager::class);
    }

    public function test_resolves_clickpay_gateway()
    {
        $gateway = $this->manager->resolve('clickpay');
        $this->assertInstanceOf(ClickpayGateway::class, $gateway);
    }

    public function test_resolves_moyasar_gateway()
    {
        $gateway = $this->manager->resolve('moyasar');
        $this->assertInstanceOf(MoyasarGateway::class, $gateway);
    }

    public function test_resolves_pay_tabs_gateway()
    {
        $gateway = $this->manager->resolve('pay_tabs');
        $this->assertInstanceOf(PayTabsGateway::class, $gateway);
    }

    public function test_throws_for_unsupported_type()
    {
        $this->expectException(UnsupportedGatewayException::class);
        $this->manager->resolve('unknown_gateway_type');
    }
}
