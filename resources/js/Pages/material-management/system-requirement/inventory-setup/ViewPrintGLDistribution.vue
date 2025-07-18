<template>
  <AppLayout :header="'View & Print GL Distribution'">
    <Head title="View & Print GL Distribution" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-green-600 to-green-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-print mr-3"></i> View & Print GL Distribution
      </h2>
      <p class="text-green-100">View and print material management GL distributions</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-md p-6 mb-6">
      <!-- Search and Actions -->
      <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        <div class="relative w-full sm:w-64">
          <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
            <i class="fas fa-search text-gray-400"></i>
          </span>
          <input
            v-model="search"
            type="text"
            placeholder="Search..."
            class="pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 block w-full"
          />
        </div>

        <div class="flex gap-2">
          <button
            @click="printReport"
            class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md flex items-center"
          >
            <i class="fas fa-print mr-2"></i>
            <span>Print</span>
          </button>
          <Link
            :href="'/material-management/system-requirement/inventory-setup/gl-distribution'"
            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md flex items-center"
          >
            <i class="fas fa-arrow-left mr-2"></i>
            <span>Back</span>
          </Link>
        </div>
      </div>

      <!-- Report Content -->
      <div class="bg-white border border-gray-200 rounded-lg p-6">
        <div class="text-center mb-6">
          <h1 class="text-2xl font-bold">GL Distribution Report</h1>
          <p class="text-gray-600">Generated on {{ formattedDate }}</p>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th
                  v-for="column in columns"
                  :key="column.key"
                  @click="sortBy(column.key)"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                  :class="{ 'text-green-600': sortColumn === column.key }"
                >
                  {{ column.label }}
                  <span v-if="sortColumn === column.key">
                    <i :class="sortDirection === 'asc' ? 'fas fa-sort-up ml-1' : 'fas fa-sort-down ml-1'"></i>
                  </span>
                  <span v-else>
                    <i class="fas fa-sort ml-1 text-gray-300"></i>
                  </span>
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="filteredGlDistributions.length === 0" class="hover:bg-gray-50">
                <td :colspan="columns.length" class="px-6 py-4 text-center text-gray-500">
                  No data available
                </td>
              </tr>
              <tr
                v-for="item in filteredGlDistributions"
                :key="item.id"
                class="hover:bg-gray-50 transition-colors"
              >
                <td class="px-6 py-4 whitespace-nowrap font-medium">
                  {{ item.gl_dist_code }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  {{ item.gl_dist_name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  {{ item.gl_account }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  {{ item.gl_account_name || 'N/A' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                    :class="item.is_linked ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                  >
                    {{ item.is_linked ? 'Pass' : 'Fail' }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Footer -->
        <div class="mt-6 text-right text-sm text-gray-500">
          <p>Total Records: {{ filteredGlDistributions.length }}</p>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
  glDistributions: Array,
});

// Table columns
const columns = [
  { key: 'gl_dist_code', label: 'GL Code' },
  { key: 'gl_dist_name', label: 'GL Name' },
  { key: 'gl_account', label: 'GL Account' },
  { key: 'gl_account_name', label: 'Account Name' },
  { key: 'is_linked', label: 'Link Status' },
];

// State variables
const glDistributionsList = ref(props.glDistributions || []);
const search = ref('');
const sortColumn = ref('gl_dist_code');
const sortDirection = ref('asc');

// Get formatted date
const formattedDate = computed(() => {
  const now = new Date();
  return now.toLocaleDateString('en-US', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
});

// Sort the data
const sortBy = (column) => {
  if (sortColumn.value === column) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortColumn.value = column;
    sortDirection.value = 'asc';
  }
};

// Filter and sort the data
const filteredGlDistributions = computed(() => {
  let filtered = [...glDistributionsList.value];
  
  if (search.value) {
    const searchLower = search.value.toLowerCase();
    filtered = filtered.filter(item => 
      item.gl_dist_code.toLowerCase().includes(searchLower) ||
      item.gl_dist_name.toLowerCase().includes(searchLower) ||
      item.gl_account.toLowerCase().includes(searchLower) ||
      (item.gl_account_name && item.gl_account_name.toLowerCase().includes(searchLower))
    );
  }
  
  filtered.sort((a, b) => {
    const modifier = sortDirection.value === 'asc' ? 1 : -1;
    if (sortColumn.value === 'is_linked') {
      return modifier * (a.is_linked === b.is_linked ? 0 : a.is_linked ? 1 : -1);
    }
    return modifier * (a[sortColumn.value] < b[sortColumn.value] ? -1 : 1);
  });
  
  return filtered;
});

// Print the report
const printReport = () => {
  window.print();
};

// Fetch data if not provided via props
const fetchGlDistributions = async () => {
  try {
    const response = await fetch('/material-management/system-requirement/inventory-setup/gl-distribution/list');
    const data = await response.json();
    
    if (data.status === 'success') {
      glDistributionsList.value = data.data;
    }
  } catch (error) {
    console.error('Error fetching GL distributions:', error);
  }
};

// Fetch data on mount if needed
onMounted(() => {
  if (!props.glDistributions || props.glDistributions.length === 0) {
    fetchGlDistributions();
  }
});
</script>

<style>
@media print {
  body * {
    visibility: hidden;
  }
  
  .bg-gradient-to-r {
    background: white !important;
    color: black !important;
    -webkit-print-color-adjust: exact;
  }
  
  .bg-gradient-to-r * {
    color: black !important;
  }
  
  .bg-white, .bg-white * {
    visibility: visible;
  }
  
  button, .flex.gap-2 {
    display: none !important;
  }
  
  table {
    width: 100%;
    border-collapse: collapse;
  }
  
  th, td {
    border: 1px solid #ddd;
    padding: 8px;
  }
  
  th {
    background-color: #f2f2f2 !important;
    -webkit-print-color-adjust: exact;
  }
}
</style> 