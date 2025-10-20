<?php

namespace App\Repositories\Sale\Contract;

use App\Repositories\Contract\BaseRepositoryContract;
use Illuminate\Pagination\LengthAwarePaginator;

interface SaleRepositoryContract extends BaseRepositoryContract
{
    /**
     * Get all sales with their associated sellers, paginated.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllWithSeller(int $perPage = 10): LengthAwarePaginator;
}
