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
        Schema::create('purchase_sub_controls', function (Blueprint $table) {
            $table->id();
            $table->string('psc_code', 20)->unique()->comment('Purchase Sub-Control Code');
            $table->string('psc_name', 100)->comment('Purchase Sub-Control Name');
            $table->string('category', 50)->nullable()->comment('Category/Type of sub-control');
            $table->text('description')->nullable()->comment('Additional description');
            $table->boolean('is_active')->default(true)->comment('Active status');
            $table->timestamps();
            
            // Indexes
            $table->index('psc_code');
            $table->index('category');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_sub_controls');
    }
}; 