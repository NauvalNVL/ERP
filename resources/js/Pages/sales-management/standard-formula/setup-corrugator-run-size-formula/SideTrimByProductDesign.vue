<template>
  <AppLayout title="Define Side Trim By Product Design">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Define Side Trim By Product Design
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <!-- Header with buttons -->
          <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 flex items-center justify-between">
            <h2 class="text-lg font-bold text-white">Define Side Trim By Product Design</h2>
            <div class="flex space-x-2">
              <button @click="exportData" class="bg-green-600 hover:bg-green-500 text-white px-3 py-1 rounded text-sm flex items-center">
                <i class="fas fa-file-excel mr-1"></i> Export
              </button>
              <button @click="printData" class="bg-blue-500 hover:bg-blue-400 text-white px-3 py-1 rounded text-sm flex items-center">
                <i class="fas fa-print mr-1"></i> Print
              </button>
              <a :href="viewPrintUrl" class="bg-indigo-600 hover:bg-indigo-500 text-white px-3 py-1 rounded text-sm flex items-center">
                <i class="fas fa-eye mr-1"></i> View & Print
              </a>
              <button @click="saveChanges" class="bg-green-700 hover:bg-green-600 text-white px-3 py-1 rounded text-sm flex items-center">
                <i class="fas fa-save mr-1"></i> Save
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
                      <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border-r">P/Design</th>
                      <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border-r">Product</th>
                      <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border-r">Flute</th>
                      <th class="px-4 py-3 text-center text-xs font-medium text-white uppercase tracking-wider border-r">Composite</th>
                      <th class="px-4 py-3 text-center text-xs font-medium text-white uppercase tracking-wider border-r">Length Less (mm)</th>
                      <th class="px-4 py-3 text-center text-xs font-medium text-white uppercase tracking-wider border-r">Length Add (mm)</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="sideTrims.length === 0">
                      <td colspan="6" class="px-4 py-4 text-center text-sm text-gray-500">
                        No side trim data found. Please add new data.
                      </td>
                    </tr>
                    <tr v-for="(trim, index) in sideTrims" :key="trim.id || index" :class="{ 'bg-yellow-100': index === 6 }">
                      <td class="px-4 py-2 text-sm font-medium text-gray-900 border-r">{{ trim.product_design_code || 'APSI' }}</td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">{{ trim.product_code || '905' }}</td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">{{ trim.flute_code }}</td>
                      <td class="px-4 py-2 text-center border-r">
                        <input type="checkbox" v-model="trim.is_composite" class="form-checkbox h-4 w-4 text-green-600" />
                      </td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">
                        <input type="number" v-model="trim.length_less" class="w-full text-center border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                      </td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">
                        <input type="number" v-model="trim.length_add" class="w-full text-center border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Add New Side Trim -->
            <div class="mt-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
              <h3 class="text-lg font-medium text-gray-700 mb-3">Add New Side Trim</h3>
              <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Product Design</label>
                  <select v-model="newSideTrim.product_design_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    <option value="">Select</option>
                    <option v-for="design in productDesigns" :key="design.id" :value="design.id">{{ design.code }}</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Product</label>
                  <select v-model="newSideTrim.product_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    <option value="">Select</option>
                    <option v-for="product in products" :key="product.id" :value="product.id">{{ product.code }}</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Flute</label>
                  <select v-model="newSideTrim.flute_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    <option value="">Select</option>
                    <option v-for="flute in flutes" :key="flute.id" :value="flute.id">{{ flute.code }}</option>
                  </select>
                </div>
                <div class="flex items-center">
                  <input type="checkbox" v-model="newSideTrim.is_composite" class="form-checkbox h-4 w-4 text-green-600 mr-2" />
                  <label class="text-sm font-medium text-gray-700">Composite</label>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Length Less (mm)</label>
                  <input type="number" v-model="newSideTrim.length_less" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Length Add (mm)</label>
                  <input type="number" v-model="newSideTrim.length_add" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" />
                </div>
                <div class="flex items-end">
                  <button @click="addSideTrim" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded text-sm" :disabled="!canAddSideTrim">Add Side Trim</button>
                </div>
              </div>
            </div>

            <!-- Notification -->
            <transition enter-active-class="transform ease-out duration-300 transition" enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2" enter-to-class="translate-y-0 opacity-100 sm:translate-x-0" leave-active-class="transition ease-in duration-200" leave-from-class="opacity-100" leave-to-class="opacity-0">
              <div v-if="notification.show" class="fixed bottom-4 right-4 w-80 p-4 rounded-lg shadow-lg border border-l-4" :class="notification.type === 'success' ? 'bg-green-50 border-green-500 text-green-800' : 'bg-red-50 border-red-500 text-red-800'">
                <div class="flex items-center">
                  <div class="flex-shrink-0">
                    <i v-if="notification.type === 'success'" class="fas fa-check-circle text-green-500"></i>
                    <i v-else class="fas fa-exclamation-circle text-red-500"></i>
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
  components: { AppLayout },
  setup() {
    const loading = ref(true);
    const sideTrims = ref([]);
    const productDesigns = ref([]);
    const products = ref([]);
    const flutes = ref([]);
    const viewPrintUrl = '/standard-formula/setup-side-trim-by-product-design/view-print';
    const newSideTrim = ref({
      product_design_id: '',
      product_id: '',
      flute_id: '',
      is_composite: false,
      length_less: 0,
      length_add: 0
    });
    const notification = ref({ show: false, message: '', type: 'success' });

    const canAddSideTrim = computed(() => {
      return newSideTrim.value.product_design_id && newSideTrim.value.product_id && newSideTrim.value.flute_id;
    });

    const showNotification = (message, type = 'success') => {
      notification.value = { show: true, message, type };
      setTimeout(() => { notification.value.show = false; }, 3000);
    };

    const loadData = async () => {
      try {
        loading.value = true;
        console.log('Loading side trim data...');
        
        // Load product designs, products, flutes, and side trims
        const [designsRes, productsRes, flutesRes, trimsRes] = await Promise.all([
          axios.get('/api/product-designs'),
          axios.get('/api/products'),
          axios.get('/api/paper-flutes'),
          axios.get('/api/side-trims-by-product-design')
        ]);
        
        productDesigns.value = designsRes.data;
        products.value = productsRes.data;
        flutes.value = flutesRes.data;
        
        console.log('API response:', trimsRes.data);
        
        // Check if we have valid data from the API
        if (trimsRes.data && trimsRes.data.status === 'success' && Array.isArray(trimsRes.data.data) && trimsRes.data.data.length > 0) {
          console.log('Processing side trim data...');
          
          // Create a map of product designs by ID for faster lookups
          const designsMap = new Map(productDesigns.value.map(design => [design.id, design]));
          
          // Create a map of products by ID for faster lookups
          const productsMap = new Map(products.value.map(product => [product.id, product]));
          
          // Process the side trims data
          sideTrims.value = trimsRes.data.data.map(trim => {
            // Find the corresponding design and product using the maps
            const design = designsMap.get(trim.product_design_id);
            const product = productsMap.get(trim.product_id);
            
            return {
              id: trim.id,
              product_design_id: trim.product_design_id,
              product_id: trim.product_id,
              flute_id: trim.flute_id,
              product_design_code: design ? design.code : 'N/A',
              product_code: product ? product.code : 'N/A',
              flute_code: trim.paper_flute?.code || 'N/A',
              is_composite: trim.is_composite === 1 || trim.is_composite === true,
              length_less: trim.length_less,
              length_add: trim.length_add
            };
          });
          
          // Sort the side trims by product design code, product code, and flute code
          sideTrims.value.sort((a, b) => {
            if (a.product_design_code === b.product_design_code) {
              if (a.product_code === b.product_code) {
                if (a.flute_code === b.flute_code) {
                  return a.is_composite === b.is_composite ? 0 : a.is_composite ? 1 : -1;
                }
                return a.flute_code.localeCompare(b.flute_code);
              }
              return a.product_code.localeCompare(b.product_code);
            }
            return a.product_design_code.localeCompare(b.product_design_code);
          });
          
          console.log('Processed side trims:', sideTrims.value);
        } else {
          console.log('No side trim data found. Attempting to seed...');
          
          // If no data, try to seed the database
          try {
            showNotification('No side trim data found. Seeding initial data...', 'info');
            const seedResponse = await axios.post('/api/side-trims-by-product-design/seed');
            
            if (seedResponse.data && seedResponse.data.success) {
              showNotification(seedResponse.data.message, 'success');
              
              // Fetch the data again after seeding
              const newTrimsResponse = await axios.get('/api/side-trims-by-product-design');
              
              if (newTrimsResponse.data && newTrimsResponse.data.status === 'success' && 
                  Array.isArray(newTrimsResponse.data.data) && newTrimsResponse.data.data.length > 0) {
                
                // Create a map of product designs by ID for faster lookups
                const designsMap = new Map(productDesigns.value.map(design => [design.id, design]));
                
                // Create a map of products by ID for faster lookups
                const productsMap = new Map(products.value.map(product => [product.id, product]));
                
                // Process the side trims data after seeding
                sideTrims.value = newTrimsResponse.data.data.map(trim => {
                  // Find the corresponding design and product using the maps
                  const design = designsMap.get(trim.product_design_id);
                  const product = productsMap.get(trim.product_id);
                  
                  return {
                    id: trim.id,
                    product_design_id: trim.product_design_id,
                    product_id: trim.product_id,
                    flute_id: trim.flute_id,
                    product_design_code: design ? design.code : 'N/A',
                    product_code: product ? product.code : 'N/A',
                    flute_code: trim.paper_flute?.code || 'N/A',
                    is_composite: trim.is_composite === 1 || trim.is_composite === true,
                    length_less: trim.length_less,
                    length_add: trim.length_add
                  };
                });
                
                // Sort the side trims
                sideTrims.value.sort((a, b) => {
                  if (a.product_design_code === b.product_design_code) {
                    if (a.product_code === b.product_code) {
                      if (a.flute_code === b.flute_code) {
                        return a.is_composite === b.is_composite ? 0 : a.is_composite ? 1 : -1;
                      }
                      return a.flute_code.localeCompare(b.flute_code);
                    }
                    return a.product_code.localeCompare(b.product_code);
                  }
                  return a.product_design_code.localeCompare(b.product_design_code);
                });
              } else {
                sideTrims.value = [];
                showNotification('Failed to load side trim data after seeding', 'error');
              }
            } else {
              sideTrims.value = [];
              showNotification('Failed to seed side trim data', 'error');
            }
          } catch (seedError) {
            console.error('Error seeding data:', seedError);
            sideTrims.value = [];
            showNotification('Failed to seed side trim data', 'error');
          }
        }
      } catch (error) {
        console.error('Error loading data:', error);
        
        let errorMessage = 'Failed to load data';
        
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
        const promises = sideTrims.value.map(trim =>
          axios.post('/api/side-trims-by-product-design', {
            product_design_id: trim.product_design_id,
            product_id: trim.product_id,
            flute_id: trim.flute_id,
            is_composite: trim.is_composite,
            length_less: trim.length_less,
            length_add: trim.length_add
          })
        );
        await Promise.all(promises);
        showNotification('Side trim data saved successfully');
        await loadData();
      } catch (error) {
        showNotification('Failed to save side trims', 'error');
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
        const exists = sideTrims.value.some(trim =>
          trim.product_design_id === newSideTrim.value.product_design_id &&
          trim.product_id === newSideTrim.value.product_id &&
          trim.flute_id === newSideTrim.value.flute_id
        );
        if (exists) {
          showNotification('This combination already exists', 'error');
          loading.value = false;
          return;
        }
        await axios.post('/api/side-trims-by-product-design', newSideTrim.value);
        showNotification('Side trim added successfully');
        newSideTrim.value = { product_design_id: '', product_id: '', flute_id: '', is_composite: false, length_less: 0, length_add: 0 };
        await loadData();
      } catch (error) {
        showNotification('Failed to add side trim', 'error');
      } finally {
        loading.value = false;
      }
    };

    const exportData = () => {
      showNotification('Exporting data...');
      axios.get('/api/side-trims-by-product-design/export', { responseType: 'blob' })
        .then(response => {
          const blob = new Blob([response.data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
          const link = document.createElement('a');
          link.href = window.URL.createObjectURL(blob);
          link.download = `side_trims_by_product_design_${new Date().toISOString().split('T')[0]}.xlsx`;
          link.click();
          showNotification('Data exported successfully');
        })
        .catch(() => {
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
      productDesigns,
      products,
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
  body * { visibility: hidden; }
  .max-w-7xl, .max-w-7xl * { visibility: visible; }
  .max-w-7xl { position: absolute; left: 0; top: 0; }
  button, .bg-gray-50:not(.border) { display: none !important; }
}
</style>
