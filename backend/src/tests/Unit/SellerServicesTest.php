<?php

namespace Tests\Unit;

use App\Exceptions\SellerNotFoundException;
use App\Repositories\Seller\Contract\SellerRepositoryContract;
use App\Services\Seller\CreateSellersService;
use App\Services\Seller\GetSellersForSelect;
use App\Services\Seller\GetSellersService;
use App\Services\Seller\GetSellersWithSalesService;
use Mockery;
use PHPUnit\Framework\TestCase;

class SellerServicesTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_create_sellers_service_calls_create(): void
    {
        $repo = Mockery::mock(SellerRepositoryContract::class);
        $repo->shouldReceive('create')->once()->with(['name' => 'A', 'email' => 'a@a.com']);

        $service = new CreateSellersService($repo);
        $service->execute(['name' => 'A', 'email' => 'a@a.com']);
        $this->assertTrue(true);
    }

}
