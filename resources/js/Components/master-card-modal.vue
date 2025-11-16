<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-2 sm:p-4 md:p-6">
    <!-- Background overlay -->
    <div class="fixed inset-0 bg-black bg-opacity-50" @click="$emit('close')"></div>
    <div class="bg-white rounded-lg shadow-lg w-full max-w-sm sm:max-w-xl md:max-w-3xl lg:w-3/4 xl:w-2/3 z-60 relative max-h-[95vh] flex flex-col mx-auto">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-3 sm:p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
        <h3 class="text-base sm:text-xl font-semibold flex items-center text-white">
          <i :class="[mode === 'add' ? 'fas fa-plus-circle' : 'fas fa-edit', 'mr-2 sm:mr-3']"></i>
          <span class="truncate">{{ mode === 'add' ? 'Add New Master Card' : 'Edit Master Card' }}</span>
        </h3>
        <button
          type="button"
          @click="$emit('close')"
          class="text-white hover:text-gray-200 focus:outline-none transform active:translate-y-px ml-2"
        >
          <i class="fas fa-times text-lg sm:text-xl"></i>
        </button>
      </div>

      <!-- Modal Body -->
      <div class="p-3 sm:p-4 md:p-5 flex-1 overflow-y-auto">
        <div v-if="loading" class="flex justify-center items-center p-4">
          <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
        </div>
        <div v-else>
          <form @submit.prevent="handleSubmit">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <!-- MC Sequence -->
              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1" for="mc_seq">MC Sequence:</label>
                <input 
                  type="text" 
                  id="mc_seq" 
                  v-model="formData.mc_seq" 
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  required
                >
              </div>
              
              <!-- MC Model -->
              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1" for="mc_model">MC Model:</label>
                <input 
                  type="text" 
                  id="mc_model" 
                  v-model="formData.mc_model" 
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  required
                >
              </div>
              
              <!-- Customer Code -->
              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1" for="customer_code">Customer Code:</label>
                <input 
                  type="text" 
                  id="customer_code" 
                  v-model="formData.customer_code" 
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
              </div>
              
              <!-- Customer Name -->
              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1" for="customer_name">Customer Name:</label>
                <input 
                  type="text" 
                  id="customer_name" 
                  v-model="formData.customer_name" 
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
              </div>
              
              <!-- Status -->
              <div class="mb-4 col-span-1 md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Status:</label>
                <div class="flex space-x-4">
                  <label class="inline-flex items-center px-3 py-2 rounded-md" :class="formData.status === 'pending' ? 'bg-yellow-50 border border-yellow-200' : ''">
                    <input 
                      type="radio" 
                      name="status" 
                      value="pending" 
                      v-model="formData.status"
                      class="form-radio h-4 w-4 text-yellow-600"
                    >
                    <span class="ml-2 text-sm" :class="formData.status === 'pending' ? 'text-yellow-800 font-medium' : 'text-gray-700'">Pending</span>
                  </label>
                  <label class="inline-flex items-center px-3 py-2 rounded-md" :class="formData.status === 'active' ? 'bg-green-50 border border-green-200' : ''">
                    <input 
                      type="radio" 
                      name="status" 
                      value="active" 
                      v-model="formData.status"
                      class="form-radio h-4 w-4 text-green-600"
                    >
                    <span class="ml-2 text-sm" :class="formData.status === 'active' ? 'text-green-800 font-medium' : 'text-gray-700'">Active</span>
                  </label>
                  <label class="inline-flex items-center px-3 py-2 rounded-md" :class="formData.status === 'obsolete' ? 'bg-red-50 border border-red-200' : ''">
                    <input 
                      type="radio" 
                      name="status" 
                      value="obsolete" 
                      v-model="formData.status"
                      class="form-radio h-4 w-4 text-red-600"
                    >
                    <span class="ml-2 text-sm" :class="formData.status === 'obsolete' ? 'text-red-800 font-medium' : 'text-gray-700'">Obsolete</span>
                  </label>
                </div>
              </div>
            </div>
            
            <!-- Error Message -->
            <div v-if="error" class="mt-4 p-2 bg-red-100 text-red-700 rounded-md">
              {{ error }}
            </div>

            <!-- Modal Footer -->
            <div class="mt-6 flex justify-end space-x-3">
              <button 
                type="button" 
                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400"
                @click="$emit('close')"
              >
                Cancel
              </button>
              <button 
                type="submit" 
                class="px-4 py-2 text-white rounded-md"
                :class="mode === 'add' ? 'bg-green-600 hover:bg-green-700' : 'bg-blue-600 hover:bg-blue-700'"
                :disabled="loading"
              >
                <span v-if="loading">Saving...</span>
                <span v-else>{{ mode === 'add' ? 'Add Master Card' : 'Save Changes' }}</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
  show: {
    type: Boolean,
    required: true
  },
  masterCard: {
    type: Object,
    default: null
  },
  mode: {
    type: String,
    default: 'edit',
    validator: (value) => ['add', 'edit'].includes(value)
  },
  apiEndpoint: {
    type: String,
    default: '/api/approve-mc'
  }
});

const emit = defineEmits(['close', 'update', 'master-card-added']);

const loading = ref(false);
const error = ref(null);
const formData = ref({
  mc_seq: '',
  mc_model: '',
  customer_code: '',
  customer_name: '',
  status: 'pending'
});

// Watch for changes in the masterCard prop
watch(() => props.masterCard, (newVal) => {
  if (newVal) {
    // Create a deep copy of the master card data
    formData.value = { 
      ...newVal,
      status: newVal.status || 'pending' // Ensure status is explicitly set
    };
    console.log('Form data initialized:', formData.value);
  } else {
    // Reset form if no master card is provided
    resetForm();
  }
}, { immediate: true });

// Reset form data to defaults
const resetForm = () => {
  formData.value = {
    mc_seq: '',
    mc_model: '',
    customer_code: '',
    customer_name: '',
    status: 'pending'
  };
  console.log('Form data reset to defaults');
};

// Debug helper
const debug = (message, data) => {
  console.log(`[MasterCardModal] ${message}:`, data);
};

// Watch status changes for debugging
watch(() => formData.value.status, (newVal, oldVal) => {
  if (newVal !== oldVal) {
    debug('Status changed', { from: oldVal, to: newVal });
  }
});

// Form validation 
const validateForm = () => {
  const errors = [];
  
  if (!formData.value.mc_seq) {
    errors.push('MC Sequence is required');
  }
  
  if (!formData.value.mc_model) {
    errors.push('MC Model is required');
  }
  
  if (!formData.value.customer_code) {
    errors.push('Customer Code is required');
  }
  
  if (!formData.value.customer_name) {
    errors.push('Customer Name is required');
  }
  
  if (!formData.value.status) {
    errors.push('Status is required');
  }
  
  return errors;
};

// Completely revamped form submission handler
const handleSubmit = async () => {
  debug('Handling form submission', { mode: props.mode });
  
  loading.value = true;
  error.value = null;
  
  try {
    // 1. Form validation
    const validationErrors = validateForm();
    if (validationErrors.length > 0) {
      error.value = validationErrors.join(', ');
      debug('Validation failed', validationErrors);
      return;
    }
    
    // 2. Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
      error.value = 'CSRF token not found. Please refresh the page.';
      debug('CSRF token missing');
      return;
    }
    
    // 3. Prepare request data
    const payload = {
      mc_seq: formData.value.mc_seq.trim(),
      mc_model: formData.value.mc_model.trim(),
      customer_code: formData.value.customer_code.trim(),
      customer_name: formData.value.customer_name.trim(),
      status: formData.value.status
    };
    
    debug('Prepared payload', payload);
    
    // 4. Determine API endpoint and method
    let url = props.apiEndpoint;
    let method = 'POST';
    
    if (props.mode === 'edit' && props.masterCard?.id) {
      url = `${props.apiEndpoint}/${props.masterCard.id}`;
      method = 'PUT';
      debug('Using update endpoint', { url, method });
    } else {
      debug('Using create endpoint', { url, method });
    }
    
    // 5. Make the API request
    const response = await fetch(url, {
      method: method,
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify(payload)
    });
    
    // 6. Parse the response
    let responseData;
    const contentType = response.headers.get('content-type');
    if (contentType && contentType.includes('application/json')) {
      responseData = await response.json();
      debug('Received JSON response', responseData);
    } else {
      const text = await response.text();
      debug('Received non-JSON response', text);
      throw new Error('Unexpected response format');
    }
    
    // 7. Handle the response
    if (!response.ok) {
      debug('API request failed', { status: response.status, statusText: response.statusText });
      
      if (responseData.errors) {
        const errorMessages = Object.values(responseData.errors).flat();
        error.value = errorMessages.join(', ');
      } else {
        error.value = responseData.message || `Server error: ${response.status} ${response.statusText}`;
      }
      return;
    }
    
    // 8. Success handling
    if (responseData.success) {
      debug('Operation successful', responseData.data);
      // Pass the data back to parent component
      if (props.mode === 'add') {
        emit('master-card-added', responseData.data);
      } else {
        emit('update', responseData.data);
      }
      emit('close');
    } else {
      // Success false but HTTP 200
      error.value = responseData.message || 'Unknown error occurred';
      debug('API returned success:false', responseData);
    }
    
  } catch (err) {
    console.error('Error in form submission:', err);
    debug('Exception thrown', err);
    error.value = 'An unexpected error occurred. Please try again.';
  } finally {
    loading.value = false;
  }
};
</script> 