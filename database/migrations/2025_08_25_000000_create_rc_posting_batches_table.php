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
        Schema::create('rc_posting_batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_number')->unique();
            $table->string('start_note_number');
            $table->string('end_note_number');
            $table->integer('total_records')->default(0);
            $table->integer('total_errors')->default(0);
            $table->integer('print_count')->default(0);
            $table->enum('status', ['New', 'Posted', 'Cancelled'])->default('New');
            $table->decimal('total_accrued_rc', 15, 2)->default(0);
            $table->string('current_ic_period');
            $table->string('current_ap_period');
            $table->string('current_gl_period');
            $table->string('prepared_by')->nullable();
            $table->date('prepared_date')->nullable();
            $table->time('prepared_time')->nullable();
            $table->string('cancelled_by')->nullable();
            $table->date('cancelled_date')->nullable();
            $table->time('cancelled_time')->nullable();
            $table->string('posted_by')->nullable();
            $table->date('posted_date')->nullable();
            $table->time('posted_time')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rc_posting_batches');
    }
};
