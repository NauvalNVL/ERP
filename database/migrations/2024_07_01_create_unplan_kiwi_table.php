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
        Schema::create('unplan_kiwi', function (Blueprint $table) {
            $table->id();
            $table->char('job_key', 20);
            $table->char('customer_number', 50);
            $table->char('customer_name', 50);
            $table->char('spec_number', 50);
            $table->dateTime('date_due');
            $table->char('productdescription', 50);
            $table->char('carrier', 50);
            $table->integer('ordered_quantity');
            $table->char('SLM', 5);
            $table->char('sales', 50);
            $table->char('internal', 50);
            $table->char('email', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unplan_kiwi');
    }
}; 