<?php

namespace App\Domain\Contracts;

use Illuminate\Http\Request;
use App\Domain\Services\{Transaction, Wallet, Messenger};
use App\Domain\Models\Users;

interface BaseContractInterface
{
    /**
     * @param Request $request
     * 
     * @return array
     */

    public function push(Request $request): array;
    
    /**
     * @param Transaction $transaction
     * @param Wallet $wallet
     * @param Users $payee
     * @param Users $payer
     * @param Messenger $messenger
     * 
     * @return void
     */
    public static function pull(
        Transaction $transaction,
        Wallet $wallet,
        Users $payee,
        Users $payer,
        Messenger $messenger
    ): void;
}
