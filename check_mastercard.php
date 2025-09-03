<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\MasterCard;

echo "Checking MasterCard table...\n";

try {
    $count = MasterCard::count();
    echo "Total MasterCard records: " . $count . "\n";
    
    if ($count > 0) {
        $sample = MasterCard::take(5)->get();
        echo "Sample records:\n";
        foreach ($sample as $mc) {
            echo "MC_SEQ: " . $mc->mc_seq . 
                 ", Customer: " . ($mc->customer_code ?? 'NULL') . 
                 ", Status: " . ($mc->status ?? 'NULL') . 
                 ", Model: " . ($mc->mc_model ?? 'NULL') . "\n";
        }
        
        // Check specific customer
        $abdullahMc = MasterCard::where('customer_code', '000211-08')->get();
        echo "\nAbdullah BPK (000211-08) MasterCards: " . $abdullahMc->count() . "\n";
        foreach ($abdullahMc as $mc) {
            echo "  - " . $mc->mc_seq . " (" . $mc->mc_model . ")\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

