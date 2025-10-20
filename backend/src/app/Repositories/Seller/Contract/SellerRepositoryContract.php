<?php

namespace App\Repositories\Seller\Contract;

use App\Models\Seller;
use App\Repositories\Contract\BaseRepositoryContract;
use Illuminate\Support\Collection;

interface SellerRepositoryContract extends BaseRepositoryContract
{

    /**
     * Find a seller with sales by id.
     *
     * @param int $id
     * @return Seller
     */
    public function findWithSales(int $id): Seller;

    /**
     * Get all sellers for select input.
     *
     * @return Collection
     */
    public function getAllForSelect(): Collection;

}
