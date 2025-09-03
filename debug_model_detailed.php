<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\MasterCard;
use Illuminate\Support\Facades\DB;

echo "Detailed Debugging MasterCard Model...\n";

try {
    // Check model fillable fields
    $model = new MasterCard();
    $fillable = $model->getFillable();
    echo "Fillable fields count: " . count($fillable) . "\n";
    echo "Fillable fields: " . implode(', ', $fillable) . "\n";
    
    // Check if customer_code is fillable
    if (in_array('customer_code', $fillable)) {
        echo "customer_code is fillable ✓\n";
    } else {
        echo "customer_code is NOT fillable ✗\n";
    }
    
    // Check model attributes
    echo "\nModel attributes:\n";
    $attributes = $model->getAttributes();
    foreach ($attributes as $key => $value) {
        echo "  $key => " . ($value ?? 'NULL') . "\n";
    }
    
    // Check table columns
    echo "\nTable columns from Schema:\n";
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('master_cards');
    foreach ($columns as $column) {
        echo "  $column\n";
    }
    
    // Test with raw SQL update
    echo "\nTesting raw SQL update...\n";
    
    $affected = DB::table('master_cards')
        ->where('mc_seq', '1609144')
        ->update(['customer_code' => '000211-08']);
    
    echo "Raw SQL update affected rows: $affected\n";
    
    // Check result
    $testMc = MasterCard::where('mc_seq', '1609144')->first();
    echo "After raw SQL update - Customer: " . ($testMc->customer_code ?? 'NULL') . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

