<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Models\Transactions;
use App\Domain\Repositories\TransactionsRepositoryInterface;

class TransactionsRepository extends BaseRepository implements TransactionsRepositoryInterface
{
    /**
     * TransactionsRepository constructor.
     *
     * @param Users $Transactions
     */
    public function __construct(Transactions $model)
    {
        parent::__construct($model);
    }
}
