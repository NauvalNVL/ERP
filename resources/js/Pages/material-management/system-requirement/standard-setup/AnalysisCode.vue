<template>
  <AppLayout :header="'Define Analysis Code'">
    <Head title="Define Analysis Code" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-barcode mr-3"></i> Define Analysis Code
      </h2>
      <p class="text-blue-100">Manage analysis codes for material management</p>
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
                <input type="text" v-model="searchQuery" placeholder="Search by code, name, or group..."
                  class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
              </div>
            </div>
            <div class="flex gap-2 w-full md:w-auto">
              <button @click="newAnalysisCode" class="btn-primary flex-1 md:flex-none">
                <i class="fas fa-plus-circle mr-2"></i> New Code
                    </button>
              <button @click="refreshData" class="btn-secondary flex-1 md:flex-none">
                <i class="fas fa-sync-alt mr-2"></i> Refresh
                      </button>
              <button @click="printAnalysisCodes" class="btn-info flex-1 md:flex-none">
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
                    <th @click="sortBy('group')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Group <i class="fas fa-sort ml-1"></i>
                      </div>
                          </th>
                    <th @click="sortBy('group2')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Group 2 <i class="fas fa-sort ml-1"></i>
                      </div>
                          </th>
                        </tr>
                      </thead>
                      <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-if="loading" class="animate-pulse">
                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                      <div class="flex justify-center items-center space-x-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>Loading analysis codes...</span>
                            </div>
                          </td>
                        </tr>
                  <tr v-else-if="paginatedAnalysisCodes.length === 0" class="hover:bg-gray-50">
                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                      No analysis codes found. Try adjusting your search.
                          </td>
                        </tr>
                  <tr v-for="code in paginatedAnalysisCodes" :key="code.code" 
                      @click="selectAnalysisCode(code)" 
                      :class="{'bg-blue-50': selectedCode && selectedCode.code === code.code}"
                      class="hover:bg-gray-50 cursor-pointer">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ code.code }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ code.name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                       <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ code.group }}
                            </span>
                          </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                       <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                        {{ code.group2 }}
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
              <span>Showing {{ paginatedAnalysisCodes.length }} of {{ filteredAnalysisCodes.length }} codes</span>
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
          <div v-if="selectedCode" class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
              <i class="fas fa-info-circle mr-2 text-blue-500"></i> Analysis Code Details
            </h3>
            <div class="space-y-3">
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Code:</span>
                <span class="font-medium text-gray-900">{{ selectedCode.code }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Name:</span>
                <span class="font-medium text-gray-900">{{ selectedCode.name }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Group:</span>
                <span class="font-medium text-gray-900">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ selectedCode.group }}
                    </span>
                </span>
                </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Group 2:</span>
                <span class="font-medium text-gray-900">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                        {{ selectedCode.group2 }}
                    </span>
                </span>
              </div>
            </div>
            <div class="mt-6 flex space-x-2">
              <button @click="editAnalysisCode(selectedCode)" class="flex-1 btn-blue">
                <i class="fas fa-edit mr-1"></i> Edit
              </button>
              <button @click="confirmDelete(selectedCode)" class="flex-1 btn-danger">
                <i class="fas fa-trash-alt mr-1"></i> Delete
              </button>
            </div>
          </div>
          <div v-else class="flex flex-col items-center justify-center h-64 text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
              <i class="fas fa-barcode text-blue-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No Code Selected</h3>
            <p class="text-gray-500 mb-4">Select a code from the table to view details</p>
            <button @click="newAnalysisCode" class="btn-primary">
              <i class="fas fa-plus-circle mr-1"></i> Create New Code
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
              <i class="fas fa-barcode text-white text-xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-white">{{ isEditing ? 'Edit' : 'New' }} Analysis Code</h3>
          </div>
          <button @click="showFormModal = false" class="bg-white/20 hover:bg-white/30 text-white p-2 rounded-lg transition-all duration-200">
            <i class="fas fa-times"></i>
          </button>
        </div>
        
        <!-- Form Content -->
        <div class="p-6 max-h-[70vh] overflow-y-auto">
          <form @submit.prevent="saveAnalysisCode">
            <div class="space-y-6">
              <!-- Code -->
              <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                <label class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                  <i class="fas fa-hashtag text-blue-500 mr-2"></i>
                  Analysis Code<span class="text-red-500">*</span>
                </label>
                <input type="text" v-model="formAnalysisCode.code" required :disabled="isEditing"
                  maxlength="10"
                  @input="formAnalysisCode.code = $event.target.value.toUpperCase()"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                  placeholder="Enter analysis code">
                <p class="text-xs text-gray-500 mt-2 italic">Code must be unique (max 10 chars) and cannot be changed later</p>
              </div>
              
              <!-- Name -->
              <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                <label class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                  <i class="fas fa-tag text-blue-500 mr-2"></i>
                  Analysis Name<span class="text-red-500">*</span>
                </label>
                <input type="text" v-model="formAnalysisCode.name" required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                  placeholder="Enter analysis name">
              </div>

              <!-- Group -->
              <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                <label class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                  <i class="fas fa-layer-group text-blue-500 mr-2"></i>
                  Analysis Group<span class="text-red-500">*</span>
                </label>
                <input type="text" v-model="formAnalysisCode.group" required
                  maxlength="10"
                  @input="formAnalysisCode.group = $event.target.value.toUpperCase()"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                  placeholder="Enter analysis group">
              </div>
              
              <!-- Group 2 -->
              <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                <label class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                  <i class="fas fa-layer-group text-purple-500 mr-2"></i>
                  Analysis Group 2<span class="text-red-500">*</span>
                </label>
                <input type="text" v-model="formAnalysisCode.group2" required
                  maxlength="10"
                  @input="formAnalysisCode.group2 = $event.target.value.toUpperCase()"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                  placeholder="Enter analysis group 2">
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
          <p class="mb-4 text-gray-600">Are you sure you want to delete the analysis code <span class="font-semibold">{{ codeToDelete?.code }}</span>? This action cannot be undone.</p>
          <div class="flex justify-end space-x-3">
            <button @click="showConfirmation = false" class="px-4 py-2 text-gray-700 border border-gray-300 rounded hover:bg-gray-50">
              <i class="fas fa-times mr-1"></i> Cancel
            </button>
            <button @click="deleteAnalysisCode" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700" :disabled="loading">
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
const analysisCodes = ref([]);
const selectedCode = ref(null);
const loading = ref(false);
const searchQuery = ref('');
const showFormModal = ref(false);
const showConfirmation = ref(false);
const codeToDelete = ref(null);
const isEditing = ref(false);
const sortOrder = ref({
  field: 'code',
  direction: 'asc'
});
const toast = useToast();
const currentPage = ref(1);
const itemsPerPage = ref(10);

// Form for creating/editing analysis code
const formAnalysisCode = ref({
  code: '',
  name: '',
  group: '',
  group2: '',
});

// Reset form to default values
const resetForm = () => {
  formAnalysisCode.value = {
    code: '',
    name: '',
    group: '',
    group2: '',
  };
  isEditing.value = false;
};

// Computed properties
const filteredAnalysisCodes = computed(() => {
  let result = analysisCodes.value;
  
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    result = result.filter(code =>
      (code.code && code.code.toLowerCase().includes(query)) ||
      (code.name && code.name.toLowerCase().includes(query)) ||
      (code.group && code.group.toLowerCase().includes(query)) ||
      (code.group2 && code.group2.toLowerCase().includes(query))
    );
  }

  result = [...result].sort((a, b) => {
    const field = sortOrder.value.field;
    const direction = sortOrder.value.direction === 'asc' ? 1 : -1;
    
    if ((a[field] || '') < (b[field] || '')) return -1 * direction;
    if ((a[field] || '') > (b[field] || '')) return 1 * direction;
    return 0;
  });

  return result;
});

const paginatedAnalysisCodes = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return filteredAnalysisCodes.value.slice(start, end);
});

const totalPages = computed(() => {
    const total = filteredAnalysisCodes.value.length;
    if (total === 0) return 1;
    return Math.ceil(total / itemsPerPage.value);
});

watch(filteredAnalysisCodes, () => {
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

const fetchAnalysisCodes = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/material-management/analysis-codes');
    analysisCodes.value = response.data;
  } catch (error) {
    console.error('Error fetching analysis codes:', error);
    toast.error('Failed to load analysis codes');
  } finally {
    loading.value = false;
  }
};

const refreshData = () => {
  selectedCode.value = null;
  searchQuery.value = '';
  fetchAnalysisCodes();
};

const selectAnalysisCode = (code) => {
  selectedCode.value = code;
};

const newAnalysisCode = () => {
  resetForm();
  showFormModal.value = true;
};

const editAnalysisCode = (code) => {
  isEditing.value = true;
  formAnalysisCode.value = { ...code };
  showFormModal.value = true;
};

const confirmDelete = (code) => {
  codeToDelete.value = code;
  showConfirmation.value = true;
};

const saveAnalysisCode = async () => {
  loading.value = true;
  try {
    let response;
    
    if (isEditing.value) {
      response = await axios.put(`/api/material-management/analysis-codes/${formAnalysisCode.value.code}`, formAnalysisCode.value);
      toast.success('Analysis code updated successfully');
      
      const index = analysisCodes.value.findIndex(d => d.code === formAnalysisCode.value.code);
      if (index !== -1) {
        analysisCodes.value[index] = response.data;
        selectedCode.value = response.data;
      }
    } else {
      response = await axios.post('/api/material-management/analysis-codes', formAnalysisCode.value);
      toast.success('Analysis code created successfully');
      analysisCodes.value.push(response.data);
      selectedCode.value = response.data;
    }
    
    showFormModal.value = false;
  } catch (error) {
    console.error('Error saving analysis code:', error);
    if (error.response && error.response.data && error.response.data.errors) {
      const errors = error.response.data.errors;
      const errorMessages = Object.values(errors).flat();
      toast.error(errorMessages.join('\n'));
    } else {
      toast.error(error.response?.data?.message || 'Failed to save analysis code');
    }
  } finally {
    loading.value = false;
  }
};

const deleteAnalysisCode = async () => {
  if (!codeToDelete.value) return;
  
  loading.value = true;
  try {
    await axios.delete(`/api/material-management/analysis-codes/${codeToDelete.value.code}`);
    toast.success('Analysis code deleted successfully');
    
    analysisCodes.value = analysisCodes.value.filter(d => d.code !== codeToDelete.value.code);
    
    if (selectedCode.value?.code === codeToDelete.value.code) {
      selectedCode.value = null;
    }
    
    showConfirmation.value = false;
    codeToDelete.value = null;
  } catch (error) {
    console.error('Error deleting analysis code:', error);
    toast.error(error.response?.data?.message || 'Failed to delete analysis code');
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

const printAnalysisCodes = () => {
  toast.info('Print functionality is not yet available.');
};

onMounted(fetchAnalysisCodes);
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
