<template>
  <AppLayout title="Define Corrugator">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Define Corrugator
      </h2>
    </template>

    <div class="py-6">
      <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
        <!-- Main Card -->
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg border border-gray-200">
          <!-- Header with buttons -->
          <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 flex items-center justify-between">
            <h2 class="text-lg font-bold text-white">Define Corrugator</h2>
            <div class="flex space-x-2">
              <button @click="resetForm" class="bg-blue-500 hover:bg-blue-400 text-white px-3 py-1 rounded text-sm flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                New
              </button>
              <button @click="saveSettings" class="bg-green-600 hover:bg-green-500 text-white px-3 py-1 rounded text-sm flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                  </svg>
                Save
              </button>
                <button 
                  @click="deleteConfig" 
                  :disabled="!formData.id"
                :class="{'opacity-50 cursor-not-allowed': !formData.id}"
                class="bg-red-600 hover:bg-red-500 text-white px-3 py-1 rounded text-sm flex items-center"
                >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                  </svg>
                Delete
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

            <div v-else>
              <!-- Corrugator Selection -->
              <div class="mb-6 flex items-center justify-between bg-gray-100 p-4 rounded-lg">
                <div class="flex items-center">
                  <span class="font-medium text-gray-700 mr-4">Min/Max Corrugator Out:</span>
                </div>
                <div class="flex items-center space-x-2">
                  <select 
                    v-model="formData.corrugator_id" 
                    @change="loadConfigById"
                    class="border border-gray-300 rounded px-3 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white"
                  >
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                  </select>
                  <span class="text-gray-500">to</span>
                  <select 
                    class="border border-gray-300 rounded px-3 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white"
                  >
                    <option value="9">9</option>
                  </select>
              </div>
            </div>

              <!-- Sheet Length Settings -->
              <div class="mb-6 flex items-center justify-between bg-gray-100 p-4 rounded-lg">
                <div class="flex items-center">
                  <span class="font-medium text-gray-700 mr-4">Min/Max Sheet Length:</span>
              </div>
                <div class="flex items-center space-x-2">
                      <input 
                        type="number" 
                        v-model="formData.min_sheet_length" 
                    class="border border-gray-300 rounded w-20 px-3 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  />
                  <span class="text-gray-500">to</span>
                      <input 
                        type="number" 
                        v-model="formData.max_sheet_length" 
                    class="border border-gray-300 rounded w-20 px-3 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                      />
                      </div>
                    </div>

              <!-- Sheet Width Settings -->
              <div class="mb-6 flex items-center justify-between bg-gray-100 p-4 rounded-lg">
                <div class="flex items-center">
                  <span class="font-medium text-gray-700 mr-4">Min/Max Sheet Width:</span>
                </div>
                <div class="flex items-center space-x-2">
                      <input 
                        type="number" 
                        v-model="formData.min_sheet_width" 
                    class="border border-gray-300 rounded w-20 px-3 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  />
                  <span class="text-gray-500">to</span>
                      <input 
                        type="number" 
                        v-model="formData.max_sheet_width" 
                    class="border border-gray-300 rounded w-20 px-3 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  />
              </div>
            </div>

              <!-- Sheet Length Multiplication Settings -->
              <div class="mb-6 bg-gray-50 border border-gray-200 rounded-lg overflow-hidden">
                <div class="bg-gray-100 px-4 py-2 border-b border-gray-200">
                  <h3 class="font-medium text-gray-700">Increase SL as Sheet Length to meet Min Sheet Length</h3>
              </div>
                <div class="p-4">
                  <div class="flex items-center">
                    <label class="inline-flex items-center mr-4">
                      <input 
                        type="checkbox" 
                        v-model="formData.is_sheet_length_multiplied" 
                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-offset-0 focus:ring-blue-200 focus:ring-opacity-50"
                      />
                      <span class="ml-2 text-gray-700">SL will be multiplied to meet min Board Length.</span>
                    </label>
                    <span class="text-gray-700">Then add</span>
                      <input 
                      type="number" 
                      v-model="slitterTrim"
                      class="mx-2 border border-gray-300 rounded w-16 px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                    <span class="text-gray-700">mm Slitter Trim when Scoring Length has been multiplied.</span>
                  </div>
                  </div>
                </div>

              <!-- Sheet Width Validation Settings -->
              <div class="mb-6 bg-gray-50 border border-gray-200 rounded-lg overflow-hidden">
                <div class="bg-gray-100 px-4 py-2 border-b border-gray-200">
                  <h3 class="font-medium text-gray-700">Increase SW as Sheet Width</h3>
                </div>
                <div class="p-4">
                  <div class="flex items-center">
                    <div class="flex items-center text-sm text-gray-600 italic bg-blue-50 p-2 rounded-md border-l-4 border-blue-400 w-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                      <span>Validate Sheet Width to meet Min/Max Sheet Width.</span>
                    </div>
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
    const formData = ref({
      id: null,
      corrugator_id: '1',
      min_sheet_length: 1,
      max_sheet_length: 9999,
      min_sheet_width: 1,
      max_sheet_width: 9999,
      is_sheet_length_multiplied: true,
      is_min_raw_multiplied: false,
      validate_sheet_width: false,
    });
    
    const defaultFormData = {
      id: null,
      corrugator_id: '1',
      min_sheet_length: 1,
      max_sheet_length: 9999,
      min_sheet_width: 1,
      max_sheet_width: 9999,
      is_sheet_length_multiplied: true,
      is_min_raw_multiplied: false,
      validate_sheet_width: false,
    };
    
    const notification = ref({
      show: false,
      message: '',
      type: 'success'
    });
    
    const allConfigs = ref([]);
    const loading = ref(false);
    const slitterTrim = ref(0);
    
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
    
    const loadConfigs = async () => {
      try {
        loading.value = true;
        const response = await axios.get('/api/corrugator-configs');
        allConfigs.value = response.data;
        
        // If we have data, load the first corrugator by default
        if (allConfigs.value.length > 0) {
          const config = allConfigs.value.find(c => c.corrugator_id === formData.value.corrugator_id);
          if (config) {
            formData.value = { ...config };
          } else {
            // If no config found for the current corrugator_id, load the first one
            formData.value = { ...allConfigs.value[0] };
          }
        } else {
          // If no data, initialize the form with default data
          resetForm();
          // Try to seed default data if no configurations exist
          await seedDefaultData();
        }
      } catch (error) {
        console.error('Error loading corrugator configs:', error);
        
        // More detailed error handling
        let errorMessage = 'Failed to load corrugator configurations';
        
        if (error.response) {
          // The request was made and the server responded with a status code
          // that falls out of the range of 2xx
          errorMessage += ` (Status: ${error.response.status})`;
          if (error.response.data && error.response.data.message) {
            errorMessage += `: ${error.response.data.message}`;
          }
        } else if (error.request) {
          // The request was made but no response was received
          errorMessage += ': No response received from server';
        } else {
          // Something happened in setting up the request that triggered an Error
          errorMessage += `: ${error.message}`;
        }
        
        showNotification(errorMessage, 'error');
        
        // Even if loading fails, try to initialize with default form data
        resetForm();
      } finally {
        loading.value = false;
      }
    };
    
    const loadConfigById = () => {
      const config = allConfigs.value.find(c => c.corrugator_id === formData.value.corrugator_id);
      if (config) {
        formData.value = { ...config };
      } else {
        // If no config found for this ID, reset form with just the corrugator_id
        const corrugatorId = formData.value.corrugator_id;
        resetForm();
        formData.value.corrugator_id = corrugatorId;
      }
    };
    
    const validateForm = () => {
      // Basic validation
      if (formData.value.min_sheet_length <= 0) {
        showNotification('Minimum sheet length must be greater than 0', 'error');
        return false;
      }
      
      if (formData.value.max_sheet_length <= formData.value.min_sheet_length) {
        showNotification('Maximum sheet length must be greater than minimum sheet length', 'error');
        return false;
      }
      
      if (formData.value.min_sheet_width <= 0) {
        showNotification('Minimum sheet width must be greater than 0', 'error');
        return false;
      }
      
      if (formData.value.max_sheet_width <= formData.value.min_sheet_width) {
        showNotification('Maximum sheet width must be greater than minimum sheet width', 'error');
        return false;
      }
      
      return true;
    };
    
    const saveSettings = async () => {
      if (!validateForm()) {
        return;
      }
      
      try {
        loading.value = true;
        let response;
        
        if (formData.value.id) {
          // Update existing
          response = await axios.put(`/api/corrugator-configs/${formData.value.id}`, formData.value);
          showNotification('Corrugator configuration updated successfully');
        } else {
          // Create new
          response = await axios.post('/api/corrugator-configs', formData.value);
          showNotification('Corrugator configuration created successfully');
        }
        
        // Refresh the list and update the form with the saved data
        await loadConfigs();
        formData.value = { ...response.data };
      } catch (error) {
        console.error('Error saving corrugator config:', error);
        if (error.response && error.response.data && error.response.data.errors) {
          // Show validation errors
          const errorMessages = Object.values(error.response.data.errors).flat();
          showNotification(errorMessages.join(', '), 'error');
        } else {
        showNotification('Failed to save corrugator configuration', 'error');
        }
      } finally {
        loading.value = false;
      }
    };
    
    const deleteConfig = async () => {
      if (!formData.value.id) {
        showNotification('No configuration selected to delete', 'error');
        return;
      }
      
      if (!confirm('Are you sure you want to delete this configuration?')) return;
      
      try {
        loading.value = true;
        await axios.delete(`/api/corrugator-configs/${formData.value.id}`);
        showNotification('Corrugator configuration deleted successfully');
        
        // Refresh the list
        await loadConfigs();
        resetForm();
      } catch (error) {
        console.error('Error deleting corrugator config:', error);
        showNotification('Failed to delete corrugator configuration', 'error');
      } finally {
        loading.value = false;
      }
    };
    
    const resetForm = () => {
      formData.value = { ...defaultFormData };
      slitterTrim.value = 0;
    };
    
    const seedDefaultData = async () => {
      loading.value = true;
      let errors = [];
      let successCount = 0;
      
      try {
        // Create configurations directly without trying the seeder API
        const defaultConfigs = [
          {
            corrugator_id: '1',
            min_sheet_length: 1,
            max_sheet_length: 9999,
            min_sheet_width: 1,
            max_sheet_width: 9999,
            is_sheet_length_multiplied: true,
            is_min_raw_multiplied: false,
            validate_sheet_width: false
          },
          {
            corrugator_id: '2',
            min_sheet_length: 1,
            max_sheet_length: 9999,
            min_sheet_width: 1,
            max_sheet_width: 9999,
            is_sheet_length_multiplied: true,
            is_min_raw_multiplied: false,
            validate_sheet_width: false
          },
          {
            corrugator_id: '3',
            min_sheet_length: 1,
            max_sheet_length: 9999,
            min_sheet_width: 1,
            max_sheet_width: 9999,
            is_sheet_length_multiplied: true,
            is_min_raw_multiplied: false,
            validate_sheet_width: false
          }
        ];
        
        // Try to create each configuration
        for (const config of defaultConfigs) {
          try {
            const response = await axios.post('/api/corrugator-configs', config);
            // Check response status and data
            if (response && (response.status === 200 || response.status === 201)) {
              successCount++;
              console.log(`Successfully created/loaded config for corrugator ${config.corrugator_id}`);
            } else {
              // Unexpected response status
              const errorMsg = `Unexpected response for corrugator ${config.corrugator_id}: Status ${response?.status || 'unknown'}`;
              console.error(errorMsg);
              errors.push(errorMsg);
            }
          } catch (configError) {
            console.error(`Error creating config for corrugator ${config.corrugator_id}:`, configError);
            
            let errorMessage = `Failed to create config for corrugator ${config.corrugator_id}`;
            if (configError.response) {
              // Server responded with error
              errorMessage += ` (Status: ${configError.response.status})`;
              if (configError.response.data) {
                if (configError.response.data.message) {
                  errorMessage += `: ${configError.response.data.message}`;
                } else if (configError.response.data.errors) {
                  const firstError = Object.values(configError.response.data.errors)[0];
                  errorMessage += `: ${Array.isArray(firstError) ? firstError[0] : firstError}`;
                }
              }
            } else if (configError.request) {
              // Request made but no response received
              errorMessage += ': No response received from server';
            } else {
              // Error in request setup
              errorMessage += `: ${configError.message}`;
            }
            
            errors.push(errorMessage);
          }
        }
        
        // Handle results
        if (successCount > 0) {
          showNotification(`Created or loaded ${successCount} default configurations successfully`);
        await loadConfigs();
          return true;
        } else if (errors.length > 0) {
          // Show the first error
          showNotification(`Failed to create configurations: ${errors[0]}`, 'error');
          throw new Error('Failed to create any configurations');
        } else {
          showNotification('No configurations were created', 'error');
          throw new Error('Failed to create any configurations');
        }
      } catch (error) {
        console.error('Error in seedDefaultData:', error);
        showNotification('Failed to create default configurations. Please try again.', 'error');
        return false;
      } finally {
        loading.value = false;
      }
    };
    
    onMounted(async () => {
      await loadConfigs();
    });

    return {
      formData,
      notification,
      loading,
      slitterTrim,
      saveSettings,
      resetForm,
      deleteConfig,
      loadConfigById,
    };
  },
});
</script>
