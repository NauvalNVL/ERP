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
        Schema::table('roll_trims_by_corrugator', function (Blueprint $table) {
            $table->boolean('compute')->default(false)->after('flute_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roll_trims_by_corrugator', function (Blueprint $table) {
            $table->dropColumn('compute');
        });
    }
};
