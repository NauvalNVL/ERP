<template>
    <AppLayout :header="'View & Print Product Designs'">
    <Head title="View & Print Product Designs" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 p-6 rounded-t-lg shadow-lg">
        <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
            <i class="fas fa-print mr-3"></i> View & Print Product Designs
        </h2>
        <p class="text-emerald-100">Preview and print product design data</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
        <!-- Actions Bar -->
        <div class="flex flex-wrap items-center justify-between mb-6">
            <div class="flex items-center space-x-2 mb-3 sm:mb-0">
                <button @click="printTable" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-file-pdf mr-2"></i> Print PDF
                </button>
                <Link href="/product-design" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Product Designs
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
                    placeholder="Search product designs..."
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
                            <i class="fas fa-drafting-compass text-3xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold">PRODUCT DESIGN LIST</h2>
                            <p class="text-sm opacity-80">View and print product design data</p>
                        </div>
                    </div>
                </div>

                <!-- Table Content -->
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th @click="sortTable('pd_code')" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Design Code <i :class="getSortIcon('pd_code')"></i>
                            </th>
                            <th @click="sortTable('pd_name')" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Design Name <i :class="getSortIcon('pd_name')"></i>
                            </th>
                            <th @click="sortTable('pd_design_type')" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Design Type <i :class="getSortIcon('pd_design_type')"></i>
                            </th>
                            <th @click="sortTable('idc')" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                IDC <i :class="getSortIcon('idc')"></i>
                            </th>
                            <th @click="sortTable('product')" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Product <i :class="getSortIcon('product')"></i>
                            </th>
                            <th @click="sortTable('joint')" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Joint <i :class="getSortIcon('joint')"></i>
                            </th>
                            <th @click="sortTable('joint_to_print')" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Joint to Print <i :class="getSortIcon('joint_to_print')"></i>
                            </th>
                            <th @click="sortTable('pcs_to_joint')" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                PCS to Joint <i :class="getSortIcon('pcs_to_joint')"></i>
                            </th>
                            <th @click="sortTable('score')" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Score <i :class="getSortIcon('score')"></i>
                            </th>
                            <th @click="sortTable('slot')" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Slot <i :class="getSortIcon('slot')"></i>
                            </th>
                            <th @click="sortTable('flute_style')" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Flute Style <i :class="getSortIcon('flute_style')"></i>
                            </th>
                            <th @click="sortTable('print_flute')" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Print Flute <i :class="getSortIcon('print_flute')"></i>
                            </th>
                            <th @click="sortTable('input_weight')" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Input Weight <i :class="getSortIcon('input_weight')"></i>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-if="loading" class="hover:bg-gray-50">
                            <td colspan="13" class="px-3 py-4 text-center text-gray-500">
                                <div class="flex justify-center">
                                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"></div>
                                </div>
                                <p class="mt-2">Loading product design data...</p>
                            </td>
                        </tr>
                        <tr v-else-if="filteredProductDesigns.length === 0" class="hover:bg-gray-50">
                            <td colspan="13" class="px-3 py-4 text-center text-gray-500">
                                No product designs found.
                                <template v-if="searchQuery">
                                    <p class="mt-2">No results match your search query: "{{ searchQuery }}"</p>
                                    <button @click="searchQuery = ''" class="mt-2 text-blue-500 hover:underline">Clear search</button>
                                </template>
                            </td>
                        </tr>
                        <tr v-for="(design, index) in filteredProductDesigns" :key="design.pd_code"
                            :class="{'bg-blue-50': index % 2 === 0}"
                            class="hover:bg-blue-100">
                            <td class="px-3 py-4 whitespace-nowrap text-sm font-medium">{{ design.pd_code || 'N/A' }}</td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm">{{ design.pd_name || 'N/A' }}</td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm">{{ design.pd_design_type || 'N/A' }}</td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm">{{ design.idc || 'N/A' }}</td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">{{ design.product || 'N/A' }}</span>
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm">{{ design.joint || 'N/A' }}</td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm">{{ design.joint_to_print || 'N/A' }}</td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm">{{ design.pcs_to_joint || 'N/A' }}</td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm">{{ design.score || 'N/A' }}</td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm">{{ design.slot || 'N/A' }}</td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm">{{ design.flute_style || 'N/A' }}</td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm">{{ design.print_flute || 'N/A' }}</td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm">{{ design.input_weight || 'N/A' }}</td>
                        </tr>
                    </tbody>
                </table>

                <!-- Table Footer -->
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 text-sm text-gray-500">
                    <div class="flex items-center justify-between">
                        <div>Total Product Designs: {{ filteredProductDesigns.length }}</div>
                        <div v-if="searchQuery">Filtered from {{ productDesigns.length }} total records</div>
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
const productDesigns = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const sortColumn = ref('pd_code');
const sortDirection = ref('asc');
const currentDate = new Date().toLocaleString();

// Fetch product designs from the API
const fetchProductDesigns = async () => {
    loading.value = true;
    try {
        const response = await fetch('/api/product-designs', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            throw new Error('Failed to fetch product designs');
        }

        const data = await response.json();

        // Handle different API response formats
        if (data.data) {
            productDesigns.value = data.data;
        } else if (Array.isArray(data)) {
            productDesigns.value = data;
        } else {
            console.error('Unexpected API response format:', data);
            productDesigns.value = [];
        }
    } catch (error) {
        console.error('Error fetching product designs:', error);
        productDesigns.value = [];
    } finally {
        loading.value = false;
    }
};

// Format date
const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    return new Date(dateString).toLocaleString();
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
        ? 'fas fa-sort-up text-blue-500'
        : 'fas fa-sort-down text-blue-500';
};

// Filtered and sorted product designs
const filteredProductDesigns = computed(() => {
    let filtered = [...productDesigns.value];

    // Apply search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(design =>
            (design.pd_code && design.pd_code.toLowerCase().includes(query)) ||
            (design.pd_name && design.pd_name.toLowerCase().includes(query)) ||
            (design.pd_design_type && design.pd_design_type.toLowerCase().includes(query)) ||
            (design.product && design.product.toLowerCase().includes(query)) ||
            (design.idc && design.idc.toLowerCase().includes(query)) ||
            (design.joint && design.joint.toLowerCase().includes(query)) ||
            (design.joint_to_print && design.joint_to_print.toLowerCase().includes(query)) ||
            (design.pcs_to_joint && design.pcs_to_joint.toLowerCase().includes(query)) ||
            (design.score && design.score.toLowerCase().includes(query)) ||
            (design.slot && design.slot.toLowerCase().includes(query)) ||
            (design.flute_style && design.flute_style.toLowerCase().includes(query)) ||
            (design.print_flute && design.print_flute.toLowerCase().includes(query)) ||
            (design.input_weight && design.input_weight.toLowerCase().includes(query))
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
        doc.text('PRODUCT DESIGN LIST', 10, 15);

        // Add subtitle
        doc.setFontSize(10);
        doc.setTextColor(100);
        doc.text('View and print product design data', 10, 22);

        // Prepare table data
        const tableData = filteredProductDesigns.value.map(design => [
            design.pd_code || 'N/A',
            design.pd_name || 'N/A',
            design.pd_design_type || 'N/A',
            design.idc || 'N/A',
            design.product || 'N/A',
            design.joint || 'N/A',
            design.joint_to_print || 'N/A',
            design.pcs_to_joint || 'N/A',
            design.score || 'N/A',
            design.slot || 'N/A',
            design.flute_style || 'N/A',
            design.print_flute || 'N/A',
            design.input_weight || 'N/A'
        ]);

        // Add table using autoTable
        autoTable(doc, {
            startY: 28,
            head: [[
                'Design Code',
                'Design Name',
                'Type',
                'IDC',
                'Product',
                'Joint',
                'Joint to Print',
                'PCS',
                'Score',
                'Slot',
                'Flute Style',
                'Print Flute',
                'Input Weight'
            ]],
            body: tableData,
            theme: 'grid',
            tableWidth: 'auto',
            headStyles: {
                fillColor: [37, 99, 235], // Blue background
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
                fillColor: [219, 234, 254] // Light blue for alternate rows
            },
            margin: { top: 28, left: 10, right: 10 },
            columnStyles: {
                0: { cellWidth: 20 },  // Design Code
                1: { cellWidth: 25 },  // Design Name
                2: { cellWidth: 18 },  // Type
                3: { cellWidth: 15 },  // IDC
                4: { cellWidth: 18 },  // Product
                5: { cellWidth: 15 },  // Joint
                6: { cellWidth: 20 },  // Joint to Print
                7: { cellWidth: 12 },  // PCS
                8: { cellWidth: 15 },  // Score
                9: { cellWidth: 15 },  // Slot
                10: { cellWidth: 20 }, // Flute Style
                11: { cellWidth: 18 }, // Print Flute
                12: { cellWidth: 20 }  // Input Weight
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
                `Total Product Designs: ${filteredProductDesigns.value.length} | Generated: ${currentDate}`,
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
        doc.save(`product-design-list-${new Date().getTime()}.pdf`);
    } catch (error) {
        console.error('Error generating PDF:', error);
        alert('Error generating PDF. Please try again.');
    }
};

// Fetch data on component mount
onMounted(() => {
    fetchProductDesigns();
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
