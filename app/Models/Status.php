<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'type',
    ];

    ## Relations

    public function statusRelatedObjects(): HasMany
    {
        return $this->hasMany(StatusRelatedObject::class);
    }

    ## Getters & Setters

    ## Query Scope Methods

    public function scopeTransactionStatus($query)
    {
        return $query->where('type', 'Transaction');
    }

    public function scopeOrderItemStatus($query)
    {
        return $query->where('type', 'OrderItem');
    }

    public function scopeInvoiceStatus($query)
    {
        return $query->where('type', 'Invoice');
    }

    public function scopeBookingStatus($query)
    {
        return $query->where('type', 'Booking');
    }

    public function scopePending($query)
    {
        return $query->where('name', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('name', 'paid');
    }

    public function scopeSuccess($query)
    {
        return $query->where('name', 'success');
    }

    public function scopeFailed($query)
    {
        return $query->where('name', 'failed');
    }
}
