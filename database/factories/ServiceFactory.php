<?php

namespace Database\Factories;

use App\Models\Provider;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'provider_id' => Provider::factory(),
            'name' => fake()->name(),
            'slug' => fake()->slug(),
            'desc' => fake()->word(),
        ];
    }
}
