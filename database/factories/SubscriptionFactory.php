<?php

namespace Database\Factories;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subscription>
 */
class SubscriptionFactory extends Factory
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
            'is_free_trail' => fake()->boolean(),
            'is_monthly' => fake()->boolean(),
            'is_yearly' => fake()->boolean(),
        ];
    }

    public function freeTrail(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_free_trail' => fake()->boolean(100),
            'is_monthly' => fake()->boolean(0),
            'is_yearly' => fake()->boolean(0),
        ]);
    }

    public function monthly(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_free_trail' => fake()->boolean(0),
            'is_monthly' => fake()->boolean(100),
            'is_yearly' => fake()->boolean(0),
        ]);
    }

    public function yearly(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_free_trail' => fake()->boolean(0),
            'is_monthly' => fake()->boolean(0),
            'is_yearly' => fake()->boolean(100),
        ]);
    }
}
