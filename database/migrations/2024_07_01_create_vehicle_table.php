<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicle', function (Blueprint $table) {
            $table->id();
            $table->string('NO_', 50);
            $table->string('VEHICLE_NO', 50);
            $table->string('VEHICLE_STATUS', 50);
            $table->string('VEHICLE_CLASS', 50);
            $table->string('VEHICLE_DESCRIPTION', 50);
            $table->string('VEHICLE_COMPANY', 50);
            $table->string('COMPANY', 50);
            $table->string('DRIVER_CODE', 50);
            $table->string('DRIVER_NAME', 50);
            $table->string('DRIVER_ID', 50);
            $table->string('DRIVER_PHONE', 50);
            $table->string('NOTE', 50);
            $table->string('STATUS', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle');
    }
}; 