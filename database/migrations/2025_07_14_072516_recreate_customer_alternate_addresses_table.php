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
        Schema::dropIfExists('customer_alternate_addresses');
        Schema::create('customer_alternate_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('customer_code');
            $table->string('delivery_code');
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('town')->nullable();
            $table->string('town_section')->nullable();
            $table->string('bill_to_name')->nullable();
            $table->text('bill_to_address')->nullable();
            $table->string('ship_to_name')->nullable();
            $table->text('ship_to_address')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('tel_no')->nullable();
            $table->string('fax_no')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
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
