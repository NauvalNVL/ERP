<template>
  <AppLayout :header="'Define Tax Type'">
    <div class="py-6 px-4 sm:px-6 lg:px-8">
      <div class="max-w-7xl mx-auto">
        <!-- Header Card -->
        <div class="bg-white shadow-lg sm:rounded-lg overflow-hidden">
          <div class="bg-blue-600 px-4 py-4 sm:px-6 flex justify-between items-center">
            <h2 class="text-xl font-bold text-white">Define Tax Type</h2>
            <div class="flex space-x-2">
              <button 
                @click="openAddModal" 
                class="bg-white hover:bg-gray-100 text-blue-700 font-semibold py-2 px-4 border border-blue-500 rounded shadow-sm flex items-center"
              >
                <i class="fas fa-plus-circle mr-2"></i>
                Add New
              </button>
              <button 
                @click="refreshData" 
                class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded shadow-sm flex items-center"
              >
                <i class="fas fa-sync-alt mr-2"></i>
                Refresh
              </button>
            </div>
          </div>
          
          <!-- Loading State -->
          <div v-if="loading" class="p-8 flex justify-center items-center">
            <div class="flex flex-col items-center">
              <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
              <p class="mt-3 text-gray-600">Loading tax types...</p>
            </div>
          </div>
          
          <!-- Search and Table Container -->
          <div v-else class="p-6">
            <!-- Search Bar -->
            <div class="mb-6">
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <i class="fas fa-search text-gray-400"></i>
                </div>
                <input
                  v-model="searchTerm"
                  type="text"
                  class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="Search by code or name..."
                />
              </div>
            </div>

            <!-- Tax Types Table -->
            <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Tax Code
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Tax Name
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Tax Applied
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Tax Rate (%)
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Actions
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-if="filteredTaxTypes.length === 0">
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                      No tax types found
                    </td>
                  </tr>
                  <tr v-for="taxType in filteredTaxTypes" :key="taxType.id" class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                      {{ taxType.code }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ taxType.name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      <span 
                        :class="[
                          'px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full',
                          taxType.is_applied ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                        ]"
                      >
                        {{ taxType.is_applied ? 'Yes' : 'No' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right pr-10">
                      {{ taxType.rate.toFixed(2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                      <button 
                        @click="editTaxType(taxType)" 
                        class="text-indigo-600 hover:text-indigo-900 mr-3"
                      >
                        <i class="fas fa-edit"></i>
                      </button>
                      <button 
                        @click="confirmDelete(taxType)" 
                        class="text-red-600 hover:text-red-900"
                      >
                        <i class="fas fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          
          <!-- Error Messages -->
          <div v-if="errorMessage" class="bg-red-50 border-l-4 border-red-400 p-4 mb-4">
            <div class="flex">
              <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-400"></i>
              </div>
              <div class="ml-3">
                <p class="text-sm text-red-700">{{ errorMessage }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Add/Edit Modal -->
    <TransitionRoot appear :show="isModalOpen" as="template">
      <Dialog as="div" @close="closeModal" class="relative z-10">
        <TransitionChild
          as="template"
          enter="duration-300 ease-out"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-black bg-opacity-25" />
        </TransitionChild>

        <div class="fixed inset-0 overflow-y-auto">
          <div class="flex min-h-full items-center justify-center p-4 text-center">
            <TransitionChild
              as="template"
              enter="duration-300 ease-out"
              enter-from="opacity-0 scale-95"
              enter-to="opacity-100 scale-100"
              leave="duration-200 ease-in"
              leave-from="opacity-100 scale-100"
              leave-to="opacity-0 scale-95"
            >
              <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 pb-2 border-b">
                  {{ isEditing ? 'Edit Tax Type' : 'Add New Tax Type' }}
                </DialogTitle>

                <div class="mt-4">
                  <div class="space-y-4">
                    <div>
                      <label for="code" class="block text-sm font-medium text-gray-700">
                        Tax Code:
                      </label>
                      <input
                        id="code"
                        v-model="form.code"
                        type="text"
                        :disabled="isEditing"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        maxlength="6"
                      />
                      <p v-if="formErrors.code" class="mt-1 text-sm text-red-600">{{ formErrors.code }}</p>
                    </div>
                    
                    <div>
                      <label for="name" class="block text-sm font-medium text-gray-700">
                        Tax Name:
                      </label>
                      <input
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                      />
                      <p v-if="formErrors.name" class="mt-1 text-sm text-red-600">{{ formErrors.name }}</p>
                    </div>
                    
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Tax Applied:</label>
                      <div class="mt-2 space-x-4">
                        <label class="inline-flex items-center">
                          <input
                            v-model="form.is_applied"
                            type="radio"
                            :value="true"
                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300"
                          />
                          <span class="ml-2 text-gray-700">Y-Yes</span>
                        </label>
                        <label class="inline-flex items-center">
                          <input
                            v-model="form.is_applied"
                            type="radio"
                            :value="false"
                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300"
                          />
                          <span class="ml-2 text-gray-700">N-No</span>
                        </label>
                      </div>
                      <p v-if="formErrors.is_applied" class="mt-1 text-sm text-red-600">{{ formErrors.is_applied }}</p>
                    </div>
                    
                    <div>
                      <label for="rate" class="block text-sm font-medium text-gray-700">
                        Tax %:
                      </label>
                      <div class="mt-1 relative rounded-md shadow-sm">
                        <input
                          id="rate"
                          v-model="form.rate"
                          type="number"
                          step="0.01"
                          min="0"
                          class="block w-full pr-12 border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                          placeholder="0.00"
                          :disabled="!form.is_applied"
                        />
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                          <span class="text-gray-500 sm:text-sm">%</span>
                        </div>
                      </div>
                      <p v-if="formErrors.rate" class="mt-1 text-sm text-red-600">{{ formErrors.rate }}</p>
                    </div>
                  </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                  <button
                    type="button"
                    @click="closeModal"
                    class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                  >
                    Cancel
                  </button>
                  <button
                    type="button"
                    @click="saveTaxType"
                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                  >
                    {{ isEditing ? 'Update' : 'Save' }}
                  </button>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
    
    <!-- Confirmation Dialog -->
    <TransitionRoot appear :show="isConfirmDialogOpen" as="template">
      <Dialog as="div" @close="closeConfirmDialog" class="relative z-10">
        <TransitionChild
          as="template"
          enter="duration-300 ease-out"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-black bg-opacity-25" />
        </TransitionChild>

        <div class="fixed inset-0 overflow-y-auto">
          <div class="flex min-h-full items-center justify-center p-4 text-center">
            <TransitionChild
              as="template"
              enter="duration-300 ease-out"
              enter-from="opacity-0 scale-95"
              enter-to="opacity-100 scale-100"
              leave="duration-200 ease-in"
              leave-from="opacity-100 scale-100"
              leave-to="opacity-0 scale-95"
            >
              <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                  Delete Tax Type
                </DialogTitle>
                
                <div class="mt-3">
                  <p class="text-sm text-gray-600">
                    Are you sure you want to delete the tax type <span class="font-bold">{{ selectedType?.code }}</span>? This action cannot be undone.
                  </p>
                </div>
                
                <div class="mt-6 flex justify-end space-x-3">
                  <button
                    type="button"
                    @click="closeConfirmDialog"
                    class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                  >
                    Cancel
                  </button>
                  <button
                    type="button"
                    @click="deleteTaxType"
                    class="inline-flex justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
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

    <!-- Toast Notification -->
    <div 
      v-if="notification.show" 
      :class="[
        'fixed bottom-4 right-4 z-50 rounded-md shadow-lg max-w-sm w-full transition-all transform',
        notification.type === 'success' ? 'bg-green-50' : 'bg-red-50'
      ]"
    >
      <div class="p-4">
        <div class="flex items-start">
          <div class="flex-shrink-0">
            <i 
              :class="[
                'text-xl',
                notification.type === 'success' ? 'text-green-500 fas fa-check-circle' : 'text-red-500 fas fa-exclamation-circle'
              ]"
            ></i>
          </div>
          <div class="ml-3 w-0 flex-1">
            <p 
              :class="[
                'text-sm font-medium',
                notification.type === 'success' ? 'text-green-800' : 'text-red-800'
              ]"
            >
              {{ notification.message }}
            </p>
          </div>
          <div class="ml-4 flex-shrink-0 flex">
            <button
              @click="closeNotification"
              class="inline-flex text-gray-400 focus:outline-none focus:text-gray-500 transition ease-in-out duration-150"
            >
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useToast } from '@/Composables/useToast';

// State management
const taxTypes = ref([]);
const searchTerm = ref('');
const loading = ref(true);
const errorMessage = ref('');
const isModalOpen = ref(false);
const isConfirmDialogOpen = ref(false);
const isEditing = ref(false);
const selectedType = ref(null);
const { showToast } = useToast();
const notification = ref({ show: false, message: '', type: 'success' });

// Form state
const form = ref({
  code: '',
  name: '',
  is_applied: false,
  rate: 0.00
});
const formErrors = ref({});

// Watch for is_applied changes to reset rate when needed
watch(() => form.value.is_applied, (newValue) => {
  if (!newValue) {
    form.value.rate = 0.00;
  }
});

// Filtered tax types based on search term
const filteredTaxTypes = computed(() => {
  if (!searchTerm.value) {
    return taxTypes.value;
  }
  
  const term = searchTerm.value.toLowerCase();
  return taxTypes.value.filter(type => {
    return type.code.toLowerCase().includes(term) || 
           type.name.toLowerCase().includes(term);
  });
});

// Fetch tax types
const fetchTaxTypes = async () => {
  loading.value = true;
  errorMessage.value = '';
  
  try {
    const response = await fetch('/api/material-management/tax-types');
    
    if (!response.ok) {
      throw new Error('Failed to fetch tax types');
    }
    
    const data = await response.json();
    taxTypes.value = data;
  } catch (error) {
    console.error('Error fetching tax types:', error);
    errorMessage.value = `Error: ${error.message}`;
    showNotification('Failed to load tax types. Please try again.', 'error');
  } finally {
    loading.value = false;
  }
};

// Open modal for adding a new tax type
const openAddModal = () => {
  isEditing.value = false;
  resetForm();
  isModalOpen.value = true;
};

// Open modal for editing a tax type
const editTaxType = (taxType) => {
  isEditing.value = true;
  selectedType.value = taxType;
  
  form.value = {
    code: taxType.code,
    name: taxType.name,
    is_applied: taxType.is_applied,
    rate: taxType.rate
  };
  
  isModalOpen.value = true;
};

// Close modal and reset form
const closeModal = () => {
  isModalOpen.value = false;
  resetForm();
};

// Reset form values and errors
const resetForm = () => {
  form.value = {
    code: '',
    name: '',
    is_applied: false,
    rate: 0.00
  };
  formErrors.value = {};
};

// Validate form inputs
const validateForm = () => {
  const errors = {};
  
  if (!form.value.code) {
    errors.code = 'Tax code is required';
  } else if (form.value.code.length > 6) {
    errors.code = 'Tax code cannot exceed 6 characters';
  }
  
  if (!form.value.name) {
    errors.name = 'Tax name is required';
  }
  
  if (form.value.is_applied === undefined) {
    errors.is_applied = 'Please select whether tax is applied';
  }
  
  if (form.value.is_applied && (form.value.rate === null || form.value.rate === undefined)) {
    errors.rate = 'Tax rate is required when tax is applied';
  }
  
  formErrors.value = errors;
  return Object.keys(errors).length === 0;
};

// Save tax type (create or update)
const saveTaxType = async () => {
  if (!validateForm()) {
    return;
  }
  
  try {
    let response;
    
    if (isEditing.value) {
      response = await fetch(`/api/material-management/tax-types/${form.value.code}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(form.value)
      });
    } else {
      // Check if code already exists
      const exists = taxTypes.value.some(type => type.code === form.value.code);
      
      if (exists) {
        formErrors.value.code = 'Tax code already exists';
        return;
      }
      
      response = await fetch('/api/material-management/tax-types', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(form.value)
      });
    }
    
    if (!response.ok) {
      const errorData = await response.json();
      throw new Error(errorData.message || 'Failed to save tax type');
    }
    
    closeModal();
    fetchTaxTypes();
    showNotification(
      isEditing.value ? 'Tax type updated successfully!' : 'Tax type created successfully!',
      'success'
    );
  } catch (error) {
    console.error('Error saving tax type:', error);
    showNotification(`Failed to save: ${error.message}`, 'error');
  }
};

// Confirm delete
const confirmDelete = (taxType) => {
  selectedType.value = taxType;
  isConfirmDialogOpen.value = true;
};

// Close confirmation dialog
const closeConfirmDialog = () => {
  isConfirmDialogOpen.value = false;
  selectedType.value = null;
};

// Delete tax type
const deleteTaxType = async () => {
  if (!selectedType.value) {
    return;
  }
  
  try {
    const response = await fetch(`/api/material-management/tax-types/${selectedType.value.code}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    });
    
    if (!response.ok) {
      const errorData = await response.json();
      throw new Error(errorData.message || 'Failed to delete tax type');
    }
    
    closeConfirmDialog();
    fetchTaxTypes();
    showNotification('Tax type deleted successfully!', 'success');
  } catch (error) {
    console.error('Error deleting tax type:', error);
    showNotification(`Failed to delete: ${error.message}`, 'error');
  }
};

// Refresh data
const refreshData = () => {
  fetchTaxTypes();
};

// Show notification
const showNotification = (message, type = 'success') => {
  notification.value = {
    show: true,
    message,
    type
  };
  
  // Hide notification after 3 seconds
  setTimeout(() => {
    closeNotification();
  }, 3000);
};

// Close notification
const closeNotification = () => {
  notification.value.show = false;
};

// Fetch data on component mount
onMounted(() => {
  fetchTaxTypes();
});
</script>

<style scoped>
/* You can add component-specific styles here */
</style>
