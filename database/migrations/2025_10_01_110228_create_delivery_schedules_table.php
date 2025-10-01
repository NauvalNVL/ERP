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
        Schema::create('delivery_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('so_number');
            $table->integer('line_number');
            $table->integer('schedule_sequence');
            $table->date('schedule_date');
            $table->time('schedule_time')->nullable();
            $table->decimal('delivery_quantity', 15, 2);
            $table->string('due_status', 10); // ETD, ETA, TBA
            $table->text('remark')->nullable();
            $table->string('delivery_code')->nullable();
            $table->string('delivery_location')->nullable();
            
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('so_number')->references('so_number')->on('sales_orders')->onDelete('cascade');
            
            // Indexes
            $table->index('so_number');
            $table->index(['so_number', 'line_number']);
            $table->index(['so_number', 'line_number', 'schedule_sequence']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_schedules');
    }
};
