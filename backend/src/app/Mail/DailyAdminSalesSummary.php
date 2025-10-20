<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DailyAdminSalesSummary extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $date,
        public float $totalAmount,
    ) {}

    public function build(): self
    {
        return $this->subject("Resumo diário do sistema - {$this->date}")
            ->view('emails.daily_admin_summary')
            ->with([
                'date' => $this->date,
                'totalAmount' => $this->totalAmount,
            ]);
    }
}
