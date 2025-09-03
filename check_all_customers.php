<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\MasterCard;

echo "Checking all customers and their master cards...\n";

try {
    $customers = [
        '000211-08', // ABDULLAH, BPK
        '000680-06', // ACEP SUNANDAR, BPK
        '000585-01', // ACHMAD JAMAL
        '000283',    // ACOSTA SUPER FOOD, PT
        '000903'     // ADHITYA SERAYAKORITA, PT
    ];

    foreach ($customers as $customerCode) {
        $masterCards = MasterCard::where('customer_code', $customerCode)->get();
        echo "\nCustomer $customerCode: " . $masterCards->count() . " master cards\n";
        
        foreach ($masterCards as $mc) {
            echo "  - " . $mc->mc_seq . " (" . $mc->mc_model . ") - Status: " . $mc->status . "\n";
        }
    }
    
    // Check for any remaining NULL customer_code
    $nullCustomerMc = MasterCard::whereNull('customer_code')->get();
    echo "\nMaster Cards with NULL customer_code: " . $nullCustomerMc->count() . "\n";
    
    if ($nullCustomerMc->count() > 0) {
        foreach ($nullCustomerMc as $mc) {
            echo "  - " . $mc->mc_seq . " (" . $mc->mc_model . ")\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

