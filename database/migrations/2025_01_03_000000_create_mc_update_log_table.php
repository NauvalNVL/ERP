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
        Schema::create('MC_UPDATE_LOG', function (Blueprint $table) {
            $table->id();
            $table->string('MCS_Num', 50)->index();
            $table->string('status', 20); // 'Active' or 'Obsolete'
            $table->string('user_id', 100);
            $table->text('reason');
            $table->timestamps();
            
            // Add index for better query performance
            $table->index(['MCS_Num', 'updated_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('MC_UPDATE_LOG');
    }
};
