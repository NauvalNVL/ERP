<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PurchaseSubControl;

class PurchaseSubControlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $purchaseSubControls = [
            // Raw Materials - Local
            [
                'psc_code' => 'RM-LOC-001',
                'psc_name' => 'Kertas Kraft Lokal',
                'category' => 'Raw Materials - Local'
            ],
            [
                'psc_code' => 'RM-LOC-002',
                'psc_name' => 'Lem Perekat Lokal',
                'category' => 'Raw Materials - Local'
            ],
            [
                'psc_code' => 'RM-LOC-003',
                'psc_name' => 'Tinta Cetak Lokal',
                'category' => 'Raw Materials - Local'
            ],

            // Raw Materials - Import
            [
                'psc_code' => 'RM-IMP-001',
                'psc_name' => 'Kertas Kraft Import',
                'category' => 'Raw Materials - Import'
            ],
            [
                'psc_code' => 'RM-IMP-002',
                'psc_name' => 'Lem Perekat Import',
                'category' => 'Raw Materials - Import'
            ],
            [
                'psc_code' => 'RM-IMP-003',
                'psc_name' => 'Tinta Cetak Import',
                'category' => 'Raw Materials - Import'
            ],

            // Production Equipment
            [
                'psc_code' => 'EQ-PROD-001',
                'psc_name' => 'Mesin Corrugator',
                'category' => 'Production Equipment'
            ],
            [
                'psc_code' => 'EQ-PROD-002',
                'psc_name' => 'Mesin Die Cutting',
                'category' => 'Production Equipment'
            ],
            [
                'psc_code' => 'EQ-PROD-003',
                'psc_name' => 'Mesin Flexo Printing',
                'category' => 'Production Equipment'
            ],
            [
                'psc_code' => 'EQ-PROD-004',
                'psc_name' => 'Mesin Slotter',
                'category' => 'Production Equipment'
            ],

            // Maintenance & Repair Services
            [
                'psc_code' => 'SVC-MNT-001',
                'psc_name' => 'Jasa Perbaikan Mesin',
                'category' => 'Maintenance Services'
            ],
            [
                'psc_code' => 'SVC-MNT-002',
                'psc_name' => 'Jasa Kalibrasi Mesin',
                'category' => 'Maintenance Services'
            ],
            [
                'psc_code' => 'SVC-MNT-003',
                'psc_name' => 'Jasa Instalasi Mesin',
                'category' => 'Maintenance Services'
            ],

            // Office Supplies
            [
                'psc_code' => 'OFF-001',
                'psc_name' => 'Alat Tulis Kantor',
                'category' => 'Office Supplies'
            ],
            [
                'psc_code' => 'OFF-002',
                'psc_name' => 'Kertas A4',
                'category' => 'Office Supplies'
            ],
            [
                'psc_code' => 'OFF-003',
                'psc_name' => 'Toner Printer',
                'category' => 'Office Supplies'
            ],

            // Safety Equipment
            [
                'psc_code' => 'SAFETY-001',
                'psc_name' => 'Alat Pelindung Diri',
                'category' => 'Safety Equipment'
            ],
            [
                'psc_code' => 'SAFETY-002',
                'psc_name' => 'Peralatan P3K',
                'category' => 'Safety Equipment'
            ],
            [
                'psc_code' => 'SAFETY-003',
                'psc_name' => 'Alat Pemadam Api',
                'category' => 'Safety Equipment'
            ],

            // IT Equipment
            [
                'psc_code' => 'IT-001',
                'psc_name' => 'Komputer & Laptop',
                'category' => 'IT Equipment'
            ],
            [
                'psc_code' => 'IT-002',
                'psc_name' => 'Software Lisensi',
                'category' => 'IT Equipment'
            ],
            [
                'psc_code' => 'IT-003',
                'psc_name' => 'Network Equipment',
                'category' => 'IT Equipment'
            ]
        ];

        foreach ($purchaseSubControls as $psc) {
            PurchaseSubControl::updateOrCreate(
                ['psc_code' => $psc['psc_code']],
                $psc
            );
        }
    }
}
