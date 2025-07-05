<template>
  <AppLayout title="Define Computation Formula">
    <template #header>
      <div class="bg-gradient-to-r from-teal-600 to-teal-800 p-4 rounded-t-lg shadow-md">
        <h2 class="text-xl font-bold text-white flex items-center">
          <i class="fas fa-calculator mr-2"></i> Define Computation Formula
        </h2>
      </div>
    </template>

    <div class="py-6 bg-gray-50">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-lg rounded-lg border border-gray-200">
          <!-- Main Content -->
          <div class="p-6">
            <!-- Loading Overlay -->
            <div v-if="loading" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
              <div class="bg-white p-6 rounded-lg shadow-lg flex items-center space-x-4">
                <svg class="animate-spin h-8 w-8 text-teal-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-gray-700 font-medium">Processing...</span>
              </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-6">
              <!-- Left Panel: Form -->
              <div class="flex-1 bg-white rounded-lg shadow-md p-6 border border-gray-200">
                <h3 class="text-lg font-medium text-gray-800 mb-6 pb-2 border-b border-gray-200 flex items-center">
                  <i class="fas fa-cog mr-2 text-teal-600"></i>
                  Computation Formula Parameters
                </h3>
                
                <!-- Form Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <!-- Board Length Section -->
                  <div class="space-y-3">
                    <label class="block text-sm font-medium text-gray-700">Board Length:</label>
                    <div class="flex items-center space-x-2">
                      <div class="flex-1">
                        <input 
                          type="number" 
                          v-model="form.board_length_min"
                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm"
                          placeholder="Minimum"
                        />
                      </div>
                      <span class="text-gray-500">to</span>
                      <div class="flex-1">
                        <input 
                          type="number" 
                          v-model="form.board_length_max"
                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm"
                          placeholder="Maximum"
                        />
                      </div>
                    </div>
                  </div>

                  <!-- Board Width Section -->
                  <div class="space-y-3">
                    <label class="block text-sm font-medium text-gray-700">Board Width:</label>
                    <div class="flex items-center space-x-2">
                      <div class="flex-1">
                        <input 
                          type="number" 
                          v-model="form.board_width_min"
                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm"
                          placeholder="Minimum"
                        />
                      </div>
                      <span class="text-gray-500">to</span>
                      <div class="flex-1">
                        <input 
                          type="number" 
                          v-model="form.board_width_max"
                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm"
                          placeholder="Maximum"
                        />
                      </div>
                    </div>
                  </div>

                  <!-- D/C Sheet Length Section -->
                  <div class="space-y-3">
                    <label class="block text-sm font-medium text-gray-700">D/C Sheet Length:</label>
                    <div class="flex items-center space-x-2">
                      <div class="flex-1">
                        <input 
                          type="number" 
                          v-model="form.dc_sheet_length_min"
                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm"
                          placeholder="Minimum"
                        />
                      </div>
                      <span class="text-gray-500">to</span>
                      <div class="flex-1">
                        <input 
                          type="number" 
                          v-model="form.dc_sheet_length_max"
                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm"
                          placeholder="Maximum"
                        />
                      </div>
                    </div>
                  </div>

                  <!-- D/C Sheet Width Section -->
                  <div class="space-y-3">
                    <label class="block text-sm font-medium text-gray-700">D/C Sheet Width:</label>
                    <div class="flex items-center space-x-2">
                      <div class="flex-1">
                        <input 
                          type="number" 
                          v-model="form.dc_sheet_width_min"
                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm"
                          placeholder="Minimum"
                        />
                      </div>
                      <span class="text-gray-500">to</span>
                      <div class="flex-1">
                        <input 
                          type="number" 
                          v-model="form.dc_sheet_width_max"
                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm"
                          placeholder="Maximum"
                        />
                      </div>
                    </div>
                  </div>

                  <!-- No of Up Section -->
                  <div class="space-y-3 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">No of Up:</label>
                    <div class="flex items-center space-x-2">
                      <div class="w-32">
                        <input 
                          type="number" 
                          v-model="form.no_of_up_min"
                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm"
                          placeholder="Minimum"
                        />
                      </div>
                      <span class="text-gray-500">to</span>
                      <div class="w-32">
                        <input 
                          type="number" 
                          v-model="form.no_of_up_max"
                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm"
                          placeholder="Maximum"
                          max="99"
                        />
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 flex flex-wrap gap-3 justify-end">
                  <button @click="seedData" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md transition duration-200 flex items-center shadow-sm">
                    <i class="fas fa-database mr-2"></i> Seed Data
                  </button>
                  <button @click="resetForm" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-md transition duration-200 flex items-center shadow-sm">
                    <i class="fas fa-redo-alt mr-2"></i> Reset
                  </button>
                  <button v-if="editingId" @click="cancelEdit" class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-md transition duration-200 flex items-center shadow-sm">
                    <i class="fas fa-times mr-2"></i> Cancel Edit
                  </button>
                  <button 
                    @click="saveFormula" 
                    :disabled="loading" 
                    class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-md transition duration-200 flex items-center shadow-sm"
                  >
                    <i class="fas fa-save mr-2"></i> {{ editingId ? 'Update' : 'Save' }}
                    <span v-if="loading" class="ml-2">
                      <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                    </span>
                  </button>
                </div>
              </div>

              <!-- Right Panel: Formulas List -->
              <div class="lg:w-1/3 bg-white rounded-lg shadow-md border border-gray-200">
                <div class="p-4 border-b border-gray-200 bg-gray-50 rounded-t-lg">
                  <h3 class="text-lg font-medium text-gray-800 flex items-center">
                    <i class="fas fa-list-ul mr-2 text-teal-600"></i>
                    Saved Formulas
                  </h3>
                  <!-- Search input -->
                  <div class="mt-2 relative">
                    <input 
                      type="text" 
                      v-model="searchQuery" 
                      placeholder="Search formulas..."
                      class="w-full pl-8 pr-4 py-1 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                    />
                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                      <i class="fas fa-search text-gray-400 text-sm"></i>
                    </div>
                    <button v-if="searchQuery" @click="searchQuery = ''" class="absolute inset-y-0 right-0 pr-2 flex items-center">
                      <i class="fas fa-times-circle text-gray-400 hover:text-gray-600"></i>
                    </button>
                  </div>
                </div>
                <div class="overflow-y-auto max-h-96">
                  <ul class="divide-y divide-gray-200">
                    <li v-for="formula in filteredFormulas" :key="formula.id" 
                        class="px-4 py-3 hover:bg-gray-50 cursor-pointer transition duration-150"
                        :class="{'bg-teal-50 border-l-4 border-teal-500': selectedFormula && selectedFormula.id === formula.id}"
                        @click="selectFormula(formula)">
                      <div class="flex justify-between">
                        <div>
                          <p class="text-sm font-medium text-gray-900">Formula #{{ formula.id }}</p>
                          <div class="mt-1 flex flex-wrap gap-1">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                              Board: {{ formula.board_length_min }}-{{ formula.board_length_max }}
                            </span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                              Width: {{ formula.board_width_min }}-{{ formula.board_width_max }}
                            </span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                              Up: {{ formula.no_of_up_min }}-{{ formula.no_of_up_max }}
                            </span>
                          </div>
                        </div>
                        <div class="flex space-x-2">
                          <button @click.stop="editFormula(formula)" class="text-teal-600 hover:text-teal-900 p-1 rounded-full hover:bg-teal-100 transition duration-150">
                            <i class="fas fa-edit"></i>
                          </button>
                          <button @click.stop="confirmDelete(formula)" class="text-red-600 hover:text-red-900 p-1 rounded-full hover:bg-red-100 transition duration-150">
                            <i class="fas fa-trash"></i>
                          </button>
                        </div>
                      </div>
                    </li>
                    <li v-if="filteredFormulas.length === 0 && formulas.length > 0" class="px-4 py-6 text-sm text-gray-500 text-center">
                      <div class="flex flex-col items-center">
                        <i class="fas fa-search text-gray-400 text-4xl mb-2"></i>
                        <p>No matching formulas found</p>
                        <p class="text-xs mt-1 text-gray-400">Try adjusting your search</p>
                      </div>
                    </li>
                    <li v-if="formulas.length === 0" class="px-4 py-6 text-sm text-gray-500 text-center">
                      <div class="flex flex-col items-center">
                        <i class="fas fa-inbox text-gray-400 text-4xl mb-2"></i>
                        <p>No formulas found</p>
                        <p class="text-xs mt-1 text-gray-400">Create your first formula using the form</p>
                      </div>
                    </li>
                  </ul>
                </div>
                <div class="p-3 bg-gray-50 border-t border-gray-200 text-xs text-gray-500 rounded-b-lg">
                  {{ filteredFormulas.length }} of {{ formulas.length }} formula(s) shown
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Notification Toast -->
    <div v-if="toast.show" class="fixed bottom-4 right-4 px-4 py-2 rounded-lg shadow-lg z-50"
      :class="{
        'bg-green-500': toast.type === 'success',
        'bg-red-500': toast.type === 'error',
        'bg-blue-500': toast.type === 'info',
      }"
    >
      <div class="flex items-center text-white">
        <span class="mr-2">
          <i :class="{
            'fas fa-check-circle': toast.type === 'success',
            'fas fa-exclamation-circle': toast.type === 'error',
            'fas fa-info-circle': toast.type === 'info',
          }"></i>
        </span>
        <span>{{ toast.message }}</span>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed z-10 inset-0 overflow-y-auto">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
          <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Formula</h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    Are you sure you want to delete this computation formula? This action cannot be undone.
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button @click="deleteFormula" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
              Delete
            </button>
            <button @click="showDeleteModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted, reactive, computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

// State
const formulas = ref([]);
const selectedFormula = ref(null);
const editingId = ref(null);
const loading = ref(false);
const showDeleteModal = ref(false);
const formulaToDelete = ref(null);
const toast = reactive({
  show: false,
  message: '',
  type: 'success',
  timeout: null
});
const searchQuery = ref('');

// Filtered formulas based on search query
const filteredFormulas = computed(() => {
  if (!searchQuery.value) {
    return formulas.value;
  }
  
  const query = searchQuery.value.toLowerCase();
  return formulas.value.filter(formula => {
    // Search by ID
    if (formula.id.toString().includes(query)) {
      return true;
    }
    
    // Search by board dimensions
    if (formula.board_length_min.toString().includes(query) || 
        formula.board_length_max.toString().includes(query) ||
        formula.board_width_min.toString().includes(query) ||
        formula.board_width_max.toString().includes(query)) {
      return true;
    }
    
    // Search by DC sheet dimensions
    if (formula.dc_sheet_length_min.toString().includes(query) ||
        formula.dc_sheet_length_max.toString().includes(query) ||
        formula.dc_sheet_width_min.toString().includes(query) ||
        formula.dc_sheet_width_max.toString().includes(query)) {
      return true;
    }
    
    // Search by no of up
    if (formula.no_of_up_min.toString().includes(query) ||
        formula.no_of_up_max.toString().includes(query)) {
      return true;
    }
    
    return false;
  });
});

// Form data
const form = reactive({
  board_length_min: 0,
  board_length_max: 999999,
  board_width_min: 0,
  board_width_max: 999999,
  dc_sheet_length_min: 1,
  dc_sheet_length_max: 999999,
  dc_sheet_width_min: 1,
  dc_sheet_width_max: 999999,
  no_of_up_min: 1,
  no_of_up_max: 99
});

// Fetch formulas on component mount
onMounted(async () => {
  await fetchFormulas();
});

// Show toast notification
const showToast = (message, type = 'success') => {
  // Clear any existing timeout
  if (toast.timeout) {
    clearTimeout(toast.timeout);
  }
  
  // Set toast properties
  toast.show = true;
  toast.message = message;
  toast.type = type;
  
  // Auto-hide after 3 seconds
  toast.timeout = setTimeout(() => {
    toast.show = false;
  }, 3000);
};

// Fetch formulas from API
const fetchFormulas = async () => {
  try {
    loading.value = true;
    const response = await axios.get('/api/diecut-computation-formulas');
    
    if (response.data && response.data.status === 'success') {
      formulas.value = response.data.data;
    } else {
      showToast('Failed to load formulas', 'error');
    }
  } catch (error) {
    console.error('Error fetching formulas:', error);
    showToast('Error loading formulas: ' + (error.response?.data?.message || error.message), 'error');
  } finally {
    loading.value = false;
  }
};

// Validate form
const validateForm = () => {
  // Check if minimum values are less than maximum values
  if (parseInt(form.board_length_min) > parseInt(form.board_length_max)) {
    showToast('Board Length minimum must be less than maximum', 'error');
    return false;
  }
  
  if (parseInt(form.board_width_min) > parseInt(form.board_width_max)) {
    showToast('Board Width minimum must be less than maximum', 'error');
    return false;
  }
  
  if (parseInt(form.dc_sheet_length_min) > parseInt(form.dc_sheet_length_max)) {
    showToast('D/C Sheet Length minimum must be less than maximum', 'error');
    return false;
  }
  
  if (parseInt(form.dc_sheet_width_min) > parseInt(form.dc_sheet_width_max)) {
    showToast('D/C Sheet Width minimum must be less than maximum', 'error');
    return false;
  }
  
  if (parseInt(form.no_of_up_min) > parseInt(form.no_of_up_max)) {
    showToast('No of Up minimum must be less than maximum', 'error');
    return false;
  }

  // Check for negative values
  if (parseInt(form.board_length_min) < 0 || 
      parseInt(form.board_width_min) < 0) {
    showToast('Board dimensions cannot be negative', 'error');
    return false;
  }

  if (parseInt(form.dc_sheet_length_min) < 1 || 
      parseInt(form.dc_sheet_width_min) < 1) {
    showToast('D/C Sheet dimensions must be at least 1', 'error');
    return false;
  }

  if (parseInt(form.no_of_up_min) < 1) {
    showToast('No of Up minimum must be at least 1', 'error');
    return false;
  }

  if (parseInt(form.no_of_up_max) > 99) {
    showToast('No of Up maximum cannot exceed 99', 'error');
    return false;
  }

  // Check for overlapping ranges with existing formulas
  if (!editingId.value) { // Only check when creating new formula
    const overlapping = checkOverlappingFormulas();
    if (overlapping) {
      showToast('This formula overlaps with an existing formula: #' + overlapping.id, 'error');
      return false;
    }
  }
  
  return true;
};

// Check for overlapping formula ranges
const checkOverlappingFormulas = () => {
  // Skip the current formula being edited
  const otherFormulas = formulas.value.filter(f => f.id !== editingId.value);
  
  for (const existingFormula of otherFormulas) {
    // Check if ranges overlap
    const boardLengthOverlap = checkRangeOverlap(
      form.board_length_min, form.board_length_max,
      existingFormula.board_length_min, existingFormula.board_length_max
    );
    
    const boardWidthOverlap = checkRangeOverlap(
      form.board_width_min, form.board_width_max,
      existingFormula.board_width_min, existingFormula.board_width_max
    );
    
    const dcSheetLengthOverlap = checkRangeOverlap(
      form.dc_sheet_length_min, form.dc_sheet_length_max,
      existingFormula.dc_sheet_length_min, existingFormula.dc_sheet_length_max
    );
    
    const dcSheetWidthOverlap = checkRangeOverlap(
      form.dc_sheet_width_min, form.dc_sheet_width_max,
      existingFormula.dc_sheet_width_min, existingFormula.dc_sheet_width_max
    );
    
    const noOfUpOverlap = checkRangeOverlap(
      form.no_of_up_min, form.no_of_up_max,
      existingFormula.no_of_up_min, existingFormula.no_of_up_max
    );
    
    // If all dimensions overlap, we have a conflicting formula
    if (boardLengthOverlap && boardWidthOverlap && dcSheetLengthOverlap && 
        dcSheetWidthOverlap && noOfUpOverlap) {
      return existingFormula;
    }
  }
  
  return null;
};

// Helper function to check if two ranges overlap
const checkRangeOverlap = (min1, max1, min2, max2) => {
  return !(parseInt(max1) < parseInt(min2) || parseInt(max2) < parseInt(min1));
};

// Reset form to default values
const resetForm = () => {
  Object.assign(form, {
    board_length_min: 0,
    board_length_max: 999999,
    board_width_min: 0,
    board_width_max: 999999,
    dc_sheet_length_min: 1,
    dc_sheet_length_max: 999999,
    dc_sheet_width_min: 1,
    dc_sheet_width_max: 999999,
    no_of_up_min: 1,
    no_of_up_max: 99
  });
  editingId.value = null;
  selectedFormula.value = null;
  showToast('Form has been reset', 'info');
};

// Cancel editing
const cancelEdit = () => {
  resetForm();
  showToast('Edit cancelled', 'info');
};

// Seed sample data
const seedData = async () => {
  try {
    loading.value = true;
    const response = await axios.post('/api/diecut-computation-formulas/seed');
    
    if (response.data && response.data.status === 'success') {
      showToast('Sample data seeded successfully', 'success');
      await fetchFormulas();
    } else {
      throw new Error('Failed to seed sample data');
    }
  } catch (error) {
    console.error('Error seeding data:', error);
    showToast('Error seeding data: ' + (error.response?.data?.message || error.message), 'error');
  } finally {
    loading.value = false;
  }
};

// Select a formula
const selectFormula = (formula) => {
  selectedFormula.value = formula;
};

// Edit a formula
const editFormula = (formula) => {
  editingId.value = formula.id;
  selectedFormula.value = formula;
  
  Object.assign(form, {
    board_length_min: formula.board_length_min || 0,
    board_length_max: formula.board_length_max || 999999,
    board_width_min: formula.board_width_min || 0,
    board_width_max: formula.board_width_max || 999999,
    dc_sheet_length_min: formula.dc_sheet_length_min || 1,
    dc_sheet_length_max: formula.dc_sheet_length_max || 999999,
    dc_sheet_width_min: formula.dc_sheet_width_min || 1,
    dc_sheet_width_max: formula.dc_sheet_width_max || 999999,
    no_of_up_min: formula.no_of_up_min || 1,
    no_of_up_max: formula.no_of_up_max || 99
  });
  
  showToast('Editing formula #' + formula.id, 'info');
};

// Save or update a formula
const saveFormula = async () => {
  if (!validateForm()) {
    return;
  }
  
  try {
    loading.value = true;
    
    const formulaData = {
      board_length_min: parseInt(form.board_length_min),
      board_length_max: parseInt(form.board_length_max),
      board_width_min: parseInt(form.board_width_min),
      board_width_max: parseInt(form.board_width_max),
      dc_sheet_length_min: parseInt(form.dc_sheet_length_min),
      dc_sheet_length_max: parseInt(form.dc_sheet_length_max),
      dc_sheet_width_min: parseInt(form.dc_sheet_width_min),
      dc_sheet_width_max: parseInt(form.dc_sheet_width_max),
      no_of_up_min: parseInt(form.no_of_up_min),
      no_of_up_max: parseInt(form.no_of_up_max)
    };
    
    let response;
    
    if (editingId.value) {
      // Update existing formula
      showToast('Updating formula...', 'info');
      response = await axios.put(`/api/diecut-computation-formulas/${editingId.value}`, formulaData);
      
      if (response.data && response.data.status === 'success') {
        showToast(`Formula #${editingId.value} updated successfully`, 'success');
        
        // Update the selected formula in the UI
        if (selectedFormula.value && selectedFormula.value.id === editingId.value) {
          selectedFormula.value = response.data.data;
        }
      } else {
        throw new Error(response.data?.message || 'Failed to update formula');
      }
    } else {
      // Create new formula
      showToast('Creating new formula...', 'info');
      response = await axios.post('/api/diecut-computation-formulas', formulaData);
      
      if (response.data && response.data.status === 'success') {
        showToast(`Formula #${response.data.data.id} created successfully`, 'success');
        
        // Select the newly created formula
        selectedFormula.value = response.data.data;
      } else {
        throw new Error(response.data?.message || 'Failed to create formula');
      }
    }
    
    // Refresh the data
    await fetchFormulas();
    
    // Reset the form after successful save
    resetForm();
    
  } catch (error) {
    console.error('Error saving formula:', error);
    
    // Handle validation errors from the server
    if (error.response?.data?.errors) {
      const errorMessages = Object.values(error.response.data.errors).flat();
      showToast('Validation error: ' + errorMessages.join(', '), 'error');
    } else {
      showToast('Error saving formula: ' + (error.response?.data?.message || error.message), 'error');
    }
  } finally {
    loading.value = false;
  }
};

// Confirm deletion of a formula
const confirmDelete = (formula) => {
  formulaToDelete.value = formula;
  showDeleteModal.value = true;
};

// Delete a formula
const deleteFormula = async () => {
  if (!formulaToDelete.value) return;
  
  try {
    loading.value = true;
    const response = await axios.delete(`/api/diecut-computation-formulas/${formulaToDelete.value.id}`);
    
    if (response.data && response.data.status === 'success') {
      showToast('Formula deleted successfully', 'success');
    } else {
      throw new Error('Failed to delete formula');
    }
    
    // Refresh the data
    await fetchFormulas();
    
    // Clear the selected formula if it was the one deleted
    if (selectedFormula.value && selectedFormula.value.id === formulaToDelete.value.id) {
      selectedFormula.value = null;
    }
    
    // Close the modal
    showDeleteModal.value = false;
    formulaToDelete.value = null;
    
  } catch (error) {
    console.error('Error deleting formula:', error);
    showToast('Error deleting formula: ' + (error.response?.data?.message || error.message), 'error');
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
/* Input number spinner styling */
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
  opacity: 1;
}

/* Smooth transitions */
.transition-all {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 150ms;
}

/* Input focus effects */
input:focus {
  box-shadow: 0 0 0 2px rgba(13, 148, 136, 0.3);
}

/* Responsive adjustments */
@media (max-width: 640px) {
  .flex-wrap {
    flex-wrap: wrap;
  }
}
</style>
