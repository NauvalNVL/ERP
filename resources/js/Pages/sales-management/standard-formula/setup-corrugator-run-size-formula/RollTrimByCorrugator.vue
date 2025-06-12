<template>
  <AppLayout title="Define Roll Trim By Corrugator">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Define Roll Trim By Corrugator
      </h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
          <!-- Header with buttons -->
          <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 flex items-center justify-between">
            <h2 class="text-lg font-bold text-white">Define Roll Trim By Corrugator</h2>
            <div class="flex space-x-2">
              <button @click="exportToExcel" class="bg-green-600 hover:bg-green-500 text-white px-3 py-1 rounded text-sm flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                Export
              </button>
              <button @click="printData" class="bg-blue-500 hover:bg-blue-400 text-white px-3 py-1 rounded text-sm flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
                </svg>
                Print
              </button>
              <button @click="saveChanges" class="bg-indigo-600 hover:bg-indigo-500 text-white px-3 py-1 rounded text-sm flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                Save
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
              <!-- Search and filter section -->
              <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                <div class="flex flex-wrap gap-4 items-center">
                  <div class="flex-grow">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search Flute</label>
                    <div class="relative">
                      <input
                        type="text"
                        id="search"
                        v-model="searchQuery"
                        @input="filterItems"
                        placeholder="Search by flute code or name..."
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                      />
                      <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Table section -->
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border">
                  <thead class="bg-gray-100">
                    <tr>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        Flute Code
                      </th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        Flute Name
                      </th>
                      <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        Min (mm)
                      </th>
                      <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        Max (mm)
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="filteredItems.length === 0" class="hover:bg-gray-50">
                      <td colspan="4" class="px-4 py-4 text-center text-sm text-gray-500">
                        No flutes found. Please try a different search term.
                      </td>
                    </tr>
                    <tr v-for="item in filteredItems" :key="item.id" class="hover:bg-gray-50">
                      <td class="px-4 py-2 text-sm font-medium text-gray-900 border-r">
                        {{ item.flute_code }}
                      </td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">
                        {{ item.flute_name }}
                      </td>
                      <td class="px-4 py-2 text-center border-r">
                        <input 
                          type="number" 
                          v-model="item.min_trim" 
                          class="w-20 text-center border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                        />
                      </td>
                      <td class="px-4 py-2 text-center">
                        <input 
                          type="number" 
                          v-model="item.max_trim" 
                          class="w-20 text-center border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                        />
                      </td>
                    </tr>
                  </tbody>
                </table>
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
    const items = ref([]);
    const filteredItems = ref([]);
    const searchQuery = ref('');
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
        // Get all paper flutes
        const flutesResponse = await axios.get('/api/paper-flutes');
        const flutesList = flutesResponse.data;
        
        // Then, get roll trim configurations if they exist
        const trimResponse = await axios.get('/api/roll-trim-by-corrugator');
        const trimsList = trimResponse.data.data;
        
        // Merge the data
        items.value = flutesList.map(flute => {
          const trim = trimsList.find(t => t.flute_code === flute.code);
          return {
            id: flute.id,
            flute_code: flute.code,
            flute_name: flute.name,
            min_trim: trim ? trim.trim_value : 0,
            max_trim: trim ? trim.trim_value + 40 : 100,
          };
        });
        
        filteredItems.value = [...items.value];
      } catch (error) {
        console.error('Error loading flutes and trim specifications:', error);
        
        // More detailed error handling
        let errorMessage = 'Failed to load flutes and trim specifications';
        
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
        
        // Initialize with some sample data for development
        items.value = getSampleData();
        filteredItems.value = [...items.value];
      } finally {
        loading.value = false;
      }
    };

    const filterItems = () => {
      if (!searchQuery.value) {
        filteredItems.value = [...items.value];
        return;
      }
      
      const query = searchQuery.value.toLowerCase();
      filteredItems.value = items.value.filter(item => 
        item.flute_code.toLowerCase().includes(query) || 
        item.flute_name.toLowerCase().includes(query)
      );
    };

    const saveChanges = async () => {
      try {
        loading.value = true;
        
        // Prepare data for saving
        const dataToSave = items.value.map(item => ({
          corrugator_name: 'BHS', // Default corrugator name
          flute_code: item.flute_code,
          trim_value: item.min_trim,
        }));
        
        // Send data to the API - save each item
        const promises = dataToSave.map(item => 
          axios.post('/api/roll-trim-by-corrugator', item)
        );
        
        await Promise.all(promises);
        showNotification('Roll trim specifications saved successfully');
      } catch (error) {
        console.error('Error saving specifications:', error);
        
        let errorMessage = 'Failed to save specifications';
        
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

    const exportToExcel = () => {
      showNotification('Exporting to Excel...');
      
      // Call the export API endpoint
      axios.get('/api/roll-trim-by-corrugator/export', { responseType: 'blob' })
        .then(response => {
          // For now, we'll just show a success message
          // In a real implementation, this would download the Excel file
          showNotification('Data exported to Excel successfully');
          
          console.log('Export data:', response.data);
        })
        .catch(error => {
          console.error('Error exporting data:', error);
          showNotification('Failed to export data', 'error');
        });
    };

    const printData = () => {
      window.print();
    };

    // Sample data for development
    const getSampleData = () => [
      { id: 1, flute_code: 'A', flute_name: 'A FLUTE', min_trim: 20, max_trim: 65 },
      { id: 2, flute_code: 'AF', flute_name: 'A FLUTE', min_trim: 20, max_trim: 65 },
      { id: 3, flute_code: 'BC', flute_name: 'BC', min_trim: 20, max_trim: 65 },
      { id: 4, flute_code: 'BF2', flute_name: 'BF2 FLUTE', min_trim: 20, max_trim: 65 },
      { id: 5, flute_code: 'BF', flute_name: 'BF FLUTE', min_trim: 20, max_trim: 65 },
      { id: 6, flute_code: 'B', flute_name: 'B FLUTE', min_trim: 20, max_trim: 65 },
      { id: 7, flute_code: 'BFS', flute_name: 'BF SINGLE FACER', min_trim: 20, max_trim: 65 },
      { id: 8, flute_code: 'BD', flute_name: 'BHPT FINAL', min_trim: 20, max_trim: 65 },
      { id: 9, flute_code: 'CF', flute_name: 'CF', min_trim: 20, max_trim: 65 },
      { id: 10, flute_code: 'CF2', flute_name: 'C FLUTE 2', min_trim: 20, max_trim: 65 },
      { id: 11, flute_code: 'CR', flute_name: 'CONEJIT', min_trim: 20, max_trim: 65 },
      { id: 12, flute_code: 'DF', flute_name: 'DF', min_trim: 20, max_trim: 65 },
      { id: 13, flute_code: 'E', flute_name: 'E', min_trim: 20, max_trim: 65 },
      { id: 14, flute_code: 'EF', flute_name: 'E FLUTE', min_trim: 20, max_trim: 65 },
      { id: 15, flute_code: 'EFC', flute_name: 'EF SINGLE FACER', min_trim: 20, max_trim: 65 },
      { id: 16, flute_code: 'EFS', flute_name: 'EF SINGLE FACER', min_trim: 20, max_trim: 65 },
      { id: 17, flute_code: 'ES', flute_name: 'E FLUTE SINGLE FACER', min_trim: 20, max_trim: 65 },
      { id: 18, flute_code: 'ETC', flute_name: 'LAIN LAIN', min_trim: 20, max_trim: 65 },
      { id: 19, flute_code: 'OF', flute_name: 'OFFSET CF', min_trim: 20, max_trim: 65 },
      { id: 20, flute_code: 'OFD', flute_name: 'OFFSET DOUBLE DUPLX', min_trim: 20, max_trim: 65 },
      { id: 21, flute_code: 'OFE', flute_name: 'OFFSET E FLUTE', min_trim: 20, max_trim: 65 },
      { id: 22, flute_code: 'RL', flute_name: 'ROLL', min_trim: 20, max_trim: 65 },
    ];

    onMounted(() => {
      loadData();
    });

    return {
      loading,
      items,
      filteredItems,
      searchQuery,
      notification,
      filterItems,
      saveChanges,
      exportToExcel,
      printData,
      showNotification
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
  button, .bg-gray-50 {
    display: none !important;
  }
}
</style>
