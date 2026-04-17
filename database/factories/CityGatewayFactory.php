<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\CityGateway;
use App\Models\Gateway;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CityGateway>
 */
class CityGatewayFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'city_id' => City::factory(),
            'gateway_id' => Gateway::factory(),
        ];
    }
}
