<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Purchaser;
use Illuminate\Support\Facades\Hash;

class PurchaserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $purchasers = [
            [
                'purchaser_id' => 'ACC',
                'purchaser_name' => 'ACCOUNTING DEPT',
                'type' => 'RQ',
                'email' => 'nkaruna@ptmbi.com',
                'password' => Hash::make('password123'),
                'cc_to_self' => true,
                'is_active' => true,
                'department' => 'ACCOUNTING',
                'position' => 'DEPARTMENT HEAD',
                'employee_id' => 'EMP001',
                'phone' => '+62-21-123-4567',
                'notes' => 'Accounting department requestor'
            ],
            [
                'purchaser_id' => 'BNGKL',
                'purchaser_name' => 'BENGKEL',
                'type' => 'RQ',
                'email' => 'mulyadi@ptmbi.com',
                'password' => Hash::make('password123'),
                'cc_to_self' => true,
                'is_active' => true,
                'department' => 'MAINTENANCE',
                'position' => 'WORKSHOP SUPERVISOR',
                'employee_id' => 'EMP002',
                'phone' => '+62-21-123-4568',
                'notes' => 'Workshop maintenance requestor'
            ],
            [
                'purchaser_id' => 'CHRS',
                'purchaser_name' => 'CHRISTINE',
                'type' => 'PU',
                'email' => 'christine@ptmbi.com',
                'password' => Hash::make('password123'),
                'cc_to_self' => true,
                'is_active' => true,
                'department' => 'PURCHASING',
                'position' => 'PURCHASING OFFICER',
                'employee_id' => 'EMP003',
                'phone' => '+62-21-123-4569',
                'notes' => 'Main purchasing officer'
            ],
            [
                'purchaser_id' => 'CORR',
                'purchaser_name' => 'CORR DEPARTMENT',
                'type' => 'RQ',
                'email' => 'corr@ptmbi.com',
                'password' => Hash::make('password123'),
                'cc_to_self' => false,
                'is_active' => true,
                'department' => 'PRODUCTION',
                'position' => 'CORRUGATION SUPERVISOR',
                'employee_id' => 'EMP004',
                'phone' => '+62-21-123-4570',
                'notes' => 'Corrugation department requestor'
            ],
            [
                'purchaser_id' => 'DISPH',
                'purchaser_name' => 'DISPATCH DEPT',
                'type' => 'RQ',
                'email' => 'johandi@ptmbi.com',
                'password' => Hash::make('password123'),
                'cc_to_self' => true,
                'is_active' => true,
                'department' => 'DISPATCH',
                'position' => 'DISPATCH SUPERVISOR',
                'employee_id' => 'EMP005',
                'phone' => '+62-21-123-4571',
                'notes' => 'Dispatch department requestor'
            ],
            [
                'purchaser_id' => 'FIN',
                'purchaser_name' => 'FINANCE DEPT',
                'type' => 'RQ',
                'email' => 'finance@ptmbi.com',
                'password' => Hash::make('password123'),
                'cc_to_self' => true,
                'is_active' => true,
                'department' => 'FINANCE',
                'position' => 'FINANCE MANAGER',
                'employee_id' => 'EMP006',
                'phone' => '+62-21-123-4572',
                'notes' => 'Finance department requestor'
            ],
            [
                'purchaser_id' => 'FLEXO',
                'purchaser_name' => 'FLEXO DEPARTMENT',
                'type' => 'RQ',
                'email' => 'flexo@ptmbi.com',
                'password' => Hash::make('password123'),
                'cc_to_self' => false,
                'is_active' => true,
                'department' => 'PRODUCTION',
                'position' => 'FLEXO OPERATOR',
                'employee_id' => 'EMP007',
                'phone' => '+62-21-123-4573',
                'notes' => 'Flexo printing department requestor'
            ],
            [
                'purchaser_id' => 'FNSH',
                'purchaser_name' => 'FINISHING DEPARTMENT',
                'type' => 'RQ',
                'email' => 'fnsh@ptmbi.com',
                'password' => Hash::make('password123'),
                'cc_to_self' => true,
                'is_active' => true,
                'department' => 'PRODUCTION',
                'position' => 'FINISHING SUPERVISOR',
                'employee_id' => 'EMP008',
                'phone' => '+62-21-123-4574',
                'notes' => 'Finishing department requestor'
            ],
            [
                'purchaser_id' => 'HANWAY',
                'purchaser_name' => 'HANWAY DIGITAL PRINTING',
                'type' => 'RQ',
                'email' => 'hanway@ptmbi.com',
                'password' => Hash::make('password123'),
                'cc_to_self' => true,
                'is_active' => true,
                'department' => 'PRODUCTION',
                'position' => 'DIGITAL PRINTING OPERATOR',
                'employee_id' => 'EMP009',
                'phone' => '+62-21-123-4575',
                'notes' => 'Digital printing department requestor'
            ],
            [
                'purchaser_id' => 'HRD',
                'purchaser_name' => 'HUMAN RESOURCES DEPT',
                'type' => 'RQ',
                'email' => 'fxaverius@ptmbi.com',
                'password' => Hash::make('password123'),
                'cc_to_self' => true,
                'is_active' => true,
                'department' => 'HUMAN RESOURCES',
                'position' => 'HR MANAGER',
                'employee_id' => 'EMP010',
                'phone' => '+62-21-123-4576',
                'notes' => 'Human resources department requestor'
            ],
            [
                'purchaser_id' => 'IT',
                'purchaser_name' => 'IT DEPT',
                'type' => 'RQ',
                'email' => 'sagurig@multiindustry.com',
                'password' => Hash::make('password123'),
                'cc_to_self' => true,
                'is_active' => true,
                'department' => 'INFORMATION TECHNOLOGY',
                'position' => 'IT MANAGER',
                'employee_id' => 'EMP011',
                'phone' => '+62-21-123-4577',
                'notes' => 'IT department requestor'
            ],
            [
                'purchaser_id' => 'MKT',
                'purchaser_name' => 'INTERNAL SALES',
                'type' => 'PU',
                'email' => 'sales@ptmbi.com',
                'password' => Hash::make('password123'),
                'cc_to_self' => false,
                'is_active' => true,
                'department' => 'SALES',
                'position' => 'SALES MANAGER',
                'employee_id' => 'EMP012',
                'phone' => '+62-21-123-4578',
                'notes' => 'Internal sales department'
            ]
        ];

        foreach ($purchasers as $purchaserData) {
            Purchaser::create($purchaserData);
        }
    }
} 