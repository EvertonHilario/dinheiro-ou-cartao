<?php

namespace App\Providers;

use App\Domain\Repositories\ExternalAuthorizerRepositoryInterface;
use App\Domain\Repositories\ExternalMessengerRepositoryInterface;
use App\Domain\Repositories\PersistenceRepositoryInterface;
use App\Domain\Repositories\UsersRepositoryInterface;
use App\Domain\Repositories\TransactionsRepositoryInterface;
use App\Domain\Repositories\WalletsRepositoryInterface;
use App\Domain\Repositories\OperationsRepositoryInterface;
use App\Domain\Repositories\TransactionsNotificationRepositoryInterface;

use App\Infrastructure\Api\ExternalAuthorizerRepository;
use App\Infrastructure\Api\ExternalMessengerRepository;
use App\Infrastructure\Persistence\BaseRepository;
use App\Infrastructure\Persistence\UsersRepository;
use App\Infrastructure\Persistence\TransactionsRepository;
use App\Infrastructure\Persistence\WalletsRepository;
use App\Infrastructure\Persistence\OperationsRepository;
use App\Infrastructure\Persistence\TransactionsNotificationRepository;

use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryServiceProvider
 * @package App\Providers
 */
class RepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PersistenceRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(UsersRepositoryInterface::class, UsersRepository::class);
        $this->app->bind(TransactionsRepositoryInterface::class, TransactionsRepository::class);
        $this->app->bind(WalletsRepositoryInterface::class, WalletsRepository::class);
        $this->app->bind(OperationsRepositoryInterface::class, OperationsRepository::class);
        $this->app->bind(ExternalAuthorizerRepositoryInterface::class, ExternalAuthorizerRepository::class);
        $this->app->bind(
            TransactionsNotificationRepositoryInterface::class,
            TransactionsNotificationRepository::class
        );
        $this->app->bind(
            ExternalMessengerRepositoryInterface::class,
            ExternalMessengerRepository::class
        );
    }
}
