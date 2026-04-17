<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\ServiceItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ServiceItem>
 */
class ServiceItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'service_id' => Service::factory(),
            'name' => fake()->name(),
            'slug' => fake()->slug(),
            'desc' => fake()->word(),
            'price' => fake()->randomFloat(2, 10, 1000),
            'fee_price' => fake()->randomFloat(2, 10, 1000),
        ];
    }
}
