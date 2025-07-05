<template>
  <AppLayout title="View & Print Bundling Computation Method">
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          View & Print Bundling Computation Method
        </h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
          <!-- Header with buttons -->
          <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 flex items-center justify-between">
            <h2 class="text-lg font-bold text-white">View & Print Bundling Computation Method</h2>
            <div class="flex space-x-2">
              <div class="relative" ref="printDropdownContainer">
                <button @click="printDropdownOpen = !printDropdownOpen" class="bg-blue-500 hover:bg-blue-400 text-white px-3 py-1 rounded text-sm flex items-center">
                  <i class="fas fa-print mr-2"></i>
                  <span>Print</span>
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
                    <a @click.prevent="printAsCsv" href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                      <i class="fas fa-file-csv mr-2 text-green-500"></i> Export as CSV
                    </a>
                  </div>
                </transition>
              </div>
              <Link :href="defineRoute" class="bg-gray-600 hover:bg-gray-500 text-white px-3 py-1 rounded text-sm flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.707-10.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L9.414 11H13a1 1 0 100-2H9.414l1.293-1.293z" clip-rule="evenodd" />
                </svg>
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
              <!-- Search and Filter -->
              <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200 print:hidden">
                <div class="flex flex-wrap items-center gap-4">
                  <div class="flex-grow relative">
                <input
                  type="text"
                  v-model="search"
                  placeholder="Search by product design, product, or flute..."
                      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                      </svg>
                    </div>
              </div>
              
              <div class="w-full md:w-auto">
                <select
                  v-model="computeFilter"
                      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  <option value="all">All</option>
                  <option value="yes">Yes</option>
                  <option value="no">No</option>
                </select>
                  </div>
                </div>
              </div>

              <!-- Report header for print -->
              <div class="hidden print:block mb-6">
                <div class="text-center">
                  <h1 class="text-2xl font-bold text-center print:text-3xl">Bundling Computation Method Report</h1>
                  <p class="text-center text-gray-600 print:text-lg">Generated on {{ formattedDate }}</p>
              </div>
            </div>

              <!-- Table section -->
            <div class="overflow-x-auto" ref="printableTable">
                <table class="min-w-full divide-y divide-gray-200 border">
                  <thead class="bg-gray-100">
                    <tr>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        Product Design
                      </th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        Product
                      </th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        Flute
                      </th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        Pieces Per Bundle
                      </th>
                      <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Compute
                      </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="filteredMethods.length === 0" class="hover:bg-gray-50">
                      <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500">
                        No computation methods found.
                      </td>
                    </tr>
                    <tr v-for="method in filteredMethods" :key="method.id" class="hover:bg-gray-50">
                      <td class="px-4 py-2 text-sm font-medium text-gray-900 border-r">
                        {{ method.product_design || '-' }}
                      </td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">
                        {{ method.product || '-' }}
                      </td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">
                        {{ method.flute || '-' }}
                      </td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">
                        {{ method.formula_pieces }}
                      </td>
                      <td class="px-4 py-2 text-sm text-center text-gray-900">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                          :class="method.is_compute ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'">
                        {{ method.is_compute ? 'Yes' : 'No' }}
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
              </div>
            </div>

            <!-- Notification -->
            <transition 
              enter-active-class="transform ease-out duration-300 transition"
              enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
              enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
              leave-active-class="transition ease-in duration-200"
              leave-from-class="opacity-100"
              leave-to-class="opacity-0"
            >
              <div 
                v-if="notification.show" 
                class="fixed bottom-4 right-4 w-80 p-4 rounded-lg shadow-lg border border-l-4 print:hidden"
                :class="notification.type === 'success' ? 'bg-green-50 border-green-500 text-green-800' : 'bg-red-50 border-red-500 text-red-800'"
              >
                <div class="flex items-center">
                  <div class="flex-shrink-0">
                    <svg v-if="notification.type === 'success'" class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <svg v-else class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                  </div>
                  <div class="ml-3">
                    <p class="text-sm">{{ notification.message }}</p>
                  </div>
                </div>
              </div>
            </transition>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import { defineComponent, ref, computed, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';
import 'jspdf-autotable';

export default defineComponent({
  components: {
    AppLayout,
    Link
  },
  setup() {
    const loading = ref(false);
const computationMethods = ref([]);
const search = ref('');
const computeFilter = ref('all');
    const printDropdownOpen = ref(false);
    const printDropdownContainer = ref(null);
const printableTable = ref(null);
    const notification = ref({
      show: false,
      message: '',
      type: 'success'
    });
    
    // Define route for back button
    const defineRoute = '/sales-management/standard-formula/bundling-computation-method/computation-method';

// Filter methods based on search and compute filter
const filteredMethods = computed(() => {
  let filtered = [...computationMethods.value];
  
  // Apply search filter
  if (search.value) {
    const searchLower = search.value.toLowerCase();
    filtered = filtered.filter(method => 
      (method.product_design && method.product_design.toLowerCase().includes(searchLower)) ||
      (method.product && method.product.toLowerCase().includes(searchLower)) ||
          (method.flute && method.flute.toLowerCase().includes(searchLower)) ||
          (method.product_name && method.product_name.toLowerCase().includes(searchLower))
    );
  }
  
  // Apply compute filter
  if (computeFilter.value !== 'all') {
    const isCompute = computeFilter.value === 'yes';
    filtered = filtered.filter(method => method.is_compute === isCompute);
  }
  
  return filtered;
});

    // Formatted current date
    const formattedDate = computed(() => {
      const now = new Date();
      return new Intl.DateTimeFormat('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      }).format(now);
    });

    const showNotification = (message, type = 'success') => {
      notification.value = {
        show: true,
        message,
        type
      };
      
      setTimeout(() => {
        notification.value.show = false;
      }, 3000);
    };

    const fetchComputationMethods = async () => {
      try {
        loading.value = true;
        const response = await axios.get('/api/bundling-computation-methods');
        
        if (response.data && response.data.status === 'success') {
          computationMethods.value = response.data.data;
        } else {
          throw new Error('Failed to load computation methods');
        }
      } catch (error) {
        console.error('Error fetching computation methods:', error);
        
        // More detailed error handling
        let errorMessage = 'Failed to load computation methods';
        
        if (error.response) {
          errorMessage += ` (Status: ${error.response.status})`;
          if (error.response.data && error.response.data.message) {
            errorMessage += `: ${error.response.data.message}`;
          }
        } else if (error.request) {
          errorMessage += ': No response received from server';
        } else {
          errorMessage += `: ${error.message}`;
        }
        
        showNotification(errorMessage, 'error');
      } finally {
        loading.value = false;
      }
    };

    const exportAsCsv = () => {
      try {
        showNotification('Exporting data as CSV...');
        
        // Create CSV content
        const headers = ['Product Design', 'Product', 'Flute', 'Pieces Per Bundle', 'Compute'];
        const csvContent = [
          headers.join(','),
          ...filteredMethods.value.map(method => [
            method.product_design || '-',
            method.product || '-',
            method.flute || '-',
            method.formula_pieces,
            method.is_compute ? 'Yes' : 'No'
          ].join(','))
        ].join('\n');
        
        // Create a blob and download link
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', `bundling_computation_methods_${new Date().toISOString().slice(0, 10)}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        showNotification('Data exported successfully');
      } catch (error) {
        console.error('Error exporting data:', error);
        showNotification('Failed to export data', 'error');
      } finally {
        printDropdownOpen.value = false;
      }
    };

    const printAsPdf = () => {
      try {
        const doc = new jsPDF();
        
        // Add document title and date
        doc.setFontSize(18);
        doc.setFont('helvetica', 'bold');
        doc.text('Bundling Computation Method Report', 15, 22);

        doc.setFontSize(11);
        doc.setFont('helvetica', 'normal');
        doc.text(`Generated on: ${formattedDate.value}`, 15, 28);
        
        // Prepare table data
        const tableData = filteredMethods.value.map(method => [
          method.product_design || '-',
          method.product || '-',
          method.flute || '-',
          method.formula_pieces.toString(),
          method.is_compute ? 'Yes' : 'No',
        ]);

        // Generate table with autoTable
        autoTable(doc, {
          head: [['Product Design', 'Product', 'Flute', 'Pieces/Bundle', 'Compute']],
          body: tableData,
          startY: 35,
          theme: 'grid',
          styles: {
            fontSize: 10,
            cellPadding: 2,
          },
          headStyles: {
            fillColor: [41, 128, 185], // A shade of blue
            textColor: 255,
            fontStyle: 'bold',
          },
          columnStyles: {
            0: { cellWidth: 'auto' },
            1: { cellWidth: 'auto' },
            2: { cellWidth: 20 },
            3: { cellWidth: 25, halign: 'center' },
            4: { cellWidth: 20, halign: 'center' },
          }
        });
        
        // Open PDF in a new tab
        doc.output('dataurlnewwindow');
        showNotification('PDF generated successfully');
      } catch (error) {
        console.error('Error generating PDF:', error);
        showNotification('Failed to generate PDF', 'error');
      } finally {
        printDropdownOpen.value = false;
      }
    };

    const printAsCsv = exportAsCsv;

    const handleClickOutside = (event) => {
      if (printDropdownContainer.value && !printDropdownContainer.value.contains(event.target)) {
        printDropdownOpen.value = false;
      }
    };

    onMounted(() => {
      fetchComputationMethods();
      document.addEventListener('click', handleClickOutside);
    });

    onUnmounted(() => {
      document.removeEventListener('click', handleClickOutside);
    });

    return {
      loading,
      computationMethods,
      filteredMethods,
      search,
      computeFilter,
      notification,
      defineRoute,
      formattedDate,
      printDropdownOpen,
      printDropdownContainer,
      printableTable,
      showNotification,
      printAsPdf,
      printAsCsv,
    };
  }
});
</script>

<style>
/* Add Font Awesome if not already available */
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');

@media print {
  @page {
    size: A4;
    margin: 1cm;
  }
  
  body {
    font-size: 12pt;
  }
  
  /* Hide non-printable elements */
  button, select, .print\:hidden, .bg-gray-50 {
    display: none !important;
  }
  
  /* Ensure tables fit on page */
  table {
    page-break-inside: avoid;
    width: 100%;
    border-collapse: collapse;
  }
  
  /* Show print-only elements */
  .hidden.print\:block {
    display: block !important;
  }
  
  /* Keep background colors for better printing */
  .bg-blue-700 {
    background-color: #1d4ed8 !important;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
  }
  
  /* Ensure text is visible */
  .text-white {
    color: #fff !important;
  }
  
  /* Make borders more visible */
  .border, .border-r {
    border-color: #000 !important;
  }
  
  th, td {
    border: 1px solid #000;
    padding: 6px;
    text-align: left;
  }
  
  th {
    background-color: #f2f2f2 !important;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
  }
}
</style> 