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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_code', 50)->unique();
            $table->string('description');
            $table->string('product_group_id', 50)->nullable();
            $table->string('category')->nullable();
            $table->string('status', 3)->default('Act')->comment('Status (Act/Obs)');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Add foreign key constraint
            $table->foreign('product_group_id')
                  ->references('product_group_id')
                  ->on('product_groups')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
