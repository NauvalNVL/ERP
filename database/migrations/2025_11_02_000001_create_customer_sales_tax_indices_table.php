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
        Schema::create('customer_sales_tax_indices', function (Blueprint $table) {
            $table->id();
            $table->string('customer_code', 50);
            $table->integer('index_number');
            $table->string('tax_group_code', 20);
            $table->char('status', 1)->default('A'); // A-Active, O-Obsolete
            $table->timestamps();
            
            // Composite unique key
            $table->unique(['customer_code', 'index_number']);
            
            // Indexes for faster queries
            $table->index('customer_code');
            $table->index('tax_group_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_sales_tax_indices');
    }
};
