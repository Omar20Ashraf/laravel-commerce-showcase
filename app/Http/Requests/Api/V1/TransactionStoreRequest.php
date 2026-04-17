<?php

namespace App\Http\Requests\Api\V1;

use App\Models\Invoice;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class TransactionStoreRequest extends FormRequest
{
    public ?Invoice $invoice;

    public function authorize(): bool
    {
        $this->invoice = Invoice::where('payment_token', $this->payment_token)->first();

        if (! $this->invoice) {
            return true; // validation 'required|string' will reject empty token with 422
        }

        return Gate::allows('pay', $this->invoice);
    }

    public function rules(): array
    {
        return [
            'payment_token' => 'required|string',

            'gateway' => [
                'required',
                'integer',
                'bail',
                function ($attribute, $value, $fail) {
                    $availableIds = $this->invoice->availableGateways()->pluck('id')->toArray();

                    if (! in_array($value, $availableIds)) {
                        $fail(__('validation.exists', ['attribute' => $attribute]));
                    }
                },
            ],
        ];
    }
}
