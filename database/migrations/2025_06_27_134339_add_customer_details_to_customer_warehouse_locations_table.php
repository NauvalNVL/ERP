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
        Schema::table('customer_warehouse_locations', function (Blueprint $table) {
            $table->string('customer_code'); // Removed ->primary()
            $table->string('customer_name')->nullable();
            $table->boolean('lock_customer_location')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_warehouse_locations', function (Blueprint $table) {
            $table->dropColumn(['customer_code', 'customer_name', 'lock_customer_location']);
        });
    }
};
