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
        Schema::create('sku', function (Blueprint $table) {
            $table->id();
            $table->string('NO', 50);
            $table->string('CAT', 50);
            $table->string('CAT_NAME', 50);
            $table->string('SKU', 50);
            $table->string('SKU_NAME', 50);
            $table->string('TYPE', 50);
            $table->string('STS', 50);
            $table->string('APGL_DIST_AC', 50);
            $table->string('GL_ACC', 50);
            $table->string('GL_NAME', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sku');
    }
}; 