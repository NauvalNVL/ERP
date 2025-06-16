<template>
  <AppLayout :header="'View & Print Sales Order Control Period'">
    <Head title="View & Print Sales Order Control Period" />
    
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-print mr-3"></i> View & Print Sales Order Control Period
      </h2>
      <p class="text-cyan-100">Preview and print sales order control period data</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
      <!-- Actions Bar -->
      <div class="flex flex-wrap items-center justify-between mb-6">
        <div class="flex items-center space-x-2 mb-3 sm:mb-0">
          <button @click="printTable" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex items-center space-x-2">
            <i class="fas fa-print mr-2"></i> Print List
          </button>
          <Link href="/sales-order/setup/define-so-period" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2">
            <i class="fas fa-arrow-left mr-2"></i> Back to SO Period Definition
          </Link>
        </div>
        <div class="relative">
          <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <i class="fas fa-search text-gray-400"></i>
          </div>
          <input 
            type="text" 
            v-model="searchQuery" 
            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
            placeholder="Search periods..."
          >
        </div>
      </div>

      <!-- Table Section -->
      <div class="overflow-x-auto">
        <div id="printableTable" class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
          <!-- Table Header -->
          <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white py-4 px-6 flex items-center">
            <div class="flex items-center">
              <div class="mr-4">
                <i class="fas fa-calendar-alt text-3xl"></i>
              </div>
              <div>
                <h2 class="text-xl font-bold">SALES ORDER CONTROL PERIOD LIST</h2>
                <p class="text-sm opacity-80">View and print sales order control period data</p>
              </div>
            </div>
          </div>

          <!-- Table Content -->
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th @click="sortTable('period')" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                  Period <i :class="getSortIcon('period')"></i>
                </th>
                <th @click="sortTable('count')" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                  Count <i :class="getSortIcon('count')"></i>
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="loading" class="hover:bg-gray-50">
                <td colspan="2" class="px-3 py-4 text-center text-gray-500">
                  <div class="flex justify-center">
                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"></div>
                  </div>
                  <p class="mt-2">Loading sales order control period data...</p>
                </td>
              </tr>
              <tr v-else-if="filteredPeriods.length === 0" class="hover:bg-gray-50">
                <td colspan="2" class="px-3 py-4 text-center text-gray-500">
                  No sales order control periods found.
                  <template v-if="searchQuery">
                    <p class="mt-2">No results match your search query: "{{ searchQuery }}"</p>
                    <button @click="searchQuery = ''" class="mt-2 text-blue-500 hover:underline">Clear search</button>
                  </template>
                </td>
              </tr>
              <tr v-for="(item, index) in filteredPeriods" :key="item.period" 
                :class="{'bg-blue-50': index % 2 === 0}" 
                class="hover:bg-blue-100">
                <td class="px-3 py-4 whitespace-nowrap text-sm font-medium">{{ item.period || 'N/A' }}</td>
                <td class="px-3 py-4 whitespace-nowrap text-sm">{{ item.count || 'N/A' }}</td>
              </tr>
            </tbody>
          </table>

          <!-- Table Footer -->
          <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 text-sm text-gray-500">
            <div class="flex items-center justify-between">
              <div>Total Sales Order Control Periods: {{ filteredPeriods.length }}</div>
              <div v-if="searchQuery">Filtered from {{ periods.length }} total records</div>
              <div class="text-xs text-gray-400">Generated: {{ currentDate }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Print Instructions -->
      <div class="mt-6 bg-blue-50 p-4 rounded-lg border border-blue-100">
        <h3 class="font-semibold text-blue-800 mb-2 flex items-center">
          <i class="fas fa-info-circle mr-2"></i> Print Instructions
        </h3>
        <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
          <li>Click the "Print List" button above to print this sales order control period list</li>
          <li>Use landscape orientation for better results</li>
          <li>You can search or sort data before printing</li>
          <li>Only the table will be included in the print output</li>
        </ul>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

// Data
const periods = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const sortColumn = ref('period');
const sortDirection = ref('asc');
const currentDate = new Date().toLocaleString();

// Fetch periods from the API
const fetchPeriods = async () => {
    loading.value = true;
    try {
        const response = await fetch('/api/so-periods', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to fetch sales order control periods');
        }
        
        const data = await response.json();
        
        // Handle different API response formats
        if (data.data) {
            periods.value = data.data;
        } else if (Array.isArray(data)) {
            periods.value = data;
        } else {
            console.error('Unexpected API response format:', data);
            periods.value = [];
        }
    } catch (error) {
        console.error('Error fetching sales order control periods:', error);
        periods.value = [];
    } finally {
        loading.value = false;
    }
};

// Sort table
const sortTable = (column) => {
    if (sortColumn.value === column) {
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortColumn.value = column;
        sortDirection.value = 'asc';
    }
};

// Get sort icon
const getSortIcon = (column) => {
    if (sortColumn.value !== column) {
        return 'fas fa-sort text-gray-300';
    }
    
    return sortDirection.value === 'asc' 
        ? 'fas fa-sort-up text-blue-500' 
        : 'fas fa-sort-down text-blue-500';
};

// Filtered and sorted periods
const filteredPeriods = computed(() => {
    let filtered = [...periods.value];
    
    // Apply search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(item => 
            (item.period && String(item.period).toLowerCase().includes(query)) ||
            (item.count && String(item.count).includes(query))
        );
    }
    
    // Apply sorting
    filtered.sort((a, b) => {
        let valueA = a[sortColumn.value];
        let valueB = b[sortColumn.value];
        
        // Handle null values
        if (valueA === null || valueA === undefined) valueA = '';
        if (valueB === null || valueB === undefined) valueB = '';
        
        // Special handling for period (mm/yyyy) for proper sorting
        if (sortColumn.value === 'period') {
            const [monthA, yearA] = valueA.split('/').map(Number);
            const [monthB, yearB] = valueB.split('/').map(Number);

            if (sortDirection.value === 'asc') {
                if (yearA !== yearB) return yearA - yearB;
                return monthA - monthB;
            } else {
                if (yearA !== yearB) return yearB - yearA;
                return monthB - monthA;
            }
        }
        
        // Convert to string for comparison if not already (for count)
        if (typeof valueA !== 'string') valueA = String(valueA || '');
        if (typeof valueB !== 'string') valueB = String(valueB || '');
        
        // Case insensitive comparison (for count, it will be numeric)
        if (sortDirection.value === 'asc') {
            return valueA.localeCompare(valueB);
        } else {
            return valueB.localeCompare(valueA);
        }
    });
    
    return filtered;
});

// Print table
const printTable = () => {
    const printableContent = document.getElementById('printableTable').outerHTML;
    const originalContent = document.body.innerHTML;
    document.body.innerHTML = printableContent;
    window.print();
    document.body.innerHTML = originalContent;
    location.reload(); // Reload to restore original page state and functionality
};

// Lifecycle hook
onMounted(() => {
    fetchPeriods();
});

</script> 

<style scoped>
/* Add any specific styles here if needed */
</style> 