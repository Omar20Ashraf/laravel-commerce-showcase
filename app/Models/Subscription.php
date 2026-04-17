<?php

namespace App\Models;

use App\Traits\HasTimezoneFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Subscription extends Model
{
    use HasFactory, HasTimezoneFields;

    protected $fillable = [
        'user_id',
        'is_free_trail',
        'ends_at',
    ];

    protected function casts(): array
    {
        return [
            'is_free_trail' => 'boolean',
            'ends_at' => 'datetime',
        ];
    }

    ## Relations

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function invoices(): MorphOne
    {
        return $this->morphOne(Invoice::class, 'invoiceable');
    }
}
