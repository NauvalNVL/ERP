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
                'customer_code' => '000211-08',
                'customer_name' => 'ABDULLAH, BPK',
                'salesperson' => 'S111',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'status' => 'Active',
            ],
            [
                'customer_code' => '000680-06',
                'customer_name' => 'ACEP SUNANDAR, BPK',
                'salesperson' => 'S140',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'status' => 'Active',
            ],
            [
                'customer_code' => '000585-01',
                'customer_name' => 'ACHMAD JAMAL',
                'salesperson' => 'S102',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'status' => 'Active',
            ],
            [
                'customer_code' => '000283',
                'customer_name' => 'ACOSTA SUPER FOOD, PT',
                'salesperson' => 'S103',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'status' => 'Active',
            ],
            [
                'customer_code' => '000903',
                'customer_name' => 'ADHITYA SERAYAKORITA, PT',
                'salesperson' => 'S103',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'status' => 'Active',
            ],
            [
                'customer_code' => '000507',
                'customer_name' => 'ADIKARYA GEMILANG',
                'salesperson' => 'S140',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'status' => 'Active',
            ],
            [
                'customer_code' => '000581',
                'customer_name' => 'AGEL LANGGENG, PT',
                'salesperson' => 'S143',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'status' => 'Active',
            ],
            [
                'customer_code' => '000004',
                'customer_name' => 'AGILITY INTERNATIONAL, PT',
                'salesperson' => 'S118',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'status' => 'Active',
            ],
            [
                'customer_code' => '000676',
                'customer_name' => 'AGRINDO MAJU LESTARI, PT',
                'salesperson' => 'S142',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'status' => 'Active',
            ],
            [
                'customer_code' => '000839',
                'customer_name' => 'AGRO MEGA PERKASA, PT',
                'salesperson' => 'S111',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'status' => 'Active',
            ],
            [
                'customer_code' => '000767',
                'customer_name' => 'AGUNG KEMUNING WIJAYA, PT',
                'salesperson' => 'S123',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'status' => 'Active',
            ],
            [
                'customer_code' => '000212-24',
                'customer_name' => 'AGUS',
                'salesperson' => 'S111',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'status' => 'Active',
            ],
            [
                'customer_code' => '000861-03',
                'customer_name' => 'AGUS IMAM MAKRUF',
                'salesperson' => 'S140',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'status' => 'Active',
            ],
            [
                'customer_code' => '000138-01',
                'customer_name' => 'AGUS, BPK',
                'salesperson' => 'S123',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'status' => 'Active',
            ],
            [
                'customer_code' => '000909-05',
                'customer_name' => 'AGUSTIN WULANDARI',
                'salesperson' => 'S111',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'status' => 'Active',
            ],
            [
                'customer_code' => '000774',
                'customer_name' => 'AGUSTINA INDRAWATI',
                'salesperson' => 'S123',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'status' => 'Active',
            ],
            [
                'customer_code' => '000211-07',
                'customer_name' => 'AHMAD SURYADI, BPK',
                'salesperson' => 'S111',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'status' => 'Active',
            ],
            [
                'customer_code' => '000701',
                'customer_name' => 'AKITA RAYA INDONESIA, PT',
                'salesperson' => 'S108',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'status' => 'Active',
            ],
            [
                'customer_code' => '000729',
                'customer_name' => 'AKROM KHASANI',
                'salesperson' => 'S140',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'status' => 'Active',
            ],
            [
                'customer_code' => '000648',
                'customer_name' => 'ALAM PANGAN SENTOSA, PT',
                'salesperson' => 'S143',
                'ac_type' => 'Local',
                'currency' => 'IDR',
                'status' => 'Active',
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
