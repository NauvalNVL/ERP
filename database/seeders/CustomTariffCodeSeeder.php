<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CustomTariffCode;

class CustomTariffCodeSeeder extends Seeder
{
    public function run()
    {
        $tariffCodes = [
            [
                'code' => '12345678',
                'name' => 'Paper and paperboard products',
                'description' => 'Various types of paper and paperboard for packaging and printing',
                'duty_rate' => 5.00,
                'tax_rate' => 10.00,
                'category' => 'Paper Products',
                'country_origin' => 'Indonesia',
                'is_active' => true,
                'notes' => 'Standard HS code for paper products',
                'created_by' => 'seeder',
                'updated_by' => 'seeder',
            ],
            [
                'code' => '23456789',
                'name' => 'Plastic packaging materials',
                'description' => 'Plastic films, sheets, and packaging materials',
                'duty_rate' => 7.50,
                'tax_rate' => 11.00,
                'category' => 'Plastic Products',
                'country_origin' => 'Malaysia',
                'is_active' => true,
                'notes' => 'Common import for packaging industry',
                'created_by' => 'seeder',
                'updated_by' => 'seeder',
            ],
            [
                'code' => '34567890',
                'name' => 'Machinery parts and components',
                'description' => 'Industrial machinery parts and mechanical components',
                'duty_rate' => 10.00,
                'tax_rate' => 12.00,
                'category' => 'Machinery',
                'country_origin' => 'Germany',
                'is_active' => true,
                'notes' => 'High-value machinery imports',
                'created_by' => 'seeder',
                'updated_by' => 'seeder',
            ],
            [
                'code' => '45678901',
                'name' => 'Electronic components',
                'description' => 'Semiconductors, integrated circuits, and electronic parts',
                'duty_rate' => 0.00,
                'tax_rate' => 10.00,
                'category' => 'Electronics',
                'country_origin' => 'Taiwan',
                'is_active' => true,
                'notes' => 'Duty-free under trade agreements',
                'created_by' => 'seeder',
                'updated_by' => 'seeder',
            ],
            [
                'code' => '56789012',
                'name' => 'Textile materials and fabrics',
                'description' => 'Cotton, synthetic, and blended textile materials',
                'duty_rate' => 15.00,
                'tax_rate' => 10.00,
                'category' => 'Textiles',
                'country_origin' => 'China',
                'is_active' => true,
                'notes' => 'High duty rate for textile protection',
                'created_by' => 'seeder',
                'updated_by' => 'seeder',
            ],
            [
                'code' => '67890123',
                'name' => 'Chemical raw materials',
                'description' => 'Industrial chemicals and raw materials for manufacturing',
                'duty_rate' => 3.00,
                'tax_rate' => 10.00,
                'category' => 'Chemicals',
                'country_origin' => 'Singapore',
                'is_active' => true,
                'notes' => 'Essential for manufacturing processes',
                'created_by' => 'seeder',
                'updated_by' => 'seeder',
            ],
            [
                'code' => '78901234',
                'name' => 'Food ingredients and additives',
                'description' => 'Food processing ingredients and food additives',
                'duty_rate' => 20.00,
                'tax_rate' => 10.00,
                'category' => 'Food Products',
                'country_origin' => 'Thailand',
                'is_active' => true,
                'notes' => 'High duty for food security',
                'created_by' => 'seeder',
                'updated_by' => 'seeder',
            ],
            [
                'code' => '89012345',
                'name' => 'Automotive parts and accessories',
                'description' => 'Vehicle parts, components, and automotive accessories',
                'duty_rate' => 12.50,
                'tax_rate' => 10.00,
                'category' => 'Automotive',
                'country_origin' => 'Japan',
                'is_active' => true,
                'notes' => 'Quality automotive components',
                'created_by' => 'seeder',
                'updated_by' => 'seeder',
            ],
            [
                'code' => '90123456',
                'name' => 'Pharmaceutical products',
                'description' => 'Medicines, drugs, and pharmaceutical preparations',
                'duty_rate' => 0.00,
                'tax_rate' => 10.00,
                'category' => 'Pharmaceuticals',
                'country_origin' => 'India',
                'is_active' => true,
                'notes' => 'Duty-free for health reasons',
                'created_by' => 'seeder',
                'updated_by' => 'seeder',
            ],
            [
                'code' => '01234567',
                'name' => 'Metal products and alloys',
                'description' => 'Steel, aluminum, and other metal products',
                'duty_rate' => 8.00,
                'tax_rate' => 10.00,
                'category' => 'Metals',
                'country_origin' => 'Australia',
                'is_active' => true,
                'notes' => 'Essential for construction and manufacturing',
                'created_by' => 'seeder',
                'updated_by' => 'seeder',
            ],
        ];

        foreach ($tariffCodes as $tariffData) {
            CustomTariffCode::updateOrCreate(
                ['code' => $tariffData['code']],
                $tariffData
            );
        }

        $this->command->info('Custom Tariff Code data seeded successfully!');
    }
} 