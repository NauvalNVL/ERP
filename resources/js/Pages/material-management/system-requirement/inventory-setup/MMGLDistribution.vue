<template>
  <AppLayout :header="'Define MM GL Distribution'">
    <Head title="Define MM GL Distribution" />

    <!-- Toast Container -->
    <ToastContainer />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-book-open mr-3"></i> Define MM GL Distribution
      </h2>
      <p class="text-blue-100">Manage Material Management GL Distribution settings</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-md p-6 mb-6">
      <div class="flex flex-col md:flex-row gap-6">
        <!-- Main Content Area -->
        <div class="flex-1">
          <!-- Action Bar -->
          <div class="mb-6 flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
            <div class="flex-1 w-full">
              <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                  <i class="fas fa-search"></i>
                </span>
                <input type="text" v-model="searchQuery" placeholder="Search by code, name, or account..."
                  class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
              </div>
            </div>
            <div class="flex gap-2 w-full md:w-auto">
              <button @click="newGlDist" class="btn-primary flex-1 md:flex-none">
                <i class="fas fa-plus-circle mr-2"></i> New Distribution
              </button>
              <button @click="refreshData" class="btn-secondary flex-1 md:flex-none">
                <i class="fas fa-sync-alt mr-2"></i> Refresh
              </button>
              <button @click="printGlDist" class="btn-info flex-1 md:flex-none">
                <i class="fas fa-print mr-2"></i> Print
              </button>
            </div>
          </div>

          <!-- Table Section -->
          <div class="bg-white rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th @click="sortBy('gl_dist_code')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        GL Code <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('gl_dist_name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        GL Name <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('gl_account')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        GL Account <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('is_linked')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Link Status <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-if="loading" class="animate-pulse">
                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                      <div class="flex justify-center items-center space-x-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>Loading data...</span>
                      </div>
                    </td>
                  </tr>
                  <tr v-else-if="paginatedGlDist.length === 0" class="hover:bg-gray-50">
                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                      No GL distributions found. Try adjusting your search.
                    </td>
                  </tr>
                  <tr v-for="item in paginatedGlDist" :key="item.id" 
                      @click="selectGlDist(item)" 
                      :class="{'bg-blue-50': selectedGlDist && selectedGlDist.id === item.id}"
                      class="hover:bg-gray-50 cursor-pointer">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ item.gl_dist_code }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ item.gl_dist_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ item.gl_account }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                      <span :class="item.is_linked ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                        {{ item.is_linked ? 'Pass' : 'Fail' }}
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Pagination Controls -->
          <div class="mt-4 flex justify-between items-center text-sm text-gray-600">
            <div>
              <span>Showing {{ paginatedGlDist.length }} of {{ filteredGlDist.length }} distributions</span>
            </div>
            <div class="flex items-center space-x-2">
              <select v-model="itemsPerPage" class="border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option :value="10">10 per page</option>
                <option :value="20">20 per page</option>
                <option :value="50">50 per page</option>
                <option :value="100">100 per page</option>
              </select>
              <button :disabled="currentPage === 1" @click="currentPage--" class="px-2 py-1 border rounded hover:bg-gray-200 disabled:opacity-50">
                <i class="fas fa-chevron-left"></i>
              </button>
              <span class="px-4">{{ currentPage }} / {{ totalPages }}</span>
              <button :disabled="currentPage === totalPages || totalPages === 0" @click="currentPage++" class="px-2 py-1 border rounded hover:bg-gray-200 disabled:opacity-50">
                <i class="fas fa-chevron-right"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Side Panel with Details -->
        <div class="w-full md:w-96 bg-gray-50 rounded-lg p-4 border border-gray-200">
          <div v-if="selectedGlDist" class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
              <i class="fas fa-info-circle mr-2 text-blue-500"></i> GL Distribution Details
            </h3>
            <div class="space-y-3">
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">GL Code:</span>
                <span class="font-medium text-gray-900">{{ selectedGlDist.gl_dist_code }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">GL Name:</span>
                <span class="font-medium text-gray-900">{{ selectedGlDist.gl_dist_name }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">GL Account:</span>
                <span class="font-medium text-gray-900">{{ selectedGlDist.gl_account }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Account Name:</span>
                <span class="font-medium text-gray-900">{{ selectedGlDist.gl_account_name }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Link Status:</span>
                <span :class="selectedGlDist.is_linked ? 'text-green-600' : 'text-red-600'" class="font-medium">
                  {{ selectedGlDist.is_linked ? 'Pass' : 'Fail' }}
                </span>
              </div>
            </div>
            <div class="mt-6">
              <button @click="editGlDist(selectedGlDist)" class="w-full btn-blue">
                <i class="fas fa-edit mr-1"></i> Edit
              </button>
            </div>
          </div>
          <div v-else class="flex flex-col items-center justify-center h-64 text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
              <i class="fas fa-book-open text-blue-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No Distribution Selected</h3>
            <p class="text-gray-500 mb-4">Select a GL distribution from the table to view details</p>
            <button @click="newGlDist" class="btn-primary">
              <i class="fas fa-plus-circle mr-1"></i> Create New Distribution
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Form Modal -->
    <Modal :show="showFormModal" @close="closeFormModal">
      <div class="p-6">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-bold text-gray-900">
            {{ isEditing ? 'Edit' : 'New' }} GL Distribution
          </h3>
          <button @click="closeFormModal" class="text-gray-400 hover:text-gray-500">
            <i class="fas fa-times"></i>
          </button>
        </div>

        <form @submit.prevent="saveGlDist" class="space-y-6">
          <!-- GL Distribution Code -->
          <div>
            <label class="block text-sm font-medium text-gray-700">GL Distribution Code</label>
            <div class="mt-1 flex rounded-md shadow-sm">
              <input type="text" v-model="form.gl_dist_code" :disabled="isEditing"
                     class="flex-1 min-w-0 block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                     maxlength="10">
            </div>
            <p v-if="errors.gl_dist_code" class="mt-1 text-sm text-red-600">{{ errors.gl_dist_code }}</p>
          </div>

          <!-- GL Distribution Name -->
          <div>
            <label class="block text-sm font-medium text-gray-700">GL Distribution Name</label>
            <div class="mt-1">
              <input type="text" v-model="form.gl_dist_name"
                     class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                     maxlength="100">
            </div>
            <p v-if="errors.gl_dist_name" class="mt-1 text-sm text-red-600">{{ errors.gl_dist_name }}</p>
          </div>

          <!-- GL Account -->
          <div>
            <label class="block text-sm font-medium text-gray-700">GL Account</label>
            <div class="mt-1 flex rounded-md shadow-sm">
              <input type="text" v-model="form.gl_account"
                     class="flex-1 min-w-0 block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                     maxlength="20">
              <button type="button" @click="openGlAccountSelector"
                      class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-search"></i>
              </button>
            </div>
            <p v-if="errors.gl_account" class="mt-1 text-sm text-red-600">{{ errors.gl_account }}</p>
          </div>

          <!-- GL Account Name (Read-only) -->
          <div>
            <label class="block text-sm font-medium text-gray-700">GL Account Name</label>
            <div class="mt-1">
              <input type="text" v-model="form.gl_account_name" readonly
                     class="block w-full px-3 py-2 rounded-md border border-gray-300 bg-gray-50 sm:text-sm">
            </div>
          </div>

          <div class="flex justify-end space-x-3 pt-5">
            <button type="button" @click="closeFormModal"
                    class="px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
              Cancel
            </button>
            <button type="submit"
                    class="px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none"
                    :disabled="submitting">
              <i v-if="submitting" class="fas fa-spinner fa-spin mr-2"></i>
              {{ isEditing ? 'Update' : 'Create' }}
            </button>
          </div>
        </form>
      </div>
    </Modal>

    <!-- GL Account Selector Modal -->
    <Modal :show="showGlAccountModal" @close="closeGlAccountModal">
      <div class="p-6">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-bold text-gray-900">Select GL Account</h3>
          <button @click="closeGlAccountModal" class="text-gray-400 hover:text-gray-500">
            <i class="fas fa-times"></i>
          </button>
        </div>

        <div class="overflow-y-auto max-h-96">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account #</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="account in chartOfAccounts" :key="account.id"
                  @click="selectGlAccount(account)"
                  :class="{'bg-blue-50': selectedAccount && selectedAccount.id === account.id}"
                  class="hover:bg-gray-50 cursor-pointer">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ account.account_number }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ account.name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ account.status }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
          <button @click="closeGlAccountModal"
                  class="px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
            Cancel
          </button>
          <button @click="confirmGlAccountSelection"
                  class="px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none"
                  :disabled="!selectedAccount">
            Select
          </button>
        </div>
      </div>
    </Modal>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Modal from '@/Components/Modal.vue';
import ToastContainer from '@/Components/ToastContainer.vue';
import { useToast } from '@/Composables/useToast';
import axios from 'axios';

const props = defineProps({
  glDistributions: Array,
  chartOfAccounts: Array,
});

// State variables
const glDistList = ref(props.glDistributions || []);
const chartOfAccounts = ref(props.chartOfAccounts || []);
const selectedGlDist = ref(null);
const selectedAccount = ref(null);
const loading = ref(false);
const submitting = ref(false);
const searchQuery = ref('');
const showFormModal = ref(false);
const showGlAccountModal = ref(false);
const isEditing = ref(false);
const currentPage = ref(1);
const itemsPerPage = ref(10);
const sortOrder = ref({
  field: 'gl_dist_code',
  direction: 'asc'
});

// Initialize toast
const toast = useToast();

// Form data
const form = ref({
  gl_dist_code: '',
  gl_dist_name: '',
  gl_account: '',
  gl_account_name: '* N/F *',
  is_linked: false,
  is_active: true
});

// Error handling
const errors = ref({});

// Reset form
const resetForm = () => {
  form.value = {
    gl_dist_code: '',
    gl_dist_name: '',
    gl_account: '',
    gl_account_name: '* N/F *',
    is_linked: false,
    is_active: true
  };
  errors.value = {};
  isEditing.value = false;
};

// Computed properties
const filteredGlDist = computed(() => {
  let result = glDistList.value;
  
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    result = result.filter(item =>
      (item.gl_dist_code && item.gl_dist_code.toLowerCase().includes(query)) ||
      (item.gl_dist_name && item.gl_dist_name.toLowerCase().includes(query)) ||
      (item.gl_account && item.gl_account.toLowerCase().includes(query))
    );
  }
  
  result = [...result].sort((a, b) => {
    const field = sortOrder.value.field;
    const direction = sortOrder.value.direction === 'asc' ? 1 : -1;
    
    if (field === 'is_linked') {
      return direction * (a.is_linked === b.is_linked ? 0 : a.is_linked ? -1 : 1);
    }
    
    const valA = a[field];
    const valB = b[field];

    if ((valA || '') < (valB || '')) return -1 * direction;
    if ((valA || '') > (valB || '')) return 1 * direction;
    return 0;
  });

  return result;
});

const paginatedGlDist = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return filteredGlDist.value.slice(start, end);
});

const totalPages = computed(() => {
  const total = filteredGlDist.value.length;
  if (total === 0) return 1;
  return Math.ceil(total / itemsPerPage.value);
});

// Watch for changes
watch(filteredGlDist, () => {
  if (currentPage.value > totalPages.value) {
    currentPage.value = totalPages.value || 1;
  }
});

watch(itemsPerPage, () => {
  currentPage.value = 1;
});

// Methods
const fetchGlDistributions = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/material-management/gl-distributions/list');
    glDistList.value = response.data;
  } catch (error) {
    console.error('Error fetching GL distributions:', error);
    toast.error('Failed to load GL distributions');
  } finally {
    loading.value = false;
  }
};

const fetchChartOfAccounts = async () => {
  try {
    const response = await axios.get('/api/material-management/chart-of-accounts');
    chartOfAccounts.value = response.data;
  } catch (error) {
    console.error('Error fetching chart of accounts:', error);
    toast.error('Failed to load chart of accounts');
  }
};

const refreshData = () => {
  selectedGlDist.value = null;
  searchQuery.value = '';
  fetchGlDistributions();
};

const selectGlDist = (item) => {
  selectedGlDist.value = item;
};

const newGlDist = () => {
  resetForm();
  showFormModal.value = true;
};

const editGlDist = (item) => {
  isEditing.value = true;
  form.value = { ...item };
  showFormModal.value = true;
};

const closeFormModal = () => {
  showFormModal.value = false;
  resetForm();
};

const saveGlDist = async () => {
  submitting.value = true;
  errors.value = {};

  try {
    let response;
    
    if (isEditing.value) {
      response = await axios.put(`/api/material-management/gl-distributions/${form.value.id}`, form.value);
      toast.success('GL Distribution updated successfully');
      
      const index = glDistList.value.findIndex(d => d.id === form.value.id);
      if (index !== -1) {
        glDistList.value[index] = response.data;
        selectedGlDist.value = response.data;
      }
    } else {
      response = await axios.post('/api/material-management/gl-distributions', form.value);
      toast.success('GL Distribution created successfully');
      glDistList.value.push(response.data);
      selectedGlDist.value = response.data;
    }
    
    showFormModal.value = false;
    resetForm();
  } catch (error) {
    console.error('Error saving GL distribution:', error);
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors;
    }
    toast.error(error.response?.data?.message || 'Failed to save GL distribution');
  } finally {
    submitting.value = false;
  }
};

const openGlAccountSelector = () => {
  showGlAccountModal.value = true;
  selectedAccount.value = null;
};

const closeGlAccountModal = () => {
  showGlAccountModal.value = false;
  selectedAccount.value = null;
};

const selectGlAccount = (account) => {
  selectedAccount.value = account;
};

const confirmGlAccountSelection = () => {
  if (selectedAccount.value) {
    form.value.gl_account = selectedAccount.value.account_number;
    form.value.gl_account_name = selectedAccount.value.name;
    form.value.is_linked = true;
    closeGlAccountModal();
  }
};

const sortBy = (field) => {
  if (sortOrder.value.field === field) {
    sortOrder.value.direction = sortOrder.value.direction === 'asc' ? 'desc' : 'asc';
  } else {
    sortOrder.value.field = field;
    sortOrder.value.direction = 'asc';
  }
};

const printGlDist = () => {
  router.get('/material-management/system-requirement/inventory-setup/mm-gl-distribution/print');
};

// Initialize data
onMounted(async () => {
  await Promise.all([
    fetchGlDistributions(),
    fetchChartOfAccounts()
  ]);
});
</script>

<style scoped>
/* Button styles */
.btn-primary {
  @apply bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

.btn-secondary {
  @apply bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

.btn-info {
  @apply bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

.btn-danger {
  @apply bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

.btn-blue {
  @apply bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

/* Table hover animation */
tbody tr {
  transition: all 0.2s;
}

tbody tr:hover {
  transform: translateY(-2px);
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* Scrollbar styling */
.overflow-x-auto, .overflow-y-auto {
  scrollbar-width: thin;
  scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
}

.overflow-x-auto::-webkit-scrollbar, .overflow-y-auto::-webkit-scrollbar {
  height: 8px;
  width: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track, .overflow-y-auto::-webkit-scrollbar-track {
  background: transparent;
}

.overflow-x-auto::-webkit-scrollbar-thumb, .overflow-y-auto::-webkit-scrollbar-thumb {
  background-color: rgba(156, 163, 175, 0.5);
  border-radius: 20px;
}
</style>
