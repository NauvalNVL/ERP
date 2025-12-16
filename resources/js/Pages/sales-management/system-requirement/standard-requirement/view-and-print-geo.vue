<template>
    <AppLayout :header="'View & Print Geo Data'">
    <Head title="View & Print Geo Data" />

    <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header Section -->
            <div class="bg-emerald-600 text-white shadow-sm rounded-xl border border-emerald-700 mb-4">
                <div class="px-4 py-3 sm:px-6 flex items-center gap-3">
                    <div class="h-9 w-9 rounded-full bg-emerald-500 flex items-center justify-center">
                        <i class="fas fa-globe-americas text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg sm:text-xl font-semibold leading-tight">View & Print Geo Data</h2>
                        <p class="text-xs sm:text-sm text-emerald-100">Preview and print geographical data</p>
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
                        <Link href="/geo" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-md flex items-center space-x-2 border border-gray-200">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Geo Management
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
                            placeholder="Search geo data..."
                        >
                    </div>
                </div>

                <!-- Table Section -->
                <div class="overflow-x-auto">
                    <div id="printableTable" class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <!-- Table Header -->
                        <div class="bg-emerald-600 text-white py-4 px-6 flex items-center">
                            <div class="flex items-center">
                                <div class="mr-4">
                                    <i class="fas fa-globe-americas text-3xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold">GEO DATA LIST</h2>
                                    <p class="text-sm opacity-80">View and print geographical data</p>
                                </div>
                            </div>
                        </div>

                        <!-- Table Content -->
                        <table class="min-w-full border-collapse">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th @click="sortTable('index')" class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                        No <i :class="getSortIcon('index')" class="text-xs"></i>
                                    </th>
                                    <th @click="sortTable('code')" class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                        Code <i :class="getSortIcon('code')" class="text-xs"></i>
                                    </th>
                                    <th @click="sortTable('country')" class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                        Country <i :class="getSortIcon('country')" class="text-xs"></i>
                                    </th>
                                    <th @click="sortTable('state')" class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                        State <i :class="getSortIcon('state')" class="text-xs"></i>
                                    </th>
                                    <th @click="sortTable('town')" class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                        Town <i :class="getSortIcon('town')" class="text-xs"></i>
                                    </th>
                                    <th @click="sortTable('town_section')" class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                        Town Section <i :class="getSortIcon('town_section')" class="text-xs"></i>
                                    </th>
                                    <th @click="sortTable('area')" class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                        Area <i :class="getSortIcon('area')" class="text-xs"></i>
                                    </th>
                                    <th class="px-4 py-2 text-left font-semibold border border-gray-300" style="color: black; width: 90px;">
                                        Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                <tr v-if="loading">
                                    <td colspan="8" class="px-4 py-3 text-center text-gray-500 border border-gray-300">
                                        <div class="flex justify-center">
                                            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-emerald-500"></div>
                                        </div>
                                        <p class="mt-2">Loading geo data...</p>
                                    </td>
                                </tr>
                                <tr v-else-if="filteredGeos.length === 0">
                                    <td colspan="8" class="px-4 py-3 text-center text-gray-500 border border-gray-300">
                                        No geo data available.
                                        <template v-if="searchQuery">
                                            <p class="mt-2">No results match your search query: "{{ searchQuery }}"</p>
                                            <button @click="searchQuery = ''" class="mt-2 text-emerald-600 hover:underline">Clear search</button>
                                        </template>
                                    </td>
                                </tr>
                                <tr v-for="(geo, index) in filteredGeos" :key="geo.code" 
                                    :class="index % 2 === 0 ? 'bg-emerald-50' : 'bg-white'"
                                    class="hover:bg-emerald-100">
                                    <td class="px-4 py-2 border border-gray-300">
                                        <div class="text-sm font-medium text-gray-900">{{ index + 1 }}</div>
                                    </td>
                                    <td class="px-4 py-2 border border-gray-300">
                                        <div class="text-sm font-medium text-gray-900">{{ geo.code || 'N/A' }}</div>
                                    </td>
                                    <td class="px-4 py-2 border border-gray-300">
                                        <div class="text-sm text-gray-900">{{ geo.country || 'N/A' }}</div>
                                    </td>
                                    <td class="px-4 py-2 border border-gray-300">
                                        <div class="text-sm text-gray-900">{{ geo.state || 'N/A' }}</div>
                                    </td>
                                    <td class="px-4 py-2 border border-gray-300">
                                        <div class="text-sm text-gray-900">{{ geo.town || 'N/A' }}</div>
                                    </td>
                                    <td class="px-4 py-2 border border-gray-300">
                                        <div class="text-sm text-gray-900">{{ geo.town_section || 'N/A' }}</div>
                                    </td>
                                    <td class="px-4 py-2 border border-gray-300">
                                        <div class="text-sm text-gray-900">{{ geo.area || 'N/A' }}</div>
                                    </td>
                                    <td class="px-4 py-2 border border-gray-300">
                                        <div class="text-sm text-gray-900">{{ getStatusValue(geo) || '-' }}</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Table Footer -->
                        <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 text-sm text-gray-500">
                            <div class="flex items-center justify-between">
                                <div>Total Geo Records: {{ filteredGeos.length }}</div>
                                <div v-if="searchQuery">Filtered from {{ geos.length }} total records</div>
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
const geos = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const sortColumn = ref('code');
const sortDirection = ref('asc');
const currentDate = new Date().toLocaleString();

// Fetch geos from API
const fetchGeos = async () => {
    loading.value = true;
    try {
        const response = await fetch('/api/geo?all_status=1', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to fetch geo data');
        }
        
        const data = await response.json();
        geos.value = data;
    } catch (error) {
        console.error('Error fetching geo data:', error);
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

// Get sort icon based on current sort state
const getSortIcon = (column) => {
    if (sortColumn.value !== column) {
        return 'fas fa-sort text-gray-400';
    }
    return sortDirection.value === 'asc' ? 'fas fa-sort-up text-emerald-600' : 'fas fa-sort-down text-emerald-600';
};

// Filtered and sorted geos
const filteredGeos = computed(() => {
    let filtered = [...geos.value];
    
    // Apply search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(geo => 
            (geo.code && geo.code.toLowerCase().includes(query)) ||
            (geo.country && geo.country.toLowerCase().includes(query)) ||
            (geo.state && geo.state.toLowerCase().includes(query)) ||
            (geo.town && geo.town.toLowerCase().includes(query)) ||
            (geo.town_section && geo.town_section.toLowerCase().includes(query)) ||
            (geo.area && geo.area.toLowerCase().includes(query))
        );
    }
    
    // Apply sorting
    filtered.sort((a, b) => {
        // Special case for index (always sort by index number)
        if (sortColumn.value === 'index') {
            return sortDirection.value === 'asc' ? 
                geos.value.indexOf(a) - geos.value.indexOf(b) :
                geos.value.indexOf(b) - geos.value.indexOf(a);
        }
        
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
        doc.setTextColor(5, 150, 105); // Emerald color
        doc.text('GEO DATA LIST', 10, 15);

        // Add subtitle
        doc.setFontSize(10);
        doc.setTextColor(100);
        doc.text('View and print geographical data', 10, 22);

        // Prepare table data
        const tableData = filteredGeos.value.map((geo, index) => [
            (index + 1).toString(),
            geo.code || 'N/A',
            geo.country || 'N/A',
            geo.state || 'N/A',
            geo.town || 'N/A',
            geo.town_section || 'N/A',
            geo.area || 'N/A',
            getStatusValue(geo) || ''
        ]);

        // Add table using autoTable
        autoTable(doc, {
            startY: 28,
            head: [['No', 'Code', 'Country', 'State', 'Town', 'Town Section', 'Area', 'Status']],
            body: tableData,
            theme: 'grid',
            tableWidth: 'auto',
            headStyles: {
                fillColor: [5, 150, 105], // Emerald background
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
                fillColor: [209, 250, 229] // Light emerald for alternate rows
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
                `Total Geo Records: ${filteredGeos.value.length} | Generated: ${currentDate}`,
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
        doc.save(`geo-list-${new Date().getTime()}.pdf`);
    } catch (error) {
        console.error('Error generating PDF:', error);
        alert('Error generating PDF. Please try again.');
    }
};

// Lifecycle hooks
onMounted(() => {
    fetchGeos();
});

const getStatusValue = (row) => {
    if (!row) return '';
    if (row.status) return String(row.status).trim();
    if (row.STATUS) return String(row.STATUS).trim();
    if (typeof row.is_active === 'boolean') return row.is_active ? 'Act' : 'Obs';
    return '';
};
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
