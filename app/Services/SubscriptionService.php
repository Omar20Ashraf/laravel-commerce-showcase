<?php

namespace App\Services;

use App\Contracts\InvoiceModelContract;
use App\Contracts\InvoiceRelatedObjectContract;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SubscriptionService implements InvoiceRelatedObjectContract
{
    public function createFreeTrial(User $user): void
    {
        DB::transaction(function () use ($user) {

            $subscription = $user->subscriptions()->create([
                'is_free_trail' => true,
            ]);

            app(InvoiceService::class)->store(
                invoiceable: $subscription,
                userId: $user->id,
                isFree: true
            );
        });
    }

    public function markAsPaid(InvoiceModelContract $subscription): void
    {
        app(UserService::class)->extendSubscriptionPeriod(user: $subscription->user, daysNumber: $subscription->daysNumber);
    }

    public function invoiceLines(InvoiceModelContract $subscription): array
    {
        if ($subscription->is_free_trail) {
            $displayName = 'Free Trial Subscription';
        } elseif ($subscription->is_monthly) {
            $displayName = 'Monthly Subscription';
        } else {
            $displayName = 'Yearly Subscription';
        }

        return [
            [
                'display_name' => $displayName,
                'amount' => 0,
            ]
        ];
    }
}
