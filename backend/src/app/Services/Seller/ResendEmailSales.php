<?php

namespace App\Services\Seller;

use App\Mail\DailySellerSalesSummary;
use App\Repositories\Sale\Contract\SaleRepositoryContract;
use Exception;
use Illuminate\Support\Facades\Mail;


class ResendEmailSales
{
    public function __construct(
        private readonly SaleRepositoryContract $saleRepository,
    )
    {
        //
    }

    public function execute(int $sellerId): void
    {
        $tz = config('app.timezone', 'UTC');
        $now = now()->timezone($tz);
        $date = $now->toDateString();

        try {
            $seller = $this->saleRepository->getSalesPerSeller($sellerId);
            Mail::to($seller->email)->queue(
                new DailySellerSalesSummary(
                    date: $date,
                    sellerName: $seller->name ?? 'Vendedor',
                    salesCount: (int) $seller->sales_count,
                    totalAmount: (float) $seller->total_amount,
                    totalCommission: (float) $seller->total_commission,
                )
            );
        } catch (Exception $e) {
            throw new Exception('Error retrieving sellers with sales: ' . $e->getMessage());
        }

    }
}
