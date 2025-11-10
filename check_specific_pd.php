<?php

// Check specific product design and its product
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Get P_DESIGN code from command line argument
$pdCode = $argv[1] ?? null;

if (!$pdCode) {
    echo "Usage: php check_specific_pd.php <PD_CODE>\n";
    echo "Example: php check_specific_pd.php \"B1\"\n";
    exit(1);
}

echo "=== Checking Product Design: {$pdCode} ===\n\n";

// Check product design
$pd = DB::table('product_designs')
    ->where('pd_code', $pdCode)
    ->first();

if (!$pd) {
    echo "❌ Product Design '{$pdCode}' NOT FOUND\n\n";
    
    // Try case-insensitive
    $pdCaseInsensitive = DB::table('product_designs')
        ->whereRaw('UPPER(pd_code) = ?', [strtoupper($pdCode)])
        ->first();
    
    if ($pdCaseInsensitive) {
        echo "✓ Found with case-insensitive search: {$pdCaseInsensitive->pd_code}\n";
        $pd = $pdCaseInsensitive;
    } else {
        // Show similar codes
        $similar = DB::table('product_designs')
            ->where('pd_code', 'LIKE', '%' . substr($pdCode, 0, 2) . '%')
            ->limit(10)
            ->pluck('pd_code')
            ->toArray();
        
        if (!empty($similar)) {
            echo "Similar codes found:\n";
            foreach ($similar as $code) {
                echo "  - {$code}\n";
            }
        }
        exit(1);
    }
}

echo "✓ Product Design Found:\n";
echo "  PD Code: {$pd->pd_code}\n";
echo "  PD Name: {$pd->pd_name}\n";
echo "  Product: " . ($pd->product ?? 'NULL') . "\n";
echo "  IDC: " . ($pd->idc ?? 'NULL') . "\n";
echo "  Design Type: " . ($pd->pd_design_type ?? 'NULL') . "\n";
echo "\n";

if (!$pd->product) {
    echo "❌ Product Design has NO product code!\n";
    echo "This P_DESIGN cannot be used to fetch UNIT.\n";
    exit(1);
}

// Check product
echo "Checking Product: {$pd->product}\n";
$product = DB::table('products')
    ->where('product_code', $pd->product)
    ->first();

if (!$product) {
    echo "❌ Product '{$pd->product}' NOT FOUND in products table!\n";
    echo "This is a data integrity issue.\n";
    exit(1);
}

echo "✓ Product Found:\n";
echo "  Product Code: {$product->product_code}\n";
echo "  Description: {$product->description}\n";
echo "  Category: " . ($product->category ?? 'NULL') . "\n";
echo "  Unit (raw): " . ($product->unit ?? 'NULL') . "\n";

if ($product->unit) {
    $unitTrimmed = trim($product->unit);
    echo "  Unit (trimmed): {$unitTrimmed}\n";
    echo "  Unit Length: " . strlen($product->unit) . " chars\n";
    echo "  Unit Trimmed Length: " . strlen($unitTrimmed) . " chars\n";
    echo "  Unit is empty: " . (empty($unitTrimmed) ? 'YES' : 'NO') . "\n";
    
    // Show character breakdown for first 50 chars
    if (strlen($product->unit) > 0) {
        echo "  Unit Characters (first 50): ";
        $chars = str_split(substr($product->unit, 0, 50));
        foreach ($chars as $char) {
            if ($char === ' ') {
                echo '[SPACE]';
            } elseif ($char === "\n") {
                echo '[NEWLINE]';
            } elseif ($char === "\r") {
                echo '[CR]';
            } elseif ($char === "\t") {
                echo '[TAB]';
            } else {
                echo $char;
            }
        }
        echo "\n";
    }
} else {
    echo "❌ Product has NO unit field!\n";
    echo "UNIT cannot be saved for this P_DESIGN.\n";
}

echo "\n=== Summary ===\n";
if ($pd && $product && $product->unit && !empty(trim($product->unit))) {
    echo "✅ P_DESIGN '{$pdCode}' is VALID and can fetch UNIT\n";
    echo "   UNIT value: " . trim($product->unit) . "\n";
} else {
    echo "❌ P_DESIGN '{$pdCode}' CANNOT fetch UNIT\n";
    if (!$pd) echo "   Reason: P_DESIGN not found\n";
    elseif (!$pd->product) echo "   Reason: P_DESIGN has no product code\n";
    elseif (!$product) echo "   Reason: Product not found\n";
    elseif (!$product->unit) echo "   Reason: Product has no unit\n";
    elseif (empty(trim($product->unit))) echo "   Reason: Product unit is empty\n";
}

echo "\n";
