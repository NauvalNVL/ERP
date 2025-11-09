<template>
    <AppLayout :header="'Dashboard'">
    <Head title="Dashboard" />

    <!-- Tambahkan transition untuk smooth loading -->
        <div class="transition-opacity duration-300">
        <!-- Welcome Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-semibold text-gray-800">
                        Welcome, {{ user.username }}
                    </h3>
                    <p class="text-gray-600 mt-1">{{ formattedDate }}</p>
                </div>
                <div class="bg-blue-100 p-2 rounded-full">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-8 w-8 text-blue-500"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 12h14M12 5l7 7-7 7"
                        />
                    </svg>
                </div>
            </div>
        </div>

        <!-- ERP Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Sales Teams -->
            <div
                class="bg-white overflow-hidden shadow-sm rounded-lg transition-all duration-300 hover:shadow-lg hover-float animate-fadeIn"
                style="animation-delay: 100ms;"
            >
                <div class="p-6 flex items-center">
                    <div class="rounded-full bg-blue-500 p-3 mr-4">
                        <i class="fas fa-users-cog text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Sales Teams</p>
                        <p class="text-2xl font-semibold text-gray-900">12</p>
                        <p class="text-sm text-blue-600 flex items-center">
                            <i class="fas fa-info-circle mr-1"></i> <span class="text-gray-500">Active teams</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Product Groups -->
            <div
                class="bg-white overflow-hidden shadow-sm rounded-lg transition-all duration-300 hover:shadow-lg hover-float animate-fadeIn"
                style="animation-delay: 200ms;"
            >
                <div class="p-6 flex items-center">
                    <div class="rounded-full bg-green-500 p-3 mr-4">
                        <i class="fas fa-boxes text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Product Groups</p>
                        <p class="text-2xl font-semibold text-gray-900">45</p>
                        <p class="text-sm text-green-600 flex items-center">
                            <i class="fas fa-info-circle mr-1"></i> <span class="text-gray-500">Defined groups</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Machines -->
            <div
                class="bg-white overflow-hidden shadow-sm rounded-lg transition-all duration-300 hover:shadow-lg hover-float animate-fadeIn"
                style="animation-delay: 300ms;"
            >
                <div class="p-6 flex items-center">
                    <div class="rounded-full bg-purple-500 p-3 mr-4">
                        <i class="fas fa-cogs text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Machines</p>
                        <p class="text-2xl font-semibold text-gray-900">28</p>
                        <p class="text-sm text-purple-600 flex items-center">
                            <i class="fas fa-info-circle mr-1"></i> <span class="text-gray-500">Active machines</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Paper Qualities -->
            <div
                class="bg-white overflow-hidden shadow-sm rounded-lg transition-all duration-300 hover:shadow-lg hover-float animate-fadeIn"
                style="animation-delay: 400ms;"
            >
                <div class="p-6 flex items-center">
                    <div class="rounded-full bg-orange-500 p-3 mr-4">
                        <i class="fas fa-scroll text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Paper Qualities</p>
                        <p class="text-2xl font-semibold text-gray-900">67</p>
                        <p class="text-sm text-orange-600 flex items-center">
                            <i class="fas fa-info-circle mr-1"></i> <span class="text-gray-500">Defined qualities</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ERP Module Shortcuts -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-sm p-6 lg:col-span-2">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">
                    ERP Module Access
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="/sales-team" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                        <div class="p-2 bg-blue-500 rounded-full mr-3">
                            <i class="fas fa-clipboard-list text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-blue-900">Standard Requirement</p>
                            <p class="text-xs text-blue-700">Manage sales teams, products, machines</p>
                        </div>
                    </a>
                    <a href="/customer-group" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                        <div class="p-2 bg-green-500 rounded-full mr-3">
                            <i class="fas fa-user-circle text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-green-900">Customer Account</p>
                            <p class="text-xs text-green-700">Manage customer groups & accounts</p>
                        </div>
                    </a>
                    <a href="/user" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                        <div class="p-2 bg-purple-500 rounded-full mr-3">
                            <i class="fas fa-shield-alt text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-purple-900">System Security</p>
                            <p class="text-xs text-purple-700">User management & permissions</p>
                        </div>
                    </a>
                    <a href="/machine/view-print" class="flex items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors">
                        <div class="p-2 bg-orange-500 rounded-full mr-3">
                            <i class="fas fa-print text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-orange-900">View & Print Reports</p>
                            <p class="text-xs text-orange-700">Generate system reports</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">
                    Recent Activities
                </h4>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="bg-blue-100 p-2 rounded-full mr-3">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 text-blue-500"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
                                />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium">New Order #1234</p>
                            <p class="text-xs text-gray-500">2 hours ago</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="bg-green-100 p-2 rounded-full mr-3">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 text-green-500"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium">Payment Received</p>
                            <p class="text-xs text-gray-500">4 hours ago</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="bg-yellow-100 p-2 rounded-full mr-3">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 text-yellow-500"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                                />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium">Low Stock Alert</p>
                            <p class="text-xs text-gray-500">6 hours ago</p>
                        </div>
                    </div>
                </div>
                <button
                    class="w-full mt-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded hover:bg-gray-200 transition duration-300"
                >
                    View All Activities
                </button>
            </div>
        </div>

        <!-- Recent Orders and Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Orders Table -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-lg font-semibold text-gray-800">
                        Recent Orders
                    </h4>
                    <a href="#" class="text-sm text-blue-500 hover:underline"
                        >View All</a
                    >
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    ID
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Customer
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Status
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Total
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td
                                    class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900"
                                >
                                    #1234
                                </td>
                                <td
                                    class="px-4 py-3 whitespace-nowrap text-sm text-gray-500"
                                >
                                    Ahmad Fauzi
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800"
                                        >Completed</span
                                    >
                                </td>
                                <td
                                    class="px-4 py-3 whitespace-nowrap text-sm text-gray-500"
                                >
                                    $1,250.00
                                </td>
                            </tr>
                            <tr>
                                <td
                                    class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900"
                                >
                                    #1233
                                </td>
                                <td
                                    class="px-4 py-3 whitespace-nowrap text-sm text-gray-500"
                                >
                                    Siti Nurhaliza
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800"
                                        >Processing</span
                                    >
                                </td>
                                <td
                                    class="px-4 py-3 whitespace-nowrap text-sm text-gray-500"
                                >
                                    $850.00
                                </td>
                            </tr>
                            <tr>
                                <td
                                    class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900"
                                >
                                    #1232
                                </td>
                                <td
                                    class="px-4 py-3 whitespace-nowrap text-sm text-gray-500"
                                >
                                    Budi Santoso
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800"
                                        >Cancelled</span
                                    >
                                </td>
                                <td
                                    class="px-4 py-3 whitespace-nowrap text-sm text-gray-500"
                                >
                                    $2,100.00
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ERP Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">
                    Quick Actions - ERP Modules
                </h4>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <a
                        href="/sales-team"
                        class="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition duration-300"
                    >
                        <div class="bg-blue-100 p-3 rounded-full mb-2">
                            <i class="fas fa-users-cog text-blue-500 text-xl"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700"
                            >Define Sales Team</span
                        >
                    </a>
                    <a
                        href="/product-group"
                        class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition duration-300"
                    >
                        <div class="bg-green-100 p-3 rounded-full mb-2">
                            <i class="fas fa-boxes text-green-500 text-xl"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700"
                            >Define Product Group</span
                        >
                    </a>
                    <a
                        href="/machine"
                        class="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition duration-300"
                    >
                        <div class="bg-purple-100 p-3 rounded-full mb-2">
                            <i class="fas fa-cogs text-purple-500 text-xl"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700"
                            >Define Machine</span
                        >
                    </a>
                    <a
                        href="/paper-quality"
                        class="flex flex-col items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition duration-300"
                    >
                        <div class="bg-orange-100 p-3 rounded-full mb-2">
                            <i class="fas fa-scroll text-orange-500 text-xl"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700"
                            >Define Paper Quality</span
                        >
                    </a>
                    <a
                        href="/machine/view-print"
                        class="flex flex-col items-center p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition duration-300"
                    >
                        <div class="bg-indigo-100 p-3 rounded-full mb-2">
                            <i class="fas fa-print text-indigo-500 text-xl"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700"
                            >View & Print Machine</span
                        >
                    </a>
                    <a
                        href="/user"
                        class="flex flex-col items-center p-4 bg-red-50 rounded-lg hover:bg-red-100 transition duration-300"
                    >
                        <div class="bg-red-100 p-3 rounded-full mb-2">
                            <i class="fas fa-user-plus text-red-500 text-xl"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700"
                            >System Security</span
                        >
                    </a>
                </div>
            </div>
        </div>

        <!-- ERP System Information -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">
                    Recently Added Data
                </h4>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-users-cog text-blue-500 mr-3"></i>
                            <span class="text-sm font-medium">Sales Team "Team Alpha"</span>
                        </div>
                        <span class="text-xs text-gray-500">2 hours ago</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-cogs text-green-500 mr-3"></i>
                            <span class="text-sm font-medium">Machine "Corrugator-01"</span>
                        </div>
                        <span class="text-xs text-gray-500">4 hours ago</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-scroll text-purple-500 mr-3"></i>
                            <span class="text-sm font-medium">Paper Quality "Premium Grade"</span>
                        </div>
                        <span class="text-xs text-gray-500">6 hours ago</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-boxes text-orange-500 mr-3"></i>
                            <span class="text-sm font-medium">Product Group "Packaging"</span>
                        </div>
                        <span class="text-xs text-gray-500">8 hours ago</span>
                    </div>
                </div>
            </div>

            <!-- System Status -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">
                    System Status
                </h4>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700"
                                >Data Completeness</span
                            >
                            <span class="text-sm font-medium text-gray-700"
                                >95%</span
                            >
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div
                                class="bg-blue-600 h-2.5 rounded-full"
                                style="width: 95%"
                            ></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700"
                                >Active Modules</span
                            >
                            <span class="text-sm font-medium text-gray-700"
                                >8/10</span
                            >
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div
                                class="bg-green-600 h-2.5 rounded-full"
                                style="width: 80%"
                            ></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700"
                                >User Activity</span
                            >
                            <span class="text-sm font-medium text-gray-700"
                                >Active</span
                            >
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div
                                class="bg-green-600 h-2.5 rounded-full"
                                style="width: 100%"
                            ></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700"
                                >System Health</span
                            >
                            <span class="text-sm font-medium text-gray-700"
                                >Excellent</span
                            >
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div
                                class="bg-green-600 h-2.5 rounded-full"
                                style="width: 98%"
                            ></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import { Head } from "@inertiajs/vue3";
import { usePage } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';

const page = usePage();
const user = computed(() => page.props.auth.user);

const formattedDate = computed(() => {
    const now = new Date();
    return now.toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
});

// ERP Dashboard initialization
onMounted(() => {
    console.log('ERP Dashboard loaded for user:', user.value?.username);
});
</script>

<style scoped>
.hover-float {
    transition: transform 0.3s ease;
}

.hover-float:hover {
    transform: translateY(-5px);
}

.animate-fadeIn {
    animation: fadeIn 0.8s ease-in-out forwards;
    opacity: 0;
}

.animate-slideUp {
    animation: slideUp 0.8s ease-out forwards;
    opacity: 0;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideUp {
    from {
        transform: translateY(30px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
</style>
