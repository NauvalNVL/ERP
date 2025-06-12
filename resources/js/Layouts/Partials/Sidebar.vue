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
        title: 'Standard Formula',
        icon: 'fas fa-calculator',
        children: [
          { title: 'Define Product Design', icon: 'fas fa-drafting-compass', route: '/product-design/standard-formula' },
          // Add other standard formula items here
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
          { title: 'View & Print MC', icon: 'fas fa-print', route: null },
          { title: 'View & Print MC Maintenance Log', icon: 'fas fa-file-alt', route: null },
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
          { title: 'Define Roll Size', icon: 'fas fa-scroll', route: '/standard-formula/corrugator-run-size/roll-size' },
          { title: 'Define Side Trim by Flute', icon: 'fas fa-layer-group', route: '/standard-formula/corrugator-run-size/side-trim-by-flute' },
          { title: 'Define Side Trim by Product Design', icon: 'fas fa-ruler', route: '/standard-formula/corrugator-run-size/side-trim-by-product-design' },
          { title: 'View & Print Product Design', icon: 'fas fa-print', route: '/standard-formula/corrugator-run-size/view-print-product-design' },
          { title: 'View & Print Corr. Spesification by Product', icon: 'fas fa-print', route: '/standard-formula/setup-corrugator-specification-by-product/view-print' },
          { title: 'View & Print Roll Trim by Corrugator', icon: 'fas fa-print', route: '/standard-formula/setup-roll-trim-by-corrugator/view-print' },
          { title: 'View & Print Roll Trim by Product Design', icon: 'fas fa-print', route: '/standard-formula/setup-roll-trim-by-product-design/view-print' },
          { title: 'View & Print Roll Size', icon: 'fas fa-print', route: '/standard-formula/corrugator-run-size/view-print-roll-size' },
          { title: 'View & Print Side Trim by Flute', icon: 'fas fa-print', route: '/standard-formula/corrugator-run-size/view-print-side-trim-by-flute' },
          { title: 'View & Print Side Trim by Product Design', icon: 'fas fa-print', route: '/standard-formula/corrugator-run-size/view-print-side-trim-by-product-design' }
        ]
      },
      {
        title: 'Define Stitching Computation Method',
        icon: 'fas fa-paperclip',
        children: [
          { title: 'Define Computation Method', icon: 'fas fa-calculator', route: '/standard-formula/stitching-computation/method' },
          { title: 'Define Finishing', icon: 'fas fa-paint-roller', route: '/standard-formula/stitching-computation/finishing' },
          { title: 'View & Print Finishing', icon: 'fas fa-print', route: '/standard-formula/stitching-computation/view-print-finishing' }
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
        icon: 'fas fa-cog',
        children: [
          { title: 'Define SO Config', icon: 'fas fa-cogs', route: '/sales-order/setup/define-so-config' },
          { title: 'Define SO Period', icon: 'fas fa-calendar-alt', route: null },
          { title: 'Define SO Rough Cut', icon: 'fas fa-cut', route: '/sales-order/setup/define-so-rough-cut' },
          { title: 'Define AC# Auto WO', icon: 'fas fa-robot', route: '/sales-order/setup/define-ac-auto-wo' },
          { title: 'Define MC Auto WO', icon: 'fas fa-robot', route: '/sales-order/setup/define-mc-auto-wo' },
          { title: 'Print SO Period', icon: 'fas fa-print', route: null },
          { title: 'Print SO Rough Cut', icon: 'fas fa-print', route: null },
          { title: 'Print AC# Auto WO', icon: 'fas fa-print', route: null },
          { title: 'Print MC Auto WO', icon: 'fas fa-print', route: null }
        ]
      },
      { title: 'Transaction', icon: 'fas fa-exchange-alt', route: null },
      { title: 'Report', icon: 'fas fa-chart-bar', route: null }
    ]
  },
  { title: 'Customer Service', icon: 'fas fa-headset', route: null }
];

// Material Management Items
const materialManagementItems = [
  { title: 'System Requirement', icon: 'fas fa-clipboard-list', route: null },
  { title: 'Purchase Order', icon: 'fas fa-shopping-basket', route: null },
  { title: 'Inventory Control', icon: 'fas fa-box-open', route: null },
  { title: 'Costing', icon: 'fas fa-calculator', route: null },
  { title: 'Account', icon: 'fas fa-file-invoice-dollar', route: null }
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