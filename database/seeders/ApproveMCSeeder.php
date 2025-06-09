<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ApproveMC;
use Illuminate\Support\Facades\DB;

class ApproveMCSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear existing records to avoid duplicates
        DB::table('approve_mcs')->truncate();

        // Add records one by one to handle nullable fields properly
        ApproveMC::create([
            'mc_seq' => 'MC001',
            'mc_model' => 'Box-Standard',
            'customer_code' => 'CUST0001',
            'customer_name' => 'PT. Indah Karya',
            'status' => 'pending',
        ]);

        ApproveMC::create([
            'mc_seq' => 'MC002',
            'mc_model' => 'Box-Premium',
            'customer_code' => 'CUST0002',
            'customer_name' => 'PT. Maju Bersama',
            'status' => 'active',
            'approved_by' => 'ADMIN',
            'approved_date' => now()->subDays(5),
        ]);

        ApproveMC::create([
            'mc_seq' => 'MC003',
            'mc_model' => 'Container-Small',
            'customer_code' => 'CUST0003',
            'customer_name' => 'CV. Berkah Jaya',
            'status' => 'obsolete',
            'rejected_by' => 'ADMIN',
            'rejected_date' => now()->subDays(2),
            'rejection_reason' => 'Incorrect specifications provided',
        ]);

        ApproveMC::create([
            'mc_seq' => 'MC004',
            'mc_model' => 'Container-Medium',
            'customer_code' => 'CUST0004',
            'customer_name' => 'UD. Sukses Mandiri',
            'status' => 'pending',
        ]);

        ApproveMC::create([
            'mc_seq' => 'MC005',
            'mc_model' => 'Box-Large',
            'customer_code' => 'CUST0005',
            'customer_name' => 'PT. Sejahtera Abadi',
            'status' => 'pending',
        ]);
    }
} 