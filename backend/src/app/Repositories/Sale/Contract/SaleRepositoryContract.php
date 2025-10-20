<?php

namespace App\Repositories\Sale\Contract;

use App\Repositories\Contract\BaseRepositoryContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface SaleRepositoryContract extends BaseRepositoryContract
{
    /**
     * Get all sales with their associated sellers, paginated.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllWithSeller(int $perPage = 10): LengthAwarePaginator;

    /**
     * Get per-seller sales aggregation for today.
     *
     * @return Collection
     */
    public function getPerSellerAggregationForToday(): Collection;

    /**
     * Get all sales for a specific seller.
     *
     * @param int $sellerId
     * @return Model
     */
    public function getSalesPerSeller(int $sellerId): Model;

    /**
     * Get total sales amount for today.
     *
     * @return float
     */
    public function totalAmountToday(): float;
}
