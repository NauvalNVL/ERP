<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComputationMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('computation_methods', function (Blueprint $table) {
            $table->id();
            $table->integer('formula_divisor')->default(0);
            $table->integer('formula_pieces')->default(0);
            $table->enum('height_type', ['internal', 'extended'])->default('internal');
            $table->enum('rounding_type', ['up', 'down'])->default('down');
            $table->boolean('is_active')->default(true);
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('computation_methods');
    }
} 