<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RollTrimByProductDesign;
use App\Models\Product;
use App\Models\ProductDesign;
use App\Models\PaperFlute;

class RollTrimByProductDesignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get products, designs and flutes
        $products = Product::all();
        $designs = ProductDesign::all();
        $flutes = PaperFlute::all();
        
        $count = 0;
        
        // Create sample data for common combinations
        foreach ($designs->take(3) as $design) {
            foreach ($products->take(5) as $product) {
                foreach ($flutes->take(4) as $flute) {
                    RollTrimByProductDesign::updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'product_design_id' => $design->id,
                            'flute_id' => $flute->id,
                        ],
                        [
                            'is_composite' => rand(0, 1) === 1,
                            'min_trim' => 20,
                            'max_trim' => 65
                        ]
                    );
                    $count++;
                }
            }
        }
        
        $this->command->info("Created $count roll trim by product design records.");
    }
} 