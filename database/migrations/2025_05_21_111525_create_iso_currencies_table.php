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
        Schema::create('iso_currencies', function (Blueprint $table) {
            $table->id();
            $table->string('iso_code', 3)->unique();
            $table->string('currency_name', 100);
            $table->string('country', 100);
            $table->string('numeric_code', 3);
            $table->integer('minor_unit')->default(2);
            $table->string('symbol', 10)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iso_currencies');
    }
};
