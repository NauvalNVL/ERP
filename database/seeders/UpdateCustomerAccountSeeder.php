<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UpdateCustomerAccount;

class UpdateCustomerAccountSeeder extends Seeder
{
    public function run()
    {
        $accounts = [
            [
                'customer_code' => 'CUST01',
                'customer_name' => 'PT. TRAILER MAKMUR',
            ],
            [
                'customer_code' => 'CUST02',
                'customer_name' => 'PT. WASTE JAYA',
            ],
            [
                'customer_code' => 'CUST03',
                'customer_name' => 'PT. USAHA PENDAPATAN',
            ],
            [
                'customer_code' => 'CUSTNA',
                'customer_name' => 'PT. USAHA NASIONAL',
            ],
        ];

        foreach ($accounts as $acc) {
            UpdateCustomerAccount::updateOrCreate(
                ['customer_code' => $acc['customer_code']],
                $acc
            );
        }
    }
}
