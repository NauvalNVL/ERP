<template>
    <AppLayout :header="'Update Customer Account'">
    <Head title="Customer Account Management" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
        <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
            <i class="fas fa-user-edit mr-3"></i> Update Customer Account
        </h2>
        <p class="text-cyan-100">Manage and update customer account information</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2">
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
                    <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-blue-500 rounded-lg mr-3">
                            <i class="fas fa-search text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Find Customer</h3>
                    </div>
                    
                    <!-- Search Section -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Search Customer:</label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input 
                                    type="text" 
                                    v-model="searchQuery" 
                                    placeholder="Enter code or name..."
                                    @keyup.enter="searchCustomers"
                                    class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                >
                                <button 
                                    type="button" 
                                    @click="searchCustomers" 
                                    :disabled="isSearching"
                                    class="inline-flex items-center px-3 py-2 border border-gray-300 bg-blue-500 hover:bg-blue-600 text-white disabled:bg-blue-300"
                                >
                                    <i v-if="!isSearching" class="fas fa-search"></i>
                                    <div v-else class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></div>
                                </button>
                                <button 
                                    v-if="searchQuery"
                                    type="button" 
                                    @click="clearSearch" 
                                    class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-r-md"
                                >
                                    <i class="fas fa-times"></i>
                                </button>
                                <span v-else class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-100 text-gray-400 rounded-r-md">
                                    <i class="fas fa-search"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Action:</label>
                            <button 
                                type="button" 
                                @click="openCustomerAccountModal" 
                                class="w-full flex items-center justify-center px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded"
                            >
                                <i class="fas fa-table mr-2"></i> Browse All
                            </button>
                        </div>
                    </div>

                    <!-- Search Results / Customer Table -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mt-4">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Customer Code
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Customer Name
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Address
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Contact
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-if="customers.length === 0">
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Search for customers using the field above.
                                        </td>
                                    </tr>
                                    <tr 
                                        v-for="customer in customers" 
                                        :key="customer.customer_code" 
                                        @click="selectCustomerForEdit(customer)"
                                        class="hover:bg-blue-50 cursor-pointer transition-colors"
                                    >
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ customer.customer_code }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                            {{ customer.customer_name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                            {{ customer.address || 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ customer.telephone_no || 'N/A' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column -->
            <div class="lg:col-span-1">
                <!-- Customer Info Card -->
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-teal-500 mb-6">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-teal-500 rounded-lg mr-3">
                            <i class="fas fa-info-circle text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Customer Information</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="p-4 bg-teal-50 rounded-lg">
                            <h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2">Instructions</h4>
                            <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                                <li>Search for a customer by code or name</li>
                                <li>Click on a customer to edit their details</li>
                                <li>Use the Browse All button to see all customers</li>
                                <li>Save changes after editing customer information</li>
                            </ul>
                        </div>

                        <div class="p-4 bg-blue-50 rounded-lg">
                            <h4 class="text-sm font-semibold text-blue-800 uppercase tracking-wider mb-2">Recent Activity</h4>
                            <div v-if="customerSelected">
                                <p class="text-sm text-gray-600 mb-1">Selected Customer:</p>
                                <p class="font-medium">{{ form.customer_code }} - {{ form.customer_name }}</p>
                                <p class="text-xs text-gray-500 mt-1">Click the Edit button to make changes</p>
                            </div>
                            <div v-else>
                                <p class="text-sm text-gray-600">No customer selected</p>
                                <p class="text-xs text-gray-500 mt-1">Search and select a customer to view or edit</p>
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
                        <Link :href="route('vue.update-customer-account.view-print')" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            <div class="p-2 bg-green-500 rounded-full mr-3">
                                <i class="fas fa-print text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-green-900">View & Print</p>
                                <p class="text-xs text-green-700">Customer list report</p>
                            </div>
                        </Link>

                        <Link :href="route('vue.customer-group.index')" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <div class="p-2 bg-blue-500 rounded-full mr-3">
                                <i class="fas fa-users text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-blue-900">Customer Groups</p>
                                <p class="text-xs text-blue-700">Manage customer groups</p>
                            </div>
                        </Link>

                        <Link :href="route('vue.customer-alternate-address.index')" class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                            <div class="p-2 bg-purple-500 rounded-full mr-3">
                                <i class="fas fa-map-marker-alt text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-purple-900">Alternate Addresses</p>
                                <p class="text-xs text-purple-700">Manage customer addresses</p>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Account Selection Modal -->
    <CustomerAccountModal 
        v-if="showCustomerAccountModal"
        :show="showCustomerAccountModal"
        @close="closeCustomerAccountModal"
        @select="selectCustomerAccount"
    />

    <!-- Support Modals -->
    <SalespersonModal
        v-if="showSalespersonModal"
        :show="showSalespersonModal"
        :salespersons="salespersons"
        @close="closeSalespersonModal"
        @select="selectSalesperson"
        class="modal-lookup"
        style="z-index: 100;"
    />

    <IndustryModal
        v-if="showIndustryModal"
        :show="showIndustryModal"
        :industries="industries"
        @close="closeIndustryModal"
        @select="selectIndustry"
        class="modal-lookup"
        style="z-index: 100;"
    />

    <GeoModal
        v-if="showGeoModal"
        :show="showGeoModal"
        :geos="geos"
        @close="closeGeoModal"
        @select="selectGeo"
        class="modal-lookup"
        style="z-index: 100;"
    />

    <CustomerGroupModal
        v-if="showCustomerGroupModal"
        :show="showCustomerGroupModal"
        :customer-groups="customerGroups"
        @close="closeCustomerGroupModal"
        @select="selectCustomerGroup"
        class="modal-lookup"
        style="z-index: 100;"
    />

    <!-- Customer Edit Modal -->
    <div v-if="showEditModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showEditModal = false"></div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl w-full">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                    <div class="flex items-center">
                        <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <h3 class="text-xl font-semibold">Edit Customer: {{ form.customer_code }} - {{ form.customer_name }}</h3>
                    </div>
                    <button type="button" @click="showEditModal = false" class="text-white hover:text-gray-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Modal content -->
                <div class="bg-white px-6 pt-5 pb-6 max-h-[70vh] overflow-y-auto">
                    <!-- Customer Details Form -->
                    <div class="space-y-6">
                        <!-- Basic Information -->
                        <div class="border-b border-gray-200 pb-4">
                            <h4 class="text-base font-medium text-gray-800 mb-3">
                                <i class="fas fa-info-circle mr-2 text-blue-500"></i>Basic Information
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Customer Name</label>
                                    <input type="text" v-model="form.customer_name" class="form-input" required>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Short Name</label>
                                    <input type="text" v-model="form.short_name" class="form-input">
                                    <span class="text-xs text-gray-500">For Production</span>
                                </div>
                                
                                <div class="col-span-1 md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                                    <textarea v-model="form.address" rows="2" class="form-input"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Contact Information -->
                        <div class="border-b border-gray-200 pb-4">
                            <h4 class="text-base font-medium text-gray-800 mb-3">
                                <i class="fas fa-phone mr-2 text-blue-500"></i>Contact Information
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Contact Person</label>
                                    <input type="text" v-model="form.contact_person" class="form-input">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Telephone No</label>
                                    <input type="text" v-model="form.telephone_no" class="form-input">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Fax No</label>
                                    <input type="text" v-model="form.fax_no" class="form-input">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Co. Email</label>
                                    <input type="email" v-model="form.co_email" class="form-input">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Financial Information -->
                        <div class="border-b border-gray-200 pb-4">
                            <h4 class="text-base font-medium text-gray-800 mb-3">
                                <i class="fas fa-dollar-sign mr-2 text-blue-500"></i>Financial Information
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Credit Limit</label>
                                    <input type="number" v-model="form.credit_limit" step="0.01" class="form-input">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Credit Terms</label>
                                    <div class="flex items-center">
                                        <input type="number" v-model="form.credit_terms" class="form-input">
                                        <span class="ml-2">Days</span>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Account Type</label>
                                    <div class="flex space-x-4 mt-1">
                                        <label class="inline-flex items-center">
                                            <input type="radio" v-model="form.ac_type" value="Y-Foreign" class="form-radio">
                                            <span class="ml-2">Y-Foreign</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" v-model="form.ac_type" value="N-Local" class="form-radio">
                                            <span class="ml-2">N-Local</span>
                                        </label>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Print AR Aging</label>
                                    <div class="flex items-center space-x-4">
                                        <label class="inline-flex items-center">
                                            <input type="radio" v-model="form.print_ar_aging" value="Y-Yes" class="form-radio">
                                            <span class="ml-2">Y-Yes</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" v-model="form.print_ar_aging" value="N-No" class="form-radio">
                                            <span class="ml-2">N-No</span>
                                        </label>
                                    </div>
                                    <span class="text-xs text-gray-500">[For Sales Order]</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Classification Information -->
                        <div>
                            <h4 class="text-base font-medium text-gray-800 mb-3">
                                <i class="fas fa-tags mr-2 text-blue-500"></i>Classification & Codes
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Currency Code</label>
                                    <div class="flex items-center">
                                        <input type="text" v-model="form.currency_code" class="form-input w-32">
                                        <span class="ml-3 text-sm text-gray-500">Leave blank if Local Account</span>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Salesperson Code</label>
                                    <div class="flex">
                                        <input type="text" v-model="form.salesperson_code" class="form-input flex-grow rounded-r-none">
                                        <button type="button" @click="openSalespersonModal($event)" class="px-3 py-2 bg-blue-500 text-white rounded-r-md hover:bg-blue-600 transition-colors">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                    <div v-if="form.salesperson_code && getSalespersonName(form.salesperson_code)" class="mt-1 text-sm text-gray-600">
                                        {{ getSalespersonName(form.salesperson_code) }}
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Industrial Code</label>
                                    <div class="flex">
                                        <input type="text" v-model="form.industrial_code" class="form-input flex-grow rounded-r-none">
                                        <button type="button" @click="openIndustryModal($event)" class="px-3 py-2 bg-blue-500 text-white rounded-r-md hover:bg-blue-600 transition-colors">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                    <div v-if="form.industrial_code && getIndustryName(form.industrial_code)" class="mt-1 text-sm text-gray-600">
                                        {{ getIndustryName(form.industrial_code) }}
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Geographical</label>
                                    <div class="flex">
                                        <input type="text" v-model="form.geographical" class="form-input flex-grow rounded-r-none">
                                        <button type="button" @click="openGeoModal($event)" class="px-3 py-2 bg-blue-500 text-white rounded-r-md hover:bg-blue-600 transition-colors">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                    <div v-if="form.geographical && getGeoLocation(form.geographical)" class="mt-1 text-sm text-gray-600">
                                        {{ getGeoLocation(form.geographical) }}
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Grouping Code</label>
                                    <div class="flex">
                                        <input type="text" v-model="form.grouping_code" class="form-input flex-grow rounded-r-none">
                                        <button type="button" @click="openCustomerGroupModal($event)" class="px-3 py-2 bg-blue-500 text-white rounded-r-md hover:bg-blue-600 transition-colors">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                    <div v-if="form.grouping_code && getCustomerGroupName(form.grouping_code)" class="mt-1 text-sm text-gray-600">
                                        {{ getCustomerGroupName(form.grouping_code) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                    <button type="button" @click="showEditModal = false" class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </button>
                    <button 
                        type="button" 
                        @click="saveCustomerAccount" 
                        :disabled="isSaving" 
                        class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:bg-blue-400"
                    >
                        <div v-if="isSaving" class="animate-spin h-5 w-5 mr-2 border-2 border-white border-t-transparent rounded-full"></div>
                        {{ isSaving ? 'Saving...' : 'Save Changes' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div v-if="notification.show" class="fixed bottom-4 right-4 z-50 shadow-lg rounded-md px-6 py-4 transition-all transform"
        :class="{
            'bg-green-500': notification.type === 'success',
            'bg-red-500': notification.type === 'error',
            'bg-yellow-500': notification.type === 'warning',
        }">
        <div class="flex items-center text-white">
            <i class="fas mr-2" :class="{
                'fa-check-circle': notification.type === 'success',
                'fa-exclamation-circle': notification.type === 'error',
                'fa-exclamation-triangle': notification.type === 'warning'
            }"></i>
            <span>{{ notification.message }}</span>
        </div>
    </div>
    </AppLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { Head } from '@inertiajs/vue3'
import { route } from '@/ziggy'
import CustomerAccountModal from '@/Components/customer-account-modal.vue'
import SalespersonModal from '@/Components/salesperson-modal.vue'
import IndustryModal from '@/Components/industry-modal.vue'
import GeoModal from '@/Components/geo-modal.vue'
import CustomerGroupModal from '@/Components/customer-group-modal.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import axios from 'axios'

// Component state
const page = usePage()
const showCustomerAccountModal = ref(false)
const showSalespersonModal = ref(false)
const showIndustryModal = ref(false)
const showGeoModal = ref(false)
const showCustomerGroupModal = ref(false)
const showEditModal = ref(false)
const notification = ref({ show: false, message: '', type: 'success' })
const customerSelected = ref(false)
const searchQuery = ref('')
const customers = ref([])
const isSearching = ref(false)
const isSaving = ref(false)

// Data from API
const industries = ref([])
const geos = ref([])
const salespersons = ref([])
const customerGroups = ref([])

// Form state
const form = reactive({
    customer_code: '',
    customer_name: '',
    short_name: '',
    address: '',
    contact_person: '',
    telephone_no: '',
    fax_no: '',
    co_email: '',
    credit_limit: 0,
    credit_terms: 0,
    ac_type: 'N-Local', // Default value
    currency_code: '',
    salesperson_code: '',
    industrial_code: '',
    geographical: '',
    grouping_code: '',
    print_ar_aging: 'N-No' // Default value
})

// Search for customers
const searchCustomers = async () => {
    if (!searchQuery.value.trim()) {
        showNotification('Please enter a search term', 'warning')
        return
    }
    
    isSearching.value = true
    
    try {
        const response = await axios.get('/api/customer-accounts', {
            params: { search: searchQuery.value }
        })
        
        if (response.data && Array.isArray(response.data.data)) {
            customers.value = response.data.data
        } else if (Array.isArray(response.data)) {
            customers.value = response.data
        } else {
            customers.value = []
            throw new Error('Unexpected response format')
        }
        
        if (customers.value.length === 0) {
            showNotification('No customers found matching your search', 'warning')
        }
    } catch (error) {
        console.error('Error searching customers:', error)
        showNotification('Error searching for customers: ' + (error.response?.data?.message || error.message), 'error')
        customers.value = []
    } finally {
        isSearching.value = false
    }
}

// Clear search results
const clearSearch = () => {
    searchQuery.value = ''
    customers.value = []
}

// Data loading functions
const loadIndustries = async () => {
    try {
        const response = await axios.get('/api/industries')
        industries.value = response.data
    } catch (error) {
        console.error('Error loading industries:', error)
        showNotification('Failed to load industry data', 'error')
    }
}

const loadGeos = async () => {
    try {
        const response = await axios.get('/api/geos')
        geos.value = response.data
    } catch (error) {
        console.error('Error loading geo data:', error)
        showNotification('Failed to load geographical data', 'error')
    }
}

const loadSalespersons = async () => {
    try {
        const response = await axios.get('/api/salespersons')
        salespersons.value = response.data
    } catch (error) {
        console.error('Error loading salespersons:', error)
        showNotification('Failed to load salesperson data', 'error')
    }
}

const loadCustomerGroups = async () => {
    try {
        const response = await axios.get('/api/customer-groups')
        customerGroups.value = response.data
    } catch (error) {
        console.error('Error loading customer groups:', error)
        showNotification('Failed to load customer group data', 'error')
    }
}

// Select customer from table and open edit modal
const selectCustomerForEdit = (customer) => {
    // Make a copy of the customer data to avoid direct reference
    const customerData = { ...customer }
    
    // Fill the form with customer data, ensuring all required fields have valid values
    form.customer_code = customerData.customer_code
    form.customer_name = customerData.customer_name || ''
    form.short_name = customerData.short_name || ''
    form.address = customerData.address || ''
    form.contact_person = customerData.contact_person || ''
    form.telephone_no = customerData.telephone_no || ''
    form.fax_no = customerData.fax_no || ''
    form.co_email = customerData.co_email || ''
    form.credit_limit = customerData.credit_limit || 0
    form.credit_terms = customerData.credit_terms || 0
    form.ac_type = customerData.ac_type || customerData.account_type || 'N-Local'
    
    // Ensure ac_type has a valid value
    if (!['Y-Foreign', 'N-Local'].includes(form.ac_type)) {
        form.ac_type = 'N-Local'
    }
    
    form.currency_code = customerData.currency_code || ''
    form.salesperson_code = customerData.salesperson_code || ''
    form.industrial_code = customerData.industrial_code || ''
    form.geographical = customerData.geographical || ''
    form.grouping_code = customerData.grouping_code || ''
    form.print_ar_aging = customerData.print_ar_aging || 'N-No'
    
    // Ensure print_ar_aging has a valid value
    if (!['Y-Yes', 'N-No'].includes(form.print_ar_aging)) {
        form.print_ar_aging = 'N-No'
    }
    
    customerSelected.value = true
    showEditModal.value = true // Automatically open the edit modal
    
    // If we don't have all the customer data, fetch the complete record
    if (!customerData.fax_no || customerData.credit_limit === undefined || 
        customerData.credit_terms === undefined || !customerData.currency_code ||
        !customerData.industrial_code || !customerData.geographical || !customerData.grouping_code) {
        
        fetchCompleteCustomerData(customerData.id)
    }
}

// Add a new function to fetch complete customer data
const fetchCompleteCustomerData = async (id) => {
    try {
        const response = await axios.get(`/api/customer-accounts/${id}`)
        if (response.data) {
            // Update form with complete data
            const completeData = response.data
            
            // Update form fields that might be missing in the initial data
            form.fax_no = completeData.fax_no || form.fax_no
            form.credit_limit = completeData.credit_limit || form.credit_limit
            form.credit_terms = completeData.credit_terms || form.credit_terms
            form.currency_code = completeData.currency_code || form.currency_code
            form.industrial_code = completeData.industrial_code || form.industrial_code
            form.geographical = completeData.geographical || form.geographical
            form.grouping_code = completeData.grouping_code || form.grouping_code
            form.print_ar_aging = completeData.print_ar_aging || form.print_ar_aging
        }
    } catch (error) {
        console.error('Error fetching complete customer data:', error)
    }
}

// Helper functions to get names for selected codes
const getIndustryName = (code) => {
    const industry = industries.value.find(i => i.code === code)
    return industry ? industry.name : ''
}

const getGeoLocation = (code) => {
    const geo = geos.value.find(g => g.code === code)
    if (!geo) return ''
    return `${geo.country} - ${geo.state}, ${geo.town}`
}

const getSalespersonName = (code) => {
    const salesperson = salespersons.value.find(s => s.code === code)
    return salesperson ? salesperson.name : ''
}

const getCustomerGroupName = (code) => {
    const group = customerGroups.value.find(g => g.group_code === code || g.code === code)
    return group ? (group.description || group.name || '') : ''
}

// Customer account modal functions
const openCustomerAccountModal = () => {
    showCustomerAccountModal.value = true
}

const closeCustomerAccountModal = () => {
    showCustomerAccountModal.value = false
}

const selectCustomerAccount = (account) => {
    // Make a copy of the account data to avoid direct reference
    const accountData = { ...account }
    
    // Fill the form with customer data, ensuring all required fields have valid values
    form.customer_code = accountData.customer_code
    form.customer_name = accountData.customer_name || ''
    form.short_name = accountData.short_name || ''
    form.address = accountData.address || ''
    form.contact_person = accountData.contact_person || ''
    form.telephone_no = accountData.telephone_no || ''
    form.fax_no = accountData.fax_no || ''
    form.co_email = accountData.co_email || ''
    form.credit_limit = accountData.credit_limit || 0
    form.credit_terms = accountData.credit_terms || 0
    form.ac_type = accountData.ac_type || accountData.account_type || 'N-Local'
    
    // Ensure ac_type has a valid value
    if (!['Y-Foreign', 'N-Local'].includes(form.ac_type)) {
        form.ac_type = 'N-Local'
    }
    
    form.currency_code = accountData.currency_code || ''
    form.salesperson_code = accountData.salesperson_code || ''
    form.industrial_code = accountData.industrial_code || ''
    form.geographical = accountData.geographical || ''
    form.grouping_code = accountData.grouping_code || ''
    form.print_ar_aging = accountData.print_ar_aging || 'N-No'
    
    // Ensure print_ar_aging has a valid value
    if (!['Y-Yes', 'N-No'].includes(form.print_ar_aging)) {
        form.print_ar_aging = 'N-No'
    }
    
    closeCustomerAccountModal()
    customerSelected.value = true
    showEditModal.value = true // Automatically open the edit modal
    
    // If we don't have all the customer data, fetch the complete record
    if (!accountData.fax_no || accountData.credit_limit === undefined || 
        accountData.credit_terms === undefined || !accountData.currency_code ||
        !accountData.industrial_code || !accountData.geographical || !accountData.grouping_code) {
        
        fetchCompleteCustomerData(accountData.id)
    }
}

// Salesperson modal functions
const openSalespersonModal = (event) => {
    // Prevent event propagation to avoid closing the edit modal
    if (event) {
        event.stopPropagation();
    }
    showSalespersonModal.value = true;
}

const closeSalespersonModal = () => {
    showSalespersonModal.value = false;
}

const selectSalesperson = (salesperson) => {
    form.salesperson_code = salesperson.code;
    closeSalespersonModal();
}

// Industry modal functions
const openIndustryModal = (event) => {
    // Prevent event propagation to avoid closing the edit modal
    if (event) {
        event.stopPropagation();
    }
    showIndustryModal.value = true;
}

const closeIndustryModal = () => {
    showIndustryModal.value = false;
}

const selectIndustry = (industry) => {
    form.industrial_code = industry.code;
    closeIndustryModal();
}

// Geo modal functions
const openGeoModal = (event) => {
    // Prevent event propagation to avoid closing the edit modal
    if (event) {
        event.stopPropagation();
    }
    showGeoModal.value = true;
}

const closeGeoModal = () => {
    showGeoModal.value = false;
}

const selectGeo = (geo) => {
    form.geographical = geo.code;
    closeGeoModal();
}

// Customer group modal functions
const openCustomerGroupModal = (event) => {
    // Prevent event propagation to avoid closing the edit modal
    if (event) {
        event.stopPropagation();
    }
    showCustomerGroupModal.value = true;
}

const closeCustomerGroupModal = () => {
    showCustomerGroupModal.value = false;
}

const selectCustomerGroup = (group) => {
    form.grouping_code = group.group_code;
    closeCustomerGroupModal();
}

// Save customer account function
const saveCustomerAccount = async () => {
    if (!form.customer_code) {
        showNotification('Please select a customer first', 'warning')
        return
    }
    
    // Basic validation
    if (!form.customer_name.trim()) {
        showNotification('Customer name is required', 'warning')
        return
    }
    
    // Ensure required fields have valid values
    if (!form.ac_type || !['Y-Foreign', 'N-Local'].includes(form.ac_type)) {
        form.ac_type = 'N-Local'; // Default to N-Local if not set or invalid
    }
    
    if (!form.print_ar_aging || !['Y-Yes', 'N-No'].includes(form.print_ar_aging)) {
        form.print_ar_aging = 'N-No'; // Default to N-No if not set or invalid
    }
    
    isSaving.value = true
    
    try {
        // Create a copy of the form data to send to the API
        const customerData = { ...form }
        
        // Ensure numeric fields are properly formatted
        if (customerData.credit_limit) {
            customerData.credit_limit = parseFloat(customerData.credit_limit);
        }
        
        if (customerData.credit_terms) {
            customerData.credit_terms = parseInt(customerData.credit_terms);
        }
        
        console.log('Sending customer data:', customerData);
        
        // Check if the customer already exists
        let response;
        const existingCustomer = customers.value.find(c => c.customer_code === form.customer_code);
        
        if (existingCustomer && existingCustomer.id) {
            // Update existing customer using PUT - Fix the URL to match the API endpoint
            console.log('Updating existing customer with ID:', existingCustomer.id);
            response = await axios.put(`/api/customer-accounts/${existingCustomer.id}`, customerData);
        } else {
            // Create new customer using POST - Fix the URL to match the API endpoint
            console.log('Creating new customer');
            response = await axios.post('/api/customer-accounts', customerData);
        }
        
        if (response.data && response.status >= 200 && response.status < 300) {
            showNotification('Customer account saved successfully', 'success')
            showEditModal.value = false
            
            // Refresh the search results to show updated data
            if (searchQuery.value) {
                await searchCustomers()
            } else {
                // If we're not in a search context, update the local list if the customer exists
                const index = customers.value.findIndex(c => c.customer_code === form.customer_code)
                if (index !== -1) {
                    customers.value[index] = { 
                        ...customers.value[index], 
                        ...customerData 
                    }
                }
            }
        } else {
            throw new Error(response.data?.message || 'No data returned from the server')
        }
    } catch (error) {
        console.error('Error saving customer account:', error)
        let errorMessage = 'Failed to save customer account'
        
        if (error.response) {
            // Handle validation errors
            if (error.response.status === 422 && error.response.data.errors) {
                // Log detailed validation errors for debugging
                console.error('Validation errors:', error.response.data.errors)
                
                // Format validation errors for display
                const validationErrors = Object.entries(error.response.data.errors).map(([field, messages]) => {
                    return `${field}: ${messages.join(', ')}`;
                });
                
                errorMessage += ': ' + validationErrors.join(' | ')
            } else if (error.response.data.message) {
                errorMessage += ': ' + error.response.data.message
            }
        } else if (error.message) {
            errorMessage += ': ' + error.message
        }
        
        showNotification(errorMessage, 'error')
    } finally {
        isSaving.value = false
    }
}

// Display notification
const showNotification = (message, type = 'success') => {
    notification.value = {
        show: true,
        message,
        type
    }
    
    setTimeout(() => {
        notification.value.show = false
    }, 3000)
}

// Initialize component
onMounted(() => {
    // Load data from APIs
    loadIndustries()
    loadGeos()
    loadSalespersons()
    loadCustomerGroups()
    
    // Check for flash messages
    if (page.props.flash && page.props.flash.message) {
        showNotification(page.props.flash.message, 'success')
    }
    
    // Load initial data if search query is provided
    if (searchQuery.value) {
        searchCustomers()
    }
})
</script>

<style scoped>
.form-input {
    @apply block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500;
}

.form-radio {
    @apply h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500;
}

/* Add higher z-index for lookup modals */
:deep(.modal-lookup) {
    z-index: 100 !important; /* Higher than the edit modal (z-50) */
}

:deep(.modal-lookup > div) {
    z-index: 100 !important;
}

/* Ensure the modal backdrop also has higher z-index */
:deep(.modal-lookup .bg-black) {
    z-index: 99 !important;
}

/* Add additional styles to prevent modal stacking issues */
:deep(.modal-lookup .fixed) {
    z-index: 100 !important;
}

:deep(.modal-lookup .bg-opacity-75) {
    z-index: 99 !important;
}
</style>