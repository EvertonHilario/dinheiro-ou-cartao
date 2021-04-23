<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Models\Operations;
use App\Domain\Repositories\OperationsRepositoryInterface;

class OperationsRepository extends BaseRepository implements OperationsRepositoryInterface
{   
    /**
     * OperationsRepository constructor.
     *
     * @param Operations $model
     */
    public function __construct(Operations $model)
    {
        parent::__construct($model);
    }
}
