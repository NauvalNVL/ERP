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
        <button v-if="user" @click="logout" class="text-gray-300 hover:text-red-400 transition-colors">
          <i class="fas fa-sign-out-alt"></i>
        </button>
        <Link v-else href="/login" class="text-gray-300 hover:text-green-400 transition-colors">
          <i class="fas fa-sign-in-alt"></i>
        </Link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { usePage, Link, router } from '@inertiajs/vue3';
import SidebarDropdown from './SidebarDropdown.vue';

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
          { title: 'View & Print Non-Active Customer', icon: 'fas fa-list', route: '/obsolete-reactive-customer-account/view-print' }
        ]
      },
      {
        title: 'Master Card',
        icon: 'fas fa-id-card',
        children: [
          { title: 'Update MC', icon: 'fas fa-edit', route: '/sales-management/system-requirement/master-card/update-mc' },
          { title: 'Approve MC', icon: 'fas fa-check', route: '/sales-management/system-requirement/master-card/approve-mc' },
          { title: 'Release Approved MC', icon: 'fas fa-unlock', route: '/sales-management/system-requirement/master-card/realese-approve-mc' },
          { title: 'Obsolate & Reactive MC', icon: 'fas fa-ban', route: '/sales-management/system-requirement/master-card/obsolate-reactive-mc' },
          { title: 'View & Print MC', icon: 'fas fa-print', route: '/sales-management/system-requirement/master-card/view-and-print-MC' },
          { title: 'View & Print MC Maintenance Log', icon: 'fas fa-file-alt', route: '/sales-management/system-requirement/master-card/view-and-print-mc-maintenance-log' },
          { title: 'View & Print MC Approval Log', icon: 'fas fa-file-alt', route: null },
          { title: 'View & Print Non-Active MC', icon: 'fas fa-file-alt', route: null },
          { title: 'Initialized MC Maintenance Log', icon: 'fas fa-file-alt', route: null },
          { title: 'View & Print MC Print/DC Block Listing', icon: 'fas fa-file-alt', route: null },
          { title: 'View & Print MC DC Block Matching', icon: 'fas fa-file-alt', route: null },
          { title: 'View & Print MC by Color', icon: 'fas fa-file-alt', route: null },
          { title: 'View & Print MC by P/Size P/Quality', icon: 'fas fa-file-alt', route: null },
          { title: 'View & Print MC by Machine', icon: 'fas fa-file-alt', route: null }
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
    icon: 'fas fa-file-invoice',
    children: [
      {
        title: 'Setup',
        icon: 'fas fa-cogs',
        children: [
          { title: 'Define SO Config', icon: 'fas fa-cog', route: '/sales-order/setup/define-so-config' },
          { title: 'Define SO Period', icon: 'fas fa-calendar-alt', route: '/sales-order/setup/define-so-period' },
          { title: 'Define SO Rough Cut', icon: 'fas fa-cut', route: '/sales-order/setup/define-so-rough-cut' },
          { title: 'Define AC# Auto WO', icon: 'fas fa-file-invoice', route: '/sales-order/setup/define-ac-auto-wo' },
          { title: 'Define MC Auto WO', icon: 'fas fa-file-invoice', route: '/sales-order/setup/define-mc-auto-wo' },
          { title: 'View & Print SO Period', icon: 'fas fa-print', route: '/sales-order/setup/print-so-period' },
          { title: 'View & Print Rough Cut Target Capacity', icon: 'fas fa-print', route: '/sales-order/setup/print-so-rough-cut' },
          { title: 'View & Print Customer for Auto Releasing W/Order', icon: 'fas fa-print', route: '/sales-order/setup/print-ac-auto-wo' },
          { title: 'View & Print M/Card for Not Auto Releasing W/Order', icon: 'fas fa-print', route: '/sales-order/setup/print-mc-auto-wo' },
        ]
      },
    ]
  },
  { title: 'Customer Service', icon: 'fas fa-headset', route: null }
];

// Material Management Items
const materialManagementItems = [
  { 
    title: 'System Requirement', 
    icon: 'fas fa-clipboard-list', 
    children: [
      { 
        title: 'Standard Setup', 
        icon: 'fas fa-tools', 
        children: [
          { title: 'Define Configuration', icon: 'fas fa-cogs', route: '/material-management/standard-setup/configuration' },
          { title: 'Define Control Period', icon: 'fas fa-calendar-alt', route: '/material-management/standard-setup/control-period' },
          { title: 'Define Transaction Type', icon: 'fas fa-exchange-alt', route: '/material-management/standard-setup/transaction-type' },
          { title: 'Define Tax Type', icon: 'fas fa-percentage', route: '/material-management/standard-setup/tax-type' },
          { title: 'Define Tax Group', icon: 'fas fa-layer-group', route: '/material-management/standard-setup/tax-group' },
          { title: 'Define Receive Destination', icon: 'fas fa-map-marker-alt', route: '/material-management/standard-setup/receive-destination' },
          { title: 'Define Analysis Code', icon: 'fas fa-tags', route: '/material-management/standard-setup/analysis-code' },
          { title: 'View & Print Control Period', icon: 'fas fa-print', route: '/material-management/standard-setup/control-period/view-print' },
          { title: 'View & Print Transaction Type', icon: 'fas fa-print', route: '/material-management/standard-setup/transaction-type/view-print' },
          { title: 'View & Print Tax Type', icon: 'fas fa-print', route: '/material-management/standard-setup/tax-type/view-print' },
          { title: 'View & Print Tax Group', icon: 'fas fa-print', route: '/material-management/standard-setup/tax-group/view-print' },
          { title: 'View & Print Receive Destination', icon: 'fas fa-print', route: '/material-management/standard-setup/receive-destination/view-print' },
          { title: 'View & Print Analysis Code', icon: 'fas fa-print', route: '/material-management/standard-setup/analysis-code/view-print' }
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
      },
      { 
        title: 'Inventory Setup', 
        icon: 'fas fa-warehouse', 
        children: [
          { title: 'Define Category', icon: 'fas fa-sitemap', route: '/material-management/inventory-setup/category' },
          { title: 'Define Location', icon: 'fas fa-map-marker-alt', route: '/material-management/inventory-setup/location' },
          { title: 'Define Unit', icon: 'fas fa-ruler', route: '/material-management/inventory-setup/unit' },
          { title: 'Define Report Group', icon: 'fas fa-layer-group', route: '/material-management/inventory-setup/report-group' },
          { title: 'Define MM GL Distribution', icon: 'fas fa-book', route: '/material-management/inventory-setup/mm-gl-distribution' },
          { title: 'Define SKU', icon: 'fas fa-barcode', route: '/material-management/inventory-setup/sku' },
          { title: 'Define SKU Price', icon: 'fas fa-tag', route: '/material-management/inventory-setup/sku-price' },
          { title: 'Amend SKU Type', icon: 'fas fa-edit', route: '/material-management/inventory-setup/amend-sku-type' },
          { title: 'Amend SKU#', icon: 'fas fa-hashtag', route: '/material-management/inventory-setup/amend-sku-number' },
          { title: 'Obsolete/Reactivate SKU Status', icon: 'fas fa-toggle-on', route: '/material-management/inventory-setup/sku-status' },
          { title: 'Define SKU Reorder Level', icon: 'fas fa-level-up-alt', route: '/material-management/inventory-setup/sku-reorder-level' },
          { title: 'Copy & Paste SKU Reorder Level', icon: 'fas fa-copy', route: '/material-management/inventory-setup/copy-sku-reorder-level' },
          { title: 'Define SKU Consumption Budget', icon: 'fas fa-chart-line', route: '/material-management/inventory-setup/sku-consumption-budget' },
          { title: 'Define Custom Tariff Code', icon: 'fas fa-file-invoice', route: '/material-management/inventory-setup/custom-tariff-code' },
          { title: 'Define SKU Custom Tariff Code', icon: 'fas fa-file-code', route: '/material-management/inventory-setup/sku-custom-tariff-code' },
          { title: 'Define DR/CR Note', icon: 'fas fa-sticky-note', route: '/material-management/inventory-setup/dr-cr-note' },
          { title: 'Unlock SKU Utility', icon: 'fas fa-unlock-alt', route: '/material-management/inventory-setup/unlock-sku-utility' },
          { title: 'View & Print Category', icon: 'fas fa-print', route: '/material-management/inventory-setup/category/view-print' },
          { title: 'View & Print Location', icon: 'fas fa-print', route: '/material-management/inventory-setup/location/view-print' },
          { title: 'View & Print Unit', icon: 'fas fa-print', route: '/material-management/inventory-setup/unit/view-print' },
          { title: 'View & Print Report Group', icon: 'fas fa-print', route: '/material-management/inventory-setup/report-group/view-print' },
          { title: 'View & Print MM GL Distribution', icon: 'fas fa-print', route: '/material-management/inventory-setup/mm-gl-distribution/view-print' },
          { title: 'View & Print SKU', icon: 'fas fa-print', route: '/material-management/inventory-setup/sku/view-print' },
          { title: 'View & Print SKU Price', icon: 'fas fa-print', route: '/material-management/inventory-setup/sku-price/view-print' },
          { title: 'View & Print SKU Reorder Level', icon: 'fas fa-print', route: '/material-management/inventory-setup/sku-reorder-level/view-print' },
          { title: 'View & Print SKU Consumption Budget', icon: 'fas fa-print', route: '/material-management/inventory-setup/sku-consumption-budget/view-print' },
          { title: 'View & Print Custom Tariff Code', icon: 'fas fa-print', route: '/material-management/inventory-setup/custom-tariff-code/view-print' },
          { title: 'View & Print SKU Custom Tariff Code', icon: 'fas fa-print', route: '/material-management/inventory-setup/sku-custom-tariff-code/view-print' },
          { title: 'View & Print DR/CR Note', icon: 'fas fa-print', route: '/material-management/inventory-setup/dr-cr-note/view-print' }
        ]
      }
    ]
  },
  { 
    title: 'Purchase Order', 
    icon: 'fas fa-shopping-basket', 
    children: [
      { 
        title: 'PR/PO', 
        icon: 'fas fa-file-invoice', 
        children: [
          // PR related items
          { title: 'Prepare PR', icon: 'fas fa-file-medical', route: '/material-management/purchase-order/pr-po/prepare-pr' },
          { title: 'Amend PR', icon: 'fas fa-edit', route: '/material-management/purchase-order/pr-po/amend-pr' },
          { title: 'Cancel PR', icon: 'fas fa-ban', route: '/material-management/purchase-order/pr-po/cancel-pr' },
          { title: 'Print PR', icon: 'fas fa-print', route: '/material-management/purchase-order/pr-po/print-pr' },
          { title: 'Approve PR', icon: 'fas fa-check-circle', route: '/material-management/purchase-order/pr-po/approve-pr' },
          { title: 'Amend Approved PR + Re-Submit', icon: 'fas fa-redo', route: '/material-management/purchase-order/pr-po/amend-approved-pr' },
          { title: 'Amend Rejected PR + Re-Submit', icon: 'fas fa-undo', route: '/material-management/purchase-order/pr-po/amend-rejected-pr' },
          { title: 'Cancel Approved PR', icon: 'fas fa-times-circle', route: '/material-management/purchase-order/pr-po/cancel-approved-pr' },
          { title: 'Close Approved PR', icon: 'fas fa-lock', route: '/material-management/purchase-order/pr-po/close-approved-pr' },
          { title: 'View & Print PR Log', icon: 'fas fa-history', route: '/material-management/purchase-order/pr-po/view-print-pr-log' },
          { title: 'Release Approve PR Lock', icon: 'fas fa-unlock', route: '/material-management/purchase-order/pr-po/release-pr-lock' },
          
          // PO related items
          { title: 'Prepare PO', icon: 'fas fa-file-medical', route: '/material-management/purchase-order/pr-po/prepare-po' },
          { title: 'Amend PO', icon: 'fas fa-edit', route: '/material-management/purchase-order/pr-po/amend-po' },
          { title: 'Cancel PO', icon: 'fas fa-ban', route: '/material-management/purchase-order/pr-po/cancel-po' },
          { title: 'Print PO', icon: 'fas fa-print', route: '/material-management/purchase-order/pr-po/print-po' },
          { title: 'Approve PO', icon: 'fas fa-check-circle', route: '/material-management/purchase-order/pr-po/approve-po' },
          { title: 'Amend Approved PO + Re-Submit', icon: 'fas fa-redo', route: '/material-management/purchase-order/pr-po/amend-approved-po' },
          { title: 'Amend Rejected PO + Re-Submit', icon: 'fas fa-undo', route: '/material-management/purchase-order/pr-po/amend-rejected-po' },
          { title: 'Cancel Approved PO', icon: 'fas fa-times-circle', route: '/material-management/purchase-order/pr-po/cancel-approved-po' },
          { title: 'Close Approved PO', icon: 'fas fa-lock', route: '/material-management/purchase-order/pr-po/close-approved-po' },
          { title: 'View & Print PO Log', icon: 'fas fa-history', route: '/material-management/purchase-order/pr-po/view-print-po-log' },
          { title: 'Release Approve PO Lock', icon: 'fas fa-unlock', route: '/material-management/purchase-order/pr-po/release-po-lock' }
        ]
      },
      { 
        title: 'PR/PO Reports', 
        icon: 'fas fa-chart-bar', 
        children: [
          { title: 'View & Print PO Arrival Schedule', icon: 'fas fa-calendar-alt', route: '/material-management/purchase-order/pr-po-reports/po-arrival-schedule' },
          { title: 'View & Print PR and PO Report', icon: 'fas fa-file-alt', route: '/material-management/purchase-order/pr-po-reports/pr-po-report' },
          { title: 'View & Print PO, RC & RT Report', icon: 'fas fa-file-invoice', route: '/material-management/purchase-order/pr-po-reports/po-rc-rt-report' },
          { title: 'View & Print PSC Report', icon: 'fas fa-clipboard-list', route: '/material-management/purchase-order/pr-po-reports/psc-report' },
          { title: 'View & Print SKU Historical Price', icon: 'fas fa-history', route: '/material-management/purchase-order/pr-po-reports/sku-historical-price' }
        ]
      },
      { 
        title: 'PR/PO Period-End Closing', 
        icon: 'fas fa-calendar-check', 
        children: [
          { title: 'Perform PR & PO Period-End Closing', icon: 'fas fa-lock', route: '/material-management/purchase-order/pr-po-closing/perform' }
        ]
      }
    ]
  },
  { 
    title: 'Inventory Control', 
    icon: 'fas fa-box-open', 
    children: [
      { 
        title: 'RC/RT', 
        icon: 'fas fa-truck-loading', 
        children: [
          // RC related items
          { title: 'Prepare RC', icon: 'fas fa-file-import', route: '/material-management/inventory-control/rc-rt/prepare-rc' },
          { title: 'Amend RC', icon: 'fas fa-edit', route: '/material-management/inventory-control/rc-rt/amend-rc' },
          { title: 'Print RC', icon: 'fas fa-print', route: '/material-management/inventory-control/rc-rt/print-rc' },
          { title: 'View & Print RC Log', icon: 'fas fa-history', route: '/material-management/inventory-control/rc-rt/view-print-rc-log' },
          
          // RT related items
          { title: 'Prepare RT', icon: 'fas fa-file-export', route: '/material-management/inventory-control/rc-rt/prepare-rt' },
          { title: 'Amend RT', icon: 'fas fa-edit', route: '/material-management/inventory-control/rc-rt/amend-rt' },
          { title: 'Print RT', icon: 'fas fa-print', route: '/material-management/inventory-control/rc-rt/print-rt' },
          { title: 'View & Print RT Log', icon: 'fas fa-history', route: '/material-management/inventory-control/rc-rt/view-print-rt-log' }
        ]
      },
      { 
        title: 'DR/CN', 
        icon: 'fas fa-file-alt', 
        children: [
          // DN related items
          { title: 'Prepare DN', icon: 'fas fa-file-invoice', route: '/material-management/inventory-control/dr-cn/prepare-dn' },
          { title: 'Amend DN', icon: 'fas fa-edit', route: '/material-management/inventory-control/dr-cn/amend-dn' },
          { title: 'Cancel DN', icon: 'fas fa-ban', route: '/material-management/inventory-control/dr-cn/cancel-dn' },
          { title: 'Print DN', icon: 'fas fa-print', route: '/material-management/inventory-control/dr-cn/print-dn' },
          { title: 'View & Print DN Log', icon: 'fas fa-history', route: '/material-management/inventory-control/dr-cn/view-print-dn-log' },
          
          // CN related items
          { title: 'Prepare CN', icon: 'fas fa-file-contract', route: '/material-management/inventory-control/dr-cn/prepare-cn' },
          { title: 'Amend CN', icon: 'fas fa-edit', route: '/material-management/inventory-control/dr-cn/amend-cn' },
          { title: 'Cancel CN', icon: 'fas fa-ban', route: '/material-management/inventory-control/dr-cn/cancel-cn' },
          { title: 'Print CN', icon: 'fas fa-print', route: '/material-management/inventory-control/dr-cn/print-cn' },
          { title: 'View & Print CN Log', icon: 'fas fa-history', route: '/material-management/inventory-control/dr-cn/view-print-cn-log' }
        ]
      },
      { 
        title: 'IS/MI/MO/LT', 
        icon: 'fas fa-exchange-alt', 
        children: [
          // IS related items
          { title: 'Prepare IS', icon: 'fas fa-dolly', route: '/material-management/inventory-control/is-mi-mo-lt/prepare-is' },
          { title: 'Cancel IS', icon: 'fas fa-ban', route: '/material-management/inventory-control/is-mi-mo-lt/cancel-is' },
          { title: 'Print IS', icon: 'fas fa-print', route: '/material-management/inventory-control/is-mi-mo-lt/print-is' },
          
          // MI related items
          { title: 'Prepare MI', icon: 'fas fa-dolly-flatbed', route: '/material-management/inventory-control/is-mi-mo-lt/prepare-mi' },
          { title: 'Cancel MI', icon: 'fas fa-ban', route: '/material-management/inventory-control/is-mi-mo-lt/cancel-mi' },
          { title: 'Print MI', icon: 'fas fa-print', route: '/material-management/inventory-control/is-mi-mo-lt/print-mi' },
          
          // MO related items
          { title: 'Prepare MO', icon: 'fas fa-truck-moving', route: '/material-management/inventory-control/is-mi-mo-lt/prepare-mo' },
          { title: 'Cancel MO', icon: 'fas fa-ban', route: '/material-management/inventory-control/is-mi-mo-lt/cancel-mo' },
          { title: 'Print MO', icon: 'fas fa-print', route: '/material-management/inventory-control/is-mi-mo-lt/print-mo' },
          
          // Log
          { title: 'View & Print IS/MI/MO/LT Log', icon: 'fas fa-history', route: '/material-management/inventory-control/is-mi-mo-lt/view-print-log' }
        ]
      },
      { 
        title: 'Inventory Reports', 
        icon: 'fas fa-clipboard-list', 
        children: [
          { title: 'Print SKU Balance Report', icon: 'fas fa-balance-scale', route: '/material-management/inventory-control/reports/sku-balance' },
          { title: 'Print SKU Reorder Report', icon: 'fas fa-level-up-alt', route: '/material-management/inventory-control/reports/sku-reorder' },
          { title: 'Print SKU Ledger Report', icon: 'fas fa-book', route: '/material-management/inventory-control/reports/sku-ledger' },
          { title: 'Print SKU Aging Report', icon: 'fas fa-calendar-alt', route: '/material-management/inventory-control/reports/sku-aging' },
          { title: 'Print SKU Open Item Aging Report', icon: 'fas fa-folder-open', route: '/material-management/inventory-control/reports/sku-open-item-aging' },
          { title: 'Inquire SKU Account', icon: 'fas fa-search', route: '/material-management/inventory-control/reports/inquire-sku-account' }
        ]
      },
      { 
        title: 'Inventory Period-End Closing', 
        icon: 'fas fa-calendar-check', 
        children: [
          { title: 'Perform Inventory Period-End Closing', icon: 'fas fa-lock', route: '/material-management/inventory-control/closing/perform' }
        ]
      },
      { 
        title: 'Inventory Stock-Take', 
        icon: 'fas fa-clipboard-check', 
        children: [
          { title: 'Run Stock-Take New Batch', icon: 'fas fa-play', route: '/material-management/inventory-control/stock-take/run-new-batch' },
          { title: 'Input Stock-Take Data', icon: 'fas fa-keyboard', route: '/material-management/inventory-control/stock-take/input-data' },
          { title: 'Print Stock-Take Data', icon: 'fas fa-print', route: '/material-management/inventory-control/stock-take/print-data' },
          { title: 'Print System Stock-Take Data', icon: 'fas fa-server', route: '/material-management/inventory-control/stock-take/print-system-data' },
          { title: 'Print Stock-Take Matching Report', icon: 'fas fa-check-double', route: '/material-management/inventory-control/stock-take/print-matching-report' }
        ]
      }
    ]
  },
  { 
    title: 'Account', 
    icon: 'fas fa-file-invoice-dollar', 
    children: [
      { 
        title: 'Setup Accounts', 
        icon: 'fas fa-cogs', 
        children: [
          { title: 'Setup Purchase SKU Accounts', icon: 'fas fa-shopping-cart', route: '/material-management/account/setup/purchase-sku-accounts' },
          { title: 'Setup Purchase Tax Accounts', icon: 'fas fa-percentage', route: '/material-management/account/setup/purchase-tax-accounts' },
          { title: 'Setup Purchase DN/CN Accounts', icon: 'fas fa-file-invoice', route: '/material-management/account/setup/purchase-dn-cn-accounts' },
          { title: 'Setup Inventory SKU Accounts', icon: 'fas fa-warehouse', route: '/material-management/account/setup/inventory-sku-accounts' },
          { title: 'View & Print Purchase AP Accounts', icon: 'fas fa-print', route: '/material-management/account/setup/view-print-purchase-ap-accounts' },
          { title: 'View & Print Purchase SKU Accounts', icon: 'fas fa-print', route: '/material-management/account/setup/view-print-purchase-sku-accounts' },
          { title: 'View & Print Purchase Tax Accounts', icon: 'fas fa-print', route: '/material-management/account/setup/view-print-purchase-tax-accounts' },
          { title: 'View & Print Purchase DN/CN Accounts', icon: 'fas fa-print', route: '/material-management/account/setup/view-print-purchase-dn-cn-accounts' },
          { title: 'View & Print Inventory SKU Accounts', icon: 'fas fa-print', route: '/material-management/account/setup/view-print-inventory-sku-accounts' }
        ]
      },
      { 
        title: 'Posting to Accounts', 
        icon: 'fas fa-book', 
        children: [
          { 
            title: 'Post RC', 
            icon: 'fas fa-file-import', 
            children: [
              { title: 'Prepare RC Posting Batch', icon: 'fas fa-layer-group', route: '/material-management/account/posting/rc/prepare-batch' },
              { title: 'Cancel RC Posting Batch', icon: 'fas fa-ban', route: '/material-management/account/posting/rc/cancel-batch' },
              { title: 'View & Print RC Posting Batch', icon: 'fas fa-print', route: '/material-management/account/posting/rc/view-print-batch' },
              { title: 'Confirm to Post RC', icon: 'fas fa-check-circle', route: '/material-management/account/posting/rc/confirm-post' }
            ]
          },
          { 
            title: 'Post RT', 
            icon: 'fas fa-file-export', 
            children: [
              { title: 'Prepare RT Posting Batch', icon: 'fas fa-layer-group', route: '/material-management/account/posting/rt/prepare-batch' },
              { title: 'Cancel RT Posting Batch', icon: 'fas fa-ban', route: '/material-management/account/posting/rt/cancel-batch' },
              { title: 'View & Print RT Posting Batch', icon: 'fas fa-print', route: '/material-management/account/posting/rt/view-print-batch' },
              { title: 'Confirm to Post RT', icon: 'fas fa-check-circle', route: '/material-management/account/posting/rt/confirm-post' }
            ]
          },
          { 
            title: 'Post DN', 
            icon: 'fas fa-file-invoice', 
            children: [
              { title: 'Prepare DN Posting Batch', icon: 'fas fa-layer-group', route: '/material-management/account/posting/dn/prepare-batch' },
              { title: 'Cancel DN Posting Batch', icon: 'fas fa-ban', route: '/material-management/account/posting/dn/cancel-batch' },
              { title: 'View & Print DN Posting Batch', icon: 'fas fa-print', route: '/material-management/account/posting/dn/view-print-batch' },
              { title: 'Confirm to Post DN', icon: 'fas fa-check-circle', route: '/material-management/account/posting/dn/confirm-post' }
            ]
          },
          { 
            title: 'Post CN', 
            icon: 'fas fa-file-contract', 
            children: [
              { title: 'Prepare CN Posting Batch', icon: 'fas fa-layer-group', route: '/material-management/account/posting/cn/prepare-batch' },
              { title: 'Cancel CN Posting Batch', icon: 'fas fa-ban', route: '/material-management/account/posting/cn/cancel-batch' },
              { title: 'View & Print CN Posting Batch', icon: 'fas fa-print', route: '/material-management/account/posting/cn/view-print-batch' },
              { title: 'Confirm to Post CN', icon: 'fas fa-check-circle', route: '/material-management/account/posting/cn/confirm-post' }
            ]
          },
          { 
            title: 'Post IS', 
            icon: 'fas fa-dolly', 
            children: [
              { title: 'Prepare IS Posting Batch', icon: 'fas fa-layer-group', route: '/material-management/account/posting/is/prepare-batch' },
              { title: 'Cancel IS Posting Batch', icon: 'fas fa-ban', route: '/material-management/account/posting/is/cancel-batch' },
              { title: 'View & Print IS Posting Batch', icon: 'fas fa-print', route: '/material-management/account/posting/is/view-print-batch' },
              { title: 'Confirm to Post IS', icon: 'fas fa-check-circle', route: '/material-management/account/posting/is/confirm-post' }
            ]
          },
          { 
            title: 'Post MI', 
            icon: 'fas fa-dolly-flatbed', 
            children: [
              { title: 'Prepare MI Posting Batch', icon: 'fas fa-layer-group', route: '/material-management/account/posting/mi/prepare-batch' },
              { title: 'Cancel MI Posting Batch', icon: 'fas fa-ban', route: '/material-management/account/posting/mi/cancel-batch' },
              { title: 'View & Print MI Posting Batch', icon: 'fas fa-print', route: '/material-management/account/posting/mi/view-print-batch' },
              { title: 'Confirm to Post MI', icon: 'fas fa-check-circle', route: '/material-management/account/posting/mi/confirm-post' }
            ]
          },
          { 
            title: 'Post MO', 
            icon: 'fas fa-truck-moving', 
            children: [
              { title: 'Prepare MO Posting Batch', icon: 'fas fa-layer-group', route: '/material-management/account/posting/mo/prepare-batch' },
              { title: 'Cancel MO Posting Batch', icon: 'fas fa-ban', route: '/material-management/account/posting/mo/cancel-batch' },
              { title: 'View & Print MO Posting Batch', icon: 'fas fa-print', route: '/material-management/account/posting/mo/view-print-batch' },
              { title: 'Confirm to Post MO', icon: 'fas fa-check-circle', route: '/material-management/account/posting/mo/confirm-post' }
            ]
          }
        ]
      }
    ]
  }
];

// Production Management Items
const productionManagementItems = [
  { title: 'Production Configuration', icon: 'fas fa-sliders-h', route: null },
  { title: 'Production Work Order', icon: 'fas fa-clipboard-list', route: null },
  { title: 'Production Floor Tracking', icon: 'fas fa-tasks', route: null }
];

// Warehouse Management Items
const warehouseManagementItems = [
  { title: 'Finished Goods', icon: 'fas fa-box-open', route: null },
  { title: 'Delivery Order', icon: 'fas fa-truck', route: null },
  { title: 'Invoice', icon: 'fas fa-file-invoice', route: null },
  { title: 'Debit & Credit Note', icon: 'fas fa-file-invoice-dollar', route: null },
  { title: 'Warehouse Analysis', icon: 'fas fa-chart-bar', route: null },
  { title: 'Custom Indonesia', icon: 'fas fa-landmark', route: null },
  { title: 'Accounts', icon: 'fas fa-calculator', route: null }
];
</script>

<style scoped>
.hide-scrollbar::-webkit-scrollbar {
  display: none;
}

.hide-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}

.sidebar-content {
  height: calc(100vh - 140px);
  max-height: 100%;
  overflow-y: auto;
  scrollbar-width: thin;
  scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
  padding-bottom: 20px;
  overscroll-behavior: contain;
}

.sidebar-content::-webkit-scrollbar {
  width: 4px;
}

.sidebar-content::-webkit-scrollbar-track {
  background: transparent;
}

.sidebar-content::-webkit-scrollbar-thumb {
  background-color: rgba(156, 163, 175, 0.5);
  border-radius: 20px;
}

.pulse {
  animation: pulse 2s infinite;
}

.slide-in-right {
  animation: slideInRight 0.5s ease-out;
}

.slide-in-up {
  animation: slideInUp 0.5s ease-out;
  animation-delay: 0.3s;
  opacity: 0;
  animation-fill-mode: forwards;
}

.hover-float {
  transition: transform 0.3s ease;
}

.hover-float:hover {
  transform: translateY(-2px);
}

/* Active menu item highlight transitions */
a, button {
  position: relative;
  transition: all 0.3s ease;
  overflow: hidden;
}

a.bg-blue-600, button.bg-gray-700 {
  box-shadow: 0 0 10px rgba(59, 130, 246, 0.5);
}

a.bg-blue-600::before, button.bg-gray-700::before {
  content: '';
  position: absolute;
  left: 0;
  top: 0;
  height: 100%;
  width: 3px;
  background-color: #60a5fa;
  transform: scaleY(1);
  transition: transform 0.3s ease;
}

@keyframes pulse {
  0% {
    transform: scale(1);
    box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7);
  }
  70% {
    transform: scale(1.05);
    box-shadow: 0 0 0 10px rgba(59, 130, 246, 0);
  }
  100% {
    transform: scale(1);
    box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
  }
}

@keyframes slideInRight {
  from {
    transform: translateX(-20px);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

@keyframes slideInUp {
  from {
    transform: translateY(20px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}
</style> 