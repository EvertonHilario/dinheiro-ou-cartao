<?php

namespace App\Domain\Services;

use App\Domain\Repositories\{OperationsRepositoryInterface, WalletsRepositoryInterface};
use App\Domain\Models\{Wallets, Operations};

class Wallet
{
    private $walletRepository;
    private $value;
    private $wallet;
    private $transaction;
    private $operations;
    private $title;

    const OPERATION_TYPE_WITHDRAW = 1;
    const OPERATION_TYPE_DEPOSIT = 2;

    public function __construct(
        WalletsRepositoryInterface $walletRepository,
        OperationsRepositoryInterface $operations
    ) {
        $this->walletRepository = $walletRepository;
        $this->operations = $operations;
    }
    
    public function setWallet($userId): self
    {
        $this->wallet = $this->walletRepository->findByAttribute("users_id", $userId);
        return $this;
    }  

    public function setValue($value): self
    {
        $this->value = $value;
        return $this;
    }

    public function setTransaction($transaction): self
    {
        $this->transaction = $transaction;
        return $this;
    }

    public function setTitle($title): self
    {
        $this->title = $title;
        return $this;
    }

    public function withdraw(): self
    {
        $this->updateBalance($this->wallet->balance - $this->value)
            ->insertOperation(self::OPERATION_TYPE_WITHDRAW);
        return $this;
    }

    public function deposit(): self
    {
        $this->updateBalance($this->wallet->balance + $this->value)
            ->insertOperation(self::OPERATION_TYPE_DEPOSIT);
        return $this;
    }

    private function updateBalance($balance): self
    {
        $this->walletRepository->update($this->wallet, ["balance" => $balance]);
        return $this;
    }

    public function insertOperation($operationType): void
    {
        $this->operations = $this->operations->create([
            "title" => $this->title,
            "value" => $this->value,
            "wallets_id" => $this->wallet->id,
            "transactions_id" => $this->transaction->id,
            "operations_type_id" => $operationType,
        ]);
    }

    public function get(): Wallets
    {
        return $this->wallet;
    }

    public function getOperation(): Operations
    {
        return $this->operations;
    }
}