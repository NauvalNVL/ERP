<template>
  <AppLayout title="Define Finishing">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Define Finishing
      </h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
          <!-- Header with buttons -->
          <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 flex items-center justify-between">
            <h2 class="text-lg font-bold text-white">Define Finishing</h2>
            <div class="flex space-x-2">
              <button @click="loadTestData" :disabled="loading" class="bg-purple-600 hover:bg-purple-500 text-white px-3 py-1 rounded text-sm flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Load Test Data
              </button>
              <button @click="openAddModal" :disabled="loading" class="bg-green-600 hover:bg-green-500 text-white px-3 py-1 rounded text-sm flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Add
              </button>
              <Link :href="viewPrintRoute" class="bg-blue-500 hover:bg-blue-400 text-white px-3 py-1 rounded text-sm flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
                </svg>
                View & Print
              </Link>
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
              <!-- Search and Filter -->
              <div class="mb-4 flex items-center">
                <div class="relative flex-grow">
                  <input
                    type="text"
                    v-model="searchQuery"
                    placeholder="Search finishing..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  />
                  <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                  </div>
                </div>
              </div>

              <!-- Table section -->
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border">
                  <thead class="bg-blue-700">
                    <tr>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border-r w-24">
                        Finishing
                      </th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border-r">
                        Description
                      </th>
                      <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">
                        Compute
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="filteredFinishings.length === 0" class="hover:bg-gray-50">
                      <td colspan="3" class="px-4 py-4 text-center text-sm text-gray-500">
                        No finishing data found. Please add a finishing first.
                      </td>
                    </tr>
                    <tr v-for="finishing in filteredFinishings" :key="finishing.code" class="hover:bg-gray-50">
                      <td class="px-4 py-2 text-sm font-medium text-gray-900 border-r">
                        {{ finishing.code }}
                      </td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">
                        {{ finishing.description }}
                      </td>
                      <td class="px-4 py-2 text-sm text-center text-gray-900">
                        <button 
                          @click="toggleCompute(finishing)"
                          :class="finishing.is_compute ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200'"
                          class="px-2 py-1 rounded-full text-xs font-medium"
                        >
                          {{ finishing.is_compute ? 'Yes' : 'No' }}
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Add/Edit Modal -->
            <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
              <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4">
                <div class="p-4 border-b border-gray-200">
                  <h3 class="text-lg font-semibold">{{ isEditing ? 'Edit Finishing' : 'Add Finishing' }}</h3>
                </div>
                <div class="p-4">
                  <form @submit.prevent="submitForm">
                    <div class="mb-4">
                      <label class="block text-sm font-medium text-gray-700 mb-1">Finishing Code</label>
                      <input
                        type="text"
                        v-model="form.code"
                        :disabled="isEditing"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        :class="{ 'bg-gray-100': isEditing }"
                        maxlength="10"
                        required
                      />
                    </div>
                    <div class="mb-4">
                      <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                      <input
                        type="text"
                        v-model="form.description"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                      />
                    </div>
                    <div class="mb-4">
                      <label class="flex items-center">
                        <input
                          type="checkbox"
                          v-model="form.is_compute"
                          class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"/>
                        <span class="ml-2 text-sm text-gray-700">Compute</span>
                      </label>
                    </div>
                    <div class="flex justify-end space-x-2 mt-6">
                      <button
                        type="button"
                        @click="closeModal"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500"
                      >
                        Cancel
                      </button>
                      <button
                        type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                      >
                        {{ isEditing ? 'Update' : 'Save' }}
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
              <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4">
                <div class="p-4 border-b border-gray-200">
                  <h3 class="text-lg font-semibold text-red-600">Confirm Delete</h3>
                </div>
                <div class="p-4">
                  <p class="mb-4">Are you sure you want to delete the finishing <strong>{{ selectedFinishing?.code }}</strong> - {{ selectedFinishing?.description }}?</p>
                  <div class="flex justify-end space-x-2">
                    <button
                      type="button"
                      @click="showDeleteModal = false"
                      class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500"
                    >
                      Cancel
                    </button>
                    <button
                      type="button"
                      @click="deleteFinishing"
                      class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500"
                    >
                      Delete
                    </button>
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
import { defineComponent, ref, computed, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

export default defineComponent({
  components: {
    AppLayout,
    Link
  },
  setup() {
    const loading = ref(true);
    const finishings = ref([]);
    const searchQuery = ref('');
    const showModal = ref(false);
    const showDeleteModal = ref(false);
    const isEditing = ref(false);
    const selectedFinishing = ref(null);
    const form = ref({
      code: '',
      description: '',
      is_compute: false
    });
    const notification = ref({
      show: false,
      message: '',
      type: 'success'
    });
    
    // Define route for view & print
    const viewPrintRoute = '/standard-formula/stitching-computation/finishing/view-print';

    const filteredFinishings = computed(() => {
      if (!searchQuery.value) return finishings.value;
      
      const query = searchQuery.value.toLowerCase();
      return finishings.value.filter(finishing => 
        finishing.code.toLowerCase().includes(query) || 
        finishing.description.toLowerCase().includes(query)
      );
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
        
        // Load finishings data
        const response = await axios.get('/api/finishings');
        
        if (response.data) {
          finishings.value = response.data;
        }
      } catch (error) {
        console.error('Error loading data:', error);
        
        // More detailed error handling
        let errorMessage = 'Failed to load finishing data';
        
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

    const openAddModal = () => {
      isEditing.value = false;
      form.value = {
        code: '',
        description: '',
        is_compute: false
      };
      showModal.value = true;
    };

    const openEditModal = (finishing) => {
      isEditing.value = true;
      selectedFinishing.value = finishing;
      form.value = {
        code: finishing.code,
        description: finishing.description,
        is_compute: finishing.is_compute
      };
      showModal.value = true;
    };

    const closeModal = () => {
      showModal.value = false;
    };

    const confirmDelete = (finishing) => {
      selectedFinishing.value = finishing;
      showDeleteModal.value = true;
    };

    const submitForm = async () => {
      try {
        loading.value = true;
        
        let response;
        
        if (isEditing.value) {
          // Update existing finishing
          response = await axios.put(`/api/finishings/${form.value.code}`, {
            code: form.value.code,
            description: form.value.description,
            is_compute: form.value.is_compute
          });
          
          if (response.data && response.data.success) {
            // Update the finishing in the list
            const index = finishings.value.findIndex(f => f.code === form.value.code);
            if (index !== -1) {
              finishings.value[index] = response.data.data;
            }
            
            showNotification('Finishing updated successfully');
          }
        } else {
          // Create new finishing
          response = await axios.post('/api/finishings', {
            code: form.value.code,
            description: form.value.description,
            is_compute: form.value.is_compute
          });
          
          if (response.data && response.data.success) {
            // Add the new finishing to the list
            finishings.value.push(response.data.data);
            
            showNotification('Finishing created successfully');
          }
        }
        
        // Close the modal
        closeModal();
      } catch (error) {
        console.error('Error submitting form:', error);
        
        // More detailed error handling
        let errorMessage = isEditing.value ? 'Failed to update finishing' : 'Failed to create finishing';
        
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

    const deleteFinishing = async () => {
      try {
        loading.value = true;
        
        // Delete the finishing
        const response = await axios.delete(`/api/finishings/${selectedFinishing.value.code}`);
        
        if (response.data && response.data.success) {
          // Remove the finishing from the list
          finishings.value = finishings.value.filter(f => f.code !== selectedFinishing.value.code);
          
          showNotification('Finishing deleted successfully');
        }
        
        // Close the modal
        showDeleteModal.value = false;
      } catch (error) {
        console.error('Error deleting finishing:', error);
        
        // More detailed error handling
        let errorMessage = 'Failed to delete finishing';
        
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
        showDeleteModal.value = false;
      }
    };

    const toggleCompute = async (finishing) => {
      try {
        const newValue = !finishing.is_compute;
        
        // Optimistically update the UI
        finishing.is_compute = newValue;
        
        // Save to the server
        const response = await axios.put(`/api/finishings/${finishing.code}`, {
          code: finishing.code,
          description: finishing.description,
          is_compute: newValue
        });
        
        if (response.data && response.data.success) {
          showNotification(`Compute value set to ${newValue ? 'Yes' : 'No'}`, 'success');
        } else {
          // Revert if failed
          finishing.is_compute = !newValue;
          showNotification('Failed to update compute value', 'error');
        }
      } catch (error) {
        console.error('Error updating compute value:', error);
        
        // Revert the change
        finishing.is_compute = !finishing.is_compute;
        
        let errorMessage = 'Failed to update compute value';
        
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
      }
    };

    const loadTestData = async () => {
      try {
        loading.value = true;
        
        // Load test data using the seed endpoint
        const seedResponse = await axios.post('/api/finishings/seed');
        
        if (seedResponse.data && seedResponse.data.success) {
          // After seeding, reload the data from the API
          const response = await axios.get('/api/finishings');
          
          if (response.data) {
            finishings.value = response.data;
            showNotification('Test data loaded successfully', 'success');
          }
        }
      } catch (error) {
        console.error('Error loading test data:', error);
        
        // More detailed error handling
        let errorMessage = 'Failed to load test data';
        
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

    onMounted(() => {
      loadData();
    });

    return {
      loading,
      finishings,
      filteredFinishings,
      searchQuery,
      showModal,
      showDeleteModal,
      isEditing,
      selectedFinishing,
      form,
      notification,
      viewPrintRoute,
      openAddModal,
      openEditModal,
      closeModal,
      confirmDelete,
      submitForm,
      deleteFinishing,
      showNotification,
      toggleCompute,
      loadTestData
    };
  }
});
</script>
