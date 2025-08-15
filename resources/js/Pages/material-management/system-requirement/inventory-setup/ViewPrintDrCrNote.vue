<template>
  <AppLayout :header="'View & Print DR or CR Note'">
    <Head title="View & Print DR or CR Note" />
    <ToastContainer />
    
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-print mr-3"></i> View & Print DR or CR Note
        </h2>
      <p class="text-blue-100">View and print DR/CR Note type configurations for financial management</p>
          </div>
          
    <!-- Toolbar Section -->
    <div class="bg-white border-b border-gray-200 px-6 py-3">
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
          <!-- Search Box -->
          <div class="relative">
            <input 
              type="text" 
              v-model="searchQuery" 
              placeholder="Search by code, name, or type..."
              class="w-64 px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              @input="handleSearch"
            >
            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
          </div>
          
          <!-- Filter Dropdown -->
          <select 
            v-model="selectedType" 
            @change="handleFilter"
            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          >
            <option value="">All Types</option>
            <option value="CN">CN - Credit Note</option>
            <option value="DN">DN - Debit Note</option>
          </select>
        </div>
        
        <div class="flex items-center space-x-2">
          <button @click="refreshData" class="btn-secondary">
            <i class="fas fa-refresh mr-2"></i> Refresh
            </button>
          <button @click="printReport" class="btn-primary">
            <i class="fas fa-print mr-2"></i> Print
            </button>
          <button @click="exitPage" class="btn-danger">
            <i class="fas fa-times mr-2"></i> Exit
            </button>
          </div>
        </div>
      </div>

      <!-- Table Section -->
    <div class="bg-white shadow-lg">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Code
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Name
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Type
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Group
              </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="loading" class="animate-pulse">
              <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                  <div class="flex justify-center items-center space-x-2">
                    <i class="fas fa-spinner fa-spin"></i>
                  <span>Loading note types...</span>
                  </div>
                </td>
              </tr>
            <tr v-else-if="filteredNoteTypes.length === 0" class="hover:bg-gray-50">
              <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                No note types found. Try adjusting your search or filters.
                </td>
              </tr>
            <tr 
              v-for="noteType in filteredNoteTypes" 
              :key="noteType.code" 
              class="hover:bg-gray-50 cursor-pointer"
              :class="{ 'bg-blue-50': selectedNoteType === noteType }"
              @click="selectNoteType(noteType)"
            >
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ noteType.code }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                {{ noteType.name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                <span :class="getTypeBadgeClass(noteType.type)" class="px-2 py-1 text-xs font-medium rounded-full">
                  {{ noteType.type }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                {{ noteType.group }}
                </td>
              </tr>
            </tbody>
          </table>
      </div>
    </div>

    <!-- Status Bar -->
    <div class="bg-gray-100 px-6 py-2 border-t border-gray-200">
      <div class="flex items-center justify-between text-sm text-gray-600">
        <div class="flex items-center space-x-4">
          <span>Total Records: {{ filteredNoteTypes.length }}</span>
          <span v-if="selectedNoteType">Selected: {{ selectedNoteType.code }}</span>
              </div>
        <div class="flex items-center space-x-2">
          <span>Press F1 for Help</span>
          <span>|</span>
          <span>Press F2 to Print</span>
          <span>|</span>
          <span>Press ESC to Exit</span>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted, onUnmounted } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import ToastContainer from '@/Components/ToastContainer.vue';
import { useToast } from '@/Composables/useToast';

const { showToast } = useToast();

// Reactive data
const loading = ref(false);
const searchQuery = ref('');
const selectedType = ref('');
const selectedNoteType = ref(null);

// Note types data - matching the desktop ERP application
const noteTypes = ref([
  {
    code: 'CN10',
    name: 'CN BY SKU + QTY + UNIT PRICE',
    type: 'CN',
    group: 10
  },
  {
    code: 'CN20',
    name: 'CN BY SKU + AMOUNT +PERCENTAGE',
    type: 'CN',
    group: 20
  },
  {
    code: 'CN30',
    name: 'CN BY SKU +AMOUNT',
    type: 'CN',
    group: 30
  },
  {
    code: 'CN40',
    name: 'CN BY OPEN LINE',
    type: 'CN',
    group: 40
  },
  {
    code: 'DN10',
    name: 'DN BY SKU+QTY+UNIT PRICE',
    type: 'DN',
    group: 10
  },
  {
    code: 'DN20',
    name: 'DN BY SKU+AMOUNT+PERCENTAGE',
    type: 'DN',
    group: 20
  },
  {
    code: 'DN30',
    name: 'DN BY SKU + AMOUNT',
    type: 'DN',
    group: 30
  },
  {
    code: 'DN40',
    name: 'DN BY OPEN LINE',
    type: 'DN',
    group: 40
  }
]);

// Computed properties
const filteredNoteTypes = computed(() => {
  let filtered = noteTypes.value;
  
  // Filter by search query
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    filtered = filtered.filter(noteType => 
      noteType.code.toLowerCase().includes(query) ||
      noteType.name.toLowerCase().includes(query) ||
      noteType.type.toLowerCase().includes(query)
    );
  }
  
  // Filter by type
  if (selectedType.value) {
    filtered = filtered.filter(noteType => noteType.type === selectedType.value);
  }
  
  return filtered;
});

// Methods
const handleSearch = () => {
  // Search is handled by computed property
};

const handleFilter = () => {
  // Filter is handled by computed property
};

const selectNoteType = (noteType) => {
  selectedNoteType.value = noteType;
};

const refreshData = () => {
  loading.value = true;
  // Simulate loading
  setTimeout(() => {
    loading.value = false;
    showToast('Data refreshed successfully', 'success');
  }, 500);
};

const printReport = () => {
  const printWindow = window.open('', '_blank');
  const printContent = `
    <html>
      <head>
        <title>DR/CR Note Types Report</title>
        <style>
          body { font-family: Arial, sans-serif; margin: 20px; }
          table { width: 100%; border-collapse: collapse; margin-top: 20px; }
          th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
          th { background-color: #f2f2f2; font-weight: bold; }
          .header { text-align: center; margin-bottom: 20px; }
          .footer { margin-top: 20px; text-align: center; font-size: 12px; }
        </style>
      </head>
      <body>
        <div class="header">
          <h1>DR/CR Note Types Report</h1>
          <p>Generated on: ${new Date().toLocaleString()}</p>
        </div>
        <table>
          <thead>
            <tr>
              <th>Code</th>
              <th>Name</th>
              <th>Type</th>
              <th>Group</th>
            </tr>
          </thead>
          <tbody>
            ${filteredNoteTypes.value.map(noteType => `
              <tr>
                <td>${noteType.code}</td>
                <td>${noteType.name}</td>
                <td>${noteType.type}</td>
                <td>${noteType.group}</td>
              </tr>
            `).join('')}
          </tbody>
        </table>
        <div class="footer">
          <p>Total Records: ${filteredNoteTypes.value.length}</p>
        </div>
      </body>
    </html>
  `;
  
  printWindow.document.write(printContent);
  printWindow.document.close();
  printWindow.print();
};

const exitPage = () => {
  router.visit('/material-management/system-requirement/inventory-setup');
};

const getTypeBadgeClass = (type) => {
  return type === 'CN' 
    ? 'bg-green-100 text-green-800' 
    : 'bg-red-100 text-red-800';
};

// Keyboard shortcuts
const handleKeydown = (event) => {
  switch (event.key) {
    case 'F1':
      event.preventDefault();
      showToast('Help: Use search to find note types, click to select, and use Print to generate reports', 'info');
      break;
    case 'F2':
      event.preventDefault();
      printReport();
      break;
    case 'Escape':
      event.preventDefault();
      exitPage();
      break;
  }
};

// Lifecycle
onMounted(() => {
  document.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
  document.removeEventListener('keydown', handleKeydown);
});
</script>

<style scoped>
.btn-primary {
  @apply bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200;
}

.btn-secondary {
  @apply bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200;
}

.btn-danger {
  @apply bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200;
}
</style> 