<?php

namespace App\Jobs;
use App\Domain\Services\TransferService;
use App\Domain\Services\Wallet;

use App\Domain\Services\Transaction;
use App\Domain\Repositories\UsersRepositoryInterface;
use App\Domain\Models\Users;

class TransferJob extends Job
{
    private $transaction;
    private $wallet;
    private $payee;
    private $payer;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        Transaction $transaction,
        Wallet $wallet,
        Users $payee,
        Users $payer
    ) {
        $this->transaction = $transaction;
        $this->wallet = $wallet;
        $this->payee = $payee;
        $this->payer = $payer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return TransferService::pull($this->transaction, $this->wallet, $this->payee, $this->payer);
    }
}
