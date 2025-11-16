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
        Schema::create('tax_groups', function (Blueprint $table) {
            $table->string('code', 20)->primary();
            $table->string('name', 100);
            $table->char('sales_tax_applied', 1)->default('Y'); // Y or N
            $table->char('status', 1)->default('A');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_groups');
    }
};
