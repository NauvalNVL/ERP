<template>
  <div class="flex flex-col h-full bg-gray-800 text-white w-full">
    <!-- Header -->
    <div class="px-4 py-3 border-b border-gray-700 flex-shrink-0">
      <div class="flex items-center justify-between">
      <div class="flex items-center">
        <div class="w-10 h-10 flex items-center justify-center bg-blue-600 text-white rounded-full mr-3 pulse">
          <i class="fas fa-building text-xl"></i>
        </div>
        <h1 class="text-xl font-bold slide-in-right">ERP System</h1>
        </div>
        <!-- Mobile Close Button -->
        <button
          @click="closeMobileSidebar"
          class="p-2 text-gray-300 hover:text-white rounded-full lg:hidden hover:bg-gray-700 transition-colors"
          title="Close sidebar"
        >
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>
    </div>

    <!-- Scrollable Navigation Menu - Fixed max-height with overflow -->
    <div class="flex-1 overflow-y-auto custom-scrollbar">
      <nav class="px-2 py-4 space-y-2">
      <!-- Dashboard -->
      <div class="relative group">
        <Link 
          href="/dashboard" 
            @click="closeMobileSidebar"
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
          menu-id="system-manager"
      />

      <!-- Sales Management -->
      <SidebarDropdown 
        title="Sales Management" 
        icon="fas fa-chart-line"
        :items="salesManagementItems"
          menu-id="sales-management"
      />

      <!-- Material Management -->
      <SidebarDropdown 
        title="Material Management" 
        icon="fas fa-boxes"
        :items="materialManagementItems"
          menu-id="material-management"
      />

      <!-- Production Management -->
      <SidebarDropdown 
        title="Production Management" 
        icon="fas fa-industry"
        :items="productionManagementItems"
          menu-id="production-management"
      />

      <!-- Warehouse Management -->
      <SidebarDropdown 
        title="Warehouse Management" 
        icon="fas fa-warehouse"
        :items="warehouseManagementItems"
          menu-id="warehouse-management"
      />

      <!-- Data Mining -->
      <div class="relative group">
        <Link 
          href="/data-mining" 
            @click="closeMobileSidebar"
          class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg transition-colors"
          :class="{ 'bg-blue-600 text-white font-medium': currentPath === '/data-mining' }"
        >
          <i class="fas fa-database w-5 h-5 mr-3"></i>
          <span>Data Mining</span>
        </Link>
      </div>
    </nav>
    </div>

    <!-- Footer -->
    <div class="border-t border-gray-700 p-4 bg-gray-800 flex-shrink-0">
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
import { computed } from 'vue';
import { usePage, Link, router } from '@inertiajs/vue3';
import SidebarDropdown from './SidebarDropdown.vue';
import sidebarStore from './sidebarStore';

const page = usePage();
const user = computed(() => page.props.auth?.user);
const userInitial = computed(() => user.value?.username ? user.value.username.charAt(0).toUpperCase() : 'G');
const currentPath = computed(() => page.url);

const logout = () => {
  router.post('/logout', {}, {
    onSuccess: () => {
      window.location.href = '/login';
    },
  });
};

const resetMenus = () => {
  sidebarStore.resetState();
};

const closeMobileSidebar = () => {
  sidebarStore.closeMobile();
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
          { title: 'Initialized MC Maintenance Log', icon: 'fas fa-history', route: '/sales-management/system-requirement/master-card/initialized-mc-maintenance-log' },
          { title: 'View & Print Update MC', icon: 'fas fa-print', route: '/sales-management/system-requirement/master-card/view-print-update-mc' },
          { title: 'View & Print Approved MC', icon: 'fas fa-print', route: '/sales-management/system-requirement/master-card/view-print-approved-mc' },
          { title: 'View & Print Released MC', icon: 'fas fa-print', route: '/sales-management/system-requirement/master-card/view-print-released-mc' },
          { title: 'View & Print Obsolete & Reactive MC', icon: 'fas fa-print', route: '/sales-management/system-requirement/master-card/view-print-obsolete-reactive-mc' },
          { title: 'View & Print MC Maintenance Log', icon: 'fas fa-print', route: '/sales-management/system-requirement/master-card/view-print-mc-maintenance-log' }
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
    icon: 'fas fa-sitemap',
    children: [
      { 
        title: 'Standard Setup', 
        icon: 'fas fa-tools',
        children: [
          { title: 'Configuration', icon: 'fas fa-cogs', route: '/material-management/system-requirement/standard-setup/configuration' },
          { title: 'Control Period', icon: 'fas fa-calendar-alt', route: '/material-management/system-requirement/standard-setup/control-period' },
          { title: 'Analysis Code', icon: 'fas fa-chart-pie', route: '/material-management/system-requirement/standard-setup/analysis-code' },
          { title: 'Receive Destination', icon: 'fas fa-map-marker-alt', route: '/material-management/system-requirement/standard-setup/receive-destination' },
          { title: 'Transaction Type', icon: 'fas fa-exchange-alt', route: '/material-management/system-requirement/standard-setup/transaction-type' },
          { title: 'Tax Type', icon: 'fas fa-percent', route: '/material-management/system-requirement/standard-setup/tax-type' },
          { title: 'Tax Group', icon: 'fas fa-layer-group', route: '/material-management/system-requirement/standard-setup/tax-group' },
          // View & Print
          { title: 'View & Print Control Period', icon: 'fas fa-print', route: '/material-management/system-requirement/standard-setup/control-period/view-print' },
          { title: 'View & Print Analysis Code', icon: 'fas fa-print', route: '/material-management/system-requirement/standard-setup/analysis-code/view-print' },
          { title: 'View & Print Receive Destination', icon: 'fas fa-print', route: '/material-management/system-requirement/standard-setup/receive-destination/view-print' },
          { title: 'View & Print Transaction Type', icon: 'fas fa-print', route: '/material-management/system-requirement/standard-setup/transaction-type/view-print' },
          { title: 'View & Print Tax Type', icon: 'fas fa-print', route: '/material-management/system-requirement/standard-setup/tax-type/view-print' },
          { title: 'View & Print Tax Group', icon: 'fas fa-print', route: '/material-management/system-requirement/standard-setup/tax-group/view-print' },
        ],
      },
      {
        title: 'Inventory Setup',
        icon: 'fas fa-dolly-flatbed',
        children: [
          { title: 'Category', icon: 'fas fa-tags', route: '/material-management/system-requirement/inventory-setup/category' },
          { title: 'Location', icon: 'fas fa-map-marked-alt', route: '/material-management/system-requirement/inventory-setup/location' },
          { title: 'SKU', icon: 'fas fa-barcode', route: '/material-management/system-requirement/inventory-setup/sku' },
          // View & Print
          { title: 'View & Print Category', icon: 'fas fa-print', route: '/material-management/system-requirement/inventory-setup/category/view-print' },
          { title: 'View & Print Location', icon: 'fas fa-print', route: '/material-management/system-requirement/inventory-setup/location/view-print' },
          { title: 'View & Print SKU', icon: 'fas fa-print', route: '/material-management/system-requirement/inventory-setup/sku/view-print' },
        ]
      },
      { 
        title: 'Purchase Order Setup', 
        icon: 'fas fa-file-invoice-dollar',
        children: [
          // Add items here
        ],
      },
    ],
  },
  { 
    title: 'Purchase Order', 
    icon: 'fas fa-shopping-basket',
    children: [
      { 
        title: 'P/R & P/O',
        icon: 'fas fa-file-signature',
        children: [
          // Add items here
        ],
      },
       {
        title: 'P/R & P/O Reports',
        icon: 'fas fa-chart-line',
        children: [
          // Add items here
        ],
      },
      {
        title: 'P/R & P/O Period End Closing',
        icon: 'fas fa-calendar-check', 
        children: [
          // Add items here
        ],
      },
    ],
  },
  { 
    title: 'Inventory Control', 
    icon: 'fas fa-tasks',
    children: [
      {
        title: 'R/C & R/T',
        icon: 'fas fa-truck-loading',
        children: [
          // Add items here
        ],
      },
       {
        title: 'I/S, M/I, M/O, L/T',
        icon: 'fas fa-exchange-alt',
        children: [
          // Add items here
        ],
      },
       {
        title: 'D/R & C/N',
        icon: 'fas fa-file-invoice',
        children: [
          // Add items here
        ],
      },
      {
        title: 'Inventory Stock Take',
        icon: 'fas fa-clipboard-check',
        children: [
          // Add items here
        ],
      },
       {
        title: 'Inventory Reports',
        icon: 'fas fa-chart-bar',
        children: [
          // Add items here
        ],
      },
      {
        title: 'Inventory Period End Closing',
        icon: 'fas fa-lock',
        children: [
          // Add items here
        ],
      },
    ],
  },
  {
    title: 'Accounts',
    icon: 'fas fa-university',
    children: [
      // Add items here
    ],
  },
];

// Production Management Items
const productionManagementItems = [
  // Add items here
];

// Warehouse Management Items
const warehouseManagementItems = [
  {
    title: 'Finished Goods',
    icon: 'fas fa-box-open',
    children: [
      {
        title: 'Setup & Maintenance',
        icon: 'fas fa-cogs',
        children: [
          { title: 'Define Configuration', icon: 'fas fa-sliders-h', route: '/warehouse-management/finished-goods/setup-maintenance/define-configuration' },
          { title: 'Define Control Period', icon: 'fas fa-calendar', route: '/warehouse-management/finished-goods/setup-maintenance/define-control-period' },
          { title: 'Define Analysis Code', icon: 'fas fa-chart-pie', route: '/warehouse-management/finished-goods/setup-maintenance/define-analysis-code' },
          { title: 'Define Transaction Type', icon: 'fas fa-exchange-alt', route: '/warehouse-management/finished-goods/setup-maintenance/define-transaction-type' },
          { title: 'Define Warehouse Location', icon: 'fas fa-warehouse', route: '/warehouse-management/finished-goods/setup-maintenance/define-warehouse-location' },
          { title: 'Define Customer Warehouse Location', icon: 'fas fa-map-marker-alt', route: '/warehouse-management/finished-goods/setup-maintenance/define-customer-warehouse-location' },
          { title: 'Define DO Entry Dates', icon: 'fas fa-calendar-plus', route: '/warehouse-management/finished-goods/setup-maintenance/define-do-entry-dates' },
        ]
      },
      { title: 'Finished Goods Stock Entry (Stock-In)', icon: 'fas fa-dolly', route: '/warehouse-management/finished-goods/stock-entry' },
      { title: 'Delivery Order Entry', icon: 'fas fa-truck', route: '/warehouse-management/finished-goods/delivery-order-entry' },
      { title: 'Finished Goods Stock Balance Adjustment', icon: 'fas fa-sliders-h', route: '/warehouse-management/finished-goods/balance-adjustment' },
      { title: 'Finished Goods Monthly Closing', icon: 'fas fa-calendar-check', route: '/warehouse-management/finished-goods/monthly-closing' },
      {
        title: 'Reports',
        icon: 'fas fa-file-alt',
        children: [
          // report items here
        ]
      }
    ]
  }
];

</script>

<style scoped>
/* Custom Scrollbar Styles */
.custom-scrollbar::-webkit-scrollbar {
  width: 8px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background-color: #1f2937; /* bg-gray-800 */
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: #4b5563; /* bg-gray-600 */
  border-radius: 10px;
  border: 2px solid #1f2937; /* bg-gray-800 */
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background-color: #6b7280; /* bg-gray-500 */
}

/* Ensure proper scrolling for nested menus */
.dropdown-menu {
  max-height: none !important;
  overflow: visible !important;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes slide-in-right {
  from { transform: translateX(-15px); opacity: 0; }
  to { transform: translateX(0); opacity: 1; }
}

@keyframes slide-in-up {
  from { transform: translateY(15px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}

@keyframes pulse {
  0%, 100% {
    transform: scale(1);
    box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4);
  }
  50% {
    transform: scale(1.05);
    box-shadow: 0 0 10px 5px rgba(59, 130, 246, 0);
  }
}

.animate-fadeIn {
  animation: fadeIn 0.5s ease-in-out;
}

.slide-in-right {
  animation: slide-in-right 0.5s ease-in-out forwards;
}

.slide-in-up {
  animation: slide-in-up 0.5s ease-in-out forwards;
}

.pulse {
  animation: pulse 2s infinite;
}
</style> 