<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CustomerSalesType;
use App\Models\Customer;
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

        // Get some customers from CUSTOMER table to associate with sales types
        $customers = Customer::limit(20)->get();

        if ($customers->isEmpty()) {
            // If no customers exist, stop here gracefully
            return;
        }

        // Sample sales types based on the screenshot
        $salesTypes = ['LC', 'EX']; // LC = Local, EX = Export

        // Create sample customer sales types
        foreach ($customers as $customer) {
            CustomerSalesType::create([
                'customer_code' => $customer->CODE,
                'customer_name' => $customer->NAME,
                'sales_type' => $salesTypes[array_rand($salesTypes)],
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);
        }
    }
}
