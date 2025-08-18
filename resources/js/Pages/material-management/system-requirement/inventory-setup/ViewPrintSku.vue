<template>
  <AppLayout title="View & Print SKU">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        View & Print SKU
      </h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
          <!-- Header with buttons -->
          <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 flex items-center justify-between">
            <h2 class="text-lg font-bold text-white">View & Print SKU</h2>
            <div class="flex space-x-2">
              <div class="relative" ref="printDropdownContainer">
                <button @click="printDropdownOpen = !printDropdownOpen" class="bg-blue-500 hover:bg-blue-400 text-white px-3 py-1 rounded text-sm flex items-center">
                  <i class="fas fa-print mr-2"></i>
                  <span>Print/Export</span>
                  <i class="fas fa-chevron-down ml-2 transition-transform" :class="{'rotate-180': printDropdownOpen}"></i>
                </button>
                <transition
                  enter-active-class="transition ease-out duration-200"
                  enter-from-class="transform opacity-0 scale-95"
                  enter-to-class="transform opacity-100 scale-100"
                  leave-active-class="transition ease-in duration-75"
                  leave-from-class="transform opacity-100 scale-100"
                  leave-to-class="transform opacity-0 scale-95"
                >
                  <div v-if="printDropdownOpen" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-20 border border-gray-200">
                    <a @click.prevent="printAsPdf" href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                      <i class="fas fa-file-pdf mr-2 text-red-500"></i> Export as PDF
                    </a>
                    <a @click.prevent="exportAsCsv" href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                      <i class="fas fa-file-csv mr-2 text-green-500"></i> Export as CSV
                    </a>
                  </div>
                </transition>
              </div>
              <Link :href="defineRoute" class="bg-gray-600 hover:bg-gray-500 text-white px-3 py-1 rounded text-sm flex items-center">
                <i class="fas fa-arrow-left mr-1"></i>
                Back
              </Link>
            </div>
          </div>

          <!-- Main content -->
          <div class="p-6">
            <!-- Loading Spinner -->
            <div v-if="loading" class="flex justify-center items-center py-8">
              <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
              <span class="ml-3 text-gray-600">Loading...</span>
            </div>
            
            <div v-else>
              <!-- Search and Filters -->
              <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200 print:hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                  <!-- SKU# Range -->
                  <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700 w-16">SKU#:</label>
                    <input 
                      v-model="filters.skuFrom" 
                      type="text" 
                      placeholder="From"
                      class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                    />
                    <span class="text-gray-500">to</span>
                    <input 
                      v-model="filters.skuTo" 
                      type="text" 
                      placeholder="To"
                      class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                    />
                  </div>

                  <!-- Category -->
                  <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700 w-20">Category:</label>
                    <select 
                      v-model="filters.category" 
                      class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                    >
                      <option value="">All Categories</option>
                      <option v-for="category in categories" :key="category.code" :value="category.code">
                        {{ category.code }} - {{ category.name }}
                      </option>
                    </select>
                  </div>

                  <!-- SKU Type -->
                  <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700 w-20">SKU Type:</label>
                    <div class="flex space-x-4">
                      <label class="flex items-center">
                        <input 
                          v-model="filters.skuTypes" 
                          type="checkbox" 
                          value="SI"
                          class="mr-2"
                        />
                        <span class="text-sm">1-Stock Item</span>
                      </label>
                      <label class="flex items-center">
                        <input 
                          v-model="filters.skuTypes" 
                          type="checkbox" 
                          value="NS"
                          class="mr-2"
                        />
                        <span class="text-sm">2-Non Stock Item</span>
                      </label>
                    </div>
                  </div>

                  <!-- SKU Status -->
                  <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700 w-20">SKU Status:</label>
                    <div class="flex space-x-4">
                      <label class="flex items-center">
                        <input 
                          v-model="filters.skuStatus" 
                          type="radio" 
                          value="A"
                          class="mr-2"
                        />
                        <span class="text-sm">A-Active</span>
                      </label>
                      <label class="flex items-center">
                        <input 
                          v-model="filters.skuStatus" 
                          type="radio" 
                          value="O"
                          class="mr-2"
                        />
                        <span class="text-sm">0-Obsolete</span>
                      </label>
                    </div>
                  </div>

                  <!-- Sort By -->
                  <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700 w-16">Sort by:</label>
                    <div class="flex space-x-4">
                      <label class="flex items-center">
                        <input 
                          v-model="filters.sortBy" 
                          type="radio" 
                          value="sku"
                          class="mr-2"
                        />
                        <span class="text-sm">SKU#</span>
                      </label>
                      <label class="flex items-center">
                        <input 
                          v-model="filters.sortBy" 
                          type="radio" 
                          value="cat_sku"
                          class="mr-2"
                        />
                        <span class="text-sm">CAT + SKU#</span>
                      </label>
                      <label class="flex items-center">
                        <input 
                          v-model="filters.sortBy" 
                          type="radio" 
                          value="sku_part"
                          class="mr-2"
                        />
                        <span class="text-sm">SKU PART#</span>
                      </label>
                      <label class="flex items-center">
                        <input 
                          v-model="filters.sortBy" 
                          type="radio" 
                          value="sku_name"
                          class="mr-2"
                        />
                        <span class="text-sm">SKU NAME</span>
                      </label>
                    </div>
                  </div>

                  <!-- Proceed Button -->
                  <div class="flex items-end">
                    <button 
                      @click="proceed"
                      class="bg-gray-600 hover:bg-gray-500 text-white px-4 py-2 rounded text-sm"
                    >
                      Proceed
                    </button>
                  </div>
                </div>
              </div>
              
              <!-- Report header for print -->
              <div class="hidden print:block mb-6">
                <div class="text-center">
                  <h1 class="text-2xl font-bold text-center print:text-3xl">SKU Table, By SKU#</h1>
                  <p class="text-center text-gray-600 print:text-lg">Generated on {{ formattedDate }}</p>
                </div>
              </div>
              
              <!-- Table section -->
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border">
                  <thead class="bg-gray-100">
                    <tr>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">SKU#</th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">STS</th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">SKU Name</th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">CAT</th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Type</th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">UOM</th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">BOH</th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">FPO</th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">ROL</th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Part#</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="filteredRows.length === 0" class="hover:bg-gray-50">
                      <td colspan="10" class="px-4 py-4 text-center text-sm text-gray-500">No data found.</td>
                    </tr>
                    <tr v-for="row in filteredRows" :key="row.id" class="hover:bg-gray-50">
                      <td class="px-4 py-2 text-sm font-medium text-gray-900 border-r">{{ row.sku }}</td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" :class="row.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                          {{ row.is_active ? 'ACT' : 'OBS' }}
                        </span>
                      </td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">{{ row.sku_name }}</td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">{{ row.category_code }}</td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">{{ row.type }}</td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">{{ row.uom }}</td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">{{ formatNumber(row.boh) }}</td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">{{ formatNumber(row.fpo) }}</td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">{{ formatNumber(row.rol) }}</td>
                      <td class="px-4 py-2 text-sm text-gray-900">{{ row.total_part || '0' }}</td>
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
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';
import 'jspdf-autotable';
import { useToast } from '@/Composables/useToast';

const rows = ref([]);
const categories = ref([]);
const loading = ref(true);
const printDropdownOpen = ref(false);
const printDropdownContainer = ref(null);
const toast = useToast();

const defineRoute = '/material-management/system-requirement/inventory-setup/sku';

// Filters matching desktop version
const filters = ref({
  skuFrom: '',
  skuTo: '',
  category: '',
  skuTypes: ['SI', 'NS'], // Default both checked
  skuStatus: 'A', // Default Active
  sortBy: 'sku' // Default sort by SKU#
});

const filteredRows = computed(() => {
  let filtered = [...rows.value];
  
  // Apply SKU range filter
  if (filters.value.skuFrom || filters.value.skuTo) {
    filtered = filtered.filter(row => {
      const sku = row.sku || '';
      const from = filters.value.skuFrom || '';
      const to = filters.value.skuTo || '';
      
      if (from && to) {
        return sku >= from && sku <= to;
      } else if (from) {
        return sku >= from;
      } else if (to) {
        return sku <= to;
      }
      return true;
    });
  }
  
  // Apply category filter
  if (filters.value.category) {
    filtered = filtered.filter(row => row.category_code === filters.value.category);
  }
  
  // Apply SKU type filter
  if (filters.value.skuTypes.length > 0) {
    filtered = filtered.filter(row => filters.value.skuTypes.includes(row.type));
  }
  
  // Apply SKU status filter
  if (filters.value.skuStatus) {
    const isActive = filters.value.skuStatus === 'A';
    filtered = filtered.filter(row => row.is_active === isActive);
  }
  
  // Apply sorting
  filtered.sort((a, b) => {
    let aValue, bValue;
    
    switch (filters.value.sortBy) {
      case 'sku':
        aValue = a.sku || '';
        bValue = b.sku || '';
        break;
      case 'cat_sku':
        aValue = (a.category_code || '') + (a.sku || '');
        bValue = (b.category_code || '') + (b.sku || '');
        break;
      case 'sku_part':
        aValue = a.sku_part || '';
        bValue = b.sku_part || '';
        break;
      case 'sku_name':
        aValue = a.sku_name || '';
        bValue = b.sku_name || '';
        break;
      default:
        aValue = a.sku || '';
        bValue = b.sku || '';
    }
    
    return aValue.localeCompare(bValue);
  });
  
  return filtered;
});

const formattedDate = computed(() => {
  const now = new Date();
  return new Intl.DateTimeFormat('en-US', {
    year: 'numeric', month: 'long', day: 'numeric',
    hour: '2-digit', minute: '2-digit'
  }).format(now);
});

const loadData = async () => {
  loading.value = true;
  try {
    const [skuResponse, categoryResponse] = await Promise.all([
      axios.get('/api/material-management/skus/for-print'),
      axios.get('/api/material-management/categories')
    ]);
    rows.value = skuResponse.data;
    categories.value = categoryResponse.data;
  } catch (error) {
    console.error('Error fetching data:', error);
    toast.error('Failed to load data. Please try again.');
  } finally {
    loading.value = false;
  }
};

const formatNumber = (value) => {
  if (value === null || value === undefined) return '0.000';
  return parseFloat(value).toFixed(3);
};

const proceed = () => {
  // This function simulates the "Proceed" button from desktop version
  // In the desktop version, this would apply the filters and show results
  toast.success('Filters applied successfully');
};

const exportAsCsv = () => {
  let csvContent = 'data:text/csv;charset=utf-8,';
  csvContent += 'SKU#,STS,SKU Name,CAT,Type,UOM,BOH,FPO,ROL,Total Part#\n';
  
  filteredRows.value.forEach(row => {
    csvContent += `"${row.sku}","${row.is_active ? 'ACT' : 'OBS'}","${row.sku_name}","${row.category_code}","${row.type}","${row.uom}","${formatNumber(row.boh)}","${formatNumber(row.fpo)}","${formatNumber(row.rol)}","${row.total_part || '0'}"\n`;
  });

  const encodedUri = encodeURI(csvContent);
  const link = document.createElement('a');
  link.setAttribute('href', encodedUri);
  link.setAttribute('download', `sku_report_${new Date().toISOString().slice(0, 10)}.csv`);
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
  printDropdownOpen.value = false;
};

const printAsPdf = () => {
  try {
    // Create new PDF document
    const doc = new jsPDF('l', 'mm', 'a4'); // Landscape orientation
    
    // Set document properties
    doc.setProperties({
      title: 'SKU Table Report',
      subject: 'SKU Listing',
      author: 'PT. Multibox Indah - CPS ENTERPRISE 2020',
      creator: 'ERP System'
    });

    // Add company header
    doc.setFontSize(20);
    doc.setFont('helvetica', 'bold');
    doc.text('PT. Multibox Indah - CPS ENTERPRISE 2020', 148, 20, { align: 'center' });
    
    doc.setFontSize(14);
    doc.setFont('helvetica', 'normal');
    doc.text('SKU Table, By SKU#', 148, 30, { align: 'center' });
    
    // Add date and time
    const currentDate = new Date().toLocaleDateString('id-ID');
    const currentTime = new Date().toLocaleTimeString('id-ID');
    doc.setFontSize(10);
    doc.text(`Generated on ${currentDate} at ${currentTime}`, 148, 40, { align: 'center' });
    
    // Add summary information
    doc.setFontSize(11);
    doc.setFont('helvetica', 'bold');
    doc.text('Summary:', 20, 55);
    doc.setFont('helvetica', 'normal');
    doc.text(`Total SKUs: ${rows.value.length}`, 20, 62);
    doc.text(`Showing: ${filteredRows.value.length} of ${rows.value.length}`, 20, 69);
    
    // Prepare table data
    const tableData = filteredRows.value.map((row, index) => [
      (index + 1).toString(),
      row.sku || '',
      row.is_active ? 'ACT' : 'OBS',
      row.sku_name || '',
      row.category_code || '',
      row.type || '',
      row.uom || '',
      formatNumber(row.boh),
      formatNumber(row.fpo),
      formatNumber(row.rol),
      row.total_part || '0'
    ]);
    
    // Add table using autoTable
    autoTable(doc, {
      startY: 80,
      head: [['No.', 'SKU#', 'STS', 'SKU Name', 'CAT', 'Type', 'UOM', 'BOH', 'FPO', 'ROL', 'Total Part#']],
      body: tableData,
      theme: 'grid',
      styles: {
        fontSize: 7,
        cellPadding: 2,
        lineColor: [0, 0, 0],
        lineWidth: 0.1,
        textColor: [0, 0, 0],
      },
      headStyles: {
        fillColor: [79, 70, 229], // Indigo color
        textColor: [255, 255, 255],
        fontStyle: 'bold',
        fontSize: 8,
        halign: 'center',
      },
      columnStyles: {
        0: { cellWidth: 12, halign: 'center', fontStyle: 'bold' }, // No.
        1: { cellWidth: 25, halign: 'center' }, // SKU#
        2: { cellWidth: 15, halign: 'center' }, // STS
        3: { cellWidth: 45, halign: 'left' }, // SKU Name
        4: { cellWidth: 15, halign: 'center' }, // CAT
        5: { cellWidth: 15, halign: 'center' }, // Type
        6: { cellWidth: 15, halign: 'center' }, // UOM
        7: { cellWidth: 20, halign: 'right' }, // BOH
        8: { cellWidth: 20, halign: 'right' }, // FPO
        9: { cellWidth: 20, halign: 'right' }, // ROL
        10: { cellWidth: 20, halign: 'center' }, // Total Part#
      },
      alternateRowStyles: {
        fillColor: [248, 250, 252], // Light gray
      },
      didDrawPage: function (data) {
        // Add page number
        const pageCount = doc.internal.getNumberOfPages();
        doc.setFontSize(8);
        doc.text(`Page ${data.pageNumber} of ${pageCount}`, 148, doc.internal.pageSize.height - 10, { align: 'center' });
      },
      margin: { top: 80, right: 15, bottom: 20, left: 15 },
      tableWidth: 'auto',
      showFoot: 'lastPage',
      footStyles: {
        fillColor: [240, 240, 240],
        textColor: [0, 0, 0],
        fontStyle: 'bold',
        fontSize: 7,
      },
      foot: [[
        { content: `Total: ${filteredRows.value.length} records`, colSpan: 11, styles: { halign: 'center' } }
      ]],
    });
    
    // Generate filename with timestamp
    const timestamp = new Date().toISOString().slice(0, 19).replace(/:/g, '-');
    const filename = `sku_table_${timestamp}.pdf`;
    
    // Save the PDF
    doc.save(filename);
    
    toast.success('PDF generated successfully');
    printDropdownOpen.value = false;
  } catch (err) {
    console.error('Error generating PDF:', err);
    toast.error('Failed to generate PDF');
  }
};

const handleClickOutside = (event) => {
  if (printDropdownContainer.value && !printDropdownContainer.value.contains(event.target)) {
    printDropdownOpen.value = false;
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
}
</style> 