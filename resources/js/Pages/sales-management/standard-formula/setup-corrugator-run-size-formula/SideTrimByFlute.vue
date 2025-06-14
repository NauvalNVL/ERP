<template>
  <AppLayout title="Define Side Trim By Flute">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Define Side Trim By Flute
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <!-- Header with buttons -->
          <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 flex items-center justify-between">
            <h2 class="text-lg font-bold text-white">Define Side Trim By Flute</h2>
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
              <a :href="viewPrintUrl" class="bg-indigo-600 hover:bg-indigo-500 text-white px-3 py-1 rounded text-sm flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                  <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                </svg>
                View & Print
              </a>
              <button @click="saveChanges" class="bg-green-700 hover:bg-green-600 text-white px-3 py-1 rounded text-sm flex items-center">
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
                    <tr v-if="sideTrims.length === 0" class="hover:bg-gray-50">
                      <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500">
                        No side trim data found. Please add new data.
                      </td>
                    </tr>
                    <tr 
                      v-for="(trim, index) in sideTrims" 
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
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">
                        <input 
                          type="number" 
                          v-model="trim.length_add" 
                          class="w-full text-center border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                        />
                      </td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">
                        <input 
                          type="number" 
                          v-model="trim.length_less" 
                          class="w-full text-center border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                        />
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Add New Side Trim -->
            <div class="mt-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
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
                  <label for="new-composite" class="block text-sm font-medium text-gray-700 mb-1">Composite</label>
                  <select
                    id="new-composite"
                    v-model="newSideTrim.is_composite"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                  >
                    <option :value="false">No</option>
                    <option :value="true">Yes</option>
                  </select>
                </div>
                
                <div>
                  <label for="new-length-add" class="block text-sm font-medium text-gray-700 mb-1">Length Add (mm)</label>
                  <input
                    id="new-length-add"
                    type="number"
                    v-model="newSideTrim.length_add"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                  />
                </div>
                
                <div>
                  <label for="new-length-less" class="block text-sm font-medium text-gray-700 mb-1">Length Less (mm)</label>
                  <input
                    id="new-length-less"
                    type="number"
                    v-model="newSideTrim.length_less"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                  />
                </div>
                
                <div class="flex items-end">
                  <button 
                    @click="addSideTrim" 
                    class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded text-sm"
                    :disabled="!canAddSideTrim"
                  >
                    Add Side Trim
                  </button>
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
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

export default defineComponent({
  components: {
    AppLayout
  },
  setup() {
    const loading = ref(true);
    const sideTrims = ref([]);
    const flutes = ref([]);
    const viewPrintUrl = '/standard-formula/setup-side-trim-by-flute/view-print';
    
    const newSideTrim = ref({
      flute_id: '',
      is_composite: false,
      length_add: 0,
      length_less: 0
    });
    
    const notification = ref({
      show: false,
      message: '',
      type: 'success'
    });

    const canAddSideTrim = computed(() => {
      return newSideTrim.value.flute_id && 
             newSideTrim.value.length_add >= 0 && 
             newSideTrim.value.length_less >= 0;
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
        } else {
          sideTrims.value = [];
          showNotification('No side trim data found. Please add new data.', 'info');
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
      } finally {
        loading.value = false;
      }
    };

    const saveChanges = async () => {
      try {
        loading.value = true;
        
        // Prepare data for saving
        const promises = sideTrims.value.map(trim => 
          axios.post('/api/side-trims-by-flute', {
            flute_id: trim.flute_id,
            length_add: trim.length_add,
            length_less: trim.length_less,
            is_composite: trim.is_composite
          })
        );
        
        await Promise.all(promises);
        showNotification('Side trim data saved successfully');
        
        // Reload data to get the updated records
        await loadData();
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
        
        showNotification(errorMessage, 'error');
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
          trim.is_composite === newSideTrim.value.is_composite
        );
        
        if (exists) {
          showNotification('This flute and composite status combination already exists', 'error');
          loading.value = false;
          return;
        }
        
        // Add new side trim
        const response = await axios.post('/api/side-trims-by-flute', {
          flute_id: newSideTrim.value.flute_id,
          length_add: newSideTrim.value.length_add,
          length_less: newSideTrim.value.length_less,
          is_composite: newSideTrim.value.is_composite
        });
        
        if (response.data && response.data.status === 'success') {
          showNotification('Side trim added successfully');
          
          // Reset form
          newSideTrim.value = {
            flute_id: '',
            is_composite: false,
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
      flutes,
      newSideTrim,
      notification,
      viewPrintUrl,
      canAddSideTrim,
      saveChanges,
      addSideTrim,
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
