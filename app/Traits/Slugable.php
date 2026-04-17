<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait Slugable
{
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function setSlugAttribute($value)
    {
        $value = substr($value, 0, 20);

        $this->attributes['slug'] = Str::slug($value, '-') . '-' . now()->timestamp . '-' . random_int(100000, 999999);
    }
}
