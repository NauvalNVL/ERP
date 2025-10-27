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
        Schema::create('COLOR', function (Blueprint $table) {
            $collation = 'SQL_Latin1_General_CP1_CI_AS';

            // Color_Code as primary key - matching CPS database structure
            $table->string('Color_Code', 15)->primary()->collation($collation);
            $table->string('Color_Name', 150)->nullable()->collation($collation);
            $table->string('GroupCode', 15)->nullable()->collation($collation);
            $table->string('Group', 50)->nullable()->collation($collation);
            
            // No timestamps - matching CPS database structure
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('COLOR');
    }
}; 