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
            $table->string('user_id'); // References userID from usercps table
            $table->string('menu_key'); // Unique identifier for each menu item
            $table->string('menu_name'); // Human readable menu name
            $table->string('menu_route')->nullable(); // Route path for the menu
            $table->string('menu_category'); // Category: dashboard, system_manager, sales_management, warehouse_management
            $table->string('menu_parent')->nullable(); // Parent menu for nested items
            $table->boolean('can_access')->default(false); // Permission to access this menu
            $table->timestamps();

            // Indexes for better performance
            $table->index(['user_id', 'menu_key']);
            $table->index(['user_id', 'can_access']);
            $table->unique(['user_id', 'menu_key']);

            // Note: Foreign key constraint removed due to usercps table structure
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
