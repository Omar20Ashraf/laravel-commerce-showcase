<?php

namespace App\Models;

use App\Traits\HasStatus;
use App\Traits\HasTimezoneFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory, HasStatus, HasTimezoneFields;

    protected $fillable = [
        'order_id',
        'current_status_id',

        'scheduled_at',
        'closed_at',

        'is_done',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'closed_at' => 'datetime',
            'is_done' => 'boolean',
        ];
    }

    ## Relations

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
