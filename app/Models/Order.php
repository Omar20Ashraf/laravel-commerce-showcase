<?php

namespace App\Models;

use App\Contracts\InvoiceModelContract;
use App\Services\OrderService;
use App\Traits\HasSerialReferenceNumber;
use App\Traits\HasTimezoneFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Order extends Model implements InvoiceModelContract
{
    use HasFactory, HasSerialReferenceNumber, HasTimezoneFields;

    protected $fillable = [
        'user_id',
        'reference_number',

        'total_amount',

        'closed_at',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'closed_at' => 'datetime',
            'paid_at' => 'datetime',
        ];
    }

    ## Relations

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function invoice(): MorphOne
    {
        return $this->morphOne(Invoice::class, 'invoiceable');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    ## Getters & Setters

    public function getTotalAmountAttribute(): float|int
    {
        return $this->attributes['total_amount'] / 100;
    }

    public function setTotalAmountAttribute($value): void
    {
        $this->attributes['total_amount'] = \round($value, 2) * 100;
    }

    public function getModuleIdAttribute(): int
    {
        return Module::orderModule()->value('id');
    }

    public function setReferenceNumberAttribute($value): void
    {
        $this->attributes['reference_number'] = $this->nextReferenceNumber(prefix: 'ORD', serialStart: '0000001');
    }

    public function resolveInvoiceService(): object
    {
        return new OrderService();
    }
}
