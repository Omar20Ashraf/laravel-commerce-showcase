<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ServiceItem;
use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'service_item_id' => ServiceItem::factory(),
            'current_status_id' => Status::factory(),
            'price' => fake()->randomFloat(2, 10, 1000),
            'fee' => fake()->word(),
            'total_amount' => fake()->randomFloat(2, 10, 1000),
            'provider_due_date_at' => fake()->dateTime(),
        ];
    }
}
