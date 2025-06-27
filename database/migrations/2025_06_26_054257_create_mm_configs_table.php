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
        Schema::create('mm_configs', function (Blueprint $table) {
            $table->id();

            // PR Fields (Purchase Requisition)
            $table->string('pr_online_approval', 1)->default('Y');
            $table->integer('pr_total_level')->default(3);
            $table->string('pr_approval_flow', 1)->default('A');
            $table->string('pr_price', 1)->default('Y');
            $table->string('pr_gl_dist', 1)->default('Y');

            // PO Fields (Purchase Order)
            $table->string('po_online_approval', 1)->default('Y');
            $table->integer('po_level')->default(1);
            $table->string('po_tran_type', 50)->nullable();
            $table->string('po_purchaser', 50)->nullable();
            $table->string('po_receive_location', 50)->nullable();
            $table->string('po_tax_group', 50)->nullable();
            $table->string('po_lock_to_pr', 1)->default('N');
            $table->string('po_note', 1)->default('N');
            $table->decimal('po_min_price', 10, 2)->default(100.00);
            $table->decimal('po_max_price', 10, 2)->default(999.00);
            $table->string('po_default_unit_price', 50)->default('0-No Default, input price');
            $table->string('po_amend_unit_price_completed', 1)->default('Y');
            $table->string('po_amend_unit_price_outstanding', 1)->default('Y');
            $table->string('po_gl_dist', 1)->default('N');

            // RC Fields (Receive Note)
            $table->string('rc_location', 255)->nullable();
            $table->string('rc_mfg_date', 1)->default('N');
            $table->string('rc_gl_dist', 1)->default('N');
            $table->string('rc_ap_source', 50)->nullable();
            $table->string('rc_ap_source_code', 50)->nullable();
            $table->string('rc_ap_ref', 50)->nullable();
            $table->json('rc_ap_remarks')->nullable(); // For the 5 AP remarks
            $table->json('rc_gl_remarks')->nullable(); // For the 5 GL remarks
            $table->string('rc_post_accrued', 1)->default('N');
            $table->string('rc_ap_gl_dist', 50)->nullable();

            // RT Fields (Return Note)
            $table->string('rt_gl_dist', 1)->default('N');
            $table->string('rt_ap_source', 50)->nullable();
            $table->string('rt_ap_ref', 50)->nullable();
            $table->string('rt_ap_source_code', 50)->nullable();
            $table->json('rt_ap_remarks')->nullable(); // For the AP remarks
            $table->json('rt_gl_remarks')->nullable(); // For the GL remarks

            // DN Fields (Debit Note)
            $table->string('dn_gl_dist', 1)->default('N');
            $table->string('dn_ap_source', 50)->nullable();
            $table->string('dn_ap_ref', 50)->nullable();
            $table->string('dn_ap_source_code', 50)->nullable();
            $table->string('dn_ap_remark', 50)->nullable();
            $table->json('dn_gl_remarks')->nullable(); // For the GL remarks

            // CN Fields (Credit Note)
            $table->string('cn_gl_dist', 1)->default('N');
            $table->string('cn_ap_source', 50)->nullable();
            $table->string('cn_ap_ref', 50)->nullable();
            $table->string('cn_ap_source_code', 50)->nullable();
            $table->string('cn_ap_remark', 50)->nullable();
            $table->json('cn_gl_remarks')->nullable(); // For the GL remarks

            // IS Fields (Issue Note)
            $table->string('is_cancel', 50)->default('1-Allow 1 Previous Month Cancellation');
            $table->string('is_gl_dist', 1)->default('N');
            $table->string('is_gl_source', 50)->nullable();
            $table->string('is_gl_ref', 50)->nullable();
            $table->json('is_gl_remarks')->nullable(); // For the GL remarks
            $table->string('is_gl_posting', 1)->default('N');

            // MI Fields (Miscellaneous Stock-In)
            $table->string('mi_location', 255)->nullable();
            $table->string('mi_gl_dist', 1)->default('N');
            $table->string('mi_gl_source', 50)->nullable();
            $table->string('mi_gl_ref', 50)->nullable();
            $table->json('mi_gl_remarks')->nullable(); // For the GL remarks

            // MO Fields (Miscellaneous Stock-Out)
            $table->string('mo_gl_dist', 1)->default('N');
            $table->string('mo_gl_source', 50)->nullable();
            $table->string('mo_gl_ref', 50)->nullable();
            $table->json('mo_gl_remarks')->nullable(); // For the GL remarks

            // LT Fields (Transfer)
            // placeholder for LT config fields

            // OLDA Fields (Online Daily Alert)
            $table->string('olda_enabled', 1)->default('Y');
            $table->string('olda_name', 255)->nullable();
            $table->string('olda_email', 255)->nullable();
            $table->string('olda_password', 255)->nullable();
            $table->string('olda_sku_reorder', 1)->default('Y');
            $table->string('olda_po_due_delivery', 1)->default('N');
            $table->string('olda_skip_email', 1)->default('Y');
            $table->integer('olda_total_po_item')->default(50);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mm_configs');
    }
};
