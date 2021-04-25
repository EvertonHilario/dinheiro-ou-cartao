<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Models\TransactionsNotification;
use App\Domain\Repositories\TransactionsNotificationRepositoryInterface;

class TransactionsNotificationRepository extends BaseRepository implements TransactionsNotificationRepositoryInterface
{   
    /**
     * TransactionsNotificationRepository constructor.
     *
     * @param TransactionsNotification $model
     */
    public function __construct(TransactionsNotification $model)
    {
        parent::__construct($model);
    }
}
