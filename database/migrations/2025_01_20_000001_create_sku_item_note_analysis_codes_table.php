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
        Schema::create('sku_item_note_analysis_codes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('analysis_group_id')->comment('Foreign key to analysis groups');
            $table->string('analysis_code', 20)->comment('Analysis Code');
            $table->string('code_name', 100)->comment('Analysis Code Name');
            $table->text('description')->nullable()->comment('Description of the analysis code');
            $table->boolean('is_active')->default(true)->comment('Active status');
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('analysis_group_id')->references('id')->on('sku_item_note_analysis_groups')->onDelete('cascade');
            
            // Unique constraint for analysis_code within the same group
            $table->unique(['analysis_group_id', 'analysis_code'], 'unique_code_per_group');
            
            // Indexes
            $table->index('analysis_group_id');
            $table->index('analysis_code');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sku_item_note_analysis_codes');
    }
}; 