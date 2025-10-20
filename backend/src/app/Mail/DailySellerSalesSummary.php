<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DailySellerSalesSummary extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $date,
        public string $sellerName,
        public int $salesCount,
        public float $totalAmount,
        public float $totalCommission,
    ) {}

    public function build(): self
    {
        return $this->subject("Resumo diário de vendas - {$this->date}")
            ->view('emails.daily_seller_summary')
            ->with([
                'date' => $this->date,
                'sellerName' => $this->sellerName,
                'salesCount' => $this->salesCount,
                'totalAmount' => $this->totalAmount,
                'totalCommission' => $this->totalCommission,
            ]);
    }
}
