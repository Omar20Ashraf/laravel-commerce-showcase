<?php

namespace App\Services\Website;

use App\Models\CartItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartService
{
    public function index(Request $request)
    {
        $scope = $this->resolveCallerScope(request: $request);

        $query = CartItem::with('serviceItem.service');

        if ($scope['user_id']) {
            $query->forUser($scope['user_id']);
        } else {
            $query->forGuest($scope['guest_ip']);
        }

        return $query->get();
    }

    public function store(Request $request): void
    {
        $scope = $this->resolveCallerScope(request: $request);

        $cartItem = CartItem::firstOrNew([
            'user_id' => $scope['user_id'],
            'guest_ip' => $scope['guest_ip'],
            'service_item_id' => $request->service_item,
        ]);

        $cartItem->qty = $cartItem->exists ? $cartItem->qty + $request->qty : $request->qty;
        $cartItem->save();
    }

    public function update(CartItem $cartItem, int $qty): void
    {
        if (! $cartItem->serviceItem->is_available) {
            $cartItem->delete();
            abort(409, __('carts.unavailable'));
        }

        $cartItem->update(['qty' => $qty]);
    }

    public function transferUserCartIfExists(User $user, string $guestIp): void
    {
        DB::transaction(function () use($user, $guestIp) {
            $guestItems = CartItem::forGuest($guestIp)->get();

            foreach ($guestItems as $guestItem) {
                $existingUserItem = CartItem::forUser($user->id)
                    ->where('service_item_id', $guestItem->service_item_id)
                    ->first();

                if ($existingUserItem) {
                    $existingUserItem->increment('qty', $guestItem->qty);
                    $guestItem->delete();
                } else {
                    $guestItem->update([
                        'user_id' => $user->id,
                        'guest_ip' => null,
                    ]);
                }
            }
        });
    }

    public function resolveCallerScope(Request $request): array
    {
        if ($request->user()) {
            return [
                'user_id' => $request->user()->id,
                'guest_ip' => null,
            ];
        }

        return [
            'user_id' => null,
            'guest_ip' => $request->ip(),
        ];
    }
}
