<template>
  <Modal :show="show" @close="close" :max-width="'7xl'">
    <div class="p-6">
      <!-- Header -->
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-bold text-gray-900">
          <i class="fas fa-chart-line mr-2 text-blue-500"></i>
          G/L Chart of Accounts Table by AC#
        </h3>
        <button @click="close" class="text-gray-400 hover:text-gray-500 transition-colors">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>

      <!-- Search and Filter Controls -->
      <div class="mb-6 flex flex-col md:flex-row gap-4">
        <div class="flex-1">
          <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
              <i class="fas fa-search"></i>
            </span>
            <input 
              type="text" 
              v-model="searchQuery" 
              placeholder="Search by AC#, Name, Dept, or Control AC..."
              class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
            >
          </div>
        </div>
        <div class="flex gap-2">
          <select 
            v-model="statusFilter" 
            class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
          >
            <option value="">All Status</option>
            <option value="A-Act">A-Act</option>
            <option value="I-Inactive">I-Inactive</option>
          </select>
          <select 
            v-model="typeFilter" 
            class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
          >
            <option value="">All Types</option>
            <option value="P-Posting Account">P-Posting Account</option>
            <option value="H-Header Account">H-Header Account</option>
          </select>
          <button 
            @click="refreshData" 
            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors"
          >
            <i class="fas fa-sync-alt mr-1"></i> Refresh
          </button>
        </div>
      </div>

      <!-- Account Table -->
      <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm">
        <div class="overflow-x-auto" style="max-height: 500px;">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-blue-50 sticky top-0">
              <tr>
                <th @click="sortBy('account_number')" 
                    class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors">
                  <div class="flex items-center">
                    AC# 
                    <i class="fas fa-sort ml-1 text-gray-400"></i>
                  </div>
                </th>
                <th @click="sortBy('dept')" 
                    class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors">
                  <div class="flex items-center">
                    Dept 
                    <i class="fas fa-sort ml-1 text-gray-400"></i>
                  </div>
                </th>
                <th @click="sortBy('sub_dept')" 
                    class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors">
                  <div class="flex items-center">
                    Sub-Dept 
                    <i class="fas fa-sort ml-1 text-gray-400"></i>
                  </div>
                </th>
                <th @click="sortBy('name')" 
                    class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors">
                  <div class="flex items-center">
                    AC Name 
                    <i class="fas fa-sort ml-1 text-gray-400"></i>
                  </div>
                </th>
                <th @click="sortBy('status')" 
                    class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors">
                  <div class="flex items-center">
                    Status 
                    <i class="fas fa-sort ml-1 text-gray-400"></i>
                  </div>
                </th>
                <th @click="sortBy('ac_type')" 
                    class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors">
                  <div class="flex items-center">
                    AC Type 
                    <i class="fas fa-sort ml-1 text-gray-400"></i>
                  </div>
                </th>
                <th @click="sortBy('control_ac')" 
                    class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors">
                  <div class="flex items-center">
                    Control AC 
                    <i class="fas fa-sort ml-1 text-gray-400"></i>
                  </div>
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="loading" class="animate-pulse">
                <td colspan="7" class="px-4 py-8 text-center text-sm text-gray-500">
                  <div class="flex justify-center items-center space-x-2">
                    <i class="fas fa-spinner fa-spin text-blue-500"></i>
                    <span>Loading accounts...</span>
                  </div>
                </td>
              </tr>
              <tr v-else-if="paginatedAccounts.length === 0" class="hover:bg-gray-50">
                <td colspan="7" class="px-4 py-8 text-center text-sm text-gray-500">
                  <div class="flex flex-col items-center space-y-2">
                    <i class="fas fa-search text-gray-300 text-3xl"></i>
                    <span>No accounts found. Try adjusting your search criteria.</span>
                  </div>
                </td>
              </tr>
              <tr v-for="account in paginatedAccounts" 
                  :key="account.id" 
                  @click="selectAccount(account)"
                  @dblclick="confirmSelection"
                  :class="{
                    'bg-blue-50 border-blue-200': selectedAccount && selectedAccount.id === account.id,
                    'hover:bg-gray-50': !(selectedAccount && selectedAccount.id === account.id)
                  }"
                  class="cursor-pointer transition-colors border-l-4 border-transparent">
                <td class="px-4 py-3 whitespace-nowrap text-sm font-mono text-blue-600 font-medium">
                  {{ account.account_number }}
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 text-center">
                  {{ account.dept }}
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 text-center">
                  {{ account.sub_dept }}
                </td>
                <td class="px-4 py-3 text-sm text-gray-900 font-medium">
                  {{ account.name }}
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-sm">
                  <span :class="{
                    'bg-green-100 text-green-800': account.status === 'A-Act',
                    'bg-red-100 text-red-800': account.status === 'I-Inactive',
                    'bg-gray-100 text-gray-800': !['A-Act', 'I-Inactive'].includes(account.status)
                  }" class="px-2 py-1 text-xs font-semibold rounded-full">
                    {{ account.status }}
                  </span>
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                  {{ account.ac_type }}
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                  {{ account.control_ac }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Pagination Controls -->
      <div class="mt-4 flex justify-between items-center text-sm text-gray-600">
        <div class="flex items-center space-x-2">
          <span>Showing {{ paginatedAccounts.length }} of {{ filteredAccounts.length }} accounts</span>
          <span v-if="selectedAccount" class="text-blue-600 font-medium">
            • Selected: {{ selectedAccount.account_number }}
          </span>
        </div>
        <div class="flex items-center space-x-2">
          <select v-model="itemsPerPage" 
                  class="border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            <option :value="10">10 per page</option>
            <option :value="25">25 per page</option>
            <option :value="50">50 per page</option>
            <option :value="100">100 per page</option>
          </select>
          <button :disabled="currentPage === 1" 
                  @click="currentPage--" 
                  class="px-2 py-1 border rounded hover:bg-gray-200 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
            <i class="fas fa-chevron-left"></i>
          </button>
          <span class="px-3 py-1 bg-gray-100 rounded">{{ currentPage }} / {{ totalPages }}</span>
          <button :disabled="currentPage === totalPages || totalPages === 0" 
                  @click="currentPage++" 
                  class="px-2 py-1 border rounded hover:bg-gray-200 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
            <i class="fas fa-chevron-right"></i>
          </button>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="mt-6 flex justify-end space-x-3 border-t border-gray-200 pt-4">
        <button @click="close"
                class="px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
          <i class="fas fa-times mr-1"></i> Cancel
        </button>
        <button @click="confirmSelection"
                class="px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                :disabled="!selectedAccount">
          <i class="fas fa-check mr-1"></i> Select Account
        </button>
      </div>

      <!-- Account Details Panel (if account is selected) -->
      <div v-if="selectedAccount" class="mt-6 bg-gray-50 rounded-lg p-4 border border-gray-200">
        <h4 class="text-sm font-semibold text-gray-800 mb-3 flex items-center">
          <i class="fas fa-info-circle mr-2 text-blue-500"></i>
          Selected Account Details
        </h4>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
          <div>
            <span class="text-gray-600">Account Number:</span>
            <div class="font-mono text-blue-600 font-medium">{{ selectedAccount.account_number }}</div>
          </div>
          <div>
            <span class="text-gray-600">Department:</span>
            <div class="font-medium">{{ selectedAccount.dept }}</div>
          </div>
          <div>
            <span class="text-gray-600">Sub-Department:</span>
            <div class="font-medium">{{ selectedAccount.sub_dept }}</div>
          </div>
          <div>
            <span class="text-gray-600">Status:</span>
            <div>
              <span :class="{
                'bg-green-100 text-green-800': selectedAccount.status === 'A-Act',
                'bg-red-100 text-red-800': selectedAccount.status === 'I-Inactive'
              }" class="px-2 py-1 text-xs font-semibold rounded-full">
                {{ selectedAccount.status }}
              </span>
            </div>
          </div>
          <div class="md:col-span-2">
            <span class="text-gray-600">Account Name:</span>
            <div class="font-medium">{{ selectedAccount.name }}</div>
          </div>
          <div>
            <span class="text-gray-600">Account Type:</span>
            <div class="font-medium">{{ selectedAccount.ac_type }}</div>
          </div>
          <div>
            <span class="text-gray-600">Control Account:</span>
            <div class="font-medium">{{ selectedAccount.control_ac }}</div>
          </div>
        </div>
      </div>
    </div>
  </Modal>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import Modal from '@/Components/Modal.vue';
import axios from 'axios';

const props = defineProps({
  show: {
    type: Boolean,
    required: true
  },
  accounts: {
    type: Array,
    default: () => []
  }
});

const emit = defineEmits(['close', 'select']);

// State
const accounts = ref(props.accounts || []);
const selectedAccount = ref(null);
const loading = ref(false);
const searchQuery = ref('');
const statusFilter = ref('');
const typeFilter = ref('');
const currentPage = ref(1);
const itemsPerPage = ref(25);
const sortOrder = ref({
  field: 'account_number',
  direction: 'asc'
});

// Computed properties
const filteredAccounts = computed(() => {
  let result = accounts.value;
  
  // Apply search filter
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    result = result.filter(account =>
      (account.account_number && account.account_number.toLowerCase().includes(query)) ||
      (account.name && account.name.toLowerCase().includes(query)) ||
      (account.dept && account.dept.toLowerCase().includes(query)) ||
      (account.sub_dept && account.sub_dept.toLowerCase().includes(query)) ||
      (account.control_ac && account.control_ac.toLowerCase().includes(query))
    );
  }
  
  // Apply status filter
  if (statusFilter.value) {
    result = result.filter(account => account.status === statusFilter.value);
  }
  
  // Apply type filter
  if (typeFilter.value) {
    result = result.filter(account => account.ac_type === typeFilter.value);
  }
  
  // Apply sorting
  result = [...result].sort((a, b) => {
    const field = sortOrder.value.field;
    const direction = sortOrder.value.direction === 'asc' ? 1 : -1;
    
    const valA = a[field] || '';
    const valB = b[field] || '';
    
    if (valA < valB) return -1 * direction;
    if (valA > valB) return 1 * direction;
    return 0;
  });
  
  return result;
});

const paginatedAccounts = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return filteredAccounts.value.slice(start, end);
});

const totalPages = computed(() => {
  const total = filteredAccounts.value.length;
  if (total === 0) return 1;
  return Math.ceil(total / itemsPerPage.value);
});

// Watch for changes
watch(filteredAccounts, () => {
  if (currentPage.value > totalPages.value) {
    currentPage.value = totalPages.value || 1;
  }
});

watch(itemsPerPage, () => {
  currentPage.value = 1;
});

watch(() => props.show, (newValue) => {
  if (newValue) {
    selectedAccount.value = null;
    if (accounts.value.length === 0) {
      fetchAccounts();
    }
  } else {
    // Reset filters when modal closes
    searchQuery.value = '';
    statusFilter.value = '';
    typeFilter.value = '';
    currentPage.value = 1;
  }
});

// Methods
const fetchAccounts = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/material-management/chart-of-accounts');
    accounts.value = response.data;
  } catch (error) {
    console.error('Error fetching chart of accounts:', error);
  } finally {
    loading.value = false;
  }
};

const refreshData = () => {
  selectedAccount.value = null;
  fetchAccounts();
};

const selectAccount = (account) => {
  selectedAccount.value = account;
};

const confirmSelection = () => {
  if (selectedAccount.value) {
    emit('select', selectedAccount.value);
    close();
  }
};

const close = () => {
  selectedAccount.value = null;
  emit('close');
};

const sortBy = (field) => {
  if (sortOrder.value.field === field) {
    sortOrder.value.direction = sortOrder.value.direction === 'asc' ? 'desc' : 'asc';
  } else {
    sortOrder.value.field = field;
    sortOrder.value.direction = 'asc';
  }
};

// Initialize accounts if provided as props
onMounted(() => {
  if (props.accounts && props.accounts.length > 0) {
    accounts.value = props.accounts;
  }
});
</script>

<style scoped>
/* Custom scrollbar for the table */
.overflow-x-auto::-webkit-scrollbar {
  height: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track {
  background: #f1f5f9;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

/* Hover effect for table rows */
tbody tr {
  transition: all 0.15s ease;
}

tbody tr:hover {
  transform: translateY(-1px);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Selected row styling */
tbody tr.bg-blue-50 {
  border-left-color: #3b82f6;
}
</style>
