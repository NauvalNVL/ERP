<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paper_flutes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name');
            $table->text('description')->nullable();
            // Take Up Ratio untuk setiap layer
            $table->decimal('tur_l2b', 8, 2)->default(0.00)->comment('Take Up Ratio Layer 2B');
            $table->decimal('tur_l3', 8, 2)->default(0.00)->comment('Take Up Ratio Layer 3');
            $table->decimal('tur_l1', 8, 2)->default(0.00)->comment('Take Up Ratio Layer 1');
            $table->decimal('tur_ace', 8, 2)->default(0.00)->comment('Take Up Ratio Layer A/C/E');
            $table->decimal('tur_2l', 8, 2)->default(0.00)->comment('Take Up Ratio Layer 2L');
            // Properti fisik
            $table->decimal('flute_height', 8, 2)->default(0.00)->comment('Flute Height in mm');
            $table->decimal('starch_consumption', 8, 2)->default(0.00)->comment('Starch Consumption Factor');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paper_flutes');
    }
};