<template>
  <AppLayout :header="'View & Print Tax Groups'">
    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Content Card -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
              <h2 class="text-xl font-semibold text-gray-800">Tax Group List</h2>
              
              <div class="flex space-x-3">
                <button 
                  @click="print" 
                  class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"
                >
                  <i class="fas fa-print w-5 h-5 mr-2"></i>
                  Print
                </button>
                <button 
                  @click="exportToCsv" 
                  class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring focus:ring-green-300 disabled:opacity-25 transition"
                >
                  <i class="fas fa-download w-5 h-5 mr-2"></i>
                  Export
                </button>
              </div>
            </div>
            
            <!-- Search and Filter -->
            <div class="flex justify-between mb-6">
              <div class="relative flex-1 max-w-md">
                <input 
                  type="text" 
                  v-model="searchQuery" 
                  placeholder="Search tax groups..." 
                  class="pl-10 w-full pr-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                />
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <i class="fas fa-search h-5 w-5 text-gray-400"></i>
                </div>
              </div>
            </div>
            
            <!-- Tax Group Table -->
            <div class="overflow-x-auto" id="printable-area">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Tax Group Code
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Tax Group Name
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Tax Types
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Created At
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Updated At
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-if="loading">
                    <td colspan="5" class="px-6 py-4 text-center">
                      <div class="flex justify-center">
                        <svg class="animate-spin h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                      </div>
                    </td>
                  </tr>
                  <tr v-else-if="filteredTaxGroups.length === 0">
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                      No tax groups found. Try adjusting your search criteria.
                    </td>
                  </tr>
                  <tr v-for="taxGroup in filteredTaxGroups" :key="taxGroup.code" class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                      {{ taxGroup.code }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                      {{ taxGroup.name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                      <!-- Display the tax types count and names when data is available -->
                      <span v-if="taxGroup.tax_types && taxGroup.tax_types.length > 0">
                        {{ taxGroup.tax_types.length }} types
                        <button 
                          @click="() => showTaxTypes(taxGroup)"
                          class="ml-1 text-blue-600 hover:text-blue-800 hover:underline text-xs"
                        >
                          (View details)
                        </button>
                      </span>
                      <span v-else class="text-gray-400 italic">None</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                      {{ formatDate(taxGroup.created_at) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                      {{ formatDate(taxGroup.updated_at) }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            
            <!-- Pagination -->
            <div class="flex items-center justify-between py-3 bg-white">
              <div class="flex justify-between w-full">
                <div class="text-sm text-gray-700">
                  Showing <span class="font-medium">{{ firstItemIndex }}</span> to <span class="font-medium">{{ lastItemIndex }}</span> of <span class="font-medium">{{ totalItems }}</span> items
                </div>
                <div class="flex space-x-2">
                  <button 
                    @click="previousPage" 
                    :disabled="currentPage === 1"
                    :class="[
                      'px-3 py-1 rounded-md',
                      currentPage === 1 ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
                    ]"
                  >
                    Previous
                  </button>
                  <button 
                    @click="nextPage" 
                    :disabled="currentPage >= totalPages"
                    :class="[
                      'px-3 py-1 rounded-md',
                      currentPage >= totalPages ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
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
    
    <!-- Tax Types Modal -->
    <TransitionRoot appear :show="isModalOpen" as="template">
      <Dialog as="div" @close="closeModal" class="relative z-10">
        <TransitionChild
          as="template"
          enter="duration-300 ease-out"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-black bg-opacity-25" />
        </TransitionChild>

        <div class="fixed inset-0 overflow-y-auto">
          <div class="flex min-h-full items-center justify-center p-4 text-center">
            <TransitionChild
              as="template"
              enter="duration-300 ease-out"
              enter-from="opacity-0 scale-95"
              enter-to="opacity-100 scale-100"
              leave="duration-200 ease-in"
              leave-from="opacity-100 scale-100"
              leave-to="opacity-0 scale-95"
            >
              <DialogPanel class="w-full max-w-lg transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                  Tax Types in {{ selectedTaxGroup?.name }}
                </DialogTitle>
                
                <div class="mt-2">
                  <div v-if="selectedTaxGroup && selectedTaxGroup.tax_types && selectedTaxGroup.tax_types.length > 0" 
                       class="max-h-96 overflow-y-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                      <thead class="bg-gray-50">
                        <tr>
                          <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                          <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        </tr>
                      </thead>
                      <tbody class="divide-y divide-gray-200">
                        <tr v-for="taxType in selectedTaxGroup.tax_types" :key="taxType.code" class="hover:bg-gray-50">
                          <td class="px-4 py-2 text-sm text-gray-900">{{ taxType.code }}</td>
                          <td class="px-4 py-2 text-sm text-gray-700">{{ taxType.name }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <p v-else class="text-gray-500 text-center py-4">No tax types found for this group.</p>
                </div>
                
                <div class="mt-4 flex justify-end">
                  <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    @click="closeModal"
                  >
                    Close
                  </button>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Dialog, DialogPanel, DialogTitle, TransitionRoot, TransitionChild } from '@headlessui/vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

// Data
const taxGroups = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const currentPage = ref(1);
const itemsPerPage = ref(10);
const isModalOpen = ref(false);
const selectedTaxGroup = ref(null);

// Computed properties for pagination
const filteredTaxGroups = computed(() => {
  if (!searchQuery.value) {
    return paginatedTaxGroups.value;
  }
  
  const query = searchQuery.value.toLowerCase();
  return taxGroups.value
    .filter(taxGroup => 
      taxGroup.code.toLowerCase().includes(query) || 
      taxGroup.name.toLowerCase().includes(query)
    )
    .slice(startIndex.value, endIndex.value);
});

const startIndex = computed(() => {
  return (currentPage.value - 1) * itemsPerPage.value;
});

const endIndex = computed(() => {
  return Math.min(startIndex.value + itemsPerPage.value, taxGroups.value.length);
});

const paginatedTaxGroups = computed(() => {
  return taxGroups.value.slice(startIndex.value, endIndex.value);
});

const totalItems = computed(() => {
  if (!searchQuery.value) {
    return taxGroups.value.length;
  }
  
  const query = searchQuery.value.toLowerCase();
  return taxGroups.value.filter(taxGroup => 
    taxGroup.code.toLowerCase().includes(query) || 
    taxGroup.name.toLowerCase().includes(query)
  ).length;
});

const totalPages = computed(() => {
  return Math.ceil(totalItems.value / itemsPerPage.value);
});

const firstItemIndex = computed(() => {
  return taxGroups.value.length === 0 ? 0 : startIndex.value + 1;
});

const lastItemIndex = computed(() => {
  return Math.min(startIndex.value + itemsPerPage.value, totalItems.value);
});

// Methods
const fetchTaxGroups = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/material-management/tax-groups');
    taxGroups.value = response.data;
  } catch (error) {
    console.error('Error fetching tax groups:', error);
    alert('Failed to load tax groups');
  } finally {
    loading.value = false;
  }
};

const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  }).format(date);
};

const previousPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
  }
};

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
  }
};

const exportToCsv = () => {
  const csvContent = 
    'Tax Group Code,Tax Group Name,Created At,Updated At\n' + 
    taxGroups.value.map(item => 
      `${item.code},${item.name},${item.created_at || ''},${item.updated_at || ''}`
    ).join('\n');
  
  const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
  const link = document.createElement('a');
  const url = URL.createObjectURL(blob);
  
  link.setAttribute('href', url);
  link.setAttribute('download', 'tax-groups.csv');
  link.style.visibility = 'hidden';
  
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
};

const print = () => {
  const printContents = document.getElementById('printable-area').innerHTML;
  const originalContents = document.body.innerHTML;
  
  document.body.innerHTML = `
    <html>
      <head>
        <title>Tax Groups Report</title>
        <style>
          body { font-family: Arial, sans-serif; }
          table { width: 100%; border-collapse: collapse; }
          th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
          th { background-color: #f2f2f2; }
          h1 { text-align: center; }
        </style>
      </head>
      <body>
        <h1>Tax Groups Report</h1>
        ${printContents}
      </body>
    </html>
  `;
  
  window.print();
  document.body.innerHTML = originalContents;
};

const showTaxTypes = (taxGroup) => {
  selectedTaxGroup.value = taxGroup;
  isModalOpen.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
  selectedTaxGroup.value = null;
};

// Lifecycle hooks
onMounted(() => {
  fetchTaxGroups();
});
</script>

<style scoped>
@media print {
  body * {
    visibility: hidden;
  }
  #printable-area, #printable-area * {
    visibility: visible;
  }
  #printable-area {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
  }
}
</style> 