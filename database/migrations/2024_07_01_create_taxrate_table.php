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
        Schema::create('taxrate', function (Blueprint $table) {
            $table->id();
            $table->string('CODE', 50);
            $table->string('NAME', 50);
            $table->string('TAXCODE', 50);
            $table->string('TAXNAME', 50);
            $table->float('RATEPPH');
            $table->float('RATEPPN');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxrate');
    }
}; 