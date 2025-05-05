<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('temp_prpo', function (Blueprint $table) {
            $table->id();
            $table->string('NO', 50);
            $table->string('PR', 50);
            $table->string('PR_STS', 50);
            $table->string('PR_DATE', 50);
            $table->string('PR_REF', 50);
            $table->string('REQ', 50);
            $table->string('AP_AC', 50);
            $table->string('AP_NAME', 50);
            $table->string('ITEM', 50);
            $table->string('SKU', 100);
            $table->string('SKU_NAME', 50);
            $table->decimal('PR_QTY', 38, 0);
            $table->decimal('PO_QTY', 38, 0);
            $table->string('PR_BAL', 50);
            $table->string('PO_RUN', 50);
            $table->string('PO', 50);
            $table->string('PO_DATE', 50);
            $table->decimal('PO_QTY1', 38, 0);
            $table->decimal('ROWNO', 38, 0);
            $table->integer('PrDateSk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_prpo');
    }
}; 