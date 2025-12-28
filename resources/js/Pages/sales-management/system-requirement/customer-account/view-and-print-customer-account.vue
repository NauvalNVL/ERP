<template>
  <AppLayout :header="'View & Print Customer Accounts'">
    <Head title="View & Print Customer Accounts" />

    <!-- Main Container with vibrant gradient background -->
    <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
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
              <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex items-start md:items-center space-x-4 mb-4 md:mb-0">
                  <div
                    class="bg-gradient-to-br from-cyan-500 to-teal-600 p-3 rounded-lg shadow-inner flex items-center justify-center relative overflow-hidden"
                  >
                    <div
                      class="absolute -top-1 -right-1 w-6 h-6 bg-blue-300 opacity-30 rounded-full animate-ping-slow"
                    ></div>
                    <div
                      class="absolute -bottom-1 -left-1 w-4 h-4 bg-teal-300 opacity-30 rounded-full animate-ping-slow animation-delay-500"
                    ></div>
                    <i class="fas fa-print text-white text-2xl z-10"></i>
                  </div>
                  <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white text-shadow">
                      View & Print Customer Accounts
                    </h1>
                    <p class="text-teal-100 max-w-2xl">
                      Preview and print customer account data
                    </p>
                  </div>
                </div>
                <div class="flex flex-wrap gap-3">
                  <button
                    @click="exportPDF"
                    class="bg-gradient-to-r from-green-500 to-teal-400 text-white hover:from-green-600 hover:to-teal-500 px-4 py-2 rounded-lg flex items-center transition-all duration-300 transform hover:scale-105 shadow-md relative overflow-hidden group"
                  >
                    <span
                      class="absolute inset-0 bg-white opacity-20 transform scale-x-0 origin-left transition-transform group-hover:scale-x-100"
                    ></span>
                    <div class="relative z-10 flex items-center">
                      <div class="bg-white bg-opacity-30 rounded-full p-1 mr-2">
                        <i class="fas fa-file-pdf text-white"></i>
                      </div>
                      <span>Print PDF</span>
                    </div>
                  </button>
                  <Link
                    href="/update-customer-account"
                    class="bg-white bg-opacity-20 text-white border border-white border-opacity-30 hover:bg-opacity-30 px-4 py-2 rounded-lg flex items-center transition-all duration-300 relative overflow-hidden"
                  >
                    <span
                      class="absolute inset-0 bg-white opacity-10 transform scale-x-0 origin-left transition-transform hover:scale-x-100"
                    ></span>
                    <div class="relative z-10 flex items-center">
                      <div
                        class="bg-white bg-opacity-30 rounded-full p-1.5 mr-2 transition-transform transform group-hover:rotate-12 shadow-inner"
                      >
                        <i class="fas fa-arrow-left text-white"></i>
                      </div>
                      <span>Back</span>
                    </div>
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div
          class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-4"
        >
          <div class="bg-blue-50 border-b border-gray-100 text-slate-800 py-3 px-6">
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
                <i class="fas fa-filter text-xl relative z-10"></i>
              </div>
              <div>
                <h2 class="text-xl font-bold">Search Parameters</h2>
                <p class="text-xs text-cyan-100 opacity-80">
                  Filter customer accounts by various criteria
                </p>
              </div>
            </div>
          </div>

          <div class="p-6">
            <!-- Actions Bar -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-end">
              <!-- Search Field -->
              <div class="space-y-2">
                <label class="text-sm font-medium text-blue-700 mb-2 flex items-center">
                  <div
                    class="w-8 h-8 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-full mr-2 flex items-center justify-center shadow-sm"
                  >
                    <i class="fas fa-search text-white text-sm"></i>
                  </div>
                  Search:
                </label>
                <div class="relative flex-grow">
                  <input
                    type="text"
                    v-model="searchQuery"
                    class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                    placeholder="Enter customer code, name, or contact..."
                  />
                  <div
                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                  >
                    <i class="fas fa-search text-gray-400"></i>
                  </div>
                </div>
              </div>

              <!-- Status Filter -->
              <div class="space-y-2">
                <label class="text-sm font-medium text-blue-700 mb-2 flex items-center">
                  <div
                    class="w-8 h-8 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-full mr-2 flex items-center justify-center shadow-sm"
                  >
                    <i class="fas fa-tag text-white text-sm"></i>
                  </div>
                  Status:
                </label>
                <select
                  v-model="statusFilter"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                >
                  <option value="all">All Status</option>
                  <option value="active">Active</option>
                  <option value="inactive">Inactive</option>
                </select>
              </div>

              <!-- Action Buttons -->
              <div class="flex flex-wrap items-end justify-end gap-3">
                <button
                  type="button"
                  @click="
                    searchQuery = '';
                    statusFilter = 'all';
                    fetchCustomerAccounts('all');
                  "
                  class="px-6 py-2.5 border border-gray-300 rounded-lg bg-white hover:bg-gray-50 text-gray-700 transition-all duration-300 shadow-sm flex items-center"
                >
                  <i class="fas fa-undo mr-2 text-gray-500"></i>
                  Reset Filters
                </button>
                <button
                  type="button"
                  @click="fetchCustomerAccounts(statusFilter.value)"
                  class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white rounded-lg transition-all duration-300 shadow-md flex items-center"
                >
                  <i class="fas fa-sync mr-2"></i>
                  Refresh Data
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <div class="bg-blue-50 border-b border-gray-100 text-slate-800 py-3 px-6">
            <div class="flex items-center">
              <div class="mr-4 bg-white bg-opacity-20 p-2 rounded-full shadow-inner">
                <i class="fas fa-users text-2xl"></i>
              </div>
              <div>
                <h2 class="text-xl font-bold">Customer Accounts List</h2>
                <p class="text-sm opacity-80">View and manage customer account data</p>
              </div>
            </div>
          </div>

          <div class="overflow-x-auto">
            <div id="printableTable" class="min-w-full bg-white">
              <!-- Table Content -->
              <table class="min-w-full border-collapse">
                <thead class="bg-gradient-to-r from-cyan-50 to-blue-50">
                  <tr>
                    <th
                      @click="sortTable('customer_code')"
                      class="px-4 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors border border-gray-200"
                    >
                      <div class="flex items-center">
                        <span>Code</span>
                        <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th
                      @click="sortTable('customer_name')"
                      class="px-4 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors border border-gray-200"
                    >
                      <div class="flex items-center">
                        <span>Name</span>
                        <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th
                      @click="sortTable('short_name')"
                      class="px-4 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors border border-gray-200"
                    >
                      <div class="flex items-center">
                        <span>Short Name</span>
                        <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th
                      @click="sortTable('address')"
                      class="px-4 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors border border-gray-200"
                    >
                      <div class="flex items-center">
                        <span>Address</span>
                        <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th
                      @click="sortTable('contact_person')"
                      class="px-4 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors border border-gray-200"
                    >
                      <div class="flex items-center">
                        <span>Contact</span>
                        <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th
                      @click="sortTable('telephone_no')"
                      class="px-4 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors border border-gray-200"
                    >
                      <div class="flex items-center">
                        <span>Phone</span>
                        <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th
                      @click="sortTable('fax_no')"
                      class="px-4 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors border border-gray-200"
                    >
                      <div class="flex items-center">
                        <span>Fax</span>
                        <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th
                      @click="sortTable('co_email')"
                      class="px-4 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors border border-gray-200"
                    >
                      <div class="flex items-center">
                        <span>Email</span>
                        <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th
                      @click="sortTable('credit_limit')"
                      class="px-4 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors border border-gray-200"
                    >
                      <div class="flex items-center">
                        <span>Credit Limit</span>
                        <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th
                      @click="sortTable('credit_terms')"
                      class="px-4 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors border border-gray-200"
                    >
                      <div class="flex items-center">
                        <span>Terms (Days)</span>
                        <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th
                      @click="sortTable('ac_type')"
                      class="px-4 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors border border-gray-200"
                    >
                      <div class="flex items-center">
                        <span>Account Type</span>
                        <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th
                      @click="sortTable('currency_code')"
                      class="px-4 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors border border-gray-200"
                    >
                      <div class="flex items-center">
                        <span>Currency</span>
                        <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th
                      @click="sortTable('npwp')"
                      class="px-4 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors border border-gray-200"
                    >
                      <div class="flex items-center">
                        <span>NPWP</span>
                        <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th
                      @click="sortTable('sales_type')"
                      class="px-4 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors border border-gray-200"
                    >
                      <div class="flex items-center">
                        <span>Customer Type</span>
                        <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th
                      @click="sortTable('salesperson_code')"
                      class="px-4 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors border border-gray-200"
                    >
                      <div class="flex items-center">
                        <span>Salesperson</span>
                        <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th
                      @click="sortTable('industrial_code')"
                      class="px-4 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors border border-gray-200"
                    >
                      <div class="flex items-center">
                        <span>Industry</span>
                        <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th
                      @click="sortTable('geographical')"
                      class="px-4 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors border border-gray-200"
                    >
                      <div class="flex items-center">
                        <span>Geographical</span>
                        <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th
                      @click="sortTable('grouping_code')"
                      class="px-4 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors border border-gray-200"
                    >
                      <div class="flex items-center">
                        <span>Group</span>
                        <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th
                      @click="sortTable('status')"
                      class="px-4 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors border border-gray-200"
                    >
                      <div class="flex items-center">
                        <span>Status</span>
                        <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white">
                  <tr v-if="loading" class="hover:bg-gray-50">
                    <td colspan="19" class="px-6 py-4 text-center text-gray-500 border border-gray-200">
                      <div class="flex justify-center">
                        <div
                          class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"
                        ></div>
                      </div>
                      <p class="mt-2">Loading customer account data...</p>
                    </td>
                  </tr>
                  <tr
                    v-else-if="filteredCustomerAccounts.length === 0"
                    class="hover:bg-gray-50"
                  >
                    <td colspan="19" class="px-6 py-4 text-center text-gray-500 border border-gray-200">
                      <div class="flex flex-col items-center">
                        <i class="fas fa-users text-4xl text-gray-300 mb-2"></i>
                        <p class="text-lg font-medium">No customer accounts found</p>
                        <template v-if="searchQuery || statusFilter !== 'all'">
                          <p class="mt-2 text-sm">
                            No results match your search criteria
                          </p>
                          <button
                            @click="
                              searchQuery = '';
                              statusFilter = 'all';
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
                    v-for="(account, index) in filteredCustomerAccounts"
                    :key="account.customer_code"
                    :class="{
                      'bg-blue-50': index % 2 === 0,
                    }"
                    class="hover:bg-blue-100 transition-colors"
                  >
                    <td class="px-4 py-3 whitespace-nowrap border border-gray-200">
                      <div class="text-sm font-medium text-gray-900">
                        {{ account.customer_code }}
                      </div>
                    </td>
                    <td class="px-4 py-3 border border-gray-200">
                      <div class="text-sm text-gray-900">
                        {{ account.customer_name }}
                      </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap border border-gray-200">
                      <div class="text-sm text-gray-900">
                        {{ account.short_name || "-" }}
                      </div>
                    </td>
                    <td class="px-4 py-3 border border-gray-200">
                      <div class="text-sm text-gray-900">
                        {{ formatAddress(account) }}
                      </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap border border-gray-200">
                      <div class="text-sm text-gray-900">
                        {{ account.contact_person || "-" }}
                      </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap border border-gray-200">
                      <div class="text-sm text-gray-900">
                        {{ account.telephone_no || "-" }}
                      </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap border border-gray-200">
                      <div class="text-sm text-gray-900">
                        {{ account.fax_no || "-" }}
                      </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap border border-gray-200">
                      <div class="text-sm text-gray-900">
                        {{ account.co_email || "-" }}
                      </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-right border border-gray-200">
                      <div class="text-sm text-gray-900">
                        {{ formatCurrency(account.credit_limit) }}
                      </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-center border border-gray-200">
                      <div class="text-sm text-gray-900">
                        {{ account.credit_terms || 0 }}
                      </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap border border-gray-200">
                      <div class="text-sm text-gray-900">
                        {{ account.ac_type || account.account_type || "-" }}
                      </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap border border-gray-200">
                      <div class="text-sm text-gray-900">
                        {{ account.currency_code || "IDR" }}
                      </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap border border-gray-200">
                      <div class="text-sm text-gray-900">
                        {{ account.npwp || "-" }}
                      </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap border border-gray-200">
                      <div class="text-sm text-gray-900">
                        {{ account.sales_type || "-" }}
                      </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap border border-gray-200">
                      <div class="text-sm text-gray-900">
                        {{ account.salesperson_code || "-" }}
                      </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap border border-gray-200">
                      <div class="text-sm text-gray-900">
                        {{ account.industrial_code || "-" }}
                      </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap border border-gray-200">
                      <div class="text-sm text-gray-900">
                        {{ account.geographical || "-" }}
                      </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap border border-gray-200">
                      <div class="text-sm text-gray-900">
                        {{ account.grouping_code || "-" }}
                      </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap border border-gray-200">
                      <span
                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium"
                        :class="{
                          'bg-green-100 text-green-800':
                            (account.statusCategory || 'active') === 'active',
                          'bg-red-100 text-red-800':
                            account.statusCategory === 'inactive',
                          'bg-gray-100 text-gray-800':
                            account.statusCategory === 'other' || !account.statusCategory,
                        }"
                      >
                        {{ account.statusLabel || "Active" }}
                      </span>
                    </td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <td
                      colspan="19"
                      class="bg-gradient-to-r from-gray-50 to-blue-50 px-6 py-3 border-t border-gray-200 text-sm text-blue-500"
                    >
                      <div class="flex items-center justify-between">
                        <div class="flex items-center">
                          <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                          <span
                            >Total Customer Accounts:
                            <span class="font-semibold text-blue-700">{{
                              filteredCustomerAccounts.length
                            }}</span></span
                          >
                        </div>
                        <div
                          v-if="searchQuery || statusFilter !== 'all'"
                          class="text-blue-600"
                        >
                          Filtered from
                          {{ customerAccounts.length }} total records
                        </div>
                        <div class="text-xs text-white">Generated: {{ currentDate }}</div>
                      </div>
                    </td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>

        <!-- Print Instructions -->
        <div class="mt-6 bg-blue-50 p-4 rounded-lg border border-blue-100">
          <h3 class="font-semibold text-blue-800 mb-2 flex items-center">
            <i class="fas fa-info-circle mr-2"></i> PDF Export Instructions
          </h3>
          <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
            <li>Click the "Print PDF" button above to generate and download PDF</li>
            <li>PDF will be automatically saved in landscape orientation</li>
            <li>You can search, filter by status, or sort data before exporting</li>
            <li>PDF includes formatted table with headers and page numbers</li>
          </ul>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import jsPDF from "jspdf";
import autoTable from "jspdf-autotable";

// Data
const customerAccounts = ref([]);
const loading = ref(true);
const searchQuery = ref("");
const statusFilter = ref("all"); // 'all', 'Active', 'Inactive'
const sortColumn = ref("customer_code");
const sortDirection = ref("asc");
const currentDate = new Date().toLocaleString();

const normalizeStatusMeta = (status) => {
  const raw = (status ?? "").toString().trim();
  const lower = raw.toLowerCase();

  if (!raw || ["a", "active", "y"].includes(lower)) {
    return { label: "Active", category: "active" };
  }

  if (["i", "inactive", "obsolete", "n"].includes(lower)) {
    // Keep original label (e.g., Obsolete) but still categorize as inactive
    const label =
      lower === "obsolete" ? "Obsolete" : lower === "inactive" ? "Inactive" : "Inactive";
    return { label, category: "inactive" };
  }

  return { label: raw || "Active", category: "other" };
};

// Helper to derive Short Name from Customer Name (max 12 characters)
const getShortName = (name) => {
  if (!name) return "";
  const str = String(name).trim();
  return str.length <= 12 ? str : str.slice(0, 12);
};

// Fetch customer accounts from API
const fetchCustomerAccounts = async (statusParam = "all") => {
  loading.value = true;
  try {
    const params = new URLSearchParams({
      status: (statusParam || "all").toString().toLowerCase(),
    });

    const response = await fetch(`/api/customer-accounts?${params.toString()}`, {
      headers: {
        Accept: "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
    });

    if (!response.ok) {
      throw new Error("Failed to fetch customer accounts");
    }

    const data = await response.json();
    console.log("Received customer accounts data:", data);

    // Handle the data structure - it might be wrapped in a 'data' property
    const accountsData = data.data || data;

    if (Array.isArray(accountsData)) {
      customerAccounts.value = accountsData.map((account) => {
        const customerName = account.customer_name || "";
        const statusMeta = normalizeStatusMeta(account.status);
        return {
          ...account,
          // Short Name always derived from customer_name, max 12 chars
          short_name: getShortName(customerName),
          statusRaw: account.status ?? "",
          status: statusMeta.label,
          statusLabel: statusMeta.label,
          statusCategory: statusMeta.category,
        };
      });
    } else {
      console.error("Expected array but received:", typeof accountsData, accountsData);
      customerAccounts.value = [];
    }
  } catch (error) {
    console.error("Error fetching customer accounts:", error);
  } finally {
    loading.value = false;
  }
};

// Format date
const formatDate = (dateString) => {
  if (!dateString) return "-";
  const date = new Date(dateString);
  return date.toLocaleString();
};

// Format address
const formatAddress = (account) => {
  const parts = [];
  if (account.address) parts.push(account.address);
  if (account.address2) parts.push(account.address2);
  if (account.address3) parts.push(account.address3);
  return parts.length > 0 ? parts.join(", ") : "-";
};

// Format currency
const formatCurrency = (value) => {
  if (!value || value === 0) return "0";
  return new Intl.NumberFormat("id-ID", {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }).format(value);
};

// Sort function
const sortTable = (column) => {
  if (sortColumn.value === column) {
    sortDirection.value = sortDirection.value === "asc" ? "desc" : "asc";
  } else {
    sortColumn.value = column;
    sortDirection.value = "asc";
  }
};

// Filtered and sorted customer accounts
const filteredCustomerAccounts = computed(() => {
  let filtered = [...customerAccounts.value];

  // Apply status filter
  if (statusFilter.value !== "all") {
    filtered = filtered.filter(
      (account) => (account.statusCategory || "active") === statusFilter.value
    );
  }

  // Apply search filter
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    filtered = filtered.filter(
      (account) =>
        (account.customer_code && account.customer_code.toLowerCase().includes(query)) ||
        (account.customer_name && account.customer_name.toLowerCase().includes(query)) ||
        (account.contact_person &&
          account.contact_person.toLowerCase().includes(query)) ||
        (account.address && account.address.toLowerCase().includes(query))
    );
  }

  // Apply sorting
  filtered.sort((a, b) => {
    let valueA = a[sortColumn.value];
    let valueB = b[sortColumn.value];

    // Handle null values
    if (valueA === null || valueA === undefined) valueA = "";
    if (valueB === null || valueB === undefined) valueB = "";

    // Handle date columns
    if (["created_at", "updated_at"].includes(sortColumn.value)) {
      valueA = valueA ? new Date(valueA).getTime() : 0;
      valueB = valueB ? new Date(valueB).getTime() : 0;

      if (sortDirection.value === "asc") {
        return valueA - valueB;
      } else {
        return valueB - valueA;
      }
    }

    // Convert to string for comparison if not already
    if (typeof valueA !== "string") valueA = valueA.toString();
    if (typeof valueB !== "string") valueB = valueB.toString();

    // Case insensitive comparison
    valueA = valueA.toLowerCase();
    valueB = valueB.toLowerCase();

    // Sort direction
    if (sortDirection.value === "asc") {
      return valueA.localeCompare(valueB);
    } else {
      return valueB.localeCompare(valueA);
    }
  });

  return filtered;
});

// Export to PDF function using jsPDF
const exportPDF = () => {
  try {
    const doc = new jsPDF({
      orientation: "landscape",
      unit: "mm",
      format: "a4",
    });

    // Add title
    doc.setFontSize(16);
    doc.setTextColor(37, 99, 235); // Blue color
    doc.text("CUSTOMER ACCOUNT LIST", 10, 15);

    // Add subtitle
    doc.setFontSize(10);
    doc.setTextColor(100);
    doc.text("View and print customer account data", 10, 22);

    // Prepare table data
    const tableData = filteredCustomerAccounts.value.map((account) => [
      account.customer_code || "N/A",
      account.customer_name || "N/A",
      account.short_name || "-",
      formatAddress(account),
      account.contact_person || "-",
      account.telephone_no || "-",
      account.fax_no || "-",
      account.co_email || "-",
      formatCurrency(account.credit_limit),
      account.credit_terms || "0",
      account.ac_type || account.account_type || "-",
      account.currency_code || "IDR",
      account.npwp || "-",
      account.sales_type || "-",
      account.salesperson_code || "-",
      account.industrial_code || "-",
      account.geographical || "-",
      account.grouping_code || "-",
      account.status === "A"
        ? "Active"
        : account.status === "I"
        ? "Inactive"
        : account.status || "Active",
    ]);

    // Add table using autoTable
    autoTable(doc, {
      startY: 28,
      head: [
        [
          "Code",
          "Customer Name",
          "Short Name",
          "Address",
          "Contact",
          "Phone",
          "Fax",
          "Email",
          "Credit Limit",
          "Terms",
          "AC Type",
          "Currency",
          "NPWP",
          "Cust Type",
          "Salesperson",
          "Industry",
          "Geo",
          "Group",
          "Status",
        ],
      ],
      body: tableData,
      theme: "grid",
      tableWidth: "auto",
      headStyles: {
        fillColor: [37, 99, 235], // Blue background
        textColor: [255, 255, 255], // White text
        fontStyle: "bold",
        halign: "left",
        fontSize: 7,
      },
      bodyStyles: {
        textColor: [50, 50, 50],
        halign: "left",
        fontSize: 6,
      },
      alternateRowStyles: {
        fillColor: [219, 234, 254], // Light blue for alternate rows
      },
      columnStyles: {
        0: { fontStyle: "bold", cellWidth: 15 },
        1: { cellWidth: 25 },
        2: { cellWidth: 15 },
        3: { cellWidth: 30 },
        4: { cellWidth: 15 },
        5: { cellWidth: 15 },
        6: { cellWidth: 12 },
        7: { cellWidth: 20 },
        8: { cellWidth: 15, halign: "right" },
        9: { cellWidth: 10, halign: "center" },
        10: { cellWidth: 12 },
        11: { cellWidth: 12 },
        12: { cellWidth: 15 },
        13: { cellWidth: 12 },
        14: { cellWidth: 12 },
        15: { cellWidth: 12 },
        16: { cellWidth: 12 },
        17: { cellWidth: 12 },
        18: { cellWidth: "auto" },
      },
      margin: { top: 28, left: 5, right: 5 },
    });

    // Add footer
    const pageCount = doc.internal.getNumberOfPages();
    const pageHeight = doc.internal.pageSize.height;

    for (let i = 1; i <= pageCount; i++) {
      doc.setPage(i);
      doc.setFontSize(8);
      doc.setTextColor(100);
      doc.text(
        `Total Customer Accounts: ${filteredCustomerAccounts.value.length} | Generated: ${currentDate}`,
        10,
        pageHeight - 10
      );
      doc.text(
        `Page ${i} of ${pageCount}`,
        doc.internal.pageSize.width - 35,
        pageHeight - 10
      );
    }

    // Save PDF
    doc.save(`customer-accounts-${new Date().getTime()}.pdf`);
  } catch (error) {
    console.error("Error generating PDF:", error);
    alert("Error generating PDF. Please try again.");
  }
};

// Lifecycle hooks
onMounted(() => {
  fetchCustomerAccounts(statusFilter.value);
});

watch(
  statusFilter,
  (newStatus) => {
    fetchCustomerAccounts(newStatus);
  },
  { immediate: false }
);
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
  body * {
    visibility: hidden;
  }
  #printableTable,
  #printableTable * {
    visibility: visible;
  }
  #printableTable {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
  }
}
</style>
