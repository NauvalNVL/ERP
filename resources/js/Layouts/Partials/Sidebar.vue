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
      />

      <!-- Sales Management -->
      <SidebarDropdown 
        title="Sales Management" 
        icon="fas fa-chart-line"
        :items="salesManagementItems"
      />

      <!-- Material Management -->
      <SidebarDropdown 
        title="Material Management" 
        icon="fas fa-boxes"
        :items="materialManagementItems"
      />

      <!-- Production Management -->
      <SidebarDropdown 
        title="Production Management" 
        icon="fas fa-industry"
        :items="productionManagementItems"
      />

      <!-- Warehouse Management -->
      <SidebarDropdown 
        title="Warehouse Management" 
        icon="fas fa-warehouse"
        :items="warehouseManagementItems"
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
      {
        title: 'Sales Order',
        icon: 'fas fa-shopping-bag',
        children: [
          { title: 'Define SO Configuration', icon: 'fas fa-cogs', route: '/sales-management/system-requirement/sales-order/define-so-config' },
          { title: 'Define Analysis Code for Auto WO Generation', icon: 'fas fa-cogs', route: '/sales-management/system-requirement/sales-order/define-ac-auto-wo' },
          { title: 'Define MC for Auto WO Generation', icon: 'fas fa-cogs', route: '/sales-management/system-requirement/sales-order/define-mc-auto-wo' },
          { title: 'Define Analysis Code for SO Price Changes', icon: 'fas fa-cogs', route: '/sales-management/system-requirement/sales-order/define-ac-so-price' },
          { title: 'Define MC for SO Price Changes', icon: 'fas fa-cogs', route: '/sales-management/system-requirement/sales-order/define-mc-so-price' },
          { title: 'Define SO Cancel Reason', icon: 'fas fa-cogs', route: '/sales-management/system-requirement/sales-order/define-so-cancel-reason' },
          { title: 'Define SO Item Note Analysis Group', icon: 'fas fa-cogs', route: '/sales-management/system-requirement/sales-order/define-so-item-note-analysis-group' },
          { title: 'Define SO Item Note Analysis Code', icon: 'fas fa-cogs', route: '/sales-management/system-requirement/sales-order/define-so-item-note-analysis-code' },
        ]
      }
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
    icon: 'fas fa-clipboard-list', 
    children: [
      { 
        title: 'Standard Setup', 
        icon: 'fas fa-cogs',
        children: [
          { title: 'Define Configuration', icon: 'fas fa-sliders-h', route: '/material-management/system-requirement/standard-setup/configuration' },
          { title: 'Define Control Period', icon: 'fas fa-calendar-alt', route: '/material-management/system-requirement/standard-setup/control-period' },
          { title: 'Define Transaction Type', icon: 'fas fa-exchange-alt', route: '/material-management/system-requirement/standard-setup/transaction-type' },
          { title: 'Define Tax Type', icon: 'fas fa-percentage', route: '/material-management/system-requirement/standard-setup/tax-type' },
          { title: 'Define Tax Group', icon: 'fas fa-layer-group', route: '/material-management/system-requirement/standard-setup/tax-group' },
          { title: 'Define Receive Destination', icon: 'fas fa-map-marker-alt', route: '/material-management/system-requirement/standard-setup/receive-destination' },
          { title: 'Define Analysis Code', icon: 'fas fa-tags', route: '/material-management/system-requirement/standard-setup/analysis-code' },
          { title: 'View & Print Control Period', icon: 'fas fa-print', route: '/material-management/system-requirement/standard-setup/control-period/view-print' },
          { title: 'View & Print Transaction Type', icon: 'fas fa-print', route: '/material-management/system-requirement/standard-setup/transaction-type/view-print' },
          { title: 'View & Print Tax Type', icon: 'fas fa-print', route: '/material-management/system-requirement/standard-setup/tax-type/view-print' },
          { title: 'View & Print Tax Group', icon: 'fas fa-print', route: '/material-management/system-requirement/standard-setup/tax-group/view-print' },
          { title: 'View & Print Receive Destination', icon: 'fas fa-print', route: '/material-management/system-requirement/standard-setup/receive-destination/view-print' },
          { title: 'View & Print Analysis Code', icon: 'fas fa-print', route: '/material-management/system-requirement/standard-setup/analysis-code/view-print' }
        ]
      },
      {
        title: 'Inventory Setup',
        icon: 'fas fa-inventory',
        children: [
          { title: 'Define Category', icon: 'fas fa-bookmark', route: '/material-management/system-requirement/inventory-setup/category' },
          { title: 'Define Location', icon: 'fas fa-map-marker-alt', route: '/material-management/system-requirement/inventory-setup/location' },
          { title: 'Define SKU', icon: 'fas fa-tags', route: '/material-management/system-requirement/inventory-setup/sku' },
          { title: 'Define SKU Alternate', icon: 'fas fa-random', route: null },
          { title: 'Define SKU User Cross-Ref', icon: 'fas fa-user-tag', route: null },
          { title: 'Define Daily Alert', icon: 'fas fa-bell', route: '/material-management/inventory-setup/daily-alert' },
          { title: 'Define Supplier', icon: 'fas fa-truck', route: '/material-management/inventory-setup/supplier' },
          { title: 'View & Print Category', icon: 'fas fa-print', route: '/material-management/inventory-setup/category/view-print' },
          { title: 'View & Print Location', icon: 'fas fa-print', route: '/material-management/inventory-setup/location/view-print' },
          { title: 'View & Print SKU', icon: 'fas fa-print', route: '/material-management/inventory-setup/sku/view-print' },
          { title: 'View & Print Daily Alert', icon: 'fas fa-print', route: '/material-management/inventory-setup/daily-alert/view-print' },
          { title: 'View & Print Supplier', icon: 'fas fa-print', route: '/material-management/inventory-setup/supplier/view-print' }
        ]
      },
      { 
        title: 'Purchase Order Setup', 
        icon: 'fas fa-shopping-cart', 
        children: [
          { title: 'Define Purchaser', icon: 'fas fa-user-tie', route: '/material-management/purchase-order-setup/purchaser' },
          { title: 'Define Approver', icon: 'fas fa-user-check', route: '/material-management/purchase-order-setup/approver' },
          { title: 'Define Purchase Sub-Control', icon: 'fas fa-sliders-h', route: '/material-management/purchase-order-setup/sub-control' },
          { title: 'Define SKU Item Note Analysis Group', icon: 'fas fa-layer-group', route: '/material-management/purchase-order-setup/sku-note-group' },
          { title: 'Define SKU Item Note Analysis Code', icon: 'fas fa-tags', route: '/material-management/purchase-order-setup/sku-note-code' },
          { title: 'View & Print Purchaser', icon: 'fas fa-print', route: '/material-management/purchase-order-setup/purchaser/view-print' },
          { title: 'View & Print Approver', icon: 'fas fa-print', route: '/material-management/purchase-order-setup/approver/view-print' },
          { title: 'View & Print Purchase Sub-Control', icon: 'fas fa-print', route: '/material-management/purchase-order-setup/sub-control/view-print' },
          { title: 'View & Print SKU Item Note Analysis Group', icon: 'fas fa-print', route: '/material-management/purchase-order-setup/sku-note-group/view-print' },
          { title: 'View & Print SKU Item Note Analysis Code', icon: 'fas fa-print', route: '/material-management/purchase-order-setup/sku-note-code/view-print' }
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
          { title: 'Purchase Requisition Entry', icon: 'fas fa-file-alt', route: '/material-management/pr-po/purchase-requisition' },
          { title: 'Purchase Order Entry', icon: 'fas fa-file-signature', route: '/material-management/pr-po/purchase-order' },
          { title: 'Approve PR/PO', icon: 'fas fa-check-double', route: '/material-management/pr-po/approve' },
          { title: 'Cancel PR/PO', icon: 'fas fa-times-circle', route: '/material-management/pr-po/cancel' },
          { title: 'Close PR/PO', icon: 'fas fa-window-close', route: '/material-management/pr-po/close' }
        ]
      },
      { 
        title: 'PR/PO Reports', 
        icon: 'fas fa-chart-bar', 
        children: [
          { title: 'Print PR/PO', icon: 'fas fa-print', route: '/material-management/pr-po-reports/print-pr-po' },
          { title: 'Print PR/PO Cancel Report', icon: 'fas fa-print', route: '/material-management/pr-po-reports/print-cancel-report' },
          { title: 'Print PR/PO Status Report', icon: 'fas fa-print', route: '/material-management/pr-po-reports/print-status-report' },
          { title: 'Print PR/PO Delivery Schedule Report', icon: 'fas fa-print', route: '/material-management/pr-po-reports/print-delivery-schedule' },
          { title: 'Print PR/PO Analysis Report', icon: 'fas fa-print', route: '/material-management/pr-po-reports/print-analysis-report' },
          { title: 'Print Supplier Performance Report', icon: 'fas fa-print', route: '/material-management/pr-po-reports/print-supplier-performance' }
        ]
      },
      {
        title: 'PR/PO Period End Closing',
        icon: 'fas fa-calendar-check', 
        children: [
          { title: 'Purge Closed PR/PO', icon: 'fas fa-trash-alt', route: '/material-management/pr-po-period-end/purge-closed' },
          { title: 'Purge Cancelled PR/PO', icon: 'fas fa-trash-alt', route: '/material-management/pr-po-period-end/purge-cancelled' }
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
          { title: 'Receive Note Entry', icon: 'fas fa-file-import', route: '/material-management/inventory-control/rc-rt/receive-note' },
          { title: 'Return Note Entry', icon: 'fas fa-file-export', route: '/material-management/inventory-control/rc-rt/return-note' },
          { title: 'Cancel RC/RT', icon: 'fas fa-times-circle', route: '/material-management/inventory-control/rc-rt/cancel' }
        ]
      },
      {
        title: 'IS/MI/MO/LT',
        icon: 'fas fa-arrows-alt-h',
        children: [
          { title: 'Issue Note Entry', icon: 'fas fa-file-upload', route: '/material-management/inventory-control/is-mi-mo-lt/issue-note' },
          { title: 'Stock-In Entry', icon: 'fas fa-dolly-flatbed', route: '/material-management/inventory-control/is-mi-mo-lt/stock-in' },
          { title: 'Stock-Out Entry', icon: 'fas fa-dolly', route: '/material-management/inventory-control/is-mi-mo-lt/stock-out' },
          { title: 'Location Transfer Entry', icon: 'fas fa-people-carry', route: '/material-management/inventory-control/is-mi-mo-lt/location-transfer' },
          { title: 'Cancel IS/MI/MO/LT', icon: 'fas fa-ban', route: '/material-management/inventory-control/is-mi-mo-lt/cancel' }
        ]
      },
      {
        title: 'DR/CN',
        icon: 'fas fa-file-invoice-dollar',
        children: [
          { title: 'Debit Note Entry', icon: 'fas fa-file-medical', route: '/material-management/inventory-control/dr-cn/debit-note' },
          { title: 'Credit Note Entry', icon: 'fas fa-file-prescription', route: '/material-management/inventory-control/dr-cn/credit-note' },
          { title: 'Cancel DR/CN', icon: 'fas fa-times', route: '/material-management/inventory-control/dr-cn/cancel' }
        ]
      },
      { 
        title: 'Inventory Reports', 
        icon: 'fas fa-chart-pie',
        children: [
          { title: 'Print RC/RT', icon: 'fas fa-print', route: '/material-management/inventory-control/inventory-reports/print-rc-rt' },
          { title: 'Print IS/MI/MO/LT', icon: 'fas fa-print', route: '/material-management/inventory-control/inventory-reports/print-is-mi-mo-lt' },
          { title: 'Print DR/CN', icon: 'fas fa-print', route: '/material-management/inventory-control/inventory-reports/print-dr-cn' },
          { title: 'Print Stock Card Report', icon: 'fas fa-print', route: '/material-management/inventory-control/inventory-reports/print-stock-card' },
          { title: 'Print Stock Balance Report', icon: 'fas fa-print', route: '/material-management/inventory-control/inventory-reports/print-stock-balance' },
          { title: 'Print Stock Aging Report', icon: 'fas fa-print', route: '/material-management/inventory-control/inventory-reports/print-stock-aging' },
          { title: 'Print Re-Order Advice Report', icon: 'fas fa-print', route: '/material-management/inventory-control/inventory-reports/print-reorder-advice' },
          { title: 'Print Slow Moving Item Report', icon: 'fas fa-print', route: '/material-management/inventory-control/inventory-reports/print-slow-moving' }
        ]
      },
      {
        title: 'Inventory Stock Take',
        icon: 'fas fa-clipboard-check',
        children: [
          { title: 'Initialize Stock Take', icon: 'fas fa-play-circle', route: '/material-management/inventory-control/stock-take/initialize' },
          { title: 'Print Stock Take Sheet', icon: 'fas fa-print', route: '/material-management/inventory-control/stock-take/print-sheet' },
          { title: 'Update Stock Take Variance', icon: 'fas fa-edit', route: '/material-management/inventory-control/stock-take/update-variance' },
          { title: 'Print Stock Take Variance Report', icon: 'fas fa-print', route: '/material-management/inventory-control/stock-take/print-variance-report' },
          { title: 'Post Stock Take Variance', icon: 'fas fa-paper-plane', route: '/material-management/inventory-control/stock-take/post-variance' }
        ]
      },
      {
        title: 'Inventory Period End Closing',
        icon: 'fas fa-calendar-alt',
        children: [
          { title: 'Purge Closed Transaction', icon: 'fas fa-trash', route: '/material-management/inventory-control/period-end/purge-closed' },
          { title: 'Purge Cancelled Transaction', icon: 'fas fa-trash', route: '/material-management/inventory-control/period-end/purge-cancelled' }
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
          { title: 'Define Chart of Account', icon: 'fas fa-sitemap', route: '/material-management/account/setup/chart-of-account' },
          { title: 'Define Account Type', icon: 'fas fa-tags', route: '/material-management/account/setup/account-type' },
          { title: 'Define Account Sub-Type', icon: 'fas fa-tag', route: '/material-management/account/setup/account-sub-type' },
          { title: 'Define Department', icon: 'fas fa-building', route: '/material-management/account/setup/department' },
          { title: 'View & Print Chart of Account', icon: 'fas fa-print', route: '/material-management/account/setup/chart-of-account/view-print' },
          { title: 'View & Print Account Type', icon: 'fas fa-print', route: '/material-management/account/setup/account-type/view-print' },
          { title: 'View & Print Account Sub-Type', icon: 'fas fa-print', route: '/material-management/account/setup/account-sub-type/view-print' },
          { title: 'View & Print Department', icon: 'fas fa-print', route: '/material-management/account/setup/department/view-print' }
        ]
      },
      { 
        title: 'Posting to Accounts', 
        icon: 'fas fa-file-import',
        children: [
          { title: 'Post RC', icon: 'fas fa-share-square', route: '/material-management/account/posting/post-rc' },
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
    title: 'System Requirement',
    icon: 'fas fa-cogs',
    children: [
      { title: 'Define Corrugator Machine', icon: 'fas fa-server', route: null },
      { title: 'Define Converting Machine', icon: 'fas fa-sync-alt', route: null },
      { title: 'Define Process', icon: 'fas fa-project-diagram', route: null },
      { title: 'Define Machine Process', icon: 'fas fa-cogs', route: null },
      { title: 'Define Unplanned Activities', icon: 'fas fa-tasks', route: null },
      { title: 'Define Corrugator Downtime', icon: 'fas fa-clock', route: null },
      { title: 'Define Converting Downtime', icon: 'fas fa-clock', route: null }
    ]
  },
  {
    title: 'Production Planning',
    icon: 'fas fa-calendar-alt',
    children: [
      { title: 'Rough Cut Capacity Planning', icon: 'fas fa-chart-pie', route: null },
      { title: 'Production Scheduling', icon: 'fas fa-calendar-check', route: null }
    ]
  },
  {
    title: 'Production Monitoring Board',
    icon: 'fas fa-desktop',
    route: '/production-monitoring-board',
  },
  {
    title: 'Production Data Entry',
    icon: 'fas fa-keyboard',
    children: [
      { title: 'Corrugator Production Entry', icon: 'fas fa-file-alt', route: null },
      { title: 'Converting Production Entry', icon: 'fas fa-file-alt', route: null }
    ]
  },
  {
    title: 'Production Reports',
    icon: 'fas fa-chart-bar',
    children: [
      { title: 'Print Rough Cut Report', icon: 'fas fa-print', route: null },
      { title: 'Print Production Schedule', icon: 'fas fa-print', route: null },
      { title: 'Print Corrugator Production Report', icon: 'fas fa-print', route: null },
      { title: 'Print Converting Production Report', icon: 'fas fa-print', route: null }
    ]
  }
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
          { title: 'Update FG Stock-In by SO', icon: 'fas fa-file-import', route: '/warehouse-management/finished-goods/fg-normal/update-fg-stock-in-so' },
          { title: 'Update FG Stock-In by WO', icon: 'fas fa-file-import', route: '/warehouse-management/finished-goods/fg-normal/update-fg-stock-in-wo' },
          { title: 'Update FG Stock-In by Barcode', icon: 'fas fa-barcode', route: '/warehouse-management/finished-goods/fg-normal/update-fg-stock-in-barcode' },
          { title: 'Update FG Stock-Out by MC', icon: 'fas fa-file-export', route: '/warehouse-management/finished-goods/fg-normal/update-fg-stock-out-mc' },
          { title: 'Update FG Location Transfer', icon: 'fas fa-exchange-alt', route: '/warehouse-management/finished-goods/fg-normal/update-fg-location-transfer' },
          { title: 'Update FG Stock-Out by Batch', icon: 'fas fa-boxes', route: '/warehouse-management/finished-goods/fg-normal/update-fg-stock-out-batch' },
          { title: 'Print FG Stock-In Log', icon: 'fas fa-print', route: '/warehouse-management/finished-goods/fg-normal/print-fg-stock-in-log' },
          { title: 'Print FG Stock-Out Log', icon: 'fas fa-print', route: '/warehouse-management/finished-goods/fg-normal/print-fg-stock-out-log' },
          { title: 'Clear FGMC Lock', icon: 'fas fa-lock-open', route: '/warehouse-management/finished-goods/fg-normal/clear-fgmc-lock' },
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
          { title: 'Define Vehicle Class', icon: 'fas fa-car-side', route: '/warehouse-management/delivery-order/setup/define-vehicle-class' },
          { title: 'Define Vehicle', icon: 'fas fa-truck-pickup', route: '/warehouse-management/delivery-order/setup/define-vehicle' },
          { title: 'Define DORN Code', icon: 'fas fa-barcode', route: '/warehouse-management/delivery-order/setup/define-dorn-code' },
          { title: 'Define Greeting Message', icon: 'fas fa-comment-dots', route: '/warehouse-management/delivery-order/setup/define-greeting-message' },
          { title: 'Define Alternate Unit', icon: 'fas fa-balance-scale', route: '/warehouse-management/delivery-order/setup/define-alternate-unit' },
          { title: 'Define Master Card Alternate Unit', icon: 'fas fa-id-card', route: '/warehouse-management/delivery-order/setup/define-master-card-alternate-unit' },
          { title: 'Define D/Order Group', icon: 'fas fa-object-group', route: '/warehouse-management/delivery-order/setup/define-dorder-group' },
          { title: 'Define User\'s D/Order Group', icon: 'fas fa-users-cog', route: '/warehouse-management/delivery-order/setup/define-users-dorder-group' },
          { title: 'View & Print Analysis Code', icon: 'fas fa-print', route: '/warehouse-management/delivery-order/setup/view-print-analysis-code' },
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
          { title: 'Amend Delivery Order', icon: 'fas fa-edit', route: '/warehouse-management/delivery-order/do-processing/amend-do' },
          { title: 'Cancel Delivery Order', icon: 'fas fa-times-circle', route: '/warehouse-management/delivery-order/do-processing/cancel-do' },
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
      { title: 'Setup', icon: 'fas fa-cogs', route: '/warehouse-management/invoice/setup' },
      { title: 'IV Processing', icon: 'fas fa-tasks', route: '/warehouse-management/invoice/iv-processing' },
      { title: 'IV Special Processing', icon: 'fas fa-tasks', route: '/warehouse-management/invoice/iv-special-processing' },
      { title: 'Manual IV Processing', icon: 'fas fa-edit', route: '/warehouse-management/invoice/manual-iv-processing' },
      { title: 'Banker Acceptance', icon: 'fas fa-money-check-alt', route: '/warehouse-management/invoice/banker-acceptance' },
    ]
  },
  { title: 'Debit/Credit Note', icon: 'fas fa-exchange-alt', route: '/warehouse-management/debit-credit-note' },
  { title: 'Warehouse Analysis', icon: 'fas fa-chart-bar', route: '/warehouse-management/warehouse-analysis' },
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