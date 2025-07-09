<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MmLocation;

class MmLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sampleLocations = [
            [
                'code' => 'WH01', 
                'name' => 'MAIN WAREHOUSE',
                'description' => 'Main storage warehouse for finished goods',
                'is_active' => true
            ],
            [
                'code' => 'OFFICE', 
                'name' => 'OFFICE',
                'description' => 'Office area',
                'is_active' => true
            ],
            [
                'code' => 'FACTORY', 
                'name' => 'FACTORY',
                'description' => 'Production facility',
                'is_active' => true
            ],
            [
                'code' => 'MESIN', 
                'name' => 'MESIN',
                'description' => 'Machine area',
                'is_active' => true
            ],
            [
                'code' => 'PRODUKSI', 
                'name' => 'PP. MESIN PRODUKSI',
                'description' => 'Production machine area',
                'is_active' => true
            ],
            [
                'code' => 'FORKLIFT', 
                'name' => 'FORKLIFT',
                'description' => 'Forklift storage area',
                'is_active' => true
            ],
            [
                'code' => 'GBAHAN', 
                'name' => 'GUDANG BAHAN PEMBANTU',
                'description' => 'Auxiliary materials warehouse',
                'is_active' => true
            ],
            [
                'code' => 'GSPARE', 
                'name' => 'GUDANG SPAREPART',
                'description' => 'Spare parts storage',
                'is_active' => true
            ],
            [
                'code' => 'GKEND', 
                'name' => 'GUDANG KENDARAAN',
                'description' => 'Vehicle storage',
                'is_active' => true
            ],
            [
                'code' => 'OFFSET', 
                'name' => 'OFFSET',
                'description' => 'Offset printing area',
                'is_active' => true
            ],
            [
                'code' => 'IMPORT', 
                'name' => 'SPAREPART IMPORT',
                'description' => 'Imported spare parts storage',
                'is_active' => true
            ],
        ];
        
        foreach ($sampleLocations as $locationData) {
            MmLocation::updateOrCreate(
                ['code' => $locationData['code']],
                $locationData
            );
        }
    }
} 