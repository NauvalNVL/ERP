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
        Schema::create('vehicleclass', function (Blueprint $table) {
            $table->id();
            $table->string('NO_', 50);
            $table->string('VEHICLE_CLASS_CODE', 50)->unique();
            $table->string('DESCRIPTION', 50);
            $table->string('STANDART_CLASS_CODE', 50)->nullable();
            $table->float('VOLUME_M3')->nullable();
            $table->float('CAPACITY_WGT_MT')->nullable();
            $table->string('STATUS', 50)->default('A');
            $table->timestamps();
        });

        // Seed vehicleclass with any existing VEHICLE_CLASS codes from vehicle table
        $existingCodes = DB::table('vehicle')
            ->select('VEHICLE_CLASS')
            ->whereNotNull('VEHICLE_CLASS')
            ->groupBy('VEHICLE_CLASS')
            ->get();

        foreach ($existingCodes as $row) {
            $code = trim((string) $row->VEHICLE_CLASS);
            if ($code === '') {
                continue;
            }

            DB::table('vehicleclass')->updateOrInsert(
                ['VEHICLE_CLASS_CODE' => $code],
                [
                    'NO_' => $code,
                    'DESCRIPTION' => $code,
                    'STATUS' => 'A',
                ]
            );
        }

        Schema::table('vehicle', function (Blueprint $table) {
            $table->foreign('VEHICLE_CLASS')
                ->references('VEHICLE_CLASS_CODE')
                ->on('vehicleclass')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicle', function (Blueprint $table) {
            $table->dropForeign(['VEHICLE_CLASS']);
        });
        Schema::dropIfExists('vehicleclass');
    }
};
