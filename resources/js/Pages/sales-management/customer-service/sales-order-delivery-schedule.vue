<template>
    <AppLayout :header="'Sales Order Delivery Schedule'">
        <Head title="Sales Order Delivery Schedule" />

        <!-- Header Section -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 p-6 rounded-t-lg shadow-lg">
            <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
                <i class="fas fa-calendar-alt mr-3"></i> Sales Order Delivery Schedule
            </h2>
            <p class="text-purple-100">View and manage sales order delivery schedules.</p>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
            <!-- Content Placeholder -->
            <div class="p-8 text-center text-gray-500 border-2 border-dashed border-gray-300 rounded-lg">
                <i class="fas fa-truck text-5xl mb-4"></i>
                <p class="text-xl font-semibold">Sales Order Delivery Schedule will be displayed here.</p>
                <p class="mt-2">This section will provide an overview of delivery dates, statuses, and related logistics for sales orders.</p>
            </div>

            <!-- Example of dynamic data loading -->
            <div class="mt-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Upcoming Deliveries</h3>
                <div v-if="loading" class="text-center text-gray-500">
                    Loading delivery schedule...
                </div>
                <div v-else-if="!deliveryScheduleData || deliveryScheduleData.length === 0" class="text-center text-red-500">
                    No upcoming deliveries found.
                </div>
                <div v-else class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 shadow-md rounded-lg">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Delivery Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="(item, index) in deliveryScheduleData" :key="index">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ item.orderId }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ item.customerName }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ item.deliveryDate }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span :class="{
                                        'px-2 inline-flex text-xs leading-5 font-semibold rounded-full': true,
                                        'bg-green-100 text-green-800': item.status === 'Delivered',
                                        'bg-yellow-100 text-yellow-800': item.status === 'Pending',
                                        'bg-blue-100 text-blue-800': item.status === 'In Transit',
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

const deliveryScheduleData = ref([]);
const loading = ref(true);

const fetchDeliveryScheduleData = async () => {
    loading.value = true;
    try {
        // Placeholder for API call to fetch actual delivery schedule data
        const response = await axios.get('/api/customer-service/delivery-schedule-data');
        deliveryScheduleData.value = response.data;
    } catch (error) {
        console.error('Error fetching delivery schedule data:', error);
        deliveryScheduleData.value = [
            { orderId: 'SO-001', customerName: 'Customer A', deliveryDate: '2025-07-10', status: 'In Transit' },
            { orderId: 'SO-002', customerName: 'Customer B', deliveryDate: '2025-07-15', status: 'Pending' },
            { orderId: 'SO-003', customerName: 'Customer C', deliveryDate: '2025-07-05', status: 'Delivered' },
        ]; // Fallback to dummy data on error
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchDeliveryScheduleData();
});
</script> 