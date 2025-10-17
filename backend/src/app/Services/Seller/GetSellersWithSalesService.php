<?php

namespace App\Services\Seller;

use App\Repositories\Seller\Contract\SellerRepositoryContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class GetSellersWithSalesService
{
    public function __construct(
        private SellerRepositoryContract $sellerRepository
    )
    {
        //
    }

    public function execute(int $id): Model
    {
        try {
            return $this->sellerRepository->findWithSales($id);
        } catch (\Exception $e) {
            throw new \Exception('Error retrieving sellers with sales: ' . $e->getMessage());
        }
    }
}
