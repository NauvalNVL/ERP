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
        Schema::create('analysis_codes', function (Blueprint $table) {
            $table->string('analysis_code', 50)->primary();
            $table->string('analysis_name', 255);
            $table->string('analysis_group', 50); // MC-Master Card, SD-Sales Order
            $table->string('analysis_group2', 50); // CS, RS, AM, CM, CL, UN
            $table->string('status', 3)->default('Act'); // Act = Active, Obs = Obsolete
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analysis_codes');
    }
};
