<!-- 
  Obsolate and Reactive Master Card Component
  This component handles obsolating and reactivating master cards in the system
-->
<template>
    <AppLayout :header="'Obsolate & Reactive MC'">
        <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 p-6">
            <div class="max-w-7xl mx-auto">
                <!-- Header -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                <!-- Refresh/Power Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-white"><path d="M23 4v6h-6"></path><path d="M1 20v-6h6"></path><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-800">Obsolate & Reactive Master Card</h1>
                                <p class="text-gray-600">Manage active and obsolate status of master cards</p>
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            <button 
                                @click="showOptions = !showOptions"
                                class="flex items-center space-x-2 px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg"
                            >
                                <!-- Filter Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                                <span>Options</span>
                            </button>
                        </div>
                    </div>

                    <!-- Search Bar -->
                    <div class="relative">
                        <!-- Search Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <input
                            type="text"
                            placeholder="Search master cards..."
                            v-model="searchTerm"
                            class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                        />
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <!-- Master Card List -->
                    <div class="lg:col-span-3">
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                            <div class="p-6 border-b border-gray-200">
                                <h2 class="text-lg font-semibold text-gray-800">Master Card Table</h2>
                            </div>
                            
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">MC Seq#</th>
                                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">MC Model</th>
                                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Customer</th>
                                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Status</th>
                                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr 
                                            v-for="mc in filteredMasterCards"
                                            :key="mc.id"
                                            class="hover:bg-blue-50 transition-colors duration-150 cursor-pointer"
                                            :class="{ 'bg-blue-50 border-l-4 border-blue-500': selectedMasterCard?.id === mc.id }"
                                            @click="selectedMasterCard = mc"
                                        >
                                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ mc.mc_seq }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-700">{{ mc.mc_model }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-700">{{ mc.customer_name }}</td>
                                            <td class="px-6 py-4">
                                                <span 
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                                    :class="{
                                                        'bg-green-100 text-green-800': mc.status === 'active', 
                                                        'bg-red-100 text-red-800': mc.status === 'obsolete'
                                                    }">
                                                    {{ mc.status.charAt(0).toUpperCase() + mc.status.slice(1) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex space-x-2">
                                                    <button 
                                                        v-if="mc.status === 'active'"
                                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-150" 
                                                        @click.stop="handleObsolate(mc)"
                                                    >
                                                        <!-- Ban Icon -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><circle cx="12" cy="12" r="10"></circle><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line></svg>
                                                    </button>
                                                    <button 
                                                        v-if="mc.status === 'obsolete'"
                                                        class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors duration-150" 
                                                        @click.stop="handleReactive(mc)"
                                                    >
                                                        <!-- Refresh Icon -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M23 4v6h-6"></path><path d="M1 20v-6h6"></path><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                                                    </button>
                                                    <button 
                                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-150" 
                                                        @click.stop="showDetailsModal = true"
                                                    >
                                                        <!-- Info Icon -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-if="filteredMasterCards.length === 0">
                                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                                No master cards found. Please adjust your search or filter criteria.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Options Panel -->
                    <div class="lg:col-span-1">
                        <div 
                            :class="[
                                'bg-white rounded-xl shadow-lg transition-all duration-300',
                                showOptions ? 'opacity-100 transform translate-y-0' : 'opacity-50 transform translate-y-2 pointer-events-none'
                            ]"
                        >
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-800">Options</h3>
                                    <!-- X Icon -->
                                    <svg 
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                                        class="w-5 h-5 text-gray-400 cursor-pointer hover:text-gray-600" 
                                        @click="showOptions = false">
                                        <line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </div>

                                <!-- Sort By Section -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Sort by:</label>
                                    <div class="space-y-3">
                                        <label class="flex items-center">
                                            <input
                                                type="radio"
                                                name="sortBy"
                                                value="seq"
                                                v-model="sortBy"
                                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                            />
                                            <span class="ml-3 text-sm text-gray-700">MC Sequence</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input
                                                type="radio"
                                                name="sortBy"
                                                value="model"
                                                v-model="sortBy"
                                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                            />
                                            <span class="ml-3 text-sm text-gray-700">MC Model</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input
                                                type="radio"
                                                name="sortBy"
                                                value="customer"
                                                v-model="sortBy"
                                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                            />
                                            <span class="ml-3 text-sm text-gray-700">Customer</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Record Status Section -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Record Status:</label>
                                    <div class="space-y-3">
                                        <label class="flex items-center">
                                            <input
                                                type="radio"
                                                name="recordStatus"
                                                value="active"
                                                v-model="recordStatus"
                                                class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500"
                                            />
                                            <span class="ml-3 text-sm text-gray-700">Active</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input
                                                type="radio"
                                                name="recordStatus"
                                                value="obsolete"
                                                v-model="recordStatus"
                                                class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500"
                                            />
                                            <span class="ml-3 text-sm text-gray-700">Obsolete</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input
                                                type="radio"
                                                name="recordStatus"
                                                value="all"
                                                v-model="recordStatus"
                                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                            />
                                            <span class="ml-3 text-sm text-gray-700">All</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Select by option -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Select by:</label>
                                    <div class="space-y-3">
                                        <label class="flex items-center">
                                            <input
                                                type="radio"
                                                name="selectBy"
                                                value="customer"
                                                v-model="selectBy"
                                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                            />
                                            <span class="ml-3 text-sm text-gray-700">Customer</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input
                                                type="radio"
                                                name="selectBy"
                                                value="mastercard"
                                                v-model="selectBy"
                                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                            />
                                            <span class="ml-3 text-sm text-gray-700">Master Card</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Selected Customer -->
                        <div v-if="selectBy === 'customer' && showOptions" class="bg-white rounded-xl shadow-lg mt-6 p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Select Customer</h3>
                            <div class="relative">
                                <select 
                                    v-model="selectedCustomerId"
                                    class="w-full pl-4 pr-10 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                >
                                    <option value="">All Customers</option>
                                    <option 
                                        v-for="customer in customers" 
                                        :key="customer.id" 
                                        :value="customer.id"
                                    >
                                        {{ customer.customer_name }}
                                    </option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Obsolate Modal -->
        <div v-if="showObsolateModal" class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-black bg-opacity-30 transition-opacity" @click="showObsolateModal = false"></div>

                <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <div>
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6 text-red-600"><circle cx="12" cy="12" r="10"></circle><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line></svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-5">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Obsolate Master Card
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to obsolate the master card? This will mark it as no longer in use.
                                </p>
                            </div>
                            <div class="mt-4">
                                <label for="obsolate-reason" class="block text-sm font-medium text-gray-700 text-left">
                                    Reason for Obsolating:
                                </label>
                                <textarea
                                    id="obsolate-reason"
                                    v-model="obsolateReason"
                                    rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Please provide a reason..."
                                ></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                        <button
                            type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:col-start-2 sm:text-sm"
                            @click="confirmObsolate"
                        >
                            Obsolate
                        </button>
                        <button
                            type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:col-start-1 sm:text-sm"
                            @click="showObsolateModal = false"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reactive Modal -->
        <div v-if="showReactiveModal" class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-black bg-opacity-30 transition-opacity" @click="showReactiveModal = false"></div>

                <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <div>
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6 text-green-600"><path d="M23 4v6h-6"></path><path d="M1 20v-6h6"></path><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-5">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Reactive Master Card
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to reactive this master card? This will make it active again.
                                </p>
                            </div>
                            <div class="mt-4">
                                <label for="reactive-reason" class="block text-sm font-medium text-gray-700 text-left">
                                    Reason for Reactivating:
                                </label>
                                <textarea
                                    id="reactive-reason"
                                    v-model="reactiveReason"
                                    rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Please provide a reason..."
                                ></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                        <button
                            type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:col-start-2 sm:text-sm"
                            @click="confirmReactive"
                        >
                            Reactivate
                        </button>
                        <button
                            type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:col-start-1 sm:text-sm"
                            @click="showReactiveModal = false"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

// Custom toast function
const useToast = () => {
    const showToast = (message, type = 'info', duration = 3000) => {
        // Create toast container if it doesn't exist
        let toastContainer = document.getElementById('toast-container');
        
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'fixed top-4 right-4 z-50 flex flex-col gap-2';
            document.body.appendChild(toastContainer);
        }
        
        // Create toast element
        const toast = document.createElement('div');
        toast.className = 'px-4 py-2 rounded-md shadow-lg transform transition-all duration-300 ease-in-out';
        
        // Add appropriate styling based on type
        switch(type) {
            case 'success':
                toast.classList.add('bg-green-500', 'text-white');
                break;
            case 'error':
                toast.classList.add('bg-red-500', 'text-white');
                break;
            case 'warning':
                toast.classList.add('bg-yellow-500', 'text-white');
                break;
            default:
                toast.classList.add('bg-blue-500', 'text-white');
        }
        
        // Add message content
        toast.textContent = message;
        
        // Add to container
        toastContainer.appendChild(toast);
        
        // Animation: slide in from right
        setTimeout(() => {
            toast.classList.add('translate-x-0');
            toast.classList.remove('translate-x-full');
        }, 10);
        
        // Remove after duration
        setTimeout(() => {
            toast.classList.add('opacity-0');
            setTimeout(() => {
                toastContainer.removeChild(toast);
                
                // Remove container if empty
                if (toastContainer.childNodes.length === 0) {
                    document.body.removeChild(toastContainer);
                }
            }, 300);
        }, duration);
    };
    
    return {
        showToast
    };
};

// Props
const props = defineProps({
    masterCards: Array,
    customers: Array
});

// State
const searchTerm = ref('');
const sortBy = ref('seq');
const recordStatus = ref('all');
const selectBy = ref('mastercard');
const selectedCustomerId = ref('');
const showOptions = ref(true);
const showObsolateModal = ref(false);
const showReactiveModal = ref(false);
const showDetailsModal = ref(false);
const obsolateReason = ref('');
const reactiveReason = ref('');
const selectedMasterCard = ref(null);
const masterCardsList = ref(props.masterCards || []);

// Toast
const { showToast } = useToast();

// Filtered master cards based on search, status and sorting
const filteredMasterCards = computed(() => {
    let filtered = [...masterCardsList.value];
    
    // Filter by search term
    if (searchTerm.value) {
        const searchTermLower = searchTerm.value.toLowerCase();
        filtered = filtered.filter(mc => 
            mc.mc_seq.toLowerCase().includes(searchTermLower) ||
            mc.mc_model.toLowerCase().includes(searchTermLower) ||
            mc.customer_name.toLowerCase().includes(searchTermLower)
        );
    }
    
    // Filter by status
    if (recordStatus.value !== 'all') {
        filtered = filtered.filter(mc => mc.status === recordStatus.value);
    }
    
    // Filter by customer
    if (selectBy.value === 'customer' && selectedCustomerId.value) {
        filtered = filtered.filter(mc => mc.customer_id === parseInt(selectedCustomerId.value));
    }
    
    // Sort results
    switch (sortBy.value) {
        case 'seq':
            filtered.sort((a, b) => a.mc_seq.localeCompare(b.mc_seq));
            break;
        case 'model':
            filtered.sort((a, b) => a.mc_model.localeCompare(b.mc_model));
            break;
        case 'customer':
            filtered.sort((a, b) => a.customer_name.localeCompare(b.customer_name));
            break;
    }
    
    return filtered;
});

// Watch for customer selection change
watch(selectedCustomerId, async (newCustomerId) => {
    if (newCustomerId && selectBy.value === 'customer') {
        try {
            const response = await axios.get(`/api/obsolate-reactive-mc/by-customer/${newCustomerId}`);
            masterCardsList.value = response.data;
        } catch (error) {
            console.error('Error fetching master cards:', error);
            showToast('Error loading master cards', 'error');
        }
    } else {
        // Reset to all master cards
        masterCardsList.value = props.masterCards || [];
    }
});

// Handle Obsolate
const handleObsolate = (mc) => {
    selectedMasterCard.value = mc;
    obsolateReason.value = '';
    showObsolateModal.value = true;
};

// Confirm Obsolate
const confirmObsolate = async () => {
    if (!obsolateReason.value.trim()) {
        showToast('Please provide a reason for obsolating this master card', 'error');
        return;
    }
    
    try {
        const response = await axios.post(`/api/obsolate-reactive-mc/obsolate/${selectedMasterCard.value.id}`, {
            reason: obsolateReason.value
        });
        
        // Update the master card in the list
        const index = masterCardsList.value.findIndex(mc => mc.id === selectedMasterCard.value.id);
        if (index !== -1) {
            masterCardsList.value[index] = response.data;
        }
        
        showToast('Master card has been marked as obsolete', 'success');
        showObsolateModal.value = false;
    } catch (error) {
        console.error('Error obsolating master card:', error);
        showToast('Error marking master card as obsolete', 'error');
    }
};

// Handle Reactive
const handleReactive = (mc) => {
    selectedMasterCard.value = mc;
    reactiveReason.value = '';
    showReactiveModal.value = true;
};

// Confirm Reactive
const confirmReactive = async () => {
    if (!reactiveReason.value.trim()) {
        showToast('Please provide a reason for reactivating this master card', 'error');
        return;
    }
    
    try {
        const response = await axios.post(`/api/obsolate-reactive-mc/reactive/${selectedMasterCard.value.id}`, {
            reason: reactiveReason.value
        });
        
        // Update the master card in the list
        const index = masterCardsList.value.findIndex(mc => mc.id === selectedMasterCard.value.id);
        if (index !== -1) {
            masterCardsList.value[index] = response.data;
        }
        
        showToast('Master card has been reactivated', 'success');
        showReactiveModal.value = false;
    } catch (error) {
        console.error('Error reactivating master card:', error);
        showToast('Error reactivating master card', 'error');
    }
};
</script>

<style scoped>
.pulse {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(0.95);
        box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.7);
    }
    
    70% {
        transform: scale(1);
        box-shadow: 0 0 0 10px rgba(79, 70, 229, 0);
    }
    
    100% {
        transform: scale(0.95);
        box-shadow: 0 0 0 0 rgba(79, 70, 229, 0);
    }
}
</style>
