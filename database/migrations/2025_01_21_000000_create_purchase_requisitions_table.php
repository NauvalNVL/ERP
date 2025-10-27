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
            $table->string('requestor_id', 20); // Reference to USERCPS.ID
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
            
            // Approval fields - Reference to USERCPS.ID
            $table->string('approved_by', 20)->nullable();
            $table->datetime('approved_date')->nullable();
            $table->string('rejected_by', 20)->nullable();
            $table->datetime('rejected_date')->nullable();
            $table->text('rejection_reason')->nullable();
            
            // Audit fields - Reference to USERCPS.ID
            $table->string('created_by', 20)->nullable();
            $table->string('updated_by', 20)->nullable();
            $table->timestamps();
            $table->softDeletes();

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
