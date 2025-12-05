<template>
    <AppLayout :header="'Customer Service Dashboard'">
        <Head title="Customer Service Dashboard" />

        <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <!-- Header Section -->
                <div class="bg-blue-600 text-white shadow-sm rounded-xl border border-blue-700 mb-4">
                    <div class="px-4 py-4 sm:px-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center">
                                <i class="fas fa-chart-line text-white text-lg"></i>
                            </div>
                            <div>
                                <h2 class="text-lg sm:text-xl font-semibold leading-tight">
                                    Customer Service Dashboard
                                </h2>
                                <p class="text-xs sm:text-sm text-blue-100">
                                    Overview and search functionalities for customer service operations.
                                </p>
                            </div>
                        </div>
                        <div v-if="dashboardData" class="flex items-center gap-2 text-xs text-blue-100">
                            <i class="fas fa-info-circle text-sm"></i>
                            <span>
                                Customers: {{ dashboardData.total_customers ?? 0 }},
                                SO: {{ dashboardData.total_sales_orders ?? 0 }},
                                DO: {{ dashboardData.total_delivery_orders ?? 0 }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 mb-6">
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Main Search Buttons Section -->
                        <div class="bg-white p-4 sm:p-5 rounded-lg border border-gray-100 shadow-sm">
                            <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                                <div class="p-2 bg-blue-500 rounded-lg mr-3">
                                    <i class="fas fa-search text-white"></i>
                                </div>
                                <h3 class="text-base sm:text-lg font-semibold text-gray-800">Search Operations</h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <button @click="openSearchBySalesOrder" class="dashboard-button">
                                    Search by Sales Order
                                </button>
                                <button @click="modalsRef.openMasterCardSearchDirectlyModal()" class="dashboard-button">
                                    Search by Master Card
                                </button>
                                <button @click="modalsRef.openInitialDeliveryOrderModal()" class="dashboard-button">
                                    Search by Delivery Order
                                </button>
                                <button @click="modalsRef.openInitialInvoiceModal()" class="dashboard-button">
                                    Search by Invoice
                                </button>
                            </div>
                            
                            <div v-if="loading" class="mt-6 bg-yellow-100 p-4 rounded-lg text-yellow-800 flex items-center space-x-3">
                                <i class="fas fa-spinner fa-spin text-xl"></i>
                                <span>Loading dashboard data...</span>
                            </div>
                            <div v-if="error" class="mt-6 bg-red-100 p-4 rounded-lg text-red-800 flex items-center space-x-3">
                                <i class="fas fa-exclamation-triangle text-xl"></i>
                                <span>Error loading data: {{ error }}</span>
                            </div>
                            <div v-if="!loading && !error && dashboardData" class="mt-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm flex items-center">
                                        <div class="p-2 bg-green-500 rounded-full mr-3">
                                            <i class="fas fa-users text-white"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold text-green-700 uppercase tracking-wide">Total Customers</p>
                                            <p class="text-xl font-bold text-green-900">
                                                {{ dashboardData.total_customers ?? 0 }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg shadow-sm">
                                        <p class="text-xs font-semibold text-blue-700 uppercase tracking-wide">Sales Orders</p>
                                        <p class="text-sm text-blue-900">
                                            <span class="font-bold text-2xl mr-1">{{ dashboardData.total_sales_orders ?? 0 }}</span>
                                            <span class="text-xs text-blue-600">total</span>
                                        </p>
                                        <p class="text-xs text-blue-700 mt-1">
                                            Outstanding / Open:
                                            <span class="font-semibold">{{ dashboardData.outstanding_sales_orders ?? 0 }}</span>
                                        </p>
                                    </div>
                                    
                                    <div class="bg-indigo-50 border-l-4 border-indigo-500 p-4 rounded-lg shadow-sm">
                                        <p class="text-xs font-semibold text-indigo-700 uppercase tracking-wide">Delivery Orders</p>
                                        <p class="text-sm text-indigo-900">
                                            <span class="font-bold text-2xl mr-1">{{ dashboardData.total_delivery_orders ?? 0 }}</span>
                                            <span class="text-xs text-indigo-600">total</span>
                                        </p>
                                        <p class="text-xs text-indigo-700 mt-1">
                                            Open / Active:
                                            <span class="font-semibold">{{ dashboardData.open_delivery_orders ?? 0 }}</span>
                                        </p>
                                    </div>
                                    
                                    <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-lg shadow-sm">
                                        <p class="text-xs font-semibold text-amber-700 uppercase tracking-wide">Invoices</p>
                                        <p class="text-sm text-amber-900">
                                            <span class="font-bold text-2xl mr-1">{{ dashboardData.total_invoices ?? 0 }}</span>
                                            <span class="text-xs text-amber-600">total</span>
                                        </p>
                                        <p class="text-xs text-amber-700 mt-1">
                                            Unposted:
                                            <span class="font-semibold">{{ dashboardData.unposted_invoices ?? 0 }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modals Component -->
        <CustomerServiceModals
            ref="modalsRef"
            @show-notification="showNotification"
            @so-selected="handleSalesOrderSelected"
        />

        <!-- Sales Order Detail View (CPS-style detail after Search by Sales Order OK) -->
        <SODetailView
            v-if="showSODetail && selectedSOData"
            :so-data="selectedSOData"
            @close="showSODetail = false"
        />

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
import axios from 'axios';
import SODetailView from '@/Components/SODetailView.vue';
import CustomerServiceModals from '@/Components/CustomerServiceModals.vue';

const dashboardData = ref(null);
const loading = ref(true);
const error = ref(null);
const modalsRef = ref(null); // Ref to access methods from the child component

// State for Sales Order detail view (result of Search by Sales Order flow)
const showSODetail = ref(false);
const selectedSOData = ref(null);

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

// Handler for SO detail selected from CustomerServiceModals (OK on Search by Sales Order)
const handleSalesOrderSelected = (soData) => {
    selectedSOData.value = soData;
    showSODetail.value = true;
};

// Wrapper agar tombol di dashboard selalu aman memanggil modal Search by Sales Order
const openSearchBySalesOrder = () => {
    if (modalsRef.value && typeof modalsRef.value.openInitialSalesOrderModal === 'function') {
        modalsRef.value.openInitialSalesOrderModal();
    } else {
        console.warn('CustomerServiceModals ref not ready or method openInitialSalesOrderModal not found');
    }
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
