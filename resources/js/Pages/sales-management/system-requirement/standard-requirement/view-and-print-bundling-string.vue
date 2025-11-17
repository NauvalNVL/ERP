<template>
    <AppLayout :header="'View & Print Bundling Strings'">
    <Head title="View & Print Bundling Strings" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 p-6 rounded-t-lg shadow-lg">
        <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
            <i class="fas fa-print mr-3"></i> View & Print Bundling Strings
        </h2>
        <p class="text-emerald-100">Preview and print bundling string data</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
        <!-- Actions Bar -->
        <div class="flex flex-wrap items-center justify-between mb-6">
            <div class="flex items-center space-x-2 mb-3 sm:mb-0">
                <button @click="printTable" class="bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-file-pdf mr-2"></i> Print PDF
                </button>
                <Link href="/bundling-string" class="bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Bundling Strings
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
                    placeholder="Search bundling strings..."
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
                            <i class="fas fa-link text-3xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold">BUNDLING STRING LIST</h2>
                            <p class="text-sm opacity-80">View and print bundling string data</p>
                        </div>
                    </div>
                </div>

                <!-- Table Content -->
                <table class="min-w-full border-collapse">
                    <thead class="bg-green-700" style="background-color: #047857;">
                        <tr>
                            <th @click="sortTable('code')" class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                Code <i :class="getSortIcon('code')" class="text-xs"></i>
                            </th>
                            <th @click="sortTable('name')" class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                Name <i :class="getSortIcon('name')" class="text-xs"></i>
                            </th>
                            <th @click="sortTable('description')" class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                Description <i :class="getSortIcon('description')" class="text-xs"></i>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <tr v-if="loading">
                            <td colspan="3" class="px-4 py-3 text-center text-gray-500 border border-gray-300">
                                <div class="flex justify-center">
                                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-emerald-500"></div>
                                </div>
                                <p class="mt-2">Loading bundling string data...</p>
                            </td>
                        </tr>
                        <tr v-else-if="filteredBundlingStrings.length === 0">
                            <td colspan="3" class="px-4 py-3 text-center text-gray-500 border border-gray-300">
                                No bundling strings found.
                                <template v-if="searchQuery">
                                    <p class="mt-2">No results match your search query: "{{ searchQuery }}"</p>
                                    <button @click="searchQuery = ''" class="mt-2 text-emerald-600 hover:underline">Clear search</button>
                                </template>
                            </td>
                        </tr>
                        <tr v-for="(string, index) in filteredBundlingStrings" :key="string.id || string.code"
                            :class="index % 2 === 0 ? 'bg-emerald-50' : 'bg-white'"
                            class="hover:bg-emerald-100">
                            <td class="px-4 py-2 border border-gray-300">
                                <div class="text-sm font-medium text-gray-900">{{ string.code || 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-2 border border-gray-300">
                                <div class="text-sm text-gray-900">{{ string.name || 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-2 border border-gray-300">
                                <div class="text-sm text-gray-900">{{ string.description || '-' }}</div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Table Footer -->
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 text-sm text-gray-500">
                    <div class="flex items-center justify-between">
                        <div>Total Bundling Strings: {{ filteredBundlingStrings.length }}</div>
                        <div v-if="searchQuery">Filtered from {{ bundlingStrings.length }} total records</div>
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
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';

const bundlingStrings = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const sortColumn = ref('code');
const sortDirection = ref('asc');
const currentDate = new Date().toLocaleString();

// Fetch bundling strings from API
const fetchBundlingStrings = async () => {
    loading.value = true;
    try {
        const response = await fetch('/api/bundling-strings', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const data = await response.json();

        if (Array.isArray(data)) {
            bundlingStrings.value = data;
        } else if (data.data && Array.isArray(data.data)) {
            bundlingStrings.value = data.data;
        } else {
            bundlingStrings.value = [];
        }
    } catch (error) {
        console.error('Error fetching bundling strings:', error);
        bundlingStrings.value = [];
    } finally {
        loading.value = false;
    }
};

onMounted(async () => {
    await fetchBundlingStrings();
    loading.value = false;
});

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

// Filtered and sorted bundling strings
const filteredBundlingStrings = computed(() => {
    let filtered = [...bundlingStrings.value];

    // Apply search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(string =>
            (string.code && string.code.toLowerCase().includes(query)) ||
            (string.name && string.name.toLowerCase().includes(query)) ||
            (string.description && string.description.toLowerCase().includes(query))
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
        doc.text('BUNDLING STRING LIST', 10, 15);

        // Add subtitle
        doc.setFontSize(10);
        doc.setTextColor(100);
        doc.text('View and print bundling string data', 10, 22);

        // Prepare table data
        const tableData = filteredBundlingStrings.value.map(string => [
            string.code || 'N/A',
            string.name || 'N/A',
            string.description || '-'
        ]);

        // Add table using autoTable
        autoTable(doc, {
            startY: 28,
            head: [['Code', 'Name', 'Description']],
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
        for (let i = 1; i <= pageCount; i++) {
            doc.setPage(i);
            doc.setFontSize(8);
            doc.setTextColor(100);

            // Page number
            const pageText = `Page ${i} of ${pageCount}`;
            doc.text(pageText, doc.internal.pageSize.width - 30, doc.internal.pageSize.height - 10);

            // Date
            doc.text(`Printed: ${new Date().toLocaleString()}`, 10, doc.internal.pageSize.height - 10);
        }

        // Save the PDF
        doc.save(`bundling-string-list-${new Date().getTime()}.pdf`);
    } catch (error) {
        console.error('Error generating PDF:', error);
        alert('Failed to generate PDF. Please try again.');
    }
};
</script>
