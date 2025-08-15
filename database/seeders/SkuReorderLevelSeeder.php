<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SkuReorderLevelSeeder extends Seeder
{
    public function run()
    {
        $sku = DB::table('mm_skus')->first();
        if (!$sku) return;
        $start = Carbon::now();
        for ($i = 0; $i < 12; $i++) {
            DB::table('sku_reorder_levels')->insert([
                'sku_id' => $sku->sku, // Fixed to use 'sku' instead of 'id'
                'period' => $start->copy()->addMonths($i)->format('m/Y'),
                'min_level' => rand(1, 10),
                'max_level' => rand(11, 20),
                'reorder_level' => rand(5, 15),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
} 