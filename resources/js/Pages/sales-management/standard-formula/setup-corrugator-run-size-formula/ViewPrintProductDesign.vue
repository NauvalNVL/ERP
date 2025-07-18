<template>
  <AppLayout :header="'View & Print Product Design'">
    <Head title="View & Print Product Design" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 sm:p-6 rounded-t-lg shadow-md">
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
          <h2 class="text-xl sm:text-2xl font-bold text-white mb-2 flex items-center">
            <i class="fas fa-drafting-compass mr-3"></i> View & Print Product Design
          </h2>
          <p class="text-blue-100 hidden sm:block">View and print product designs for your manufacturing process</p>
        </div>
        <div class="flex flex-wrap gap-2 w-full md:w-auto">
          <div class="relative" ref="printDropdownContainer">
            <button @click="printDropdownOpen = !printDropdownOpen" class="btn-action bg-blue-500 hover:bg-blue-400 flex-grow md:flex-grow-0 w-full justify-center">
              <i class="fas fa-print mr-2"></i>
              <span class="hidden sm:inline">Print</span>
              <i class="fas fa-chevron-down ml-2 transition-transform" :class="{'rotate-180': printDropdownOpen}"></i>
          </button>
            <transition name="fade">
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
          <Link :href="'/standard-formula/setup-corrugator-run-size-formula/product-design'" class="btn-action bg-indigo-600 hover:bg-indigo-500 flex-grow md:flex-grow-0">
            <i class="fas fa-arrow-left mr-2"></i>
            <span class="hidden sm:inline">Back</span>
          </Link>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-b-lg shadow-md p-4 sm:p-6 mb-6">
      <!-- Loading Spinner -->
      <div v-if="loading" class="flex justify-center items-center py-12">
        <div class="animate-spin rounded-full h-16 w-16 border-t-2 border-b-2 border-blue-500"></div>
        <span class="ml-3 text-lg text-gray-600">Loading product designs...</span>
      </div>

      <div v-else>
        <!-- Filters Section -->
        <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
          <h3 @click="showFilters = !showFilters" class="text-lg font-medium text-gray-700 mb-3 cursor-pointer md:cursor-default flex justify-between items-center">
            <span>Filters</span>
            <i class="fas md:hidden" :class="showFilters ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
          </h3>
          <div v-show="showFilters" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Product Design Code</label>
              <input type="text" v-model="filters.code" @input="applyFilters" 
                class="input-field"
                placeholder="Filter by code">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Product Design Name</label>
              <input type="text" v-model="filters.name" @input="applyFilters" 
                class="input-field"
                placeholder="Filter by name">
            </div>
            <div class="md:col-span-2 flex justify-end">
              <button @click="resetFilters" class="btn-secondary">
                <i class="fas fa-undo mr-2"></i> Reset Filters
              </button>
            </div>
          </div>
        </div>

        <!-- Table Section (Desktop View) -->
        <div class="hidden md:block bg-white rounded-lg overflow-hidden border border-gray-200">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th @click="sortBy('pd_code')" class="th-sortable">
                    Product Design <i :class="sortIcon('pd_code')"></i>
                  </th>
                  <th @click="sortBy('pd_name')" class="th-sortable">
                    Product Design Name <i :class="sortIcon('pd_name')"></i>
                  </th>
                  <th @click="sortBy('compute')" class="th-sortable">
                    To Compute <i :class="sortIcon('compute')"></i>
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-if="filteredDesigns.length === 0">
                  <td colspan="3" class="table-cell-info">
                    No product designs found matching your filters.
                  </td>
                </tr>
                <tr v-for="design in paginatedDesigns" :key="design.pd_code" 
                    @click="selectDesign(design)" 
                    :class="{'bg-blue-50': selectedDesign && selectedDesign.pd_code === design.pd_code}"
                    class="hover:bg-gray-50 cursor-pointer transition-colors">
                  <td class="table-cell-main">{{ design.pd_code }}</td>
                  <td class="table-cell">{{ design.pd_name }}</td>
                  <td class="table-cell">
                    <span :class="design.compute ? 'status-yes' : 'status-no'">
                      {{ design.compute ? 'Yes' : 'No' }}
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Card List Section (Mobile View) -->
        <div class="block md:hidden space-y-3">
          <div v-if="filteredDesigns.length === 0" class="text-center text-gray-500 py-8">
            No product designs found.
          </div>
          <div v-for="design in paginatedDesigns" :key="design.pd_code"
            @click="selectDesign(design)"
            class="bg-white p-4 rounded-lg shadow-md border-l-4 transition-all"
            :class="selectedDesign && selectedDesign.pd_code === design.pd_code ? 'border-blue-500 bg-blue-50' : 'border-gray-200'">
            <div class="flex justify-between items-center">
              <div>
                <p class="font-bold text-blue-800">{{ design.pd_code }}</p>
                <p class="text-gray-700 text-sm">{{ design.pd_name }}</p>
              </div>
              <div class="text-right">
                <p class="text-xs text-gray-500 mb-1">To Compute</p>
                <span :class="design.compute ? 'status-yes' : 'status-no'">
                  {{ design.compute ? 'Yes' : 'No' }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Pagination Controls -->
        <div class="mt-4 flex flex-col md:flex-row justify-between items-center text-sm text-gray-600 gap-4">
          <div>
            <span>Showing {{ paginatedDesigns.length }} of {{ filteredDesigns.length }} designs</span>
          </div>
          <div class="flex items-center space-x-2">
            <select v-model="itemsPerPage" class="input-field-sm">
              <option :value="10">10/page</option>
              <option :value="20">20/page</option>
              <option :value="50">50/page</option>
              <option :value="100">100/page</option>
            </select>
            <button :disabled="currentPage === 1" @click="currentPage--" class="pagination-btn">
              <i class="fas fa-chevron-left"></i>
            </button>
            <span class="px-2 sm:px-4">{{ currentPage }} / {{ totalPages }}</span>
            <button :disabled="currentPage === totalPages" @click="currentPage++" class="pagination-btn">
              <i class="fas fa-chevron-right"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Design Details Panel -->
      <div v-if="selectedDesign" class="mt-6 bg-gray-50 p-4 sm:p-6 rounded-lg border border-gray-200" id="details-panel">
        <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-4 flex items-center">
          <i class="fas fa-info-circle mr-2 text-blue-500"></i> Design Details: {{ selectedDesign.pd_code }}
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div class="detail-group">
            <h4 class="detail-header">Basic Information</h4>
            <div class="space-y-3">
              <div class="detail-item"><span>Design Code:</span><strong>{{ selectedDesign.pd_code }}</strong></div>
              <div class="detail-item"><span>Design Name:</span><strong>{{ selectedDesign.pd_name }}</strong></div>
              <div class="detail-item"><span>Design Type:</span><strong>{{ selectedDesign.pd_design_type }}</strong></div>
              <div class="detail-item"><span>IDC:</span><strong>{{ selectedDesign.idc || 'N/A' }}</strong></div>
              <div class="detail-item"><span>Product:</span><strong class="badge-blue">{{ selectedDesign.product }}</strong></div>
            </div>
          </div>
          <div class="detail-group">
            <h4 class="detail-header">Joint Properties</h4>
            <div class="space-y-3">
              <div class="detail-item">
                <span>Joint:</span><strong :class="selectedDesign.joint === 'Yes' ? 'status-yes' : 'status-no'">{{ selectedDesign.joint }}</strong>
              </div>
              <div class="detail-item">
                <span>Joint to Print:</span><strong :class="selectedDesign.joint_to_print === 'Yes' ? 'status-yes' : 'status-no'">{{ selectedDesign.joint_to_print }}</strong>
              </div>
              <div class="detail-item"><span>PCS to Joint:</span><strong>{{ selectedDesign.pcs_to_joint }}</strong></div>
            </div>
          </div>
          <div class="detail-group">
            <h4 class="detail-header">Manufacturing Properties</h4>
            <div class="space-y-3">
              <div class="detail-item">
                <span>Score:</span><strong :class="selectedDesign.score === 'Yes' ? 'status-yes' : 'status-no'">{{ selectedDesign.score }}</strong>
              </div>
              <div class="detail-item">
                <span>Slot:</span><strong :class="selectedDesign.slot === 'Yes' ? 'status-yes' : 'status-no'">{{ selectedDesign.slot }}</strong>
              </div>
              <div class="detail-item"><span>Flute Style:</span><strong>{{ selectedDesign.flute_style }}</strong></div>
              <div class="detail-item">
                <span>Print Flute:</span><strong :class="selectedDesign.print_flute === 'Yes' ? 'status-yes' : 'status-no'">{{ selectedDesign.print_flute }}</strong>
              </div>
              <div class="detail-item">
                <span>Input Weight:</span><strong :class="selectedDesign.input_weight === 'Yes' ? 'status-yes' : 'status-no'">{{ selectedDesign.input_weight }}</strong>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch, nextTick, onUnmounted } from 'vue';
import axios from 'axios';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';

// State variables
const designs = ref([]);
const selectedDesign = ref(null);
const loading = ref(true);
const printDropdownOpen = ref(false);
const printDropdownContainer = ref(null);
const sortOrder = ref({
  field: 'pd_code',
  direction: 'asc'
});
const currentPage = ref(1);
const itemsPerPage = ref(10);
const filters = ref({
  code: '',
  name: '',
});
const showFilters = ref(true);

// Computed properties
const productOptions = computed(() => {
  const products = new Set();
  designs.value.forEach(design => {
    if (design.product) {
      products.add(design.product);
    }
  });
  return Array.from(products).sort();
});

const filteredDesigns = computed(() => {
  let result = designs.value;

  // Apply filters
  if (filters.value.code) {
    result = result.filter(design => 
      design.pd_code && design.pd_code.toLowerCase().includes(filters.value.code.toLowerCase())
    );
  }
  
  if (filters.value.name) {
    result = result.filter(design => 
      design.pd_name && design.pd_name.toLowerCase().includes(filters.value.name.toLowerCase())
    );
  }

  // Apply sorting
  result = [...result].sort((a, b) => {
    const field = sortOrder.value.field;
    const direction = sortOrder.value.direction === 'asc' ? 1 : -1;
    
    if (field === 'compute') {
        return (a.compute === b.compute) ? 0 : a.compute ? -1 * direction : 1 * direction;
    }
    
    if ((a[field] || '') < (b[field] || '')) return -1 * direction;
    if ((a[field] || '') > (b[field] || '')) return 1 * direction;
    return 0;
  });

  return result;
});

// Pagination computed properties
const paginatedDesigns = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return filteredDesigns.value.slice(start, end);
});

const totalPages = computed(() => {
  return Math.max(1, Math.ceil(filteredDesigns.value.length / itemsPerPage.value));
});

// Watch for changes in filtered designs to update pagination
watch(filteredDesigns, () => {
  currentPage.value = 1;
});

// Watch for changes in items per page
watch(itemsPerPage, () => {
  currentPage.value = 1;
});

// Function to fetch designs from API
const fetchDesigns = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/product-designs');
    designs.value = response.data.map(d => ({...d, compute: !!d.compute}));
  } catch (error) {
    console.error('Error fetching designs:', error);
    // You might want to use a toast notification here
  } finally {
    loading.value = false;
  }
};

// Function to select a design
const selectDesign = async (design) => {
  selectedDesign.value = design;
  // On mobile, scroll to the details panel
  await nextTick();
  if (window.innerWidth < 768) {
    const detailsPanel = document.getElementById('details-panel');
    if (detailsPanel) {
      detailsPanel.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  }
};

// Function to sort table by a given field
const sortBy = (field) => {
  if (sortOrder.value.field === field) {
    // Toggle direction if same field
    sortOrder.value.direction = sortOrder.value.direction === 'asc' ? 'desc' : 'asc';
  } else {
    // Change field and reset direction
    sortOrder.value.field = field;
    sortOrder.value.direction = 'asc';
  }
};

// Function to apply filters
const applyFilters = () => {
  // This function is called when filters change
  // The filtering is handled by the computed property
};

// Function to reset filters
const resetFilters = () => {
  filters.value = {
    code: '',
    name: '',
  };
};

// Function to handle print options
const printAsPdf = () => {
  printDesigns();
  printDropdownOpen.value = false;
};

const printAsCsv = () => {
  exportToCsv();
  printDropdownOpen.value = false;
};

// Function to export data to CSV
const exportToCsv = () => {
  try {
    // Create CSV headers
    const headers = ['Product Design', 'Product Design Name', 'To Compute'];
    
    // Convert data to CSV format
    const csvContent = [
      headers.join(','),
      ...filteredDesigns.value.map(design => [
        design.pd_code || '',
        design.pd_name || '',
        design.compute ? 'Yes' : 'No'
      ].join(','))
    ].join('\n');
    
    // Create a blob and download link
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = `product_designs_${new Date().toISOString().split('T')[0]}.csv`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  } catch (error) {
    alert('Failed to export product designs to CSV.');
    console.error('Error exporting designs to CSV:', error);
  }
};

// Function to print designs
const printDesigns = () => {
  const doc = new jsPDF();
  
  // Set document properties
  doc.setProperties({
    title: 'Product Design List',
    subject: 'List of all available product designs',
    author: 'ERP System',
  });

  // Header
  doc.setFontSize(18);
  doc.setFont('helvetica', 'bold');
  doc.text('Product Design List', 15, 22);

  // Date
  doc.setFontSize(11);
  doc.setFont('helvetica', 'normal');
  doc.text(`Date: ${new Date().toLocaleDateString()}`, 15, 28);
  
  const tableData = filteredDesigns.value.map(design => [
    design.pd_code,
    design.pd_name,
    design.compute ? 'Yes' : 'No',
  ]);

  autoTable(doc, {
    head: [['Product Design', 'Product Design Name', 'To Compute']],
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
        2: { cellWidth: 'wrap', halign: 'center' },
    }
  });

  // Open PDF in a new window for preview and print
  doc.output('dataurlnewwindow');
};

const sortIcon = (field) => {
  if (sortOrder.value.field !== field) return 'fas fa-sort';
  return sortOrder.value.direction === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down';
};

const handleClickOutside = (event) => {
  if (printDropdownContainer.value && !printDropdownContainer.value.contains(event.target)) {
    printDropdownOpen.value = false;
  }
};

// Load data when component is mounted
onMounted(() => {
  if (window.innerWidth < 768) {
    showFilters.value = false;
  }
  fetchDesigns();
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>

<style scoped>
.input-field {
  @apply block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm;
}
.input-field-sm {
  @apply border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs sm:text-sm;
}
.btn-action {
  @apply text-white px-3 py-2 sm:px-4 rounded-lg shadow flex items-center justify-center transition-colors;
}
.btn-secondary {
  @apply px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg shadow-sm transition;
}
.pagination-btn {
  @apply px-3 py-1 border rounded hover:bg-gray-200 disabled:opacity-50 disabled:cursor-not-allowed;
}

/* Table and Card Styles */
.th-sortable {
  @apply px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none;
}
.table-cell-info { @apply px-6 py-4 text-center text-sm text-gray-500; }
.table-cell { @apply px-4 py-4 whitespace-nowrap text-sm text-gray-600; }
.table-cell-main { @apply px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900; }

.status-yes { @apply text-green-600 font-medium; }
.status-no { @apply text-red-600 font-medium; }
.badge-blue { @apply px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800; }

/* Details Panel Styles */
.detail-group { @apply bg-white p-4 rounded-lg border; }
.detail-header { @apply text-sm font-medium text-gray-500 uppercase tracking-wider mb-3; }
.detail-item { @apply flex justify-between items-center border-b border-gray-200 py-2 text-sm; }
.detail-item span { @apply text-gray-600; }
.detail-item strong { @apply font-medium text-gray-900 text-right; }

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.2s ease, transform 0.2s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

@media print {
  body, #app {
    background-color: white;
  }
  .bg-gradient-to-r, .md\:hidden, .hidden.md\:block .overflow-x-auto, 
  .flex-wrap.gap-2, .mb-6, .mt-4, #details-panel, nav, header {
    display: none !important;
  }
  
  .hidden.md\:block {
    display: block !important;
    border: none !important;
    box-shadow: none !important;
  }
  
  table {
    width: 100%;
    border-collapse: collapse;
  }
  
  th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
  }
  
  thead {
    background-color: #f2f2f2;
  }
}
</style>
