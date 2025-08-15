<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Run the seeder
$seeder = new \Database\Seeders\UpdateMcSeeder();
$seeder->run();

echo "Seeder completed successfully!\n"; 