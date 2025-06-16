<template>
    <AppLayout :header="'View & Print MC Maintenance Log'">
        <!-- Main Container with vibrant gradient background -->
        <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 p-4 md:p-6">
            <div class="max-w-7xl mx-auto">
                <!-- Header Section with animated elements -->
                <div class="bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 rounded-xl shadow-lg overflow-hidden mb-6 transform transition-all duration-500 hover:shadow-xl">
                    <div class="relative overflow-hidden">
                        <!-- Decorative Elements -->
                        <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20"></div>
                        <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10"></div>
                        <div class="absolute bottom-0 right-0 w-32 h-32 bg-yellow-400 opacity-5 rounded-full translate-y-10 translate-x-10"></div>
                        
                        <div class="p-6 md:p-8 relative z-10">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                <div class="flex items-start md:items-center space-x-4 mb-4 md:mb-0">
                                    <div class="bg-gradient-to-br from-pink-500 to-purple-600 p-3 rounded-lg shadow-inner flex items-center justify-center">
                                        <i class="fas fa-history text-white text-2xl"></i>
                                    </div>
                                    <div>
                                        <h1 class="text-2xl md:text-3xl font-bold text-white text-shadow">MC Maintenance Log</h1>
                                        <p class="text-blue-100 max-w-2xl">Track and review all maintenance activities performed on master cards</p>
                                    </div>
                                </div>
                                <div class="flex flex-wrap gap-3">
                                    <button @click="printPage" class="bg-gradient-to-r from-green-500 to-teal-400 text-white hover:from-green-600 hover:to-teal-500 px-4 py-2 rounded-lg flex items-center transition-all duration-300 transform hover:scale-105 shadow-md">
                                        <i class="fas fa-print mr-2"></i> Print Report
                                    </button>
                                    <Link href="/sales-management/system-requirement/master-card/obsolate-reactive-mc" class="bg-white bg-opacity-20 text-white border border-white border-opacity-30 hover:bg-opacity-30 px-4 py-2 rounded-lg flex items-center transition-all duration-300">
                                        <i class="fas fa-arrow-left mr-2"></i> Back
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Section with vibrant design -->
                <div class="bg-gradient-to-br from-white to-purple-50 rounded-xl shadow-lg mb-6 transform transition-all duration-500 hover:shadow-xl overflow-hidden">
                    <div class="border-b border-purple-100">
                        <div class="flex items-center p-5">
                            <div class="bg-gradient-to-r from-indigo-500 to-purple-500 text-white p-2 rounded-lg mr-3 shadow-md">
                                <i class="fas fa-filter"></i>
                            </div>
                            <h2 class="text-xl font-semibold text-indigo-800">Search Filters</h2>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Date Range with enhanced styling -->
                            <div class="col-span-1">
                                <div class="flex flex-col space-y-2">
                                    <label class="font-medium text-indigo-700 flex items-center">
                                        <i class="fas fa-calendar-alt text-purple-500 mr-2"></i>
                                        Date Range
                                    </label>
                                    <div class="flex items-center space-x-2">
                                        <div class="relative w-full">
                                            <input 
                                                type="date" 
                                                v-model="startDate"
                                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50"
                                            >
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <i class="fas fa-calendar-alt text-blue-400"></i>
                                            </div>
                                        </div>
                                        <span class="text-gray-500">to</span>
                                        <div class="relative w-full">
                                            <input 
                                                type="date" 
                                                v-model="endDate"
                                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50"
                                            >
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <i class="fas fa-calendar-alt text-blue-400"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Time Range with enhanced styling -->
                            <div class="col-span-1">
                                <div class="flex flex-col space-y-2">
                                    <label class="font-medium text-indigo-700 flex items-center">
                                        <i class="fas fa-clock text-purple-500 mr-2"></i>
                                        Time Range
                                    </label>
                                    <div class="flex items-center space-x-2">
                                        <div class="flex space-x-1">
                                            <select v-model="startTimeHour" class="px-2 py-3 border border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                                                <option v-for="hour in 24" :key="'start-hour-' + hour" :value="hour - 1">{{ (hour - 1).toString().padStart(2, '0') }}</option>
                                            </select>
                                            <span class="text-xl text-blue-500">:</span>
                                            <select v-model="startTimeMinute" class="px-2 py-3 border border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                                                <option v-for="minute in 60" :key="'start-min-' + minute" :value="minute - 1">{{ (minute - 1).toString().padStart(2, '0') }}</option>
                                            </select>
                                            <span class="text-xl text-blue-500">:</span>
                                            <select v-model="startTimeSecond" class="px-2 py-3 border border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                                                <option v-for="second in 60" :key="'start-sec-' + second" :value="second - 1">{{ (second - 1).toString().padStart(2, '0') }}</option>
                                            </select>
                                        </div>
                                        <span class="text-gray-500">to</span>
                                        <div class="flex space-x-1">
                                            <select v-model="endTimeHour" class="px-2 py-3 border border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                                                <option v-for="hour in 24" :key="'end-hour-' + hour" :value="hour - 1">{{ (hour - 1).toString().padStart(2, '0') }}</option>
                                            </select>
                                            <span class="text-xl text-blue-500">:</span>
                                            <select v-model="endTimeMinute" class="px-2 py-3 border border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                                                <option v-for="minute in 60" :key="'end-min-' + minute" :value="minute - 1">{{ (minute - 1).toString().padStart(2, '0') }}</option>
                                            </select>
                                            <span class="text-xl text-blue-500">:</span>
                                            <select v-model="endTimeSecond" class="px-2 py-3 border border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                                                <option v-for="second in 60" :key="'end-sec-' + second" :value="second - 1">{{ (second - 1).toString().padStart(2, '0') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <span class="text-gray-400 text-xs">Format: hh:mm:ss</span>
                                </div>
                            </div>

                            <!-- User ID with enhanced styling -->
                            <div class="col-span-1">
                                <div class="flex flex-col space-y-2">
                                    <label class="font-medium text-indigo-700 flex items-center">
                                        <i class="fas fa-user text-purple-500 mr-2"></i>
                                        User ID
                                    </label>
                                    <div class="relative w-full">
                                        <input 
                                            type="text" 
                                            v-model="userId"
                                            class="w-full pl-10 pr-10 py-3 border border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50"
                                            placeholder="Enter User ID"
                                        >
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <i class="fas fa-user text-gray-400"></i>
                                        </div>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <button @click="showUserLookup = true" class="text-blue-500 hover:text-blue-600 p-1">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- A/C# with enhanced styling -->
                            <div class="col-span-1">
                                <div class="flex flex-col space-y-2">
                                    <label class="font-medium text-indigo-700 flex items-center">
                                        <i class="fas fa-building text-purple-500 mr-2"></i>
                                        AC#
                                    </label>
                                    <div class="relative w-full">
                                        <input 
                                            type="text" 
                                            v-model="accountCode"
                                            class="w-full pl-10 pr-10 py-3 border border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50"
                                            placeholder="Enter Account Code"
                                        >
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <i class="fas fa-building text-gray-400"></i>
                                        </div>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <button @click="showAccountLookup = true" class="text-blue-500 hover:text-blue-600 p-1">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- MC S# with enhanced styling -->
                            <div class="col-span-1">
                                <div class="flex flex-col space-y-2">
                                    <label class="font-medium text-indigo-700 flex items-center">
                                        <i class="fas fa-id-card text-purple-500 mr-2"></i>
                                        MCS#
                                    </label>
                                    <div class="relative w-full">
                                        <input 
                                            type="text" 
                                            v-model="mcSequence"
                                            class="w-full pl-10 pr-10 py-3 border border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50"
                                            placeholder="Enter MC Sequence"
                                        >
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <i class="fas fa-id-card text-gray-400"></i>
                                        </div>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <button @click="showMcLookup = true" class="text-blue-500 hover:text-blue-600 p-1">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons with enhanced styling -->
                        <div class="flex justify-end space-x-4 mt-8">
                            <button 
                                @click="resetFilters"
                                class="px-6 py-2.5 border border-purple-200 rounded-lg bg-white hover:bg-purple-50 text-indigo-700 transition-all duration-300 shadow-sm flex items-center"
                            >
                                <i class="fas fa-undo mr-2 text-purple-500"></i>
                                Reset Filters
                            </button>
                            <button 
                                @click="searchLogs"
                                class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-pink-500 hover:from-purple-700 hover:to-pink-600 text-white rounded-lg transition-all duration-300 shadow-md flex items-center"
                            >
                                <i class="fas fa-search mr-2"></i>
                                Proceed
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Results Section with vibrant styling -->
                <div v-if="showResults" class="bg-gradient-to-br from-white to-purple-50 rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 p-5 text-white">
                        <h2 class="text-xl font-bold">Maintenance Log Results</h2>
                        <p class="text-sm text-purple-100">Found {{ filteredLogs.length }} maintenance log entries</p>
                    </div>
                    <div class="p-6">
                        <!-- Results content will be displayed here in a future update -->
                        <div class="animate-pulse flex flex-col space-y-4">
                            <div class="h-4 bg-purple-100 rounded w-3/4"></div>
                            <div class="h-4 bg-purple-100 rounded"></div>
                            <div class="h-4 bg-purple-100 rounded w-5/6"></div>
                        </div>
                    </div>
                </div>
                
                <!-- No Results Feedback - Shown when search is performed but no results -->
                <div v-if="hasSearched && !showResults" class="bg-gradient-to-br from-white to-purple-50 rounded-xl p-10 shadow-lg text-center">
                    <div class="flex flex-col items-center justify-center space-y-4">
                        <div class="w-20 h-20 bg-gradient-to-br from-purple-100 to-pink-100 rounded-full flex items-center justify-center text-purple-600 text-xl shadow-inner">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3 class="text-xl font-medium text-indigo-800">No Results Found</h3>
                        <p class="text-indigo-600 max-w-md">We couldn't find any maintenance logs matching your search criteria. Try adjusting your filters or search terms.</p>
                        <button 
                            @click="resetAllFilters" 
                            class="mt-4 px-6 py-2 bg-gradient-to-r from-purple-600 to-pink-500 text-white rounded-lg hover:from-purple-700 hover:to-pink-600 transition-colors shadow-md"
                        >
                            Reset All Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

// Form data
const startDate = ref(formattedDate(new Date()));
const endDate = ref(formattedDate(new Date()));
const startTimeHour = ref(0);
const startTimeMinute = ref(0);
const startTimeSecond = ref(0);
const endTimeHour = ref(23);
const endTimeMinute = ref(59);
const endTimeSecond = ref(59);
const userId = ref('');
const accountCode = ref('');
const mcSequence = ref('');

// UI state
const showUserLookup = ref(false);
const showAccountLookup = ref(false);
const showMcLookup = ref(false);
const loading = ref(false);
const showResults = ref(false);
const hasSearched = ref(false);
const tableSearch = ref('');
const actionFilter = ref('all');
const sortColumn = ref('created_at');
const sortDirection = ref('desc');

// Sample data (replace with API call)
const maintenanceLogs = ref([
    {
        created_at: '2023-08-15T09:15:30',
        user_id: 'admin',
        customer_name: 'Acme Corporation',
        mc_seq: 'MC001',
        action: 'create',
        details: 'Created new master card'
    },
    {
        created_at: '2023-08-16T14:22:45',
        user_id: 'manager',
        customer_name: 'Acme Corporation',
        mc_seq: 'MC001',
        action: 'update',
        details: 'Updated customer information'
    },
    {
        created_at: '2023-08-17T11:05:12',
        user_id: 'supervisor',
        customer_name: 'Acme Corporation',
        mc_seq: 'MC001',
        action: 'approve',
        details: 'Approved master card'
    },
    {
        created_at: '2023-09-05T16:45:22',
        user_id: 'admin',
        customer_name: 'Globex Industries',
        mc_seq: 'MC002',
        action: 'create',
        details: 'Created new master card'
    },
    {
        created_at: '2023-09-10T10:35:18',
        user_id: 'supervisor',
        customer_name: 'Globex Industries',
        mc_seq: 'MC002',
        action: 'obsolate',
        details: 'Master card marked as obsolete - Product discontinued'
    },
    {
        created_at: '2023-10-02T09:12:40',
        user_id: 'manager',
        customer_name: 'Globex Industries',
        mc_seq: 'MC002',
        action: 'reactive',
        details: 'Master card reactivated - Product reinstated'
    }
]);

// Computed filtered logs based on search and filters
const filteredLogs = computed(() => {
    let result = [...maintenanceLogs.value];
    
    // Apply action filter
    if (actionFilter.value !== 'all') {
        result = result.filter(log => log.action === actionFilter.value);
    }
    
    // Apply table search
    if (tableSearch.value.trim() !== '') {
        const searchTerm = tableSearch.value.toLowerCase();
        result = result.filter(log => 
            log.user_id.toLowerCase().includes(searchTerm) ||
            log.customer_name.toLowerCase().includes(searchTerm) ||
            log.mc_seq.toLowerCase().includes(searchTerm) ||
            log.details.toLowerCase().includes(searchTerm)
        );
    }
    
    // Apply sorting
    result.sort((a, b) => {
        let valA = a[sortColumn.value];
        let valB = b[sortColumn.value];
        
        if (typeof valA === 'string') valA = valA.toLowerCase();
        if (typeof valB === 'string') valB = valB.toLowerCase();
        
        if (valA < valB) return sortDirection.value === 'asc' ? -1 : 1;
        if (valA > valB) return sortDirection.value === 'asc' ? 1 : -1;
        return 0;
    });
    
    return result;
});

// Helper functions
function formattedDate(date) {
    const year = date.getFullYear();
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const day = date.getDate().toString().padStart(2, '0');
    return `${year}-${month}-${day}`;
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString();
}

function formatTime(dateString) {
    const date = new Date(dateString);
    return date.toLocaleTimeString();
}

function formatDateTime(dateString) {
    const date = new Date(dateString);
    return date.toLocaleString();
}

function capitalize(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

// Sort table function
function sortTable(column) {
    if (sortColumn.value === column) {
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortColumn.value = column;
        sortDirection.value = 'asc';
    }
}

// Event handlers
function resetFilters() {
    startDate.value = formattedDate(new Date());
    endDate.value = formattedDate(new Date());
    startTimeHour.value = 0;
    startTimeMinute.value = 0;
    startTimeSecond.value = 0;
    endTimeHour.value = 23;
    endTimeMinute.value = 59;
    endTimeSecond.value = 59;
    userId.value = '';
    accountCode.value = '';
    mcSequence.value = '';
}

function resetAllFilters() {
    resetFilters();
    tableSearch.value = '';
    actionFilter.value = 'all';
    hasSearched.value = false;
    showResults.value = false;
}

function searchLogs() {
    loading.value = true;
    showResults.value = true;
    hasSearched.value = true;
    
    // Simulate API call
    setTimeout(() => {
        loading.value = false;
        
        // Here you would filter the logs based on the search criteria
        // For now, we're just using the sample data
    }, 1000);
}

function printPage() {
    window.print();
}
</script>

<style scoped>
@media print {
    button {
        display: none;
    }
    
    input, select {
        border: none !important;
        background: white !important;
    }
    
    .bg-gray-100 {
        background-color: white !important;
        padding: 0 !important;
    }
    
    .from-blue-600, .to-indigo-700 {
        background: white !important;
        color: black !important;
    }
    
    .text-white {
        color: black !important;
    }
    
    .shadow-lg, .shadow-xl, .shadow-md {
        box-shadow: none !important;
    }
}

/* Hover animations */
.transition-all {
    transition: all 0.3s ease;
}

/* Pulse effect for certain elements */
@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(147, 51, 234, 0.5);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(147, 51, 234, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(147, 51, 234, 0);
    }
}

/* Text shadow for headings */
.text-shadow {
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Subtle hover glow effect */
.hover-glow:hover {
    box-shadow: 0 0 15px rgba(147, 51, 234, 0.3);
}

/* Gradient text effect */
.gradient-text {
    background: linear-gradient(to right, #9333ea, #ec4899);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
</style>
