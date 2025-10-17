<?php

namespace App\Services\Seller;

use App\Repositories\Seller\Contract\SellerRepositoryContract;
use Illuminate\Support\Collection;

class GetSellersService
{
    public function __construct(
        private SellerRepositoryContract $sellerRepository
    )
    {
        //
    }

    /**
     * @return Collection
     */
    public function execute(): Collection
    {
        return $this->sellerRepository->getAll();
    }
}
