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
        Schema::create('purchasers', function (Blueprint $table) {
            $table->string('purchaser_id', 20)->primary();
            $table->string('purchaser_name', 100);
            $table->enum('type', ['PU', 'RQ'])->comment('PU-Purchaser, RQ-Requestor');
            $table->string('email', 150);
            $table->string('password', 255)->nullable();
            $table->boolean('cc_to_self')->default(true);
            $table->boolean('is_active')->default(true);
            $table->string('department', 50)->nullable();
            $table->string('position', 50)->nullable();
            $table->string('employee_id', 20)->nullable();
            $table->string('phone', 20)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['type', 'is_active']);
            $table->index('email');
            $table->index('employee_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchasers');
    }
}; 