<template>
    <AppLayout :header="'View & Print Paper Qualities'">
    <Head title="View & Print Paper Qualities" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
        <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
            <i class="fas fa-print mr-3"></i> View & Print Paper Qualities
        </h2>
        <p class="text-cyan-100">Preview and print paper quality data</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
        <!-- Actions Bar -->
        <div class="flex flex-wrap items-center justify-between mb-6">
            <div class="flex items-center space-x-2 mb-3 sm:mb-0">
                <button @click="printTable" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-file-pdf mr-2"></i> Print PDF
                </button>
                <Link href="/paper-quality" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Paper Qualities
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
                    placeholder="Search paper qualities..."
                >
            </div>
        </div>

        <!-- Table Section -->
        <div class="overflow-x-auto">
            <div id="printableTable" class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                <!-- Table Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white py-4 px-6 flex items-center">
                    <div class="flex items-center">
                        <div class="mr-4">
                            <i class="fas fa-file-alt text-3xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold">PAPER QUALITY LIST</h2>
                            <p class="text-sm opacity-80">View and print paper quality data</p>
                        </div>
                    </div>
                </div>

                <!-- Table Content -->
                <table class="min-w-full border-collapse">
                    <thead class="bg-blue-600" style="background-color: #2563eb;">
                        <tr>
                            <th @click="sortTable('id')" class="px-2 py-2 text-left text-xs font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                ID <i :class="getSortIcon('id')" class="text-xs"></i>
                            </th>
                            <th @click="sortTable('paper_quality')" class="px-2 py-2 text-left text-xs font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                Quality <i :class="getSortIcon('paper_quality')" class="text-xs"></i>
                            </th>
                            <th @click="sortTable('paper_name')" class="px-2 py-2 text-left text-xs font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                Name <i :class="getSortIcon('paper_name')" class="text-xs"></i>
                            </th>
                            <th @click="sortTable('weight_kg_m')" class="px-2 py-2 text-left text-xs font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                Weight <i :class="getSortIcon('weight_kg_m')" class="text-xs"></i>
                            </th>
                            <th @click="sortTable('commercial_code')" class="px-2 py-2 text-left text-xs font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                Comm <i :class="getSortIcon('commercial_code')" class="text-xs"></i>
                            </th>
                            <th @click="sortTable('wet_end_code')" class="px-2 py-2 text-left text-xs font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                Wet-End <i :class="getSortIcon('wet_end_code')" class="text-xs"></i>
                            </th>
                            <th @click="sortTable('decc_code')" class="px-2 py-2 text-left text-xs font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                DECC <i :class="getSortIcon('decc_code')" class="text-xs"></i>
                            </th>
                            <th @click="sortTable('status')" class="px-2 py-2 text-left text-xs font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                Status <i :class="getSortIcon('status')" class="text-xs"></i>
                            </th>
                            <th @click="sortTable('flute')" class="px-2 py-2 text-left text-xs font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                Flute <i :class="getSortIcon('flute')" class="text-xs"></i>
                            </th>
                            <th @click="sortTable('db')" class="px-2 py-2 text-left text-xs font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                DB <i :class="getSortIcon('db')" class="text-xs"></i>
                            </th>
                            <th @click="sortTable('b')" class="px-2 py-2 text-left text-xs font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                B <i :class="getSortIcon('b')" class="text-xs"></i>
                            </th>
                            <th @click="sortTable('il')" class="px-2 py-2 text-left text-xs font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                IL <i :class="getSortIcon('il')" class="text-xs"></i>
                            </th>
                            <th @click="sortTable('a_c_e')" class="px-2 py-2 text-left text-xs font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                A/C/E <i :class="getSortIcon('a_c_e')" class="text-xs"></i>
                            </th>
                            <th @click="sortTable('2l')" class="px-2 py-2 text-left text-xs font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                2L <i :class="getSortIcon('2l')" class="text-xs"></i>
                            </th>
                            <th @click="sortTable('is_active')" class="px-2 py-2 text-left text-xs font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                Active <i :class="getSortIcon('is_active')" class="text-xs"></i>
                            </th>
                            <th @click="sortTable('created_at')" class="px-2 py-2 text-left text-xs font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                Created <i :class="getSortIcon('created_at')" class="text-xs"></i>
                            </th>
                            <th @click="sortTable('updated_at')" class="px-2 py-2 text-left text-xs font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                Updated <i :class="getSortIcon('updated_at')" class="text-xs"></i>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <tr v-if="loading">
                            <td colspan="17" class="px-2 py-3 text-center text-gray-500 border border-gray-300">
                                <div class="flex justify-center">
                                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"></div>
                                </div>
                                <p class="mt-2">Loading paper quality data...</p>
                            </td>
                        </tr>
                        <tr v-else-if="filteredPaperQualities.length === 0">
                            <td colspan="17" class="px-2 py-3 text-center text-gray-500 border border-gray-300">
                                No paper qualities found. 
                                <template v-if="searchQuery">
                                    <p class="mt-2">No results match your search query: "{{ searchQuery }}"</p>
                                    <button @click="searchQuery = ''" class="mt-2 text-blue-500 hover:underline">Clear search</button>
                                </template>
                            </td>
                        </tr>
                        <tr v-for="(quality, index) in filteredPaperQualities" :key="quality.id" 
                            :class="index % 2 === 0 ? 'bg-blue-100' : 'bg-white'"
                            class="hover:bg-blue-200">
                            <td class="px-2 py-2 border border-gray-300 text-xs">{{ quality.id }}</td>
                            <td class="px-2 py-2 border border-gray-300 text-xs">{{ quality.paper_quality || 'N/A' }}</td>
                            <td class="px-2 py-2 border border-gray-300 text-xs">{{ quality.paper_name || 'N/A' }}</td>
                            <td class="px-2 py-2 border border-gray-300 text-xs text-right">{{ formatNumber(quality.weight_kg_m, 4) }}</td>
                            <td class="px-2 py-2 border border-gray-300 text-xs">{{ quality.commercial_code || 'N/A' }}</td>
                            <td class="px-2 py-2 border border-gray-300 text-xs">{{ quality.wet_end_code || 'N/A' }}</td>
                            <td class="px-2 py-2 border border-gray-300 text-xs">{{ quality.decc_code || 'N/A' }}</td>
                            <td class="px-2 py-2 border border-gray-300 text-xs">{{ quality.status || 'N/A' }}</td>
                            <td class="px-2 py-2 border border-gray-300 text-xs">{{ quality.flute || 'N/A' }}</td>
                            <td class="px-2 py-2 border border-gray-300 text-xs">{{ quality.db || 'N/A' }}</td>
                            <td class="px-2 py-2 border border-gray-300 text-xs">{{ quality.b || 'N/A' }}</td>
                            <td class="px-2 py-2 border border-gray-300 text-xs">{{ quality.il || 'N/A' }}</td>
                            <td class="px-2 py-2 border border-gray-300 text-xs">{{ quality.a_c_e || 'N/A' }}</td>
                            <td class="px-2 py-2 border border-gray-300 text-xs">{{ quality['2l'] || 'N/A' }}</td>
                            <td class="px-2 py-2 border border-gray-300 text-xs">{{ quality.is_active ? 'Yes' : 'No' }}</td>
                            <td class="px-2 py-2 border border-gray-300 text-xs">{{ formatDate(quality.created_at) }}</td>
                            <td class="px-2 py-2 border border-gray-300 text-xs">{{ formatDate(quality.updated_at) }}</td>
                        </tr>
                    </tbody>
                </table>

                <!-- Table Footer -->
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 text-sm text-gray-500">
                    <div class="flex items-center justify-between">
                        <div>Total Paper Qualities: {{ filteredPaperQualities.length }}</div>
                        <div v-if="searchQuery">Filtered from {{ paperQualities.length }} total records</div>
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

// Data
const paperQualities = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const sortColumn = ref('paper_quality');
const sortDirection = ref('asc');
const currentDate = new Date().toLocaleString();

// Fetch paper qualities from API
const fetchPaperQualities = async () => {
    loading.value = true;
    try {
        const response = await fetch('/api/paper-qualities', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to fetch paper qualities');
        }
        
        const data = await response.json();
        paperQualities.value = data;
    } catch (error) {
        console.error('Error fetching paper qualities:', error);
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
        ? 'fas fa-sort-up text-blue-600' 
        : 'fas fa-sort-down text-blue-600';
};

// Format number
const formatNumber = (value, decimals = 2) => {
    if (value === null || value === undefined) return '0.' + '0'.repeat(decimals);
    return Number(value).toFixed(decimals);
};

// Format date
const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    return new Date(dateString).toLocaleString();
};

// Filtered and sorted paper qualities
const filteredPaperQualities = computed(() => {
    let filtered = [...paperQualities.value];
    
    // Apply search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(quality => 
            (quality.id && quality.id.toString().includes(query)) ||
            (quality.paper_quality && quality.paper_quality.toLowerCase().includes(query)) ||
            (quality.paper_name && quality.paper_name.toLowerCase().includes(query)) ||
            (quality.commercial_code && quality.commercial_code.toLowerCase().includes(query)) ||
            (quality.status && quality.status.toLowerCase().includes(query))
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
        if (['id', 'weight_kg_m'].includes(sortColumn.value)) {
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
        doc.setTextColor(37, 99, 235); // Blue color
        doc.text('PAPER QUALITY LIST', 10, 15);

        // Add subtitle
        doc.setFontSize(10);
        doc.setTextColor(100);
        doc.text('View and print paper quality data', 10, 22);

        // Prepare table data
        const tableData = filteredPaperQualities.value.map(quality => [
            quality.id || '',
            quality.paper_quality || 'N/A',
            quality.paper_name || 'N/A',
            formatNumber(quality.weight_kg_m, 4),
            quality.commercial_code || 'N/A',
            quality.wet_end_code || 'N/A',
            quality.decc_code || 'N/A',
            quality.status || 'N/A',
            quality.flute || 'N/A',
            quality.db || 'N/A',
            quality.b || 'N/A',
            quality.il || 'N/A',
            quality.a_c_e || 'N/A',
            quality['2l'] || 'N/A',
            quality.is_active ? 'Yes' : 'No',
            formatDate(quality.created_at),
            formatDate(quality.updated_at)
        ]);

        // Add table using autoTable
        autoTable(doc, {
            startY: 28,
            head: [['ID', 'Quality', 'Name', 'Weight', 'Comm', 'Wet-End', 'DECC', 'Status', 'Flute', 'DB', 'B', 'IL', 'A/C/E', '2L', 'Active', 'Created', 'Updated']],
            body: tableData,
            theme: 'grid',
            tableWidth: 'auto',
            headStyles: {
                fillColor: [37, 99, 235], // Blue background
                textColor: [255, 255, 255], // White text
                fontStyle: 'bold',
                halign: 'left',
                fontSize: 7
            },
            bodyStyles: {
                textColor: [50, 50, 50],
                halign: 'left',
                fontSize: 6
            },
            alternateRowStyles: {
                fillColor: [219, 234, 254] // Light blue for alternate rows
            },
            margin: { top: 28, left: 5, right: 5 },
            columnStyles: {
                0: { cellWidth: 10 },   // ID
                1: { cellWidth: 18 },   // Quality
                2: { cellWidth: 25 },   // Name
                3: { cellWidth: 15 },   // Weight
                4: { cellWidth: 15 },   // Comm
                5: { cellWidth: 18 },   // Wet-End
                6: { cellWidth: 15 },   // DECC
                7: { cellWidth: 15 },   // Status
                8: { cellWidth: 12 },   // Flute
                9: { cellWidth: 12 },   // DB
                10: { cellWidth: 12 },  // B
                11: { cellWidth: 12 },  // IL
                12: { cellWidth: 15 },  // A/C/E
                13: { cellWidth: 12 },  // 2L
                14: { cellWidth: 13 },  // Active
                15: { cellWidth: 25 },  // Created
                16: { cellWidth: 25 }   // Updated
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
                `Total Paper Qualities: ${filteredPaperQualities.value.length} | Generated: ${currentDate}`,
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
        doc.save(`paper-quality-list-${new Date().getTime()}.pdf`);
    } catch (error) {
        console.error('Error generating PDF:', error);
        alert('Error generating PDF. Please try again.');
    }
};

// Lifecycle hooks
onMounted(() => {
    fetchPaperQualities();
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
