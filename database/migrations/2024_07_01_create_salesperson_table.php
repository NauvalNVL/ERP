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
            $table->string('Code', 50);
            $table->string('Name', 50);
            $table->string('Grup', 20);
            $table->string('CodeGrup', 50);
            $table->float('TargetSales');
            $table->string('Internal', 20);
            $table->string('Email', 100);
            $table->char('status', 10);
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