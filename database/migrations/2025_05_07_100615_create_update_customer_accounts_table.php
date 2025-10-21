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
            $table->string('short_name', 50)->nullable();
            $table->text('address')->nullable();
            $table->string('contact_person', 100)->nullable();
            $table->string('telephone_no', 30)->nullable();
            $table->string('fax_no', 30)->nullable();
            $table->string('co_email', 100)->nullable();
            $table->decimal('credit_limit', 15, 2)->nullable();
            $table->integer('credit_terms')->nullable();
            $table->string('ac_type', 20);
            $table->string('currency_code', 10)->nullable();
            $table->string('salesperson_code', 20)->nullable();
            $table->string('industrial_code', 20)->nullable();
            $table->string('geographical', 20)->nullable();
            $table->string('grouping_code', 20)->nullable();
            $table->string('print_ar_aging', 10);
            $table->string('salesperson', 20)->nullable();
            $table->string('currency', 10)->nullable();
            $table->string('status', 20)->nullable();
            $table->string('created_by', 20)->nullable(); // Reference to USERCPS.ID
            $table->string('updated_by', 20)->nullable(); // Reference to USERCPS.ID
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
