<?php

namespace App\Services;

use App\Contracts\InvoiceModelContract;
use App\Contracts\InvoiceRelatedObjectContract;

class OrderService implements InvoiceRelatedObjectContract
{
    public function markAsPaid(InvoiceModelContract $order): void
    {
        $order->update([
            'closed_at' => now(),
            'paid_at' => now(),
        ]);
    }

    public function invoiceLines(InvoiceModelContract $subscription): array
    {
        return [];
    }
}
