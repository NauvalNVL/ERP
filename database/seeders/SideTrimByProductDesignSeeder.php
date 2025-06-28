<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductDesign;
use App\Models\Product;
use App\Models\PaperFlute;
use App\Models\SideTrimByProductDesign;

class SideTrimByProductDesignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all product designs, products, and flutes
        $productDesigns = ProductDesign::all();
        $products = Product::all();
        $flutes = PaperFlute::all();
        
        if ($productDesigns->count() === 0 || $products->count() === 0 || $flutes->count() === 0) {
            $this->command->info('Missing required data. Please ensure ProductDesign, Product, and PaperFlute models have data.');
            return;
        }

        $count = 0;
        $maxEntries = 60; // Limit the number of entries to avoid creating too many
        
        // Create sample data
        foreach ($productDesigns->take(5) as $design) {
            foreach ($products->take(4) as $product) {
                foreach ($flutes->take(3) as $flute) {
                    // Create a non-compute entry
                    SideTrimByProductDesign::updateOrCreate(
                        [
                            'product_design_id' => $design->id,
                            'product_id' => $product->id,
                            'flute_id' => $flute->id,
                        ],
                        [
                            'length_add' => rand(10, 30),
                            'length_less' => rand(5, 20),
                            'compute' => false,
                        ]
                    );
                    $count++;
                    
                    // Create a compute entry for some combinations
                    if (rand(0, 1) > 0) {
                        SideTrimByProductDesign::updateOrCreate(
                            [
                                'product_design_id' => $design->id,
                                'product_id' => $product->id,
                                'flute_id' => $flute->id,
                            ],
                            [
                                'length_add' => rand(20, 40),
                                'length_less' => rand(10, 25),
                                'compute' => true,
                            ]
                        );
                        $count++;
                    }
                    
                    if ($count >= $maxEntries) {
                        break 3; // Exit all loops if we've reached the maximum
                    }
                }
            }
        }
        
        $this->command->info("Created $count side trim by product design records.");
    }
} 