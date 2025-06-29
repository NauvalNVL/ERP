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
        if (!Schema::hasTable('corrugator_spec_by_products')) {
            Schema::create('corrugator_spec_by_products', function (Blueprint $table) {
                $table->id();
                $table->string('product_code', 50);
                $table->boolean('compute')->default(false);
                $table->integer('min_sheet_length')->default(1);
                $table->integer('max_sheet_length')->default(99999);
                $table->integer('min_sheet_width')->default(1);
                $table->integer('max_sheet_width')->default(99999);
                $table->timestamps();
                
                // Add unique constraint to ensure one spec per product
                $table->unique('product_code');
                
                // Add foreign key if products table exists
                if (Schema::hasTable('products')) {
                    $table->foreign('product_code')
                        ->references('product_code')
                        ->on('products')
                        ->onDelete('cascade');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('corrugator_spec_by_products');
    }
}; 