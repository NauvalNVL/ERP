<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnalysisCodesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('mm_analysis_codes')->insert([
            ['code' => 'SI01', 'name' => 'FG STOCK IN', 'group' => 'FGSI', 'group2' => 'STOCKIN', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'SO01', 'name' => 'FG STOCK OUT', 'group' => 'FGSO', 'group2' => 'STOCKOUT', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'SOWST', 'name' => 'STOCK OUT WASTE', 'group' => 'FGSO', 'group2' => 'WASTE', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'ST01', 'name' => 'FG STOCK TAKE', 'group' => 'FGST', 'group2' => 'STOCKTAKE', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'WT01', 'name' => 'TRANSFER LOKASI', 'group' => 'FGTR', 'group2' => 'TRANSFER', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'WTQC', 'name' => 'TRANSFER LOKASI DARI QC', 'group' => 'FGTR', 'group2' => 'TRANSFER', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
