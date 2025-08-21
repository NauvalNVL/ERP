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
        Schema::table('purchase_sub_controls', function (Blueprint $table) {
            // Drop index first before dropping the column
            $table->dropIndex('purchase_sub_controls_is_active_index');
        });
        
        Schema::table('purchase_sub_controls', function (Blueprint $table) {
            // Then drop the columns
            $table->dropColumn(['description', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_sub_controls', function (Blueprint $table) {
            $table->text('description')->nullable()->comment('Additional description');
            $table->boolean('is_active')->default(true)->comment('Active status');
            $table->index('is_active');
        });
    }
};
