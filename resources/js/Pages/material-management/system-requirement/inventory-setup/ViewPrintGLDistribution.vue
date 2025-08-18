<template>
  <AppLayout title="View & Print GL Distribution">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        View & Print GL Distribution
      </h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
          <!-- Header with buttons -->
          <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 flex items-center justify-between">
            <h2 class="text-lg font-bold text-white">View & Print GL Distribution</h2>
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div class="relative flex-grow">
          <input
            type="text"
                      v-model="searchQuery" 
                      placeholder="Search by GL DIST#, name, or account..."
                      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                      <i class="fas fa-search text-gray-400"></i>
                    </div>
        </div>
        </div>
      </div>

              <!-- Report header for print -->
              <div class="hidden print:block mb-6">
                <div class="text-center">
                  <h1 class="text-2xl font-bold text-center print:text-3xl">GL Distribution Report</h1>
                  <p class="text-center text-gray-600 print:text-lg">Generated on {{ formattedDate }}</p>
              </div>
        </div>

              <!-- Table section -->
        <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border">
                  <thead class="bg-gray-100">
                    <tr>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">GL DIST#</th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">GL DIST Name</th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">GL Acct#</th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">GL Acct Name</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="filteredRows.length === 0" class="hover:bg-gray-50">
                      <td colspan="4" class="px-4 py-4 text-center text-sm text-gray-500">No data found.</td>
              </tr>
                    <tr v-for="row in filteredRows" :key="row.id" class="hover:bg-gray-50">
                      <td class="px-4 py-2 text-sm font-medium text-gray-900 border-r">{{ row.gl_dist_code }}</td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">{{ row.gl_dist_name }}</td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">{{ row.gl_account }}</td>
                      <td class="px-4 py-2 text-sm text-gray-900">{{ row.gl_account_name || 'N/A' }}</td>
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
const loading = ref(true);
const searchQuery = ref('');
const printDropdownOpen = ref(false);
const printDropdownContainer = ref(null);
const toast = useToast();

const defineRoute = '/material-management/system-requirement/inventory-setup/gl-distribution';

const filteredRows = computed(() => {
  return rows.value.filter(row => {
    const searchMatch = (
      (row.gl_dist_code && row.gl_dist_code.toLowerCase().includes(searchQuery.value.toLowerCase())) ||
      (row.gl_dist_name && row.gl_dist_name.toLowerCase().includes(searchQuery.value.toLowerCase())) ||
      (row.gl_account && row.gl_account.toLowerCase().includes(searchQuery.value.toLowerCase())) ||
      (row.gl_account_name && row.gl_account_name.toLowerCase().includes(searchQuery.value.toLowerCase()))
    );
    
    return searchMatch;
  });
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
    const response = await axios.get('/api/material-management/gl-distributions/for-print');
    rows.value = response.data;
  } catch (error) {
    console.error('Error fetching GL distribution data:', error);
    toast.error('Failed to load GL distribution data. Please try again.');
  } finally {
    loading.value = false;
  }
};

const exportAsCsv = () => {
  let csvContent = 'data:text/csv;charset=utf-8,';
  csvContent += 'GL DIST#,GL DIST Name,GL Acct#,GL Acct Name\n';
  
  filteredRows.value.forEach(row => {
    csvContent += `"${row.gl_dist_code}","${row.gl_dist_name}","${row.gl_account}","${row.gl_account_name || ''}"\n`;
  });

  const encodedUri = encodeURI(csvContent);
  const link = document.createElement('a');
  link.setAttribute('href', encodedUri);
  link.setAttribute('download', `gl_distributions_${new Date().toISOString().slice(0, 10)}.csv`);
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
  printDropdownOpen.value = false;
};

const printAsPdf = () => {
  try {
    // Create new PDF document
    const doc = new jsPDF('p', 'mm', 'a4');
    
    // Set document properties
    doc.setProperties({
      title: 'Material Management GL Distribution',
      subject: 'GL Distribution Report',
      author: 'PT. Multibox Indah - CPS ENTERPRISE 2020',
      creator: 'ERP System'
    });

    // Add company header
    doc.setFontSize(20);
    doc.setFont('helvetica', 'bold');
    doc.text('PT. Multibox Indah - CPS ENTERPRISE 2020', 105, 20, { align: 'center' });
    
    doc.setFontSize(14);
    doc.setFont('helvetica', 'normal');
    doc.text('Material Management GL Distribution', 105, 30, { align: 'center' });
    
    // Add date and time
    const currentDate = new Date().toLocaleDateString('id-ID');
    const currentTime = new Date().toLocaleTimeString('id-ID');
    doc.setFontSize(10);
    doc.text(`Printed on ${currentDate} at ${currentTime}`, 105, 40, { align: 'center' });
    
    // Add summary information
    doc.setFontSize(11);
    doc.setFont('helvetica', 'bold');
    doc.text('Summary:', 20, 55);
    doc.setFont('helvetica', 'normal');
    doc.text(`Total GL Distributions: ${rows.value.length}`, 20, 62);
    doc.text(`Showing: ${filteredRows.value.length} of ${rows.value.length}`, 20, 69);
    
    // Prepare table data
    const tableData = filteredRows.value.map((row, index) => [
      (index + 1).toString(),
      row.gl_dist_code || '',
      row.gl_dist_name || '',
      row.gl_account || '',
      row.gl_account_name || 'N/A'
    ]);
    
    // Add table using autoTable
    autoTable(doc, {
      startY: 80,
      head: [['No.', 'GL DIST#', 'GL DIST Name', 'GL Acct#', 'GL Acct Name']],
      body: tableData,
      theme: 'grid',
      styles: {
        fontSize: 8,
        cellPadding: 3,
        lineColor: [0, 0, 0],
        lineWidth: 0.1,
        textColor: [0, 0, 0],
      },
      headStyles: {
        fillColor: [79, 70, 229], // Indigo color
        textColor: [255, 255, 255],
        fontStyle: 'bold',
        fontSize: 9,
        halign: 'center',
      },
      columnStyles: {
        0: { 
          cellWidth: 15,
          halign: 'center',
          fontStyle: 'bold'
        }, // No. column
        1: { 
          cellWidth: 25,
          halign: 'center'
        }, // GL DIST# column
        2: { 
          cellWidth: 35,
          halign: 'left'
        }, // GL DIST Name column
        3: { 
          cellWidth: 25,
          halign: 'center'
        }, // GL Acct# column
        4: { 
          cellWidth: 35,
          halign: 'left'
        }, // GL Acct Name column
      },
      alternateRowStyles: {
        fillColor: [248, 250, 252], // Light gray
      },
      didDrawPage: function (data) {
        // Add page number
        const pageCount = doc.internal.getNumberOfPages();
        doc.setFontSize(8);
        doc.text(`Page ${data.pageNumber} of ${pageCount}`, 105, doc.internal.pageSize.height - 10, { align: 'center' });
      },
      margin: { top: 80, right: 15, bottom: 20, left: 15 },
      tableWidth: 'auto',
      showFoot: 'lastPage',
      footStyles: {
        fillColor: [240, 240, 240],
        textColor: [0, 0, 0],
        fontStyle: 'bold',
        fontSize: 8,
      },
      foot: [[
        { content: `Total: ${filteredRows.value.length} records`, colSpan: 5, styles: { halign: 'center' } }
      ]],
    });
    
    // Generate filename with timestamp
    const timestamp = new Date().toISOString().slice(0, 19).replace(/:/g, '-');
    const filename = `gl_distributions_${timestamp}.pdf`;
    
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