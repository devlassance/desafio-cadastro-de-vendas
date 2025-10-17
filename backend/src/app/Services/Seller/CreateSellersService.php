<?php

namespace App\Services\Seller;

use App\Repositories\Seller\Contract\SellerRepositoryContract;
use Exception;

class CreateSellersService
{
    public function __construct(
        private SellerRepositoryContract $sellerRepository
    )
    {

    }

    /**
     * @param array $data
     *
     * @throws Exception
     */
    public function execute(array $data): void
    {
        try {
            $this->sellerRepository->create($data);
        } catch (Exception $e) {
            throw new Exception('Error creating seller: ' . $e->getMessage());
        }
    }
}
