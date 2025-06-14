<template>
  <AppLayout title="Define Bundling Computation Method">
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Define Bundling Computation Method
        </h2>
        <div class="flex space-x-2">
          <button @click="resetForm" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md transition duration-200 flex items-center">
            <i class="fas fa-redo-alt mr-2"></i> Reset
          </button>
          <button @click="saveMethod" :disabled="loading" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-200 flex items-center">
            <i class="fas fa-save mr-2"></i> {{ editingId ? 'Update' : 'Save' }}
          </button>
        </div>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <!-- Main Content -->
          <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
              <!-- Left Panel: Data Table -->
              <div class="lg:col-span-2 bg-white rounded-lg shadow">
                <div class="p-4 border-b border-gray-200">
                  <h3 class="text-lg font-medium text-gray-700">Computation Methods</h3>
                </div>
                <div class="overflow-x-auto">
                  <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                      <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Design</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Flute</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pieces Per Bundle</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Compute</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                      </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                      <tr v-for="method in computationMethods" :key="method.id" :class="{'bg-blue-50': selectedMethod && selectedMethod.id === method.id}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ method.product_design || 'APFI' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ method.product || 'BKS' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ method.flute || 'AB' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ method.formula_pieces }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                          <span :class="method.is_compute ? 'text-green-600' : 'text-red-600'">
                            {{ method.is_compute ? 'Yes' : 'No' }}
                          </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                          <button @click="editMethod(method)" class="text-indigo-600 hover:text-indigo-900 mr-3">
                            <i class="fas fa-edit"></i>
                          </button>
                          <button @click="confirmDelete(method)" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                          </button>
                        </td>
                      </tr>
                      <tr v-if="computationMethods.length === 0">
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No computation methods found</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- Right Panel: Form -->
              <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-700 mb-4">{{ editingId ? 'Edit' : 'Add' }} Computation Method</h3>
                
                <div class="space-y-4">
                  <!-- Product Name -->
                  <div>
                    <label for="product_name" class="block text-sm font-medium text-gray-700">Product Name:</label>
                    <input 
                      type="text" 
                      id="product_name" 
                      v-model="form.product_name"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                      placeholder="PENJUALAN LAIN LAIN PCS"
                    />
                  </div>

                  <!-- Product Design Name -->
                  <div>
                    <label for="product_design" class="block text-sm font-medium text-gray-700">P/Design Name:</label>
                    <input 
                      type="text" 
                      id="product_design" 
                      v-model="form.product_design"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                      placeholder="APFI"
                    />
                  </div>

                  <!-- Pieces Per Bundle -->
                  <div>
                    <label for="formula_pieces" class="block text-sm font-medium text-gray-700">Pieces Per Bundle:</label>
                    <input 
                      type="number" 
                      id="formula_pieces" 
                      v-model.number="form.formula_pieces"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                      min="0"
                    />
                  </div>

                  <!-- To Compute -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700">To Compute:</label>
                    <div class="mt-2 flex items-center space-x-4">
                      <label class="inline-flex items-center">
                        <input type="radio" v-model="form.is_compute" :value="true" class="form-radio h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500" />
                        <span class="ml-2 text-sm text-gray-700">Yes</span>
                      </label>
                      <label class="inline-flex items-center">
                        <input type="radio" v-model="form.is_compute" :value="false" class="form-radio h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500" />
                        <span class="ml-2 text-sm text-gray-700">No</span>
                      </label>
                    </div>
                  </div>

                  <!-- Formula Divisor (Hidden in UI but used in backend) -->
                  <input type="hidden" v-model.number="form.formula_divisor" />
                  
                  <!-- Height Type (Hidden in UI but used in backend) -->
                  <input type="hidden" v-model="form.height_type" />
                  
                  <!-- Rounding Type (Hidden in UI but used in backend) -->
                  <input type="hidden" v-model="form.rounding_type" />
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
                <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Computation Method</h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    Are you sure you want to delete this computation method? This action cannot be undone.
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button @click="deleteMethod" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
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
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

// State
const computationMethods = ref([]);
const selectedMethod = ref(null);
const editingId = ref(null);
const loading = ref(false);
const showDeleteModal = ref(false);
const methodToDelete = ref(null);

// Form data
const form = reactive({
  product_name: '',
  product_design: '',
  formula_divisor: 1,
  formula_pieces: 0,
  height_type: 'internal',
  rounding_type: 'down',
  is_compute: false,
  product: '',
  flute: ''
});

// Fetch computation methods on component mount
onMounted(async () => {
  await fetchComputationMethods();
});

// Fetch computation methods from API
const fetchComputationMethods = async () => {
  try {
    loading.value = true;
    const response = await axios.get('/api/bundling-computation-methods');
    computationMethods.value = response.data.data;
  } catch (error) {
    console.error('Error fetching computation methods:', error);
    // Show error notification
  } finally {
    loading.value = false;
  }
};

// Reset form to default values
const resetForm = () => {
  Object.assign(form, {
    product_name: '',
    product_design: '',
    formula_divisor: 1,
    formula_pieces: 0,
    height_type: 'internal',
    rounding_type: 'down',
    is_compute: false,
    product: '',
    flute: ''
  });
  editingId.value = null;
  selectedMethod.value = null;
};

// Edit a computation method
const editMethod = (method) => {
  editingId.value = method.id;
  selectedMethod.value = method;
  
  Object.assign(form, {
    product_name: method.product_name || '',
    product_design: method.product_design || '',
    formula_divisor: method.formula_divisor || 1,
    formula_pieces: method.formula_pieces || 0,
    height_type: method.height_type || 'internal',
    rounding_type: method.rounding_type || 'down',
    is_compute: method.is_compute || false,
    product: method.product || '',
    flute: method.flute || ''
  });
};

// Save or update a computation method
const saveMethod = async () => {
  try {
    loading.value = true;
    
    const methodData = {
      product_name: form.product_name,
      product_design: form.product_design,
      formula_divisor: form.formula_divisor,
      formula_pieces: form.formula_pieces,
      height_type: form.height_type,
      rounding_type: form.rounding_type,
      is_compute: form.is_compute,
      product: form.product,
      flute: form.flute
    };
    
    if (editingId.value) {
      // Update existing method
      await axios.put(`/api/bundling-computation-methods/${editingId.value}`, methodData);
    } else {
      // Create new method
      await axios.post('/api/bundling-computation-methods', methodData);
    }
    
    // Refresh the data
    await fetchComputationMethods();
    
    // Reset the form
    resetForm();
    
    // Show success notification
  } catch (error) {
    console.error('Error saving computation method:', error);
    // Show error notification
  } finally {
    loading.value = false;
  }
};

// Confirm deletion of a computation method
const confirmDelete = (method) => {
  methodToDelete.value = method;
  showDeleteModal.value = true;
};

// Delete a computation method
const deleteMethod = async () => {
  if (!methodToDelete.value) return;
  
  try {
    loading.value = true;
    await axios.delete(`/api/bundling-computation-methods/${methodToDelete.value.id}`);
    
    // Refresh the data
    await fetchComputationMethods();
    
    // Close the modal
    showDeleteModal.value = false;
    methodToDelete.value = null;
    
    // Show success notification
  } catch (error) {
    console.error('Error deleting computation method:', error);
    // Show error notification
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
/* Add any additional custom styles here */
.form-radio {
  appearance: none;
  -webkit-appearance: none;
  border-radius: 50%;
  width: 1rem;
  height: 1rem;
  border: 2px solid #d1d5db;
  outline: none;
  transition: all 0.2s ease-in-out;
}

.form-radio:checked {
  background-color: #2563eb;
  border-color: #2563eb;
  background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3ccircle cx='8' cy='8' r='4'/%3e%3c/svg%3e");
  background-size: 100% 100%;
  background-position: center;
  background-repeat: no-repeat;
}

/* Animate table row hover */
tbody tr {
  transition: all 0.2s ease-in-out;
}

tbody tr:hover {
  background-color: rgba(243, 244, 246, 0.7);
}
</style>
