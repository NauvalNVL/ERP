<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\MasterCard;

echo "=== MasterCard Debug ===\n";

// Check total count
echo "Total MasterCard count: " . MasterCard::count() . "\n";

// Show sample records to see the actual data
echo "\nSample MasterCard records:\n";
$sampleMCs = MasterCard::take(3)->get();
foreach ($sampleMCs as $mc) {
    echo "- Seq: {$mc->mc_seq}, Model: {$mc->mc_model}, Status: {$mc->status}, Customer Code: '{$mc->customer_code}'\n";
}

// Check specific customer
$customerCode = '000211-08';
$mcs = MasterCard::where('customer_code', $customerCode)->get();

echo "\nMasterCard with customer_code {$customerCode}: " . $mcs->count() . "\n";

if ($mcs->count() > 0) {
    echo "Sample data:\n";
    foreach ($mcs->take(3) as $mc) {
        echo "- Seq: {$mc->mc_seq}, Model: {$mc->mc_model}, Status: {$mc->status}\n";
    }
} else {
    echo "No master cards found for customer {$customerCode}\n";
    
    // Show all unique customer codes
    echo "\nAvailable customer codes:\n";
    $allCustomers = MasterCard::distinct()->pluck('customer_code');
    foreach ($allCustomers as $code) {
        $count = MasterCard::where('customer_code', $code)->count();
        echo "- '{$code}': {$count} master card(s)\n";
    }
}

echo "\n=== End Debug ===\n";
