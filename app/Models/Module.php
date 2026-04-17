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
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // # Relations

    public function gateways(): BelongsToMany
    {
        return $this->belongsToMany(Gateway::class, 'gateway_module');
    }

    public function gatewayModules(): HasMany
    {
        return $this->hasMany(GatewayModule::class);
    }
}
