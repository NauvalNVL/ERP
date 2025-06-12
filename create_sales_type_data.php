<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get or create a user
$user = \App\Models\User::first();
if (!$user) {
    $user = \App\Models\User::create([
        'name' => 'Admin',
        'username' => 'admin',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
    ]);
}

try {
    // Create a customer account with all required fields
    $customer = \App\Models\UpdateCustomerAccount::create([
        'customer_code' => 'CUST001',
        'customer_name' => 'Test Customer',
        'address' => 'Test Address',
        'ac_type' => 'LC', // Adding the required ac_type field
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);

    // Create sales type records
    $salesTypes = ['LC', 'EX'];
    foreach ($salesTypes as $type) {
        \App\Models\CustomerSalesType::create([
            'customer_code' => $customer->customer_code,
            'customer_name' => $customer->customer_name,
            'sales_type' => $type,
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);
        
        echo "Created sales type {$type} for customer {$customer->customer_name}\n";
    }

    echo "Data creation completed successfully.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 