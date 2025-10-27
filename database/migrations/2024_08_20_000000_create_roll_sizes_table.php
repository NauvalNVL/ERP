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
            $table->decimal('flute_id', 18, 0);
            $table->foreign('flute_id')->references('No')->on('Flute_CPS')->onDelete('cascade');
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
