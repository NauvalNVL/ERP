<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSideTrimsByFluteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('side_trims_by_flute', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flute_id')->constrained('paper_flutes', 'id')->onDelete('cascade');
            $table->integer('length_add')->default(0);
            $table->integer('length_less')->default(0);
            $table->boolean('compute')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('side_trims_by_flute');
    }
} 