<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VehicleClass;

class VehicleClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicleClasses = [
            [
                'NO_' => 1,
                'VEHICLE_CLASS_CODE' => 'BE',
                'DESCRIPTION' => 'T. Box Engkel'
            ],
            [
                'NO_' => 2,
                'VEHICLE_CLASS_CODE' => 'DB',
                'DESCRIPTION' => 'D.BOX'
            ],
            [
                'NO_' => 3,
                'VEHICLE_CLASS_CODE' => 'GM',
                'DESCRIPTION' => 'GRAND MAX'
            ],
            [
                'NO_' => 4,
                'VEHICLE_CLASS_CODE' => 'HT',
                'DESCRIPTION' => 'H.TRAILER'
            ],
            [
                'NO_' => 5,
                'VEHICLE_CLASS_CODE' => 'LL',
                'DESCRIPTION' => 'LOHAN ENGKEL'
            ],
            [
                'NO_' => 6,
                'VEHICLE_CLASS_CODE' => 'WB',
                'DESCRIPTION' => 'WING BOX'
            ],
            [
                'NO_' => 7,
                'VEHICLE_CLASS_CODE' => 'FB',
                'DESCRIPTION' => 'FLAT BED'
            ],
            [
                'NO_' => 8,
                'VEHICLE_CLASS_CODE' => 'CB',
                'DESCRIPTION' => 'CONTAINER BOX'
            ],
            [
                'NO_' => 9,
                'VEHICLE_CLASS_CODE' => 'SB',
                'DESCRIPTION' => 'SIDE BOX'
            ],
            [
                'NO_' => 10,
                'VEHICLE_CLASS_CODE' => 'TB',
                'DESCRIPTION' => 'TANKER BOX'
            ]
        ];

        foreach ($vehicleClasses as $vehicleClass) {
            VehicleClass::updateOrCreate(
                ['VEHICLE_CLASS_CODE' => $vehicleClass['VEHICLE_CLASS_CODE']],
                $vehicleClass
            );
        }
    }
}
