<template>
  <div class="flex flex-col h-screen bg-gray-800 text-white animate-fadeIn overflow-hidden">
    <!-- Header with animation -->
    <div class="px-4 py-3 border-b border-gray-700 relative z-10">
      <div class="flex items-center">
        <div class="w-10 h-10 flex items-center justify-center bg-blue-600 text-white rounded-full mr-3 pulse">
          <i class="fas fa-building text-xl"></i>
        </div>
        <h1 class="text-xl font-bold slide-in-right">ERP System</h1>
      </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-grow px-2 py-4 space-y-2 overflow-y-auto sidebar-content">
      <!-- Dashboard -->
      <div v-if="hasPermission('dashboard')" class="relative group">
        <Link
          href="/dashboard"
          class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg transition-all duration-200"
          :class="{
            'bg-blue-600 text-white font-semibold shadow-lg border-l-4 border-blue-300': isActive('/dashboard'),
            'sidebar-active': isActive('/dashboard')
          }"
          :style="isActive('/dashboard') ? 'background-color: #2563eb !important; color: white !important; border-left: 4px solid #93c5fd !important;' : ''"
          :title="`Current: ${currentPath} | Route: /dashboard | Active: ${isActive('/dashboard')}`"
        >
          <i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i>
          <span>Dashboard</span>
          <!-- Active indicator dot -->
          <div v-if="isActive('/dashboard')" class="ml-auto w-2 h-2 bg-blue-200 rounded-full animate-pulse"></div>
        </Link>
      </div>

      <!-- System Manager -->
      <SidebarDropdown
        v-if="hasPermission('system_manager')"
        title="System Manager"
        icon="fas fa-cogs"
        :items="filterItemsByPermission(systemManagerItems)"
        :current-path="currentPath"
        :has-permission="hasPermission"
      />

      <!-- Sales Management -->
      <SidebarDropdown
        v-if="hasPermission('sales_management')"
        title="Sales Management"
        icon="fas fa-chart-line"
        :items="filterItemsByPermission(salesManagementItems)"
        :current-path="currentPath"
        :has-permission="hasPermission"
      />

      <!-- Warehouse Management -->
      <SidebarDropdown
        v-if="hasPermission('warehouse_management')"
        title="Warehouse Management"
        icon="fas fa-warehouse"
        :items="filterItemsByPermission(warehouseManagementItems)"
        :current-path="currentPath"
        :has-permission="hasPermission"
      />
    </nav>

    <!-- User Profile & Logout -->
    <div class="border-t border-gray-700 p-4 sticky bottom-0 bg-gray-800 z-10 slide-in-up">
      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <div class="w-8 h-8 rounded-full bg-gray-600 flex items-center justify-center">
            <span class="text-sm font-medium">{{ userInitial }}</span>
          </div>
          <div class="ml-3">
            <span class="text-sm font-medium">{{ user?.username || 'Guest' }}</span>
            <p class="text-xs text-gray-400" v-if="user">
              <i class="fas fa-id-badge mr-1"></i>
              <span class="font-mono">{{ user.user_id }}</span>
            </p>
            <p class="text-xs text-gray-400" v-else>
              <i class="fas fa-exclamation-circle mr-1"></i>
              <span class="font-mono">Not logged in</span>
            </p>
          </div>
        </div>
        <div class="flex items-center space-x-3">
          <button
            @click="resetMenus"
            class="text-gray-300 hover:text-blue-400 transition-colors"
            title="Reset menu state"
          >
            <i class="fas fa-redo-alt text-sm"></i>
          </button>
        <button v-if="user" @click="logout" class="text-gray-300 hover:text-red-400 transition-colors">
          <i class="fas fa-sign-out-alt"></i>
        </button>
        <Link v-else href="/login" class="text-gray-300 hover:text-green-400 transition-colors">
          <i class="fas fa-sign-in-alt"></i>
        </Link>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.sidebar-active {
  background-color: #2563eb !important;
  color: white !important;
  font-weight: 600 !important;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
  border-left: 4px solid #93c5fd !important;
}

.pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: .5;
  }
}

.slide-in-right {
  animation: slideInRight 0.5s ease-out;
}

@keyframes slideInRight {
  from {
    opacity: 0;
    transform: translateX(30px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

.slide-in-up {
  animation: slideInUp 0.5s ease-out;
}

@keyframes slideInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fadeIn {
  animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}
</style>

<script setup>
import { ref, computed } from 'vue';
import { usePage, Link, router } from '@inertiajs/vue3';
import SidebarDropdown from './SidebarDropdown.vue';
import sidebarStore from './sidebarStore';

const page = usePage();
const user = computed(() => page.props.auth?.user);
const userPermissions = computed(() => page.props.auth?.permissions || []);
const userInitial = computed(() => user.value?.username ? user.value.username.charAt(0).toUpperCase() : 'G');
const currentPath = computed(() => page.url);

// Extract the base path from the URL (e.g., '/foreign-currency/view-print' -> '/foreign-currency')
const getBasePath = (path) => {
  if (!path) return '';
  const parts = path.split('/').filter(Boolean);
  return parts.length > 0 ? `/${parts[0]}` : '';
};

// Check if the current route matches the given route exactly
const isActive = (route) => {
  try {
    if (!route || !currentPath.value) return false;

    // Normalize paths - remove trailing slashes and convert to lowercase
    const current = currentPath.value.toLowerCase().replace(/\/+$/, '') || '/';
    const target = route.toLowerCase().replace(/\/+$/, '') || '/';

    // Check for exact match or if current path starts with target path
    const isExactMatch = current === target;
    const isSubPath = current.startsWith(target + '/');
    const isMatch = isExactMatch || isSubPath;

    return isMatch;
  } catch (error) {
    return false;
  }
};

// Check if user has permission for a specific menu
const hasPermission = (menuKey) => {
  if (!user.value) return false;
  
  // Temporary: Allow define_machine for testing
  if (menuKey === 'define_machine' || menuKey === 'view_print_machine') {
    return true;
  }
  
  return userPermissions.value.includes(menuKey);
};

// Filter menu items based on permissions (recursive for nested children)
const filterItemsByPermission = (items) => {
  return items.map(item => {
    if (item.children) {
      // For items with children, filter the children recursively
      const filteredChildren = item.children
        .map(child => {
          if (child.children) {
            // Handle nested children (sub-sub menu)
            const nestedFilteredChildren = child.children.filter(nestedChild => {
              const permissionKey = getPermissionKeyFromTitle(nestedChild.title);
              return hasPermission(permissionKey);
            });

            return {
              ...child,
              children: nestedFilteredChildren
            };
          } else {
            // Handle direct children
            const permissionKey = getPermissionKeyFromTitle(child.title);
            return hasPermission(permissionKey) ? child : null;
          }
        })
        .filter(child => {
          // Keep item if it has children or if it passed permission check
          return child !== null && (child.children?.length > 0 || !child.children);
        });

      return {
        ...item,
        children: filteredChildren
      };
    }
    return item;
  });
};

// Map menu titles to permission keys
const getPermissionKeyFromTitle = (title) => {
  const titleMap = {
    // System Manager
    'Define User': 'define_user',
    'Amend User Password': 'amend_user_password',
    'Define User Access Permission': 'define_user_access_permission',
    'Copy & Paste User Access Permission': 'copy_paste_user_access_permission',
    'View & Print User': 'view_print_user',

    // Sales Management - Sales Configuration
    'Define Sales Configuration': 'define_sales_configuration',

    // Sales Management - Standard Requirement
    'Define Sales Team': 'define_sales_team',
    'Define Salesperson': 'define_salesperson',
    'Define Salesperson Team': 'define_salesperson_team',
    'Define Industry': 'define_industry',
    'Define Geo': 'define_geo',
    'Define Product Group': 'define_product_group',
    'Define Product': 'define_product',
    'Define Product Design': 'define_product_design',
    'Define Scoring Tool': 'define_scoring_tool',
    'Define Paper Quality': 'define_paper_quality',
    'Define Paper Flute': 'define_paper_flute',
    'Define Paper Size': 'define_paper_size',
    'Define Color Group': 'define_color_group',
    'Define Color': 'define_color',
    'Define Finishing': 'define_finishing',
    'Define Analysis Code': 'define_analysis_code',

    // Sales Management - Customer Account
    'Define Customer Group': 'define_customer_group',
    'Update Customer Account': 'update_customer_account',
    'Define Customer Alternate Address': 'define_customer_alternate_address',
    'Define Customer Sales Type': 'define_customer_sales_type',

    // Sales Management - Customer Account
    'Obsolete/Reactive Customer A/C': 'obsolete_reactive_customer_ac',

    // Sales Management - Master Card
    'Update MC': 'update_mc',
    'Approve MC': 'approve_mc',
    'Release Approved MC': 'release_approved_mc',
    'Obsolete & Reactive MC': 'obsolete_reactive_mc',
    'View & Print MC': 'view_print_mc',
    'View & Print MC Maintenance Log': 'view_print_mc_maintenance_log',
    'View & Print MC Approval Log': 'view_print_mc_approval_log',
    'View & Print Non-Active MC': 'view_print_non_active_mc',
    'Initialized MC Maintenance Log': 'initialized_mc_maintenance_log',
    'View & Print MC Print/DC Block Listing': 'view_print_mc_print_dc_block_listing',
    'View & Print MC DC Block Matching': 'view_print_mc_dc_block_matching',
    'View & Print MC by Color': 'view_print_mc_by_color',
    'View & Print MC by P/Size P/Quality': 'view_print_mc_by_psize_pquality',
    'View & Print MC by Machine': 'view_print_mc_by_machine',

    // Sales Management - Standard Requirement
    'Define Sales Team': 'define_sales_team',
    'Define Salesperson': 'define_salesperson',
    'Define Salesperson Team': 'define_salesperson_team',
    'Define Industry': 'define_industry',
    'Define Geo': 'define_geo',
    'Define Product Group': 'define_product_group',
    'Define Product': 'define_product',
    'Define Product Design': 'define_product_design',
    'Define Scoring Tool': 'define_scoring_tool',
    'Define Paper Quality': 'define_paper_quality',
    'Obsolete/Unobsolete Paper Quality': 'obsolete_unobsolete_paper_quality',
    'Define Paper Flute': 'define_paper_flute',
    'Define Paper Size': 'define_paper_size',
    'Define Color Group': 'define_color_group',
    'Define Color': 'define_color',
    'Define Finishing': 'define_finishing',
    'Define Analysis Code': 'define_analysis_code',
    'Define Stitch Wire': 'define_stitch_wire',
    'Define Chemical Coat': 'define_chemical_coat',
    'Define Reinforcement Tape': 'define_reinforcement_tape',
    'Define Bundling String': 'define_bundling_string',
    'Define Wrapping Material': 'define_wrapping_material',
    'Define Glueing Material': 'define_glueing_material',
    'Define Machine': 'define_machine',
    'View & Print Sales Team': 'view_print_sales_team',
    'View & Print Salesperson': 'view_print_salesperson',
    'View & Print Salesperson Team': 'view_print_salesperson_team',
    'View & Print Industry': 'view_print_industry',
    'View & Print Geo': 'view_print_geo',
    'View & Print Product Group': 'view_print_product_group',
    'View & Print Product': 'view_print_product',
    'View & Print Product Design': 'view_print_product_design',
    'View & Print Paper Quality': 'view_print_paper_quality',
    'View & Print Paper Flute': 'view_print_paper_flute',
    'View & Print Paper Size': 'view_print_paper_size',
    'View & Print Scoring Tool': 'view_print_scoring_tool',
    'View & Print Color Group': 'view_print_color_group',
    'View & Print Color': 'view_print_color',
    'View & Print Finishing': 'view_print_finishing',
    'View & Print Stitch Wire': 'view_print_stitch_wire',
    'View & Print Chemical Coat': 'view_print_chemical_coat',
    'View & Print Reinforcement Tape': 'view_print_reinforcement_tape',
    'View & Print Bundling String': 'view_print_bundling_string',
    'View & Print Wrapping Material': 'view_print_wrapping_material',
    'View & Print Glueing Material': 'view_print_glueing_material',
    'View & Print Machine': 'view_print_machine',
    // Sales Management - Customer Account
    'Define Customer Group': 'define_customer_group',
    'Update Customer Account': 'update_customer_account',
    'Define Customer Alternate Address': 'define_customer_alternate_address',
    'Define Customer Sales Type': 'define_customer_sales_type',
    'View & Print Customer Group': 'view_print_customer_group',
    'View & Print Customer Account': 'view_print_customer_account',
    'View & Print Customer Alternate Address': 'view_print_customer_alternate_address',
    'View & Print Customer Sales Type': 'view_print_customer_sales_type',
    'View & Print Non-Active Customer': 'view_print_non_active_customer',

    // Sales Management - Sales Order
    'Prepare MC SO': 'prepare_mc_so',
    'Prepare SB SO': 'prepare_sb_so',
    'Prepare JIT SO': 'prepare_jit_so',
    'Print SO': 'print_so',
    'Cancel SO': 'cancel_so',
    'Amend SO': 'amend_so',
    'Amend Approved SO': 'amend_approved_so',
    'Amend SO Price': 'amend_so_price',
    'Amend Approved SO Price': 'amend_approved_so_price',
    'Close SO': 'close_so',
    'Close SO by Period': 'close_so_by_period',
    'Unclose SO': 'unclose_so',
    'Re-Submit Rejected SO for Credit Check': 'resubmit_rejected_so',
    'Release WO by SO': 'release_wo_by_so',
    'Print SO Log': 'print_so_log',
    'Print SO JIT Tracking': 'print_so_jit_tracking',

    // Sales Order Setup
    'Define SO Config': 'define_so_config',
    'Define SO Period': 'define_so_period',
    'Define SO Rough Cut': 'define_so_rough_cut',
    'Define AC# Auto WO': 'define_ac_auto_wo',
    'Define MC Auto WO': 'define_mc_auto_wo',
    'Print SO Period': 'print_so_period',
    'Print SO Rough Cut': 'print_so_rough_cut',
    'Print AC# Auto WO': 'print_ac_auto_wo',
    'Print MC Auto WO': 'print_mc_auto_wo',

    // Sales Order Report
    'Define Report Format': 'define_report_format',
    'Print Rough Cut Report': 'print_rough_cut_report',
    'Print SO Report': 'print_so_report',
    'Print SO Cancel Report': 'print_so_cancel_report',

    // Customer Service
    'Customer Service Dashboard': 'customer_service_dashboard',

    // Warehouse Management - Delivery Order Setup
    'Define Analysis Code': 'define_analysis_code',
    'Define Transport Contractor': 'define_transport_contractor',
    'Define Vehicle Class': 'define_vehicle_class',
    'Define Vehicle': 'define_vehicle',
    'Define DORN Code': 'define_dorn_code',
    'Define Greeting Message': 'define_greeting_message',
    'Define Alternate Unit': 'define_alternate_unit',
    'Define Master Card Alternate Unit': 'define_master_card_alternate_unit',
    'Define D/Order Group': 'define_dorder_group',
    'Define User\'s D/Order Group': 'define_users_dorder_group',
    'View & Print Analysis Code': 'view_print_analysis_code',
    'View & Print Vehicle Class': 'view_print_vehicle_class',
    'View & Print Vehicle': 'view_print_vehicle',

    // Warehouse Management - DO Processing
    'Prepare Delivery Order (Single Item)': 'prepare_delivery_order_single',
    'Prepare Delivery Order (Multiple Item)': 'prepare_delivery_order_multiple',
    'Print Delivery Order': 'print_delivery_order',
    'Print DO Proforma Invoice': 'print_do_proforma_invoice',
    'Print COA Result by WO#': 'print_coa_result_by_wo',
    'Print COA Result by SO#': 'print_coa_result_by_so',
    'Amend Delivery Order': 'amend_delivery_order',
    'Cancel Delivery Order': 'cancel_delivery_order',
    'Reconcile Delivery Order Unapplied F/Goods': 'reconcile_do_unapplied_fg',
    'View & Print Delivery Order Log': 'view_print_do_log',
    'View & Print Delivery Order Unapplied F/Goods': 'view_print_do_unapplied_fg',
    'Customer S/Order Delivery Schedule - Obsolote': 'customer_so_delivery_obsolete',
    'Sales Order Delivery Schedule': 'sales_order_delivery_schedule',

    // Warehouse Management - DORN Processing
    'Issue DORN': 'issue_dorn',
    'Print DORN': 'print_dorn',
    'Amend DORN': 'amend_dorn',
    'Cancel DORN': 'cancel_dorn',
    'View & Print DORN Log': 'view_print_dorn_log',

    // Warehouse Management - Manual DO Processing
    'Activate Manual Configuration': 'activate_manual_configuration',
    'Register Manual Numbers': 'register_manual_numbers',
    'View & Print Registered Manual Numbers Log': 'view_print_registered_manual_numbers_log',

    // Warehouse Management - Invoice Setup
    'Define Tax Type': 'define_tax_type',
    'Define Tax Group': 'define_tax_group',
    'Define Customer Sales Tax Index': 'define_customer_sales_tax_index',
    'View & Print Tax Type': 'view_print_tax_type',
    'View & Print Tax Group': 'view_print_tax_group',
    'View & Print Customer Sales Tax Index': 'view_print_customer_sales_tax_index',

    // Warehouse Management - IV Processing
    'Prepare Invoice by D/Order (Current Period)': 'prepare_invoice_by_do_current_period',
    'Prepare Invoice by D/Order (Open Period)': 'prepare_invoice_by_do_open_period',
    'Print Invoice': 'print_invoice',
    'Amend Invoice': 'amend_invoice',
    'Cancel Active Invoice': 'cancel_active_invoice',
    'View & Print Invoice Log': 'view_print_invoice_log'
  };

  return titleMap[title] || title.toLowerCase().replace(/\s+/g, '_').replace(/[^a-z0-9_]/g, '');
};

const logout = () => {
  // Use Inertia router for logout with fresh CSRF token
  router.post('/logout', {
    _token: page.props.csrf
  }, {
    preserveScroll: false,
    preserveState: false,
    onStart: () => {
      console.log('Logout started');
    },
    onSuccess: () => {
      console.log('Logout successful');
      // Force redirect to login page
      window.location.replace('/login');
    },
    onError: (errors) => {
      console.error('Logout failed:', errors);
      // Try to get fresh CSRF and retry once
      if (!logout.retried) {
        logout.retried = true;
        setTimeout(() => {
          router.reload({
            onSuccess: () => {
              logout();
            }
          });
        }, 100);
      } else {
        // Force redirect even if logout fails after retry
        window.location.replace('/login');
      }
    },
    onFinish: () => {
      console.log('Logout finished');
    }
  });
};

// Reset all dropdown menus to their closed state
const resetMenus = () => {
  sidebarStore.resetState();
};

// Function to check if a menu is expanded
const isExpanded = (menuId) => {
  return sidebarStore.isOpen(menuId);
};

// Function to toggle menu expansion
const toggleExpanded = (menuId) => {
  sidebarStore.toggle(menuId);
};

// System Manager Items
const systemManagerItems = [
  {
    title: 'System Security',
    icon: 'fas fa-shield-alt',
    children: [
      { title: 'Define User', icon: 'fas fa-user-plus', route: '/user' },
      { title: 'Amend User Password', icon: 'fas fa-key', route: '/system-security/amend-password' },
      { title: 'Define User Access Permission', icon: 'fas fa-user-lock', route: '/system-security/define-access' },
      { title: 'Copy & Paste User Access Permission', icon: 'fas fa-copy', route: '/system-security/copy-paste-access' },
      { title: 'View & Print User', icon: 'fas fa-users', route: '/system-security/view-print-user' }
    ]
  }
];

// Sales Management Items
const salesManagementItems = [
  {
    title: 'System Requirement',
    icon: 'fas fa-clipboard-list',
    children: [
      {
        title: 'Sales Configuration',
        icon: 'fas fa-cog',
        children: [
          { title: 'Define Sales Configuration', icon: 'fas fa-sliders-h', route: '/sales-configuration' }
        ]
      },
      {
        title: 'Standard Requirement',
        icon: 'fas fa-clipboard-check',
        children: [
          { title: 'Define Sales Team', icon: 'fas fa-users-cog', route: '/sales-team' },
          { title: 'Define Salesperson', icon: 'fas fa-user-tie', route: '/sales-person' },
          { title: 'Define Salesperson Team', icon: 'fas fa-user-friends', route: '/sales-person-team' },
          { title: 'Define Industry', icon: 'fas fa-industry', route: '/industry' },
          { title: 'Define Geo', icon: 'fas fa-globe', route: '/geo' },
          { title: 'Define Product Group', icon: 'fas fa-boxes', route: '/product-group' },
          { title: 'Define Product', icon: 'fas fa-box', route: '/product' },
          { title: 'Define Product Design', icon: 'fas fa-drafting-compass', route: '/product-design' },
          { title: 'Define Scoring Tool', icon: 'fas fa-cut', route: '/scoring-tool' },
          { title: 'Define Paper Quality', icon: 'fas fa-scroll', route: '/paper-quality' },
          { title: 'Obsolete/Unobsolete Paper Quality', icon: 'fas fa-ban', route: '/paper-quality/status' },
          { title: 'Define Paper Flute', icon: 'fas fa-layer-group', route: '/paper-flute' },
          { title: 'Define Paper Size', icon: 'fas fa-ruler-combined', route: '/paper-size' },
          { title: 'Define Color Group', icon: 'fas fa-palette', route: '/color-group' },
          { title: 'Define Color', icon: 'fas fa-fill-drip', route: '/color' },
          { title: 'Define Finishing', icon: 'fas fa-paint-roller', route: '/finishing' },
          { title: 'Define Analysis Code', icon: 'fas fa-code-branch', route: '/analysis-code' },
          { title: 'Define Stitch Wire', icon: 'fas fa-paperclip', route: '/stitch-wire' },
          { title: 'Define Chemical Coat', icon: 'fas fa-vial', route: '/chemical-coat' },
          { title: 'Define Reinforcement Tape', icon: 'fas fa-tape', route: '/reinforcement-tape' },
          { title: 'Define Bundling String', icon: 'fas fa-link', route: '/bundling-string' },
          { title: 'Define Wrapping Material', icon: 'fas fa-box-open', route: '/wrapping-material' },
          { title: 'Define Glueing Material', icon: 'fas fa-vial', route: '/glueing-material' },
          { title: 'Define Machine', icon: 'fas fa-cogs', route: '/machine' },
          // View & Print Section
          { title: 'View & Print Sales Team', icon: 'fas fa-print', route: '/sales-team/view-print' },
          { title: 'View & Print Salesperson', icon: 'fas fa-print', route: '/sales-person/view-print' },
          { title: 'View & Print Salesperson Team', icon: 'fas fa-print', route: '/sales-person-team/view-print' },
          { title: 'View & Print Industry', icon: 'fas fa-print', route: '/industry/view-print' },
          { title: 'View & Print Geo', icon: 'fas fa-print', route: '/geo/view-print' },
          { title: 'View & Print Product Group', icon: 'fas fa-print', route: '/product-group/view-print' },
          { title: 'View & Print Product', icon: 'fas fa-print', route: '/product/view-print' },
          { title: 'View & Print Product Design', icon: 'fas fa-print', route: '/product-design/view-print' },
          { title: 'View & Print Paper Quality', icon: 'fas fa-print', route: '/paper-quality/view-print' },
          { title: 'View & Print Paper Flute', icon: 'fas fa-print', route: '/paper-flute/view-print' },
          { title: 'View & Print Paper Size', icon: 'fas fa-print', route: '/paper-size/view-print' },
          { title: 'View & Print Scoring Tool', icon: 'fas fa-print', route: '/scoring-tool/view-print' },
          { title: 'View & Print Color Group', icon: 'fas fa-print', route: '/color-group/view-print' },
          { title: 'View & Print Color', icon: 'fas fa-print', route: '/color/view-print' },
          { title: 'View & Print Finishing', icon: 'fas fa-print', route: '/finishing/view-print' },
          { title: 'View & Print Stitch Wire', icon: 'fas fa-print', route: '/stitch-wire/view-print' },
          { title: 'View & Print Chemical Coat', icon: 'fas fa-print', route: '/chemical-coat/view-print' },
          { title: 'View & Print Reinforcement Tape', icon: 'fas fa-print', route: '/reinforcement-tape/view-print' },
          { title: 'View & Print Bundling String', icon: 'fas fa-print', route: '/bundling-string/view-print' },
          { title: 'View & Print Wrapping Material', icon: 'fas fa-print', route: '/wrapping-material/view-print' },
          { title: 'View & Print Glueing Material', icon: 'fas fa-print', route: '/glueing-material/view-print' },
          { title: 'View & Print Machine', icon: 'fas fa-print', route: '/machine/view-print' }
        ]
      },
      {
        title: 'Customer Account',
        icon: 'fas fa-user-circle',
        children: [
          { title: 'Define Customer Group', icon: 'fas fa-users', route: '/customer-group' },
          { title: 'Update Customer Account', icon: 'fas fa-user-edit', route: '/update-customer-account' },
          { title: 'Obsolete/Reactive Customer A/C', icon: 'fas fa-user-clock', route: '/obsolete-reactive-customer-account' },
          { title: 'Define Customer Alternate Address', icon: 'fas fa-map-marked-alt', route: '/customer-alternate-address' },
          { title: 'Define Customer Sales Type', icon: 'fas fa-tags', route: '/customer-sales-type' },
          { title: 'View & Print Customer Group', icon: 'fas fa-list', route: '/customer-group/view-print' },
          { title: 'View & Print Customer Account', icon: 'fas fa-list', route: '/update-customer-account/view-print' },
          { title: 'View & Print Customer Alternate Address', icon: 'fas fa-list', route: '/customer-alternate-address/view-print' },
          { title: 'View & Print Customer Sales Type', icon: 'fas fa-list', route: '/customer-sales-type/view-print' },
          { title: 'View & Print Non-Active Customer', icon: 'fas fa-list', route: '/obsolete-reactive-customer-account/view-print' },

        ]
      },
      {
        title: 'Master Card',
        icon: 'fas fa-id-card',
        children: [
          { title: 'Update MC', icon: 'fas fa-edit', route: '/sales-management/system-requirement/master-card/update-mc' },
          { title: 'Obsolete & Reactive MC', icon: 'fas fa-ban', route: '/sales-management/system-requirement/master-card/obsolete-reactive-mc' },
          { title: 'View & Print MC', icon: 'fas fa-print', route: '/sales-management/system-requirement/master-card/view-and-print-MC' },
        ]
      },
    ]
  },
  {
    title: 'Sales Order',
    icon: 'fas fa-file-invoice-dollar',
    children: [
      {
        title: 'Transaction',
        icon: 'fas fa-exchange-alt',
        children: [
          { title: 'Prepare MC SO', icon: 'fas fa-file-invoice', route: '/sales-order/transaction/prepare-mc-so' },
          { title: 'Print SO', icon: 'fas fa-print', route: '/sales-order/transaction/print-so' },
          { title: 'Cancel SO', icon: 'fas fa-times-circle', route: '/sales-order/transaction/cancel-so' },
          { title: 'Amend SO', icon: 'fas fa-edit', route: '/sales-order/transaction/amend-so' },
        ]
      },
    ]
  },
    {
    title: 'Customer Service',
    icon: 'fas fa-headset',
    children: [
      { title: 'Customer Service Dashboard', icon: 'fas fa-tachometer-alt', route: '/customer-service/dashboard' }
    ]
  },
];

// Warehouse Management Items
const warehouseManagementItems = [
  {
    title: 'Delivery Order',
    icon: 'fas fa-truck',
    children: [
      {
        title: 'Setup',
        icon: 'fas fa-cogs',
        children: [
          { title: 'Define Analysis Code', icon: 'fas fa-tags', route: '/warehouse-management/delivery-order/setup/define-analysis-code' },
          { title: 'Define Transport Contractor', icon: 'fas fa-truck-moving', route: '/warehouse-management/delivery-order/setup/define-transport-contractor' },
          { title: 'Define Vehicle Class', icon: 'fas fa-car-side', route: '/warehouse-management/delivery-order/setup/vehicle-class' },
          { title: 'Define Vehicle', icon: 'fas fa-truck-pickup', route: '/warehouse-management/delivery-order/setup/vehicle' },
          { title: 'Define DORN Code', icon: 'fas fa-barcode', route: '/warehouse-management/delivery-order/setup/define-dorn-code' },
          { title: 'Define Greeting Message', icon: 'fas fa-comment-dots', route: '/warehouse-management/delivery-order/setup/define-greeting-message' },
          { title: 'Define Alternate Unit', icon: 'fas fa-balance-scale', route: '/warehouse-management/delivery-order/setup/define-alternate-unit' },
          { title: 'Define Master Card Alternate Unit', icon: 'fas fa-id-card', route: '/warehouse-management/delivery-order/setup/define-master-card-alternate-unit' },
          { title: 'Define D/Order Group', icon: 'fas fa-object-group', route: '/warehouse-management/delivery-order/setup/define-dorder-group' },
          { title: 'Define User\'s D/Order Group', icon: 'fas fa-users-cog', route: '/warehouse-management/delivery-order/setup/define-users-dorder-group' },
          { title: 'View & Print Analysis Code', icon: 'fas fa-print', route: '/warehouse-management/delivery-order/setup/view-print-analysis-code' },
          { title: 'View & Print Vehicle Class', icon: 'fas fa-print', route: '/warehouse-management/delivery-order/setup/vehicle-class/view-print' },
          { title: 'View & Print Vehicle', icon: 'fas fa-print', route: '/warehouse-management/delivery-order/setup/vehicle/view-print' },
        ]
      },
      {
        title: 'DO Processing',
        icon: 'fas fa-tasks',
        children: [
          { title: 'Prepare Delivery Order (Single Item)', icon: 'fas fa-file-invoice', route: '/warehouse-management/delivery-order/do-processing/prepare-single' },
          { title: 'Prepare Delivery Order (Multiple Item)', icon: 'fas fa-file-invoice-dollar', route: '/warehouse-management/delivery-order/do-processing/prepare-multiple' },
          { title: 'Print Delivery Order', icon: 'fas fa-print', route: '/warehouse-management/delivery-order/do-processing/print-delivery-order' },
          { title: 'Print DO Proforma Invoice', icon: 'fas fa-print', route: '/warehouse-management/delivery-order/do-processing/print-do-proforma' },
          { title: 'Print COA Result by WO#', icon: 'fas fa-print', route: '/warehouse-management/delivery-order/do-processing/print-coa-wo' },
          { title: 'Print COA Result by SO#', icon: 'fas fa-print', route: '/warehouse-management/delivery-order/do-processing/print-coa-so' },
          { title: 'Amend Delivery Order', icon: 'fas fa-edit', route: '/warehouse-management/delivery-order/do-processing/amend-delivery-order' },
          { title: 'Cancel Delivery Order', icon: 'fas fa-times-circle', route: '/warehouse-management/delivery-order/do-processing/cancel-delivery-order' },
          { title: 'Reconcile Delivery Order Unapplied F/Goods', icon: 'fas fa-sync-alt', route: '/warehouse-management/delivery-order/do-processing/reconcile-unapplied-fg' },
          { title: 'View & Print Delivery Order Log', icon: 'fas fa-history', route: '/warehouse-management/delivery-order/do-processing/view-print-do-log' },
          { title: 'View & Print Delivery Order Unapplied F/Goods', icon: 'fas fa-file-alt', route: '/warehouse-management/delivery-order/do-processing/view-print-unapplied-fg' },
          { title: 'Customer S/Order Delivery Schedule - Obsolote', icon: 'fas fa-calendar-times', route: '/warehouse-management/delivery-order/do-processing/customer-so-delivery-obsolete' },
          { title: 'Sales Order Delivery Schedule', icon: 'fas fa-calendar-alt', route: '/warehouse-management/delivery-order/do-processing/sales-order-delivery' },
        ]
      },
      {
        title: 'DORN Processing',
        icon: 'fas fa-tasks',
        children: [
          { title: 'Issue DORN', icon: 'fas fa-file-invoice', route: '/warehouse-management/delivery-order/dorn-processing/issue-dorn' },
          { title: 'Print DORN', icon: 'fas fa-print', route: '/warehouse-management/delivery-order/dorn-processing/print-dorn' },
          { title: 'Amend DORN', icon: 'fas fa-edit', route: '/warehouse-management/delivery-order/dorn-processing/amend-dorn' },
          { title: 'Cancel DORN', icon: 'fas fa-times-circle', route: '/warehouse-management/delivery-order/dorn-processing/cancel-dorn' },
          { title: 'View & Print DORN Log', icon: 'fas fa-history', route: '/warehouse-management/delivery-order/dorn-processing/view-print-dorn-log' },
        ]
      },
      {
        title: 'Manual DO Processing',
        icon: 'fas fa-edit',
        children: [
          { title: 'Activate Manual Configuration', icon: 'fas fa-cogs', route: '/warehouse-management/delivery-order/manual-do-processing/activate-manual-configuration' },
          { title: 'Register Manual Numbers', icon: 'fas fa-clipboard-list', route: '/warehouse-management/delivery-order/manual-do-processing/register-manual-numbers' },
          { title: 'View & Print Registered Manual Numbers Log', icon: 'fas fa-history', route: '/warehouse-management/delivery-order/manual-do-processing/view-print-registered-manual-numbers-log' },
        ]
      },
    ]
  },
  {
    title: 'Invoice',
    icon: 'fas fa-file-invoice',
    children: [
      {
        title: 'Setup',
        icon: 'fas fa-cogs',
        children: [
          { title: 'Define Tax Type', icon: 'fas fa-percent', route: '/warehouse-management/invoice/setup/define-tax-type' },
          { title: 'Define Tax Group', icon: 'fas fa-layer-group', route: '/warehouse-management/invoice/setup/define-tax-group' },
          { title: 'Define Customer Sales Tax Index', icon: 'fas fa-user-tag', route: '/warehouse-management/invoice/setup/define-customer-sales-tax-index' },
          { title: 'View & Print Tax Type', icon: 'fas fa-print', route: '/warehouse-management/invoice/setup/view-print-tax-type' },
          { title: 'View & Print Tax Group', icon: 'fas fa-print', route: '/warehouse-management/invoice/setup/view-print-tax-group' },
          { title: 'View & Print Customer Sales Tax Index', icon: 'fas fa-print', route: '/warehouse-management/invoice/setup/view-print-customer-sales-tax-index' },
        ]
      },
      {
        title: 'IV Processing',
        icon: 'fas fa-tasks',
        children: [
          { title: 'Prepare Invoice by D/Order (Current Period)', icon: 'fas fa-file-invoice', route: '/warehouse-management/invoice/iv-processing/prepare-by-do-current-period' },
          { title: 'Prepare Invoice by D/Order (Open Period)', icon: 'fas fa-file-invoice', route: '/warehouse-management/invoice/iv-processing/prepare-do-open' },
          { title: 'Print Invoice', icon: 'fas fa-print', route: '/warehouse-management/invoice/iv-processing/print-invoice' },
          { title: 'Amend Invoice', icon: 'fas fa-edit', route: '/warehouse-management/invoice/iv-processing/amend-invoice' },
          { title: 'Cancel Active Invoice', icon: 'fas fa-times-circle', route: '/warehouse-management/invoice/iv-processing/cancel-active-invoice' },
          { title: 'View & Print Invoice Log', icon: 'fas fa-history', route: '/warehouse-management/invoice/iv-processing/view-print-invoice-log' },
        ]
      },
    ]
  },
];
</script>

<style scoped>
/* Custom scrollbar for webkit browsers */
.sidebar-content::-webkit-scrollbar {
  width: 8px;
}
.sidebar-content::-webkit-scrollbar-track {
  background: #2d3748; /* bg-gray-800 */
}
.sidebar-content::-webkit-scrollbar-thumb {
  background: #4a5568; /* bg-gray-600 */
  border-radius: 4px;
}
.sidebar-content::-webkit-scrollbar-thumb:hover {
  background: #718096; /* bg-gray-500 */
}

/* Animations */
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
  }
.animate-fadeIn {
  animation: fadeIn 0.5s ease-in-out;
}

@keyframes slideInRight {
  from { transform: translateX(-20px); opacity: 0; }
  to { transform: translateX(0); opacity: 1; }
}
.slide-in-right {
  animation: slideInRight 0.5s ease-in-out forwards;
}

@keyframes slideInUp {
  from { transform: translateY(20px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}
.slide-in-up {
  animation: slideInUp 0.5s ease-in-out forwards;
}

@keyframes pulse {
  0%, 100% {
    transform: scale(1);
    box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7);
  }
  50% {
    transform: scale(1.05);
    box-shadow: 0 0 0 10px rgba(59, 130, 246, 0);
  }
}
.pulse {
  animation: pulse 2s infinite;
}
</style>
