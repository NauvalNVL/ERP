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
        Schema::table('mm_tax_types', function (Blueprint $table) {
            $table->string('tax_group_code', 10)->nullable()->after('name');
            $table->foreign('tax_group_code')->references('code')->on('mm_tax_groups')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mm_tax_types', function (Blueprint $table) {
            $table->dropForeign(['tax_group_code']);
            $table->dropColumn('tax_group_code');
        });
    }
}; 