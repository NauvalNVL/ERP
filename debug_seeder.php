<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\MasterCard;
use Illuminate\Support\Facades\DB;

echo "Debugging MasterCard Seeder...\n";

try {
    // Check current data
    $count = MasterCard::count();
    echo "Total MasterCard records: " . $count . "\n";
    
    if ($count > 0) {
        $sample = MasterCard::take(5)->get();
        echo "Sample records:\n";
        foreach ($sample as $mc) {
            echo "MC_SEQ: " . $mc->mc_seq . 
                 ", Customer: " . ($mc->customer_code ?? 'NULL') . 
                 ", Status: " . $mc->status . 
                 ", Model: " . $mc->mc_model . "\n";
        }
    }
    
    // Test the seeder logic manually
    echo "\nTesting seeder logic manually...\n";
    
    $customers = [
        '000211-08', // ABDULLAH, BPK
        '000680-06', // ACEP SUNANDAR, BPK
        '000585-01', // ACHMAD JAMAL
        '000283',    // ACOSTA SUPER FOOD, PT
        '000903'     // ADHITYA SERAYAKORITA, PT
    ];

    $masterCards = [
        ['mc_seq' => '1609138', 'customer_code' => $customers[0], 'mc_model' => 'BOX BASO 4.5 KG', 'status' => 'Active'],
        ['mc_seq' => '1609144', 'customer_code' => $customers[0], 'mc_model' => 'BOX IKAN HARIMAU 4.5 KG', 'status' => 'Active'],
        ['mc_seq' => '1609145', 'customer_code' => $customers[1], 'mc_model' => 'BOX SRIKAYA 4.5 KG', 'status' => 'Active'],
        ['mc_seq' => '1609162', 'customer_code' => $customers[1], 'mc_model' => 'BIHUN FANIA 5 KG', 'status' => 'Active'],
        ['mc_seq' => '1609163', 'customer_code' => $customers[1], 'mc_model' => 'BIHUN IKAN TUNA 4.5 KG BARU', 'status' => 'Active'],
        ['mc_seq' => '1609164', 'customer_code' => $customers[2], 'mc_model' => 'BIHUN IKAN TUNA 5 KG BARU', 'status' => 'Active'],
        ['mc_seq' => '1609166', 'customer_code' => $customers[2], 'mc_model' => 'BIHUN PIRING MAS 5 KG', 'status' => 'Active'],
        ['mc_seq' => '1609173', 'customer_code' => $customers[3], 'mc_model' => 'BOX JAGUNG SRIKAYA 5 KG', 'status' => 'Active'],
        ['mc_seq' => '1609181', 'customer_code' => $customers[3], 'mc_model' => 'BIHUN POHON KOPI 5 KG', 'status' => 'Active'],
        ['mc_seq' => '1609182', 'customer_code' => $customers[4], 'mc_model' => 'BIHUN DELLIS 5 KG', 'status' => 'Active'],
        ['mc_seq' => '1609185', 'customer_code' => $customers[4], 'mc_model' => 'POLOS UK 506 X 356 X 407', 'status' => 'Active'],
        ['mc_seq' => '1609186', 'customer_code' => $customers[4], 'mc_model' => 'POLOS 480 X 410 X 401', 'status' => 'Active'],
    ];

    echo "Attempting to update records...\n";
    
    foreach ($masterCards as $data) {
        echo "Updating MC_SEQ: " . $data['mc_seq'] . " with customer: " . $data['customer_code'] . "\n";
        
        try {
            $result = MasterCard::updateOrCreate(
                ['mc_seq' => $data['mc_seq']],
                $data
            );
            
            echo "  - Result: " . ($result->customer_code ?? 'NULL') . "\n";
            
        } catch (Exception $e) {
            echo "  - Error: " . $e->getMessage() . "\n";
        }
    }
    
    // Check results
    echo "\nChecking results after update...\n";
    $abdullahMc = MasterCard::where('customer_code', '000211-08')->get();
    echo "Abdullah BPK (000211-08) MasterCards: " . $abdullahMc->count() . "\n";
    foreach ($abdullahMc as $mc) {
        echo "  - " . $mc->mc_seq . " (" . $mc->mc_model . ")\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
