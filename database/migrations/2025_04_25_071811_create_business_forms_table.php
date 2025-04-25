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
        Schema::create('business_forms', function (Blueprint $table) {
            $table->id();
            $table->string('bf_code')->unique(); // BF Code
            $table->string('bf_name');          // BF Name
            $table->string('bf_group');         // BF Group
            $table->string('bf_iso')->nullable(); // BF ISO# (nullable)
            $table->string('check_by_name')->nullable(); // BF CHECK BY NAME (nullable)
            $table->string('check_by_title')->nullable();// BF CHECK BY TITLE (nullable)
            $table->string('approve_by_name')->nullable();// BF APPROVE BY NAME (nullable)
            $table->string('approve_by_title')->nullable();// BF APPROVE BY TITLE (nullable)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_forms');
    }
};
