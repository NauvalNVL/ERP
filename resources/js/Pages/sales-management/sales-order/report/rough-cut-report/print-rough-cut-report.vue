<template>
    <AppLayout :header="'Print Rough Cut Report'">
        <Head title="Print Rough Cut Report" />

        <!-- Header Section -->
        <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
            <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
                <i class="fas fa-print mr-3"></i> Print Rough Cut Report
            </h2>
            <p class="text-cyan-100">Generate and print rough cut reports.</p>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
            <!-- Navigation Buttons -->
            <div class="flex space-x-2 mb-6">
                <button @click="powerOff" class="btn-red-icon">
                    <i class="fas fa-power-off"></i>
                </button>
                <button @click="loadReport" class="btn-green-icon">
                    <i class="fas fa-sign-in-alt"></i>
                </button>
            </div>

            <!-- Form Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="flex items-center">
                    <label for="currentSOPeriod" class="w-36 text-gray-700 font-semibold">Current S/O Period:</label>
                    <input
                        type="text"
                        id="currentSOPeriod"
                        v-model="form.currentSOPeriod"
                        class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        placeholder="mm/yyyy"
                    />
                </div>

                <div class="flex items-center">
                    <label for="reportFormat" class="w-36 text-gray-700 font-semibold">Report Format:</label>
                    <div class="flex-1 flex rounded-md shadow-sm">
                        <input
                            type="text"
                            name="reportFormat"
                            id="reportFormat"
                            v-model="form.reportFormat"
                            class="flex-1 block w-full rounded-none rounded-l-md border-gray-300 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            placeholder="Enter Report Format Code"
                        />
                        <button
                            type="button"
                            @click="openReportFormatModal"
                            class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-white text-sm text-gray-500 rounded-r-md hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                        >
                            <i class="fas fa-table"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center">
                    <label for="periodStartMonth" class="w-36 text-gray-700 font-semibold">Period:</label>
                    <input
                        type="number"
                        id="periodStartMonth"
                        v-model="form.periodStartMonth"
                        class="w-20 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm mr-2"
                        placeholder="MM"
                    />
                    <input
                        type="number"
                        id="periodStartYear"
                        v-model="form.periodStartYear"
                        class="w-24 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        placeholder="YYYY"
                    />
                    <span class="mx-2">to</span>
                    <input
                        type="number"
                        id="periodEndMonth"
                        v-model="form.periodEndMonth"
                        class="w-20 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm mr-2"
                        placeholder="MM"
                    />
                    <input
                        type="number"
                        id="periodEndYear"
                        v-model="form.periodEndYear"
                        class="w-24 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        placeholder="YYYY"
                    />
                    <span class="ml-2 text-gray-500">mm/yyyy</span>
                </div>

                <div class="flex items-center">
                    <label for="dueDateStart" class="w-36 text-gray-700 font-semibold">Due Date:</label>
                    <input
                        type="date"
                        id="dueDateStart"
                        v-model="form.dueDateStart"
                        class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    />
                    <span class="mx-2">to</span>
                    <input
                        type="date"
                        id="dueDateEnd"
                        v-model="form.dueDateEnd"
                        class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    />
                    <button class="ml-2 p-2 rounded-md border border-gray-300 bg-white hover:bg-gray-50 text-gray-500 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        <i class="fas fa-calendar-alt"></i>
                    </button>
                </div>
            </div>

            <!-- Report Content Area (placeholder for now) -->
            <div class="mt-8 p-4 bg-gray-50 border border-gray-200 rounded-lg text-center text-gray-600">
                <p>Report content will appear here after criteria are selected and 'Load' is clicked.</p>
                <p class="mt-2 text-sm text-gray-500">This area will display the generated report based on your selections.</p>
            </div>
        </div>

        <!-- Report Format Modal -->
        <div v-if="showReportFormatModal" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-4 py-3 sm:px-6 flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-medium text-white flex items-center">
                            <i class="fas fa-table mr-2"></i>
                            Report Format Table
                        </h3>
                        <button @click="hideReportFormatModal" class="text-white hover:text-gray-200 focus:outline-none">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="bg-gray-50 px-4 py-3">
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" v-model="reportFormatSearch" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Search report formats...">
                        </div>
                    </div>

                    <div class="max-h-96 overflow-y-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100 sticky top-0">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider bg-blue-50 border-b-2 border-blue-200">
                                        CODE
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider bg-blue-50 border-b-2 border-blue-200">
                                        NAME
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-if="loadingReportFormats" class="animate-pulse">
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
                                    class="hover:bg-blue-50 cursor-pointer transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ format.code }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ format.name }}</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="bg-gray-100 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" @click="selectReportFormat(null)" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Exit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';
import { useToast } from '@/Composables/useToast';

const toast = useToast();

const form = ref({
    currentSOPeriod: '', // Will be set on mounted
    reportFormat: '',
    periodStartMonth: null,
    periodStartYear: null,
    periodEndMonth: null,
    periodEndYear: null,
    dueDateStart: '',
    dueDateEnd: '',
});

const showReportFormatModal = ref(false);
const reportFormats = ref([]);
const loadingReportFormats = ref(false);
const reportFormatSearch = ref('');

const filteredReportFormats = computed(() => {
    if (!reportFormats.value) return [];
    const query = reportFormatSearch.value.toLowerCase();
    return reportFormats.value.filter(format => (
        (format.code && format.code.toLowerCase().includes(query)) ||
        (format.name && format.name.toLowerCase().includes(query))
    ));
});

const fetchReportFormats = async () => {
    loadingReportFormats.value = true;
    try {
        const response = await axios.get('/api/report-formats');
        reportFormats.value = response.data;
        toast.success('Report formats loaded successfully!');
    } catch (error) {
        console.error('Error fetching report formats:', error);
        toast.error('Failed to load report formats.');
    } finally {
        loadingReportFormats.value = false;
    }
};

const openReportFormatModal = () => {
    fetchReportFormats();
    showReportFormatModal.value = true;
};

const hideReportFormatModal = () => {
    showReportFormatModal.value = false;
    reportFormatSearch.value = ''; // Clear search when closing modal
};

const selectReportFormat = (format) => {
    if (format) {
        form.value.reportFormat = format.code;
    }
    hideReportFormatModal();
};

const powerOff = () => {
    toast.info('Power Off button clicked');
    // Implement actual power off logic here, e.g., redirect or close application
};

const loadReport = () => {
    toast.info('Load Report button clicked. Implement report generation logic.');
    console.log('Form data:', form.value);
    // Implement report generation logic based on form.value
};

// Set initial current S/O Period and Period fields to current month/year
onMounted(() => {
    const today = new Date();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const year = today.getFullYear();
    form.value.currentSOPeriod = `${month}/${year}`;
    form.value.periodStartMonth = parseInt(month);
    form.value.periodStartYear = year;
    form.value.periodEndMonth = parseInt(month);
    form.value.periodEndYear = year;
});

</script>

<style scoped>
/* Add any specific styles for this component here */
.btn-red-icon {
    @apply bg-red-500 hover:bg-red-600 text-white p-2 rounded-full shadow-md transition-colors;
}
.btn-green-icon {
    @apply bg-green-500 hover:bg-green-600 text-white p-2 rounded-full shadow-md transition-colors;
}
</style> 