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
        Schema::create('mm_analysis_codes', function (Blueprint $table) {
            $table->string('code')->primary(); // Set code as primary key
            $table->string('name');
            $table->string('group'); // Changed from 'grouping'
            $table->string('group2'); // Added group2
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mm_analysis_codes');
    }
};
