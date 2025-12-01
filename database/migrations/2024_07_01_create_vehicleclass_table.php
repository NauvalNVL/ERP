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
        Schema::create('vehicleclass', function (Blueprint $table) {
            $table->id();
            $table->string('NO_', 50);
            $table->string('VEHICLE_CLASS_CODE', 50)->unique();
            $table->string('DESCRIPTION', 50);
            $table->string('STATUS', 50)->default('A');
            $table->timestamps();
        });

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
