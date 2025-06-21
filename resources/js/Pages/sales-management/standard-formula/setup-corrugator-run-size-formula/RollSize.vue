<template>
  <AppLayout title="Define Roll Size">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Define Roll Size
      </h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="p-6 sm:px-8 bg-white border-b border-gray-200">
            <div class="flex items-center justify-between">
              <div>
                <h2 class="text-2xl font-bold text-gray-800">Roll Size Settings</h2>
                <p class="mt-1 text-sm text-gray-600">Manage roll size specifications for each flute.</p>
              </div>
            <div class="flex space-x-2">
                <button @click="saveChanges" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                </svg>
                Save
              </button>
              </div>
            </div>
          </div>

          <div class="p-6 sm:px-8">
            <div v-if="loading" class="flex justify-center items-center py-16">
              <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-blue-500"></div>
              <span class="ml-4 text-lg text-gray-700">Loading Flutes...</span>
            </div>

            <div v-else>
              <div class="mb-6">
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                  </div>
                  <input
                    type="text"
                    v-model="searchQuery"
                    @input="filterItems"
                    placeholder="Search by flute code or name..."
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                  />
                </div>
              </div>

              <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Flute
                      </th>
                      <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Roll Length (mm)
                      </th>
                      <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Compute
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="paginatedItems.length === 0">
                      <td colspan="3" class="px-6 py-12 text-center text-sm text-gray-500">
                        <div class="flex flex-col items-center">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                          </svg>
                          <h3 class="mt-2 text-lg font-medium text-gray-900">No roll sizes found</h3>
                          <p class="mt-1 text-sm text-gray-500">Please try a different search term or add new roll sizes.</p>
                        </div>
                      </td>
                    </tr>
                    <tr v-for="size in paginatedItems" :key="size.id" class="hover:bg-gray-50 transition-colors duration-150">
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ size.flute_code }}</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-center">
                        <input 
                          type="number" 
                          v-model="size.roll_length" 
                          step="0.01"
                          class="w-24 text-center border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        />
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-center">
                        <Switch
                          v-model="size.is_composite"
                          @update:modelValue="toggleCompute(size)"
                          :class="size.is_composite ? 'bg-blue-600' : 'bg-gray-200'"
                          class="relative inline-flex items-center h-6 rounded-full w-11 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                          :disabled="savingCompute[size.id]"
                        >
                          <span class="sr-only">Enable compute</span>
                          <span
                            :class="size.is_composite ? 'translate-x-6' : 'translate-x-1'"
                            class="inline-block w-4 h-4 transform bg-white rounded-full transition-transform"
                          />
                          <div v-if="savingCompute[size.id]" class="absolute inset-0 flex items-center justify-center">
                            <div class="w-4 h-4 border-2 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                          </div>
                        </Switch>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <!-- Pagination -->
              <div v-if="filteredItems.length > itemsPerPage" class="mt-6 flex items-center justify-between">
                <p class="text-sm text-gray-700">
                  Showing
                  <span class="font-medium">{{ (currentPage - 1) * itemsPerPage + 1 }}</span>
                  to
                  <span class="font-medium">{{ Math.min(currentPage * itemsPerPage, filteredItems.length) }}</span>
                  of
                  <span class="font-medium">{{ filteredItems.length }}</span>
                  results
                </p>
                <div class="flex-1 flex justify-end">
                  <button
                    @click="prevPage"
                    :disabled="currentPage === 1"
                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50"
                  >
                    Previous
                  </button>
                  <button
                    @click="nextPage"
                    :disabled="currentPage === totalPages"
                    class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50"
                  >
                    Next
                  </button>
                </div>
              </div>
              </div>
            </div>

            <!-- Add New Roll Size -->
          <div class="p-6 sm:px-8 bg-gray-50 border-t border-gray-200">
              <h3 class="text-lg font-medium text-gray-700 mb-3">Add New Roll Size</h3>
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                  <label for="new-flute" class="block text-sm font-medium text-gray-700 mb-1">Flute</label>
                  <select
                    id="new-flute"
                    v-model="newRollSize.flute_id"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                  >
                    <option value="">Select Flute</option>
                    <option v-for="flute in flutes" :key="flute.id" :value="flute.id">
                      {{ flute.code }}
                    </option>
                  </select>
                </div>
                
                <div>
                  <label for="new-roll-length" class="block text-sm font-medium text-gray-700 mb-1">Roll Length (mm)</label>
                  <input
                    id="new-roll-length"
                    type="number"
                    v-model="newRollSize.roll_length"
                    step="0.01"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                  />
                </div>
                
                <div class="flex items-end">
                  <div class="mr-4">
                    <label class="flex items-center">
                      <input
                        type="checkbox"
                        v-model="newRollSize.is_composite"
                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                      />
                    <span class="ml-2 text-sm text-gray-700">Compute</span>
                    </label>
                  </div>
                  <button 
                    @click="addRollSize" 
                    class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded text-sm"
                    :disabled="!canAddRollSize"
                  >
                    Add Roll Size
                  </button>
                </div>
              </div>
                  </div>
                </div>
              </div>
          </div>


  </AppLayout>
</template>

<script>
import { defineComponent, ref, computed, onMounted, reactive } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Switch } from '@headlessui/vue';
import axios from 'axios';
import Swal from 'sweetalert2';

export default defineComponent({
  components: {
    AppLayout,
    Switch
  },
  setup() {
    const loading = ref(true);
    const rollSizes = ref([]);
    const filteredItems = ref([]);
    const flutes = ref([]);
    const searchQuery = ref('');
    const currentPage = ref(1);
    const itemsPerPage = ref(10);
    const savingCompute = reactive({});
    const viewPrintUrl = '/standard-formula/setup-roll-size/view-print';
    
    const newRollSize = ref({
      flute_id: '',
      roll_length: '',
      is_composite: false
    });
    


    const totalPages = computed(() => Math.ceil(filteredItems.value.length / itemsPerPage.value));
    const paginatedItems = computed(() => {
      const start = (currentPage.value - 1) * itemsPerPage.value;
      const end = start + itemsPerPage.value;
      return filteredItems.value.slice(start, end);
    });

    const nextPage = () => {
      if (currentPage.value < totalPages.value) currentPage.value++;
    };

    const prevPage = () => {
      if (currentPage.value > 1) currentPage.value--;
    };

    const canAddRollSize = computed(() => {
      return newRollSize.value.flute_id && 
             newRollSize.value.roll_length && 
             newRollSize.value.roll_length > 0;
    });

    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
      }
    });

    const showNotification = (message, type = 'success') => {
      Toast.fire({
        icon: type,
        title: message
      });
    };

    const loadData = async () => {
      try {
        loading.value = true;
        
        // Load flutes
        const flutesResponse = await axios.get('/api/paper-flutes');
        flutes.value = flutesResponse.data;
        
        // Load roll sizes
        const sizesResponse = await axios.get('/api/roll-sizes');
        
        if (sizesResponse.data && sizesResponse.data.status === 'success' && Array.isArray(sizesResponse.data.data) && sizesResponse.data.data.length > 0) {
          // Process the data from the API response
          rollSizes.value = sizesResponse.data.data.map(size => {
            const flute = size.paper_flute || {};
            
            return {
              id: size.id,
              flute_id: size.flute_id,
              flute_code: flute.code || 'N/A',
              roll_length: size.roll_length,
              is_composite: size.is_composite === 1 || size.is_composite === true
            };
          });
          
          // Sort by flute code and then by roll length
          rollSizes.value.sort((a, b) => {
            if (a.flute_code === b.flute_code) {
              return a.roll_length - b.roll_length;
            }
            return a.flute_code.localeCompare(b.flute_code);
          });
        } else {
          // If no data from API, seed the database first
          try {
            showNotification('No roll size data found. Seeding initial data...', 'info');
            await axios.post('/api/roll-sizes/seed');
            showNotification('Initial data has been seeded. Loading data...', 'success');
            
            // Try to fetch the data again after seeding
            const newSizesResponse = await axios.get('/api/roll-sizes');
            if (newSizesResponse.data && newSizesResponse.data.status === 'success' && Array.isArray(newSizesResponse.data.data) && newSizesResponse.data.data.length > 0) {
              rollSizes.value = newSizesResponse.data.data.map(size => {
                const flute = size.paper_flute || {};
                
                return {
                  id: size.id,
                  flute_id: size.flute_id,
                  flute_code: flute.code || 'N/A',
                  roll_length: size.roll_length,
                  is_composite: size.is_composite === 1 || size.is_composite === true
                };
              });
              
              // Sort by flute code and then by roll length
              rollSizes.value.sort((a, b) => {
                if (a.flute_code === b.flute_code) {
                  return a.roll_length - b.roll_length;
                }
                return a.flute_code.localeCompare(b.flute_code);
              });
            } else {
              rollSizes.value = [];
              showNotification('Failed to load roll size data after seeding', 'error');
            }
          } catch (seedError) {
            console.error('Error seeding data:', seedError);
            rollSizes.value = [];
            showNotification('Failed to seed roll size data', 'error');
          }
        }
        
        filteredItems.value = [...rollSizes.value];
      } catch (error) {
        console.error('Error loading data:', error);
        
        let errorMessage = 'Failed to load roll size data';
        
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
        rollSizes.value = [];
        filteredItems.value = [];
      } finally {
        loading.value = false;
      }
    };

    const filterItems = () => {
      currentPage.value = 1;
      if (!searchQuery.value) {
        filteredItems.value = [...rollSizes.value];
        return;
      }
      
      const query = searchQuery.value.toLowerCase();
      filteredItems.value = rollSizes.value.filter(size => 
        size.flute_code.toLowerCase().includes(query)
      );
    };

    // Function to toggle compute value with immediate save
    const toggleCompute = async (size) => {
      try {
        // Set loading state for this specific item
        savingCompute[size.id] = true;
        
        // Prepare data for saving
        const dataToSave = {
          id: size.id,
          flute_id: size.flute_id,
          roll_length: size.roll_length,
          is_composite: size.is_composite
        };
        
        // Save the data
        await axios.put(`/api/roll-sizes/${size.id}`, dataToSave);
        
        // Show small notification
        showNotification(`Compute status for ${size.flute_code} updated successfully`, 'success');
      } catch (error) {
        console.error('Error toggling compute status:', error);
        
        // Revert the change in the UI
        size.is_composite = !size.is_composite;
        
        // Show error notification
        showNotification(`Failed to update compute status for ${size.flute_code}`, 'error');
      } finally {
        // Clear loading state
        savingCompute[size.id] = false;
      }
    };

    const saveChanges = async () => {
      try {
        loading.value = true;
        
        // Prepare data for saving
        const dataToSave = rollSizes.value.map(size => ({
          id: size.id,
            flute_id: size.flute_id,
            roll_length: size.roll_length,
            is_composite: size.is_composite
        }));
        
        // Send data to the API
        const response = await axios.post('/api/roll-sizes/batch', dataToSave);
        
        if (response.data.errors && response.data.errors.length > 0) {
          const errorCount = response.data.errors.length;
          Swal.fire({
            icon: 'error',
            title: 'Batch Save Error',
            text: `${errorCount} roll sizes could not be saved.`,
          });
          console.error('Errors saving roll sizes:', response.data.errors);
        } else {
          Swal.fire({
            icon: 'success',
            title: 'Save Successful',
            text: `All roll sizes have been saved.`,
          });
        }
        
        // Reload data to get the updated records
        await loadData();
      } catch (error) {
        console.error('Error saving roll sizes:', error);
        
        let errorMessage = 'Failed to save roll sizes';
        
        if (error.response && error.response.data) {
          if (error.response.data.message) {
            errorMessage += `: ${error.response.data.message}`;
          } else if (error.response.data.errors) {
            const firstError = Object.values(error.response.data.errors)[0];
            errorMessage += `: ${Array.isArray(firstError) ? firstError[0] : firstError}`;
          }
        } else {
          errorMessage += `: ${error.message}`;
        }
        
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: errorMessage,
        });
      } finally {
        loading.value = false;
      }
    };

    const addRollSize = async () => {
      if (!canAddRollSize.value) {
        showNotification('Please fill in all required fields', 'error');
        return;
      }
      
      try {
        loading.value = true;
        
        // Check if this combination already exists
        const exists = rollSizes.value.some(size => 
          size.flute_id === newRollSize.value.flute_id && 
          size.roll_length === parseFloat(newRollSize.value.roll_length)
        );
        
        if (exists) {
          showNotification('This flute and roll length combination already exists', 'error');
          loading.value = false;
          return;
        }
        
        // Add new roll size
        const response = await axios.post('/api/roll-sizes', {
          flute_id: newRollSize.value.flute_id,
          roll_length: newRollSize.value.roll_length,
          is_composite: newRollSize.value.is_composite
        });
        
        if (response.data && response.data.status === 'success') {
          showNotification('Roll size added successfully');
          
          // Reset form
          newRollSize.value = {
            flute_id: '',
            roll_length: '',
            is_composite: false
          };
          
          // Reload data
          await loadData();
        } else {
          showNotification('Failed to add roll size', 'error');
        }
      } catch (error) {
        console.error('Error adding roll size:', error);
        
        let errorMessage = 'Failed to add roll size';
        
        if (error.response && error.response.data) {
          if (error.response.data.message) {
            errorMessage += `: ${error.response.data.message}`;
          } else if (error.response.data.errors) {
            const firstError = Object.values(error.response.data.errors)[0];
            errorMessage += `: ${Array.isArray(firstError) ? firstError[0] : firstError}`;
          }
        } else {
          errorMessage += `: ${error.message}`;
        }
        
        showNotification(errorMessage, 'error');
      } finally {
        loading.value = false;
      }
    };

    const exportData = () => {
      showNotification('Exporting data...');
      
      // Call the export API endpoint
      axios.get('/api/roll-sizes/export', { responseType: 'blob' })
        .then(response => {
          // Create a download link for the exported file
          const blob = new Blob([response.data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
          const link = document.createElement('a');
          link.href = window.URL.createObjectURL(blob);
          link.download = `roll_sizes_${new Date().toISOString().split('T')[0]}.xlsx`;
          link.click();
          
          showNotification('Data exported successfully');
        })
        .catch(error => {
          console.error('Error exporting data:', error);
          showNotification('Failed to export data', 'error');
        });
    };

    const printData = () => {
      window.print();
    };

    onMounted(() => {
      loadData();
    });

    return {
      loading,
      rollSizes,
      filteredItems,
      paginatedItems,
      flutes,
      newRollSize,
      searchQuery,
      currentPage,
      itemsPerPage,
      totalPages,
      viewPrintUrl,
      savingCompute,
      canAddRollSize,
      filterItems,
      saveChanges,
      addRollSize,
      exportData,
      printData,
      showNotification,
      nextPage,
      prevPage,
      toggleCompute
    };
  }
});
</script>

<style>
@media print {
  body * {
    visibility: hidden;
  }
  .max-w-7xl, .max-w-7xl * {
    visibility: visible;
  }
  .max-w-7xl {
    position: absolute;
    left: 0;
    top: 0;
  }
  button, .bg-gray-50:not(.border) {
    display: none !important;
  }
}
</style>
