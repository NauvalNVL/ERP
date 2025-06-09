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
                                            @click="openCustomerAccountModal"
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
                                        <i class="fas fa-plus mr-2"></i>
                                        <span>Add New</span>
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

        <!-- Customer Account Modal -->
        <div v-if="showCustomerAccountModal" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto">
            <!-- Modal backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="showCustomerAccountModal = false"></div>
            
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-xl w-96 mx-auto max-w-lg z-10 transform transition-all">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
                    <h3 class="text-xl font-semibold flex items-center">
                        <i class="fas fa-filter mr-3"></i>Options
                    </h3>
                    <button type="button" @click="showCustomerAccountModal = false" class="text-white hover:text-gray-200 focus:outline-none">
                        <i class="fas fa-times text-xl"></i>
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
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-8 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        OK
                    </button>
                    <button 
                        @click="showCustomerAccountModal = false" 
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-8 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2"
                    >
                        Exit
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
                <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
                    <h3 class="text-xl font-semibold flex items-center">
                        <i class="fas fa-table mr-3"></i>Customer Account Table
                    </h3>
                    <button type="button" @click="showCustomerAccountTable = false" class="text-white hover:text-gray-200 focus:outline-none">
                        <i class="fas fa-times text-xl"></i>
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
                        @click="selectCustomer(selectedCustomer)" 
                        :disabled="!selectedCustomer"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-xs disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Select
                    </button>
                    <button 
                        @click="showCustomerAccountTable = false" 
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-3 rounded text-xs"
                    >
                        Exit
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
                <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
                    <h3 class="text-xl font-semibold flex items-center">
                        <i class="fas fa-filter mr-3"></i>Options
                    </h3>
                    <button type="button" @click="showMcsOptionsModal = false" class="text-white hover:text-gray-200 focus:outline-none">
                        <i class="fas fa-times text-xl"></i>
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
                <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
                    <h3 class="text-xl font-semibold flex items-center">
                        <i class="fas fa-table mr-3"></i>Master Card Seq Table
                    </h3>
                    <button type="button" @click="showMcsTableModal = false" class="text-white hover:text-gray-200 focus:outline-none">
                        <i class="fas fa-times text-xl"></i>
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
const applyMcsFilter = () => {
    showMcsOptionsModal.value = false;
    showMcsTableModal.value = true;
};

const selectMcs = (mcs) => {
    if (!mcs) return;
    
    selectedMcs.value = mcs;
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
