<?php

namespace Tests\Feature\Api;

use App\Actions\V1\SendPaymentHttpRequestAction;
use App\Models\City;
use App\Models\CityGateway;
use App\Models\Gateway;
use App\Models\GatewayModule;
use App\Models\Invoice;
use App\Models\Module;
use App\Models\Status;
use App\Models\User;
use Database\Seeders\StatusesTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Response;
use Mockery;
use Tests\TestCase;

class PaymentInitiateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(StatusesTableSeeder::class);
    }

    private function setupInvoiceAndGateway($inCity = true): array
    {
        $invoiceStatus = Status::pending()->invoiceStatus()->first();

        $city = City::factory()->create();
        $module = Module::factory()->create(['name' => 'order']);
        $user = User::factory()->create(['city_id' => $city->id]);

        $gateway = Gateway::factory()->create(['type' => 'clickpay', 'is_active' => true]);

        if ($inCity) {
            CityGateway::create(['city_id' => $city->id, 'gateway_id' => $gateway->id]);
        }
        GatewayModule::create(['module_id' => $module->id, 'gateway_id' => $gateway->id]);

        $invoice = Invoice::factory()->order()->create([
            'user_id' => $user->id,
            'closed_at' => null,
            'current_status_id' => $invoiceStatus->id,
            'payment_token' => 'test-token-123',
            'total_amount' => 500,
        ]);

        return [$invoice, $gateway];
    }

    private function mockClickpayResponse(string $reference = 'CLICKPAY-REF-001'): void
    {
        $mockResponse = Mockery::mock(Response::class);
        $mockResponse->shouldReceive('failed')->andReturn(false);
        $mockResponse->shouldReceive('json')->andReturn([
            'tran_ref' => $reference,
            'redirect_url' => 'https://secure.clickpay.com.sa/payment/redirect/'.$reference,
        ]);

        $this->mock(SendPaymentHttpRequestAction::class, function ($mock) use ($mockResponse) {
            $mock->shouldReceive('execute')->once()->andReturn($mockResponse);
        });
    }

    public function test_can_initiate_payment_with_valid_data()
    {
        [$invoice, $gateway] = $this->setupInvoiceAndGateway();
        $this->mockClickpayResponse();

        $response = $this->postJson('/api/v1/transactions', [
            'payment_token' => 'test-token-123',
            'gateway' => $gateway->id,
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['message', 'redirect_url']);

        $this->assertDatabaseHas('transactions', [
            'invoice_id' => $invoice->id,
            'gateway_id' => $gateway->id,
        ]);

        $this->assertDatabaseHas('transactions', [
            'trans_reference_number' => 'CLICKPAY-REF-001',
        ]);
    }

    public function test_returns_422_for_missing_fields()
    {
        $response = $this->postJson('/api/v1/transactions', []);

        $response->assertStatus(422);
    }

    public function test_returns_403_for_closed_invoice()
    {
        $invoiceStatus = Status::pending()->invoiceStatus()->first();
        $city = City::factory()->create();
        $user = User::factory()->create(['city_id' => $city->id]);

        Invoice::factory()->order()->create([
            'user_id' => $user->id,
            'closed_at' => now(),
            'current_status_id' => $invoiceStatus->id,
            'payment_token' => 'closed-token',
            'total_amount' => 500,
        ]);

        $response = $this->postJson('/api/v1/transactions', [
            'payment_token' => 'closed-token',
            'gateway' => 1,
        ]);

        $response->assertStatus(403);
    }

    public function test_returns_422_for_unavailable_gateway()
    {
        [$invoice, $gateway] = $this->setupInvoiceAndGateway(inCity: false);

        $response = $this->postJson('/api/v1/transactions', [
            'payment_token' => 'test-token-123',
            'gateway' => $gateway->id,
        ]);

        $response->assertStatus(422);
    }
}
