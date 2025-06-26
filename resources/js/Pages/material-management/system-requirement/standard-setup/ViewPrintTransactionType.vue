<template>
  <AppLayout :header="'View & Print Transaction Type'">
    <div class="py-6">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
          <div class="p-6">
            <!-- Action Bar -->
            <div class="flex flex-wrap justify-between items-center mb-4 gap-2">
              <div class="flex gap-2">
                <button @click="printTable" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 flex items-center">
                  <PrinterIcon class="w-5 h-5 mr-1" /> Print
                </button>
                <button @click="exportData" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 flex items-center">
                  <DocumentDownloadIcon class="w-5 h-5 mr-1" /> Export
                </button>
                <button @click="refreshData" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 flex items-center">
                  <RefreshIcon class="w-5 h-5 mr-1" /> Refresh
                </button>
              </div>
              <div class="relative">
                <input v-model="searchQuery" type="text" placeholder="Search..." class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <SearchIcon class="w-5 h-5 text-gray-400" />
                </div>
              </div>
            </div>

            <!-- Table -->
            <div id="transactionTypeTable" class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Group</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-if="loading">
                    <td colspan="3" class="px-6 py-4 text-center">
                      <svg class="animate-spin h-5 w-5 text-blue-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                    </td>
                  </tr>
                  <tr v-else-if="filteredRows.length === 0">
                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">No data found</td>
                  </tr>
                  <tr v-for="row in filteredRows" :key="row.code" class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-900">{{ row.code }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ row.name }}</td>
                    <td class="px-6 py-4 text-gray-700">
                      <span :class="getGroupBadgeClass(row.group)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                        {{ row.group }}
                      </span>
                    </td>
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
import { PrinterIcon, DocumentDownloadIcon, RefreshIcon, SearchIcon } from '@heroicons/vue/solid';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

const rows = ref([]);
const loading = ref(false);
const searchQuery = ref('');

const fetchData = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/material-management/transaction-types');
    rows.value = response.data;
  } catch (error) {
    console.error('Error fetching transaction types:', error);
  } finally {
    loading.value = false;
  }
};

const refreshData = () => {
  fetchData();
};

const filteredRows = computed(() => {
  if (!searchQuery.value) return rows.value;
  const q = searchQuery.value.toLowerCase();
  return rows.value.filter(row =>
    row.code.toLowerCase().includes(q) ||
    row.name.toLowerCase().includes(q) ||
    row.group.toLowerCase().includes(q)
  );
});

const getGroupBadgeClass = (group) => {
  switch (group) {
    case 'PO':
      return 'bg-blue-100 text-blue-800';
    case 'IC':
      return 'bg-green-100 text-green-800';
    case 'CX':
      return 'bg-purple-100 text-purple-800';
    default:
      return 'bg-gray-100 text-gray-800';
  }
};

const printTable = () => {
  const printContents = document.getElementById('transactionTypeTable').innerHTML;
  const originalContents = document.body.innerHTML;
  document.body.innerHTML = `
    <div class='print-container'>
      <style>
        @media print {
          body { font-family: Arial, sans-serif; }
          table { width: 100%; border-collapse: collapse; }
          th, td { padding: 8px; border: 1px solid #ddd; text-align: left; }
          th { background-color: #f2f2f2; }
          h1 { font-size: 18px; margin-bottom: 10px; }
          .inline-flex { display: inline-flex; }
          button, .pagination, input, select { display: none !important; }
        }
      </style>
      ${printContents}
    </div>
  `;
  window.print();
  document.body.innerHTML = originalContents;
};

const exportData = () => {
  let csvContent = 'data:text/csv;charset=utf-8,';
  csvContent += 'Code,Name,Group\n';
  filteredRows.value.forEach(row => {
    csvContent += `"${row.code}","${row.name}","${row.group}"\n`;
  });
  const encodedUri = encodeURI(csvContent);
  const link = document.createElement('a');
  link.setAttribute('href', encodedUri);
  link.setAttribute('download', 'transaction_types.csv');
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
};

onMounted(() => {
  fetchData();
});
</script>

<style scoped>
@media print {
  .no-print {
    display: none;
  }
}
</style>
