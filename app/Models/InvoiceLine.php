<?php

namespace App\Models;

use App\Traits\HasTimezoneFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceLine extends Model
{
    use HasFactory, HasTimezoneFields;

    protected $fillable = [
        'invoice_id',
        'display_name',
        'amount',
    ];

    // # Relations

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    // # Getters & Setters

    public function getAmountAttribute(): float|int
    {
        return $this->attributes['amount'] / 100;
    }

    public function setAmountAttribute($value): void
    {
        $this->attributes['amount'] = \round($value, 2) * 100;
    }
}
