<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\Website\CartService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TransferGuestCartJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly User $user,
        public readonly string $guestIp
    ) {
        $this->afterCommit();
    }

    public function handle(CartService $cartService): void
    {
        $cartService->transferUserCartIfExists(user: $this->user, guestIp: $this->guestIp);
    }
}
