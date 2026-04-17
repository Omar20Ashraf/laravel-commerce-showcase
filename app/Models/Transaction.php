<?php

namespace App\Models;

use App\Traits\HasStatus;
use App\Traits\HasTimezoneFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory, HasStatus, HasTimezoneFields;

    protected $fillable = [
        'invoice_id',
        'gateway_id',
        'current_status_id',

        'due_date_at',
        'payload',
        'trans_reference_number',
    ];

    protected function casts(): array
    {
        return [
            'due_date_at' => 'datetime',
            'payload' => 'array',
        ];
    }

    ## Relations

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function gateway(): BelongsTo
    {
        return $this->belongsTo(Gateway::class);
    }

    ## Getters & Setters

    public function getAmountAttribute(): float|int
    {
        return $this->invoice->total_amount;
    }

    ## Query Scope Methods

    public function scopeByGatewayReference($query, string $reference)
    {
        return $query->where('trans_reference_number', $reference);
    }

    public function scopeAvailableToPayment($query)
    {
        $statusIds = Status::transactionStatus()
        ->where(function($q){
            $q->where('name', 'success')->orWhere('name', 'pending');
        })
        ->pluck('id')
        ->toArray();

        return $query->whereNotIn('current_status_id', $statusIds);
    }
}
