<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\V1\HandlePaymentTransactionCallbackAction;
use App\Http\Controllers\Controller;

class CallbackController extends Controller
{
    public function store(HandlePaymentTransactionCallbackAction $callbackAction)
    {
        $callbackAction->execute();

        return response([]);
    }
}
