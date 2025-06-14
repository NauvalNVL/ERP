<template>
  <AppLayout title="View & Print Bundling Computation Method">
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          View & Print Bundling Computation Method
        </h2>
        <div class="flex space-x-2">
          <button @click="printTable" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-200 flex items-center">
            <i class="fas fa-print mr-2"></i> Print
          </button>
        </div>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <!-- Main Content -->
          <div class="p-6">
            <!-- Search and Filter Section -->
            <div class="mb-6 flex flex-wrap items-center gap-4">
              <div class="flex-grow">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input
                  type="text"
                  id="search"
                  v-model="search"
                  placeholder="Search by product design, product, or flute..."
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                />
              </div>
              
              <div class="w-full md:w-auto">
                <label for="filter" class="block text-sm font-medium text-gray-700 mb-1">Filter by Compute</label>
                <select
                  id="filter"
                  v-model="computeFilter"
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                >
                  <option value="all">All</option>
                  <option value="yes">Yes</option>
                  <option value="no">No</option>
                </select>
              </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto" ref="printableTable">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Design</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Flute</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pieces Per Bundle</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Compute</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="method in filteredMethods" :key="method.id">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ method.product_design || 'APFI' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ method.product || 'BKS' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ method.flute || 'AB' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ method.formula_pieces }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      <span :class="method.is_compute ? 'text-green-600' : 'text-red-600'">
                        {{ method.is_compute ? 'Yes' : 'No' }}
                      </span>
                    </td>
                  </tr>
                  <tr v-if="filteredMethods.length === 0">
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No computation methods found</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

// State
const computationMethods = ref([]);
const search = ref('');
const computeFilter = ref('all');
const loading = ref(false);
const printableTable = ref(null);

// Fetch computation methods on component mount
onMounted(async () => {
  await fetchComputationMethods();
});

// Fetch computation methods from API
const fetchComputationMethods = async () => {
  try {
    loading.value = true;
    const response = await axios.get('/api/bundling-computation-methods');
    computationMethods.value = response.data.data;
  } catch (error) {
    console.error('Error fetching computation methods:', error);
    // Show error notification
  } finally {
    loading.value = false;
  }
};

// Filter methods based on search and compute filter
const filteredMethods = computed(() => {
  let filtered = [...computationMethods.value];
  
  // Apply search filter
  if (search.value) {
    const searchLower = search.value.toLowerCase();
    filtered = filtered.filter(method => 
      (method.product_design && method.product_design.toLowerCase().includes(searchLower)) ||
      (method.product && method.product.toLowerCase().includes(searchLower)) ||
      (method.flute && method.flute.toLowerCase().includes(searchLower))
    );
  }
  
  // Apply compute filter
  if (computeFilter.value !== 'all') {
    const isCompute = computeFilter.value === 'yes';
    filtered = filtered.filter(method => method.is_compute === isCompute);
  }
  
  return filtered;
});

// Print table
const printTable = () => {
  const printContent = printableTable.value.innerHTML;
  const originalContent = document.body.innerHTML;
  
  document.body.innerHTML = `
    <div style="padding: 20px;">
      <h1 style="text-align: center; margin-bottom: 20px;">Bundling Computation Methods</h1>
      <table style="width: 100%; border-collapse: collapse;">
        ${printContent}
      </table>
    </div>
  `;
  
  window.print();
  document.body.innerHTML = originalContent;
  
  // Reload the page to restore Vue functionality
  setTimeout(() => {
    window.location.reload();
  }, 100);
};
</script>

<style scoped>
@media print {
  table {
    width: 100%;
    border-collapse: collapse;
  }
  
  th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
  }
  
  th {
    background-color: #f2f2f2;
  }
  
  tr:nth-child(even) {
    background-color: #f9f9f9;
  }
}
</style> 