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
        // Clear the table first
        DB::table('foreign_currencies')->delete();

        // Insert sample data
        ForeignCurrency::create([
            'currency_code' => 'CNY',
            'country' => 'CHINA',
            'currency_name' => 'China Yuan',
            'exchange_rate' => 1961.475000,
            'exchange_method' => 1, // Multiply
            'variance_control' => 100.00,
            'max_tax_adj' => 0.00,
        ]);

        ForeignCurrency::create([
            'currency_code' => 'USD',
            'country' => 'USA',
            'currency_name' => 'US Dollar',
            'exchange_rate' => 14500.000000, // Example Rate
            'exchange_method' => 1, // Multiply
            'variance_control' => 5.00, // Example Variance
            'max_tax_adj' => 0.00,
        ]);
        
        ForeignCurrency::create([
            'currency_code' => 'EUR',
            'country' => 'EUROPE',
            'currency_name' => 'Euro',
            'exchange_rate' => 16000.000000, // Example Rate
            'exchange_method' => 1, // Multiply
            'variance_control' => 5.00, // Example Variance
            'max_tax_adj' => 0.00,
        ]);

        // Add more currencies as needed
    }
}
