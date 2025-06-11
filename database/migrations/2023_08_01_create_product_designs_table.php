<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_designs', function (Blueprint $table) {
            $table->id();
            $table->string('pd_code')->unique();
            $table->string('pd_name');
            $table->string('product_code');
            $table->string('dimension');
            $table->string('idc');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_designs');
    }
}; 