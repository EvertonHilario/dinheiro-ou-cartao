<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Models\Wallets;
use App\Domain\Repositories\WalletsRepositoryInterface;

class WalletsRepository extends BaseRepository implements WalletsRepositoryInterface
{   
    /**
     * WalletsRepository constructor.
     *
     * @param Wallets $model
     */
    public function __construct(Wallets $model)
    {
        parent::__construct($model);
    }
}
