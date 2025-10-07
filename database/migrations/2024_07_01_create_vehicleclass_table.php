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
            $table->string('VEHICLE_CLASS_CODE', 50);
            $table->string('DESCRIPTION', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicleclass');
    }
}; 