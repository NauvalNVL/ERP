<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "Checking master_cards table structure...\n";

try {
    // Check if table exists
    if (Schema::hasTable('master_cards')) {
        echo "Table 'master_cards' exists.\n";
        
        // Get table columns
        $columns = Schema::getColumnListing('master_cards');
        echo "Columns: " . implode(', ', $columns) . "\n";
        
        // Check if customer_code column exists
        if (in_array('customer_code', $columns)) {
            echo "Column 'customer_code' exists.\n";
            
            // Check column details
            $columnDetails = DB::select("PRAGMA table_info(master_cards)");
            foreach ($columnDetails as $col) {
                if ($col->name === 'customer_code') {
                    echo "customer_code column details:\n";
                    echo "  - Type: " . $col->type . "\n";
                    echo "  - Not Null: " . ($col->notnull ? 'Yes' : 'No') . "\n";
                    echo "  - Default: " . ($col->dflt_value ?? 'NULL') . "\n";
                    break;
                }
            }
        } else {
            echo "Column 'customer_code' does NOT exist.\n";
        }
        
        // Check sample data
        $sampleData = DB::table('master_cards')->select('mc_seq', 'customer_code', 'mc_model', 'status')->take(3)->get();
        echo "\nSample data:\n";
        foreach ($sampleData as $row) {
            echo "MC_SEQ: " . $row->mc_seq . 
                 ", Customer: " . ($row->customer_code ?? 'NULL') . 
                 ", Status: " . $row->status . 
                 ", Model: " . $row->mc_model . "\n";
        }
        
    } else {
        echo "Table 'master_cards' does not exist.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

