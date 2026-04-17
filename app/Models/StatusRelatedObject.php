<?php

namespace App\Models;

use App\Traits\HasTimezoneFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StatusRelatedObject extends Model
{
    use HasFactory, HasTimezoneFields;

    protected $fillable = [
        'status_id',
        'user_id',

        'statusable_type',
        'statusable_id',

        'notes',
    ];

    // # Relations

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function statusable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
