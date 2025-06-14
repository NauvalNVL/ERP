<template>
  <AppLayout :header="'View & Print Product Design'">
    <Head title="View & Print Product Design" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-t-lg shadow-md">
      <div class="flex justify-between items-center">
        <div>
          <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
            <i class="fas fa-drafting-compass mr-3"></i> View & Print Product Design
          </h2>
          <p class="text-blue-100">View and print product designs for your manufacturing process</p>
        </div>
        <div class="flex space-x-3">
          <button @click="exportToExcel" class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded-lg shadow flex items-center">
            <i class="fas fa-file-excel mr-2"></i> Export
          </button>
          <button @click="printDesigns" class="bg-blue-500 hover:bg-blue-400 text-white px-4 py-2 rounded-lg shadow flex items-center">
            <i class="fas fa-print mr-2"></i> Print
          </button>
          <Link :href="'/standard-formula/setup-corrugator-run-size-formula/product-design'" class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-lg shadow flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to Define
          </Link>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-b-lg shadow-md p-6 mb-6">
      <!-- Loading Spinner -->
      <div v-if="loading" class="flex justify-center items-center py-12">
        <div class="animate-spin rounded-full h-16 w-16 border-t-2 border-b-2 border-blue-500"></div>
        <span class="ml-3 text-lg text-gray-600">Loading product designs...</span>
      </div>

      <div v-else>
        <!-- Filters Section -->
        <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
          <h3 class="text-lg font-medium text-gray-700 mb-3">Filters</h3>
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Product Design Code</label>
              <input type="text" v-model="filters.code" @input="applyFilters" 
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                placeholder="Filter by code">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Product Design Name</label>
              <input type="text" v-model="filters.name" @input="applyFilters" 
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                placeholder="Filter by name">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Product</label>
              <select v-model="filters.product" @change="applyFilters" 
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                <option value="">All Products</option>
                <option v-for="product in productOptions" :key="product" :value="product">{{ product }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Joint</label>
              <select v-model="filters.joint" @change="applyFilters" 
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                <option value="">All</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
            <div class="md:col-span-4 flex justify-end">
              <button @click="resetFilters" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg shadow">
                <i class="fas fa-undo mr-2"></i> Reset Filters
              </button>
            </div>
          </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-lg overflow-hidden border border-gray-200">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th @click="sortBy('pd_code')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                    <div class="flex items-center">
                      Design# 
                      <i :class="[
                        'ml-1 fas', 
                        sortOrder.field === 'pd_code' ? 
                          (sortOrder.direction === 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 
                          'fa-sort'
                      ]"></i>
                    </div>
                  </th>
                  <th @click="sortBy('pd_name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                    <div class="flex items-center">
                      Design Name
                      <i :class="[
                        'ml-1 fas', 
                        sortOrder.field === 'pd_name' ? 
                          (sortOrder.direction === 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 
                          'fa-sort'
                      ]"></i>
                    </div>
                  </th>
                  <th @click="sortBy('product')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                    <div class="flex items-center">
                      Product
                      <i :class="[
                        'ml-1 fas', 
                        sortOrder.field === 'product' ? 
                          (sortOrder.direction === 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 
                          'fa-sort'
                      ]"></i>
                    </div>
                  </th>
                  <th @click="sortBy('joint')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                    <div class="flex items-center">
                      Joint
                      <i :class="[
                        'ml-1 fas', 
                        sortOrder.field === 'joint' ? 
                          (sortOrder.direction === 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 
                          'fa-sort'
                      ]"></i>
                    </div>
                  </th>
                  <th @click="sortBy('score')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                    <div class="flex items-center">
                      Score
                      <i :class="[
                        'ml-1 fas', 
                        sortOrder.field === 'score' ? 
                          (sortOrder.direction === 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 
                          'fa-sort'
                      ]"></i>
                    </div>
                  </th>
                  <th @click="sortBy('slot')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                    <div class="flex items-center">
                      Slot
                      <i :class="[
                        'ml-1 fas', 
                        sortOrder.field === 'slot' ? 
                          (sortOrder.direction === 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 
                          'fa-sort'
                      ]"></i>
                    </div>
                  </th>
                  <th @click="sortBy('flute_style')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                    <div class="flex items-center">
                      Flute Style
                      <i :class="[
                        'ml-1 fas', 
                        sortOrder.field === 'flute_style' ? 
                          (sortOrder.direction === 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 
                          'fa-sort'
                      ]"></i>
                    </div>
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-if="filteredDesigns.length === 0" class="hover:bg-gray-50">
                  <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                    No product designs found matching your filters.
                  </td>
                </tr>
                <tr v-for="design in paginatedDesigns" :key="design.pd_code" 
                    @click="selectDesign(design)" 
                    :class="{'bg-blue-50': selectedDesign && selectedDesign.pd_code === design.pd_code}"
                    class="hover:bg-gray-50 cursor-pointer transition-colors">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ design.pd_code }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ design.pd_name }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                      {{ design.product }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    <span v-if="design.joint === 'Yes'" class="text-green-500"><i class="fas fa-check-circle"></i> Yes</span>
                    <span v-else class="text-red-500"><i class="fas fa-times-circle"></i> No</span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    <span v-if="design.score === 'Yes'" class="text-green-500"><i class="fas fa-check-circle"></i> Yes</span>
                    <span v-else class="text-red-500"><i class="fas fa-times-circle"></i> No</span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    <span v-if="design.slot === 'Yes'" class="text-green-500"><i class="fas fa-check-circle"></i> Yes</span>
                    <span v-else class="text-red-500"><i class="fas fa-times-circle"></i> No</span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ design.flute_style }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Pagination Controls -->
        <div class="mt-4 flex justify-between items-center text-sm text-gray-600">
          <div>
            <span>Showing {{ paginatedDesigns.length }} of {{ filteredDesigns.length }} designs</span>
          </div>
          <div class="flex items-center space-x-2">
            <select v-model="itemsPerPage" class="border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option :value="10">10 per page</option>
              <option :value="20">20 per page</option>
              <option :value="50">50 per page</option>
              <option :value="100">100 per page</option>
            </select>
            <button :disabled="currentPage === 1" @click="currentPage--" class="px-2 py-1 border rounded hover:bg-gray-200 disabled:opacity-50">
              <i class="fas fa-chevron-left"></i>
            </button>
            <span class="px-4">{{ currentPage }} / {{ totalPages }}</span>
            <button :disabled="currentPage === totalPages" @click="currentPage++" class="px-2 py-1 border rounded hover:bg-gray-200 disabled:opacity-50">
              <i class="fas fa-chevron-right"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Design Details Panel -->
      <div v-if="selectedDesign" class="mt-6 bg-gray-50 p-6 rounded-lg border border-gray-200">
        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
          <i class="fas fa-info-circle mr-2 text-blue-500"></i> Design Details: {{ selectedDesign.pd_code }}
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div>
            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Basic Information</h4>
            <div class="space-y-3">
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Design Code:</span>
                <span class="font-medium text-gray-900">{{ selectedDesign.pd_code }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Design Name:</span>
                <span class="font-medium text-gray-900">{{ selectedDesign.pd_name }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Design Type:</span>
                <span class="font-medium text-gray-900">{{ selectedDesign.pd_design_type }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">IDC:</span>
                <span class="font-medium text-gray-900">{{ selectedDesign.idc || 'N/A' }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Product:</span>
                <span class="font-medium bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">{{ selectedDesign.product }}</span>
              </div>
            </div>
          </div>
          <div>
            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Joint Properties</h4>
            <div class="space-y-3">
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Joint:</span>
                <span class="font-medium" :class="{'text-green-600': selectedDesign.joint === 'Yes', 'text-red-600': selectedDesign.joint !== 'Yes'}">
                  {{ selectedDesign.joint }}
                </span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Joint to Print:</span>
                <span class="font-medium" :class="{'text-green-600': selectedDesign.joint_to_print === 'Yes', 'text-red-600': selectedDesign.joint_to_print !== 'Yes'}">
                  {{ selectedDesign.joint_to_print }}
                </span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">PCS to Joint:</span>
                <span class="font-medium text-gray-900">{{ selectedDesign.pcs_to_joint }}</span>
              </div>
            </div>
          </div>
          <div>
            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Manufacturing Properties</h4>
            <div class="space-y-3">
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Score:</span>
                <span class="font-medium" :class="{'text-green-600': selectedDesign.score === 'Yes', 'text-red-600': selectedDesign.score !== 'Yes'}">
                  {{ selectedDesign.score }}
                </span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Slot:</span>
                <span class="font-medium" :class="{'text-green-600': selectedDesign.slot === 'Yes', 'text-red-600': selectedDesign.slot !== 'Yes'}">
                  {{ selectedDesign.slot }}
                </span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Flute Style:</span>
                <span class="font-medium text-gray-900">{{ selectedDesign.flute_style }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Print Flute:</span>
                <span class="font-medium" :class="{'text-green-600': selectedDesign.print_flute === 'Yes', 'text-red-600': selectedDesign.print_flute !== 'Yes'}">
                  {{ selectedDesign.print_flute }}
                </span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Input Weight:</span>
                <span class="font-medium" :class="{'text-green-600': selectedDesign.input_weight === 'Yes', 'text-red-600': selectedDesign.input_weight !== 'Yes'}">
                  {{ selectedDesign.input_weight }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import axios from 'axios';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

// State variables
const designs = ref([]);
const selectedDesign = ref(null);
const loading = ref(true);
const sortOrder = ref({
  field: 'pd_code',
  direction: 'asc'
});
const currentPage = ref(1);
const itemsPerPage = ref(20);
const filters = ref({
  code: '',
  name: '',
  product: '',
  joint: ''
});

// Computed properties
const productOptions = computed(() => {
  const products = new Set();
  designs.value.forEach(design => {
    if (design.product) {
      products.add(design.product);
    }
  });
  return Array.from(products).sort();
});

const filteredDesigns = computed(() => {
  let result = designs.value;

  // Apply filters
  if (filters.value.code) {
    result = result.filter(design => 
      design.pd_code && design.pd_code.toLowerCase().includes(filters.value.code.toLowerCase())
    );
  }
  
  if (filters.value.name) {
    result = result.filter(design => 
      design.pd_name && design.pd_name.toLowerCase().includes(filters.value.name.toLowerCase())
    );
  }
  
  if (filters.value.product) {
    result = result.filter(design => design.product === filters.value.product);
  }
  
  if (filters.value.joint) {
    result = result.filter(design => design.joint === filters.value.joint);
  }

  // Apply sorting
  result = [...result].sort((a, b) => {
    const field = sortOrder.value.field;
    const direction = sortOrder.value.direction === 'asc' ? 1 : -1;
    
    if ((a[field] || '') < (b[field] || '')) return -1 * direction;
    if ((a[field] || '') > (b[field] || '')) return 1 * direction;
    return 0;
  });

  return result;
});

// Pagination computed properties
const paginatedDesigns = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return filteredDesigns.value.slice(start, end);
});

const totalPages = computed(() => {
  return Math.max(1, Math.ceil(filteredDesigns.value.length / itemsPerPage.value));
});

// Watch for changes in filtered designs to update pagination
watch(filteredDesigns, () => {
  currentPage.value = 1;
});

// Watch for changes in items per page
watch(itemsPerPage, () => {
  currentPage.value = 1;
});

// Function to fetch designs from API
const fetchDesigns = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/product-designs');
    designs.value = response.data;
  } catch (error) {
    console.error('Error fetching designs:', error);
    alert('Failed to load product designs');
  } finally {
    loading.value = false;
  }
};

// Function to select a design
const selectDesign = (design) => {
  selectedDesign.value = design;
};

// Function to sort table by a given field
const sortBy = (field) => {
  if (sortOrder.value.field === field) {
    // Toggle direction if same field
    sortOrder.value.direction = sortOrder.value.direction === 'asc' ? 'desc' : 'asc';
  } else {
    // Change field and reset direction
    sortOrder.value.field = field;
    sortOrder.value.direction = 'asc';
  }
};

// Function to apply filters
const applyFilters = () => {
  // This function is called when filters change
  // The filtering is handled by the computed property
};

// Function to reset filters
const resetFilters = () => {
  filters.value = {
    code: '',
    name: '',
    product: '',
    joint: ''
  };
};

// Function to export data to Excel
const exportToExcel = async () => {
  try {
    const response = await axios.get('/api/product-designs/export', { responseType: 'blob' });
    const blob = new Blob([response.data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
    const link = document.createElement('a');
    link.href = window.URL.createObjectURL(blob);
    link.download = `product_designs_${new Date().toISOString().split('T')[0]}.xlsx`;
    link.click();
  } catch (error) {
    console.error('Error exporting designs:', error);
    alert('Failed to export product designs');
  }
};

// Function to print designs
const printDesigns = () => {
  window.print();
};

// Load data when component is mounted
onMounted(fetchDesigns);
</script>

<style scoped>
@media print {
  .bg-gradient-to-r {
    background: #2563eb !important;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
  }
  
  .text-white {
    color: white !important;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
  }
  
  button {
    display: none !important;
  }
  
  a {
    display: none !important;
  }
  
  .bg-gray-50 {
    background-color: #f9fafb !important;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
  }
  
  .bg-blue-50 {
    background-color: #eff6ff !important;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
  }
  
  .bg-blue-100 {
    background-color: #dbeafe !important;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
  }
  
  .text-blue-800 {
    color: #1e40af !important;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
  }
  
  .text-green-500 {
    color: #10b981 !important;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
  }
  
  .text-red-500 {
    color: #ef4444 !important;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
  }
}

/* Scrollbar styling */
.overflow-x-auto {
  scrollbar-width: thin;
  scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
}

.overflow-x-auto::-webkit-scrollbar {
  height: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track {
  background: transparent;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
  background-color: rgba(156, 163, 175, 0.5);
  border-radius: 20px;
}
</style>
