<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Http\Controllers\UpdateMcController;
use Illuminate\Http\Request;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Testing API Controller Directly ===\n\n";

try {
    $controller = new UpdateMcController();
    
    // Create mock request
    $request = new Request([
        'customer_code' => '000211-08',
        'status' => ['Act'],
        'sortBy' => 'mc_seq',
        'sortOrder' => 'asc'
    ]);
    
    $response = $controller->apiIndex($request);
    $data = json_decode($response->getContent(), true);
    
    echo "API Response:\n";
    echo "- Total records: " . $data['total'] . "\n";
    echo "- Current page: " . $data['current_page'] . "\n";
    echo "- Master Cards found:\n";
    
    if (!empty($data['data'])) {
        foreach ($data['data'] as $mc) {
            echo "  * MC: {$mc['seq']}, Model: {$mc['model']}, Customer: {$mc['customer_code']}\n";
        }
    } else {
        echo "  No data returned\n";
    }
    
    echo "\nDebug - Raw data structure:\n";
    print_r($data);
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== Test Complete ===\n";
