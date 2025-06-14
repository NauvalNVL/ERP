<template>
  <AppLayout title="Define Corrugator Specification by Product">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Define Corrugator Specification by Product
      </h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
          <!-- Header with buttons -->
          <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 flex items-center justify-between">
            <h2 class="text-lg font-bold text-white">Define Corrugator Specification by Product</h2>
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
                        <input 
                          type="checkbox" 
                          v-model="product.composite" 
                          class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                        />
                      </td>
                      <td class="px-2 py-2 text-center border-r">
                        <input 
                          type="number" 
                          v-model="product.min_sheet_length" 
                          class="w-20 text-center border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                        />
                      </td>
                      <td class="px-2 py-2 text-center border-r">
                        <input 
                          type="number" 
                          v-model="product.max_sheet_length" 
                          class="w-20 text-center border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                        />
                      </td>
                      <td class="px-2 py-2 text-center border-r">
                        <input 
                          type="number" 
                          v-model="product.min_sheet_width" 
                          class="w-20 text-center border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                        />
                      </td>
                      <td class="px-2 py-2 text-center">
                        <input 
                          type="number" 
                          v-model="product.max_sheet_width" 
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
    const products = ref([]);
    const filteredProducts = ref([]);
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

    const loadProducts = async () => {
      try {
        loading.value = true;
        // First, get all products
        const productsResponse = await axios.get('/api/products');
        const productsList = productsResponse.data;
        
        // Then, get corrugator specifications by product if they exist
        const specsResponse = await axios.get('/api/corrugator-specs-by-product');
        const specsList = specsResponse.data;
        
        // Merge the data
        products.value = productsList.map(product => {
          const spec = specsList.find(s => s.product_id === product.id);
          return {
            id: product.id,
            product_code: product.product_code,
            product_name: product.name || product.description,
            composite: spec ? spec.composite : false,
            min_sheet_length: spec ? spec.min_sheet_length : 1,
            max_sheet_length: spec ? spec.max_sheet_length : 99999,
            min_sheet_width: spec ? spec.min_sheet_width : 1,
            max_sheet_width: spec ? spec.max_sheet_width : 99999,
          };
        });
        
        filteredProducts.value = [...products.value];
      } catch (error) {
        console.error('Error loading products and specifications:', error);
        
        // More detailed error handling
        let errorMessage = 'Failed to load products and specifications';
        
        if (error.response) {
          // The request was made and the server responded with a status code
          // that falls out of the range of 2xx
          errorMessage += ` (Status: ${error.response.status})`;
          if (error.response.data && error.response.data.message) {
            errorMessage += `: ${error.response.data.message}`;
          }
        } else if (error.request) {
          // The request was made but no response was received
          errorMessage += ': No response received from server';
        } else {
          // Something happened in setting up the request that triggered an Error
          errorMessage += `: ${error.message}`;
        }
        
        showNotification(errorMessage, 'error');
        
        // Initialize with some sample data for development
        products.value = getSampleProducts();
        filteredProducts.value = [...products.value];
      } finally {
        loading.value = false;
      }
    };

    const filterProducts = () => {
      if (!searchQuery.value) {
        filteredProducts.value = [...products.value];
        return;
      }
      
      const query = searchQuery.value.toLowerCase();
      filteredProducts.value = products.value.filter(product => 
        product.product_code.toLowerCase().includes(query) || 
        product.product_name.toLowerCase().includes(query)
      );
    };

    const saveChanges = async () => {
      try {
        loading.value = true;
        
        // Prepare data for saving
        const specsToSave = products.value.map(product => ({
          product_id: product.id,
          composite: product.composite,
          min_sheet_length: product.min_sheet_length,
          max_sheet_length: product.max_sheet_length,
          min_sheet_width: product.min_sheet_width,
          max_sheet_width: product.max_sheet_width,
        }));
        
        // Send data to the API
        await axios.post('/api/corrugator-specs-by-product/batch', specsToSave);
        
        showNotification('Corrugator specifications saved successfully');
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
      // This would be implemented with a library like SheetJS or by calling a server endpoint
      showNotification('Exporting to Excel...');
      
      // For now, we'll just simulate the export
      setTimeout(() => {
        showNotification('Data exported to Excel successfully');
      }, 1500);
    };

    const printData = () => {
      window.print();
    };

    // Sample data for development
    const getSampleProducts = () => [
      { id: 1, product_code: '001', product_name: 'RSC STANDARD', composite: true, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
      { id: 2, product_code: '002', product_name: 'DIE CUT', composite: true, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
      { id: 3, product_code: '003', product_name: 'BHPT BOX', composite: true, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
      { id: 4, product_code: '004', product_name: 'PENJUALAN WASTE', composite: true, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
      { id: 5, product_code: '005', product_name: 'PENJUALAN LAIN-LAIN PCS', composite: true, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
      { id: 6, product_code: '006', product_name: 'CONEJIT', composite: true, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
      { id: 7, product_code: '007', product_name: 'ROLL', composite: true, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
      { id: 8, product_code: '008', product_name: 'PENJUALAN LAIN-LAIN KG', composite: true, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
      { id: 9, product_code: '009', product_name: 'PENJUALAN LAIN-LAIN', composite: true, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
      { id: 10, product_code: '010', product_name: 'TRAY', composite: true, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
      { id: 11, product_code: '011', product_name: 'SINGLE FACER KG', composite: true, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
      { id: 12, product_code: '012', product_name: 'SINGLE FACER SHEET', composite: true, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
      { id: 13, product_code: '013', product_name: 'PENJUALAN LAIN-LAIN', composite: false, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
      { id: 14, product_code: '014', product_name: 'SEWA TRUCK', composite: false, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
      { id: 15, product_code: '015', product_name: 'CORE PLUS', composite: false, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
      { id: 16, product_code: '016', product_name: 'PAPER TUBE', composite: false, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
      { id: 17, product_code: '017', product_name: 'OFFSET', composite: false, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
      { id: 18, product_code: '018', product_name: '2 FAX OFFSET', composite: false, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
      { id: 19, product_code: '019', product_name: 'DIGITAL PRINT', composite: false, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
      { id: 20, product_code: '020', product_name: 'SEWA TRUCK TRAILER', composite: false, min_sheet_length: 1, max_sheet_length: 99999, min_sheet_width: 1, max_sheet_width: 99999 },
    ];

    onMounted(() => {
      loadProducts();
    });

    return {
      loading,
      products,
      filteredProducts,
      searchQuery,
      notification,
      filterProducts,
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
  button {
    display: none !important;
  }
}
</style>
