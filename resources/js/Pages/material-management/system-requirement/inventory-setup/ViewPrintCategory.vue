<template>
  <AppLayout title="View & Print Categories">
    <div class="bg-white shadow rounded-lg p-6 print:shadow-none print:p-0">
      <div class="flex justify-between items-center mb-6 print:mb-2 print:flex-col print:items-start">
        <h1 class="text-2xl font-semibold text-gray-800">View & Print Categories</h1>
        <div class="flex space-x-3 print:hidden">
          <button
            @click="exportCSV"
            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition duration-200"
          >
            <i class="fas fa-file-csv mr-2"></i>Export CSV
          </button>
          <button
            @click="print"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200"
          >
            <i class="fas fa-print mr-2"></i>Print
          </button>
        </div>
      </div>
      
      <!-- Search & Filter Section (hidden when printing) -->
      <div class="mb-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 print:hidden">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
          <input
            v-model="search"
            type="text"
            placeholder="Search by code, name or description"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
          <select
            v-model="statusFilter"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            <option value="all">All</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
          <select
            v-model="sortField"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            <option value="code">Code</option>
            <option value="name">Name</option>
            <option value="updated_at">Last Updated</option>
          </select>
        </div>
      </div>
      
      <!-- Print Header (Visible only when printing) -->
      <div class="hidden print:block print:mb-4 print:border-b print:pb-2">
        <div class="flex flex-col items-center">
          <h1 class="text-xl font-bold">ERP System - Categories List</h1>
          <p class="text-sm text-gray-600">{{ printDate }}</p>
        </div>
      </div>
      
      <!-- Categories Table -->
      <div class="overflow-x-auto border-b border-gray-200 rounded-lg print:border-0 print:rounded-none">
        <table class="min-w-full divide-y divide-gray-200 print:border print:border-gray-300">
          <thead class="bg-gray-50">
            <tr class="print:border-b print:border-gray-300">
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider print:py-1 print:bg-gray-100">
                Code
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider print:py-1 print:bg-gray-100">
                Name
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider print:py-1 print:bg-gray-100">
                Description
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider print:py-1 print:bg-gray-100">
                Status
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider print:py-1 print:bg-gray-100">
                Last Updated
              </th>
            </tr>
          </thead>
          <tbody v-if="filteredCategories.length > 0" class="bg-white divide-y divide-gray-200">
            <tr v-for="category in filteredCategories" :key="category.code" class="hover:bg-gray-50 print:hover:bg-white print:border-b print:border-gray-200">
              <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800 print:py-2">
                {{ category.code }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-700 print:py-2">
                {{ category.name }}
              </td>
              <td class="px-6 py-4 text-gray-700 print:py-2">
                {{ category.description || '-' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap print:py-2">
                <span :class="[
                  'px-2 py-1 text-xs font-semibold rounded-full', 
                  category.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800',
                  'print:bg-transparent print:border print:border-current'
                ]">
                  {{ category.is_active ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-700 print:py-2">
                {{ formatDate(category.updated_at) }}
              </td>
            </tr>
          </tbody>
          <tbody v-else class="bg-white">
            <tr>
              <td colspan="5" class="px-6 py-4 text-center text-gray-500 italic">
                No categories found
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Print Footer -->
      <div class="hidden print:block print:mt-4 print:text-sm print:text-gray-500">
        <div class="flex justify-between">
          <span>Generated by ERP System</span>
          <span>Page 1 of 1</span>
        </div>
      </div>

      <div class="mt-6 text-right text-sm text-gray-500 print:hidden">
        Total Categories: {{ filteredCategories.length }}
      </div>
    </div>

    <ToastContainer />
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import ToastContainer from '@/Components/ToastContainer.vue';
import { useToast } from '@/Composables/useToast.js';

// Toast notifications
const { showToast } = useToast();

// Data
const categories = ref([]);
const search = ref('');
const statusFilter = ref('all');
const sortField = ref('code');
const sortDirection = ref('asc');
const isLoading = ref(true);

// Current date for print header
const printDate = computed(() => {
  return new Date().toLocaleString('en-US', {
    weekday: 'long',
    year: 'numeric', 
    month: 'long', 
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
});

// Fetch categories
const fetchCategories = async () => {
  isLoading.value = true;
  try {
    const response = await fetch('/api/material-management/categories/view-print');
    const data = await response.json();
    categories.value = data;
  } catch (error) {
    console.error('Error fetching categories:', error);
    showToast('Failed to load categories', 'error');
  } finally {
    isLoading.value = false;
  }
};

// Format date
const formatDate = (dateString) => {
  if (!dateString) return '-';
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }).format(date);
};

// Filtered and sorted categories
const filteredCategories = computed(() => {
  let filtered = [...categories.value];

  // Apply search filter
  if (search.value) {
    const searchLower = search.value.toLowerCase();
    filtered = filtered.filter(category => 
      (category.code && category.code.toLowerCase().includes(searchLower)) ||
      (category.name && category.name.toLowerCase().includes(searchLower)) ||
      (category.description && category.description.toLowerCase().includes(searchLower))
    );
  }

  // Apply status filter
  if (statusFilter.value !== 'all') {
    filtered = filtered.filter(category => 
      statusFilter.value === 'active' ? category.is_active : !category.is_active
    );
  }

  // Apply sorting
  filtered.sort((a, b) => {
    let fieldA = a[sortField.value];
    let fieldB = b[sortField.value];

    // Handle string comparison
    if (typeof fieldA === 'string' && typeof fieldB === 'string') {
      fieldA = fieldA.toLowerCase();
      fieldB = fieldB.toLowerCase();
    }

    if (fieldA < fieldB) return sortDirection.value === 'asc' ? -1 : 1;
    if (fieldA > fieldB) return sortDirection.value === 'asc' ? 1 : -1;
    return 0;
  });

  return filtered;
});

// Toggle sort direction
const toggleSort = (field) => {
  if (sortField.value === field) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortField.value = field;
    sortDirection.value = 'asc';
  }
};

// Print function
const print = () => {
  window.print();
};

// Export to CSV
const exportCSV = () => {
  if (categories.value.length === 0) {
    showToast('No data to export', 'error');
    return;
  }

  // Define CSV headers
  const headers = ['Code', 'Name', 'Description', 'Status', 'Last Updated'];
  
  // Convert categories to CSV rows
  const rows = filteredCategories.value.map(category => [
    category.code,
    category.name,
    category.description || '',
    category.is_active ? 'Active' : 'Inactive',
    formatDate(category.updated_at)
  ]);
  
  // Combine headers and rows
  const csvContent = [
    headers.join(','),
    ...rows.map(row => row.map(cell => `"${String(cell).replace(/"/g, '""')}"`).join(','))
  ].join('\n');
  
  // Create a Blob and download link
  const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
  const url = URL.createObjectURL(blob);
  const link = document.createElement('a');
  link.setAttribute('href', url);
  link.setAttribute('download', `categories-export-${new Date().toISOString().split('T')[0]}.csv`);
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
  
  showToast('CSV export successful', 'success');
};

// Initial fetch
onMounted(() => {
  fetchCategories();
});
</script>

<style scoped>
@media print {
  @page {
    margin: 1cm;
    size: portrait;
  }
  
  body {
    font-size: 12pt;
    line-height: 1.3;
    color: #000;
  }
  
  table {
    width: 100%;
    border-collapse: collapse;
  }
}
</style> 