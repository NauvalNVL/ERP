<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'menu_key',
        'menu_name',
        'menu_route',
        'menu_category',
        'menu_parent',
        'can_access'
    ];

    protected $casts = [
        'can_access' => 'boolean'
    ];

    /**
     * Relationship with UserCps
     */
    public function user()
    {
        return $this->belongsTo(UserCps::class, 'user_id', 'userID');
    }

    /**
     * Get all menu items with their structure
     */
    public static function getAllMenuItems()
    {
        return [
            // Dashboard
            [
                'key' => 'dashboard',
                'name' => 'Dashboard',
                'route' => '/dashboard',
                'category' => 'dashboard',
                'parent' => null
            ],
            
            // System Manager
            [
                'key' => 'system_manager',
                'name' => 'System Manager',
                'route' => null,
                'category' => 'system_manager',
                'parent' => null
            ],
            [
                'key' => 'define_user',
                'name' => 'Define User',
                'route' => '/user',
                'category' => 'system_manager',
                'parent' => 'system_manager'
            ],
            [
                'key' => 'amend_user_password',
                'name' => 'Amend User Password',
                'route' => '/system-security/amend-password',
                'category' => 'system_manager',
                'parent' => 'system_manager'
            ],
            [
                'key' => 'define_user_access_permission',
                'name' => 'Define User Access Permission',
                'route' => '/system-security/define-access',
                'category' => 'system_manager',
                'parent' => 'system_manager'
            ],
            [
                'key' => 'copy_paste_user_access_permission',
                'name' => 'Copy & Paste User Access Permission',
                'route' => '/system-security/copy-paste-access',
                'category' => 'system_manager',
                'parent' => 'system_manager'
            ],
            [
                'key' => 'view_print_user',
                'name' => 'View & Print User',
                'route' => '/system-security/view-print-user',
                'category' => 'system_manager',
                'parent' => 'system_manager'
            ],

            // Sales Management
            [
                'key' => 'sales_management',
                'name' => 'Sales Management',
                'route' => null,
                'category' => 'sales_management',
                'parent' => null
            ],
            
            // Sales Configuration
            [
                'key' => 'define_sales_configuration',
                'name' => 'Define Sales Configuration',
                'route' => '/sales-configuration',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            
            // Standard Requirement
            [
                'key' => 'define_sales_team',
                'name' => 'Define Sales Team',
                'route' => '/sales-team',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'define_salesperson',
                'name' => 'Define Salesperson',
                'route' => '/sales-person',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'define_salesperson_team',
                'name' => 'Define Salesperson Team',
                'route' => '/sales-person-team',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'define_industry',
                'name' => 'Define Industry',
                'route' => '/industry',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'define_geo',
                'name' => 'Define Geo',
                'route' => '/geo',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'define_product_group',
                'name' => 'Define Product Group',
                'route' => '/product-group',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'define_product',
                'name' => 'Define Product',
                'route' => '/product',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'define_product_design',
                'name' => 'Define Product Design',
                'route' => '/product-design',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'define_scoring_tool',
                'name' => 'Define Scoring Tool',
                'route' => '/scoring-tool',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'define_paper_quality',
                'name' => 'Define Paper Quality',
                'route' => '/paper-quality',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'define_paper_flute',
                'name' => 'Define Paper Flute',
                'route' => '/paper-flute',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'define_paper_size',
                'name' => 'Define Paper Size',
                'route' => '/paper-size',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'define_color_group',
                'name' => 'Define Color Group',
                'route' => '/color-group',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'define_color',
                'name' => 'Define Color',
                'route' => '/color',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'define_finishing',
                'name' => 'Define Finishing',
                'route' => '/finishing',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'define_stitch_wire',
                'name' => 'Define Stitch Wire',
                'route' => '/stitch-wire',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'define_chemical_coat',
                'name' => 'Define Chemical Coat',
                'route' => '/chemical-coat',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'define_reinforcement_tape',
                'name' => 'Define Reinforcement Tape',
                'route' => '/reinforcement-tape',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'define_bundling_string',
                'name' => 'Define Bundling String',
                'route' => '/bundling-string',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'define_wrapping_material',
                'name' => 'Define Wrapping Material',
                'route' => '/wrapping-material',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'define_glueing_material',
                'name' => 'Define Glueing Material',
                'route' => '/glueing-material',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            
            // Sales Management - Standard Requirement
            [
                'key' => 'view_print_sales_team',
                'name' => 'View & Print Sales Team',
                'route' => '/sales-management/system-requirement/standard-requirement/view-print-sales-team',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'view_print_salesperson',
                'name' => 'View & Print Salesperson',
                'route' => '/sales-management/system-requirement/standard-requirement/view-print-salesperson',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'view_print_salesperson_team',
                'name' => 'View & Print Salesperson Team',
                'route' => '/sales-management/system-requirement/standard-requirement/view-print-salesperson-team',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'view_print_industry',
                'name' => 'View & Print Industry',
                'route' => '/sales-management/system-requirement/standard-requirement/view-print-industry',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'view_print_geo',
                'name' => 'View & Print Geo',
                'route' => '/sales-management/system-requirement/standard-requirement/view-print-geo',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'view_print_product_group',
                'name' => 'View & Print Product Group',
                'route' => '/sales-management/system-requirement/standard-requirement/view-print-product-group',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'view_print_product',
                'name' => 'View & Print Product',
                'route' => '/sales-management/system-requirement/standard-requirement/view-print-product',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'view_print_product_design',
                'name' => 'View & Print Product Design',
                'route' => '/sales-management/system-requirement/standard-requirement/view-print-product-design',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'view_print_paper_quality',
                'name' => 'View & Print Paper Quality',
                'route' => '/sales-management/system-requirement/standard-requirement/view-print-paper-quality',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'view_print_paper_flute',
                'name' => 'View & Print Paper Flute',
                'route' => '/sales-management/system-requirement/standard-requirement/view-print-paper-flute',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'view_print_paper_size',
                'name' => 'View & Print Paper Size',
                'route' => '/sales-management/system-requirement/standard-requirement/view-print-paper-size',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'view_print_scoring_tool',
                'name' => 'View & Print Scoring Tool',
                'route' => '/sales-management/system-requirement/standard-requirement/view-print-scoring-tool',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'view_print_color_group',
                'name' => 'View & Print Color Group',
                'route' => '/sales-management/system-requirement/standard-requirement/view-print-color-group',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'view_print_color',
                'name' => 'View & Print Color',
                'route' => '/sales-management/system-requirement/standard-requirement/view-print-color',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'view_print_finishing',
                'name' => 'View & Print Finishing',
                'route' => '/sales-management/system-requirement/standard-requirement/view-print-finishing',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'view_print_stitch_wire',
                'name' => 'View & Print Stitch Wire',
                'route' => '/stitch-wire/view-print',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'view_print_chemical_coat',
                'name' => 'View & Print Chemical Coat',
                'route' => '/chemical-coat/view-print',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'view_print_reinforcement_tape',
                'name' => 'View & Print Reinforcement Tape',
                'route' => '/reinforcement-tape/view-print',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'view_print_bundling_string',
                'name' => 'View & Print Bundling String',
                'route' => '/bundling-string/view-print',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'view_print_wrapping_material',
                'name' => 'View & Print Wrapping Material',
                'route' => '/wrapping-material/view-print',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'view_print_glueing_material',
                'name' => 'View & Print Glueing Material',
                'route' => '/glueing-material/view-print',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            
            // Sales Management - Customer Account
            [
                'key' => 'define_customer_group',
                'name' => 'Define Customer Group',
                'route' => '/sales-management/system-requirement/customer-account/define-customer-group',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'update_customer_account',
                'name' => 'Update Customer Account',
                'route' => '/sales-management/system-requirement/customer-account/update-customer-account',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'obsolete_reactive_customer_ac',
                'name' => 'Obsolete/Reactive Customer A/C',
                'route' => '/sales-management/system-requirement/customer-account/obsolete-reactive-customer-account',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'define_customer_alternate_address',
                'name' => 'Define Customer Alternate Address',
                'route' => '/sales-management/system-requirement/customer-account/define-customer-alternate-address',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'define_customer_sales_type',
                'name' => 'Define Customer Sales Type',
                'route' => '/sales-management/system-requirement/customer-account/define-customer-sales-type',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'view_print_customer_group',
                'name' => 'View & Print Customer Group',
                'route' => '/sales-management/system-requirement/customer-account/view-print-customer-group',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'view_print_customer_account',
                'name' => 'View & Print Customer Account',
                'route' => '/sales-management/system-requirement/customer-account/view-print-customer-account',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'view_print_customer_alternate_address',
                'name' => 'View & Print Customer Alternate Address',
                'route' => '/sales-management/system-requirement/customer-account/view-print-customer-alternate-address',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'view_print_customer_sales_type',
                'name' => 'View & Print Customer Sales Type',
                'route' => '/sales-management/system-requirement/customer-account/view-print-customer-sales-type',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'view_print_non_active_customer',
                'name' => 'View & Print Non-Active Customer',
                'route' => '/sales-management/system-requirement/customer-account/view-print-non-active-customer',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            
            // Master Card
            [
                'key' => 'update_mc',
                'name' => 'Update MC',
                'route' => '/sales-management/system-requirement/master-card/update-mc',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'approve_mc',
                'name' => 'Approve MC',
                'route' => '/sales-management/system-requirement/master-card/approve-mc',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'release_approved_mc',
                'name' => 'Release Approved MC',
                'route' => '/sales-management/system-requirement/master-card/realese-approve-mc',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'obsolete_reactive_mc',
                'name' => 'Obsolete & Reactive MC',
                'route' => '/sales-management/system-requirement/master-card/obsolete-reactive-mc',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'view_print_mc',
                'name' => 'View & Print MC',
                'route' => '/sales-management/system-requirement/master-card/view-and-print-MC',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'view_print_mc_maintenance_log',
                'name' => 'View & Print MC Maintenance Log',
                'route' => '/sales-management/system-requirement/master-card/view-and-print-mc-maintenance-log',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            
            // Sales Order
            [
                'key' => 'prepare_mc_so',
                'name' => 'Prepare MC SO',
                'route' => '/sales-order/transaction/prepare-mc-so',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'prepare_sb_so',
                'name' => 'Prepare SB SO',
                'route' => '/sales-order/transaction/prepare-sb-so',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'prepare_jit_so',
                'name' => 'Prepare JIT SO',
                'route' => '/sales-order/transaction/prepare-jit-so',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'print_so',
                'name' => 'Print SO',
                'route' => '/sales-order/transaction/print-so',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'cancel_so',
                'name' => 'Cancel SO',
                'route' => '/sales-order/transaction/cancel-so',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'amend_so',
                'name' => 'Amend SO',
                'route' => '/sales-order/transaction/amend-so',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'amend_approved_so',
                'name' => 'Amend Approved SO',
                'route' => '/sales-order/transaction/amend-approved-so',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'amend_so_price',
                'name' => 'Amend SO Price',
                'route' => '/sales-order/transaction/amend-so-price',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'amend_approved_so_price',
                'name' => 'Amend Approved SO Price',
                'route' => '/sales-order/transaction/amend-approved-so-price',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'close_so',
                'name' => 'Close SO',
                'route' => '/sales-order/transaction/close-so',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'close_so_by_period',
                'name' => 'Close SO by Period',
                'route' => '/sales-order/transaction/close-so-by-period',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'unclose_so',
                'name' => 'Unclose SO',
                'route' => '/sales-order/transaction/unclose-so',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'resubmit_rejected_so',
                'name' => 'Re-Submit Rejected SO for Credit Check',
                'route' => '/sales-order/transaction/resubmit-rejected-so',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'release_wo_by_so',
                'name' => 'Release WO by SO',
                'route' => '/sales-order/transaction/release-wo-by-so',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'print_so_log',
                'name' => 'Print SO Log',
                'route' => '/sales-order/transaction/print-so-log',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'print_so_jit_tracking',
                'name' => 'Print SO JIT Tracking',
                'route' => '/sales-order/transaction/print-so-jit-tracking',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            
            // Sales Order Setup
            [
                'key' => 'define_so_config',
                'name' => 'Define SO Config',
                'route' => '/sales-order/setup/define-so-config',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'define_so_period',
                'name' => 'Define SO Period',
                'route' => '/sales-order/setup/define-so-period',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'define_so_rough_cut',
                'name' => 'Define SO Rough Cut',
                'route' => '/sales-order/setup/define-so-rough-cut',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'define_ac_auto_wo',
                'name' => 'Define AC# Auto WO',
                'route' => '/sales-order/setup/define-ac-auto-wo',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'define_mc_auto_wo',
                'name' => 'Define MC Auto WO',
                'route' => '/sales-order/setup/define-mc-auto-wo',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'print_so_period',
                'name' => 'Print SO Period',
                'route' => '/sales-order/setup/print-so-period',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'print_so_rough_cut',
                'name' => 'Print SO Rough Cut',
                'route' => '/sales-order/setup/print-so-rough-cut',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'print_ac_auto_wo',
                'name' => 'Print AC# Auto WO',
                'route' => '/sales-order/setup/print-ac-auto-wo',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'print_mc_auto_wo',
                'name' => 'Print MC Auto WO',
                'route' => '/sales-order/setup/print-mc-auto-wo',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            
            // Sales Order Report
            [
                'key' => 'define_report_format',
                'name' => 'Define Report Format',
                'route' => '/sales-order/report/define-report-format',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'print_rough_cut_report',
                'name' => 'Print Rough Cut Report',
                'route' => '/sales-order/report/print-rough-cut-report',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'print_so_report',
                'name' => 'Print SO Report',
                'route' => '/sales-order/report/print-so-report',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            [
                'key' => 'print_so_cancel_report',
                'name' => 'Print SO Cancel Report',
                'route' => '/sales-order/report/print-so-cancel-report',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],
            
            // Customer Service
            [
                'key' => 'customer_service_dashboard',
                'name' => 'Customer Service Dashboard',
                'route' => '/customer-service/dashboard',
                'category' => 'sales_management',
                'parent' => 'sales_management'
            ],

            // Warehouse Management
            [
                'key' => 'warehouse_management',
                'name' => 'Warehouse Management',
                'route' => null,
                'category' => 'warehouse_management',
                'parent' => null
            ],
            
            // Delivery Order Setup
            [
                'key' => 'define_analysis_code',
                'name' => 'Define Analysis Code',
                'route' => '/warehouse-management/delivery-order/setup/define-analysis-code',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'define_transport_contractor',
                'name' => 'Define Transport Contractor',
                'route' => '/warehouse-management/delivery-order/setup/define-transport-contractor',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'define_vehicle_class',
                'name' => 'Define Vehicle Class',
                'route' => '/warehouse-management/delivery-order/setup/vehicle-class',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'define_vehicle',
                'name' => 'Define Vehicle',
                'route' => '/warehouse-management/delivery-order/setup/vehicle',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'define_dorn_code',
                'name' => 'Define DORN Code',
                'route' => '/warehouse-management/delivery-order/setup/define-dorn-code',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'define_greeting_message',
                'name' => 'Define Greeting Message',
                'route' => '/warehouse-management/delivery-order/setup/define-greeting-message',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'define_alternate_unit',
                'name' => 'Define Alternate Unit',
                'route' => '/warehouse-management/delivery-order/setup/define-alternate-unit',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'define_master_card_alternate_unit',
                'name' => 'Define Master Card Alternate Unit',
                'route' => '/warehouse-management/delivery-order/setup/define-master-card-alternate-unit',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'define_dorder_group',
                'name' => 'Define D/Order Group',
                'route' => '/warehouse-management/delivery-order/setup/define-dorder-group',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'define_users_dorder_group',
                'name' => 'Define User\'s D/Order Group',
                'route' => '/warehouse-management/delivery-order/setup/define-users-dorder-group',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'view_print_analysis_code',
                'name' => 'View & Print Analysis Code',
                'route' => '/warehouse-management/delivery-order/setup/view-print-analysis-code',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'view_print_vehicle_class',
                'name' => 'View & Print Vehicle Class',
                'route' => '/warehouse-management/delivery-order/setup/vehicle-class/view-print',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'view_print_vehicle',
                'name' => 'View & Print Vehicle',
                'route' => '/warehouse-management/delivery-order/setup/vehicle/view-print',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            
            // DO Processing
            [
                'key' => 'prepare_delivery_order_single',
                'name' => 'Prepare Delivery Order (Single Item)',
                'route' => '/warehouse-management/delivery-order/do-processing/prepare-single',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'prepare_delivery_order_multiple',
                'name' => 'Prepare Delivery Order (Multiple Item)',
                'route' => '/warehouse-management/delivery-order/do-processing/prepare-multiple',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'print_delivery_order',
                'name' => 'Print Delivery Order',
                'route' => '/warehouse-management/delivery-order/do-processing/print-delivery-order',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'print_do_proforma_invoice',
                'name' => 'Print DO Proforma Invoice',
                'route' => '/warehouse-management/delivery-order/do-processing/print-do-proforma',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'print_coa_result_by_wo',
                'name' => 'Print COA Result by WO#',
                'route' => '/warehouse-management/delivery-order/do-processing/print-coa-wo',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'print_coa_result_by_so',
                'name' => 'Print COA Result by SO#',
                'route' => '/warehouse-management/delivery-order/do-processing/print-coa-so',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'amend_delivery_order',
                'name' => 'Amend Delivery Order',
                'route' => '/warehouse-management/delivery-order/do-processing/amend-delivery-order',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'cancel_delivery_order',
                'name' => 'Cancel Delivery Order',
                'route' => '/warehouse-management/delivery-order/do-processing/cancel-delivery-order',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'reconcile_do_unapplied_fg',
                'name' => 'Reconcile Delivery Order Unapplied F/Goods',
                'route' => '/warehouse-management/delivery-order/do-processing/reconcile-unapplied-fg',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'view_print_do_log',
                'name' => 'View & Print Delivery Order Log',
                'route' => '/warehouse-management/delivery-order/do-processing/view-print-do-log',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'view_print_do_unapplied_fg',
                'name' => 'View & Print Delivery Order Unapplied F/Goods',
                'route' => '/warehouse-management/delivery-order/do-processing/view-print-unapplied-fg',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'customer_so_delivery_obsolete',
                'name' => 'Customer S/Order Delivery Schedule - Obsolote',
                'route' => '/warehouse-management/delivery-order/do-processing/customer-so-delivery-obsolete',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'sales_order_delivery_schedule',
                'name' => 'Sales Order Delivery Schedule',
                'route' => '/warehouse-management/delivery-order/do-processing/sales-order-delivery',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            
            // DORN Processing
            [
                'key' => 'issue_dorn',
                'name' => 'Issue DORN',
                'route' => '/warehouse-management/delivery-order/dorn-processing/issue-dorn',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'print_dorn',
                'name' => 'Print DORN',
                'route' => '/warehouse-management/delivery-order/dorn-processing/print-dorn',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'amend_dorn',
                'name' => 'Amend DORN',
                'route' => '/warehouse-management/delivery-order/dorn-processing/amend-dorn',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'cancel_dorn',
                'name' => 'Cancel DORN',
                'route' => '/warehouse-management/delivery-order/dorn-processing/cancel-dorn',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'view_print_dorn_log',
                'name' => 'View & Print DORN Log',
                'route' => '/warehouse-management/delivery-order/dorn-processing/view-print-dorn-log',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            
            // Manual DO Processing
            [
                'key' => 'activate_manual_configuration',
                'name' => 'Activate Manual Configuration',
                'route' => '/warehouse-management/delivery-order/manual-do-processing/activate-manual-configuration',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'register_manual_numbers',
                'name' => 'Register Manual Numbers',
                'route' => '/warehouse-management/delivery-order/manual-do-processing/register-manual-numbers',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'view_print_registered_manual_numbers_log',
                'name' => 'View & Print Registered Manual Numbers Log',
                'route' => '/warehouse-management/delivery-order/manual-do-processing/view-print-registered-manual-numbers-log',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            
            // Invoice Setup
            [
                'key' => 'define_tax_type',
                'name' => 'Define Tax Type',
                'route' => '/warehouse-management/invoice/setup/define-tax-type',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'define_tax_group',
                'name' => 'Define Tax Group',
                'route' => '/warehouse-management/invoice/setup/define-tax-group',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'define_customer_sales_tax_index',
                'name' => 'Define Customer Sales Tax Index',
                'route' => '/warehouse-management/invoice/setup/define-customer-sales-tax-index',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'view_print_tax_type',
                'name' => 'View & Print Tax Type',
                'route' => '/warehouse-management/invoice/setup/view-print-tax-type',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'view_print_tax_group',
                'name' => 'View & Print Tax Group',
                'route' => '/warehouse-management/invoice/setup/view-print-tax-group',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'view_print_customer_sales_tax_index',
                'name' => 'View & Print Customer Sales Tax Index',
                'route' => '/warehouse-management/invoice/setup/view-print-customer-sales-tax-index',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            
            // IV Processing
            [
                'key' => 'prepare_invoice_by_do_current_period',
                'name' => 'Prepare Invoice by D/Order (Current Period)',
                'route' => '/warehouse-management/invoice/iv-processing/prepare-by-do-current-period',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'prepare_invoice_by_do_open_period',
                'name' => 'Prepare Invoice by D/Order (Open Period)',
                'route' => '/warehouse-management/invoice/iv-processing/prepare-do-open',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'print_invoice',
                'name' => 'Print Invoice',
                'route' => '/warehouse-management/invoice/iv-processing/print-invoice',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'amend_invoice',
                'name' => 'Amend Invoice',
                'route' => '/warehouse-management/invoice/iv-processing/amend-invoice',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'cancel_active_invoice',
                'name' => 'Cancel Active Invoice',
                'route' => '/warehouse-management/invoice/iv-processing/cancel-active-invoice',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ],
            [
                'key' => 'view_print_invoice_log',
                'name' => 'View & Print Invoice Log',
                'route' => '/warehouse-management/invoice/iv-processing/view-print-invoice-log',
                'category' => 'warehouse_management',
                'parent' => 'warehouse_management'
            ]
        ];
    }

    /**
     * Create default permissions for a user (all access)
     */
    public static function createDefaultPermissions($userId)
    {
        $menuItems = self::getAllMenuItems();
        
        foreach ($menuItems as $item) {
            self::updateOrCreate(
                [
                    'user_id' => $userId,
                    'menu_key' => $item['key']
                ],
                [
                    'menu_name' => $item['name'],
                    'menu_route' => $item['route'],
                    'menu_category' => $item['category'],
                    'menu_parent' => $item['parent'],
                    'can_access' => true
                ]
            );
        }
    }

    /**
     * Get user permissions as array
     */
    public static function getUserPermissions($userId)
    {
        return self::where('user_id', $userId)
            ->where('can_access', true)
            ->pluck('menu_key')
            ->toArray();
    }
}
