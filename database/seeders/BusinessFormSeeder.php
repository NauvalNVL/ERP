<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BusinessForm;

class BusinessFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BusinessForm::updateOrCreate(
            ['bf_code' => 'SO'],
            [
                'bf_name' => 'ISO SO',
                'bf_group' => '1-SALES',
                'bf_iso' => 'Dok. No : MBI-FM-MKT-015 Rev : 00',
                // Tambahkan data check/approve jika ada
            ]
        );

        BusinessForm::updateOrCreate(
            ['bf_code' => 'PO'],
            [
                'bf_name' => 'ISO PO',
                'bf_group' => '5-MATERIAL',
                // Tambahkan data iso, check/approve jika ada
            ]
        );

        BusinessForm::updateOrCreate(
            ['bf_code' => 'PR'],
            [
                'bf_name' => 'ISO PR',
                'bf_group' => '5-MATERIAL',
                // Tambahkan data iso, check/approve jika ada
            ]
        );

        BusinessForm::updateOrCreate(
            ['bf_code' => 'TOPM'],
            [
                'bf_name' => 'MBI-FM-M&E-007',
                'bf_group' => '7-PLANT MAINTENANCE',
                // Tambahkan data iso, check/approve jika ada
            ]
        );

        BusinessForm::updateOrCreate(
            ['bf_code' => 'TORS'],
            [
                'bf_name' => 'MBI-FM-M&E-005',
                'bf_group' => '7-PLANT MAINTENANCE',
                // Tambahkan data iso, check/approve jika ada
            ]
        );
    }
}
