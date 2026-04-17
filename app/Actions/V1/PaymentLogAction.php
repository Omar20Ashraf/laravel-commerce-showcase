<?php

namespace App\Actions\V1;

use Illuminate\Support\Facades\Log;

class PaymentLogAction
{
    public function execute(string $message, array $data)
    {
        Log::channel('payment')->info($message, $data);
    }
}
