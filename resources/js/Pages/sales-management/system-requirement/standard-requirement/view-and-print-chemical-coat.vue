<template>
    <AppLayout :header="'View & Print Chemical Coats'">
    <Head title="View & Print Chemical Coats" />

    <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header Section -->
            <div class="bg-emerald-600 text-white shadow-sm rounded-xl border border-emerald-700 mb-4">
                <div class="px-4 py-3 sm:px-6 flex items-center gap-3">
                    <div class="h-9 w-9 rounded-full bg-emerald-500 flex items-center justify-center">
                        <i class="fas fa-vial text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg sm:text-xl font-semibold leading-tight">View & Print Chemical Coats</h2>
                        <p class="text-xs sm:text-sm text-emerald-100">Preview and print chemical coat data</p>
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
                        <Link href="/chemical-coat" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-md flex items-center space-x-2 border border-gray-200">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Chemical Coats
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
                            placeholder="Search chemical coats..."
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
                                    <i class="fas fa-vial text-3xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold">CHEMICAL COAT LIST</h2>
                                    <p class="text-sm opacity-80">View and print chemical coat data</p>
                                </div>
                            </div>
                        </div>

                        <!-- Table Content -->
                        <table class="min-w-full border-collapse">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th @click="sortTable('code')" class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                        Code <i :class="getSortIcon('code')" class="text-xs"></i>
                                    </th>
                                    <th @click="sortTable('name')" class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                        Name <i :class="getSortIcon('name')" class="text-xs"></i>
                                    </th>
                                    <th @click="sortTable('dry_end_code')" class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                        Dry-End Code <i :class="getSortIcon('dry_end_code')" class="text-xs"></i>
                                    </th>
                                    <th class="px-4 py-2 text-left font-semibold border border-gray-300" style="color: black;">
                                        Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                <tr v-if="loading">
                                    <td colspan="4" class="px-4 py-3 text-center text-gray-500 border border-gray-300">
                                        <div class="flex justify-center">
                                            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-emerald-500"></div>
                                        </div>
                                        <p class="mt-2">Loading chemical coat data...</p>
                                    </td>
                                </tr>
                                <tr v-else-if="filteredItems.length === 0">
                                    <td colspan="4" class="px-4 py-3 text-center text-gray-500 border border-gray-300">
                                        No chemical coats found.
                                        <template v-if="searchQuery">
                                            <p class="mt-2">No results match your search query: "{{ searchQuery }}"</p>
                                            <button @click="searchQuery = ''" class="mt-2 text-emerald-600 hover:underline">Clear search</button>
                                        </template>
                                    </td>
                                </tr>
                                <tr v-for="(item, index) in filteredItems" :key="item.id"
                                    :class="index % 2 === 0 ? 'bg-emerald-50' : 'bg-white'"
                                    class="hover:bg-emerald-100">
                                    <td class="px-4 py-2 border border-gray-300">
                                        <div class="text-sm font-medium text-gray-900">{{ item.code || 'N/A' }}</div>
                                    </td>
                                    <td class="px-4 py-2 border border-gray-300">
                                        <div class="text-sm text-gray-900">{{ item.name || 'N/A' }}</div>
                                    </td>
                                    <td class="px-4 py-2 border border-gray-300">
                                        <div class="text-sm text-gray-900">{{ item.dry_end_code || '-' }}</div>
                                    </td>
                                    <td class="px-4 py-2 border border-gray-300">
                                        <div class="text-sm text-gray-900">
                                            <span v-if="item.is_active" class="text-green-600">Active</span>
                                            <span v-else class="text-red-600">Inactive</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Table Footer -->
                        <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 text-sm text-gray-500">
                            <div class="flex items-center justify-between">
                                <div>Total Chemical Coats: {{ filteredItems.length }}</div>
                                <div v-if="searchQuery">Filtered from {{ items.length }} total records</div>
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
import AppLayout from '@/Layouts/AppLayout.vue';
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';

const items = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const sortColumn = ref('code');
const sortDirection = ref('asc');
const currentDate = new Date().toLocaleString();

onMounted(async () => {
    await fetchItems();
    loading.value = false;
});

const fetchItems = async () => {
    try {
        const response = await fetch('/api/chemical-coats', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            throw new Error('Failed to fetch chemical coats');
        }

        const data = await response.json();
        items.value = Array.isArray(data) ? data : (data.data || []);
    } catch (error) {
        console.error('Error fetching chemical coats:', error);
        items.value = [];
    }
};

const sortTable = (column) => {
    if (sortColumn.value === column) {
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortColumn.value = column;
        sortDirection.value = 'asc';
    }
};

const getSortIcon = (column) => {
    if (sortColumn.value !== column) {
        return 'fas fa-sort text-black';
    }
    return sortDirection.value === 'asc'
        ? 'fas fa-sort-up text-black'
        : 'fas fa-sort-down text-black';
};

const filteredItems = computed(() => {
    let filtered = [...items.value];

    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(item =>
            (item.code && String(item.code).toLowerCase().includes(query)) ||
            (item.name && item.name.toLowerCase().includes(query)) ||
            (item.dry_end_code && item.dry_end_code.toLowerCase().includes(query))
        );
    }

    filtered.sort((a, b) => {
        let valueA = a[sortColumn.value] || '';
        let valueB = b[sortColumn.value] || '';

        if (typeof valueA !== 'string') valueA = String(valueA);
        if (typeof valueB !== 'string') valueB = String(valueB);

        valueA = valueA.toLowerCase();
        valueB = valueB.toLowerCase();

        if (sortDirection.value === 'asc') {
            return valueA.localeCompare(valueB);
        } else {
            return valueB.localeCompare(valueA);
        }
    });

    return filtered;
});

const printTable = () => {
    try {
        const doc = new jsPDF({
            orientation: 'landscape',
            unit: 'mm',
            format: 'a4'
        });

        doc.setFontSize(16);
        doc.setTextColor(37, 99, 235);
        doc.text('CHEMICAL COAT LIST', 10, 15);

        doc.setFontSize(10);
        doc.setTextColor(100);
        doc.text('View and print chemical coat data', 10, 22);

        const tableData = filteredItems.value.map(item => [
            item.code || 'N/A',
            item.name || 'N/A',
            item.dry_end_code || '-',
            item.is_active ? 'Active' : 'Inactive'
        ]);

        autoTable(doc, {
            startY: 28,
            head: [['Code', 'Name', 'Dry-End Code', 'Status']],
            body: tableData,
            theme: 'grid',
            tableWidth: 'auto',
            headStyles: {
                fillColor: [37, 99, 235],
                textColor: [255, 255, 255],
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
                fillColor: [219, 234, 254]
            },
            margin: { top: 28, left: 10, right: 10 }
        });

        const pageCount = doc.internal.getNumberOfPages();
        const pageHeight = doc.internal.pageSize.height;
        
        for (let i = 1; i <= pageCount; i++) {
            doc.setPage(i);
            doc.setFontSize(8);
            doc.setTextColor(100);
            doc.text(
                `Total Chemical Coats: ${filteredItems.value.length} | Generated: ${currentDate}`,
                10,
                pageHeight - 10
            );
            doc.text(
                `Page ${i} of ${pageCount}`,
                doc.internal.pageSize.width - 35,
                pageHeight - 10
            );
        }

        doc.save(`chemical-coat-list-${new Date().getTime()}.pdf`);
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
