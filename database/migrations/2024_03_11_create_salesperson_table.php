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
        Schema::create('salesperson', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique()->comment('Salesperson code');
            $table->string('name', 100)->comment('Salesperson name');
            $table->foreignId('sales_team_id')->nullable()->constrained('sales_team');
            $table->string('position', 50)->nullable();
            $table->string('user_id', 20)->nullable()->comment('User ID for this salesperson');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salesperson');
    }
}; 