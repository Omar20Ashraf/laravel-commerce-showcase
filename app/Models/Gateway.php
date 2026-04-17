<?php

namespace App\Models;

use App\Traits\HasTimezoneFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gateway extends Model
{
    use HasFactory, HasTimezoneFields;

    protected $fillable = [
        'name',
        'type',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    ## Relations

    public function cities(): BelongsToMany
    {
        return $this->belongsToMany(City::class, 'city_gateway')->withTimestamps();
    }

    public function cityGateways(): HasMany
    {
        return $this->hasMany(CityGateway::class);
    }

    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'gateway_module')->withTimestamps();
    }

    public function gatewayModules(): HasMany
    {
        return $this->hasMany(GatewayModule::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    ## Query Scope Methods

    public function scopeActive($query, bool $active = true)
    {
        return $query->where('is_active', $active);
    }

    public function scopeByCityId($query, int $cityId)
    {
        return $query->whereHas('cities', fn ($q) => $q->where('city_id', $cityId));
    }

    public function scopeByModuleId($query, int $moduleId)
    {
        return $query->whereHas('modules', fn ($q) => $q->where('module_id', $moduleId));
    }

    public function scopeAvailableForCityAndModule($query, int $cityId, int $moduleId)
    {
        return $query->byCityId(cityId: $cityId)->byModuleId(moduleId: $moduleId)->active();
    }
}
