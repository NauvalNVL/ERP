<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('custom_tariff_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique(); // HS Code / Tariff Code
            $table->string('name', 255); // Description/Name of the tariff code
            $table->text('description')->nullable(); // Additional description
            $table->decimal('duty_rate', 5, 2)->default(0); // Duty rate percentage
            $table->decimal('tax_rate', 5, 2)->default(0); // Tax rate percentage
            $table->string('category', 100)->nullable(); // Category classification
            $table->string('country_origin', 50)->nullable(); // Country of origin
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->string('created_by', 50)->nullable();
            $table->string('updated_by', 50)->nullable();
            $table->timestamps();

            $table->index(['code', 'is_active']);
            $table->index('category');
        });
    }

    public function down()
    {
        Schema::dropIfExists('custom_tariff_codes');
    }
}; 