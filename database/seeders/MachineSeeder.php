<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Machine;

class MachineSeeder extends Seeder
{
    /**
     * Seed the machine master data.
     */
    public function run(): void
    {
        $machines = [
            [
                'machine_code'   => 'COR-01',
                'machine_name'   => 'Corrugator Line 1',
                'process'        => '10 - CORRUGATING',
                'sub_process'    => null,
                'resource_type'  => 'I-InHouse',
                'finisher_type'  => 'X-N/Applicable',
                'status'         => 'Act',
            ],
            [
                'machine_code'   => 'PRN-01',
                'machine_name'   => 'Printer Slotter 1',
                'process'        => '20 - CONVERTING',
                'sub_process'    => '10 - PRINTER',
                'resource_type'  => 'I-InHouse',
                'finisher_type'  => 'G-Gluer',
                'status'         => 'Act',
            ],
            [
                'machine_code'   => 'DCT-01',
                'machine_name'   => 'Diecutter 1',
                'process'        => '20 - CONVERTING',
                'sub_process'    => '20 - DIECUTTER',
                'resource_type'  => 'I-InHouse',
                'finisher_type'  => 'S-Stitcher',
                'status'         => 'Act',
            ],
            [
                'machine_code'   => 'FIN-01',
                'machine_name'   => 'Finishing Line 1',
                'process'        => '20 - CONVERTING',
                'sub_process'    => '30 - FINISHER',
                'resource_type'  => 'I-InHouse',
                'finisher_type'  => 'P-Packing',
                'status'         => 'Act',
            ],
        ];

        foreach ($machines as $data) {
            Machine::updateOrCreate(
                ['machine_code' => $data['machine_code']],
                $data
            );
        }
    }
}
