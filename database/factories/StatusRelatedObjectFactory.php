<?php

namespace Database\Factories;

use App\Models\Status;
use App\Models\StatusRelatedObject;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StatusRelatedObject>
 */
class StatusRelatedObjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status_id' => Status::factory(),
            'user_id' => User::factory(),
            'statusable_type' => fake()->word(),
            'statusable_id' => 1,
            'notes' => fake()->word(),
        ];
    }
}
