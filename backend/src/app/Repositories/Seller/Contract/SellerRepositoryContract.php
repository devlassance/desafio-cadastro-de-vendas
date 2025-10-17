<?php

namespace App\Repositories\Seller\Contract;

use App\Repositories\Contract\BaseRepositoryContract;

interface SellerRepositoryContract extends BaseRepositoryContract
{

    /**
     * Find a seller with sales by id.
     *
     * @param int|string $id
     * @return mixed
     */
    public function findWithSales($id);

}