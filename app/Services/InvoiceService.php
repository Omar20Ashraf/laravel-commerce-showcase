<?php

namespace App\Services;

use App\Actions\StoreStatusRelatedObjectAction;
use App\Contracts\InvoiceModelContract;
use App\Models\Invoice;
use App\Models\Status;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    public function store(InvoiceModelContract $invoiceable, int $userId, bool $isFree = false): void
    {
        $pendingStatus = Status::invoiceStatus()->pending()->first();

        $invoice = $invoiceable->invoice()->create([
            'reference_number' => '',
            'user_id' => $userId,
            'current_status_id' => $pendingStatus->id,
            'due_date_at' => $isFree ? null : now()->addDays(config('app.invoice_due_date_in_minutes'))
        ]);

        app(StoreStatusRelatedObjectAction::class)->execute(
            statusable: $invoice,
            status: $pendingStatus,
        );

        app(InvoiceLineService::class)->storeLines(
            invoice: $invoice,
            lines: $invoiceable->resolveInvoiceService()->invoiceLines($invoiceable)
        );

        $linesTotals = $invoice->lines()->sum('amount') / 100; // the query will get the sum without using the value from getter

        $invoice->update([
            'total_amount' => $linesTotals,
        ]);

        if ($isFree) {
            $this->markAsPaid(invoice: $invoice);
        }
    }

    public function markAsPaid(Invoice $invoice): void
    {
        DB::transaction(function () use ($invoice) {

            $paidStatus = Status::invoiceStatus()->paid()->first();

            $invoice->update([
                'current_status_id' => $paidStatus->id,
                'closed_at' => now(),
                'paid_at' => now(),
            ]);

            app(StoreStatusRelatedObjectAction::class)->execute(
                statusable: $invoice,
                status: $paidStatus,
                userId: $invoice->user_id,
                notes: 'Invoice marked as paid automatically.'
            );

            $invoice->invoiceable->resolveInvoiceService()->markAsPaid($invoice->invoiceable);
        });
    }
}
