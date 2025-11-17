<template>
    <AppLayout :header="'View & Print Colors'">
    <Head title="View & Print Colors" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 p-6 rounded-t-lg shadow-lg">
        <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
            <i class="fas fa-print mr-3"></i> View & Print Colors
        </h2>
        <p class="text-emerald-100">Preview and print color data</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
        <!-- Actions Bar -->
        <div class="flex flex-wrap items-center justify-between mb-6">
            <div class="flex items-center space-x-2 mb-3 sm:mb-0">
                <button @click="printTable" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-file-pdf mr-2"></i> Print PDF
            </button>
                <Link href="/color" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Colors
                </Link>
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input
                    type="text"
                    v-model="searchQuery"
                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Search colors..."
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
                            <i class="fas fa-palette text-3xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold">COLOR LIST</h2>
                            <p class="text-sm opacity-80">View and print color data</p>
                        </div>
                    </div>
        </div>

                <!-- Table Content -->
                <table class="min-w-full border-collapse">
                    <thead class="bg-blue-600" style="background-color: #2563eb;">
                        <tr>
                            <th @click="sortTable('color_id')" class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                Color# <i :class="getSortIcon('color_id')" class="text-xs"></i>
                            </th>
                            <th @click="sortTable('color_name')" class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                Color Name <i :class="getSortIcon('color_name')" class="text-xs"></i>
                            </th>
                            <th @click="sortTable('origin')" class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                Origin <i :class="getSortIcon('origin')" class="text-xs"></i>
                            </th>
                            <th @click="sortTable('color_group_id')" class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                CG# <i :class="getSortIcon('color_group_id')" class="text-xs"></i>
                            </th>
                            <th class="px-4 py-2 text-left font-semibold border border-gray-300" style="color: black;">
                                KG Per M2
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <tr v-if="loading">
                            <td colspan="5" class="px-4 py-3 text-center text-gray-500 border border-gray-300">
                                <div class="flex justify-center">
                                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"></div>
                                </div>
                                <p class="mt-2">Loading color data...</p>
                            </td>
                    </tr>
                        <tr v-else-if="filteredColors.length === 0">
                            <td colspan="5" class="px-4 py-3 text-center text-gray-500 border border-gray-300">
                            No colors found.
                                <template v-if="searchQuery">
                                    <p class="mt-2">No results match your search query: "{{ searchQuery }}"</p>
                                    <button @click="searchQuery = ''" class="mt-2 text-blue-500 hover:underline">Clear search</button>
                                </template>
                        </td>
                    </tr>
                        <tr v-for="(color, index) in filteredColors" :key="color.color_id"
                            :class="index % 2 === 0 ? 'bg-blue-100' : 'bg-white'"
                            class="hover:bg-blue-200">
                            <td class="px-4 py-2 border border-gray-300">
                                <div class="text-sm font-medium text-gray-900">{{ color.color_id || 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-2 border border-gray-300">
                                <div class="text-sm text-gray-900">{{ color.color_name || 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-2 border border-gray-300">
                                <div class="text-sm text-gray-900">{{ color.origin || 'ID' }}</div>
                            </td>
                            <td class="px-4 py-2 border border-gray-300">
                                <div class="text-sm text-gray-900">{{ color.color_group_id || 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-2 border border-gray-300">
                                <div class="text-sm text-gray-900">{{ color.kg_per_m2 || '1.0000' }}</div>
                            </td>
                        </tr>
                </tbody>
            </table>

                <!-- Table Footer -->
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 text-sm text-gray-500">
                    <div class="flex items-center justify-between">
                        <div>Total Colors: {{ filteredColors.length }}</div>
                        <div v-if="searchQuery">Filtered from {{ colors.length }} total records</div>
                        <div class="text-xs text-gray-400">Generated: {{ currentDate }}</div>
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
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';

const colors = ref([]);
const colorGroups = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const sortColumn = ref('color_id');
const sortDirection = ref('asc');
const currentDate = new Date().toLocaleString();

onMounted(async () => {
    await Promise.all([fetchColors(), fetchColorGroups()]);
    loading.value = false;
});

const fetchColors = async () => {
    try {
        const response = await fetch('/api/colors', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            throw new Error('Failed to fetch colors');
        }

        const data = await response.json();

        // Handle different response formats
        if (Array.isArray(data)) {
            colors.value = data;
        } else if (data.colors && Array.isArray(data.colors)) {
            colors.value = data.colors;
        } else if (data.data && Array.isArray(data.data)) {
            colors.value = data.data;
        } else {
            colors.value = [];
            console.error('Unexpected data format for colors:', data);
        }
    } catch (error) {
        console.error('Error fetching colors:', error);
        colors.value = []; // Ensure it's always an array
    }
};

const fetchColorGroups = async () => {
    try {
        const response = await fetch('/api/color-groups', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            throw new Error('Failed to fetch color groups');
        }

        const data = await response.json();

        // Handle different response formats
        if (Array.isArray(data)) {
            colorGroups.value = data;
        } else if (data.color_groups && Array.isArray(data.color_groups)) {
            colorGroups.value = data.color_groups;
        } else if (data.data && Array.isArray(data.data)) {
            colorGroups.value = data.data;
        } else {
            colorGroups.value = [];
            console.error('Unexpected data format for color groups:', data);
        }
    } catch (error) {
        console.error('Error fetching color groups:', error);
        colorGroups.value = []; // Ensure it's always an array
    }
};


// Sort table
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
        return 'fas fa-sort text-black';
    }

    return sortDirection.value === 'asc'
        ? 'fas fa-sort-up text-black'
        : 'fas fa-sort-down text-black';
};

// Filtered and sorted colors
const filteredColors = computed(() => {
    let filtered = [...colors.value];

    // Apply search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(color =>
            (color.color_id && String(color.color_id).toLowerCase().includes(query)) ||
            (color.color_name && color.color_name.toLowerCase().includes(query)) ||
            (color.origin && color.origin.toLowerCase().includes(query)) ||
            (color.color_group_id && color.color_group_id.toLowerCase().includes(query))
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
        if (typeof valueA !== 'string') valueA = String(valueA || '');
        if (typeof valueB !== 'string') valueB = String(valueB || '');

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
        doc.text('COLOR LIST', 10, 15);

        // Add subtitle
        doc.setFontSize(10);
        doc.setTextColor(100);
        doc.text('View and print color data', 10, 22);

        // Prepare table data
        const tableData = filteredColors.value.map(color => [
            color.color_id || 'N/A',
            color.color_name || 'N/A',
            color.origin || 'ID',
            color.color_group_id || 'N/A',
            color.kg_per_m2 || '1.0000'
        ]);

        // Add table using autoTable - call via imported function
        autoTable(doc, {
            startY: 28,
            head: [['Color#', 'Color Name', 'Origin', 'CG#', 'KG Per M2']],
            body: tableData,
            theme: 'grid',
            tableWidth: 'auto',  // Let autoTable calculate optimal widths
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
                `Total Colors: ${filteredColors.value.length} | Generated: ${currentDate}`,
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
        doc.save(`color-list-${new Date().getTime()}.pdf`);
    } catch (error) {
        console.error('Error generating PDF:', error);
        alert('Error generating PDF. Please try again.');
    }
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
