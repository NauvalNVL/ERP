<?php

// Check B0 data in database
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Checking B0 Product Design ===\n\n";

// Check exact match
echo "1. Searching for exact match 'B0':\n";
$exact = DB::table('product_designs')->where('pd_code', 'B0')->first();
if ($exact) {
    echo "   ✓ Found: {$exact->pd_code} - {$exact->pd_name}\n";
    echo "   Product: " . ($exact->product ?? 'NULL') . "\n";
} else {
    echo "   ✗ Not found\n";
}

echo "\n2. Searching for case-insensitive 'B0':\n";
$caseInsensitive = DB::table('product_designs')->whereRaw('UPPER(pd_code) = ?', ['B0'])->first();
if ($caseInsensitive) {
    echo "   ✓ Found: {$caseInsensitive->pd_code} - {$caseInsensitive->pd_name}\n";
    echo "   Product: " . ($caseInsensitive->product ?? 'NULL') . "\n";
} else {
    echo "   ✗ Not found\n";
}

echo "\n3. Searching for similar codes starting with 'B':\n";
$similar = DB::table('product_designs')
    ->where('pd_code', 'LIKE', 'B%')
    ->limit(20)
    ->get();

if ($similar->count() > 0) {
    echo "   Found {$similar->count()} similar codes:\n";
    foreach ($similar as $pd) {
        $productInfo = '';
        if ($pd->product) {
            $product = DB::table('products')->where('product_code', $pd->product)->first();
            $productInfo = $product ? " → Product: {$pd->product}, Unit: " . ($product->unit ?? 'NULL') : " → Product: {$pd->product} (not found in products table)";
        }
        echo "   - {$pd->pd_code} ({$pd->pd_name}){$productInfo}\n";
    }
} else {
    echo "   ✗ No similar codes found\n";
}

echo "\n4. Checking if 'BO' (letter O) exists:\n";
$bo = DB::table('product_designs')->where('pd_code', 'BO')->first();
if ($bo) {
    echo "   ✓ Found: {$bo->pd_code} - {$bo->pd_name}\n";
    echo "   Product: " . ($bo->product ?? 'NULL') . "\n";
    if ($bo->product) {
        $product = DB::table('products')->where('product_code', $bo->product)->first();
        if ($product) {
            echo "   Product Details: {$product->description}\n";
            echo "   Unit: " . ($product->unit ?? 'NULL') . "\n";
        }
    }
} else {
    echo "   ✗ Not found\n";
}

echo "\n5. Checking if 'BO/BO' exists:\n";
$bobo = DB::table('product_designs')->where('pd_code', 'BO/BO')->first();
if ($bobo) {
    echo "   ✓ Found: {$bobo->pd_code} - {$bobo->pd_name}\n";
    echo "   Product: " . ($bobo->product ?? 'NULL') . "\n";
    if ($bobo->product) {
        $product = DB::table('products')->where('product_code', $bobo->product)->first();
        if ($product) {
            echo "   Product Details: {$product->description}\n";
            echo "   Unit: " . ($product->unit ?? 'NULL') . "\n";
        }
    }
} else {
    echo "   ✗ Not found\n";
}

echo "\n=== Check Complete ===\n";
