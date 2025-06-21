<template>
  <AppLayout title="Define Roll Trim By Corrugator">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Define Roll Trim By Corrugator
      </h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="p-6 sm:px-8 bg-white border-b border-gray-200">
            <div class="flex items-center justify-between">
              <div>
                <h2 class="text-2xl font-bold text-gray-800">Roll Trim Settings</h2>
                <p class="mt-1 text-sm text-gray-600">Manage roll trim specifications for each flute.</p>
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
                        Compute
                      </th>
                      <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Min Trim (mm)
                      </th>
                      <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Max Trim (mm)
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="paginatedItems.length === 0">
                      <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-500">
                        <div class="flex flex-col items-center">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                          </svg>
                          <h3 class="mt-2 text-lg font-medium text-gray-900">No flutes found</h3>
                          <p class="mt-1 text-sm text-gray-500">Please try a different search term.</p>
                        </div>
                      </td>
                    </tr>
                    <tr v-for="item in paginatedItems" :key="item.id" class="hover:bg-gray-50 transition-colors duration-150">
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ item.flute_code }}</div>
                        <div class="text-sm text-gray-500">{{ item.flute_name }}</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-center">
                        <Switch
                          v-model="item.compute"
                          @update:modelValue="toggleCompute(item)"
                          :class="item.compute ? 'bg-blue-600' : 'bg-gray-200'"
                          class="relative inline-flex items-center h-6 rounded-full w-11 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                          :disabled="savingCompute[item.id]"
                        >
                          <span class="sr-only">Enable compute</span>
                          <span
                            :class="item.compute ? 'translate-x-6' : 'translate-x-1'"
                            class="inline-block w-4 h-4 transform bg-white rounded-full transition-transform"
                          />
                          <div v-if="savingCompute[item.id]" class="absolute inset-0 flex items-center justify-center">
                            <div class="w-4 h-4 border-2 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                          </div>
                        </Switch>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-center">
                        <input 
                          type="number" 
                          v-model.number="item.min_trim"
                          class="w-24 text-center border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                          placeholder="Min"
                        />
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-center">
                        <input 
                          type="number" 
                          v-model.number="item.max_trim" 
                          class="w-24 text-center border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                          placeholder="Max"
                          disabled
                        />
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
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import { defineComponent, ref, onMounted, reactive, computed, watch } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Switch } from '@headlessui/vue';
import axios from 'axios';
import Swal from 'sweetalert2';

export default defineComponent({
  components: {
    AppLayout,
    Switch,
  },
  setup() {
    const loading = ref(true);
    const items = ref([]);
    const filteredItems = ref([]);
    const searchQuery = ref('');
    const savingCompute = reactive({});
    const currentPage = ref(1);
    const itemsPerPage = ref(10);

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

    // Watch for changes to min_trim and update max_trim accordingly
    const updateMaxTrim = (item) => {
      if (item.min_trim !== null && item.min_trim !== undefined) {
        item.max_trim = item.min_trim + 10;
      }
    };

    const loadData = async () => {
      try {
        loading.value = true;
        const [flutesResponse, trimsResponse] = await Promise.all([
          axios.get('/api/paper-flutes'),
          axios.get('/api/roll-trim-by-corrugator')
        ]);
        
        const flutesList = flutesResponse.data;
        const trimsList = trimsResponse.data;
        
        items.value = flutesList.map(flute => {
          const trim = trimsList.find(t => t.flute_id === flute.id);
          return {
            id: flute.id, // Use flute id as the key
            flute_id: flute.id,
            flute_code: flute.code,
            flute_name: flute.name,
            compute: trim ? !!trim.compute : false,
            min_trim: trim ? trim.min_trim : 0,
            max_trim: trim ? trim.max_trim : 10,
          };
        });
        
        filteredItems.value = [...items.value];
      } catch (error) {
        console.error('Error loading data:', error);
        let errorMessage = 'Failed to load data';
        if (error.response && error.response.data && error.response.data.message) {
            errorMessage += `: ${error.response.data.message}`;
        }
        Swal.fire({
          icon: 'error',
          title: 'Loading Error',
          text: errorMessage,
        });
      } finally {
        loading.value = false;
      }
    };

    const filterItems = () => {
      currentPage.value = 1;
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

    // Function to toggle compute value with immediate save
    const toggleCompute = async (item) => {
      try {
        // Set loading state for this specific item
        savingCompute[item.id] = true;
        
        // Prepare data for saving
        const specToSave = {
          flute_id: item.flute_id,
          compute: item.compute,
          min_trim: item.min_trim !== null && item.min_trim !== undefined && item.min_trim !== '' ? item.min_trim : 0,
          max_trim: item.max_trim !== null && item.max_trim !== undefined && item.max_trim !== '' ? item.max_trim : 10,
        };
        
        // Save the data
        const response = await axios.post('/api/roll-trim-by-corrugator/batch', [specToSave]);
        
        if (response.data.results && response.data.results.length > 0) {
          const updatedItem = response.data.results[0];
          item.min_trim = updatedItem.min_trim;
          item.max_trim = updatedItem.max_trim;
        }
        
        // Show small notification
        showNotification(`Compute status for ${item.flute_code} updated successfully`, 'success');
      } catch (error) {
        console.error('Error toggling compute status:', error);
        
        // Revert the change in the UI
        item.compute = !item.compute;
        
        // Show error notification
        showNotification(`Failed to update compute status for ${item.flute_code}`, 'error');
      } finally {
        // Clear loading state
        savingCompute[item.id] = false;
      }
    };

    const saveChanges = async () => {
      try {
        loading.value = true;
        const specsToSave = items.value.map(item => ({
          flute_id: item.flute_id,
          compute: item.compute,
          min_trim: item.min_trim !== null && item.min_trim !== undefined && item.min_trim !== '' ? item.min_trim : 0,
          max_trim: item.max_trim !== null && item.max_trim !== undefined && item.max_trim !== '' ? item.max_trim : 10,
        }));
        
        const response = await axios.post('/api/roll-trim-by-corrugator/batch', specsToSave);
        
        if (response.data.errors && response.data.errors.length > 0) {
          const errorCount = response.data.errors.length;
          Swal.fire({
            icon: 'error',
            title: 'Batch Save Error',
            text: `${errorCount} specifications could not be saved.`,
          });
          console.error('Errors saving specifications:', response.data.errors);
        } else {
          // Update items with the returned results
          if (response.data.results && response.data.results.length > 0) {
            response.data.results.forEach(result => {
              const item = items.value.find(i => i.flute_id === result.flute_id);
              if (item) {
                item.min_trim = result.min_trim;
                item.max_trim = result.max_trim;
              }
            });
          }
          
          Swal.fire({
            icon: 'success',
            title: 'Save Successful',
            text: `All roll trim specifications have been saved.`,
          });
        }
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
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: errorMessage,
        });
      } finally {
        loading.value = false;
      }
    };

    // Watch for changes to min_trim and update max_trim accordingly
    watch(items, (newItems) => {
      newItems.forEach(item => {
        watch(() => item.min_trim, () => updateMaxTrim(item), { immediate: true });
      });
    }, { deep: true });

    onMounted(loadData);

    return {
      loading,
      items,
      filteredItems,
      paginatedItems,
      searchQuery,
      savingCompute,
      currentPage,
      itemsPerPage,
      totalPages,
      filterItems,
      saveChanges,
      toggleCompute,
      showNotification,
      nextPage,
      prevPage,
      updateMaxTrim,
    };
  }
});
</script>

<style>
/* No additional styles needed */
</style>
