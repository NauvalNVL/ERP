<template>
    <AppLayout :header="'View & Print Paper Sizes'">
    <Head title="View & Print Paper Sizes" />

    <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 text-white shadow-sm rounded-xl border border-green-700 mb-4">
            <div class="px-4 py-3 sm:px-6 flex items-center gap-3">
                <div class="h-9 w-9 rounded-full bg-green-500 flex items-center justify-center">
                    <i class="fas fa-print text-white text-lg"></i>
                </div>
                <div>
                    <h2 class="text-lg sm:text-xl font-semibold leading-tight">View & Print Paper Sizes</h2>
                    <p class="text-xs sm:text-sm text-green-100">Preview and print paper size data</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-4 sm:p-6 mb-6">
        <!-- Actions Bar -->
        <div class="flex flex-wrap items-center justify-between mb-6">
            <div class="flex items-center space-x-2 mb-3 sm:mb-0">
                <button @click="printTable" class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-md flex items-center space-x-2">
                    <i class="fas fa-file-pdf mr-2"></i> Print PDF
                </button>
                <Link href="/paper-size" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-md flex items-center space-x-2 border border-gray-200">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Paper Sizes
                </Link>
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input 
                    type="text" 
                    v-model="searchQuery" 
                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-emerald-500 focus:border-emerald-500"
                    placeholder="Search paper sizes..."
                >
            </div>
        </div>

        <!-- Table Section -->
        <div class="overflow-x-auto">
            <div id="printableTable" class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                <!-- Table Header -->
                <div class="bg-gradient-to-r from-green-600 to-green-700 text-white py-4 px-6 flex items-center">
                    <div class="flex items-center">
                        <div class="mr-4">
                            <i class="fas fa-ruler-combined text-3xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold">PAPER SIZE LIST</h2>
                            <p class="text-sm opacity-80">View and print paper size data</p>
                        </div>
                    </div>
                </div>

                <!-- Table Content -->
                <table class="min-w-full border-collapse table-fixed">
                    <thead class="bg-gray-50">
                        <tr>
                            <th @click="sortTable('id')" class="px-6 py-3 text-center font-semibold border border-gray-300 cursor-pointer" style="color: black; width: 15%;">
                                NO. <i :class="getSortIcon('id')" class="text-xs ml-1"></i>
                            </th>
                            <th @click="sortTable('millimeter')" class="px-6 py-3 text-center font-semibold border border-gray-300 cursor-pointer" style="color: black; width: 45%;">
                                MILLIMETER <i :class="getSortIcon('millimeter')" class="text-xs ml-1"></i>
                            </th>
                            <th @click="sortTable('inches')" class="px-6 py-3 text-center font-semibold border border-gray-300 cursor-pointer" style="color: black; width: 40%;">
                                INCHES <i :class="getSortIcon('inches')" class="text-xs ml-1"></i>
                            </th>
                            <th @click="sortTable('status')" class="px-6 py-3 text-center font-semibold border border-gray-300 cursor-pointer" style="color: black; width: 20%;">
                                STATUS <i :class="getSortIcon('status')" class="text-xs ml-1"></i>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <tr v-if="loading">
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500 border border-gray-300">
                                <div class="flex justify-center">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-500"></div>
                                </div>
                                <p class="mt-3 font-medium">Loading paper size data...</p>
                            </td>
                        </tr>
                        <tr v-else-if="filteredPaperSizes.length === 0">
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500 border border-gray-300">
                                <p class="font-medium text-gray-700">No paper sizes found.</p>
                                <template v-if="searchQuery">
                                    <p class="mt-2 text-sm">No results match your search query: "{{ searchQuery }}"</p>
                                    <button @click="searchQuery = ''" class="mt-3 text-emerald-600 hover:text-emerald-700 hover:underline font-medium">Clear search</button>
                                </template>
                            </td>
                        </tr>
                        <tr v-for="(size, index) in filteredPaperSizes" :key="size.id" 
                            :class="index % 2 === 0 ? 'bg-emerald-50' : 'bg-white'"
                            class="hover:bg-emerald-100 transition-colors duration-150">
                            <td class="px-6 py-3 border border-gray-300 text-center">
                                <div class="text-sm font-semibold text-gray-900">{{ size.id }}</div>
                            </td>
                            <td class="px-6 py-3 border border-gray-300 text-center">
                                <div class="text-sm font-medium text-gray-900">{{ formatNumber(size.millimeter) }}</div>
                            </td>
                            <td class="px-6 py-3 border border-gray-300 text-center">
                                <div class="text-sm font-medium text-gray-900">{{ formatNumber(size.inches) }}</div>
                            </td>
                            <td class="px-6 py-3 border border-gray-300 text-center">
                                <div class="text-sm font-medium text-gray-900">{{ getStatusValue(size) }}</div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Table Footer -->
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 text-sm text-gray-500">
                    <div class="flex items-center justify-between">
                        <div>Total Paper Sizes: {{ filteredPaperSizes.length }}</div>
                        <div v-if="searchQuery">Filtered from {{ paperSizes.length }} total records</div>
                        <div class="text-xs text-gray-400">Generated: {{ currentDate }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Print Instructions -->
        <div class="mt-6 bg-emerald-50 p-4 rounded-lg border border-emerald-100">
            <h3 class="font-semibold text-emerald-800 mb-2 flex items-center">
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
     </div>
     </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';

// Data
const paperSizes = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const sortColumn = ref('millimeter');
const sortDirection = ref('asc');
const currentDate = new Date().toLocaleString();

// Fetch paper sizes from API
const fetchPaperSizes = async () => {
    loading.value = true;
    try {
        const response = await fetch('/api/paper-sizes?all_status=1', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to fetch paper sizes');
        }
        
        const data = await response.json();
        paperSizes.value = data;
    } catch (error) {
        console.error('Error fetching paper sizes:', error);
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

// Get sort icon
const getSortIcon = (column) => {
    if (sortColumn.value !== column) {
        return 'fas fa-sort text-gray-400';
    }
    
    return sortDirection.value === 'asc' 
        ? 'fas fa-sort-up text-emerald-600' 
        : 'fas fa-sort-down text-emerald-600';
};

// Format number
const formatNumber = (value) => {
    if (value === null || value === undefined) return '0.00';
    return Number(value).toFixed(2);
};

// Format date
const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    return new Date(dateString).toLocaleString();
};

const getStatusValue = (row) => {
    if (!row) return '';
    if (row.status) return String(row.status).trim();
    if (row.STATUS) return String(row.STATUS).trim();
    if (typeof row.is_active === 'boolean') return row.is_active ? 'Act' : 'Obs';
    return '';
};

// Filtered and sorted paper sizes
const filteredPaperSizes = computed(() => {
    let filtered = [...paperSizes.value];
    
    // Apply search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(size => 
            (size.id && size.id.toString().includes(query)) ||
            (size.millimeter && size.millimeter.toString().includes(query)) ||
            (size.inches && size.inches.toString().includes(query)) ||
            (getStatusValue(size) && getStatusValue(size).toLowerCase().includes(query))
        );
    }
    
    // Apply sorting
    filtered.sort((a, b) => {
        let valueA = a[sortColumn.value];
        let valueB = b[sortColumn.value];
        
        // Handle null values
        if (valueA === null || valueA === undefined) valueA = '';
        if (valueB === null || valueB === undefined) valueB = '';
        
        // Handle numeric columns
        if (['id', 'millimeter', 'inches'].includes(sortColumn.value)) {
            valueA = parseFloat(valueA) || 0;
            valueB = parseFloat(valueB) || 0;
            
            if (sortDirection.value === 'asc') {
                return valueA - valueB;
            } else {
                return valueB - valueA;
            }
        }
        
        // Handle boolean column
        if (sortColumn.value === 'is_active') {
            if (sortDirection.value === 'asc') {
                return valueA === valueB ? 0 : valueA ? -1 : 1;
            } else {
                return valueA === valueB ? 0 : valueA ? 1 : -1;
            }
        }
        
        // Handle date columns
        if (['created_at', 'updated_at'].includes(sortColumn.value)) {
            const dateA = valueA ? new Date(valueA).getTime() : 0;
            const dateB = valueB ? new Date(valueB).getTime() : 0;
            
            if (sortDirection.value === 'asc') {
                return dateA - dateB;
            } else {
                return dateB - dateA;
            }
        }
        
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

// Print function using jsPDF
const printTable = () => {
    try {
        const doc = new jsPDF({
            orientation: 'landscape',
            unit: 'mm',
            format: 'a4'
        });

        // Add title
        doc.setFontSize(16);
        doc.setTextColor(5, 150, 105); // Emerald color
        doc.text('PAPER SIZE LIST', 10, 15);

        // Add subtitle
        doc.setFontSize(10);
        doc.setTextColor(100);
        doc.text('View and print paper size data', 10, 22);

        // Prepare table data
        const tableData = filteredPaperSizes.value.map(size => [
            size.id || '',
            formatNumber(size.millimeter),
            formatNumber(size.inches),
            getStatusValue(size)
        ]);

        // Add table using autoTable
        autoTable(doc, {
            startY: 28,
            head: [['NO.', 'MILLIMETER', 'INCHES', 'STATUS']],
            body: tableData,
            theme: 'grid',
            tableWidth: 'auto',
            headStyles: {
                fillColor: [5, 150, 105], // Emerald background
                textColor: [255, 255, 255], // White text
                fontStyle: 'bold',
                halign: 'center',
                fontSize: 10
            },
            bodyStyles: {
                textColor: [50, 50, 50],
                halign: 'center',
                fontSize: 9
            },
            alternateRowStyles: {
                fillColor: [209, 250, 229] // Light emerald for alternate rows
            },
            margin: { top: 28, left: 10, right: 10 },
            columnStyles: {
                0: { cellWidth: 30, halign: 'center' },  // ID
                1: { cellWidth: 60, halign: 'center' },  // Millimeter
                2: { cellWidth: 60, halign: 'center' },  // Inches
                3: { cellWidth: 30, halign: 'center' }   // Status
            }
        });

        // Add footer
        const pageCount = doc.internal.getNumberOfPages();
        const pageHeight = doc.internal.pageSize.height;
        
        for (let i = 1; i <= pageCount; i++) {
            doc.setPage(i);
            doc.setFontSize(8);
            doc.setTextColor(100);
            doc.text(
                `Total Paper Sizes: ${filteredPaperSizes.value.length} | Generated: ${currentDate}`,
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
        doc.save(`paper-size-list-${new Date().getTime()}.pdf`);
    } catch (error) {
        console.error('Error generating PDF:', error);
        alert('Error generating PDF. Please try again.');
    }
};

// Lifecycle hooks
onMounted(() => {
    fetchPaperSizes();
});
</script>

<style scoped>
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

.text-right {
    text-align: right;
}
</style>
