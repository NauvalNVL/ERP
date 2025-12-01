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
            $table->string('userID', 50)->unique();
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
            $table->string('SM', 50)->nullable();
            $table->string('PASS', 255); // Increased to 255 to accommodate bcrypt hash (60 chars)
            $table->string('PRICE', 50);
            $table->string('COST', 50);
            $table->timestamps();
        });

        Schema::table('usercps', function (Blueprint $table) {
            $table->foreign('SM')
                  ->references('Code')
                  ->on('salesperson');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usercps', function (Blueprint $table) {
            $table->dropForeign(['SM']);
        });

        Schema::dropIfExists('usercps');
    }
}; 