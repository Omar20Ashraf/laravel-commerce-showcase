<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\Website\CartItemStoreRequest;
use App\Http\Requests\Website\CartItemUpdateRequest;
use App\Models\CartItem;
use App\Services\Website\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CartItemController extends Controller
{
    public function __construct(private readonly CartService $cartService)
    {
    }

    public function index(Request $request)
    {
        $cartItems = $this->cartService->index(request: $request);

        return view('website.cart.index', compact('cartItems'));
    }

    public function store(CartItemStoreRequest $request)
    {
        $this->cartService->store(request: $request);

        return back()->with('success', __('carts.stored'));
    }

    public function update(CartItemUpdateRequest $request, CartItem $cartItem)
    {
        Gate::authorize('modify', $cartItem);

        $this->cartService->update(cartItem: $cartItem, qty: $request->qty);

        return back()->with('success', __('carts.updated'));
    }

    public function destroy(CartItem $cartItem)
    {
        Gate::authorize('modify', $cartItem);

        $cartItem->delete();

        return back()->with('success', __('carts.removed'));
    }
}
