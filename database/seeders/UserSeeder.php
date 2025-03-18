<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Data super admin
        User::create([
            'user_id' => 'ADMIN001',
            'username' => 'superadmin',
            'official_name' => 'Super Administrator',
            'official_title' => 'System Admin',
            'mobile_number' => '081234567890',
            'official_tel' => '021123456',
            'password' => bcrypt('admin123'),
            'status' => 'A',
            'password_expiry_date' => 90, // dalam hari
            'amend_expired_password' => 'Yes',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Data sample user
        User::create([
            'user_id' => 'USER001',
            'username' => 'john.doe',
            'official_name' => 'John Doe',
            'official_title' => 'Sales Manager',
            'mobile_number' => '087812345678',
            'official_tel' => '0217654321',
            'password' => bcrypt('password123'),
            'status' => 'A',
            'password_expiry_date' => 30,
            'amend_expired_password' => 'No',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}

