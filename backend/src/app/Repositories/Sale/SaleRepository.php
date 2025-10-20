<?php

namespace App\Repositories\Sale;

use App\Models\Sale;
use App\Repositories\BaseRepository;
use App\Repositories\Sale\Contract\SaleRepositoryContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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

    /**
     * Get per-seller sales aggregation for today.
     *
     * @return Collection
     */
    public function getPerSellerAggregationForToday(): Collection
    {
        $tz = config('app.timezone', 'UTC');
        $now = now()->timezone($tz);
        $start = $now->copy()->startOfDay();
        $end = $now->copy()->endOfDay();

        return $this->model
            ->select([
                'seller_id',
                DB::raw('COUNT(*) as sales_count'),
                DB::raw('COALESCE(SUM(amount),0) as total_amount'),
                DB::raw('COALESCE(SUM(commission),0) as total_commission'),
            ])
            ->whereBetween('sale_date', [$start, $end])
            ->groupBy('seller_id')
            ->get();
    }

    /**
     * Get sales aggregation for a specific seller for today.
     *
     * @param int $sellerId
     * @return Model
     */
    public function getSalesPerSeller(int $sellerId): Model
    {
        $tz = config('app.timezone', 'UTC');
        $now = now()->timezone($tz);
        $start = $now->copy()->startOfDay();
        $end = $now->copy()->endOfDay();

        return $this->model
            ->select([
                'seller_id',
                'sellers.name as name',
                'sellers.email as email',
                DB::raw('COUNT(*) as sales_count'),
                DB::raw('COALESCE(SUM(amount), 0) as total_amount'),
                DB::raw('COALESCE(SUM(commission), 0) as total_commission'),
            ])
            ->join('sellers', 'sales.seller_id', '=', 'sellers.id')
            ->where('seller_id', $sellerId)
            ->whereBetween('sale_date', [$start, $end])
            ->groupBy('seller_id', 'sellers.name', 'sellers.email')
            ->first();
    }

    /**
     * Get total sales amount for today.
     *
     * @return float
     */
    public function totalAmountToday(): float
    {
        $tz = config('app.timezone', 'UTC');
        $now = now()->timezone($tz);
        $start = $now->copy()->startOfDay();
        $end = $now->copy()->endOfDay();

        return $this->model
            ->whereBetween('sale_date', [$start, $end])
            ->sum('amount');
    }
}
