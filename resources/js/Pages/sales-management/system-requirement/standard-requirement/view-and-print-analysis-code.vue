<template>
    <AppLayout :header="'View & Print Analysis Codes'">
    <Head title="View & Print Analysis Codes" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 p-6 rounded-t-lg shadow-lg">
        <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
            <i class="fas fa-print mr-3"></i> View & Print Analysis Codes
        </h2>
        <p class="text-emerald-100">Preview and print analysis code data</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
        <!-- Actions Bar -->
        <div class="flex flex-wrap items-center justify-between mb-6">
            <div class="flex items-center space-x-2 mb-3 sm:mb-0">
                <button @click="printTable" class="bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-file-pdf mr-2"></i> Print PDF
                </button>
                <Link href="/analysis-code" class="bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Analysis Code
                </Link>
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input
                    type="text"
                    v-model="searchQuery"
                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500"
                    placeholder="Search analysis codes..."
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
                            <i class="fas fa-code-branch text-3xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold">ANALYSIS CODE LIST</h2>
                            <p class="text-sm opacity-80">View and print analysis code data</p>
                        </div>
                    </div>
                </div>

                <!-- Table Content -->
                <table class="min-w-full border-collapse">
                    <thead class="bg-green-700" style="background-color: #047857;">
                        <tr>
                            <th @click="sortTable('analysis_code')" class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                Analysis Code <i :class="getSortIcon('analysis_code')" class="text-xs"></i>
                            </th>
                            <th @click="sortTable('analysis_name')" class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                Analysis Name <i :class="getSortIcon('analysis_name')" class="text-xs"></i>
                            </th>
                            <th @click="sortTable('analysis_group')" class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                Analysis Group <i :class="getSortIcon('analysis_group')" class="text-xs"></i>
                            </th>
                            <th @click="sortTable('analysis_group2')" class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                Analysis Group2 <i :class="getSortIcon('analysis_group2')" class="text-xs"></i>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <tr v-if="loading">
                            <td colspan="4" class="px-4 py-3 text-center text-gray-500 border border-gray-300">
                                <div class="flex justify-center">
                                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-emerald-500"></div>
                                </div>
                                <p class="mt-2">Loading analysis code data...</p>
                            </td>
                        </tr>
                        <tr v-else-if="filteredCodes.length === 0">
                            <td colspan="4" class="px-4 py-3 text-center text-gray-500 border border-gray-300">
                                No analysis codes found.
                                <template v-if="searchQuery">
                                    <p class="mt-2">No results match your search query: "{{ searchQuery }}"</p>
                                    <button @click="searchQuery = ''" class="mt-2 text-emerald-600 hover:underline">Clear search</button>
                                </template>
                            </td>
                        </tr>
                        <tr v-for="(code, index) in filteredCodes" :key="code.analysis_code"
                            :class="index % 2 === 0 ? 'bg-emerald-50' : 'bg-white'"
                            class="hover:bg-emerald-100">
                            <td class="px-4 py-2 border border-gray-300">
                                <div class="text-sm font-medium text-gray-900">{{ code.analysis_code || 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-2 border border-gray-300">
                                <div class="text-sm text-gray-900">{{ code.analysis_name || 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-2 border border-gray-300">
                                <div class="text-sm text-gray-900">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-emerald-100 text-emerald-800">
                                        {{ code.analysis_group || 'N/A' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-2 border border-gray-300">
                                <div class="text-sm text-gray-900">{{ code.analysis_group2 || 'N/A' }}</div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Table Footer -->
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 text-sm text-gray-500">
                    <div class="flex items-center justify-between">
                        <div>Total Analysis Codes: {{ filteredCodes.length }}</div>
                        <div v-if="searchQuery">Filtered from {{ analysisCodes.length }} total records</div>
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
                <li>Analysis codes are grouped by Analysis Group for better organization</li>
            </ul>
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

const analysisCodes = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const sortColumn = ref('analysis_code');
const sortDirection = ref('asc');
const currentDate = new Date().toLocaleString();

onMounted(async () => {
    await fetchAnalysisCodes();
    loading.value = false;
});

const fetchAnalysisCodes = async () => {
    try {
        const response = await fetch('/api/analysis-codes', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            throw new Error('Failed to fetch analysis codes');
        }

        const data = await response.json();

        // Handle different response formats
        if (Array.isArray(data)) {
            analysisCodes.value = data;
        } else if (data.analysis_codes && Array.isArray(data.analysis_codes)) {
            analysisCodes.value = data.analysis_codes;
        } else if (data.data && Array.isArray(data.data)) {
            analysisCodes.value = data.data;
        } else {
            analysisCodes.value = [];
            console.error('Unexpected data format for analysis codes:', data);
        }
    } catch (error) {
        console.error('Error fetching analysis codes:', error);
        analysisCodes.value = [];
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

// Filtered and sorted analysis codes
const filteredCodes = computed(() => {
    let filtered = [...analysisCodes.value];

    // Apply search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(code =>
            (code.analysis_code && String(code.analysis_code).toLowerCase().includes(query)) ||
            (code.analysis_name && code.analysis_name.toLowerCase().includes(query)) ||
            (code.analysis_group && code.analysis_group.toLowerCase().includes(query)) ||
            (code.analysis_group2 && code.analysis_group2.toLowerCase().includes(query))
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
        doc.setTextColor(5, 150, 105); // Emerald color
        doc.text('ANALYSIS CODE LIST', 10, 15);

        // Add subtitle
        doc.setFontSize(10);
        doc.setTextColor(100);
        doc.text('View and print analysis code data', 10, 22);

        // Prepare table data
        const tableData = filteredCodes.value.map(code => [
            code.analysis_code || 'N/A',
            code.analysis_name || 'N/A',
            code.analysis_group || 'N/A',
            code.analysis_group2 || 'N/A'
        ]);

        // Add table using autoTable
        autoTable(doc, {
            startY: 28,
            head: [['Analysis Code', 'Analysis Name', 'Analysis Group', 'Analysis Group2']],
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
            columnStyles: {
                0: { cellWidth: 40 },  // Analysis Code
                1: { cellWidth: 80 },  // Analysis Name
                2: { cellWidth: 50 },  // Analysis Group
                3: { cellWidth: 50 }   // Analysis Group2
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
                `Total Analysis Codes: ${filteredCodes.value.length} | Generated: ${currentDate}`,
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
        doc.save(`analysis-code-list-${new Date().getTime()}.pdf`);
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
