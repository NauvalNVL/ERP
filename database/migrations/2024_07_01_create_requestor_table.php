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
        Schema::create('requestor', function (Blueprint $table) {
            $table->id();
            $table->integer('NO');
            $table->string('BUYER', 50);
            $table->string('NAME', 50);
            $table->string('TIPE', 50);
            $table->integer('LEADTIME');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requestor');
    }
}; 