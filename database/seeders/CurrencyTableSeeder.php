<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Currency::AvailableCurrencies as $index => $currency) {
            $currency['id'] = $index;
            Currency::firstOrCreate($currency);
        }
    }
}
