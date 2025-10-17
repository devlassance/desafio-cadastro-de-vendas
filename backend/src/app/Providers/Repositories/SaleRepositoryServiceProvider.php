<?php

namespace App\Providers\Repositories;

use App\Repositories\Sale\Contract\SaleRepositoryContract;
use App\Repositories\Sale\SaleRepository;
use Illuminate\Support\ServiceProvider;

class SaleRepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services
     */
    public function register(): void
    {
        $this->app->bind(
          SaleRepositoryContract::class,
          SaleRepository::class
        );
    }

    /**
     * Bootstrap services
     */
    public function boot(): array
    {
        return [
            SaleRepositoryContract::class,
        ];
    }
}
