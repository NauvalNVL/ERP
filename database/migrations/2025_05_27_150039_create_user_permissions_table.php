<?php

// SKIPPED: This migration is disabled in favor of 2024_11_03_100000_create_user_permissions_table.php
// which has a more complete structure for the user permissions system.

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
        // SKIPPED - using 2024_11_03 version instead
        return;
        
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
        // SKIPPED
        return;
        
        Schema::dropIfExists('user_permissions');
    }
};
