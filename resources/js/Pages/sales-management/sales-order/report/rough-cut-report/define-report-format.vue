<template>
    <AppLayout :header="'Define Rough Cut Report Format'">
        <Head title="Define Rough Cut Report Format" />

        <!-- Header Section -->
        <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
            <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
                <i class="fas fa-file-alt mr-3"></i> Define Report Format
            </h2>
            <p class="text-cyan-100">Set up and manage report formats.</p>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Left Panel: Input Form and Buttons -->
                <div class="w-full md:w-1/3 bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-cog mr-2 text-blue-500"></i> Report Format Entry
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label for="reportCode" class="block text-sm font-medium text-gray-700">Report Code:</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <input
                                    type="text"
                                    name="reportCode"
                                    id="reportCode"
                                    v-model="form.reportCode"
                                    class="flex-1 block w-full rounded-none rounded-l-md border-gray-300 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="Enter Report Code"
                                />
                                <button
                                    type="button"
                                    @click="showReportFormatTable"
                                    class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-white text-sm text-gray-500 rounded-r-md hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                >
                                    <i class="fas fa-table"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="flex justify-around mt-4">
                            <button @click="powerOff" class="btn-red-icon">
                                <i class="fas fa-power-off"></i>
                            </button>
                            <button @click="prevReport" class="btn-blue-icon">
                                <i class="fas fa-arrow-left"></i>
                            </button>
                            <button @click="nextReport" class="btn-blue-icon">
                                <i class="fas fa-arrow-right"></i>
                            </button>
                            <button @click="loadReport" class="btn-green-icon">
                                <i class="fas fa-sign-in-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right Panel: Report Format Table (conditionally displayed) -->
                <div v-if="showTable" class="w-full md:w-2/3 bg-white rounded-lg overflow-hidden border border-gray-200 shadow-md">
                    <div class="p-4 border-b border-gray-200 bg-gray-100 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-table mr-2"></i> Report Format Table
                        </h3>
                        <button @click="hideReportFormatTable" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th @click="sortTable('code')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                        CODE <i class="fas fa-sort ml-1"></i>
                                    </th>
                                    <th @click="sortTable('name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                        NAME <i class="fas fa-sort ml-1"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-if="loading" class="animate-pulse">
                                    <td colspan="2" class="px-6 py-4 text-center text-sm text-gray-500">
                                        <div class="flex justify-center items-center space-x-2">
                                            <i class="fas fa-spinner fa-spin"></i>
                                            <span>Loading report formats...</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-else-if="filteredReportFormats.length === 0" class="hover:bg-gray-50">
                                    <td colspan="2" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No report formats found.
                                    </td>
                                </tr>
                                <tr v-for="format in filteredReportFormats" :key="format.code" 
                                    @click="selectReportFormat(format)" 
                                    :class="{'bg-blue-50': selectedReportFormat && selectedReportFormat.code === format.code}"
                                    class="hover:bg-gray-50 cursor-pointer">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ format.code }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ format.name }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination Controls -->
                    <div class="mt-4 flex justify-between items-center text-sm text-gray-600 p-4">
                        <div>
                            <span>Showing {{ filteredReportFormats.length }} of {{ reportFormats.length }} formats</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <select v-model="itemsPerPage" class="border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option :value="10">10 per page</option>
                                <option :value="20">20 per page</option>
                                <option :value="50">50 per page</option>
                                <option :value="100">100 per page</option>
                            </select>
                            <button :disabled="currentPage === 1" @click="currentPage--" class="px-2 py-1 border rounded hover:bg-gray-200 disabled:opacity-50">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <span class="px-4">{{ currentPage }} / {{ totalPages }}</span>
                            <button :disabled="currentPage === totalPages" @click="currentPage++" class="px-2 py-1 border rounded hover:bg-gray-200 disabled:opacity-50">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- No Table Selected Placeholder -->
                <div v-else class="w-full md:w-2/3 bg-gray-50 rounded-lg p-4 border border-gray-200 flex flex-col items-center justify-center text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-table text-blue-500 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No Report Format Table Displayed</h3>
                    <p class="text-gray-500 mb-4">Click the table icon next to the Report Code to view report formats.</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';
import { useToast } from '@/Composables/useToast';

const toast = useToast();

const form = ref({
    reportCode: '',
});

const showTable = ref(false);
const reportFormats = ref([]);
const loading = ref(false);
const searchQuery = ref('');
const selectedReportFormat = ref(null);

const currentPage = ref(1);
const itemsPerPage = ref(10);
const sortOrder = ref({
    field: 'code',
    direction: 'asc'
});

const filteredReportFormats = computed(() => {
    let result = reportFormats.value;

    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        result = result.filter(format => (
            (format.code && format.code.toLowerCase().includes(query)) ||
            (format.name && format.name.toLowerCase().includes(query))
        ));
    }

    result = [...result].sort((a, b) => {
        const field = sortOrder.value.field;
        const direction = sortOrder.value.direction === 'asc' ? 1 : -1;
        
        if ((a[field] || '') < (b[field] || '')) return -1 * direction;
        if ((a[field] || '') > (b[field] || '')) return 1 * direction;
        return 0;
    });

    return result;
});

const paginatedReportFormats = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return filteredReportFormats.value.slice(start, end);
});

const totalPages = computed(() => {
    return Math.max(1, Math.ceil(filteredReportFormats.value.length / itemsPerPage.value));
});

const fetchReportFormats = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/api/report-formats'); // New API endpoint
        reportFormats.value = response.data;
        toast.success('Report formats loaded successfully!');
    } catch (error) {
        console.error('Error fetching report formats:', error);
        toast.error('Failed to load report formats.');
    } finally {
        loading.value = false;
    }
};

const showReportFormatTable = () => {
    showTable.value = true;
    fetchReportFormats();
};

const hideReportFormatTable = () => {
    showTable.value = false;
};

const selectReportFormat = (format) => {
    selectedReportFormat.value = format;
    form.value.reportCode = format.code; // Populate the input field
    hideReportFormatTable(); // Hide table after selection
};

const sortTable = (field) => {
    if (sortOrder.value.field === field) {
        sortOrder.value.direction = sortOrder.value.direction === 'asc' ? 'desc' : 'asc';
    } else {
        sortOrder.value.field = field;
        sortOrder.value.direction = 'asc';
    }
};

// Dummy functions for buttons (to be implemented with logic later)
const powerOff = () => {
    toast.info('Power Off button clicked');
};

const prevReport = () => {
    toast.info('Previous Report button clicked');
};

const nextReport = () => {
    toast.info('Next Report button clicked');
};

const loadReport = () => {
    toast.info(`Load Report button clicked for code: ${form.value.reportCode}`);
    // Here you would load the actual report based on the reportCode
};

// Watch for changes in filtered reports to update pagination
watch(filteredReportFormats, () => {
    currentPage.value = 1;
});

// Watch for changes in items per page
watch(itemsPerPage, () => {
    currentPage.value = 1;
});

</script>

<style scoped>
/* Your existing styles or new styles */
.btn-red-icon {
    @apply bg-red-500 hover:bg-red-600 text-white p-2 rounded-full shadow-md transition-colors;
}
.btn-blue-icon {
    @apply bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-full shadow-md transition-colors;
}
.btn-green-icon {
    @apply bg-green-500 hover:bg-green-600 text-white p-2 rounded-full shadow-md transition-colors;
}
</style> 