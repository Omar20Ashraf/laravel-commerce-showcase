<?php

namespace Tests\Feature\Api;

use App\Models\City;
use App\Models\CityGateway;
use App\Models\Gateway;
use App\Models\GatewayModule;
use App\Models\Invoice;
use App\Models\Module;
use App\Models\Transaction;
use App\Models\User;
use Database\Seeders\StatusesTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentAvailableGatewaysTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(StatusesTableSeeder::class);
    }

    private function setupInvoiceAndGateway($isActive = true, $inCity = true, $inModule = true, $isPending = true)
    {
        $city = City::factory()->create();
        $module = Module::factory()->create(['name' => 'order']);
        $user = User::factory()->create(['city_id' => $city->id]);

        $gateway = Gateway::factory()->create(['type' => 'clickpay', 'is_active' => $isActive]);

        if ($inCity) {
            CityGateway::create(['city_id' => $city->id, 'gateway_id' => $gateway->id]);
        }
        if ($inModule) {
            GatewayModule::create(['module_id' => $module->id, 'gateway_id' => $gateway->id]);
        }

        $invoice = Invoice::factory()->order()->pending()->create([
            'user_id' => $user->id,
            'closed_at' => $isPending ? null : now(),
            'payment_token' => 'test-token-123',
        ]);

        return [$invoice, $gateway];
    }

    public function test_can_get_invoice_and_gateways_with_valid_token()
    {
        $this->setupInvoiceAndGateway();

        $response = $this->getJson('/api/v1/invoices/test-token-123');

        $response->assertOk();
        $response->assertJsonStructure([
            'invoice' => ['id', 'reference_number', 'total_amount', 'status', 'available_gateways'],
        ]);
        $this->assertCount(1, $response->json('invoice.available_gateways'));
    }

    public function test_returns_404_for_invalid_token()
    {
        $response = $this->getJson('/api/v1/invoices/invalid-token');

        $response->assertStatus(403);
    }

    public function test_excludes_inactive_gateways()
    {
        $this->setupInvoiceAndGateway(isActive: false);

        $response = $this->getJson('/api/v1/invoices/test-token-123');

        $response->assertOk();
        $this->assertCount(0, $response->json('invoice.available_gateways'));
    }

    public function test_excludes_gateways_not_in_city()
    {
        $this->setupInvoiceAndGateway(inCity: false);

        $response = $this->getJson('/api/v1/invoices/test-token-123');

        $response->assertOk();
        $this->assertCount(0, $response->json('invoice.available_gateways'));
    }

    public function test_excludes_gateways_not_in_module()
    {
        $this->setupInvoiceAndGateway(inModule: false);

        $response = $this->getJson('/api/v1/invoices/test-token-123');

        $response->assertOk();
        $this->assertCount(0, $response->json('invoice.available_gateways'));
    }

    public function test_returns_403_for_closed_invoice()
    {
        $this->setupInvoiceAndGateway(isPending: false);

        $response = $this->getJson('/api/v1/invoices/test-token-123');

        $response->assertStatus(403);
    }

    public function test_returns_403_for_invoice_with_existing_transaction()
    {
        [$invoice, $gateway] = $this->setupInvoiceAndGateway();
        Transaction::factory()->create(['invoice_id' => $invoice->id]);

        $response = $this->getJson('/api/v1/invoices/test-token-123');

        $response->assertStatus(403);
    }
}
