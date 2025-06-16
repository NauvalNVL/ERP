<template>
    <AppLayout :header="'Production Monitoring Board [PMB3]'">
        <Head title="Production Monitoring Board [PMB3]" />

        <!-- Header Section -->
        <div class="bg-gradient-to-r from-blue-600 to-cyan-600 p-6 rounded-t-lg shadow-lg">
            <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
                <i class="fas fa-tv mr-3"></i> Production Monitoring Board [PMB3]
            </h2>
            <p class="text-blue-100">Real-time monitoring of production activities.</p>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
            <!-- Content Placeholder -->
            <div class="p-8 text-center text-gray-500 border-2 border-dashed border-gray-300 rounded-lg">
                <i class="fas fa-industry text-5xl mb-4"></i>
                <p class="text-xl font-semibold">Production Monitoring Board [PMB3] will be displayed here.</p>
                <p class="mt-2">This section provides a real-time view of production progress, machine status, and order fulfillment on the factory floor.</p>
            </div>

            <!-- Example of dynamic data loading -->
            <div class="mt-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Live Production Status</h3>
                <div v-if="loading" class="text-center text-gray-500">
                    Loading production data...
                </div>
                <div v-else-if="!productionData || productionData.length === 0" class="text-center text-red-500">
                    No production data available.
                </div>
                <div v-else class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 shadow-md rounded-lg">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Machine ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Job</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="(item, index) in productionData" :key="index">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ item.machineId }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ item.currentJob }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ item.progress }}%</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span :class="{
                                        'px-2 inline-flex text-xs leading-5 font-semibold rounded-full': true,
                                        'bg-green-100 text-green-800': item.status === 'Running',
                                        'bg-yellow-100 text-yellow-800': item.status === 'Idle',
                                        'bg-red-100 text-red-800': item.status === 'Maintenance',
                                    }">{{ item.status }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
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

const productionData = ref([]);
const loading = ref(true);

const fetchProductionData = async () => {
    loading.value = true;
    try {
        // Placeholder for API call to fetch actual production monitoring data
        const response = await axios.get('/api/customer-service/production-monitoring-data');
        productionData.value = response.data;
    } catch (error) {
        console.error('Error fetching production data:', error);
        productionData.value = [
            { machineId: 'MCH-001', currentJob: 'Order #123', progress: 75, status: 'Running' },
            { machineId: 'MCH-002', currentJob: '' , progress: 0, status: 'Idle' },
            { machineId: 'MCH-003', currentJob: 'Order #456', progress: 30, status: 'Running' },
            { machineId: 'MCH-004', currentJob: 'Maintenance', progress: 0, status: 'Maintenance' },
        ]; // Fallback to dummy data on error
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchProductionData();
});
</script> 