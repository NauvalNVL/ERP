<template>
  <AppLayout :header="'Define Tax Type'">
    <Head title="Define Tax Type" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-percent mr-3"></i> Define Tax Type
      </h2>
      <p class="text-blue-100">Manage tax types for financial transactions</p>
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
                <input type="text" v-model="searchQuery" placeholder="Search by code or name..."
                  class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
              </div>
            </div>
            <div class="flex gap-2 w-full md:w-auto">
              <button @click="newTaxType" class="btn-primary flex-1 md:flex-none">
                <i class="fas fa-plus-circle mr-2"></i> New Type
              </button>
              <button @click="refreshData" class="btn-secondary flex-1 md:flex-none">
                <i class="fas fa-sync-alt mr-2"></i> Refresh
              </button>
              <button @click="printTaxTypes" class="btn-info flex-1 md:flex-none">
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
                    <th @click="sortBy('code')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Code <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Name <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('is_applied')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Applied <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                     <th @click="sortBy('rate')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Rate (%)<i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-if="loading" class="animate-pulse">
                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                      <div class="flex justify-center items-center space-x-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>Loading tax types...</span>
                      </div>
                    </td>
                  </tr>
                  <tr v-else-if="paginatedTaxTypes.length === 0" class="hover:bg-gray-50">
                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                      No tax types found. Try adjusting your search.
                    </td>
                  </tr>
                  <tr v-for="type in paginatedTaxTypes" :key="type.code" 
                      @click="selectTaxType(type)" 
                      :class="{'bg-blue-50': selectedType && selectedType.code === type.code}"
                      class="hover:bg-gray-50 cursor-pointer">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ type.code }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ type.name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                       <span :class="type.is_applied ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                        {{ type.is_applied ? 'Yes' : 'No' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ type.rate.toFixed(2) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          
          <!-- Pagination Controls -->
          <div class="mt-4 flex justify-between items-center text-sm text-gray-600">
            <div>
              <span>Showing {{ paginatedTaxTypes.length }} of {{ filteredTaxTypes.length }} types</span>
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
          <div v-if="selectedType" class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
              <i class="fas fa-info-circle mr-2 text-blue-500"></i> Tax Type Details
            </h3>
            <div class="space-y-3">
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Code:</span>
                <span class="font-medium text-gray-900">{{ selectedType.code }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Name:</span>
                <span class="font-medium text-gray-900">{{ selectedType.name }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Applied:</span>
                 <span class="font-medium text-gray-900">
                    <span :class="selectedType.is_applied ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                        {{ selectedType.is_applied ? 'Yes' : 'No' }}
                    </span>
                </span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Rate (%):</span>
                <span class="font-medium text-gray-900">{{ selectedType.rate.toFixed(2) }}</span>
              </div>
            </div>
            <div class="mt-6 flex space-x-2">
              <button @click="editTaxType(selectedType)" class="flex-1 btn-blue">
                <i class="fas fa-edit mr-1"></i> Edit
              </button>
              <button @click="confirmDelete(selectedType)" class="flex-1 btn-danger">
                <i class="fas fa-trash-alt mr-1"></i> Delete
              </button>
            </div>
          </div>
          <div v-else class="flex flex-col items-center justify-center h-64 text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
              <i class="fas fa-percent text-blue-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No Type Selected</h3>
            <p class="text-gray-500 mb-4">Select a tax type from the table to view details</p>
            <button @click="newTaxType" class="btn-primary">
              <i class="fas fa-plus-circle mr-1"></i> Create New Type
            </button>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Form Modal -->
    <div v-if="showFormModal" class="fixed inset-0 flex items-center justify-center z-50">
      <div class="absolute inset-0 bg-black opacity-50" @click="showFormModal = false"></div>
      <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg z-10 relative transform transition-all duration-300 ease-in-out">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-indigo-800 rounded-t-xl">
          <div class="flex items-center">
            <div class="bg-white/20 p-2 rounded-lg mr-3">
              <i class="fas fa-percent text-white text-xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-white">{{ isEditing ? 'Edit' : 'New' }} Tax Type</h3>
          </div>
          <button @click="showFormModal = false" class="bg-white/20 hover:bg-white/30 text-white p-2 rounded-lg transition-all duration-200">
            <i class="fas fa-times"></i>
          </button>
        </div>
        
        <!-- Form Content -->
        <div class="p-6 max-h-[70vh] overflow-y-auto">
          <form @submit.prevent="saveTaxType">
            <div class="space-y-6">
              <!-- Code -->
              <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                <label class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                  <i class="fas fa-hashtag text-blue-500 mr-2"></i>
                  Tax Code<span class="text-red-500">*</span>
                      </label>
                <select v-model="formTaxType.code" required :disabled="isEditing"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 appearance-none bg-white bg-no-repeat bg-right pr-10"
                  style="background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2224%22 height=%2224%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%23666%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22><polyline points=%226 9 12 15 18 9%22></polyline></svg>'); background-size: 16px;">
                  <option value="">Select a tax code</option>
                  <option v-for="group in availableTaxGroups" :key="group.code" :value="group.code">
                    {{ group.name }} ({{ group.code }})
                  </option>
                </select>
                <p class="text-xs text-gray-500 mt-2 italic">Code is selected from available Tax Groups and cannot be changed later.</p>
                    </div>
                    
              <!-- Name -->
              <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                <label class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                  <i class="fas fa-tag text-blue-500 mr-2"></i>
                  Tax Name<span class="text-red-500">*</span>
                      </label>
                <input type="text" v-model="formTaxType.name" required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                  placeholder="Enter tax name">
                    </div>
                    
              <!-- Applied -->
              <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                 <label class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                  <i class="fas fa-check-circle text-blue-500 mr-2"></i>
                  Tax Applied<span class="text-red-500">*</span>
                </label>
                      <div class="mt-2 space-x-4">
                        <label class="inline-flex items-center">
                        <input v-model="formTaxType.is_applied" type="radio" :value="true" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300"/>
                          <span class="ml-2 text-gray-700">Y-Yes</span>
                        </label>
                        <label class="inline-flex items-center">
                        <input v-model="formTaxType.is_applied" type="radio" :value="false" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300"/>
                          <span class="ml-2 text-gray-700">N-No</span>
                        </label>
                      </div>
                    </div>
                    
               <!-- Rate -->
              <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                <label class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                  <i class="fas fa-percent text-blue-500 mr-2"></i>
                  Tax Rate (%)<span class="text-red-500">*</span>
                      </label>
                        <input
                          id="rate"
                    v-model="formTaxType.rate"
                          type="number"
                          step="0.01"
                          min="0"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                          placeholder="0.00"
                    :disabled="!formTaxType.is_applied"
                        />
                        </div>

                </div>

            <!-- Form Footer with Buttons -->
            <div class="flex justify-end space-x-3 border-t border-gray-200 pt-5 mt-4">
              <button type="button" @click="showFormModal = false" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition-all duration-200 flex items-center">
                <i class="fas fa-times mr-2"></i> Cancel
                  </button>
              <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition-all duration-200 flex items-center" :disabled="loading">
                <i class="fas fa-save mr-2"></i> {{ isEditing ? 'Update' : 'Create' }}
                <span v-if="loading" class="ml-2 animate-spin"><i class="fas fa-spinner"></i></span>
                  </button>
                </div>
          </form>
        </div>
          </div>
        </div>

    <!-- Confirmation Modal -->
    <div v-if="showConfirmation" class="fixed inset-0 flex items-center justify-center z-50">
      <div class="absolute inset-0 bg-black opacity-50" @click="showConfirmation = false"></div>
      <div class="bg-white rounded-lg shadow-lg max-w-md z-10 w-full">
        <div class="p-6">
          <div class="flex items-center mb-4">
            <div class="bg-red-100 rounded-full p-2 mr-3">
              <i class="fas fa-exclamation-triangle text-red-600"></i>
          </div>
            <h3 class="text-lg font-medium text-gray-900">Confirm Delete</h3>
          </div>
          <p class="mb-4 text-gray-600">Are you sure you want to delete the tax type <span class="font-semibold">{{ typeToDelete?.code }}</span>? This action cannot be undone.</p>
          <div class="flex justify-end space-x-3">
            <button @click="showConfirmation = false" class="px-4 py-2 text-gray-700 border border-gray-300 rounded hover:bg-gray-50">
              <i class="fas fa-times mr-1"></i> Cancel
            </button>
            <button @click="deleteTaxType" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700" :disabled="loading">
              <i class="fas fa-trash-alt mr-1"></i> Delete
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import axios from 'axios';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useToast } from '@/Composables/useToast';

// State variables
const taxTypes = ref([]);
const taxGroups = ref([]);
const selectedType = ref(null);
const loading = ref(false);
const searchQuery = ref('');
const showFormModal = ref(false);
const showConfirmation = ref(false);
const typeToDelete = ref(null);
const isEditing = ref(false);
const sortOrder = ref({
  field: 'code',
  direction: 'asc'
});
const toast = useToast();
const currentPage = ref(1);
const itemsPerPage = ref(10);

// Form for creating/editing tax type
const formTaxType = ref({
  code: '',
  name: '',
  is_applied: false,
  rate: 0.00
});

watch(() => formTaxType.value.is_applied, (newValue) => {
  if (!newValue) {
    formTaxType.value.rate = 0.00;
  }
});

// Reset form to default values
const resetForm = () => {
  formTaxType.value = {
    code: '',
    name: '',
    is_applied: false,
    rate: 0.00
  };
  isEditing.value = false;
};

const processType = (type) => {
    return {
        ...type,
        rate: parseFloat(type.rate) || 0,
        is_applied: !!type.is_applied,
        tax_group: type.tax_group || null
    };
};

const availableTaxGroups = computed(() => {
    if (isEditing.value) {
        return taxGroups.value;
    }
    const usedTaxTypeCodes = new Set(taxTypes.value.map(t => t.code));
    return taxGroups.value.filter(g => !usedTaxTypeCodes.has(g.code));
});

// Computed properties
const filteredTaxTypes = computed(() => {
  let result = taxTypes.value;

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    result = result.filter(type =>
      (type.code && type.code.toLowerCase().includes(query)) ||
      (type.name && type.name.toLowerCase().includes(query))
    );
  }

  result = [...result].sort((a, b) => {
    const field = sortOrder.value.field;
    const direction = sortOrder.value.direction === 'asc' ? 1 : -1;
    
    if(typeof a[field] === 'boolean'){
         if (a[field] === b[field]) return 0;
         return a[field] ? -1 * direction : 1 * direction;
    }

    if ((a[field] || '') < (b[field] || '')) return -1 * direction;
    if ((a[field] || '') > (b[field] || '')) return 1 * direction;
    return 0;
  });

  return result;
});

const paginatedTaxTypes = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return filteredTaxTypes.value.slice(start, end);
});

const totalPages = computed(() => {
    const total = filteredTaxTypes.value.length;
    if (total === 0) return 1;
    return Math.ceil(total / itemsPerPage.value);
});

watch(filteredTaxTypes, () => {
  if (currentPage.value > totalPages.value) {
      currentPage.value = totalPages.value || 1;
  }
});

watch(itemsPerPage, () => {
  currentPage.value = 1;
});

watch(searchQuery, () => {
    currentPage.value = 1;
});

const fetchTaxTypes = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/material-management/tax-types');
    taxTypes.value = response.data.map(type => processType(type));
  } catch (error) {
    console.error('Error fetching tax types:', error);
    toast.error('Failed to load tax types');
  } finally {
    loading.value = false;
  }
};

const fetchTaxGroups = async () => {
  try {
    const response = await axios.get('/api/material-management/tax-groups');
    taxGroups.value = response.data;
  } catch (error) {
    console.error('Error fetching tax groups:', error);
    toast.error('Failed to load tax groups for selection.');
  }
};

const refreshData = () => {
  selectedType.value = null;
  searchQuery.value = '';
  fetchTaxTypes();
  fetchTaxGroups();
};

const selectTaxType = (type) => {
  selectedType.value = type;
};

const newTaxType = () => {
  resetForm();
  showFormModal.value = true;
};

const editTaxType = (type) => {
  isEditing.value = true;
  formTaxType.value = { ...type };
  showFormModal.value = true;
};

const confirmDelete = (type) => {
  typeToDelete.value = type;
  showConfirmation.value = true;
};

const saveTaxType = async () => {
  loading.value = true;
  try {
    let response;
    
    const dataToSend = {
        ...formTaxType.value,
        tax_group_code: formTaxType.value.code
    };
    
    if (isEditing.value) {
      response = await axios.put(`/api/material-management/tax-types/${formTaxType.value.code}`, dataToSend);
      toast.success('Tax type updated successfully');
      
      const updatedType = processType(response.data.data);
      const index = taxTypes.value.findIndex(d => d.code === updatedType.code);
      if (index !== -1) {
        taxTypes.value[index] = updatedType;
        selectedType.value = updatedType;
      }
    } else {
      response = await axios.post('/api/material-management/tax-types', dataToSend);
      toast.success('Tax type created successfully');
      const newType = processType(response.data.data);
      taxTypes.value.push(newType);
      selectedType.value = newType;
    }
    
    showFormModal.value = false;
  } catch (error) {
    console.error('Error saving tax type:', error);
    if (error.response && error.response.data && error.response.data.errors) {
        const errors = error.response.data.errors;
        let errorMessage = Object.values(errors).join(' ');
        toast.error(errorMessage);
    } else {
        toast.error(error.response?.data?.message || 'Failed to save tax type');
    }
  } finally {
    loading.value = false;
  }
};

const deleteTaxType = async () => {
  if (!typeToDelete.value) return;
  
  loading.value = true;
  try {
    await axios.delete(`/api/material-management/tax-types/${typeToDelete.value.code}`);
    toast.success('Tax type deleted successfully');
    
    taxTypes.value = taxTypes.value.filter(d => d.code !== typeToDelete.value.code);
    
    if (selectedType.value?.code === typeToDelete.value.code) {
      selectedType.value = null;
    }
    
    showConfirmation.value = false;
    typeToDelete.value = null;
  } catch (error) {
    console.error('Error deleting tax type:', error);
    toast.error(error.response?.data?.message || 'Failed to delete tax type');
  } finally {
    loading.value = false;
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

const printTaxTypes = () => {
  // This can be implemented later
  toast.info('Print functionality is not yet available.');
  // window.location.href = '/material-management/tax-type/view-print';
};

onMounted(() => {
  fetchTaxTypes();
    fetchTaxGroups();
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
