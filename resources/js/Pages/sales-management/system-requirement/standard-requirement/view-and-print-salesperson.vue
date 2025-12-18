<template>
    <AppLayout :header="'View & Print Salespersons'">
    <Head title="View & Print Salespersons" />

    <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 text-white shadow-sm rounded-xl border border-green-700 mb-4">
            <div class="px-4 py-3 sm:px-6 flex items-center gap-3">
                <div class="h-9 w-9 rounded-full bg-green-500 flex items-center justify-center">
                    <i class="fas fa-print text-white text-lg"></i>
                </div>
                <div>
                    <h2 class="text-lg sm:text-xl font-semibold leading-tight">View & Print Salespersons</h2>
                    <p class="text-xs sm:text-sm text-green-100">Preview and print salesperson data</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-4 sm:p-6 mb-6">
        <!-- Actions Bar -->
        <div class="flex flex-wrap items-center justify-between mb-6">
            <div class="flex items-center space-x-2 mb-3 sm:mb-0">
                <button @click="exportPDF" class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-md flex items-center space-x-2">
                    <i class="fas fa-file-pdf mr-2"></i> Print PDF
                </button>
                <Link href="/sales-person" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-md flex items-center space-x-2 border border-gray-200">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Salespersons
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
                    placeholder="Search salespersons..."
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
                            <i class="fas fa-user-tie text-3xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold">SALESPERSON LIST</h2>
                            <p class="text-sm opacity-80">View and print salesperson data</p>
                        </div>
            </div>
        </div>

                <!-- Table Content -->
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                            <th @click="sortTable('code')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            Code <i :class="getSortIcon('code')"></i>
                        </th>
                            <th @click="sortTable('name')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            Name <i :class="getSortIcon('name')"></i>
                        </th>
                            <th @click="sortTable('grup')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            Group <i :class="getSortIcon('grup')"></i>
                        </th>
                            <th @click="sortTable('code_grup')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            Code Group <i :class="getSortIcon('code_grup')"></i>
                        </th>
                            <th @click="sortTable('target_sales')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            Target Sales <i :class="getSortIcon('target_sales')"></i>
                        </th>
                            <th @click="sortTable('internal')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            Internal <i :class="getSortIcon('internal')"></i>
                        </th>
                            <th @click="sortTable('email')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            Email <i :class="getSortIcon('email')"></i>
                        </th>
                            <th @click="sortTable('status')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            Status <i :class="getSortIcon('status')"></i>
                        </th>
                    </tr>
                </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-if="loading" class="hover:bg-gray-50">
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                <div class="flex justify-center">
                                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-emerald-500"></div>
                                </div>
                                <p class="mt-2">Loading salesperson data...</p>
                            </td>
                        </tr>
                        <tr v-else-if="filteredSalespersons.length === 0" class="hover:bg-gray-50">
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                No salespersons found.
                                <template v-if="searchQuery">
                                    <p class="mt-2">No results match your search query: "{{ searchQuery }}"</p>
                                    <button @click="searchQuery = ''" class="mt-2 text-emerald-600 hover:text-emerald-700 hover:underline">Clear search</button>
                                </template>
                            </td>
                        </tr>
                        <tr v-for="(person, index) in filteredSalespersons" :key="person.code"
                            :class="{'bg-emerald-50': index % 2 === 0}"
                            class="hover:bg-emerald-100">
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">{{ person.code || 'N/A' }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm">{{ person.name || 'N/A' }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm">{{ person.grup || '-' }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm">{{ person.code_grup || '-' }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-right">{{ formatTargetSales(person.target_sales) }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm">{{ person.internal || '-' }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm">{{ person.email || '-' }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm">
                                <span :class="getStatusClass(person.status)">{{ person.status || 'N/A' }}</span>
                            </td>
                    </tr>
                </tbody>
            </table>

                <!-- Table Footer -->
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 text-sm text-gray-500">
                    <div class="flex items-center justify-between">
                        <div>Total Salespersons: {{ filteredSalespersons.length }}</div>
                        <div v-if="searchQuery">Filtered from {{ salespersons.length }} total records</div>
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
            <ul class="list-disc pl-5 text-sm text-slate-700 space-y-1">
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
import AppLayout from '@/Layouts/AppLayout.vue';
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';

// Data
const salespersons = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const sortColumn = ref('code');
const sortDirection = ref('asc');
const currentDate = new Date().toLocaleString();

// Fetch salespersons from the API
const fetchSalespersons = async () => {
    loading.value = true;
    try {
        const response = await fetch('/api/salesperson?all_status=1', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            throw new Error('Failed to fetch salespersons');
        }

        const data = await response.json();

        // Handle different API response formats
        if (data.data) {
            salespersons.value = data.data;
        } else if (Array.isArray(data)) {
            salespersons.value = data;
        } else {
            console.error('Unexpected API response format:', data);
            salespersons.value = [];
        }

        // No need to fetch sales teams since we're only showing code and name
    } catch (error) {
        console.error('Error fetching salespersons:', error);
        salespersons.value = [];
    } finally {
        loading.value = false;
    }
};

// Format date (kept for potential future use)
const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    return new Date(dateString).toLocaleString();
};

// Format target sales
const formatTargetSales = (value) => {
    if (value === null || value === undefined || value === '') return '0.00';
    return Number(value).toFixed(2);
};

// Get status class for styling
const getStatusClass = (status) => {
    if (!status) return 'px-2 py-1 text-xs rounded-full bg-gray-200 text-gray-800';

    const statusLower = status.toLowerCase();
    if (statusLower === 'active') {
        return 'px-2 py-1 text-xs rounded-full bg-green-200 text-green-800';
    } else if (statusLower === 'inactive') {
        return 'px-2 py-1 text-xs rounded-full bg-red-200 text-red-800';
    }
    return 'px-2 py-1 text-xs rounded-full bg-gray-200 text-gray-800';
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
        return 'fas fa-sort text-gray-300';
    }

    return sortDirection.value === 'asc'
        ? 'fas fa-sort-up text-emerald-600'
        : 'fas fa-sort-down text-emerald-600';
};

// Filtered and sorted salespersons
const filteredSalespersons = computed(() => {
    let filtered = [...salespersons.value];

    // Apply search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(person =>
            (person.code && person.code.toLowerCase().includes(query)) ||
            (person.name && person.name.toLowerCase().includes(query)) ||
            (person.grup && person.grup.toLowerCase().includes(query)) ||
            (person.code_grup && person.code_grup.toLowerCase().includes(query)) ||
            (person.internal && person.internal.toLowerCase().includes(query)) ||
            (person.email && person.email.toLowerCase().includes(query)) ||
            (person.status && person.status.toLowerCase().includes(query))
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
        doc.setTextColor(5, 150, 105); // Emerald color
        doc.text('SALESPERSON LIST', 10, 15);

        // Add subtitle
        doc.setFontSize(10);
        doc.setTextColor(100);
        doc.text('View and print salesperson data', 10, 22);

        // Prepare table data
        const tableData = filteredSalespersons.value.map(person => [
            person.code || 'N/A',
            person.name || 'N/A',
            person.grup || '-',
            person.code_grup || '-',
            formatTargetSales(person.target_sales),
            person.internal || '-',
            person.email || '-',
            person.status || 'N/A'
        ]);

        // Add table using autoTable
        autoTable(doc, {
            startY: 28,
            head: [['Code', 'Name', 'Group', 'Code Group', 'Target Sales', 'Internal', 'Email', 'Status']],
            body: tableData,
            theme: 'grid',
            tableWidth: 'auto',
            headStyles: {
                fillColor: [5, 150, 105], // Emerald background
                textColor: [255, 255, 255], // White text
                fontStyle: 'bold',
                halign: 'left',
                fontSize: 8
            },
            bodyStyles: {
                textColor: [50, 50, 50],
                halign: 'left',
                fontSize: 7
            },
            alternateRowStyles: {
                fillColor: [209, 250, 229] // Light emerald for alternate rows
            },
            columnStyles: {
                0: { cellWidth: 25 },
                1: { cellWidth: 40 },
                2: { cellWidth: 30 },
                3: { cellWidth: 25 },
                4: { cellWidth: 25, halign: 'right' },
                5: { cellWidth: 20 },
                6: { cellWidth: 45 },
                7: { cellWidth: 20 }
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
                `Total Salespersons: ${filteredSalespersons.value.length} | Generated: ${currentDate}`,
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
        doc.save(`salespersons-${new Date().getTime()}.pdf`);
    } catch (error) {
        console.error('Error generating PDF:', error);
        alert('Error generating PDF. Please try again.');
    }
};

// Fetch data on component mount
onMounted(() => {
    fetchSalespersons();
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
