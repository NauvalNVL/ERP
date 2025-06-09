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
        Schema::create('customer_alternate_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_code');
            $table->string('salesperson_type');
            $table->string('currency');
            $table->string('currency_code');
            $table->string('status');
            $table->string('address');
            $table->string('city');
            $table->string('postal_code');
            $table->string('phone');
            $table->string('email');
            $table->timestamps();
            
            // Add index for faster searches
            $table->index('customer_code');
            $table->index('customer_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_alternate_addresses');
    }
}; 