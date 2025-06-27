<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MmCategory;

class MmCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'code' => 'RAW', 
                'name' => 'RAW MATERIALS',
                'description' => 'Raw materials used for production processes including paper, glue, and other manufacturing inputs',
                'is_active' => true
            ],
            [
                'code' => 'FINISH', 
                'name' => 'FINISHED GOODS',
                'description' => 'Completed products ready for delivery or sale',
                'is_active' => true
            ],
            [
                'code' => 'SPARE', 
                'name' => 'SPARE PARTS',
                'description' => 'Equipment and machinery parts for maintenance and repairs',
                'is_active' => true
            ],
            [
                'code' => 'OFFICE', 
                'name' => 'OFFICE SUPPLIES',
                'description' => 'General office supplies including stationery, toner cartridges, and accessories',
                'is_active' => true
            ],
            [
                'code' => 'TOOL', 
                'name' => 'TOOLS AND EQUIPMENT',
                'description' => 'Maintenance tools and equipment for factory operations',
                'is_active' => true
            ],
            [
                'code' => 'PACK', 
                'name' => 'PACKAGING MATERIALS',
                'description' => 'Materials used for packaging including tapes, strapping, and labels',
                'is_active' => true
            ],
            [
                'code' => 'CONS', 
                'name' => 'CONSUMABLES',
                'description' => 'Consumable items for production that are used up during manufacturing processes',
                'is_active' => true
            ],
            [
                'code' => 'SAFETY', 
                'name' => 'SAFETY EQUIPMENT',
                'description' => 'PPE and safety-related items including helmets, gloves, and protective clothing',
                'is_active' => true
            ],
            [
                'code' => 'CHEM', 
                'name' => 'CHEMICALS',
                'description' => 'Chemical substances used in production and maintenance',
                'is_active' => true
            ],
            [
                'code' => 'IT', 
                'name' => 'IT EQUIPMENT',
                'description' => 'Computer hardware, software, and accessories',
                'is_active' => true
            ],
            [
                'code' => 'JANITORIAL', 
                'name' => 'JANITORIAL SUPPLIES',
                'description' => 'Cleaning supplies and equipment for facility maintenance',
                'is_active' => true
            ],
            [
                'code' => 'ELECT', 
                'name' => 'ELECTRICAL SUPPLIES',
                'description' => 'Electrical components and supplies for facility and equipment maintenance',
                'is_active' => true
            ],
            [
                'code' => 'MECH', 
                'name' => 'MECHANICAL COMPONENTS',
                'description' => 'Mechanical parts and components for equipment maintenance and repair',
                'is_active' => false
            ],
            [
                'code' => 'FOOD', 
                'name' => 'FOOD SUPPLIES',
                'description' => 'Food and beverage supplies for canteen and employee facilities',
                'is_active' => true
            ],
            [
                'code' => 'UNIFORM', 
                'name' => 'EMPLOYEE UNIFORMS',
                'description' => 'Work uniforms and company-branded apparel',
                'is_active' => true
            ]
        ];

        foreach ($categories as $category) {
            MmCategory::updateOrCreate(
                ['code' => $category['code']],
                $category
            );
        }
    }
} 