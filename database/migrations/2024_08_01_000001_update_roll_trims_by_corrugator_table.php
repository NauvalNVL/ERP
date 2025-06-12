<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRollTrimsByCorrugatorTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop the existing table if it exists
        Schema::dropIfExists('roll_trims_by_corrugator');
        
        // Create the table with the new structure
        Schema::create('roll_trims_by_corrugator', function (Blueprint $table) {
            $table->id();
            $table->string('corrugator_name');
            $table->string('flute_code');
            $table->integer('trim_value');
            $table->timestamps();
            
            // Add unique constraint to prevent duplicates
            $table->unique(['corrugator_name', 'flute_code']);
            
            // Note: Foreign key constraint removed due to column type mismatch
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roll_trims_by_corrugator');
    }
} 