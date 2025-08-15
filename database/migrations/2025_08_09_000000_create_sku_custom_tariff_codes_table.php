<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('sku_custom_tariff_codes', function (Blueprint $table) {
            $table->id();
            $table->string('sku_id', 20); // References mm_skus.sku
            $table->unsignedBigInteger('custom_tariff_code_id'); // References custom_tariff_codes.id
            $table->string('tariff_description')->nullable(); // Official tariff description
            $table->decimal('duty_rate', 5, 2)->default(0); // Duty rate percentage
            $table->decimal('vat_rate', 5, 2)->default(0); // VAT/PPN rate percentage
            $table->decimal('pph_import_rate', 5, 2)->default(0); // PPh import rate percentage
            $table->string('country_origin', 50)->nullable(); // Country of origin
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->string('created_by', 50)->nullable();
            $table->string('updated_by', 50)->nullable();
            $table->timestamps();

            $table->foreign('sku_id')->references('sku')->on('mm_skus')->onDelete('cascade');
            $table->foreign('custom_tariff_code_id')->references('id')->on('custom_tariff_codes')->onDelete('cascade');
            $table->unique(['sku_id', 'custom_tariff_code_id']);
            $table->index(['sku_id', 'is_active']);
            $table->index('custom_tariff_code_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sku_custom_tariff_codes');
    }
}; 