<template>
  <AppLayout title="View & Print Approver">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        View & Print Approver
      </h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
          <!-- Header with buttons -->
          <div class="bg-gradient-to-r from-green-600 to-green-800 p-4 flex items-center justify-between">
            <h2 class="text-lg font-bold text-white">View & Print Approver</h2>
            <div class="flex space-x-2">
              <div class="relative" ref="printDropdownContainer">
                <button @click="printDropdownOpen = !printDropdownOpen" class="bg-green-500 hover:bg-green-400 text-white px-3 py-1 rounded text-sm flex items-center">
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
                    <a @click.prevent="printTable" href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                      <i class="fas fa-print mr-2 text-blue-500"></i> Print Table
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
              <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-green-500"></div>
              <span class="ml-3 text-gray-600">Loading...</span>
            </div>
            
            <div v-else>
              <!-- Search and Filters -->
              <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200 print:hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div class="relative">
                    <input 
                      type="text" 
                      v-model="searchQuery" 
                      placeholder="Search ID, name, email..."
                      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                    />
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                      <i class="fas fa-search text-gray-400"></i>
                    </div>
                  </div>
                  <div>
                    <select v-model="oldaFilter" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                      <option value="">All OLDA</option>
                      <option :value="true">OLDA Enabled</option>
                      <option :value="false">OLDA Disabled</option>
                    </select>
                  </div>
                </div>
              </div>
              
              <!-- Report header for print -->
              <div class="hidden print:block mb-6">
                <div class="text-center">
                  <h1 class="text-2xl font-bold text-center print:text-3xl">View & Print Approver</h1>
                  <p class="text-center text-gray-600 print:text-lg">PT. Multibox Indah - CPS ENTERPRISE 2020</p>
                  <p class="text-center text-gray-600 print:text-sm">Generated on {{ formattedDate }}</p>
                </div>
              </div>

              <!-- Statistics Cards -->
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 print:hidden">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                  <div class="flex items-center">
                    <div class="flex-shrink-0">
                      <i class="fas fa-users text-blue-500 text-2xl"></i>
                    </div>
                    <div class="ml-3">
                      <p class="text-sm font-medium text-blue-600">Total Approvers</p>
                      <p class="text-2xl font-semibold text-blue-900">{{ approvers.length }}</p>
                    </div>
                  </div>
                </div>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                  <div class="flex items-center">
                    <div class="flex-shrink-0">
                      <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                    </div>
                    <div class="ml-3">
                      <p class="text-sm font-medium text-green-600">OLDA Enabled</p>
                      <p class="text-2xl font-semibold text-green-900">{{ oldaEnabledCount }}</p>
                    </div>
                  </div>
                </div>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                  <div class="flex items-center">
                    <div class="flex-shrink-0">
                      <i class="fas fa-toggle-on text-yellow-500 text-2xl"></i>
                    </div>
                    <div class="ml-3">
                      <p class="text-sm font-medium text-yellow-600">Active</p>
                      <p class="text-2xl font-semibold text-yellow-900">{{ activeCount }}</p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Authorization Settings Section -->
              <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                <div class="bg-white border border-gray-300 rounded p-3">
                  <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                      <span class="text-gray-700 font-medium">PR Authorized:</span>
                      <span class="font-medium">Yes</span>
                    </div>
                    <div class="flex justify-between">
                      <span class="text-gray-700 font-medium">PR Level:</span>
                      <span class="font-medium">1</span>
                    </div>
                    <div class="flex justify-between">
                      <span class="text-gray-700 font-medium">PR Approval Style:</span>
                      <span class="font-medium">Tick 1 PR</span>
                    </div>
                    <div class="flex justify-between">
                      <span class="text-gray-700 font-medium">PR Zero Price:</span>
                      <span class="font-medium">Yes, Allowed</span>
                    </div>
                    <div class="flex justify-between">
                      <span class="text-gray-700 font-medium">PR Price History:</span>
                      <span class="font-medium">No</span>
                    </div>
                  </div>
                </div>
                
                <div class="bg-white border border-gray-300 rounded p-3">
                  <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                      <span class="text-gray-700 font-medium">PO Authorized:</span>
                      <span class="font-medium">No</span>
                    </div>
                    <div class="flex justify-between">
                      <span class="text-gray-700 font-medium">PO Level:</span>
                      <span class="font-medium">1</span>
                    </div>
                    <div class="flex justify-between">
                      <span class="text-gray-700 font-medium">PO Approval Style:</span>
                      <span class="font-medium">Tick 1 PO</span>
                    </div>
                    <div class="flex justify-between">
                      <span class="text-gray-700 font-medium">PO Min Limit:</span>
                      <span class="font-medium">1.00</span>
                    </div>
                    <div class="flex justify-between">
                      <span class="text-gray-700 font-medium">PO Max Limit:</span>
                      <span class="font-medium">99,999,999.00</span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Table section -->
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border" id="approver-table">
                  <thead class="bg-gray-100">
                    <tr>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Approver ID</th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Approver Name</th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">User ID</th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Email</th>
                      <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">OLDA</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="filteredApprovers.length === 0" class="hover:bg-gray-50">
                      <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500">No approvers found.</td>
                    </tr>
                    <tr v-for="approver in filteredApprovers" :key="approver.approver_id" class="hover:bg-gray-50">
                      <td class="px-4 py-2 text-sm font-medium text-gray-900 border-r">{{ approver.approver_id }}</td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">{{ approver.approver_name }}</td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">{{ approver.user_id || '-' }}</td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">{{ approver.email }}</td>
                      <td class="px-4 py-2 text-sm text-center">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                              :class="approver.olda_enabled ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'">
                          {{ approver.olda_enabled ? 'Yes' : 'No' }}
                        </span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <!-- Summary Information -->
              <div class="mt-6 bg-gray-50 rounded-lg p-4 text-sm text-gray-600">
                <p><strong>Total Records:</strong> {{ filteredApprovers.length }} of {{ approvers.length }} approvers</p>
                <p><strong>Filtered by:</strong> 
                  <span v-if="searchQuery">Search: "{{ searchQuery }}"</span>
                  <span v-if="oldaFilter !== ''"> | OLDA: {{ oldaFilter ? 'Enabled' : 'Disabled' }}</span>
                  <span v-if="!searchQuery && oldaFilter === ''">None</span>
                </p>
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

const approvers = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const oldaFilter = ref('');
const printDropdownOpen = ref(false);
const printDropdownContainer = ref(null);
const toast = useToast();

const defineRoute = '/material-management/system-requirement/purchase-order-setup/approver';

const filteredApprovers = computed(() => {
  return approvers.value.filter(approver => {
    const searchMatch = (
      approver.approver_id.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      approver.approver_name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      (approver.user_id && approver.user_id.toLowerCase().includes(searchQuery.value.toLowerCase())) ||
      approver.email.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
    
    const oldaMatch = oldaFilter.value === '' ? true : approver.olda_enabled === oldaFilter.value;
    
    return searchMatch && oldaMatch;
  });
});

const oldaEnabledCount = computed(() => {
  return approvers.value.filter(approver => approver.olda_enabled).length;
});

const activeCount = computed(() => {
  return approvers.value.filter(approver => approver.is_active).length;
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
    const response = await axios.get('/api/approvers');
    approvers.value = response.data;
  } catch (error) {
    console.error('Error fetching approver data:', error);
    toast.error('Failed to load approver data. Please try again.');
  } finally {
    loading.value = false;
  }
};

const formatCurrency = (value) => {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits: 2
  }).format(value);
};

const exportAsCsv = () => {
  let csvContent = 'data:text/csv;charset=utf-8,';
  csvContent += 'Approver ID,Approver Name,User ID,Email,OLDA\n';
  
  filteredApprovers.value.forEach(approver => {
    csvContent += `"${approver.approver_id}","${approver.approver_name}","${approver.user_id || ''}","${approver.email}","${approver.olda_enabled ? 'Yes' : 'No'}"\n`;
  });

  const encodedUri = encodeURI(csvContent);
  const link = document.createElement('a');
  link.setAttribute('href', encodedUri);
  link.setAttribute('download', `approvers_${new Date().toISOString().slice(0, 10)}.csv`);
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
  printDropdownOpen.value = false;
  toast.success('CSV export completed');
};

const printTable = () => {
  window.print();
  printDropdownOpen.value = false;
};

const printAsPdf = () => {
  try {
    // Create new PDF document
    const doc = new jsPDF('l', 'mm', 'a4'); // Landscape orientation for better table fit
    
    // Set document properties
    doc.setProperties({
      title: 'View & Print Approver',
      subject: 'Approver Report',
      author: 'PT. Multibox Indah - CPS ENTERPRISE 2020',
      creator: 'ERP System'
    });

    // Add company header
    doc.setFontSize(20);
    doc.setFont('helvetica', 'bold');
    doc.text('PT. Multibox Indah - CPS ENTERPRISE 2020', 148, 20, { align: 'center' });
    
    doc.setFontSize(16);
    doc.setFont('helvetica', 'normal');
    doc.text('View & Print Approver', 148, 30, { align: 'center' });
    
    // Add date and time
    const currentDate = new Date().toLocaleDateString('id-ID');
    const currentTime = new Date().toLocaleTimeString('id-ID');
    doc.setFontSize(10);
    doc.text(`Printed on ${currentDate} at ${currentTime}`, 148, 40, { align: 'center' });
    
    // Add summary information
    doc.setFontSize(11);
    doc.setFont('helvetica', 'bold');
    doc.text('Summary:', 20, 55);
    doc.setFont('helvetica', 'normal');
    doc.text(`Total Approvers: ${approvers.value.length}`, 20, 62);
    doc.text(`OLDA Enabled: ${oldaEnabledCount.value}`, 20, 69);
    doc.text(`Active: ${activeCount.value}`, 20, 76);
    doc.text(`Showing: ${filteredApprovers.value.length} of ${approvers.value.length}`, 20, 83);
    
    // Prepare table data
    const tableData = filteredApprovers.value.map(approver => [
      approver.approver_id,
      approver.approver_name,
      approver.user_id || '-',
      approver.email,
      approver.olda_enabled ? 'Yes' : 'No'
    ]);
    
    // Add table using autoTable
    autoTable(doc, {
      startY: 95,
      head: [['Approver ID', 'Approver Name', 'User ID', 'Email', 'OLDA']],
      body: tableData,
      theme: 'grid',
      styles: {
        fontSize: 9,
        cellPadding: 3,
        lineColor: [0, 0, 0],
        lineWidth: 0.1,
      },
      headStyles: {
        fillColor: [34, 197, 94], // Green color matching header
        textColor: [255, 255, 255],
        fontStyle: 'bold',
        fontSize: 10,
      },
      columnStyles: {
        0: { cellWidth: 35 }, // Approver ID
        1: { cellWidth: 60 }, // Approver Name
        2: { cellWidth: 35 }, // User ID
        3: { cellWidth: 80 }, // Email
        4: { cellWidth: 25 }, // OLDA
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
      margin: { top: 95, right: 20, bottom: 20, left: 20 },
    });
    
    // Generate filename with timestamp
    const timestamp = new Date().toISOString().slice(0, 19).replace(/:/g, '-');
    const filename = `approver_report_${timestamp}.pdf`;
    
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
    display: none !important;
  }
  .print\:block {
    display: block !important;
  }
  
  /* Print specific table styling */
  #approver-table {
    width: 100% !important;
    font-size: 10px !important;
  }
  
  #approver-table th,
  #approver-table td {
    padding: 4px !important;
    border: 1px solid #000 !important;
  }
  
  #approver-table thead {
    background-color: #f3f4f6 !important;
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
  }
}
</style>

