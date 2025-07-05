<template>
  <AppLayout title="View & Print Side Trim By Flute">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        View & Print Side Trim By Flute
      </h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
          <!-- Header with buttons -->
          <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 flex items-center justify-between">
            <h2 class="text-lg font-bold text-white">View & Print Side Trim By Flute</h2>
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
              <a href="/standard-formula/setup-side-trim-by-flute" class="bg-gray-600 hover:bg-gray-500 text-white px-3 py-1 rounded text-sm flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
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
              <!-- Filters -->
              <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                <div class="flex flex-wrap gap-4 items-end">
                  <div class="flex-grow md:flex-grow-0 md:w-48">
                    <label for="flute-filter" class="block text-sm font-medium text-gray-700 mb-1">Filter by Flute</label>
                    <select
                      id="flute-filter"
                      v-model="filter.fluteId"
                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                      @change="filterData"
                    >
                      <option value="">All Flutes</option>
                      <option v-for="flute in flutes" :key="flute.id" :value="flute.id">
                        {{ flute.code }}
                      </option>
                    </select>
                  </div>
                  <div class="flex-grow md:flex-grow-0 md:w-48">
                    <label for="compute-filter" class="block text-sm font-medium text-gray-700 mb-1">Filter by Compute</label>
                    <select
                      id="compute-filter"
                      v-model="filter.compute"
                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                      @change="filterData"
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
                      <i class="fas fa-redo-alt mr-1"></i>
                      Reset
                    </button>
                  </div>
                </div>
              </div>

              <!-- Table section -->
              <div id="printable-content" class="overflow-x-auto">
                <div class="mb-6 print:mb-8 hidden print:block">
                  <h1 class="text-2xl font-bold text-center print:text-3xl">Side Trim By Flute Report</h1>
                  <p class="text-center text-gray-600 print:text-lg">Generated on {{ formattedDate }}</p>
                </div>

                <table class="min-w-full divide-y divide-gray-200 border" id="sideTrimsTable">
                  <thead class="bg-gray-100">
                    <tr>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        Flute
                      </th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        Flute Name
                      </th>
                      <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        Compute
                      </th>
                      <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        Length Add (mm)
                      </th>
                      <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        Length Less (mm)
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="filteredSideTrims.length === 0" class="hover:bg-gray-50">
                      <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500">
                        No side trim data found matching the current filters.
                      </td>
                    </tr>
                    <tr 
                      v-for="trim in filteredSideTrims" 
                      :key="trim.id" 
                      class="hover:bg-gray-50"
                    >
                      <td class="px-4 py-2 text-sm font-medium text-gray-900 border-r">
                        {{ trim.flute_code }}
                      </td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">
                        {{ trim.flute_name }}
                      </td>
                      <td class="px-4 py-2 text-center border-r">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                          :class="trim.compute ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'">
                          {{ trim.compute ? 'Yes' : 'No' }}
                        </span>
                      </td>
                      <td class="px-4 py-2 text-center text-sm text-gray-900 border-r">
                        {{ trim.length_add }}
                      </td>
                      <td class="px-4 py-2 text-center text-sm text-gray-900 border-r">
                        {{ trim.length_less }}
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
                class="fixed bottom-4 right-4 w-80 p-4 rounded-lg shadow-lg border border-l-4"
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
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';

export default defineComponent({
  components: {
    AppLayout
  },
  setup() {
    const loading = ref(true);
    const sideTrims = ref([]);
    const filteredSideTrims = ref([]);
    const flutes = ref([]);
    const filter = ref({
      fluteId: '',
      compute: ''
    });
    
    const printDropdownOpen = ref(false);
    const printDropdownContainer = ref(null);
    
    const notification = ref({
      show: false,
      message: '',
      type: 'success'
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

    const loadData = async () => {
      try {
        loading.value = true;
        
        // Load flutes
        const flutesResponse = await axios.get('/api/paper-flutes');
        flutes.value = flutesResponse.data;
        
        // Load side trims
        const trimsResponse = await axios.get('/api/side-trims-by-flute');
        
        if (trimsResponse.data && trimsResponse.data.status === 'success') {
          sideTrims.value = trimsResponse.data.data.map(trim => {
            const flute = trim.paper_flute || {};
            
            return {
              id: trim.id,
              flute_id: trim.flute_id,
              flute_code: flute.code || 'N/A',
              flute_name: flute.name || 'N/A',
              length_add: trim.length_add,
              length_less: trim.length_less,
              compute: trim.compute === true || trim.compute === 1
            };
          });
          
          // Sort by flute code and then by compute status
          sideTrims.value.sort((a, b) => {
            if (a.flute_code === b.flute_code) {
              return a.compute === b.compute ? 0 : a.compute ? 1 : -1;
            }
            return a.flute_code.localeCompare(b.flute_code);
          });
          
          // Initialize filtered data
          filteredSideTrims.value = [...sideTrims.value];
        } else {
          sideTrims.value = [];
          filteredSideTrims.value = [];
          showNotification('No side trim data found.', 'info');
        }
      } catch (error) {
        console.error('Error loading data:', error);
        
        let errorMessage = 'Failed to load side trim data';
        
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
        const matchesFlute = !filter.value.fluteId || trim.flute_id === filter.value.fluteId;
        const matchesCompute = filter.value.compute === '' || 
          (filter.value.compute === 'true' && trim.compute) || 
          (filter.value.compute === 'false' && !trim.compute);
        
        return matchesFlute && matchesCompute;
      });
    };

    const resetFilters = () => {
      filter.value = {
        fluteId: '',
        compute: ''
      };
      filteredSideTrims.value = [...sideTrims.value];
    };

    const printAsExcel = () => {
      showNotification('Exporting data...');
      
      // Call the export API endpoint
      axios.get('/api/side-trims-by-flute/export', { responseType: 'blob' })
        .then(response => {
          // Create a download link for the exported file
          const blob = new Blob([response.data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
          const link = document.createElement('a');
          link.href = window.URL.createObjectURL(blob);
          link.download = `side_trims_by_flute_${new Date().toISOString().split('T')[0]}.xlsx`;
          link.click();
          
          showNotification('Data exported successfully');
        })
        .catch(error => {
          console.error('Error exporting data:', error);
          showNotification('Failed to export data', 'error');
        });
        
      printDropdownOpen.value = false;
    };

    const generatePdf = () => {
      const doc = new jsPDF();
      
      doc.setFontSize(18);
      doc.setFont('helvetica', 'bold');
      doc.text('Side Trim By Flute Report', 15, 22);

      doc.setFontSize(11);
      doc.setFont('helvetica', 'normal');
      doc.text(`Generated on: ${formattedDate.value}`, 15, 28);
      
      const tableData = filteredSideTrims.value.map(trim => [
        trim.flute_code,
        trim.flute_name,
        trim.compute ? 'Yes' : 'No',
        trim.length_add,
        trim.length_less
      ]);

      autoTable(doc, {
        head: [['Flute', 'Flute Name', 'Compute', 'Length Add (mm)', 'Length Less (mm)']],
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
          1: { cellWidth: 'auto', halign: 'left' },
          2: { halign: 'center' },
          3: { halign: 'center' },
          4: { halign: 'center' }
        }
      });
      
      doc.output('dataurlnewwindow');
    };

    const printAsPdf = () => {
      generatePdf();
      printDropdownOpen.value = false;
    };

    const handleClickOutside = (event) => {
      if (printDropdownContainer.value && !printDropdownContainer.value.contains(event.target)) {
        printDropdownOpen.value = false;
      }
    };

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
      flutes,
      filter,
      notification,
      printDropdownOpen,
      printDropdownContainer,
      formattedDate,
      filterData,
      resetFilters,
      printAsPdf,
      printAsExcel,
      showNotification
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

/* Scrollbar styling */
.overflow-x-auto {
  scrollbar-width: thin;
  scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
}
.overflow-x-auto::-webkit-scrollbar {
  height: 8px;
}
.overflow-x-auto::-webkit-scrollbar-track {
  background: transparent;
}
.overflow-x-auto::-webkit-scrollbar-thumb {
  background-color: rgba(156, 163, 175, 0.5);
  border-radius: 20px;
}
</style> 