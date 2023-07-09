<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FakeExchangeRate;

class FakeExchangeRateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $startDate = '2023-01-01';
        $endDate = '2023-12-31';

        $currentDate = $startDate;
        $faker = \Faker\Factory::create();

        while ($currentDate <= $endDate) {
            $exchangeRate = new FakeExchangeRate();
            $exchangeRate->date = $currentDate;
            $exchangeRate->rate_eur = 1; // EUR base

            // Generate rates based on EUR
            $exchangeRate->rate_brl = $faker->randomFloat(2, 5.18, 5.45);
            $exchangeRate->rate_usd = $faker->randomFloat(2, 1.07, 1.10);
            $exchangeRate->rate_gbp = $faker->randomFloat(2, 0.85, 0.90);

            $exchangeRate->save();

            $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
        }
    }
}
