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
        Schema::create('purchaser_approval_flows', function (Blueprint $table) {
            $table->id();
            $table->string('purchaser_id', 20);
            $table->string('approver_id', 20);
            $table->string('approver_name', 100);
            $table->boolean('level_1')->default(false);
            $table->boolean('level_2')->default(false);
            $table->boolean('level_3')->default(false);
            $table->boolean('email_notification')->default(false);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('purchaser_id')->references('purchaser_id')->on('purchasers')->onDelete('cascade');

            // Indexes
            $table->index(['purchaser_id', 'approver_id']);
            $table->index('email_notification');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchaser_approval_flows');
    }
}; 