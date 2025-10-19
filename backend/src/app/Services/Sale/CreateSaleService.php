<?php

namespace App\Services\Sale;

use App\Repositories\Sale\Contract\SaleRepositoryContract;
use Exception;

class CreateSaleService
{
    public function __construct(
        private readonly SaleRepositoryContract $saleRepository
    )
    {
        //
    }

    /**
     * @param array $data
     *
     * @throws Exception
     */
    public function execute(array $data): void
    {
        try {
            $commission = $data['amount'] * config('commission.initial_percentage');
            $data['commission'] = $commission;
            $this->saleRepository->create($data);
        } catch (Exception $e) {
            throw new Exception('Error creating sale: ' . $e->getMessage());
        }
    }
}
