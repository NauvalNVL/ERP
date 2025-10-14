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
        Schema::table('master_cards', function (Blueprint $table) {
            if (!Schema::hasColumn('master_cards', 'mc_approval')) {
                $table->string('mc_approval', 10)->default('No')->after('status');
            }
            if (!Schema::hasColumn('master_cards', 'detailed_master_card')) {
                $table->json('detailed_master_card')->nullable()->after('customer_code');
            }
            if (!Schema::hasColumn('master_cards', 'pd_setup')) {
                $table->json('pd_setup')->nullable()->after('detailed_master_card');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_cards', function (Blueprint $table) {
            if (Schema::hasColumn('master_cards', 'pd_setup')) {
                $table->dropColumn('pd_setup');
            }
            if (Schema::hasColumn('master_cards', 'detailed_master_card')) {
                $table->dropColumn('detailed_master_card');
            }
            if (Schema::hasColumn('master_cards', 'mc_approval')) {
                $table->dropColumn('mc_approval');
            }
        });
    }
};
























