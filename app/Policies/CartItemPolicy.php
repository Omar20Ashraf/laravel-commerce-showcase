<?php

namespace App\Policies;

use App\Models\CartItem;
use App\Models\User;
use App\Services\Website\CartService;
use Illuminate\Auth\Access\HandlesAuthorization;

class CartItemPolicy
{
    use HandlesAuthorization;

    public function modify(?User $user, CartItem $cartItem): bool
    {
        $scope = app(CartService::class)->resolveCallerScope(request());

        if ($scope['user_id']) {
            return $cartItem->user_id === $scope['user_id'];
        }

        return $cartItem->guest_ip === $scope['guest_ip'] && $cartItem->user_id === null;
    }
}
