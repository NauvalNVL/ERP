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
        Schema::table('salesperson_teams', function (Blueprint $table) {
            $table->string('status', 3)->default('Act')->comment('Status (Act/Obs)');
            $table->boolean('is_active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salesperson_teams', function (Blueprint $table) {
            $table->dropColumn(['status', 'is_active']);
        });
    }
};
