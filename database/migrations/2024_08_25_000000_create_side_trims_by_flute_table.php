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
            $table->boolean('is_composite')->default(false);
            $table->timestamps();
            
            // Add a unique constraint to prevent duplicate entries
            $table->unique(['flute_id', 'is_composite'], 'side_trim_flute_unique');
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