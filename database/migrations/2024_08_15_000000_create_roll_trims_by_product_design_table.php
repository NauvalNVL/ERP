<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRollTrimsByProductDesignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roll_trims_by_product_design', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('product_design_id')->constrained('product_designs')->onDelete('cascade');
            $table->decimal('flute_id', 18, 0);
            $table->foreign('flute_id')->references('No')->on('Flute_CPS')->onDelete('cascade');
            $table->boolean('is_composite')->default(false);
            $table->integer('min_trim')->default(20);
            $table->integer('max_trim')->default(65);
            $table->timestamps();

            // Add a unique constraint to prevent duplicate entries
            $table->unique(['product_id', 'product_design_id', 'flute_id'], 'roll_trim_product_design_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roll_trims_by_product_design');
    }
}
