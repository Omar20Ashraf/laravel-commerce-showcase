<?php

namespace Database\Factories;

use App\Models\Gateway;
use App\Models\Invoice;
use App\Models\Status;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invoice_id' => Invoice::factory(),
            'gateway_id' => Gateway::factory(),
            'current_status_id' => Status::factory(),
            'amount' => fake()->randomFloat(2, 10, 1000),
            'payload' => [],
            'trans_reference_number' => fake()->word(),
        ];
    }
}
