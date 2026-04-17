<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\SubscriptionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateFreeTrialSubscriptionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public readonly User $user)
    {
        $this->afterCommit();
    }

    public function handle(SubscriptionService $subscriptionService): void
    {
        $subscriptionService->createFreeTrial(user: $this->user);
    }
}
