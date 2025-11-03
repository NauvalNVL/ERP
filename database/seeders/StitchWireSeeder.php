<?php

namespace Database\Seeders;

use App\Models\StitchWire;
use Illuminate\Database\Seeder;

class StitchWireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stitchWires = [
            ['code' => '001', 'name' => 'TIPE 1+1+1+1+1', 'description' => '5 Wire Stitch Type 1', 'is_active' => true],
            ['code' => '002', 'name' => 'TIPE 2+1+1+1+2', 'description' => '5 Wire Stitch Type 2', 'is_active' => true],
            ['code' => '003', 'name' => 'TIPE 2+2+2+2+2', 'description' => '5 Wire Stitch Type 3', 'is_active' => true],
        ];

        foreach ($stitchWires as $stitchWire) {
            StitchWire::updateOrCreate(
                ['code' => $stitchWire['code']],
                $stitchWire
            );
        }
    }
}
