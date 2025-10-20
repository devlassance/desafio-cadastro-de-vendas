<?php

namespace App\Services\Sale;

use App\Repositories\Sale\Contract\SaleRepositoryContract;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class GetSalesService
{
    public function __construct(
        private readonly SaleRepositoryContract $saleRepository
    )
    {
        //
    }

    public function execute(): LengthAwarePaginator
    {
        try {
            return $this->saleRepository->getAllWithSeller();
        } catch (Exception $e) {
            throw new Exception('Error retrieving sales: ' . $e->getMessage());
        }
    }
}
