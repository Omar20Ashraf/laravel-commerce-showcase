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
        'is_available',
    ];

    protected function casts(): array
    {
        return [
            'is_available' => 'boolean',
        ];
    }

    // # Relations

    public function gateways(): BelongsToMany
    {
        return $this->belongsToMany(Gateway::class, 'city_gateway');
    }

    public function cityGateways(): HasMany
    {
        return $this->hasMany(CityGateway::class);
    }
}
