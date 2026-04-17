<?php

namespace App\Http\Requests\Website;

use App\Models\ServiceItem;
use Illuminate\Foundation\Http\FormRequest;

class CartItemStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'service_item' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    $serviceItem = ServiceItem::find($value);

                    if (! $serviceItem?->is_available) {
                        $fail(__('carts.unavailable'));
                    }
                },
            ],
            'qty' => 'required|integer|min:1',
        ];
    }

    public function attributes()
    {
        return [
            'service_item' => __('carts.attributes.service_item'),
            'qty' => __('carts.attributes.qty'),
        ];
    }
}
