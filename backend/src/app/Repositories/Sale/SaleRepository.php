<?php

namespace App\Repositories\Sale;

use App\Models\Sale;
use App\Repositories\BaseRepository;
use App\Repositories\Sale\Contract\SaleRepositoryContract;

class SaleRepository extends BaseRepository implements SaleRepositoryContract
{
    protected Sale $model;
    
    public function __construct(Sale $model)
    {
        $this->model = $model;
    }
}
