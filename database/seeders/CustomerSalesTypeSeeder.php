<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CustomerSalesType;
use App\Models\UpdateCustomerAccount;
use App\Models\User;
use App\Models\UserCps;

class CustomerSalesTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a default user ID from USERCPS (legacy table with string ID)
        $defaultUser = UserCps::first();
        $userId = $defaultUser ? $defaultUser->ID : 'SYSTEM';

        // Get some customers to associate with sales types
        $customers = UpdateCustomerAccount::limit(20)->get();

        if ($customers->isEmpty()) {
            // If no customers exist, create a default one
            $customer = UpdateCustomerAccount::create([
                'customer_code' => 'CUST001',
                'customer_name' => 'Default Customer',
                'address' => 'Default Address',
                'ac_type' => 'Default', // Add a default value for ac_type
                'print_ar_aging' => 'Yes', // Add default value
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);
            $customers = collect([$customer]);
        }

        // Sample sales types based on the screenshot
        $salesTypes = ['LC', 'EX']; // LC = Local, EX = Export

        // Create sample customer sales types
        foreach ($customers as $customer) {
            CustomerSalesType::create([
                'customer_code' => $customer->customer_code,
                'customer_name' => $customer->customer_name,
                'sales_type' => $salesTypes[array_rand($salesTypes)],
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);
        }
    }
}
