<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('sku_consumption_budgets', function (Blueprint $table) {
            $table->id();
            $table->string('sku_id', 20); // References mm_skus.sku
            $table->string('effective_month', 7); // format mm/yyyy
            $table->decimal('budget_quantity', 12, 2)->default(0);
            $table->decimal('actual_quantity', 12, 2)->default(0);
            $table->decimal('variance', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->string('created_by', 50)->nullable();
            $table->string('updated_by', 50)->nullable();
            $table->timestamps();

            $table->foreign('sku_id')->references('sku')->on('mm_skus')->onDelete('cascade');
            $table->unique(['sku_id', 'effective_month']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('sku_consumption_budgets');
    }
}; 