<template>
    <AppLayout :header="'Update MC'">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
            <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
                <i class="fas fa-id-card mr-3"></i> Update Master Card
            </h2>
            <p class="text-cyan-100">Manage and update master card information in the system</p>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Main Content -->
                <div class="lg:col-span-2">
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
                        <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                            <div class="p-2 bg-blue-500 rounded-lg mr-3">
                                <i class="fas fa-edit text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800">Master Card Management</h3>
                        </div>
                        
                        <!-- Header with action buttons -->
                        <div class="flex items-center space-x-2 mb-6">
                            <button 
                                @click="closeRecord"
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px"
                                title="Close"
                            >
                                <i class="fas fa-power-off"></i>
                            </button>
                            <button 
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px"
                                title="Next Record"
                            >
                                <i class="fas fa-arrow-right"></i>
                            </button>
                            <button 
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px"
                                title="Previous Record"
                            >
                                <i class="fas fa-arrow-left"></i>
                            </button>
                            <button 
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px"
                                title="Search"
                            >
                                <i class="fas fa-search"></i>
                            </button>
                            <button 
                                @click="saveRecord"
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px"
                                title="Save"
                            >
                                <i class="fas fa-save"></i>
                            </button>
                        </div>

                        <!-- Form content -->
                        <form @submit.prevent="saveRecord" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="ac" class="block text-sm font-medium text-gray-700 mb-1">AC#:</label>
                                    <div class="relative flex">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                            <i class="fas fa-hashtag"></i>
                                        </span>
                                        <input 
                                            type="text" 
                                            id="ac" 
                                            v-model="form.ac"
                                            class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                        />
                                        <button 
                                            type="button"
                                            @click="searchAc"
                                            class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md transition-colors transform active:translate-y-px"
                                        >
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="flex items-end">
                                    <button 
                                        type="button"
                                        @click="selectRecord"
                                        class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px"
                                    >
                                        <i class="fas fa-check-circle mr-2"></i>
                                        <span>Record: Select</span>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label for="mcs" class="block text-sm font-medium text-gray-700 mb-1">MCS#:</label>
                                <div class="relative flex">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                        <i class="fas fa-barcode"></i>
                                    </span>
                                    <input 
                                        type="text" 
                                        id="mcs" 
                                        v-model="form.mcs"
                                        class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                    />
                                    <button 
                                        type="button"
                                        @click="searchMcs"
                                        class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md transition-colors transform active:translate-y-px"
                                    >
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Additional fields can be added here as needed -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Customer Name:</label>
                                    <input 
                                        type="text" 
                                        id="customer_name" 
                                        v-model="form.customer_name" 
                                        readonly
                                        class="block w-full px-3 py-2 rounded-md border border-gray-300 bg-gray-50"
                                    />
                                </div>
                                <div>
                                    <label for="product_code" class="block text-sm font-medium text-gray-700 mb-1">Product Code:</label>
                                    <input 
                                        type="text" 
                                        id="product_code" 
                                        v-model="form.product_code" 
                                        class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                    />
                                </div>
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status:</label>
                                    <select 
                                        id="status" 
                                        v-model="form.status" 
                                        class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                    >
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="pending">Pending</option>
                                    </select>
                                </div>
                            </div>

                            <div v-if="recordSelected" class="mt-6 bg-green-100 p-4 rounded">
                                <p class="text-sm font-medium text-green-800">Record loaded successfully</p>
                                <p class="text-xs text-green-700 mt-1">You can now edit the master card details</p>
                            </div>
                        </form>
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
                            <h3 class="text-lg font-semibold text-gray-800">Master Card Info</h3>
                        </div>

                        <div class="space-y-4">
                            <div class="p-4 bg-teal-50 rounded-lg">
                                <h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2">Instructions</h4>
                                <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                                    <li>Enter AC# or MCS# to search for existing records</li>
                                    <li>Click "Record: Select" to load the record details</li>
                                    <li>Update information as needed</li>
                                    <li>Save changes with the save button</li>
                                    <li>Red button will close the form without saving</li>
                                </ul>
                            </div>

                            <div class="p-4 bg-blue-50 rounded-lg">
                                <h4 class="text-sm font-semibold text-blue-800 uppercase tracking-wider mb-2">Recent Activity</h4>
                                <div class="space-y-2">
                                    <p class="text-xs text-gray-600">No recent activity found</p>
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

                        <div class="space-y-2">
                            <a href="#" class="block p-2 hover:bg-gray-50 rounded-md text-sm text-gray-700 hover:text-blue-600 transition-colors">
                                <i class="fas fa-check-circle mr-2 text-green-500"></i> Approve Master Card
                            </a>
                            <a href="#" class="block p-2 hover:bg-gray-50 rounded-md text-sm text-gray-700 hover:text-blue-600 transition-colors">
                                <i class="fas fa-print mr-2 text-blue-500"></i> View & Print MC
                            </a>
                            <a href="#" class="block p-2 hover:bg-gray-50 rounded-md text-sm text-gray-700 hover:text-blue-600 transition-colors">
                                <i class="fas fa-history mr-2 text-amber-500"></i> MC Maintenance Log
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

// Form data
const form = ref({
    ac: '',
    mcs: '',
    customer_name: '',
    product_code: '',
    status: 'active'
});

// UI state
const recordSelected = ref(false);

// Actions
const searchAc = async () => {
    try {
        const response = await axios.post('/api/update-mc/search-ac', {
            ac: form.value.ac
        });
        console.log(response.data);
        // Handle the response as needed
        // In a real implementation, you'd populate the form with returned data
        form.value.customer_name = 'Sample Customer';
        recordSelected.value = true;
    } catch (error) {
        console.error('Error searching AC:', error);
    }
};

const searchMcs = async () => {
    try {
        const response = await axios.post('/api/update-mc/search-mcs', {
            mcs: form.value.mcs
        });
        console.log(response.data);
        // Handle the response as needed
        // In a real implementation, you'd populate the form with returned data
        form.value.customer_name = 'Sample Customer';
        recordSelected.value = true;
    } catch (error) {
        console.error('Error searching MCS:', error);
    }
};

const selectRecord = () => {
    // Add logic to select a record
    console.log('Record select clicked');
    recordSelected.value = true;
    form.value.customer_name = 'Selected Customer';
};

const saveRecord = () => {
    // Add logic to save the record
    console.log('Save record clicked');
    // Here you would typically make an API call to save the data
};

const closeRecord = () => {
    // Add logic to close the record
    console.log('Close record clicked');
    // Here you would typically reset the form or navigate away
};
</script>

<style scoped>
/* Add any component-specific styles here */
</style>
