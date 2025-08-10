<template>
    <AppLayout :header="'Update MC'">
        <!-- Header Section with animated elements -->
        <div class="bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 p-6 rounded-t-lg shadow-lg overflow-hidden relative">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10"></div>
            <div class="absolute bottom-0 right-0 w-32 h-32 bg-yellow-400 opacity-5 rounded-full translate-y-10 translate-x-10"></div>
            
            <div class="flex items-center">
                <div class="bg-gradient-to-br from-pink-500 to-purple-600 p-3 rounded-lg shadow-inner flex items-center justify-center relative overflow-hidden mr-4">
                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-yellow-300 opacity-30 rounded-full animate-ping-slow"></div>
                    <div class="absolute -bottom-1 -left-1 w-4 h-4 bg-blue-300 opacity-30 rounded-full animate-ping-slow animation-delay-500"></div>
                    <i class="fas fa-id-card text-white text-2xl z-10"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-1 text-shadow">Update Master Card</h2>
                    <p class="text-blue-100 max-w-2xl">Manage and update master card information in the system</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6 bg-gradient-to-br from-white to-indigo-50">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Main Content -->
                <div class="lg:col-span-2">
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-indigo-500 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 relative overflow-hidden">
                        <div class="absolute -top-20 -right-20 w-40 h-40 bg-indigo-50 rounded-full opacity-20"></div>
                        <div class="absolute -bottom-8 -left-8 w-24 h-24 bg-purple-50 rounded-full opacity-20"></div>
                        
                        <div class="flex items-center mb-6 pb-2 border-b border-gray-200 relative z-10">
                            <div class="p-2 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg mr-3 shadow-md">
                                <i class="fas fa-edit text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800">Master Card Management</h3>
                        </div>

                        <!-- Form content -->
                        <form @submit.prevent="saveRecord" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="ac" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full mr-2 shadow-sm">
                                            <i class="fas fa-building text-white text-xs"></i>
                                        </span>
                                        AC#:
                                    </label>
                                    <div class="relative flex group">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 group-hover:bg-indigo-50 group-hover:text-indigo-500 transition-colors">
                                            <i class="fas fa-hashtag"></i>
                                        </span>
                                        <input 
                                            type="text" 
                                            id="ac" 
                                            v-model="form.ac"
                                            class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-all group-hover:border-indigo-300"
                                        />
                                        <button 
                                            type="button"
                                            @click="openCustomerAccountModal"
                                            class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-r-md transition-all transform active:translate-y-px relative overflow-hidden shadow-sm"
                                        >
                                            <span class="absolute inset-0 bg-white opacity-0 hover:opacity-20 transition-opacity"></span>
                                            <i class="fas fa-search relative z-10"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="flex items-end">
                                    <button 
                                        type="button"
                                        @click="selectRecord"
                                        class="bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transform active:translate-y-px transition-all duration-300 shadow-md relative overflow-hidden group"
                                    >
                                        <span class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity"></span>
                                        <div class="bg-white bg-opacity-30 rounded-full p-1.5 mr-2 flex items-center justify-center">
                                            <i class="fas fa-plus text-white text-xs"></i>
                                        </div>
                                        <span>Add New</span>
                                    </button>
                                </div>
                            </div>

                            <div class="absolute top-6 right-6">
                                <button type="button" class="bg-orange-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-orange-600 transition-colors text-sm font-semibold">
                                    Record: Review
                                </button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="mcs" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-pink-500 to-red-500 rounded-full mr-2 shadow-sm">
                                            <i class="fas fa-barcode text-white text-xs"></i>
                                        </span>
                                        MCS#:
                                    </label>
                                    <div class="relative flex group">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 group-hover:bg-indigo-50 group-hover:text-indigo-500 transition-colors">
                                            <i class="fas fa-barcode"></i>
                                        </span>
                                        <input 
                                            type="text" 
                                            id="mcs" 
                                            v-model="form.mcs"
                                            class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-all group-hover:border-indigo-300"
                                        />
                                        <button 
                                            type="button"
                                            @click="searchMcs"
                                            class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-pink-500 to-red-500 hover:from-pink-600 hover:to-red-600 text-white rounded-r-md transition-all transform active:translate-y-px relative overflow-hidden shadow-sm"
                                        >
                                            <span class="absolute inset-0 bg-white opacity-0 hover:opacity-20 transition-opacity"></span>
                                            <i class="fas fa-search relative z-10"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="flex items-end">
                                    <button 
                                        type="button"
                                        @click="handleMcsProceed"
                                        class="bg-gradient-to-r from-blue-500 to-teal-600 hover:from-blue-600 hover:to-teal-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transform active:translate-y-px transition-all duration-300 shadow-md relative overflow-hidden group"
                                    >
                                        <span class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity"></span>
                                        <div class="bg-white bg-opacity-30 rounded-full p-1.5 mr-2 flex items-center justify-center">
                                            <i class="fas fa-play text-white text-xs"></i>
                                        </div>
                                        <span>Proceed</span>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Customer Name, Product Code, Status -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full mr-2 shadow-sm">
                                            <i class="fas fa-user text-white text-xs"></i>
                                        </span>
                                        Customer Name:
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-user-circle text-gray-400"></i>
                                        </div>
                                    <input 
                                        type="text" 
                                        id="customer_name" 
                                        v-model="form.customer_name" 
                                        readonly
                                            class="block w-full pl-10 pr-3 py-2 rounded-md border border-gray-300 bg-gray-50"
                                    />
                                    </div>
                                </div>
                                <div>
                                    <label for="product_code" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full mr-2 shadow-sm">
                                            <i class="fas fa-box text-white text-xs"></i>
                                        </span>
                                        Product Code:
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-box text-gray-400"></i>
                                        </div>
                                    <input 
                                        type="text" 
                                        id="product_code" 
                                        v-model="form.product_code" 
                                            class="block w-full pl-10 pr-3 py-2 rounded-md border border-gray-300 focus:ring-amber-500 focus:border-amber-500"
                                    />
                                    </div>
                                </div>
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full mr-2 shadow-sm">
                                            <i class="fas fa-toggle-on text-white text-xs"></i>
                                        </span>
                                        Status:
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-tag text-gray-400"></i>
                                        </div>
                                    <select 
                                        id="status" 
                                        v-model="form.status" 
                                            class="block w-full pl-10 pr-3 py-2 rounded-md border border-gray-300 focus:ring-green-500 focus:border-green-500"
                                    >
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="pending">Pending</option>
                                    </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                            </div>

                    <!-- Detailed MC Info Section -->
                    <div v-if="showDetailedMcInfo" class="mt-6 bg-white p-6 rounded-lg shadow-md border-t-4 border-emerald-500 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 relative overflow-hidden">
                        <div class="absolute -top-20 -right-20 w-40 h-40 bg-emerald-50 rounded-full opacity-20"></div>
                        <div class="absolute -bottom-8 -left-8 w-24 h-24 bg-green-50 rounded-full opacity-20"></div>
                        
                        <div class="flex items-center mb-6 pb-2 border-b border-gray-200 relative z-10">
                            <div class="p-2 bg-gradient-to-r from-emerald-500 to-green-600 rounded-lg mr-3 shadow-md">
                                <i class="fas fa-info-circle text-white"></i>
                                    </div>
                            <h3 class="text-xl font-semibold text-gray-800">Detailed Master Card Information</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div class="space-y-2">
                                    <div>
                                    <label class="block text-gray-600">AC Name:</label>
                                    <input type="text" :value="mcDetails.ac_name" readonly class="block w-full border-gray-200 rounded-md bg-gray-50 px-3 py-2" />
                                    </div>
                                <div>
                                    <label class="block text-gray-600">MC Model:</label>
                                    <input type="text" :value="mcDetails.mc_model" readonly class="block w-full border-gray-200 rounded-md bg-gray-50 px-3 py-2" />
                                </div>
                                <div>
                                    <label class="block text-gray-600">MC Short Model:</label>
                                    <input type="text" :value="mcDetails.mc_short_model" readonly class="block w-full border-gray-200 rounded-md bg-gray-50 px-3 py-2" />
                            </div>
                            </div>
                            <div class="space-y-2">
                                <div>
                                    <label class="block text-gray-600">MC Status:</label>
                                    <input type="text" :value="mcDetails.mc_status" readonly class="block w-full border-gray-200 rounded-md bg-gray-50 px-3 py-2" />
                                </div>
                                <div>
                                    <label class="block text-gray-600">MC Approval:</label>
                                    <input type="text" :value="mcDetails.mc_approval" readonly class="block w-full border-gray-200 rounded-md bg-gray-50 px-3 py-2" />
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block text-gray-600">Last MCS#:</label>
                                        <input type="text" :value="mcDetails.last_mcs" readonly class="block w-full border-gray-200 rounded-md bg-gray-50 px-3 py-2" />
                                    </div>
                                    <div>
                                        <label class="block text-gray-600">Last Updated Seq#:</label>
                                        <input type="text" :value="mcDetails.last_updated_seq" readonly class="block w-full border-gray-200 rounded-md bg-gray-50 px-3 py-2" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex flex-wrap gap-4 justify-between">
                            <button type="button" @click="showMaintenanceLogModal = true" class="px-4 py-2 bg-indigo-500 text-white rounded-md shadow-md hover:bg-indigo-600 transition-colors">Maintenance Log</button>
                            <button type="button" class="px-4 py-2 bg-purple-500 text-white rounded-md shadow-md hover:bg-purple-600 transition-colors">Status Log</button>
                            <button type="button" class="px-4 py-2 bg-blue-500 text-white rounded-md shadow-md hover:bg-blue-600 transition-colors">Approval Log</button>
                            <button type="button" @click="showSecondPasswordAccessModal = true" class="ml-auto px-4 py-2 bg-green-500 text-white rounded-md shadow-md hover:bg-green-600 transition-colors">Next Setup</button>
                        </div>
                    </div>

                    <div class="mt-6 text-center" v-if="recordSelected">
                        <button type="button" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded-full shadow-lg transition-all transform hover:scale-105">
                            View & Print MC by Machine
                        </button>
                    </div>
                </div>

                <!-- Right Column - Quick Info -->
                <div class="lg:col-span-1">
                    <!-- Info Card -->
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-teal-500 mb-6 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 relative overflow-hidden">
                        <div class="absolute -top-16 -right-16 w-32 h-32 bg-teal-50 rounded-full opacity-20"></div>
                        <div class="absolute -bottom-6 -left-6 w-20 h-20 bg-green-50 rounded-full opacity-20"></div>
                        
                        <div class="flex items-center mb-4 pb-2 border-b border-gray-200 relative z-10">
                            <div class="p-2 bg-gradient-to-r from-teal-500 to-green-500 rounded-lg mr-3 shadow-md">
                                <i class="fas fa-info-circle text-white"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Master Card Info</h3>
                        </div>

                        <div class="space-y-4">
                            <div class="p-4 bg-teal-50 rounded-lg">
                                <h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2 flex items-center">
                                    <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-teal-500 to-green-500 rounded-full mr-2 shadow-sm">
                                        <i class="fas fa-book text-white text-xs"></i>
                                    </span>
                                    Instructions
                                </h4>
                                <ul class="text-sm text-gray-600 space-y-2">
                                    <li class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-400 to-indigo-400 rounded-full mr-2 shadow-sm mt-0.5">
                                            <i class="fas fa-search text-white text-xs"></i>
                                        </span>
                                        <span>Enter AC# or MCS# to search for existing records</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full mr-2 shadow-sm mt-0.5">
                                            <i class="fas fa-mouse-pointer text-white text-xs"></i>
                                        </span>
                                        <span>Click "Record: Select" to load the record details</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="inline-flex items-center justify-circuit w-5 h-5 bg-gradient-to-r from-amber-400 to-orange-400 rounded-full mr-2 shadow-sm mt-0.5">
                                            <i class="fas fa-edit text-white text-xs"></i>
                                        </span>
                                        <span>Update information as needed</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-green-400 to-emerald-400 rounded-full mr-2 shadow-sm mt-0.5">
                                            <i class="fas fa-save text-white text-xs"></i>
                                        </span>
                                        <span>Save changes with the save button</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-red-400 to-rose-400 rounded-full mr-2 shadow-sm mt-0.5">
                                            <i class="fas fa-times text-white text-xs"></i>
                                        </span>
                                        <span>Red button will close the form without saving</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="p-4 bg-blue-50 rounded-lg">
                                <h4 class="text-sm font-semibold text-blue-800 uppercase tracking-wider mb-2 flex items-center">
                                    <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full mr-2 shadow-sm">
                                        <i class="fas fa-clock text-white text-xs"></i>
                                    </span>
                                    Recent Activity
                                </h4>
                                <div class="space-y-3">
                                    <div class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-gray-400 to-slate-500 rounded-full mr-2 shadow-sm mt-0.5 flex-shrink-0">
                                            <i class="fas fa-info-circle text-white text-xs"></i>
                                        </span>
                                        <div>
                                    <p class="text-xs text-gray-600">No recent activity found</p>
                                            <p class="text-xs text-gray-400 mt-1">All updates will appear here</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-purple-500 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 relative overflow-hidden">
                        <div class="absolute -top-16 -right-16 w-32 h-32 bg-purple-50 rounded-full opacity-20"></div>
                        <div class="absolute -bottom-6 -left-6 w-20 h-20 bg-pink-50 rounded-full opacity-20"></div>
                        
                        <div class="flex items-center mb-4 pb-2 border-b border-gray-200 relative z-10">
                            <div class="p-2 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg mr-3 shadow-md">
                                <i class="fas fa-link text-white"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Quick Links</h3>
                        </div>

                        <div class="space-y-2">
                            <a href="#" class="group block p-2 hover:bg-gray-50 rounded-md text-sm text-gray-700 hover:text-blue-600 transition-colors">
                                <span class="inline-flex items-center">
                                    <span class="inline-flex items-center justify-center w-6 h-6 bg-gradient-to-r from-green-400 to-teal-500 rounded-full mr-2 shadow-sm transition-all duration-300 group-hover:scale-110">
                                        <i class="fas fa-check-circle text-white text-xs"></i>
                                    </span>
                                    <span class="transition-all duration-300 group-hover:translate-x-1">Approve Master Card</span>
                                </span>
                            </a>
                            <a href="#" class="group block p-2 hover:bg-gray-50 rounded-md text-sm text-gray-700 hover:text-blue-600 transition-colors">
                                <span class="inline-flex items-center">
                                    <span class="inline-flex items-center justify-center w-6 h-6 bg-gradient-to-r from-amber-400 to-orange-500 rounded-full mr-2 shadow-sm transition-all duration-300 group-hover:scale-110">
                                        <i class="fas fa-history text-white text-xs"></i>
                                    </span>
                                    <span class="transition-all duration-300 group-hover:translate-x-1">MC Maintenance Log</span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Account Modal -->
        <CustomerAccountModal 
            v-if="showCustomerAccountTable"
            :show="showCustomerAccountTable"
            @close="showCustomerAccountTable = false"
            @select="selectCustomer"
        />

        <!-- MCS Table Modal (after selecting options) -->
        <div v-if="showMcsTableModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
            <!-- Modal content -->
            <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-2/3 lg:w-3/4 max-w-4xl mx-auto flex flex-col max-h-[90vh]">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
                    <h3 class="text-xl font-semibold flex items-center">
                        <i class="fas fa-id-card mr-3"></i>Master Card Table
                    </h3>
                    <div class="flex space-x-3 items-center">
                        <div class="text-white text-sm mr-2">
                            <span class="mr-2">Zoom:</span>
                            <select v-model="zoomOption" @change="handleZoomChange" class="bg-blue-700 text-white border border-blue-500 rounded px-1 py-0.5 text-xs">
                                <option value="mc_specification">M/card specification</option>
                                <option value="current_price">Current price</option>
                                <option value="stand_by_price">Stand by price</option>
                            </select>
                        </div>
                        <div class="text-white text-sm mr-2">
                            <span class="mr-2">Sort:</span>
                            <select v-model="mcsSortOption" @change="fetchMcsData()" class="bg-blue-700 text-white border border-blue-500 rounded px-1 py-0.5 text-xs">
                                <option value="mc_seq">MC Seq#</option>
                                <option value="mc_model">MC Model</option>
                                <option value="part_no">MC PD Part#</option>
                                <option value="ext_dim_1">MC PD ED</option>
                                <option value="int_dim_1">MC PD ID</option>
                            </select>
                        </div>
                        <div class="text-white text-sm">
                            <span class="mr-2">Order:</span>
                            <select v-model="mcsSortOrder" @change="fetchMcsData()" class="bg-blue-700 text-white border border-blue-500 rounded px-1 py-0.5 text-xs">
                                <option value="asc">Asc</option>
                                <option value="desc">Desc</option>
                            </select>
                        </div>
                        <div class="text-white text-sm">
                            <span class="mr-2">Status:</span>
                            <select @change="fetchMcsData()" v-model="mcsStatusFilter" class="bg-blue-700 text-white border border-blue-500 rounded px-1 py-0.5 text-xs">
                                <option value="Act">Active</option>
                                <option value="Obsolete">Obsolete</option>
                                <option value="all">All</option>
                            </select>
                        </div>
                        <button type="button" @click="showMcsTableModal = false" class="text-white hover:text-gray-200 focus:outline-none">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="p-2 overflow-y-auto flex-grow" style="max-height: 60vh;">
                    <div class="mb-4 flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="relative w-full md:w-64">
                            <input 
                                type="text" 
                                v-model="mcsSearchTerm" 
                                placeholder="Search..." 
                                @keyup.enter="fetchMcsData()"
                                class="border border-gray-300 rounded-md py-1 px-2 text-xs focus:ring-blue-500 focus:border-blue-500 w-full shadow-sm"
                            />
                            <i class="fas fa-search absolute right-2 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>
                    
                    <div v-if="mcsLoading" class="flex justify-center items-center p-4">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                        <span class="ml-2 text-gray-600">Loading data...</span>
                    </div>
                
                    <div v-else-if="mcsError" class="p-4 text-red-500 bg-red-50 rounded border border-red-200">
                        <div class="font-bold mb-1">Error:</div>
                        <div>{{ mcsError }}</div>
                        <button @click="fetchMcsData()" class="mt-2 px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">
                            Try Again
                        </button>
                    </div>

                    <div v-else-if="mcsMasterCards.length === 0" class="p-4 text-amber-700 bg-amber-50 rounded border border-amber-200">
                        No master card records found. Please adjust your filter criteria.
                    </div>
                
                    <table v-else class="min-w-full text-xs border border-gray-300">
                        <thead class="bg-gray-200 sticky top-0">
                            <tr v-if="mcsSortOption === 'mc_model'">
                                <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Model</th>
                                <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">M/Card Seq#</th>
                                <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                            <tr v-else-if="mcsSortOption === 'part_no'">
                                <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Part No</th>
                                <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MC Seq#</th>
                                <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comp#</th>
                                <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">P/Design</th>
                                <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                            <tr v-else-if="mcsSortOption === 'ext_dim_1'">
                                <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ext. Dimension</th>
                                <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MC Seq#</th>
                                <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Model</th>
                                <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                            <tr v-else-if="mcsSortOption === 'int_dim_1'">
                                <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Int. Dimension</th>
                                <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MC Seq#</th>
                                <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Model</th>
                                <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                            <tr v-else>
                                <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">M/Card Seq#</th>
                                <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Model</th>
                                <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Part</th>
                                <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comp#</th>
                                <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="mcs in mcsMasterCards" :key="mcs.seq" 
                                class="hover:bg-blue-100 cursor-pointer"
                                :class="{ 'bg-blue-200': selectedMcs?.seq === mcs.seq }"
                                @click="selectedMcs = mcs"
                                @dblclick="selectMcs(mcs)">
                                <template v-if="mcsSortOption === 'mc_model'">
                                    <td class="px-2 py-1 border border-gray-300">{{ mcs.model }}</td>
                                    <td class="px-2 py-1 border border-gray-300">{{ mcs.seq }}</td>
                                    <td class="px-2 py-1 border border-gray-300">
                                        <span
                                            :class="(mcs.status === 'Act' || mcs.status === 'Active') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                            class="px-2 py-0.5 rounded-full text-xs">
                                            {{ mcs.status }}
                                        </span>
                                    </td>
                                </template>
                                <template v-else-if="mcsSortOption === 'part_no'">
                                    <td class="px-2 py-1 border border-gray-300">{{ mcs.part }}</td>
                                    <td class="px-2 py-1 border border-gray-300">{{ mcs.seq }}</td>
                                    <td class="px-2 py-1 border border-gray-300">{{ mcs.comp }}</td>
                                    <td class="px-2 py-1 border border-gray-300">{{ mcs.p_design }}</td>
                                    <td class="px-2 py-1 border border-gray-300">
                                        <span
                                            :class="(mcs.status === 'Act' || mcs.status === 'Active') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                            class="px-2 py-0.5 rounded-full text-xs">
                                            {{ mcs.status }}
                                        </span>
                                    </td>
                                </template>
                                <template v-else-if="mcsSortOption === 'ext_dim_1'">
                                    <td class="px-2 py-1 border border-gray-300">{{ mcs.ext_dim_1 }}x{{ mcs.ext_dim_2 }}x{{ mcs.ext_dim_3 }}</td>
                                    <td class="px-2 py-1 border border-gray-300">{{ mcs.seq }}</td>
                                    <td class="px-2 py-1 border border-gray-300">{{ mcs.model }}</td>
                                    <td class="px-2 py-1 border border-gray-300">
                                        <span
                                            :class="(mcs.status === 'Act' || mcs.status === 'Active') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                            class="px-2 py-0.5 rounded-full text-xs">
                                            {{ mcs.status }}
                                        </span>
                                    </td>
                                </template>
                                <template v-else-if="mcsSortOption === 'int_dim_1'">
                                    <td class="px-2 py-1 border border-gray-300">{{ mcs.int_dim_1 }}x{{ mcs.int_dim_2 }}x{{ mcs.int_dim_3 }}</td>
                                    <td class="px-2 py-1 border border-gray-300">{{ mcs.seq }}</td>
                                    <td class="px-2 py-1 border border-gray-300">{{ mcs.model }}</td>
                                    <td class="px-2 py-1 border border-gray-300">
                                        <span
                                            :class="(mcs.status === 'Act' || mcs.status === 'Active') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                            class="px-2 py-0.5 rounded-full text-xs">
                                            {{ mcs.status }}
                                        </span>
                                    </td>
                                </template>
                                <template v-else>
                                    <td class="px-2 py-1 border border-gray-300">{{ mcs.seq }}</td>
                                    <td class="px-2 py-1 border border-gray-300">{{ mcs.model }}</td>
                                    <td class="px-2 py-1 border border-gray-300">{{ mcs.part }}</td>
                                    <td class="px-2 py-1 border border-gray-300">{{ mcs.comp }}</td>
                                    <td class="px-2 py-1 border border-gray-300">
                                        <span
                                            :class="(mcs.status === 'Act' || mcs.status === 'Active') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                            class="px-2 py-0.5 rounded-full text-xs">
                                            {{ mcs.status }}
                                        </span>
                                    </td>
                                </template>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-end gap-2 p-2 border-t border-gray-200 bg-gray-100 rounded-b-lg flex-shrink-0">
                    <div class="text-xs text-gray-500 mr-auto" v-if="mcsMasterCards.length > 0">
                        {{ mcsMasterCards.length }} records found
                    </div>
                    <!-- Pagination -->
                    <button @click="goToMcsPage(mcsCurrentPage - 1)" :disabled="mcsCurrentPage === 1" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-3 rounded text-xs disabled:opacity-50 disabled:cursor-not-allowed"><i class="fas fa-chevron-left"></i> Previous</button>
                    <span class="text-gray-600 text-xs">Page {{ mcsCurrentPage }} of {{ mcsLastPage }}</span>
                    <button @click="goToMcsPage(mcsCurrentPage + 1)" :disabled="mcsCurrentPage === mcsLastPage" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-3 rounded text-xs disabled:opacity-50 disabled:cursor-not-allowed">Next <i class="fas fa-chevron-right"></i></button>
                    
                    <button 
                        @click="selectMcs(selectedMcs)" 
                        :disabled="!selectedMcs"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-xs disabled:opacity-50 disabled:cursor-not-allowed">
                        Select
                    </button>
                    <button type="button" @click="showMcsTableModal = false" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-3 rounded text-xs">Exit</button>
                </div>
            </div>
        </div>

        <!-- Maintenance Log Modal -->
        <MasterCardMaintenanceLogModal 
            :show="showMaintenanceLogModal"
            @close="showMaintenanceLogModal = false"
        />

        <!-- Zoom Modals (for dropdown selection) -->
        <MasterCardZoomModal
            :show="showMasterCardSpecModal"
            @close="showMasterCardSpecModal = false"
            :masterCardData="selectedMcs"
        />

        <MasterCardCurrentPriceModal
            :show="showMasterCardCurrentPriceModal"
            @close="showMasterCardCurrentPriceModal = false"
            :masterCardData="selectedMcs"
        />

        <MasterCardStandByPriceModal
            :show="showMasterCardStandByPriceModal"
            @close="showMasterCardStandByPriceModal = false"
            :masterCardData="selectedMcs"
        />

        <!-- Second Password Access Modal -->
        <SecondPasswordAccessModal
            :show="showSecondPasswordAccessModal"
            @close="showSecondPasswordAccessModal = false"
            @select="handleSecondPasswordSelect"
        />

    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';
import CustomerAccountModal from '@/Components/CustomerAccountModal.vue';
import MasterCardMaintenanceLogModal from '@/Components/MasterCardMaintenanceLogModal.vue';
import MasterCardZoomModal from '@/Components/MasterCardZoomModal.vue';
import MasterCardCurrentPriceModal from '@/Components/MasterCardCurrentPriceModal.vue';
import MasterCardStandByPriceModal from '@/Components/MasterCardStandByPriceModal.vue'; // Assuming this component exists or will be created
import SecondPasswordAccessModal from '@/Components/SecondPasswordAccessModal.vue';

// Form data
const form = ref({
    ac: '',
    mcs: '',
    customer_name: '',
    product_code: '',
    status: 'active',
});

// UI state
const recordSelected = ref(false);
const showCustomerAccountTable = ref(false);
const sortOption = ref('code');
const recordStatus = ref({
    active: true,
    obsolete: false
});
const tableSearchTerm = ref('');
const selectedCustomer = ref(null);

// MCS Modal state
const showMcsTableModal = ref(false);
const mcsSortOption = ref('mc_seq');
const mcsSortOrder = ref('asc');
const mcsRecordStatus = ref({
    active: true,
    obsolete: false
});
const mcsSearchTerm = ref('');
const selectedMcs = ref(null);
const mcsMasterCards = ref([]);
const mcsLoading = ref(false);
const mcsError = ref(null);
const mcsCurrentPage = ref(1);
const mcsLastPage = ref(1);
const mcsStatusFilter = ref('Act'); // Updated to use 'Act' instead of 'active'

// New state for Maintenance Log Modal
const showMaintenanceLogModal = ref(false);

// New states for Zoom Dropdown
const zoomOption = ref(''); // To hold the selected zoom option
const showMasterCardSpecModal = ref(false);
const showMasterCardCurrentPriceModal = ref(false);
const showMasterCardStandByPriceModal = ref(false);

// New state for Second Password Access Modal
const showSecondPasswordAccessModal = ref(false);

// Computed property for filtered and sorted MCS data (now fetched from API)
const filteredMcsData = computed(() => mcsMasterCards.value); // Data is now filtered/sorted by API

// New state for the detailed MC info section
const showDetailedMcInfo = ref(false);
const mcDetails = ref({
    ac_name: '',
    mc_model: '',
    mc_short_model: '',
    mc_status: 'Active',
    mc_approval: 'No',
    last_mcs: '',
    last_updated_seq: '',
    ext_dim_1: '',
    ext_dim_2: '',
    ext_dim_3: '',
    int_dim_1: '',
    int_dim_2: '',
    int_dim_3: '',
});

const searchAc = async () => {
    try {
        const response = await axios.post('/api/update-mc/search-ac', {
            ac: form.value.ac
        });
        console.log(response.data);
        // Assuming API returns customer_name, update it here
        mcDetails.value.ac_name = 'Sample AC Name from API'; // Placeholder
        recordSelected.value = true; // This might need adjustment based on overall flow
    } catch (error) {
        console.error('Error searching AC:', error);
    }
};

const searchMcs = () => {
    // Directly show the MCS table modal
    showMcsTableModal.value = true;
    fetchMcsData(); // Fetch data when modal opens
};

const selectRecord = () => {
    console.log('Record select clicked');
    recordSelected.value = true;
    form.value.customer_name = 'Selected Customer';
};

const saveRecord = () => {
    console.log('Save record clicked');
    if (!recordSelected.value) {
        alert('No record selected to save');
        return;
    }
    
    // API call to save record would go here
    console.log(`Saving record: ${JSON.stringify(form.value)}`);
};

const deleteRecord = () => {
    console.log('Delete record clicked');
    if (!recordSelected.value) {
        alert('No record selected to delete');
        return;
    }
    
    if (confirm('Are you sure you want to delete this record?')) {
        // API call to delete record would go here
        console.log(`Deleting record: ${form.value.mcs}`);
        // Reset form after delete
        form.value = {
            ac: '',
            mcs: '',
        };
        recordSelected.value = false;
        showDetailedMcInfo.value = false; // Hide detailed info after delete
    }
};

const printRecord = () => {
    console.log('Print record clicked');
    if (!recordSelected.value) {
        alert('No record selected to print');
        return;
    }
    
    // Print logic would go here
    console.log(`Printing record: ${form.value.mcs}`);
};

const closeRecord = () => {
    console.log('Close record clicked');
};

const openCustomerAccountModal = () => {
    // Skip the options modal and directly show the customer account table
    showCustomerAccountTable.value = true;
};

const applyFilter = () => {
    showCustomerAccountTable.value = true;
};

const selectCustomer = (customer) => {
    if (!customer) return;
    
    selectedCustomer.value = customer;
    form.value.ac = customer.customer_code;
    form.value.customer_name = customer.customer_name;
    recordSelected.value = true;
    showCustomerAccountTable.value = false;
};

// MCS Modal actions

const selectMcs = (mcs) => {
    if (!mcs) return;
    
    selectedMcs.value = mcs;
    
    // Populate the mcs field
    form.value.mcs = mcs.seq;
    
    // Populate mcDetails with data from selected MCS
    mcDetails.value.ac_name = mcs.customer_name || ''; // Assuming customer_name is available
    mcDetails.value.mc_model = mcs.model || '';
    mcDetails.value.mc_short_model = mcs.short_model || '';
    mcDetails.value.mc_status = mcs.status || 'Active';
    mcDetails.value.mc_approval = mcs.approval || 'No';
    mcDetails.value.last_mcs = mcs.last_mcs || '';
    mcDetails.value.last_updated_seq = mcs.last_updated_seq || '';
    mcDetails.value.ext_dim_1 = mcs.ext_dim_1 || '';
    mcDetails.value.ext_dim_2 = mcs.ext_dim_2 || '';
    mcDetails.value.ext_dim_3 = mcs.ext_dim_3 || '';
    mcDetails.value.int_dim_1 = mcs.int_dim_1 || '';
    mcDetails.value.int_dim_2 = mcs.int_dim_2 || '';
    mcDetails.value.int_dim_3 = mcs.int_dim_3 || '';

    recordSelected.value = true;
    showMcsTableModal.value = false;
};

const fetchMcsData = async (page = 1) => {
    mcsLoading.value = true;
    mcsError.value = null;
    try {
        let statusQuery = '';
        if (mcsStatusFilter.value === 'Act') {
            statusQuery = '&status[]=Act';
        } else if (mcsStatusFilter.value === 'Obsolete') {
            statusQuery = '&status[]=Obsolete';
        }

        const response = await axios.get(`/api/update-mc/master-cards?page=${page}&query=${mcsSearchTerm.value}&sortBy=${mcsSortOption.value}&sortOrder=${mcsSortOrder.value}${statusQuery}`);
        mcsMasterCards.value = response.data.data;
        mcsCurrentPage.value = response.data.current_page;
        mcsLastPage.value = response.data.last_page;
    } catch (error) {
        console.error('Error fetching MCS data:', error);
        mcsError.value = 'Failed to load master cards.';
    } finally {
        mcsLoading.value = false;
    }
};

const goToMcsPage = (page) => {
    if (page >= 1 && page <= mcsLastPage.value) {
        fetchMcsData(page);
    }
};

const handleMcsProceed = () => {
    console.log('Proceed button clicked');
    // Logic to proceed with the selected MCS record
    if (form.value.ac && form.value.mcs) {
        // Populate mcDetails with data from selected MCS. If not selected, it uses dummy data.
        const selectedRecord = selectedMcs.value; // Use the actual selected record

        if (selectedRecord) {
            mcDetails.value.ac_name = selectedRecord.customer_name || 'BAHAGIA IDKHO MANDIRI, PT'; // Use actual customer_name if available
            mcDetails.value.mc_model = selectedRecord.model;
            mcDetails.value.mc_short_model = selectedRecord.short_model || '';
            mcDetails.value.mc_status = selectedRecord.status || 'Active';
            mcDetails.value.mc_approval = selectedRecord.approval || 'No';
            mcDetails.value.last_mcs = selectedRecord.last_mcs || '';
            mcDetails.value.last_updated_seq = selectedRecord.last_updated_seq || '';
            mcDetails.value.ext_dim_1 = selectedRecord.ext_dim_1 || '';
            mcDetails.value.ext_dim_2 = selectedRecord.ext_dim_2 || '';
            mcDetails.value.ext_dim_3 = selectedRecord.ext_dim_3 || '';
            mcDetails.value.int_dim_1 = selectedRecord.int_dim_1 || '';
            mcDetails.value.int_dim_2 = selectedRecord.int_dim_2 || '';
            mcDetails.value.int_dim_3 = selectedRecord.int_dim_3 || '';
        } else {
            // Fallback to dummy data if no record is selected (though selectMcs should handle this)
            mcDetails.value.ac_name = 'BAHAGIA IDKHO MANDIRI, PT';
            mcDetails.value.mc_model = 'CARTON BOX BK TIN 48 X 60 GRAM';
            mcDetails.value.mc_short_model = 'CARTON BOX BK TIN 48';
            mcDetails.value.mc_status = 'Active';
            mcDetails.value.mc_approval = 'No';
            mcDetails.value.last_mcs = '042597';
            mcDetails.value.last_updated_seq = '042597';
            mcDetails.value.ext_dim_1 = '396';
            mcDetails.value.ext_dim_2 = '243';
            mcDetails.value.ext_dim_3 = '297';
            mcDetails.value.int_dim_1 = '393';
            mcDetails.value.int_dim_2 = '240';
            mcDetails.value.int_dim_3 = '292';
        }
        
        // Also populate the form fields that are displayed on the main page
        form.value.customer_name = mcDetails.value.ac_name;
        form.value.product_code = mcDetails.value.mc_model;
        form.value.status = mcDetails.value.mc_status.toLowerCase(); // Ensure lowercase for select

        showDetailedMcInfo.value = true; // Show the detailed section
        showMcsTableModal.value = false; // Hide the MCS table modal
        recordSelected.value = true;
    } else {
        alert('Please fill in both AC# and MCS# to proceed.');
    }
};

const handleZoomChange = () => {
  if (!selectedMcs.value) {
    alert('Please select a Master Card first.');
    zoomOption.value = ''; // Reset dropdown
    return;
  }
  switch (zoomOption.value) {
    case 'mc_specification':
      showMasterCardSpecModal.value = true;
      break;
    case 'current_price':
      showMasterCardCurrentPriceModal.value = true;
      break;
    case 'stand_by_price':
      showMasterCardStandByPriceModal.value = true;
      break;
  }
  zoomOption.value = ''; // Reset dropdown after action
};

const handleSecondPasswordSelect = ({ userId, password }) => {
  console.log('Second password access granted for:', userId);
  // Implement logic after second password is provided, e.g., proceed with the "Next Setup" action
  // For now, simply log and close.
  showSecondPasswordAccessModal.value = false;
  alert(`Access granted for User ID: ${userId}`);
};
</script>

<style scoped>
/* Add transition effects */
.transform {
    transition-property: transform, opacity;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}

/* Add modal animations */
@keyframes modalFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes modalScaleIn {
    from { transform: scale(0.95); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

/* Custom animation for slow ping effect */
@keyframes ping-slow {
    0% {
        transform: scale(1);
        opacity: 0.5;
    }
    50% {
        transform: scale(1.8);
        opacity: 0.15;
    }
    100% {
        transform: scale(1);
        opacity: 0.5;
    }
}

.animate-ping-slow {
    animation: ping-slow 3s ease-in-out infinite;
}

.animation-delay-500 {
    animation-delay: 1.5s;
}

/* Text shadow for headings */
.text-shadow {
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.fixed.inset-0 {
    animation: modalFadeIn 0.2s ease-out forwards;
}

.fixed.inset-0 > div.relative {
    animation: modalScaleIn 0.3s ease-out forwards;
}

/* Table styling */
table {
    border-collapse: collapse;
}

@keyframes spin-slow {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
.animate-spin-slow {
  animation: spin-slow 2.5s linear infinite;
}

@keyframes fadeIn {
  from { opacity: 0; transform: scale(0.95); }
  to { opacity: 1; transform: scale(1); }
}
.animate-fadeIn {
  animation: fadeIn 0.25s ease-out;
}
</style>

