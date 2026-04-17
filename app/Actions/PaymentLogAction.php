<?php

namespace App\Actions;

use Illuminate\Support\Facades\Log;

class PaymentLogAction
{
    public function execute(string $message, array $data)
    {
        Log::channel('payment')->info($message, $data);
    }
}
