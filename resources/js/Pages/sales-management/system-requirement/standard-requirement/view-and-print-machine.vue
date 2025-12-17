<template>
    <AppLayout :header="'View & Print Machines'">
        <Head title="View & Print Machines" />

        <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
            <div class="max-w-6xl mx-auto">
            <!-- Header Section -->
            <div class="bg-emerald-600 text-white shadow-sm rounded-xl border border-emerald-700 mb-4">
                <div class="px-4 py-3 sm:px-6 flex items-center gap-3">
                    <div class="h-9 w-9 rounded-full bg-emerald-500 flex items-center justify-center">
                        <i class="fas fa-print text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg sm:text-xl font-semibold leading-tight">View & Print Machines</h2>
                        <p class="text-xs sm:text-sm text-emerald-100">Preview and print machine data</p>
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
                    <Link href="/machine" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-md flex items-center space-x-2 border border-gray-200">
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
                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-emerald-500 focus:border-emerald-500"
                        placeholder="Search machines..."
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
                        <thead class="bg-gray-50">
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
                                <th @click="sortTable('status')" class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer" style="color: black; width: 90px;">
                                    STATUS <i :class="getSortIcon('status')" class="text-xs"></i>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            <tr v-if="isLoading">
                                <td colspan="8" class="px-4 py-3 text-center text-gray-500 border border-gray-300">
                                    <div class="flex justify-center">
                                        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-emerald-500"></div>
                                    </div>
                                    <p class="mt-2">Loading machine data...</p>
                                </td>
                            </tr>
                            <tr v-else-if="filteredMachines.length === 0">
                                <td colspan="8" class="px-4 py-3 text-center text-gray-500 border border-gray-300">
                                    No machines found.
                                    <template v-if="searchQuery">
                                        <p class="mt-2">No results match your search query.</p>
                                        <button @click="searchQuery = ''" class="mt-2 text-emerald-600 hover:underline">Clear search</button>
                                    </template>
                                </td>
                            </tr>
                            <tr v-for="(machine, index) in filteredMachines" :key="machine.id"
                                :class="index % 2 === 0 ? 'bg-emerald-50' : 'bg-white'"
                                class="hover:bg-emerald-100">
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
                                <td class="px-4 py-2 border border-gray-300">
                                    <div class="text-sm text-gray-900">{{ getStatusValue(machine) }}</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Summary -->
                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                    <div class="text-sm text-gray-600">
                        Total Machines: <span class="font-semibold">{{ filteredMachines.length }}</span>
                        <span v-if="searchQuery">
                            (filtered from {{ machines.length }} total)
                        </span>
                    </div>
                </div>
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
const searchQuery = ref('');
const sortKey = ref('machine_code');
const sortAsc = ref(true);

// Computed properties
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
            (machine.finisher_type && machine.finisher_type.toLowerCase().includes(query)) ||
            (getStatusValue(machine) && getStatusValue(machine).toLowerCase().includes(query))
        );
    }

    // Apply sorting
    return [...filtered].sort((a, b) => {
        const aVal = sortKey.value === 'status' ? getStatusValue(a) : (a[sortKey.value] || '');
        const bVal = sortKey.value === 'status' ? getStatusValue(b) : (b[sortKey.value] || '');
        
        if (aVal < bVal) return sortAsc.value ? -1 : 1;
        if (aVal > bVal) return sortAsc.value ? 1 : -1;
        return 0;
    });
});

// Methods
const fetchMachines = async () => {
    try {
        isLoading.value = true;
        const response = await axios.get('/api/machines?all_status=1');

        if (response.data && Array.isArray(response.data)) {
            machines.value = response.data;
            return;
        }

        if (response.data && Array.isArray(response.data.machines)) {
            machines.value = response.data.machines;
            return;
        }

        console.error('Invalid response format:', response.data);
        machines.value = [];
    } catch (error) {
        console.error('Error fetching machines:', error);
        machines.value = [];
    } finally {
        isLoading.value = false;
    }
};

const getStatusValue = (row) => {
    if (!row) return '';
    if (row.status) return String(row.status).trim();
    if (row.STATUS) return String(row.STATUS).trim();
    if (typeof row.is_active === 'boolean') return row.is_active ? 'Act' : 'Obs';
    return '';
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
            machine.finisher_type || '-',
            getStatusValue(machine)
        ]);

        // Add table using autoTable - use the same pattern as industry.vue
        autoTable(doc, {
            startY: 38,
            head: [['NO.', 'MACHINE CODE', 'MACHINE NAME', 'PROCESS', 'SUB PROCESS', 'RESOURCE TYPE', 'FINISHER TYPE', 'STATUS']],
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
                6: { cellWidth: 25 },  // Finisher Type
                7: { cellWidth: 18 }   // Status
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