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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_name');
            $table->string('ap_ac_number')->unique();
            $table->string('status')->default('A-Act'); // A-Act, I-Inact, P-Pending
            $table->string('ac_type')->default('Local'); // Local, Foreign
            $table->string('currency')->default('IDR');
            $table->string('gl_ap_control')->nullable();
            $table->string('gl_bank_control')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['vendor_name', 'ap_ac_number']);
            $table->index('status');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
