<template>
    <AppLayout :header="'Customer Service Dashboard'">
        <Head title="Customer Service Dashboard" />

        <!-- Header Section -->
        <div class="bg-gradient-to-r from-teal-600 to-green-600 p-6 rounded-t-lg shadow-lg">
            <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
                <i class="fas fa-chart-pie mr-3"></i> Customer Service Dashboard
            </h2>
            <p class="text-teal-100">Overview of key customer service metrics.</p>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
            <!-- Dashboard Content Placeholder -->
            <div class="p-8 text-center text-gray-500 border-2 border-dashed border-gray-300 rounded-lg">
                <i class="fas fa-tachometer-alt text-5xl mb-4"></i>
                <p class="text-xl font-semibold">Your Customer Service Dashboard will appear here.</p>
                <p class="mt-2">This section can display key metrics, charts, and summaries related to customer interactions, service performance, and customer satisfaction.</p>
            </div>

            <!-- Example of dynamic data loading (similar to sales team example) -->
            <div class="mt-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Key Metrics</h3>
                <div v-if="loading" class="text-center text-gray-500">
                    Loading dashboard data...
                </div>
                <div v-else-if="!dashboardData" class="text-center text-red-500">
                    Failed to load dashboard data.
                </div>
                <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-blue-100 p-4 rounded-lg shadow-md flex items-center">
                        <div class="bg-blue-500 text-white p-3 rounded-full mr-4">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-blue-700">Total Customers</p>
                            <p class="text-2xl font-bold text-blue-900">{{ dashboardData.totalCustomers }}</p>
                        </div>
                    </div>
                    <div class="bg-green-100 p-4 rounded-lg shadow-md flex items-center">
                        <div class="bg-green-500 text-white p-3 rounded-full mr-4">
                            <i class="fas fa-ticket-alt text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-green-700">Open Tickets</p>
                            <p class="text-2xl font-bold text-green-900">{{ dashboardData.openTickets }}</p>
                        </div>
                    </div>
                    <div class="bg-yellow-100 p-4 rounded-lg shadow-md flex items-center">
                        <div class="bg-yellow-500 text-white p-3 rounded-full mr-4">
                            <i class="fas fa-clock text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-yellow-700">Avg. Response Time</p>
                            <p class="text-2xl font-bold text-yellow-900">{{ dashboardData.avgResponseTime }}</p>
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

const dashboardData = ref(null);
const loading = ref(true);

const fetchDashboardData = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/api/customer-service/dashboard-data');
        dashboardData.value = response.data;
    } catch (error) {
        console.error('Error fetching dashboard data:', error);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchDashboardData();
});
</script> 