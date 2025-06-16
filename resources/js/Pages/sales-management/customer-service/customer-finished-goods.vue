<template>
    <AppLayout :header="'Customer Finished Goods'">
        <Head title="Customer Finished Goods" />

        <!-- Header Section -->
        <div class="bg-gradient-to-r from-pink-600 to-red-600 p-6 rounded-t-lg shadow-lg">
            <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
                <i class="fas fa-box-open mr-3"></i> Customer Finished Goods
            </h2>
            <p class="text-pink-100">Overview and management of finished goods for customers.</p>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
            <!-- Content Placeholder -->
            <div class="p-8 text-center text-gray-500 border-2 border-dashed border-gray-300 rounded-lg">
                <i class="fas fa-boxes text-5xl mb-4"></i>
                <p class="text-xl font-semibold">Customer Finished Goods information will be displayed here.</p>
                <p class="mt-2">This section will show details about finished products ready for delivery or shipment to customers, including inventory levels and order fulfillment status.</p>
            </div>

            <!-- Example of dynamic data loading -->
            <div class="mt-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Finished Goods Inventory</h3>
                <div v-if="loading" class="text-center text-gray-500">
                    Loading finished goods data...
                </div>
                <div v-else-if="!finishedGoodsData || finishedGoodsData.length === 0" class="text-center text-red-500">
                    No finished goods found for customers.
                </div>
                <div v-else class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 shadow-md rounded-lg">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="(item, index) in finishedGoodsData" :key="index">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ item.productId }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ item.customerName }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ item.quantity }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ item.location }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span :class="{
                                        'px-2 inline-flex text-xs leading-5 font-semibold rounded-full': true,
                                        'bg-green-100 text-green-800': item.status === 'Ready for Pickup',
                                        'bg-blue-100 text-blue-800': item.status === 'Shipped',
                                        'bg-yellow-100 text-yellow-800': item.status === 'In Warehouse',
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

const finishedGoodsData = ref([]);
const loading = ref(true);

const fetchFinishedGoodsData = async () => {
    loading.value = true;
    try {
        // Placeholder for API call to fetch actual finished goods data
        const response = await axios.get('/api/customer-service/finished-goods-data');
        finishedGoodsData.value = response.data;
    } catch (error) {
        console.error('Error fetching finished goods data:', error);
        finishedGoodsData.value = [
            { productId: 'PROD-001', customerName: 'Customer X', quantity: 150, location: 'WH-A', status: 'Ready for Pickup' },
            { productId: 'PROD-002', customerName: 'Customer Y', quantity: 200, location: 'WH-B', status: 'In Warehouse' },
            { productId: 'PROD-003', customerName: 'Customer Z', quantity: 50, location: 'WH-A', status: 'Shipped' },
        ]; // Fallback to dummy data on error
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchFinishedGoodsData();
});
</script> 