<template>
  <AppLayout :header="'Define Analysis Code'">
    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Main Content Card -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="p-6">
            <!-- Action buttons -->
            <div class="mb-6 flex flex-wrap justify-between items-center gap-4">
              <div class="flex flex-wrap gap-2">
                <button 
                  @click="isEditMode = false; openFormPanel()"
                  class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors inline-flex items-center"
                >
                  <PlusIcon class="w-5 h-5 mr-1" />
                  Add New
                </button>
                <button 
                  @click="refreshData"
                  class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition-colors inline-flex items-center"
                >
                  <RefreshIcon class="w-5 h-5 mr-1" />
                  Refresh
                </button>
                <button 
                  @click="exportData"
                  class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors inline-flex items-center"
                >
                  <DocumentDownloadIcon class="w-5 h-5 mr-1" />
                  Export
                </button>
                
                <!-- Group Filter -->
                <div class="relative">
                  <select
                    v-model="groupFilter"
                    @change="filterData"
                    class="pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                  >
                    <option value="">All Groups</option>
                    <option v-for="group in availableGroups" :key="group" :value="group">
                      {{ group }}
                    </option>
                  </select>
                </div>
                
                <!-- Group2 Filter -->
                <div class="relative">
                  <select
                    v-model="group2Filter"
                    @change="filterData"
                    class="pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                  >
                    <option value="">All Group2s</option>
                    <option v-for="group2 in availableGroup2s" :key="group2" :value="group2">
                      {{ group2 }}
                    </option>
                  </select>
                </div>
              </div>
              
              <div class="relative">
                <input 
                  v-model="searchQuery"
                  type="text" 
                  placeholder="Search analysis codes..."
                  class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <SearchIcon class="w-5 h-5 text-gray-400" />
                </div>
              </div>
            </div>
            
            <!-- Form and Table Container (side by side in large screens) -->
            <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
              <!-- Analysis Code Form Panel -->
              <Transition name="slide-fade">
                <div v-if="showFormPanel" 
                    class="bg-gray-50 rounded-lg border border-gray-200 p-4 shadow-inner w-full md:w-1/3 transition-all">
                  <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">
                      {{ isEditMode ? 'Edit Analysis Code' : 'Add New Analysis Code' }}
                    </h3>
                    <button @click="closeFormPanel" class="text-gray-500 hover:text-gray-700">
                      <XIcon class="w-5 h-5" />
                    </button>
                  </div>
                  
                  <form @submit.prevent="submitForm" class="space-y-4">
                    <div>
                      <label for="code" class="block text-sm font-medium text-gray-700 mb-1">
                        Analysis Code
                      </label>
                      <input 
                        id="code" 
                        v-model="form.code"
                        type="text"
                        :disabled="isEditMode"
                        :class="{'bg-gray-200': isEditMode}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                        placeholder="Enter code"
                        required
                        maxlength="10"
                        autocomplete="off"
                      />
                      <p v-if="errors.code" class="mt-1 text-sm text-red-600">{{ errors.code }}</p>
                    </div>
                    
                    <div>
                      <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Analysis Name
                      </label>
                      <input 
                        id="name" 
                        v-model="form.name"
                        type="text"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                        placeholder="Enter name"
                        required
                        maxlength="100"
                        autocomplete="off"
                      />
                      <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
                    </div>
                    
                    <div>
                      <label for="group" class="block text-sm font-medium text-gray-700 mb-1">
                        Analysis Group
                      </label>
                      <input 
                        id="group" 
                        v-model="form.group"
                        type="text"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                        placeholder="Enter group"
                        required
                        maxlength="10"
                        autocomplete="off"
                      />
                      <p v-if="errors.group" class="mt-1 text-sm text-red-600">{{ errors.group }}</p>
                    </div>
                    
                    <div>
                      <label for="group2" class="block text-sm font-medium text-gray-700 mb-1">
                        Analysis Group 2
                      </label>
                      <input 
                        id="group2" 
                        v-model="form.group2"
                        type="text"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                        placeholder="Enter group 2"
                        required
                        maxlength="10"
                        autocomplete="off"
                      />
                      <p v-if="errors.group2" class="mt-1 text-sm text-red-600">{{ errors.group2 }}</p>
                    </div>
                    
                    <div class="flex justify-end space-x-2 pt-2">
                      <button 
                        type="button" 
                        @click="closeFormPanel"
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                      >
                        Cancel
                      </button>
                      <button 
                        type="submit"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                      >
                        {{ isEditMode ? 'Update' : 'Save' }}
                      </button>
                    </div>
                  </form>
                </div>
              </Transition>
              
              <!-- Analysis Code Table -->
              <div class="w-full" :class="{ 'md:w-2/3': showFormPanel, 'md:w-full': !showFormPanel }">
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                  <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                      <thead class="bg-gray-50">
                        <tr>
                          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Code
                          </th>
                          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Name
                          </th>
                          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Group
                          </th>
                          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Group 2
                          </th>
                          <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                          </th>
                        </tr>
                      </thead>
                      <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-if="loading">
                          <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex justify-center">
                              <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                              </svg>
                            </div>
                          </td>
                        </tr>
                        <tr v-else-if="filteredAnalysisCodes.length === 0">
                          <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                            No analysis codes found
                          </td>
                        </tr>
                        <tr v-for="analysisCode in paginatedAnalysisCodes" 
                            :key="analysisCode.code" 
                            class="hover:bg-gray-50 transition-colors"
                            :class="{'bg-blue-50': selectedAnalysisCode && selectedAnalysisCode.code === analysisCode.code}">
                          <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900">{{ analysisCode.code }}</div>
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                            {{ analysisCode.name }}
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-blue-100 text-blue-800">
                              {{ analysisCode.group }}
                            </span>
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-purple-100 text-purple-800">
                              {{ analysisCode.group2 }}
                            </span>
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex justify-end space-x-1">
                              <button 
                                @click="editAnalysisCode(analysisCode)"
                                class="inline-flex items-center p-1.5 text-blue-600 hover:bg-blue-100 rounded-full transition-colors"
                                title="Edit"
                              >
                                <PencilIcon class="h-4 w-4" />
                              </button>
                              <button 
                                @click="confirmDelete(analysisCode)"
                                class="inline-flex items-center p-1.5 text-red-600 hover:bg-red-100 rounded-full transition-colors"
                                title="Delete"
                              >
                                <TrashIcon class="h-4 w-4" />
                              </button>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  
                  <!-- Table Footer with Pagination -->
                  <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    <div class="flex items-center justify-between">
                      <div class="text-sm text-gray-700">
                        Showing {{ filteredAnalysisCodes.length > 0 ? ((currentPage - 1) * itemsPerPage) + 1 : 0 }} 
                        to {{ Math.min(filteredAnalysisCodes.length, currentPage * itemsPerPage) }} 
                        of {{ totalItems }} results
                      </div>
                      <div class="flex space-x-2">
                        <button
                          @click="currentPage > 1 && currentPage--"
                          :disabled="currentPage === 1"
                          :class="[
                            'relative inline-flex items-center px-4 py-2 text-sm font-medium rounded-md',
                            currentPage === 1 
                              ? 'bg-gray-100 text-gray-400 cursor-not-allowed' 
                              : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300'
                          ]"
                        >
                          Previous
                        </button>
                        <button
                          @click="currentPage < totalPages && currentPage++"
                          :disabled="currentPage === totalPages"
                          :class="[
                            'relative inline-flex items-center px-4 py-2 text-sm font-medium rounded-md',
                            currentPage === totalPages 
                              ? 'bg-gray-100 text-gray-400 cursor-not-allowed' 
                              : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300'
                          ]"
                        >
                          Next
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Delete Confirmation Dialog -->
    <TransitionRoot appear :show="isConfirmingDelete" as="template">
      <Dialog as="div" @close="cancelDelete" class="relative z-10">
        <TransitionChild
          as="template"
          enter="ease-out duration-300"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="ease-in duration-200"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-black bg-opacity-25" />
        </TransitionChild>

        <div class="fixed inset-0 overflow-y-auto">
          <div class="flex min-h-full items-center justify-center p-4 text-center">
            <TransitionChild
              as="template"
              enter="ease-out duration-300"
              enter-from="opacity-0 scale-95"
              enter-to="opacity-100 scale-100"
              leave="ease-in duration-200"
              leave-from="opacity-100 scale-100"
              leave-to="opacity-0 scale-95"
            >
              <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                  Confirm Delete
                </DialogTitle>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    Are you sure you want to delete the analysis code "{{ pendingDelete?.code }}"?
                    This action cannot be undone.
                  </p>
                </div>

                <div class="mt-4 flex justify-end space-x-2">
                  <button
                    type="button"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    @click="cancelDelete"
                  >
                    Cancel
                  </button>
                  <button
                    type="button"
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                    @click="deleteAnalysisCode"
                  >
                    Delete
                  </button>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';
import { PlusIcon, PencilIcon, TrashIcon, RefreshIcon, DocumentDownloadIcon, SearchIcon, XIcon } from '@heroicons/vue/solid';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

// State
const analysisCodes = ref([]);
const availableGroups = ref([]);
const availableGroup2s = ref([]);
const groupFilter = ref('');
const group2Filter = ref('');
const searchQuery = ref('');
const loading = ref(false);
const showFormPanel = ref(false);
const isEditMode = ref(false);
const selectedAnalysisCode = ref(null);
const pendingDelete = ref(null);
const isConfirmingDelete = ref(false);
const currentPage = ref(1);
const itemsPerPage = ref(10);
const errors = ref({});

// Form
const form = ref({
  code: '',
  name: '',
  group: '',
  group2: ''
});

// Fetch data
const fetchData = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/material-management/analysis-codes');
    analysisCodes.value = response.data;
    fetchGroups();
    fetchGroup2s();
  } catch (error) {
    console.error('Error fetching analysis codes:', error);
  } finally {
    loading.value = false;
  }
};

const fetchGroups = async () => {
  try {
    const response = await axios.get('/api/material-management/analysis-codes/groups');
    availableGroups.value = response.data;
  } catch (error) {
    console.error('Error fetching groups:', error);
  }
};

const fetchGroup2s = async () => {
  try {
    const response = await axios.get('/api/material-management/analysis-codes/group2s');
    availableGroup2s.value = response.data;
  } catch (error) {
    console.error('Error fetching group2s:', error);
  }
};

// Filter data
const filterData = () => {
  currentPage.value = 1;
};

const filteredAnalysisCodes = computed(() => {
  let filtered = [...analysisCodes.value];
  
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    filtered = filtered.filter(code => 
      code.code.toLowerCase().includes(query) || 
      code.name.toLowerCase().includes(query) ||
      code.group.toLowerCase().includes(query) ||
      code.group2.toLowerCase().includes(query)
    );
  }
  
  if (groupFilter.value) {
    filtered = filtered.filter(code => code.group === groupFilter.value);
  }
  
  if (group2Filter.value) {
    filtered = filtered.filter(code => code.group2 === group2Filter.value);
  }
  
  return filtered;
});

// Pagination
const totalItems = computed(() => filteredAnalysisCodes.value.length);
const totalPages = computed(() => Math.ceil(totalItems.value / itemsPerPage.value));

const paginatedAnalysisCodes = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return filteredAnalysisCodes.value.slice(start, end);
});

// Form Panel
const openFormPanel = () => {
  showFormPanel.value = true;
};

const closeFormPanel = () => {
  showFormPanel.value = false;
  resetForm();
};

const resetForm = () => {
  form.value = {
    code: '',
    name: '',
    group: '',
    group2: ''
  };
  errors.value = {};
  selectedAnalysisCode.value = null;
};

// Edit Analysis Code
const editAnalysisCode = (analysisCode) => {
  isEditMode.value = true;
  selectedAnalysisCode.value = analysisCode;
  form.value = {
    code: analysisCode.code,
    name: analysisCode.name,
    group: analysisCode.group,
    group2: analysisCode.group2
  };
  openFormPanel();
};

// Delete Analysis Code
const confirmDelete = (analysisCode) => {
  pendingDelete.value = analysisCode;
  isConfirmingDelete.value = true;
};

const cancelDelete = () => {
  isConfirmingDelete.value = false;
  pendingDelete.value = null;
};

const deleteAnalysisCode = async () => {
  if (!pendingDelete.value) return;
  
  try {
    await axios.delete(`/api/material-management/analysis-codes/${pendingDelete.value.code}`);
    analysisCodes.value = analysisCodes.value.filter(code => code.code !== pendingDelete.value.code);
    isConfirmingDelete.value = false;
    pendingDelete.value = null;
  } catch (error) {
    console.error('Error deleting analysis code:', error);
  }
};

// Form Submission
const submitForm = async () => {
  errors.value = {};
  
  try {
    if (isEditMode.value) {
      await axios.put(`/api/material-management/analysis-codes/${form.value.code}`, form.value);
      
      // Update item in the list
      const index = analysisCodes.value.findIndex(code => code.code === form.value.code);
      if (index !== -1) {
        analysisCodes.value[index] = { ...form.value };
      }
      
      closeFormPanel();
    } else {
      const response = await axios.post('/api/material-management/analysis-codes', form.value);
      analysisCodes.value.push(response.data);
      closeFormPanel();
    }
  } catch (error) {
    if (error.response && error.response.data && error.response.data.errors) {
      errors.value = error.response.data.errors;
    } else {
      console.error('Error submitting form:', error);
    }
  }
};

// Other Actions
const refreshData = () => {
  fetchData();
};

const exportData = () => {
  // Create CSV content
  let csvContent = "data:text/csv;charset=utf-8,";
  csvContent += "Code,Name,Group,Group2\n";
  
  filteredAnalysisCodes.value.forEach(code => {
    csvContent += `"${code.code}","${code.name}","${code.group}","${code.group2}"\n`;
  });
  
  // Create download link
  const encodedUri = encodeURI(csvContent);
  const link = document.createElement("a");
  link.setAttribute("href", encodedUri);
  link.setAttribute("download", "analysis_codes.csv");
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
};

// Watch for filter changes
watch(searchQuery, () => {
  currentPage.value = 1;
});

// Fetch data on component mount
onMounted(() => {
  fetchData();
});
</script>

<style scoped>
.slide-fade-enter-active,
.slide-fade-leave-active {
  transition: all 0.3s ease;
}

.slide-fade-enter-from,
.slide-fade-leave-to {
  transform: translateX(-20px);
  opacity: 0;
}
</style>
