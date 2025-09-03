<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterCard;
use App\Models\UpdateCustomerAccount;

class UpdateMasterCardCustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get some customers to assign to Master Cards
        $customers = UpdateCustomerAccount::take(5)->get();
        
        if ($customers->isEmpty()) {
            $this->command->error('No customers found. Please run UpdateCustomerAccountSeeder first.');
            return;
        }
        
        // Get all Master Cards
        $masterCards = MasterCard::all();
        
        if ($masterCards->isEmpty()) {
            $this->command->error('No Master Cards found. Please run MasterCardSeeder first.');
            return;
        }
        
        $this->command->info('Updating Master Cards with customer codes...');
        
        // Assign customers to Master Cards in a round-robin fashion
        $customerIndex = 0;
        foreach ($masterCards as $masterCard) {
            $customer = $customers[$customerIndex % $customers->count()];
            
            $masterCard->update([
                'customer_code' => $customer->customer_code
            ]);
            
            $this->command->info("Assigned Master Card {$masterCard->mc_seq} ({$masterCard->mc_model}) to Customer {$customer->customer_code} ({$customer->customer_name})");
            
            $customerIndex++;
        }
        
        $this->command->info('Successfully updated ' . $masterCards->count() . ' Master Cards with customer codes.');
    }
}
