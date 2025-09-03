<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\MasterCard;
use Illuminate\Support\Facades\DB;

echo "Debugging MasterCard Model...\n";

try {
    // Check model fillable fields
    $model = new MasterCard();
    echo "Fillable fields: " . implode(', ', $model->getFillable()) . "\n";
    
    // Check if customer_code is fillable
    if (in_array('customer_code', $model->getFillable())) {
        echo "customer_code is fillable ✓\n";
    } else {
        echo "customer_code is NOT fillable ✗\n";
    }
    
    // Test direct update
    echo "\nTesting direct update...\n";
    
    $testMc = MasterCard::where('mc_seq', '1609138')->first();
    if ($testMc) {
        echo "Before update - MC_SEQ: " . $testMc->mc_seq . ", Customer: " . ($testMc->customer_code ?? 'NULL') . "\n";
        
        // Try direct update
        $testMc->customer_code = '000211-08';
        $testMc->save();
        
        // Refresh from database
        $testMc->refresh();
        echo "After update - MC_SEQ: " . $testMc->mc_seq . ", Customer: " . ($testMc->customer_code ?? 'NULL') . "\n";
        
        // Check if it was actually saved
        $freshMc = MasterCard::where('mc_seq', '1609138')->first();
        echo "Fresh from DB - MC_SEQ: " . $freshMc->mc_seq . ", Customer: " . ($freshMc->customer_code ?? 'NULL') . "\n";
    }
    
    // Test updateOrCreate again
    echo "\nTesting updateOrCreate again...\n";
    
    $result = MasterCard::updateOrCreate(
        ['mc_seq' => '1609144'],
        [
            'mc_seq' => '1609144',
            'customer_code' => '000211-08',
            'mc_model' => 'BOX IKAN HARIMAU 4.5 KG',
            'status' => 'Active'
        ]
    );
    
    echo "updateOrCreate result - Customer: " . ($result->customer_code ?? 'NULL') . "\n";
    
    // Check if it was actually saved
    $freshMc2 = MasterCard::where('mc_seq', '1609144')->first();
    echo "Fresh from DB after updateOrCreate - Customer: " . ($freshMc2->customer_code ?? 'NULL') . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

