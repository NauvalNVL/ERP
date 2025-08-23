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
        Schema::create('dr_cr_notes', function (Blueprint $table) {
            $table->id();
            $table->string('note_number', 50)->unique();
            $table->enum('note_type', ['DR', 'CR']);
            $table->string('reference_document', 100)->nullable(); // Invoice number, PO number, etc.
            $table->string('reference_type', 50)->nullable(); // Invoice, Purchase Order, Sales Order, etc.
            $table->string('customer_code', 20)->nullable();
            $table->string('vendor_code', 20)->nullable();
            $table->string('customer_name', 200)->nullable();
            $table->string('vendor_name', 200)->nullable();
            $table->decimal('amount', 15, 2);
            $table->text('description');
            $table->text('reason')->nullable();
            $table->enum('status', ['Draft', 'Pending', 'Approved', 'Rejected', 'Posted', 'Cancelled'])->default('Draft');
            $table->date('note_date');
            $table->date('due_date')->nullable();
            $table->string('currency', 3)->default('IDR');
            $table->decimal('exchange_rate', 10, 4)->default(1.0000);
            $table->string('created_by', 50);
            $table->string('approved_by', 50)->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Indexes
            $table->index(['note_type', 'status']);
            $table->index(['customer_code', 'vendor_code']);
            $table->index('note_date');
            $table->index('reference_document');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dr_cr_notes');
    }
};
