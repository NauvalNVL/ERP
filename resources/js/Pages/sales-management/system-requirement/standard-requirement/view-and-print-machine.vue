<template>
    <AppLayout :header="'View & Print Machines'">
        <Head title="View & Print Machines" />

        <!-- Header Section -->
        <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
            <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
                <i class="fas fa-cogs mr-3"></i> View & Print Machines
            </h2>
            <p class="text-cyan-100">Preview and print machine data</p>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
            <!-- Actions Bar -->
            <div class="flex flex-wrap items-center justify-between mb-6">
                <div class="flex items-center space-x-2 mb-3 sm:mb-0">
                    <button @click="printTable" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                        <i class="fas fa-file-pdf mr-2"></i> Print PDF
                    </button>
                    <button @click="exportToExcel" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                        <i class="fas fa-file-excel mr-2"></i> Export Excel
                    </button>
                    <Link href="/machine" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Machine Management
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
                        placeholder="Search machines..."
                    >
                </div>
            </div>

            <!-- Filter Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Process:</label>
                    <select v-model="selectedProcess" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">All Processes</option>
                        <option v-for="process in uniqueProcesses" :key="process" :value="process">{{ process }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Resource Type:</label>
                    <select v-model="selectedResourceType" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">All Resource Types</option>
                        <option value="I-InHouse">I-InHouse</option>
                        <option value="E-External">E-External</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Finisher Type:</label>
                    <select v-model="selectedFinisherType" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">All Finisher Types</option>
                        <option value="S-Stitcher">S-Stitcher</option>
                        <option value="L-Stitcher">L-Stitcher</option>
                        <option value="G-Gluer">G-Gluer</option>
                        <option value="A-Assembler">A-Assembler</option>
                        <option value="P-Packing">P-Packing</option>
                        <option value="X-N/Applicable">X-N/Applicable</option>
                    </select>
                </div>
            </div>

            <!-- Table Section -->
            <div class="overflow-x-auto">
                <div id="printableTable" class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                    <!-- Table Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white py-4 px-6 flex items-center">
                        <div class="flex items-center">
                            <div class="mr-4">
                                <i class="fas fa-cogs text-3xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold">MACHINE DATA LIST</h2>
                                <p class="text-sm opacity-80">View and print machine data</p>
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
                                <th @click="sortTable('machine_code')" class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                    MACHINE CODE <i :class="getSortIcon('machine_code')" class="text-xs"></i>
                                </th>
                                <th @click="sortTable('machine_name')" class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                    MACHINE NAME <i :class="getSortIcon('machine_name')" class="text-xs"></i>
                                </th>
                                <th @click="sortTable('process')" class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                    PROCESS <i :class="getSortIcon('process')" class="text-xs"></i>
                                </th>
                                <th @click="sortTable('sub_process')" class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                    SUB PROCESS <i :class="getSortIcon('sub_process')" class="text-xs"></i>
                                </th>
                                <th @click="sortTable('resource_type')" class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                    RESOURCE TYPE <i :class="getSortIcon('resource_type')" class="text-xs"></i>
                                </th>
                                <th @click="sortTable('finisher_type')" class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                    FINISHER TYPE <i :class="getSortIcon('finisher_type')" class="text-xs"></i>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            <tr v-if="isLoading">
                                <td colspan="7" class="px-4 py-3 text-center text-gray-500 border border-gray-300">
                                    <div class="flex justify-center">
                                        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"></div>
                                    </div>
                                    <p class="mt-2">Loading machine data...</p>
                                </td>
                            </tr>
                            <tr v-else-if="filteredMachines.length === 0">
                                <td colspan="7" class="px-4 py-3 text-center text-gray-500 border border-gray-300">
                                    No machines found.
                                    <template v-if="searchQuery || selectedProcess || selectedResourceType || selectedFinisherType">
                                        <p class="mt-2">No results match your search criteria.</p>
                                        <button @click="clearFilters" class="mt-2 text-blue-500 hover:underline">Clear filters</button>
                                    </template>
                                </td>
                            </tr>
                            <tr v-for="(machine, index) in filteredMachines" :key="machine.id"
                                :class="index % 2 === 0 ? 'bg-blue-100' : 'bg-white'"
                                class="hover:bg-blue-200">
                                <td class="px-4 py-2 border border-gray-300 text-center">
                                    <div class="text-sm font-medium text-gray-900">{{ index + 1 }}</div>
                                </td>
                                <td class="px-4 py-2 border border-gray-300">
                                    <div class="text-sm font-medium text-gray-900">{{ machine.machine_code || '' }}</div>
                                </td>
                                <td class="px-4 py-2 border border-gray-300">
                                    <div class="text-sm text-gray-900">{{ machine.machine_name || '' }}</div>
                                </td>
                                <td class="px-4 py-2 border border-gray-300">
                                    <div class="text-sm text-gray-900">{{ machine.process || '-' }}</div>
                                </td>
                                <td class="px-4 py-2 border border-gray-300">
                                    <div class="text-sm text-gray-900">{{ machine.sub_process || '-' }}</div>
                                </td>
                                <td class="px-4 py-2 border border-gray-300">
                                    <div class="text-sm text-gray-900">{{ machine.resource_type || '-' }}</div>
                                </td>
                                <td class="px-4 py-2 border border-gray-300">
                                    <div class="text-sm text-gray-900">{{ machine.finisher_type || '-' }}</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Summary -->
                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                    <div class="text-sm text-gray-600">
                        Total Machines: <span class="font-semibold">{{ filteredMachines.length }}</span>
                        <span v-if="searchQuery || selectedProcess || selectedResourceType || selectedFinisherType">
                            (filtered from {{ machines.length }} total)
                        </span>
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
import axios from 'axios';
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';

// Props
const props = defineProps({
    machines: {
        type: Array,
        default: () => []
    }
});

// Reactive data
const machines = ref(props.machines || []);
const isLoading = ref(false);
const selectedProcess = ref('');
const selectedResourceType = ref('');
const selectedFinisherType = ref('');
const searchQuery = ref('');
const sortKey = ref('machine_code');
const sortAsc = ref(true);

// Computed properties
const uniqueProcesses = computed(() => {
    const processes = machines.value
        .map(machine => machine.process)
        .filter(process => process && process.trim() !== '');
    return [...new Set(processes)].sort();
});

const filteredMachines = computed(() => {
    let filtered = machines.value;

    // Apply search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(machine =>
            (machine.machine_code && machine.machine_code.toLowerCase().includes(query)) ||
            (machine.machine_name && machine.machine_name.toLowerCase().includes(query)) ||
            (machine.process && machine.process.toLowerCase().includes(query)) ||
            (machine.sub_process && machine.sub_process.toLowerCase().includes(query)) ||
            (machine.resource_type && machine.resource_type.toLowerCase().includes(query)) ||
            (machine.finisher_type && machine.finisher_type.toLowerCase().includes(query))
        );
    }

    // Apply dropdown filters
    if (selectedProcess.value) {
        filtered = filtered.filter(machine => machine.process === selectedProcess.value);
    }

    if (selectedResourceType.value) {
        filtered = filtered.filter(machine => machine.resource_type === selectedResourceType.value);
    }

    if (selectedFinisherType.value) {
        filtered = filtered.filter(machine => machine.finisher_type === selectedFinisherType.value);
    }

    // Apply sorting
    return [...filtered].sort((a, b) => {
        const aVal = a[sortKey.value] || '';
        const bVal = b[sortKey.value] || '';
        
        if (aVal < bVal) return sortAsc.value ? -1 : 1;
        if (aVal > bVal) return sortAsc.value ? 1 : -1;
        return 0;
    });
});

// Methods
const fetchMachines = async () => {
    try {
        isLoading.value = true;
        const response = await axios.get('/api/machines');
        
        if (response.data && Array.isArray(response.data)) {
            machines.value = response.data;
        } else {
            console.error('Invalid response format:', response.data);
            machines.value = [];
        }
    } catch (error) {
        console.error('Error fetching machines:', error);
        machines.value = [];
    } finally {
        isLoading.value = false;
    }
};

const sortTable = (key) => {
    if (sortKey.value === key) {
        sortAsc.value = !sortAsc.value;
    } else {
        sortKey.value = key;
        sortAsc.value = true;
    }
};

const getSortIcon = (key) => {
    if (sortKey.value !== key) {
        return 'fas fa-sort text-gray-400';
    }
    return sortAsc.value ? 'fas fa-sort-up text-blue-600' : 'fas fa-sort-down text-blue-600';
};

const clearFilters = () => {
    searchQuery.value = '';
    selectedProcess.value = '';
    selectedResourceType.value = '';
    selectedFinisherType.value = '';
};

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
        doc.text('MACHINE DATA LIST', 10, 15);

        // Add subtitle
        doc.setFontSize(10);
        doc.setTextColor(100);
        doc.text('View and print machine data', 10, 22);

        // Add generation info
        doc.setFontSize(8);
        doc.setTextColor(100);
        doc.text(`Generated on: ${new Date().toLocaleString()}`, 10, 28);
        doc.text(`Total Machines: ${filteredMachines.value.length}`, 10, 32);

        // Prepare table data
        const tableData = filteredMachines.value.map((machine, index) => [
            (index + 1).toString(),
            machine.machine_code || '',
            machine.machine_name || '',
            machine.process || '-',
            machine.sub_process || '-',
            machine.resource_type || '-',
            machine.finisher_type || '-'
        ]);

        // Add table using autoTable - use the same pattern as industry.vue
        autoTable(doc, {
            startY: 38,
            head: [['NO.', 'MACHINE CODE', 'MACHINE NAME', 'PROCESS', 'SUB PROCESS', 'RESOURCE TYPE', 'FINISHER TYPE']],
            body: tableData,
            theme: 'grid',
            tableWidth: 'auto',
            headStyles: {
                fillColor: [37, 99, 235], // Blue background
                textColor: [255, 255, 255], // White text
                fontStyle: 'bold',
                halign: 'left',
                fontSize: 9
            },
            bodyStyles: {
                textColor: [50, 50, 50],
                halign: 'left',
                fontSize: 8
            },
            alternateRowStyles: {
                fillColor: [219, 234, 254] // Light blue for alternate rows
            },
            columnStyles: {
                0: { cellWidth: 15, halign: 'center' }, // NO.
                1: { cellWidth: 25 }, // Machine Code
                2: { cellWidth: 35 }, // Machine Name
                3: { cellWidth: 30 }, // Process
                4: { cellWidth: 30 }, // Sub Process
                5: { cellWidth: 25 }, // Resource Type
                6: { cellWidth: 25 }  // Finisher Type
            },
            margin: { top: 38, left: 10, right: 10 },
            didDrawPage: function (data) {
                // Add page number
                doc.setFontSize(8);
                doc.setTextColor(100);
                doc.text(
                    `Page ${data.pageNumber}`,
                    doc.internal.pageSize.getWidth() - 30,
                    doc.internal.pageSize.getHeight() - 10
                );
            }
        });

        // Save the PDF
        doc.save(`Machine_List_${new Date().toISOString().split('T')[0]}.pdf`);
        
    } catch (error) {
        console.error('Error generating PDF:', error);
    }
};

const exportToExcel = () => {
    const csvContent = generateCSV();
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    
    if (link.download !== undefined) {
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', `machine_list_${new Date().toISOString().split('T')[0]}.csv`);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
};

const generateCSV = () => {
    const headers = ['Machine Code', 'Machine Name', 'Process', 'Sub Process', 'Resource Type', 'Finisher Type'];
    const csvRows = [headers.join(',')];
    
    filteredMachines.value.forEach(machine => {
        const row = [
            `"${machine.machine_code || ''}"`,
            `"${machine.machine_name || ''}"`,
            `"${machine.process || ''}"`,
            `"${machine.sub_process || ''}"`,
            `"${machine.resource_type || ''}"`,
            `"${machine.finisher_type || ''}"`
        ];
        csvRows.push(row.join(','));
    });
    
    return csvRows.join('\n');
};

// Notification function removed to eliminate popups

// Lifecycle
onMounted(() => {
    if (!machines.value || machines.value.length === 0) {
        fetchMachines();
    }
});
</script>

<style scoped>
/* Custom styles for better print layout */
@media print {
    .no-print {
        display: none !important;
    }
    
    table {
        font-size: 10px;
    }
    
    th, td {
        padding: 4px;
    }
}

/* Hover effects */
tbody tr:hover {
    background-color: #f8f9fa;
}

/* Responsive table */
@media (max-width: 768px) {
    .overflow-x-auto {
        -webkit-overflow-scrolling: touch;
    }
}
</style>