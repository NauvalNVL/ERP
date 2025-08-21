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
        Schema::create('purchase_requisitions', function (Blueprint $table) {
            $table->id();
            $table->string('pr_number', 20)->unique()->index();
            $table->integer('pr_period');
            $table->integer('pr_year');
            $table->string('department_code', 20)->nullable();
            $table->string('department_name', 100)->nullable();
            $table->unsignedBigInteger('requestor_id');
            $table->string('requestor_name', 100);
            $table->date('request_date');
            $table->date('required_date');
            $table->enum('priority', ['LOW', 'MEDIUM', 'HIGH', 'URGENT'])->default('MEDIUM');
            $table->enum('status', [
                'DRAFT', 
                'PENDING_APPROVAL', 
                'APPROVED', 
                'REJECTED', 
                'CANCELLED',
                'PARTIALLY_CONVERTED',
                'FULLY_CONVERTED'
            ])->default('DRAFT');
            $table->string('budget_code', 50)->nullable();
            $table->text('description')->nullable();
            $table->decimal('total_estimated_value', 15, 2)->default(0);
            $table->string('currency', 3)->default('IDR');
            
            // Approval fields
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->datetime('approved_date')->nullable();
            $table->unsignedBigInteger('rejected_by')->nullable();
            $table->datetime('rejected_date')->nullable();
            $table->text('rejection_reason')->nullable();
            
            // Audit fields
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign key constraints
            $table->foreign('requestor_id')->references('id')->on('users')->onDelete('no action');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('no action');
            $table->foreign('rejected_by')->references('id')->on('users')->onDelete('no action');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('no action');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('no action');

            // Indexes
            $table->index(['pr_period', 'pr_year']);
            $table->index(['status']);
            $table->index(['priority']);
            $table->index(['department_code']);
            $table->index(['requestor_id']);
            $table->index(['request_date']);
            $table->index(['required_date']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_requisitions');
    }
};
