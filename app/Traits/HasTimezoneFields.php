<?php

namespace App\Traits;

use App\Services\TimeZoneService;

trait HasTimezoneFields
{
    public function getCreatedAtAttribute($value)
    {
        return $this->attributes['created_at'] != null ? TimeZoneService::createFromServer($this->attributes['created_at']) : $value;
    }

    public function getUpdatedAtAttribute($value)
    {
        return $this->attributes['updated_at'] != null ? TimeZoneService::createFromServer($this->attributes['updated_at']) : $value;
    }
}
