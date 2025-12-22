<template>
  <AppLayout :header="'View & Print Master Cards'">
    <Head title="View & Print Master Cards" />

    <!-- Main Container with vibrant gradient background -->
    <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
      <div class="max-w-7xl mx-auto">
        <!-- Header Section with animated elements -->
        <div class="bg-blue-600 text-white shadow-sm rounded-xl border border-blue-700 mb-4">
          <div class="px-4 py-4 sm:px-6">
            <!-- Decorative Elements -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
              <div class="flex items-start md:items-center space-x-4 mb-2 md:mb-0">
                <div class="bg-blue-500 p-3 rounded-lg flex items-center justify-center">
                  <i class="fas fa-print text-white text-2xl"></i>
                </div>
                <div>
                  <h1 class="text-2xl md:text-3xl font-bold text-white text-shadow">View & Print Master Cards</h1>
                  <p class="text-blue-100 max-w-2xl">Preview and print master card data</p>
                </div>
              </div>
              <div class="flex flex-wrap gap-3">
                <button @click="printTable" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center transition-all duration-200 shadow-sm">
                  <div class="bg-white bg-opacity-20 rounded-full p-1 mr-2">
                    <i class="fas fa-print text-white"></i>
                  </div>
                  <span>Print List</span>
                </button>
                <Link href="/sales-management/system-requirement/master-card/obsolete-reactive-mc" class="bg-white bg-opacity-10 hover:bg-opacity-20 text-white border border-white border-opacity-30 px-4 py-2 rounded-lg flex items-center transition-all duration-200">
                  <div class="bg-white bg-opacity-20 rounded-full p-1.5 mr-2">
                    <i class="fas fa-arrow-left text-white"></i>
                  </div>
                  <span>Back</span>
                </Link>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
          <div class="bg-slate-50 border-b border-gray-200 py-3 px-6">
            <div class="flex items-center">
              <div class="mr-3 bg-blue-500 bg-opacity-10 p-2 rounded-full">
                <i class="fas fa-filter text-blue-600 text-xl"></i>
              </div>
              <div>
                <h2 class="text-sm sm:text-base font-semibold text-slate-800">Search Parameters</h2>
                <p class="text-xs text-slate-500">Filter master cards by various criteria</p>
              </div>
            </div>
          </div>

          <div class="p-4 sm:p-6">
            <!-- Actions Bar -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <!-- Customer Filter -->
              <div class="space-y-2">
                <label class="flex items-center text-sm font-medium text-indigo-700 mb-2">
                  <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full mr-2 flex items-center justify-center shadow-sm">
                    <i class="fas fa-user text-white text-sm"></i>
                  </div>
                  Customer:
                </label>
                <div class="relative">
                  <div class="flex items-stretch h-12 bg-white rounded-lg border border-gray-300">
                    <input
                      v-model="customerQuery"
                      type="text"
                      placeholder="Type customer number or name"
                      class="flex-grow min-w-0 px-3 text-sm h-12 focus:ring-indigo-500 focus:border-indigo-500 border-0 rounded-l-lg"
                      @focus="onCustomerQueryFocus"
                      @input="onCustomerQueryInput"
                      @blur="onCustomerQueryBlur"
                      @keydown.enter.prevent="handleCustomerEnter"
                    />
                    <button
                      type="button"
                      @click="openCustomerModal"
                      class="w-12 h-12 flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white rounded-r-lg"
                    >
                      <i class="fas fa-table"></i>
                    </button>
                  </div>

                  <div
                    v-if="showCustomerSuggestions && customerSuggestions.length"
                    class="absolute left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-20 max-h-64 overflow-auto"
                  >
                    <button
                      v-for="account in customerSuggestions"
                      :key="account.customer_code"
                      type="button"
                      class="w-full text-left px-3 py-2 hover:bg-indigo-50"
                      @mousedown.prevent="selectCustomerSuggestion(account)"
                    >
                      <div class="text-sm text-slate-900">
                        <span class="font-semibold">{{ account.customer_code }}</span>
                        <span class="text-slate-500"> - {{ account.customer_name }}</span>
                      </div>
                    </button>
                  </div>
                </div>
                <div class="px-3 border border-gray-200 rounded-md bg-gray-50 text-sm text-slate-800 min-h-[48px] flex items-center">
                  {{ selectedCustomer?.name || '-' }}
                </div>
                <div v-if="!form.customer_code" class="text-xs text-slate-500">
                  Please select a customer first to display master cards.
                </div>
              </div>

              <!-- Status Filter -->
              <div class="space-y-2">
                <label class="flex items-center text-sm font-medium text-indigo-700 mb-2">
                  <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full mr-2 flex items-center justify-center shadow-sm">
                    <i class="fas fa-tag text-white text-sm"></i>
                  </div>
                  Status:
                </label>
                <div class="flex space-x-4">
                  <div class="flex items-center">
                    <input
                      type="radio"
                      id="status-all"
                      v-model="statusFilter"
                      value="all"
                      class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
                    />
                    <label for="status-all" class="ml-2 flex items-center">
                      <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        <i class="fas fa-list-ul mr-1"></i> All
                      </span>
                    </label>
                  </div>
                  <div class="flex items-center">
                    <input
                      type="radio"
                      id="status-active"
                      v-model="statusFilter"
                      value="active"
                      class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
                    />
                    <label for="status-active" class="ml-2">
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-1"></i> Active
                      </span>
                    </label>
                  </div>
                  <div class="flex items-center">
                    <input
                      type="radio"
                      id="status-obsolete"
                      v-model="statusFilter"
                      value="obsolete"
                      class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
                    />
                    <label for="status-obsolete" class="ml-2">
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        <i class="fas fa-ban mr-1"></i> Obsolete
                      </span>
                    </label>
                  </div>
                </div>
              </div>

              <!-- Action Buttons -->
              <div class="flex items-end justify-end gap-3">
                <button @click="resetFilters" class="px-6 h-12 border border-gray-300 rounded-lg bg-white hover:bg-gray-50 text-gray-700 transition-all duration-300 shadow-sm flex items-center">
                  <i class="fas fa-undo mr-2 text-gray-500"></i>
                  Reset Filters
                </button>
                <button
                  @click="fetchMasterCards()"
                  :disabled="!form.customer_code"
                  class="px-6 h-12 rounded-lg transition-all duration-300 shadow-md flex items-center"
                  :class="!form.customer_code
                    ? 'bg-gray-300 text-gray-600 cursor-not-allowed'
                    : 'bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white'"
                >
                  <i class="fas fa-sync mr-2"></i>
                  Refresh Data
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <div class="bg-blue-600 text-white py-4 px-6">
            <div class="flex items-center">
              <div class="mr-4 bg-white bg-opacity-20 p-2 rounded-full shadow-inner">
                <i class="fas fa-id-card text-2xl"></i>
              </div>
              <div>
                <h2 class="text-xl font-bold">Master Cards List</h2>
                <p class="text-sm opacity-80">View and manage master card data</p>
              </div>
            </div>
          </div>

          <div class="overflow-x-auto">
            <div id="printableTable" class="min-w-full bg-white">
              <!-- Table Content -->
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th @click="sortTable('mc_seq')" class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider cursor-pointer hover:bg-indigo-100 transition-colors">
                      <div class="flex items-center">
                        <span>MC Seq#</span>
                        <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortTable('comp')" class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider cursor-pointer hover:bg-indigo-100 transition-colors">
                      <div class="flex items-center">
                        <span>Comp</span>
                        <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortTable('mc_model')" class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider cursor-pointer hover:bg-indigo-100 transition-colors">
                      <div class="flex items-center">
                        <span>MC Model</span>
                        <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortTable('customer_name')" class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider cursor-pointer hover:bg-indigo-100 transition-colors">
                      <div class="flex items-center">
                        <span>Customer</span>
                        <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortTable('description')" class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider cursor-pointer hover:bg-indigo-100 transition-colors">
                      <div class="flex items-center">
                        <span>Product Design</span>
                        <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortTable('status')" class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider cursor-pointer hover:bg-indigo-100 transition-colors">
                      <div class="flex items-center">
                        <span>Status</span>
                        <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortTable('updated_at')" class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider cursor-pointer hover:bg-indigo-100 transition-colors">
                      <div class="flex items-center">
                        <span>Last Updated</span>
                        <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-if="loading" class="hover:bg-gray-50">
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                      <div class="flex justify-center">
                        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-purple-500"></div>
                      </div>
                      <p class="mt-2">Loading master card data...</p>
                    </td>
                  </tr>
                  <tr v-else-if="filteredMasterCards.length === 0" class="hover:bg-gray-50">
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                      <span v-if="!form.customer_code">Please select a customer first.</span>
                      <span v-else>No master cards found.</span>
                    </td>
                  </tr>
                  <tr v-for="(card, index) in filteredMasterCards" :key="card.id"
                    :class="{'bg-gray-50': index % 2 === 0}"
                    class="hover:bg-indigo-50 transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900">{{ card.mc_seq }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">{{ card.comp || '-' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">{{ card.mc_model }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">{{ card.customer_name }}</div>
                    </td>
                    <td class="px-6 py-4">
                      <div class="text-sm text-gray-900">{{ card.description || '-' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span
                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium"
                        :class="{
                          'bg-green-100 text-green-800': card.status === 'active',
                          'bg-red-100 text-red-800': card.status === 'obsolete',
                          'bg-gray-100 text-gray-800': card.status !== 'active' && card.status !== 'obsolete'
                        }">
                        <i :class="{
                          'fas fa-check-circle mr-1': card.status === 'active',
                          'fas fa-ban mr-1': card.status === 'obsolete',
                          'fas fa-question-circle mr-1': card.status !== 'active' && card.status !== 'obsolete'
                        }"></i>
                        {{ card.status === 'active' ? 'Active' : 'Obsolete' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900 flex items-center">
                        <i class="fas fa-clock text-indigo-400 mr-2"></i>
                        {{ formatDate(card.updated_at) }}
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Table Footer -->
          <div class="bg-gray-50 px-6 py-3 border-t border-gray-100">
            <div class="flex flex-wrap items-center justify-between gap-3">
              <div class="text-sm text-indigo-700 flex items-center">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 mr-2">
                  <i class="fas fa-list mr-1"></i>
                  {{ filteredMasterCards.length }}
                </span>
                Master Cards Found
              </div>
              <div v-if="form.customer_code || statusFilter !== 'all'" class="text-sm text-indigo-700">
                Filtered from <span class="font-bold">{{ masterCards.length }}</span> total records
              </div>
              <div class="text-xs text-indigo-400 flex items-center">
                <i class="fas fa-calendar-day mr-1"></i>
                Generated: {{ currentDate }}
              </div>
            </div>
          </div>
        </div>

        <!-- Instructions and Tips -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6 mt-6">
          <div class="bg-blue-600 text-white py-3 px-6">
            <div class="flex items-center">
              <div class="mr-3 bg-white bg-opacity-20 p-2 rounded-full shadow-inner">
                <i class="fas fa-lightbulb text-xl"></i>
              </div>
              <h2 class="text-xl font-bold">Usage Instructions</h2>
            </div>
          </div>
          <div class="p-6">
            <ul class="space-y-3">
              <li class="flex items-start">
                <div class="w-6 h-6 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                  <i class="fas fa-check text-white text-xs"></i>
                </div>
                <span class="text-gray-700">Select a customer first (required) to load and display master card data</span>
              </li>
              <li class="flex items-start">
                <div class="w-6 h-6 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                  <i class="fas fa-check text-white text-xs"></i>
                </div>
                <span class="text-gray-700">Filter by status (Active, Obsolete, or All) using the status options</span>
              </li>
              <li class="flex items-start">
                <div class="w-6 h-6 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                  <i class="fas fa-check text-white text-xs"></i>
                </div>
                <span class="text-gray-700">Click any column header to sort the table by that column</span>
              </li>
              <li class="flex items-start">
                <div class="w-6 h-6 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                  <i class="fas fa-check text-white text-xs"></i>
                </div>
                <span class="text-gray-700">Click the "Print List" button to download the current filtered/sorted table view as PDF</span>
              </li>
              <li class="flex items-start">
                <div class="w-6 h-6 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                  <i class="fas fa-check text-white text-xs"></i>
                </div>
                <span class="text-gray-700">Use landscape orientation for optimal PDF/print layout</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <CustomerAccountModal
      v-if="showCustomerModal"
      :show="showCustomerModal"
      :customer-accounts="customers"
      :initial-search="customerSearch"
      @close="showCustomerModal = false"
      @select="handleCustomerSelect"
    />
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import CustomerAccountModal from '@/Components/customer-account-modal.vue';
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';

// Data
const masterCards = ref([]);
const loading = ref(true);
const statusFilter = ref('all'); // 'all', 'active', 'obsolete'
const sortColumn = ref('mc_seq');
const sortDirection = ref('asc');
const currentDate = new Date().toLocaleString();

const form = ref({
  customer_code: '',
});

const customerQuery = ref('');
const showCustomerSuggestions = ref(false);
const loadingCustomers = ref(false);

const selectedCustomer = ref(null);
const showCustomerModal = ref(false);
const customers = ref([]);
const customerSearch = ref('');

const openCustomerModal = async () => {
  customerSearch.value = String(customerQuery.value ?? '').trim();
  if (customers.value.length === 0) {
    await loadCustomers();
  }
  showCustomerModal.value = true;
};

const loadCustomers = async () => {
  try {
    loadingCustomers.value = true;
    const response = await fetch('/api/customers-with-status?status=active', {
      headers: {
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      },
    });

    if (!response.ok) {
      throw new Error('Failed to load customers');
    }

    const data = await response.json();

    if (Array.isArray(data)) {
      customers.value = data;
    } else if (data?.data && Array.isArray(data.data)) {
      customers.value = data.data;
    } else {
      customers.value = [];
    }
  } catch (error) {
    console.error('Error loading customers:', error);
    customers.value = [];
  } finally {
    loadingCustomers.value = false;
  }
};

const ensureCustomersLoaded = async () => {
  if (customers.value.length > 0 || loadingCustomers.value) {
    return;
  }
  await loadCustomers();
};

const normalizeCustomerAccount = (account) => {
  return {
    customer_code: account?.customer_code ?? account?.code ?? '',
    customer_name: account?.customer_name ?? account?.name ?? '',
  };
};

const customerSuggestions = computed(() => {
  const q = String(customerQuery.value ?? '').trim().toLowerCase();
  if (!q) {
    return [];
  }

  return (customers.value || [])
    .map((c) => normalizeCustomerAccount(c))
    .filter((c) => c.customer_code && (String(c.customer_code).toLowerCase().includes(q) || String(c.customer_name).toLowerCase().includes(q)))
    .slice(0, 20);
});

const selectCustomerSuggestion = (account) => {
  const normalized = normalizeCustomerAccount(account);
  if (!normalized.customer_code) {
    return;
  }

  form.value.customer_code = normalized.customer_code;
  customerQuery.value = normalized.customer_code;
  selectedCustomer.value = {
    code: normalized.customer_code,
    name: normalized.customer_name,
  };
  showCustomerSuggestions.value = false;
  fetchMasterCards();
};

const onCustomerQueryFocus = async () => {
  await ensureCustomersLoaded();
  if (String(customerQuery.value ?? '').trim()) {
    showCustomerSuggestions.value = true;
  }
};

const onCustomerQueryInput = async () => {
  await ensureCustomersLoaded();
  showCustomerSuggestions.value = true;
};

const onCustomerQueryBlur = () => {
  setTimeout(() => {
    showCustomerSuggestions.value = false;
  }, 120);
};

const handleCustomerEnter = async () => {
  const q = String(customerQuery.value ?? '').trim();
  if (!q) {
    return;
  }

  await ensureCustomersLoaded();

  const qLower = q.toLowerCase();
  const normalizedCustomers = (customers.value || []).map((c) => normalizeCustomerAccount(c));
  const exactCode = normalizedCustomers.find((c) => String(c.customer_code ?? '').toLowerCase() === qLower);
  if (exactCode) {
    selectCustomerSuggestion(exactCode);
    return;
  }

  if (customerSuggestions.value.length === 1) {
    selectCustomerSuggestion(customerSuggestions.value[0]);
    return;
  }

  customerSearch.value = q;
  await openCustomerModal();
};

const handleCustomerSelect = (account) => {
  customerQuery.value = account.customer_code;
  form.value.customer_code = account.customer_code;
  selectedCustomer.value = {
    code: account.customer_code,
    name: account.customer_name,
  };
  showCustomerModal.value = false;
  fetchMasterCards();
};

const resetFilters = () => {
  form.value.customer_code = '';
  customerQuery.value = '';
  selectedCustomer.value = null;
  statusFilter.value = 'all';
  masterCards.value = [];
  loading.value = false;
};

watch(customerQuery, (val) => {
  const q = String(val ?? '').trim();
  if (!q) {
    form.value.customer_code = '';
    selectedCustomer.value = null;
    masterCards.value = [];
    return;
  }

  if (selectedCustomer.value?.code && String(selectedCustomer.value.code).toLowerCase() !== q.toLowerCase()) {
    form.value.customer_code = '';
    selectedCustomer.value = null;
    masterCards.value = [];
  }
});

// Fetch master cards from API
const fetchMasterCards = async () => {
  loading.value = true;
  try {
    const code = String(form.value.customer_code ?? '').trim();
    if (!code) {
      masterCards.value = [];
      return;
    }

    const response = await fetch(`/api/obsolete-reactive-mc/by-customer/${encodeURIComponent(code)}`, {
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    });

    if (!response.ok) {
      throw new Error('Failed to fetch master cards');
    }

    const data = await response.json();

    if (Array.isArray(data)) {
      masterCards.value = data.map((item) => ({
        id: `${item.MCS_Num}-${item.COMP || ''}`,
        mc_seq: item.MCS_Num,
        comp: item.COMP || '',
        mc_model: item.MODEL,
        customer_code: item.AC_NUM || '',
        customer_name: item.AC_NAME || item.AC_NUM,
        description: item.P_DESIGN || '',
        status: (item.STS || '').toLowerCase(),
        // Use last status-change time from MC_UPDATE_LOG if available
        updated_at: item.last_updated_at || item.updated_at || null,
      }));
    } else {
      masterCards.value = [];
      console.error('Unexpected master card data format:', data);
    }
  } catch (error) {
    console.error('Error fetching master cards:', error);
  } finally {
    loading.value = false;
  }
};

// Format date
const formatDate = (dateString) => {
  if (!dateString) return '-';
  const date = new Date(dateString);
  return date.toLocaleString();
};

// Sort function
const sortTable = (column) => {
  if (sortColumn.value === column) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortColumn.value = column;
    sortDirection.value = 'asc';
  }
};

// Filtered and sorted master cards
const filteredMasterCards = computed(() => {
  let filtered = [...masterCards.value];

  if (form.value.customer_code) {
    const code = String(form.value.customer_code ?? '').toLowerCase();
    filtered = filtered.filter((card) =>
      String(card.customer_code ?? '').toLowerCase().includes(code)
    );
  }

  // Apply status filter
  if (statusFilter.value !== 'all') {
    filtered = filtered.filter(card => card.status === statusFilter.value);
  }

  // Apply sorting
  filtered.sort((a, b) => {
    let valueA = a[sortColumn.value];
    let valueB = b[sortColumn.value];

    // Handle null values
    if (valueA === null || valueA === undefined) valueA = '';
    if (valueB === null || valueB === undefined) valueB = '';

    // Case insensitive string comparison
    if (typeof valueA === 'string') valueA = valueA.toLowerCase();
    if (typeof valueB === 'string') valueB = valueB.toLowerCase();

    if (valueA < valueB) return sortDirection.value === 'asc' ? -1 : 1;
    if (valueA > valueB) return sortDirection.value === 'asc' ? 1 : -1;
    return 0;
  });

  return filtered;
});

// Print table function
const printTable = () => {
  const doc = new jsPDF('l', 'mm', 'a4');
  const marginX = 10;
  const marginY = 14;

  const title = 'Master Cards List';
  const printedAt = new Date().toLocaleString();

  doc.setFontSize(14);
  doc.text(title, 148.5, marginY, { align: 'center' });
  doc.setFontSize(9);
  doc.text(`Printed on: ${printedAt}`, 287, marginY, { align: 'right' });

  const rows = (filteredMasterCards.value || []).map((card) => [
    card.mc_seq ?? '',
    card.comp ?? '',
    card.mc_model ?? '',
    card.customer_name ?? '',
    card.description ?? '',
    card.status ?? '',
    formatDate(card.updated_at),
  ]);

  autoTable(doc, {
    startY: marginY + 6,
    head: [[
      'MC Seq#',
      'Comp',
      'MC Model',
      'Customer',
      'Product Design',
      'Status',
      'Last Updated',
    ]],
    body: rows,
    styles: { fontSize: 8 },
    headStyles: {
      fillColor: [37, 99, 235],
      textColor: 255,
      halign: 'left',
    },
    alternateRowStyles: { fillColor: [243, 244, 246] },
    margin: { left: marginX, right: marginX },
  });

  const safeDate = new Date()
    .toISOString()
    .slice(0, 19)
    .replace(/[:T]/g, '-');
  doc.save(`master-cards-${safeDate}.pdf`);
};

// Fetch data on component mount
onMounted(() => {
  loading.value = false;
  masterCards.value = [];
});
</script>

<style scoped>
@media print {
  body * {
    visibility: hidden;
  }

  #printableTable, #printableTable * {
    visibility: visible;
  }

  #printableTable {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
  }

  .bg-gradient-to-r {
    background: white !important;
    color: black !important;
  }

  .text-white {
    color: black !important;
  }
}

/* Text shadow for headings */
.text-shadow {
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Transition effects */
.transition-all {
  transition: all 0.3s ease;
}

/* Custom animation for slow ping effect */
@keyframes ping-slow {
  0% {
    transform: scale(1);
    opacity: 0.5;
  }
  50% {
    transform: scale(1.8);
    opacity: 0.15;
  }
  100% {
    transform: scale(1);
    opacity: 0.5;
  }
}

.animate-ping-slow {
  animation: ping-slow 3s ease-in-out infinite;
}

.animation-delay-500 {
  animation-delay: 1.5s;
}
</style>
