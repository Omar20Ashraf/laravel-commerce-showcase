<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GatewayModule extends Model
{
    use HasFactory;

    protected $table = 'gateway_module';

    protected $fillable = [
        'gateway_id',
        'module_id',
    ];

    // # Relations

    public function gateway(): BelongsTo
    {
        return $this->belongsTo(Gateway::class);
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }
}
