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
        Schema::create('user_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 20); // Reference to USERCPS.ID (string type)
            $table->string('permission_name');
            $table->timestamps();
            
            // Add a unique constraint to prevent duplicate permissions for the same user
            $table->unique(['user_id', 'permission_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_permissions');
    }
};
