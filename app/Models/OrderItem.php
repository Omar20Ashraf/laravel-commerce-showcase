<?php

namespace App\Models;

use App\Traits\HasStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory, HasStatus;

    protected $fillable = [
        'order_id',
        'service_item_id',
        'current_status_id',

        'price',
        'fee',
        'qty',
        'total_amount',

        'provider_due_date_at',
        'scheduled_at',
    ];

    protected function casts(): array
    {
        return [
            'provider_due_date_at' => 'datetime',
            'scheduled_at' => 'datetime',
        ];
    }

    ## Relations

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function serviceItem(): BelongsTo
    {
        return $this->belongsTo(ServiceItem::class);
    }

    ## Getters & Setters

    public function getPriceAttribute(): float|int
    {
        return $this->attributes['price'] / 100;
    }

    public function setPriceAttribute($value): void
    {
        $this->attributes['price'] = \round($value, 2) * 100;
    }

    public function getFeeAttribute(): float|int
    {
        return $this->attributes['fee'] / 100;
    }

    public function setFeeAttribute($value): void
    {
        $this->attributes['fee'] = \round($value, 2) * 100;
    }

    public function getTotalAmountAttribute(): float|int
    {
        return $this->attributes['total_amount'] / 100;
    }

    public function setTotalAmountAttribute($value): void
    {
        $this->attributes['total_amount'] = \round($value, 2) * 100;
    }
}
