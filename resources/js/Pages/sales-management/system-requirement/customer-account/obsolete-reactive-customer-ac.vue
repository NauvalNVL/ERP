<template>
    <AppLayout :header="'Obsolete/Reactive Customer Account'">
    <Head title="Obsolete/Reactive Customer Account" />

    <!-- Hidden form with CSRF token -->
    <form ref="csrfForm" class="hidden">
        @csrf
    </form>

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
        <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
            <i class="fas fa-user-slash mr-3"></i> Obsolete/Reactive Customer Account
        </h2>
        <p class="text-cyan-100">Manage customer account status: Activate or Deactivate accounts</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2">
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
                    <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-blue-500 rounded-lg mr-3">
                            <i class="fas fa-edit text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Customer Account Status Management</h3>
                    </div>
                    
                    <!-- Search Section -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Customer Account:</label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" v-model="searchQuery" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <button type="button" @click="openSortDialog" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md transition-colors transform active:translate-y-px">
                                    <i class="fas fa-table"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Action:</label>
                            <button type="button" @click="performAction" class="w-full flex items-center justify-center px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded transition-colors transform active:translate-y-px">
                                <i class="fas fa-sync-alt mr-2"></i> Update Status
                            </button>
                        </div>
                    </div>
                    
                    <!-- Data Status Information -->
                    <div v-if="loading" class="mt-4 bg-yellow-100 p-3 rounded">
                        <div class="flex items-center">
                            <div class="mr-3 animate-spin rounded-full h-6 w-6 border-b-2 border-yellow-700"></div>
                            <p class="text-sm font-medium text-yellow-800">Loading customer data...</p>
                        </div>
                    </div>
                    <div v-else-if="!selectedAccount" class="mt-4 bg-yellow-100 p-3 rounded">
                        <p class="text-sm font-medium text-yellow-800">No customer account selected.</p>
                        <p class="text-xs text-yellow-700 mt-1">Please search and select a customer account first.</p>
                    </div>
                    <div v-else class="mt-4 bg-green-100 p-3 rounded">
                        <p class="text-sm font-medium text-green-800">Selected Account: {{ selectedAccount.customer_code }} - {{ selectedAccount.customer_name }}</p>
                        <div class="flex items-center mt-2">
                            <span class="text-xs font-medium mr-2">Current Status:</span>
                            <span v-if="selectedAccount.status === 'Active' || selectedAccount.status === undefined" class="px-2 py-1 text-xs font-medium bg-green-200 text-green-800 rounded-full">
                                <i class="fas fa-check-circle mr-1"></i> Active
                            </span>
                            <span v-else class="px-2 py-1 text-xs font-medium bg-red-200 text-red-800 rounded-full">
                                <i class="fas fa-times-circle mr-1"></i> Inactive
                            </span>
                        </div>
                    </div>

                    <!-- Customer Details when selected -->
                    <div v-if="selectedAccount" class="mt-6 border-t border-gray-200 pt-6">
                        <h4 class="text-lg font-medium text-gray-800 mb-4">Customer Account Details</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Customer Code:</p>
                                <p class="font-medium">{{ selectedAccount.customer_code }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Customer Name:</p>
                                <p class="font-medium">{{ selectedAccount.customer_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Address:</p>
                                <p class="font-medium">{{ selectedAccount.address || 'Not specified' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Contact Person:</p>
                                <p class="font-medium">{{ selectedAccount.contact_person || 'Not specified' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Telephone:</p>
                                <p class="font-medium">{{ selectedAccount.telephone_no || 'Not specified' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Email:</p>
                                <p class="font-medium">{{ selectedAccount.co_email || 'Not specified' }}</p>
                            </div>
                        </div>

                        <!-- Status Change Form -->
                        <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <h5 class="font-medium text-gray-700 mb-3">Update Account Status</h5>
                            <div class="flex items-center space-x-6">
                                <label class="inline-flex items-center">
                                    <input type="radio" class="form-radio h-5 w-5 text-blue-600" v-model="newStatus" value="Y" checked>
                                    <span class="ml-2 text-gray-700">Activate Account</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" class="form-radio h-5 w-5 text-red-600" v-model="newStatus" value="N">
                                    <span class="ml-2 text-gray-700">Deactivate Account</span>
                                </label>
                            </div>
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Reason for Change:</label>
                                <textarea v-model="reason" rows="3" class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter reason for status change..."></textarea>
                            </div>
                            <div class="mt-4 flex justify-end">
                                <button type="button" @click="confirmStatusChange" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    Update Status
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Right Column - Quick Info -->
            <div class="lg:col-span-1">
                <!-- Info Card -->
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-teal-500 mb-6">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-teal-500 rounded-lg mr-3">
                            <i class="fas fa-info-circle text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Account Status Information</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="p-4 bg-teal-50 rounded-lg">
                            <h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2">Instructions</h4>
                            <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                                <li>Search for a customer account first</li>
                                <li>Check the current status before making changes</li>
                                <li>Provide a reason for status changes</li>
                                <li>Deactivated accounts will not appear in most reports</li>
                            </ul>
                        </div>

                        <div class="p-4 bg-blue-50 rounded-lg">
                            <h4 class="text-sm font-semibold text-blue-800 uppercase tracking-wider mb-2">Status Definitions</h4>
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                                    <span class="text-sm font-medium text-gray-800">Active:</span>
                                    <span class="text-sm text-gray-600 ml-2">Account is operational</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span>
                                    <span class="text-sm font-medium text-gray-800">Inactive:</span>
                                    <span class="text-sm text-gray-600 ml-2">Account is deactivated</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-purple-500">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-purple-500 rounded-lg mr-3">
                            <i class="fas fa-link text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Quick Links</h3>
                    </div>

                    <div class="grid grid-cols-1 gap-3">
                        <Link href="/update-customer-account" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <div class="p-2 bg-blue-500 rounded-full mr-3">
                                <i class="fas fa-user-edit text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-blue-900">Update Customer</p>
                                <p class="text-xs text-blue-700">Modify customer details</p>
                            </div>
                        </Link>

                        <Link href="/customer-group" class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                            <div class="p-2 bg-purple-500 rounded-full mr-3">
                                <i class="fas fa-users text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-purple-900">Customer Groups</p>
                                <p class="text-xs text-purple-700">Manage customer groups</p>
                            </div>
                        </Link>

                        <Link href="/customer-alternate-address" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            <div class="p-2 bg-green-500 rounded-full mr-3">
                                <i class="fas fa-map-marker-alt text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-green-900">Alternate Addresses</p>
                                <p class="text-xs text-green-700">Manage delivery addresses</p>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Account Modal -->
    <CustomerAccountModal
        :show="showModal"
        :customer-accounts="customerAccounts"
        @close="showModal = false"
        @select="onCustomerAccountSelected"
        @sort="handleSortConfirm"
    />

    <!-- Loading Overlay -->
    <div v-if="saving" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex justify-center items-center">
        <div class="w-12 h-12 border-4 border-solid border-blue-500 border-t-transparent rounded-full animate-spin"></div>
    </div>
    
    <!-- Notification Toast -->
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
import { ref, onMounted, watch, computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import CustomerAccountModal from '@/Components/customer-account-modal.vue';

// Get the header from props
const props = defineProps({
    header: {
        type: String,
        default: 'Obsolete/Reactive Customer Account'
    }
});

// Reference to the CSRF form
const csrfForm = ref(null);

// Function to get fresh CSRF token from the form
const getCsrfToken = () => {
    // Try to get token from meta tag first (prefer this method)
    let token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    // Option 2: Try to get from Inertia usePage if available
    if (!token && usePage().props.value?.csrf) {
        token = usePage().props.value.csrf;
    }
    
    // Option 3: Try to get from hidden form with @csrf directive
    if (!token && csrfForm.value) {
        const tokenInput = csrfForm.value.querySelector('input[name="_token"]');
        if (tokenInput) {
            token = tokenInput.value;
        }
    }
    
    return token || '';
};

const customerAccounts = ref([]);
const loading = ref(false);
const saving = ref(false);
const showModal = ref(false);
const selectedAccount = ref(null);
const searchQuery = ref('');
const newStatus = ref('Y');
const reason = ref('');
const notification = ref({ show: false, message: '', type: 'success' });
const sortConfig = ref({
    sortBy: 'customer_code',
    status: 'active'
});

// Fetch customer accounts from API
const fetchCustomerAccounts = async () => {
    loading.value = true;
    try {
        console.log('Fetching customer accounts from parent component...');
        const response = await fetch('/api/customer-accounts', { 
            headers: { 
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });
        
        console.log('API response status:', response.status);
        
        if (!response.ok) {
            throw new Error(`Server returned ${response.status}: ${response.statusText}`);
        }
        
        const rawData = await response.json();
        console.log('Customer accounts data type:', typeof rawData, 'Is array:', Array.isArray(rawData), 'Length:', Array.isArray(rawData) ? rawData.length : 'N/A');
        
        if (rawData.error) {
            throw new Error(rawData.error);
        }
        
        if (Array.isArray(rawData)) {
            customerAccounts.value = rawData;
            console.log(`Loaded ${rawData.length} customer accounts`);
        } else if (rawData.data && Array.isArray(rawData.data)) {
            customerAccounts.value = rawData.data;
            console.log(`Loaded ${rawData.data.length} customer accounts from data property`);
        } else {
            customerAccounts.value = [];
            console.error('Unexpected data format:', rawData);
            throw new Error('Invalid data format returned from server');
        }
    } catch (error) {
        console.error('Error fetching customer accounts:', error);
        showNotification(`Failed to load customer data: ${error.message}`, 'error');
        customerAccounts.value = [];
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchCustomerAccounts();
});

// Watch for changes in search query to filter the data
watch(searchQuery, (newQuery) => {
    if (newQuery && customerAccounts.value.length > 0) {
        const foundAccount = customerAccounts.value.find(account => 
            (account.customer_code && account.customer_code.toLowerCase().includes(newQuery.toLowerCase())) ||
            (account.customer_name && account.customer_name.toLowerCase().includes(newQuery.toLowerCase()))
        );
        
        if (foundAccount) {
            selectedAccount.value = foundAccount;
            // Default to active if status is undefined
            newStatus.value = (foundAccount.status === 'Inactive') ? 'N' : 'Y';
        }
    }
});

const onCustomerAccountSelected = (account) => {
    selectedAccount.value = account;
    searchQuery.value = account.customer_code;
    showModal.value = false;
    // Default to active if status is undefined
    newStatus.value = (account.status === 'Inactive') ? 'N' : 'Y';
    reason.value = '';
};

const performAction = () => {
    if (selectedAccount.value) {
        confirmStatusChange();
    } else {
        showNotification('Please select a customer account first', 'warning');
    }
};

const confirmStatusChange = async () => {
    if (!selectedAccount.value) {
        showNotification('Please select a customer account first', 'warning');
        return;
    }

    if (!reason.value) {
        showNotification('Please provide a reason for the status change', 'warning');
        return;
    }

    saving.value = true;
    try {
        const csrfToken = getCsrfToken();
        console.log('Updating customer status for:', selectedAccount.value.customer_code);
        
        const response = await fetch(`/api/customer-accounts/${selectedAccount.value.customer_code}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                active: newStatus.value,
                reason: reason.value
            }),
            credentials: 'same-origin'
        });
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || `Server returned ${response.status}: ${response.statusText}`);
        }
        
        const result = await response.json();
        console.log('Status update result:', result);
        
        // Update local data
        selectedAccount.value.active = newStatus.value;
        
        // Show success notification
        showNotification(
            newStatus.value === 'Y' 
                ? 'Customer account activated successfully' 
                : 'Customer account deactivated successfully',
            'success'
        );
        
        // Refresh customer accounts list
        fetchCustomerAccounts();
        
        // Reset reason field
        reason.value = '';
        
    } catch (error) {
        console.error('Error updating customer status:', error);
        showNotification('Error: ' + error.message, 'error');
    } finally {
        saving.value = false;
    }
};

const showNotification = (message, type = 'success') => {
    notification.value = {
        show: true,
        message,
        type
    };
    
    setTimeout(() => {
        notification.value.show = false;
    }, 3000);
};

const openSortDialog = () => {
    if (customerAccounts.value.length === 0) {
        fetchCustomerAccounts().then(() => {
            showModal.value = true;
        });
    } else {
        showModal.value = true;
    }
};

const handleSortConfirm = (config) => {
    sortConfig.value = config;
};

const filteredCustomerAccounts = computed(() => {
    let filtered = [...customerAccounts.value];
    
    // Filter berdasarkan status
    if (sortConfig.value.status === 'active') {
        filtered = filtered.filter(account => account.status === 'Active' || account.status === undefined);
    } else if (sortConfig.value.status === 'obsolete') {
        filtered = filtered.filter(account => account.status === 'Inactive');
    }
    
    // Sort berdasarkan field yang dipilih
    filtered.sort((a, b) => {
        const fieldA = a[sortConfig.value.sortBy]?.toLowerCase() || '';
        const fieldB = b[sortConfig.value.sortBy]?.toLowerCase() || '';
        return fieldA.localeCompare(fieldB);
    });
    
    return filtered;
});
</script>