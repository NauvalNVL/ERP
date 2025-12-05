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
        Schema::create('machine', function (Blueprint $table) {
            $table->id();
            $table->string('machine_code', 20)->unique()->comment('Machine Code - Primary identifier');
            $table->string('machine_name', 100)->comment('Machine Name');
            $table->string('process', 100)->nullable()->comment('Process');
            $table->string('sub_process', 100)->nullable()->comment('Sub-Process');
            $table->string('resource_type', 50)->nullable()->comment('Resource Type');
            $table->string('finisher_type', 50)->nullable()->comment('Finisher Type');
            $table->string('status', 3)->default('Act')->comment('Status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machine');
    }
};
