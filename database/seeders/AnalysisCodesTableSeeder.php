<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnalysisCodesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('analysis_codes')->insert([
            [
                'analysis_code'   => 'SI01',
                'analysis_name'   => 'FG STOCK IN',
                'analysis_group'  => 'FGSI',
                'analysis_group2' => 'STOCKIN',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'analysis_code'   => 'SO01',
                'analysis_name'   => 'FG STOCK OUT',
                'analysis_group'  => 'FGSO',
                'analysis_group2' => 'STOCKOUT',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'analysis_code'   => 'SOWST',
                'analysis_name'   => 'STOCK OUT WASTE',
                'analysis_group'  => 'FGSO',
                'analysis_group2' => 'WASTE',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'analysis_code'   => 'ST01',
                'analysis_name'   => 'FG STOCK TAKE',
                'analysis_group'  => 'FGST',
                'analysis_group2' => 'STOCKTAKE',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'analysis_code'   => 'WT01',
                'analysis_name'   => 'TRANSFER LOKASI',
                'analysis_group'  => 'FGTR',
                'analysis_group2' => 'TRANSFER',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'analysis_code'   => 'WTQC',
                'analysis_name'   => 'TRANSFER LOKASI DARI QC',
                'analysis_group'  => 'FGTR',
                'analysis_group2' => 'TRANSFER',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
        ]);
    }
}
