<template>
  <AppLayout title="View & Print SKU">
    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
          <h1 class="text-2xl font-semibold text-gray-800">View & Print SKU</h1>
          
          <div class="flex space-x-3">
            <button 
              @click="exportToCSV"
              class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
            >
              <i class="fas fa-file-csv mr-2"></i> Export to CSV
            </button>
            
            <button 
              @click="print"
              class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              <i class="fas fa-print mr-2"></i> Print
            </button>
          </div>
        </div>
        
        <!-- Search and filter section -->
        <div class="mt-4 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4 border border-gray-200 dark:border-gray-700 print:hidden">
          <div class="flex flex-wrap gap-4">
            <div class="w-full sm:w-64">
              <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <i class="fas fa-search text-gray-400"></i>
                </div>
                <input 
                  v-model="filters.search" 
                  @input="applyFilters"
                  type="text" 
                  class="form-input block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                  placeholder="Search SKU or name"
                />
              </div>
            </div>
            
            <div class="w-full sm:w-64">
              <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
              <select 
                v-model="filters.category_code" 
                @change="applyFilters"
                class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
              >
                <option value="">All Categories</option>
                <option v-for="category in categories" :key="category.code" :value="category.code">
                  {{ category.name }}
                </option>
              </select>
            </div>
            
            <div class="w-full sm:w-64">
              <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
              <select 
                v-model="filters.sort_by" 
                @change="applyFilters"
                class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
              >
                <option value="sku">SKU Number</option>
                <option value="sku_name">SKU Name</option>
                <option value="category_code">Category</option>
                <option value="type">Type</option>
                <option value="uom">UOM</option>
                <option value="boh">BOH</option>
                <option value="is_active">Active Status</option>
              </select>
            </div>
            
            <div class="w-full sm:w-64">
              <label class="block text-sm font-medium text-gray-700 mb-1">Sort Direction</label>
              <select 
                v-model="filters.sort_direction" 
                @change="applyFilters"
                class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
              >
                <option value="asc">Ascending</option>
                <option value="desc">Descending</option>
              </select>
            </div>
            
            <div class="w-full sm:w-auto flex items-end">
              <button 
                @click="resetFilters"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
              >
                <i class="fas fa-redo-alt mr-2"></i> Reset
              </button>
            </div>
          </div>
        </div>
        
        <!-- Print Header (only visible when printing) -->
        <div class="hidden print:block mb-6">
          <div class="text-center">
            <h2 class="text-xl font-bold">ERP System</h2>
            <h3 class="text-lg font-semibold">SKU Listing</h3>
            <p class="text-sm text-gray-600">Generated on {{ formatDate(new Date()) }}</p>
          </div>
        </div>
        
        <!-- Main content section -->
        <div class="mt-4">
          <!-- Loading indicator -->
          <div v-if="loading" class="flex justify-center items-center py-8 print:hidden">
            <div class="animate-spin rounded-full h-10 w-10 border-t-2 border-b-2 border-blue-500"></div>
          </div>
          
          <!-- Error message -->
          <div v-else-if="error" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 print:hidden" role="alert">
            <p class="font-bold">Error</p>
            <p>{{ error }}</p>
          </div>
          
          <!-- SKU Table -->
          <div v-else-if="displaySkus.length > 0" class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 print:shadow-none print:border-0">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 print:bg-white">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider print:border-b print:border-gray-300">
                      SKU#
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider print:border-b print:border-gray-300">
                      Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider print:border-b print:border-gray-300">
                      SKU Name
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider print:border-b print:border-gray-300">
                      Category
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider print:border-b print:border-gray-300">
                      Type
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider print:border-b print:border-gray-300">
                      UOM
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider print:border-b print:border-gray-300">
                      BOH
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="sku in displaySkus" :key="sku.sku" class="hover:bg-gray-50 print:hover:bg-white">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 print:py-2">
                      {{ sku.sku }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm print:py-2">
                      <span 
                        :class="{
                          'print:bg-white print:border print:py-0': true,
                          'px-2 inline-flex text-xs leading-5 font-semibold rounded-full': true,
                          'bg-green-100 text-green-800': sku.is_active,
                          'bg-red-100 text-red-800': !sku.is_active
                        }"
                      >
                        {{ sku.is_active ? 'Active' : 'Inactive' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 print:py-2">
                      {{ sku.sku_name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 print:py-2">
                      {{ sku.category ? sku.category.name : 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 print:py-2">
                      {{ sku.type || 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 print:py-2">
                      {{ sku.uom || 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 print:py-2">
                      {{ formatNumber(sku.boh) }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          
          <!-- Empty state -->
          <div v-else class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6 print:hidden">
            <div class="text-center">
              <i class="fas fa-box-open text-4xl text-gray-400 mb-3"></i>
              <h3 class="text-lg leading-6 font-medium text-gray-900">No SKUs found</h3>
              <p class="mt-1 text-sm text-gray-500">
                Try adjusting your search or filter settings.
              </p>
            </div>
          </div>
        </div>
        
        <!-- Print Footer (only visible when printing) -->
        <div class="hidden print:block mt-6">
          <div class="text-center text-sm text-gray-500">
            <p>Page 1 of 1</p>
            <p>© {{ new Date().getFullYear() }} ERP System. All rights reserved.</p>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';
import debounce from 'lodash/debounce';
import { useToast } from '@/Composables/useToast';

// Toast notification
const { showToast } = useToast();

// Data
const skus = ref([]);
const filteredSkus = ref([]);
const displaySkus = ref([]);
const categories = ref([]);

// UI state
const loading = ref(false);
const error = ref(null);

// Filters
const filters = ref({
  search: '',
  category_code: '',
  sort_by: 'sku',
  sort_direction: 'asc'
});

// Methods
const fetchSkus = async () => {
  try {
    loading.value = true;
    error.value = null;
    
    const params = { ...filters.value };
    
    const response = await axios.get('/api/skus-for-print', { params });
    skus.value = response.data;
    applyFilters();
  } catch (err) {
    console.error('Error fetching SKUs:', err);
    error.value = 'Failed to load SKUs. Please try again.';
  } finally {
    loading.value = false;
  }
};

const fetchCategories = async () => {
  try {
    const response = await axios.get('/api/categories');
    categories.value = response.data;
  } catch (err) {
    console.error('Error fetching categories:', err);
    showToast('Failed to load categories', 'error');
  }
};

const applyFilters = debounce(() => {
  // Apply search filter
  let result = [...skus.value];
  
  if (filters.value.search) {
    const searchTerm = filters.value.search.toLowerCase();
    result = result.filter(sku => {
      return (
        sku.sku.toLowerCase().includes(searchTerm) ||
        sku.sku_name.toLowerCase().includes(searchTerm)
      );
    });
  }
  
  // Apply category filter
  if (filters.value.category_code) {
    result = result.filter(sku => sku.category_code === filters.value.category_code);
  }
  
  // Apply sorting
  result.sort((a, b) => {
    const sortField = filters.value.sort_by;
    let aValue = a[sortField];
    let bValue = b[sortField];
    
    // Handle special cases
    if (sortField === 'category_code' && a.category && b.category) {
      aValue = a.category.name;
      bValue = b.category.name;
    }
    
    // Handle nullish values
    if (aValue == null) aValue = '';
    if (bValue == null) bValue = '';
    
    // Default string comparison
    const result = String(aValue).localeCompare(String(bValue));
    
    // Apply sort direction
    return filters.value.sort_direction === 'desc' ? -result : result;
  });
  
  filteredSkus.value = result;
  displaySkus.value = filteredSkus.value;
}, 300);

const resetFilters = () => {
  filters.value = {
    search: '',
    category_code: '',
    sort_by: 'sku',
    sort_direction: 'asc'
  };
  applyFilters();
};

const formatNumber = (value) => {
  if (value === null || value === undefined) return '0.000';
  return parseFloat(value).toFixed(3);
};

const formatDate = (date) => {
  return new Intl.DateTimeFormat('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }).format(date);
};

const print = () => {
  window.print();
};

const exportToCSV = () => {
  if (!filteredSkus.value.length) {
    showToast('No data to export', 'warning');
    return;
  }
  
  // Prepare CSV header
  const headers = [
    'SKU',
    'Status',
    'SKU Name',
    'Category',
    'Type',
    'UOM',
    'BOH',
    'FPO',
    'ROL',
    'Total Part',
    'Min Qty',
    'Max Qty'
  ];
  
  // Prepare CSV rows
  const rows = filteredSkus.value.map(sku => {
    return [
      sku.sku || '',
      sku.is_active ? 'Active' : 'Inactive',
      sku.sku_name || '',
      (sku.category ? sku.category.name : '') || '',
      sku.type || '',
      sku.uom || '',
      formatNumber(sku.boh),
      formatNumber(sku.fpo),
      formatNumber(sku.rol),
      sku.total_part || '0',
      formatNumber(sku.min_qty),
      formatNumber(sku.max_qty)
    ];
  });
  
  // Combine headers and rows
  const csvContent = [
    headers.join(','),
    ...rows.map(row => row.join(','))
  ].join('\n');
  
  // Create and trigger download
  const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
  const url = URL.createObjectURL(blob);
  const link = document.createElement('a');
  link.setAttribute('href', url);
  link.setAttribute('download', `sku_report_${new Date().toISOString().slice(0, 10)}.csv`);
  link.style.display = 'none';
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
};

// Lifecycle hooks
onMounted(async () => {
  try {
    await Promise.all([
      fetchSkus(),
      fetchCategories()
    ]);
  } catch (err) {
    console.error('Error during initial data loading:', err);
  }
});

// Watch for filter changes
watch(filters.value, () => {
  applyFilters();
}, { deep: true });
</script>

<style>
@media print {
  @page {
    size: landscape;
    margin: 2cm;
  }
  
  body {
    font-size: 12pt;
  }
  
  table {
    width: 100%;
    border-collapse: collapse;
  }
  
  th, td {
    padding: 0.25rem 0.5rem;
    border-bottom: 1px solid #e2e8f0;
  }
}
</style> 