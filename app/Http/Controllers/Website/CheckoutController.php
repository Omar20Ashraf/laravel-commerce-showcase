<?php

namespace App\Http\Controllers\Website;

use App\Actions\ProcessCheckoutAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Website\CheckoutStoreRequest;

class CheckoutController extends Controller
{
    public function store(CheckoutStoreRequest $request, ProcessCheckoutAction $processCheckoutAction)
    {
        $processCheckoutAction->checkout(
            itemsData: $request->items,
            userId: $request->user()->id
        );

        return back()->with('success', __('checkout.stored'));
    }
}
