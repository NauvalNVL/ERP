<?php
// Test Chemical Coat API
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing Chemical Coat API...\n\n";

// Test 1: Check if table exists
echo "1. Checking if table exists...\n";
$tableExists = Schema::hasTable('chemical_coats');
echo "   Table exists: " . ($tableExists ? "YES" : "NO") . "\n\n";

// Test 2: Count records
echo "2. Counting records...\n";
$count = App\Models\ChemicalCoat::count();
echo "   Total records: $count\n\n";

// Test 3: Get all records
echo "3. Fetching all records...\n";
$coats = App\Models\ChemicalCoat::orderBy('code')->get();
foreach ($coats as $coat) {
    echo "   - {$coat->code}: {$coat->name}\n";
}
echo "\n";

// Test 4: Test API response format
echo "4. Testing API response format...\n";
$response = $coats->toArray();
echo "   Response is array: " . (is_array($response) ? "YES" : "NO") . "\n";
echo "   Response count: " . count($response) . "\n";
if (count($response) > 0) {
    echo "   First item keys: " . implode(', ', array_keys($response[0])) . "\n";
}
