<template>
    <AppLayout :header="'View & Print Master Cards'">
    <Head title="View & Print Master Cards" />

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
                                    <i class="fas fa-print text-white text-2xl"></i>
                                </div>
                                <div>
                                    <h1 class="text-2xl md:text-3xl font-bold text-white text-shadow">View & Print Master Cards</h1>
                                    <p class="text-blue-100 max-w-2xl">Preview and print master card data</p>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-3">
                                <button @click="printTable" class="bg-gradient-to-r from-green-500 to-teal-400 text-white hover:from-green-600 hover:to-teal-500 px-4 py-2 rounded-lg flex items-center transition-all duration-300 transform hover:scale-105 shadow-md">
                                    <i class="fas fa-print mr-2"></i> Print List
                                </button>
                                <Link href="/sales-management/system-requirement/master-card/obsolate-reactive-mc" class="bg-white bg-opacity-20 text-white border border-white border-opacity-30 hover:bg-opacity-30 px-4 py-2 rounded-lg flex items-center transition-all duration-300">
                                    <i class="fas fa-arrow-left mr-2"></i> Back to Master Cards
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-white to-purple-50 rounded-xl shadow-lg mb-6 transform transition-all duration-500 hover:shadow-xl overflow-hidden">
                <div class="border-b border-purple-100">
                    <div class="flex items-center p-5">
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-500 text-white p-2 rounded-lg mr-3 shadow-md">
                            <i class="fas fa-filter"></i>
                        </div>
                        <h2 class="text-xl font-semibold text-indigo-800">Search & Filter</h2>
                    </div>
                </div>

                <div class="p-5">
                    <!-- Actions Bar -->
                    <div class="flex flex-wrap items-center justify-between mb-6">
                        <div class="flex items-center space-x-4 mb-3 sm:mb-0">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="fas fa-search text-purple-400"></i>
                                </div>
                                <input 
                                    type="text" 
                                    v-model="searchQuery" 
                                    class="pl-10 pr-4 py-2 border border-purple-200 rounded-lg focus:ring-purple-500 focus:border-purple-500 bg-white"
                                    placeholder="Search master cards..."
                                >
                            </div>
                            <div>
                                <select v-model="statusFilter" class="py-2 pl-3 pr-8 border border-purple-200 rounded-lg focus:ring-purple-500 focus:border-purple-500 bg-white">
                                    <option value="all">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="obsolete">Obsolete</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="bg-gradient-to-br from-white to-purple-50 rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <div id="printableTable" class="min-w-full bg-white rounded-lg overflow-hidden">
                        <!-- Table Header -->
                        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-4 px-6 flex items-center">
                            <div class="flex items-center">
                                <div class="mr-4">
                                    <i class="fas fa-id-card text-3xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold">MASTER CARDS LIST</h2>
                                    <p class="text-sm opacity-80">View and print master card data</p>
                                </div>
                            </div>
                        </div>

                        <!-- Table Content -->
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-purple-100 to-indigo-100">
                                <tr>
                                    <th @click="sortTable('mc_seq')" class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider cursor-pointer">
                                        MC Seq# <i class="fas fa-sort ml-1"></i>
                                    </th>
                                    <th @click="sortTable('mc_model')" class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider cursor-pointer">
                                        MC Model <i class="fas fa-sort ml-1"></i>
                                    </th>
                                    <th @click="sortTable('customer_name')" class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider cursor-pointer">
                                        Customer <i class="fas fa-sort ml-1"></i>
                                    </th>
                                    <th @click="sortTable('description')" class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider cursor-pointer">
                                        Description <i class="fas fa-sort ml-1"></i>
                                    </th>
                                    <th @click="sortTable('status')" class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider cursor-pointer">
                                        Status <i class="fas fa-sort ml-1"></i>
                                    </th>
                                    <th @click="sortTable('updated_at')" class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider cursor-pointer">
                                        Last Updated <i class="fas fa-sort ml-1"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-if="loading" class="hover:bg-purple-50">
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        <div class="flex justify-center">
                                            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-purple-500"></div>
                                        </div>
                                        <p class="mt-2">Loading master card data...</p>
                                    </td>
                                </tr>
                                <tr v-else-if="filteredMasterCards.length === 0" class="hover:bg-purple-50">
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        No master cards found. 
                                        <template v-if="searchQuery">
                                            <p class="mt-2">No results match your search query: "{{ searchQuery }}"</p>
                                            <button @click="searchQuery = ''" class="mt-2 text-purple-500 hover:underline">Clear search</button>
                                        </template>
                                    </td>
                                </tr>
                                <tr v-for="(card, index) in filteredMasterCards" :key="card.id" 
                                    :class="{'bg-purple-50': index % 2 === 0}" 
                                    class="hover:bg-indigo-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ card.mc_seq }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ card.mc_model }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ card.customer_name }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ card.description || '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span 
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium"
                                            :class="{
                                                'bg-green-100 text-green-800': card.status === 'active',
                                                'bg-red-100 text-red-800': card.status === 'obsolete',
                                                'bg-gray-100 text-gray-800': card.status !== 'active' && card.status !== 'obsolete'
                                            }">
                                            {{ card.status === 'active' ? 'Active' : 'Obsolete' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ formatDate(card.updated_at) }}</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Table Footer -->
                        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 px-6 py-3 border-t border-purple-100 text-sm text-indigo-700">
                            <div class="flex items-center justify-between">
                                <div>Total Master Cards: {{ filteredMasterCards.length }}</div>
                                <div v-if="searchQuery || statusFilter !== 'all'">Filtered from {{ masterCards.length }} total records</div>
                                <div class="text-xs text-indigo-400">Generated: {{ currentDate }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Print Instructions -->
            <div class="mt-6 bg-gradient-to-r from-purple-50 to-pink-50 p-4 rounded-lg border border-purple-100 shadow-sm">
                <h3 class="font-semibold text-indigo-800 mb-2 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-purple-500"></i> Print Instructions
                </h3>
                <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                    <li>Click the "Print List" button above to print this master card list</li>
                    <li>Use landscape orientation for better results</li>
                    <li>You can search, filter by status, or sort data before printing</li>
                    <li>Only the table will be included in the print output</li>
                </ul>
            </div>
        </div>
    </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

// Data
const masterCards = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const statusFilter = ref('all'); // 'all', 'active', 'obsolete'
const sortColumn = ref('mc_seq');
const sortDirection = ref('asc');
const currentDate = new Date().toLocaleString();

// Fetch master cards from API
const fetchMasterCards = async () => {
    loading.value = true;
    try {
        const response = await fetch('/api/obsolate-reactive-mc', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to fetch master cards');
        }
        
        const data = await response.json();
        masterCards.value = data;
    } catch (error) {
        console.error('Error fetching master cards:', error);
    } finally {
        loading.value = false;
    }
};

// Format date
const formatDate = (dateString) => {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleString();
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

// Filtered and sorted master cards
const filteredMasterCards = computed(() => {
    let filtered = [...masterCards.value];
    
    // Apply status filter
    if (statusFilter.value !== 'all') {
        filtered = filtered.filter(card => card.status === statusFilter.value);
    }
    
    // Apply search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(card => 
            (card.mc_seq && card.mc_seq.toLowerCase().includes(query)) ||
            (card.mc_model && card.mc_model.toLowerCase().includes(query)) ||
            (card.customer_name && card.customer_name.toLowerCase().includes(query)) ||
            (card.description && card.description.toLowerCase().includes(query))
        );
    }
    
    // Apply sorting
    filtered.sort((a, b) => {
        let valueA = a[sortColumn.value];
        let valueB = b[sortColumn.value];
        
        // Handle null values
        if (valueA === null || valueA === undefined) valueA = '';
        if (valueB === null || valueB === undefined) valueB = '';
        
        // Case insensitive string comparison
        if (typeof valueA === 'string') valueA = valueA.toLowerCase();
        if (typeof valueB === 'string') valueB = valueB.toLowerCase();
        
        if (valueA < valueB) return sortDirection.value === 'asc' ? -1 : 1;
        if (valueA > valueB) return sortDirection.value === 'asc' ? 1 : -1;
        return 0;
    });
    
    return filtered;
});

// Print table function
const printTable = () => {
    const printContents = document.getElementById('printableTable').innerHTML;
    const originalContents = document.body.innerHTML;
    
    document.body.innerHTML = `
        <div style="padding: 20px;">
            <h1 style="text-align: center; margin-bottom: 20px; color: #6d28d9;">Master Cards List</h1>
            ${printContents}
            <div style="text-align: right; margin-top: 20px; font-size: 12px; color: #7c3aed;">
                Printed on: ${new Date().toLocaleString()}
            </div>
        </div>
    `;
    
    window.print();
    document.body.innerHTML = originalContents;
    
    // Reinitialize any necessary event listeners or Vue bindings
    // (This may not be necessary with Inertia.js, which would reload the page after print)
};

// Fetch data on component mount
onMounted(() => {
    fetchMasterCards();
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
    
    .from-purple-600, .to-indigo-600 {
        background: white !important;
        color: black !important;
    }
    
    .text-white {
        color: black !important;
    }
}

/* Text shadow for headings */
.text-shadow {
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Transition effects */
.transition-all {
    transition: all 0.3s ease;
}
</style>
