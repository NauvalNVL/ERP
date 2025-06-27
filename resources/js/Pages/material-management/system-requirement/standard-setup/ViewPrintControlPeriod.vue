<template>
  <AppLayout :header="'View & Print Control Period'">
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
            <div id="controlPeriodTable" class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Period</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Control Date</th>
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
                  <tr v-for="row in filteredRows" :key="row.documentType" class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-900">{{ row.documentType }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ row.currentPeriod }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ row.controlDate }}</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Summary Section -->
            <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg border border-gray-200">
              <div>
                <div class="text-xs text-gray-500">Forward Period</div>
                <div class="font-semibold text-gray-800">{{ summary.forwardPeriod }} Months</div>
              </div>
              <div>
                <div class="text-xs text-gray-500">Backward Period</div>
                <div class="font-semibold text-gray-800">{{ summary.backwardPeriod }}</div>
              </div>
              <div>
                <div class="text-xs text-gray-500">Min. Allow %</div>
                <div class="font-semibold text-gray-800">{{ summary.minAllow }}%</div>
              </div>
              <div>
                <div class="text-xs text-gray-500">Max. Allow %</div>
                <div class="font-semibold text-gray-800">{{ summary.maxAllow }}%</div>
              </div>
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
const summary = ref({ forwardPeriod: '', backwardPeriod: '', minAllow: '', maxAllow: '' });
const loading = ref(false);
const searchQuery = ref('');

const fetchData = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/material-management/control-periods/summary');
    rows.value = response.data.rows;
    summary.value = response.data.summary;
  } catch (error) {
    console.error('Error fetching control period summary:', error);
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
    row.documentType.toLowerCase().includes(q) ||
    row.currentPeriod.toLowerCase().includes(q) ||
    row.controlDate.toLowerCase().includes(q)
  );
});

const printTable = () => {
  const printContents = document.getElementById('controlPeriodTable').innerHTML;
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
  csvContent += 'Document Type,Current Period,Control Date\n';
  filteredRows.value.forEach(row => {
    csvContent += `"${row.documentType}","${row.currentPeriod}","${row.controlDate}"\n`;
  });
  csvContent += `\nForward Period:,${summary.value.forwardPeriod} Months\n`;
  csvContent += `Backward Period:,${summary.value.backwardPeriod}\n`;
  csvContent += `Min. Allow %:,${summary.value.minAllow}\n`;
  csvContent += `Max. Allow %:,${summary.value.maxAllow}\n`;
  const encodedUri = encodeURI(csvContent);
  const link = document.createElement('a');
  link.setAttribute('href', encodedUri);
  link.setAttribute('download', 'control_period_summary.csv');
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
