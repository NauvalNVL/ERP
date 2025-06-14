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
        // Skip creating the table if it already exists
        if (!Schema::hasTable('roll_trims_by_corrugator')) {
            Schema::create('roll_trims_by_corrugator', function (Blueprint $table) {
                $table->id();
                $table->foreignId('flute_id')->constrained('paper_flutes');
                $table->integer('min_trim')->default(0);
                $table->integer('max_trim')->default(100);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't drop the table here as it might be used by other migrations
        // Schema::dropIfExists('roll_trims_by_corrugator');
    }
};
