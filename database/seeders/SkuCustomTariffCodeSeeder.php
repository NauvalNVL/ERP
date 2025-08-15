<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SkuCustomTariffCode;
use App\Models\MmSku;
use App\Models\CustomTariffCode;

class SkuCustomTariffCodeSeeder extends Seeder
{
    public function run()
    {
        // Get some sample SKUs and tariff codes
        $skus = MmSku::where('is_active', true)->limit(20)->get();
        $tariffCodes = CustomTariffCode::where('is_active', true)->get();
        
        if ($skus->isEmpty() || $tariffCodes->isEmpty()) {
            $this->command->info('No SKUs or tariff codes found. Skipping SKU tariff code seeding.');
            return;
        }

        $assignments = [
            // Paper products
            ['sku_pattern' => 'PAPER', 'tariff_category' => 'Paper Products'],
            ['sku_pattern' => 'CARD', 'tariff_category' => 'Paper Products'],
            ['sku_pattern' => 'BOARD', 'tariff_category' => 'Paper Products'],
            
            // Plastic products
            ['sku_pattern' => 'PLASTIC', 'tariff_category' => 'Plastic Products'],
            ['sku_pattern' => 'FILM', 'tariff_category' => 'Plastic Products'],
            
            // Machinery
            ['sku_pattern' => 'MACHINE', 'tariff_category' => 'Machinery'],
            ['sku_pattern' => 'MOTOR', 'tariff_category' => 'Machinery'],
            ['sku_pattern' => 'PUMP', 'tariff_category' => 'Machinery'],
            
            // Electronics
            ['sku_pattern' => 'ELECTRONIC', 'tariff_category' => 'Electronics'],
            ['sku_pattern' => 'CIRCUIT', 'tariff_category' => 'Electronics'],
            ['sku_pattern' => 'CHIP', 'tariff_category' => 'Electronics'],
            
            // Textiles
            ['sku_pattern' => 'FABRIC', 'tariff_category' => 'Textiles'],
            ['sku_pattern' => 'CLOTH', 'tariff_category' => 'Textiles'],
            
            // Chemicals
            ['sku_pattern' => 'CHEMICAL', 'tariff_category' => 'Chemicals'],
            ['sku_pattern' => 'ACID', 'tariff_category' => 'Chemicals'],
            
            // Food products
            ['sku_pattern' => 'FOOD', 'tariff_category' => 'Food Products'],
            ['sku_pattern' => 'INGREDIENT', 'tariff_category' => 'Food Products'],
            
            // Automotive
            ['sku_pattern' => 'AUTO', 'tariff_category' => 'Automotive'],
            ['sku_pattern' => 'CAR', 'tariff_category' => 'Automotive'],
            
            // Pharmaceuticals
            ['sku_pattern' => 'MEDICINE', 'tariff_category' => 'Pharmaceuticals'],
            ['sku_pattern' => 'DRUG', 'tariff_category' => 'Pharmaceuticals'],
            
            // Metals
            ['sku_pattern' => 'METAL', 'tariff_category' => 'Metals'],
            ['sku_pattern' => 'STEEL', 'tariff_category' => 'Metals'],
            ['sku_pattern' => 'ALUMINUM', 'tariff_category' => 'Metals'],
        ];

        $created = 0;
        foreach ($skus as $sku) {
            // Find matching tariff code based on SKU name pattern
            $matchingTariffCode = null;
            foreach ($assignments as $assignment) {
                if (stripos($sku->sku_name, $assignment['sku_pattern']) !== false) {
                    $matchingTariffCode = $tariffCodes->where('category', $assignment['tariff_category'])->first();
                    break;
                }
            }
            
            // If no specific match, assign a random tariff code
            if (!$matchingTariffCode) {
                $matchingTariffCode = $tariffCodes->random();
            }
            
            // Create SKU tariff code assignment
            $skuTariffCode = SkuCustomTariffCode::updateOrCreate(
                [
                    'sku_id' => $sku->sku,
                    'custom_tariff_code_id' => $matchingTariffCode->id
                ],
                [
                    'tariff_description' => $matchingTariffCode->name,
                    'duty_rate' => $matchingTariffCode->duty_rate,
                    'vat_rate' => $matchingTariffCode->tax_rate,
                    'pph_import_rate' => rand(0, 5), // Random PPh rate
                    'country_origin' => $matchingTariffCode->country_origin,
                    'is_active' => true,
                    'notes' => 'Auto-assigned based on SKU category',
                    'created_by' => 'seeder',
                    'updated_by' => 'seeder',
                ]
            );
            
            $created++;
        }

        $this->command->info("SKU Custom Tariff Code data seeded successfully! Created {$created} assignments.");
    }
} 