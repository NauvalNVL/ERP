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
            $table->string('Color_Code', 15)->unique();
            $table->string('Color_Name', 150)->nullable();
            $table->string('GroupCode', 15)->nullable();
            $table->string('Group', 50)->nullable();
            $table->string('status', 3)->default('Act')->comment('Status (Act/Obs)');


            // Add foreign key constraint
            $table->foreign('GroupCode')
                ->references('CG')
                ->on('COLOR_GROUP')
                ->onDelete('set null');

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
