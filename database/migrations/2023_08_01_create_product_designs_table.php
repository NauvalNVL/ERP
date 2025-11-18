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
            $table->string('pd_alt_name')->nullable();
            $table->string('pd_design_type');
            $table->string('idc');
            $table->string('product');
            $table->string('joint')->default('No');
            $table->string('joint_to_print')->default('No');
            $table->string('pcs_to_joint')->default('No');
            $table->string('score')->default('No');
            $table->string('slot')->default('No');
            $table->string('flute_style');
            $table->string('print_flute');
            $table->string('input_weight');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_designs');
    }
}; 