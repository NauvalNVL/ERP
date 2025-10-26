<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('GEO', function (Blueprint $table) {
            $table->string('CODE', 10)->primary();
            $table->string('COUNTRY', 50);
            $table->string('STATE', 50);
            $table->string('TOWN', 50);
            $table->string('TOWN_SECTION', 50);
            $table->string('AREA', 50);
            
            // No timestamps - matching CPS database structure
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('GEO');
    }
};