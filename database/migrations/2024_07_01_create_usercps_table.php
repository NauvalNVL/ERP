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
        Schema::create('usercps', function (Blueprint $table) {
            $table->id();
            $table->string('NO_', 50);
            $table->string('userID', 50);
            $table->string('userName', 50);
            $table->string('OFFICIAL_NAME', 50);
            $table->string('OFFICIAL_TITLE', 50);
            $table->string('MOBILE', 50);
            $table->string('TEL_', 50);
            $table->string('STS', 50);
            $table->string('EXPIRY_DATE', 50);
            $table->string('EXPIRED', 50);
            $table->string('PRINTER', 50);
            $table->string('ROUTE', 50);
            $table->string('TYPE', 50);
            $table->string('U_PRICE', 50);
            $table->string('AC', 50);
            $table->string('MC', 50);
            $table->string('MC_PRICE', 50);
            $table->string('SM', 50);
            $table->string('PASS', 50);
            $table->string('PRICE', 50);
            $table->string('COST', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usercps');
    }
}; 