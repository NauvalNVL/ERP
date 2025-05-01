@extends('layouts.app')

@section('title', 'Dashboard - ERP System')

@section('header', 'Dashboard')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6 mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-xl font-semibold text-gray-800">Welcome, {{ Auth::user()->name }}</h3>
            <p class="text-gray-600 mt-1">{{ now()->format('l, d F Y') }}</p>
        </div>
        <div class="bg-blue-100 p-2 rounded-full">
            <i class="fas fa-arrow-right text-blue-500 text-2xl"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Card 1 -->
    <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500 hover:shadow-md transition duration-300">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Sales</p>
                <h4 class="text-2xl font-bold text-gray-800">$8.5K</h4>
                <p class="text-xs text-green-500 mt-1">
                    <span class="font-bold">↑ 12%</span> from last month
                </p>
            </div>
            <div class="bg-blue-100 p-3 rounded-full">
                <i class="fas fa-dollar-sign text-blue-500"></i>
            </div>
        </div>
    </div>

    <!-- Card 2 -->
    <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500 hover:shadow-md transition duration-300">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-500 font-medium">Products Sold</p>
                <h4 class="text-2xl font-bold text-gray-800">254</h4>
                <p class="text-xs text-green-500 mt-1">
                    <span class="font-bold">↑ 8%</span> from last month
                </p>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
                <i class="fas fa-shopping-bag text-green-500"></i>
            </div>
        </div>
    </div>

    <!-- Card 3 -->
    <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-purple-500 hover:shadow-md transition duration-300">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-500 font-medium">New Customers</p>
                <h4 class="text-2xl font-bold text-gray-800">45</h4>
                <p class="text-xs text-green-500 mt-1">
                    <span class="font-bold">↑ 15%</span> from last month
                </p>
            </div>
            <div class="bg-purple-100 p-3 rounded-full">
                <i class="fas fa-users text-purple-500"></i>
            </div>
        </div>
    </div>

    <!-- Card 4 -->
    <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-yellow-500 hover:shadow-md transition duration-300">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-500 font-medium">Pending Orders</p>
                <h4 class="text-2xl font-bold text-gray-800">12</h4>
                <p class="text-xs text-red-500 mt-1">
                    <span class="font-bold">↑ 3</span> from yesterday
                </p>
            </div>
            <div class="bg-yellow-100 p-3 rounded-full">
                <i class="fas fa-clock text-yellow-500"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Chart -->
    <div class="bg-white rounded-lg shadow-sm p-6 lg:col-span-2">
        <h4 class="text-lg font-semibold text-gray-800 mb-4">Sales Chart</h4>
        <div class="h-64">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h4 class="text-lg font-semibold text-gray-800 mb-4">Recent Activities</h4>
        <div class="space-y-4">
            <div class="flex items-start">
                <div class="bg-blue-100 p-2 rounded-full mr-3">
                    <i class="fas fa-shopping-bag text-blue-500 text-sm"></i>
                </div>
                <div>
                    <p class="text-sm font-medium">New Order #1234</p>
                    <p class="text-xs text-gray-500">2 hours ago</p>
                </div>
            </div>
            <div class="flex items-start">
                <div class="bg-green-100 p-2 rounded-full mr-3">
                    <i class="fas fa-check text-green-500 text-sm"></i>
                </div>
                <div>
                    <p class="text-sm font-medium">Payment Received</p>
                    <p class="text-xs text-gray-500">4 hours ago</p>
                </div>
            </div>
            <div class="flex items-start">
                <div class="bg-yellow-100 p-2 rounded-full mr-3">
                    <i class="fas fa-exclamation-triangle text-yellow-500 text-sm"></i>
                </div>
                <div>
                    <p class="text-sm font-medium">Low Stock Alert</p>
                    <p class="text-xs text-gray-500">6 hours ago</p>
                </div>
            </div>
        </div>
        <button class="w-full mt-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded hover:bg-gray-200 transition duration-300">
            View All Activities
        </button>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Orders -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-4">
            <h4 class="text-lg font-semibold text-gray-800">Recent Orders</h4>
            <a href="#" class="text-sm text-blue-500 hover:underline">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">#1234</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">Ahmad Fauzi</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Completed</span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">$1,250.00</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">#1233</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">Siti Nurhaliza</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Processing</span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">$850.00</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">#1232</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">Budi Santoso</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Cancelled</span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">$2,100.00</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h4 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h4>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <a href="#" class="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition duration-300">
                <div class="bg-blue-100 p-3 rounded-full mb-2">
                    <i class="fas fa-plus text-blue-500"></i>
                </div>
                <span class="text-sm font-medium text-gray-700">Add Product</span>
            </a>
            <a href="#" class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition duration-300">
                <div class="bg-green-100 p-3 rounded-full mb-2">
                    <i class="fas fa-shopping-bag text-green-500"></i>
                </div>
                <span class="text-sm font-medium text-gray-700">Create Order</span>
            </a>
            <a href="#" class="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition duration-300">
                <div class="bg-purple-100 p-3 rounded-full mb-2">
                    <i class="fas fa-user-plus text-purple-500"></i>
                </div>
                <span class="text-sm font-medium text-gray-700">Add Customer</span>
            </a>
            <a href="#" class="flex flex-col items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition duration-300">
                <div class="bg-yellow-100 p-3 rounded-full mb-2">
                    <i class="fas fa-chart-bar text-yellow-500"></i>
                </div>
                <span class="text-sm font-medium text-gray-700">Reports</span>
            </a>
            <a href="#" class="flex flex-col items-center p-4 bg-red-50 rounded-lg hover:bg-red-100 transition duration-300">
                <div class="bg-red-100 p-3 rounded-full mb-2">
                    <i class="fas fa-money-bill-alt text-red-500"></i>
                </div>
                <span class="text-sm font-medium text-gray-700">Finance</span>
            </a>
            <a href="#" class="flex flex-col items-center p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition duration-300">
                <div class="bg-indigo-100 p-3 rounded-full mb-2">
                    <i class="fas fa-cog text-indigo-500"></i>
                </div>
                <span class="text-sm font-medium text-gray-700">Settings</span>
            </a>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Product Categories Chart -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h4 class="text-lg font-semibold text-gray-800 mb-4">Product Categories</h4>
        <div class="h-64">
            <canvas id="productChart"></canvas>
        </div>
    </div>
    
    <!-- Performance Metrics -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h4 class="text-lg font-semibold text-gray-800 mb-4">Monthly Performance</h4>
        <div class="space-y-4">
            <div>
                <div class="flex justify-between mb-1">
                    <span class="text-sm font-medium text-gray-700">Sales Target</span>
                    <span class="text-sm font-medium text-gray-700">85%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: 85%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between mb-1">
                    <span class="text-sm font-medium text-gray-700">Customer Satisfaction</span>
                    <span class="text-sm font-medium text-gray-700">92%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-green-600 h-2.5 rounded-full" style="width: 92%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between mb-1">
                    <span class="text-sm font-medium text-gray-700">On-time Delivery</span>
                    <span class="text-sm font-medium text-gray-700">78%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-yellow-500 h-2.5 rounded-full" style="width: 78%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between mb-1">
                    <span class="text-sm font-medium text-gray-700">Visitor Conversion</span>
                    <span class="text-sm font-medium text-gray-700">65%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-purple-600 h-2.5 rounded-full" style="width: 65%"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sales chart data
        const salesData = {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [
                {
                    label: 'Sales 2023',
                    data: [5.2, 6.3, 8.5, 7.8, 8.2, 7.5, 9.1, 10.5, 11.2, 9.8, 8.7, 12.3],
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderColor: 'rgba(59, 130, 246, 0.8)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Sales 2022',
                    data: [4.8, 5.7, 7.2, 6.5, 7.1, 6.8, 8.2, 9.3, 9.8, 8.5, 7.9, 10.5],
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderColor: 'rgba(16, 185, 129, 0.8)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }
            ]
        };

        // Chart configuration
        const config = {
            type: 'line',
            data: salesData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 6
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.9)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 10,
                        cornerRadius: 6,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': $' + context.parsed.y + 'K';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value + 'K';
                            }
                        }
                    }
                }
            }
        };

        // Initialize chart
        const salesChart = new Chart(
            document.getElementById('salesChart'),
            config
        );

        // Product categories chart
        const productData = {
            labels: ['Electronics', 'Fashion', 'Food', 'Furniture', 'Health'],
            datasets: [{
                label: 'Sales by Category',
                data: [35, 25, 20, 15, 5],
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(139, 92, 246, 0.8)',
                    'rgba(239, 68, 68, 0.8)'
                ],
                borderWidth: 0
            }]
        };

        // Doughnut chart configuration
        const doughnutConfig = {
            type: 'doughnut',
            data: productData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 6
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.9)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 10,
                        cornerRadius: 6,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed + '%';
                            }
                        }
                    }
                },
                cutout: '70%'
            }
        };

        // Initialize doughnut chart
        const productChart = new Chart(
            document.getElementById('productChart'),
            doughnutConfig
        );
    });
</script>
@endpush 