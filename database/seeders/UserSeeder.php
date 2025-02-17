<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'user_id' => 'ADMIN001',
            'username' => 'admin',
            'official_name' => 'Administrator',
            'official_title' => 'System Admin',
            'mobile_number' => '08123456789', 
            'official_tel' => '0217654321',
            'password' => bcrypt('admin123'),
            'status' => 'A',
            'password_expiry_date' => 0,
            'amend_expired_password' => 'No',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
