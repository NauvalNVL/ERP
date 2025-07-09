<template>
  <AppLayout title="View & Print Location">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        View & Print Location
      </h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
          <!-- Header with buttons -->
          <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 flex items-center justify-between">
            <h2 class="text-lg font-bold text-white">View & Print Location</h2>
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
                      placeholder="Search by code, name, or description..."
                      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                      <i class="fas fa-search text-gray-400"></i>
                    </div>
                  </div>
                  <div>
                    <select v-model="statusFilter" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Statuses</option>
                        <option :value="true">Active</option>
                        <option :value="false">Inactive</option>
                    </select>
                  </div>
                </div>
              </div>
              
              <!-- Report header for print -->
              <div class="hidden print:block mb-6">
                <div class="text-center">
                  <h1 class="text-2xl font-bold text-center print:text-3xl">Location Report</h1>
                  <p class="text-center text-gray-600 print:text-lg">Generated on {{ formattedDate }}</p>
              </div>
            </div>
            
              <!-- Table section -->
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border">
                  <thead class="bg-gray-100">
                    <tr>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Code</th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Name</th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Description</th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="filteredRows.length === 0" class="hover:bg-gray-50">
                      <td colspan="4" class="px-4 py-4 text-center text-sm text-gray-500">No data found.</td>
                    </tr>
                    <tr v-for="row in filteredRows" :key="row.code" class="hover:bg-gray-50">
                      <td class="px-4 py-2 text-sm font-medium text-gray-900 border-r">{{ row.code }}</td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">{{ row.name }}</td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">{{ row.description || '-' }}</td>
                      <td class="px-4 py-2 text-sm text-gray-900">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" :class="row.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                            {{ row.is_active ? 'Active' : 'Inactive' }}
                        </span>
                      </td>
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
const statusFilter = ref('');
const printDropdownOpen = ref(false);
const printDropdownContainer = ref(null);
const toast = useToast();

const defineRoute = '/material-management/system-requirement/inventory-setup/location';

const filteredRows = computed(() => {
  return rows.value.filter(row => {
    const searchMatch = (
      (row.code && row.code.toLowerCase().includes(searchQuery.value.toLowerCase())) ||
      (row.name && row.name.toLowerCase().includes(searchQuery.value.toLowerCase())) ||
      (row.description && row.description.toLowerCase().includes(searchQuery.value.toLowerCase()))
    );
    const statusMatch = statusFilter.value === '' ? true : row.is_active === statusFilter.value;
    
    return searchMatch && statusMatch;
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
    const response = await axios.get('/api/material-management/locations/for-print');
    rows.value = response.data;
  } catch (error) {
    console.error('Error fetching location data:', error);
    toast.error('Failed to load location data. Please try again.');
  } finally {
    loading.value = false;
  }
};

const exportAsCsv = () => {
  let csvContent = 'data:text/csv;charset=utf-8,';
  csvContent += 'Code,Name,Description,Status\n';
  
  filteredRows.value.forEach(row => {
    csvContent += `"${row.code}","${row.name}","${row.description || ''}","${row.is_active ? 'Active' : 'Inactive'}"\n`;
  });

  const encodedUri = encodeURI(csvContent);
  const link = document.createElement('a');
  link.setAttribute('href', encodedUri);
  link.setAttribute('download', `locations_${new Date().toISOString().slice(0, 10)}.csv`);
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
  printDropdownOpen.value = false;
};

const printAsPdf = () => {
  const doc = new jsPDF();
  
  doc.setFontSize(18);
  doc.setFont('helvetica', 'bold');
  doc.text('Location Report', 15, 22);

  doc.setFontSize(11);
  doc.setFont('helvetica', 'normal');
  doc.text(`Generated on: ${formattedDate.value}`, 15, 28);
  
  const tableData = filteredRows.value.map(item => [
    item.code,
    item.name,
    item.description || '-',
    item.is_active ? 'Active' : 'Inactive',
  ]);

  autoTable(doc, {
    head: [['Code', 'Name', 'Description', 'Status']],
    body: tableData,
    startY: 35,
    theme: 'grid',
    styles: { fontSize: 10, cellPadding: 2 },
    headStyles: { fillColor: [41, 128, 185], textColor: 255, fontStyle: 'bold' },
    columnStyles: {
      0: { cellWidth: 'auto' },
      1: { cellWidth: '*' },
      2: { cellWidth: '*' },
      3: { cellWidth: 'auto' },
    }
  });

  doc.output('dataurlnewwindow');
  printDropdownOpen.value = false;
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