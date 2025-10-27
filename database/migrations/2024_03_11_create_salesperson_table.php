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
            $table->string('Code', 50)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->string('Name', 50)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->string('Grup', 20)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->string('CodeGrup', 50)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->decimal('TargetSales', 18, 2)->nullable();
            $table->string('Internal', 20)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->string('Email', 100)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
            $table->char('status', 10)->nullable()->collation('SQL_Latin1_General_CP1_CI_AS');
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