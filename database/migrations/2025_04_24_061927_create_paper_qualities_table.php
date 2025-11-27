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
        Schema::create('paper_qualities', function (Blueprint $table) {
            $table->id();
            $table->string('paper_quality', 10)->unique()->comment('Paper Quality Code');
            $table->string('paper_name', 50)->comment('Paper Name');
            $table->decimal('weight_kg_m', 6, 4)->nullable()->comment('Weight KG/M');
            $table->string('commercial_code', 10)->nullable()->comment('Commercial Code');
            $table->string('wet_end_code', 10)->nullable()->comment('Wet-End Code');
            $table->string('decc_code', 10)->nullable()->comment('DECC Code');
            $table->string('status', 3)->default('Act')->comment('Status (Act/Obs)');
            $table->string('flute', 5)->nullable()->comment('Flute');
            $table->string('db', 5)->nullable()->comment('DB');
            $table->string('b', 5)->nullable()->comment('B');
            $table->string('il', 5)->nullable()->comment('IL');
            $table->string('a_c_e', 5)->nullable()->comment('A/C/E');
            $table->string('2l', 5)->nullable()->comment('2L');
            $table->string('created_by')->default('system');
            $table->string('updated_by')->default('system');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paper_qualities');
    }
};
