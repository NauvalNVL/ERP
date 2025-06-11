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
        Schema::table('approve_mcs', function (Blueprint $table) {
            $table->string('released_by')->nullable()->after('rejection_reason')->comment('User ID who released the MC');
            $table->timestamp('released_date')->nullable()->after('released_by')->comment('Date when MC was released');
            $table->text('release_notes')->nullable()->after('released_date')->comment('Notes about the release');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('approve_mcs', function (Blueprint $table) {
            $table->dropColumn(['released_by', 'released_date', 'release_notes']);
        });
    }
}; 