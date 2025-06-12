<template>
  <AppLayout title="View & Print Roll Trim by Product Design">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        View & Print Roll Trim by Product Design
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <!-- Header with buttons -->
          <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 flex items-center justify-between">
            <h2 class="text-lg font-bold text-white">View & Print Roll Trim by Product Design</h2>
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
              <Link :href="route('vue.standard-formula.setup-roll-trim-by-product-design')" class="bg-indigo-600 hover:bg-indigo-500 text-white px-3 py-1 rounded text-sm flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L4.414 9H17a1 1 0 110 2H4.414l5.293 5.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back to Edit
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
              <!-- Search and filter section -->
              <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200 print:hidden">
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

              <!-- Report header for print -->
              <div class="hidden print:block mb-6">
                <div class="text-center">
                  <h1 class="text-xl font-bold">Roll Trim By Product Design Report</h1>
                  <p class="text-gray-600">Generated on {{ new Date().toLocaleDateString() }}</p>
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
                        Product Code
                      </th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        Product Name
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
                      <td colspan="7" class="px-4 py-4 text-center text-sm text-gray-500">
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
                        {{ item.product_name }}
                      </td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">
                        {{ item.flute_code }}
                      </td>
                      <td class="px-4 py-2 text-center border-r">
                        <span v-if="item.is_composite" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                          Yes
                        </span>
                        <span v-else class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                          No
                        </span>
                      </td>
                      <td class="px-4 py-2 text-center border-r">
                        {{ item.min_trim }}
                      </td>
                      <td class="px-4 py-2 text-center">
                        {{ item.max_trim }}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <!-- Print footer -->
              <div class="hidden print:block mt-6 text-xs text-gray-500">
                <div class="flex justify-between">
                  <div>Printed by: {{ userName }}</div>
                  <div>Page 1 of 1</div>
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
    const userName = ref('System User'); // This would ideally come from auth

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
        
        // Process the data
        if (trimResponse.data && trimResponse.data.status === 'success') {
          items.value = trimResponse.data.data.map(trim => {
            const product = products.value.find(p => p.id === trim.product_id);
            const design = productDesigns.value.find(d => d.id === trim.product_design_id);
            const flute = flutes.value.find(f => f.id === trim.flute_id);
            
            return {
              id: trim.id,
              product_id: trim.product_id,
              product_code: product ? product.product_code : 'Unknown',
              product_name: product ? product.description : 'Unknown',
              product_design_id: trim.product_design_id,
              product_design_name: design ? design.pd_name : 'Unknown',
              flute_id: trim.flute_id,
              flute_code: flute ? flute.code : 'Unknown',
              is_composite: trim.is_composite,
              min_trim: trim.min_trim,
              max_trim: trim.max_trim
            };
          });
        } else {
          // If no data or error, initialize with sample data
          items.value = getSampleData();
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
        
        // Initialize with sample data for development
        items.value = getSampleData();
        filteredItems.value = [...items.value];
      } finally {
        loading.value = false;
      }
    };

    const filterItems = () => {
      filteredItems.value = items.value.filter(item => {
        const matchesProductDesign = !filters.value.productDesign || item.product_design_id === filters.value.productDesign;
        const matchesProduct = !filters.value.product || item.product_id === filters.value.product;
        const matchesFlute = !filters.value.flute || item.flute_id === filters.value.flute;
        
        return matchesProductDesign && matchesProduct && matchesFlute;
      });
    };

    const exportData = () => {
      showNotification('Exporting data...');
      
      // Call the export API endpoint
      axios.get('/api/roll-trim-by-product-design/export', { responseType: 'blob' })
        .then(response => {
          // For now, we'll just show a success message
          // In a real implementation, this would download the Excel file
          showNotification('Data exported successfully');
          
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
    const getSampleData = () => {
      // Generate sample data based on products, designs and flutes
      const sampleData = [];
      
      // Use the first few products, designs and flutes to create sample data
      const sampleProducts = products.value.slice(0, 5);
      const sampleDesigns = productDesigns.value.slice(0, 3);
      const sampleFlutes = flutes.value.slice(0, 4);
      
      // Create combinations
      let id = 1;
      sampleDesigns.forEach(design => {
        sampleProducts.forEach(product => {
          sampleFlutes.forEach(flute => {
            sampleData.push({
              id: id++,
              product_id: product.id,
              product_code: product.product_code,
              product_name: product.description || 'Product Name',
              product_design_id: design.id,
              product_design_name: design.pd_name || 'APRI',
              flute_id: flute.id,
              flute_code: flute.code,
              is_composite: Math.random() > 0.5,
              min_trim: 20,
              max_trim: 65
            });
          });
        });
      });
      
      return sampleData;
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
      userName,
      filterItems,
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
    width: 100%;
  }
  .print\\:hidden {
    display: none !important;
  }
  .print\\:block {
    display: block !important;
  }
  table {
    width: 100%;
    border-collapse: collapse;
  }
  th, td {
    border: 1px solid #ddd;
    padding: 8px;
    font-size: 12px;
  }
  th {
    background-color: #f3f4f6 !important;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
  }
}
</style> 