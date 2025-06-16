<template>
    <AppLayout :header="'Customer Account Credit'">
        <Head title="Customer Account Credit" />

        <!-- Header Section -->
        <div class="bg-gradient-to-r from-teal-600 to-green-600 p-6 rounded-t-lg shadow-lg">
            <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
                <i class="fas fa-credit-card mr-3"></i> Customer Account Credit
            </h2>
            <p class="text-teal-100">Manage and view customer account credit information.</p>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
            <!-- Content Placeholder -->
            <div class="p-8 text-center text-gray-500 border-2 border-dashed border-gray-300 rounded-lg">
                <i class="fas fa-info-circle text-5xl mb-4"></i>
                <p class="text-xl font-semibold">Customer Account Credit management will be displayed here.</p>
                <p class="mt-2">This section will allow for the management, viewing, and reporting of customer credit limits and outstanding balances.</p>
            </div>

            <!-- Example of dynamic data loading (similar to sales team example) -->
            <div class="mt-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Credit Overview</h3>
                <div v-if="loading" class="text-center text-gray-500">
                    Loading credit data...
                </div>
                <div v-else-if="!creditData" class="text-center text-red-500">
                    Failed to load credit data.
                </div>
                <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-purple-100 p-4 rounded-lg shadow-md flex items-center">
                        <div class="bg-purple-500 text-white p-3 rounded-full mr-4">
                            <i class="fas fa-dollar-sign text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-purple-700">Total Credit Limit</p>
                            <p class="text-2xl font-bold text-purple-900">$ {{ creditData.totalCreditLimit.toLocaleString() }}</p>
                        </div>
                    </div>
                    <div class="bg-orange-100 p-4 rounded-lg shadow-md flex items-center">
                        <div class="bg-orange-500 text-white p-3 rounded-full mr-4">
                            <i class="fas fa-money-bill-wave text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-orange-700">Outstanding Balance</p>
                            <p class="text-2xl font-bold text-orange-900">$ {{ creditData.outstandingBalance.toLocaleString() }}</p>
                        </div>
                    </div>
                    <div class="bg-teal-100 p-4 rounded-lg shadow-md flex items-center">
                        <div class="bg-teal-500 text-white p-3 rounded-full mr-4">
                            <i class="fas fa-percent text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-teal-700">Used Credit</p>
                            <p class="text-2xl font-bold text-teal-900">{{ creditData.usedCreditPercentage }}%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

const creditData = ref(null);
const loading = ref(true);

const fetchCreditData = async () => {
    loading.value = true;
    try {
        // Placeholder for API call to fetch actual credit data
        const response = await axios.get('/api/customer-service/account-credit-data');
        creditData.value = response.data;
    } catch (error) {
        console.error('Error fetching credit data:', error);
        creditData.value = {
            totalCreditLimit: 0,
            outstandingBalance: 0,
            usedCreditPercentage: 0,
        }; // Fallback to dummy data on error
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchCreditData();
});
</script> 