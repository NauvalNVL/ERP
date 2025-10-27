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
        Schema::create('obsolate_reactive_mcs', function (Blueprint $table) {
            $table->id();
            $table->string('mc_seq', 50)->unique();
            $table->string('mc_model', 100);
            $table->unsignedBigInteger('customer_id');
            $table->string('customer_name', 100);
            $table->string('description', 255)->nullable();
            $table->enum('status', ['active', 'obsolete'])->default('active');
            $table->timestamp('obsolate_date')->nullable();
            $table->string('obsolate_by', 20)->nullable(); // Reference to USERCPS.ID
            $table->string('obsolate_reason', 255)->nullable();
            $table->timestamp('reactive_date')->nullable();
            $table->string('reactive_by', 20)->nullable(); // Reference to USERCPS.ID
            $table->string('reactive_reason', 255)->nullable();
            $table->string('created_by', 20); // Reference to USERCPS.ID
            $table->string('updated_by', 20); // Reference to USERCPS.ID
            $table->timestamps();
            
            $table->foreign('customer_id')->references('id')->on('update_customer_accounts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obsolate_reactive_mcs');
    }
}; 