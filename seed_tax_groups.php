<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TaxGroup;

echo "🌱 Seeding Tax Groups...\n\n";

$sampleGroups = [
    ['code' => 'NIL', 'name' => 'NDH PPN', 'sales_tax_applied' => 'N'],
    ['code' => 'PPN', 'name' => 'PPN 10%', 'sales_tax_applied' => 'Y'],
    ['code' => 'PPN BRKT', 'name' => 'PPN KEL KWSN BERIKAT', 'sales_tax_applied' => 'Y'],
    ['code' => 'PPN11', 'name' => 'PPN 11%', 'sales_tax_applied' => 'Y'],
    ['code' => 'PPN12', 'name' => 'PPN 12%', 'sales_tax_applied' => 'Y'],
];

$created = 0;
$skipped = 0;

foreach ($sampleGroups as $group) {
    $exists = TaxGroup::where('code', $group['code'])->exists();
    if (!$exists) {
        TaxGroup::create($group);
        echo "✅ Created: {$group['code']} - {$group['name']}\n";
        $created++;
    } else {
        echo "⏭️  Skipped: {$group['code']} (already exists)\n";
        $skipped++;
    }
}

echo "\n📊 Summary:\n";
echo "   Created: {$created}\n";
echo "   Skipped: {$skipped}\n";
echo "   Total: " . TaxGroup::count() . "\n\n";

echo "🎉 Done! Tax groups in database:\n";
$allGroups = TaxGroup::orderBy('code')->get();
foreach ($allGroups as $group) {
    echo "   - {$group->code}: {$group->name} (Tax: {$group->sales_tax_applied})\n";
}

echo "\n";
