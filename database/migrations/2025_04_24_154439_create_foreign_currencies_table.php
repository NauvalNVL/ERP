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
        Schema::create('foreign_currencies', function (Blueprint $table) {
            $table->id();
            $table->string('currency_code', 3)->unique();
            $table->string('country', 100);
            $table->string('currency_name', 100);
            $table->decimal('exchange_rate', 15, 6);
            $table->tinyInteger('exchange_method')->comment('1: Multiply, 2: Divide');
            $table->decimal('variance_control', 5, 2);
            $table->decimal('max_tax_adj', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foreign_currencies');
    }
};
