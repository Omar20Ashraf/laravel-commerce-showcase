<?php

namespace App\Services;

use App\Actions\StoreStatusRelatedObjectAction;
use App\Models\Order;
use App\Models\Status;

class BookingService
{
    public function store(Order $order): void
    {
        $pendingStatus = Status::BookingStatus()->pending()->first();

        foreach ($order->orderItems as $orderItem):
            $booking = $order->bookings()->create([
                'current_status_id' => $pendingStatus->id,

                'scheduled_at' => $orderItem->scheduled_at,
            ]);

            app(StoreStatusRelatedObjectAction::class)->execute(
                statusable: $booking,
                status: $pendingStatus,
            );
        endforeach;
    }
}
