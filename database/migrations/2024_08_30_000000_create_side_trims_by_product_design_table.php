<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSideTrimsByProductDesignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('side_trims_by_product_design', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_design_id')->constrained('product_designs')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->decimal('flute_id', 18, 0);
            $table->foreign('flute_id')->references('No')->on('Flute_CPS')->onDelete('cascade');
            $table->boolean('compute')->default(false);
            $table->decimal('length_less', 8, 2)->default(0);
            $table->decimal('length_add', 8, 2)->default(0);
            $table->timestamps();

            // Add unique constraint for the combination
            $table->unique(['product_design_id', 'product_id', 'flute_code'], 'side_trims_by_product_design_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('side_trims_by_product_design');
    }
}
