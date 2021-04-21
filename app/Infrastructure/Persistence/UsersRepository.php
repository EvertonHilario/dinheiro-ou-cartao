<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Models\Users;
use App\Domain\Repositories\UsersRepositoryInterface;

class UsersRepository extends BaseRepository implements UsersRepositoryInterface
{
   /**
    * UsersRepository constructor.
    *
    * @param Users $model
    */
   public function __construct(Users $model)
   {
       parent::__construct($model);
   }
}
