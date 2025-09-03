<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\MasterCard;

echo "=== MasterCard Debug ===\n";

// Check total count
echo "Total MasterCard count: " . MasterCard::count() . "\n";

// Check specific customer
$customerCode = '000211-08';
$mcs = MasterCard::where('customer_code', $customerCode)->get();

echo "MasterCard with customer_code {$customerCode}: " . $mcs->count() . "\n";

if ($mcs->count() > 0) {
    echo "Sample data:\n";
    foreach ($mcs->take(3) as $mc) {
        echo "- Seq: {$mc->mc_seq}, Model: {$mc->mc_model}, Status: {$mc->status}\n";
    }
} else {
    echo "No master cards found for customer {$customerCode}\n";
    
    // Show all unique customer codes
    echo "\nAvailable customer codes:\n";
    $allCustomers = MasterCard::distinct()->pluck('customer_code')->take(10);
    foreach ($allCustomers as $code) {
        echo "- {$code}\n";
    }
}

echo "\n=== End Debug ===\n";
