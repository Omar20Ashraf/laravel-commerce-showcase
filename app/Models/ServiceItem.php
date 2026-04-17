<?php

namespace App\Models;

use App\Traits\HasTimezoneFields;
use App\Traits\Slugable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class ServiceItem extends Model
{
    use HasFactory, HasTimezoneFields, Slugable;

    protected $fillable = [
        'service_id',

        'name',
        'slug',
        'desc',

        'price',
        'fee_price',
    ];

    ## Relations

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function provider(): HasOneThrough
    {
        return $this->hasOneThrough(
            Provider::class,
            Service::class,
            'id',
            'id',
            'service_id',
            'provider_id',
        );
    }

    ## Getters & Setters

    public function getIsAvailableAttribute(): bool
    {
        return $this->provider->is_active;
    }

    public function getPriceAttribute(): float|int
    {
        return $this->attributes['price'] / 100;
    }

    public function setPriceAttribute($value): void
    {
        $this->attributes['price'] = \round($value, 2) * 100;
    }

    public function getFeePriceAttribute(): float|int
    {
        return $this->attributes['fee_price'] / 100;
    }

    public function setFeePriceAttribute($value): void
    {
        $this->attributes['fee_price'] = \round($value, 2) * 100;
    }
}
