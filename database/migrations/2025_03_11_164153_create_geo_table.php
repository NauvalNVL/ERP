<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeoTable extends Migration
{
    public function up()
    {
        Schema::create('geo', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('country');
            $table->string('state');
            $table->string('town');
            $table->string('town_section');
            $table->string('area');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('geo');
    }
}