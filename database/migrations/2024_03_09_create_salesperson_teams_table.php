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
        Schema::create('salesperson_teams', function (Blueprint $table) {
            $table->id();
            $table->string('s_person_code', 10)->unique();
            $table->string('salesperson_name', 100);
            $table->string('st_code', 2);
            $table->string('sales_team_name', 100);
            $table->string('sales_team_position', 20)->default('E-Executive');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salesperson_teams');
    }
}; 