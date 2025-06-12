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
        Schema::create('roll_trims_by_corrugator', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('flute_id');
            $table->integer('min_trim')->default(0);
            $table->integer('max_trim')->default(100);
            $table->timestamps();

            $table->foreign('flute_id')->references('id')->on('paper_flutes')->onDelete('cascade');
            $table->unique('flute_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roll_trims_by_corrugator');
    }
};
