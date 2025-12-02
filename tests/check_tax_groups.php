<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TaxGroup;

echo "=== TAX GROUP DATABASE CHECK ===\n\n";

$count = TaxGroup::count();
echo "📊 Total Tax Groups: {$count}\n\n";

if ($count > 0) {
    echo "Tax Groups in Database:\n";
    echo str_repeat("-", 60) . "\n";
    $groups = TaxGroup::orderBy('code')->get();
    foreach ($groups as $group) {
        echo sprintf(
            "%-15s | %-30s | Tax: %s\n",
            $group->code,
            $group->name,
            $group->sales_tax_applied
        );
    }
    echo str_repeat("-", 60) . "\n";
} else {
    echo "❌ No tax groups found in database!\n";
}

echo "\n=== API TEST ===\n";
echo "Testing endpoint: /api/invoices/tax-groups\n\n";

// Simulate API call
$controller = new \App\Http\Controllers\Invoice\TaxGroupController();
$response = $controller->index();
$content = $response->getContent();
$data = json_decode($content, true);

echo "API Response:\n";
echo "Success: " . ($data['success'] ? 'Yes' : 'No') . "\n";
echo "Data Count: " . (isset($data['data']) ? count($data['data']) : 0) . "\n";

if (isset($data['data']) && count($data['data']) > 0) {
    echo "\n✅ API returning data correctly!\n";
    foreach ($data['data'] as $item) {
        echo "  - {$item['code']}: {$item['name']}\n";
    }
} else {
    echo "\n❌ API not returning data!\n";
}

echo "\n";
