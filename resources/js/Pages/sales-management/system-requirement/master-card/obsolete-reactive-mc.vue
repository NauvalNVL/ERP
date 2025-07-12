<!-- TEST EDIT -->
<!-- 
  Obsolete and Reactive Master Card Component
  This component handles bulk obsoleting and reactivating master cards in the system.
  Final polished version with enhanced UI, colors, responsiveness, and animations.
-->
<template>
    <AppLayout :header="'Obsolete & Reactive MC'">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 p-6 rounded-t-lg shadow-lg overflow-hidden relative">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20 animate-pulse-slow"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10 animate-pulse-slow animation-delay-500"></div>
            <div class="flex items-center">
                <div class="bg-gradient-to-br from-pink-500 to-purple-600 p-3 rounded-lg shadow-inner mr-4">
                    <i class="fas fa-sync-alt text-white text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-white text-shadow">Obsolete & Reactive Master Card</h2>
                    <p class="text-blue-100">Manage master card status in bulk based on specific criteria.</p>
                </div>
            </div>
        </div>

        <div class="rounded-b-lg shadow-lg p-6 mb-6 bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left Column - Main Content (The Form) -->
                <div class="lg:col-span-2 animate-fade-in-up">
                    <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-indigo-500 transition-all duration-300 hover:shadow-xl relative overflow-hidden">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-50 rounded-full opacity-50"></div>
                        <div class="absolute -bottom-12 -left-12 w-36 h-36 bg-purple-50 rounded-full opacity-50"></div>
                        
                        <div class="flex items-center mb-6 pb-3 border-b border-gray-200 relative z-10">
                            <div class="p-2 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg mr-4 shadow-md">
                                <i class="fas fa-edit text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800">Process Bulk Action</h3>
                        </div>
                            
                        <!-- Form for Bulk Action -->
                        <div class="space-y-6 relative z-10">
                            <!-- AC# -->
                            <div>
                                <label for="ac" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <span class="flex items-center justify-center h-6 w-6 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white mr-3 shadow-md transition-transform group-hover:scale-110">
                                        <i class="fas fa-user-circle text-xs"></i>
                                    </span>
                                    AC#:
                                </label>
                                <div class="relative flex group">
                                    <input type="text" id="ac" v-model="form.ac" placeholder="Customer Account" class="input-field">
                                    <button @click="openCustomerLookup" type="button" class="inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 rounded-r-md transition-all duration-300 bg-gradient-to-r from-indigo-500 to-purple-500 text-white hover:from-indigo-600 hover:to-purple-600 shadow-sm hover:shadow-md transform hover:-translate-y-px">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- Product Code -->
                            <div>
                                <label for="product_code" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                     <span class="flex items-center justify-center h-6 w-6 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 text-white mr-3 shadow-md transition-transform group-hover:scale-110">
                                        <i class="fas fa-box text-xs"></i>
                                    </span>
                                    Product Code:
                                </label>
                                <div class="relative flex group">
                                    <input type="text" id="product_code" v-model="form.product_code" placeholder="Product Code" class="input-field">
                                    <button @click="openProductLookup" type="button" class="inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 rounded-r-md transition-all duration-300 bg-gradient-to-r from-blue-500 to-cyan-500 text-white hover:from-blue-600 hover:to-cyan-600 shadow-sm hover:shadow-md transform hover:-translate-y-px">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- MCS# Range -->
                            <div>
                                <label for="mcs_from" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                     <span class="flex items-center justify-center h-6 w-6 rounded-full bg-gradient-to-br from-pink-500 to-orange-500 text-white mr-3 shadow-md transition-transform group-hover:scale-110">
                                        <i class="fas fa-barcode text-xs"></i>
                                    </span>
                                    MCS# Range:
                                </label>
                                <div class="flex items-center gap-4">
                                    <div class="relative flex group flex-1">
                                        <input type="text" id="mcs_from" v-model="form.mcs_from" placeholder="From Sequence" class="input-field">
                                        <button @click="openMcsLookup('from')" type="button" class="inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 rounded-r-md transition-all duration-300 bg-gradient-to-r from-pink-500 to-orange-500 text-white hover:from-pink-600 hover:to-orange-600 shadow-sm hover:shadow-md transform hover:-translate-y-px">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                    <span class="text-gray-500 font-medium">TO</span>
                                    <div class="relative flex group flex-1">
                                        <input type="text" id="mcs_to" v-model="form.mcs_to" placeholder="To Sequence" class="input-field">
                                        <button @click="openMcsLookup('to')" type="button" class="inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 rounded-r-md transition-all duration-300 bg-gradient-to-r from-pink-500 to-orange-500 text-white hover:from-pink-600 hover:to-orange-600 shadow-sm hover:shadow-md transform hover:-translate-y-px">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Divider -->
                            <div class="border-t border-gray-200 !my-6"></div>

                            <!-- Action To Perform -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <span class="flex items-center justify-center h-6 w-6 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-white mr-3 shadow-md transition-transform group-hover:scale-110">
                                        <i class="fas fa-bolt text-xs"></i>
                                    </span>
                                    Action To Perform
                                </label>
                                <div class="flex flex-col sm:flex-row gap-4 mt-2">
                                    <label class="action-radio" :class="{ 'action-radio-obsolete-active': form.action === 'obsolete' }">
                                        <input type="radio" v-model="form.action" value="obsolete" name="action-radio" class="hidden">
                                        <i class="fas fa-ban w-6 text-xl" :class="form.action === 'obsolete' ? 'text-red-500' : 'text-gray-400'"></i>
                                        <span class="font-bold" :class="form.action === 'obsolete' ? 'text-red-800' : 'text-gray-700'">Obsolete Active</span>
                                    </label>
                                    <label class="action-radio" :class="{ 'action-radio-reactivate-active': form.action === 'reactivate' }">
                                        <input type="radio" v-model="form.action" value="reactivate" name="action-radio" class="hidden">
                                        <i class="fas fa-check-circle w-6 text-xl" :class="form.action === 'reactivate' ? 'text-green-500' : 'text-gray-400'"></i>
                                        <span class="font-bold" :class="form.action === 'reactivate' ? 'text-green-800' : 'text-gray-700'">Reactivate Obsolete</span>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Reason -->
                            <div>
                                <label for="reason" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <span class="flex items-center justify-center h-6 w-6 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 text-white mr-3 shadow-md transition-transform group-hover:scale-110">
                                        <i class="fas fa-comment-dots text-xs"></i>
                                    </span>
                                    Provide Reason
                                </label>
                                <textarea id="reason" v-model="form.reason" rows="3" placeholder="A reason must be provided..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 shadow-sm focus:shadow-lg transition-shadow"></textarea>
                            </div>

                            <!-- Process Button -->
                            <div class="pt-6 text-center border-t border-gray-200">
                                <button @click="processSelections" class="process-button group">
                                    <span class="shimmer-effect"></span>
                                    <i class="fas fa-cogs mr-3 text-xl group-hover:animate-spin"></i>
                                    Process Selections
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Quick Info Panel -->
                <div class="lg:col-span-1 animate-fade-in-up animation-delay-300">
                    <div class="sticky top-24">
                        <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-teal-500">
                            <div class="flex items-center mb-4 pb-3 border-b border-gray-200">
                                 <div class="p-2 bg-gradient-to-r from-teal-500 to-green-500 rounded-lg mr-4 shadow-md">
                                    <i class="fas fa-info-circle text-white"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Instructions</h3>
                            </div>
                            <ul class="text-sm text-gray-600 space-y-4">
                                <li class="flex items-start">
                                    <i class="fas fa-filter text-teal-500 w-4 mt-1 mr-3 flex-shrink-0"></i>
                                    <span>Fill in one or more criteria to filter the master cards you want to update.</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-bolt text-teal-500 w-4 mt-1 mr-3 flex-shrink-0"></i>
                                    <span>Choose whether to <span class="font-semibold text-red-600">Obsolete</span> or <span class="font-semibold text-green-600">Reactivate</span>.</span>
                                </li>
                                 <li class="flex items-start">
                                    <i class="fas fa-comment-dots text-teal-500 w-4 mt-1 mr-3 flex-shrink-0"></i>
                                    <span>A reason for the bulk action is mandatory.</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-cogs text-teal-500 w-4 mt-1 mr-3 flex-shrink-0"></i>
                                    <span>Click 'Process Selections' to apply the changes. This action is irreversible.</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <LookupModal 
            :show="lookup.show"
            :title="lookup.title"
            :items="lookup.items"
            :headers="lookup.headers"
            :headerClass="lookup.headerClass"
            :headerIconClass="lookup.headerIconClass"
            :headerIconBgClass="lookup.headerIconBgClass"
            :filterTag="lookup.filterTag"
            :showMoreOptionsButton="lookup.showMoreOptionsButton"
            :showZoomButton="lookup.showZoomButton"
            @close="lookup.show = false"
            @select="handleLookupSelection"
            @moreOptions="handleCustomerMoreOptions"
            @zoom="handleCustomerZoom"
        />

        <!-- Customer Account Options Modal -->
        <div v-if="showCustomerAccountOptionsModal" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto">
            <!-- Modal backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="showCustomerAccountOptionsModal = false"></div>
            
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-xl w-96 mx-auto max-w-lg z-10 transform transition-all">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-t-lg relative overflow-hidden">
                    <div class="absolute -top-8 -left-8 w-16 h-16 bg-white opacity-10 rounded-full"></div>
                    <div class="absolute -bottom-8 -right-8 w-16 h-16 bg-white opacity-10 rounded-full"></div>
                    
                    <h3 class="text-xl font-semibold flex items-center relative z-10">
                        <span class="inline-flex items-center justify-center w-8 h-8 bg-white bg-opacity-20 rounded-full mr-3 shadow-inner">
                            <i class="fas fa-filter text-white"></i>
                        </span>
                        Options
                    </h3>
                    <button type="button" @click="showCustomerAccountOptionsModal = false" 
                        class="text-white hover:text-gray-200 focus:outline-none transition-transform hover:scale-110 relative z-10 bg-red-500 bg-opacity-30 hover:bg-opacity-50 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="p-6 space-y-6">
                    <!-- Sort By Options -->
                    <div class="bg-white border border-gray-300 rounded-md shadow-sm p-4">
                        <h4 class="font-semibold mb-3 text-gray-700 flex items-center">
                            <i class="fas fa-sort mr-2 text-blue-500"></i>Sort by:
                        </h4>
                        <div class="space-y-2 ml-2">
                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md">
                                <input type="radio" v-model="customerOptionSortBy" value="customer_code" class="form-radio h-4 w-4 text-blue-600">
                                <span class="ml-2 text-gray-700">Customer Code</span>
                            </label>
                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md">
                                <input type="radio" v-model="customerOptionSortBy" value="customer_name" class="form-radio h-4 w-4 text-blue-600">
                                <span class="ml-2 text-gray-700">Customer Name</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Record Status Options -->
                    <div class="bg-white border border-gray-300 rounded-md shadow-sm p-4">
                        <h4 class="font-semibold mb-3 text-gray-700 flex items-center">
                            <i class="fas fa-tag mr-2 text-blue-500"></i>Record Status:
                        </h4>
                        <div class="flex items-center space-x-6 ml-2">
                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md">
                                <input type="checkbox" v-model="customerOptionRecordStatus.active" class="form-checkbox h-4 w-4 text-blue-600 rounded">
                                <span class="ml-2 text-gray-700">Active</span>
                            </label>
                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md">
                                <input type="checkbox" v-model="customerOptionRecordStatus.obsolete" class="form-checkbox h-4 w-4 text-blue-600 rounded">
                                <span class="ml-2 text-gray-700">Obsolete</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-center space-x-4 p-4 border-t border-gray-200 bg-gray-50 rounded-b-lg">
                    <button 
                        @click="applyCustomerOptions" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-8 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        <i class="fas fa-check mr-2"></i>OK
                    </button>
                    <button 
                        @click="showCustomerAccountOptionsModal = false" 
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-8 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2"
                    >
                        <i class="fas fa-times mr-2"></i>Exit
                    </button>
                </div>
            </div>
        </div>

        <!-- MCS Options Modal -->
        <div v-if="showMcsOptionsModal" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto">
            <!-- Modal backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="showMcsOptionsModal = false"></div>
            
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-xl w-96 mx-auto max-w-lg z-10 transform transition-all">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-green-500 to-teal-500 text-white rounded-t-lg relative overflow-hidden">
                    <div class="absolute -top-8 -left-8 w-16 h-16 bg-white opacity-10 rounded-full"></div>
                    <div class="absolute -bottom-8 -right-8 w-16 h-16 bg-white opacity-10 rounded-full"></div>
                    
                    <h3 class="text-xl font-semibold flex items-center relative z-10">
                        <span class="inline-flex items-center justify-center w-8 h-8 bg-white bg-opacity-20 rounded-full mr-3 shadow-inner">
                            <i class="fas fa-filter text-white"></i>
                        </span>
                        Options
                    </h3>
                    <button type="button" @click="showMcsOptionsModal = false" 
                        class="text-white hover:text-gray-200 focus:outline-none transition-transform hover:scale-110 relative z-10 bg-red-500 bg-opacity-30 hover:bg-opacity-50 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="p-6 space-y-6">
                    <!-- Sort By Options -->
                    <div class="bg-white border border-gray-300 rounded-md shadow-sm p-4">
                        <h4 class="font-semibold mb-3 text-gray-700 flex items-center">
                            <i class="fas fa-sort mr-2 text-blue-500"></i>Sort by:
                        </h4>
                        <div class="space-y-2 ml-2">
                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md">
                                <input type="radio" v-model="mcsOptionSortBy" value="mc_seq" class="form-radio h-4 w-4 text-blue-600">
                                <span class="ml-2 text-gray-700">MC Seq#</span>
                            </label>
                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md">
                                <input type="radio" v-model="mcsOptionSortBy" value="mc_model" class="form-radio h-4 w-4 text-blue-600">
                                <span class="ml-2 text-gray-700">MC Model</span>
                            </label>
                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md">
                                <input type="radio" v-model="mcsOptionSortBy" value="pd_part_no" class="form-radio h-4 w-4 text-blue-600">
                                <span class="ml-2 text-gray-700">MC PD Part#</span>
                            </label>
                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md">
                                <input type="radio" v-model="mcsOptionSortBy" value="pd_ed" class="form-radio h-4 w-4 text-blue-600">
                                <span class="ml-2 text-gray-700">MC PD ED</span>
                            </label>
                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md">
                                <input type="radio" v-model="mcsOptionSortBy" value="pd_id" class="form-radio h-4 w-4 text-blue-600">
                                <span class="ml-2 text-gray-700">MC PD ID</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Sort Order Options -->
                    <div class="bg-white border border-gray-300 rounded-md shadow-sm p-4">
                        <h4 class="font-semibold mb-3 text-gray-700 flex items-center">
                            <i class="fas fa-sort-amount-up mr-2 text-blue-500"></i>Sort Order:
                        </h4>
                        <div class="space-y-2 ml-2">
                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md">
                                <input type="radio" v-model="mcsOptionSortOrder" value="Ascending" class="form-radio h-4 w-4 text-blue-600">
                                <span class="ml-2 text-gray-700">Ascending</span>
                            </label>
                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md">
                                <input type="radio" v-model="mcsOptionSortOrder" value="Descending" class="form-radio h-4 w-4 text-blue-600">
                                <span class="ml-2 text-gray-700">Descending</span>
                            </label>
                        </div>
                    </div>

                    <!-- Record Status Options -->
                    <div class="bg-white border border-gray-300 rounded-md shadow-sm p-4">
                        <h4 class="font-semibold mb-3 text-gray-700 flex items-center">
                            <i class="fas fa-tag mr-2 text-blue-500"></i>Record Status:
                        </h4>
                        <div class="flex items-center space-x-6 ml-2">
                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md">
                                <input type="checkbox" v-model="mcsOptionStatus.active" class="form-checkbox h-4 w-4 text-blue-600 rounded">
                                <span class="ml-2 text-gray-700">Active</span>
                            </label>
                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md">
                                <input type="checkbox" v-model="mcsOptionStatus.obsolete" class="form-checkbox h-4 w-4 text-blue-600 rounded">
                                <span class="ml-2 text-gray-700">Obsolete</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-center space-x-4 p-4 border-t border-gray-200 bg-gray-50 rounded-b-lg">
                    <button 
                        @click="applyMcsOptions" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-8 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        <i class="fas fa-check mr-2"></i>OK
                    </button>
                    <button 
                        @click="showMcsOptionsModal = false" 
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-8 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2"
                    >
                        <i class="fas fa-times mr-2"></i>Exit
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useToast } from '@/Composables/useToast';
import LookupModal from '@/Components/obsolete-reactive-modal.vue';
import axios from 'axios';

const { showToast } = useToast();

const form = ref({
    ac: '',
    mcs_from: '',
    mcs_to: '',
    product_code: '',
    action: 'obsolete', // Default action
    reason: ''
});

// New state variables for Customer Account Options Modal
const showCustomerAccountOptionsModal = ref(false);
const customerOptionSortBy = ref('customer_code'); // Default sort by Customer Code
const customerOptionRecordStatus = ref({
    active: true,
    obsolete: true
});

// --- Lookup Modal State and Data ---
const lookup = ref({
    show: false,
    title: '',
    items: [],
    headers: [],
    type: '' // 'customer', 'product', 'mcs_from', 'mcs_to'
});

// Dummy data for lookups
const customers = ref([
    { id: 1, customer_code: '000211-08', customer_name: 'ABDULLAH, BPK', salesperson: 'S111', acType: 'Local', currency: 'IDR', status: 'Active' },
    { id: 2, customer_code: '000680-06', customer_name: 'ACEP SUNANDAR, BPK', salesperson: 'S140', acType: 'Local', currency: 'IDR', status: 'Active' },
    { id: 3, customer_code: '000585-01', customer_name: 'ACHMAD JAMAL', salesperson: 'S102', acType: 'Local', currency: 'IDR', status: 'Active' },
    { id: 4, customer_code: '000283', customer_name: 'ACOSTA SUPER FOOD, PT', salesperson: 'S143', acType: 'Local', currency: 'IDR', status: 'Active' },
    { id: 5, customer_code: '000903', customer_name: 'ADHITYA SERAYAKORITA, PT', salesperson: 'S103', acType: 'Local', currency: 'IDR', status: 'Active' },
    { id: 6, customer_code: '000212-08', customer_name: 'BAMBANG, BPK', salesperson: 'S112', acType: 'Export', currency: 'USD', status: 'Obsolete' },
    { id: 7, customer_code: '000681-06', customer_name: 'BUDI, BPK', salesperson: 'S141', acType: 'Local', currency: 'IDR', status: 'Active' },
    { id: 8, customer_code: '000586-01', customer_name: 'CITRA, IBU', salesperson: 'S103', acType: 'Local', currency: 'IDR', status: 'Active' },
    { id: 9, customer_code: '000284', customer_name: 'DEDI, BPK', salesperson: 'S144', acType: 'Export', currency: 'USD', status: 'Active' },
    { id: 10, customer_code: '000904', customer_name: 'EKA, IBU', salesperson: 'S104', acType: 'Local', currency: 'IDR', status: 'Obsolete' },
]);
const products = ref([
    { id: 1, product_code: '001', description: 'BOX', group: 'B', category: '1' },
    { id: 2, product_code: '002', description: 'SHEET BOARD', group: 'S', category: '5' },
    { id: 3, product_code: '003', description: 'BUTT ROLL', group: 'R', category: '3' },
    { id: 4, product_code: '004', description: 'PENJUALAN WASTE', group: 'OT', category: 'X' },
    { id: 5, product_code: '005', description: 'PENJUALAN LAIN LAIN PCS', group: 'OT', category: 'X' },
    { id: 6, product_code: '006', description: 'CONEST', group: 'R', category: '3' },
    { id: 7, product_code: '007', description: 'ROLL', group: 'R', category: '3' },
    { id: 8, product_code: '008', description: 'PENDAPATAN LAIN LAIN', group: 'OT', category: 'X' },
    { id: 9, product_code: '009', description: 'PENJUALAN LAIN LAIN KG', group: 'OT', category: '3' },
    { id: 10, product_code: '010', description: 'SINGLE FACER ROLL', group: 'S', category: '2' },
    { id: 11, product_code: '011', description: 'SINGLE FACER KG', group: 'S', category: '3' },
    { id: 12, product_code: '012', description: 'SINGLE FACER SHEET', group: 'S', category: '4' },
    { id: 13, product_code: '013', description: 'PENDAPATAN LAIN - LAIN', group: 'OT', category: 'X' },
    { id: 14, product_code: '014', description: 'SEWA TRUCK', group: 'OT', category: 'X' },
    { id: 15, product_code: '015', description: 'CORE PLUG', group: 'OT', category: 'X' },
    { id: 16, product_code: '016', description: 'PAPER TUBE', group: 'OT', category: 'X' },
    { id: 17, product_code: '017', description: 'OFFSET', group: 'OF', category: '1' },
    { id: 18, product_code: '018', description: 'CETAK OFFSET', group: 'OF', category: '1' },
    { id: 19, product_code: '019', description: 'DIGITAL PRINT', group: 'OF', category: '1' },
    { id: 20, product_code: '500', description: 'SEWA TRUCK TRAILER', group: 'OT', category: 'X' },
]);
const masterCards = ref([
    { id: 1, mc_seq: 'MC-2023-001', mc_model: 'Model-A', pd_part_no: 'PD-P001', pd_ed: 'PD-E001', pd_id: 'PD-I001', status: 'Active' },
    { id: 2, mc_seq: 'MC-2023-002', mc_model: 'Model-B', pd_part_no: 'PD-P002', pd_ed: 'PD-E002', pd_id: 'PD-I002', status: 'Active' },
    { id: 3, mc_seq: 'MC-2023-003', mc_model: 'Model-C', pd_part_no: 'PD-P003', pd_ed: 'PD-E003', pd_id: 'PD-I003', status: 'Obsolete' },
    { id: 4, mc_seq: 'MC-2023-004', mc_model: 'Model-D', pd_part_no: 'PD-P004', pd_ed: 'PD-E004', pd_id: 'PD-I004', status: 'Active' },
]);

// New state variables for MCS Options Modal
const showMcsOptionsModal = ref(false);
const mcsOptionSortBy = ref('mc_seq');
const mcsOptionSortOrder = ref('Ascending');
const mcsOptionStatus = ref({
    active: true,
    obsolete: true
});
const currentMcsLookupType = ref(''); // 'mcs_from' or 'mcs_to'

// Functions to open different lookups
const openCustomerLookup = () => {
    showCustomerAccountOptionsModal.value = true; // Open options modal first
};

const applyCustomerOptions = () => {
    let filteredCustomers = customers.value.filter(customer => {
        const isActive = customerOptionRecordStatus.value.active && customer.status === 'Active';
        const isObsolete = customerOptionRecordStatus.value.obsolete && customer.status === 'Obsolete';
        return isActive || isObsolete;
    });

    filteredCustomers.sort((a, b) => {
        if (customerOptionSortBy.value === 'customer_code') {
            return a.customer_code.localeCompare(b.customer_code);
        } else {
            return a.customer_name.localeCompare(b.customer_name);
        }
    });

    showCustomerAccountOptionsModal.value = false; // Close options modal

    lookup.value = {
        show: true,
        title: 'Customer Account Table',
        items: filteredCustomers,
        headers: [
            { key: 'customer_code', label: 'Customer Code' },
            { key: 'customer_name', label: 'Customer Name' },
            { key: 'salesperson', label: 'S/Person' },
            { key: 'acType', label: 'AC Type' },
            { key: 'currency', label: 'Currency' },
            { key: 'status', label: 'Status' },
        ],
        type: 'customer',
        headerClass: 'bg-gradient-to-r from-purple-600 to-indigo-600',
        headerIconClass: 'fas fa-th',
        headerIconBgClass: 'bg-white bg-opacity-20',
        filterTag: customerOptionRecordStatus.value.active && !customerOptionRecordStatus.value.obsolete ? 'Active Records Only' : '',
        showMoreOptionsButton: true,
        showZoomButton: true
    };
};

const handleCustomerMoreOptions = () => {
    showCustomerAccountOptionsModal.value = true; // Re-open the options modal
    lookup.value.show = false; // Close the lookup modal
};

const handleCustomerZoom = () => {
    // Implement zoom logic if needed
    showToast('Zoom functionality for Customer Account is not yet implemented.', 'info');
};
const openProductLookup = () => {
    lookup.value = {
        show: true,
        title: 'Product Table',
        items: products.value,
        headers: [
            { key: 'product_code', label: 'Code' },
            { key: 'description', label: 'Description' },
            { key: 'group', label: 'Group#' },
            { key: 'category', label: 'Category' },
        ],
        type: 'product'
    };
};
const openMcsLookup = (target) => {
    currentMcsLookupType.value = target;
    showMcsOptionsModal.value = true; // Open MCS options modal first
};

const applyMcsOptions = () => {
    let filteredMcs = masterCards.value.filter(mc => {
        const isActive = mcsOptionStatus.value.active && mc.status === 'Active';
        const isObsolete = mcsOptionStatus.value.obsolete && mc.status === 'Obsolete';
        return isActive || isObsolete;
    });

    filteredMcs.sort((a, b) => {
        let compareA, compareB;

        switch (mcsOptionSortBy.value) {
            case 'mc_seq':
                compareA = a.mc_seq;
                compareB = b.mc_seq;
                break;
            case 'mc_model':
                compareA = a.mc_model;
                compareB = b.mc_model;
                break;
            case 'pd_part_no':
                compareA = a.pd_part_no;
                compareB = b.pd_part_no;
                break;
            case 'pd_ed':
                compareA = a.pd_ed;
                compareB = b.pd_ed;
                break;
            case 'pd_id':
                compareA = a.pd_id;
                compareB = b.pd_id;
                break;
        }

        if (mcsOptionSortOrder.value === 'Ascending') {
            return String(compareA).localeCompare(String(compareB));
        } else {
            return String(compareB).localeCompare(String(compareA));
        }
    });

    showMcsOptionsModal.value = false; // Close MCS options modal

    let headers = [];
    switch (mcsOptionSortBy.value) {
        case 'mc_seq':
            headers = [{ key: 'mc_seq', label: 'MC Seq#' }];
            break;
        case 'mc_model':
            headers = [{ key: 'mc_model', label: 'MC Model' }];
            break;
        case 'pd_part_no':
            headers = [{ key: 'pd_part_no', label: 'MC PD Part#' }];
            break;
        case 'pd_ed':
            headers = [{ key: 'pd_ed', label: 'MC PD ED' }];
            break;
        case 'pd_id':
            headers = [{ key: 'pd_id', label: 'MC PD ID' }];
            break;
    }

    lookup.value = {
        show: true,
        title: 'Master Card Sequence',
        items: filteredMcs,
        headers: headers,
        type: currentMcsLookupType.value
    };
};

// Function to handle selection from modal
const handleLookupSelection = (selectedItem) => {
    switch (lookup.value.type) {
        case 'customer':
            form.value.ac = selectedItem.customer_code;
            break;
        case 'product':
            form.value.product_code = selectedItem.product_code;
            break;
        case 'mcs_from':
            form.value.mcs_from = selectedItem.mc_seq;
            break;
        case 'mcs_to':
            form.value.mcs_to = selectedItem.mc_seq;
            break;
    }
    lookup.value.show = false;
};
// --- End Lookup Modal ---

const processSelections = async () => {
    if (!form.value.reason || form.value.reason.trim() === '') {
        showToast('Reason is required.', 'error');
        const reasonEl = document.getElementById('reason');
        if (reasonEl) {
            reasonEl.classList.add('border-red-500', 'ring-2', 'ring-red-200');
            setTimeout(() => {
                reasonEl.classList.remove('border-red-500', 'ring-2', 'ring-red-200');
            }, 2500);
        }
        return;
    }

    const endpoint = form.value.action === 'obsolete' 
        ? '/api/obsolate-reactive-mc/bulk-obsolete' 
        : '/api/obsolate-reactive-mc/bulk-reactive';

    try {
        const response = await axios.post(endpoint, form.value);
        showToast(response.data.message || 'Process completed successfully!', 'success');
        // Reset the form after successful submission
        form.value.ac = '';
        form.value.mcs_from = '';
        form.value.mcs_to = '';
        form.value.product_code = '';
        form.value.reason = '';

    } catch (error) {
        const errorMessage = error.response?.data?.message || 'An error occurred during bulk processing.';
        console.error(`Error during bulk ${form.value.action}:`, error);
        showToast(errorMessage, 'error');
    }
};
</script>

<style>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');

.text-shadow {
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.input-field {
    @apply flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 group-hover:border-indigo-400 shadow-sm focus:shadow-md;
}
.action-radio {
    @apply flex-1 p-4 border-2 rounded-xl cursor-pointer transition-all duration-300 bg-white flex items-center gap-4 hover:border-gray-400;
}
.action-radio-obsolete-active {
    @apply border-red-400 bg-red-50 shadow-lg scale-105;
}
.action-radio-reactivate-active {
    @apply border-green-400 bg-green-50 shadow-lg scale-105;
}
.process-button {
    @apply w-full md:w-auto bg-gradient-to-r from-green-500 to-blue-500 text-white font-bold py-3 px-12 rounded-lg shadow-lg hover:shadow-2xl transform hover:scale-105 transition-all duration-300 flex items-center justify-center mx-auto relative overflow-hidden;
}

.form-radio {
  @apply appearance-none w-5 h-5 border-2 border-gray-300 rounded-full transition-all duration-200;
}
.action-card:hover .form-radio { @apply border-current; }
.form-radio:checked { @apply border-8 border-current; }

/* Animations */
@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.animate-fade-in-up {
    animation: fade-in-up 0.6s ease-out forwards;
}

@keyframes pulse-slow {
    0%, 100% { transform: scale(1); opacity: 0.05; }
    50% { transform: scale(1.1); opacity: 0.08; }
}
.animate-pulse-slow {
    animation: pulse-slow 5s infinite;
}
.animation-delay-300 { animation-delay: 0.3s; }
.animation-delay-500 { animation-delay: 0.5s; }

.shimmer-effect {
    @apply absolute top-0 -left-[150%] h-full w-[50%] skew-x-[-25deg] bg-white/20;
    animation: shimmer 2.5s infinite;
}
@keyframes shimmer {
    100% {
        left: 150%;
    }
}
</style>
