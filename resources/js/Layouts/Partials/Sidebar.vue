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
      <div class="relative group">
        <Link
          href="/dashboard"
          class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg transition-colors"
          :class="{ 'bg-blue-600 text-white font-medium': currentPath === '/dashboard' }"
        >
          <i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i>
          <span>Dashboard</span>
        </Link>
      </div>

      <!-- System Manager -->
      <SidebarDropdown
        title="System Manager"
        icon="fas fa-cogs"
        :items="systemManagerItems"
        :current-path="currentPath"
      />

      <!-- Sales Management -->
      <SidebarDropdown
        title="Sales Management"
        icon="fas fa-chart-line"
        :items="salesManagementItems"
        :current-path="currentPath"
      />

      <!-- Warehouse Management -->
      <SidebarDropdown
        title="Warehouse Management"
        icon="fas fa-warehouse"
        :items="warehouseManagementItems"
        :current-path="currentPath"
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

<script setup>
import { ref, computed } from 'vue';
import { usePage, Link, router } from '@inertiajs/vue3';
import SidebarDropdown from './SidebarDropdown.vue';
import sidebarStore from './sidebarStore';

const page = usePage();
const user = computed(() => page.props.auth?.user);
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
  if (!route) return false;
  return currentPath.value === route;
};

const logout = () => {
  // Use Inertia router for logout
  router.post('/logout', {}, {
    preserveScroll: true,
    onSuccess: () => {
      window.location.href = '/login';
    },
    onError: () => {
      console.error('Logout failed');
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
      { title: 'Release Locked Password', icon: 'fas fa-unlock', route: null },
      { title: 'Define User Access Permission', icon: 'fas fa-user-lock', route: '/system-security/define-access' },
      { title: 'Copy & Paste User Access Permission', icon: 'fas fa-copy', route: '/system-security/copy-paste-access' },
      { title: 'View & Print User', icon: 'fas fa-users', route: '/system-security/view-print-user' }
    ]
  },
  {
    title: 'System Maintenance',
    icon: 'fas fa-wrench',
    children: [
      { title: 'Define ISO Currency', icon: 'fas fa-coins', route: '/iso-currency' },
      { title: 'Define Foreign Currency', icon: 'fas fa-money-bill-wave', route: '/foreign-currency' },
      { title: 'Define Business Form', icon: 'fas fa-file-alt', route: '/business-form' },
      { title: 'View & Print ISO Currency', icon: 'fas fa-list', route: '/iso-currency/view-print' },
      { title: 'View & Print Foreign Currency', icon: 'fas fa-list-alt', route: '/foreign-currency/view-print' },
      { title: 'View & Print Business Form', icon: 'fas fa-print', route: '/business-form/view-print' }
    ]
  },
  { title: 'System Administrator', icon: 'fas fa-user-cog', route: null },
  { title: 'DB Integrity Check', icon: 'fas fa-database', route: null }
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
          { title: 'View & Print Finishing', icon: 'fas fa-print', route: '/finishing/view-print' }
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
          { title: 'Approve MC', icon: 'fas fa-check', route: '/sales-management/system-requirement/master-card/approve-mc' },
          { title: 'Release Approved MC', icon: 'fas fa-unlock', route: '/sales-management/system-requirement/master-card/realese-approve-mc' },
          { title: 'Obsolete & Reactive MC', icon: 'fas fa-ban', route: '/sales-management/system-requirement/master-card/obsolete-reactive-mc' },
          { title: 'View & Print MC', icon: 'fas fa-print', route: '/sales-management/system-requirement/master-card/view-and-print-MC' },
          { title: 'View & Print MC Maintenance Log', icon: 'fas fa-file-alt', route: '/sales-management/system-requirement/master-card/view-and-print-mc-maintenance-log' },
          { title: 'View & Print MC Approval Log', icon: 'fas fa-file-alt', route: '/sales-management/system-requirement/master-card/view-and-print-mc-approval-log' },
          { title: 'View & Print Non-Active MC', icon: 'fas fa-file-alt', route: '/sales-management/system-requirement/master-card/view-and-print-non-active-mc' },
          { title: 'Initialized MC Maintenance Log', icon: 'fas fa-file-alt', route: '/sales-management/system-requirement/master-card/initialized-mc-maintenance-log' },
          { title: 'View & Print MC Print/DC Block Listing', icon: 'fas fa-file-alt', route: '/sales-management/system-requirement/master-card/view-and-print-mc-print-dc-block-listing' },
          { title: 'View & Print MC DC Block Matching', icon: 'fas fa-project-diagram', route: '/sales-management/system-requirement/master-card/view-and-print-mc-print-dc-block-matching' },
          { title: 'View & Print MC by Color', icon: 'fas fa-palette', route: '/sales-management/system-requirement/master-card/view-and-print-mc-by-color' },
          { title: 'View & Print MC by P/Size P/Quality', icon: 'fas fa-ruler-combined', route: '/sales-management/system-requirement/master-card/view-and-print-mc-by-psize-pquality' },
          { title: 'View & Print MC by Machine', icon: 'fas fa-cogs', route: '/sales-management/system-requirement/master-card/view-and-print-mc-by-machine' }
        ]
      },
    ]
  },
  {
    title: 'Standard Formula',
    icon: 'fas fa-flask',
    children: [
      { title: 'Setup Standard Formula Configuration', icon: 'fas fa-cogs', route: '/standard-formula-configuration' },
      {
        title: 'Setup Scoring Formula',
        icon: 'fas fa-cut',
        children: [
          { title: 'Define Scoring Formula', icon: 'fas fa-pen-fancy', route: '/scoring-formula' },
          { title: 'View & Print Scoring Formula', icon: 'fas fa-print', route: '/scoring-formula/view-print' }
        ]
      },
      {
        title: 'Setup Corrugator Run Size Formula',
        icon: 'fas fa-box',
        children: [
          { title: 'Define Product Design', icon: 'fas fa-drafting-compass', route: '/product-design/standard-formula' },
          { title: 'Define Corrugator', icon: 'fas fa-cog', route: '/standard-formula/setup-corrugator' },
          { title: 'Define Corrugator Spesification by Product', icon: 'fas fa-cogs', route: '/standard-formula/setup-corrugator-specification-by-product' },
          { title: 'Define Roll Trim by Corrugator', icon: 'fas fa-cut', route: '/standard-formula/setup-roll-trim-by-corrugator' },
          { title: 'Define Roll Trim by Product Design', icon: 'fas fa-ruler-combined', route: '/standard-formula/setup-roll-trim-by-product-design' },
          { title: 'Define Roll Size', icon: 'fas fa-ruler', route: '/standard-formula/setup-roll-size' },
          { title: 'Define Side Trim by Flute', icon: 'fas fa-layer-group', route: '/standard-formula/setup-side-trim-by-flute' },
          { title: 'Define Side Trim by Product Design', icon: 'fas fa-object-group', route: '/standard-formula/setup-side-trim-by-product-design' },
          { title: 'View & Print Product Design', icon: 'fas fa-print', route: '/standard-formula/setup-corrugator-run-size-formula/product-design/view-print' },
          { title: 'View & Print Corr. Spesification by Product', icon: 'fas fa-print', route: '/standard-formula/setup-corrugator-specification-by-product/view-print' },
          { title: 'View & Print Roll Trim by Corrugator', icon: 'fas fa-print', route: '/standard-formula/setup-roll-trim-by-corrugator/view-print' },
          { title: 'View & Print Roll Trim by Product Design', icon: 'fas fa-print', route: '/standard-formula/setup-roll-trim-by-product-design/view-print' },
          { title: 'View & Print Roll Size', icon: 'fas fa-print', route: '/standard-formula/setup-roll-size/view-print' },
          { title: 'View & Print Side Trim by Flute', icon: 'fas fa-print', route: '/standard-formula/setup-side-trim-by-flute/view-print' },
          { title: 'View & Print Side Trim by Product Design', icon: 'fas fa-print', route: '/standard-formula/setup-side-trim-by-product-design/view-print' }
        ]
      },
      {
        title: 'Define Stitching Computation Method',
        icon: 'fas fa-paperclip',
        children: [
          { title: 'Define Computation Method', icon: 'fas fa-calculator', route: '/standard-formula/stitching-computation-method' },
          { title: 'Define Finishing', icon: 'fas fa-paint-roller', route: '/standard-formula/stitching-computation/finishing' },
          { title: 'View & Print Finishing', icon: 'fas fa-print', route: '/standard-formula/stitching-computation/finishing/view-print' }
        ]
      },
      {
        title: 'Define Bundling Computation Method',
        icon: 'fas fa-cubes',
        children: [
          { title: 'Define Computation Method', icon: 'fas fa-calculator', route: '/standard-formula/bundling-computation/method' },
          { title: 'View & Print Computation Method', icon: 'fas fa-print', route: '/standard-formula/bundling-computation/view-print-method' }
        ]
      },
      {
        title: 'Define Diecut Computation Method',
        icon: 'fas fa-cut',
        children: [
          { title: 'Define Computation Formula', icon: 'fas fa-calculator', route: '/standard-formula/diecut-computation/formula' },
          { title: 'Define Product Design', icon: 'fas fa-drafting-compass', route: '/standard-formula/diecut-computation/product-design' },
          { title: 'View & Print Product Design', icon: 'fas fa-print', route: '/standard-formula/diecut-computation/view-print-product-design' }
        ]
      }
    ]
  },
  {
    title: 'Sales Order',
    icon: 'fas fa-file-invoice-dollar',
    children: [
      {
        title: 'Setup',
        icon: 'fas fa-cogs',
        children: [
          { title: 'Define SO Config', icon: 'fas fa-cog', route: '/sales-order/setup/define-so-config' },
          { title: 'Define SO Period', icon: 'fas fa-calendar-alt', route: '/sales-order/setup/define-so-period' },
          { title: 'Define SO Rough Cut', icon: 'fas fa-cut', route: '/sales-order/setup/define-so-rough-cut' },
          { title: 'Define AC# Auto WO', icon: 'fas fa-file-invoice', route: '/sales-order/setup/define-ac-auto-wo' },
          { title: 'Define MC Auto WO', icon: 'fas fa-file-invoice-dollar', route: '/sales-order/setup/define-mc-auto-wo' },
          { title: 'Print SO Period', icon: 'fas fa-print', route: '/sales-order/setup/print-so-period' },
          { title: 'Print SO Rough Cut', icon: 'fas fa-print', route: '/sales-order/setup/print-so-rough-cut' },
          { title: 'Print AC# Auto WO', icon: 'fas fa-print', route: '/sales-order/setup/print-ac-auto-wo' },
          { title: 'Print MC Auto WO', icon: 'fas fa-print', route: '/sales-order/setup/print-mc-auto-wo' },
        ]
      },
      {
        title: 'Transaction',
        icon: 'fas fa-exchange-alt',
        children: [
          { title: 'Prepare MC SO', icon: 'fas fa-file-invoice', route: '/sales-order/transaction/prepare-mc-so' },
          { title: 'Prepare SB SO', icon: 'fas fa-file-invoice-dollar', route: '/sales-order/transaction/prepare-sb-so' },
          { title: 'Prepare JIT SO', icon: 'fas fa-clock', route: '/sales-order/transaction/prepare-jit-so' },
          { title: 'Print SO', icon: 'fas fa-print', route: '/sales-order/transaction/print-so' },
          { title: 'Cancel SO', icon: 'fas fa-times-circle', route: '/sales-order/transaction/cancel-so' },
          { title: 'Amend SO', icon: 'fas fa-edit', route: '/sales-order/transaction/amend-so' },
          { title: 'Amend Approved SO', icon: 'fas fa-check-double', route: '/sales-order/transaction/amend-approved-so' },
          { title: 'Amend SO Price', icon: 'fas fa-dollar-sign', route: '/sales-order/transaction/amend-so-price' },
          { title: 'Amend Approved SO Price', icon: 'fas fa-check-dollar', route: '/sales-order/transaction/amend-approved-so-price' },
          { title: 'Close SO', icon: 'fas fa-check-circle', route: '/sales-order/transaction/close-so' },
          { title: 'Close SO by Period', icon: 'fas fa-calendar-check', route: '/sales-order/transaction/close-so-by-period' },
          { title: 'Unclose SO', icon: 'fas fa-undo', route: '/sales-order/transaction/unclose-so' },
          { title: 'Re-Submit Rejected SO for Credit Check', icon: 'fas fa-redo', route: '/sales-order/transaction/resubmit-rejected-so' },
          { title: 'Release WO by SO', icon: 'fas fa-unlock', route: '/sales-order/transaction/release-wo-by-so' },
          { title: 'Print SO Log', icon: 'fas fa-list-alt', route: '/sales-order/transaction/print-so-log' },
          { title: 'Print SO JIT Tracking', icon: 'fas fa-search', route: '/sales-order/transaction/print-so-jit-tracking' },
        ]
      },
      {
        title: 'Report',
        icon: 'fas fa-chart-pie',
        children: [
          { title: 'Define Report Format', icon: 'fas fa-file-invoice', route: '/sales-order/report/rough-cut-report/define-report-format' },
          { title: 'Print Rough Cut Report', icon: 'fas fa-print', route: '/sales-order/report/rough-cut-report/print-rough-cut-report' },
          { title: 'Print SO Report', icon: 'fas fa-print', route: '/sales-order/report/print-so-report' },
          { title: 'Print SO Cancel Report', icon: 'fas fa-print', route: '/sales-order/report/print-so-cancel-report' },
        ]
      },
    ]
  },
    {
    title: 'Customer Service',
    icon: 'fas fa-headset',
    children: [
      { title: 'Customer Service Dashboard', icon: 'fas fa-tachometer-alt', route: '/customer-service/dashboard' },
      { title: 'Customer Account Credit', icon: 'fas fa-credit-card', route: '/customer-service/account-credit' },
      { title: 'Sales Order Delivery Schedule', icon: 'fas fa-truck-loading', route: '/customer-service/delivery-schedule' },
      { title: 'Customer Finished Goods', icon: 'fas fa-boxes', route: '/customer-service/finished-goods' },
      { title: 'Production Monitoring Board', icon: 'fas fa-chart-bar', route: '/customer-service/production-monitoring-board' },
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
          { title: 'Define Customer Sales Tax Index', icon: 'fas fa-tags', route: '/warehouse-management/invoice/setup/define-customer-sales-tax-index' },
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