<template>
  <AppLayout :header="'View & Print Receive Destinations'">
    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Main Content Card -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="p-6">
            <!-- Action buttons -->
            <div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-3 sm:space-y-0">
              <h2 class="text-xl font-semibold text-gray-800">Receive Destinations List</h2>
              
              <div class="flex flex-wrap gap-2">
                <div class="relative">
                  <input 
                    v-model="searchQuery"
                    type="text" 
                    placeholder="Search destinations..."
                    class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  />
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <SearchIcon class="w-5 h-5 text-gray-400" />
                  </div>
                </div>
                
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
              </div>
            </div>
            
            <!-- Destinations Table -->
            <div id="print-section" class="border border-gray-200 rounded-lg overflow-hidden">
              <div class="overflow-x-auto">
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
                        Address
                      </th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Contact
                      </th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Telephone
                      </th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Fax
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="loading">
                      <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="flex justify-center">
                          <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                          </svg>
                        </div>
                      </td>
                    </tr>
                    <tr v-else-if="filteredDestinations.length === 0">
                      <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                        No receive destinations found
                      </td>
                    </tr>
                    <tr v-for="destination in filteredDestinations" 
                        :key="destination.code" 
                        class="hover:bg-gray-50 transition-colors">
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-gray-900">{{ destination.code }}</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                        {{ destination.name }}
                      </td>
                      <td class="px-6 py-4 text-gray-700 max-w-[200px]">
                        {{ destination.address || '—' }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                        {{ destination.contact || '—' }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                        {{ destination.tel || '—' }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                        {{ destination.fax || '—' }}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              
              <!-- Table Footer with Pagination -->
              <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6 print:hidden">
                <div class="flex items-center justify-between">
                  <div class="text-sm text-gray-700">
                    Showing {{ filteredDestinations.length > 0 ? 1 : 0 }} 
                    to {{ filteredDestinations.length }} 
                    of {{ filteredDestinations.length }} results
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Success/Error Toast Notification -->
    <div 
      v-if="notification.show" 
      class="fixed bottom-4 right-4 p-4 rounded-lg shadow-lg w-80 z-50 transition-all transform duration-500 ease-in-out print:hidden"
      :class="{
        'bg-green-50 border border-green-200': notification.type === 'success',
        'bg-red-50 border border-red-200': notification.type === 'error',
        'translate-x-0': notification.show,
        'translate-x-full': !notification.show
      }">
      <div class="flex items-center">
        <div class="flex-shrink-0">
          <CheckCircleIcon v-if="notification.type === 'success'" class="h-6 w-6 text-green-500" />
          <ExclamationCircleIcon v-else class="h-6 w-6 text-red-500" />
        </div>
        <div class="ml-3">
          <p class="text-sm font-medium" 
            :class="{
              'text-green-800': notification.type === 'success',
              'text-red-800': notification.type === 'error'
            }">
            {{ notification.message }}
          </p>
        </div>
        <div class="ml-auto pl-3">
          <div class="-mx-1.5 -my-1.5">
            <button
              @click="notification.show = false"
              class="inline-flex rounded-md p-1.5 focus:outline-none focus:ring-2 focus:ring-offset-2"
              :class="{
                'bg-green-100 text-green-500 hover:bg-green-200 focus:ring-green-600': notification.type === 'success',
                'bg-red-100 text-red-500 hover:bg-red-200 focus:ring-red-600': notification.type === 'error'
              }">
              <XIcon class="h-4 w-4" />
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { 
  RefreshIcon, 
  DocumentDownloadIcon, 
  SearchIcon,
  PrinterIcon,
  XIcon,
  CheckCircleIcon,
  ExclamationCircleIcon
} from '@heroicons/vue/solid';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

// Reactive data
const destinations = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const notification = ref({
  show: false,
  type: 'success',
  message: ''
});

// Computed properties
const filteredDestinations = computed(() => {
  if (!searchQuery.value) {
    return destinations.value;
  }
  
  const query = searchQuery.value.toLowerCase();
  return destinations.value.filter(dest => 
    dest.code.toLowerCase().includes(query) || 
    dest.name.toLowerCase().includes(query) ||
    (dest.address && dest.address.toLowerCase().includes(query)) ||
    (dest.contact && dest.contact.toLowerCase().includes(query))
  );
});

// Methods
const fetchDestinations = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/material-management/receive-destinations');
    destinations.value = response.data;
  } catch (error) {
    showNotification('Error loading receive destinations', 'error');
    console.error('Failed to load receive destinations:', error);
  } finally {
    loading.value = false;
  }
};

const refreshData = () => {
  fetchDestinations();
  searchQuery.value = '';
};

const exportData = () => {
  const csvRows = [];
  // Add CSV header
  csvRows.push(['Code', 'Name', 'Address', 'Contact', 'Telephone', 'Fax'].join(','));
  
  // Add data rows
  filteredDestinations.value.forEach(dest => {
    csvRows.push([
      // Wrap fields in quotes to handle commas in text
      `"${dest.code}"`,
      `"${dest.name}"`,
      `"${dest.address || ''}"`,
      `"${dest.contact || ''}"`,
      `"${dest.tel || ''}"`,
      `"${dest.fax || ''}"`,
    ].join(','));
  });
  
  const csvContent = csvRows.join('\n');
  const blob = new Blob([csvContent], { type: 'text/csv' });
  const url = window.URL.createObjectURL(blob);
  
  const a = document.createElement('a');
  a.setAttribute('hidden', '');
  a.setAttribute('href', url);
  a.setAttribute('download', 'receive-destinations.csv');
  document.body.appendChild(a);
  a.click();
  document.body.removeChild(a);
};

const printTable = () => {
  window.print();
};

const showNotification = (message, type = 'success') => {
  notification.value = {
    show: true,
    type,
    message
  };
  
  // Auto-hide notification after 3 seconds
  setTimeout(() => {
    notification.value.show = false;
  }, 3000);
};

// Initialize data
onMounted(() => {
  fetchDestinations();
});
</script>

<style scoped>
@media print {
  body * {
    visibility: hidden;
  }
  
  #print-section,
  #print-section * {
    visibility: visible;
  }
  
  #print-section {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
  }
  
  .print\:hidden {
    display: none;
  }
}
</style> 