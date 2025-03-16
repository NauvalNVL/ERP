<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('paper_sizes', function (Blueprint $table) {
            $table->id();
            $table->decimal('size', 10, 2);
            $table->decimal('inches', 10, 2);
            $table->string('unit', 20)->default('Millimeter');
            $table->boolean('is_active')->default(true);
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('paper_sizes');
    }
}; 