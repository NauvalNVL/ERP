<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mm_gl_distributions', function (Blueprint $table) {
            $table->id();
            $table->string('gl_dist_code', 10)->unique();
            $table->string('gl_dist_name', 100);
            $table->string('gl_account', 20);
            $table->string('gl_account_segment1', 20)->nullable();
            $table->string('gl_account_segment2', 20)->nullable();
            $table->string('gl_account_segment3', 20)->nullable();
            $table->string('gl_account_name', 100)->nullable();
            $table->boolean('is_linked')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mm_gl_distributions');
    }
}; 