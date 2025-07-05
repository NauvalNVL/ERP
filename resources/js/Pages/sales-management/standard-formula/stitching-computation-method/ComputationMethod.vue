<template>
  <AppLayout title="Define Computation Method">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Define Computation Method
      </h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
          <!-- Header with buttons -->
          <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 flex items-center justify-between">
            <h2 class="text-lg font-bold text-white">Define Computation Method</h2>
            <div class="flex space-x-2">
              <button @click="saveMethod" :disabled="loading" class="bg-green-600 hover:bg-green-500 text-white px-3 py-1 rounded text-sm flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                Save
              </button>
              <button @click="resetForm" :disabled="loading" class="bg-red-600 hover:bg-red-500 text-white px-3 py-1 rounded text-sm flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                </svg>
                Reset
              </button>
            </div>
          </div>

          <!-- Main content -->
          <div class="p-6">
            <!-- Loading Spinner -->
            <div v-if="loading" class="flex justify-center items-center py-8">
              <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
              <span class="ml-3 text-gray-600">Loading...</span>
            </div>

            <div v-else class="bg-gray-50 p-6 rounded-lg border border-gray-200">
              <!-- Formula Section -->
              <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Formula:</label>
                <div class="flex items-center">
                  <span class="mr-2">Height divided by</span>
                  <div class="relative w-16">
                    <input
                      type="number"
                      v-model="method.formula_divisor"
                      min="0"
                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    />
                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                      <span class="text-gray-500 sm:text-sm">mm</span>
                    </div>
                  </div>
                  <span class="mx-2">+</span>
                  <div class="relative w-16">
                    <input
                      type="number"
                      v-model="method.formula_pieces"
                      min="0"
                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    />
                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                      <span class="text-gray-500 sm:text-sm">pieces</span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Height Section -->
              <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Height:</label>
                <div class="flex items-center space-x-6">
                  <div class="flex items-center">
                    <input
                      id="height-internal"
                      type="radio"
                      v-model="method.height_type"
                      value="internal"
                      class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-500"
                    />
                    <label for="height-internal" class="ml-2 block text-sm text-gray-700">Internal</label>
                  </div>
                  <div class="flex items-center">
                    <input
                      id="height-extended"
                      type="radio"
                      v-model="method.height_type"
                      value="extended"
                      class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-500"
                    />
                    <label for="height-extended" class="ml-2 block text-sm text-gray-700">Extended</label>
                  </div>
                </div>
              </div>

              <!-- Rounding Section -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Rounding:</label>
                <div class="flex items-center space-x-6">
                  <div class="flex items-center">
                    <input
                      id="rounding-up"
                      type="radio"
                      v-model="method.rounding_type"
                      value="up"
                      class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-500"
                    />
                    <label for="rounding-up" class="ml-2 block text-sm text-gray-700">U-Up</label>
                  </div>
                  <div class="flex items-center">
                    <input
                      id="rounding-down"
                      type="radio"
                      v-model="method.rounding_type"
                      value="down"
                      class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-500"
                    />
                    <label for="rounding-down" class="ml-2 block text-sm text-gray-700">D-Down</label>
                  </div>
                </div>
              </div>
            </div>

            <!-- Notification -->
            <transition 
              enter-active-class="transform ease-out duration-300 transition"
              enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
              enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
              leave-active-class="transition ease-in duration-200"
              leave-from-class="opacity-100"
              leave-to-class="opacity-0"
            >
              <div 
                v-if="notification.show" 
                class="fixed bottom-4 right-4 w-80 p-4 rounded-lg shadow-lg border border-l-4"
                :class="notification.type === 'success' ? 'bg-green-50 border-green-500 text-green-800' : 'bg-red-50 border-red-500 text-red-800'"
              >
                <div class="flex items-center">
                  <div class="flex-shrink-0">
                    <svg v-if="notification.type === 'success'" class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <svg v-else class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                  </div>
                  <div class="ml-3">
                    <p class="text-sm">{{ notification.message }}</p>
                  </div>
                </div>
              </div>
            </transition>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import { defineComponent, ref, onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

export default defineComponent({
  components: {
    AppLayout,
  },
  setup() {
    const loading = ref(true);
    const method = ref({
      formula_divisor: 1,
      formula_pieces: 0,
      height_type: 'internal',
      rounding_type: 'down',
      is_active: true
    });
    const notification = ref({
      show: false,
      message: '',
      type: 'success'
    });
    
    const showNotification = (message, type = 'success') => {
      notification.value = {
        show: true,
        message,
        type
      };
      
      setTimeout(() => {
        notification.value.show = false;
      }, 3000);
    };

    const loadData = async () => {
      try {
        loading.value = true;
        
        // Load computation method data
        const response = await axios.get('/api/computation-methods');
        
        if (response.data && response.data.status === 'success' && response.data.data.length > 0) {
          method.value = response.data.data[0]; // Get the first method (should be only one)
        }
      } catch (error) {
        console.error('Error loading data:', error);
        
        // More detailed error handling
        let errorMessage = 'Failed to load computation method data';
        
        if (error.response) {
          errorMessage += ` (Status: ${error.response.status})`;
          if (error.response.data && error.response.data.message) {
            errorMessage += `: ${error.response.data.message}`;
          }
        } else if (error.request) {
          errorMessage += ': No response received from server';
        } else {
          errorMessage += `: ${error.message}`;
        }
        
        showNotification(errorMessage, 'error');
      } finally {
        loading.value = false;
      }
    };

    const saveMethod = async () => {
      try {
        loading.value = true;
        
        // Validate inputs
        if (method.value.formula_divisor < 0 || method.value.formula_pieces < 0) {
          showNotification('Formula values must be non-negative numbers', 'error');
          loading.value = false;
          return;
        }
        
        // Save or update the computation method
        let response;
        
        if (method.value.id) {
          // Update existing method
          response = await axios.put(`/api/computation-methods/${method.value.id}`, method.value);
        } else {
          // Create new method
          response = await axios.post('/api/computation-methods', method.value);
        }
        
        if (response.data && response.data.status === 'success') {
          showNotification(response.data.message || 'Computation method saved successfully');
          
          // Update the method with the returned data
          method.value = response.data.data;
        } else {
          showNotification('Failed to save computation method', 'error');
        }
      } catch (error) {
        console.error('Error saving data:', error);
        
        // More detailed error handling
        let errorMessage = 'Failed to save computation method';
        
        if (error.response) {
          errorMessage += ` (Status: ${error.response.status})`;
          if (error.response.data && error.response.data.message) {
            errorMessage += `: ${error.response.data.message}`;
          }
        } else if (error.request) {
          errorMessage += ': No response received from server';
        } else {
          errorMessage += `: ${error.message}`;
        }
        
        showNotification(errorMessage, 'error');
      } finally {
        loading.value = false;
      }
    };

    const resetForm = () => {
      method.value = {
        formula_divisor: 1,
        formula_pieces: 0,
        height_type: 'internal',
        rounding_type: 'down',
        is_active: true
      };
      
      showNotification('Form has been reset');
    };

    onMounted(() => {
      loadData();
    });

    return {
      loading,
      method,
      notification,
      saveMethod,
      resetForm,
      showNotification
    };
  }
});
</script>
