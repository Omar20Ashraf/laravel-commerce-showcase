<?php

namespace Tests\Feature\Custom\Website;

use App\Jobs\ProcessCheckoutJob;
use App\Models\CartItem;
use App\Models\City;
use App\Models\Provider;
use App\Models\Service;
use App\Models\ServiceItem;
use App\Models\Status;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private ServiceItem $serviceItem1;
    private ServiceItem $serviceItem2;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure pending status exists
        Status::factory()->create(['name' => 'pending', 'type' => 'OrderItem']);

        $city = City::factory()->create();
        $this->user = User::factory()->create([
            'city_id' => $city->id,
            'subscriptions_ends_at' => null // Not subscribed
        ]);

        $provider = Provider::factory()->create(['is_active' => true]);
        $service = Service::factory()->create(['provider_id' => $provider->id]);

        $this->serviceItem1 = ServiceItem::factory()->create([
            'service_id' => $service->id,
            'price' => 100, // stored as 10000 in DB
            'fee_price' => 10, // stored as 1000 in DB
        ]);

        $this->serviceItem2 = ServiceItem::factory()->create([
            'service_id' => $service->id,
            'price' => 200,
            'fee_price' => 15,
        ]);
    }

    public function test_authenticated_user_can_checkout_with_valid_items()
    {
        Queue::fake();

        $cartItem = CartItem::factory()->create([
            'user_id' => $this->user->id,
            'service_item_id' => $this->serviceItem1->id,
            'qty' => 2,
        ]);

        $scheduledAt = now()->addDays(2)->format('Y-m-d H:i:s');

        $response = $this->actingAs($this->user)->post('/checkout', [
            'items' => [
                [
                    'cart_item_id' => $cartItem->id,
                    'scheduled_at' => $scheduledAt,
                ]
            ]
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('cart_items', [
            'id' => $cartItem->id,
        ]);


        Queue::assertPushed(ProcessCheckoutJob::class, function ($job) {
            return $job->userId === $this->user->id;
        });
    }

    public function test_checkout_fails_if_item_is_unavailable_and_removes_it()
    {
        // Make provider inactive to make service item unavailable
        $this->serviceItem1->service->provider->update(['is_active' => false]);

        $cartItem = CartItem::factory()->create([
            'user_id' => $this->user->id,
            'service_item_id' => $this->serviceItem1->id,
            'qty' => 1,
        ]);

        $response = $this->actingAs($this->user)->post('/checkout', [
            'items' => [
                [
                    'cart_item_id' => $cartItem->id,
                    'scheduled_at' => now()->addDays(1)->format('Y-m-d H:i:s'),
                ]
            ]
        ]);

        $response->assertStatus(409); // Conflict

        // The unavailable item should have been removed from the cart
        $this->assertDatabaseMissing('cart_items', [
            'id' => $cartItem->id,
        ]);
    }

    public function test_checkout_requires_scheduled_at_to_be_in_future()
    {
        $cartItem = CartItem::factory()->create([
            'user_id' => $this->user->id,
            'service_item_id' => $this->serviceItem1->id,
        ]);

        $response = $this->actingAs($this->user)->post('/checkout', [
            'items' => [
                [
                    'cart_item_id' => $cartItem->id,
                    'scheduled_at' => now()->subDay()->format('Y-m-d H:i:s'), // Past date
                ]
            ]
        ]);

        $response->assertSessionHasErrors('items.0.scheduled_at');
    }

    public function test_action_calculates_fees_correctly_for_unsubscribed_user()
    {
        $scheduledAt = now()->addDays(2)->format('Y-m-d H:i:s');
        $snapshotData = [
            [
                'service_item_id' => $this->serviceItem1->id,
                'qty' => 2,
                'scheduled_at' => $scheduledAt,
            ]
        ];

        app(OrderService::class)->store($this->user->id, $snapshotData);

        // Price = 100, Fee = 10, Qty = 2 -> Total = (100+10)*2 = 220
        $this->assertDatabaseHas('order_items', [
            'service_item_id' => $this->serviceItem1->id,
            'qty' => 2,
            'price' => 10000,
            'fee' => 1000,
            'total_amount' => 22000,
        ]);
    }

    public function test_action_calculates_fees_correctly_for_subscribed_user()
    {
        // Subscribe the user
        $this->user->update(['subscriptions_ends_at' => now()->addMonth()]);

        $scheduledAt = now()->addDays(2)->format('Y-m-d H:i:s');
        $snapshotData = [
            [
                'service_item_id' => $this->serviceItem1->id,
                'qty' => 2,
                'scheduled_at' => $scheduledAt,
            ]
        ];

        app(OrderService::class)->store($this->user->id, $snapshotData);

        // Price = 100, Fee = 0 (subscribed), Qty = 2 -> Total = 200
        $this->assertDatabaseHas('order_items', [
            'service_item_id' => $this->serviceItem1->id,
            'qty' => 2,
            'price' => 10000,
            'fee' => 0,
            'total_amount' => 20000,
        ]);
    }
}
