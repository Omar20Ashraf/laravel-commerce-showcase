<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Order;
use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
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
            'current_status_id' => Status::factory(),
            'scheduled_at' => fake()->dateTime(),
            'closed_at' => fake()->dateTime(),
            'is_done' => fake()->boolean(),
            //
        ];
    }
}
