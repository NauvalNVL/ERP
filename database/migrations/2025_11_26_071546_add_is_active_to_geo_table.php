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
        Schema::table('GEO', function (Blueprint $table) {
            $table->string('STATUS', 3)->default('Act')->comment('Status (Act/Obs)');
            $table->boolean('IS_ACTIVE')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('GEO', function (Blueprint $table) {
            $table->dropColumn(['STATUS', 'IS_ACTIVE']);
        });
    }
};
