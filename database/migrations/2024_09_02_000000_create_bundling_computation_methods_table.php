<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBundlingComputationMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bundling_computation_methods', function (Blueprint $table) {
            $table->id();
            $table->string('product_name')->nullable();
            $table->string('product_design')->nullable();
            $table->string('product')->nullable();
            $table->string('flute')->nullable();
            $table->integer('formula_divisor')->default(1);
            $table->integer('formula_pieces')->default(0);
            $table->enum('height_type', ['internal', 'extended'])->default('internal');
            $table->enum('rounding_type', ['up', 'down'])->default('down');
            $table->boolean('is_compute')->default(false);
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
            
            // Add a unique constraint on the combination of product_design, product, and flute
            $table->unique(['product_design', 'product', 'flute'], 'bundling_computation_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bundling_computation_methods');
    }
} 