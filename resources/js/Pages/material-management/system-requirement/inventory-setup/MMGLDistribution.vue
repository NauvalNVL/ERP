<template>
  <AppLayout :header="'Define MM GL Distribution'">
    <Head title="Define MM GL Distribution" />

    <div class="bg-white rounded-lg shadow-md p-6">
      <!-- Main Form Section -->
      <div class="mb-6 border border-gray-300 rounded-lg overflow-hidden">
        <div class="bg-blue-100 border-b border-blue-300 p-3">
          <h3 class="text-lg font-medium text-blue-900">Define MM GL Distribution</h3>
        </div>
        
        <div class="p-4 bg-white">
          <div class="grid grid-cols-3 gap-4 mb-4">
            <!-- Left Column - Input Fields -->
            <div class="col-span-2">
              <div class="grid grid-cols-1 gap-y-4">
                <!-- GL DIST# -->
                <div class="grid grid-cols-3 items-center">
                  <label class="text-sm font-medium text-gray-700">GL DIST#:</label>
                  <div class="col-span-2 flex space-x-2">
                    <input
                      v-model="form.gl_dist_code"
                      type="text"
                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-1 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
                      :disabled="isEditing"
                      maxlength="10"
                    />
                    <button 
                      @click="openGlDistSelector" 
                      class="px-2 bg-gray-100 border border-gray-300 rounded-md"
                    >
                      <i class="fas fa-search text-blue-600"></i>
                    </button>
                  </div>
                </div>

                <!-- GL DIST Name -->
                <div class="grid grid-cols-3 items-center">
                  <label class="text-sm font-medium text-gray-700">GL DIST Name:</label>
                  <div class="col-span-2">
                    <input
                      v-model="form.gl_dist_name"
                      type="text"
                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-1 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
                      maxlength="100"
                    />
                  </div>
                </div>

                <!-- GL Account# -->
                <div class="grid grid-cols-3 items-center">
                  <label class="text-sm font-medium text-gray-700">GL Account#:</label>
                  <div class="col-span-2 flex space-x-2">
                    <input
                      v-model="form.gl_account_segment1"
                      type="text"
                      class="mt-1 w-24 border border-gray-300 rounded-md shadow-sm py-1 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
                      maxlength="6"
                    />
                    <input
                      v-model="form.gl_account_segment2"
                      type="text"
                      class="mt-1 w-16 border border-gray-300 rounded-md shadow-sm py-1 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
                      maxlength="2"
                    />
                    <input
                      v-model="form.gl_account_segment3"
                      type="text"
                      class="mt-1 w-16 border border-gray-300 rounded-md shadow-sm py-1 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
                      maxlength="2"
                    />
                    <button
                      @click="openGlAccountSelector"
                      class="px-2 bg-gray-100 border border-gray-300 rounded-md"
                    >
                      <i class="fas fa-search text-blue-600"></i>
                    </button>
                  </div>
                </div>

                <!-- GL Account Name -->
                <div class="grid grid-cols-3 items-center">
                  <label class="text-sm font-medium text-gray-700">GL Account Name:</label>
                  <div class="col-span-2">
                    <input
                      v-model="form.gl_account_name"
                      type="text"
                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-1 px-3 bg-gray-100 text-sm"
                      readonly
                    />
                  </div>
                </div>
              </div>
            </div>

            <!-- Right Column - Record Navigation -->
            <div class="flex flex-col items-center justify-center border-l border-gray-300 pl-4">
              <div class="text-center mb-4">
                <button 
                  class="w-full bg-blue-50 border border-blue-300 rounded-md py-1 px-4 text-blue-800 font-medium hover:bg-blue-100"
                  @click="currentMode === 'select' ? selectCurrentRecord() : previewCurrentRecord()"
                >
                  Record: {{ currentMode === 'select' ? 'Select' : 'Review' }}
                </button>
              </div>
              
              <div class="grid grid-cols-2 gap-2 w-full">
                <button 
                  @click="saveGlDistribution"
                  class="bg-blue-600 text-white rounded-md py-1 px-3 hover:bg-blue-700 text-sm"
                  :disabled="submitting"
                >
                  <i v-if="submitting" class="fas fa-spinner fa-spin mr-1"></i>
                  <i v-else class="fas fa-save mr-1"></i> Save
                </button>
                
                <button 
                  @click="resetForm"
                  class="bg-gray-300 text-gray-700 rounded-md py-1 px-3 hover:bg-gray-400 text-sm"
                >
                  <i class="fas fa-times mr-1"></i> Cancel
                </button>
                
                <button 
                  @click="deleteGlDistribution"
                  class="bg-red-600 text-white rounded-md py-1 px-3 hover:bg-red-700 text-sm"
                  :disabled="!isEditing"
                >
                  <i class="fas fa-trash-alt mr-1"></i> Delete
                </button>
                
                <Link
                  :href="'/mm-gl-distribution/view-print'"
                  class="bg-green-600 text-white rounded-md py-1 px-3 hover:bg-green-700 text-sm text-center"
                >
                  <i class="fas fa-print mr-1"></i> Print
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- GL Distribution Table -->
      <div v-if="showGlDistTable" class="border border-gray-300 rounded-lg overflow-hidden bg-white shadow-sm">
        <div class="bg-blue-50 border-b border-blue-300 p-2 flex justify-between items-center">
          <h3 class="text-md font-medium text-blue-900">Material G/L Distribution Table</h3>
          <div class="flex items-center">
            <input
              v-model="search"
              type="text"
              placeholder="Search..."
              class="border border-gray-300 rounded-md shadow-sm py-1 px-3 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
        </div>
        
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th 
                  @click="sortBy('gl_dist_code')" 
                  class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                >
                  GL DIST#
                  <span v-if="sortColumn === 'gl_dist_code'" class="ml-1">
                    <i :class="sortDirection === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down'"></i>
                  </span>
                </th>
                <th 
                  @click="sortBy('gl_dist_name')" 
                  class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                >
                  GL Dist Name
                  <span v-if="sortColumn === 'gl_dist_name'" class="ml-1">
                    <i :class="sortDirection === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down'"></i>
                  </span>
                </th>
                <th 
                  @click="sortBy('gl_account')" 
                  class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                >
                  GL Account#
                  <span v-if="sortColumn === 'gl_account'" class="ml-1">
                    <i :class="sortDirection === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down'"></i>
                  </span>
                </th>
                <th 
                  @click="sortBy('is_linked')" 
                  class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                >
                  Link to GL
                  <span v-if="sortColumn === 'is_linked'" class="ml-1">
                    <i :class="sortDirection === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down'"></i>
                  </span>
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="loading" class="animate-pulse">
                <td colspan="4" class="px-4 py-2 text-center">
                  <div class="flex justify-center">
                    <i class="fas fa-circle-notch fa-spin text-blue-500"></i>
                    <span class="ml-2">Loading data...</span>
                  </div>
                </td>
              </tr>
              <tr v-else-if="filteredGlDistributions.length === 0" class="hover:bg-gray-50">
                <td colspan="4" class="px-4 py-2 text-center text-gray-500">
                  No data available
                </td>
              </tr>
              <tr
                v-for="item in filteredGlDistributions"
                :key="item.id"
                class="hover:bg-blue-50 transition-colors cursor-pointer"
                @click="selectGlDistribution(item)"
                :class="{ 'bg-blue-50': selectedGlDistribution?.id === item.id }"
              >
                <td class="px-4 py-2 whitespace-nowrap font-medium">{{ item.gl_dist_code }}</td>
                <td class="px-4 py-2 whitespace-nowrap">{{ item.gl_dist_name }}</td>
                <td class="px-4 py-2 whitespace-nowrap">{{ item.gl_account }}</td>
                <td class="px-4 py-2 whitespace-nowrap">
                  <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                    :class="item.is_linked ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                  >
                    {{ item.is_linked ? 'Pass' : 'Fail' }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        
        <div class="bg-gray-100 px-4 py-2 flex justify-center space-x-4 border-t border-gray-300">
          <button 
            @click="selectCurrentRecord"
            class="bg-blue-600 text-white py-1 px-4 rounded hover:bg-blue-700 text-sm"
          >
            Select
          </button>
          <button 
            @click="closeGlDistTable"
            class="bg-gray-300 text-gray-700 py-1 px-4 rounded hover:bg-gray-400 text-sm"
          >
            Exit
          </button>
        </div>
      </div>

      <!-- GL Account Selector Modal -->
      <Modal :show="showGlAccountModal" @close="closeGlAccountModal">
        <div class="p-4">
          <h3 class="text-lg font-bold text-gray-800 mb-4">
            G/L Chart of Accounts Table by AC#
          </h3>

          <div class="overflow-y-auto max-h-96">
            <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">AC#</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dept</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sub-Dept</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">AC Name</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Control AC</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr 
                  v-for="account in glAccounts" 
                  :key="account.id"
                  @click="selectGlAccount(account)"
                  class="hover:bg-blue-50 cursor-pointer"
                >
                  <td class="px-4 py-2 whitespace-nowrap font-mono text-xs">{{ account.account_number }}</td>
                  <td class="px-4 py-2 whitespace-nowrap text-xs">{{ account.dept }}</td>
                  <td class="px-4 py-2 whitespace-nowrap text-xs">{{ account.sub_dept }}</td>
                  <td class="px-4 py-2 whitespace-nowrap text-xs">{{ account.name }}</td>
                  <td class="px-4 py-2 whitespace-nowrap text-xs">{{ account.status }}</td>
                  <td class="px-4 py-2 whitespace-nowrap text-xs">{{ account.control_ac }}</td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="mt-4 flex justify-center space-x-4">
            <button
              @click="selectCurrentGlAccount"
              class="bg-blue-600 py-1 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700 focus:outline-none"
            >
              Select
            </button>
            <button
              @click="closeGlAccountModal"
              class="bg-gray-300 py-1 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-400 focus:outline-none"
            >
              Exit
            </button>
          </div>
        </div>
      </Modal>

      <!-- Delete Confirmation Modal -->
      <Modal :show="showDeleteModal" @close="closeDeleteModal">
        <div class="p-6">
          <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100 mx-auto mb-4">
            <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
          </div>
          
          <h3 class="text-center text-lg font-bold text-gray-900 mb-2">Confirm Deletion</h3>
          
          <p class="text-center text-gray-600 mb-6">
            Are you sure you want to delete this GL distribution?
            <br>
            <span class="font-medium">{{ form.gl_dist_code }} - {{ form.gl_dist_name }}</span>
          </p>

          <div class="flex justify-center space-x-3">
            <button
              @click="closeDeleteModal"
              class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none"
            >
              Cancel
            </button>
            <button
              @click="confirmDelete"
              class="bg-red-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-red-700 focus:outline-none"
              :disabled="deleting"
            >
              <i v-if="deleting" class="fas fa-spinner fa-spin mr-2"></i>
              Delete
            </button>
          </div>
        </div>
      </Modal>
      
      <!-- Bottom navigational links -->
      <div class="mt-4">
        <div class="flex items-center space-x-2 text-blue-600">
          <i class="fas fa-unlock text-green-500"></i>
          <Link :href="'/mm-sku/unlock-utility'" class="text-green-500 hover:underline">Unlock SKU Utility</Link>
        </div>
        <div class="flex items-center space-x-2 text-blue-600">
          <i class="fas fa-print text-green-500"></i>
          <Link :href="'/mm-category/view-print'" class="text-green-500 hover:underline">View & Print Category</Link>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Modal from '@/Components/Modal.vue';
import { useToast } from '@/Composables/useToast';
import axios from 'axios';

const props = defineProps({
  glDistributions: Array,
  glAccounts: Array,
});

// Toast notifications
const toast = useToast();

// Table columns
const columns = [
  { key: 'gl_dist_code', label: 'GL Code' },
  { key: 'gl_dist_name', label: 'GL Name' },
  { key: 'gl_account', label: 'GL Account' },
  { key: 'is_linked', label: 'Link Status' },
];

// State variables
const glDistributionsList = ref(props.glDistributions || []);
const search = ref('');
const sortColumn = ref('gl_dist_code');
const sortDirection = ref('asc');
const selectedGlDistribution = ref(null);
const currentRecordIndex = ref(0);
const showGlAccountModal = ref(false);
const showDeleteModal = ref(false);
const showGlDistTable = ref(false);
const isEditing = ref(false);
const submitting = ref(false);
const deleting = ref(false);
const loading = ref(false);
const currentMode = ref('review'); // 'review' or 'select'
const selectedGlAccount = ref(null);

const form = useForm({
  id: null,
  gl_dist_code: '',
  gl_dist_name: '',
  gl_account: '',
  gl_account_segment1: '',
  gl_account_segment2: '',
  gl_account_segment3: '',
  gl_account_name: '* N/F *',
  is_linked: false,
});

// Sample data for GL accounts
const glAccounts = ref(props.glAccounts || []);

// Navigation computed properties
const hasPrevious = computed(() => currentRecordIndex.value > 0);
const hasNext = computed(() => currentRecordIndex.value < filteredGlDistributions.value.length - 1);

// Watch for changes to account segments and update the full account
watch([() => form.gl_account_segment1, () => form.gl_account_segment2, () => form.gl_account_segment3], 
  ([segment1, segment2, segment3]) => {
    if (segment1 && segment2 && segment3) {
      form.gl_account = `${segment1}-${segment2}-${segment3}`;
      // Attempt to find a matching account name
      const matchingAccount = glAccounts.value.find(acc => acc.account_number === form.gl_account);
      if (matchingAccount) {
        form.gl_account_name = matchingAccount.name;
        form.is_linked = true;
      } else {
        form.gl_account_name = '* N/F *';
        form.is_linked = false;
      }
    }
  }
);

// Get all GL distributions
const fetchGlDistributions = async () => {
  loading.value = true;
  try {
    // Use direct URL instead of route helper
    const response = await axios.get('/mm-gl-distribution/list');
    glDistributionsList.value = response.data.data;
  } catch (error) {
    console.error('Error fetching GL distributions:', error);
    toast.error('Error loading GL distributions');
  } finally {
    loading.value = false;
  }
};

// Sort the data
const sortBy = (column) => {
  if (sortColumn.value === column) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortColumn.value = column;
    sortDirection.value = 'asc';
  }
};

// Filter and sort the data
const filteredGlDistributions = computed(() => {
  let filtered = [...glDistributionsList.value];
  
  if (search.value) {
    const searchLower = search.value.toLowerCase();
    filtered = filtered.filter(item => 
      item.gl_dist_code.toLowerCase().includes(searchLower) ||
      item.gl_dist_name.toLowerCase().includes(searchLower) ||
      item.gl_account.toLowerCase().includes(searchLower)
    );
  }
  
  filtered.sort((a, b) => {
    const modifier = sortDirection.value === 'asc' ? 1 : -1;
    if (sortColumn.value === 'is_linked') {
      return modifier * (a.is_linked === b.is_linked ? 0 : a.is_linked ? 1 : -1);
    }
    return modifier * (a[sortColumn.value] < b[sortColumn.value] ? -1 : 1);
  });
  
  return filtered;
});

// Open GL Distribution selector
const openGlDistSelector = () => {
  showGlDistTable.value = true;
  currentMode.value = 'select';
  fetchGlDistributions();
};

// Close GL Distribution table
const closeGlDistTable = () => {
  showGlDistTable.value = false;
};

// Handle selection of a GL distribution
const selectGlDistribution = (item) => {
  selectedGlDistribution.value = item;
  currentRecordIndex.value = filteredGlDistributions.value.findIndex(dist => dist.id === item.id);
  
  if (currentMode.value === 'select') {
    // Just highlight the selection but don't close the modal yet
    return;
  }
  
  setCurrentRecordToForm(item);
};

const setCurrentRecordToForm = (item) => {
    isEditing.value = true;
    const segments = item.gl_account.split('-');
    form.id = item.id;
    form.gl_dist_code = item.gl_dist_code;
    form.gl_dist_name = item.gl_dist_name;
    form.gl_account = item.gl_account;
    form.gl_account_segment1 = segments[0] || '';
    form.gl_account_segment2 = segments[1] || '';
    form.gl_account_segment3 = segments[2] || '';
    form.gl_account_name = item.gl_account_name || '* N/F *';
    form.is_linked = item.is_linked;
}

// Select current record from GL Distribution table
const selectCurrentRecord = () => {
  if (selectedGlDistribution.value) {
    setCurrentRecordToForm(selectedGlDistribution.value);
    closeGlDistTable();
  }
};

// Preview current record
const previewCurrentRecord = () => {
  showGlDistTable.value = true;
  currentMode.value = 'review';
};

// Reset form
const resetForm = () => {
  isEditing.value = false;
  selectedGlDistribution.value = null;
  form.reset();
  form.gl_account_name = '* N/F *';
  form.is_linked = false;
};

// Save GL Distribution
const saveGlDistribution = () => {
  // Validate required fields
  if (!form.gl_dist_code) {
    toast.error('GL Distribution Code is required');
    return;
  }
  
  if (!form.gl_dist_name) {
    toast.error('GL Distribution Name is required');
    return;
  }
  
  if (!form.gl_account_segment1 || !form.gl_account_segment2 || !form.gl_account_segment3) {
    toast.error('All GL Account segments are required');
    return;
  }

  // Update full GL account from segments if needed
  if (form.gl_account_segment1 && form.gl_account_segment2 && form.gl_account_segment3) {
    form.gl_account = `${form.gl_account_segment1}-${form.gl_account_segment2}-${form.gl_account_segment3}`;
  }

  const url = isEditing.value 
    ? `/mm-gl-distribution/${form.id}`
    : '/mm-gl-distribution';
  
  const method = isEditing.value ? 'put' : 'post';
  
  submitting.value = true;

  form.submit(method, url, {
    onSuccess: () => {
        toast.success(
            isEditing.value
            ? 'GL Distribution updated successfully'
            : 'GL Distribution created successfully'
        );
        resetForm();
        fetchGlDistributions();
        submitting.value = false;
    },
    onError: (errors) => {
        console.error('Error submitting form:', errors);
        if (errors && Object.keys(errors).length > 0) {
          // Display first error message
          const firstError = Object.values(errors)[0];
          toast.error(firstError);
        } else {
          toast.error('An error occurred while saving');
        }
        submitting.value = false;
    },
  });
};

// Open GL Account selector modal
const openGlAccountSelector = () => {
  showGlAccountModal.value = true;
  selectedGlAccount.value = null;
};

// Close GL Account selector modal
const closeGlAccountModal = () => {
  showGlAccountModal.value = false;
};

// Select GL Account from modal
const selectGlAccount = (account) => {
  selectedGlAccount.value = account;
};

// Confirm selection of current GL Account
const selectCurrentGlAccount = () => {
  if (selectedGlAccount.value) {
    const segments = selectedGlAccount.value.account_number.split('-');
    form.gl_account_segment1 = segments[0];
    form.gl_account_segment2 = segments[1];
    form.gl_account_segment3 = segments[2];
    form.gl_account = selectedGlAccount.value.account_number;
    form.gl_account_name = selectedGlAccount.value.name;
    form.is_linked = true;
    closeGlAccountModal();
  }
};

// Delete GL Distribution
const deleteGlDistribution = () => {
  if (!isEditing.value) return;
  showDeleteModal.value = true;
};

// Close delete modal
const closeDeleteModal = () => {
  showDeleteModal.value = false;
};

// Confirm delete
const confirmDelete = () => {
  if (!form.id) return;
  
  const url = `/mm-gl-distribution/${form.id}`;
  
  deleting.value = true;

  form.delete(url, {
    onSuccess: () => {
      toast.success('GL Distribution deleted successfully');
      resetForm();
      fetchGlDistributions();
      closeDeleteModal();
      deleting.value = false;
    },
    onError: (errors) => {
      console.error('Error deleting item:', errors);
      toast.error('An error occurred while deleting');
      closeDeleteModal();
      deleting.value = false;
    },
  });
};

// Fetch data on mount
onMounted(() => {
    fetchGlDistributions();
});
</script>
