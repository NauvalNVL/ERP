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
            $table->string('approver_id', 20); // Reference to USERCPS.ID
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
            
            // Delegation fields - Reference to USERCPS.ID
            $table->string('delegation_from', 20)->nullable();
            $table->text('delegation_reason')->nullable();
            
            // Audit fields - Reference to USERCPS.ID
            $table->string('created_by', 20)->nullable();
            $table->string('updated_by', 20)->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign key constraints
            $table->foreign('pr_id')->references('id')->on('purchase_requisitions')->onDelete('cascade');

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
