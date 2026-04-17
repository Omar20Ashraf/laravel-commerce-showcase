<?php

namespace App\Actions;

use App\Jobs\ProcessCheckoutJob;
use App\Models\CartItem;
use Illuminate\Support\Facades\DB;

class ProcessCheckoutAction
{
    public function checkout(array $itemsData, int $userId): void
    {
        $cartItemsIds = collect($itemsData)->pluck('cart_item_id')->toArray();

        $cartItems = CartItem::forUser($userId)
            ->whereIn('id', $cartItemsIds)
            ->with('serviceItem.provider')
            ->get();

        $unavailableItemsCount = 0;

        foreach ($cartItems as $cartItem) {
            if (! $cartItem->serviceItem->is_available) {
                $cartItem->delete();
                $unavailableItemsCount++;
            }
        }

        if ($unavailableItemsCount > 0) {
            abort(409, __('checkout.unavailable'));
        }

        if ($cartItems->count() !== collect($itemsData)->count()) {
            abort(409, __('checkout.invalid_items'));
        }

        DB::transaction(function () use ($itemsData, $cartItems, $userId) {
            $snapshotData = [];
            $itemsDataCollect = collect($itemsData)->keyBy('cart_item_id');

            foreach ($cartItems as $cartItem) {
                $snapshotData[] = [
                    'service_item_id' => $cartItem->service_item_id,
                    'qty' => $cartItem->qty,
                    'scheduled_at' => $itemsDataCollect[$cartItem->id]['scheduled_at'],
                ];

                $cartItem->delete();
            }

            ProcessCheckoutJob::dispatch($userId, $snapshotData);
        });
    }
}
