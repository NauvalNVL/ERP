<template>
  <AppLayout :header="'View & Print SKU Consumption Budget'">
    <Head title="View & Print SKU Consumption Budget" />
    <ToastContainer />
    
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-chart-line mr-3"></i> View & Print SKU Consumption Budget
      </h2>
      <p class="text-blue-100">View and print SKU consumption budget reports</p>
    </div>

    <!-- Toolbar Section -->
    <div class="bg-white border-b border-gray-200 px-6 py-3">
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
          <!-- Exit Button -->
          <Link :href="defineRoute" class="btn-danger">
            <i class="fas fa-power-off mr-2"></i>
            Exit
          </Link>
          
          <!-- Print Button -->
          <button @click="printReport" class="btn-secondary">
            <i class="fas fa-print mr-2"></i>
            Print
          </button>
          
          <!-- Export CSV Button -->
          <button @click="exportAsCsv" class="btn-secondary">
            <i class="fas fa-download mr-2"></i>
            Export CSV
          </button>
          
          <!-- Refresh Button -->
          <button @click="refreshData" class="btn-secondary">
            <i class="fas fa-sync-alt mr-2"></i>
            Refresh
          </button>
        </div>
        
        <div class="flex items-center space-x-4">
          <!-- Print Dropdown -->
          <div class="relative" ref="printDropdownContainer">
            <button @click="printDropdownOpen = !printDropdownOpen" class="btn-info">
              <i class="fas fa-ellipsis-v mr-2"></i>
              More Options
            </button>
            
            <div v-if="printDropdownOpen" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50">
              <div class="py-1">
                <button @click="printAsPdf" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                  <i class="fas fa-file-pdf mr-2"></i>
                  Export as PDF
                </button>
                <button @click="exportAsCsv" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                  <i class="fas fa-file-csv mr-2"></i>
                  Export as CSV
                </button>
                <button @click="printReport" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                  <i class="fas fa-print mr-2"></i>
                  Print Report
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="p-6">
      <!-- Loading Spinner -->
      <div v-if="loading" class="flex justify-center items-center py-8">
        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
        <span class="ml-3 text-gray-600">Loading...</span>
      </div>
      <div v-else>
        <!-- Search and Filters -->
        <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200 print:hidden">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="relative flex-grow">
              <input 
                type="text" 
                v-model="searchQuery" 
                placeholder="Search by SKU, name, or category..."
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
              <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
              </div>
            </div>
            <div>
              <select v-model="statusFilter" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Statuses</option>
                <option :value="true">Active</option>
                <option :value="false">Obsolete</option>
              </select>
            </div>
            <div>
              <select v-model="checkFlagFilter" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Check Flags</option>
                <option value="OVER">Over Budget</option>
                <option value="UNDER">Under Budget</option>
                <option value="OK">On Budget</option>
              </select>
            </div>
          </div>
        </div>
        
        <!-- Report header for print -->
        <div class="hidden print:block mb-6">
          <div class="text-center">
            <h1 class="text-2xl font-bold text-center print:text-3xl">SKU Consumption Budget Report</h1>
            <p class="text-center text-gray-600 print:text-lg">Generated on {{ formattedDate }}</p>
          </div>
        </div>
        
        <!-- Table section -->
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 border">
            <thead class="bg-gray-100">
              <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">SKU#</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">SKU Name</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Eff. Period</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Budget Qty</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Unit</th>
                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Check Flag</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="filteredRows.length === 0" class="hover:bg-gray-50">
                <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">
                  <div class="flex flex-col items-center">
                    <i class="fas fa-inbox text-4xl text-gray-300 mb-2"></i>
                    <p>No SKU consumption budget data found.</p>
                  </div>
                </td>
              </tr>
              <tr v-for="row in filteredRows" :key="`${row.sku}-${row.effective_month}`" class="hover:bg-gray-50">
                <td class="px-4 py-2 text-sm font-medium text-gray-900 border-r">{{ row.sku }}</td>
                <td class="px-4 py-2 text-sm text-gray-900 border-r">{{ row.sku_name }}</td>
                <td class="px-4 py-2 text-sm text-gray-900 border-r">{{ row.effective_month }}</td>
                <td class="px-4 py-2 text-sm text-gray-900 text-right border-r">{{ formatNumber(row.budget_quantity) }}</td>
                <td class="px-4 py-2 text-sm text-gray-900 border-r">{{ row.uom }}</td>
                <td class="px-4 py-2 text-sm text-center">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" :class="getCheckFlagClass(row.check_flag)">
                    {{ row.check_flag }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        
        <!-- Summary Section -->
        <div class="mt-6 bg-gray-50 p-4 rounded-lg border border-gray-200 print:bg-white">
          <h3 class="text-lg font-semibold text-gray-800 mb-3">Summary</h3>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center">
              <div class="text-2xl font-bold text-blue-600">{{ filteredRows.length }}</div>
              <div class="text-sm text-gray-600">Total Records</div>
            </div>
            <div class="text-center">
              <div class="text-2xl font-bold text-green-600">{{ overBudgetCount }}</div>
              <div class="text-sm text-gray-600">Over Budget</div>
            </div>
            <div class="text-center">
              <div class="text-2xl font-bold text-red-600">{{ underBudgetCount }}</div>
              <div class="text-sm text-gray-600">Under Budget</div>
            </div>
            <div class="text-center">
              <div class="text-2xl font-bold text-gray-600">{{ onBudgetCount }}</div>
              <div class="text-sm text-gray-600">On Budget</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Link, Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import ToastContainer from '@/Components/ToastContainer.vue';
import axios from 'axios';
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';
import 'jspdf-autotable';
import { useToast } from '@/Composables/useToast';

const rows = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const statusFilter = ref('');
const checkFlagFilter = ref('');
const printDropdownOpen = ref(false);
const printDropdownContainer = ref(null);

const toast = useToast();

// Computed properties
const filteredRows = computed(() => {
  let filtered = rows.value;
  
  // Search filter
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    filtered = filtered.filter(row => 
      row.sku.toLowerCase().includes(query) ||
      row.sku_name.toLowerCase().includes(query) ||
      row.category_code.toLowerCase().includes(query)
    );
  }
  
  // Status filter
  if (statusFilter.value !== '') {
    filtered = filtered.filter(row => row.is_active === statusFilter.value);
  }
  
  // Check flag filter
  if (checkFlagFilter.value) {
    filtered = filtered.filter(row => row.check_flag === checkFlagFilter.value);
  }
  
  return filtered;
});

const overBudgetCount = computed(() => {
  return filteredRows.value.filter(row => row.check_flag === 'OVER').length;
});

const underBudgetCount = computed(() => {
  return filteredRows.value.filter(row => row.check_flag === 'UNDER').length;
});

const onBudgetCount = computed(() => {
  return filteredRows.value.filter(row => row.check_flag === 'OK').length;
});

const formattedDate = computed(() => {
  const now = new Date();
  return new Intl.DateTimeFormat('en-US', {
    year: 'numeric', month: 'long', day: 'numeric',
    hour: '2-digit', minute: '2-digit'
  }).format(now);
});

const defineRoute = computed(() => {
  return '/material-management/system-requirement/inventory-setup/sku-consumption-budget';
});

const loadData = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/material-management/system-requirement/inventory-setup/sku-consumption-budgets/view-print');
    rows.value = response.data;
  } catch (error) {
    console.error('Error fetching SKU consumption budget data:', error);
    toast.error('Failed to load SKU consumption budget data. Please try again.');
  } finally {
    loading.value = false;
  }
};

const refreshData = () => {
  loadData();
};

const exportAsCsv = () => {
  let csvContent = 'data:text/csv;charset=utf-8,';
  csvContent += 'SKU#,SKU Name,Eff. Period,Budget Qty,Unit,Check Flag\n';
  filteredRows.value.forEach(row => {
    csvContent += `"${row.sku}","${row.sku_name}","${row.effective_month}","${row.budget_quantity}","${row.uom}","${row.check_flag}"\n`;
  });
  const encodedUri = encodeURI(csvContent);
  const link = document.createElement('a');
  link.setAttribute('href', encodedUri);
  link.setAttribute('download', `sku_consumption_budget_${new Date().toISOString().slice(0, 10)}.csv`);
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
  printDropdownOpen.value = false;
};

const printAsPdf = () => {
  const doc = new jsPDF();
  doc.setFontSize(18);
  doc.setFont('helvetica', 'bold');
  doc.text('SKU Consumption Budget Report', 15, 22);
  doc.setFontSize(11);
  doc.setFont('helvetica', 'normal');
  doc.text(`Generated on: ${formattedDate.value}`, 15, 28);
  
  const tableData = filteredRows.value.map(item => [
    item.sku,
    item.sku_name,
    item.effective_month,
    formatNumber(item.budget_quantity),
    item.uom,
    item.check_flag,
  ]);
  
  autoTable(doc, {
    head: [['SKU#', 'SKU Name', 'Eff. Period', 'Budget Qty', 'Unit', 'Check Flag']],
    body: tableData,
    startY: 35,
    theme: 'grid',
    styles: { fontSize: 10, cellPadding: 2 },
    headStyles: { fillColor: [41, 128, 185], textColor: 255, fontStyle: 'bold' },
    columnStyles: {
      0: { cellWidth: 'auto' },
      1: { cellWidth: '*' },
      2: { cellWidth: 'auto' },
      3: { cellWidth: 'auto', halign: 'right' },
      4: { cellWidth: 'auto' },
      5: { cellWidth: 'auto', halign: 'center' },
    }
  });
  
  doc.output('dataurlnewwindow');
  printDropdownOpen.value = false;
};

const printReport = () => {
  window.print();
  printDropdownOpen.value = false;
};

const handleClickOutside = (event) => {
  if (printDropdownContainer.value && !printDropdownContainer.value.contains(event.target)) {
    printDropdownOpen.value = false;
  }
};

const formatNumber = (num) => {
  return new Intl.NumberFormat('en-US', { minimumFractionDigits: 2 }).format(num);
};

const getCheckFlagClass = (checkFlag) => {
  switch (checkFlag) {
    case 'OVER':
      return 'bg-red-100 text-red-800';
    case 'UNDER':
      return 'bg-yellow-100 text-yellow-800';
    case 'OK':
      return 'bg-green-100 text-green-800';
    default:
      return 'bg-gray-100 text-gray-800';
  }
};

onMounted(() => {
  loadData();
  document.addEventListener('mousedown', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('mousedown', handleClickOutside);
});
</script>

<style>
@media print {
  body * {
    visibility: hidden;
  }
  .print-container, .print-container * {
    visibility: visible;
  }
  .print-container {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
  }
  .print\:hidden {
    display: none;
  }
  .print\:block {
    display: block;
  }
  .print\:bg-white {
    background-color: white !important;
  }
}

.btn-primary {
  @apply bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition flex items-center justify-center whitespace-nowrap;
}
.btn-secondary {
  @apply bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg shadow transition flex items-center justify-center whitespace-nowrap;
}
.btn-info {
  @apply bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-lg shadow transition flex items-center justify-center whitespace-nowrap;
}
.btn-danger {
  @apply bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow transition flex items-center justify-center whitespace-nowrap;
}

tbody tr { transition: all 0.2s; }
tbody tr:hover { transform: translateY(-2px); box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
.overflow-x-auto, .overflow-y-auto { scrollbar-width: thin; scrollbar-color: rgba(156,163,175,0.5) transparent; }
.overflow-x-auto::-webkit-scrollbar, .overflow-y-auto::-webkit-scrollbar { height: 8px; width: 8px; }
.overflow-x-auto::-webkit-scrollbar-track, .overflow-y-auto::-webkit-scrollbar-track { background: transparent; }
.overflow-x-auto::-webkit-scrollbar-thumb, .overflow-y-auto::-webkit-scrollbar-thumb { background-color: rgba(156,163,175,0.5); border-radius: 20px; }
</style>
