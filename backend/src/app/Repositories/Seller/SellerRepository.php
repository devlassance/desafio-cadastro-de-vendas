<?php

namespace App\Repositories\Seller;

use App\Models\Seller;
use App\Repositories\BaseRepository;
use App\Repositories\Seller\Contract\SellerRepositoryContract;
use Illuminate\Support\Collection;

class SellerRepository extends BaseRepository implements SellerRepositoryContract
{
    protected Seller $model;

    public function __construct(Seller $model)
    {
        $this->model = $model;
    }

    /**
     * Find a seller by id with sales eager loaded.
     *
     * @param int|string $id
     * @return Seller
     */
    public function findWithSales($id): Seller
    {
        return $this->model->with('sales')->findOrFail($id);
    }

    /**
     * Get all sellers for select input.
     *
     * @return Collection
     */
    public function getAllForSelect(): Collection
    {
        return $this->model->pluck('name', 'id');
    }
}
