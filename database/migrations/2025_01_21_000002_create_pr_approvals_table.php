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
        Schema::create('pr_approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pr_id');
            $table->unsignedBigInteger('approver_id');
            $table->integer('approver_level')->default(1);
            $table->enum('action', [
                'PENDING',
                'APPROVED', 
                'REJECTED', 
                'DELEGATED', 
                'CANCELLED',
                'RETURNED'
            ])->default('PENDING');
            $table->text('comments')->nullable();
            $table->datetime('approved_date')->nullable();
            $table->integer('approval_sequence')->default(1);
            $table->boolean('is_required')->default(true);
            
            // Delegation fields
            $table->unsignedBigInteger('delegation_from')->nullable();
            $table->text('delegation_reason')->nullable();
            
            // Audit fields
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign key constraints
            $table->foreign('pr_id')->references('id')->on('purchase_requisitions')->onDelete('cascade');
            $table->foreign('approver_id')->references('id')->on('users')->onDelete('no action');
            $table->foreign('delegation_from')->references('id')->on('users')->onDelete('no action');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('no action');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('no action');

            // Indexes
            $table->index(['pr_id']);
            $table->index(['approver_id']);
            $table->index(['action']);
            $table->index(['approver_level']);
            $table->index(['approval_sequence']);
            $table->index(['is_required']);
            $table->index(['approved_date']);
            $table->unique(['pr_id', 'approver_id', 'approval_sequence'], 'pr_approver_sequence_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pr_approvals');
    }
};
