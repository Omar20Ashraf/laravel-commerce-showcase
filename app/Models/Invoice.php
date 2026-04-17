<?php

namespace App\Models;

use App\Traits\HasSerialReferenceNumber;
use App\Traits\HasStatus;
use App\Traits\HasTimezoneFields;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Invoice extends Model
{
    use HasFactory, HasSerialReferenceNumber, HasStatus, HasTimezoneFields;

    protected $fillable = [
        'user_id',
        'current_status_id',
        'invoiceable_type',
        'invoiceable_id',

        'reference_number',
        'total_amount',

        'due_date_at',
        'closed_at',
        'paid_at',

        'payment_token',
    ];

    protected function casts(): array
    {
        return [
            'due_date_at' => 'datetime',
            'closed_at' => 'datetime',
            'paid_at' => 'datetime',
        ];
    }

    ## Relations

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function invoiceable(): MorphTo
    {
        return $this->morphTo();
    }

    public function lines(): HasMany
    {
        return $this->hasMany(InvoiceLine::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    ## Getters & Setters

    public function setReferenceNumberAttribute($value)
    {
        $this->attributes['reference_number'] = $this->nextReferenceNumber(prefix: 'INV', serialStart: '0000001');
    }

    public function getTotalAmountAttribute(): float|int
    {
        return $this->attributes['total_amount'] / 100;
    }

    public function setTotalAmountAttribute($value): void
    {
        $this->attributes['total_amount'] = \round($value, 2) * 100;
    }

    public function getIsPayableAttribute(): bool
    {
        return $this->closed_at === null && ! $this->transactions()->availableToPayment()->exists();
    }

    public function getModuleIdAttribute(): string
    {
        return $this->invoiceable->moduleId;
    }

    public function getCityIdAttribute(): int
    {
        return $this->user->city_id;
    }

    ## Query Scope Methods

    public function scopeByPaymentToken($query, string $token)
    {
        return $query->where('payment_token', $token);
    }

    ## Other Methods

    public function availableGateways(): Collection
    {
        return Gateway::active()
            ->availableForCityAndModule(cityId: $this->cityId, moduleId: $this->moduleId)
            ->get();
    }
}
