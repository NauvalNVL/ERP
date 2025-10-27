<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRollSizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roll_sizes', function (Blueprint $table) {
            $table->id();
            // Replace FK to non-existent paper_flutes with soft reference to Flute_CPS.Flute
            $table->string('flute_code', 25)->nullable()->comment('Soft reference to Flute_CPS.Flute');
            $table->float('roll_length', 8, 2);
            $table->boolean('compute')->default(false);
            $table->timestamps();
            
            // Add a unique constraint to prevent duplicate entries
            $table->unique(['flute_code', 'roll_length'], 'roll_size_flute_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roll_sizes');
    }
} 