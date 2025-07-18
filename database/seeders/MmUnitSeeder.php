<?php

namespace Database\Seeders;

use App\Models\MmUnit;
use Illuminate\Database\Seeder;

class MmUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            [
                'code' => '2 KTS',
                'short_name' => 'KTS',
                'full_name' => 'KTS (1 UNIT) SEWA FORKLIFT',
                'is_active' => true
            ],
            [
                'code' => 'BAG',
                'short_name' => 'BAG',
                'full_name' => 'BAG',
                'is_active' => true
            ],
            [
                'code' => 'BALL',
                'short_name' => 'BALL',
                'full_name' => 'BALL',
                'is_active' => true
            ],
            [
                'code' => 'BDL',
                'short_name' => 'BDL',
                'full_name' => 'BDL',
                'is_active' => true
            ],
            [
                'code' => 'BKS',
                'short_name' => 'BUNGKUS',
                'full_name' => 'BUNGKUS',
                'is_active' => true
            ],
            [
                'code' => 'BLAG',
                'short_name' => 'BLAG',
                'full_name' => 'BLAG',
                'is_active' => true
            ],
            [
                'code' => 'BOX',
                'short_name' => 'BOX',
                'full_name' => 'BOX',
                'is_active' => true
            ],
            [
                'code' => 'BTG',
                'short_name' => 'BATANG',
                'full_name' => 'BATANG',
                'is_active' => true
            ],
            [
                'code' => 'BTL',
                'short_name' => 'BOTOL',
                'full_name' => 'BOTOL',
                'is_active' => true
            ],
            [
                'code' => 'BUKU',
                'short_name' => 'BUKU',
                'full_name' => 'BUKU',
                'is_active' => true
            ],
            [
                'code' => 'BUNDEL',
                'short_name' => 'BUNDEL',
                'full_name' => 'BUNDEL (PER)',
                'is_active' => true
            ],
            [
                'code' => 'BUTIR',
                'short_name' => 'BUTIR',
                'full_name' => 'BUTIR',
                'is_active' => true
            ],
            [
                'code' => 'CC',
                'short_name' => 'CC',
                'full_name' => 'CC',
                'is_active' => true
            ],
            [
                'code' => 'CELL',
                'short_name' => 'CELL',
                'full_name' => 'CELL',
                'is_active' => true
            ],
            [
                'code' => 'CM',
                'short_name' => 'CM',
                'full_name' => 'CENTIMETER',
                'is_active' => true
            ],
            [
                'code' => 'COLTD',
                'short_name' => 'COLTD',
                'full_name' => 'COLT DIESEL',
                'is_active' => true
            ],
            [
                'code' => 'DAY',
                'short_name' => 'DAY',
                'full_name' => 'DAY',
                'is_active' => true
            ],
            [
                'code' => 'DRG',
                'short_name' => 'DRG',
                'full_name' => 'DERIGEN',
                'is_active' => true
            ],
            [
                'code' => 'DRUM',
                'short_name' => 'DRUM',
                'full_name' => 'DRUM',
                'is_active' => true
            ],
            [
                'code' => 'DUMTRC',
                'short_name' => 'DUMTRUCK',
                'full_name' => 'DUMTRUCK',
                'is_active' => true
            ],
            [
                'code' => 'KG',
                'short_name' => 'KG',
                'full_name' => 'KILOGRAM',
                'is_active' => true
            ],
            [
                'code' => 'PCS',
                'short_name' => 'PCS',
                'full_name' => 'PIECES',
                'is_active' => true
            ],
            [
                'code' => 'ROLL',
                'short_name' => 'ROLL',
                'full_name' => 'ROLL',
                'is_active' => true
            ],
            [
                'code' => 'LEMBAR',
                'short_name' => 'LEMBAR',
                'full_name' => 'LEMBAR',
                'is_active' => true
            ]
        ];

        foreach ($units as $unit) {
            MmUnit::updateOrCreate(
                ['code' => $unit['code']],
                $unit
            );
        }
    }
} 