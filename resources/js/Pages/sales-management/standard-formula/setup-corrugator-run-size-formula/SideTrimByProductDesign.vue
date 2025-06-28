<template>
  <AppLayout title="Define Side Trim By Product Design">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Define Side Trim By Product Design
      </h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="p-6 sm:px-8 bg-white border-b border-gray-200">
            <div class="flex items-center justify-between">
              <div>
                <h2 class="text-2xl font-bold text-gray-800">Side Trim By Product Design Settings</h2>
                <p class="mt-1 text-sm text-gray-600">Manage side trim specifications for each product design.</p>
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
                    placeholder="Search by P/Design, Product, or Flute..."
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                  />
                </div>
              </div>

              <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">P/Design</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Flute</th>
                      <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Compute</th>
                      <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Length Less (mm)</th>
                      <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Length Add (mm)</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="paginatedItems.length === 0">
                      <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">
                        <div class="flex flex-col items-center">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                          </svg>
                          <h3 class="mt-2 text-lg font-medium text-gray-900">No side trims found</h3>
                          <p class="mt-1 text-sm text-gray-500">Please try a different search term or add new side trims.</p>
                        </div>
                      </td>
                    </tr>
                    <tr v-for="trim in paginatedItems" :key="trim.id" class="hover:bg-gray-50 transition-colors duration-150">
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ trim.product_design_code }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ trim.product_code }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ trim.flute_code }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-center">
                        <Switch
                          v-model="trim.compute"
                          :class="trim.compute ? 'bg-blue-600' : 'bg-gray-200'"
                          class="relative inline-flex items-center h-6 rounded-full w-11 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                          <span class="sr-only">Enable compute</span>
                          <span
                            :class="trim.compute ? 'translate-x-6' : 'translate-x-1'"
                            class="inline-block w-4 h-4 transform bg-white rounded-full transition-transform"
                          />
                        </Switch>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-center">
                        <input type="number" v-model="trim.length_less" class="w-24 text-center border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" />
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-center">
                        <input type="number" v-model="trim.length_add" class="w-24 text-center border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" />
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
                  <button @click="prevPage" :disabled="currentPage === 1" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50">
                    Previous
                  </button>
                  <button @click="nextPage" :disabled="currentPage === totalPages" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50">
                    Next
                  </button>
                </div>
              </div>
              </div>
            </div>

            <!-- Add New Side Trim -->
          <div class="p-6 sm:px-8 bg-gray-50 border-t border-gray-200">
              <h3 class="text-lg font-medium text-gray-700 mb-3">Add New Side Trim</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                <label for="new-design" class="block text-sm font-medium text-gray-700 mb-1">Product Design</label>
                <select id="new-design" v-model="newSideTrim.product_design_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                  <option value="">Select Design</option>
                    <option v-for="design in productDesigns" :key="design.id" :value="design.id">{{ design.code }}</option>
                  </select>
                </div>
                <div>
                <label for="new-product" class="block text-sm font-medium text-gray-700 mb-1">Product</label>
                <select id="new-product" v-model="newSideTrim.product_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                  <option value="">Select Product</option>
                    <option v-for="product in products" :key="product.id" :value="product.id">{{ product.code }}</option>
                  </select>
                </div>
                <div>
                <label for="new-flute" class="block text-sm font-medium text-gray-700 mb-1">Flute</label>
                <select id="new-flute" v-model="newSideTrim.flute_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                  <option value="">Select Flute</option>
                    <option v-for="flute in flutes" :key="flute.id" :value="flute.id">{{ flute.code }}</option>
                  </select>
                </div>
              <div>
                <label for="new-length-less" class="block text-sm font-medium text-gray-700 mb-1">Length Less (mm)</label>
                <input id="new-length-less" type="number" v-model="newSideTrim.length_less" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" />
                </div>
                <div>
                <label for="new-length-add" class="block text-sm font-medium text-gray-700 mb-1">Length Add (mm)</label>
                <input id="new-length-add" type="number" v-model="newSideTrim.length_add" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" />
                </div>
              <div class="flex items-center">
                <input type="checkbox" id="new-compute" v-model="newSideTrim.compute" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                <label for="new-compute" class="ml-2 text-sm font-medium text-gray-700">Compute</label>
                </div>
                <div class="flex items-end">
                  <button @click="addSideTrim" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded text-sm" :disabled="!canAddSideTrim">Add Side Trim</button>
                </div>
              </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import { defineComponent, ref, computed, onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Switch } from '@headlessui/vue';
import axios from 'axios';
import Swal from 'sweetalert2';

export default defineComponent({
  components: { AppLayout, Switch },
  setup() {
    const loading = ref(true);
    const sideTrims = ref([]);
    const filteredItems = ref([]);
    const productDesigns = ref([]);
    const products = ref([]);
    const flutes = ref([]);
    const searchQuery = ref('');
    const currentPage = ref(1);
    const itemsPerPage = ref(10);
    
    const newSideTrim = ref({
      product_design_id: '',
      product_id: '',
      flute_id: '',
      compute: false,
      length_less: 0,
      length_add: 0
    });

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

    const canAddSideTrim = computed(() => {
      return newSideTrim.value.product_design_id && newSideTrim.value.product_id && newSideTrim.value.flute_id;
    });

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
        
        const [designsRes, productsRes, flutesRes, trimsRes] = await Promise.all([
          axios.get('/api/product-designs'),
          axios.get('/api/products'),
          axios.get('/api/paper-flutes'),
          axios.get('/api/side-trims-by-product-design')
        ]);
        
        productDesigns.value = designsRes.data;
        products.value = productsRes.data;
        flutes.value = flutesRes.data;
        
        if (trimsRes.data && trimsRes.data.status === 'success' && Array.isArray(trimsRes.data.data) && trimsRes.data.data.length > 0) {
          const designsMap = new Map(productDesigns.value.map(d => [d.id, d.code]));
          const productsMap = new Map(products.value.map(p => [p.id, p.code]));

          sideTrims.value = trimsRes.data.data.map(trim => ({
              id: trim.id,
              product_design_id: trim.product_design_id,
              product_id: trim.product_id,
              flute_id: trim.flute_id,
            product_design_code: designsMap.get(trim.product_design_id) || 'N/A',
            product_code: productsMap.get(trim.product_id) || 'N/A',
              flute_code: trim.paper_flute?.code || 'N/A',
            compute: trim.compute === 1 || trim.compute === true,
              length_less: trim.length_less,
              length_add: trim.length_add
          })).sort((a, b) => {
            if (a.product_design_code !== b.product_design_code) return a.product_design_code.localeCompare(b.product_design_code);
            if (a.product_code !== b.product_code) return a.product_code.localeCompare(b.product_code);
                return a.flute_code.localeCompare(b.flute_code);
          });
        } else {
            showNotification('No side trim data found. Seeding initial data...', 'info');
          await axios.post('/api/side-trims-by-product-design/seed');
          showNotification('Initial data has been seeded. Loading data...', 'success');
          await loadData(); // Reload after seeding
          return; // Exit to avoid running finally block too early
        }

        filteredItems.value = [...sideTrims.value];
      } catch (error) {
        console.error('Full error details:', error.response ? error.response.data : error);
        showNotification('Failed to load data', 'error');
        sideTrims.value = [];
        filteredItems.value = [];
      } finally {
        loading.value = false;
      }
    };

    const filterItems = () => {
      currentPage.value = 1;
      const query = searchQuery.value.toLowerCase();
      if (!query) {
        filteredItems.value = [...sideTrims.value];
        return;
      }
      filteredItems.value = sideTrims.value.filter(trim => 
        trim.product_design_code.toLowerCase().includes(query) ||
        trim.product_code.toLowerCase().includes(query) ||
        trim.flute_code.toLowerCase().includes(query)
      );
    };

    const saveChanges = async () => {
      try {
        loading.value = true;
        const dataToSave = sideTrims.value.map(trim => ({
            id: trim.id,
            product_design_id: trim.product_design_id,
            product_id: trim.product_id,
            flute_id: trim.flute_id,
            length_add: trim.length_add === '' ? null : parseInt(trim.length_add),
            length_less: trim.length_less === '' ? null : parseInt(trim.length_less),
            compute: trim.compute === 1 || trim.compute === true || trim.compute === 'true',
        }));
        
        const response = await axios.post('/api/side-trims-by-product-design/batch', dataToSave);
        
        // Handle different response scenarios
        if (response.data.status === 'partial_success') {
          // Show a warning about partial updates
          Swal.fire({
            icon: 'warning',
            title: 'Partial Update',
            html: `
              <p>Successfully updated ${response.data.updated_count} out of ${dataToSave.length} records.</p>
              <p>Some records could not be updated:</p>
              <ul>
                ${response.data.errors.map(error => 
                  `<li>Record ID ${error.id}: ${error.message}</li>`
                ).join('')}
              </ul>
            `,
            confirmButtonText: 'OK'
          });

          // Refresh the data, focusing on successfully updated records
          await loadData();
        } else if (response.data.status === 'success') {
          // Show success message
          Swal.fire({
            icon: 'success',
            title: 'Update Successful',
            text: `Successfully updated ${response.data.updated_count} records.`,
            confirmButtonText: 'OK'
          });

          // Refresh the data
        await loadData();
        } else if (response.data.status === 'error') {
          // Show error message
          Swal.fire({
            icon: 'error',
            title: 'Update Failed',
            text: response.data.message || 'An error occurred while saving changes.',
            confirmButtonText: 'OK'
          });
        }
      } catch (error) {
        console.error('Error saving changes:', error);
        console.error('Full error details:', error.response ? error.response.data : error);
        
        // Show error message
        Swal.fire({
          icon: 'error',
          title: 'Update Failed',
          text: error.response?.data?.message || 'An error occurred while saving changes.',
          confirmButtonText: 'OK'
        });
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
        
        const payload = {
            ...newSideTrim.value,
            length_add: newSideTrim.value.length_add === '' ? null : newSideTrim.value.length_add,
            length_less: newSideTrim.value.length_less === '' ? null : newSideTrim.value.length_less,
        };

        await axios.post('/api/side-trims-by-product-design', payload);
        showNotification('Side trim added successfully');
        newSideTrim.value = { product_design_id: '', product_id: '', flute_id: '', compute: false, length_less: 0, length_add: 0 };
        await loadData();
      } catch (error) {
        showNotification('Failed to add side trim', 'error');
        console.error('Error adding side trim:', error);
        console.error('Full error details:', error.response ? error.response.data : error);
      } finally {
        loading.value = false;
      }
    };

    onMounted(loadData);

    return {
      loading,
      sideTrims,
      filteredItems,
      paginatedItems,
      productDesigns,
      products,
      flutes,
      newSideTrim,
      searchQuery,
      currentPage,
      itemsPerPage,
      totalPages,
      canAddSideTrim,
      saveChanges,
      addSideTrim,
      filterItems,
      nextPage,
      prevPage,
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
