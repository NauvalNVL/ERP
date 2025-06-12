<template>
  <AppLayout title="Define Corrugator">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Define Corrugator
      </h2>
    </template>

    <div class="py-8">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Main Card -->
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
          <!-- Header with gradient -->
          <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-6">
            <div class="flex items-center justify-between">
              <div class="flex items-center">
                <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-4">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                  </svg>
                </div>
                <div>
                  <h2 class="text-xl font-bold text-white">Corrugator Configuration</h2>
                  <p class="text-blue-100 text-sm">Define the parameters for corrugator run size</p>
                </div>
              </div>
              
              <!-- Action buttons -->
              <div class="flex gap-2">
                <button 
                  @click="deleteConfig" 
                  class="p-2 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg transition-all duration-200 text-white"
                  :class="{'opacity-50 cursor-not-allowed': !formData.id, 'hover:scale-105': formData.id}"
                  :disabled="!formData.id"
                  title="Delete configuration"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                  </svg>
                </button>
                <button 
                  @click="resetForm" 
                  class="p-2 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg transition-all duration-200 text-white hover:scale-105"
                  title="Add new configuration"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                  </svg>
                </button>
                <button 
                  @click="editConfig" 
                  class="p-2 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg transition-all duration-200 text-white"
                  :class="{'opacity-50 cursor-not-allowed': !formData.id, 'hover:scale-105': formData.id}"
                  :disabled="!formData.id"
                  title="Edit configuration"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                  </svg>
                </button>
              </div>
            </div>
          </div>

          <!-- Form content -->
          <div class="p-6">
            <!-- Corrugator selection card -->
            <div class="bg-gray-50 p-5 rounded-lg shadow-sm border border-gray-100 mb-8">
              <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                  <div class="bg-blue-500 p-2 rounded-md mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                      <path d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4zM11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V16a1 1 0 102 0V8.732a2 2 0 000-3.464V4zM16 3a1 1 0 011 1v7.268a2 2 0 010 3.464V16a1 1 0 11-2 0v-1.268a2 2 0 010-3.464V4a1 1 0 011-1z" />
                    </svg>
                  </div>
                  <h3 class="text-lg font-semibold text-gray-700">Corrugator Selection</h3>
                </div>
                <div class="relative z-0 w-32">
                  <select 
                    v-model="formData.corrugator_id" 
                    @change="loadConfigById"
                    class="block w-full py-2.5 px-4 text-sm text-gray-900 bg-transparent rounded-lg border-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                  >
                    <option value="1">Corrugator 1</option>
                    <option value="2">Corrugator 2</option>
                    <option value="3">Corrugator 3</option>
                  </select>
                  <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                  </div>
                </div>
              </div>
            </div>

            <!-- Dimensions settings -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden mb-8">
              <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-5 py-3 border-b border-gray-200">
                <h3 class="font-medium text-gray-700">Dimensions Settings</h3>
              </div>
              <div class="p-5 space-y-6">
                <!-- Sheet Length Settings -->
                <div class="group">
                  <label class="flex items-center text-sm font-medium text-gray-700 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                    </svg>
                    Min/Max Sheet Length
                  </label>
                  <div class="flex items-center space-x-3">
                    <div class="relative">
                      <input 
                        type="number" 
                        v-model="formData.min_sheet_length" 
                        class="block w-full p-2.5 text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-blue-300"
                      />
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-400 text-xs">Min</span>
                      </div>
                    </div>
                    
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                    
                    <div class="relative">
                      <input 
                        type="number" 
                        v-model="formData.max_sheet_length" 
                        class="block w-full p-2.5 text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-blue-300"
                      />
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-400 text-xs">Max</span>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Sheet Width Settings -->
                <div class="group">
                  <label class="flex items-center text-sm font-medium text-gray-700 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                    Min/Max Sheet Width
                  </label>
                  <div class="flex items-center space-x-3">
                    <div class="relative">
                      <input 
                        type="number" 
                        v-model="formData.min_sheet_width" 
                        class="block w-full p-2.5 text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-blue-300"
                      />
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-400 text-xs">Min</span>
                      </div>
                    </div>
                    
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                    
                    <div class="relative">
                      <input 
                        type="number" 
                        v-model="formData.max_sheet_width" 
                        class="block w-full p-2.5 text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-blue-300"
                      />
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-400 text-xs">Max</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Advanced settings -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden mb-8">
              <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-5 py-3 border-b border-gray-200">
                <h3 class="font-medium text-gray-700">Advanced Settings</h3>
              </div>
              <div class="p-5 space-y-6">
                <!-- Sheet Length Multiplication -->
                <div class="group">
                  <h4 class="flex items-center text-sm font-medium text-gray-700 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    Sheet Length Multiplication
                  </h4>
                  <div class="ml-7 space-y-3 bg-gray-50 p-4 rounded-lg">
                    <label class="inline-flex items-center relative">
                      <input 
                        type="checkbox" 
                        id="sl-option1" 
                        v-model="formData.is_sheet_length_multiplied" 
                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-offset-0 focus:ring-blue-200 focus:ring-opacity-50"
                      />
                      <span class="ml-2 text-sm text-gray-700">SL will be multiplied to meet min Board Length</span>
                    </label>
                    
                    <label class="inline-flex items-center relative">
                      <input 
                        type="checkbox" 
                        id="min-raw" 
                        v-model="formData.is_min_raw_multiplied" 
                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-offset-0 focus:ring-blue-200 focus:ring-opacity-50"
                      />
                      <span class="ml-2 text-sm text-gray-700">Min SL(in) / raw where Scoring Length has been multiplied</span>
                    </label>
                  </div>
                </div>

                <!-- Sheet Width Validation -->
                <div class="group">
                  <h4 class="flex items-center text-sm font-medium text-gray-700 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Sheet Width Validation
                  </h4>
                  <div class="ml-7 space-y-3 bg-gray-50 p-4 rounded-lg">
                    <label class="inline-flex items-center relative">
                      <input 
                        type="checkbox" 
                        id="validate-width" 
                        v-model="formData.validate_sheet_width" 
                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-offset-0 focus:ring-blue-200 focus:ring-opacity-50"
                      />
                      <span class="ml-2 text-sm text-gray-700">Validate Sheet Width to meet Min/Max Sheet Width</span>
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3">
              <button 
                @click="resetForm" 
                class="flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Cancel
              </button>
              <button 
                @click="saveSettings" 
                class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Save
              </button>
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
                class="mt-6 sm:fixed sm:top-4 sm:right-4 w-full sm:w-80 p-4 rounded-lg shadow-lg border border-l-4"
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
      max_sheet_length: 99999,
      min_sheet_width: 1,
      max_sheet_width: 99999,
      is_sheet_length_multiplied: true,
      is_min_raw_multiplied: false,
      validate_sheet_width: false,
    });
    
    const notification = ref({
      show: false,
      message: '',
      type: 'success'
    });
    
    const allConfigs = ref([]);
    
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
        const response = await axios.get('/api/corrugator-configs');
        allConfigs.value = response.data;
        
        // If we have data, load the first corrugator by default
        if (allConfigs.value.length > 0) {
          formData.value = { ...allConfigs.value.find(c => c.corrugator_id === formData.value.corrugator_id) };
        } else {
          // If no data, initialize the form with default data
          resetForm();
        }
      } catch (error) {
        console.error('Error loading corrugator configs:', error);
        showNotification('Failed to load corrugator configurations', 'error');
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
    
    const saveSettings = async () => {
      try {
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
        showNotification('Failed to save corrugator configuration', 'error');
      }
    };
    
    const deleteConfig = async () => {
      if (!formData.value.id) return;
      
      if (!confirm('Are you sure you want to delete this configuration?')) return;
      
      try {
        await axios.delete(`/api/corrugator-configs/${formData.value.id}`);
        showNotification('Corrugator configuration deleted successfully');
        
        // Refresh the list
        await loadConfigs();
        resetForm();
      } catch (error) {
        console.error('Error deleting corrugator config:', error);
        showNotification('Failed to delete corrugator configuration', 'error');
      }
    };
    
    const editConfig = () => {
      // Just a placeholder for edit functionality
      // The form is already populated with the selected config
      showNotification('Editing current configuration. Make changes and click Save.');
    };
    
    const resetForm = () => {
      formData.value = {
        id: null,
        corrugator_id: '1',
        min_sheet_length: 1,
        max_sheet_length: 99999,
        min_sheet_width: 1,
        max_sheet_width: 99999,
        is_sheet_length_multiplied: true,
        is_min_raw_multiplied: false,
        validate_sheet_width: false,
      };
    };
    
    const seedDefaultData = async () => {
      try {
        await axios.post('/api/corrugator-configs/seed');
        showNotification('Default corrugator configurations loaded successfully');
        await loadConfigs();
      } catch (error) {
        console.error('Error seeding default data:', error);
        showNotification('Failed to load default configurations', 'error');
      }
    };
    
    onMounted(async () => {
      await loadConfigs();
      
      // If no configurations are found, seed the default data
      if (allConfigs.value.length === 0) {
        await seedDefaultData();
      }
    });

    return {
      formData,
      notification,
      saveSettings,
      resetForm,
      deleteConfig,
      editConfig,
      loadConfigById,
    };
  },
});
</script>
