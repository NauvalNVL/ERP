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
            $table->foreignId('flute_id')->constrained('paper_flutes', 'id')->onDelete('cascade');
            $table->float('roll_length', 8, 2);
            $table->boolean('compute')->default(false);
            $table->timestamps();
            
            // Add a unique constraint to prevent duplicate entries
            $table->unique(['flute_id', 'roll_length'], 'roll_size_flute_unique');
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