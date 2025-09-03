<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\MasterCard;
use App\Models\UpdateCustomerAccount;

echo "=== Database Check ===\n";

// Check MasterCard data
echo "MasterCard count: " . MasterCard::count() . "\n";

// Check specific customer
$customerCode = '000211-08';
echo "MasterCard with customer_code {$customerCode}: " . MasterCard::where('customer_code', $customerCode)->count() . "\n";

// Show sample master cards
echo "\nSample MasterCards:\n";
$sampleMCs = MasterCard::take(3)->get();
foreach ($sampleMCs as $mc) {
    echo "- Seq: {$mc->seq}, Customer: {$mc->customer_code}, Model: {$mc->model}\n";
}

// Check customer account
echo "\nCustomer Account {$customerCode} exists: " . (UpdateCustomerAccount::where('customer_code', $customerCode)->exists() ? 'Yes' : 'No') . "\n";

echo "\n=== End Check ===\n";
