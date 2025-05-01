<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\DB; // Tidak digunakan lagi
use App\Models\SalesTeam; // Import model SalesTeam

class SalesTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = [
            [
                'code' => '01',
                'name' => 'MBI',
                // created_at dan updated_at akan dihandle otomatis oleh Eloquent
            ],
            [
                'code' => '02',
                'name' => 'MANAGEMENT LOCAL',
            ],
            [
                'code' => '03',
                'name' => 'MANAGEMENT MNC',
            ]
            // Tambahkan data tim lainnya di sini jika perlu
        ];

        // Loop melalui data dan gunakan updateOrCreate
        foreach ($teams as $teamData) {
            SalesTeam::updateOrCreate(
                ['code' => $teamData['code']], // Kolom unik untuk pencarian
                $teamData // Data untuk diupdate atau dibuat
            );
        }

        // DB::table('sales_team')->insert($teams); // Ganti baris ini
    }
} 