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
                // Replace FK to non-existent paper_flutes with soft reference to Flute_CPS.Flute
                $table->string('flute_code', 25)->nullable()->comment('Soft reference to Flute_CPS.Flute');
                $table->boolean('compute')->default(false);
                $table->integer('min_trim')->default(0);
                $table->integer('max_trim')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roll_trims_by_corrugator');
    }
};
