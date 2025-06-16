<template>
    <AppLayout :header="'Customer Service Dashboard'">
        <Head title="Customer Service Dashboard" />

        <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
            <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
                <i class="fas fa-chart-line mr-3"></i> Customer Service Dashboard
            </h2>
            <p class="text-cyan-100">Overview and search functionalities for customer service operations.</p>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
            <div class="grid grid-cols-1 gap-8">
                <!-- Main Search Buttons Section -->
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
                    <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-blue-500 rounded-lg mr-3">
                            <i class="fas fa-search text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Search Operations</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <button @click="modalsRef.openInitialSalesOrderModal()" class="dashboard-button">
                            Search by Sales Order
                        </button>
                        <button @click="modalsRef.openMasterCardSearchDirectlyModal()" class="dashboard-button">
                            Search by Master Card
                        </button>
                        <button @click="modalsRef.openPurchaseOrderRefSearchDirectlyModal()" class="dashboard-button">
                            Search by Purchase Order
                        </button>
                        <button @click="modalsRef.openBoardPurchaseSearchModal()" class="dashboard-button">
                            Search by Board Purchase
                        </button>
                        <button @click="modalsRef.openInitialWorkOrderModal()" class="dashboard-button">
                            Search by Work Order
                        </button>
                        <button @click="modalsRef.openInitialDeliveryOrderModal()" class="dashboard-button">
                            Search by Delivery Order
                        </button>
                        <button @click="modalsRef.openInitialInvoiceModal()" class="dashboard-button">
                            Search by Invoice
                        </button>
                    </div>

                    <div v-if="loading" class="mt-8 bg-yellow-100 p-4 rounded-lg text-yellow-800 flex items-center space-x-3">
                        <i class="fas fa-spinner fa-spin text-xl"></i>
                        <span>Loading dashboard data...</span>
                    </div>
                    <div v-if="error" class="mt-8 bg-red-100 p-4 rounded-lg text-red-800 flex items-center space-x-3">
                        <i class="fas fa-exclamation-triangle text-xl"></i>
                        <span>Error loading data: {{ error }}</span>
                    </div>
                    <div v-if="!loading && !error && dashboardData" class="mt-8 bg-green-100 p-4 rounded-lg text-green-800 flex items-center space-x-3">
                        <i class="fas fa-check-circle text-xl"></i>
                        <span>Dashboard data loaded successfully! Example data: {{ dashboardData.message }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modals Component -->
        <CustomerServiceModals ref="modalsRef" @show-notification="showNotification" />

        <!-- General Notification Toast -->
        <div v-if="notification.show" class="fixed bottom-4 right-4 z-50 shadow-xl rounded-lg transition-all duration-300"
             :class="{
                 'bg-green-100 border-l-4 border-green-500': notification.type === 'success',
                 'bg-red-100 border-l-4 border-red-500': notification.type === 'error',
                 'bg-yellow-100 border-l-4 border-yellow-500': notification.type === 'warning'
             }">
            <div class="p-4 flex items-center">
                <div class="mr-3">
                    <i v-if="notification.type === 'success'" class="fas fa-check-circle text-green-500 text-xl"></i>
                    <i v-else-if="notification.type === 'error'" class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                    <i v-else class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
                </div>
                <div>
                    <p :class="{
                        'text-green-800': notification.type === 'success',
                        'text-red-800': notification.type === 'error',
                        'text-yellow-800': notification.type === 'warning'
                    }">{{ notification.message }}</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import CustomerServiceModals from '@/Components/CustomerServiceModals.vue';
import axios from 'axios';

const dashboardData = ref(null);
const loading = ref(true);
const error = ref(null);
const modalsRef = ref(null); // Ref to access methods from the child component

const notification = ref({
    show: false,
    message: '',
    type: 'success'
});

const fetchDashboardData = async () => {
    loading.value = true;
    error.value = null;
    try {
        const response = await axios.get('/api/customer-service/dashboard-data');
        dashboardData.value = response.data;
        showNotification('Dashboard data loaded successfully!', 'success');
    } catch (err) {
        console.error('Error fetching dashboard data:', err);
        error.value = err.message;
        showNotification('Failed to load dashboard data.', 'error');
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchDashboardData();
});

const showNotification = (message, type) => {
    notification.value.show = true;
    notification.value.message = message;
    notification.value.type = type;

    setTimeout(() => {
        notification.value.show = false;
    }, 3000);
};
</script>

<style scoped>
.dashboard-button {
    @apply bg-blue-500 text-white font-semibold py-3 px-6 rounded-lg shadow-md hover:bg-blue-600 transition-transform transform hover:scale-105 flex items-center justify-center text-lg;
}

.dashboard-button i {
    margin-right: 0.75rem; /* Equivalent to mr-3 */
    font-size: 1.25rem; /* Equivalent to text-xl */
}
</style> 