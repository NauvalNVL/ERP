<template>
  <AppLayout :header="'View & Print SKU Tariff Code'">
    <Head title="View & Print SKU Tariff Code" />
    <ToastContainer />
    
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-print mr-3"></i> View & Print SKU Tariff Code
      </h2>
      <p class="text-blue-100">View and print SKU tariff code assignments for customs management</p>
    </div>

    <!-- Toolbar Section -->
    <div class="bg-white border-b border-gray-200 px-6 py-3">
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
          <!-- Exit Button -->
          <button @click="$router.back()" class="btn-danger">
            <i class="fas fa-power-off mr-2"></i>
            Exit
          </button>
          
          <!-- Print Button -->
          <button @click="printReport" class="btn-secondary">
            <i class="fas fa-print mr-2"></i>
            Print
          </button>
          
          <!-- Refresh Button -->
          <button @click="refreshData" class="btn-secondary">
            <i class="fas fa-sync-alt mr-2"></i>
            Refresh
          </button>
        </div>
        
        <!-- Search Section -->
        <div class="flex items-center space-x-4">
          <input 
            type="text" 
            v-model="searchQuery" 
            @input="handleSearch"
            placeholder="Search SKU or tariff code..."
            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
          >
          <button @click="handleSearch" class="btn-primary">
            <i class="fas fa-search mr-2"></i>
            Search
          </button>
        </div>
      </div>
    </div>

    <!-- Main Content Area -->
    <div class="bg-white rounded-b-lg shadow-md p-6 mb-6">
      <!-- SKU Tariff Code Table -->
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU#</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU Name</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tariff Code</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-if="loading" class="animate-pulse">
              <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                <i class="fas fa-spinner fa-spin"></i> Loading SKU tariff codes...
              </td>
            </tr>
            <tr v-else-if="skuTariffCodes.length === 0" class="hover:bg-gray-50">
              <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                No SKU tariff codes found. Try adjusting your search.
              </td>
            </tr>
            <tr v-for="record in skuTariffCodes" :key="record.id" 
                @click="selectRecord(record)" 
                :class="{'bg-blue-50': selectedRecord && selectedRecord.id === record.id}"
                class="hover:bg-gray-50 cursor-pointer">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ record.sku_id }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                <span :class="record.is_active ? 'text-green-600' : 'text-red-600'" class="font-medium">
                  {{ record.is_active ? 'A-Active' : '0-Obsolete' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ record.sku?.sku_name || 'N/A' }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                {{ record.custom_tariff_code?.code || 'Not assigned' }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div v-if="pagination" class="mt-4 flex items-center justify-between">
        <div class="text-sm text-gray-700">
          Showing {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total }} results
        </div>
        <div class="flex space-x-2">
          <button @click="loadData(pagination.current_page - 1)" :disabled="pagination.current_page <= 1" class="btn-secondary px-3 py-1">
            Previous
          </button>
          <button @click="loadData(pagination.current_page + 1)" :disabled="pagination.current_page >= pagination.last_page" class="btn-secondary px-3 py-1">
            Next
          </button>
        </div>
      </div>
    </div>

    <!-- Summary Panel -->
    <div v-if="summary" class="bg-white rounded-lg shadow-md p-6">
      <h3 class="text-lg font-semibold text-gray-800 mb-4">Summary</h3>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="text-center">
          <div class="text-2xl font-bold text-blue-600">{{ summary.total_assignments }}</div>
          <div class="text-sm text-gray-600">Total Assignments</div>
        </div>
        <div class="text-center">
          <div class="text-2xl font-bold text-green-600">{{ summary.active_assignments }}</div>
          <div class="text-sm text-gray-600">Active Assignments</div>
        </div>
        <div class="text-center">
          <div class="text-2xl font-bold text-orange-600">{{ summary.skus_with_tariff_codes }}</div>
          <div class="text-sm text-gray-600">SKUs with Tariff Codes</div>
        </div>
        <div class="text-center">
          <div class="text-2xl font-bold text-red-600">{{ summary.skus_without_tariff_codes }}</div>
          <div class="text-sm text-gray-600">SKUs without Tariff Codes</div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useToast } from '@/Composables/useToast';
import ToastContainer from '@/Components/ToastContainer.vue';

const toast = useToast();
const loading = ref(false);
const skuTariffCodes = ref([]);
const selectedRecord = ref(null);
const searchQuery = ref('');
const summary = ref(null);
const pagination = ref(null);

// Load SKU tariff codes data
const loadData = async (page = 1) => {
  loading.value = true;
  try {
    const params = {
      page,
      search: searchQuery.value
    };

    const res = await axios.get('/api/material-management/system-requirement/inventory-setup/sku-custom-tariff-codes', { params });
    
    if (res.data.data) {
      skuTariffCodes.value = res.data.data;
      pagination.value = res.data;
    } else {
      skuTariffCodes.value = res.data;
      pagination.value = null;
    }
  } catch (e) {
    toast.error('Failed to load SKU tariff codes');
  } finally {
    loading.value = false;
  }
};

const handleSearch = () => {
  loadData(1);
};

const selectRecord = (record) => {
  selectedRecord.value = record;
};

const refreshData = () => {
  loadData(1);
  loadSummary();
};

const printReport = () => {
  const printWindow = window.open('', '_blank');
  printWindow.document.write(`
    <html>
      <head>
        <title>SKU Tariff Code Report</title>
        <style>
          body { font-family: Arial, sans-serif; margin: 20px; }
          table { width: 100%; border-collapse: collapse; margin-top: 20px; }
          th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
          th { background-color: #f2f2f2; font-weight: bold; }
          .header { text-align: center; margin-bottom: 20px; }
          .status-active { color: green; font-weight: bold; }
          .status-inactive { color: red; font-weight: bold; }
        </style>
      </head>
      <body>
        <div class="header">
          <h1>SKU Tariff Code Report</h1>
          <p>Generated on: ${new Date().toLocaleDateString()}</p>
          <p>Total Records: ${skuTariffCodes.value.length}</p>
        </div>
        <table>
          <thead>
            <tr>
              <th>SKU#</th>
              <th>Status</th>
              <th>SKU Name</th>
              <th>Tariff Code</th>
            </tr>
          </thead>
          <tbody>
            ${skuTariffCodes.value.map(record => `
              <tr>
                <td>${record.sku_id}</td>
                <td class="${record.is_active ? 'status-active' : 'status-inactive'}">
                  ${record.is_active ? 'A-Active' : '0-Obsolete'}
                </td>
                <td>${record.sku?.sku_name || 'N/A'}</td>
                <td>${record.custom_tariff_code?.code || 'Not assigned'}</td>
              </tr>
            `).join('')}
          </tbody>
        </table>
      </body>
    </html>
  `);
  printWindow.document.close();
  printWindow.print();
};

const loadSummary = async () => {
  try {
    const res = await axios.get('/api/material-management/system-requirement/inventory-setup/sku-custom-tariff-codes/summary');
    summary.value = res.data;
  } catch (e) {
    console.error('Failed to load summary:', e);
  }
};

onMounted(() => {
  loadData();
  loadSummary();
});
</script>

<style scoped>
.btn-primary {
  @apply bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition flex items-center justify-center whitespace-nowrap;
}
.btn-secondary {
  @apply bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg shadow transition flex items-center justify-center whitespace-nowrap;
}
.btn-danger {
  @apply bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow transition flex items-center justify-center whitespace-nowrap;
}
tbody tr { transition: all 0.2s; }
tbody tr:hover { transform: translateY(-2px); box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
</style>
