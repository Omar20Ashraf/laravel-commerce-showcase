<?php

namespace Database\Factories;

use App\Models\CartItem;
use App\Models\ServiceItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CartItem>
 */
class CartItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => null,
            'service_item_id' => ServiceItem::factory(),
            'guest_ip' => fake()->ipv4(),
            'qty' => fake()->numberBetween(1, 5),
        ];
    }
}
