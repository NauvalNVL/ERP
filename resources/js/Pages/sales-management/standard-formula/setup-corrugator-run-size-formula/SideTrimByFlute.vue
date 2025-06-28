<template>
  <AppLayout title="Define Side Trim By Flute">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Define Side Trim By Flute
      </h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="p-6 sm:px-8 bg-white border-b border-gray-200">
            <div class="flex items-center justify-between">
              <div>
                <h2 class="text-2xl font-bold text-gray-800">Side Trim By Flute</h2>
                <p class="mt-1 text-sm text-gray-600">Manage side trim specifications for each flute type.</p>
              </div>
            <div class="flex space-x-2">
                <button @click="saveChanges" class="inline-flex items-center px-4 py-2 bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-800 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring focus:ring-green-300 disabled:opacity-25 transition">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                Save
              </button>
              </div>
            </div>
          </div>

          <div class="p-6 sm:px-8">
            <!-- Loading Spinner -->
            <div v-if="loading" class="flex justify-center items-center py-16">
              <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-blue-500"></div>
              <span class="ml-4 text-lg text-gray-700">Loading...</span>
            </div>

            <div v-else>
              <!-- Search functionality -->
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
                    @input="filterSideTrims"
                    placeholder="Search by flute code or name..."
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                  />
                </div>
              </div>

              <!-- Table section -->
              <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Flute
                      </th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Flute Name
                      </th>
                      <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Compute
                      </th>
                      <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Length Less [mm]
                      </th>
                      <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Length Add [mm]
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="paginatedSideTrims.length === 0" class="hover:bg-gray-50">
                      <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">
                        <div class="flex flex-col items-center">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                          </svg>
                          <h3 class="mt-2 text-lg font-medium text-gray-900">No side trim data found</h3>
                          <p class="mt-1 text-sm text-gray-500">Please add new data or try a different search term.</p>
                        </div>
                      </td>
                    </tr>
                    <tr 
                      v-for="(trim, index) in paginatedSideTrims" 
                      :key="trim.id" 
                      class="hover:bg-gray-50 transition-colors duration-150"
                    >
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ trim.flute_code }}</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">{{ trim.flute_name }}</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-center relative">
                        <Switch
                          v-model="trim.compute"
                          @update:modelValue="toggleCompute(trim)"
                          :class="trim.compute ? 'bg-blue-600' : 'bg-gray-200'"
                          class="relative inline-flex items-center h-6 rounded-full w-11 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                          :disabled="savingTrim[trim.id]"
                        >
                          <span class="sr-only">Enable compute</span>
                          <span
                            :class="trim.compute ? 'translate-x-6' : 'translate-x-1'"
                            class="inline-block w-4 h-4 transform bg-white rounded-full transition-transform"
                          />
                          <div v-if="savingTrim[trim.id]" class="absolute inset-0 flex items-center justify-center">
                            <div class="w-4 h-4 border-2 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                          </div>
                        </Switch>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-center relative">
                        <input 
                          type="number" 
                          v-model="trim.length_less" 
                          @change="updateTrimValue(trim, 'length_less')"
                          class="w-24 text-center border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                          :disabled="savingTrim[trim.id]"
                        />
                        <div v-if="savingTrim[trim.id]" class="absolute inset-0 flex items-center justify-center">
                          <div class="w-4 h-4 border-2 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                        </div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-center relative">
                        <input 
                          type="number" 
                          v-model="trim.length_add" 
                          @change="updateTrimValue(trim, 'length_add')"
                          class="w-24 text-center border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                          :disabled="savingTrim[trim.id]"
                        />
                        <div v-if="savingTrim[trim.id]" class="absolute inset-0 flex items-center justify-center">
                          <div class="w-4 h-4 border-2 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <!-- Pagination -->
              <div v-if="filteredSideTrims.length > itemsPerPage" class="mt-6 flex items-center justify-between">
                <p class="text-sm text-gray-700">
                  Showing
                  <span class="font-medium">{{ (currentPage - 1) * itemsPerPage + 1 }}</span>
                  to
                  <span class="font-medium">{{ Math.min(currentPage * itemsPerPage, filteredSideTrims.length) }}</span>
                  of
                  <span class="font-medium">{{ filteredSideTrims.length }}</span>
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

            <!-- Add New Side Trim -->
            <div class="mt-6 bg-gray-50 p-6 rounded-lg shadow-sm border border-gray-200">
              <h3 class="text-lg font-medium text-gray-700 mb-3">Add New Side Trim</h3>
              <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                  <label for="new-flute" class="block text-sm font-medium text-gray-700 mb-1">Flute</label>
                  <select
                    id="new-flute"
                    v-model="newSideTrim.flute_id"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                  >
                    <option value="">Select Flute</option>
                    <option v-for="flute in flutes" :key="flute.id" :value="flute.id">
                      {{ flute.code }}
                    </option>
                  </select>
                </div>
                
                <div>
                  <label for="new-compute" class="block text-sm font-medium text-gray-700 mb-1">Compute</label>
                  <select
                    id="new-compute"
                    v-model="newSideTrim.compute"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                  >
                    <option :value="false">No</option>
                    <option :value="true">Yes</option>
                  </select>
                </div>
                
                <div>
                  <label for="new-length-less" class="block text-sm font-medium text-gray-700 mb-1">Length Less [mm]</label>
                  <input
                    id="new-length-less"
                    type="number"
                    v-model="newSideTrim.length_less"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                  />
                </div>
                
                <div>
                  <label for="new-length-add" class="block text-sm font-medium text-gray-700 mb-1">Length Add [mm]</label>
                  <input
                    id="new-length-add"
                    type="number"
                    v-model="newSideTrim.length_add"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                  />
                </div>
                
                <div class="flex items-end">
                  <button 
                    @click="addSideTrim" 
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition"
                    :disabled="!canAddSideTrim"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Side Trim
                  </button>
                </div>
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
    const sideTrims = ref([]);
    const filteredSideTrims = ref([]);
    const flutes = ref([]);
    const searchQuery = ref('');
    const currentPage = ref(1);
    const itemsPerPage = ref(10);
    const savingTrim = reactive({});
    
    const newSideTrim = ref({
      flute_id: '',
      compute: false,
      length_add: 0,
      length_less: 0
    });
    
    const totalPages = computed(() => {
      return Math.ceil(filteredSideTrims.value.length / itemsPerPage.value);
    });

    const paginatedSideTrims = computed(() => {
      const start = (currentPage.value - 1) * itemsPerPage.value;
      const end = start + itemsPerPage.value;
      return filteredSideTrims.value.slice(start, end);
    });

    const nextPage = () => {
      if (currentPage.value < totalPages.value) {
        currentPage.value++;
      }
    };

    const prevPage = () => {
      if (currentPage.value > 1) {
        currentPage.value--;
      }
    };

    const canAddSideTrim = computed(() => {
      return newSideTrim.value.flute_id && 
             newSideTrim.value.length_less !== '' && 
             newSideTrim.value.length_add !== '';
    });

    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
      }
    });

    const showNotification = (message, type = 'success') => {
      Toast.fire({
        icon: type,
        title: message
      });
    };

    const filterSideTrims = () => {
      if (!searchQuery.value) {
        filteredSideTrims.value = [...sideTrims.value];
        return;
      }
      
      const query = searchQuery.value.toLowerCase();
      filteredSideTrims.value = sideTrims.value.filter(trim => 
        trim.flute_code.toLowerCase().includes(query) || 
        trim.flute_name.toLowerCase().includes(query)
      );
      currentPage.value = 1;
    };

    const loadData = async () => {
      try {
        loading.value = true;
        
        const response = await axios.get('/api/side-trims-by-flute');
        
        console.log('API Response for side trims:', response.data);
          
          // Process the data from the API response
        if (response.data && response.data.status === 'success' && Array.isArray(response.data.data) && response.data.data.length > 0) {
          sideTrims.value = response.data.data.map(trim => {
            const flute = trim.paper_flute || {};
            
            return {
              id: trim.id,
              flute_id: trim.flute_id,
              flute_code: flute.code || 'N/A',
              flute_name: flute.name || 'N/A',
              compute: trim.compute === 1 || trim.compute === true || trim.compute === 'true',
              length_less: trim.length_less || 0,
              length_add: trim.length_add || 0
            };
          }).sort((a, b) => {
            if (a.flute_code !== b.flute_code) return a.flute_code.localeCompare(b.flute_code);
            return a.compute === b.compute ? 0 : (a.compute ? -1 : 1);
          });
        } else {
          // If no data from API, seed the database first
          try {
            showNotification('No side trim data found. Seeding initial data...', 'info');
            await axios.post('/api/side-trims-by-flute/seed');
            showNotification('Initial data has been seeded. Loading data...', 'success');
            
            // Try to fetch the data again after seeding
            const newResponse = await axios.get('/api/side-trims-by-flute');
            if (newResponse.data && newResponse.data.status === 'success' && Array.isArray(newResponse.data.data) && newResponse.data.data.length > 0) {
              sideTrims.value = newResponse.data.data.map(trim => {
                const flute = trim.paper_flute || {};
                
                return {
                  id: trim.id,
                  flute_id: trim.flute_id,
                  flute_code: flute.code || 'N/A',
                  flute_name: flute.name || 'N/A',
                  compute: trim.compute === 1 || trim.compute === true || trim.compute === 'true',
                  length_less: trim.length_less || 0,
                  length_add: trim.length_add || 0
                };
              }).sort((a, b) => {
                if (a.flute_code !== b.flute_code) return a.flute_code.localeCompare(b.flute_code);
                return a.compute === b.compute ? 0 : (a.compute ? -1 : 1);
              });
            } else {
              sideTrims.value = [];
              showNotification('Failed to load side trim data after seeding', 'error');
            }
          } catch (seedError) {
            console.error('Error seeding data:', seedError);
            sideTrims.value = [];
            showNotification('Failed to seed side trim data', 'error');
          }
        }
        
        // Explicitly convert filtered items
        filteredSideTrims.value = sideTrims.value.map(trim => ({
          ...trim,
          compute: trim.compute === 1 || trim.compute === true || trim.compute === 'true'
        }));
      } catch (error) {
        console.error('Error loading side trims:', error);
        
        let errorMessage = 'Failed to load side trim data';
        
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
        sideTrims.value = [];
        filteredSideTrims.value = [];
      } finally {
        loading.value = false;
      }
    };

    // Save a single field update
    const updateTrimValue = async (trim, field) => {
      try {
        // Store the original value in case of rollback
        const originalValue = trim[field];
        
        // Set loading state for this specific item
        savingTrim[trim.id] = true;
        
        // Validate input
        const numericValue = trim[field] === '' ? 0 : parseInt(trim[field], 10);
        
        // Prepare data for saving
        const dataToSave = {
          id: trim.id,
          flute_id: trim.flute_id,
          length_add: field === 'length_add' ? numericValue : trim.length_add,
          length_less: field === 'length_less' ? numericValue : trim.length_less,
          compute: trim.compute
        };
        
        // Save the data
        const response = await axios.put(`/api/side-trims-by-flute/${trim.id}`, dataToSave);
        
        if (response.data.status === 'success') {
          // Update the local data with the server response
          trim[field] = numericValue;
          
          // Show small notification
          showNotification(`${field} updated successfully for ${trim.flute_code}`, 'success');
        } else {
          // Revert the change in the UI
          trim[field] = originalValue;
          
          // Show error notification
          showNotification(response.data.message || `Failed to update ${field}`, 'error');
        }
      } catch (error) {
        console.error(`Error updating ${field}:`, error);
        
        // Revert the change in the UI
        trim[field] = originalValue;
        
        // Show detailed error notification
        const errorMessage = error.response?.data?.message || 
          `Failed to update ${field} for ${trim.flute_code}`;
        
        showNotification(errorMessage, 'error');
      } finally {
        // Clear loading state
        savingTrim[trim.id] = false;
      }
    };
    
    // Toggle compute value with immediate save
    const toggleCompute = async (trim) => {
      try {
        // Store the original value in case of rollback
        const originalValue = trim.compute;
        
        // Set loading state for this specific item
        savingTrim[trim.id] = true;
        
        // Prepare data for saving
        const dataToSave = {
          id: trim.id,
          flute_id: trim.flute_id,
          length_add: trim.length_add || 0,
          length_less: trim.length_less || 0,
          compute: !originalValue // Toggle the compute value
        };
        
        // Save the data
        const response = await axios.put(`/api/side-trims-by-flute/${trim.id}`, dataToSave);
        
        if (response.data.status === 'success') {
          // Update the local data with the server response
          trim.compute = response.data.data.compute;
          
          // Show small notification
          showNotification(`Compute status for ${trim.flute_code} updated successfully`, 'success');
        } else {
          // Revert the change in the UI
          trim.compute = originalValue;
          
          // Show error notification
          showNotification(response.data.message || `Failed to update compute status for ${trim.flute_code}`, 'error');
        }
      } catch (error) {
        console.error('Error toggling compute status:', error);
        
        // Revert the change in the UI
        trim.compute = originalValue;
        
        // Show detailed error notification
        const errorMessage = error.response?.data?.message || 
          `Failed to update compute status for ${trim.flute_code}`;
        
        showNotification(errorMessage, 'error');
      } finally {
        // Clear loading state
        savingTrim[trim.id] = false;
      }
    };

    const saveChanges = async () => {
      try {
        loading.value = true;
        
        // Prepare data for saving
        const dataToSave = sideTrims.value.map(trim => ({
          id: trim.id,
            flute_id: trim.flute_id,
          length_less: trim.length_less === '' ? null : parseInt(trim.length_less),
          length_add: trim.length_add === '' ? null : parseInt(trim.length_add),
          // Explicitly convert compute to numeric value
          compute: trim.compute ? 1 : 0
        }));
        
        // Send data to the API
        const response = await axios.post('/api/side-trims-by-flute/batch', dataToSave);
        
        if (response.data.status === 'success') {
          // Reload data to ensure consistency
          await loadData();
          
          Swal.fire({
            icon: 'success',
            title: 'Save Successful',
            text: `All side trims have been saved.`,
            confirmButtonText: 'OK'
          });
        } else if (response.data.errors && response.data.errors.length > 0) {
          const errorCount = response.data.errors.length;
          Swal.fire({
            icon: 'warning',
            title: 'Partial Update',
            html: `
              <p>Some side trims could not be saved:</p>
              <ul>
                ${response.data.errors.map(error => 
                  `<li>${error}</li>`
                ).join('')}
              </ul>
            `,
            confirmButtonText: 'OK'
          });
          
          // Reload data to reflect any successful updates
        await loadData();
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Save Failed',
            text: response.data.message || 'An unknown error occurred while saving.',
            confirmButtonText: 'OK'
          });
        }
      } catch (error) {
        console.error('Error saving side trims:', error);
        
        let errorMessage = 'Failed to save side trims';
        
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
          confirmButtonText: 'OK'
        });
      } finally {
        loading.value = false;
      }
    };

    const addSideTrim = async () => {
      if (!canAddSideTrim.value) {
        showNotification('Please fill in all required fields', 'error');
        return;
      }
      
      try {
        loading.value = true;
        
        // Check if this combination already exists
        const exists = sideTrims.value.some(trim => 
          trim.flute_id === newSideTrim.value.flute_id && 
          trim.compute === newSideTrim.value.compute
        );
        
        if (exists) {
          showNotification('This flute and compute combination already exists', 'error');
          loading.value = false;
          return;
        }
        
        // Add new side trim
        const response = await axios.post('/api/side-trims-by-flute', {
          flute_id: newSideTrim.value.flute_id,
          length_add: newSideTrim.value.length_add || 0,
          length_less: newSideTrim.value.length_less || 0,
          // Explicitly convert compute to numeric value
          compute: newSideTrim.value.compute ? 1 : 0
        });
        
        if (response.data && response.data.status === 'success') {
          // Show success message with details
          Swal.fire({
            icon: 'success',
            title: 'Side Trim Added',
            html: `
              <p>Successfully added side trim for:</p>
              <ul>
                <li>Flute: ${response.data.data.paper_flute?.code || 'N/A'}</li>
                <li>Length Add: ${response.data.data.length_add} mm</li>
                <li>Length Less: ${response.data.data.length_less} mm</li>
                <li>Compute: ${response.data.data.compute ? 'Yes' : 'No'}</li>
              </ul>
            `,
            confirmButtonText: 'OK'
          });
          
          // Reset form
          newSideTrim.value = {
            flute_id: '',
            length_add: '',
            length_less: '',
            compute: false
          };
          
          // Reload data
          await loadData();
        } else {
          // Show error message
          Swal.fire({
            icon: 'error',
            title: 'Failed to Add Side Trim',
            text: response.data.message || 'An unknown error occurred',
            confirmButtonText: 'OK'
          });
        }
      } catch (error) {
        console.error('Error adding side trim:', error);
        
        let errorMessage = 'Failed to add side trim';
        
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
        
        // Show error message
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: errorMessage,
          confirmButtonText: 'OK'
        });
      } finally {
        loading.value = false;
      }
    };

    onMounted(() => {
      loadData();
    });

    return {
      loading,
      sideTrims,
      filteredSideTrims,
      paginatedSideTrims,
      flutes,
      newSideTrim,
      searchQuery,
      currentPage,
      itemsPerPage,
      totalPages,
      savingTrim,
      canAddSideTrim,
      filterSideTrims,
      saveChanges,
      addSideTrim,
      showNotification,
      nextPage,
      prevPage,
      updateTrimValue,
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
