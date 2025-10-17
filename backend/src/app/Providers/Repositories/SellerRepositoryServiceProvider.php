<?php

namespace App\Providers\Repositories;

use App\Repositories\Seller\Contract\SellerRepositoryContract;
use App\Repositories\Seller\SellerRepository;
use Illuminate\Support\ServiceProvider;

class SellerRepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            SellerRepositoryContract::class,
            SellerRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): array
    {
        return [
          SellerRepositoryContract::class,
        ];
    }
}
