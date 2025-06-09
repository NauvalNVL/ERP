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
        Schema::create('approve_mcs', function (Blueprint $table) {
            $table->id();
            $table->string('mc_seq')->unique()->comment('Master Card Sequence Number');
            $table->string('mc_model')->comment('Master Card Model');
            $table->string('customer_code')->comment('Customer Code Reference');
            $table->string('customer_name')->comment('Customer Name');
            $table->enum('status', ['pending', 'active', 'obsolete'])->default('pending')->comment('Master Card Status');
            $table->string('approved_by')->nullable()->comment('User ID who approved');
            $table->timestamp('approved_date')->nullable()->comment('Date of approval');
            $table->string('rejected_by')->nullable()->comment('User ID who rejected');
            $table->timestamp('rejected_date')->nullable()->comment('Date of rejection');
            $table->text('rejection_reason')->nullable()->comment('Reason for rejection');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approve_mcs');
    }
};
