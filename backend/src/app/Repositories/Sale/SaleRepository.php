<?php

namespace App\Repositories\Sale;

use App\Models\Sale;
use App\Repositories\BaseRepository;
use App\Repositories\Sale\Contract\SaleRepositoryContract;
use Illuminate\Pagination\LengthAwarePaginator;

class SaleRepository extends BaseRepository implements SaleRepositoryContract
{
    protected Sale $model;

    public function __construct(Sale $model)
    {
        $this->model = $model;
    }

    /**
     * Get all sales with their associated sellers, paginated.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllWithSeller(int $perPage = 10): LengthAwarePaginator
    {
        $paginator = $this->model
            ->select('id', 'seller_id', 'amount', 'commission', 'sale_date', 'created_at', 'updated_at')
            ->with('seller:id,name')
            ->paginate($perPage);

        $paginator->getCollection()->transform(function ($item) {
            $item->setHidden(['seller_id']);
            return $item;
        });

        return $paginator;
    }
}
