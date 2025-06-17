<template>
  <AppLayout :header="'View & Print Master Cards'">
    <Head title="View & Print Master Cards" />

    <!-- Main Container with vibrant gradient background -->
    <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 p-4 md:p-6">
      <div class="max-w-7xl mx-auto">
        <!-- Header Section with animated elements -->
        <div class="bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 rounded-xl shadow-lg overflow-hidden mb-6 transform transition-all duration-500 hover:shadow-xl">
          <div class="relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10"></div>
            <div class="absolute bottom-0 right-0 w-32 h-32 bg-yellow-400 opacity-5 rounded-full translate-y-10 translate-x-10"></div>
            
            <div class="p-6 md:p-8 relative z-10">
              <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex items-start md:items-center space-x-4 mb-4 md:mb-0">
                  <div class="bg-gradient-to-br from-pink-500 to-purple-600 p-3 rounded-lg shadow-inner flex items-center justify-center relative overflow-hidden">
                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-yellow-300 opacity-30 rounded-full animate-ping-slow"></div>
                    <div class="absolute -bottom-1 -left-1 w-4 h-4 bg-blue-300 opacity-30 rounded-full animate-ping-slow animation-delay-500"></div>
                    <i class="fas fa-print text-white text-2xl z-10"></i>
                  </div>
                  <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white text-shadow">View & Print Master Cards</h1>
                    <p class="text-blue-100 max-w-2xl">Preview and print master card data</p>
                  </div>
                </div>
                <div class="flex flex-wrap gap-3">
                  <button @click="printTable" class="bg-gradient-to-r from-green-500 to-teal-400 text-white hover:from-green-600 hover:to-teal-500 px-4 py-2 rounded-lg flex items-center transition-all duration-300 transform hover:scale-105 shadow-md relative overflow-hidden group">
                    <span class="absolute inset-0 bg-white opacity-20 transform scale-x-0 origin-left transition-transform group-hover:scale-x-100"></span>
                    <div class="relative z-10 flex items-center">
                      <div class="bg-white bg-opacity-30 rounded-full p-1 mr-2">
                        <i class="fas fa-print text-white"></i>
                      </div>
                      <span>Print List</span>
                    </div>
                  </button>
                  <Link href="/sales-management/system-requirement/master-card/obsolate-reactive-mc" class="bg-white bg-opacity-20 text-white border border-white border-opacity-30 hover:bg-opacity-30 px-4 py-2 rounded-lg flex items-center transition-all duration-300 relative overflow-hidden">
                    <span class="absolute inset-0 bg-white opacity-10 transform scale-x-0 origin-left transition-transform hover:scale-x-100"></span>
                    <div class="relative z-10 flex items-center">
                      <div class="bg-white bg-opacity-30 rounded-full p-1.5 mr-2 transition-transform transform group-hover:rotate-12 shadow-inner">
                        <i class="fas fa-arrow-left text-white"></i>
                      </div>
                      <span>Back</span>
                    </div>
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
          <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 px-6">
            <div class="flex items-center">
              <div class="mr-3 bg-white bg-opacity-20 p-2 rounded-full shadow-inner relative overflow-hidden">
                <div class="absolute -top-1 -right-1 w-4 h-4 bg-blue-300 opacity-30 rounded-full"></div>
                <div class="absolute -bottom-1 -left-1 w-3 h-3 bg-yellow-300 opacity-30 rounded-full"></div>
                <i class="fas fa-filter text-xl relative z-10"></i>
              </div>
              <div>
                <h2 class="text-xl font-bold">Search Parameters</h2>
                <p class="text-xs text-blue-100 opacity-80">Filter master cards by various criteria</p>
              </div>
            </div>
          </div>

          <div class="p-6">
            <!-- Actions Bar -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <!-- Search Field -->
              <div class="space-y-2">
                <label class="block text-sm font-medium text-indigo-700 mb-2 flex items-center">
                  <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full mr-2 flex items-center justify-center shadow-sm">
                    <i class="fas fa-search text-white text-sm"></i>
                  </div>
                  Search:
                </label>
                <div class="relative flex-grow">
                  <input 
                    type="text" 
                    v-model="searchQuery" 
                    class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                    placeholder="Enter MC#, model, customer name..."
                  />
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                  </div>
                </div>
              </div>
              
              <!-- Status Filter -->
              <div class="space-y-2">
                <label class="block text-sm font-medium text-indigo-700 mb-2 flex items-center">
                  <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full mr-2 flex items-center justify-center shadow-sm">
                    <i class="fas fa-tag text-white text-sm"></i>
                  </div>
                  Status:
                </label>
                <div class="flex space-x-4">
                  <div class="flex items-center">
                    <input 
                      type="radio" 
                      id="status-all" 
                      v-model="statusFilter" 
                      value="all" 
                      class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
                    />
                    <label for="status-all" class="ml-2 flex items-center">
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        <i class="fas fa-list-ul mr-1"></i> All
                      </span>
                    </label>
                  </div>
                  <div class="flex items-center">
                    <input 
                      type="radio" 
                      id="status-active" 
                      v-model="statusFilter" 
                      value="active" 
                      class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
                    />
                    <label for="status-active" class="ml-2">
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-1"></i> Active
                      </span>
                    </label>
                  </div>
                  <div class="flex items-center">
                    <input 
                      type="radio" 
                      id="status-obsolete" 
                      v-model="statusFilter" 
                      value="obsolete" 
                      class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
                    />
                    <label for="status-obsolete" class="ml-2">
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        <i class="fas fa-ban mr-1"></i> Obsolete
                      </span>
                    </label>
                  </div>
                </div>
              </div>
              
              <!-- Action Buttons -->
              <div class="flex items-end">
                <button @click="searchQuery = ''; statusFilter = 'all'" class="px-6 py-2.5 border border-gray-300 rounded-lg bg-white hover:bg-gray-50 text-gray-700 transition-all duration-300 shadow-sm flex items-center mr-3">
                  <i class="fas fa-undo mr-2 text-gray-500"></i>
                  Reset Filters
                </button>
                <button @click="fetchMasterCards()" class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-lg transition-all duration-300 shadow-md flex items-center">
                  <i class="fas fa-sync mr-2"></i>
                  Refresh Data
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
          <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-4 px-6">
            <div class="flex items-center">
              <div class="mr-4 bg-white bg-opacity-20 p-2 rounded-full shadow-inner">
                <i class="fas fa-id-card text-2xl"></i>
              </div>
              <div>
                <h2 class="text-xl font-bold">Master Cards List</h2>
                <p class="text-sm opacity-80">View and manage master card data</p>
              </div>
            </div>
          </div>
          
          <div class="overflow-x-auto">
            <div id="printableTable" class="min-w-full bg-white">
              <!-- Table Content -->
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-purple-50 to-indigo-50">
                  <tr>
                    <th @click="sortTable('mc_seq')" class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider cursor-pointer hover:bg-indigo-100 transition-colors">
                      <div class="flex items-center">
                        <span>MC Seq#</span>
                        <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortTable('mc_model')" class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider cursor-pointer hover:bg-indigo-100 transition-colors">
                      <div class="flex items-center">
                        <span>MC Model</span>
                        <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortTable('customer_name')" class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider cursor-pointer hover:bg-indigo-100 transition-colors">
                      <div class="flex items-center">
                        <span>Customer</span>
                        <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortTable('description')" class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider cursor-pointer hover:bg-indigo-100 transition-colors">
                      <div class="flex items-center">
                        <span>Description</span>
                        <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortTable('status')" class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider cursor-pointer hover:bg-indigo-100 transition-colors">
                      <div class="flex items-center">
                        <span>Status</span>
                        <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortTable('updated_at')" class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider cursor-pointer hover:bg-indigo-100 transition-colors">
                      <div class="flex items-center">
                        <span>Last Updated</span>
                        <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-if="loading" class="hover:bg-purple-50">
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                      <div class="flex justify-center">
                        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-purple-500"></div>
                      </div>
                      <p class="mt-2">Loading master card data...</p>
                    </td>
                  </tr>
                  <tr v-else-if="filteredMasterCards.length === 0" class="hover:bg-purple-50">
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                      No master cards found. 
                      <template v-if="searchQuery">
                        <p class="mt-2">No results match your search query: "{{ searchQuery }}"</p>
                        <button @click="searchQuery = ''" class="mt-2 text-purple-500 hover:underline">Clear search</button>
                      </template>
                    </td>
                  </tr>
                  <tr v-for="(card, index) in filteredMasterCards" :key="card.id" 
                    :class="{'bg-purple-50': index % 2 === 0}" 
                    class="hover:bg-indigo-50 transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900">{{ card.mc_seq }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">{{ card.mc_model }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">{{ card.customer_name }}</div>
                    </td>
                    <td class="px-6 py-4">
                      <div class="text-sm text-gray-900">{{ card.description || '-' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span 
                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium"
                        :class="{
                          'bg-green-100 text-green-800': card.status === 'active',
                          'bg-red-100 text-red-800': card.status === 'obsolete',
                          'bg-gray-100 text-gray-800': card.status !== 'active' && card.status !== 'obsolete'
                        }">
                        <i :class="{
                          'fas fa-check-circle mr-1': card.status === 'active',
                          'fas fa-ban mr-1': card.status === 'obsolete',
                          'fas fa-question-circle mr-1': card.status !== 'active' && card.status !== 'obsolete'
                        }"></i>
                        {{ card.status === 'active' ? 'Active' : 'Obsolete' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900 flex items-center">
                        <i class="fas fa-clock text-indigo-400 mr-2"></i>
                        {{ formatDate(card.updated_at) }}
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Table Footer -->
          <div class="bg-gradient-to-r from-purple-50 to-indigo-50 px-6 py-3 border-t border-purple-100">
            <div class="flex flex-wrap items-center justify-between gap-3">
              <div class="text-sm text-indigo-700 flex items-center">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 mr-2">
                  <i class="fas fa-list mr-1"></i>
                  {{ filteredMasterCards.length }}
                </span>
                Master Cards Found
              </div>
              <div v-if="searchQuery || statusFilter !== 'all'" class="text-sm text-indigo-700">
                Filtered from <span class="font-bold">{{ masterCards.length }}</span> total records
              </div>
              <div class="text-xs text-indigo-400 flex items-center">
                <i class="fas fa-calendar-day mr-1"></i>
                Generated: {{ currentDate }}
              </div>
            </div>
          </div>
        </div>

        <!-- Instructions and Tips -->
        <div class="bg-gradient-to-br from-white to-purple-50 rounded-xl shadow-lg overflow-hidden mb-6 mt-6">
          <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white py-3 px-6">
            <div class="flex items-center">
              <div class="mr-3 bg-white bg-opacity-20 p-2 rounded-full shadow-inner">
                <i class="fas fa-lightbulb text-xl"></i>
              </div>
              <h2 class="text-xl font-bold">Usage Instructions</h2>
            </div>
          </div>
          <div class="p-6">
            <ul class="space-y-3">
              <li class="flex items-start">
                <div class="w-6 h-6 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                  <i class="fas fa-check text-white text-xs"></i>
                </div>
                <span class="text-gray-700">Use the search field to filter master cards by any visible column data</span>
              </li>
              <li class="flex items-start">
                <div class="w-6 h-6 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                  <i class="fas fa-check text-white text-xs"></i>
                </div>
                <span class="text-gray-700">Filter by status (Active, Obsolete, or All) using the status options</span>
              </li>
              <li class="flex items-start">
                <div class="w-6 h-6 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                  <i class="fas fa-check text-white text-xs"></i>
                </div>
                <span class="text-gray-700">Click any column header to sort the table by that column</span>
              </li>
              <li class="flex items-start">
                <div class="w-6 h-6 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                  <i class="fas fa-check text-white text-xs"></i>
                </div>
                <span class="text-gray-700">Click the "Print List" button to print the current filtered/sorted table view</span>
              </li>
              <li class="flex items-start">
                <div class="w-6 h-6 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                  <i class="fas fa-check text-white text-xs"></i>
                </div>
                <span class="text-gray-700">Use landscape orientation when printing for optimal results</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

// Data
const masterCards = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const statusFilter = ref('all'); // 'all', 'active', 'obsolete'
const sortColumn = ref('mc_seq');
const sortDirection = ref('asc');
const currentDate = new Date().toLocaleString();

// Fetch master cards from API
const fetchMasterCards = async () => {
  loading.value = true;
  try {
    const response = await fetch('/api/obsolate-reactive-mc', {
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    });
    
    if (!response.ok) {
      throw new Error('Failed to fetch master cards');
    }
    
    const data = await response.json();
    masterCards.value = data;
  } catch (error) {
    console.error('Error fetching master cards:', error);
  } finally {
    loading.value = false;
  }
};

// Format date
const formatDate = (dateString) => {
  if (!dateString) return '-';
  const date = new Date(dateString);
  return date.toLocaleString();
};

// Sort function
const sortTable = (column) => {
  if (sortColumn.value === column) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortColumn.value = column;
    sortDirection.value = 'asc';
  }
};

// Filtered and sorted master cards
const filteredMasterCards = computed(() => {
  let filtered = [...masterCards.value];
  
  // Apply status filter
  if (statusFilter.value !== 'all') {
    filtered = filtered.filter(card => card.status === statusFilter.value);
  }
  
  // Apply search filter
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    filtered = filtered.filter(card => 
      (card.mc_seq && card.mc_seq.toLowerCase().includes(query)) ||
      (card.mc_model && card.mc_model.toLowerCase().includes(query)) ||
      (card.customer_name && card.customer_name.toLowerCase().includes(query)) ||
      (card.description && card.description.toLowerCase().includes(query))
    );
  }
  
  // Apply sorting
  filtered.sort((a, b) => {
    let valueA = a[sortColumn.value];
    let valueB = b[sortColumn.value];
    
    // Handle null values
    if (valueA === null || valueA === undefined) valueA = '';
    if (valueB === null || valueB === undefined) valueB = '';
    
    // Case insensitive string comparison
    if (typeof valueA === 'string') valueA = valueA.toLowerCase();
    if (typeof valueB === 'string') valueB = valueB.toLowerCase();
    
    if (valueA < valueB) return sortDirection.value === 'asc' ? -1 : 1;
    if (valueA > valueB) return sortDirection.value === 'asc' ? 1 : -1;
    return 0;
  });
  
  return filtered;
});

// Print table function
const printTable = () => {
  const printContents = document.getElementById('printableTable').innerHTML;
  const originalContents = document.body.innerHTML;
  
  document.body.innerHTML = `
    <div style="padding: 20px;">
      <h1 style="text-align: center; margin-bottom: 20px; font-size: 24px; color: #4f46e5;">
        <i class="fas fa-print" style="margin-right: 10px;"></i>
        Master Cards List
      </h1>
      ${printContents}
      <div style="text-align: right; margin-top: 20px; font-size: 12px; color: #6d28d9;">
        Printed on: ${new Date().toLocaleString()}
      </div>
    </div>
  `;
  
  window.print();
  document.body.innerHTML = originalContents;
};

// Fetch data on component mount
onMounted(() => {
  fetchMasterCards();
});
</script>

<style scoped>
@media print {
  body * {
    visibility: hidden;
  }
  
  #printableTable, #printableTable * {
    visibility: visible;
  }
  
  #printableTable {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
  }
  
  .bg-gradient-to-r {
    background: white !important;
    color: black !important;
  }
  
  .text-white {
    color: black !important;
  }
}

/* Text shadow for headings */
.text-shadow {
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Transition effects */
.transition-all {
  transition: all 0.3s ease;
}

/* Button hover effects */
button:hover {
  transform: translateY(-1px);
}

button:active {
  transform: translateY(0);
}

/* Custom animation for slow ping effect */
@keyframes ping-slow {
  0% {
    transform: scale(1);
    opacity: 0.5;
  }
  50% {
    transform: scale(1.8);
    opacity: 0.15;
  }
  100% {
    transform: scale(1);
    opacity: 0.5;
  }
}

.animate-ping-slow {
  animation: ping-slow 3s ease-in-out infinite;
}

.animation-delay-500 {
  animation-delay: 1.5s;
}
</style>
