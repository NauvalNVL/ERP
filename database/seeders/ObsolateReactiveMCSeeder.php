<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ObsolateReactiveMC;
use App\Models\UpdateCustomerAccount;
use App\Models\User;

class ObsolateReactiveMCSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a default user for created_by and updated_by
        $user = User::first() ?? User::factory()->create();
        
        // Get some customers to associate with master cards
        $customers = UpdateCustomerAccount::limit(5)->get();
        
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
        
        // Create sample master cards
        $masterCards = [
            [
                'mc_seq' => 'MC001',
                'mc_model' => 'Model A', 
                'description' => 'First master card',
                'status' => 'active',
            ],
            [
                'mc_seq' => 'MC002',
                'mc_model' => 'Model B',
                'description' => 'Second master card',
                'status' => 'active',
            ],
            [
                'mc_seq' => 'MC003',
                'mc_model' => 'Model C',
                'description' => 'Third master card',
                'status' => 'obsolete',
                'obsolate_date' => now()->subDays(5),
                'obsolate_by' => $user->id,
                'obsolate_reason' => 'Product discontinued',
            ],
            [
                'mc_seq' => 'MC004',
                'mc_model' => 'Model D',
                'description' => 'Fourth master card',
                'status' => 'active',
                'obsolate_date' => now()->subDays(10),
                'obsolate_by' => $user->id,
                'obsolate_reason' => 'Product discontinued',
                'reactive_date' => now()->subDays(2),
                'reactive_by' => $user->id,
                'reactive_reason' => 'Product reintroduced',
            ],
        ];
        
        foreach ($masterCards as $masterCard) {
            $customer = $customers->random();
            
            ObsolateReactiveMC::create([
                'mc_seq' => $masterCard['mc_seq'],
                'mc_model' => $masterCard['mc_model'],
                'customer_id' => $customer->id,
                'customer_name' => $customer->customer_name,
                'description' => $masterCard['description'],
                'status' => $masterCard['status'],
                'obsolate_date' => $masterCard['obsolate_date'] ?? null,
                'obsolate_by' => $masterCard['obsolate_by'] ?? null,
                'obsolate_reason' => $masterCard['obsolate_reason'] ?? null,
                'reactive_date' => $masterCard['reactive_date'] ?? null,
                'reactive_by' => $masterCard['reactive_by'] ?? null,
                'reactive_reason' => $masterCard['reactive_reason'] ?? null,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);
        }
    }
}
