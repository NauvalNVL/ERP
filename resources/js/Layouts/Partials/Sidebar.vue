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

      <!-- Material Management -->
      <SidebarDropdown
        title="Material Management"
        icon="fas fa-boxes"
        :items="materialManagementItems"
        :current-path="currentPath"
      />

      <!-- Production Management -->
      <SidebarDropdown
        title="Production Management"
        icon="fas fa-industry"
        :items="productionManagementItems"
        :current-path="currentPath"
      />

      <!-- Warehouse Management -->
      <SidebarDropdown
        title="Warehouse Management"
        icon="fas fa-warehouse"
        :items="warehouseManagementItems"
        :current-path="currentPath"
      />

      <!-- Data Mining -->
      <div class="relative group">
        <Link
          href="/data-mining"
          class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg transition-colors"
          :class="{ 'bg-blue-600 text-white font-medium': currentPath === '/data-mining' }"
        >
          <i class="fas fa-database w-5 h-5 mr-3"></i>
          <span>Data Mining</span>
        </Link>
      </div>
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
      { title: 'Copy & Paste User Access Permission', icon: 'fas fa-copy', route: null },
      { title: 'Purge User Log-in/out Audit Log', icon: 'fas fa-trash-alt', route: null },
      { title: 'View & Print User', icon: 'fas fa-users', route: null },
      { title: 'View & Print User Access Permission', icon: 'fas fa-list-alt', route: null },
      { title: 'View & Print User Log-in/out Audit Log', icon: 'fas fa-history', route: null }
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

// Material Management Items
const materialManagementItems = [
  {
    title: 'System Requirement',
    icon: 'fas fa-cogs',
    children: [
      {
        title: 'Standard Setup',
        icon: 'fas fa-sliders-h',
        children: [
          { title: 'Define Configuration', icon: 'fas fa-tools', route: '/material-management/system-requirement/standard-setup/configuration' },
          { title: 'Define Control Period', icon: 'fas fa-calendar-alt', route: '/material-management/system-requirement/standard-setup/control-period' },
          { title: 'Define Transaction Type', icon: 'fas fa-exchange-alt', route: '/material-management/system-requirement/standard-setup/transaction-type' },
          { title: 'Define Tax Type', icon: 'fas fa-percent', route: '/material-management/system-requirement/standard-setup/tax-type' },
          { title: 'Define Tax Group', icon: 'fas fa-layer-group', route: '/material-management/system-requirement/standard-setup/tax-group' },
          { title: 'Define Receive Destination', icon: 'fas fa-map-marker-alt', route: '/material-management/system-requirement/standard-setup/receive-destination' },
          { title: 'Define Analysis Code', icon: 'fas fa-barcode', route: '/material-management/system-requirement/standard-setup/analysis-code' },
          // View & Print
          { title: 'View & Print Control Period', icon: 'fas fa-print', route: '/material-management/system-requirement/standard-setup/control-period/view-print' },
          { title: 'View & Print Transaction Type', icon: 'fas fa-print', route: '/material-management/system-requirement/standard-setup/transaction-type/view-print' },
          { title: 'View & Print Tax Type', icon: 'fas fa-print', route: '/material-management/system-requirement/standard-setup/tax-type/view-print' },
          { title: 'View & Print Tax Group', icon: 'fas fa-print', route: '/material-management/system-requirement/standard-setup/tax-group/view-print' },
          { title: 'View & Print Receive Destination', icon: 'fas fa-print', route: '/material-management/system-requirement/standard-setup/receive-destination/view-print' },
          { title: 'View & Print Analysis Code', icon: 'fas fa-print', route: '/material-management/system-requirement/standard-setup/analysis-code/view-print' }
        ]
      },
      {
        title: 'Purchase Order Setup',
        icon: 'fas fa-shopping-cart',
        children: [
          { title: 'Define Purchaser', icon: 'fas fa-user-tie', route: '/material-management/system-requirement/purchase-order-setup/define-purchaser' },
          { title: 'Define Approver', icon: 'fas fa-user-check', route: '/material-management/system-requirement/purchase-order-setup/define-approver' },
          { title: 'Define Purchase Sub-Control', icon: 'fas fa-user-cog', route: '/material-management/system-requirement/purchase-order-setup/define-purchase-sub-control' },
          { title: 'Define SKU Item Note Analysis Group', icon: 'fas fa-tags', route: '/material-management/system-requirement/purchase-order-setup/sku-item-note-analysis-group' },
          { title: 'Define SKU Item Note Analysis Code', icon: 'fas fa-code', route: '/material-management/system-requirement/purchase-order-setup/sku-item-note-analysis-code' },
          { title: 'View & Print Purchaser', icon: 'fas fa-print', route: '/material-management/system-requirement/purchase-order-setup/define-purchaser/view-print' },
          { title: 'View & Print Approver', icon: 'fas fa-print', route: '/material-management/system-requirement/purchase-order-setup/define-approver/view-print' },
          { title: 'View & Print Purchase Sub-Control', icon: 'fas fa-print', route: '/material-management/system-requirement/purchase-order-setup/define-purchase-sub-control/view-print' },
          { title: 'View & Print SKU Item Note Analysis Group', icon: 'fas fa-print', route: '/material-management/system-requirement/purchase-order-setup/sku-item-note-analysis-group/view-print' },
          { title: 'View & Print SKU Item Note Analysis Code', icon: 'fas fa-print', route: '/material-management/system-requirement/purchase-order-setup/sku-item-note-analysis-code/view-print' }
        ]
      },
      {
        title: 'Inventory Setup',
        icon: 'fas fa-dolly-flatbed',
        children: [
          { title: 'Define Category', icon: 'fas fa-tags', route: '/material-management/system-requirement/inventory-setup/category' },
          { title: 'Define Location', icon: 'fas fa-map-marked-alt', route: '/material-management/system-requirement/inventory-setup/location' },
          { title: 'Define Unit', icon: 'fas fa-balance-scale', route: '/material-management/system-requirement/inventory-setup/unit' },
          { title: 'Define Report Group', icon: 'fas fa-file-alt', route: '/material-management/system-requirement/inventory-setup/report-group' },
          { title: 'Define MM GL Distribution', icon: 'fas fa-sitemap', route: '/material-management/system-requirement/inventory-setup/gl-distribution' },
          { title: 'Define SKU', icon: 'fas fa-box-open', route: '/material-management/system-requirement/inventory-setup/sku' },
          { title: 'Define SKU Price', icon: 'fas fa-dollar-sign', route: '/material-management/system-requirement/inventory-setup/sku-price' },
          { title: 'Amend SKU Type', icon: 'fas fa-edit', route: '/material-management/system-requirement/inventory-setup/amend-sku-type' },
          { title: 'Amend SKU#', icon: 'fas fa-tag', route: '/material-management/system-requirement/inventory-setup/amend-sku' },
          { title: 'Obsolete/Reactive SKU Status', icon: 'fas fa-toggle-on', route: '/material-management/system-requirement/inventory-setup/obsolete-reactive-sku' },
          { title: 'Define SKU Reorder Level', icon: 'fas fa-level-down-alt', route: '/material-management/system-requirement/inventory-setup/sku-reorder-level' },
          { title: 'Copy & Paste SKU Reorder Level', icon: 'fas fa-copy', route: '/material-management/system-requirement/inventory-setup/copy-paste-sku-reorder-level' },
          { title: 'Define SKU Consumption Budget', icon: 'fas fa-chart-line', route: '/material-management/system-requirement/inventory-setup/sku-consumption-budget' },
          { title: 'Define Custom Tariff Code', icon: 'fas fa-file-invoice-dollar', route: '/material-management/system-requirement/inventory-setup/custom-tariff-code' },
          { title: 'Define SKU Custom Tariff Code', icon: 'fas fa-link', route: '/material-management/system-requirement/inventory-setup/sku-custom-tariff-code' },
          { title: 'Define DR or CR Note', icon: 'fas fa-sticky-note', route: '/material-management/system-requirement/inventory-setup/dr-cr-note' },
          { title: 'Unlock SKU Utility', icon: 'fas fa-unlock-alt', route: '/material-management/system-requirement/inventory-setup/unlock-sku-utility' },
          // View & Print
          { title: 'View & Print Category', icon: 'fas fa-print', route: '/material-management/system-requirement/inventory-setup/category/view-print' },
          { title: 'View & Print Location', icon: 'fas fa-print', route: '/material-management/system-requirement/inventory-setup/location/view-print' },
          { title: 'View & Print Unit', icon: 'fas fa-print', route: '/material-management/system-requirement/inventory-setup/unit/view-print' },
          { title: 'View & Print Report Group', icon: 'fas fa-print', route: '/material-management/system-requirement/inventory-setup/report-group/view-print' },
          { title: 'View & Print MM GL Distribution', icon: 'fas fa-print', route: '/material-management/system-requirement/inventory-setup/gl-distribution/view-print' },
          { title: 'View & Print SKU', icon: 'fas fa-print', route: '/material-management/system-requirement/inventory-setup/sku/view-print' },
          { title: 'View & Print SKU Price', icon: 'fas fa-print', route: '/material-management/system-requirement/inventory-setup/sku-price/view-print' },
          { title: 'View & Print SKU Reorder Level', icon: 'fas fa-print', route: '/material-management/system-requirement/inventory-setup/sku-reorder-level/view-print' },
          { title: 'View & Print SKU Consumption Budget', icon: 'fas fa-print', route: '/material-management/system-requirement/inventory-setup/sku-consumption-budget/view-print' },
          { title: 'View & Print Custom Tariff Code', icon: 'fas fa-print', route: '/material-management/system-requirement/inventory-setup/custom-tariff-code/view-print' },
          { title: 'View & Print SKU Tariff Code', icon: 'fas fa-print', route: '/material-management/system-requirement/inventory-setup/sku-tariff-code/view-print' },
          { title: 'View & Print DR or CR Note', icon: 'fas fa-print', route: '/material-management/system-requirement/inventory-setup/dr-cr-note/view-print' }
        ]
      }
    ]
  },
  {
    title: 'Purchase Order',
    icon: 'fas fa-shopping-cart',
    children: [
      {
        title: 'PR/PO',
        icon: 'fas fa-file-invoice',
        children: [
          // Purchase Requisition Management
          { title: 'Prepare PR', icon: 'fa fa-plus', route: '/material-management/purchase-order/pr-po/prepare-pr' },
          { title: 'Amend PR', icon: 'fa fa-edit', route: '/material-management/purchase-order/pr-po/amend-pr' },
          { title: 'Cancel PR', icon: 'fa fa-times', route: '/material-management/purchase-order/pr-po/cancel-pr' },
          { title: 'Print PR', icon: 'fa fa-print', route: '/material-management/purchase-order/pr-po/print-pr' },
          { title: 'Amend Approved PR + Re-Submit', icon: 'fa fa-pencil', route: '/material-management/purchase-order/pr-po/amend-approved-pr' },
          { title: 'Approved PR', icon: 'fa fa-check', route: '/material-management/purchase-order/pr-po/approved-pr' },
          
          // Purchase Order Management
          { title: 'Amend Rejected PO + Re-Submit', icon: 'fa fa-undo', route: '/material-management/purchase-order/pr-po/amend-rejected-po' },
                       { title: 'Cancel Approved PO', icon: 'fa fa-ban', route: '/material-management/purchase-order/pr-po/cancel-approved-po' },
             { title: 'View & Print PO Log', icon: 'fa fa-list', route: '/material-management/purchase-order/pr-po/view-print-po-log' }
        ]
      },
      {
        title: 'PR/PO Reports',
        icon: 'fas fa-chart-bar',
        children: [
          { title: 'View & print PO Arrival Schedule', icon: 'fas fa-calendar', route: '/material-management/purchase-order/pr-po/view-print-po-arrival-schedule' },
          { title: 'View & print PR PO Reports', icon: 'fas fa-print', route: '/material-management/purchase-order/pr-po/view-print-pr-po-reports' },
          { title: 'View & print PO RC & RT Report', icon: 'fas fa-print', route: '/material-management/purchase-order/pr-po/view-print-po-rc-rt-report' },
          { title: 'View & print PSC Report', icon: 'fas fa-print', route: '/material-management/purchase-order/pr-po/view-print-psc-report' },
          { title: 'View & print SKU historical price', icon: 'fas fa-print', route: '/material-management/purchase-order/pr-po/view-print-sku-historical-price' }
        ]
      },
      {
        title: 'PR/PO Period End Closing',
        icon: 'fas fa-calendar-check',
        children: [
          { title: 'Perform PR & PO Period-End Closing', icon: 'fas fa-play-circle', route: '/material-management/purchase-order/pr-po/perform-pr-po-period-closing' }
        ]
      }
    ]
  },
  {
    title: 'Inventory Control',
    icon: 'fas fa-boxes',
    children: [
      {
        title: 'RC/RT',
        icon: 'fas fa-exchange-alt',
        children: [
          { title: 'Prepare RC', icon: 'fas fa-plus-circle', route: '/material-management/inventory-control/rc-rt/prepare-rc' },
          { title: 'Amend RC', icon: 'fas fa-edit', route: '/material-management/inventory-control/rc-rt/amend-rc' },
          { title: 'Print RC', icon: 'fas fa-print', route: '/material-management/inventory-control/rc-rt/print-rc' },
          { title: 'View & Print RC Log', icon: 'fas fa-list-alt', route: '/material-management/inventory-control/rc-rt/view-print-rc-log' },
          { title: 'Prepare RT', icon: 'fas fa-plus-circle', route: '/material-management/inventory-control/rc-rt/prepare-rt' },
          { title: 'Amend RT', icon: 'fas fa-edit', route: '/material-management/inventory-control/rc-rt/amend-rt' },
          { title: 'Print RT', icon: 'fas fa-print', route: '/material-management/inventory-control/rc-rt/print-rt' },
          { title: 'View & Print RT Log', icon: 'fas fa-list-alt', route: '/material-management/inventory-control/rc-rt/view-print-rt-log' }
        ]
      },

      {
        title: 'DR/CN',
        icon: 'fas fa-file-invoice-dollar',
        children: [
          { title: 'Prepare DN', icon: 'fas fa-plus-circle', route: '/material-management/inventory-control/dr-cn/prepare-dn' },
          { title: 'Amend DN', icon: 'fas fa-edit', route: '/material-management/inventory-control/dr-cn/amend-dn' },
          { title: 'Cancel DN', icon: 'fas fa-times', route: '/material-management/inventory-control/dr-cn/cancel-dn' },
          { title: 'Print DN', icon: 'fas fa-print', route: '/material-management/inventory-control/dr-cn/print-dn' },
          { title: 'View & Print DN Log', icon: 'fas fa-list-alt', route: '/material-management/inventory-control/dr-cn/view-print-dn-log' },
          { title: 'Prepare CN', icon: 'fas fa-plus-circle', route: '/material-management/inventory-control/dr-cn/prepare-cn' },
          { title: 'Amend CN', icon: 'fas fa-edit', route: '/material-management/inventory-control/dr-cn/amend-cn' },
          { title: 'Cancel CN', icon: 'fas fa-times', route: '/material-management/inventory-control/dr-cn/cancel-cn' },
          { title: 'Print CN', icon: 'fas fa-print', route: '/material-management/inventory-control/dr-cn/print-cn' },
          { title: 'View & Print CN Log', icon: 'fas fa-list-alt', route: '/material-management/inventory-control/dr-cn/view-print-cn-log' }
        ]
      },
      {
        title: 'IS/MI/MO/LT',
        icon: 'fas fa-exchange-alt',
        children: [
          { title: 'Prepare IS', icon: 'fas fa-plus-circle', route: '/material-management/inventory-control/is-mi-mo-lt/prepare-is' },
          { title: 'Cancel IS', icon: 'fas fa-times', route: '/material-management/inventory-control/is-mi-mo-lt/cancel-is' },
          { title: 'Print IS', icon: 'fas fa-print', route: '/material-management/inventory-control/is-mi-mo-lt/print-is' },
          { title: 'Prepare MI', icon: 'fas fa-plus-circle', route: '/material-management/inventory-control/is-mi-mo-lt/prepare-mi' },
          { title: 'Cancel MI', icon: 'fas fa-times', route: '/material-management/inventory-control/is-mi-mo-lt/cancel-mi' },
          { title: 'Print MI', icon: 'fas fa-print', route: '/material-management/inventory-control/is-mi-mo-lt/print-mi' },
          { title: 'Prepare MO', icon: 'fas fa-plus-circle', route: '/material-management/inventory-control/is-mi-mo-lt/prepare-mo' },
          { title: 'View & Print IS/MI/MO/LT Log', icon: 'fas fa-list-alt', route: '/material-management/inventory-control/is-mi-mo-lt/view-print-log' }
        ]
      },
      {
        title: 'Inventory Reports',
        icon: 'fas fa-chart-pie',
        children: [
          { title: 'Print SKU Balance Report', icon: 'fas fa-balance-scale', route: '/material-management/inventory-control/inventory-reports/print-sku-balance' },
          { title: 'Print SKU Reorder Report', icon: 'fas fa-sort-amount-down', route: '/material-management/inventory-control/inventory-reports/print-sku-reorder' },
          { title: 'Print SKU Ledger Report', icon: 'fas fa-book', route: '/material-management/inventory-control/inventory-reports/print-sku-ledger' },
          { title: 'Print SKU Aging Report', icon: 'fas fa-clock', route: '/material-management/inventory-control/inventory-reports/print-sku-aging' },
          { title: 'Print SKU Open Item Aging Report', icon: 'fas fa-hourglass-half', route: '/material-management/inventory-control/inventory-reports/print-sku-open-item-aging' },
          { title: 'Inquire SKU Account', icon: 'fas fa-search', route: '/material-management/inventory-control/inventory-reports/inquire-sku-account' }
        ]
      },
      {
        title: 'Inventory Period End Closing',
        icon: 'fas fa-calendar-alt',
        children: [
          { title: 'Perform Inventory Period-End Closing', icon: 'fas fa-play-circle', route: '/material-management/inventory-control/period-end-closing/perform-inventory-period-end-closing' }
        ]
      },
      {
        title: 'Inventory Stock-Take',
        icon: 'fas fa-clipboard-check',
        children: [
          { title: 'Run Stock-Take New Batch', icon: 'fas fa-play-circle', route: '/material-management/inventory-control/inventory-stock-take/run-stock-take-new-batch' },
          { title: 'Input Stock-Take Data', icon: 'fas fa-edit', route: '/material-management/inventory-control/inventory-stock-take/input-stock-take-data' },
          { title: 'Print Stock-Take Data', icon: 'fas fa-print', route: '/material-management/inventory-control/inventory-stock-take/print-stock-take-data' },
          { title: 'Print System Stock-Take Data', icon: 'fas fa-database', route: '/material-management/inventory-control/inventory-stock-take/print-system-stock-take-data' },
          { title: 'Print Stock-Take Matching Report', icon: 'fas fa-chart-line', route: '/material-management/inventory-control/inventory-stock-take/print-stock-take-matching-report' }
        ]
      }
    ]
  },
  {
    title: 'Account',
    icon: 'fas fa-book',
    children: [
      {
        title: 'Setup Account',
        icon: 'fas fa-user-cog',
        children: [
          { title: 'Setup Purchase SKU Accounts', icon: 'fas fa-shopping-cart', route: '/material-management/account/setup-account/setup-purchase-sku-accounts' },
          { title: 'Setup Purchase Tax Accounts', icon: 'fas fa-percentage', route: '/material-management/account/setup-account/setup-purchase-tax-accounts' },
          { title: 'Setup Purchase DN/CN Accounts', icon: 'fas fa-exchange-alt', route: '/material-management/account/setup-account/setup-purchase-dn-cn-accounts' },
          { title: 'Setup Inventory SKU Accounts', icon: 'fas fa-boxes', route: '/material-management/account/setup-account/setup-inventory-sku-accounts' },
          { title: 'View & Print Purchase AP Accounts', icon: 'fas fa-print', route: '/material-management/account/setup-account/view-print-purchase-ap-accounts' },
          { title: 'View & Print Purchase SKU Accounts', icon: 'fas fa-print', route: '/material-management/account/setup-account/view-print-purchase-sku-accounts' },
          { title: 'View & Print Purchase Tax Accounts', icon: 'fas fa-print', route: '/material-management/account/setup-account/view-print-purchase-tax-accounts' },
          { title: 'View & Print Purchase DN/CN Accounts', icon: 'fas fa-print', route: '/material-management/account/setup-account/view-print-purchase-dn-cn-accounts' },
          { title: 'View & Print Inventory SKU Accounts', icon: 'fas fa-print', route: '/material-management/account/setup-account/view-print-inventory-sku-accounts' }
        ]
      },
      {
        title: 'Posting to Accounts',
        icon: 'fas fa-file-import',
        children: [
          {
            title: 'Post RC',
            icon: 'fas fa-share-square',
            children: [
              { title: 'Prepare RC Posting Batch', icon: 'fas fa-plus', route: '/material-management/accounts/posting-to-accounts/post-rc/prepare-rc-posting-batch' },
              { title: 'Cancel RC Posting Batch', icon: 'fas fa-times', route: '/material-management/accounts/posting-to-accounts/post-rc/cancel-rc-posting-batch' },
              { title: 'View & Print RC Posting Batch', icon: 'fas fa-print', route: '/material-management/accounts/posting-to-accounts/post-rc/view-print-rc-posting-batch' },
              { title: 'Confirm to Post RC', icon: 'fas fa-check', route: '/material-management/accounts/posting-to-accounts/post-rc/confirm-to-post-rc' }
            ]
          },
          { title: 'Post RT', icon: 'fas fa-reply', route: '/material-management/account/posting/post-rt' },
          { title: 'Post DN', icon: 'fas fa-arrow-up', route: '/material-management/account/posting/post-dn' },
          { title: 'Post CN', icon: 'fas fa-arrow-down', route: '/material-management/account/posting/post-cn' },
          { title: 'Post IS', icon: 'fas fa-arrow-right', route: '/material-management/account/posting/post-is' },
          { title: 'Post MI', icon: 'fas fa-arrow-left', route: '/material-management/account/posting/post-mi' },
          { title: 'Post MO', icon: 'fas fa-minus-circle', route: '/material-management/account/posting/post-mo' }
        ]
      }
    ]
  }
];

// Production Management Items
const productionManagementItems = [
  {
    title: 'Production Configuration',
    icon: 'fas fa-cogs',
    children: [
      {
        title: 'System Configuration',
        icon: 'fas fa-cog',
        children: [
          { title: 'Define Production Configuration', icon: 'fas fa-cogs', route: null },
        ]
      },
      {
        title: 'Standard Specifications',
        icon: 'fas fa-list-alt',
        children: [
          { title: 'Define W/Order & Schedule Control Period', icon: 'fas fa-calendar-alt', route: null },
      { title: 'Define Process', icon: 'fas fa-project-diagram', route: null },
          { title: 'Define Sub-Process', icon: 'fas fa-sitemap', route: null },
          { title: 'Define Lead Time', icon: 'fas fa-hourglass-half', route: null },
          { title: 'Define Machine', icon: 'fas fa-cogs', route: null },
          { title: 'Define Production Allowance by Product', icon: 'fas fa-percent', route: null },
          { title: 'Define Production Allowance by Flute', icon: 'fas fa-layer-group', route: null },
          { title: 'Define Production Allowance by MSP', icon: 'fas fa-cube', route: null },
          { title: 'Define Production Allowance by Customer', icon: 'fas fa-user-friends', route: null },
          { title: 'Define Production Allowance by M/Card', icon: 'fas fa-id-card', route: null },
          { title: 'Define Color Priority', icon: 'fas fa-palette', route: null },
          { title: 'Define Order Priority', icon: 'fas fa-sort-numeric-up', route: null },
          { title: 'Define Analysis Code', icon: 'fas fa-barcode', route: null },
          { title: 'Setup Sales and Roll Link-Up', icon: 'fas fa-link', route: null },
          { title: 'View & Print W/Order & Schedule Control Period', icon: 'fas fa-print', route: null },
          { title: 'View & Print Process', icon: 'fas fa-print', route: null },
          { title: 'View & Print Sub-Process', icon: 'fas fa-print', route: null },
          { title: 'View & Print Machine', icon: 'fas fa-print', route: null },
          { title: 'View & Print Production Allowance by Product', icon: 'fas fa-print', route: null },
          { title: 'View & Print Production Allowance by Flute', icon: 'fas fa-print', route: null },
          { title: 'View & Print Production Allowance by MSP', icon: 'fas fa-print', route: null },
          { title: 'View & Print Production Allowance by Customer', icon: 'fas fa-print', route: null },
          { title: 'View & Print Production Allowance by M/Card', icon: 'fas fa-print', route: null },
          { title: 'View & Print Color Priority', icon: 'fas fa-print', route: null },
          { title: 'View & Print Order Priority', icon: 'fas fa-print', route: null },
          { title: 'View & Print Analysis Code', icon: 'fas fa-print', route: null },
          { title: 'View & Print Sales and Roll Link-Up', icon: 'fas fa-print', route: null }
        ]
      },
      {
        title: 'Machine Specifications',
        icon: 'fas fa-cogs',
    children: [
          { title: 'Define Corrugator Specifications', icon: 'fas fa-cogs', route: null },
          { title: 'Define Printer Specifications', icon: 'fas fa-print', route: null },
          { title: 'Define Diecutter Specifications', icon: 'fas fa-cut', route: null },
          { title: 'Define Finisher Specifications', icon: 'fas fa-paint-roller', route: null },
          { title: 'Define Flute Layer Position', icon: 'fas fa-layer-group', route: null },
          { title: 'View & Print Corrugator Specifications', icon: 'fas fa-print', route: null },
          { title: 'View & Print Printer Specifications', icon: 'fas fa-print', route: null },
          { title: 'View & Print Diecutter Specifications', icon: 'fas fa-print', route: null },
          { title: 'View & Print Finisher Specifications', icon: 'fas fa-print', route: null },
          { title: 'View & Print Flute Layer Position', icon: 'fas fa-print', route: null }
    ]
  },
  {
        title: 'Customer Specifications',
        icon: 'fas fa-users',
        children: [
          { title: 'Define MSP Choice', icon: 'fas fa-check-square', route: null },
          { title: 'Define Customer Requirement', icon: 'fas fa-clipboard-check', route: null },
          { title: 'Define M/Card MSP', icon: 'fas fa-credit-card', route: null },
          { title: 'Define M/Card W/Order B/Quality', icon: 'fas fa-industry', route: null },
          { title: 'Customize M/Card IDC Drawing', icon: 'fas fa-pencil-ruler', route: null },
          { title: 'View & Print MSP Choice', icon: 'fas fa-print', route: null },
          { title: 'View & Print Customer Requirement', icon: 'fas fa-print', route: null },
          { title: 'View & Print M/Card MSP', icon: 'fas fa-print', route: null },
        ]
      },
      {
        title: 'Plant Specifications',
        icon: 'fas fa-industry',
    children: [
          { title: 'Define Run Unit', icon: 'fas fa-running', route: null },
          { title: 'Define Run Calendar', icon: 'fas fa-calendar-alt', route: null },
          { title: 'Define Shift', icon: 'fas fa-users', route: null },
          { title: 'View & Print Run Unit', icon: 'fas fa-print', route: null },
          { title: 'View & Print Run Calendar', icon: 'fas fa-print', route: null },
          { title: 'View & Print Shift', icon: 'fas fa-print', route: null },
    ]
  },
  {
        title: 'Analysis Specifications',
    icon: 'fas fa-chart-bar',
    children: [
          { title: 'Define Wastage', icon: 'fas fa-trash-alt', route: null },
          { title: 'Define Production Time', icon: 'fas fa-hourglass', route: null },
          { title: 'Define PMB Machine', icon: 'fas fa-cogs', route: null },
          { title: 'Define PMB Delay Column', icon: 'fas fa-clock', route: null },
          { title: 'View & Print Wastage', icon: 'fas fa-print', route: null },
          { title: 'View & Print Production Time', icon: 'fas fa-print', route: null },
          { title: 'View & Print OMB Machine', icon: 'fas fa-print', route: null },
          { title: 'View & Print PMB Delay Column', icon: 'fas fa-print', route: null },
        ]
      }
    ]
  },
  {
    title: 'Production Work Order',
    icon: 'fas fa-clipboard-list',
    children: [
      {
        title: 'Work Order',
        icon: 'fas fa-file-alt',
        children: [
          { title: 'Release Work Order by Sales Order', icon: 'fas fa-file-invoice', route: null },
          { title: 'Release Work Order by Product Design', icon: 'fas fa-file-invoice-dollar', route: null },
          { title: 'Issue Combined COCR Run Work Order', icon: 'fas fa-file-signature', route: null },
          { title: 'Print Work Order', icon: 'fas fa-print', route: null },
          { title: 'Print WO FG Book Excess Memo', icon: 'fas fa-print', route: null },
          { title: 'Amend Work Order', icon: 'fas fa-edit', route: null },
          { title: 'Cancel Work Order', icon: 'fas fa-times-circle', route: null },
          { title: 'Close Work Order', icon: 'fas fa-check-circle', route: null },
          { title: 'Close Work Order by Period', icon: 'fas fa-calendar-times', route: null },
          { title: 'Unlock W/Order & Schedule - Utility', icon: 'fas fa-unlock', route: null },
          { title: 'View & Print Late Work Order', icon: 'fas fa-print', route: null },
          { title: 'View & Print Work Order Log', icon: 'fas fa-print', route: null },
          { title: 'View & Print Unreleased Sales Order', icon: 'fas fa-print', route: null },
          { title: 'Print Rerun Work Order Analysis', icon: 'fas fa-print', route: null },
        ]
      },
    ]
  },
  {
    title: 'Production Floor Tracking',
    icon: 'fas fa-desktop',
    children: [
      {
        title: 'Setup Maintenance',
        icon: 'fas fa-tools',
        children: [
          { title: 'Setup Floor Tracking Configuration', icon: 'fas fa-cogs', route: null },
          { title: 'Define Floor Track Control Period', icon: 'fas fa-calendar-alt', route: null },
          { title: 'Define Pallet Type', icon: 'fas fa-pallet', route: null },
          { title: 'View & Print Floor Track Control Period', icon: 'fas fa-print', route: null },
          { title: 'View & Print Pallet Type', icon: 'fas fa-print', route: null },
        ]
      },
      {
        title: 'Job Tracking',
        icon: 'fas fa-tasks',
        children: [
          { title: 'Amend Convertor Machine Before Floor Track', icon: 'fas fa-edit', route: null },
          { title: 'Insert Active Job to Converting Schedule', icon: 'fas fa-plus-square', route: null },
          { title: 'Enter & Update CORR FT by Cut', icon: 'fas fa-cut', route: null },
          { title: 'Enter & Update CORR FT by Sheet', icon: 'fas fa-file', route: null },
          { title: 'Enter & Update CORR FT by In & Out', icon: 'fas fa-exchange-alt', route: null },
          { title: 'Enter & Update PRINTER FT by Pressed Qty', icon: 'fas fa-print', route: null },
          { title: 'Enter & Update DC FT by Pressed Qty', icon: 'fas fa-cogs', route: null },
          { title: 'Enter & Update DC FT by Produced Qty', icon: 'fas fa-industry', route: null },
          { title: 'Enter & Update FINISHING FT by Produced Qty', icon: 'fas fa-paint-roller', route: null },
          { title: 'FT Printer by Press Qty', icon: 'fas fa-print', route: null },
          { title: 'FT Die Cutter by Press Qty', icon: 'fas fa-cut', route: null },
          { title: 'FT Die Cutter by Produce Qty', icon: 'fas fa-industry', route: null },
          { title: 'FT Finisher by Produce Qty', icon: 'fas fa-paint-roller', route: null },
          { title: 'Amend Floor Track', icon: 'fas fa-edit', route: null },
          { title: 'Cancel Floor Track', icon: 'fas fa-times-circle', route: null },
          { title: 'View & Print Floor Track Log', icon: 'fas fa-print', route: null },
          { title: 'Print Dry-End Pallet Label', icon: 'fas fa-print', route: null },
          { title: 'Print Dry-End Pallet Label + No Control', icon: 'fas fa-print', route: null },
          { title: 'Print FG Pallet Label', icon: 'fas fa-print', route: null },
          { title: 'Print FG Pallet Label + No Control', icon: 'fas fa-print', route: null },
          { title: 'View & Print Floor Track Status', icon: 'fas fa-print', route: null },
          { title: 'Floor Track Status with Value', icon: 'fas fa-chart-bar', route: null },
        ]
      },
      {
        title: 'Timesheet Tracking',
        icon: 'fas fa-clock',
        children: [
          { title: 'Enter & Update Machine Time Sheet', icon: 'fas fa-edit', route: null },
          { title: 'View & Print Machine Time Sheet', icon: 'fas fa-print', route: null },
        ]
      },
      {
        title: 'Corrugator Wet-End Tracking',
        icon: 'fas fa-tint',
        children: [
          { title: 'Enter & Update Corrugator Wet-End Waste', icon: 'fas fa-recycle', route: null },
          { title: 'View & Print Corrugator Wet-End Waste', icon: 'fas fa-print', route: null },
        ]
      },
      {
        title: 'WIP Stock-Take',
        icon: 'fas fa-boxes',
        children: [
          { title: 'Print Machine Barcode', icon: 'fas fa-barcode', route: null },
          { title: 'Commence WIP Stock-Take', icon: 'fas fa-play-circle', route: null },
          { title: 'Input WIP Stock-Take', icon: 'fas fa-keyboard', route: null },
          { title: 'View & Print WIP Stock-Take', icon: 'fas fa-print', route: null },
          { title: 'Print WIP Stock-Take Result', icon: 'fas fa-print', route: null },
        ]
      },
    ]
  },
];

// Warehouse Management Items
const warehouseManagementItems = [
  {
    title: 'Finished Goods',
    icon: 'fas fa-boxes',
    children: [
      {
        title: 'Setup Maintenance',
        icon: 'fas fa-cogs',
        children: [
          { title: 'Setup F/Goods & D/Order Configuration', icon: 'fas fa-cogs', route: '/warehouse-management/finished-goods/setup-maintenance/fg-do-configuration' },
          { title: 'Define Control Period', icon: 'fas fa-calendar-alt', route: '/warehouse-management/finished-goods/setup-maintenance/define-control-period' },
          { title: 'Define Warehouse Location', icon: 'fas fa-map-marker-alt', route: '/warehouse-management/finished-goods/setup-maintenance/define-warehouse-location' },
          { title: 'Define Customer Warehouse Location', icon: 'fas fa-users-cog', route: '/warehouse-management/finished-goods/setup-maintenance/define-customer-warehouse-location' },
          { title: 'Define Delivery Order Format', icon: 'fas fa-file-invoice', route: '/warehouse-management/finished-goods/setup-maintenance/define-delivery-order-format' },
          { title: 'Define Customer Warehouse Requirement', icon: 'fas fa-tasks', route: '/warehouse-management/finished-goods/setup-maintenance/define-customer-warehouse-requirement' },
          { title: 'Define Analysis Code', icon: 'fas fa-tags', route: '/warehouse-management/finished-goods/setup-maintenance/define-analysis-code' },
          { title: 'View & Print Control Period', icon: 'fas fa-print', route: '/warehouse-management/finished-goods/setup-maintenance/view-print-control-period' },
          { title: 'View & Print Warehouse Location', icon: 'fas fa-print', route: '/warehouse-management/finished-goods/setup-maintenance/view-print-warehouse-location' },
          { title: 'View & Print Customer Warehouse Location', icon: 'fas fa-print', route: '/warehouse-management/finished-goods/setup-maintenance/view-print-customer-warehouse-location' },
          { title: 'View & Print Delivery Order Format', icon: 'fas fa-print', route: '/warehouse-management/finished-goods/setup-maintenance/view-print-delivery-order-format' },
          { title: 'View & Print Customer Warehouse Requirement', icon: 'fas fa-print', route: '/warehouse-management/finished-goods/setup-maintenance/view-print-customer-warehouse-requirement' },
          { title: 'View & Print Analysis Code', icon: 'fas fa-print', route: '/warehouse-management/finished-goods/setup-maintenance/view-print-analysis-code' },
        ]
      },
      {
        title: 'FG Normal',
        icon: 'fas fa-box-open',
        children: [
          { title: 'Check FG Balance', icon: 'fas fa-balance-scale', route: '/warehouse-management/finished-goods/fg-normal/check-fg-balance' },
          { title: 'Update FG Stock-In by SO', icon: 'fas fa-file-import', route: '/warehouse-management/finished-goods/fg-normal/update-fg-stock-in-by-so' },
          { title: 'Update FG Stock-In by WO', icon: 'fas fa-file-import', route: '/warehouse-management/finished-goods/fg-normal/update-fg-stock-in-by-wo' },
          { title: 'Update FG Stock-In by Barcode', icon: 'fas fa-barcode', route: '/warehouse-management/finished-goods/fg-normal/update-fg-stock-in-by-barcode' },
          { title: 'Update FG Stock-Out by MC', icon: 'fas fa-file-export', route: '/warehouse-management/finished-goods/fg-normal/update-fg-stock-out-by-mc' },
          { title: 'Update FG Location Transfer', icon: 'fas fa-exchange-alt', route: '/warehouse-management/finished-goods/fg-normal/update-fg-location-transfer' },
          { title: 'Update FG Stock-Out by Batch', icon: 'fas fa-boxes', route: '/warehouse-management/finished-goods/fg-normal/update-fg-stock-out-by-batch' },
          { title: 'Print FG Stock-In Log', icon: 'fas fa-print', route: '/warehouse-management/finished-goods/fg-normal/print-fg-stock-in-log' },
          { title: 'Print FG Stock-Out Log', icon: 'fas fa-print', route: '/warehouse-management/finished-goods/fg-normal/print-fg-stock-out-log' },
          { title: 'Clear FGMC Lock', icon: 'fas fa-lock-open', route: '/warehouse-management/finished-goods/fg-normal/clear-fg-mc-lock' },
        ]
      },
      {
        title: 'Period-End Closing',
        icon: 'fas fa-calendar-alt',
        children: [
          { title: 'Perform Period-End Closing', icon: 'fas fa-calendar-check', route: '/warehouse-management/finished-goods/period-end-closing/perform' },
          { title: 'Purge Finished Goods Ledger', icon: 'fas fa-trash-alt', route: '/warehouse-management/finished-goods/period-end-closing/purge-ledger' },
        ]
      },
      {
        title: 'Finished Goods Stock-Take V2',
        icon: 'fas fa-clipboard-check',
        children: [
          { title: 'Define Stock-Take Configuration', icon: 'fas fa-cogs', route: '/warehouse-management/finished-goods/stock-take-v2/define-configuration' },
          { title: 'Commence Stock-Take Exercise', icon: 'fas fa-play-circle', route: '/warehouse-management/finished-goods/stock-take-v2/commence-exercise' },
          { title: 'Enter & Update Stock-Take Records', icon: 'fas fa-edit', route: '/warehouse-management/finished-goods/stock-take-v2/enter-update-records' },
          { title: 'View & Print Stock-Take Records', icon: 'fas fa-print', route: '/warehouse-management/finished-goods/stock-take-v2/view-print-records' },
          { title: 'Print Stock-Take Matching Results', icon: 'fas fa-print', route: '/warehouse-management/finished-goods/stock-take-v2/print-matching-results' },
          { title: 'Update Stock-Take to FG Inventory [by SO]', icon: 'fas fa-file-invoice', route: '/warehouse-management/finished-goods/stock-take-v2/update-fg-inventory-so' },
          { title: 'Update Stock-Take to FG Inventory [by Model]', icon: 'fas fa-cube', route: '/warehouse-management/finished-goods/stock-take-v2/update-fg-inventory-model' },
        ]
      },
      {
        title: 'FG Stock-Take V3 (GRX and Non-GRX)',
        icon: 'fas fa-clipboard-check',
        children: [
          { title: 'Define Stock-Take Configuration', icon: 'fas fa-cogs', route: '/warehouse-management/finished-goods/stock-take-v3/define-configuration' },
          { title: 'Commence Stock-Take Exercise', icon: 'fas fa-play-circle', route: '/warehouse-management/finished-goods/stock-take-v3/commence-exercise' },
          { title: 'Enter & Update Stock-Take Records', icon: 'fas fa-edit', route: '/warehouse-management/finished-goods/stock-take-v3/enter-update-records' },
          { title: 'View & Print Stock-Take Records', icon: 'fas fa-print', route: '/warehouse-management/finished-goods/stock-take-v3/view-print-records' },
          { title: 'Print Stock-Take Matching Results', icon: 'fas fa-print', route: '/warehouse-management/finished-goods/stock-take-v3/print-matching-results' },
          { title: 'Update Stock-Take to FG Inventory', icon: 'fas fa-boxes', route: '/warehouse-management/finished-goods/stock-take-v3/update-fg-inventory' },
        ]
      }
    ]
  },
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
          { title: 'Print Delivery Order', icon: 'fas fa-print', route: '/warehouse-management/delivery-order/do-processing/print-do' },
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
          { title: 'Define Control Period', icon: 'fas fa-calendar-alt', route: '/warehouse-management/invoice/setup/define-control-period' },
          { title: 'Setup Invoice Configuration', icon: 'fas fa-cog', route: '/warehouse-management/invoice/setup/invoice-configuration' },
          { title: 'Define Tax Type', icon: 'fas fa-percent', route: '/warehouse-management/invoice/setup/define-tax-type' },
          { title: 'Define Tax Group', icon: 'fas fa-layer-group', route: '/warehouse-management/invoice/setup/define-tax-group' },
          { title: 'Define Custom Tariff Code', icon: 'fas fa-file-invoice', route: '/warehouse-management/invoice/setup/define-custom-tariff-code' },
          { title: 'Define Invoice Group', icon: 'fas fa-object-group', route: '/warehouse-management/invoice/setup/define-invoice-group' },
          { title: 'Define User\'s Invoice Group', icon: 'fas fa-users-cog', route: '/warehouse-management/invoice/setup/define-users-invoice-group' },
          { title: 'Define Customer Sales Tax Index', icon: 'fas fa-tags', route: '/warehouse-management/invoice/setup/define-customer-sales-tax-index' },
          { title: 'Define Customer Invoice Requirement', icon: 'fas fa-clipboard-list', route: '/warehouse-management/invoice/setup/define-customer-invoice-requirement' },
          { title: 'View & Print Control Period', icon: 'fas fa-print', route: '/warehouse-management/invoice/setup/view-print-control-period' },
          { title: 'View & Print Tax Type', icon: 'fas fa-print', route: '/warehouse-management/invoice/setup/view-print-tax-type' },
          { title: 'View & Print Tax Group', icon: 'fas fa-print', route: '/warehouse-management/invoice/setup/view-print-tax-group' },
          { title: 'View & Print Custom Tariff Code', icon: 'fas fa-print', route: '/warehouse-management/invoice/setup/view-print-custom-tariff-code' },
          { title: 'View & Print Invoice Group', icon: 'fas fa-print', route: '/warehouse-management/invoice/setup/view-print-invoice-group' },
          { title: 'View & Print User\'s Invoice Group', icon: 'fas fa-print', route: '/warehouse-management/invoice/setup/view-print-users-invoice-group' },
          { title: 'View & Print Customer Sales Tax Index', icon: 'fas fa-print', route: '/warehouse-management/invoice/setup/view-print-customer-sales-tax-index' },
          { title: 'View & Print Customer Invoice Requirement', icon: 'fas fa-print', route: '/warehouse-management/invoice/setup/view-print-customer-invoice-requirement' },
        ]
      },
      {
        title: 'IV Processing',
        icon: 'fas fa-tasks',
        children: [
          { title: 'Prepare Invoice by D/Order (Current Period)', icon: 'fas fa-file-invoice', route: '/warehouse-management/invoice/iv-processing/prepare-do-current' },
          { title: 'Prepare Invoice by D/Order (Open Period)', icon: 'fas fa-file-invoice', route: '/warehouse-management/invoice/iv-processing/prepare-do-open' },
          { title: 'Prepare Invoice by S/Order (Current Period)', icon: 'fas fa-file-invoice-dollar', route: '/warehouse-management/invoice/iv-processing/prepare-so-current' },
          { title: 'Prepare Invoice by S/Order (Open Period)', icon: 'fas fa-file-invoice-dollar', route: '/warehouse-management/invoice/iv-processing/prepare-so-open' },
          { title: 'Prepare Invoice by M/Card (Current Period)', icon: 'fas fa-id-card', route: '/warehouse-management/invoice/iv-processing/prepare-mc-current' },
          { title: 'Print Invoice', icon: 'fas fa-print', route: '/warehouse-management/invoice/iv-processing/print-invoice' },
          { title: 'Print Invoice Supporting D/Order by M/Cards', icon: 'fas fa-print', route: '/warehouse-management/invoice/iv-processing/print-supporting-do-mc' },
          { title: 'Amend Invoice', icon: 'fas fa-edit', route: '/warehouse-management/invoice/iv-processing/amend-invoice' },
          { title: 'Amend Invoice Quantity', icon: 'fas fa-sort-numeric-up', route: '/warehouse-management/invoice/iv-processing/amend-invoice-quantity' },
          { title: 'Amend Invoice Unit Price', icon: 'fas fa-dollar-sign', route: '/warehouse-management/invoice/iv-processing/amend-invoice-unit-price' },
          { title: 'Cancel Active Invoice', icon: 'fas fa-times-circle', route: '/warehouse-management/invoice/iv-processing/cancel-active-invoice' },
          { title: 'View & Print Invoice Log', icon: 'fas fa-history', route: '/warehouse-management/invoice/iv-processing/view-print-invoice-log' },
        ]
      },
      {
        title: 'IV Special Processing',
        icon: 'fas fa-tasks',
        children: [
          { title: 'Cancel Posted Invoice [XPIV]', icon: 'fas fa-times', route: '/warehouse-management/invoice/iv-special-processing/cancel-posted-invoice' },
        ]
      },
      {
        title: 'Manual IV Processing',
        icon: 'fas fa-edit',
        children: [
          { title: 'Activate Manual Configuration', icon: 'fas fa-cogs', route: '/warehouse-management/invoice/manual-iv-processing/activate-manual-configuration' },
          { title: 'Register Manual Numbers', icon: 'fas fa-clipboard-list', route: '/warehouse-management/invoice/manual-iv-processing/register-manual-numbers' },
          { title: 'View & Print Registered Manual Numbers Log', icon: 'fas fa-history', route: '/warehouse-management/invoice/manual-iv-processing/view-print-registered-manual-numbers-log' },
        ]
      },
      {
        title: 'Banker Acceptance',
        icon: 'fas fa-money-check-alt',
        children: [
          { title: 'Print Invoice for Banker Acceptance', icon: 'fas fa-print', route: '/warehouse-management/invoice/banker-acceptance/print-invoice' },
        ]
      },
    ]
  },
  {
    title: 'Custom Indonesia',
    icon: 'fas fa-flag',
    children: [
      {
        title: 'Setup',
        icon: 'fas fa-cogs',
        children: [
          { title: 'Setup Faktur Pajak Configuration', icon: 'fas fa-cog', route: '/warehouse-management/custom-indonesia/setup/faktur-pajak-configuration' },
          { title: 'Setup Faktur Pajak Counter', icon: 'fas fa-calculator', route: '/warehouse-management/custom-indonesia/setup/faktur-pajak-counter' },
          { title: 'Setup Faktur Pajak Customer', icon: 'fas fa-user-tag', route: '/warehouse-management/custom-indonesia/setup/faktur-pajak-customer' },
          { title: 'Setup Foreign Currency Control', icon: 'fas fa-money-bill-wave', route: '/warehouse-management/custom-indonesia/setup/foreign-currency-control' },
          { title: 'View & Print Faktur Pajak Customer', icon: 'fas fa-print', route: '/warehouse-management/custom-indonesia/setup/view-print-faktur-pajak-customer' },
          { title: 'View & Print Foreign Currency Control', icon: 'fas fa-print', route: '/warehouse-management/custom-indonesia/setup/view-print-foreign-currency-control' },
          { title: 'Regen Faktur Pajak', icon: 'fas fa-sync-alt', route: '/warehouse-management/custom-indonesia/setup/regen-faktur-pajak' },
        ]
      },
      {
        title: 'Processing',
        icon: 'fas fa-tasks',
        children: [
          { title: 'Prepare Faktuk Pajak + Print', icon: 'fas fa-file-invoice', route: '/warehouse-management/custom-indonesia/processing/prepare-faktur-pajak-print' },
          { title: 'Amend Faktur Pajak + Print', icon: 'fas fa-edit', route: '/warehouse-management/custom-indonesia/processing/amend-faktur-pajak-print' },
          { title: 'Print Faktur Pajak', icon: 'fas fa-print', route: '/warehouse-management/custom-indonesia/processing/print-faktur-pajak' },
          { title: 'View & Print Faktur Pajak Log', icon: 'fas fa-history', route: '/warehouse-management/custom-indonesia/processing/view-print-faktur-pajak-log' },
          { title: 'Print Faktur Pajak Month-End Report', icon: 'fas fa-file-alt', route: '/warehouse-management/custom-indonesia/processing/print-faktur-pajak-month-end-report' },
        ]
      },
      {
        title: 'e-Pajak',
        icon: 'fas fa-file-invoice',
        children: [
          { title: 'e-Faktur Pajak', icon: 'fas fa-file-invoice-dollar', route: '/warehouse-management/custom-indonesia/e-pajak/e-faktur-pajak' },
          { title: 'e-Faktur Pajak (Taxable - PPN)', icon: 'fas fa-percent', route: '/warehouse-management/custom-indonesia/e-pajak/e-faktur-pajak-taxable-ppn' },
          { title: 'e-Faktur Pajak (Taxable - PPnBM)', icon: 'fas fa-percent', route: '/warehouse-management/custom-indonesia/e-pajak/e-faktur-pajak-taxable-ppnbm' },
          { title: 'e-Faktur Pajak (Not Taxable)', icon: 'fas fa-times', route: '/warehouse-management/custom-indonesia/e-pajak/e-faktur-pajak-not-taxable' },
          { title: 'e-Faktur Pajak (Free)', icon: 'fas fa-hand-holding-usd', route: '/warehouse-management/custom-indonesia/e-pajak/e-faktur-pajak-free' },
          { title: 'e-Faktur Pajak (Exemption)', icon: 'fas fa-ban', route: '/warehouse-management/custom-indonesia/e-pajak/e-faktur-pajak-exemption' },
          { title: 'e-Faktur Pajak (Export)', icon: 'fas fa-file-export', route: '/warehouse-management/custom-indonesia/e-pajak/e-faktur-pajak-export' },
          { title: 'e-Faktur Pajak (Consolidated)', icon: 'fas fa-layer-group', route: '/warehouse-management/custom-indonesia/e-pajak/e-faktur-pajak-consolidated' },
        ]
      },
    ]
  },
  { title: 'Accounts', icon: 'fas fa-cash-register', route: '/warehouse-management/accounts' },
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