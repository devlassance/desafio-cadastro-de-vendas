<?php

namespace App\Services\Sale;

use App\Repositories\Sale\Contract\SaleRepositoryContract;
use Exception;
use Illuminate\Support\Collection;

class GetSalesService
{
    public function __construct(
        private SaleRepositoryContract $saleRepository
    )
    {
        //
    }

    public function execute(): Collection
    {
        try {
            return $this->saleRepository->getAll();
        } catch (Exception $e) {
            throw new Exception('Error retrieving sales: ' . $e->getMessage());
        }
    }
}
