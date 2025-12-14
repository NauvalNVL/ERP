<?php

namespace Database\Seeders;

use App\Models\UserCps;
use App\Models\UserPermission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserCpsSeeder extends Seeder
{
    public function run()
    {
        // Data super admin
        // Hapus dulu semua permission yang terkait sebelum hapus user
        UserPermission::where('user_id', 'ADMIN001')->delete();

        $existingAdmin = UserCps::where('userID', 'ADMIN001')->first();
        if ($existingAdmin) {
            $existingAdmin->delete();
        }
        
        $adminUser = UserCps::createUser([
            'user_id' => 'ADMIN001',
            'username' => 'superadmin',
            'official_name' => 'Super Administrator',
            'official_title' => 'System Admin',
            'mobile_number' => '081234567890',
            'official_tel' => '021123456',
            'password' => 'admin123',
            'status' => 'A',
            'password_expiry_date' => 90,
            'amend_expired_password' => 'Yes'
        ]);

        // Create full permissions for admin
        UserPermission::createDefaultPermissions('ADMIN001');

        // Data sample user
        // Hapus dulu semua permission yang terkait sebelum hapus user
        UserPermission::where('user_id', 'USER001')->delete();

        $existingUser = UserCps::where('userID', 'USER001')->first();
        if ($existingUser) {
            $existingUser->delete();
        }
        
        $sampleUser = UserCps::createUser([
            'user_id' => 'USER001',
            'username' => 'john.doe',
            'official_name' => 'John Doe',
            'official_title' => 'Sales Manager',
            'mobile_number' => '087812345678',
            'official_tel' => '0217654321',
            'password' => 'password123',
            'status' => 'A',
            'password_expiry_date' => 30,
            'amend_expired_password' => 'No',
        ]);

        // Create full permissions for sample user
        UserPermission::createDefaultPermissions('USER001');
    }
}

