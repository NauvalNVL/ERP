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
        Schema::create('scoring_formulas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_design_id')->constrained('product_designs')->onDelete('cascade');
            // Use soft reference to CPS table Flute_CPS instead of FK to non-existent paper_flutes
            // Store either the numeric primary key (No) or the Flute code. Here we store Flute code for readability.
            $table->string('paper_flute_code', 25)->nullable()->comment('Soft reference to Flute_CPS.Flute');
            $table->json('scoring_length_formula')->comment('JSON array of scoring length formulas');
            $table->json('scoring_width_formula')->comment('JSON array of scoring width formulas');
            $table->decimal('length_conversion', 8, 2)->default(7.00)->comment('Length conversion value in mm');
            $table->decimal('width_conversion', 8, 2)->default(7.00)->comment('Width conversion value in mm');
            $table->decimal('height_conversion', 8, 2)->default(12.00)->comment('Height conversion value in mm');
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Add unique constraint for product design and paper flute combination
            $table->unique(['product_design_id', 'paper_flute_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scoring_formulas');
    }
}; 