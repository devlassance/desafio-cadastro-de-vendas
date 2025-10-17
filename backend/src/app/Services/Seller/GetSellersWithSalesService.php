<?php

namespace App\Services\Seller;

use App\Exceptions\SellerNotFoundException;
use App\Repositories\Seller\Contract\SellerRepositoryContract;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetSellersWithSalesService
{
    public function __construct(
        private SellerRepositoryContract $sellerRepository
    )
    {
        //
    }

    /**
     * @param int $id
     *
     * @return Model
     *
     * @throws SellerNotFoundException
     * @throws Exception
     */
    public function execute(int $id): Model
    {
        try {
            return $this->sellerRepository->findWithSales($id);
        } catch (ModelNotFoundException $e) {
            throw new SellerNotFoundException();
        } catch (Exception $e) {
            throw new Exception('Error retrieving sellers with sales: ' . $e->getMessage());
        }
    }
}
