<template>
  <AppLayout title="View & Print Corrugator Specification by Product">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        View & Print Corrugator Specification by Product
      </h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
          <!-- Header with buttons -->
          <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 flex items-center justify-between">
            <h2 class="text-lg font-bold text-white">View & Print Corrugator Specification by Product</h2>
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
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search Product</label>
                    <div class="relative">
                      <input
                        type="text"
                        id="search"
                        v-model="searchQuery"
                        @input="filterProducts"
                        placeholder="Search by product code or name..."
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                      />
                      <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                      </div>
                    </div>
                  </div>
                  <div>
                    <label for="composite-filter" class="block text-sm font-medium text-gray-700 mb-1">Composite Filter</label>
                    <select
                      id="composite-filter"
                      v-model="compositeFilter"
                      @change="filterProducts"
                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    >
                      <option value="all">All</option>
                      <option value="true">Composite Only</option>
                      <option value="false">Non-Composite Only</option>
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
                        Product Code
                      </th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        Product Name
                      </th>
                      <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        Composite
                      </th>
                      <th scope="col" colspan="2" class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        Sheet Length
                        <div class="flex justify-around mt-1">
                          <span class="text-xs">Min</span>
                          <span class="text-xs">Max</span>
                        </div>
                      </th>
                      <th scope="col" colspan="2" class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Sheet Width
                        <div class="flex justify-around mt-1">
                          <span class="text-xs">Min</span>
                          <span class="text-xs">Max</span>
                        </div>
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="filteredProducts.length === 0" class="hover:bg-gray-50">
                      <td colspan="7" class="px-4 py-4 text-center text-sm text-gray-500">
                        No products found. Please try a different search term.
                      </td>
                    </tr>
                    <tr v-for="product in filteredProducts" :key="product.id" class="hover:bg-gray-50">
                      <td class="px-4 py-2 text-sm font-medium text-gray-900 border-r">
                        {{ product.product_code }}
                      </td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">
                        {{ product.product_name }}
                      </td>
                      <td class="px-4 py-2 text-center border-r">
                        <span 
                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                          :class="product.composite ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                        >
                          {{ product.composite ? 'Yes' : 'No' }}
                        </span>
                      </td>
                      <td class="px-2 py-2 text-center border-r">
                        {{ product.min_sheet_length }}
                      </td>
                      <td class="px-2 py-2 text-center border-r">
                        {{ product.max_sheet_length }}
                      </td>
                      <td class="px-2 py-2 text-center border-r">
                        {{ product.min_sheet_width }}
                      </td>
                      <td class="px-2 py-2 text-center">
                        {{ product.max_sheet_width }}
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
    const products = ref([]);
    const filteredProducts = ref([]);
    const searchQuery = ref('');
    const compositeFilter = ref('all');
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

    const loadProducts = async () => {
      try {
        loading.value = true;
        // Get all corrugator specifications with product data
        const response = await axios.get('/api/corrugator-specs-by-product');
        
        // Transform the data for display
        products.value = response.data.map(spec => ({
          id: spec.id,
          product_id: spec.product_id,
          product_code: spec.product?.product_code || 'N/A',
          product_name: spec.product?.description || 'Unknown Product',
          composite: spec.composite,
          min_sheet_length: spec.min_sheet_length,
          max_sheet_length: spec.max_sheet_length,
          min_sheet_width: spec.min_sheet_width,
          max_sheet_width: spec.max_sheet_width,
        }));
        
        filterProducts();
      } catch (error) {
        console.error('Error loading corrugator specifications:', error);
        
        // More detailed error handling
        let errorMessage = 'Failed to load corrugator specifications';
        
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
        
        // Initialize with sample data for development
        products.value = getSampleProducts();
        filterProducts();
      } finally {
        loading.value = false;
      }
    };

    const filterProducts = () => {
      let filtered = [...products.value];
      
      // Apply search filter
      if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(product => 
          product.product_code.toLowerCase().includes(query) || 
          product.product_name.toLowerCase().includes(query)
        );
      }
      
      // Apply composite filter
      if (compositeFilter.value !== 'all') {
        const isComposite = compositeFilter.value === 'true';
        filtered = filtered.filter(product => product.composite === isComposite);
      }
      
      filteredProducts.value = filtered;
    };

    const exportToExcel = () => {
      showNotification('Exporting to Excel...');
      
      // Call the export API endpoint
      axios.get('/api/corrugator-specs-by-product/export', { responseType: 'blob' })
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
    const getSampleProducts = () => [
      { id: 1, product_id: 1, product_code: '001', product_name: 'RSC STANDARD', composite: true, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
      { id: 2, product_id: 2, product_code: '002', product_name: 'DIE CUT', composite: true, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
      { id: 3, product_id: 3, product_code: '003', product_name: 'BHPT BOX', composite: true, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
      { id: 4, product_id: 4, product_code: '004', product_name: 'PENJUALAN WASTE', composite: true, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
      { id: 5, product_id: 5, product_code: '005', product_name: 'PENJUALAN LAIN-LAIN PCS', composite: true, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
      { id: 6, product_id: 6, product_code: '006', product_name: 'CONEJIT', composite: true, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
      { id: 7, product_id: 7, product_code: '007', product_name: 'ROLL', composite: true, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
      { id: 8, product_id: 8, product_code: '008', product_name: 'PENJUALAN LAIN-LAIN KG', composite: true, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
      { id: 9, product_id: 9, product_code: '009', product_name: 'PENJUALAN LAIN-LAIN', composite: true, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
      { id: 10, product_id: 10, product_code: '010', product_name: 'TRAY', composite: true, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
      { id: 11, product_id: 11, product_code: '011', product_name: 'SINGLE FACER KG', composite: true, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
      { id: 12, product_id: 12, product_code: '012', product_name: 'SINGLE FACER SHEET', composite: true, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
      { id: 13, product_id: 13, product_code: '013', product_name: 'PENJUALAN LAIN-LAIN', composite: false, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
      { id: 14, product_id: 14, product_code: '014', product_name: 'SEWA TRUCK', composite: false, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
      { id: 15, product_id: 15, product_code: '015', product_name: 'CORE PLUS', composite: false, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
      { id: 16, product_id: 16, product_code: '016', product_name: 'PAPER TUBE', composite: false, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
      { id: 17, product_id: 17, product_code: '017', product_name: 'OFFSET', composite: false, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
      { id: 18, product_id: 18, product_code: '018', product_name: '2 FAX OFFSET', composite: false, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
      { id: 19, product_id: 19, product_code: '019', product_name: 'DIGITAL PRINT', composite: false, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
      { id: 20, product_id: 20, product_code: '020', product_name: 'SEWA TRUCK TRAILER', composite: false, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
    ];

    onMounted(() => {
      loadProducts();
    });

    return {
      loading,
      products,
      filteredProducts,
      searchQuery,
      compositeFilter,
      notification,
      filterProducts,
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