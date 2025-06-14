<template>
  <AppLayout title="Define Computation Formula">
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Define Computation Formula
        </h2>
        <div class="flex space-x-2">
          <button @click="resetForm" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md transition duration-200 flex items-center shadow-sm">
            <i class="fas fa-redo-alt mr-2"></i> Reset
          </button>
          <button @click="saveFormula" :disabled="loading" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-200 flex items-center shadow-sm">
            <i class="fas fa-save mr-2"></i> {{ editingId ? 'Update' : 'Save' }}
          </button>
        </div>
      </div>
    </template>

    <div class="py-12 bg-gray-50">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg border border-gray-100">
          <!-- Main Content -->
          <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
              <!-- Left Panel: Form -->
              <div class="lg:col-span-2 bg-white rounded-lg shadow-md p-6 border border-gray-100">
                <h3 class="text-lg font-medium text-gray-800 mb-6 pb-2 border-b border-gray-200">
                  <i class="fas fa-calculator mr-2 text-blue-500"></i>
                  Computation Formula Settings
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                  <!-- Board Length Section -->
                  <div class="space-y-4">
                    <h4 class="text-md font-medium text-gray-800 border-b pb-2 flex items-center">
                      <span class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-2">
                        <i class="fas fa-arrows-alt-h text-blue-600"></i>
                      </span>
                      Board Length
                    </h4>
                    <div class="grid grid-cols-2 gap-4">
                      <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Minimum</label>
                        <input 
                          type="number" 
                          v-model="form.board_length_min"
                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                          placeholder="0"
                        />
                      </div>
                      <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Maximum</label>
                        <input 
                          type="number" 
                          v-model="form.board_length_max"
                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                          placeholder="999999"
                        />
                      </div>
                    </div>
                  </div>

                  <!-- Board Width Section -->
                  <div class="space-y-4">
                    <h4 class="text-md font-medium text-gray-800 border-b pb-2 flex items-center">
                      <span class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center mr-2">
                        <i class="fas fa-arrows-alt-v text-indigo-600"></i>
                      </span>
                      Board Width
                    </h4>
                    <div class="grid grid-cols-2 gap-4">
                      <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Minimum</label>
                        <input 
                          type="number" 
                          v-model="form.board_width_min"
                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                          placeholder="0"
                        />
                      </div>
                      <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Maximum</label>
                        <input 
                          type="number" 
                          v-model="form.board_width_max"
                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                          placeholder="999999"
                        />
                      </div>
                    </div>
                  </div>

                  <!-- D/C Sheet Length Section -->
                  <div class="space-y-4">
                    <h4 class="text-md font-medium text-gray-800 border-b pb-2 flex items-center">
                      <span class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-2">
                        <i class="fas fa-ruler-horizontal text-green-600"></i>
                      </span>
                      D/C Sheet Length
                    </h4>
                    <div class="grid grid-cols-3 gap-4 items-end">
                      <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Minimum</label>
                        <input 
                          type="number" 
                          v-model="form.dc_sheet_length_min"
                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                          placeholder="1"
                        />
                      </div>
                      <div class="text-center">
                        <span class="text-sm font-medium text-gray-700 bg-gray-100 px-3 py-1 rounded-full">to</span>
                      </div>
                      <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Maximum</label>
                        <input 
                          type="number" 
                          v-model="form.dc_sheet_length_max"
                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                          placeholder="999999"
                        />
                      </div>
                    </div>
                  </div>

                  <!-- D/C Sheet Width Section -->
                  <div class="space-y-4">
                    <h4 class="text-md font-medium text-gray-800 border-b pb-2 flex items-center">
                      <span class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center mr-2">
                        <i class="fas fa-ruler-vertical text-yellow-600"></i>
                      </span>
                      D/C Sheet Width
                    </h4>
                    <div class="grid grid-cols-3 gap-4 items-end">
                      <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Minimum</label>
                        <input 
                          type="number" 
                          v-model="form.dc_sheet_width_min"
                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                          placeholder="1"
                        />
                      </div>
                      <div class="text-center">
                        <span class="text-sm font-medium text-gray-700 bg-gray-100 px-3 py-1 rounded-full">to</span>
                      </div>
                      <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Maximum</label>
                        <input 
                          type="number" 
                          v-model="form.dc_sheet_width_max"
                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                          placeholder="999999"
                        />
                      </div>
                    </div>
                  </div>

                  <!-- No of Up Section -->
                  <div class="space-y-4 md:col-span-2">
                    <h4 class="text-md font-medium text-gray-800 border-b pb-2 flex items-center">
                      <span class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center mr-2">
                        <i class="fas fa-layer-group text-red-600"></i>
                      </span>
                      No of Up
                    </h4>
                    <div class="grid grid-cols-3 md:grid-cols-5 gap-4 items-end">
                      <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Minimum</label>
                        <input 
                          type="number" 
                          v-model="form.no_of_up_min"
                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                          placeholder="1"
                        />
                      </div>
                      <div class="text-center">
                        <span class="text-sm font-medium text-gray-700 bg-gray-100 px-3 py-1 rounded-full">to</span>
                      </div>
                      <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Maximum</label>
                        <input 
                          type="number" 
                          v-model="form.no_of_up_max"
                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                          placeholder="99"
                          max="99"
                        />
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Quick Actions -->
                <div class="mt-8 pt-4 border-t border-gray-200">
                  <div class="flex flex-wrap gap-2">
                    <button @click="setPresetValues('small')" class="px-3 py-1 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition duration-200 text-sm flex items-center">
                      <i class="fas fa-compress-alt mr-1"></i> Small Size Preset
                    </button>
                    <button @click="setPresetValues('medium')" class="px-3 py-1 bg-green-100 text-green-700 rounded-md hover:bg-green-200 transition duration-200 text-sm flex items-center">
                      <i class="fas fa-expand mr-1"></i> Medium Size Preset
                    </button>
                    <button @click="setPresetValues('large')" class="px-3 py-1 bg-purple-100 text-purple-700 rounded-md hover:bg-purple-200 transition duration-200 text-sm flex items-center">
                      <i class="fas fa-expand-arrows-alt mr-1"></i> Large Size Preset
                    </button>
                  </div>
                </div>
              </div>

              <!-- Right Panel: Formulas List -->
              <div class="bg-white rounded-lg shadow-md border border-gray-100">
                <div class="p-4 border-b border-gray-200 bg-gray-50 rounded-t-lg">
                  <h3 class="text-lg font-medium text-gray-800 flex items-center">
                    <i class="fas fa-list-ul mr-2 text-blue-500"></i>
                    Saved Formulas
                  </h3>
                </div>
                <div class="overflow-y-auto max-h-96">
                  <ul class="divide-y divide-gray-200">
                    <li v-for="formula in formulas" :key="formula.id" 
                        class="px-4 py-3 hover:bg-gray-50 cursor-pointer transition duration-150"
                        :class="{'bg-blue-50 border-l-4 border-blue-500': selectedFormula && selectedFormula.id === formula.id}"
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
                          <button @click.stop="editFormula(formula)" class="text-indigo-600 hover:text-indigo-900 p-1 rounded-full hover:bg-indigo-100 transition duration-150">
                            <i class="fas fa-edit"></i>
                          </button>
                          <button @click.stop="confirmDelete(formula)" class="text-red-600 hover:text-red-900 p-1 rounded-full hover:bg-red-100 transition duration-150">
                            <i class="fas fa-trash"></i>
                          </button>
                        </div>
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
                  {{ formulas.length }} formula(s) found
                </div>
              </div>
            </div>
          </div>
        </div>
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
import { ref, onMounted, reactive } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

// State
const formulas = ref([]);
const selectedFormula = ref(null);
const editingId = ref(null);
const loading = ref(false);
const showDeleteModal = ref(false);
const formulaToDelete = ref(null);

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

// Fetch formulas from API
const fetchFormulas = async () => {
  try {
    loading.value = true;
    const response = await axios.get('/api/diecut-computation-formulas');
    formulas.value = response.data.data;
  } catch (error) {
    console.error('Error fetching formulas:', error);
    // Show error notification
  } finally {
    loading.value = false;
  }
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
};

// Set preset values
const setPresetValues = (preset) => {
  switch (preset) {
    case 'small':
      Object.assign(form, {
        board_length_min: 100,
        board_length_max: 1000,
        board_width_min: 100,
        board_width_max: 1000,
        dc_sheet_length_min: 100,
        dc_sheet_length_max: 1000,
        dc_sheet_width_min: 100,
        dc_sheet_width_max: 1000,
        no_of_up_min: 1,
        no_of_up_max: 10
      });
      break;
    case 'medium':
      Object.assign(form, {
        board_length_min: 1000,
        board_length_max: 5000,
        board_width_min: 1000,
        board_width_max: 5000,
        dc_sheet_length_min: 1000,
        dc_sheet_length_max: 5000,
        dc_sheet_width_min: 1000,
        dc_sheet_width_max: 5000,
        no_of_up_min: 5,
        no_of_up_max: 20
      });
      break;
    case 'large':
      Object.assign(form, {
        board_length_min: 5000,
        board_length_max: 10000,
        board_width_min: 5000,
        board_width_max: 10000,
        dc_sheet_length_min: 5000,
        dc_sheet_length_max: 10000,
        dc_sheet_width_min: 5000,
        dc_sheet_width_max: 10000,
        no_of_up_min: 10,
        no_of_up_max: 50
      });
      break;
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
};

// Save or update a formula
const saveFormula = async () => {
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
    
    if (editingId.value) {
      // Update existing formula
      await axios.put(`/api/diecut-computation-formulas/${editingId.value}`, formulaData);
    } else {
      // Create new formula
      await axios.post('/api/diecut-computation-formulas', formulaData);
    }
    
    // Refresh the data
    await fetchFormulas();
    
    // Reset the form
    resetForm();
    
    // Show success notification
  } catch (error) {
    console.error('Error saving formula:', error);
    // Show error notification
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
    await axios.delete(`/api/diecut-computation-formulas/${formulaToDelete.value.id}`);
    
    // Refresh the data
    await fetchFormulas();
    
    // Close the modal
    showDeleteModal.value = false;
    formulaToDelete.value = null;
    
    // Show success notification
  } catch (error) {
    console.error('Error deleting formula:', error);
    // Show error notification
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
/* Add any additional custom styles here */
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
  box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
}
</style>
