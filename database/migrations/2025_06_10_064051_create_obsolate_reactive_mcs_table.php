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
            $table->unsignedBigInteger('obsolate_by')->nullable();
            $table->string('obsolate_reason', 255)->nullable();
            $table->timestamp('reactive_date')->nullable();
            $table->unsignedBigInteger('reactive_by')->nullable();
            $table->string('reactive_reason', 255)->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->timestamps();
            
            $table->foreign('customer_id')->references('id')->on('update_customer_accounts');
            $table->foreign('obsolate_by')->references('id')->on('users');
            $table->foreign('reactive_by')->references('id')->on('users');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
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