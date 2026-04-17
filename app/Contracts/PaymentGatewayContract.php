<?php

namespace App\Contracts;

use App\Models\Transaction;

interface PaymentGatewayContract
{
    public function initiate(Transaction $transaction): array;

    public function prepareRequestData(Transaction $transaction): array;
}
