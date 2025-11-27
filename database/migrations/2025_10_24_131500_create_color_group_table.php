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
        Schema::create('COLOR_GROUP', function (Blueprint $table) {
            $collation = 'SQL_Latin1_General_CP1_CI_AS';

            // CG as primary key - matching CPS database structure
            $table->string('CG', 15)->primary()->collation($collation);
            $table->string('CG_Name', 150)->nullable()->collation($collation);
            $table->string('CG_Type', 50)->nullable()->collation($collation);
            $table->string('status', 3)->default('Act')->comment('Status (Act/Obs)');
            
            // No timestamps - matching CPS database structure
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('COLOR_GROUP');
    }
};
