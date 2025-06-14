<template>
  <AppLayout title="View & Print Product Design">
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          View & Print Product Design
        </h2>
        <div class="flex space-x-2">
          <button @click="printTable" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-200 flex items-center shadow-sm">
            <i class="fas fa-print mr-2"></i> Print
          </button>
        </div>
      </div>
    </template>

    <div class="py-12 bg-gray-50">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg border border-gray-100">
          <!-- Main Content -->
          <div class="p-6">
            <!-- Search and Filter Section -->
            <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
              <!-- Search Box -->
              <div class="relative flex-grow max-w-md">
                <input 
                  type="text" 
                  v-model="searchQuery"
                  placeholder="Search designs..."
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm pl-10"
                />
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <i class="fas fa-search text-gray-400"></i>
                </div>
              </div>
              
              <!-- Filter by Product -->
              <div class="relative w-full md:w-64">
                <select
                  v-model="productFilter"
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm appearance-none"
                >
                  <option value="">All Products</option>
                  <option v-for="product in products" :key="product.id" :value="product.id">
                    {{ product.product_code }}
                  </option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                  <i class="fas fa-chevron-down"></i>
                </div>
              </div>
            </div>

            <!-- Table Section -->
            <div class="overflow-x-auto" ref="printSection">
              <table class="min-w-full divide-y divide-gray-200 border">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                      Code
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                      Name
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                      Product
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                      Compute
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Alternate Name
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="design in filteredDesigns" :key="design.id" class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-r">
                      {{ design.code }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border-r">
                      {{ design.name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border-r">
                      {{ getProductName(design.product_id) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border-r">
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                            :class="design.compute ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'">
                        {{ design.compute ? 'Yes' : 'No' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ design.alt_name || '-' }}
                    </td>
                  </tr>
                  <tr v-if="filteredDesigns.length === 0">
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                      No product designs found
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4 flex items-center justify-between">
              <div class="text-sm text-gray-700">
                Showing <span class="font-medium">{{ filteredDesigns.length }}</span> designs
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

// State
const productDesigns = ref([]);
const products = ref([]);
const searchQuery = ref('');
const productFilter = ref('');
const loading = ref(false);

// Fetch data on component mount
onMounted(async () => {
  await Promise.all([
    fetchProductDesigns(),
    fetchProducts()
  ]);
});

// Fetch product designs from API
const fetchProductDesigns = async () => {
  try {
    loading.value = true;
    const response = await axios.get('/api/product-designs');
    productDesigns.value = response.data.data;
  } catch (error) {
    console.error('Error fetching product designs:', error);
  } finally {
    loading.value = false;
  }
};

// Fetch products from API
const fetchProducts = async () => {
  try {
    const response = await axios.get('/api/products');
    products.value = response.data;
  } catch (error) {
    console.error('Error fetching products:', error);
  }
};

// Get product name by ID
const getProductName = (productId) => {
  const product = products.value.find(p => p.id === productId);
  return product ? product.product_code : '';
};

// Filter designs based on search query and product filter
const filteredDesigns = computed(() => {
  let filtered = productDesigns.value;
  
  // Apply search filter
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    filtered = filtered.filter(design => {
      return design.code.toLowerCase().includes(query) || 
             design.name.toLowerCase().includes(query) ||
             (design.alt_name && design.alt_name.toLowerCase().includes(query));
    });
  }
  
  // Apply product filter
  if (productFilter.value) {
    filtered = filtered.filter(design => design.product_id === productFilter.value);
  }
  
  return filtered;
});

// Print function
const printSection = ref(null);
const printTable = () => {
  const printContent = printSection.value.innerHTML;
  const originalContent = document.body.innerHTML;
  
  document.body.innerHTML = `
    <div style="padding: 20px;">
      <h1 style="text-align: center; margin-bottom: 20px;">Product Designs</h1>
      ${printContent}
    </div>
  `;
  
  window.print();
  document.body.innerHTML = originalContent;
};
</script>

<style scoped>
@media print {
  button {
    display: none;
  }
  
  .bg-gray-50 {
    background-color: white !important;
  }
  
  .shadow-lg {
    box-shadow: none !important;
  }
  
  .border {
    border: 1px solid #e5e7eb !important;
  }
}
</style> 