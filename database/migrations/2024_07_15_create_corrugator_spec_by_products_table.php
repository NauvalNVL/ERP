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
        Schema::create('corrugator_spec_by_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->boolean('composite')->default(false);
            $table->integer('min_sheet_length')->default(1);
            $table->integer('max_sheet_length')->default(99999);
            $table->integer('min_sheet_width')->default(1);
            $table->integer('max_sheet_width')->default(99999);
            $table->timestamps();
            
            // Add unique constraint to ensure one spec per product
            $table->unique('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('corrugator_spec_by_products');
    }
}; 