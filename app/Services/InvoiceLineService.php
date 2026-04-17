<?php

namespace App\Services;

use App\Models\Invoice;

class InvoiceLineService
{
    public function storeLines(Invoice $invoice, array $lines): void
    {
        $invoice->lines()->createMany($lines);
    }
}
