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
        Schema::create('sku_item_note_analysis_groups', function (Blueprint $table) {
            $table->id();
            $table->string('group_code', 20)->unique()->comment('Analysis Group Code');
            $table->string('group_name', 100)->comment('Analysis Group Name');
            $table->text('description')->nullable()->comment('Description of the analysis group');
            $table->string('category', 50)->nullable()->comment('Category/Type of analysis group');
            $table->boolean('is_active')->default(true)->comment('Active status');
            $table->timestamps();
            
            // Indexes
            $table->index('group_code');
            $table->index('category');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sku_item_note_analysis_groups');
    }
}; 