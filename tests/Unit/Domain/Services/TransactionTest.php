<?php

namespace Tests\Unit\Domain\Services;

use TestCase;

use App\Domain\Repositories\TransactionsRepositoryInterface;
use App\Domain\Models\Transactions;
use App\Domain\Services\Transaction;
use App\Infrastructure\Persistence\TransactionsRepository;

class TransactionTest extends TestCase
{
    public function testRequested(): void
    {
        $transactionsRepositoryInterface = $this->createMock(
            TransactionsRepositoryInterface::class
        );
        $transactionsRepositoryInterface->method('create')->willReturn($this->mockTransaction());

        $service = $this->factory(Transaction::class, [
            $transactionsRepositoryInterface
        ]);

        $return = $service
            ->setPayerId(1)
            ->setPayeeId(2)
            ->setValue(10)
            ->setTransactionType(1)
            ->requested();

        $this->assertInstanceOf(Transaction::class, $return);

        $this->assertEquals(1, $return->get()->id);
        $this->assertEquals(1, $return->get()->payer_id);
        $this->assertEquals(2, $return->get()->payee_id);
        $this->assertEquals(10, $return->get()->value);
        $this->assertEquals(1, $return->get()->transaction_type_id);
    }

    public function mockTransaction()
    {
        $transaction = new Transactions;
        $transaction->id = 1;
        $transaction->payer_id = 1;
        $transaction->payee_id = 2;
        $transaction->value = 10;
        $transaction->transaction_type_id = 1;
        return $transaction;
    }
}
