<template>
  <AppLayout :header="'View & Print Scoring Formula'">
    <Head title="View & Print Scoring Formula" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-print mr-3"></i> View & Print Scoring Formula
      </h2>
      <p class="text-cyan-100">View and print scoring formula configurations for manufacturing</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
      <!-- Action Buttons -->
      <div class="flex mb-6 space-x-2">
        <div class="relative" ref="printDropdownContainer">
          <button @click="printDropdownOpen = !printDropdownOpen" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded shadow flex items-center">
            <i class="fas fa-print mr-2"></i> Print
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
            <div v-if="printDropdownOpen" class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg z-20 border border-gray-200">
              <a @click.prevent="exportAsPdf" href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                <i class="fas fa-file-pdf mr-2 text-red-500"></i> Export as PDF
              </a>
              <a @click.prevent="printBrowser" href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                <i class="fas fa-print mr-2 text-blue-500"></i> Browser Print
              </a>
            </div>
          </transition>
        </div>
        <button class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded shadow flex items-center" @click="fetchFormulaData">
          <i class="fas fa-sync-alt mr-2"></i> Refresh
        </button>
        <Link :href="defineRoute" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow flex items-center">
          <i class="fas fa-arrow-left mr-2"></i> Back to Define
        </Link>
      </div>

      <!-- Loading indicator -->
      <div v-if="loading" class="flex justify-center items-center py-8">
        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
        <span class="ml-3 text-gray-600">Loading formula data...</span>
      </div>

      <!-- No Data Message -->
      <div v-else-if="!formulas.length" class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
        <i class="fas fa-exclamation-triangle text-yellow-500 text-3xl mb-3"></i>
        <h3 class="text-lg font-medium text-yellow-800 mb-2">No Scoring Formula Data Found</h3>
        <p class="text-yellow-700">Please create scoring formulas in the Define Scoring Formula section first.</p>
      </div>

      <!-- Main Content -->
      <div v-else id="printable-content" class="border border-gray-300 rounded-lg bg-gray-100 p-4">
        <!-- Formula Header -->
        <div class="bg-white p-3 rounded-t-lg border border-gray-300 mb-1 flex justify-between items-center">
          <h3 class="text-lg font-bold text-gray-800">View & Print Scoring Formula</h3>
          <div class="flex items-center">
            <div class="w-6 h-6 rounded-full bg-red-500 mr-2"></div>
            <div class="w-6 h-6 rounded-full bg-gray-300"></div>
          </div>
        </div>

        <!-- Search and Filter -->
        <div class="bg-white p-4 border border-gray-300 mb-4">
          <div class="flex flex-wrap gap-4">
            <div class="flex-grow">
              <label class="block text-sm font-medium text-gray-700 mb-1">Search:</label>
              <input 
                type="text" 
                v-model="search" 
                placeholder="Search by product design or flute code..."
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
            <div class="w-48">
              <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Status:</label>
              <select 
                v-model="statusFilter"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="all">All</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Formula Content -->
        <div class="bg-white border border-gray-300 rounded-b-lg p-4">
          <!-- Tables Layout (2 columns) -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Left Column: Product Design & Paper Flute -->
            <div>
              <div class="mb-2 font-bold text-gray-700">Product Design</div>
              <div class="relative max-h-64 overflow-auto border border-gray-300 rounded mb-4">
                <table class="w-full text-sm">
                  <thead class="bg-gray-200 sticky top-0">
                    <tr>
                      <th class="text-left py-1 px-2">Product Design</th>
                      <th class="text-left py-1 px-2">Paper Flute</th>
                      <th class="text-left py-1 px-2">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-if="filteredFormulas.length === 0">
                      <td colspan="3" class="py-4 text-center text-gray-500">No formulas found</td>
                    </tr>
                    <tr v-for="formula in filteredFormulas" :key="formula.id" 
                        class="hover:bg-blue-100 cursor-pointer"
                        :class="{ 'bg-blue-100': selectedFormula && selectedFormula.id === formula.id }"
                        @click="selectFormula(formula)">
                      <td class="py-1 px-2 border-b">{{ formula.product_design?.pd_code || 'N/A' }}</td>
                      <td class="py-1 px-2 border-b">{{ formula.paper_flute?.code || 'N/A' }}</td>
                      <td class="py-1 px-2 border-b">
                        <span :class="formula.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'" 
                              class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium">
                          {{ formula.is_active ? 'Active' : 'Inactive' }}
                        </span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Right Column: Scoring Length/Width -->
            <div v-if="selectedFormula">
              <!-- Scoring Length -->
              <div class="mb-2 font-bold text-gray-700">Scoring Length/Base</div>
              <div class="relative max-h-28 overflow-auto border border-gray-300 rounded mb-4">
                <table class="w-full text-sm">
                  <thead class="bg-gray-200 sticky top-0">
                    <tr>
                      <th class="text-center py-1 px-2 w-10">#</th>
                      <th class="text-left py-1 px-2">Value</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-if="!parsedLengthFormula || parsedLengthFormula.length === 0">
                      <td colspan="2" class="py-4 text-center text-gray-500">No length formula data</td>
                    </tr>
                    <tr v-for="(item, index) in parsedLengthFormula" :key="index" class="hover:bg-blue-100">
                      <td class="py-1 px-2 border-b text-center">{{ item.index }}</td>
                      <td class="py-1 px-2 border-b">{{ item.value || '' }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <!-- Scoring Width -->
              <div class="mb-2 font-bold text-gray-700">Scoring Width/Base</div>
              <div class="relative max-h-28 overflow-auto border border-gray-300 rounded mb-4">
                <table class="w-full text-sm">
                  <thead class="bg-gray-200 sticky top-0">
                    <tr>
                      <th class="text-center py-1 px-2 w-10">#</th>
                      <th class="text-left py-1 px-2">Value</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-if="!parsedWidthFormula || parsedWidthFormula.length === 0">
                      <td colspan="2" class="py-4 text-center text-gray-500">No width formula data</td>
                    </tr>
                    <tr v-for="(item, index) in parsedWidthFormula" :key="index" class="hover:bg-blue-100">
                      <td class="py-1 px-2 border-b text-center">{{ item.index }}</td>
                      <td class="py-1 px-2 border-b">{{ item.value || '' }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div v-else class="flex items-center justify-center h-full">
              <p class="text-gray-500">Select a formula to view details</p>
            </div>
          </div>

          <!-- Dimension Conversion Formula -->
          <div v-if="selectedFormula" class="mt-6">
            <div class="mb-2 font-bold text-gray-700">— Dimension Conversion Formula —</div>
            <div class="bg-gray-50 border border-gray-300 rounded p-3 text-sm">
              <p class="mb-2">To convert from ordered to internal dimensions or vice-versa</p>
              <div class="flex items-center space-x-2 mb-1 text-gray-800">
                <span class="font-bold">Length:</span>
                <span class="px-2 py-1 bg-white border border-gray-300 rounded">{{ selectedFormula.length_conversion }}</span>
                <span>mm</span>
              </div>
              <div class="flex items-center space-x-2 mb-1 text-gray-800">
                <span class="font-bold">Width:</span>
                <span class="px-2 py-1 bg-white border border-gray-300 rounded">{{ selectedFormula.width_conversion }}</span>
                <span>mm</span>
              </div>
              <div class="flex items-center space-x-2 text-gray-800">
                <span class="font-bold">Height:</span>
                <span class="px-2 py-1 bg-white border border-gray-300 rounded">{{ selectedFormula.height_conversion }}</span>
                <span>mm</span>
              </div>
            </div>
          </div>

          <!-- Scoring Length & Width Formula -->
          <div v-if="selectedFormula" class="mt-6">
            <div class="mb-2 font-bold text-gray-700">Scoring Length & Width Formula</div>
            <div class="bg-gray-50 border border-gray-300 rounded p-3 text-sm">
              <div class="flex items-center">
                <span class="mr-3">Calculate using:</span>
                <div class="flex items-center space-x-4">
                  <label class="flex items-center">
                    <input type="radio" name="calculation" class="mr-1" checked>
                    <span>Internal Measurement</span>
                  </label>
                  <label class="flex items-center">
                    <input type="radio" name="calculation" class="mr-1">
                    <span>External Measurement</span>
                  </label>
                </div>
              </div>
              <div class="mt-2 text-gray-600" v-if="selectedFormula.notes">
                <p class="font-bold">Notes:</p>
                <p>{{ selectedFormula.notes }}</p>
              </div>
            </div>
          </div>
          
          <!-- Created/Updated Info -->
          <div v-if="selectedFormula" class="mt-6 text-xs text-gray-500 text-right">
            <p v-if="selectedFormula.created_at">Created: {{ formatDate(selectedFormula.created_at) }}</p>
            <p v-if="selectedFormula.updated_at">Last Updated: {{ formatDate(selectedFormula.updated_at) }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Loading Overlay -->
    <div v-if="loading" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex justify-center items-center">
      <div class="bg-white p-5 rounded-lg shadow-lg flex items-center">
        <div class="w-10 h-10 border-4 border-blue-500 border-t-transparent rounded-full animate-spin mr-3"></div>
        <p class="text-gray-700 font-medium">Loading data...</p>
      </div>
    </div>
    
    <!-- Notification Toast -->
    <div v-if="notification.show" class="fixed bottom-4 right-4 z-50 shadow-xl rounded-lg transition-all duration-300"
        :class="{
            'bg-green-100 border-l-4 border-green-500': notification.type === 'success',
            'bg-red-100 border-l-4 border-red-500': notification.type === 'error',
            'bg-yellow-100 border-l-4 border-yellow-500': notification.type === 'warning'
        }">
      <div class="p-4 flex items-center">
        <div class="mr-3">
          <i v-if="notification.type === 'success'" class="fas fa-check-circle text-green-500 text-xl"></i>
          <i v-else-if="notification.type === 'error'" class="fas fa-exclamation-circle text-red-500 text-xl"></i>
          <i v-else class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
        </div>
        <div>
          <p :class="{
              'text-green-800': notification.type === 'success',
              'text-red-800': notification.type === 'error',
              'text-yellow-800': notification.type === 'warning'
          }">{{ notification.message }}</p>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';
import 'jspdf-autotable';

// State variables
const loading = ref(false);
const notification = ref({ show: false, message: '', type: 'success' });
const formulas = ref([]);
const selectedFormula = ref(null);
const printDropdownOpen = ref(false);
const printDropdownContainer = ref(null);
const search = ref('');
const statusFilter = ref('all');
const defineRoute = '/sales-management/standard-formula/setup-scoring-formula/scoring-formula';

// Computed properties
const filteredFormulas = computed(() => {
  let result = formulas.value;
  
  // Apply search filter
  if (search.value.trim() !== '') {
    const searchLower = search.value.toLowerCase();
    result = result.filter(formula => 
      (formula.product_design?.pd_code?.toLowerCase().includes(searchLower)) || 
      (formula.paper_flute?.code?.toLowerCase().includes(searchLower))
    );
  }
  
  // Apply status filter
  if (statusFilter.value !== 'all') {
    const isActive = statusFilter.value === 'active';
    result = result.filter(formula => formula.is_active === isActive);
  }
  
  return result;
});

const parsedLengthFormula = computed(() => {
  if (!selectedFormula.value || !selectedFormula.value.scoring_length_formula) return [];
  
  try {
    // Handle if it's already an array or if it's a JSON string
    if (Array.isArray(selectedFormula.value.scoring_length_formula)) {
      return selectedFormula.value.scoring_length_formula;
    } else {
      return JSON.parse(selectedFormula.value.scoring_length_formula);
    }
  } catch (e) {
    console.error('Error parsing scoring_length_formula:', e);
    return [];
  }
});

const parsedWidthFormula = computed(() => {
  if (!selectedFormula.value || !selectedFormula.value.scoring_width_formula) return [];
  
  try {
    // Handle if it's already an array or if it's a JSON string
    if (Array.isArray(selectedFormula.value.scoring_width_formula)) {
      return selectedFormula.value.scoring_width_formula;
    } else {
      return JSON.parse(selectedFormula.value.scoring_width_formula);
    }
  } catch (e) {
    console.error('Error parsing scoring_width_formula:', e);
    return [];
  }
});

// Format date helper
const formatDate = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('en-US', {
    year: 'numeric',
    month: 'short',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit'
  }).format(date);
};

// Formatted current date
const formattedDate = () => {
  const now = new Date();
  return new Intl.DateTimeFormat('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }).format(now);
};

// Fetch formula data from API
const fetchFormulaData = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/scoring-formulas');
    
    console.log('API Response:', response.data);
    
    if (response.data && response.data.status === 'success') {
      // Handle if the API returns { status: 'success', data: [...] }
      formulas.value = response.data.data;
    } else if (Array.isArray(response.data)) {
      // Handle if the API directly returns an array
      formulas.value = response.data;
    } else {
      console.error('Unexpected data format:', response.data);
      formulas.value = [];
      showNotification('Error loading formula data: Invalid data format', 'error');
      return;
    }
    
    // If we have formulas, select the first one by default
    if (formulas.value.length > 0) {
      selectFormula(formulas.value[0]);
    } else {
      selectedFormula.value = null;
    }
    
    showNotification('Formula data loaded successfully', 'success');
  } catch (error) {
    console.error('Error fetching formula data:', error);
    formulas.value = [];
    selectedFormula.value = null;
    
    let errorMessage = 'Error loading formula data';
    if (error.response) {
      errorMessage += `: ${error.response.status} ${error.response.statusText}`;
    } else if (error.request) {
      errorMessage += ': No response from server';
    } else {
      errorMessage += `: ${error.message}`;
    }
    
    showNotification(errorMessage, 'error');
  } finally {
    loading.value = false;
  }
};

// Select a formula to display details
const selectFormula = (formula) => {
  console.log('Selected formula:', formula);
  selectedFormula.value = formula;
};

// Original browser print functionality
const printBrowser = () => {
  if (!selectedFormula.value) {
    showNotification('Please select a formula to print', 'warning');
    return;
  }
  
  const printContent = document.getElementById('printable-content');
  const originalContent = document.body.innerHTML;
  
  document.body.innerHTML = `
    <div style="padding: 20px;">
      <h1 style="text-align: center; margin-bottom: 20px;">Scoring Formula: ${selectedFormula.value.product_design?.pd_code || 'N/A'} - ${selectedFormula.value.paper_flute?.code || 'N/A'}</h1>
      ${printContent.innerHTML}
    </div>
  `;
  
  window.print();
  document.body.innerHTML = originalContent;
  
  // Re-initialize the Vue app after printing
  location.reload();
};

// New PDF export functionality
const exportAsPdf = () => {
  if (!selectedFormula.value) {
    showNotification('Please select a formula to print', 'warning');
    return;
  }
  
  try {
    const doc = new jsPDF();
    
    // Add document title and date
    doc.setFontSize(18);
    doc.setFont('helvetica', 'bold');
    doc.text('Scoring Formula Report', 15, 22);
    
    doc.setFontSize(14);
    doc.text(`${selectedFormula.value.product_design?.pd_code || 'N/A'} - ${selectedFormula.value.paper_flute?.code || 'N/A'}`, 15, 30);

    doc.setFontSize(11);
    doc.setFont('helvetica', 'normal');
    doc.text(`Generated on: ${formattedDate()}`, 15, 38);
    
    // Scoring Length Formula Table
    if (parsedLengthFormula.value && parsedLengthFormula.value.length > 0) {
      doc.setFontSize(12);
      doc.setFont('helvetica', 'bold');
      doc.text('Scoring Length Formula', 15, 48);
      
      const lengthData = parsedLengthFormula.value.map(item => [
        item.index !== null && item.index !== undefined ? item.index.toString() : '-',
        item.value !== null && item.value !== undefined ? item.value.toString() : '-'
      ]);
      
      autoTable(doc, {
        head: [['#', 'Value']],
        body: lengthData,
        startY: 50,
        theme: 'grid',
        styles: {
          fontSize: 10,
          cellPadding: 3,
        },
        headStyles: {
          fillColor: [41, 128, 185],
          textColor: 255,
          fontStyle: 'bold',
        },
        columnStyles: {
          0: { cellWidth: 20, halign: 'center' },
          1: { cellWidth: 'auto' },
        }
      });
    }
    
    // Scoring Width Formula Table
    if (parsedWidthFormula.value && parsedWidthFormula.value.length > 0) {
      const finalY = doc.lastAutoTable ? doc.lastAutoTable.finalY + 10 : 50;
      
      doc.setFontSize(12);
      doc.setFont('helvetica', 'bold');
      doc.text('Scoring Width Formula', 15, finalY);
      
      const widthData = parsedWidthFormula.value.map(item => [
        item.index !== null && item.index !== undefined ? item.index.toString() : '-',
        item.value !== null && item.value !== undefined ? item.value.toString() : '-'
      ]);
      
      autoTable(doc, {
        head: [['#', 'Value']],
        body: widthData,
        startY: finalY + 2,
        theme: 'grid',
        styles: {
          fontSize: 10,
          cellPadding: 3,
        },
        headStyles: {
          fillColor: [41, 128, 185],
          textColor: 255,
          fontStyle: 'bold',
        },
        columnStyles: {
          0: { cellWidth: 20, halign: 'center' },
          1: { cellWidth: 'auto' },
        }
      });
    }
    
    // Dimension Conversion Table
    const finalY = doc.lastAutoTable ? doc.lastAutoTable.finalY + 15 : 120;
    
    doc.setFontSize(12);
    doc.setFont('helvetica', 'bold');
    doc.text('Dimension Conversion Formula', 15, finalY);
    doc.setFontSize(10);
    doc.setFont('helvetica', 'normal');
    doc.text('To convert from ordered to internal dimensions or vice-versa', 15, finalY + 6);
    
    const conversionData = [
      ['Length', `${selectedFormula.value.length_conversion || 0} mm`],
      ['Width', `${selectedFormula.value.width_conversion || 0} mm`],
      ['Height', `${selectedFormula.value.height_conversion || 0} mm`],
    ];
    
    autoTable(doc, {
      head: [['Dimension', 'Conversion']],
      body: conversionData,
      startY: finalY + 8,
      theme: 'grid',
      styles: {
        fontSize: 10,
        cellPadding: 3,
      },
      headStyles: {
        fillColor: [41, 128, 185],
        textColor: 255,
        fontStyle: 'bold',
      },
      columnStyles: {
        0: { cellWidth: 40 },
        1: { cellWidth: 'auto' },
      }
    });
    
    // Notes section
    if (selectedFormula.value.notes) {
      const notesY = doc.lastAutoTable.finalY + 15;
      
      doc.setFontSize(12);
      doc.setFont('helvetica', 'bold');
      doc.text('Notes:', 15, notesY);
      
      doc.setFontSize(10);
      doc.setFont('helvetica', 'normal');
      const splitNotes = doc.splitTextToSize(selectedFormula.value.notes, 180);
      doc.text(splitNotes, 15, notesY + 6);
    }
    
    // Open PDF in a new tab
    doc.output('dataurlnewwindow');
    showNotification('PDF generated successfully', 'success');
  } catch (error) {
    console.error('Error generating PDF:', error);
    showNotification('Failed to generate PDF: ' + error.message, 'error');
  } finally {
    printDropdownOpen.value = false;
  }
};

// Show notification toast
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

const handleClickOutside = (event) => {
  if (printDropdownContainer.value && !printDropdownContainer.value.contains(event.target)) {
    printDropdownOpen.value = false;
  }
};

// Load data on component mount
onMounted(() => {
  fetchFormulaData();
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');

@media print {
  body {
    margin: 0;
    padding: 0;
    background: white;
  }
  
  button, .no-print {
    display: none !important;
  }
  
  #printable-content {
    width: 100%;
    padding: 0;
    margin: 0;
    background: white;
    border: none;
  }
}
</style>
