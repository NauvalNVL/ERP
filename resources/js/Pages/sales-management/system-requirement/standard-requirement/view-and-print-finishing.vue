<template>
    <AppLayout :header="'View & Print Finishing'">
    <Head title="View & Print Finishings" />

    <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-green-600 to-green-700 text-white shadow-sm rounded-xl border border-green-700 mb-4">
                <div class="px-4 py-3 sm:px-6 flex items-center gap-3">
                    <div class="h-9 w-9 rounded-full bg-green-500 flex items-center justify-center">
                        <i class="fas fa-tools text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg sm:text-xl font-semibold leading-tight">View & Print Finishings</h2>
                        <p class="text-xs sm:text-sm text-green-100">Preview and print finishing data</p>
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
                        <Link href="/finishing" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-md flex items-center space-x-2 border border-gray-200">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Finishings
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
                            placeholder="Search finishings..."
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
                                    <i class="fas fa-tools text-3xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold">FINISHING LIST</h2>
                                    <p class="text-sm opacity-80">View and print finishing data</p>
                                </div>
                            </div>
                        </div>

                        <!-- Table Content -->
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th @click="sortTable('id')" class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider cursor-pointer">
                                        ID <i class="fas fa-sort ml-1"></i>
                                    </th>
                                    <th @click="sortTable('code')" class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider cursor-pointer">
                                        Code <i class="fas fa-sort ml-1"></i>
                                    </th>
                                    <th @click="sortTable('description')" class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider cursor-pointer">
                                        Description <i class="fas fa-sort ml-1"></i>
                                    </th>
                                    <th @click="sortTable('status')" class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider cursor-pointer">
                                        Status <i class="fas fa-sort ml-1"></i>
                                    </th>
                                    <th @click="sortTable('created_at')" class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider cursor-pointer">
                                        Created At <i class="fas fa-sort ml-1"></i>
                                    </th>
                                    <th @click="sortTable('updated_at')" class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider cursor-pointer">
                                        Updated At <i class="fas fa-sort ml-1"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-if="loading" class="hover:bg-gray-50">
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        <div class="flex justify-center">
                                            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-emerald-500"></div>
                                        </div>
                                        <p class="mt-2">Loading finishing data...</p>
                                    </td>
                                </tr>
                                <tr v-else-if="filteredFinishings.length === 0" class="hover:bg-gray-50">
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        No finishings found.
                                        <template v-if="searchQuery">
                                            <p class="mt-2">No results match your search query: "{{ searchQuery }}"</p>
                                            <button @click="searchQuery = ''" class="mt-2 text-emerald-600 hover:underline">Clear search</button>
                                        </template>
                                    </td>
                                </tr>
                                <tr v-for="(finishing, index) in filteredFinishings" :key="finishing.id" 
                                    :class="{'bg-emerald-50': index % 2 === 0}" 
                                    class="hover:bg-emerald-100">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ finishing.id }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ finishing.code || 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ finishing.description || 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ finishing.status || '' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ formatDate(finishing.created_at) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ formatDate(finishing.updated_at) }}</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Table Footer -->
                        <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 text-sm text-gray-500">
                            <div class="flex items-center justify-between">
                                <div>Total Finishings: {{ filteredFinishings.length }}</div>
                                <div v-if="searchQuery">Filtered from {{ finishings.length }} total records</div>
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
const finishings = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const sortColumn = ref('id');
const sortDirection = ref('asc');
const currentDate = new Date().toLocaleString();

// Fetch finishings from API
const fetchFinishings = async () => {
    loading.value = true;
    try {
        const response = await fetch('/api/finishings?all_status=1', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to fetch finishings');
        }
        
        const data = await response.json();
        
        if (Array.isArray(data)) {
            finishings.value = data;
        } else if (data.data && Array.isArray(data.data)) {
            finishings.value = data.data;
        } else {
            console.error('Unexpected API response format:', data);
            finishings.value = [];
        }
    } catch (error) {
        console.error('Error fetching finishings:', error);
        finishings.value = [];
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

// Format date
const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    return new Date(dateString).toLocaleString();
};

// Filtered and sorted finishings
const filteredFinishings = computed(() => {
    let filtered = [...finishings.value];
    
    // Apply search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(finishing => 
            (finishing.id && finishing.id.toString().includes(query)) ||
            (finishing.code && finishing.code.toLowerCase().includes(query)) ||
            (finishing.description && finishing.description.toLowerCase().includes(query)) ||
            (finishing.status && String(finishing.status).toLowerCase().includes(query))
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
        doc.setTextColor(37, 99, 235); // Blue color
        doc.text('FINISHING LIST', 10, 15);

        // Add subtitle
        doc.setFontSize(10);
        doc.setTextColor(100);
        doc.text('View and print finishing data', 10, 22);

        // Prepare table data
        const tableData = filteredFinishings.value.map(finishing => [
            finishing.id || 'N/A',
            finishing.code || 'N/A',
            finishing.description || 'N/A',
            finishing.status || '',
            formatDate(finishing.created_at),
            formatDate(finishing.updated_at)
        ]);

        // Add table using autoTable
        autoTable(doc, {
            startY: 28,
            head: [['ID', 'Code', 'Description', 'Status', 'Created At', 'Updated At']],
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
                `Total Finishings: ${filteredFinishings.value.length} | Generated: ${currentDate}`,
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
        doc.save(`finishing-list-${new Date().getTime()}.pdf`);
    } catch (error) {
        console.error('Error generating PDF:', error);
        alert('Error generating PDF. Please try again.');
    }
};

// Lifecycle hooks
onMounted(() => {
    fetchFinishings();
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
</style>
