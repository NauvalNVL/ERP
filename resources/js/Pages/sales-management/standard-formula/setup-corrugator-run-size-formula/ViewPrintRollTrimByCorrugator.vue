<template>
  <AppLayout title="View & Print Roll Trim By Corrugator">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        View & Print Roll Trim By Corrugator
      </h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
          <!-- Header with buttons -->
          <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 flex items-center justify-between">
            <h2 class="text-lg font-bold text-white">View & Print Roll Trim By Corrugator</h2>
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
              <Link
                :href="setupRollTrimByCorRoute"
                class="bg-gray-600 hover:bg-gray-500 text-white px-3 py-1 rounded text-sm flex items-center"
              >
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
              <!-- Filter section -->
              <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                <div class="flex flex-wrap gap-4 items-end">
                  <!-- Corrugator Filter -->
                  <div class="flex-grow md:flex-grow-0 md:w-48">
                    <label for="corrugator-filter" class="block text-sm font-medium text-gray-700 mb-1">Corrugator</label>
                    <select
                      id="corrugator-filter"
                      v-model="filters.corrugator"
                      @change="applyFilters"
                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    >
                      <option value="">All Corrugators</option>
                      <option v-for="corrugator in corrugators" :key="corrugator" :value="corrugator">
                        {{ corrugator }}
                      </option>
                    </select>
                  </div>
                  
                  <!-- Flute Filter -->
                  <div class="flex-grow md:flex-grow-0 md:w-48">
                    <label for="flute-filter" class="block text-sm font-medium text-gray-700 mb-1">Flute Type</label>
                    <select
                      id="flute-filter"
                      v-model="filters.fluteCode"
                      @change="applyFilters"
                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    >
                      <option value="">All Flutes</option>
                      <option v-for="flute in flutes" :key="flute.code" :value="flute.code">
                        {{ flute.name }} ({{ flute.code }})
                      </option>
                    </select>
                  </div>
                  
                  <!-- Reset Button -->
                  <div>
                    <button 
                      @click="resetFilters" 
                      class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                      </svg>
                      Reset
                    </button>
                  </div>
                </div>
              </div>

              <!-- Table section -->
              <div id="printable-content" class="overflow-x-auto">
                <div class="mb-6 print:mb-8 hidden print:block">
                  <h1 class="text-2xl font-bold text-center print:text-3xl">Roll Trim By Corrugator Report</h1>
                  <p class="text-center text-gray-600 print:text-lg">Generated on {{ formattedDate }}</p>
                </div>

                <table class="min-w-full divide-y divide-gray-200 border">
                  <thead class="bg-gray-100">
                    <tr>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        Flute Code
                      </th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        Flute Name
                      </th>
                      <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        To Compute
                      </th>
                      <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        Min Trim (mm)
                      </th>
                      <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Max Trim (mm)
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="filteredRollTrims.length === 0" class="hover:bg-gray-50">
                      <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500">
                        No roll trim data found matching your filters.
                      </td>
                    </tr>
                    <tr v-for="trim in filteredRollTrims" :key="trim.id" class="hover:bg-gray-50">
                      <td class="px-4 py-2 text-sm font-medium text-gray-900 border-r">
                        {{ trim.flute_code }}
                      </td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">
                        {{ trim.flute_name || 'N/A' }}
                      </td>
                      <td class="px-4 py-2 text-center border-r">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                          :class="trim.compute ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'">
                          {{ trim.compute ? 'Yes' : 'No' }}
                        </span>
                      </td>
                      <td class="px-4 py-2 text-center border-r">
                        {{ trim.min_trim }}
                      </td>
                      <td class="px-4 py-2 text-center">
                        {{ trim.max_trim }}
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
    // Data
    const loading = ref(true);
    const rollTrims = ref([]);
    const flutes = ref([]);
    const corrugators = ['BHS', 'FOSBER', 'MHI'];
    const setupRollTrimByCorRoute = '/standard-formula/setup-roll-trim-by-corrugator';
    const printDropdownOpen = ref(false);
    const printDropdownContainer = ref(null);
    
    // Filters
    const filters = ref({
      corrugator: '',
      fluteCode: ''
    });
    
    // Notification
    const notification = ref({
      show: false,
      message: '',
      type: 'success'
    });

    // Computed
    const filteredRollTrims = computed(() => {
      let filtered = [...rollTrims.value];
      
      if (filters.value.corrugator) {
        filtered = filtered.filter(trim => trim.corrugator_name === filters.value.corrugator);
      }
      
      if (filters.value.fluteCode) {
        filtered = filtered.filter(trim => trim.flute_code === filters.value.fluteCode);
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

    // Methods
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

    const fetchData = async () => {
      try {
        loading.value = true;
        
        // Fetch roll trims
        const rollTrimsResponse = await axios.get('/api/roll-trim-by-corrugator');
        if (Array.isArray(rollTrimsResponse.data)) {
          rollTrims.value = rollTrimsResponse.data;
        } else {
          throw new Error('Received unexpected data format for roll trims.');
        }
        
        // Fetch flutes for filtering
        const flutesResponse = await axios.get('/api/paper-flutes');
        if (flutesResponse.data) {
          flutes.value = flutesResponse.data;
        } else {
          throw new Error('Failed to fetch flute data for filters');
        }
      } catch (err) {
        console.error('Error fetching data:', err);
        showNotification(err.message || 'An error occurred while fetching data', 'error');
      } finally {
        loading.value = false;
      }
    };

    const applyFilters = () => {
      // Filters are automatically applied through the computed property
    };

    const resetFilters = () => {
      filters.value = {
        corrugator: '',
        fluteCode: ''
      };
    };

    const exportToCsv = () => {
      try {
        axios({
          url: '/api/roll-trim-by-corrugator/export',
          method: 'GET',
          responseType: 'blob'
        }).then((response) => {
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'roll_trim_by_corrugator.csv');
          document.body.appendChild(link);
          link.click();
          link.remove();
          showNotification('Data exported successfully');
        });
      } catch (error) {
        console.error('Export error:', error);
        showNotification('Failed to export data', 'error');
      }
    };

    const generatePdf = () => {
      const doc = new jsPDF();
      
      doc.setFontSize(18);
      doc.setFont('helvetica', 'bold');
      doc.text('Roll Trim By Corrugator Report', 15, 22);

      doc.setFontSize(11);
      doc.setFont('helvetica', 'normal');
      doc.text(`Generated on: ${formattedDate.value}`, 15, 28);
      
      const tableData = filteredRollTrims.value.map(trim => [
        trim.flute_code,
        trim.flute_name || 'N/A',
        trim.compute ? 'Yes' : 'No',
        trim.min_trim,
        trim.max_trim,
      ]);

      autoTable(doc, {
        head: [['Flute Code', 'Flute Name', 'To Compute', 'Min Trim (mm)', 'Max Trim (mm)']],
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
            1: { cellWidth: '*' },
            2: { halign: 'center' },
            3: { halign: 'center' },
            4: { halign: 'center' },
        }
      });
      
      doc.output('dataurlnewwindow');
    };

    const printAsPdf = () => {
      generatePdf();
      printDropdownOpen.value = false;
    };

    const printAsCsv = () => {
      exportToCsv();
      printDropdownOpen.value = false;
    };

    const handleClickOutside = (event) => {
      if (printDropdownContainer.value && !printDropdownContainer.value.contains(event.target)) {
        printDropdownOpen.value = false;
      }
    };

    // Initialize
    onMounted(() => {
      fetchData();
      document.addEventListener('click', handleClickOutside);
    });

    onUnmounted(() => {
      document.removeEventListener('click', handleClickOutside);
    });

    return {
      loading,
      rollTrims,
      flutes,
      corrugators,
      filters,
      notification,
      filteredRollTrims,
      formattedDate,
      setupRollTrimByCorRoute,
      printDropdownOpen,
      printDropdownContainer,
      showNotification,
      applyFilters,
      resetFilters,
      printAsPdf,
      printAsCsv,
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