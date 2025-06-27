<template>
  <AppLayout :header="'View & Print Location'">
    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Main Content Card -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="p-6">
            <!-- Action buttons -->
            <div class="mb-6 flex justify-between items-center">
              <div class="flex items-center">
                <h2 class="text-xl font-semibold text-gray-800 mr-4">Location Listing</h2>
                <div class="text-sm text-gray-500">
                  Total: <span class="font-medium">{{ locations.length }}</span> locations
                </div>
              </div>
              <div class="flex space-x-2">
                <button 
                  @click="printDocument"
                  class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors inline-flex items-center"
                >
                  <PrinterIcon class="w-5 h-5 mr-1" />
                  Print
                </button>
                <button 
                  @click="exportData"
                  class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors inline-flex items-center"
                >
                  <DocumentDownloadIcon class="w-5 h-5 mr-1" />
                  Export
                </button>
                <button 
                  @click="refreshData"
                  class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition-colors inline-flex items-center"
                >
                  <RefreshIcon class="w-5 h-5 mr-1" />
                  Refresh
                </button>
              </div>
            </div>
            
            <!-- Filter Controls -->
            <div class="mb-6 flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
              <div class="w-full md:w-1/3">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">
                  Search
                </label>
                <div class="relative">
                  <input 
                    id="search"
                    v-model="searchQuery"
                    type="text" 
                    placeholder="Search by code or name..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  />
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <SearchIcon class="w-5 h-5 text-gray-400" />
                  </div>
                </div>
              </div>
              <div class="w-full md:w-1/3">
                <label for="status-filter" class="block text-sm font-medium text-gray-700 mb-1">
                  Status
                </label>
                <select 
                  id="status-filter"
                  v-model="statusFilter"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                  <option value="all">All Statuses</option>
                  <option value="active">Active</option>
                  <option value="inactive">Inactive</option>
                </select>
              </div>
              <div class="w-full md:w-1/3">
                <label for="sort-by" class="block text-sm font-medium text-gray-700 mb-1">
                  Sort By
                </label>
                <select 
                  id="sort-by"
                  v-model="sortBy"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                  <option value="code">Code</option>
                  <option value="name">Name</option>
                  <option value="status">Status</option>
                </select>
              </div>
            </div>
            
            <!-- Locations Table -->
            <div id="printable-content">
              <div class="print-header">
                <h1 class="text-xl font-bold text-center mb-4">Location Master Listing</h1>
                <p class="text-sm text-center text-gray-500 mb-6">Print Date: {{ new Date().toLocaleDateString() }}</p>
              </div>
              
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        No.
                      </th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        Code
                      </th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        Name
                      </th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        Description
                      </th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="loading">
                      <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="flex justify-center">
                          <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                          </svg>
                        </div>
                      </td>
                    </tr>
                    <tr v-else-if="filteredLocations.length === 0">
                      <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                        No locations found
                      </td>
                    </tr>
                    <tr v-for="(location, index) in filteredLocations" 
                        :key="location.code" 
                        class="hover:bg-gray-50 transition-colors">
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-r">
                        {{ ((currentPage - 1) * itemsPerPage) + index + 1 }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-r">
                        {{ location.code }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r">
                        {{ location.name }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r">
                        {{ location.description || '—' }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        <span 
                          class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                          :class="location.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                        >
                          {{ location.is_active ? 'Active' : 'Inactive' }}
                        </span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              
              <!-- Table Footer with Pagination -->
              <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6 print:hidden">
                <div class="flex items-center justify-between">
                  <div class="text-sm text-gray-700">
                    Showing {{ filteredLocations.length > 0 ? ((currentPage - 1) * itemsPerPage) + 1 : 0 }} 
                    to {{ Math.min(totalItems, currentPage * itemsPerPage) }} 
                    of {{ totalItems }} results
                  </div>
                  <div class="flex space-x-2">
                    <button
                      @click="currentPage > 1 && currentPage--"
                      :disabled="currentPage === 1"
                      :class="[
                        'relative inline-flex items-center px-4 py-2 text-sm font-medium rounded-md',
                        currentPage === 1 
                          ? 'bg-gray-100 text-gray-400 cursor-not-allowed' 
                          : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300'
                      ]"
                    >
                      Previous
                    </button>
                    <button
                      @click="currentPage < totalPages && currentPage++"
                      :disabled="currentPage >= totalPages"
                      :class="[
                        'relative inline-flex items-center px-4 py-2 text-sm font-medium rounded-md',
                        currentPage >= totalPages 
                          ? 'bg-gray-100 text-gray-400 cursor-not-allowed' 
                          : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300'
                      ]"
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
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { 
  PrinterIcon, 
  RefreshIcon, 
  DocumentDownloadIcon, 
  SearchIcon
} from '@heroicons/vue/solid';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

// Reactive data
const locations = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const statusFilter = ref('all');
const sortBy = ref('code');
const currentPage = ref(1);
const itemsPerPage = ref(10);

// Computed properties
const filteredLocations = computed(() => {
  let filtered = [...locations.value];
  
  // Apply search filter
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    filtered = filtered.filter(loc => 
      loc.code.toLowerCase().includes(query) || 
      loc.name.toLowerCase().includes(query) ||
      (loc.description && loc.description.toLowerCase().includes(query))
    );
  }
  
  // Apply status filter
  if (statusFilter.value !== 'all') {
    const isActive = statusFilter.value === 'active';
    filtered = filtered.filter(loc => loc.is_active === isActive);
  }
  
  // Apply sorting
  filtered.sort((a, b) => {
    if (sortBy.value === 'code') {
      return a.code.localeCompare(b.code);
    } else if (sortBy.value === 'name') {
      return a.name.localeCompare(b.name);
    } else if (sortBy.value === 'status') {
      return (a.is_active === b.is_active) ? 0 : a.is_active ? -1 : 1;
    }
    return 0;
  });
  
  const startIndex = (currentPage.value - 1) * itemsPerPage.value;
  return filtered.slice(startIndex, startIndex + itemsPerPage.value);
});

const totalItems = computed(() => {
  let filtered = [...locations.value];
  
  // Apply search filter
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    filtered = filtered.filter(loc => 
      loc.code.toLowerCase().includes(query) || 
      loc.name.toLowerCase().includes(query) ||
      (loc.description && loc.description.toLowerCase().includes(query))
    );
  }
  
  // Apply status filter
  if (statusFilter.value !== 'all') {
    const isActive = statusFilter.value === 'active';
    filtered = filtered.filter(loc => loc.is_active === isActive);
  }
  
  return filtered.length;
});

const totalPages = computed(() => Math.ceil(totalItems.value / itemsPerPage.value));

// Methods
const fetchLocations = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/material-management/locations/view-print');
    locations.value = response.data;
  } catch (error) {
    console.error('Failed to load locations:', error);
  } finally {
    loading.value = false;
  }
};

const refreshData = () => {
  fetchLocations();
  searchQuery.value = '';
  statusFilter.value = 'all';
  sortBy.value = 'code';
  currentPage.value = 1;
};

const exportData = () => {
  // Create filtered and sorted data for export
  let exportData = [...locations.value];
  
  // Apply status filter
  if (statusFilter.value !== 'all') {
    const isActive = statusFilter.value === 'active';
    exportData = exportData.filter(loc => loc.is_active === isActive);
  }
  
  // Apply sorting
  exportData.sort((a, b) => {
    if (sortBy.value === 'code') {
      return a.code.localeCompare(b.code);
    } else if (sortBy.value === 'name') {
      return a.name.localeCompare(b.name);
    } else if (sortBy.value === 'status') {
      return (a.is_active === b.is_active) ? 0 : a.is_active ? -1 : 1;
    }
    return 0;
  });

  // Create CSV content
  const csvRows = [];
  csvRows.push(['No.', 'Code', 'Name', 'Description', 'Status'].join(','));
  
  exportData.forEach((loc, index) => {
    csvRows.push([
      index + 1,
      `"${loc.code}"`,
      `"${loc.name}"`,
      `"${loc.description || ''}"`,
      `"${loc.is_active ? 'Active' : 'Inactive'}"`,
    ].join(','));
  });
  
  const csvContent = csvRows.join('\n');
  const blob = new Blob([csvContent], { type: 'text/csv' });
  const url = window.URL.createObjectURL(blob);
  
  const a = document.createElement('a');
  a.setAttribute('hidden', '');
  a.setAttribute('href', url);
  a.setAttribute('download', 'locations_report.csv');
  document.body.appendChild(a);
  a.click();
  document.body.removeChild(a);
};

const printDocument = () => {
  window.print();
};

// Reset to first page when filters change
watch([searchQuery, statusFilter, sortBy], () => {
  currentPage.value = 1;
});

// Initialize data
onMounted(() => {
  fetchLocations();
});
</script>

<style scoped>
@media print {
  @page {
    size: landscape;
  }
  
  .print-header {
    display: block !important;
  }
  
  .print\:hidden {
    display: none !important;
  }
  
  body {
    font-size: 12pt;
  }
  
  table {
    width: 100%;
    border-collapse: collapse;
  }
  
  th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
  }
  
  thead {
    display: table-header-group;
  }
  
  tfoot {
    display: table-footer-group;
  }
  
  tr {
    page-break-inside: avoid;
  }
}

.print-header {
  display: none;
}
</style> 