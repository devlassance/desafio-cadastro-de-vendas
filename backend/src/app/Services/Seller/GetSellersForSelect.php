<?php

namespace App\Services\Seller;

use App\Repositories\Seller\Contract\SellerRepositoryContract;
use Exception;
use Illuminate\Support\Collection;

class GetSellersForSelect
{
    public function __construct(
        private readonly SellerRepositoryContract $sellerRepository
    )
    {
        //
    }

    /**
     * @return Collection
     *
     * @throws \Exception
     */
    public function execute(): Collection
    {
        try {
            return $this->sellerRepository->getAllForSelect();
        } catch (Exception $e) {
            throw new Exception('Error retrieving sellers: ' . $e->getMessage());
        }
    }
}
