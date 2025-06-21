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
                <button @click="exportData" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring focus:ring-green-300 disabled:opacity-25 transition">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Export
              </button>
                <button @click="printData" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print
              </button>
                <a :href="viewPrintUrl" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                View & Print
              </a>
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
                      <td class="px-6 py-4 whitespace-nowrap text-center">
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
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="relative">
                        <input 
                          type="number" 
                          v-model="trim.length_less" 
                            @change="updateTrimValue(trim, 'length_less')" 
                            class="w-24 text-center border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            :disabled="savingTrim[trim.id]"
                        />
                          <div v-if="savingTrim[trim.id]" class="absolute right-0 top-1/2 transform -translate-y-1/2 mr-2">
                            <div class="w-3 h-3 border-2 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                          </div>
                        </div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="relative">
                        <input 
                          type="number" 
                          v-model="trim.length_add" 
                            @change="updateTrimValue(trim, 'length_add')" 
                            class="w-24 text-center border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            :disabled="savingTrim[trim.id]"
                        />
                          <div v-if="savingTrim[trim.id]" class="absolute right-0 top-1/2 transform -translate-y-1/2 mr-2">
                            <div class="w-3 h-3 border-2 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                          </div>
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
    const viewPrintUrl = '/standard-formula/setup-side-trim-by-flute/view-print';
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
             newSideTrim.value.length_add >= 0 && 
             newSideTrim.value.length_less >= 0;
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
        
        // Load flutes
        const flutesResponse = await axios.get('/api/paper-flutes');
        flutes.value = flutesResponse.data;
        
        // Load side trims
        const trimsResponse = await axios.get('/api/side-trims-by-flute');
        
        if (trimsResponse.data && trimsResponse.data.status === 'success' && Array.isArray(trimsResponse.data.data) && trimsResponse.data.data.length > 0) {
          console.log('Received data from API:', trimsResponse.data.data);
          // Process the data from the API response
          sideTrims.value = trimsResponse.data.data.map(trim => {
            const flute = trim.paper_flute || {};
            
            return {
              id: trim.id,
              flute_id: trim.flute_id,
              flute_code: flute.code || 'N/A',
              flute_name: flute.name || 'N/A',
              length_add: trim.length_add,
              length_less: trim.length_less,
              compute: trim.compute === 1 || trim.compute === true
            };
          });
          
          // Sort by flute code and then by compute status
          sideTrims.value.sort((a, b) => {
            if (a.flute_code === b.flute_code) {
              return a.compute === b.compute ? 0 : a.compute ? 1 : -1;
            }
            return a.flute_code.localeCompare(b.flute_code);
          });
          
          filteredSideTrims.value = [...sideTrims.value];
          console.log('Processed side trims:', sideTrims.value);
        } else {
          console.log('No data found or invalid response format:', trimsResponse.data);
          // If no data from API, seed the database first
          try {
            showNotification('No side trim data found. Seeding initial data...', 'info');
            await axios.post('/api/side-trims-by-flute/seed');
            showNotification('Initial data has been seeded. Loading data...', 'success');
            
            // Try to fetch the data again after seeding
            const newTrimsResponse = await axios.get('/api/side-trims-by-flute');
            if (newTrimsResponse.data && newTrimsResponse.data.status === 'success' && Array.isArray(newTrimsResponse.data.data) && newTrimsResponse.data.data.length > 0) {
              sideTrims.value = newTrimsResponse.data.data.map(trim => {
                const flute = trim.paper_flute || {};
                
                return {
                  id: trim.id,
                  flute_id: trim.flute_id,
                  flute_code: flute.code || 'N/A',
                  flute_name: flute.name || 'N/A',
                  length_add: trim.length_add,
                  length_less: trim.length_less,
                  compute: trim.compute === 1 || trim.compute === true
                };
              });
              
              // Sort by flute code and then by compute status
              sideTrims.value.sort((a, b) => {
                if (a.flute_code === b.flute_code) {
                  return a.compute === b.compute ? 0 : a.compute ? 1 : -1;
                }
                return a.flute_code.localeCompare(b.flute_code);
              });
              
              filteredSideTrims.value = [...sideTrims.value];
            } else {
              sideTrims.value = [];
              filteredSideTrims.value = [];
              showNotification('Failed to load side trim data after seeding', 'error');
            }
          } catch (seedError) {
            console.error('Error seeding data:', seedError);
            sideTrims.value = [];
            filteredSideTrims.value = [];
            showNotification('Failed to seed side trim data', 'error');
          }
        }
      } catch (error) {
        console.error('Error loading data:', error);
        
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
        // Don't save if already saving this item
        if (savingTrim[trim.id]) {
          return;
        }
        
        // Set saving state
        savingTrim[trim.id] = true;
        
        // Store original value
        const originalValue = trim[field];
        
        // Prepare data for saving
        const response = await axios.put(`/api/side-trims-by-flute/${trim.id}`, {
          flute_id: trim.flute_id,
          length_add: trim.length_add,
          length_less: trim.length_less,
          compute: trim.compute
        });
        
        if (response.data && response.data.status === 'success') {
          showNotification(`Updated ${field.replace('_', ' ')} successfully`, 'success');
          
          // Update the local data with the server response to ensure consistency
          if (response.data.data) {
            trim.compute = response.data.data.compute === 1 || response.data.data.compute === true;
            trim.length_add = response.data.data.length_add;
            trim.length_less = response.data.data.length_less;
          }
        } else {
          showNotification(`Failed to update ${field.replace('_', ' ')}`, 'error');
          // Revert to original value
          trim[field] = originalValue;
        }
      } catch (error) {
        console.error(`Error updating ${field}:`, error);
        
        // Revert to original value
        trim[field] = originalValue;
        
        let errorMessage = `Failed to update ${field.replace('_', ' ')}`;
        
        if (error.response && error.response.data) {
          if (error.response.data.message) {
            errorMessage = error.response.data.message;
          } else if (error.response.data.errors) {
            const firstError = Object.values(error.response.data.errors)[0];
            errorMessage = Array.isArray(firstError) ? firstError[0] : firstError;
          }
        } else if (error.message) {
          errorMessage += `: ${error.message}`;
        }
        
        showNotification(errorMessage, 'error');
      } finally {
        // Clear saving state after a short delay to show the spinner
        setTimeout(() => {
          savingTrim[trim.id] = false;
        }, 500);
      }
    };
    
    // Toggle compute value with immediate save
    const toggleCompute = async (trim) => {
      try {
        // Set saving state for this specific trim
        savingTrim[trim.id] = true;
        
        // Store the original value in case we need to revert
        const originalValue = trim.compute;
        
        // Prepare data for saving
        const response = await axios.put(`/api/side-trims-by-flute/${trim.id}`, {
          flute_id: trim.flute_id,
          length_add: trim.length_add,
          length_less: trim.length_less,
          compute: trim.compute
        });
        
        if (response.data && response.data.status === 'success') {
          showNotification(`Compute status for ${trim.flute_code} updated successfully`, 'success');
          
          // Update the local data with the server response to ensure consistency
          if (response.data.data) {
            trim.compute = response.data.data.compute === 1 || response.data.data.compute === true;
            trim.length_add = response.data.data.length_add;
            trim.length_less = response.data.data.length_less;
          }
        } else {
          showNotification(`Failed to update compute status for ${trim.flute_code}`, 'error');
          // Revert the change in UI
          trim.compute = originalValue;
        }
      } catch (error) {
        console.error('Error toggling compute status:', error);
        
        // Revert the change in UI
        trim.compute = !trim.compute;
        
        let errorMessage = `Failed to update compute status for ${trim.flute_code}`;
        
        if (error.response && error.response.data) {
          if (error.response.data.message) {
            errorMessage = error.response.data.message;
          } else if (error.response.data.errors) {
            const firstError = Object.values(error.response.data.errors)[0];
            errorMessage = Array.isArray(firstError) ? firstError[0] : firstError;
          }
        } else if (error.message) {
          errorMessage += `: ${error.message}`;
        }
        
        showNotification(errorMessage, 'error');
      } finally {
        // Clear saving state after a short delay to show the spinner
        setTimeout(() => {
          savingTrim[trim.id] = false;
        }, 500);
      }
    };

    const saveChanges = async () => {
      try {
        loading.value = true;
        
        // Prepare data for saving
        const promises = sideTrims.value.map(trim => 
          axios.put(`/api/side-trims-by-flute/${trim.id}`, {
            flute_id: trim.flute_id,
            length_add: trim.length_add,
            length_less: trim.length_less,
            compute: trim.compute
          })
        );
        
        await Promise.all(promises);
        showNotification('All side trim data saved successfully');
        
        // Reload data to get the updated records
        await loadData();
      } catch (error) {
        console.error('Error saving side trims:', error);
        
        let errorMessage = 'Failed to save side trims';
        
        if (error.response && error.response.data) {
          if (error.response.data.message) {
            errorMessage = error.response.data.message;
          } else if (error.response.data.errors) {
            const firstError = Object.values(error.response.data.errors)[0];
            errorMessage = Array.isArray(firstError) ? firstError[0] : firstError;
          }
        } else if (error.message) {
          errorMessage += `: ${error.message}`;
        }
        
        showNotification(errorMessage, 'error');
        
        // Reload data to ensure UI is consistent with database
        await loadData();
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
          showNotification('This flute and compute status combination already exists', 'error');
          loading.value = false;
          return;
        }
        
        // Add new side trim
        const response = await axios.post('/api/side-trims-by-flute', {
          flute_id: newSideTrim.value.flute_id,
          length_add: newSideTrim.value.length_add,
          length_less: newSideTrim.value.length_less,
          compute: newSideTrim.value.compute
        });
        
        if (response.data && response.data.status === 'success') {
          showNotification('Side trim added successfully');
          
          // Reset form
          newSideTrim.value = {
            flute_id: '',
            compute: false,
            length_add: 0,
            length_less: 0
          };
          
          // Reload data
          await loadData();
        } else {
          showNotification('Failed to add side trim', 'error');
        }
      } catch (error) {
        console.error('Error adding side trim:', error);
        
        let errorMessage = 'Failed to add side trim';
        
        if (error.response && error.response.data) {
          if (error.response.data.message) {
            errorMessage = error.response.data.message;
          } else if (error.response.data.errors) {
            const firstError = Object.values(error.response.data.errors)[0];
            errorMessage = Array.isArray(firstError) ? firstError[0] : firstError;
          }
        } else if (error.message) {
          errorMessage += `: ${error.message}`;
        }
        
        // Reload data to ensure UI is consistent with database
        await loadData();
        
        showNotification(errorMessage, 'error');
      } finally {
        loading.value = false;
      }
    };

    const exportData = () => {
      showNotification('Exporting data...');
      
      // Call the export API endpoint
      axios.get('/api/side-trims-by-flute/export', { responseType: 'blob' })
        .then(response => {
          // Create a download link for the exported file
          const blob = new Blob([response.data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
          const link = document.createElement('a');
          link.href = window.URL.createObjectURL(blob);
          link.download = `side_trims_by_flute_${new Date().toISOString().split('T')[0]}.xlsx`;
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
      sideTrims,
      filteredSideTrims,
      paginatedSideTrims,
      flutes,
      newSideTrim,
      viewPrintUrl,
      searchQuery,
      currentPage,
      itemsPerPage,
      totalPages,
      savingTrim,
      canAddSideTrim,
      saveChanges,
      addSideTrim,
      exportData,
      printData,
      showNotification,
      nextPage,
      prevPage,
      filterSideTrims,
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
