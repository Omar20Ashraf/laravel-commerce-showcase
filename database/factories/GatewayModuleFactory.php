<?php

namespace Database\Factories;

use App\Models\Gateway;
use App\Models\GatewayModule;
use App\Models\Module;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GatewayModule>
 */
class GatewayModuleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'gateway_id' => Gateway::factory(),
            'module_id' => Module::factory(),
        ];
    }
}
