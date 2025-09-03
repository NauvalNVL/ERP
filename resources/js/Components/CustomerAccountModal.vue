<template>
    <div
        v-if="show"
        class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center"
    >
        <div
            class="bg-white rounded-lg shadow-xl w-11/12 md:w-2/3 lg:w-3/4 max-w-4xl mx-auto"
        >
            <!-- Modal Header -->
            <div
                class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg"
            >
                <h3 class="text-xl font-semibold flex items-center">
                    <i class="fas fa-list mr-3"></i>Customer Account Table
                </h3>
                <div class="flex space-x-3 items-center">
                    <div class="text-white text-sm mr-2">
                        <span class="mr-2">Zoom:</span>
                        <select
                            @change="handleZoomChange"
                            class="bg-blue-700 text-white border border-blue-500 rounded px-1 py-0.5 text-xs"
                        >
                            <option value="">Select Zoom</option>
                            <option value="customer_details">
                                Customer Details
                            </option>
                            <option value="customer_address">
                                Customer Address
                            </option>
                            <option value="customer_contacts">
                                Customer Contacts
                            </option>
                        </select>
                    </div>
                    <div class="text-white text-sm mr-2">
                        <span class="mr-2">Sort:</span>
                        <select
                            v-model="sortBy"
                            class="bg-blue-700 text-white border border-blue-500 rounded px-1 py-0.5 text-xs"
                        >
                            <option value="customer_code">Customer Code</option>
                            <option value="customer_name">Customer Name</option>
                        </select>
                    </div>
                    <div class="text-white text-sm">
                        <span class="mr-2">Status:</span>
                        <select
                            v-model="statusFilter"
                            class="bg-blue-700 text-white border border-blue-500 rounded px-1 py-0.5 text-xs"
                        >
                            <option value="active" selected>Active</option>
                            <option value="obsolete">Obsolete</option>
                            <option value="all">All</option>
                        </select>
                    </div>
                    <button
                        type="button"
                        @click="$emit('close')"
                        class="text-white hover:text-gray-200 focus:outline-none"
                    >
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="px-4 py-2 bg-gray-50 border-b border-gray-200">
                <div class="relative">
                    <div
                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                    >
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input
                        type="text"
                        v-model="searchQuery"
                        placeholder="Search by customer code or name..."
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm"
                        @keyup.escape="searchQuery = ''"
                    />
                    <div
                        v-if="searchQuery"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center"
                    >
                        <button
                            @click="searchQuery = ''"
                            class="text-gray-400 hover:text-gray-600"
                            title="Clear search"
                        >
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-2 overflow-y-auto" style="max-height: 55vh">
                <div
                    v-if="loading"
                    class="flex justify-center items-center p-4"
                >
                    <div
                        class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"
                    ></div>
                    <span class="ml-2 text-gray-600">Loading data...</span>
                </div>
                <div
                    v-else-if="error"
                    class="p-4 text-red-500 bg-red-50 rounded border border-red-200"
                >
                    <div class="font-bold mb-1">Error:</div>
                    <div>{{ error }}</div>
                    <button
                        @click="fetchCustomerAccounts"
                        class="mt-2 px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600"
                    >
                        Try Again
                    </button>
                </div>
                <div
                    v-else-if="filteredAccounts.length === 0"
                    class="p-4 text-amber-700 bg-amber-50 rounded border border-amber-200"
                >
                    <div v-if="searchQuery">
                        No customers found matching "<strong>{{
                            searchQuery
                        }}</strong
                        >".
                        <button
                            @click="searchQuery = ''"
                            class="text-blue-600 underline ml-1"
                        >
                            Clear search
                        </button>
                    </div>
                    <div v-else>
                        No customer accounts found. Please adjust your filter
                        criteria or add new accounts.
                    </div>
                </div>
                <table v-else class="min-w-full text-xs border border-gray-300">
                    <thead class="bg-gray-200 sticky top-0">
                        <tr>
                            <th
                                class="px-2 py-1 border border-gray-300 text-left"
                            >
                                Customer Code
                            </th>
                            <th
                                class="px-2 py-1 border border-gray-300 text-left"
                            >
                                Customer Name
                            </th>
                            <th
                                class="px-2 py-1 border border-gray-300 text-left"
                            >
                                S/person
                            </th>
                            <th
                                class="px-2 py-1 border border-gray-300 text-left"
                            >
                                AC Type
                            </th>
                            <th
                                class="px-2 py-1 border border-gray-300 text-left"
                            >
                                Currency
                            </th>
                            <th
                                class="px-2 py-1 border border-gray-300 text-left"
                            >
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr
                            v-for="account in filteredAccounts"
                            :key="account.customer_code"
                            class="hover:bg-blue-50 cursor-pointer transition-colors"
                            :class="{
                                'bg-blue-200 border-2 border-blue-500 shadow-sm':
                                    selectedAccount?.customer_code ===
                                    account.customer_code,
                                'hover:bg-blue-100':
                                    selectedAccount?.customer_code !==
                                    account.customer_code,
                            }"
                            @click="selectAccount(account)"
                            @dblclick="handleSelect"
                            title="Click to select, double-click to confirm"
                        >
                            <td class="px-2 py-1 border border-gray-300">
                                {{ account.customer_code }}
                            </td>
                            <td class="px-2 py-1 border border-gray-300">
                                {{ account.customer_name }}
                            </td>
                            <td class="px-2 py-1 border border-gray-300">
                                {{ account.salesperson_code }}
                            </td>
                            <td class="px-2 py-1 border border-gray-300">
                                {{ account.account_type || account.ac_type }}
                            </td>
                            <td class="px-2 py-1 border border-gray-300">
                                {{ account.currency_code }}
                            </td>
                            <td class="px-2 py-1 border border-gray-300">
                                <span
                                    :class="
                                        account.status === 'Active' ||
                                        !account.status
                                            ? 'bg-green-100 text-green-800'
                                            : 'bg-red-100 text-red-800'
                                    "
                                    class="px-2 py-0.5 rounded-full text-xs"
                                >
                                    {{ account.status || "Active" }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Modal Footer -->
            <div
                class="flex items-center justify-between gap-2 p-2 border-t border-gray-200 bg-gray-100 rounded-b-lg"
            >
                <div class="text-xs text-gray-500 flex-1">
                    <div v-if="filteredAccounts.length > 0">
                        {{ filteredAccounts.length }} accounts found
                    </div>
                    <div
                        v-if="selectedAccount"
                        class="text-blue-600 font-medium mt-1"
                    >
                        Selected: {{ selectedAccount.customer_code }} -
                        {{ selectedAccount.customer_name }}
                    </div>
                </div>
                <div class="flex gap-2">
                    <button
                        @click="handleSelect"
                        :disabled="!selectedAccount || loading"
                        :class="{
                            'bg-blue-500 hover:bg-blue-700':
                                selectedAccount && !loading,
                            'bg-gray-400 cursor-not-allowed':
                                !selectedAccount || loading,
                        }"
                        class="text-white font-bold py-1 px-3 rounded text-xs transition-colors flex items-center"
                    >
                        <i
                            v-if="loading"
                            class="fas fa-spinner fa-spin mr-1"
                        ></i>
                        {{
                            loading
                                ? "Loading..."
                                : selectedAccount
                                ? "Select"
                                : "Select a Customer"
                        }}
                    </button>
                    <button
                        type="button"
                        @click="$emit('close')"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-3 rounded text-xs"
                    >
                        Exit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Account Zoom Modal -->
    <CustomerAccountZoomModal
        :show="showCustomerAccountZoomModal"
        @close="showCustomerAccountZoomModal = false"
        :customerAccountData="selectedAccount"
    />
</template>

<script>
import { ref, onMounted, onUnmounted, computed, watch } from "vue";
import axios from "axios";
import CustomerAccountZoomModal from "@/Components/CustomerAccountZoomModal.vue";

export default {
    components: {
        CustomerAccountZoomModal,
    },
    props: {
        show: {
            type: Boolean,
            required: true,
        },
        customerAccounts: {
            type: Array,
            default: () => [],
        },
        initialSortBy: {
            type: String,
            default: "customer_code",
        },
        initialStatusFilter: {
            type: Array,
            default: () => ["Active"],
        },
    },
    emits: ["close", "select", "sort-by-code", "sort-by-name"],
    setup(props, { emit }) {
        const allAccounts = ref([]);
        const selectedAccount = ref(null);
        const loading = ref(false);
        const error = ref(null);
        const sortBy = ref(props.initialSortBy);
        const statusFilter = ref(
            props.initialStatusFilter.includes("Active")
                ? "active"
                : props.initialStatusFilter.includes("Obsolete")
                ? "obsolete"
                : "all"
        );
        const searchQuery = ref("");

        // New state for Customer Account Zoom Modal
        const showCustomerAccountZoomModal = ref(false);

        // Track request cancellation
        let currentRequest = null;

        const fetchCustomerAccounts = async () => {
            // Use provided customer accounts if available
            if (props.customerAccounts && props.customerAccounts.length > 0) {
                console.log(
                    "Using provided customer accounts:",
                    props.customerAccounts.length
                );
                allAccounts.value = props.customerAccounts;
                return;
            }

            // Prevent multiple simultaneous requests
            if (loading.value) {
                console.log("Request already in progress, skipping...");
                return;
            }

            // Cancel previous request if exists
            if (currentRequest) {
                currentRequest.cancel("New request initiated");
            }

            loading.value = true;
            error.value = null;

            try {
                console.log("Fetching customer accounts from API...");

                // Create cancelable request
                const source = axios.CancelToken.source();
                currentRequest = source;

                // Use the correct API endpoint for customer accounts with timeout
                const response = await axios.get("/api/customers-with-status", {
                    timeout: 10000, // 10 second timeout
                    cancelToken: source.token,
                });

                const data = response.data;

                if (data.error) {
                    throw new Error(data.error);
                }

                if (Array.isArray(data)) {
                    allAccounts.value = data;
                    console.log(`Loaded ${data.length} accounts`);
                } else if (data.data && Array.isArray(data.data)) {
                    allAccounts.value = data.data;
                    console.log(
                        `Loaded ${data.data.length} accounts from data property`
                    );
                } else {
                    console.error("Unexpected data format:", data);
                    throw new Error("Invalid data format returned from server");
                }

                console.log("Customer accounts loaded successfully");

                // Don't auto-select - let user choose
                selectedAccount.value = null;
            } catch (err) {
                if (axios.isCancel(err)) {
                    console.log("Request cancelled:", err.message);
                    return; // Don't set error for cancelled requests
                }

                console.error("Error fetching customer accounts:", err);

                // Provide more specific error messages
                let errorMessage = "Failed to load customer accounts";

                if (
                    err.code === "ECONNABORTED" ||
                    err.message.includes("timeout")
                ) {
                    errorMessage =
                        "Request timed out. Please check your internet connection and try again.";
                } else if (err.response) {
                    errorMessage = `Server error: ${err.response.status} - ${
                        err.response.data?.message || err.response.statusText
                    }`;
                } else if (err.request) {
                    errorMessage =
                        "Network error. Please check your internet connection.";
                } else {
                    errorMessage = err.message || "Unknown error occurred";
                }

                error.value = errorMessage;
            } finally {
                loading.value = false;
                currentRequest = null;
            }
        };

        const filteredAccounts = computed(() => {
            let filtered = [...allAccounts.value];

            // Filter based on search query
            if (searchQuery.value.trim()) {
                const query = searchQuery.value.toLowerCase().trim();
                filtered = filtered.filter(
                    (account) =>
                        account.customer_code?.toLowerCase().includes(query) ||
                        account.customer_name?.toLowerCase().includes(query)
                );
            }

            // Filter based on status
            if (statusFilter.value === "active") {
                filtered = filtered.filter(
                    (account) =>
                        account.status === "Active" ||
                        account.status === undefined
                );
            } else if (statusFilter.value === "obsolete") {
                filtered = filtered.filter(
                    (account) =>
                        account.status === "Inactive" ||
                        account.status === "Obsolete"
                );
            }

            // Sort based on selected field
            filtered.sort((a, b) => {
                const fieldA = a[sortBy.value]?.toString().toLowerCase() || "";
                const fieldB = b[sortBy.value]?.toString().toLowerCase() || "";
                return fieldA.localeCompare(fieldB);
            });

            return filtered;
        });

        const selectAccount = (account) => {
            console.log("Selecting account:", account);
            selectedAccount.value = account;
        };

        const handleSelect = () => {
            if (selectedAccount.value) {
                console.log("Selected customer:", selectedAccount.value);
                emit("select", selectedAccount.value);
                emit("close");
            } else {
                console.warn("No customer account selected");
                error.value = "Please select a customer account from the list";

                // Clear the error after 3 seconds
                setTimeout(() => {
                    error.value = null;
                }, 3000);
            }
        };

        // Functions to open new modals
        const openCustomerAccountZoomModal = () => {
            showCustomerAccountZoomModal.value = true;
        };

        const handleZoomChange = (event) => {
            const zoomOption = event.target.value;
            if (zoomOption && selectedAccount.value) {
                // Handle different zoom options
                switch (zoomOption) {
                    case "customer_details":
                        // Open zoom modal for customer details
                        showCustomerAccountZoomModal.value = true;
                        break;
                    case "customer_address":
                        // Handle customer address zoom
                        console.log("Customer address zoom selected");
                        break;
                    case "customer_contacts":
                        // Handle customer contacts zoom
                        console.log("Customer contacts zoom selected");
                        break;
                }
                // Reset zoom dropdown
                event.target.value = "";
            }
        };

        // Watch for changes in sort options and emit sort event
        watch([sortBy, statusFilter], () => {
            if (sortBy.value === "customer_code") {
                emit("sort-by-code", {
                    sortBy: sortBy.value,
                    status: statusFilter.value,
                });
            } else if (sortBy.value === "customer_name") {
                emit("sort-by-name", {
                    sortBy: sortBy.value,
                    status: statusFilter.value,
                });
            }
        });

        // Watch for changes in the show prop to fetch data when modal is shown
        watch(
            () => props.show,
            (newValue, oldValue) => {
                if (newValue === true && oldValue === false) {
                    // Only fetch when modal is actually being opened (not on initial mount)
                    // And only if we don't already have data
                    if (allAccounts.value.length === 0) {
                        fetchCustomerAccounts();
                    } else {
                        console.log("Using cached customer accounts data");
                    }

                    // Reset selected account when reopening modal
                    selectedAccount.value = null;
                }

                if (newValue === false) {
                    // Cancel any ongoing request when modal is closed
                    if (currentRequest) {
                        currentRequest.cancel("Modal closed");
                    }
                }
            }
        );

        // Watch for changes in external customer accounts
        watch(
            () => props.customerAccounts,
            (newAccounts) => {
                if (newAccounts && newAccounts.length > 0) {
                    console.log(
                        "Customer accounts prop updated:",
                        newAccounts.length
                    );
                    allAccounts.value = [...newAccounts]; // Create a copy to avoid mutation
                    selectedAccount.value = null; // Reset selection when data changes
                }
            },
            { deep: true }
        );

        // Watch search query to reset selection when user starts searching
        watch(searchQuery, (newQuery) => {
            if (newQuery.trim() && selectedAccount.value) {
                // Clear selection when user starts searching to avoid confusion
                selectedAccount.value = null;
            }
        });

        // Cleanup function for request cancellation
        const cleanup = () => {
            if (currentRequest) {
                currentRequest.cancel("Component unmounting");
            }
        };

        // Cleanup on component unmount
        onUnmounted(() => {
            cleanup();
        });

        return {
            allAccounts,
            filteredAccounts,
            selectedAccount,
            loading,
            error,
            sortBy,
            statusFilter,
            searchQuery,
            selectAccount,
            handleSelect,
            fetchCustomerAccounts,
            cleanup,
            // Expose new modal states and functions
            showCustomerAccountZoomModal,
            openCustomerAccountZoomModal,
            handleZoomChange,
        };
    },
};
</script>
