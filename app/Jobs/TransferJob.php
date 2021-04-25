<?php

namespace App\Jobs;
use App\Domain\Services\TransferService;
use App\Domain\Services\{Transaction, Wallet, Messenger};
use App\Domain\Models\Users;

class TransferJob extends Job
{
    private $transaction;
    private $wallet;
    private $payee;
    private $payer;
    private $messenger;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        Transaction $transaction,
        Wallet $wallet,
        Users $payee,
        Users $payer,
        Messenger $messenger
    ) {
        $this->transaction = $transaction;
        $this->wallet = $wallet;
        $this->payee = $payee;
        $this->payer = $payer;
        $this->messenger = $messenger;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return TransferService::pull($this->transaction, $this->wallet, $this->payee, $this->payer, $this->messenger);
    }
}
