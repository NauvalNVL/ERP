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
        Schema::create('snp_check_tab', function (Blueprint $table) {
            $table->id();
            $table->string('CATALOG_NAME', 100);
            $table->string('SCHEMA_NAME', 100);
            $table->string('RESOURCE_NAME', 100);
            $table->string('FULL_RES_NAME', 100);
            $table->string('ERR_TYPE', 1);
            $table->string('ERR_MESS', 250);
            $table->dateTime('CHECK_DATE');
            $table->string('ORIGIN', 100);
            $table->string('CONS_NAME', 35);
            $table->string('CONS_TYPE', 2);
            $table->decimal('ERR_COUNT', 10, 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('snp_check_tab');
    }
}; 