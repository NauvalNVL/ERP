<template>
  <AppLayout title="View & Print Side Trim By Product Design">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        View & Print Side Trim By Product Design
      </h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
          <!-- Header with buttons -->
          <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 flex items-center justify-between">
            <h2 class="text-lg font-bold text-white">View & Print Side Trim By Product Design</h2>
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
                    <a @click.prevent="printAsExcel" href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                      <i class="fas fa-file-excel mr-2 text-green-500"></i> Export as Excel
                    </a>
                  </div>
                </transition>
              </div>
              <a href="/standard-formula/setup-side-trim-by-product-design" class="bg-gray-600 hover:bg-gray-500 text-white px-3 py-1 rounded text-sm flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.707-10.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L9.414 11H13a1 1 0 100-2H9.414l1.293-1.293z" clip-rule="evenodd" />
                </svg>
                Back
              </a>
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
              <!-- Filter section -->
              <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200 print:hidden">
                <div class="flex flex-wrap gap-4 items-end">
                  <!-- Product Design Filter -->
                  <div class="flex-grow md:flex-grow-0 md:w-48">
                    <label for="product-design-filter" class="block text-sm font-medium text-gray-700 mb-1">Product Design</label>
                    <select
                      id="product-design-filter"
                      v-model="filters.productDesignId"
                      @change="filterData"
                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    >
                      <option value="">All Product Designs</option>
                      <option v-for="design in productDesigns" :key="design.id" :value="design.id">
                        {{ design.pd_name || design.code }}
                      </option>
                    </select>
                  </div>
                  
                  <!-- Product Filter -->
                  <div class="flex-grow md:flex-grow-0 md:w-48">
                    <label for="product-filter" class="block text-sm font-medium text-gray-700 mb-1">Product</label>
                    <select
                      id="product-filter"
                      v-model="filters.productId"
                      @change="filterData"
                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    >
                      <option value="">All Products</option>
                      <option v-for="product in products" :key="product.id" :value="product.id">
                        {{ product.product_code || product.code }}
                      </option>
                    </select>
                  </div>
                  
                  <!-- Flute Filter -->
                  <div class="flex-grow md:flex-grow-0 md:w-48">
                    <label for="flute-filter" class="block text-sm font-medium text-gray-700 mb-1">Flute</label>
                    <select
                      id="flute-filter"
                      v-model="filters.fluteId"
                      @change="filterData"
                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    >
                      <option value="">All Flutes</option>
                      <option v-for="flute in flutes" :key="flute.id" :value="flute.id">
                        {{ flute.code }}
                      </option>
                    </select>
                  </div>
                  
                  <!-- Compute Filter -->
                  <div class="flex-grow md:flex-grow-0 md:w-48">
                    <label for="compute-filter" class="block text-sm font-medium text-gray-700 mb-1">Compute</label>
                    <select
                      id="compute-filter"
                      v-model="filters.compute"
                      @change="filterData"
                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    >
                      <option value="">All</option>
                      <option value="true">Yes</option>
                      <option value="false">No</option>
                    </select>
                </div>
                
                <!-- Reset Button -->
                  <div>
                  <button 
                    @click="resetFilters" 
                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 100-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                    </svg>
                      Reset
                  </button>
                </div>
                </div>
              </div>

              <!-- Table section -->
              <div id="printable-content" class="overflow-x-auto">
                <div class="mb-6 print:mb-8 hidden print:block">
                  <h1 class="text-2xl font-bold text-center print:text-3xl">Side Trim By Product Design Report</h1>
                  <p class="text-center text-gray-600 print:text-lg">Generated on {{ formattedDate }}</p>
                </div>

                <table class="min-w-full divide-y divide-gray-200 border" id="sideTrimsTable">
                  <thead class="bg-gray-100">
                    <tr>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        P/Design
                      </th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        Product
                      </th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        Flute
                      </th>
                      <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        Compute
                      </th>
                      <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        Length Less (mm)
                      </th>
                      <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Length Add (mm)
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="filteredSideTrims.length === 0" class="hover:bg-gray-50">
                      <td colspan="6" class="px-4 py-4 text-center text-sm text-gray-500">
                        No side trim data found matching your filters.
                      </td>
                    </tr>
                    <tr v-for="trim in filteredSideTrims" :key="trim.id" class="hover:bg-gray-50">
                      <td class="px-4 py-2 text-sm font-medium text-gray-900 border-r">
                        {{ trim.product_design_code }}
                      </td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">
                        {{ trim.product_code }}
                      </td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">
                        {{ trim.flute_code }}
                      </td>
                      <td class="px-4 py-2 text-center border-r">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                          :class="trim.compute ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'">
                          {{ trim.compute ? 'Yes' : 'No' }}
                        </span>
                      </td>
                      <td class="px-4 py-2 text-center text-sm text-gray-900 border-r">
                        {{ trim.length_less }}
                      </td>
                      <td class="px-4 py-2 text-center text-sm text-gray-900">
                        {{ trim.length_add }}
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

export default defineComponent({
  components: {
    AppLayout,
    Link
  },
  setup() {
    const loading = ref(true);
    const sideTrims = ref([]);
    const filteredSideTrims = ref([]);
    const productDesigns = ref([]);
    const products = ref([]);
    const flutes = ref([]);
    const filters = ref({
      productDesignId: '',
      productId: '',
      fluteId: '',
      compute: ''
    });
    const notification = ref({
      show: false,
      message: '',
      type: 'success'
    });
    
    const printDropdownOpen = ref(false);
    const printDropdownContainer = ref(null);

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

    const loadData = async () => {
      try {
        loading.value = true;
        
        // Load all data in parallel for better performance
        const [designsResponse, productsResponse, flutesResponse, trimResponse] = await Promise.all([
          axios.get('/api/product-designs'),
          axios.get('/api/products'),
          axios.get('/api/paper-flutes'),
          axios.get('/api/side-trims-by-product-design')
        ]);
        
        // Store the fetched data
        productDesigns.value = designsResponse.data;
        products.value = productsResponse.data;
        flutes.value = flutesResponse.data;
        
        if (trimResponse.data && trimResponse.data.status === 'success') {
          // Process the data from the API response
          sideTrims.value = trimResponse.data.data.map(trim => {
            // Get product design, product, and flute information
            const productDesign = productDesigns.value.find(d => d.id === trim.product_design_id);
            const product = products.value.find(p => p.id === trim.product_id);
            const flute = flutes.value.find(f => f.id === trim.flute_id);
            
            return {
              id: trim.id,
              product_design_id: trim.product_design_id,
              product_id: trim.product_id,
              flute_id: trim.flute_id,
              product_design_code: productDesign?.pd_name || productDesign?.code || 'N/A',
              product_code: product?.product_code || product?.code || 'N/A',
              flute_code: flute?.code || 'N/A',
              compute: trim.compute === true || trim.compute === 1,
              length_less: trim.length_less || 0,
              length_add: trim.length_add || 0
            };
          });
          
          // Sort by product design, product, and flute
          sideTrims.value.sort((a, b) => {
            if (a.product_design_code !== b.product_design_code) {
              return a.product_design_code.localeCompare(b.product_design_code);
            }
            if (a.product_code !== b.product_code) {
              return a.product_code.localeCompare(b.product_code);
            }
            return a.flute_code.localeCompare(b.flute_code);
          });
          
          // Initialize filtered data
          filteredSideTrims.value = [...sideTrims.value];
        } else {
          showNotification('No side trim data found or API error.', 'error');
          sideTrims.value = [];
          filteredSideTrims.value = [];
        }
      } catch (error) {
        console.error('Error loading data:', error);
        
        // More detailed error handling
        let errorMessage = 'Failed to load side trim by product design data';
        
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
        
        sideTrims.value = [];
        filteredSideTrims.value = [];
      } finally {
        loading.value = false;
      }
    };

    const filterData = () => {
      filteredSideTrims.value = sideTrims.value.filter(trim => {
        const matchesProductDesign = !filters.value.productDesignId || trim.product_design_id === filters.value.productDesignId;
        const matchesProduct = !filters.value.productId || trim.product_id === filters.value.productId;
        const matchesFlute = !filters.value.fluteId || trim.flute_id === filters.value.fluteId;
        const matchesCompute = filters.value.compute === '' || 
          (filters.value.compute === 'true' && trim.compute) || 
          (filters.value.compute === 'false' && !trim.compute);
        
        return matchesProductDesign && matchesProduct && matchesFlute && matchesCompute;
      });
    };

    const resetFilters = () => {
      filters.value = {
        productDesignId: '',
        productId: '',
        fluteId: '',
        compute: ''
      };
      filteredSideTrims.value = [...sideTrims.value];
    };

    const exportToExcel = () => {
      try {
        showNotification('Preparing Excel export...');
        
        // Since backend export is not implemented, we'll create the Excel file on the frontend
        // We'll use a simple CSV format that Excel can open
        const headers = ['Product Design', 'Product', 'Flute', 'Compute', 'Length Less (mm)', 'Length Add (mm)'];
        
        // Convert data to CSV format
        const csvContent = [
          headers.join(','),
          ...filteredSideTrims.value.map(trim => [
            `"${trim.product_design_code}"`,
            `"${trim.product_code}"`,
            `"${trim.flute_code}"`,
            trim.compute ? 'Yes' : 'No',
            trim.length_less,
            trim.length_add
          ].join(','))
        ].join('\n');
        
        // Create a Blob with the CSV data
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        
        // Create a link element and trigger download
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', `side_trim_by_product_design_${new Date().toISOString().split('T')[0]}.csv`);
        document.body.appendChild(link);
        link.click();
        link.remove();
        
        showNotification('Excel export completed successfully');
      } catch (error) {
        console.error('Error exporting to Excel:', error);
        showNotification('Failed to export data to Excel', 'error');
      }
    };
    
    const printAsExcel = () => {
      exportToExcel();
      printDropdownOpen.value = false;
    };
    
    const generatePdf = () => {
      const doc = new jsPDF();
      
      doc.setFontSize(18);
      doc.setFont('helvetica', 'bold');
      doc.text('Side Trim By Product Design Report', 15, 22);

      doc.setFontSize(11);
      doc.setFont('helvetica', 'normal');
      doc.text(`Generated on: ${formattedDate.value}`, 15, 28);
      
      // Add filter information if filters are applied
      let yPos = 35;
      if (filters.value.productDesignId || filters.value.productId || filters.value.fluteId || filters.value.compute !== '') {
        doc.setFontSize(11);
        doc.setFont('helvetica', 'bold');
        doc.text('Applied Filters:', 15, yPos);
        yPos += 6;
        
        doc.setFont('helvetica', 'normal');
        
        if (filters.value.productDesignId) {
          const design = productDesigns.value.find(d => d.id === filters.value.productDesignId);
          doc.text(`Product Design: ${design?.pd_name || design?.code || 'Unknown'}`, 15, yPos);
          yPos += 5;
        }
        
        if (filters.value.productId) {
          const product = products.value.find(p => p.id === filters.value.productId);
          doc.text(`Product: ${product?.product_code || product?.code || 'Unknown'}`, 15, yPos);
          yPos += 5;
        }
        
        if (filters.value.fluteId) {
          const flute = flutes.value.find(f => f.id === filters.value.fluteId);
          doc.text(`Flute: ${flute?.code || 'Unknown'}`, 15, yPos);
          yPos += 5;
        }
        
        if (filters.value.compute !== '') {
          doc.text(`Compute: ${filters.value.compute === 'true' ? 'Yes' : 'No'}`, 15, yPos);
          yPos += 5;
        }
        
        yPos += 5;
      }
      
      const tableData = filteredSideTrims.value.map(trim => [
        trim.product_design_code,
        trim.product_code,
        trim.flute_code,
        trim.compute ? 'Yes' : 'No',
        trim.length_less,
        trim.length_add
      ]);

      autoTable(doc, {
        head: [['Product Design', 'Product', 'Flute', 'Compute', 'Length Less (mm)', 'Length Add (mm)']],
        body: tableData,
        startY: yPos,
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
          2: { cellWidth: 'auto' },
          3: { halign: 'center' },
          4: { halign: 'center' },
          5: { halign: 'center' }
        }
      });
      
      // Add page number
      const pageCount = doc.internal.getNumberOfPages();
      for (let i = 1; i <= pageCount; i++) {
        doc.setPage(i);
        doc.setFontSize(8);
        doc.text(`Page ${i} of ${pageCount}`, doc.internal.pageSize.width - 30, doc.internal.pageSize.height - 10);
      }
      
      doc.save(`side_trim_by_product_design_${new Date().toISOString().split('T')[0]}.pdf`);
    };

    const printAsPdf = () => {
      generatePdf();
      printDropdownOpen.value = false;
    };

    // PDF function is now implemented as printAsPdf

    // Close dropdown when clicking outside
    const handleClickOutside = (event) => {
      if (printDropdownContainer.value && !printDropdownContainer.value.contains(event.target)) {
        printDropdownOpen.value = false;
      }
    };

    // Lifecycle hooks
    onMounted(() => {
      loadData();
      document.addEventListener('click', handleClickOutside);
    });

    onUnmounted(() => {
      document.removeEventListener('click', handleClickOutside);
    });

    return {
      loading,
      sideTrims,
      filteredSideTrims,
      productDesigns,
      products,
      flutes,
      filters,
      notification,
      printDropdownOpen,
      printDropdownContainer,
      formattedDate,
      filterData,
      resetFilters,
      printAsExcel,
      printAsPdf,
      generatePdf,
      exportToExcel
    };
  }
});
</script>

<style>
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
  }
  
  /* Show print-only elements */
  .hidden.print\:block {
    display: block !important;
  }
  
  /* Remove background colors for better printing */
  .bg-gray-100 {
    background-color: #f9fafb !important;
    -webkit-print-color-adjust: exact;
  }
  
  /* Ensure text is visible */
  .text-gray-500, .text-gray-900 {
    color: #000 !important;
  }
  
  /* Make borders more visible */
  .border, .border-r {
    border-color: #000 !important;
  }
}
</style> 