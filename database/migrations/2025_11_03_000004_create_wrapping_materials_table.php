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
        Schema::create('wrapping_materials', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 255);
            $table->string('description')->nullable();
            $table->string('status', 3)->default('Act');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('MC', function (Blueprint $table) {
            $table->foreign('WRAPPING')
                  ->references('code')
                  ->on('wrapping_materials');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('MC', function (Blueprint $table) {
            $table->dropForeign(['WRAPPING']);
        });
        Schema::dropIfExists('wrapping_materials');
    }
};
