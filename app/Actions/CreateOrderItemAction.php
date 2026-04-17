<?php

namespace App\Actions;

use App\Models\Order;
use App\Models\ServiceItem;
use App\Models\Status;

class CreateOrderItemAction
{
    public function execute(Order $order, array $snapshotData)
    {
        $pendingStatus = Status::orderItemStatus()->pending()->first();
        $isSubscribed = $order->user->is_subscribed;

        foreach ($snapshotData as $data) {
            $serviceItem = ServiceItem::find($data['service_item_id']);

            $price = $serviceItem->price;
            $fee = $isSubscribed ? 0 : $serviceItem->fee_price;
            $totalAmount = ($price + $fee) * $data['qty'];

            $orderItem = $order->orderItems()->create([
                'service_item_id' => $serviceItem->id,
                'current_status_id' => $pendingStatus->id,
                'price' => $price,
                'fee' => $fee,
                'qty' => $data['qty'],
                'total_amount' => $totalAmount,
                'provider_due_date_at' => now()->addMinutes(config('app.provider_due_date_in_minutes')),
                'scheduled_at' => $data['scheduled_at'],
            ]);

            app(StoreStatusRelatedObjectAction::class)->execute(
                statusable: $orderItem,
                status: $pendingStatus,
                userId: $order->user_id,
                notes: 'Order item created from checkout.'
            );
        }
    }
}
