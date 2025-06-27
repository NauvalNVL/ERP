<?php

namespace Database\Seeders;

use App\Models\MmConfig;
use Illuminate\Database\Seeder;

class MmConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default configuration if it doesn't exist
        if (!MmConfig::first()) {
            MmConfig::create([
                // PR Fields
                'pr_online_approval' => 'Y',
                'pr_total_level' => 3,
                'pr_approval_flow' => 'A',
                'pr_price' => 'Y',
                'pr_gl_dist' => 'Y',
                
                // PO Fields
                'po_online_approval' => 'Y',
                'po_level' => 1,
                'po_tran_type' => 'PO',
                'po_purchaser' => 'PURST',
                'po_receive_location' => 'BP',
                'po_tax_group' => 'PN2025',
                'po_lock_to_pr' => 'N',
                'po_note' => 'N',
                'po_min_price' => 100.00,
                'po_max_price' => 999.00,
                'po_default_unit_price' => '0-No Default, input price',
                'po_amend_unit_price_completed' => 'Y',
                'po_amend_unit_price_outstanding' => 'Y',
                'po_gl_dist' => 'N',
                
                // RC Fields
                'rc_location' => '',
                'rc_mfg_date' => 'N',
                'rc_gl_dist' => 'N',
                'rc_ap_source' => 'AP',
                'rc_ap_source_code' => 'RC',
                'rc_ap_ref' => 'PO-PO#',
                'rc_ap_remarks' => ['PO-PO#+PO Date | RC#+RC Date', 'DO-SUPP DO#+DO Date', 'IV-SUPP IV#+IV Date', 'P1-PO Note 1', 'P2-PO Note 2'],
                'rc_gl_remarks' => ['PO-PO#+PO Date | RC#+RC Date', 'SI-SKU# + Qty | UOM | U.Price', 'R2-RC SKU Item Note 2', 'A#-AP AC# + Name', 'N-None'],
                'rc_post_accrued' => 'N',
                'rc_ap_gl_dist' => 'HUTANG',
                
                // RT Fields
                'rt_gl_dist' => 'N',
                'rt_ap_source' => 'AP',
                'rt_ap_ref' => 'RT-RT#',
                'rt_ap_source_code' => 'CN-SUPP CN#',
                'rt_ap_remarks' => ['PO-PO#+PO Date | RC#+RC Date', 'DO-SUPP DO#+DO Date', 'IV-SUPP IV#+IV Date', 'CN-SUPP CN#+CN Date', 'N-None'],
                'rt_gl_remarks' => ['DO-SUPP DO#+DO Date', 'A#-AP AC# + Name', 'N-None', 'N-None', 'N-None'],
                
                // DN Fields
                'dn_gl_dist' => 'N',
                'dn_ap_source' => 'AP',
                'dn_ap_ref' => 'DN-DN#',
                'dn_ap_source_code' => 'CN-SUPP CN#',
                'dn_ap_remark' => 'D1-DN Note1',
                'dn_gl_remarks' => ['N-None', 'N-None', 'N-None', 'N-None', 'N-None'],
                
                // CN Fields
                'cn_gl_dist' => 'N',
                'cn_ap_source' => 'AP',
                'cn_ap_ref' => 'CN-CN#',
                'cn_ap_source_code' => 'DN-SUPP DN#',
                'cn_ap_remark' => 'C1-CN Note1',
                'cn_gl_remarks' => ['N-None', 'N-None', 'N-None', 'N-None', 'N-None'],
                
                // IS Fields
                'is_cancel' => '1-Allow 1 Previous Month Cancellation',
                'is_gl_dist' => 'N',
                'is_gl_source' => 'GL',
                'is_gl_ref' => 'IS-IS#',
                'is_gl_remarks' => ['IS-IS#', 'N-None', 'N-None', 'N-None', 'N-None'],
                'is_gl_posting' => 'N',
                
                // MI Fields
                'mi_location' => '',
                'mi_gl_dist' => 'N',
                'mi_gl_source' => 'GL',
                'mi_gl_ref' => 'MI-MI#',
                'mi_gl_remarks' => ['MI-MI#', 'N-None', 'N-None', 'N-None', 'N-None'],
                
                // MO Fields
                'mo_gl_dist' => 'N',
                'mo_gl_source' => 'GL',
                'mo_gl_ref' => 'MO-MO#',
                'mo_gl_remarks' => ['MO-MO#', 'N-None', 'N-None', 'N-None', 'N-None'],
                
                // OLDA Fields
                'olda_enabled' => 'Y',
                'olda_name' => 'PT.MBI',
                'olda_email' => 'no-reply@ptmbi.com',
                'olda_password' => '********',
                'olda_sku_reorder' => 'Y',
                'olda_po_due_delivery' => 'N',
                'olda_skip_email' => 'Y',
                'olda_total_po_item' => 50,
            ]);
        }
    }
} 