<template>
    <AppLayout :header="'View & Print Industries'">
    <Head title="View & Print Industries" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 p-6 rounded-t-lg shadow-lg">
        <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
            <i class="fas fa-industry mr-3"></i> View & Print Industries
        </h2>
        <p class="text-emerald-100">Preview and print industry data</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
        <!-- Actions Bar -->
        <div class="flex flex-wrap items-center justify-between mb-6">
            <div class="flex items-center space-x-2 mb-3 sm:mb-0">
                <button @click="printTable" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-file-pdf mr-2"></i> Print PDF
                </button>
                <Link href="/industry" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Industry Management
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
                    placeholder="Search industries..."
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
                            <i class="fas fa-industry text-3xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold">INDUSTRY DATA LIST</h2>
                            <p class="text-sm opacity-80">View and print industry data</p>
                        </div>
                    </div>
                </div>

                <!-- Table Content -->
                <table class="min-w-full border-collapse">
                    <thead class="bg-blue-600" style="background-color: #2563eb;">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold border border-gray-300" style="color: black; width: 80px;">
                                NO.
                            </th>
                            <th @click="sortTable('code')" class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                INDUSTRY CODE <i :class="getSortIcon('code')" class="text-xs"></i>
                            </th>
                            <th @click="sortTable('name')" class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                INDUSTRY NAME <i :class="getSortIcon('name')" class="text-xs"></i>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <tr v-if="loading">
                            <td colspan="3" class="px-4 py-3 text-center text-gray-500 border border-gray-300">
                                <div class="flex justify-center">
                                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"></div>
                                </div>
                                <p class="mt-2">Loading industry data...</p>
                            </td>
                        </tr>
                        <tr v-else-if="filteredIndustries.length === 0">
                            <td colspan="3" class="px-4 py-3 text-center text-gray-500 border border-gray-300">
                                No industries found.
                                <template v-if="searchQuery">
                                    <p class="mt-2">No results match your search query: "{{ searchQuery }}"</p>
                                    <button @click="searchQuery = ''" class="mt-2 text-blue-500 hover:underline">Clear search</button>
                                </template>
                            </td>
                        </tr>
                        <tr v-for="(industry, index) in filteredIndustries" :key="industry.code"
                            :class="index % 2 === 0 ? 'bg-blue-100' : 'bg-white'"
                            class="hover:bg-blue-200">
                            <td class="px-4 py-2 border border-gray-300 text-center">
                                <div class="text-sm font-medium text-gray-900">{{ index + 1 }}</div>
                            </td>
                            <td class="px-4 py-2 border border-gray-300">
                                <div class="text-sm font-medium text-gray-900">{{ industry.code || '' }}</div>
                            </td>
                            <td class="px-4 py-2 border border-gray-300">
                                <div class="text-sm text-gray-900">{{ industry.name || '' }}</div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Table Footer -->
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 text-sm text-gray-500">
                    <div class="flex items-center justify-between">
                        <div>Total Industries: {{ filteredIndustries.length }}</div>
                        <div v-if="searchQuery">Filtered from {{ industries.length }} total records</div>
                        <div class="text-xs text-gray-400">Generated: {{ currentDate }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Print Instructions -->
        <div class="mt-6 bg-blue-50 p-4 rounded-lg border border-blue-100">
            <h3 class="font-semibold text-blue-800 mb-2 flex items-center">
                <i class="fas fa-info-circle mr-2"></i> Print Instructions
            </h3>
            <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                <li>Click the "Print PDF" button above to generate a PDF of this industry data list</li>
                <li>PDF will be generated in landscape orientation</li>
                <li>You can search or sort data before generating the PDF</li>
                <li>The PDF will include all filtered data</li>
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

// Data
const industries = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const sortColumn = ref('code');
const sortDirection = ref('asc');
const currentDate = new Date().toLocaleString();

// Fetch industries from API
const fetchIndustries = async () => {
    loading.value = true;
    try {
        const response = await fetch('/api/industry', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            throw new Error('Failed to fetch industries');
        }

        const data = await response.json();

        // Handle different response formats
        if (Array.isArray(data)) {
            industries.value = data;
        } else if (data.data && Array.isArray(data.data)) {
            industries.value = data.data;
        } else {
            industries.value = [];
            console.error('Unexpected data format for industries:', data);
        }
    } catch (error) {
        console.error('Error fetching industries:', error);
        industries.value = []; // Ensure it's always an array
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
        return 'fas fa-sort text-black';
    }
    return sortDirection.value === 'asc' ? 'fas fa-sort-up text-black' : 'fas fa-sort-down text-black';
};

// Filtered and sorted industries
const filteredIndustries = computed(() => {
    let filtered = [...industries.value];

    // Apply search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(industry =>
            (industry.code && industry.code.toLowerCase().includes(query)) ||
            (industry.name && industry.name.toLowerCase().includes(query))
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
            orientation: 'portrait',
            unit: 'mm',
            format: 'a4'
        });

        // Add title
        doc.setFontSize(16);
        doc.setTextColor(37, 99, 235); // Blue color
        doc.text('INDUSTRY DATA LIST', 10, 15);

        // Add subtitle
        doc.setFontSize(10);
        doc.setTextColor(100);
        doc.text('View and print industry data', 10, 22);

        // Prepare table data
        const tableData = filteredIndustries.value.map((industry, index) => [
            (index + 1).toString(),
            industry.code || '',
            industry.name || ''
        ]);

        // Add table using autoTable
        autoTable(doc, {
            startY: 28,
            head: [['NO.', 'INDUSTRY CODE', 'INDUSTRY NAME']],
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
            margin: { top: 28, left: 10, right: 10 },
            columnStyles: {
                0: { cellWidth: 20, halign: 'center' }, // NO. column - centered
                1: { cellWidth: 40 }, // Industry Code column
                2: { cellWidth: 'auto' } // Industry Name column takes remaining space
            }
        });

        // Add footer with date and count
        const finalY = doc.lastAutoTable.finalY || 28;
        doc.setFontSize(8);
        doc.setTextColor(100);
        doc.text(`Generated: ${currentDate}`, 10, finalY + 10);
        doc.text(`Total Industries: ${filteredIndustries.value.length}`, 10, finalY + 15);

        // Save the PDF
        doc.save('industry-list.pdf');
    } catch (error) {
        console.error('Error generating PDF:', error);
        alert('Failed to generate PDF. Please try again.');
    }
};

// Lifecycle hooks
onMounted(() => {
    fetchIndustries();
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
