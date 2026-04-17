<?php

namespace Database\Factories;

use App\Models\Package;
use App\Models\PackageService;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PackageService>
 */
class PackageServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'package_id' => Package::factory(),
            'service_id' => Service::factory(),
        ];
    }
}
