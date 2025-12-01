<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;
use App\Models\VehicleClass;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get vehicle classes for reference
        $vehicleClasses = VehicleClass::all()->pluck('VEHICLE_CLASS_CODE')->toArray();
        
        $vehicles = [
            [
                'NO_' => '001',
                'VEHICLE_NO' => '21313',
                'VEHICLE_STATUS' => 'A',
                'VEHICLE_CLASS' => 'HT',
                'VEHICLE_DESCRIPTION' => 'Heavy Trailer',
                'VEHICLE_COMPANY' => 'KIM',
                'COMPANY' => 'KIM',
                'DRIVER_CODE' => '991219',
                'DRIVER_NAME' => 'yanto',
                'DRIVER_ID' => '2123213313',
                'DRIVER_PHONE' => '313131312',
                'NOTE' => 'drive safe',
                'STATUS' => 'Active'
            ],
            [
                'NO_' => '002',
                'VEHICLE_NO' => '75753',
                'VEHICLE_STATUS' => 'A',
                'VEHICLE_CLASS' => 'BE',
                'VEHICLE_DESCRIPTION' => 'Box Engkel',
                'VEHICLE_COMPANY' => 'MBI',
                'COMPANY' => 'MBI',
                'DRIVER_CODE' => '992020',
                'DRIVER_NAME' => 'budi',
                'DRIVER_ID' => '2123213314',
                'DRIVER_PHONE' => '313131313',
                'NOTE' => 'regular maintenance',
                'STATUS' => 'Active'
            ],
            [
                'NO_' => '003',
                'VEHICLE_NO' => '12345',
                'VEHICLE_STATUS' => 'A',
                'VEHICLE_CLASS' => 'WB',
                'VEHICLE_DESCRIPTION' => 'Wing Box',
                'VEHICLE_COMPANY' => 'CUSTOMER',
                'COMPANY' => 'CUSTOMER',
                'DRIVER_CODE' => '993030',
                'DRIVER_NAME' => 'sari',
                'DRIVER_ID' => '2123213315',
                'DRIVER_PHONE' => '313131314',
                'NOTE' => 'new driver',
                'STATUS' => 'Active'
            ],
            [
                'NO_' => '004',
                'VEHICLE_NO' => '67890',
                'VEHICLE_STATUS' => 'O',
                'VEHICLE_CLASS' => 'DB',
                'VEHICLE_DESCRIPTION' => 'D.BOX',
                'VEHICLE_COMPANY' => 'KIM',
                'COMPANY' => 'KIM',
                'DRIVER_CODE' => '994040',
                'DRIVER_NAME' => 'andi',
                'DRIVER_ID' => '2123213316',
                'DRIVER_PHONE' => '313131315',
                'NOTE' => 'obsolete vehicle',
                'STATUS' => 'Obsolete'
            ],
            [
                'NO_' => '005',
                'VEHICLE_NO' => '11111',
                'VEHICLE_STATUS' => 'A',
                'VEHICLE_CLASS' => 'GM',
                'VEHICLE_DESCRIPTION' => 'Grand Max',
                'VEHICLE_COMPANY' => 'MBI',
                'COMPANY' => 'MBI',
                'DRIVER_CODE' => '995050',
                'DRIVER_NAME' => 'rina',
                'DRIVER_ID' => '2123213317',
                'DRIVER_PHONE' => '313131316',
                'NOTE' => 'fuel efficient',
                'STATUS' => 'Active'
            ],
            [
                'NO_' => '006',
                'VEHICLE_NO' => '22222',
                'VEHICLE_STATUS' => 'A',
                'VEHICLE_CLASS' => 'LL',
                'VEHICLE_DESCRIPTION' => 'Lohan Engkel',
                'VEHICLE_COMPANY' => 'CUSTOMER',
                'COMPANY' => 'CUSTOMER',
                'DRIVER_CODE' => '996060',
                'DRIVER_NAME' => 'dani',
                'DRIVER_ID' => '2123213318',
                'DRIVER_PHONE' => '313131317',
                'NOTE' => 'long distance',
                'STATUS' => 'Active'
            ],
            [
                'NO_' => '007',
                'VEHICLE_NO' => '33333',
                'VEHICLE_STATUS' => 'A',
                'VEHICLE_CLASS' => 'DB',
                'VEHICLE_DESCRIPTION' => 'Test Vehicle',
                'VEHICLE_COMPANY' => 'KIM',
                'COMPANY' => 'KIM',
                'DRIVER_CODE' => '997070',
                'DRIVER_NAME' => 'lina',
                'DRIVER_ID' => '2123213319',
                'DRIVER_PHONE' => '313131318',
                'NOTE' => 'test vehicle',
                'STATUS' => 'Active'
            ],
            [
                'NO_' => '008',
                'VEHICLE_NO' => '44444',
                'VEHICLE_STATUS' => 'O',
                'VEHICLE_CLASS' => 'HT',
                'VEHICLE_DESCRIPTION' => 'Heavy Trailer Old',
                'VEHICLE_COMPANY' => 'MBI',
                'COMPANY' => 'MBI',
                'DRIVER_CODE' => '998080',
                'DRIVER_NAME' => 'tono',
                'DRIVER_ID' => '2123213320',
                'DRIVER_PHONE' => '313131319',
                'NOTE' => 'retired',
                'STATUS' => 'Obsolete'
            ],
            [
                'NO_' => '009',
                'VEHICLE_NO' => '55555',
                'VEHICLE_STATUS' => 'A',
                'VEHICLE_CLASS' => 'BE',
                'VEHICLE_DESCRIPTION' => 'Box Engkel New',
                'VEHICLE_COMPANY' => 'CUSTOMER',
                'COMPANY' => 'CUSTOMER',
                'DRIVER_CODE' => '999090',
                'DRIVER_NAME' => 'maya',
                'DRIVER_ID' => '2123213321',
                'DRIVER_PHONE' => '313131320',
                'NOTE' => 'new model',
                'STATUS' => 'Active'
            ],
            [
                'NO_' => '010',
                'VEHICLE_NO' => '66666',
                'VEHICLE_STATUS' => 'A',
                'VEHICLE_CLASS' => 'WB',
                'VEHICLE_DESCRIPTION' => 'Wing Box Premium',
                'VEHICLE_COMPANY' => 'KIM',
                'COMPANY' => 'KIM',
                'DRIVER_CODE' => '991010',
                'DRIVER_NAME' => 'joko',
                'DRIVER_ID' => '2123213322',
                'DRIVER_PHONE' => '313131321',
                'NOTE' => 'premium service',
                'STATUS' => 'Active'
            ]
        ];

        foreach ($vehicles as $vehicleData) {
            Vehicle::updateOrCreate(
                ['VEHICLE_NO' => $vehicleData['VEHICLE_NO']],
                $vehicleData
            );
        }
    }
}
