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

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Card 1 -->
            <div
                class="bg-white overflow-hidden shadow-sm rounded-lg transition-all duration-300 hover:shadow-lg hover-float animate-fadeIn"
                style="animation-delay: 100ms;"
            >
                <div class="p-6 flex items-center">
                    <div class="rounded-full bg-blue-500 p-3 mr-4">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Sales</p>
                        <p class="text-2xl font-semibold text-gray-900">$24,780</p>
                        <p class="text-sm text-green-600 flex items-center">
                            <i class="fas fa-arrow-up mr-1"></i> 12% <span class="text-gray-500 ml-2">from last month</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div
                class="bg-white overflow-hidden shadow-sm rounded-lg transition-all duration-300 hover:shadow-lg hover-float animate-fadeIn"
                style="animation-delay: 200ms;"
            >
                <div class="p-6 flex items-center">
                    <div class="rounded-full bg-green-500 p-3 mr-4">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Active Customers</p>
                        <p class="text-2xl font-semibold text-gray-900">573</p>
                        <p class="text-sm text-green-600 flex items-center">
                            <i class="fas fa-arrow-up mr-1"></i> 5.3% <span class="text-gray-500 ml-2">from last month</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div
                class="bg-white overflow-hidden shadow-sm rounded-lg transition-all duration-300 hover:shadow-lg hover-float animate-fadeIn"
                style="animation-delay: 300ms;"
            >
                <div class="p-6 flex items-center">
                    <div class="rounded-full bg-purple-500 p-3 mr-4">
                        <i class="fas fa-box text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Products</p>
                        <p class="text-2xl font-semibold text-gray-900">128</p>
                        <p class="text-sm text-green-600 flex items-center">
                            <i class="fas fa-arrow-up mr-1"></i> 3.2% <span class="text-gray-500 ml-2">from last month</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div
                class="bg-white overflow-hidden shadow-sm rounded-lg transition-all duration-300 hover:shadow-lg hover-float animate-fadeIn"
                style="animation-delay: 400ms;"
            >
                <div class="p-6 flex items-center">
                    <div class="rounded-full bg-red-500 p-3 mr-4">
                        <i class="fas fa-tasks text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Pending Orders</p>
                        <p class="text-2xl font-semibold text-gray-900">25</p>
                        <p class="text-sm text-red-600 flex items-center">
                            <i class="fas fa-arrow-down mr-1"></i> 2.5% <span class="text-gray-500 ml-2">from last month</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Activities -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-sm p-6 lg:col-span-2">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">
                    Sales Chart
                </h4>
                <div class="h-64">
                    <canvas ref="salesChart"></canvas>
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

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">
                    Quick Actions
                </h4>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <a
                        href="#"
                        class="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition duration-300"
                    >
                        <div class="bg-blue-100 p-3 rounded-full mb-2">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 text-blue-500"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700"
                            >Add Product</span
                        >
                    </a>
                    <a
                        href="#"
                        class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition duration-300"
                    >
                        <div class="bg-green-100 p-3 rounded-full mb-2">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 text-green-500"
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
                        <span class="text-sm font-medium text-gray-700"
                            >Create Order</span
                        >
                    </a>
                    <a
                        href="#"
                        class="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition duration-300"
                    >
                        <div class="bg-purple-100 p-3 rounded-full mb-2">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 text-purple-500"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"
                                />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700"
                            >Add Customer</span
                        >
                    </a>
                    <a
                        href="#"
                        class="flex flex-col items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition duration-300"
                    >
                        <div class="bg-yellow-100 p-3 rounded-full mb-2">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 text-yellow-500"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700"
                            >Reports</span
                        >
                    </a>
                    <a
                        href="#"
                        class="flex flex-col items-center p-4 bg-red-50 rounded-lg hover:bg-red-100 transition duration-300"
                    >
                        <div class="bg-red-100 p-3 rounded-full mb-2">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 text-red-500"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700"
                            >Finance</span
                        >
                    </a>
                    <a
                        href="#"
                        class="flex flex-col items-center p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition duration-300"
                    >
                        <div class="bg-indigo-100 p-3 rounded-full mb-2">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 text-indigo-500"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                                />
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700"
                            >Settings</span
                        >
                    </a>
                </div>
            </div>
        </div>

        <!-- Product Categories Chart -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">
                    Product Categories
                </h4>
                <div class="h-64">
                    <canvas ref="productChart"></canvas>
                </div>
            </div>

            <!-- Performance Metrics -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">
                    Monthly Performance
                </h4>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700"
                                >Sales Target</span
                            >
                            <span class="text-sm font-medium text-gray-700"
                                >85%</span
                            >
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div
                                class="bg-blue-600 h-2.5 rounded-full"
                                style="width: 85%"
                            ></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700"
                                >Customer Satisfaction</span
                            >
                            <span class="text-sm font-medium text-gray-700"
                                >92%</span
                            >
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div
                                class="bg-green-600 h-2.5 rounded-full"
                                style="width: 92%"
                            ></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700"
                                >On-time Delivery</span
                            >
                            <span class="text-sm font-medium text-gray-700"
                                >78%</span
                            >
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div
                                class="bg-yellow-500 h-2.5 rounded-full"
                                style="width: 78%"
                            ></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700"
                                >Visitor Conversion</span
                            >
                            <span class="text-sm font-medium text-gray-700"
                                >65%</span
                            >
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div
                                class="bg-purple-600 h-2.5 rounded-full"
                                style="width: 65%"
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
import Chart from "chart.js/auto";

const page = usePage();
const user = computed(() => page.props.auth.user);

const salesChart = ref(null);
const productChart = ref(null);

const formattedDate = computed(() => {
    const now = new Date();
    return now.toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
});

// Chart configurations
const initCharts = () => {
    if (!salesChart.value || !productChart.value) return;

    // Sales Chart
    new Chart(salesChart.value, {
        type: "line",
        data: {
            labels: [
                "Jan",
                "Feb",
                "Mar",
                "Apr",
                "May",
                "Jun",
                "Jul",
                "Aug",
                "Sep",
                "Oct",
                "Nov",
                "Dec",
            ],
            datasets: [
                {
                    label: "Sales 2023",
                    data: [
                        5.2, 6.3, 8.5, 7.8, 8.2, 7.5, 9.1, 10.5, 11.2, 9.8, 8.7,
                        12.3,
                    ],
                    backgroundColor: "rgba(59, 130, 246, 0.1)",
                    borderColor: "rgba(59, 130, 246, 0.8)",
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                },
                {
                    label: "Sales 2022",
                    data: [
                        4.8, 5.7, 7.2, 6.5, 7.1, 6.8, 8.2, 9.3, 9.8, 8.5, 7.9,
                        10.5,
                    ],
                    backgroundColor: "rgba(16, 185, 129, 0.1)",
                    borderColor: "rgba(16, 185, 129, 0.8)",
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: "top",
                    labels: {
                        usePointStyle: true,
                        boxWidth: 6,
                    },
                },
                tooltip: {
                    backgroundColor: "rgba(17, 24, 39, 0.9)",
                    titleColor: "#fff",
                    bodyColor: "#fff",
                    padding: 10,
                    cornerRadius: 6,
                    displayColors: false,
                    callbacks: {
                        label: function (context) {
                            return (
                                context.dataset.label +
                                ": $" +
                                context.parsed.y +
                                "K"
                            );
                        },
                    },
                },
            },
            scales: {
                x: { grid: { display: false } },
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function (value) {
                            return "$" + value + "K";
                        },
                    },
                },
            },
        },
    });

    // Product Categories Chart
    new Chart(productChart.value, {
        type: "doughnut",
        data: {
            labels: ["Electronics", "Fashion", "Food", "Furniture", "Health"],
            datasets: [
                {
                    label: "Sales by Category",
                    data: [35, 25, 20, 15, 5],
                    backgroundColor: [
                        "rgba(59, 130, 246, 0.8)",
                        "rgba(16, 185, 129, 0.8)",
                        "rgba(245, 158, 11, 0.8)",
                        "rgba(139, 92, 246, 0.8)",
                        "rgba(239, 68, 68, 0.8)",
                    ],
                    borderWidth: 0,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: "right",
                    labels: {
                        usePointStyle: true,
                        boxWidth: 6,
                    },
                },
                tooltip: {
                    backgroundColor: "rgba(17, 24, 39, 0.9)",
                    titleColor: "#fff",
                    bodyColor: "#fff",
                    padding: 10,
                    cornerRadius: 6,
                    displayColors: false,
                    callbacks: {
                        label: function (context) {
                            return context.label + ": " + context.parsed + "%";
                        },
                    },
                },
            },
            cutout: "70%",
        },
    });
};

onMounted(() => {
    initCharts();
});

// Untuk responsive charts
const resizeCharts = () => {
    if (window.chartInstances) {
        window.chartInstances.forEach((chart) => chart.destroy());
        initCharts();
    }
};

window.addEventListener("resize", resizeCharts);
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
