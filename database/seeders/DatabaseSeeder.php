<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        DB::table('system_configurations')->insert([
            'business_name' => 'Default Business',
            'registration_number' => '123456789',
            'cps_version' => '1.0.0',
            'display_registration' => 1,
            'logo_appear_time' => 5,
            'date_validity_start' => now()->format('Y-m-d'),
            'date_validity_end' => now()->addYear()->format('Y-m-d'),
            'turbo_loop_engine' => '100',
            'financial_system_code' => '01',
            'period_end_closing' => 'X-Check & Block',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
