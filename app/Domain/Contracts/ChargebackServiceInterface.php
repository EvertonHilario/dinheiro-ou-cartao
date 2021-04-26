<?php

namespace App\Domain\Contracts;

use Illuminate\Http\Request;
use App\Domain\Services\{Transaction, Wallet, Messenger};
use App\Domain\Models\Users;

interface ChargebackServiceInterface
{
    public function push(Request $request): array;

    public static function pull(
        Transaction $transaction,
        Wallet $wallet,
        Users $payee,
        Users $payer,
        Messenger $messenger
    ): void;
}
