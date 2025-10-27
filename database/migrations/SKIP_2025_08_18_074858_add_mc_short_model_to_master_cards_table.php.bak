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
        if (Schema::hasTable('master_cards')) {
            Schema::table('master_cards', function (Blueprint $table) {
                if (!Schema::hasColumn('master_cards', 'mc_short_model')) {
                    $table->string('mc_short_model')->nullable()->after('mc_model');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('master_cards') && Schema::hasColumn('master_cards', 'mc_short_model')) {
            Schema::table('master_cards', function (Blueprint $table) {
                $table->dropColumn('mc_short_model');
            });
        }
    }
};
