<template>
    <AppLayout :header="'View & Print Customer Alternate Addresses'">
        <Head title="View & Print Customer Alternate Addresses" />

        <!-- Main Container with vibrant gradient background -->
        <div
            class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8"
        >
            <div class="max-w-7xl mx-auto">
                <!-- Header Section with animated elements -->
                <div
                    class="bg-blue-600 text-white shadow-sm rounded-xl border border-blue-700 mb-4"
                >
                    <div class="relative overflow-hidden">
                        <!-- Decorative Elements -->
                        <div
                            class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20 animate-pulse-slow"
                        ></div>
                        <div
                            class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10 animate-pulse-slow animation-delay-500"
                        ></div>
                        <div
                            class="absolute bottom-0 right-0 w-32 h-32 bg-cyan-400 opacity-5 rounded-full translate-y-10 translate-x-10"
                        ></div>

                        <div class="p-6 md:p-8 relative z-10">
                            <div
                                class="flex flex-col md:flex-row md:items-center md:justify-between"
                            >
                                <div
                                    class="flex items-start md:items-center space-x-4 mb-4 md:mb-0"
                                >
                                    <div
                                        class="bg-gradient-to-br from-cyan-500 to-teal-600 p-3 rounded-lg shadow-inner flex items-center justify-center relative overflow-hidden"
                                    >
                                        <div
                                            class="absolute -top-1 -right-1 w-6 h-6 bg-blue-300 opacity-30 rounded-full animate-ping-slow"
                                        ></div>
                                        <div
                                            class="absolute -bottom-1 -left-1 w-4 h-4 bg-teal-300 opacity-30 rounded-full animate-ping-slow animation-delay-500"
                                        ></div>
                                        <i
                                            class="fas fa-map-marker-alt text-white text-2xl z-10"
                                        ></i>
                                    </div>
                                    <div>
                                        <h1
                                            class="text-2xl md:text-3xl font-bold text-white text-shadow"
                                        >
                                            View & Print Customer Alternate
                                            Addresses
                                        </h1>
                                        <p class="text-teal-100 max-w-2xl">
                                            Preview and print customer alternate
                                            address data
                                        </p>
                                    </div>
                                </div>
                                <div class="flex flex-wrap gap-3">
                                    <button
                                        @click="printPdf"
                                        class="bg-gradient-to-r from-blue-600 to-cyan-600 text-white hover:from-blue-700 hover:to-cyan-700 px-4 py-2 rounded-lg flex items-center transition-all duration-300 transform hover:scale-105 shadow-md relative overflow-hidden group"
                                    >
                                        <span
                                            class="absolute inset-0 bg-white opacity-20 transform scale-x-0 origin-left transition-transform group-hover:scale-x-100"
                                        ></span>
                                        <div
                                            class="relative z-10 flex items-center"
                                        >
                                            <div
                                                class="bg-white bg-opacity-30 rounded-full p-1 mr-2"
                                            >
                                                <i
                                                    class="fas fa-file-pdf text-white"
                                                ></i>
                                            </div>
                                            <span>Print PDF</span>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-4">
                    <div
                        class="bg-blue-50 border-b border-gray-100 text-slate-800 py-3 px-6"
                    >
                        <div class="flex items-center">
                            <div
                                class="mr-3 bg-white bg-opacity-20 p-2 rounded-full shadow-inner relative overflow-hidden"
                            >
                                <div
                                    class="absolute -top-1 -right-1 w-4 h-4 bg-cyan-300 opacity-30 rounded-full"
                                ></div>
                                <div
                                    class="absolute -bottom-1 -left-1 w-3 h-3 bg-teal-300 opacity-30 rounded-full"
                                ></div>
                                <i
                                    class="fas fa-filter text-xl relative z-10"
                                ></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold">
                                    Search Parameters
                                </h2>
                                <p class="text-xs text-cyan-100 opacity-80">
                                    Filter customer alternate addresses by
                                    various criteria
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <!-- Actions Bar -->
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-end"
                        >
                            <!-- Search Field -->
                            <div class="space-y-2">
                                <label
                                    class="text-sm font-medium text-blue-700 mb-2 flex items-center"
                                >
                                    <div
                                        class="w-8 h-8 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-full mr-2 flex items-center justify-center shadow-sm"
                                    >
                                        <i
                                            class="fas fa-search text-white text-sm"
                                        ></i>
                                    </div>
                                    Search:
                                </label>
                                <div class="relative flex-grow">
                                    <input
                                        type="text"
                                        v-model="searchTerm"
                                        class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                                        placeholder="Search by customer name, code, or address..."
                                    />
                                    <div
                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                                    >
                                        <i
                                            class="fas fa-search text-gray-400"
                                        ></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Filter -->
                            <div class="space-y-2">
                                <label
                                    class="text-sm font-medium text-blue-700 mb-2 flex items-center"
                                >
                                    <div
                                        class="w-8 h-8 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-full mr-2 flex items-center justify-center shadow-sm"
                                    >
                                        <i
                                            class="fas fa-tag text-white text-sm"
                                        ></i>
                                    </div>
                                    Record Status:
                                </label>
                                <select
                                    v-model="recordStatus"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                                >
                                    <option value="both">All Records</option>
                                    <option value="Active">Active Only</option>
                                    <option value="Obsolete">
                                        Obsolete Only
                                    </option>
                                </select>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-end">
                                <button
                                    @click="
                                        searchTerm = '';
                                        recordStatus = 'both';
                                    "
                                    class="px-6 py-2.5 border border-gray-300 rounded-lg bg-white hover:bg-gray-50 text-gray-700 transition-all duration-300 shadow-sm flex items-center mr-3"
                                >
                                    <i
                                        class="fas fa-undo mr-2 text-gray-500"
                                    ></i>
                                    Reset Filters
                                </button>
                                <button
                                    @click="fetchData()"
                                    class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white rounded-lg transition-all duration-300 shadow-md flex items-center"
                                >
                                    <i class="fas fa-sync mr-2"></i>
                                    Refresh Data
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div
                        class="bg-blue-50 border-b border-gray-100 text-slate-800 py-3 px-6"
                    >
                        <div class="flex items-center">
                            <div
                                class="mr-4 bg-white bg-opacity-20 p-2 rounded-full shadow-inner"
                            >
                                <i class="fas fa-table text-2xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold">
                                    Customer Alternate Addresses Data
                                </h2>
                                <p class="text-sm opacity-80">
                                    View and manage customer alternate address
                                    information
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="overflow-x-auto bg-white rounded-lg max-w-full"
                        id="printable-table"
                    >
                        <table
                            class="min-w-full divide-y divide-gray-200 text-xs sm:text-sm"
                        >
                            <thead
                                class="bg-gradient-to-r from-cyan-50 to-blue-50"
                            >
                                <tr>
                                    <th
                                        @click="sortBy('customer_code')"
                                        class="px-3 py-2 sm:px-4 sm:py-3 md:px-6 md:py-3 text-[10px] sm:text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors"
                                    >
                                        <div class="flex items-center">
                                            <span>Customer Code</span>
                                            <i class="fas fa-sort ml-1"></i>
                                        </div>
                                    </th>
                                    <th
                                        @click="sortBy('delivery_code')"
                                        class="px-3 py-2 sm:px-4 sm:py-3 md:px-6 md:py-3 text-[10px] sm:text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors"
                                    >
                                        <div class="flex items-center">
                                            <span>Delivery Code</span>
                                            <i class="fas fa-sort ml-1"></i>
                                        </div>
                                    </th>
                                    <th
                                        @click="sortBy('country')"
                                        class="px-3 py-2 sm:px-4 sm:py-3 md:px-6 md:py-3 text-[10px] sm:text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors"
                                    >
                                        <div class="flex items-center">
                                            <span>Country</span>
                                            <i class="fas fa-sort ml-1"></i>
                                        </div>
                                    </th>
                                    <th
                                        @click="sortBy('state')"
                                        class="px-3 py-2 sm:px-4 sm:py-3 md:px-6 md:py-3 text-[10px] sm:text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors"
                                    >
                                        <div class="flex items-center">
                                            <span>State</span>
                                            <i class="fas fa-sort ml-1"></i>
                                        </div>
                                    </th>
                                    <th
                                        @click="sortBy('town')"
                                        class="px-3 py-2 sm:px-4 sm:py-3 md:px-6 md:py-3 text-[10px] sm:text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors"
                                    >
                                        <div class="flex items-center">
                                            <span>Town</span>
                                            <i class="fas fa-sort ml-1"></i>
                                        </div>
                                    </th>
                                    <th
                                        @click="sortBy('bill_to_name')"
                                        class="px-3 py-2 sm:px-4 sm:py-3 md:px-6 md:py-3 text-[10px] sm:text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors"
                                    >
                                        <div class="flex items-center">
                                            <span>Bill To Name</span>
                                            <i class="fas fa-sort ml-1"></i>
                                        </div>
                                    </th>
                                    <th
                                        @click="sortBy('bill_to_address')"
                                        class="px-3 py-2 sm:px-4 sm:py-3 md:px-6 md:py-3 text-[10px] sm:text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors"
                                    >
                                        <div class="flex items-center">
                                            <span>Bill To Address</span>
                                            <i class="fas fa-sort ml-1"></i>
                                        </div>
                                    </th>
                                    <th
                                        @click="sortBy('ship_to_name')"
                                        class="px-3 py-2 sm:px-4 sm:py-3 md:px-6 md:py-3 text-[10px] sm:text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors"
                                    >
                                        <div class="flex items-center">
                                            <span>Ship To Name</span>
                                            <i class="fas fa-sort ml-1"></i>
                                        </div>
                                    </th>
                                    <th
                                        @click="sortBy('ship_to_address')"
                                        class="px-3 py-2 sm:px-4 sm:py-3 md:px-6 md:py-3 text-[10px] sm:text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors"
                                    >
                                        <div class="flex items-center">
                                            <span>Ship To Address</span>
                                            <i class="fas fa-sort ml-1"></i>
                                        </div>
                                    </th>
                                    <th
                                        @click="sortBy('contact_person')"
                                        class="px-3 py-2 sm:px-4 sm:py-3 md:px-6 md:py-3 text-[10px] sm:text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors"
                                    >
                                        <div class="flex items-center">
                                            <span>Contact Person</span>
                                            <i class="fas fa-sort ml-1"></i>
                                        </div>
                                    </th>
                                    <th
                                        @click="sortBy('tel_no')"
                                        class="px-3 py-2 sm:px-4 sm:py-3 md:px-6 md:py-3 text-[10px] sm:text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors"
                                    >
                                        <div class="flex items-center">
                                            <span>Tel No</span>
                                            <i class="fas fa-sort ml-1"></i>
                                        </div>
                                    </th>
                                    <th
                                        @click="sortBy('fax_no')"
                                        class="px-3 py-2 sm:px-4 sm:py-3 md:px-6 md:py-3 text-[10px] sm:text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors"
                                    >
                                        <div class="flex items-center">
                                            <span>Fax No</span>
                                            <i class="fas fa-sort ml-1"></i>
                                        </div>
                                    </th>
                                    <th
                                        @click="sortBy('email')"
                                        class="px-3 py-2 sm:px-4 sm:py-3 md:px-6 md:py-3 text-[10px] sm:text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors"
                                    >
                                        <div class="flex items-center">
                                            <span>Email</span>
                                            <i class="fas fa-sort ml-1"></i>
                                        </div>
                                    </th>
                                    <th
                                        @click="sortBy('status')"
                                        class="px-3 py-2 sm:px-4 sm:py-3 md:px-6 md:py-3 text-[10px] sm:text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors"
                                    >
                                        <div class="flex items-center">
                                            <span>Status</span>
                                            <i class="fas fa-sort ml-1"></i>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-if="loading">
                                    <td
                                        colspan="15"
                                        class="px-6 py-4 text-center text-sm text-gray-500"
                                    >
                                        <div
                                            class="flex justify-center items-center"
                                        >
                                            <div
                                                class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"
                                            ></div>
                                            <span class="ml-3"
                                                >Loading data...</span
                                            >
                                        </div>
                                    </td>
                                </tr>
                                <tr v-else-if="filteredAddresses.length === 0">
                                    <td
                                        colspan="15"
                                        class="px-3 py-3 sm:px-4 sm:py-4 md:px-6 text-center text-xs sm:text-sm text-gray-500"
                                    >
                                        <div class="flex flex-col items-center">
                                            <i
                                                class="fas fa-map-marker-alt text-4xl text-gray-300 mb-2"
                                            ></i>
                                            <p class="text-lg font-medium">
                                                No customer alternate addresses
                                                found
                                            </p>
                                            <template
                                                v-if="
                                                    searchTerm ||
                                                    recordStatus !== 'both'
                                                "
                                            >
                                                <p class="mt-2 text-sm">
                                                    No results match your search
                                                    criteria
                                                </p>
                                                <button
                                                    @click="
                                                        searchTerm = '';
                                                        recordStatus = 'both';
                                                    "
                                                    class="mt-2 text-blue-500 hover:underline"
                                                >
                                                    Clear filters
                                                </button>
                                            </template>
                                        </div>
                                    </td>
                                </tr>
                                <tr
                                    v-for="(
                                        address, index
                                    ) in filteredAddresses"
                                    :key="address.id"
                                    :class="{ 'bg-blue-50': index % 2 === 0 }"
                                    class="hover:bg-blue-100 transition-colors"
                                >
                                    <td
                                        class="px-3 py-2 sm:px-4 sm:py-3 md:px-6 md:py-4 whitespace-normal md:whitespace-nowrap text-xs sm:text-sm font-medium text-gray-900"
                                    >
                                        {{ address.customer_code }}
                                    </td>
                                    <td
                                        class="px-3 py-2 sm:px-4 sm:py-3 md:px-6 md:py-4 whitespace-normal md:whitespace-nowrap text-xs sm:text-sm text-gray-500"
                                    >
                                        {{ address.delivery_code }}
                                    </td>
                                    <td
                                        class="px-3 py-2 sm:px-4 sm:py-3 md:px-6 md:py-4 whitespace-normal md:whitespace-nowrap text-xs sm:text-sm text-gray-500"
                                    >
                                        {{ address.country }}
                                    </td>
                                    <td
                                        class="px-3 py-2 sm:px-4 sm:py-3 md:px-6 md:py-4 whitespace-normal md:whitespace-nowrap text-xs sm:text-sm text-gray-500"
                                    >
                                        {{ address.state }}
                                    </td>
                                    <td
                                        class="px-3 py-2 sm:px-4 sm:py-3 md:px-6 md:py-4 whitespace-normal md:whitespace-nowrap text-xs sm:text-sm text-gray-500"
                                    >
                                        {{ address.town }}
                                    </td>
                                    <td
                                        class="px-3 py-2 sm:px-4 sm:py-3 md:px-6 md:py-4 whitespace-normal md:whitespace-nowrap text-xs sm:text-sm text-gray-500"
                                    >
                                        {{ address.bill_to_name }}
                                    </td>
                                    <td
                                        class="px-3 py-2 sm:px-4 sm:py-3 md:px-6 md:py-4 whitespace-normal md:whitespace-nowrap text-xs sm:text-sm text-gray-500"
                                    >
                                        {{ address.bill_to_address }}
                                    </td>
                                    <td
                                        class="px-3 py-2 sm:px-4 sm:py-3 md:px-6 md:py-4 whitespace-normal md:whitespace-nowrap text-xs sm:text-sm text-gray-500"
                                    >
                                        {{ address.ship_to_name }}
                                    </td>
                                    <td
                                        class="px-3 py-2 sm:px-4 sm:py-3 md:px-6 md:py-4 whitespace-normal md:whitespace-nowrap text-xs sm:text-sm text-gray-500"
                                    >
                                        {{ address.ship_to_address }}
                                    </td>
                                    <td
                                        class="px-3 py-2 sm:px-4 sm:py-3 md:px-6 md:py-4 whitespace-normal md:whitespace-nowrap text-xs sm:text-sm text-gray-500"
                                    >
                                        {{ address.contact_person }}
                                    </td>
                                    <td
                                        class="px-3 py-2 sm:px-4 sm:py-3 md:px-6 md:py-4 whitespace-normal md:whitespace-nowrap text-xs sm:text-sm text-gray-500"
                                    >
                                        {{ address.tel_no }}
                                    </td>
                                    <td
                                        class="px-3 py-2 sm:px-4 sm:py-3 md:px-6 md:py-4 whitespace-normal md:whitespace-nowrap text-xs sm:text-sm text-gray-500"
                                    >
                                        {{ address.fax_no }}
                                    </td>
                                    <td
                                        class="px-3 py-2 sm:px-4 sm:py-3 md:px-6 md:py-4 whitespace-normal md:whitespace-nowrap text-xs sm:text-sm text-gray-500"
                                    >
                                        {{ address.email }}
                                    </td>
                                    <td
                                        class="px-3 py-2 sm:px-4 sm:py-3 md:px-6 md:py-4 whitespace-normal md:whitespace-nowrap text-xs sm:text-sm"
                                    >
                                        <span
                                            :class="{
                                                'px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full': true,
                                                'bg-green-100 text-green-800':
                                                    address.status === 'Active',
                                                'bg-red-100 text-red-800':
                                                    address.status ===
                                                    'Obsolete',
                                            }"
                                        >
                                            {{ address.status }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Table Footer -->
                    <div
                        class="bg-gradient-to-r from-gray-50 to-blue-50 px-6 py-3 border-t border-gray-200 text-sm text-slate-700"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i
                                    class="fas fa-info-circle mr-2 text-blue-500"
                                ></i>
                                <span class="font-medium"
                                    >Showing {{ filteredAddresses.length }} of
                                    {{ addresses.length }} entries</span
                                >
                            </div>
                            <div class="text-xs text-slate-600">
                                Generated: {{ new Date().toLocaleString() }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Instructions Card -->
                <div
                    class="bg-gradient-to-r from-teal-50 to-cyan-50 border border-teal-200 rounded-xl p-6 mb-6 shadow-md"
                >
                    <div class="flex items-start">
                        <div class="text-teal-600 mr-4 mt-1">
                            <div
                                class="w-10 h-10 bg-gradient-to-r from-teal-500 to-cyan-600 rounded-full flex items-center justify-center shadow-sm"
                            >
                                <i
                                    class="fas fa-info-circle text-white text-lg"
                                ></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4
                                class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-3"
                            >
                                Instructions
                            </h4>
                            <ul
                                class="list-disc pl-5 text-sm text-teal-900 space-y-2"
                            >
                                <li>
                                    Use the search box to filter by customer
                                    name, code, or address
                                </li>
                                <li>
                                    Click the column headers to sort the data
                                </li>
                                <li>
                                    Use the status filter to show active or
                                    obsolete records
                                </li>
                                <li>
                                    Click the print button to print the current
                                    view
                                </li>
                                <li>
                                    Use the export button to download data in
                                    Excel format
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { Head } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import axios from "axios";
import jsPDF from "jspdf";
import autoTable from "jspdf-autotable";

// Data
const addresses = ref([]);
const loading = ref(true);
const searchTerm = ref("");
const recordStatus = ref("both");
const sortColumn = ref("customer_code");
const sortDirection = ref("asc");

// Fetch data
const fetchData = async () => {
    loading.value = true;
    try {
        const response = await axios.get("/api/customer-alternate-addresses");
        addresses.value = response.data;
    } catch (error) {
        console.error("Error fetching customer alternate addresses:", error);
    } finally {
        loading.value = false;
    }
};

// Computed properties
const filteredAddresses = computed(() => {
    let filtered = addresses.value;

    // Filter by search term (defensively handle null/undefined fields)
    if (searchTerm.value) {
        const term = searchTerm.value.toLowerCase();
        filtered = filtered.filter((address) => {
            const code = (address.customer_code ?? "").toString().toLowerCase();
            const delivery = (address.delivery_code ?? "")
                .toString()
                .toLowerCase();
            const country = (address.country ?? "").toString().toLowerCase();
            const state = (address.state ?? "").toString().toLowerCase();
            const town = (address.town ?? "").toString().toLowerCase();
            const billName = (address.bill_to_name ?? "")
                .toString()
                .toLowerCase();
            const billAddr = (address.bill_to_address ?? "")
                .toString()
                .toLowerCase();
            const shipName = (address.ship_to_name ?? "")
                .toString()
                .toLowerCase();
            const shipAddr = (address.ship_to_address ?? "")
                .toString()
                .toLowerCase();
            const contact = (address.contact_person ?? "")
                .toString()
                .toLowerCase();
            const tel = (address.tel_no ?? "").toString().toLowerCase();
            const fax = (address.fax_no ?? "").toString().toLowerCase();
            const email = (address.email ?? "").toString().toLowerCase();

            return (
                code.includes(term) ||
                delivery.includes(term) ||
                country.includes(term) ||
                state.includes(term) ||
                town.includes(term) ||
                billName.includes(term) ||
                billAddr.includes(term) ||
                shipName.includes(term) ||
                shipAddr.includes(term) ||
                contact.includes(term) ||
                tel.includes(term) ||
                fax.includes(term) ||
                email.includes(term)
            );
        });
    }

    // Filter by status
    if (recordStatus.value !== "both") {
        filtered = filtered.filter(
            (address) => address.status === recordStatus.value
        );
    }

    // Sort (normalize null and mixed types before comparison)
    filtered.sort((a, b) => {
        let valA = a[sortColumn.value];
        let valB = b[sortColumn.value];

        // Normalize null/undefined
        if (valA == null) valA = "";
        if (valB == null) valB = "";

        // Convert to primitive for comparison
        if (typeof valA !== "number" && typeof valA !== "string") {
            valA = String(valA);
        }
        if (typeof valB !== "number" && typeof valB !== "string") {
            valB = String(valB);
        }

        // Case-insensitive string comparison
        if (typeof valA === "string") {
            valA = valA.toLowerCase();
        }
        if (typeof valB === "string") {
            valB = valB.toLowerCase();
        }

        if (valA < valB) return sortDirection.value === "asc" ? -1 : 1;
        if (valA > valB) return sortDirection.value === "asc" ? 1 : -1;
        return 0;
    });

    return filtered;
});

// Methods
const sortBy = (column) => {
    if (sortColumn.value === column) {
        sortDirection.value = sortDirection.value === "asc" ? "desc" : "asc";
    } else {
        sortColumn.value = column;
        sortDirection.value = "asc";
    }
};

const printPdf = () => {
    const doc = new jsPDF("l", "mm", "a4");
    const marginX = 10;
    const marginY = 15;

    doc.setFontSize(14);
    doc.text("Customer Alternate Addresses", 148.5, marginY, {
        align: "center",
    });

    const rows = filteredAddresses.value.map((address) => [
        address.customer_code || "",
        address.delivery_code || "",
        address.country || "",
        address.state || "",
        address.town || "",
        address.bill_to_name || "",
        address.bill_to_address || "",
        address.ship_to_name || "",
        address.ship_to_address || "",
        address.contact_person || "",
        address.tel_no || "",
        address.fax_no || "",
        address.email || "",
        address.status || "",
    ]);

    autoTable(doc, {
        startY: marginY + 8,
        head: [
            [
                "Customer Code",
                "Delivery Code",
                "Country",
                "State",
                "Town",
                "Bill To Name",
                "Bill To Address",
                "Ship To Name",
                "Ship To Address",
                "Contact Person",
                "Tel No",
                "Fax No",
                "Email",
                "Status",
            ],
        ],
        body: rows,
        styles: { fontSize: 8 },
        headStyles: {
            fillColor: [37, 99, 235],
            textColor: 255,
            halign: "left",
        },
        alternateRowStyles: { fillColor: [240, 249, 255] },
        margin: { left: marginX, right: marginX },
    });

    doc.save("customer-alternate-addresses.pdf");
};

// Lifecycle hooks
onMounted(() => {
    fetchData();
});
</script>

<style scoped>
.text-shadow {
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

@keyframes pulse-slow {
    0%,
    100% {
        transform: scale(1);
        opacity: 0.05;
    }
    50% {
        transform: scale(1.1);
        opacity: 0.08;
    }
}

.animate-pulse-slow {
    animation: pulse-slow 5s infinite;
}

.animation-delay-500 {
    animation-delay: 0.5s;
}

@keyframes ping-slow {
    75%,
    100% {
        transform: scale(2);
        opacity: 0;
    }
}

.animate-ping-slow {
    animation: ping-slow 3s cubic-bezier(0, 0, 0.2, 1) infinite;
}

@media print {
    .no-print {
        display: none !important;
    }

    body {
        font-size: 12pt;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }
}
</style>
