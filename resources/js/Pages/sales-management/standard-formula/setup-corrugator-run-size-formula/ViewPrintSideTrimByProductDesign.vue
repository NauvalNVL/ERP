<template>
  <AppLayout title="View & Print Side Trim By Product Design">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        View & Print Side Trim By Product Design
      </h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
          <!-- Header with buttons -->
          <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 flex items-center justify-between">
            <h2 class="text-lg font-bold text-white">View & Print Side Trim By Product Design</h2>
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
              <Link :href="backToDefineRoute" class="bg-indigo-600 hover:bg-indigo-500 text-white px-3 py-1 rounded text-sm flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L4.414 9H17a1 1 0 110 2H4.414l5.293 5.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back to Define
              </Link>
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
              <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200 print:hidden">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <div>
                    <label for="product-design-filter" class="block text-sm font-medium text-gray-700 mb-1">Product Design</label>
                    <select
                      id="product-design-filter"
                      v-model="filters.productDesignId"
                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                      @change="filterData"
                    >
                      <option value="">All Product Designs</option>
                      <option v-for="design in productDesigns" :key="design.id" :value="design.id">
                        {{ design.pd_name || design.code }}
                      </option>
                    </select>
                  </div>
                  
                  <div>
                    <label for="product-filter" class="block text-sm font-medium text-gray-700 mb-1">Product</label>
                    <select
                      id="product-filter"
                      v-model="filters.productId"
                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                      @change="filterData"
                    >
                      <option value="">All Products</option>
                      <option v-for="product in products" :key="product.id" :value="product.id">
                        {{ product.product_code || product.code }}
                      </option>
                    </select>
                  </div>
                  
                  <div>
                    <label for="flute-filter" class="block text-sm font-medium text-gray-700 mb-1">Flute</label>
                    <select
                      id="flute-filter"
                      v-model="filters.fluteId"
                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                      @change="filterData"
                    >
                      <option value="">All Flutes</option>
                      <option v-for="flute in flutes" :key="flute.id" :value="flute.id">
                        {{ flute.code }}
                      </option>
                    </select>
                  </div>
                </div>
                
                <!-- Reset Button -->
                <div class="mt-4 flex justify-end">
                  <button 
                    @click="resetFilters" 
                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                    </svg>
                    Reset Filters
                  </button>
                </div>
              </div>

              <!-- Report header for print -->
              <div class="hidden print:block mb-6">
                <div class="text-center">
                  <h1 class="text-xl font-bold">Side Trim By Product Design Report</h1>
                  <p class="text-gray-600">Generated on {{ formattedDate }}</p>
                </div>
              </div>

              <!-- Table section -->
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border">
                  <thead class="bg-blue-700">
                    <tr>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border-r">
                        P/Design
                      </th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border-r">
                        Product
                      </th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border-r">
                        Flute
                      </th>
                      <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-white uppercase tracking-wider border-r">
                        Composite
                      </th>
                      <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-white uppercase tracking-wider border-r">
                        Length Less (mm)
                      </th>
                      <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">
                        Length Add (mm)
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="filteredSideTrims.length === 0" class="hover:bg-gray-50">
                      <td colspan="6" class="px-4 py-4 text-center text-sm text-gray-500">
                        No side trim data found. Please try different filters or add new data.
                      </td>
                    </tr>
                    <tr v-for="(trim, index) in filteredSideTrims" :key="trim.id || index" 
                        :class="{ 'bg-yellow-100': index === 6 }"
                        class="hover:bg-gray-50">
                      <td class="px-4 py-2 text-sm font-medium text-gray-900 border-r">
                        {{ trim.product_design_name || trim.product_design_code || '—' }}
                      </td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">
                        {{ trim.product_code || '—' }}
                      </td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">
                        {{ trim.flute_code || '—' }}
                      </td>
                      <td class="px-4 py-2 text-center border-r">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                          :class="trim.is_composite ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                          {{ trim.is_composite ? 'Yes' : 'No' }}
                        </span>
                      </td>
                      <td class="px-4 py-2 text-center text-sm text-gray-900 border-r">
                        {{ trim.length_less }}
                      </td>
                      <td class="px-4 py-2 text-center text-sm text-gray-900">
                        {{ trim.length_add }}
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
                class="fixed bottom-4 right-4 w-80 p-4 rounded-lg shadow-lg border border-l-4 print:hidden"
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
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

export default defineComponent({
  components: {
    AppLayout,
    Link
  },
  setup() {
    const loading = ref(true);
    const sideTrims = ref([]);
    const filteredSideTrims = ref([]);
    const productDesigns = ref([]);
    const products = ref([]);
    const flutes = ref([]);
    const filters = ref({
      productDesignId: '',
      productId: '',
      fluteId: ''
    });
    const notification = ref({
      show: false,
      message: '',
      type: 'success'
    });
    
    // Define route for back button
    const backToDefineRoute = '/standard-formula/setup-side-trim-by-product-design';

    // Formatted current date
    const formattedDate = computed(() => {
      const now = new Date();
      return new Intl.DateTimeFormat('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      }).format(now);
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
        
        // Load side trim by product design data
        const trimResponse = await axios.get('/api/side-trims-by-product-design');
        
        if (trimResponse.data && trimResponse.data.status === 'success') {
          // Process the data from the API response
          sideTrims.value = trimResponse.data.data.map(trim => {
            // Get product design, product, and flute information
            const productDesign = productDesigns.value.find(d => d.id === trim.product_design_id);
            const product = products.value.find(p => p.id === trim.product_id);
            const flute = flutes.value.find(f => f.id === trim.flute_id);
            
            return {
              id: trim.id,
              product_design_id: trim.product_design_id,
              product_id: trim.product_id,
              flute_id: trim.flute_id,
              product_design_name: productDesign?.pd_name || productDesign?.code || 'APSI',
              product_code: product?.product_code || product?.code || '905',
              flute_code: flute?.code || '9589',
              is_composite: trim.is_composite,
              length_less: trim.length_less || 0,
              length_add: trim.length_add || 0
            };
          });
          
          // Sort by product design, product, and flute
          sideTrims.value.sort((a, b) => {
            if (a.product_design_name !== b.product_design_name) {
              return a.product_design_name.localeCompare(b.product_design_name);
            }
            if (a.product_code !== b.product_code) {
              return a.product_code.localeCompare(b.product_code);
            }
            return a.flute_code.localeCompare(b.flute_code);
          });
        } else {
          // If no data from API, use sample data
          sideTrims.value = getSampleData();
        }
        
        filteredSideTrims.value = [...sideTrims.value];
      } catch (error) {
        console.error('Error loading data:', error);
        
        // More detailed error handling
        let errorMessage = 'Failed to load side trim by product design data';
        
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
        sideTrims.value = getSampleData();
        filteredSideTrims.value = [...sideTrims.value];
      } finally {
        loading.value = false;
      }
    };

    const filterData = () => {
      filteredSideTrims.value = sideTrims.value.filter(trim => {
        const matchesProductDesign = !filters.value.productDesignId || trim.product_design_id === filters.value.productDesignId;
        const matchesProduct = !filters.value.productId || trim.product_id === filters.value.productId;
        const matchesFlute = !filters.value.fluteId || trim.flute_id === filters.value.fluteId;
        
        return matchesProductDesign && matchesProduct && matchesFlute;
      });
    };

    const resetFilters = () => {
      filters.value = {
        productDesignId: '',
        productId: '',
        fluteId: ''
      };
      filteredSideTrims.value = [...sideTrims.value];
    };

    const exportData = () => {
      showNotification('Exporting data...');
      
      // Call the export API endpoint
      axios({
        url: '/api/side-trims-by-product-design/export',
        method: 'GET',
        responseType: 'blob'
      }).then((response) => {
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', `side_trim_by_product_design_${new Date().toISOString().split('T')[0]}.csv`);
        document.body.appendChild(link);
        link.click();
        link.remove();
        showNotification('Data exported successfully');
      }).catch(error => {
        console.error('Error exporting data:', error);
        showNotification('Failed to export data', 'error');
      });
    };

    const printData = () => {
      window.print();
    };

    // Sample data for development/fallback
    const getSampleData = () => [
      { id: 1, product_design_id: 1, product_design_name: 'APSI', product_id: 1, product_code: '905', flute_id: 1, flute_code: '9589', is_composite: false, length_less: 15, length_add: 29 },
      { id: 2, product_design_id: 1, product_design_name: 'APSI', product_id: 1, product_code: '905', flute_id: 2, flute_code: '9589', is_composite: false, length_less: 5, length_add: 27 },
      { id: 3, product_design_id: 1, product_design_name: 'APSI', product_id: 1, product_code: '905', flute_id: 3, flute_code: '9589', is_composite: true, length_less: 11, length_add: 35 },
      { id: 4, product_design_id: 1, product_design_name: 'APSI', product_id: 1, product_code: '905', flute_id: 4, flute_code: '9589', is_composite: true, length_less: 18, length_add: 21 },
      { id: 5, product_design_id: 1, product_design_name: 'APSI', product_id: 1, product_code: '905', flute_id: 5, flute_code: '9589', is_composite: false, length_less: 8, length_add: 10 },
      { id: 6, product_design_id: 1, product_design_name: 'APSI', product_id: 1, product_code: '905', flute_id: 6, flute_code: '9589', is_composite: false, length_less: 7, length_add: 28 },
      { id: 7, product_design_id: 1, product_design_name: 'APSI', product_id: 1, product_code: '905', flute_id: 7, flute_code: '9589', is_composite: false, length_less: 19, length_add: 28 },
      { id: 8, product_design_id: 1, product_design_name: 'APSI', product_id: 1, product_code: '905', flute_id: 8, flute_code: '9589', is_composite: false, length_less: 5, length_add: 19 },
      { id: 9, product_design_id: 1, product_design_name: 'APSI', product_id: 1, product_code: '905', flute_id: 9, flute_code: '9589', is_composite: true, length_less: 25, length_add: 36 },
      { id: 10, product_design_id: 1, product_design_name: 'APSI', product_id: 1, product_code: '905', flute_id: 10, flute_code: '9589', is_composite: false, length_less: 9, length_add: 24 },
    ];

    onMounted(() => {
      loadData();
    });

    return {
      loading,
      sideTrims,
      filteredSideTrims,
      productDesigns,
      products,
      flutes,
      filters,
      notification,
      backToDefineRoute,
      formattedDate,
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
  @page {
    size: A4;
    margin: 1cm;
  }
  
  body {
    font-size: 12pt;
  }
  
  /* Hide non-printable elements */
  button, select, .print\:hidden, .bg-gray-50 {
    display: none !important;
  }
  
  /* Ensure tables fit on page */
  table {
    page-break-inside: avoid;
    width: 100%;
    border-collapse: collapse;
  }
  
  /* Show print-only elements */
  .hidden.print\:block {
    display: block !important;
  }
  
  /* Keep background colors for better printing */
  .bg-blue-700 {
    background-color: #1d4ed8 !important;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
  }
  
  .bg-yellow-100 {
    background-color: #fef3c7 !important;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
  }
  
  /* Ensure text is visible */
  .text-white {
    color: #fff !important;
  }
  
  /* Make borders more visible */
  .border, .border-r {
    border-color: #000 !important;
  }
}
</style> 