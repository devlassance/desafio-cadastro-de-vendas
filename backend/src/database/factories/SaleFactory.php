<?php

namespace Database\Factories;

use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Sale>
 */
class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $amount = $this->faker->randomFloat(2, 100, 10000);
        return [
            'amount' => $amount,
            'commission' => $amount * config('commission.initial_percentage'),
            'sale_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'seller_id' => Seller::factory(),
        ];
    }
}
