<?php

namespace App\Traits;

use App\Models\Status;
use App\Models\StatusRelatedObject;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasStatus
{
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'current_status_id');
    }

    public function statusRelatedObjects(): MorphMany
    {
        return $this->morphMany(StatusRelatedObject::class, 'statusable');
    }
}
