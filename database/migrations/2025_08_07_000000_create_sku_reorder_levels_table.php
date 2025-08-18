<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('sku_reorder_levels', function (Blueprint $table) {
            $table->id();
            $table->string('sku_id', 20); // Changed to string to match mm_skus.sku column type
            $table->string('period', 7); // format mm/yyyy
            $table->decimal('min_level', 12, 2)->default(0);
            $table->decimal('max_level', 12, 2)->default(0);
            $table->decimal('reorder_level', 12, 2)->default(0);
            $table->timestamps();

            $table->foreign('sku_id')->references('sku')->on('mm_skus')->onDelete('cascade'); // Fixed to reference 'sku' instead of 'id'
            $table->unique(['sku_id', 'period']);
        });
    }
    public function down()
    {
        Schema::dropIfExists('sku_reorder_levels');
    }
}; 