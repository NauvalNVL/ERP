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
        Schema::create('top_real', function (Blueprint $table) {
            $table->integer('ID')->primary();
            $table->string('AC_Num', 50);
            $table->string('AC_Name', 100);
            $table->integer('AVG_Bayar');
            $table->integer('Insurance');
            $table->string('Status', 2);
            $table->string('UserLog', 20);
            $table->string('UserEditLog', 20);
            $table->dateTime('LOGDateTime');
            $table->dateTime('LOGDateTimeEdit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('top_real');
    }
}; 