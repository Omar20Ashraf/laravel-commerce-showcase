<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function extendSubscriptionPeriod(User $user, int $daysNumber): void
    {
        $user->update([
            'subscriptions_ends_at' => now()->addDays($daysNumber),
        ]);
    }
}
