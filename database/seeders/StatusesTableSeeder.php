<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'display_name' => 'Pending',
                'name' => 'pending',
                'type' => 'OrderItem',
            ],
            [
                'display_name' => 'Approved',
                'name' => 'approved',
                'type' => 'OrderItem',
            ],
            [
                'display_name' => 'Rejected',
                'name' => 'rejected',
                'type' => 'OrderItem',
            ],
            [
                'display_name' => 'Due Date Canceled',
                'name' => 'due_date_canceled',
                'type' => 'OrderItem',
            ],

            [
                'display_name' => 'Pending',
                'name' => 'pending',
                'type' => 'Invoice',
            ],
            [
                'display_name' => 'Paid',
                'name' => 'paid',
                'type' => 'Invoice',
            ],
            [
                'display_name' => 'Failed',
                'name' => 'failed',
                'type' => 'Invoice',
            ],
            [
                'display_name' => 'Due Date Canceled',
                'name' => 'due_date_canceled',
                'type' => 'Invoice',
            ],

            [
                'display_name' => 'Pending',
                'name' => 'pending',
                'type' => 'Booking',
            ],
            [
                'display_name' => 'Done',
                'name' => 'done',
                'type' => 'Booking',
            ],
            [
                'display_name' => 'Customer Canceled',
                'name' => 'customer_canceled',
                'type' => 'Booking',
            ],
            [
                'display_name' => 'Provider Canceled',
                'name' => 'provider_canceled',
                'type' => 'Booking',
            ],
            [
                'display_name' => 'Closed By Administration',
                'name' => 'closed_by_administration',
                'type' => 'Booking',
            ],

            [
                'display_name' => 'Pending',
                'name' => 'pending',
                'type' => 'Transaction',
            ],
            [
                'display_name' => 'Success',
                'name' => 'success',
                'type' => 'Transaction',
            ],
            [
                'display_name' => 'Failed',
                'name' => 'failed',
                'type' => 'Transaction',
            ],
            [
                'display_name' => 'Due Date Closed',
                'name' => 'due_date_closed',
                'type' => 'Transaction',
            ],
        ];

        for ($i = 0; $i < \count($statuses); $i++) {
            $status = $statuses[$i];

            $statusExists = Status::where('type', $status['type'])
                ->where('name', $status['name'])
                ->exists();

            if (!$statusExists) {
                Status::create([
                    'name' => $status['name'],
                    'display_name' => $status['display_name'],
                    'type' => $status['type'],
                ]);
            }
        }
    }
}
