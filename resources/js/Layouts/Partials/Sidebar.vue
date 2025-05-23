<template>
  <div class="flex flex-col h-full bg-gray-800 text-white animate-fadeIn">
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
    <nav class="flex-grow px-2 py-4 space-y-2 overflow-y-auto hide-scrollbar">
      <!-- Dashboard -->
      <div class="relative group">
        <Link href="/vue/dashboard" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
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
        <Link href="/vue/dashboard" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
          <i class="fas fa-database w-5 h-5 mr-3"></i>
          <span>Data Mining</span>
        </Link>
      </div>
    </nav>

    <!-- User Profile & Logout -->
    <div class="border-t border-gray-700 p-4 mt-auto slide-in-up">
      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <div class="w-8 h-8 rounded-full bg-gray-600 flex items-center justify-center">
            <span class="text-sm font-medium">{{ userInitial }}</span>
          </div>
          <div class="ml-3">
            <span class="text-sm font-medium">{{ user?.name || 'Guest' }}</span>
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
const userInitial = computed(() => user.value?.name ? user.value.name.charAt(0) : 'G');

const logout = () => {
  // Gunakan form submission langsung
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = '/logout';
  
  // Tambahkan CSRF token
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  const csrfInput = document.createElement('input');
  csrfInput.type = 'hidden';
  csrfInput.name = '_token';
  csrfInput.value = csrfToken;
  
  form.appendChild(csrfInput);
  document.body.appendChild(form);
  form.submit();
};

// System Manager Items
const systemManagerItems = [
  {
    title: 'System Security',
    icon: 'fas fa-shield-alt',
    children: [
      { title: 'Define User', icon: 'fas fa-user-plus', route: 'vue/user' },
      { title: 'Amend User Password', icon: 'fas fa-key', route: 'vue/system-security/amend-password' },
      { title: 'Release Locked Password', icon: 'fas fa-unlock', route: null },
      { title: 'Define User Access Permission', icon: 'fas fa-user-lock', route: 'vue/system-security/define-access' },
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
      { title: 'Define ISO Currency', icon: 'fas fa-coins', route: 'vue/iso-currency' },
      { title: 'Define Foreign Currency', icon: 'fas fa-money-bill-wave', route: 'vue/foreign-currency' },
      { title: 'Define Business Form', icon: 'fas fa-file-alt', route: 'vue/business-form' },
      { title: 'View & Print ISO Currency', icon: 'fas fa-list', route: 'vue/view-and-print-iso-currency' },
      { title: 'View & Print Foreign Currency', icon: 'fas fa-list-alt', route: 'vue/foreign-currency/view-print' },
      { title: 'View & Print Business Form', icon: 'fas fa-print', route: 'vue/business-form/view-print' }
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
          { title: 'Define Sales Configuration', icon: 'fas fa-sliders-h', route: 'vue/sales-configuration' }
        ]
      },
      {
        title: 'Standard Requirement',
        icon: 'fas fa-clipboard-check',
        children: [
          { title: 'Define Sales Team', icon: 'fas fa-users-cog', route: 'vue/sales-team' },
          { title: 'Define Salesperson', icon: 'fas fa-user-tie', route: 'vue/sales-person' },
          { title: 'Define Salesperson Team', icon: 'fas fa-user-friends', route: 'vue/sales-person-team' },
          { title: 'Define Industry', icon: 'fas fa-industry', route: 'vue/industry' },
          { title: 'Define Geo', icon: 'fas fa-globe', route: 'vue/geo' },
          { title: 'Define Product Group', icon: 'fas fa-boxes', route: 'vue/product-group' },
          { title: 'Define Product', icon: 'fas fa-box', route: 'vue/product' },
          { title: 'Define Product Design', icon: 'fas fa-drafting-compass', route: 'vue/product-design' },
          { title: 'Define Scoring Tool', icon: 'fas fa-cut', route: 'vue/scoring-tool' },
          { title: 'Define Paper Quality', icon: 'fas fa-scroll', route: 'vue/paper-quality' },
          { title: 'Obsolete/Unobsolete Paper Quality', icon: 'fas fa-ban', route: 'vue/paper-quality/status' },
          { title: 'Define Paper Flute', icon: 'fas fa-layer-group', route: 'vue/paper-flute' },
          { title: 'Define Paper Size', icon: 'fas fa-ruler-combined', route: 'vue/paper-size' },
          { title: 'Define Color Group', icon: 'fas fa-palette', route: 'vue/color-group' },
          { title: 'Define Color', icon: 'fas fa-fill-drip', route: 'vue/color' },
          { title: 'Define Finishing', icon: 'fas fa-paint-roller', route: 'vue/finishing' },
          // View & Print Section
          { title: 'View & Print Sales Team', icon: 'fas fa-print', route: 'vue/sales-team/view-print' },
          { title: 'View & Print Salesperson', icon: 'fas fa-print', route: 'vue/salesperson/view-print' },
          { title: 'View & Print Salesperson Team', icon: 'fas fa-print', route: 'vue/salesperson-team/view-print' },
          { title: 'View & Print Industry', icon: 'fas fa-print', route: 'vue/industry/view-print' },
          { title: 'View & Print Geo', icon: 'fas fa-print', route: 'vue/geo/view-print' },
          { title: 'View & Print Product Group', icon: 'fas fa-print', route: 'vue/product-group/view-print' },
          { title: 'View & Print Product', icon: 'fas fa-print', route: 'vue/product/view-print' },
          { title: 'View & Print Product Design', icon: 'fas fa-print', route: 'vue/product-design/view-print' },
          { title: 'View & Print Paper Quality', icon: 'fas fa-print', route: 'vue/paper-quality/view-print' },
          { title: 'View & Print Paper Flute', icon: 'fas fa-print', route: 'vue/paper-flute/view-print' },
          { title: 'View & Print Paper Size', icon: 'fas fa-print', route: 'vue/paper-size/view-print' },
          { title: 'View & Print Scoring Tool', icon: 'fas fa-print', route: 'vue/scoring-tool/view-print' },
          { title: 'View & Print Color Group', icon: 'fas fa-print', route: 'vue/color-group/view-print' },
          { title: 'View & Print Color', icon: 'fas fa-print', route: 'vue/color/view-print' },
          { title: 'View & Print Finishing', icon: 'fas fa-print', route: 'vue/finishing/view-print' }
        ]
      },
      {
        title: 'Customer Account',
        icon: 'fas fa-user-circle',
        children: [
          { title: 'Define Customer Group', icon: 'fas fa-users', route: 'vue/customer-group' },
          { title: 'Update Customer Account', icon: 'fas fa-user-edit', route: 'vue/update-customer-account' },
          { title: 'Obsolete/Reactive Customer A/C', icon: 'fas fa-user-clock', route: null },
          { title: 'Define Customer Alternate Address', icon: 'fas fa-map-marked-alt', route: 'vue/customer-alternate-address' },
          { title: 'Define Customer Sales Type', icon: 'fas fa-tags', route: null },
          { title: 'View & Print Customer Group', icon: 'fas fa-list', route: null },
          { title: 'View & Print Customer Account', icon: 'fas fa-list', route: null },
          { title: 'View & Print Customer Alternate Address', icon: 'fas fa-list', route: null },
          { title: 'View & Print Customer Sales Type', icon: 'fas fa-list', route: null },
          { title: 'View & Print Non-Active Customer', icon: 'fas fa-list', route: null }
        ]
      },
      {
        title: 'Master Card',
        icon: 'fas fa-id-card',
        children: [
          { title: 'Update MC', icon: 'fas fa-edit', route: null },
          { title: 'Approve MC', icon: 'fas fa-check', route: null },
          { title: 'Release Approved MC', icon: 'fas fa-unlock', route: null },
          { title: 'Obsolate & Reactive MC', icon: 'fas fa-ban', route: null },
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
  { title: 'Standard Costing', icon: 'fas fa-calculator', route: null },
  { title: 'Standard Formula', icon: 'fas fa-flask', route: null },
  { title: 'Sales Order', icon: 'fas fa-shopping-cart', route: null },
  { title: 'Sales Analysis', icon: 'fas fa-chart-bar', route: null },
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
</style> 