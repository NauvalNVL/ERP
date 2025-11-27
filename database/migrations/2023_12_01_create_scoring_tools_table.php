<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table structure matches CPS Enterprise 2020
     * Columns: NO., CODE, NAME, SCORER GAP
     */
    public function up(): void
    {
        Schema::create('scoring_tools', function (Blueprint $table) {
            $table->id(); // NO. column (auto increment)
            $table->string('code', 10)->unique(); // CODE column
            $table->string('name', 100); // NAME column
            $table->decimal('scorer_gap', 8, 1)->default(0.0); // SCORER GAP column
            $table->string('status', 3)->default('Act')->comment('Status (Act/Obs)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scoring_tools');
    }
}; 