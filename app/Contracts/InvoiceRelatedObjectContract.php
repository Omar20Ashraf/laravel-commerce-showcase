<?php

namespace App\Contracts;

interface InvoiceRelatedObjectContract
{
    public function markAsPaid(InvoiceModelContract $invoiceModelContract): void;

    public function invoiceLines(InvoiceModelContract $subscription): array;
}
