<?php

namespace App\Models;

use App\Traits\HasTimezoneFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
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

    ## Relations

    public function gateways(): BelongsToMany
    {
        return $this->belongsToMany(Gateway::class, 'gateway_module')->withTimestamps();
    }

    public function gatewayModules(): HasMany
    {
        return $this->hasMany(GatewayModule::class);
    }

    ## Getters & Setters

    ## Query Scope Methods

    public function scopeAvailable($query, bool $available = true)
    {
        return $query->where('is_available', $available);
    }
}
