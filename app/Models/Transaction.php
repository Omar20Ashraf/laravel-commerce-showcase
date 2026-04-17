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

        'amount',
        'payload',
        'trans_reference_number',
    ];

    protected function casts(): array
    {
        return [
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
        return $this->attributes['amount'] / 100;
    }

    public function setAmountAttribute($value): void
    {
        $this->attributes['amount'] = \round($value, 2) * 100;
    }
}
