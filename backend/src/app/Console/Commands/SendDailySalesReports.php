<?php

namespace App\Console\Commands;

use App\Mail\DailyAdminSalesSummary;
use App\Mail\DailySellerSalesSummary;
use App\Models\User;
use App\Repositories\Sale\Contract\SaleRepositoryContract;
use App\Repositories\Seller\Contract\SellerRepositoryContract;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDailySalesReports extends Command
{
    public function __construct(
        private readonly SaleRepositoryContract $saleRepository,
        private readonly SellerRepositoryContract $sellerRepository
    )
    {
        parent::__construct();
    }
    protected $signature = 'sales:send-daily-reports';
    protected $description = 'Send daily sales summaries to sellers and admin';

    public function handle(): int
    {
        $tz = config('app.timezone', 'UTC');
        $now = now()->timezone($tz);
        $date = $now->toDateString();

        // Per-seller aggregation for today
        $perSeller = $this->saleRepository->getPerSellerAggregationForToday();

        foreach ($perSeller as $row) {
            $seller = $this->sellerRepository->findById($row->seller_id);
            if (empty($seller->email)) {
                continue;
            }

            Mail::to($seller->email)->queue(
                new DailySellerSalesSummary(
                    date: $date,
                    sellerName: $seller->name ?? 'Vendedor',
                    salesCount: (int) $row->sales_count,
                    totalAmount: (float) $row->total_amount,
                    totalCommission: (float) $row->total_commission,
                )
            );
        }


        $totalAmountToday = $this->saleRepository->totalAmountToday();

        $admin = User::query()->orderBy('id')->first();
        if ($admin && !empty($admin->email)) {
            Mail::to($admin->email)->queue(
                new DailyAdminSalesSummary(
                    date: $date,
                    totalAmount: $totalAmountToday
                )
            );
        }

        $this->info('Daily sales reports queued.');
        return self::SUCCESS;
    }
}
