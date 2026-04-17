<?php

namespace App\Models;

use App\Traits\HasTimezoneFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    use HasFactory, HasTimezoneFields;

    protected $fillable = [
        'name',
        'code',
        'is_available',
    ];

    protected function casts(): array
    {
        return [
            'is_available' => 'boolean',
        ];
    }

    ## Relations

    public function gateways(): BelongsToMany
    {
        return $this->belongsToMany(Gateway::class, 'city_gateway')->withTimestamps();
    }

    public function cityGateways(): HasMany
    {
        return $this->hasMany(CityGateway::class);
    }

    ## Getters & Setters

    ## Query Scope Methods

    public function scopeAvailable($query, bool $available = true)
    {
        return $query->where('is_available', $available);
    }
}
