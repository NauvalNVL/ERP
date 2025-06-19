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
                                    <!-- Empty div to match AC# layout -->
                                </div>
                            </div>
                            

                            
                            <!-- Additional fields can be added here as needed -->
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

                            <div v-if="recordSelected" class="mt-6 bg-gradient-to-r from-green-100 to-emerald-100 p-4 rounded-lg shadow-sm border border-green-200 relative overflow-hidden">
                                <div class="absolute -top-6 -right-6 w-12 h-12 bg-green-200 rounded-full opacity-50"></div>
                                <div class="absolute -bottom-4 -left-4 w-8 h-8 bg-emerald-200 rounded-full opacity-50"></div>
                                <div class="relative z-10 flex items-center">
                                    <div class="bg-gradient-to-r from-green-500 to-emerald-500 p-2 rounded-full mr-3 shadow-sm">
                                        <i class="fas fa-check text-white text-sm"></i>
                                    </div>
                                    <div>
                                <p class="text-sm font-medium text-green-800">Record loaded successfully</p>
                                <p class="text-xs text-green-700 mt-1">You can now edit the master card details</p>
                                    </div>
                                </div>
                            </div>
                        </form>
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
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-amber-400 to-orange-400 rounded-full mr-2 shadow-sm mt-0.5">
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
                                    <span class="inline-flex items-center justify-center w-6 h-6 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-full mr-2 shadow-sm transition-all duration-300 group-hover:scale-110">
                                        <i class="fas fa-print text-white text-xs"></i>
                                    </span>
                                    <span class="transition-all duration-300 group-hover:translate-x-1">View & Print MC</span>
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
        <div v-if="showCustomerAccountModal" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto">
            <!-- Modal backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="showCustomerAccountModal = false"></div>
            
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-xl w-96 mx-auto max-w-lg z-10 transform transition-all">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-t-lg relative overflow-hidden">
                    <div class="absolute -top-8 -left-8 w-16 h-16 bg-white opacity-10 rounded-full"></div>
                    <div class="absolute -bottom-8 -right-8 w-16 h-16 bg-white opacity-10 rounded-full"></div>
                    
                    <h3 class="text-xl font-semibold flex items-center relative z-10">
                        <span class="inline-flex items-center justify-center w-8 h-8 bg-white bg-opacity-20 rounded-full mr-3 shadow-inner">
                            <i class="fas fa-filter text-white"></i>
                        </span>
                        Options
                    </h3>
                    <button type="button" @click="showCustomerAccountModal = false" 
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
                                <input type="radio" v-model="sortOption" value="code" class="form-radio h-4 w-4 text-blue-600">
                                <span class="ml-2 text-gray-700">Customer Code</span>
                            </label>
                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md">
                                <input type="radio" v-model="sortOption" value="name" class="form-radio h-4 w-4 text-blue-600">
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
                                <input type="checkbox" v-model="recordStatus.active" class="form-checkbox h-4 w-4 text-blue-600 rounded">
                                <span class="ml-2 text-gray-700">Active</span>
                            </label>
                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md">
                                <input type="checkbox" v-model="recordStatus.obsolete" class="form-checkbox h-4 w-4 text-blue-600 rounded">
                                <span class="ml-2 text-gray-700">Obsolete</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-center space-x-4 p-4 border-t border-gray-200 bg-gray-50 rounded-b-lg">
                    <button 
                        @click="applyFilter" 
                        class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-medium py-2 px-8 rounded-md transition-all focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-md hover:shadow-lg relative overflow-hidden group"
                    >
                        <span class="absolute inset-0 w-full h-full bg-white opacity-0 group-hover:opacity-10 transition-opacity"></span>
                        <span class="inline-flex items-center">
                            <i class="fas fa-check mr-2"></i>
                        OK
                        </span>
                    </button>
                    <button 
                        @click="showCustomerAccountModal = false" 
                        class="bg-gradient-to-r from-gray-300 to-gray-400 hover:from-gray-400 hover:to-gray-500 text-gray-800 font-medium py-2 px-8 rounded-md transition-all focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 shadow-md hover:shadow-lg relative overflow-hidden group"
                    >
                        <span class="absolute inset-0 w-full h-full bg-white opacity-0 group-hover:opacity-10 transition-opacity"></span>
                        <span class="inline-flex items-center">
                            <i class="fas fa-times mr-2"></i>
                        Exit
                        </span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Customer Account Table (after selecting options) -->
        <div v-if="showCustomerAccountTable" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto">
            <!-- Modal backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="showCustomerAccountTable = false"></div>
            
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-xl w-4/5 max-w-4xl mx-auto z-10 transform transition-all">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg relative overflow-hidden">
                    <div class="absolute -top-8 -left-8 w-16 h-16 bg-white opacity-10 rounded-full"></div>
                    <div class="absolute -bottom-8 -right-8 w-16 h-16 bg-white opacity-10 rounded-full"></div>
                    
                    <h3 class="text-xl font-semibold flex items-center relative z-10">
                        <span class="inline-flex items-center justify-center w-8 h-8 bg-white bg-opacity-20 rounded-full mr-3 shadow-inner">
                            <i class="fas fa-table text-white"></i>
                        </span>
                        Customer Account Table
                    </h3>
                    <button type="button" @click="showCustomerAccountTable = false" 
                        class="text-white hover:text-gray-200 focus:outline-none transition-transform hover:scale-110 relative z-10 bg-red-500 bg-opacity-30 hover:bg-opacity-50 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="p-6 overflow-auto" style="max-height: 440px;">
                    <div class="mb-4 flex justify-between items-center">
                        <div class="text-gray-600 text-sm">
                            <span class="font-semibold">Filter:</span> 
                            {{ recordStatus.active && recordStatus.obsolete ? 'All Records' : 
                                recordStatus.active ? 'Active Records Only' : 
                                recordStatus.obsolete ? 'Obsolete Records Only' : 'No Records' }}
                        </div>
                        <div class="relative">
                            <input 
                                type="text" 
                                v-model="tableSearchTerm" 
                                placeholder="Search..." 
                                class="border border-gray-300 rounded-md py-1 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                            <i class="fas fa-search absolute right-3 top-2 text-gray-400"></i>
                        </div>
                    </div>
                    
                    <table class="min-w-full border border-gray-300 text-xs">
                        <thead class="bg-gray-100 sticky top-0">
                            <tr>
                                <th class="px-2 py-1 border border-gray-300 text-left">Customer Name</th>
                                <th class="px-2 py-1 border border-gray-300 text-left">Customer Code</th>
                                <th class="px-2 py-1 border border-gray-300 text-left">S/person</th>
                                <th class="px-2 py-1 border border-gray-300 text-left">AC Type</th>
                                <th class="px-2 py-1 border border-gray-300 text-left">Currency</th>
                                <th class="px-2 py-1 border border-gray-300 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="(customer, index) in displayedCustomers" :key="customer.code" 
                                class="hover:bg-blue-100 cursor-pointer"
                                :class="{ 'bg-blue-200': selectedCustomer?.code === customer.code }"
                                @click="selectForView(customer)" 
                                @dblclick="selectCustomer(customer)">
                                <td class="px-2 py-1 border border-gray-300">{{ customer.name }}</td>
                                <td class="px-2 py-1 border border-gray-300">{{ customer.code }}</td>
                                <td class="px-2 py-1 border border-gray-300">{{ customer.salesperson }}</td>
                                <td class="px-2 py-1 border border-gray-300">{{ customer.acType }}</td>
                                <td class="px-2 py-1 border border-gray-300">{{ customer.currency }}</td>
                                <td class="px-2 py-1 border border-gray-300">{{ customer.status }}</td>
                            </tr>
                            <tr v-if="displayedCustomers.length === 0">
                                <td colspan="6" class="px-2 py-4 text-center text-gray-500 border border-gray-300">
                                    No customer accounts found matching your criteria
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="flex items-center justify-end gap-2 p-4 border-t border-gray-200 bg-gray-50 rounded-b-lg">
                    <button 
                        class="bg-gradient-to-r from-gray-300 to-gray-400 hover:from-gray-400 hover:to-gray-500 text-gray-800 font-bold py-1 px-3 rounded text-xs shadow-sm hover:shadow transition-all group relative overflow-hidden"
                    >
                        <span class="absolute inset-0 w-full h-full bg-white opacity-0 group-hover:opacity-10 transition-opacity"></span>
                        <span class="inline-flex items-center">
                            <i class="fas fa-cog mr-1 text-gray-600"></i>
                        More Options
                        </span>
                    </button>
                    <button 
                        class="bg-gradient-to-r from-gray-300 to-gray-400 hover:from-gray-400 hover:to-gray-500 text-gray-800 font-bold py-1 px-3 rounded text-xs shadow-sm hover:shadow transition-all group relative overflow-hidden"
                    >
                        <span class="absolute inset-0 w-full h-full bg-white opacity-0 group-hover:opacity-10 transition-opacity"></span>
                        <span class="inline-flex items-center">
                            <i class="fas fa-search-plus mr-1 text-gray-600"></i>
                        Zoom
                        </span>
                    </button>
                    <button 
                        @click="selectCustomer(selectedCustomer)" 
                        :disabled="!selectedCustomer"
                        class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-bold py-1 px-3 rounded text-xs shadow-sm hover:shadow disabled:opacity-50 disabled:cursor-not-allowed transition-all group relative overflow-hidden"
                    >
                        <span class="absolute inset-0 w-full h-full bg-white opacity-0 group-hover:opacity-10 transition-opacity"></span>
                        <span class="inline-flex items-center">
                            <i class="fas fa-check-circle mr-1"></i>
                        Select
                        </span>
                    </button>
                    <button 
                        @click="showCustomerAccountTable = false" 
                        class="bg-gradient-to-r from-gray-300 to-gray-400 hover:from-gray-400 hover:to-gray-500 text-gray-800 font-bold py-1 px-3 rounded text-xs shadow-sm hover:shadow transition-all group relative overflow-hidden"
                    >
                        <span class="absolute inset-0 w-full h-full bg-white opacity-0 group-hover:opacity-10 transition-opacity"></span>
                        <span class="inline-flex items-center">
                            <i class="fas fa-times mr-1 text-gray-600"></i>
                        Exit
                        </span>
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
                <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-green-600 to-teal-600 text-white rounded-t-lg relative overflow-hidden">
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
                                <input type="radio" v-model="mcsSortOption" value="seq" class="form-radio h-4 w-4 text-blue-600">
                                <span class="ml-2 text-gray-700">MC Seq#</span>
                            </label>
                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md">
                                <input type="radio" v-model="mcsSortOption" value="model" class="form-radio h-4 w-4 text-blue-600">
                                <span class="ml-2 text-gray-700">MC Model</span>
                            </label>
                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md">
                                <input type="radio" v-model="mcsSortOption" value="part" class="form-radio h-4 w-4 text-blue-600">
                                <span class="ml-2 text-gray-700">MC PD Part#</span>
                            </label>
                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md">
                                <input type="radio" v-model="mcsSortOption" value="ed" class="form-radio h-4 w-4 text-blue-600">
                                <span class="ml-2 text-gray-700">MC PD ED</span>
                            </label>
                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md">
                                <input type="radio" v-model="mcsSortOption" value="id" class="form-radio h-4 w-4 text-blue-600">
                                <span class="ml-2 text-gray-700">MC PD ID</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Sort Order Options -->
                    <div class="bg-white border border-gray-300 rounded-md shadow-sm p-4">
                        <h4 class="font-semibold mb-3 text-gray-700 flex items-center">
                            <i class="fas fa-arrow-up-long mr-2 text-blue-500"></i>Sort Order:
                        </h4>
                        <div class="flex items-center space-x-6 ml-2">
                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md">
                                <input type="radio" v-model="mcsSortOrder" value="asc" class="form-radio h-4 w-4 text-blue-600">
                                <span class="ml-2 text-gray-700">Ascending</span>
                            </label>
                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md">
                                <input type="radio" v-model="mcsSortOrder" value="desc" class="form-radio h-4 w-4 text-blue-600">
                                <span class="ml-2 text-gray-700">Descending</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- MC Status Options -->
                    <div class="bg-white border border-gray-300 rounded-md shadow-sm p-4">
                        <h4 class="font-semibold mb-3 text-gray-700 flex items-center">
                            <i class="fas fa-tag mr-2 text-blue-500"></i>MC Status:
                        </h4>
                        <div class="flex items-center space-x-6 ml-2">
                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md">
                                <input type="checkbox" v-model="mcsRecordStatus.active" class="form-checkbox h-4 w-4 text-blue-600 rounded">
                                <span class="ml-2 text-gray-700">Active</span>
                            </label>
                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md">
                                <input type="checkbox" v-model="mcsRecordStatus.obsolete" class="form-checkbox h-4 w-4 text-blue-600 rounded">
                                <span class="ml-2 text-gray-700">Obsolete</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-center space-x-4 p-4 border-t border-gray-200 bg-gray-50 rounded-b-lg">
                    <button 
                        @click="applyMcsFilter" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-8 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        OK
                    </button>
                    <button 
                        @click="showMcsOptionsModal = false" 
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-8 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2"
                    >
                        Exit
                    </button>
                </div>
            </div>
        </div>

        <!-- MCS Table Modal (after selecting options) -->
        <div v-if="showMcsTableModal" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto">
            <!-- Modal backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="showMcsTableModal = false"></div>
            
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-xl w-4/5 max-w-4xl mx-auto z-10 transform transition-all">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-t-lg relative overflow-hidden">
                    <div class="absolute -top-8 -left-8 w-16 h-16 bg-white opacity-10 rounded-full"></div>
                    <div class="absolute -bottom-8 -right-8 w-16 h-16 bg-white opacity-10 rounded-full"></div>
                    
                    <h3 class="text-xl font-semibold flex items-center relative z-10">
                        <span class="inline-flex items-center justify-center w-8 h-8 bg-white bg-opacity-20 rounded-full mr-3 shadow-inner">
                            <i class="fas fa-id-card text-white"></i>
                        </span>
                        Master Card Seq Table
                    </h3>
                    <button type="button" @click="showMcsTableModal = false" 
                        class="text-white hover:text-gray-200 focus:outline-none transition-transform hover:scale-110 relative z-10 bg-red-500 bg-opacity-30 hover:bg-opacity-50 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="p-6 overflow-auto" style="max-height: 440px;">
                    <div class="mb-4 flex justify-between items-center">
                        <div class="text-gray-600 text-sm">
                            <span class="font-semibold">Filter:</span> 
                            {{ mcsRecordStatus.active && mcsRecordStatus.obsolete ? 'All Records' : 
                                mcsRecordStatus.active ? 'Active Records Only' : 
                                mcsRecordStatus.obsolete ? 'Obsolete Records Only' : 'No Records' }}
                        </div>
                        <div class="relative">
                            <input 
                                type="text" 
                                v-model="mcsSearchTerm" 
                                placeholder="Search..." 
                                class="border border-gray-300 rounded-md py-1 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                            <i class="fas fa-search absolute right-3 top-2 text-gray-400"></i>
                        </div>
                    </div>
                    <table class="min-w-full border border-gray-300 text-xs">
                        <thead class="bg-gray-100 sticky top-0">
                            <tr>
                                <th class="px-2 py-1 border border-gray-300 text-left">MC Seq#</th>
                                <th class="px-2 py-1 border border-gray-300 text-left">MC Model</th>
                                <th class="px-2 py-1 border border-gray-300 text-left">MC PD Part#</th>
                                <th class="px-2 py-1 border border-gray-300 text-left">MC PD ED</th>
                                <th class="px-2 py-1 border border-gray-300 text-left">MC PD ID</th>
                                <th class="px-2 py-1 border border-gray-300 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="(mcs, index) in filteredMcsData" :key="mcs.seq" 
                                class="hover:bg-blue-100 cursor-pointer"
                                :class="{ 'bg-blue-200': selectedMcs?.seq === mcs.seq }"
                                @click="selectForViewMcs(mcs)"
                                @dblclick="selectMcs(mcs)">
                                <td class="px-2 py-1 border border-gray-300">{{ mcs.seq }}</td>
                                <td class="px-2 py-1 border border-gray-300">{{ mcs.model }}</td>
                                <td class="px-2 py-1 border border-gray-300">{{ mcs.part }}</td>
                                <td class="px-2 py-1 border border-gray-300">{{ mcs.ed }}</td>
                                <td class="px-2 py-1 border border-gray-300">{{ mcs.id }}</td>
                                <td class="px-2 py-1 border border-gray-300">{{ mcs.status }}</td>
                            </tr>
                            <tr v-if="filteredMcsData.length === 0">
                                <td colspan="6" class="px-2 py-4 text-center text-gray-500 border border-gray-300">
                                    No master card records found matching your criteria
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="flex items-center justify-end gap-2 p-4 border-t border-gray-200 bg-gray-50 rounded-b-lg">
                    <button 
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-3 rounded text-xs"
                    >
                        More Options
                    </button>
                    <button 
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-3 rounded text-xs"
                    >
                        Zoom
                    </button>
                    <button 
                        @click="selectMcs(selectedMcs)" 
                        :disabled="!selectedMcs"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-xs disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Select
                    </button>
                    <button 
                        @click="showMcsTableModal = false" 
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-3 rounded text-xs"
                    >
                        Exit
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
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
const showCustomerAccountModal = ref(false);
const showCustomerAccountTable = ref(false);
const sortOption = ref('code');
const recordStatus = ref({
    active: true,
    obsolete: false
});
const tableSearchTerm = ref('');
const selectedCustomer = ref(null);

// MCS Modal state
const showMcsOptionsModal = ref(false);
const showMcsTableModal = ref(false);
const mcsSortOption = ref('seq');
const mcsSortOrder = ref('asc');
const mcsRecordStatus = ref({
    active: true,
    obsolete: false
});
const selectedMcs = ref(null);
const mcsSearchTerm = ref('');

// Sample customer data with additional fields to match screenshot
const customers = ref([
    { code: '000211-03', name: 'ABDULLAH, BPK', salesperson: 'S111', acType: 'Local', currency: 'IDR', status: 'Active' },
    { code: '000680-06', name: 'ACEP SUNANDAH, BPK', salesperson: 'S140', acType: 'Local', currency: 'IDR', status: 'Active' },
    { code: '000585-01', name: 'ACHMAD JAMAL', salesperson: 'S102', acType: 'Local', currency: 'IDR', status: 'Active' },
    { code: '000283', name: 'ACOSTA SUPER FOOD, PT', salesperson: 'S143', acType: 'Local', currency: 'IDR', status: 'Active' },
    { code: '000903', name: 'ADHITYA SERAYAKORITA, PT', salesperson: 'S103', acType: 'Local', currency: 'IDR', status: 'Active' },
    { code: '000507', name: 'ADISATYA GEMILANG, PT', salesperson: 'S140', acType: 'Local', currency: 'IDR', status: 'Active' },
    { code: '000851', name: 'ADEL LANGGENG, PT', salesperson: 'S143', acType: 'Local', currency: 'IDR', status: 'Active' },
    { code: '000004', name: 'AGILITY INTERNATIONAL, PT', salesperson: 'S118', acType: 'Local', currency: 'IDR', status: 'Active' },
    { code: '000676', name: 'AGRINDO MAJU LESTARI, PT', salesperson: 'S142', acType: 'Local', currency: 'IDR', status: 'Active' },
    { code: '000839', name: 'AGRO MEGA PERKASA, PT', salesperson: 'S111', acType: 'Local', currency: 'IDR', status: 'Active' },
    { code: '000767', name: 'AGUNG KEMUNING WIJAYA, PT', salesperson: 'S123', acType: 'Local', currency: 'IDR', status: 'Obsolete' }
]);

// Sample MCS data
const mcsData = ref([
    { seq: 'MC001', model: 'Box-Standard', part: 'P123', ed: 'ED001', id: 'ID001', status: 'Active' },
    { seq: 'MC002', model: 'Box-Premium', part: 'P124', ed: 'ED002', id: 'ID002', status: 'Active' },
    { seq: 'MC003', model: 'Container-Small', part: 'P125', ed: 'ED003', id: 'ID003', status: 'Obsolete' },
    { seq: 'MC004', model: 'Container-Medium', part: 'P126', ed: 'ED004', id: 'ID004', status: 'Active' },
    { seq: 'MC005', model: 'Container-Large', part: 'P127', ed: 'ED005', id: 'ID005', status: 'Active' },
]);

// Computed
const filteredCustomers = computed(() => {
    return customers.value.filter(customer => {
        // Filter by status
        if (recordStatus.value.active && customer.status === 'Active') {
            return true;
        }
        if (recordStatus.value.obsolete && customer.status === 'Obsolete') {
            return true;
        }
        return false;
    });
});

const filteredMcs = computed(() => {
    return mcsData.value.filter(mcs => {
        if (mcsRecordStatus.value.active && mcs.status === 'Active') {
            return true;
        }
        if (mcsRecordStatus.value.obsolete && mcs.status === 'Obsolete') {
            return true;
        }
        return false;
    });
});

// Filter and sort the displayed customers
const displayedCustomers = computed(() => {
    let result = [...filteredCustomers.value];
    
    // Apply search filter
    if (tableSearchTerm.value) {
        const searchLower = tableSearchTerm.value.toLowerCase();
        result = result.filter(customer => 
            customer.code.toLowerCase().includes(searchLower) ||
            customer.name.toLowerCase().includes(searchLower)
        );
    }
    
    // Apply sorting
    if (sortOption.value === 'code') {
        result.sort((a, b) => a.code.localeCompare(b.code));
    } else if (sortOption.value === 'name') {
        result.sort((a, b) => a.name.localeCompare(b.name));
    }
    
    return result;
});

// Filter and sort MCS data
const filteredMcsData = computed(() => {
    let result = [...filteredMcs.value];
    
    // Apply search filter if there's a search term
    if (mcsSearchTerm.value) {
        const searchLower = mcsSearchTerm.value.toLowerCase();
        result = result.filter(mcs => 
            mcs.seq.toLowerCase().includes(searchLower) ||
            mcs.model.toLowerCase().includes(searchLower) ||
            mcs.part.toLowerCase().includes(searchLower) ||
            mcs.ed.toLowerCase().includes(searchLower) ||
            mcs.id.toLowerCase().includes(searchLower)
        );
    }
    
    // Apply sorting
    result.sort((a, b) => {
        let aValue, bValue;
        
        // Determine which field to sort by
        switch (mcsSortOption.value) {
            case 'seq':
                aValue = a.seq;
                bValue = b.seq;
                break;
            case 'model':
                aValue = a.model;
                bValue = b.model;
                break;
            case 'part':
                aValue = a.part;
                bValue = b.part;
                break;
            case 'ed':
                aValue = a.ed;
                bValue = b.ed;
                break;
            case 'id':
                aValue = a.id;
                bValue = b.id;
                break;
            default:
                aValue = a.seq;
                bValue = b.seq;
        }
        
        // Apply sort order
        if (mcsSortOrder.value === 'asc') {
            return aValue.localeCompare(bValue);
        } else {
            return bValue.localeCompare(aValue);
        }
    });
    
    return result;
});

// Actions
const searchAc = async () => {
    try {
        const response = await axios.post('/api/update-mc/search-ac', {
            ac: form.value.ac
        });
        console.log(response.data);
        form.value.customer_name = 'Sample Customer';
        recordSelected.value = true;
    } catch (error) {
        console.error('Error searching AC:', error);
    }
};

const searchMcs = () => {
    // Show the options modal first
    showMcsOptionsModal.value = true;
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
            mcsTo: '',
            customer_name: '',
            product_code: '',
            status: 'active'
        };
        recordSelected.value = false;
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
    showCustomerAccountModal.value = true;
};

const applyFilter = () => {
    showCustomerAccountModal.value = false;
    showCustomerAccountTable.value = true;
};

const selectCustomer = (customer) => {
    if (!customer) return;
    
    selectedCustomer.value = customer;
    form.value.ac = customer.code;
    form.value.customer_name = customer.name;
    recordSelected.value = true;
    showCustomerAccountTable.value = false;
};

const selectForView = (customer) => {
    selectedCustomer.value = customer;
};

// MCS Modal actions

const searchMcsRange = () => {
    // Always show the options modal for MCS range search
    showMcsOptionsModal.value = true;
};

const applyMcsFilter = () => {
    showMcsOptionsModal.value = false;
    showMcsTableModal.value = true;
};

const selectMcs = (mcs) => {
    if (!mcs) return;
    
    selectedMcs.value = mcs;
    
    // Populate the mcs field
    form.value.mcs = mcs.seq;
    
    recordSelected.value = true;
    showMcsTableModal.value = false;
};

const selectForViewMcs = (mcs) => {
    selectedMcs.value = mcs;
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
</style>
