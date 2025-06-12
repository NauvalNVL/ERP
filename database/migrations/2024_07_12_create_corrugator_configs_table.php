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
        Schema::create('corrugator_configs', function (Blueprint $table) {
            $table->id();
            $table->string('corrugator_id', 10);
            $table->integer('min_sheet_length')->default(1);
            $table->integer('max_sheet_length')->default(99999);
            $table->integer('min_sheet_width')->default(1);
            $table->integer('max_sheet_width')->default(99999);
            $table->boolean('is_sheet_length_multiplied')->default(true);
            $table->boolean('is_min_raw_multiplied')->default(false);
            $table->boolean('validate_sheet_width')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('corrugator_configs');
    }
}; 