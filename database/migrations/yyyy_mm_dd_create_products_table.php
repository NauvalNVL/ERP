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
        // Skip if table already exists
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('product_code', 10)->unique();
                $table->string('description', 255);
                $table->string('product_group_id', 10);
                $table->string('category');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });

            Schema::table('products', function (Blueprint $table) {
                if (Schema::hasTable('product_groups')) {
                    $table->foreign('product_group_id')
                        ->references('product_group_id')
                        ->on('product_groups')
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
        // Don't drop the table here as it might be used by other migrations
        // Schema::dropIfExists('products');
    }
}; 