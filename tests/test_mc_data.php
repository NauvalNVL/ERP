<?php

// Test script to verify MC data lookup
// Run this from command line: php test_mc_data.php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Testing MC Data Lookup ===\n\n";

// Test 1: Check if CUSTOMER table has data with CURRENCY
echo "1. Checking CUSTOMER table:\n";
$customers = DB::table('CUSTOMER')->select('CODE', 'NAME', 'CURRENCY')->limit(5)->get();
if ($customers->count() > 0) {
    echo "   ✓ Found " . $customers->count() . " customers\n";
    foreach ($customers as $customer) {
        echo "   - Code: {$customer->CODE}, Name: {$customer->NAME}, Currency: " . ($customer->CURRENCY ?? 'NULL') . "\n";
    }
} else {
    echo "   ✗ No customers found\n";
}

echo "\n";

// Test 2: Check if product_designs table has data
echo "2. Checking product_designs table:\n";
$productDesigns = DB::table('product_designs')->select('pd_code', 'pd_name', 'product')->limit(5)->get();
if ($productDesigns->count() > 0) {
    echo "   ✓ Found " . $productDesigns->count() . " product designs\n";
    foreach ($productDesigns as $pd) {
        echo "   - PD Code: {$pd->pd_code}, Name: {$pd->pd_name}, Product: " . ($pd->product ?? 'NULL') . "\n";
    }
} else {
    echo "   ✗ No product designs found\n";
}

echo "\n";

// Test 3: Check if products table has data with unit
echo "3. Checking products table:\n";
$products = DB::table('products')->select('product_code', 'description', 'unit')->limit(5)->get();
if ($products->count() > 0) {
    echo "   ✓ Found " . $products->count() . " products\n";
    foreach ($products as $product) {
        echo "   - Product Code: {$product->product_code}, Description: {$product->description}, Unit: " . ($product->unit ?? 'NULL') . "\n";
    }
} else {
    echo "   ✗ No products found\n";
}

echo "\n";

// Test 4: Test the full lookup chain
echo "4. Testing full lookup chain:\n";
if ($productDesigns->count() > 0) {
    $testPd = $productDesigns->first();
    echo "   Using PD Code: {$testPd->pd_code}\n";
    
    if ($testPd->product) {
        $product = DB::table('products')
            ->where('product_code', $testPd->product)
            ->first();
        
        if ($product) {
            echo "   ✓ Product found: {$product->product_code}\n";
            echo "   ✓ Unit: " . ($product->unit ?? 'NULL') . "\n";
        } else {
            echo "   ✗ Product not found for code: {$testPd->product}\n";
        }
    } else {
        echo "   ✗ Product design has no product code\n";
    }
}

echo "\n";

// Test 5: Check MC table structure
echo "5. Checking MC table structure:\n";
try {
    $columns = DB::select("SHOW COLUMNS FROM MC WHERE Field IN ('UNIT', 'CURRENCY', 'TOTAL_COLOR')");
    if (count($columns) > 0) {
        echo "   ✓ MC table has the required columns:\n";
        foreach ($columns as $col) {
            echo "   - {$col->Field} ({$col->Type})\n";
        }
    } else {
        echo "   ✗ MC table missing UNIT, CURRENCY, or TOTAL_COLOR columns\n";
    }
} catch (\Exception $e) {
    echo "   ✗ Error checking MC table: " . $e->getMessage() . "\n";
}

echo "\n=== Test Complete ===\n";
