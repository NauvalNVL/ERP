<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaperQualitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        
        $paperQualities = [
            [
                'code' => 'KL125',
                'name' => 'Kraft Liner 125',
                'description' => 'Brown kraft liner paper 125 gsm',
                'gsm' => 125,
                'paper_type' => 'liner',
                'is_active' => true,
                'created_by' => 'system',
                'updated_by' => 'system',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'KL150',
                'name' => 'Kraft Liner 150',
                'description' => 'Brown kraft liner paper 150 gsm',
                'gsm' => 150,
                'paper_type' => 'liner',
                'is_active' => true,
                'created_by' => 'system',
                'updated_by' => 'system',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'KL200',
                'name' => 'Kraft Liner 200',
                'description' => 'Brown kraft liner paper 200 gsm',
                'gsm' => 200,
                'paper_type' => 'liner',
                'is_active' => true,
                'created_by' => 'system',
                'updated_by' => 'system',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'TL125',
                'name' => 'Test Liner 125',
                'description' => 'Test liner paper 125 gsm',
                'gsm' => 125,
                'paper_type' => 'liner',
                'is_active' => true,
                'created_by' => 'system',
                'updated_by' => 'system',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'CM120',
                'name' => 'Corrugated Medium 120',
                'description' => 'Fluting medium paper 120 gsm',
                'gsm' => 120,
                'paper_type' => 'medium',
                'is_active' => true,
                'created_by' => 'system',
                'updated_by' => 'system',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'CM150',
                'name' => 'Corrugated Medium 150',
                'description' => 'Fluting medium paper 150 gsm',
                'gsm' => 150,
                'paper_type' => 'medium',
                'is_active' => true,
                'created_by' => 'system',
                'updated_by' => 'system',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'WTL250',
                'name' => 'White Top Liner 250',
                'description' => 'White coated top liner 250 gsm',
                'gsm' => 250,
                'paper_type' => 'liner',
                'is_active' => true,
                'created_by' => 'system',
                'updated_by' => 'system',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'CBP350',
                'name' => 'Chipboard 350',
                'description' => 'Gray chipboard 350 gsm',
                'gsm' => 350,
                'paper_type' => 'chipboard',
                'is_active' => true,
                'created_by' => 'system',
                'updated_by' => 'system',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'ART180',
                'name' => 'Art Paper 180',
                'description' => 'Glossy art paper 180 gsm',
                'gsm' => 180,
                'paper_type' => 'art',
                'is_active' => true,
                'created_by' => 'system',
                'updated_by' => 'system',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'ART250',
                'name' => 'Art Paper 250',
                'description' => 'Glossy art paper 250 gsm',
                'gsm' => 250,
                'paper_type' => 'art',
                'is_active' => true,
                'created_by' => 'system',
                'updated_by' => 'system',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
        
        // Empty the table first
        DB::table('paper_qualities')->truncate();
        
        // Insert the data
        DB::table('paper_qualities')->insert($paperQualities);
    }
}
