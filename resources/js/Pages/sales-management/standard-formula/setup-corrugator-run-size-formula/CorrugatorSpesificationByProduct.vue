<template>
  <AppLayout title="Define Corrugator Specification by Product">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Define Corrugator Specification by Product
      </h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="p-6 sm:px-8 bg-white border-b border-gray-200">
            <div class="flex items-center justify-between">
              <div>
                <h2 class="text-2xl font-bold text-gray-800">Corrugator Specifications</h2>
                <p class="mt-1 text-sm text-gray-600">Manage specifications for each product.</p>
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
              <span class="ml-4 text-lg text-gray-700">Loading Products...</span>
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
                    @input="filterProducts"
                    placeholder="Search by product code or name..."
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                  />
                </div>
              </div>

              <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Product
                      </th>
                      <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Compute
                      </th>
                      <th scope="col" colspan="2" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Sheet Length (min/max)
                      </th>
                      <th scope="col" colspan="2" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Sheet Width (min/max)
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="filteredProducts.length === 0">
                      <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">
                        <div class="flex flex-col items-center">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                          </svg>
                          <h3 class="mt-2 text-lg font-medium text-gray-900">No products found</h3>
                          <p class="mt-1 text-sm text-gray-500">Please try a different search term.</p>
                        </div>
                      </td>
                    </tr>
                    <tr v-for="product in paginatedProducts" :key="product.id" class="hover:bg-gray-50 transition-colors duration-150">
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ product.product_code }}</div>
                        <div class="text-sm text-gray-500">{{ product.product_name }}</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-center">
                        <Switch
                            v-model="product.compute"
                          @update:modelValue="toggleCompute(product)"
                          :class="product.compute ? 'bg-blue-600' : 'bg-gray-200'"
                          class="relative inline-flex items-center h-6 rounded-full w-11 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            :disabled="savingCompute[product.id]"
                        >
                          <span class="sr-only">Enable compute</span>
                          <span
                            :class="product.compute ? 'translate-x-6' : 'translate-x-1'"
                            class="inline-block w-4 h-4 transform bg-white rounded-full transition-transform"
                        />
                          <div v-if="savingCompute[product.id]" class="absolute inset-0 flex items-center justify-center">
                            <div class="w-4 h-4 border-2 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                          </div>
                        </Switch>
                      </td>
                      <td class="px-1 py-4 whitespace-nowrap">
                        <input 
                          type="number" 
                          v-model.number="product.min_sheet_length" 
                          class="w-24 text-center border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                          placeholder="Min"
                        />
                      </td>
                      <td class="px-1 py-4 whitespace-nowrap">
                        <input 
                          type="number" 
                          v-model.number="product.max_sheet_length" 
                          class="w-24 text-center border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                          placeholder="Max"
                        />
                      </td>
                      <td class="px-1 py-4 whitespace-nowrap">
                        <input 
                          type="number" 
                          v-model.number="product.min_sheet_width" 
                          class="w-24 text-center border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                          placeholder="Min"
                        />
                      </td>
                      <td class="px-1 py-4 whitespace-nowrap">
                        <input 
                          type="number" 
                          v-model.number="product.max_sheet_width" 
                          class="w-24 text-center border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                          placeholder="Max"
                        />
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- Pagination -->
              <div v-if="filteredProducts.length > itemsPerPage" class="mt-6 flex items-center justify-between">
                <p class="text-sm text-gray-700">
                  Showing
                  <span class="font-medium">{{ (currentPage - 1) * itemsPerPage + 1 }}</span>
                  to
                  <span class="font-medium">{{ Math.min(currentPage * itemsPerPage, filteredProducts.length) }}</span>
                  of
                  <span class="font-medium">{{ filteredProducts.length }}</span>
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
import { defineComponent, ref, onMounted, reactive, computed } from 'vue';
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
    const products = ref([]);
    const filteredProducts = ref([]);
    const searchQuery = ref('');
    const savingCompute = reactive({});
    const currentPage = ref(1);
    const itemsPerPage = ref(10);

    const totalPages = computed(() => {
      return Math.ceil(filteredProducts.value.length / itemsPerPage.value);
    });

    const paginatedProducts = computed(() => {
      const start = (currentPage.value - 1) * itemsPerPage.value;
      const end = start + itemsPerPage.value;
      return filteredProducts.value.slice(start, end);
    });

    const nextPage = () => {
      if (currentPage.value < totalPages.value) {
        currentPage.value++;
      }
    };

    const prevPage = () => {
      if (currentPage.value > 1) {
        currentPage.value--;
      }
    };

    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
      }
    });

    const showNotification = (message, type = 'success') => {
      Toast.fire({
        icon: type,
        title: message
      });
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
        
        console.log('Products:', productsList);
        console.log('Specs:', specsList);
        
        // Merge the data
        products.value = productsList.map(product => {
          const spec = specsList.find(s => s.product_code === product.product_code);
          return {
            id: product.id,
            product_code: product.product_code,
            product_name: product.name || product.description,
            compute: spec ? !!spec.compute : false,
            min_sheet_length: spec ? spec.min_sheet_length : null,
            max_sheet_length: spec ? spec.max_sheet_length : null,
            min_sheet_width: spec ? spec.min_sheet_width : null,
            max_sheet_width: spec ? spec.max_sheet_width : null,
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
      currentPage.value = 1;
    };
    
    // Function to toggle compute value with immediate save
    const toggleCompute = async (product) => {
      try {
        // Set loading state for this specific product
        savingCompute[product.id] = true;
        
        // Prepare data for saving
        const specToSave = {
          product_code: product.product_code,
          compute: product.compute,
          min_sheet_length: product.min_sheet_length !== null && product.min_sheet_length !== undefined && product.min_sheet_length !== '' ? product.min_sheet_length : 1,
          max_sheet_length: product.max_sheet_length !== null && product.max_sheet_length !== undefined && product.max_sheet_length !== '' ? product.max_sheet_length : 99999,
          min_sheet_width: product.min_sheet_width !== null && product.min_sheet_width !== undefined && product.min_sheet_width !== '' ? product.min_sheet_width : 1,
          max_sheet_width: product.max_sheet_width !== null && product.max_sheet_width !== undefined && product.max_sheet_width !== '' ? product.max_sheet_width : 99999,
        };
        
        // Find if this product already has a spec
        const existingSpecResponse = await axios.get(`/api/corrugator-specs-by-product?product_code=${product.product_code}`);
        const existingSpec = existingSpecResponse.data.length > 0 ? existingSpecResponse.data[0] : null;
        
        if (existingSpec) {
          // Update existing spec
          await axios.put(`/api/corrugator-specs-by-product/${existingSpec.id}`, specToSave);
        } else {
          // Create new spec
          await axios.post('/api/corrugator-specs-by-product', specToSave);
        }
        
        // Show small notification
        showNotification(`Compute status for ${product.product_code} updated successfully`, 'success');
      } catch (error) {
        console.error('Error toggling compute status:', error);
        
        // Revert the change in the UI
        product.compute = !product.compute;
        
        // Show error notification
        showNotification(`Failed to update compute status for ${product.product_code}`, 'error');
      } finally {
        // Clear loading state
        savingCompute[product.id] = false;
      }
    };

    const saveChanges = async () => {
      try {
        loading.value = true;
        
        // Prepare data for saving
        const specsToSave = products.value.map(product => ({
          product_code: product.product_code,
          compute: product.compute,
          min_sheet_length: product.min_sheet_length !== null && product.min_sheet_length !== undefined && product.min_sheet_length !== '' ? product.min_sheet_length : 1,
          max_sheet_length: product.max_sheet_length !== null && product.max_sheet_length !== undefined && product.max_sheet_length !== '' ? product.max_sheet_length : 99999,
          min_sheet_width: product.min_sheet_width !== null && product.min_sheet_width !== undefined && product.min_sheet_width !== '' ? product.min_sheet_width : 1,
          max_sheet_width: product.max_sheet_width !== null && product.max_sheet_width !== undefined && product.max_sheet_width !== '' ? product.max_sheet_width : 99999,
        }));
        
        // Send data to the API
        const response = await axios.post('/api/corrugator-specs-by-product/batch', specsToSave);
        
        if (response.data.errors && response.data.errors.length > 0) {
          const errorCount = response.data.errors.length;
          const errorMessage = `${errorCount} specifications could not be saved.`;
          Swal.fire({
            icon: 'error',
            title: 'Batch Save Error',
            text: errorMessage,
          });
          console.error('Errors saving specifications:', response.data.errors);
        } else {
          Swal.fire({
            icon: 'success',
            title: 'Save Successful',
            text: `${specsToSave.length} corrugator specifications have been saved.`,
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

    // Sample data for development
    const getSampleProducts = () => [
      { id: 1, product_code: '001', product_name: 'RSC STANDARD', compute: true, min_sheet_length: null, max_sheet_length: null, min_sheet_width: null, max_sheet_width: null },
      { id: 2, product_code: '002', product_name: 'DIE CUT', compute: true, min_sheet_length: null, max_sheet_length: null, min_sheet_width: null, max_sheet_width: null },
      { id: 3, product_code: '003', product_name: 'BHPT BOX', compute: true, min_sheet_length: null, max_sheet_length: null, min_sheet_width: null, max_sheet_width: null },
      { id: 4, product_code: '004', product_name: 'PENJUALAN WASTE', compute: true, min_sheet_length: null, max_sheet_length: null, min_sheet_width: null, max_sheet_width: null },
      { id: 5, product_code: '005', product_name: 'PENJUALAN LAIN-LAIN PCS', compute: true, min_sheet_length: null, max_sheet_length: null, min_sheet_width: null, max_sheet_width: null },
      { id: 6, product_code: '006', product_name: 'CONEJIT', compute: true, min_sheet_length: null, max_sheet_length: null, min_sheet_width: null, max_sheet_width: null },
      { id: 7, product_code: '007', product_name: 'ROLL', compute: true, min_sheet_length: null, max_sheet_length: null, min_sheet_width: null, max_sheet_width: null },
      { id: 8, product_code: '008', product_name: 'PENJUALAN LAIN-LAIN KG', compute: true, min_sheet_length: null, max_sheet_length: null, min_sheet_width: null, max_sheet_width: null },
      { id: 9, product_code: '009', product_name: 'PENJUALAN LAIN-LAIN', compute: true, min_sheet_length: null, max_sheet_length: null, min_sheet_width: null, max_sheet_width: null },
      { id: 10, product_code: '010', product_name: 'TRAY', compute: true, min_sheet_length: null, max_sheet_length: null, min_sheet_width: null, max_sheet_width: null },
      { id: 11, product_code: '011', product_name: 'SINGLE FACER KG', compute: true, min_sheet_length: null, max_sheet_length: null, min_sheet_width: null, max_sheet_width: null },
      { id: 12, product_code: '012', product_name: 'SINGLE FACER SHEET', compute: true, min_sheet_length: null, max_sheet_length: null, min_sheet_width: null, max_sheet_width: null },
      { id: 13, product_code: '013', product_name: 'PENJUALAN LAIN-LAIN', compute: false, min_sheet_length: null, max_sheet_length: null, min_sheet_width: null, max_sheet_width: null },
      { id: 14, product_code: '014', product_name: 'SEWA TRUCK', compute: false, min_sheet_length: null, max_sheet_length: null, min_sheet_width: null, max_sheet_width: null },
      { id: 15, product_code: '015', product_name: 'CORE PLUS', compute: false, min_sheet_length: null, max_sheet_length: null, min_sheet_width: null, max_sheet_width: null },
      { id: 16, product_code: '016', product_name: 'PAPER TUBE', compute: false, min_sheet_length: null, max_sheet_length: null, min_sheet_width: null, max_sheet_width: null },
      { id: 17, product_code: '017', product_name: 'OFFSET', compute: false, min_sheet_length: null, max_sheet_length: null, min_sheet_width: null, max_sheet_width: null },
      { id: 18, product_code: '018', product_name: '2 FAX OFFSET', compute: false, min_sheet_length: null, max_sheet_length: null, min_sheet_width: null, max_sheet_width: null },
      { id: 19, product_code: '019', product_name: 'DIGITAL PRINT', compute: false, min_sheet_length: null, max_sheet_length: null, min_sheet_width: null, max_sheet_width: null },
      { id: 20, product_code: '020', product_name: 'SEWA TRUCK TRAILER', compute: false, min_sheet_length: null, max_sheet_length: null, min_sheet_width: null, max_sheet_width: null },
    ];

    onMounted(() => {
      loadProducts();
    });

    return {
      loading,
      products,
      filteredProducts,
      paginatedProducts,
      searchQuery,
      savingCompute,
      currentPage,
      itemsPerPage,
      totalPages,
      nextPage,
      prevPage,
      filterProducts,
      saveChanges,
      toggleCompute,
      showNotification
    };
  }
});
</script>

<style>
</style>
