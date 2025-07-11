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
        Schema::table('mm_control_periods', function (Blueprint $table) {
            $table->string('fg_entry_date')->nullable();
            $table->string('do_entry_date')->nullable();
            $table->string('do_rejection_entry_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mm_control_periods', function (Blueprint $table) {
            $table->dropColumn(['fg_entry_date', 'do_entry_date', 'do_rejection_entry_date']);
        });
    }
};
