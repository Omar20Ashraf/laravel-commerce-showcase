<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\V1\CreateTransactionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\TransactionStoreRequest;

class TransactionController extends Controller
{
    public function store(TransactionStoreRequest $request, CreateTransactionAction $createTransactionAction)
    {
        $redirectUrl = $createTransactionAction->execute(
            invoice: $request->invoice,
            gatewayId: $request->gateway
        );

        return response([
            'message' => __('payment.initiated'),
            'redirect_url' => $redirectUrl,
        ]);
    }
}
