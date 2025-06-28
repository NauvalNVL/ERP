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
            $table->foreignId('product_design_id')->constrained('product_designs', 'id')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products', 'id')->onDelete('cascade');
            $table->foreignId('flute_id')->constrained('paper_flutes', 'id')->onDelete('cascade');
            $table->integer('length_add')->default(0);
            $table->integer('length_less')->default(0);
            $table->boolean('compute')->default(false);
            $table->timestamps();
            
            // Add a unique constraint to prevent duplicate entries
            $table->unique(['product_design_id', 'product_id', 'flute_id'], 'side_trim_product_design_unique');
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