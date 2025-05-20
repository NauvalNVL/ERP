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
        Schema::create('update_customer_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('customer_code', 20);
            $table->string('customer_name', 100);
            $table->string('salesperson', 20)->nullable();
            $table->string('ac_type', 20)->nullable();
            $table->string('currency', 10)->nullable();
            $table->string('status', 20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('update_customer_accounts');
    }
};
