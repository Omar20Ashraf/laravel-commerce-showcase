<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\Status;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'current_status_id' => Status::factory(),
            'invoiceable_type' => fake()->word(),
            'invoiceable_id' => fake()->numberBetween(1, 20),
            'reference_number' => fake()->word(),
            'total_amount' => fake()->randomFloat(2, 10, 1000),
            'due_date_at' => fake()->dateTime(),
            'closed_at' => fake()->dateTime(),
            'paid_at' => fake()->dateTime(),
            'payment_token' => fake()->unique()->uuid(),
        ];
    }

    public function order(): static
    {
        return $this->state(fn (array $attributes) => [
            'invoiceable_type' => Order::class,
            'invoiceable_id' => Order::factory(),
        ]);
    }

    public function subscription(): static
    {
        return $this->state(fn (array $attributes) => [
            'invoiceable_type' => Subscription::class,
            'invoiceable_id' => Subscription::factory(),
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'current_status_id' => Status::where('type', 'Invoice')->where('name', 'pending')->first()?->id ?? Status::factory(),
            'closed_at' => null,
            'paid_at' => null,
        ]);
    }
}
