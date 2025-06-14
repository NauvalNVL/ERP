<template>
  <AppLayout title="View & Print Computation Formula">
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          View & Print Computation Formula
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
            <div class="mb-6">
              <h3 class="text-lg font-medium text-gray-700 mb-4">Diecut Computation Formulas</h3>
              
              <!-- Search and Filter -->
              <div class="flex flex-wrap gap-4 mb-4">
                <div class="flex-1 min-w-[200px]">
                  <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                  <input
                    type="text"
                    id="search"
                    v-model="search"
                    placeholder="Search formulas..."
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                  />
                </div>
              </div>

              <!-- Formulas Table -->
              <div class="overflow-x-auto" ref="printableTable">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        ID
                      </th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Board Length
                      </th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Board Width
                      </th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        D/C Sheet Length
                      </th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        D/C Sheet Width
                      </th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        No of Up
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="formula in filteredFormulas" :key="formula.id" class="hover:bg-gray-50">
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ formula.id }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ formula.board_length_min }} - {{ formula.board_length_max }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ formula.board_width_min }} - {{ formula.board_width_max }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ formula.dc_sheet_length_min }} - {{ formula.dc_sheet_length_max }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ formula.dc_sheet_width_min }} - {{ formula.dc_sheet_width_max }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ formula.no_of_up_min }} - {{ formula.no_of_up_max }}
                      </td>
                    </tr>
                    <tr v-if="filteredFormulas.length === 0">
                      <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                        No formulas found
                      </td>
                    </tr>
                  </tbody>
                </table>
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
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

// State
const formulas = ref([]);
const search = ref('');
const loading = ref(false);
const printableTable = ref(null);

// Fetch formulas on component mount
onMounted(async () => {
  await fetchFormulas();
});

// Fetch formulas from API
const fetchFormulas = async () => {
  try {
    loading.value = true;
    const response = await axios.get('/api/diecut-computation-formulas');
    formulas.value = response.data.data;
  } catch (error) {
    console.error('Error fetching formulas:', error);
  } finally {
    loading.value = false;
  }
};

// Filter formulas based on search term
const filteredFormulas = computed(() => {
  if (!search.value) return formulas.value;
  
  const searchTerm = search.value.toLowerCase();
  return formulas.value.filter(formula => {
    return (
      formula.id.toString().includes(searchTerm) ||
      formula.board_length_min.toString().includes(searchTerm) ||
      formula.board_length_max.toString().includes(searchTerm) ||
      formula.board_width_min.toString().includes(searchTerm) ||
      formula.board_width_max.toString().includes(searchTerm)
    );
  });
});

// Print table function
const printTable = () => {
  const printContent = printableTable.value.innerHTML;
  const originalContent = document.body.innerHTML;
  
  const printWindow = window.open('', '_blank');
  printWindow.document.write(`
    <html>
      <head>
        <title>Diecut Computation Formulas</title>
        <style>
          body { font-family: Arial, sans-serif; }
          table { width: 100%; border-collapse: collapse; }
          th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
          th { background-color: #f2f2f2; }
          h1 { text-align: center; }
        </style>
      </head>
      <body>
        <h1>Diecut Computation Formulas</h1>
        <table>${printContent}</table>
      </body>
    </html>
  `);
  
  printWindow.document.close();
  printWindow.focus();
  printWindow.print();
  printWindow.close();
};
</script>

<style scoped>
@media print {
  .no-print {
    display: none;
  }
}
</style> 