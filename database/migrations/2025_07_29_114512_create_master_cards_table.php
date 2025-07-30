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
        Schema::create('master_cards', function (Blueprint $table) {
            $table->string('mc_seq')->primary(); // Primary key
            $table->string('mc_model')->nullable();
            $table->string('part_no')->nullable();
            $table->string('comp_no')->nullable();
            $table->string('p_design')->nullable();
            $table->string('status')->default('Active');
            $table->string('ext_dim_1')->nullable();
            $table->string('ext_dim_2')->nullable();
            $table->string('ext_dim_3')->nullable();
            $table->string('int_dim_1')->nullable();
            $table->string('int_dim_2')->nullable();
            $table->string('int_dim_3')->nullable();
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
        Schema::dropIfExists('master_cards');
    }
};
