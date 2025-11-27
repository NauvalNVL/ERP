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
                        <p class="text-2xl font-semibold text-gray-900">{{ dashboardStats.salesTeams || 0 }}</p>
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
                        <p class="text-2xl font-semibold text-gray-900">{{ dashboardStats.productGroups || 0 }}</p>
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
                        <p class="text-2xl font-semibold text-gray-900">{{ dashboardStats.machines || 0 }}</p>
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
                        <p class="text-2xl font-semibold text-gray-900">{{ dashboardStats.paperQualities || 0 }}</p>
                        <p class="text-sm text-orange-600 flex items-center">
                            <i class="fas fa-info-circle mr-1"></i> <span class="text-gray-500">Defined qualities</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Master Data Overview Chart -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-lg font-semibold text-gray-800">
                    Master Data Overview
                </h4>
            </div>
            <div class="h-64">
                <canvas ref="kpiChart" class="w-full h-full"></canvas>
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

            <!-- Business Snapshot (real metrics) -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">
                    Business Snapshot
                </h4>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-2 rounded-full mr-3">
                                <i class="fas fa-user-friends text-blue-500"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">
                                    Total Customers
                                </p>
                                <p class="text-xs text-gray-500">
                                    From CUSTOMER master
                                </p>
                            </div>
                        </div>
                        <div class="text-xl font-semibold text-gray-900">
                            {{ businessStats.customers || 0 }}
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="bg-green-100 p-2 rounded-full mr-3">
                                <i class="fas fa-file-invoice-dollar text-green-500"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">
                                    Total Invoices
                                </p>
                                <p class="text-xs text-gray-500">
                                    From INV table
                                </p>
                            </div>
                        </div>
                        <div class="text-xl font-semibold text-gray-900">
                            {{ businessStats.invoices || 0 }}
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="bg-purple-100 p-2 rounded-full mr-3">
                                <i class="fas fa-file-signature text-purple-500"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">
                                    Sales Orders
                                </p>
                                <p class="text-xs text-gray-500">
                                    From sales orders module
                                </p>
                            </div>
                        </div>
                        <div class="text-xl font-semibold text-gray-900">
                            {{ businessStats.salesOrders || 0 }}
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="bg-orange-100 p-2 rounded-full mr-3">
                                <i class="fas fa-truck-loading text-orange-500"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">
                                    Delivery Orders
                                </p>
                                <p class="text-xs text-gray-500">
                                    From DO table
                                </p>
                            </div>
                        </div>
                        <div class="text-xl font-semibold text-gray-900">
                            {{ businessStats.deliveryOrders || 0 }}
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Recent Data and Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Industries Table (real data) -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-lg font-semibold text-gray-800">
                        Recent Industries
                    </h4>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Code
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Name
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-if="!recentIndustries || recentIndustries.length === 0">
                                <td
                                    colspan="3"
                                    class="px-4 py-3 whitespace-nowrap text-sm text-center text-gray-500"
                                >
                                    No recent industries found.
                                </td>
                            </tr>
                            <tr v-for="industry in recentIndustries" :key="industry.code">
                                <td
                                    class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900"
                                >
                                    {{ industry.code }}
                                </td>
                                <td
                                    class="px-4 py-3 whitespace-nowrap text-sm text-gray-500"
                                >
                                    {{ industry.name }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                        :class="['A', 'ACT', 'ACTIVE'].includes(((industry.status ?? '').toString().trim().toUpperCase()))
                                            ? 'bg-green-100 text-green-800'
                                            : 'bg-red-100 text-red-800'"
                                    >
                                        {{ ['A', 'ACT', 'ACTIVE'].includes(((industry.status ?? '').toString().trim().toUpperCase()))
                                            ? 'Active'
                                            : 'Obsolete' }}
                                    </span>
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

        <!-- Invoices Overview (real data) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Invoice Trend Chart -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">
                    Invoice Trend (Last 6 Months)
                </h4>
                <div class="h-64">
                    <canvas ref="invoiceChart" class="w-full h-full"></canvas>
                </div>
            </div>

            <!-- Invoice Summary -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">
                    Invoice Summary
                </h4>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-700">
                                Total Invoices (All Time)
                            </p>
                            <p class="text-xs text-gray-500">From INV table</p>
                        </div>
                        <div class="text-xl font-semibold text-gray-900">
                            {{ businessStats.invoices || 0 }}
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-700">
                                Current Month
                            </p>
                            <p class="text-xs text-gray-500">
                                Invoices this month
                            </p>
                        </div>
                        <div class="text-xl font-semibold text-gray-900">
                            {{ invoiceTrendData.data && invoiceTrendData.data.length
                                ? invoiceTrendData.data[invoiceTrendData.data.length - 1]
                                : 0 }}
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-700">
                                Previous Month
                            </p>
                            <p class="text-xs text-gray-500">
                                Invoices last month
                            </p>
                        </div>
                        <div class="text-xl font-semibold text-gray-900">
                            {{ invoiceTrendData.data && invoiceTrendData.data.length > 1
                                ? invoiceTrendData.data[invoiceTrendData.data.length - 2]
                                : 0 }}
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-700">
                                Month-over-Month Change
                            </p>
                            <p class="text-xs text-gray-500">
                                Current vs previous month
                            </p>
                        </div>
                        <div
                            class="text-sm font-semibold"
                            :class="invoiceChange >= 0 ? 'text-green-600' : 'text-red-600'"
                        >
                            <span v-if="invoiceChange >= 0">+{{ invoiceChange }}</span>
                            <span v-else>{{ invoiceChange }}</span>
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
import { Head, usePage } from "@inertiajs/vue3";
import AppLayout from "../Layouts/AppLayout.vue";
import Chart from "chart.js/auto";

const page = usePage();

const user = computed(() => page.props.auth.user);
const dashboardStats = computed(() => page.props.dashboardStats || {});
const businessStats = computed(() => page.props.businessStats || {});
const recentIndustries = computed(() => page.props.recentIndustries || []);
const kpiChartData = computed(
    () => page.props.kpiChartData || { labels: [], data: [] }
);
const invoiceTrendData = computed(
    () => page.props.invoiceTrendData || { labels: [], data: [] }
);

// Month-over-month change in invoice count (current month - previous month)
const invoiceChange = computed(() => {
    const data = invoiceTrendData.value?.data || [];
    if (!Array.isArray(data) || data.length < 2) {
        return 0;
    }
    const current = Number(data[data.length - 1] ?? 0) || 0;
    const previous = Number(data[data.length - 2] ?? 0) || 0;
    return current - previous;
});

const formattedDate = computed(() => {
    const now = new Date();
    return now.toLocaleDateString("en-US", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    });
});

const kpiChart = ref(null);
let kpiChartInstance = null;

const invoiceChart = ref(null);
let invoiceChartInstance = null;

onMounted(() => {
    console.log("ERP Dashboard loaded for user:", user.value?.username);

    if (kpiChart.value) {
        const ctx = kpiChart.value.getContext("2d");

        if (kpiChartInstance) {
            kpiChartInstance.destroy();
        }

        kpiChartInstance = new Chart(ctx, {
            type: "bar",
            data: {
                labels: kpiChartData.value.labels,
                datasets: [
                    {
                        label: "Count",
                        data: kpiChartData.value.data,
                        backgroundColor: [
                            "rgba(59, 130, 246, 0.6)", // blue
                            "rgba(34, 197, 94, 0.6)", // green
                            "rgba(147, 51, 234, 0.6)", // purple
                            "rgba(249, 115, 22, 0.6)", // orange
                        ],
                        borderColor: [
                            "rgba(59, 130, 246, 1)",
                            "rgba(34, 197, 94, 1)",
                            "rgba(147, 51, 234, 1)",
                            "rgba(249, 115, 22, 1)",
                        ],
                        borderWidth: 1,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                        },
                    },
                },
            },
        });
    }

    if (invoiceChart.value) {
        const ctx2 = invoiceChart.value.getContext("2d");

        if (invoiceChartInstance) {
            invoiceChartInstance.destroy();
        }

        invoiceChartInstance = new Chart(ctx2, {
            type: "line",
            data: {
                labels: invoiceTrendData.value.labels,
                datasets: [
                    {
                        label: "Invoices per Month",
                        data: invoiceTrendData.value.data,
                        borderColor: "rgba(59, 130, 246, 1)",
                        backgroundColor: "rgba(59, 130, 246, 0.15)",
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                        },
                    },
                },
            },
        });
    }
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
