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
        Schema::create('customer_sales_types', function (Blueprint $table) {
            $table->id();
            $table->string('customer_code', 20);
            $table->string('customer_name', 100);
            $table->string('sales_type', 10)->default('LC'); // Default to LC (Local)
            $table->string('created_by', 20)->nullable(); // Reference to USERCPS.ID
            $table->string('updated_by', 20)->nullable(); // Reference to USERCPS.ID
            $table->timestamps();
            
            // Add indexes instead of foreign keys to avoid potential issues
            $table->index('customer_code');
            $table->index('created_by');
            $table->index('updated_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_sales_types');
    }
}; 