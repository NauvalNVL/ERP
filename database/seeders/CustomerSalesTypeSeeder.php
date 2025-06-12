<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CustomerSalesType;
use App\Models\UpdateCustomerAccount;
use App\Models\User;

class CustomerSalesTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a default user for created_by and updated_by
        $user = User::first() ?? User::factory()->create();
        
        // Get some customers to associate with sales types
        $customers = UpdateCustomerAccount::limit(20)->get();
        
        if ($customers->isEmpty()) {
            // If no customers exist, create a default one
            $customer = UpdateCustomerAccount::create([
                'customer_code' => 'CUST001',
                'customer_name' => 'Default Customer',
                'address' => 'Default Address',
                'created_by' => $user->id,
                'updated_by' => $user->id,
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
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);
        }
    }
}
