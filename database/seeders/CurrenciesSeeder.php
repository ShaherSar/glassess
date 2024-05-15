<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Currency;

class CurrenciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            'USD',
            'JOR',
            'GBP',
            'EUR',
            'JPY'
        ];

        foreach( $currencies as $currency ) {
            Currency::insert([
                'name' => $currency
            ]);
        }
    }
}
