<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('industries', function (Blueprint $table) {
            $table->id();
            $table->string('code', 4)->unique();
            $table->string('name', 100);
            $table->timestamps();
        });
        
        // Insert data sesuai dengan gambar
        $industries = [
            ['code' => 'ID01', 'name' => 'AUTOMOTIVE'],
            ['code' => 'ID02', 'name' => 'CHEMICAL MATERIAL'],
            ['code' => 'ID03', 'name' => 'CERAMIC TILE'],
            ['code' => 'ID04', 'name' => 'ELECTRONIC'],
            ['code' => 'ID05', 'name' => 'FOOD & BEVERAGES'],
            ['code' => 'ID06', 'name' => 'FARM'],
            ['code' => 'ID07', 'name' => 'PHARMACY'],
            ['code' => 'ID08', 'name' => 'FURNITURE'],
            ['code' => 'ID09', 'name' => 'HOSPITAL'],
            ['code' => 'ID10', 'name' => 'GARMENT'],
            ['code' => 'ID11', 'name' => 'AUTOMOTIVE'],
            ['code' => 'ID12', 'name' => 'INDUSTRY'],
            ['code' => 'ID13', 'name' => 'JEWELLERY'],
            ['code' => 'ID14', 'name' => 'PERSONAL CARES'],
            ['code' => 'ID15', 'name' => 'SHOES'],
            ['code' => 'ID16', 'name' => 'TEXTILE'],
            ['code' => 'ID17', 'name' => 'TOYS'],
            ['code' => 'ID18', 'name' => 'OTHER INDUSTRY'],
        ];
        
        DB::table('industries')->insert($industries);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('industries');
    }
}; 