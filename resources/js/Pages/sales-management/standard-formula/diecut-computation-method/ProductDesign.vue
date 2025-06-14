<template>
  <AppLayout title="Define Product Design">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Define Product Design
      </h2>
    </template>

    <div class="py-4">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-4">
            <!-- Action Buttons -->
            <div class="flex space-x-2 mb-3">
              <button @click="addNew" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                <i class="fas fa-plus mr-1"></i> Add
              </button>
              <button @click="editSelected" :disabled="!selectedDesign" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm" :class="{'opacity-50': !selectedDesign}">
                <i class="fas fa-edit mr-1"></i> Edit
              </button>
              <button @click="confirmDelete" :disabled="!selectedDesign" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm" :class="{'opacity-50': !selectedDesign}">
                <i class="fas fa-trash mr-1"></i> Delete
              </button>
            </div>

            <!-- Main Content -->
            <div class="flex flex-col">
              <!-- Product Design Table -->
              <div class="border border-gray-300">
                <table class="min-w-full">
                  <thead>
                    <tr>
                      <th class="px-3 py-2 bg-blue-600 text-white text-left text-xs font-medium uppercase tracking-wider border-r border-blue-500">
                        Product Design
                      </th>
                      <th class="px-3 py-2 bg-blue-600 text-white text-left text-xs font-medium uppercase tracking-wider border-r border-blue-500">
                        Name
                      </th>
                      <th class="px-3 py-2 bg-blue-600 text-white text-left text-xs font-medium uppercase tracking-wider border-r border-blue-500">
                        Product
                      </th>
                      <th class="px-3 py-2 bg-blue-600 text-white text-left text-xs font-medium uppercase tracking-wider">
                        Compute
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white">
                    <tr v-for="design in filteredDesigns" :key="design.pd_code" 
                        class="hover:bg-gray-100 cursor-pointer border-b border-gray-300"
                        :class="{'bg-blue-100': selectedDesign && selectedDesign.pd_code === design.pd_code}"
                        @click="selectDesign(design)">
                      <td class="px-3 py-1 text-sm text-gray-900 border-r border-gray-300">
                        {{ design.pd_code }}
                      </td>
                      <td class="px-3 py-1 text-sm text-gray-900 border-r border-gray-300">
                        {{ design.pd_name }}
                      </td>
                      <td class="px-3 py-1 text-sm text-gray-900 border-r border-gray-300">
                        {{ design.product }}
                      </td>
                      <td class="px-3 py-1 text-sm text-gray-900">
                        {{ design.score === 'Yes' ? 'Yes' : 'No' }}
                      </td>
                    </tr>
                    <tr v-if="filteredDesigns.length === 0">
                      <td colspan="4" class="px-3 py-3 text-center text-sm text-gray-500">
                        No product designs found
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <!-- Form Section -->
              <div class="mt-4 border border-gray-300 p-3 bg-gray-100">
                <!-- Form Header -->
                <div class="mb-3 font-medium text-gray-700">
                  {{ editMode ? 'Edit Product Design: ' + form.pd_code : 'Create New Product Design' }}
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                  <!-- Product Design Code -->
                  <div class="flex items-center">
                    <label for="pd_code" class="block text-sm font-medium text-gray-700 w-32">Product Design:</label>
                    <input 
                      type="text" 
                      id="pd_code"
                      v-model="form.pd_code"
                      :disabled="editMode"
                      class="block w-full border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                      :class="{'bg-gray-100': editMode}"
                    />
                  </div>

                  <!-- Name -->
                  <div class="flex items-center">
                    <label for="pd_name" class="block text-sm font-medium text-gray-700 w-32">Name:</label>
                    <input 
                      type="text" 
                      id="pd_name"
                      v-model="form.pd_name"
                      class="block w-full border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    />
                  </div>

                  <!-- Product Selection -->
                  <div class="flex items-center">
                    <label for="product" class="block text-sm font-medium text-gray-700 w-32">Product:</label>
                    <input 
                      type="text" 
                      id="product"
                      v-model="form.product"
                      class="block w-full border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    />
                  </div>

                  <!-- Compute Flag -->
                  <div class="flex items-center">
                    <label for="score" class="block text-sm font-medium text-gray-700 w-32">Compute:</label>
                    <select
                      id="score"
                      v-model="form.score"
                      class="block w-full border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    >
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                    </select>
                  </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-3 flex justify-end space-x-2">
                  <button @click="resetForm" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-1 rounded text-sm">
                    Reset
                  </button>
                  <button v-if="!editMode" @click="createProductDesign" :disabled="loading" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-1 rounded text-sm">
                    Save
                  </button>
                  <button v-else @click="updateProductDesign" :disabled="loading" class="bg-green-500 hover:bg-green-600 text-white px-4 py-1 rounded text-sm">
                    Update
                  </button>
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
                <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Product Design</h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    Are you sure you want to delete this product design? This action cannot be undone.
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button @click="deleteDesign" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
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
const productDesigns = ref([]);
const selectedDesign = ref(null);
const editMode = ref(false);
const loading = ref(false);
const showDeleteModal = ref(false);
const searchQuery = ref('');
const errors = ref({});

// Form data
const form = reactive({
  pd_code: '',
  pd_name: '',
  pd_design_type: '',
  idc: '',
  product: '',
  joint: 'No',
  joint_to_print: 'No',
  pcs_to_joint: 'No',
  score: 'No',
  slot: 'No',
  flute_style: '',
  print_flute: '',
  input_weight: ''
});

// Original form data for comparison when updating
const originalForm = ref({});

// Fetch data on component mount
onMounted(async () => {
  await fetchProductDesigns();
});

// Fetch product designs from API
const fetchProductDesigns = async () => {
  try {
    loading.value = true;
    const response = await axios.get('/api/product-designs');
    productDesigns.value = response.data;
  } catch (error) {
    console.error('Error fetching product designs:', error);
  } finally {
    loading.value = false;
  }
};

// Filter designs based on search query
const filteredDesigns = computed(() => {
  if (!searchQuery.value) return productDesigns.value;
  
  const query = searchQuery.value.toLowerCase();
  return productDesigns.value.filter(design => {
    return design.pd_code.toLowerCase().includes(query) || 
           design.pd_name.toLowerCase().includes(query) ||
           design.product.toLowerCase().includes(query);
  });
});

// Reset form to default values
const resetForm = () => {
  Object.assign(form, {
    pd_code: '',
    pd_name: '',
    pd_design_type: '',
    idc: '',
    product: '',
    joint: 'No',
    joint_to_print: 'No',
    pcs_to_joint: 'No',
    score: 'No',
    slot: 'No',
    flute_style: '',
    print_flute: '',
    input_weight: ''
  });
  editMode.value = false;
  selectedDesign.value = null;
  originalForm.value = {};
  errors.value = {};
};

// Add new product design
const addNew = () => {
  resetForm();
  editMode.value = false;
};

// Select a design
const selectDesign = (design) => {
  // Set the selected design
  selectedDesign.value = design;
  
  // Reset edit mode when selecting a new design
  editMode.value = false;
  
  // Fill the form with the selected design data
  Object.assign(form, {
    pd_code: design.pd_code,
    pd_name: design.pd_name,
    pd_design_type: design.pd_design_type || '',
    idc: design.idc || '',
    product: design.product || '',
    joint: design.joint || 'No',
    joint_to_print: design.joint_to_print || 'No',
    pcs_to_joint: design.pcs_to_joint || 'No',
    score: design.score || 'No',
    slot: design.slot || 'No',
    flute_style: design.flute_style || '',
    print_flute: design.print_flute || '',
    input_weight: design.input_weight || ''
  });
  
  // Save original form data for comparison
  originalForm.value = { ...form };
  
  // Clear any previous errors
  errors.value = {};
};

// Edit a design
const editSelected = () => {
  if (!selectedDesign.value) return;
  
  // Make sure we're using the original data from the selected design
  Object.assign(form, {
    pd_code: selectedDesign.value.pd_code,
    pd_name: selectedDesign.value.pd_name,
    pd_design_type: selectedDesign.value.pd_design_type || '',
    idc: selectedDesign.value.idc || '',
    product: selectedDesign.value.product || '',
    joint: selectedDesign.value.joint || 'No',
    joint_to_print: selectedDesign.value.joint_to_print || 'No',
    pcs_to_joint: selectedDesign.value.pcs_to_joint || 'No',
    score: selectedDesign.value.score || 'No',
    slot: selectedDesign.value.slot || 'No',
    flute_style: selectedDesign.value.flute_style || '',
    print_flute: selectedDesign.value.print_flute || '',
    input_weight: selectedDesign.value.input_weight || ''
  });
  
  // Save original form data for comparison
  originalForm.value = { ...form };
  
  // Set edit mode
  editMode.value = true;
};

// Create a new product design
const createProductDesign = async () => {
  try {
    loading.value = true;
    errors.value = {};
    
    // Create new design
    await axios.post('/api/product-designs', form);
    
    // Show success message
    alert('Product design created successfully');
    
    // Refresh the data
    await fetchProductDesigns();
    
    // Reset the form
    resetForm();
    
  } catch (error) {
    console.error('Error creating product design:', error);
    handleApiError(error);
  } finally {
    loading.value = false;
  }
};

// Update an existing product design
const updateProductDesign = async () => {
  try {
    if (!selectedDesign.value) return;
    
    loading.value = true;
    errors.value = {};
    
    // Create an object with only the fields that have changed
    const updateData = {};
    
    // Only include fields that have changed
    if (form.pd_name !== originalForm.value.pd_name) updateData.pd_name = form.pd_name;
    if (form.pd_design_type !== originalForm.value.pd_design_type) updateData.pd_design_type = form.pd_design_type;
    if (form.idc !== originalForm.value.idc) updateData.idc = form.idc;
    if (form.product !== originalForm.value.product) updateData.product = form.product;
    if (form.joint !== originalForm.value.joint) updateData.joint = form.joint;
    if (form.joint_to_print !== originalForm.value.joint_to_print) updateData.joint_to_print = form.joint_to_print;
    if (form.pcs_to_joint !== originalForm.value.pcs_to_joint) updateData.pcs_to_joint = form.pcs_to_joint;
    if (form.score !== originalForm.value.score) updateData.score = form.score;
    if (form.slot !== originalForm.value.slot) updateData.slot = form.slot;
    if (form.flute_style !== originalForm.value.flute_style) updateData.flute_style = form.flute_style;
    if (form.print_flute !== originalForm.value.print_flute) updateData.print_flute = form.print_flute;
    if (form.input_weight !== originalForm.value.input_weight) updateData.input_weight = form.input_weight;
    
    // If nothing has changed, show a message and return
    if (Object.keys(updateData).length === 0) {
      alert('No changes detected');
      loading.value = false;
      return;
    }
    
    // Add required fields if they're not included (API validation requires them)
    if (!updateData.pd_name) updateData.pd_name = form.pd_name;
    if (!updateData.pd_design_type) updateData.pd_design_type = form.pd_design_type;
    if (!updateData.product) updateData.product = form.product;
    
    // Update the product design
    await axios.put(`/api/product-designs/${selectedDesign.value.pd_code}`, updateData);
    
    // Show success message
    alert('Product design updated successfully');
    
    // Refresh the data
    await fetchProductDesigns();
    
    // Reset the form
    resetForm();
    
  } catch (error) {
    console.error('Error updating product design:', error);
    handleApiError(error);
  } finally {
    loading.value = false;
  }
};

// Handle API errors
const handleApiError = (error) => {
  if (error.response && error.response.data && error.response.data.message) {
    alert(error.response.data.message);
  } else if (error.response && error.response.data && error.response.data.errors) {
    const errorMessages = [];
    for (const field in error.response.data.errors) {
      errorMessages.push(error.response.data.errors[field][0]);
    }
    alert('Validation error: ' + errorMessages.join(', '));
    errors.value = error.response.data.errors;
  } else {
    alert('An error occurred while saving the product design');
  }
};

// Confirm deletion of a design
const confirmDelete = () => {
  if (!selectedDesign.value) return;
  showDeleteModal.value = true;
};

// Delete a design
const deleteDesign = async () => {
  if (!selectedDesign.value) return;
  
  try {
    loading.value = true;
    await axios.delete(`/api/product-designs/${selectedDesign.value.pd_code}`);
    
    // Refresh the data
    await fetchProductDesigns();
    
    // Close the modal
    showDeleteModal.value = false;
    
    // Reset the form
    resetForm();
    
  } catch (error) {
    console.error('Error deleting product design:', error);
    if (error.response && error.response.data && error.response.data.message) {
      alert(error.response.data.message);
    }
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
/* Smooth transitions */
.transition-all {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 150ms;
}

/* Input focus effects */
input:focus, select:focus {
  box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
}

/* Table styling to look more like desktop app */
table {
  border-collapse: collapse;
  width: 100%;
}

td, th {
  padding: 4px 8px;
}

/* Custom checkbox styling */
input[type="checkbox"] {
  cursor: pointer;
}
</style>
