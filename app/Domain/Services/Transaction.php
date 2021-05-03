<?php

namespace App\Domain\Services;

use App\Domain\Repositories\TransactionsRepositoryInterface;
use App\Domain\Models\Transactions;

class Transaction
{
    private $payerId;
    private $payeeId;
    private $value;
    private $transactionType;
    private $transaction;
    private $transactionRepository;

    const STATUS_REQUESTED = 1;
    const STATUS_PROCESSING = 2;
    const STATUS_PROCESSED = 3;
    const STATUS_REVERSED = 4;

    public function __construct(
        TransactionsRepositoryInterface $transactionRepository
    ) {
        $this->transactionRepository = $transactionRepository;
    }

    public function setPayerId($payerId)
    {
        $this->payerId = $payerId;
        return $this;
    }

    public function setPayeeId($payeeId)
    {
        $this->payeeId = $payeeId;
        return $this;
    }

    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    public function setTransactionType($transactionType)
    {
        $this->transactionType = $transactionType;
        return $this;
    }

    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
        return $this;
    }

    public function requested()
    {
        $this->transaction = $this->transactionRepository->create([
            'hash' => $this->hashGenerate(),
            'value' => $this->value,
            'payer_id' => $this->payerId,
            'payee_id' => $this->payeeId,
            'transactions_type_id' => $this->transactionType,
            'transactions_status_id' => self::STATUS_REQUESTED,
        ]);
        return $this;
    }

    public function processed()
    {
        $this->transactionRepository->update($this->transaction, ["transactions_status_id" => self::STATUS_PROCESSED]);
        return $this;
    }

    public function processing()
    {
        $this->transactionRepository->update($this->transaction, [
            "transactions_status_id" => self::STATUS_PROCESSING
        ]);
        return $this;
    }

    public function reversed()
    {
        $this->transactionRepository->update($this->transaction, ["transactions_status_id" => self::STATUS_REVERSED]);
        return $this;
    }

    public function getByHash($hash): Transactions
    {
        return $this->transactionRepository->findByAttribute("hash", $hash);
    }

    public function get()
    {
        return $this->transaction;
    }

    private function hashGenerate()
    {
        return bin2hex(openssl_random_pseudo_bytes(7));
    }
}
