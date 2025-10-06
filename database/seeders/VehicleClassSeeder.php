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
                'DESCRIPTION' => 'T. Box Engkel',
                'STANDART_CLASS_CODE' => '#03-1',
                'VOLUME_M3' => 18.50,
                'CAPACITY_WGT_MT' => 3.00
            ],
            [
                'NO_' => 2,
                'VEHICLE_CLASS_CODE' => 'DB',
                'DESCRIPTION' => 'D.BOX',
                'STANDART_CLASS_CODE' => '#05-1',
                'VOLUME_M3' => 22.90,
                'CAPACITY_WGT_MT' => 5.00
            ],
            [
                'NO_' => 3,
                'VEHICLE_CLASS_CODE' => 'GM',
                'DESCRIPTION' => 'GRAND MAX',
                'STANDART_CLASS_CODE' => '#01-1',
                'VOLUME_M3' => 8.57,
                'CAPACITY_WGT_MT' => 1.00
            ],
            [
                'NO_' => 4,
                'VEHICLE_CLASS_CODE' => 'HT',
                'DESCRIPTION' => 'H.TRAILER',
                'STANDART_CLASS_CODE' => '#40-1',
                'VOLUME_M3' => 60.00,
                'CAPACITY_WGT_MT' => 40.00
            ],
            [
                'NO_' => 5,
                'VEHICLE_CLASS_CODE' => 'LL',
                'DESCRIPTION' => 'LOHAN ENGKEL',
                'STANDART_CLASS_CODE' => '#10-1',
                'VOLUME_M3' => 41.95,
                'CAPACITY_WGT_MT' => 10.00
            ],
            [
                'NO_' => 6,
                'VEHICLE_CLASS_CODE' => 'WB',
                'DESCRIPTION' => 'WING BOX',
                'STANDART_CLASS_CODE' => '#24-1',
                'VOLUME_M3' => 40.45,
                'CAPACITY_WGT_MT' => 24.00
            ],
            [
                'NO_' => 7,
                'VEHICLE_CLASS_CODE' => 'FB',
                'DESCRIPTION' => 'FLAT BED',
                'STANDART_CLASS_CODE' => '#26-1',
                'VOLUME_M3' => 45.42,
                'CAPACITY_WGT_MT' => 26.00
            ],
            [
                'NO_' => 8,
                'VEHICLE_CLASS_CODE' => 'CB',
                'DESCRIPTION' => 'CONTAINER BOX',
                'STANDART_CLASS_CODE' => '#20-1',
                'VOLUME_M3' => 33.20,
                'CAPACITY_WGT_MT' => 20.00
            ],
            [
                'NO_' => 9,
                'VEHICLE_CLASS_CODE' => 'SB',
                'DESCRIPTION' => 'SIDE BOX',
                'STANDART_CLASS_CODE' => '#15-1',
                'VOLUME_M3' => 25.50,
                'CAPACITY_WGT_MT' => 15.00
            ],
            [
                'NO_' => 10,
                'VEHICLE_CLASS_CODE' => 'TB',
                'DESCRIPTION' => 'TANKER BOX',
                'STANDART_CLASS_CODE' => '#30-1',
                'VOLUME_M3' => 50.00,
                'CAPACITY_WGT_MT' => 30.00
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
