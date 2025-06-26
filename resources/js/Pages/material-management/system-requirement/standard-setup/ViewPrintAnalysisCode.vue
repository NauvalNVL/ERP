<template>
  <AppLayout :header="'View & Print Analysis Code'">
    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Main Content Card -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="p-6">
            <!-- Action buttons and filters -->
            <div class="mb-6 flex flex-wrap justify-between items-center gap-4">
              <div class="flex flex-wrap gap-2">
                <button 
                  @click="printTable" 
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
                
                <!-- Group Filter -->
                <div class="relative">
                  <select
                    v-model="groupFilter"
                    @change="filterData"
                    class="pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                  >
                    <option value="">All Groups</option>
                    <option v-for="group in availableGroups" :key="group" :value="group">
                      {{ group }}
                    </option>
                  </select>
                </div>
                
                <!-- Group2 Filter -->
                <div class="relative">
                  <select
                    v-model="group2Filter"
                    @change="filterData"
                    class="pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                  >
                    <option value="">All Group2s</option>
                    <option v-for="group2 in availableGroup2s" :key="group2" :value="group2">
                      {{ group2 }}
                    </option>
                  </select>
                </div>
              </div>
              
              <div class="relative">
                <input 
                  v-model="searchQuery"
                  type="text" 
                  placeholder="Search analysis codes..."
                  class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <SearchIcon class="w-5 h-5 text-gray-400" />
                </div>
              </div>
            </div>
            
            <!-- Analysis Code Table -->
            <div id="analysisCodeTable" class="overflow-hidden">
              <div class="flex justify-between items-center mb-4">
                <h1 class="text-xl font-bold text-gray-800">Analysis Code List</h1>
                <p class="text-sm text-gray-600">{{ new Date().toLocaleString() }}</p>
              </div>
              
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Code
                      </th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Name
                      </th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Group
                      </th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Group 2
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="loading">
                      <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="flex justify-center">
                          <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                          </svg>
                        </div>
                      </td>
                    </tr>
                    <tr v-else-if="filteredAnalysisCodes.length === 0">
                      <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                        No analysis codes found
                      </td>
                    </tr>
                    <tr v-for="analysisCode in paginatedAnalysisCodes" 
                        :key="analysisCode.code" 
                        class="hover:bg-gray-50 transition-colors">
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-gray-900">{{ analysisCode.code }}</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                        {{ analysisCode.name }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-blue-100 text-blue-800">
                          {{ analysisCode.group }}
                        </span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-purple-100 text-purple-800">
                          {{ analysisCode.group2 }}
                        </span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              
              <!-- Table Footer with Pagination -->
              <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                <div class="flex items-center justify-between">
                  <div class="text-sm text-gray-700">
                    Showing {{ filteredAnalysisCodes.length > 0 ? ((currentPage - 1) * itemsPerPage) + 1 : 0 }} 
                    to {{ Math.min(filteredAnalysisCodes.length, currentPage * itemsPerPage) }} 
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
                      :disabled="currentPage === totalPages"
                      :class="[
                        'relative inline-flex items-center px-4 py-2 text-sm font-medium rounded-md',
                        currentPage === totalPages 
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
import { PrinterIcon, DocumentDownloadIcon, RefreshIcon, SearchIcon } from '@heroicons/vue/solid';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

// State
const analysisCodes = ref([]);
const availableGroups = ref([]);
const availableGroup2s = ref([]);
const groupFilter = ref('');
const group2Filter = ref('');
const searchQuery = ref('');
const loading = ref(false);
const currentPage = ref(1);
const itemsPerPage = ref(10);

// Fetch data
const fetchData = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/material-management/analysis-codes');
    analysisCodes.value = response.data;
    fetchGroups();
    fetchGroup2s();
  } catch (error) {
    console.error('Error fetching analysis codes:', error);
  } finally {
    loading.value = false;
  }
};

const fetchGroups = async () => {
  try {
    const response = await axios.get('/api/material-management/analysis-codes/groups');
    availableGroups.value = response.data;
  } catch (error) {
    console.error('Error fetching groups:', error);
  }
};

const fetchGroup2s = async () => {
  try {
    const response = await axios.get('/api/material-management/analysis-codes/group2s');
    availableGroup2s.value = response.data;
  } catch (error) {
    console.error('Error fetching group2s:', error);
  }
};

// Filter data
const filterData = () => {
  currentPage.value = 1;
};

const filteredAnalysisCodes = computed(() => {
  let filtered = [...analysisCodes.value];
  
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    filtered = filtered.filter(code => 
      code.code.toLowerCase().includes(query) || 
      code.name.toLowerCase().includes(query) ||
      code.group.toLowerCase().includes(query) ||
      code.group2.toLowerCase().includes(query)
    );
  }
  
  if (groupFilter.value) {
    filtered = filtered.filter(code => code.group === groupFilter.value);
  }
  
  if (group2Filter.value) {
    filtered = filtered.filter(code => code.group2 === group2Filter.value);
  }
  
  return filtered;
});

// Pagination
const totalItems = computed(() => filteredAnalysisCodes.value.length);
const totalPages = computed(() => Math.ceil(totalItems.value / itemsPerPage.value));

const paginatedAnalysisCodes = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return filteredAnalysisCodes.value.slice(start, end);
});

// Actions
const refreshData = () => {
  fetchData();
};

const printTable = () => {
  const printContents = document.getElementById('analysisCodeTable').innerHTML;
  const originalContents = document.body.innerHTML;
  
  document.body.innerHTML = `
    <div class="print-container">
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
  // Create CSV content
  let csvContent = "data:text/csv;charset=utf-8,";
  csvContent += "Code,Name,Group,Group2\n";
  
  filteredAnalysisCodes.value.forEach(code => {
    csvContent += `"${code.code}","${code.name}","${code.group}","${code.group2}"\n`;
  });
  
  // Create download link
  const encodedUri = encodeURI(csvContent);
  const link = document.createElement("a");
  link.setAttribute("href", encodedUri);
  link.setAttribute("download", "analysis_codes.csv");
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
};

// Watch for filter changes
watch(searchQuery, () => {
  currentPage.value = 1;
});

// Fetch data on component mount
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