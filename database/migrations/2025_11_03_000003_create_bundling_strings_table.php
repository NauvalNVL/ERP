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
        Schema::create('bundling_strings', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 255);
            $table->string('status', 3)->default('Act'); // Act = Active, Obs = Obsolete
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('MC', function (Blueprint $table) {
            $table->foreign('STRING_TYPE')
                  ->references('code')
                  ->on('bundling_strings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('MC', function (Blueprint $table) {
            $table->dropForeign(['STRING_TYPE']);
        });
        Schema::dropIfExists('bundling_strings');
    }
};
