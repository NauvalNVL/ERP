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

                                <div class="flex items-end" v-if="!showDetailedMcInfo">
                                    <button 
                                        type="button"
                                        @click="addNewRecord"
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

                            <div class="absolute top-6 right-6" v-if="showDetailedMcInfo">
                                <button type="button" 
                                    :class="recordMode === 'existing' ? 'bg-orange-500 hover:bg-orange-600' : 'bg-blue-500 hover:bg-blue-600'"
                                    class="text-white px-4 py-2 rounded-lg shadow-md transition-colors text-sm font-semibold">
                                    Record: {{ recordMode === 'existing' ? 'Review' : 'Add' }}
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
                                            @input="handleMcsInput"
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
                            
                            <!-- Fields yang hanya muncul setelah proceed -->
                            <div v-if="showDetailedMcInfo" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full mr-2 shadow-sm">
                                            <i class="fas fa-user text-white text-xs"></i>
                                        </span>
                                        AC Name:
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
                                    <label for="mc_model" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full mr-2 shadow-sm">
                                            <i class="fas fa-box text-white text-xs"></i>
                                        </span>
                                        MC Model:
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-box text-gray-400"></i>
                                        </div>
                                    <input 
                                        type="text" 
                                        id="mc_model" 
                                        v-model="form.mc_model" 
                                        :readonly="recordMode === 'existing'"
                                        :class="recordMode === 'existing' ? 'bg-gray-50' : 'bg-white'"
                                            class="block w-full pl-10 pr-3 py-2 rounded-md border border-gray-300 focus:ring-amber-500 focus:border-amber-500"
                                    />
                                    </div>
                                </div>
                                <div>
                                    <label for="mc_short_model" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full mr-2 shadow-sm">
                                            <i class="fas fa-tag text-white text-xs"></i>
                                        </span>
                                        MC Short Model:
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-tag text-gray-400"></i>
                                        </div>
                                    <input 
                                        type="text" 
                                        id="mc_short_model" 
                                        v-model="form.mc_short_model" 
                                        :readonly="recordMode === 'existing'"
                                        :class="recordMode === 'existing' ? 'bg-gray-50' : 'bg-white'"
                                            class="block w-full pl-10 pr-3 py-2 rounded-md border border-gray-300 focus:ring-green-500 focus:border-green-500"
                                    />
                                    </div>
                                </div>
                            </div>

                            <!-- Status fields yang hanya muncul setelah proceed -->
                            <div v-if="showDetailedMcInfo" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="mc_status" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full mr-2 shadow-sm">
                                            <i class="fas fa-toggle-on text-white text-xs"></i>
                                        </span>
                                        MC Status:
                                    </label>
                                    <div class="relative flex group">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-info-circle text-gray-400"></i>
                                        </div>
                                        <input 
                                            type="text" 
                                            id="mc_status" 
                                            v-model="form.mc_status" 
                                            readonly
                                            class="flex-1 min-w-0 block w-full pl-10 pr-3 py-2 rounded-l-md border border-r-0 border-gray-300 bg-gray-50"
                                        />
                                        <button 
                                            type="button" 
                                            class="inline-flex items-center px-3 py-2 border border-gray-300 bg-purple-500 text-white rounded-r-md shadow-md hover:bg-purple-600 transition-colors text-sm">
                                            Status Log
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label for="mc_approval" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full mr-2 shadow-sm">
                                            <i class="fas fa-check-circle text-white text-xs"></i>
                                        </span>
                                        MC Approval:
                                    </label>
                                    <div class="relative flex group">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-check text-gray-400"></i>
                                        </div>
                                        <input 
                                            type="text" 
                                            id="mc_approval" 
                                            v-model="form.mc_approval" 
                                            readonly
                                            :class="form.mc_approval === 'Yes' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                            class="flex-1 min-w-0 block w-full pl-10 pr-3 py-2 rounded-l-md border border-r-0 border-gray-300 font-semibold"
                                        />
                                        <button 
                                            type="button" 
                                            class="inline-flex items-center px-3 py-2 border border-gray-300 bg-blue-500 text-white rounded-r-md shadow-md hover:bg-blue-600 transition-colors text-sm">
                                            Approval Log
                                        </button>
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
                            <button type="button" @click="handleNextSetup" class="ml-auto px-4 py-2 bg-green-500 text-white rounded-md shadow-md hover:bg-green-600 transition-colors">Next Setup</button>
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
                                        <span>Select customer account first (AC#)</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full mr-2 shadow-sm mt-0.5">
                                            <i class="fas fa-mouse-pointer text-white text-xs"></i>
                                        </span>
                                        <span>Enter existing MCS# or new number</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-green-400 to-emerald-400 rounded-full mr-2 shadow-sm mt-0.5">
                                            <i class="fas fa-play text-white text-xs"></i>
                                        </span>
                                        <span>Click "Proceed" to continue</span>
                                    </li>
                                    <li v-if="showDetailedMcInfo" class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-amber-400 to-orange-400 rounded-full mr-2 shadow-sm mt-0.5">
                                            <i class="fas fa-edit text-white text-xs"></i>
                                        </span>
                                        <span>Fill MC Model for new records</span>
                                    </li>
                                    <li v-if="showDetailedMcInfo" class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-red-400 to-rose-400 rounded-full mr-2 shadow-sm mt-0.5">
                                            <i class="fas fa-cogs text-white text-xs"></i>
                                        </span>
                                        <span>Use Next Setup to configure components</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="p-4 bg-blue-50 rounded-lg">
                                <h4 class="text-sm font-semibold text-blue-800 uppercase tracking-wider mb-2 flex items-center">
                                    <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full mr-2 shadow-sm">
                                        <i class="fas fa-info-circle text-white text-xs"></i>
                                    </span>
                                    Status
                                </h4>
                                <div class="space-y-3">
                                    <div class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-gray-400 to-slate-500 rounded-full mr-2 shadow-sm mt-0.5 flex-shrink-0">
                                            <i class="fas fa-info-circle text-white text-xs"></i>
                                        </span>
                                        <div>
                                            <p v-if="!showDetailedMcInfo" class="text-xs text-gray-600">Ready to start</p>
                                            <p v-else class="text-xs text-gray-600">{{ recordMode === 'existing' ? 'Existing MC - Approved' : 'New MC - Pending Approval' }}</p>
                                            <p v-if="!showDetailedMcInfo" class="text-xs text-gray-400 mt-1">Enter AC# and MCS# to proceed</p>
                                            <p v-else class="text-xs text-gray-400 mt-1">{{ recordMode === 'existing' ? 'Ready for modifications' : 'Requires approval workflow' }}</p>
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

        <!-- Update MC Modals -->
        <UpdateMcModal
            :showErrorModal="showErrorModal"
            :showSetupMcModal="showSetupMcModal"
            :showSetupPdModal="showSetupPdModal"
            :showMcsTableModal="showMcsTableModal"
            :formData="form"
            :mcComponents="mcComponents"
            :zoomOption="zoomOption"
            :mcsSortOption="mcsSortOption"
            :mcsSortOrder="mcsSortOrder"
            :mcsStatusFilter="mcsStatusFilter"
            :mcsSearchTerm="mcsSearchTerm"
            :mcsLoading="mcsLoading"
            :mcsError="mcsError"
            :mcsMasterCards="mcsMasterCards"
            :selectedMcs="selectedMcs"
            :mcsCurrentPage="mcsCurrentPage"
            :mcsLastPage="mcsLastPage"
            :productDesigns="productDesigns"
            :paperFlutes="paperFlutes"
            @closeErrorModal="showErrorModal = false"
            @closeSetupMcModal="showSetupMcModal = false"
            @closeSetupPdModal="showSetupPdModal = false"
            @closeMcsTableModal="showMcsTableModal = false"
            @selectComponent="selectComponent"
            @setupPD="setupPD"
            @setupOthers="setupOthers"
            @handleZoomChange="handleZoomChange"
            @fetchMcsData="fetchMcsData"
            @selectMcsItem="selectedMcs = $event"
            @selectMcs="selectMcs"
            @goToMcsPage="goToMcsPage"
            @updateSearchTerm="mcsSearchTerm = $event"
            @updateSortOption="mcsSortOption = $event"
            @productDesignSelected="onProductDesignSelected"
            @paperFluteSelected="onPaperFluteSelected"
        />

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
import UpdateMcModal from '@/Components/UpdateMcModal.vue';
import MasterCardMaintenanceLogModal from '@/Components/MasterCardMaintenanceLogModal.vue';
import MasterCardZoomModal from '@/Components/MasterCardZoomModal.vue';
import MasterCardCurrentPriceModal from '@/Components/MasterCardCurrentPriceModal.vue';
import MasterCardStandByPriceModal from '@/Components/MasterCardStandByPriceModal.vue';
import SecondPasswordAccessModal from '@/Components/SecondPasswordAccessModal.vue';

// Form data
const form = ref({
    ac: '',
    mcs: '',
    customer_name: '',
    mc_model: '',
    mc_short_model: '',
    mc_status: 'Active',
    mc_approval: 'No',
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
const recordMode = ref('new'); // 'new' or 'existing'

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
const mcsStatusFilter = ref('Act');

// New state for Maintenance Log Modal
const showMaintenanceLogModal = ref(false);

// New states for Zoom Dropdown
const zoomOption = ref('');
const showMasterCardSpecModal = ref(false);
const showMasterCardCurrentPriceModal = ref(false);
const showMasterCardStandByPriceModal = ref(false);

// New state for Second Password Access Modal
const showSecondPasswordAccessModal = ref(false);

// Error modal state
const showErrorModal = ref(false);

// Setup MC Component Modal state
const showSetupMcModal = ref(false);
const showSetupPdModal = ref(false);
const mcComponents = ref([
    { c_num: 'Main', pd: '', pcs_set: '', part_num: '', selected: true },
    { c_num: 'Fit1', pd: '', pcs_set: '', part_num: '', selected: false },
    { c_num: 'Fit2', pd: '', pcs_set: '', part_num: '', selected: false },
    { c_num: 'Fit3', pd: '', pcs_set: '', part_num: '', selected: false },
    { c_num: 'Fit4', pd: '', pcs_set: '', part_num: '', selected: false },
    { c_num: 'Fit5', pd: '', pcs_set: '', part_num: '', selected: false },
    { c_num: 'Fit6', pd: '', pcs_set: '', part_num: '', selected: false },
    { c_num: 'Fit7', pd: '', pcs_set: '', part_num: '', selected: false },
    { c_num: 'Fit8', pd: '', pcs_set: '', part_num: '', selected: false },
    { c_num: 'Fit9', pd: '', pcs_set: '', part_num: '', selected: false },
]);

// Computed property for filtered and sorted MCS data
const filteredMcsData = computed(() => mcsMasterCards.value);

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

// Product Design data - sample data matching the image format
const productDesigns = ref([
    { pd_code: 'APR', pd_name: 'APR', pd_design_type: 'T-Trading', idc: 'NA', product: '005', joint: 'No', joint_to_print: 'No', pcs_to_joint: '1', score: 'Yes', slot: 'No', flute_style: 'Blank N/A', print_flute: 'No', input_weight: 'Yes' },
    { pd_code: 'B0', pd_name: 'B0/B0', pd_design_type: 'M-Manufacture', idc: '0510', product: '001', joint: 'No', joint_to_print: 'No', pcs_to_joint: '1', score: 'Yes', slot: 'No', flute_style: 'N-Normal', print_flute: 'No', input_weight: 'Yes' },
    { pd_code: 'B0 DJ', pd_name: 'B0/B0 DOUBLE JOINT', pd_design_type: 'M-Manufacture', idc: '0511u2', product: '001', joint: 'Yes', joint_to_print: 'Yes', pcs_to_joint: '2', score: 'Yes', slot: 'No', flute_style: 'N-Normal', print_flute: 'No', input_weight: 'Yes' },
    { pd_code: 'B0/B1', pd_name: 'B0/B1 FLEP+FLAP', pd_design_type: 'M-Manufacture', idc: '0200B', product: '001', joint: 'No', joint_to_print: 'No', pcs_to_joint: '1', score: 'Yes', slot: 'No', flute_style: 'N-Normal', print_flute: 'No', input_weight: 'Yes' },
    { pd_code: 'B0/B1 4J', pd_name: 'B0/B1 4 JOINT', pd_design_type: 'M-Manufacture', idc: '0200+B', product: '001', joint: 'Yes', joint_to_print: 'Yes', pcs_to_joint: '4', score: 'Yes', slot: 'No', flute_style: 'N-Normal', print_flute: 'No', input_weight: 'Yes' },
    { pd_code: 'B0/B1 DJ', pd_name: 'B0/B1 DOUBLE JOINT', pd_design_type: 'M-Manufacture', idc: '0201+B', product: '001', joint: 'Yes', joint_to_print: 'Yes', pcs_to_joint: '2', score: 'Yes', slot: 'No', flute_style: 'N-Normal', print_flute: 'No', input_weight: 'Yes' },
    { pd_code: 'B1', pd_name: 'REGULAR BOX', pd_design_type: 'M-Manufacture', idc: '0201', product: '001', joint: 'No', joint_to_print: 'No', pcs_to_joint: '1', score: 'Yes', slot: 'No', flute_style: 'N-Normal', print_flute: 'No', input_weight: 'Yes' },
    { pd_code: 'B1 4J', pd_name: 'B1 4 JOINT', pd_design_type: 'M-Manufacture', idc: '0201+a', product: '001', joint: 'Yes', joint_to_print: 'Yes', pcs_to_joint: '4', score: 'Yes', slot: 'No', flute_style: 'N-Normal', print_flute: 'No', input_weight: 'Yes' },
    { pd_code: 'B1 4J 2C', pd_name: 'B1 4 JOINT 2 CREASING', pd_design_type: 'M-Manufacture', idc: '0201+', product: '001', joint: 'Yes', joint_to_print: 'Yes', pcs_to_joint: '4', score: 'Yes', slot: 'No', flute_style: 'N-Normal', print_flute: 'No', input_weight: 'Yes' },
    { pd_code: 'B1 DJ', pd_name: 'B1 DOUBLE JOINT + CREASING', pd_design_type: 'M-Manufacture', idc: '0201J2', product: '001', joint: 'Yes', joint_to_print: 'Yes', pcs_to_joint: '2', score: 'Yes', slot: 'No', flute_style: 'N-Normal', print_flute: 'No', input_weight: 'Yes' },
    { pd_code: 'B1+1CRJ', pd_name: 'B1+1CREASING JOINT', pd_design_type: 'M-Manufacture', idc: '0201+', product: '001', joint: 'Yes', joint_to_print: 'Yes', pcs_to_joint: '1', score: 'Yes', slot: 'No', flute_style: 'N-Normal', print_flute: 'No', input_weight: 'Yes' },
    { pd_code: 'B1+1CRDJ', pd_name: 'B1+1CREASING DOUBLE JOINT', pd_design_type: 'M-Manufacture', idc: '0201+2', product: '001', joint: 'Yes', joint_to_print: 'Yes', pcs_to_joint: '2', score: 'Yes', slot: 'No', flute_style: 'N-Normal', print_flute: 'No', input_weight: 'Yes' },
    { pd_code: 'B1+2CRJ', pd_name: 'B1+2CREASING', pd_design_type: 'M-Manufacture', idc: '0201+2', product: '001', joint: 'Yes', joint_to_print: 'Yes', pcs_to_joint: '1', score: 'Yes', slot: 'No', flute_style: 'N-Normal', print_flute: 'No', input_weight: 'Yes' },
    { pd_code: 'B1-FB', pd_name: 'REGULAR BOX FLUTE REVERSE', pd_design_type: 'M-Manufacture', idc: '0201R', product: '001', joint: 'No', joint_to_print: 'No', pcs_to_joint: '1', score: 'Yes', slot: 'No', flute_style: 'R-Reverse/Rotate', print_flute: 'Yes', input_weight: 'Yes' },
    { pd_code: 'B1/B0', pd_name: 'B1/B0 FLEP FLAP', pd_design_type: 'M-Manufacture', idc: '0200T', product: '001', joint: 'No', joint_to_print: 'No', pcs_to_joint: '1', score: 'Yes', slot: 'No', flute_style: 'N-Normal', print_flute: 'No', input_weight: 'Yes' },
    { pd_code: 'B1/B0 4J', pd_name: 'B1/B0 4 JOINT', pd_design_type: 'M-Manufacture', idc: '0200+T', product: '001', joint: 'Yes', joint_to_print: 'Yes', pcs_to_joint: '4', score: 'Yes', slot: 'No', flute_style: 'N-Normal', print_flute: 'No', input_weight: 'Yes' },
    { pd_code: 'B1/B0 DJ', pd_name: 'B1/B0 DOUBLE JOINT', pd_design_type: 'M-Manufacture', idc: '0201+T', product: '001', joint: 'Yes', joint_to_print: 'Yes', pcs_to_joint: '2', score: 'Yes', slot: 'No', flute_style: 'N-Normal', print_flute: 'No', input_weight: 'Yes' },
    { pd_code: 'B1/B4', pd_name: 'B1/B4', pd_design_type: 'M-Manufacture', idc: 'NA', product: '001', joint: 'No', joint_to_print: 'No', pcs_to_joint: '1', score: 'Yes', slot: 'No', flute_style: 'N-Normal', print_flute: 'No', input_weight: 'Yes' }
]);

const onProductDesignSelected = (design) => {
    // Update the P/Design field in the form or wherever it's needed
    console.log('Product Design Selected:', design);
    // You can add logic here to update form fields based on the selected design
};

// Paper Flute data - akan diambil dari API
const paperFlutes = ref([]);
const paperFluteLoading = ref(false);

// Fetch paper flutes dari API
const fetchPaperFlutes = async () => {
    paperFluteLoading.value = true;
    try {
        const res = await fetch('/api/paper-flutes', { 
            headers: { 
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            } 
        });
        
        if (!res.ok) {
            throw new Error('Network response was not ok');
        }
        
        const data = await res.json();
        if (Array.isArray(data)) {
            console.log('Paper Flutes data:', data.slice(0, 3)); // Debug: show first 3 items
            paperFlutes.value = data;
        } else {
            paperFlutes.value = [];
            console.error('Unexpected data format:', data);
        }
    } catch (e) {
        console.error('Error fetching paper flutes:', e);
        paperFlutes.value = [];
    } finally {
        paperFluteLoading.value = false;
    }
};

const onPaperFluteSelected = (flute) => {
    // Update the Flute field in the form or wherever it's needed
    console.log('Paper Flute Selected:', flute);
    // You can add logic here to update form fields based on the selected flute
};

// Load paper flutes saat komponen dimount
onMounted(() => {
    fetchPaperFlutes();
});

const handleMcsInput = () => {
    // Reset record mode when user manually changes MCS input
    if (form.value.mcs && !selectedMcs.value) {
        recordMode.value = 'new';
        form.value.mc_approval = 'No';
    }
};

const addNewRecord = () => {
    recordMode.value = 'new';
    form.value.mc_approval = 'No';
    recordSelected.value = true;
};

const searchAc = async () => {
    try {
        const response = await axios.post('/api/update-mc/search-ac', {
            ac: form.value.ac
        });
        console.log(response.data);
        mcDetails.value.ac_name = 'Sample AC Name from API';
        recordSelected.value = true;
    } catch (error) {
        console.error('Error searching AC:', error);
    }
};

const searchMcs = () => {
    showMcsTableModal.value = true;
    fetchMcsData();
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
    
    console.log(`Saving record: ${JSON.stringify(form.value)}`);
};

const deleteRecord = () => {
    console.log('Delete record clicked');
    if (!recordSelected.value) {
        alert('No record selected to delete');
        return;
    }
    
    if (confirm('Are you sure you want to delete this record?')) {
        console.log(`Deleting record: ${form.value.mcs}`);
        form.value = {
            ac: '',
            mcs: '',
            customer_name: '',
            mc_model: '',
            mc_short_model: '',
            mc_status: 'Active',
            mc_approval: 'No',
        };
        recordSelected.value = false;
        showDetailedMcInfo.value = false;
    }
};

const printRecord = () => {
    console.log('Print record clicked');
    if (!recordSelected.value) {
        alert('No record selected to print');
        return;
    }
    
    console.log(`Printing record: ${form.value.mcs}`);
};

const closeRecord = () => {
    console.log('Close record clicked');
};

const openCustomerAccountModal = () => {
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

const selectMcs = (mcs) => {
    if (!mcs) return;
    
    selectedMcs.value = mcs;
    recordMode.value = 'existing';
    
    // Populate the mcs field
    form.value.mcs = mcs.seq;
    form.value.mc_model = mcs.model || '';
    form.value.mc_short_model = mcs.short_model || '';
    form.value.mc_status = mcs.status || 'Active';
    form.value.mc_approval = 'Yes'; // Existing MC is already approved
    
    // Populate mcDetails with data from selected MCS
    mcDetails.value.ac_name = mcs.customer_name || form.value.customer_name;
    mcDetails.value.mc_model = mcs.model || '';
    mcDetails.value.mc_short_model = mcs.short_model || '';
    mcDetails.value.mc_status = mcs.status || 'Active';
    mcDetails.value.mc_approval = 'Yes';
    mcDetails.value.last_mcs = mcs.last_mcs || mcs.seq;
    mcDetails.value.last_updated_seq = mcs.last_updated_seq || mcs.seq;
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

        // Filter by customer account if selected
        let customerFilter = '';
        if (form.value.ac) {
            customerFilter = `&customer_code=${form.value.ac}`;
        }

        const response = await axios.get(`/api/update-mc/master-cards?page=${page}&query=${mcsSearchTerm.value}&sortBy=${mcsSortOption.value}&sortOrder=${mcsSortOrder.value}${statusQuery}${customerFilter}`);
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

const handleMcsProceed = async () => {
    console.log('Proceed button clicked');
    
    if (!form.value.ac) {
        alert('Please select customer account (AC#) first.');
        return;
    }
    
    if (!form.value.mcs) {
        alert('Please enter MCS# to proceed.');
        return;
    }

    // Check if MCS already exists
    try {
        const response = await axios.get(`/api/update-mc/check-mcs/${form.value.mcs}`);
        
        if (response.data.exists) {
            // Existing MC - load data and set as approved
            const existingMc = response.data.data;
            recordMode.value = 'existing';
            
            form.value.mc_model = existingMc.mc_model || '';
            form.value.mc_short_model = existingMc.mc_short_model || '';
            form.value.mc_status = existingMc.status || 'Active';
            form.value.mc_approval = 'Yes';
            
            mcDetails.value.ac_name = form.value.customer_name;
            mcDetails.value.mc_model = existingMc.mc_model || '';
            mcDetails.value.mc_short_model = existingMc.mc_short_model || '';
            mcDetails.value.mc_status = existingMc.status || 'Active';
            mcDetails.value.mc_approval = 'Yes';
            mcDetails.value.last_mcs = existingMc.mc_seq || form.value.mcs;
            mcDetails.value.last_updated_seq = existingMc.mc_seq || form.value.mcs;
        } else {
            // New MC - set as not approved
            recordMode.value = 'new';
            form.value.mc_approval = 'No';
            form.value.mc_status = 'Active';
            
            mcDetails.value.ac_name = form.value.customer_name;
            mcDetails.value.mc_approval = 'No';
            mcDetails.value.mc_status = 'Active';
            mcDetails.value.last_mcs = '';
            mcDetails.value.last_updated_seq = '';
        }
        
        showDetailedMcInfo.value = true;
        recordSelected.value = true;
        
    } catch (error) {
        console.error('Error checking MCS:', error);
        // Fallback to treating as new MC
        recordMode.value = 'new';
        form.value.mc_approval = 'No';
        showDetailedMcInfo.value = true;
        recordSelected.value = true;
    }
};

const handleNextSetup = () => {
    // Validate MC Model is filled for new records
    if (recordMode.value === 'new' && !form.value.mc_model.trim()) {
        showErrorModal.value = true;
        return;
    }
    
    // Show Setup MC Component modal
    showSetupMcModal.value = true;
};

const selectComponent = (component, index) => {
    // Reset all selections
    mcComponents.value.forEach(comp => comp.selected = false);
    // Select the clicked component
    component.selected = true;
};

const setupPD = () => {
    console.log('Setup PD clicked');
    showSetupPdModal.value = true;
};

const setupOthers = () => {
    console.log('Setup Others clicked');
    // Implementation for Setup Others functionality
};

const handleZoomChange = () => {
  if (!selectedMcs.value) {
    alert('Please select a Master Card first.');
    zoomOption.value = '';
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
  zoomOption.value = '';
};

const handleSecondPasswordSelect = ({ userId, password }) => {
  console.log('Second password access granted for:', userId);
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

