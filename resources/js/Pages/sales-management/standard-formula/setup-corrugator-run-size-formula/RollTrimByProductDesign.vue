<template>
  <AppLayout title="Define Roll Trim by Product Design">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Define Roll Trim by Product Design
      </h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="p-6 sm:px-8 bg-white border-b border-gray-200">
            <div class="flex items-center justify-between">
              <div>
                <h2 class="text-2xl font-bold text-gray-800">Roll Trim Settings</h2>
                <p class="mt-1 text-sm text-gray-600">Manage roll trim specifications for each product design.</p>
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
              <span class="ml-4 text-lg text-gray-700">Loading Data...</span>
            </div>

            <div v-else>
              <!-- Search and filter section -->
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
                    @input="filterBySearch"
                    placeholder="Search by product code or design name..."
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                  />
                </div>
              </div>

              <!-- Filters section -->
              <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <!-- Product Design Filter -->
                  <div>
                    <label for="product-design-filter" class="block text-sm font-medium text-gray-700 mb-1">Product Design</label>
                    <select
                      id="product-design-filter"
                      v-model="filters.productDesign"
                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                      @change="filterItems"
                    >
                      <option value="">All Product Designs</option>
                      <option v-for="design in productDesigns" :key="design.id" :value="design.id">
                        {{ design.pd_name }}
                      </option>
                    </select>
                  </div>
                  
                  <!-- Product Filter -->
                  <div>
                    <label for="product-filter" class="block text-sm font-medium text-gray-700 mb-1">Product</label>
                    <select
                      id="product-filter"
                      v-model="filters.product"
                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                      @change="filterItems"
                    >
                      <option value="">All Products</option>
                      <option v-for="product in products" :key="product.id" :value="product.id">
                        {{ product.product_code }}
                      </option>
                    </select>
                  </div>
                  
                  <!-- Flute Filter -->
                  <div>
                    <label for="flute-filter" class="block text-sm font-medium text-gray-700 mb-1">Flute</label>
                    <select
                      id="flute-filter"
                      v-model="filters.flute"
                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                      @change="filterItems"
                    >
                      <option value="">All Flutes</option>
                      <option v-for="flute in flutes" :key="flute.id" :value="flute.id">
                        {{ flute.code }}
                      </option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        P/Design
                      </th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Product
                      </th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Flute
                      </th>
                      <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Compute
                      </th>
                      <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Min [mm]
                      </th>
                      <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Max [mm]
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="paginatedItems.length === 0">
                      <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">
                        <div class="flex flex-col items-center">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                          </svg>
                          <h3 class="mt-2 text-lg font-medium text-gray-900">No data found</h3>
                          <p class="mt-1 text-sm text-gray-500">Please try different filters.</p>
                        </div>
                      </td>
                    </tr>
                    <tr v-for="item in paginatedItems" :key="item.id" class="hover:bg-gray-50 transition-colors duration-150">
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ item.product_design_name }}</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ item.product_code }}</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ item.flute_code }}</div>
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
    const items = ref([]);
    const filteredItems = ref([]);
    const productDesigns = ref([]);
    const products = ref([]);
    const flutes = ref([]);
    const searchQuery = ref('');
    const filters = ref({
      productDesign: '',
      product: '',
      flute: ''
    });
    const currentPage = ref(1);
    const itemsPerPage = ref(10);
    const savingCompute = reactive({});

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

    const loadData = async () => {
      try {
        loading.value = true;
        
        // Load product designs
        const designsResponse = await axios.get('/api/product-designs');
        productDesigns.value = designsResponse.data;
        
        // Load products
        const productsResponse = await axios.get('/api/products');
        products.value = productsResponse.data;
        
        // Load flutes
        const flutesResponse = await axios.get('/api/paper-flutes');
        flutes.value = flutesResponse.data;
        
        // Load roll trim by product design data
        const trimResponse = await axios.get('/api/roll-trim-by-product-design');
        
        if (trimResponse.data && trimResponse.data.status === 'success' && Array.isArray(trimResponse.data.data) && trimResponse.data.data.length > 0) {
          // Process the data from the API response
          items.value = trimResponse.data.data.map(trim => {
            // Get related objects using the relationships from the API
            const product = trim.product || {};
            const design = trim.product_design || {};
            const flute = trim.paper_flute || {};
            
            return {
              id: trim.id,
              product_id: trim.product_id,
              product_code: product.product_code || 'N/A',
              product_design_id: trim.product_design_id,
              product_design_name: design.pd_name || design.pd_alt_name || 'N/A',
              flute_id: trim.flute_id,
              flute_code: flute.code || 'N/A',
              compute: trim.compute === 1 || trim.compute === true,
              min_trim: trim.min_trim,
              max_trim: trim.max_trim
            };
          });
        } else {
          // If no data from API, seed the database first
          try {
            await axios.post('/api/roll-trim-by-product-design/seed');
            showNotification('Initial data has been seeded. Loading data...', 'success');
            
            // Try to fetch the data again after seeding
            const newTrimResponse = await axios.get('/api/roll-trim-by-product-design');
            if (newTrimResponse.data && newTrimResponse.data.status === 'success' && Array.isArray(newTrimResponse.data.data) && newTrimResponse.data.data.length > 0) {
              items.value = newTrimResponse.data.data.map(trim => {
                const product = trim.product || {};
                const design = trim.product_design || {};
                const flute = trim.paper_flute || {};
                
                return {
                  id: trim.id,
                  product_id: trim.product_id,
                  product_code: product.product_code || 'N/A',
                  product_design_id: trim.product_design_id,
                  product_design_name: design.pd_name || design.pd_alt_name || 'N/A',
                  flute_id: trim.flute_id,
                  flute_code: flute.code || 'N/A',
                  compute: trim.compute === 1 || trim.compute === true,
                  min_trim: trim.min_trim,
                  max_trim: trim.max_trim
                };
              });
            } else {
              // If still no data, generate combinations
              items.value = generateCombinations();
              showNotification('No roll trim data found. Using generated combinations for editing.', 'info');
            }
          } catch (seedError) {
            console.error('Error seeding data:', seedError);
            items.value = generateCombinations();
            showNotification('No roll trim data found. Using generated combinations for editing.', 'info');
          }
        }
        
        filteredItems.value = [...items.value];
      } catch (error) {
        console.error('Error loading data:', error);
        
        // More detailed error handling
        let errorMessage = 'Failed to load roll trim by product design data';
        
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
        
        Swal.fire({
          icon: 'error',
          title: 'Loading Error',
          text: errorMessage,
        });
        
        // Initialize with generated combinations as fallback
        items.value = generateCombinations();
        filteredItems.value = [...items.value];
      } finally {
        loading.value = false;
      }
    };

    // Generate combinations of products, designs and flutes
    const generateCombinations = () => {
      const combinations = [];
      let id = 1;
      
      // Use all available products, designs and flutes
      for (const design of productDesigns.value) {
        for (const product of products.value) {
          for (const flute of flutes.value) {
            combinations.push({
              id: id++,
              product_id: product.id,
              product_code: product.product_code,
              product_design_id: design.id,
              product_design_name: design.pd_name || design.pd_alt_name || 'N/A',
              flute_id: flute.id,
              flute_code: flute.code,
              compute: false,
              min_trim: 20,
              max_trim: 65
            });
          }
        }
      }
      
      return combinations;
    };

    const filterBySearch = () => {
      currentPage.value = 1;
      applyFilters();
    };

    const filterItems = () => {
      currentPage.value = 1;
      applyFilters();
    };

    const applyFilters = () => {
      filteredItems.value = items.value.filter(item => {
        // Apply search filter
        const matchesSearch = !searchQuery.value || 
          item.product_code.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
          item.product_design_name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
          item.flute_code.toLowerCase().includes(searchQuery.value.toLowerCase());
        
        // Apply dropdown filters
        const matchesProductDesign = !filters.value.productDesign || item.product_design_id === filters.value.productDesign;
        const matchesProduct = !filters.value.product || item.product_id === filters.value.product;
        const matchesFlute = !filters.value.flute || item.flute_id === filters.value.flute;
        
        return matchesSearch && matchesProductDesign && matchesProduct && matchesFlute;
      });
    };

    // Function to toggle compute value with immediate save
    const toggleCompute = async (item) => {
      try {
        // Set loading state for this specific item
        savingCompute[item.id] = true;
        
        // Prepare data for saving
        const specToSave = {
          product_id: item.product_id,
          product_design_id: item.product_design_id,
          flute_id: item.flute_id,
          compute: item.compute,
          min_trim: item.min_trim !== null && item.min_trim !== undefined && item.min_trim !== '' ? item.min_trim : 0,
          max_trim: item.max_trim !== null && item.max_trim !== undefined && item.max_trim !== '' ? item.max_trim : 99999,
        };
        
        // Save the data
        await axios.post('/api/roll-trim-by-product-design', specToSave);
        
        // Show small notification
        showNotification(`Compute status updated successfully`, 'success');
      } catch (error) {
        console.error('Error toggling compute status:', error);
        
        // Revert the change in the UI
        item.compute = !item.compute;
        
        // Show error notification
        showNotification(`Failed to update compute status`, 'error');
      } finally {
        // Clear loading state
        savingCompute[item.id] = false;
      }
    };

    const saveChanges = async () => {
      try {
        loading.value = true;
        
        // Prepare data for saving
        const dataToSave = filteredItems.value.map(item => ({
          product_id: item.product_id,
          product_design_id: item.product_design_id,
          flute_id: item.flute_id,
          compute: item.compute,
          min_trim: item.min_trim !== null && item.min_trim !== undefined && item.min_trim !== '' ? item.min_trim : 0,
          max_trim: item.max_trim !== null && item.max_trim !== undefined && item.max_trim !== '' ? item.max_trim : 99999,
        }));
        
        // Send data to the API
        const response = await axios.post('/api/roll-trim-by-product-design/batch', dataToSave);
        
        if (response.data.errors && response.data.errors.length > 0) {
          const errorCount = response.data.errors.length;
          Swal.fire({
            icon: 'error',
            title: 'Batch Save Error',
            text: `${errorCount} specifications could not be saved.`,
          });
          console.error('Errors saving specifications:', response.data.errors);
        } else {
          Swal.fire({
            icon: 'success',
            title: 'Save Successful',
            text: `All roll trim specifications have been saved.`,
          });
        }
        
        // Reload data to get the updated records from the server
        await loadData();
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

    onMounted(() => {
      loadData();
    });

    return {
      loading,
      items,
      filteredItems,
      paginatedItems,
      productDesigns,
      products,
      flutes,
      filters,
      searchQuery,
      currentPage,
      itemsPerPage,
      totalPages,
      savingCompute,
      filterItems,
      filterBySearch,
      saveChanges,
      toggleCompute,
      showNotification,
      nextPage,
      prevPage
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
