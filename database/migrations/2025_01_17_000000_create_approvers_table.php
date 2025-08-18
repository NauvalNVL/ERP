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
        Schema::create('approvers', function (Blueprint $table) {
            $table->string('approver_id', 20)->primary();
            $table->string('approver_name', 100);
            $table->string('user_id', 20)->nullable();
            $table->string('email', 150);
            $table->string('password', 255)->nullable();
            $table->boolean('olda_enabled')->default(false)->comment('Online Daily Alert');
            
            // PR (Purchase Requisition) Authorization
            $table->boolean('pr_authorized')->default(false);
            $table->integer('pr_level')->default(1);
            $table->boolean('pr_zero_price_allowed')->default(false);
            $table->enum('pr_approval_style', ['tick_1_pr', 'tick_all_pr'])->default('tick_1_pr');
            $table->boolean('pr_price_history')->default(false);
            
            // PO (Purchase Order) Authorization
            $table->boolean('po_authorized')->default(false);
            $table->integer('po_level')->default(1);
            $table->enum('po_approval_style', ['tick_1_po', 'tick_all_po'])->default('tick_1_po');
            $table->decimal('po_min_limit', 15, 2)->default(1.00);
            $table->decimal('po_max_limit', 15, 2)->default(99999999.00);
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Indexes
            $table->index(['pr_authorized', 'is_active']);
            $table->index(['po_authorized', 'is_active']);
            $table->index('user_id');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approvers');
    }
}; 