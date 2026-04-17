<?php

namespace App\Models;

use App\Traits\Slugable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    use HasFactory, Slugable;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    // # Relations

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'package_service');
    }

    public function packageServices(): HasMany
    {
        return $this->hasMany(PackageService::class);
    }
}
