<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CityGateway extends Model
{
    use HasFactory;

    protected $table = 'city_gateway';

    protected $fillable = [
        'city_id',
        'gateway_id',
    ];

    // # Relations

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function gateway(): BelongsTo
    {
        return $this->belongsTo(Gateway::class);
    }
}
