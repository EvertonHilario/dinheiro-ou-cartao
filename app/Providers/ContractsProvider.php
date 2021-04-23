<?php 

namespace App\Providers;

use App\Domain\Contracts\TransferServiceInterface;
use App\Domain\Services\TransferService;

use Illuminate\Support\ServiceProvider;

/**
* Class RepositoryServiceProvider
* @package App\Providers
*/
class ContractsProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(TransferServiceInterface::class, TransferService::class);
    }
}