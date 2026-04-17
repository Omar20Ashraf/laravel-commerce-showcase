<?php

namespace App\Services;

use App\Actions\CreateOrderItemAction;
use App\Contracts\InvoiceModelContract;
use App\Contracts\InvoiceRelatedObjectContract;
use App\Models\Order;

class OrderService implements InvoiceRelatedObjectContract
{
    public function store(int $userId, array $snapshotData): void
    {
        $order = Order::create([
            'user_id' => $userId,
            'reference_number' => '',
            'total_amount' => 0,
        ]);

        app(CreateOrderItemAction::class)->execute(order: $order, snapshotData: $snapshotData);

        $itemsTotal = $order->orderItems()->sum('total_amount') / 100;
        $order->update([
            'total_amount' => $itemsTotal,
        ]);
    }

    public function markAsPaid(InvoiceModelContract $order): void
    {
        $order->update([
            'closed_at' => now(),
            'paid_at' => now(),
        ]);

        app(BookingService::class)->store(order: $order);
    }

    public function invoiceLines(InvoiceModelContract $order): array
    {
        $lines = [];

        $items = $order->orderItems()->with('serviceItem')->get();

        foreach ($items as $item):
            $lines[] = [
                'display_name' => $item->serviceItem->name,
                'amount' => $item->total_amount,
            ];
        endforeach;

        return $lines;
    }
}
