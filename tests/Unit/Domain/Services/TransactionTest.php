<?php

namespace Tests\Unit\Domain\Services;

use TestCase;

use App\Domain\Repositories\TransactionsRepositoryInterface;
use App\Domain\Models\Transactions;
use App\Domain\Services\Transaction;
use App\Infrastructure\Persistence\TransactionsRepository;

class TransactionTest extends TestCase
{
    const STATUS_REQUESTED = 1;
    const STATUS_PROCESSING = 2;
    const STATUS_PROCESSED = 3;
    const STATUS_REVERSED = 4;

    public function testRequested(): void
    {
        $transactionsRepositoryInterface = $this->createMock(
            TransactionsRepositoryInterface::class
        );
        $expected = $this->mockTransaction();
        $transactionsRepositoryInterface->method('create')->willReturn($expected);

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

        $this->assertEquals($expected->id, $return->get()->id);
        $this->assertEquals($expected->payer_id, $return->get()->payer_id);
        $this->assertEquals($expected->payee_id, $return->get()->payee_id);
        $this->assertEquals($expected->value, $return->get()->value);
        $this->assertEquals($expected->transaction_type_id, $return->get()->transaction_type_id);
    }

    public function testProcessed(): void
    {
        $transactionsRepositoryInterface = $this->createMock(
            TransactionsRepositoryInterface::class
        );
        $expected = $this->mockTransaction();
        $expected->transaction_type_id = self::STATUS_PROCESSED;
        $transactionsRepositoryInterface->method('update')->willReturn(true);

        $service = $this->factory(Transaction::class, [
            $transactionsRepositoryInterface
        ]);

        $return = $service
            ->setTransaction($expected)
            ->processed();

        $this->assertInstanceOf(Transaction::class, $return);

        $this->assertEquals($expected->id, $return->get()->id);
        $this->assertEquals($expected->payer_id, $return->get()->payer_id);
        $this->assertEquals($expected->payee_id, $return->get()->payee_id);
        $this->assertEquals($expected->value, $return->get()->value);
        $this->assertEquals($expected->transaction_type_id, $return->get()->transaction_type_id);
    }

    public function testProcessing(): void
    {
        $transactionsRepositoryInterface = $this->createMock(
            TransactionsRepositoryInterface::class
        );
        $expected = $this->mockTransaction();
        $expected->transaction_type_id = self::STATUS_PROCESSING;
        $transactionsRepositoryInterface->method('update')->willReturn(true);

        $service = $this->factory(Transaction::class, [
            $transactionsRepositoryInterface
        ]);

        $return = $service
            ->setTransaction($expected)
            ->processing();

        $this->assertInstanceOf(Transaction::class, $return);

        $this->assertEquals($expected->id, $return->get()->id);
        $this->assertEquals($expected->payer_id, $return->get()->payer_id);
        $this->assertEquals($expected->payee_id, $return->get()->payee_id);
        $this->assertEquals($expected->value, $return->get()->value);
        $this->assertEquals($expected->transaction_type_id, $return->get()->transaction_type_id);
    }

    public function testreversed(): void
    {
        $transactionsRepositoryInterface = $this->createMock(
            TransactionsRepositoryInterface::class
        );
        $expected = $this->mockTransaction();
        $expected->transaction_type_id = self::STATUS_REVERSED;
        $transactionsRepositoryInterface->method('update')->willReturn(true);

        $service = $this->factory(Transaction::class, [
            $transactionsRepositoryInterface
        ]);

        $return = $service
            ->setTransaction($expected)
            ->reversed();

        $this->assertInstanceOf(Transaction::class, $return);

        $this->assertEquals($expected->id, $return->get()->id);
        $this->assertEquals($expected->payer_id, $return->get()->payer_id);
        $this->assertEquals($expected->payee_id, $return->get()->payee_id);
        $this->assertEquals($expected->value, $return->get()->value);
        $this->assertEquals($expected->transaction_type_id, $return->get()->transaction_type_id);
    }

    public function mockTransaction()
    {
        $transaction = new Transactions;
        $transaction->id = 1;
        $transaction->payer_id = 1;
        $transaction->payee_id = 2;
        $transaction->value = 10;
        $transaction->transaction_type_id = self::STATUS_REQUESTED;
        return $transaction;
    }
}
