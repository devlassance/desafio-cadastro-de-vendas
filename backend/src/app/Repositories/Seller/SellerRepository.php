<?php

namespace App\Repositories\Seller;

use App\Models\Seller;
use App\Repositories\BaseRepository;
use App\Repositories\Seller\Contract\SellerRepositoryContract;

class SellerRepository extends BaseRepository implements SellerRepositoryContract
{
    protected Seller $model;

    public function __construct(Seller $model)
    {
        $this->model = $model;
    }
}
