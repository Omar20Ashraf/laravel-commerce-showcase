<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_item_id',
        'guest_ip',
        'qty',
    ];

    ## Relations

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function serviceItem(): BelongsTo
    {
        return $this->belongsTo(ServiceItem::class);
    }

    ## Query Scope Methods

    public function scopeForUser($query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForGuest($query, string $ip): Builder
    {
        return $query->whereNull('user_id')->where('guest_ip', $ip);
    }
}
