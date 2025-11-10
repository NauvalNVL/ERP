<?php

// Check all product designs in database
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Checking All Product Designs ===\n\n";

// Count total records
$total = DB::table('product_designs')->count();
echo "Total Product Designs: {$total}\n\n";

if ($total == 0) {
    echo "⚠️  WARNING: product_designs table is EMPTY!\n";
    echo "You need to add product design data first.\n\n";
} else {
    // Show first 20 records
    echo "First 20 Product Designs:\n";
    echo str_repeat("-", 80) . "\n";
    printf("%-15s %-30s %-15s %-10s\n", "PD Code", "PD Name", "Product", "Unit");
    echo str_repeat("-", 80) . "\n";
    
    $designs = DB::table('product_designs')
        ->select('pd_code', 'pd_name', 'product')
        ->limit(20)
        ->get();
    
    foreach ($designs as $pd) {
        $unit = 'N/A';
        if ($pd->product) {
            $product = DB::table('products')
                ->where('product_code', $pd->product)
                ->first();
            if ($product && $product->unit) {
                $unit = $product->unit;
            }
        }
        
        printf("%-15s %-30s %-15s %-10s\n", 
            $pd->pd_code, 
            substr($pd->pd_name, 0, 30), 
            $pd->product ?? 'NULL',
            $unit
        );
    }
    
    echo str_repeat("-", 80) . "\n\n";
    
    // Group by first character
    echo "Product Designs grouped by first character:\n";
    $grouped = DB::select("
        SELECT 
            SUBSTRING(pd_code, 1, 1) as first_char,
            COUNT(*) as count
        FROM product_designs
        GROUP BY SUBSTRING(pd_code, 1, 1)
        ORDER BY first_char
    ");
    
    foreach ($grouped as $group) {
        echo "  {$group->first_char}: {$group->count} designs\n";
    }
    
    echo "\n";
    
    // Check if there are any designs with product and unit
    echo "Product Designs with valid product and unit:\n";
    $validDesigns = DB::table('product_designs as pd')
        ->join('products as p', 'pd.product', '=', 'p.product_code')
        ->whereNotNull('p.unit')
        ->select('pd.pd_code', 'pd.pd_name', 'pd.product', 'p.unit')
        ->limit(10)
        ->get();
    
    if ($validDesigns->count() > 0) {
        echo "  ✓ Found {$validDesigns->count()} designs with valid unit:\n";
        foreach ($validDesigns as $vd) {
            echo "    - {$vd->pd_code} → Product: {$vd->product}, Unit: {$vd->unit}\n";
        }
    } else {
        echo "  ⚠️  No product designs have valid product with unit!\n";
        echo "  This means even if P_DESIGN is found, UNIT will be NULL.\n";
    }
}

echo "\n=== Check Complete ===\n";
