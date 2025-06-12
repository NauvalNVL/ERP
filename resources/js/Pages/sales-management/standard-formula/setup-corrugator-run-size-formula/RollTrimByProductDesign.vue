<template>
  <AppLayout title="Define Roll Trim by Product Design">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Define Roll Trim by Product Design
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <!-- Header with buttons -->
          <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 flex items-center justify-between">
            <h2 class="text-lg font-bold text-white">Define Roll Trim by Product Design</h2>
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
              <!-- Search and filter section -->
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

              <!-- Table section -->
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border">
                  <thead class="bg-gray-100">
                    <tr>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        PD/Design
                      </th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        Product
                      </th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        Flute
                      </th>
                      <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        Composite
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
                      <td colspan="6" class="px-4 py-4 text-center text-sm text-gray-500">
                        No roll trim data found. Please try different filters or add new data.
                      </td>
                    </tr>
                    <tr v-for="item in filteredItems" :key="item.id" class="hover:bg-gray-50">
                      <td class="px-4 py-2 text-sm font-medium text-gray-900 border-r">
                        {{ item.product_design_name }}
                      </td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">
                        {{ item.product_code }}
                      </td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">
                        {{ item.flute_code }}
                      </td>
                      <td class="px-4 py-2 text-center border-r">
                        <input 
                          type="checkbox" 
                          v-model="item.is_composite"
                          class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                        />
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

            <!-- Product Name and PD/Design Name Display -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="border rounded-md p-4 bg-gray-50">
                <div class="mb-2">
                  <label class="block text-sm font-medium text-gray-700">Product Name:</label>
                  <div class="mt-1 p-2 bg-white border rounded-md">
                    {{ selectedProductName }}
                  </div>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">PD/Design Name:</label>
                  <div class="mt-1 p-2 bg-white border rounded-md">
                    {{ selectedPDName }}
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
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

export default defineComponent({
  components: {
    AppLayout
  },
  setup() {
    const loading = ref(true);
    const items = ref([]);
    const filteredItems = ref([]);
    const productDesigns = ref([]);
    const products = ref([]);
    const flutes = ref([]);
    const filters = ref({
      productDesign: '',
      product: '',
      flute: ''
    });
    const notification = ref({
      show: false,
      message: '',
      type: 'success'
    });
    
    // Define URL for View & Print page
    const viewPrintUrl = '/standard-formula/setup-roll-trim-by-product-design/view-print';

    const selectedProductName = computed(() => {
      if (!filters.value.product) return 'Select a product to view details';
      
      const product = products.value.find(p => p.id === filters.value.product);
      return product ? product.description || product.product_name || 'No name available' : 'Product not found';
    });

    const selectedPDName = computed(() => {
      if (!filters.value.productDesign) return 'Select a product design to view details';
      
      const design = productDesigns.value.find(d => d.id === filters.value.productDesign);
      return design ? design.pd_name || design.pd_alt_name || 'No name available' : 'Product design not found';
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
        
        if (trimResponse.data && trimResponse.data.status === 'success' && trimResponse.data.data.length > 0) {
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
              is_composite: trim.is_composite,
              min_trim: trim.min_trim,
              max_trim: trim.max_trim
            };
          });
        } else {
          // If no data from API, generate combinations of existing products, designs and flutes
          items.value = generateCombinations();
          
          // Show notification that we're using generated data
          showNotification('No roll trim data found. Using generated combinations for editing.', 'info');
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
        
        showNotification(errorMessage, 'error');
        
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
              is_composite: false,
              min_trim: 20,
              max_trim: 65
            });
          }
        }
      }
      
      return combinations;
    };

    const filterItems = () => {
      filteredItems.value = items.value.filter(item => {
        const matchesProductDesign = !filters.value.productDesign || item.product_design_id === filters.value.productDesign;
        const matchesProduct = !filters.value.product || item.product_id === filters.value.product;
        const matchesFlute = !filters.value.flute || item.flute_id === filters.value.flute;
        
        return matchesProductDesign && matchesProduct && matchesFlute;
      });
    };

    const saveChanges = async () => {
      try {
        loading.value = true;
        
        // Prepare data for saving
        const dataToSave = filteredItems.value.map(item => ({
          product_id: item.product_id,
          product_design_id: item.product_design_id,
          flute_id: item.flute_id,
          is_composite: item.is_composite,
          min_trim: item.min_trim,
          max_trim: item.max_trim
        }));
        
        // Send data to the API
        const promises = dataToSave.map(item => 
          axios.post('/api/roll-trim-by-product-design', item)
        );
        
        await Promise.all(promises);
        showNotification('Roll trim by product design specifications saved successfully');
        
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
        
        showNotification(errorMessage, 'error');
      } finally {
        loading.value = false;
      }
    };

    const exportData = () => {
      showNotification('Exporting data...');
      
      // Call the export API endpoint
      axios.get('/api/roll-trim-by-product-design/export', { responseType: 'blob' })
        .then(response => {
          // Create a download link for the exported file
          const blob = new Blob([response.data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
          const link = document.createElement('a');
          link.href = window.URL.createObjectURL(blob);
          link.download = `roll_trim_by_product_design_${new Date().toISOString().split('T')[0]}.xlsx`;
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
      items,
      filteredItems,
      productDesigns,
      products,
      flutes,
      filters,
      notification,
      selectedProductName,
      selectedPDName,
      viewPrintUrl,
      filterItems,
      saveChanges,
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
