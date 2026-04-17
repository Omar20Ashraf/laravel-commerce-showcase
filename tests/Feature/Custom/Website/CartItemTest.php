<?php

namespace Tests\Feature\Custom\Website;

use App\Models\CartItem;
use App\Models\City;
use App\Models\Provider;
use App\Models\Service;
use App\Models\ServiceItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartItemTest extends TestCase
{
    use RefreshDatabase;

    private ServiceItem $serviceItem;

    protected function setUp(): void
    {
        parent::setUp();
        
        $provider = Provider::factory()->create(['is_active' => true]);
        $service = Service::factory()->create(['provider_id' => $provider->id]);
        $this->serviceItem = ServiceItem::factory()->create(['service_id' => $service->id]);
    }

    public function test_guest_can_add_item_to_cart()
    {
        $response = $this->post('/cart-items', [
            'service_item' => $this->serviceItem->id,
            'qty' => 2,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('cart_items', [
            'service_item_id' => $this->serviceItem->id,
            'qty' => 2,
            'user_id' => null,
        ]);
    }

    public function test_guest_cannot_add_unavailable_item()
    {
        $provider = Provider::factory()->create(['is_active' => false]);
        $service = Service::factory()->create(['provider_id' => $provider->id]);
        $unavailableItem = ServiceItem::factory()->create(['service_id' => $service->id]);

        $response = $this->post('/cart-items', [
            'service_item' => $unavailableItem->id,
            'qty' => 2,
        ]);

        $response->assertSessionHasErrors(['service_item']);
    }

    public function test_guest_can_view_cart()
    {
        CartItem::factory()->create([
            'service_item_id' => $this->serviceItem->id,
            'guest_ip' => '127.0.0.1',
            'qty' => 3,
            'user_id' => null,
        ]);

        $response = $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1'])->get('/cart-items');

        $response->assertOk();
        $response->assertViewHas('cartItems');
        $this->assertCount(1, $response->viewData('cartItems'));
    }

    public function test_guest_can_update_cart_item()
    {
        $cartItem = CartItem::factory()->create([
            'service_item_id' => $this->serviceItem->id,
            'guest_ip' => '127.0.0.1',
            'qty' => 1,
            'user_id' => null,
        ]);

        $response = $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1'])->put("/cart-items/{$cartItem->id}", [
            'qty' => 5,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('cart_items', [
            'id' => $cartItem->id,
            'qty' => 5,
        ]);
    }

    public function test_guest_cannot_update_others_cart_item()
    {
        $cartItem = CartItem::factory()->create([
            'service_item_id' => $this->serviceItem->id,
            'guest_ip' => '192.168.1.1',
            'qty' => 1,
            'user_id' => null,
        ]);

        $response = $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1'])->put("/cart-items/{$cartItem->id}", [
            'qty' => 5,
        ]);

        $response->assertForbidden();
    }

    public function test_guest_can_remove_cart_item()
    {
        $cartItem = CartItem::factory()->create([
            'service_item_id' => $this->serviceItem->id,
            'guest_ip' => '127.0.0.1',
            'qty' => 1,
            'user_id' => null,
        ]);

        $response = $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1'])->delete("/cart-items/{$cartItem->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('cart_items', [
            'id' => $cartItem->id,
        ]);
    }

    public function test_auth_user_cart_merges_on_login()
    {
        $city = City::factory()->create();
        $user = User::factory()->create([
            'password' => bcrypt('password'),
            'city_id' => $city->id,
        ]);

        // Guest adds an item
        CartItem::factory()->create([
            'service_item_id' => $this->serviceItem->id,
            'guest_ip' => '127.0.0.1',
            'qty' => 2,
            'user_id' => null,
        ]);

        // Login as user from the same IP
        $response = $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1'])->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect();

        // The job should have moved it
        $this->assertDatabaseHas('cart_items', [
            'service_item_id' => $this->serviceItem->id,
            'qty' => 2,
            'user_id' => $user->id,
            'guest_ip' => null,
        ]);
    }
}
