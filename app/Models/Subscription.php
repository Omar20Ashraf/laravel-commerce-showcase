<?php

namespace App\Models;

use App\Contracts\InvoiceModelContract;
use App\Services\SubscriptionService;
use App\Traits\HasTimezoneFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Subscription extends Model implements InvoiceModelContract
{
    use HasFactory, HasTimezoneFields;

    protected $fillable = [
        'user_id',

        'is_free_trail',
        'is_monthly',
        'is_yearly',
    ];

    protected function casts(): array
    {
        return [
            'is_free_trail' => 'boolean',
            'is_monthly' => 'boolean',
            'is_yearly' => 'boolean',
        ];
    }

    ## Relations

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function invoice(): MorphOne
    {
        return $this->morphOne(Invoice::class, 'invoiceable');
    }

    ## Getters & Setters

    public function getModuleIdAttribute(): int
    {
        return Module::subscriptionModule()->value('id');
    }

    public function getDaysNumberAttribute(): int
    {
        if ($this->is_free_trail) {
            return config('subscription.free_trial_days');
        }

        if ($this->is_monthly) {
            return config('subscription.monthly_trial_days');
        }

        return config('subscription.yearly_trial_days');
    }

    ## Query Scope Methods

    ## Other Methods

    public function resolveInvoiceService(): object
    {
        return new SubscriptionService();
    }
}
