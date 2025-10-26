<template>
    <AppLayout :header="'View & Print Customer Groups'">
    <Head title="View & Print Customer Groups" />

    <!-- Main Container with vibrant gradient background -->
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-cyan-50 to-teal-50 p-4 md:p-6">
      <div class="max-w-7xl mx-auto">
        <!-- Header Section with animated elements -->
        <div class="bg-gradient-to-r from-blue-600 via-cyan-600 to-teal-500 rounded-xl shadow-lg overflow-hidden mb-6 transform transition-all duration-500 hover:shadow-xl">
          <div class="relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20 animate-pulse-slow"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10 animate-pulse-slow animation-delay-500"></div>
            <div class="absolute bottom-0 right-0 w-32 h-32 bg-cyan-400 opacity-5 rounded-full translate-y-10 translate-x-10"></div>

            <div class="p-6 md:p-8 relative z-10">
              <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex items-start md:items-center space-x-4 mb-4 md:mb-0">
                  <div class="bg-gradient-to-br from-cyan-500 to-teal-600 p-3 rounded-lg shadow-inner flex items-center justify-center relative overflow-hidden">
                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-blue-300 opacity-30 rounded-full animate-ping-slow"></div>
                    <div class="absolute -bottom-1 -left-1 w-4 h-4 bg-teal-300 opacity-30 rounded-full animate-ping-slow animation-delay-500"></div>
                    <i class="fas fa-print text-white text-2xl z-10"></i>
                  </div>
                  <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white text-shadow">View & Print Customer Groups</h1>
                    <p class="text-teal-100 max-w-2xl">Preview and print customer group data</p>
                  </div>
                </div>
                <div class="flex flex-wrap gap-3">
                  <button @click="exportPDF" class="bg-gradient-to-r from-green-500 to-teal-400 text-white hover:from-green-600 hover:to-teal-500 px-4 py-2 rounded-lg flex items-center transition-all duration-300 transform hover:scale-105 shadow-md relative overflow-hidden group">
                    <span class="absolute inset-0 bg-white opacity-20 transform scale-x-0 origin-left transition-transform group-hover:scale-x-100"></span>
                    <div class="relative z-10 flex items-center">
                      <div class="bg-white bg-opacity-30 rounded-full p-1 mr-2">
                        <i class="fas fa-file-pdf text-white"></i>
                      </div>
                      <span>Print PDF</span>
    </div>
                </button>
                  <Link href="/customer-group" class="bg-white bg-opacity-20 text-white border border-white border-opacity-30 hover:bg-opacity-30 px-4 py-2 rounded-lg flex items-center transition-all duration-300 relative overflow-hidden">
                    <span class="absolute inset-0 bg-white opacity-10 transform scale-x-0 origin-left transition-transform hover:scale-x-100"></span>
                    <div class="relative z-10 flex items-center">
                      <div class="bg-white bg-opacity-30 rounded-full p-1.5 mr-2 transition-transform transform group-hover:rotate-12 shadow-inner">
                        <i class="fas fa-arrow-left text-white"></i>
                      </div>
                      <span>Back</span>
                    </div>
                </Link>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
          <div class="bg-gradient-to-r from-blue-600 to-cyan-600 text-white py-3 px-6">
            <div class="flex items-center">
              <div class="mr-3 bg-white bg-opacity-20 p-2 rounded-full shadow-inner relative overflow-hidden">
                <div class="absolute -top-1 -right-1 w-4 h-4 bg-cyan-300 opacity-30 rounded-full"></div>
                <div class="absolute -bottom-1 -left-1 w-3 h-3 bg-teal-300 opacity-30 rounded-full"></div>
                <i class="fas fa-filter text-xl relative z-10"></i>
              </div>
              <div>
                <h2 class="text-xl font-bold">Search Parameters</h2>
                <p class="text-xs text-cyan-100 opacity-80">Filter customer groups by various criteria</p>
              </div>
            </div>
          </div>

          <div class="p-6">
            <!-- Actions Bar -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <!-- Search Field -->
              <div class="space-y-2">
                <label class="block text-sm font-medium text-blue-700 mb-2 flex items-center">
                  <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-full mr-2 flex items-center justify-center shadow-sm">
                    <i class="fas fa-search text-white text-sm"></i>
                </div>
                  Search:
                </label>
                <div class="relative flex-grow">
                <input
                    type="text"
                    v-model="searchQuery"
                    class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                    placeholder="Enter group code or description..."
                  />
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                  </div>
                </div>
              </div>

              <!-- Sort Options -->
              <div class="space-y-2">
                <label class="block text-sm font-medium text-blue-700 mb-2 flex items-center">
                  <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-full mr-2 flex items-center justify-center shadow-sm">
                    <i class="fas fa-sort text-white text-sm"></i>
                  </div>
                  Sort By:
                </label>
                <select v-model="sortColumn" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                  <option value="group_code">Group Code</option>
                  <option value="description">Description</option>
                </select>
              </div>

              <!-- Action Buttons -->
              <div class="flex items-end">
                <button @click="searchQuery = ''; sortColumn = 'group_code'" class="px-6 py-2.5 border border-gray-300 rounded-lg bg-white hover:bg-gray-50 text-gray-700 transition-all duration-300 shadow-sm flex items-center mr-3">
                  <i class="fas fa-undo mr-2 text-gray-500"></i>
                  Reset Filters
                </button>
                <button @click="fetchCustomerGroups()" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white rounded-lg transition-all duration-300 shadow-md flex items-center">
                  <i class="fas fa-sync mr-2"></i>
                  Refresh Data
                </button>
              </div>
            </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
          <div class="bg-gradient-to-r from-blue-600 to-cyan-600 text-white py-4 px-6">
                    <div class="flex items-center">
              <div class="mr-4 bg-white bg-opacity-20 p-2 rounded-full shadow-inner">
                <i class="fas fa-users text-2xl"></i>
                        </div>
    <div>
                <h2 class="text-xl font-bold">Customer Groups List</h2>
                <p class="text-sm opacity-80">View and manage customer group data</p>
                        </div>
                    </div>
                </div>

          <div class="overflow-x-auto">
            <div id="printableTable" class="min-w-full bg-white">
                <!-- Table Content -->
                <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-cyan-50 to-blue-50">
                        <tr>
                    <th @click="sortTable('group_code')" class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors">
                      <div class="flex items-center">
                        <span>Group Code</span>
                        <i class="fas fa-sort ml-1"></i>
                      </div>
                            </th>
                    <th @click="sortTable('description')" class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors">
                      <div class="flex items-center">
                        <span>Description</span>
                        <i class="fas fa-sort ml-1"></i>
                      </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-if="loading" class="hover:bg-gray-50">
                            <td colspan="2" class="px-6 py-4 text-center text-gray-500">
                                <div class="flex justify-center">
                                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"></div>
                                </div>
                                <p class="mt-2">Loading customer group data...</p>
                            </td>
                        </tr>
                        <tr v-else-if="filteredCustomerGroups.length === 0" class="hover:bg-gray-50">
                            <td colspan="2" class="px-6 py-4 text-center text-gray-500">
                      <div class="flex flex-col items-center">
                        <i class="fas fa-users text-4xl text-gray-300 mb-2"></i>
                        <p class="text-lg font-medium">No customer groups found</p>
                                <template v-if="searchQuery">
                          <p class="mt-2 text-sm">No results match your search query: "{{ searchQuery }}"</p>
                                    <button @click="searchQuery = ''" class="mt-2 text-blue-500 hover:underline">Clear search</button>
                                </template>
                      </div>
                            </td>
                        </tr>
                        <tr v-for="(group, index) in filteredCustomerGroups" :key="group.group_code"
                            :class="{'bg-blue-50': index % 2 === 0}"
                      class="hover:bg-blue-100 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ group.group_code }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ group.description }}</div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Table Footer -->
              <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-6 py-3 border-t border-gray-200 text-sm text-white">
                    <div class="flex items-center justify-between">
                  <div class="flex items-center">
                    <i class="fas fa-info-circle mr-2 text-white"></i>
                    <span>Total Customer Groups: <span class="font-semibold text-white">{{ filteredCustomerGroups.length }}</span></span>
                  </div>
                  <div v-if="searchQuery" class="text-white">
                    Filtered from {{ customerGroups.length }} total records
                  </div>
                        <div class="text-xs text-gray-400">Generated: {{ currentDate }}</div>
                </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Print Instructions -->
        <div class="mt-6 bg-blue-50 p-4 rounded-lg border border-blue-100">
          <h3 class="font-semibold text-blue-800 mb-2 flex items-center">
            <i class="fas fa-info-circle mr-2"></i> PDF Export Instructions
            </h3>
          <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                <li>Click the "Print PDF" button above to generate and download PDF</li>
                <li>PDF will be automatically saved in landscape orientation</li>
                <li>You can search or sort data before exporting</li>
                <li>PDF includes formatted table with headers and page numbers</li>
            </ul>
        </div>
        </div>
    </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';

// Data
const customerGroups = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const sortColumn = ref('group_code');
const sortDirection = ref('asc');
const currentDate = new Date().toLocaleString();

// Fetch customer groups from API
const fetchCustomerGroups = async () => {
    loading.value = true;
    try {
        const response = await fetch('/api/customer-groups', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            throw new Error('Failed to fetch customer groups');
        }

        const data = await response.json();
        console.log('Received customer groups data:', data);
        console.log('Data type:', typeof data);
        console.log('Is array:', Array.isArray(data));

        // Handle both data structures - wrapped in 'data' property or direct array
        if (data && Array.isArray(data.data)) {
            customerGroups.value = data.data;
        } else if (Array.isArray(data)) {
            customerGroups.value = data;
        } else {
            customerGroups.value = [];
            console.error('Unexpected data format:', data);
        }
    } catch (error) {
        console.error('Error fetching customer groups:', error);
    } finally {
        loading.value = false;
    }
};

// Sort function
const sortTable = (column) => {
    if (sortColumn.value === column) {
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortColumn.value = column;
        sortDirection.value = 'asc';
    }
};

// Filtered and sorted customer groups
const filteredCustomerGroups = computed(() => {
    let filtered = [...customerGroups.value];

    // Apply search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(group =>
            (group.group_code && group.group_code.toLowerCase().includes(query)) ||
            (group.description && group.description.toLowerCase().includes(query))
        );
    }

    // Apply sorting
    filtered.sort((a, b) => {
        let valueA = a[sortColumn.value];
        let valueB = b[sortColumn.value];

        // Handle null values
        if (valueA === null || valueA === undefined) valueA = '';
        if (valueB === null || valueB === undefined) valueB = '';

        // Convert to string for comparison if not already
        if (typeof valueA !== 'string') valueA = valueA.toString();
        if (typeof valueB !== 'string') valueB = valueB.toString();

        // Case insensitive comparison
        valueA = valueA.toLowerCase();
        valueB = valueB.toLowerCase();

        // Sort direction
        if (sortDirection.value === 'asc') {
            return valueA.localeCompare(valueB);
        } else {
            return valueB.localeCompare(valueA);
        }
    });

    return filtered;
});

// Export to PDF function using jsPDF
const exportPDF = () => {
    try {
        const doc = new jsPDF({
            orientation: 'landscape',
            unit: 'mm',
            format: 'a4'
        });

        // Add title
        doc.setFontSize(16);
        doc.setTextColor(37, 99, 235); // Blue color
        doc.text('CUSTOMER GROUP LIST', 10, 15);

        // Add subtitle
        doc.setFontSize(10);
        doc.setTextColor(100);
        doc.text('View and print customer group data', 10, 22);

        // Prepare table data
        const tableData = filteredCustomerGroups.value.map(group => [
            group.group_code || 'N/A',
            group.description || 'N/A'
        ]);

        // Add table using autoTable - call via imported function
        autoTable(doc, {
            startY: 28,
            head: [['Group Code', 'Description']],
            body: tableData,
            theme: 'grid',
            tableWidth: 'auto',
            headStyles: {
                fillColor: [37, 99, 235], // Blue background
                textColor: [255, 255, 255], // White text
                fontStyle: 'bold',
                halign: 'left',
                fontSize: 10
            },
            bodyStyles: {
                textColor: [50, 50, 50],
                halign: 'left',
                fontSize: 9
            },
            alternateRowStyles: {
                fillColor: [219, 234, 254] // Light blue for alternate rows
            },
            columnStyles: {
                0: { fontStyle: 'bold', cellWidth: 40 },
                1: { cellWidth: 'auto' }
            },
            margin: { top: 28, left: 10, right: 10 }
        });

        // Add footer
        const pageCount = doc.internal.getNumberOfPages();
        const pageHeight = doc.internal.pageSize.height;

        for (let i = 1; i <= pageCount; i++) {
            doc.setPage(i);
            doc.setFontSize(8);
            doc.setTextColor(100);
            doc.text(
                `Total Customer Groups: ${filteredCustomerGroups.value.length} | Generated: ${currentDate}`,
                10,
                pageHeight - 10
            );
            doc.text(
                `Page ${i} of ${pageCount}`,
                doc.internal.pageSize.width - 35,
                pageHeight - 10
            );
        }

        // Save PDF
        doc.save(`customer-groups-${new Date().getTime()}.pdf`);
    } catch (error) {
        console.error('Error generating PDF:', error);
        alert('Error generating PDF. Please try again.');
    }
};

// Lifecycle hooks
onMounted(() => {
    fetchCustomerGroups();
});
</script>

<style scoped>
.text-shadow {
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

@keyframes pulse-slow {
    0%, 100% { transform: scale(1); opacity: 0.05; }
    50% { transform: scale(1.1); opacity: 0.08; }
}

.animate-pulse-slow {
    animation: pulse-slow 5s infinite;
}

.animation-delay-500 {
    animation-delay: 0.5s;
}

@keyframes ping-slow {
    75%, 100% {
        transform: scale(2);
        opacity: 0;
    }
}

.animate-ping-slow {
    animation: ping-slow 3s cubic-bezier(0, 0, 0.2, 1) infinite;
}

@media print {
    body * {
        visibility: hidden;
    }
    #printableTable, #printableTable * {
        visibility: visible;
    }
    #printableTable {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
}
</style>
