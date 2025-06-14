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
        // Skip creating the table if it already exists
        if (!Schema::hasTable('finishings')) {
            Schema::create('finishings', function (Blueprint $table) {
                $table->id();
                $table->string('code', 10)->unique();
                $table->string('description');
                $table->boolean('compute')->default(false);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't drop the table here as it might be used by other migrations
        // Schema::dropIfExists('finishings');
    }
}; 