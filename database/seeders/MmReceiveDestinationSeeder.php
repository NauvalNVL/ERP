<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MmReceiveDestination;

class MmReceiveDestinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $destinations = [
            [
                'code' => 'BP', 
                'name' => 'GUDANG BAHAN PEMBANTU',
                'address' => 'MBI CIKANDE',
                'contact' => '',
                'tel' => '',
                'fax' => ''
            ],
            [
                'code' => 'IMPORT', 
                'name' => 'SPARE PARTS WAREHOUSE',
                'address' => '',
                'contact' => '',
                'tel' => '',
                'fax' => ''
            ],
            [
                'code' => 'KIM', 
                'name' => 'KARYA INDAH MULTIGUNA',
                'address' => '',
                'contact' => '',
                'tel' => '',
                'fax' => ''
            ],
            [
                'code' => 'MBI', 
                'name' => 'MBI CIKANDE',
                'address' => '',
                'contact' => '',
                'tel' => '',
                'fax' => ''
            ],
            [
                'code' => 'MBIDFS', 
                'name' => 'MBI CIKANDE OFFSET',
                'address' => '',
                'contact' => '',
                'tel' => '',
                'fax' => ''
            ],
            [
                'code' => 'MMTDFS', 
                'name' => 'MMT DFS',
                'address' => '',
                'contact' => '',
                'tel' => '',
                'fax' => ''
            ],
            [
                'code' => 'SPP', 
                'name' => 'GUDANG SPAREPART',
                'address' => '',
                'contact' => '',
                'tel' => '',
                'fax' => ''
            ],
            [
                'code' => 'TRK', 
                'name' => 'GUDANG SPAREPART TRAILER',
                'address' => '',
                'contact' => '',
                'tel' => '',
                'fax' => ''
            ],
        ];
        
        foreach ($destinations as $destination) {
            MmReceiveDestination::updateOrCreate(
                ['code' => $destination['code']],
                $destination
            );
        }
    }
} 