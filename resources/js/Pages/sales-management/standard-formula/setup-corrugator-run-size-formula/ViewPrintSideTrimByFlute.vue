<template>
  <AppLayout title="View & Print Side Trim By Flute">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        View & Print Side Trim By Flute
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <!-- Header with buttons -->
          <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 flex items-center justify-between">
            <h2 class="text-lg font-bold text-white">View & Print Side Trim By Flute</h2>
            <div class="flex space-x-2">
              <button @click="exportData" class="bg-green-600 hover:bg-green-500 text-white px-3 py-1 rounded text-sm flex items-center">
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
              <a href="/standard-formula/setup-side-trim-by-flute" class="bg-indigo-600 hover:bg-indigo-500 text-white px-3 py-1 rounded text-sm flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back to Define
              </a>
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
              <!-- Filters -->
              <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <div>
                    <label for="flute-filter" class="block text-sm font-medium text-gray-700 mb-1">Filter by Flute</label>
                    <select
                      id="flute-filter"
                      v-model="filter.fluteId"
                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                      @change="filterData"
                    >
                      <option value="">All Flutes</option>
                      <option v-for="flute in flutes" :key="flute.id" :value="flute.id">
                        {{ flute.code }}
                      </option>
                    </select>
                  </div>
                  <div>
                    <label for="composite-filter" class="block text-sm font-medium text-gray-700 mb-1">Filter by Composite</label>
                    <select
                      id="composite-filter"
                      v-model="filter.isComposite"
                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                      @change="filterData"
                    >
                      <option value="">All</option>
                      <option value="true">Yes</option>
                      <option value="false">No</option>
                    </select>
                  </div>
                  <div class="flex items-end">
                    <button 
                      @click="resetFilters" 
                      class="bg-gray-500 hover:bg-gray-400 text-white px-4 py-2 rounded text-sm"
                    >
                      Reset Filters
                    </button>
                  </div>
                </div>
              </div>

              <!-- Table section -->
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border">
                  <thead class="bg-blue-700">
                    <tr>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border-r w-1/4">
                        Flute
                      </th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border-r w-1/4">
                        Flute Name
                      </th>
                      <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-white uppercase tracking-wider border-r w-1/6">
                        Composite
                      </th>
                      <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-white uppercase tracking-wider border-r w-1/6">
                        Length Add (mm)
                      </th>
                      <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-white uppercase tracking-wider border-r w-1/6">
                        Length Less (mm)
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="filteredSideTrims.length === 0" class="hover:bg-gray-50">
                      <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500">
                        No side trim data found matching the current filters.
                      </td>
                    </tr>
                    <tr 
                      v-for="(trim, index) in filteredSideTrims" 
                      :key="trim.id" 
                      class="hover:bg-gray-50"
                      :class="{ 'bg-yellow-100': index === 6 }"
                    >
                      <td class="px-4 py-2 text-sm font-medium text-gray-900 border-r">
                        {{ trim.flute_code }}
                      </td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">
                        {{ trim.flute_name }}
                      </td>
                      <td class="px-4 py-2 text-center border-r">
                        <span v-if="trim.is_composite" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                          Yes
                        </span>
                        <span v-else class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                          No
                        </span>
                      </td>
                      <td class="px-4 py-2 text-center text-sm text-gray-900 border-r">
                        {{ trim.length_add }}
                      </td>
                      <td class="px-4 py-2 text-center text-sm text-gray-900 border-r">
                        {{ trim.length_less }}
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
import { defineComponent, ref, computed, onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

export default defineComponent({
  components: {
    AppLayout
  },
  setup() {
    const loading = ref(true);
    const sideTrims = ref([]);
    const filteredSideTrims = ref([]);
    const flutes = ref([]);
    const filter = ref({
      fluteId: '',
      isComposite: ''
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
        
        // Load flutes
        const flutesResponse = await axios.get('/api/paper-flutes');
        flutes.value = flutesResponse.data;
        
        // Load side trims
        const trimsResponse = await axios.get('/api/side-trims-by-flute');
        
        if (trimsResponse.data && trimsResponse.data.status === 'success') {
          sideTrims.value = trimsResponse.data.data.map(trim => {
            const flute = trim.paper_flute || {};
            
            return {
              id: trim.id,
              flute_id: trim.flute_id,
              flute_code: flute.code || 'N/A',
              flute_name: flute.name || 'N/A',
              length_add: trim.length_add,
              length_less: trim.length_less,
              is_composite: trim.is_composite
            };
          });
          
          // Sort by flute code and then by composite status
          sideTrims.value.sort((a, b) => {
            if (a.flute_code === b.flute_code) {
              return a.is_composite === b.is_composite ? 0 : a.is_composite ? 1 : -1;
            }
            return a.flute_code.localeCompare(b.flute_code);
          });
          
          // Initialize filtered data
          filteredSideTrims.value = [...sideTrims.value];
        } else {
          sideTrims.value = [];
          filteredSideTrims.value = [];
          showNotification('No side trim data found.', 'info');
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

    const filterData = () => {
      filteredSideTrims.value = sideTrims.value.filter(trim => {
        const matchesFlute = !filter.value.fluteId || trim.flute_id === filter.value.fluteId;
        const matchesComposite = filter.value.isComposite === '' || 
          (filter.value.isComposite === 'true' && trim.is_composite) || 
          (filter.value.isComposite === 'false' && !trim.is_composite);
        
        return matchesFlute && matchesComposite;
      });
    };

    const resetFilters = () => {
      filter.value = {
        fluteId: '',
        isComposite: ''
      };
      filteredSideTrims.value = [...sideTrims.value];
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
      flutes,
      filter,
      notification,
      filterData,
      resetFilters,
      exportData,
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
  button, .bg-gray-50:not(.border) {
    display: none !important;
  }
}
</style> 