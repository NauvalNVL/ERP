<template>
  <AppLayout title="View & Print Product Design">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        View & Print Product Design
      </h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
          <!-- Header with buttons -->
          <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 flex items-center justify-between">
            <h2 class="text-lg font-bold text-white">View & Print Product Design</h2>
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div class="relative flex-grow">
                    <input
                      type="text"
                      v-model="searchQuery"
                      placeholder="Search designs..."
                      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                      <i class="fas fa-search text-gray-400"></i>
                    </div>
                  </div>
                  <div class="relative w-full">
                    <select
                      v-model="productFilter"
                      class="block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none"
                    >
                      <option value="">All Products</option>
                      <option v-for="product in uniqueProducts" :key="product" :value="product">
                        {{ product }}
                      </option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                      <i class="fas fa-chevron-down"></i>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Report header for print -->
              <div class="hidden print:block mb-6">
                <div class="text-center">
                  <h1 class="text-2xl font-bold text-center print:text-3xl">Product Design Report</h1>
                  <p class="text-center text-gray-600 print:text-lg">Generated on {{ formattedDate }}</p>
                </div>
              </div>

              <!-- Table section -->
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border">
                  <thead class="bg-gray-100">
                    <tr>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        Product Design
                      </th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        Name
                      </th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        Product
                      </th>
                      <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        Compute
                      </th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Alternate Name
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="filteredDesigns.length === 0" class="hover:bg-gray-50">
                      <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500">
                        No product designs found.
                      </td>
                    </tr>
                    <tr v-for="design in filteredDesigns" :key="design.pd_code" class="hover:bg-gray-50">
                      <td class="px-4 py-2 text-sm font-medium text-gray-900 border-r">
                        {{ design.pd_code }}
                      </td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">
                        {{ design.pd_name }}
                      </td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">
                        {{ design.product }}
                      </td>
                      <td class="px-4 py-2 text-sm text-center text-gray-900 border-r">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                              :class="design.score === 'Yes' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'">
                          {{ design.score }}
                        </span>
                      </td>
                      <td class="px-4 py-2 text-sm text-gray-900">
                        {{ design.pd_alt_name || '-' }}
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

<script setup>
import { ref, onMounted, computed, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';
import jsPDF from 'jspdf';
import 'jspdf-autotable';

// State
const productDesigns = ref([]);
const searchQuery = ref('');
const productFilter = ref('');
const loading = ref(false);
const printDropdownOpen = ref(false);
const printDropdownContainer = ref(null);
const notification = ref({
  show: false,
  message: '',
  type: 'success'
});

const defineRoute = '/standard-formula/diecut-computation-method/product-design';

// Fetch data on component mount
onMounted(async () => {
  await fetchProductDesigns();
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});

// Fetch product designs from API
const fetchProductDesigns = async () => {
  try {
    loading.value = true;
    const response = await axios.get('/api/product-designs');
    if (Array.isArray(response.data)) {
      productDesigns.value = response.data;
    } else {
      productDesigns.value = [];
    }
  } catch (error) {
    showNotification('Failed to load product designs.', 'error');
  } finally {
    loading.value = false;
  }
};

const showNotification = (message, type = 'success') => {
  notification.value = { show: true, message, type };
  setTimeout(() => {
    notification.value.show = false;
  }, 3000);
};

// Get unique products for filtering
const uniqueProducts = computed(() => {
  const products = new Set(productDesigns.value.map(design => design.product).filter(Boolean));
  return Array.from(products).sort();
});

const formattedDate = computed(() => {
  return new Intl.DateTimeFormat('en-US', {
    year: 'numeric', month: 'long', day: 'numeric',
    hour: '2-digit', minute: '2-digit'
  }).format(new Date());
});

// Filter designs based on search query and product filter
const filteredDesigns = computed(() => {
  return productDesigns.value.filter(design => {
    const searchMatch = searchQuery.value ? 
      (design.pd_code?.toLowerCase().includes(searchQuery.value.toLowerCase())) || 
      (design.pd_name?.toLowerCase().includes(searchQuery.value.toLowerCase())) ||
      (design.product?.toLowerCase().includes(searchQuery.value.toLowerCase()))
      : true;
    const productMatch = productFilter.value ? design.product === productFilter.value : true;
    return searchMatch && productMatch;
  });
});

const printAsPdf = () => {
  const doc = new jsPDF();
  doc.setFontSize(18);
  doc.setFont('helvetica', 'bold');
  doc.text('Product Design Report', 15, 22);
  doc.setFontSize(11);
  doc.setFont('helvetica', 'normal');
  doc.text(`Generated on: ${formattedDate.value}`, 15, 28);
  
  const tableData = filteredDesigns.value.map(item => [
    item.pd_code,
    item.pd_name,
    item.product,
    item.score,
    item.pd_alt_name || '-'
  ]);

  doc.autoTable({
    head: [['Product Design', 'Name', 'Product', 'Compute', 'Alternate Name']],
    body: tableData,
    startY: 35,
    theme: 'grid',
    styles: { fontSize: 9, cellPadding: 2 },
    headStyles: { fillColor: [41, 128, 185], textColor: 255, fontStyle: 'bold' },
    columnStyles: {
      0: { cellWidth: 30 },
      1: { cellWidth: 'auto' },
      2: { cellWidth: 30 },
      3: { halign: 'center', cellWidth: 25 },
      4: { cellWidth: 'auto' }
    }
  });
  
  doc.output('dataurlnewwindow');
  printDropdownOpen.value = false;
};

const printAsCsv = () => {
  try {
    showNotification('Exporting data as CSV...');
    const headers = ['Product Design', 'Name', 'Product', 'Compute', 'Alternate Name'];
    const csvContent = [
      headers.join(','),
      ...filteredDesigns.value.map(item => [
        `"${item.pd_code || ''}"`,
        `"${item.pd_name || ''}"`,
        `"${item.product || ''}"`,
        `"${item.score || 'No'}"`,
        `"${item.pd_alt_name || ''}"`
      ].join(','))
    ].join('\n');
    
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', `product_designs_${new Date().toISOString().slice(0, 10)}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    showNotification('Data exported successfully');
  } catch (error) {
    showNotification('Failed to export data', 'error');
  } finally {
    printDropdownOpen.value = false;
  }
};

const handleClickOutside = (event) => {
  if (printDropdownContainer.value && !printDropdownContainer.value.contains(event.target)) {
    printDropdownOpen.value = false;
  }
};

</script>

<style>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
@media print {
  @page { size: A4; margin: 1cm; }
  body { font-size: 12pt; }
  .print\:hidden { display: none !important; }
  table { page-break-inside: avoid; width: 100%; border-collapse: collapse; }
  .hidden.print\:block { display: block !important; }
  .bg-blue-600 { background-color: #2563eb !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
  .text-white { color: #fff !important; }
  .border, .border-r { border-color: #000 !important; }
}
</style> 