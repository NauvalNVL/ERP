<template>
  <AppLayout :header="'View & Print Category'">
    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <!-- Top Action Bar -->
          <div class="p-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <div class="flex items-center space-x-2">
              <button
                @click="refreshData"
                class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                <i class="fas fa-sync-alt mr-2"></i>
                Refresh
              </button>
            </div>
            <div class="flex items-center space-x-2">
              <div class="relative">
                <input
                  type="text"
                  v-model="searchTerm"
                  placeholder="Search..."
                  class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                />
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <i class="fas fa-search text-gray-400"></i>
                </div>
              </div>
              <select
                v-model="statusFilter"
                class="block w-32 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
              >
                <option value="all">All Status</option>
                <option value="active">Active Only</option>
                <option value="inactive">Inactive Only</option>
              </select>
              <button
                @click="printCategories"
                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                <i class="fas fa-print mr-2"></i>
                Print
              </button>
              <Link
                href="/material-management/system-requirement/inventory-setup/category"
                class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                <i class="fas fa-arrow-left mr-2"></i>
                Back
              </Link>
            </div>
          </div>

          <!-- Category Table -->
          <div id="printableArea" class="overflow-x-auto">
            <div class="print-header text-center py-4 hidden">
              <h1 class="text-xl font-bold">Material Management Categories</h1>
              <p class="text-sm text-gray-500">Printed on {{ new Date().toLocaleDateString() }}</p>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Code
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Name
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Description
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Status
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-if="loading" class="animate-pulse">
                  <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                    Loading categories...
                  </td>
                </tr>
                <tr v-else-if="filteredCategories.length === 0" class="hover:bg-gray-50">
                  <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                    No categories found matching your criteria.
                  </td>
                </tr>
                <tr v-for="category in filteredCategories" :key="category.code" class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    {{ category.code }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ category.name }}
                  </td>
                  <td class="px-6 py-4 text-sm text-gray-500 max-w-xs">
                    {{ category.description || 'No description' }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <span
                      :class="category.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                      class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                    >
                      {{ category.is_active ? 'Active' : 'Inactive' }}
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

// State
const categories = ref([]);
const loading = ref(true);
const searchTerm = ref('');
const statusFilter = ref('all');

// Computed properties
const filteredCategories = computed(() => {
  let filtered = categories.value;
  
  // Apply status filter
  if (statusFilter.value === 'active') {
    filtered = filtered.filter(category => category.is_active);
  } else if (statusFilter.value === 'inactive') {
    filtered = filtered.filter(category => !category.is_active);
  }
  
  // Apply search filter
  if (searchTerm.value) {
    const term = searchTerm.value.toLowerCase();
    filtered = filtered.filter(category => 
      category.code.toLowerCase().includes(term) || 
      category.name.toLowerCase().includes(term) || 
      (category.description && category.description.toLowerCase().includes(term))
    );
  }
  
  return filtered;
});

// Methods
const fetchCategories = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/material-management/categories/for-print');
    categories.value = response.data;
  } catch (error) {
    console.error('Error fetching categories:', error);
  } finally {
    loading.value = false;
  }
};

const refreshData = () => {
  fetchCategories();
};

const printCategories = () => {
  // Add print-specific styles
  const style = document.createElement('style');
  style.innerHTML = `
    @media print {
      body * {
        visibility: hidden;
      }
      #printableArea, #printableArea * {
        visibility: visible;
      }
      #printableArea {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
      }
      .print-header {
        display: block !important;
      }
      thead {
        display: table-header-group;
      }
    }
  `;
  document.head.appendChild(style);
  
  window.print();
  
  // Clean up
  document.head.removeChild(style);
};

// Lifecycle hooks
onMounted(() => {
  fetchCategories();
});
</script>

<style>
@media print {
  .print-header {
    display: block !important;
  }
}
</style> 