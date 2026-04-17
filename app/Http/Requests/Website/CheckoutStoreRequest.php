<?php

namespace App\Http\Requests\Website;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => 'required|array|min:1',
            'items.*.cart_item_id' => 'required|integer|exists:cart_items,id',
            'items.*.scheduled_at' => 'required|date|after:now',
        ];
    }

    public function attributes()
    {
        return [
            'items' => __('checkout.attributes.items'),
            'items.*.cart_item_id' => __('checkout.attributes.cart_item_id'),
            'items.*.scheduled_at' => __('checkout.attributes.scheduled_at'),
        ];
    }
}
