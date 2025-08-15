<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ForeignCurrency;
use Illuminate\Support\Facades\DB;

class ForeignCurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            [
                'currency_code' => 'EUR',
                'country' => 'EROPA',
                'currency_name' => 'Euro',
                'exchange_rate' => 16252.000000,
                'exchange_method' => 1,
                'variance_control' => 100.00,
                'max_tax_adj' => 0.00,
            ],
            [
                'currency_code' => 'IDR',
                'country' => 'INDONESIA',
                'currency_name' => 'Rupiah',
                'exchange_rate' => 1.000000,
                'exchange_method' => 1,
                'variance_control' => 100.00,
                'max_tax_adj' => 0.00,
            ],
            [
                'currency_code' => 'USD',
                'country' => 'UNITED STATES',
                'currency_name' => 'US Dollar',
                'exchange_rate' => 14880.000000,
                'exchange_method' => 1,
                'variance_control' => 100.00,
                'max_tax_adj' => 0.00,
            ],
            [
                'currency_code' => 'CNY',
                'country' => 'CHINA',
                'currency_name' => 'China Yuan',
                'exchange_rate' => 1961.475000,
                'exchange_method' => 1,
                'variance_control' => 100.00,
                'max_tax_adj' => 0.00,
            ],
        ];

        foreach ($currencies as $currency) {
            ForeignCurrency::updateOrCreate(
                ['currency_code' => $currency['currency_code']],
                $currency
            );
        }
    }
}
