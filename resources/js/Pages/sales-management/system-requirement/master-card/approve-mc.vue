<!-- 
  Approve Master Card Component
  This component handles the approval of master cards in the system
-->
<template>
    <AppLayout :header="'Approve MC'">
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
                    <i class="fas fa-check-circle text-white text-2xl z-10"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-1 text-shadow">Approve Master Card</h2>
                    <p class="text-blue-100 max-w-2xl">Review and approve master cards submitted by users</p>
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
                        <div class="space-y-6">
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
                                            v-model="searchTerm"
                                            placeholder="Search master cards..."
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
                                        @click="handleAddNew"
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

                            <div>
                                <label for="mcs" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                    <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-pink-500 to-red-500 rounded-full mr-2 shadow-sm">
                                        <i class="fas fa-barcode text-white text-xs"></i>
                                    </span>
                                    MCS#:
                                </label>
                                <div class="flex items-center space-x-2">
                                    <div class="relative flex group flex-1">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 group-hover:bg-indigo-50 group-hover:text-indigo-500 transition-colors">
                                            <i class="fas fa-barcode"></i>
                                        </span>
                                        <input 
                                            type="text" 
                                            id="mcs" 
                                            v-model="mcsInput"
                                            placeholder="Enter MCS number"
                                            class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-all group-hover:border-indigo-300"
                                        />
                                        <button 
                                            type="button"
                                            @click="openMcsModal"
                                            class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-pink-500 to-red-500 hover:from-pink-600 hover:to-red-600 text-white rounded-r-md transition-all transform active:translate-y-px relative overflow-hidden shadow-sm"
                                        >
                                            <span class="absolute inset-0 bg-white opacity-0 hover:opacity-20 transition-opacity"></span>
                                            <i class="fas fa-search relative z-10"></i>
                                        </button>
                                    </div>
                                    
                                    <span class="text-gray-700 font-medium">to</span>
                                    
                                    <div class="relative flex group flex-1">
                                        <input 
                                            type="text" 
                                            id="mcsTo" 
                                            v-model="mcsInputTo"
                                            placeholder=""
                                            class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-all group-hover:border-indigo-300"
                                        />
                                        <button 
                                            type="button"
                                            @click="openMcsModalTo"
                                            class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-r-md transition-all transform active:translate-y-px relative overflow-hidden shadow-sm"
                                        >
                                            <span class="absolute inset-0 bg-white opacity-0 hover:opacity-20 transition-opacity"></span>
                                            <i class="fas fa-search relative z-10"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Master Card Table -->
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                MC Seq#
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                MC Model
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Customer
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr 
                                            v-for="mc in filteredMasterCards"
                                            :key="mc.id"
                                            class="hover:bg-indigo-50 transition-colors duration-150 cursor-pointer"
                                            :class="{ 'bg-indigo-100 border-l-4 border-indigo-500': selectedMasterCard?.id === mc.id }"
                                            @click="selectedMasterCard = mc"
                                        >
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-8 w-8 bg-gradient-to-r from-indigo-400 to-purple-500 text-white rounded-md flex items-center justify-center mr-3 shadow-sm">
                                                        <i class="fas fa-file-alt text-xs"></i>
                                                    </div>
                                                    {{ mc.mc_seq }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                {{ mc.mc_model }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                {{ mc.customer_name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span 
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                    :class="{
                                                        'bg-green-100 text-green-800': mc.status === 'active', 
                                                        'bg-yellow-100 text-yellow-800': mc.status === 'pending',
                                                        'bg-red-100 text-red-800': mc.status === 'obsolete'
                                                    }">
                                                    {{ mc.status.charAt(0).toUpperCase() + mc.status.slice(1) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <button class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white p-1.5 rounded shadow-sm hover:shadow transition-all duration-200" @click.stop="handleEdit(mc)">
                                                        <i class="fas fa-edit text-xs"></i>
                                                    </button>
                                                    <button class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white p-1.5 rounded shadow-sm hover:shadow transition-all duration-200" @click.stop="handleApprove(mc)">
                                                        <i class="fas fa-check text-xs"></i>
                                                    </button>
                                                    <button class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white p-1.5 rounded shadow-sm hover:shadow transition-all duration-200" @click.stop="handleReject(mc)">
                                                        <i class="fas fa-times text-xs"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-if="filteredMasterCards.length === 0">
                                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                                <div class="flex flex-col items-center">
                                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                                        <i class="fas fa-search text-gray-400 text-2xl"></i>
                                                    </div>
                                                    <p>No master cards found. Please adjust your search or filter criteria.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="px-6 py-3 bg-gray-50 border-t border-gray-200 flex items-center justify-between mt-4 rounded-b-lg">
                                <span class="text-sm text-gray-500">
                                    Showing {{ filteredMasterCards.length }} master cards
                                </span>
                                <div class="flex items-center space-x-2">
                                    <button class="px-3 py-1 bg-white border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50 shadow-sm">
                                        <i class="fas fa-chevron-left mr-1 text-xs"></i> Previous
                                    </button>
                                    <button class="px-3 py-1 bg-gradient-to-r from-indigo-500 to-purple-600 border border-indigo-500 rounded-md text-sm text-white hover:from-indigo-600 hover:to-purple-700 shadow-sm">
                                        Next <i class="fas fa-chevron-right ml-1 text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
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
                                        <span>Click on a record to view details and approve/reject</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-green-400 to-emerald-400 rounded-full mr-2 shadow-sm mt-0.5">
                                            <i class="fas fa-check-circle text-white text-xs"></i>
                                        </span>
                                        <span>Use the action buttons to approve or reject master cards</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Selected Master Card Detail -->
                        <div v-if="selectedMasterCard" class="mt-6">
                            <div class="p-4 bg-indigo-50 rounded-lg border border-indigo-100">
                                <h4 class="text-sm font-semibold text-indigo-800 uppercase tracking-wider mb-3 flex items-center">
                                    <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full mr-2 shadow-sm">
                                        <i class="fas fa-id-card text-white text-xs"></i>
                                    </span>
                                    Selected Master Card
                                </h4>
                                
                                <div class="flex items-center mb-3">
                                    <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-indigo-500 to-purple-600 text-white rounded-lg flex items-center justify-center mr-3 shadow-md">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <div>
                                        <h5 class="text-sm font-medium text-gray-900">{{ selectedMasterCard.mc_model }}</h5>
                                        <p class="text-xs text-indigo-600 font-mono">{{ selectedMasterCard.mc_seq }}</p>
                                    </div>
                                </div>
                                
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-500">Customer:</span>
                                        <span class="font-medium text-gray-900">{{ selectedMasterCard.customer_name }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-500">Status:</span>
                                        <span 
                                            class="px-2 py-1 rounded-full text-xs font-medium"
                                            :class="{
                                                'bg-gradient-to-r from-green-400 to-green-500 text-white': selectedMasterCard.status === 'active', 
                                                'bg-gradient-to-r from-yellow-400 to-yellow-500 text-white': selectedMasterCard.status === 'pending',
                                                'bg-gradient-to-r from-red-400 to-red-500 text-white': selectedMasterCard.status === 'obsolete'
                                            }">
                                            {{ selectedMasterCard.status.charAt(0).toUpperCase() + selectedMasterCard.status.slice(1) }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="mt-4 grid grid-cols-2 gap-2">
                                    <button 
                                        @click="handleApprove(selectedMasterCard)" 
                                        class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-3 py-1.5 rounded-lg flex items-center justify-center space-x-1 transform active:translate-y-px transition-all duration-300 shadow-md text-sm"
                                    >
                                        <i class="fas fa-check text-xs"></i>
                                        <span>Approve</span>
                                    </button>
                                    <button 
                                        @click="handleReject(selectedMasterCard)" 
                                        class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-3 py-1.5 rounded-lg flex items-center justify-center space-x-1 transform active:translate-y-px transition-all duration-300 shadow-md text-sm"
                                    >
                                        <i class="fas fa-times text-xs"></i>
                                        <span>Reject</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Filter Options -->
                        <div class="mt-6">
                            <h4 class="text-sm font-semibold text-gray-800 uppercase tracking-wider mb-3 flex items-center">
                                <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-full mr-2 shadow-sm">
                                    <i class="fas fa-filter text-white text-xs"></i>
                                </span>
                                Filter Options
                            </h4>
                            
                            <div class="space-y-3">
                                <div>
                                    <label class="text-xs font-medium text-gray-700 block mb-1">Status:</label>
                                    <div class="flex space-x-2">
                                        <label class="inline-flex items-center px-2 py-1 bg-gray-50 border border-gray-200 rounded-md text-xs cursor-pointer hover:bg-white transition-colors">
                                            <input type="radio" name="recordStatus" value="all" v-model="recordStatus" class="h-3 w-3 text-blue-600 mr-1">
                                            <span>All</span>
                                        </label>
                                        <label class="inline-flex items-center px-2 py-1 bg-green-50 border border-green-200 rounded-md text-xs cursor-pointer hover:bg-white transition-colors">
                                            <input type="radio" name="recordStatus" value="active" v-model="recordStatus" class="h-3 w-3 text-green-600 mr-1">
                                            <span>Active</span>
                                        </label>
                                        <label class="inline-flex items-center px-2 py-1 bg-yellow-50 border border-yellow-200 rounded-md text-xs cursor-pointer hover:bg-white transition-colors">
                                            <input type="radio" name="recordStatus" value="pending" v-model="recordStatus" class="h-3 w-3 text-yellow-600 mr-1">
                                            <span>Pending</span>
                                        </label>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="text-xs font-medium text-gray-700 block mb-1">Sort by:</label>
                                    <select 
                                        v-model="sortBy" 
                                        class="block w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                    >
                                        <option value="seq">MC Sequence</option>
                                        <option value="model">MC Model</option>
                                        <option value="customer">Customer</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approval Confirmation Modal -->
        <div v-if="showApprovalModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" aria-hidden="true" @click="showApprovalModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full animate-modalScaleIn">
                    <!-- Modal Header with gradient -->
                    <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4 relative overflow-hidden">
                        <div class="absolute -top-8 -left-8 w-16 h-16 bg-white opacity-10 rounded-full"></div>
                        <div class="absolute -bottom-8 -right-8 w-16 h-16 bg-white opacity-10 rounded-full"></div>
                        
                        <div class="flex items-center">
                            <div class="bg-white bg-opacity-20 p-2 rounded-full mr-3">
                                <i class="fas fa-check text-white text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white">Approve Master Card</h3>
                        </div>
                    </div>
                    
                    <div class="bg-white px-6 py-5">
                        <div class="sm:flex sm:items-start">
                            <div class="bg-gradient-to-br from-green-400 to-green-500 p-3 rounded-lg shadow-md flex-shrink-0 mr-4">
                                <i class="fas fa-file-alt text-white text-lg"></i>
                            </div>
                            <div>
                                <p class="text-gray-600 mb-3">
                                    Are you sure you want to approve this master card? This action cannot be undone.
                                </p>
                                <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-3">
                                    <div class="flex items-center mb-1">
                                        <i class="fas fa-info-circle text-green-600 mr-2"></i>
                                        <span class="font-medium text-green-900">Master Card Details</span>
                                    </div>
                                    <p class="text-sm text-green-800">
                                        <span class="font-medium">MC Seq#:</span> {{ masterCardToAction?.mc_seq }}
                                    </p>
                                    <p class="text-sm text-green-800">
                                        <span class="font-medium">Model:</span> {{ masterCardToAction?.mc_model }}
                                    </p>
                                    <p class="text-sm text-green-800">
                                        <span class="font-medium">Customer:</span> {{ masterCardToAction?.customer_name }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                        <button 
                            type="button" 
                            class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-5 py-2 rounded-lg flex items-center space-x-2 transform active:translate-y-px transition-all duration-300 shadow-md relative overflow-hidden group"
                            @click="confirmApproval"
                        >
                            <span class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity"></span>
                            <i class="fas fa-check text-sm mr-1"></i>
                            <span>Approve</span>
                        </button>
                        <button 
                            type="button" 
                            class="bg-gradient-to-r from-gray-200 to-gray-300 hover:from-gray-300 hover:to-gray-400 text-gray-700 px-5 py-2 rounded-lg flex items-center space-x-2 transform active:translate-y-px transition-all duration-300 shadow-md relative overflow-hidden group"
                            @click="showApprovalModal = false"
                        >
                            <span class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity"></span>
                            <i class="fas fa-times text-sm mr-1"></i>
                            <span>Cancel</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rejection Modal -->
        <div v-if="showRejectionModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" aria-hidden="true" @click="showRejectionModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full animate-modalScaleIn">
                    <!-- Modal Header with gradient -->
                    <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4 relative overflow-hidden">
                        <div class="absolute -top-8 -left-8 w-16 h-16 bg-white opacity-10 rounded-full"></div>
                        <div class="absolute -bottom-8 -right-8 w-16 h-16 bg-white opacity-10 rounded-full"></div>
                        
                        <div class="flex items-center">
                            <div class="bg-white bg-opacity-20 p-2 rounded-full mr-3">
                                <i class="fas fa-times text-white text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white">Reject Master Card</h3>
                        </div>
                    </div>
                    
                    <div class="bg-white px-6 py-5">
                        <div class="sm:flex sm:items-start mb-4">
                            <div class="bg-gradient-to-br from-red-400 to-red-500 p-3 rounded-lg shadow-md flex-shrink-0 mr-4">
                                <i class="fas fa-file-alt text-white text-lg"></i>
                            </div>
                            <div>
                                <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-3">
                                    <div class="flex items-center mb-1">
                                        <i class="fas fa-info-circle text-red-600 mr-2"></i>
                                        <span class="font-medium text-red-900">Master Card Details</span>
                                    </div>
                                    <p class="text-sm text-red-800">
                                        <span class="font-medium">MC Seq#:</span> {{ masterCardToAction?.mc_seq }}
                                    </p>
                                    <p class="text-sm text-red-800">
                                        <span class="font-medium">Model:</span> {{ masterCardToAction?.mc_model }}
                                    </p>
                                    <p class="text-sm text-red-800">
                                        <span class="font-medium">Customer:</span> {{ masterCardToAction?.customer_name }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-red-500 to-red-600 rounded-full mr-2 shadow-sm">
                                    <i class="fas fa-comment-alt text-white text-xs"></i>
                                </span>
                                Rejection Reason:
                            </label>
                            <textarea 
                                v-model="rejectionReason" 
                                rows="4" 
                                placeholder="Please provide a detailed reason for rejecting this master card..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm"
                            ></textarea>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                        <button 
                            type="button" 
                            class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-5 py-2 rounded-lg flex items-center space-x-2 transform active:translate-y-px transition-all duration-300 shadow-md relative overflow-hidden group"
                            @click="confirmRejection"
                        >
                            <span class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity"></span>
                            <i class="fas fa-times text-sm mr-1"></i>
                            <span>Reject</span>
                        </button>
                        <button 
                            type="button" 
                            class="bg-gradient-to-r from-gray-200 to-gray-300 hover:from-gray-300 hover:to-gray-400 text-gray-700 px-5 py-2 rounded-lg flex items-center space-x-2 transform active:translate-y-px transition-all duration-300 shadow-md relative overflow-hidden group"
                            @click="showRejectionModal = false"
                        >
                            <span class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity"></span>
                            <i class="fas fa-arrow-left text-sm mr-1"></i>
                            <span>Cancel</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Option Modal -->
        <div v-if="showOptionModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showOptionModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full animate-modalScaleIn">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-4 py-3">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">
                                Select Option
                            </h3>
                            <button 
                                @click="showOptionModal = false" 
                                class="text-white opacity-70 hover:opacity-100 focus:outline-none"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="bg-white p-6">
                        <div class="bg-gray-50 border border-gray-200 rounded-md p-4 mb-6">
                            <div class="space-y-3">
                                <label class="flex items-center cursor-pointer p-2 hover:bg-gray-100 rounded-md transition-colors">
                                    <input type="radio" v-model="selectOption" value="customer" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <div class="ml-3">
                                        <span class="font-medium text-gray-900 block">Select by Customer</span>
                                        <span class="text-xs text-gray-500">Filter master cards by customer information</span>
                                    </div>
                                </label>
                                <label class="flex items-center cursor-pointer p-2 hover:bg-gray-100 rounded-md transition-colors">
                                    <input type="radio" v-model="selectOption" value="mastercard" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <div class="ml-3">
                                        <span class="font-medium text-gray-900 block">Select by Master Card</span>
                                        <span class="text-xs text-gray-500">Filter by master card attributes</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                        <div class="flex justify-center space-x-4">
                            <button 
                                type="button" 
                                class="inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" 
                                @click="handleSelectOption"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Apply
                            </button>
                            <button 
                                type="button" 
                                class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" 
                                @click="showOptionModal = false"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Master Card Modal -->
        <master-card-modal
            v-if="showEditModal"
            :show="showEditModal"
            :master-card="editingMasterCard"
            :mode="modalMode"
            @close="closeEditModal"
            @update="handleUpdateMasterCard"
        />

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
                                <input type="checkbox" v-model="recordStatusFilter.active" class="form-checkbox h-4 w-4 text-blue-600 rounded">
                                <span class="ml-2 text-gray-700">Active</span>
                            </label>
                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md">
                                <input type="checkbox" v-model="recordStatusFilter.obsolete" class="form-checkbox h-4 w-4 text-blue-600 rounded">
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
                            <i class="fas fa-times mr-2"></i> Exit
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
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-4xl mx-auto z-10 transform transition-all border-2 border-indigo-200">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-5 border-b-2 border-indigo-200 bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 text-white rounded-t-2xl relative overflow-hidden">
                    <!-- Decorative animated circles -->
                    <div class="absolute -top-8 -left-8 w-24 h-24 bg-white opacity-10 rounded-full animate-ping-slow"></div>
                    <div class="absolute -bottom-8 -right-8 w-20 h-20 bg-cyan-300 opacity-10 rounded-full animate-ping-slow animation-delay-500"></div>
                    <div class="absolute top-0 right-0 w-16 h-16 bg-white opacity-5 rounded-full"></div>
                    <h3 class="text-2xl font-bold flex items-center relative z-10 text-shadow">
                        <span class="inline-flex items-center justify-center w-10 h-10 bg-white bg-opacity-20 rounded-full mr-4 shadow-inner">
                            <i class="fas fa-table text-white text-xl"></i>
                        </span>
                        Customer Account Table
                    </h3>
                    <button type="button" @click="showCustomerAccountTable = false" 
                        class="text-white hover:text-gray-200 focus:outline-none transition-transform hover:scale-110 relative z-10 bg-red-500 bg-opacity-30 hover:bg-opacity-50 rounded-full w-10 h-10 flex items-center justify-center">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="p-6 bg-gradient-to-br from-white via-blue-50 to-cyan-50 rounded-b-2xl overflow-auto" style="max-height: 440px;">
                    <div class="mb-4 flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="text-gray-700 text-base font-medium">
                            <span class="font-bold text-indigo-700">Filter:</span> 
                            <span class="text-gray-600">{{ recordStatusFilter.active && recordStatusFilter.obsolete ? 'All Records' : recordStatusFilter.active ? 'Active Records Only' : recordStatusFilter.obsolete ? 'Obsolete Records Only' : 'No Records' }}</span>
                        </div>
                        <div class="relative w-full md:w-64">
                            <input 
                                type="text" 
                                v-model="tableSearchTerm" 
                                placeholder="Search..." 
                                class="border border-indigo-200 rounded-lg py-2 px-4 text-base focus:outline-none focus:ring-2 focus:ring-blue-400 w-full shadow-sm"
                            />
                            <i class="fas fa-search absolute right-4 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto rounded-lg shadow">
                    <table class="min-w-full border border-indigo-200 text-base bg-white rounded-lg overflow-hidden">
                        <thead class="bg-gradient-to-r from-blue-500 via-cyan-500 to-teal-400 text-white">
                            <tr>
                                <th class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold">Customer Name</th>
                                <th class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold">Customer Code</th>
                                <th class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold">S/person</th>
                                <th class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold">AC Type</th>
                                <th class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold">Currency</th>
                                <th class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-indigo-100">
                            <tr v-for="(customer, index) in displayedCustomers" :key="customer.code" 
                                class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-cyan-100 cursor-pointer transition-all duration-150"
                                :class="{ 'bg-gradient-to-r from-blue-100 to-cyan-100': selectedCustomer?.code === customer.code }"
                                @click="selectForView(customer)" 
                                @dblclick="selectCustomer(customer)">
                                <td class="px-4 py-2 border-b border-indigo-100 font-semibold">{{ customer.name }}</td>
                                <td class="px-4 py-2 border-b border-indigo-100">{{ customer.code }}</td>
                                <td class="px-4 py-2 border-b border-indigo-100">{{ customer.salesperson }}</td>
                                <td class="px-4 py-2 border-b border-indigo-100">{{ customer.acType }}</td>
                                <td class="px-4 py-2 border-b border-indigo-100">{{ customer.currency }}</td>
                                <td class="px-4 py-2 border-b border-indigo-100">
                                    <span v-if="customer.status === 'Active'" class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-green-400 to-emerald-500 text-white shadow">Active</span>
                                    <span v-else-if="customer.status === 'Obsolete'" class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-rose-400 to-red-500 text-white shadow">Obsolete</span>
                                    <span v-else class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-gray-400 to-gray-500 text-white shadow">{{ customer.status }}</span>
                                </td>
                            </tr>
                            <tr v-if="displayedCustomers.length === 0">
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500 border-b border-indigo-100 bg-white">
                                    No customer accounts found matching your criteria
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
                
                <div class="flex flex-wrap items-center justify-end gap-3 p-5 border-t-2 border-indigo-200 bg-gradient-to-r from-blue-50 via-cyan-50 to-white rounded-b-2xl">
                    <button 
                        class="relative overflow-hidden font-bold py-2 px-5 rounded-xl text-base shadow-lg transition-all duration-200 group flex items-center border-0 focus:outline-none bg-gradient-to-r from-teal-400 via-blue-500 to-indigo-500 text-white before:absolute before:inset-0 before:rounded-xl before:bg-gradient-to-r before:from-cyan-200 before:via-blue-300 before:to-indigo-200 before:opacity-0 group-hover:before:opacity-40 before:blur-sm before:transition-opacity before:duration-300 group-hover:scale-105 group-hover:shadow-2xl"
                    >
                        <span class="relative z-10 flex items-center">
                            <i class="fas fa-cog mr-2 animate-spin-slow"></i> More Options
                        </span>
                    </button>
                    <button 
                        class="bg-gradient-to-r from-blue-400 to-cyan-500 hover:from-blue-500 hover:to-cyan-600 text-white font-bold py-2 px-4 rounded-lg text-base shadow-sm hover:shadow transition-all group relative overflow-hidden flex items-center"
                    >
                        <span class="absolute inset-0 w-full h-full bg-white opacity-0 group-hover:opacity-10 transition-opacity"></span>
                        <i class="fas fa-search-plus mr-2"></i> Zoom
                    </button>
                    <button 
                        @click="selectCustomer(selectedCustomer)" 
                        :disabled="!selectedCustomer"
                        class="bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-bold py-2 px-4 rounded-lg text-base shadow-sm hover:shadow disabled:opacity-50 disabled:cursor-not-allowed transition-all group relative overflow-hidden flex items-center"
                    >
                        <span class="absolute inset-0 w-full h-full bg-white opacity-0 group-hover:opacity-10 transition-opacity"></span>
                        <i class="fas fa-check-circle mr-2"></i> Select
                    </button>
                    <button 
                        @click="showCustomerAccountTable = false" 
                        class="relative overflow-hidden font-bold py-2 px-5 rounded-xl text-base shadow-lg transition-all duration-200 group flex items-center border-0 focus:outline-none bg-gradient-to-r from-pink-500 via-red-500 to-orange-400 text-white before:absolute before:inset-0 before:rounded-xl before:bg-gradient-to-r before:from-rose-200 before:via-red-200 before:to-orange-100 before:opacity-0 group-hover:before:opacity-40 before:blur-sm before:transition-opacity before:duration-300 group-hover:scale-105 group-hover:shadow-2xl"
                    >
                        <span class="relative z-10 flex items-center">
                            <i class="fas fa-times mr-2"></i> Exit
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
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-4xl mx-auto z-10 transform transition-all border-2 border-indigo-200">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-5 border-b-2 border-indigo-200 bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 text-white rounded-t-2xl relative overflow-hidden">
                    <!-- Decorative animated circles -->
                    <div class="absolute -top-8 -left-8 w-24 h-24 bg-white opacity-10 rounded-full animate-ping-slow"></div>
                    <div class="absolute -bottom-8 -right-8 w-20 h-20 bg-cyan-300 opacity-10 rounded-full animate-ping-slow animation-delay-500"></div>
                    <div class="absolute top-0 right-0 w-16 h-16 bg-white opacity-5 rounded-full"></div>
                    <h3 class="text-2xl font-bold flex items-center relative z-10 text-shadow">
                        <span class="inline-flex items-center justify-center w-10 h-10 bg-white bg-opacity-20 rounded-full mr-4 shadow-inner">
                            <i class="fas fa-id-card text-white text-xl"></i>
                        </span>
                        Master Card Seq Table
                    </h3>
                    <button type="button" @click="showMcsTableModal = false" 
                        class="text-white hover:text-gray-200 focus:outline-none transition-transform hover:scale-110 relative z-10 bg-red-500 bg-opacity-30 hover:bg-opacity-50 rounded-full w-10 h-10 flex items-center justify-center">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="p-6 bg-gradient-to-br from-white via-blue-50 to-cyan-50 rounded-b-2xl overflow-auto" style="max-height: 440px;">
                    <div class="mb-4 flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="text-gray-700 text-base font-medium">
                            <span class="font-bold text-indigo-700">Filter:</span> 
                            <span class="text-gray-600">{{ mcsRecordStatus.active && mcsRecordStatus.obsolete ? 'All Records' : mcsRecordStatus.active ? 'Active Records Only' : mcsRecordStatus.obsolete ? 'Obsolete Records Only' : 'No Records' }}</span>
                        </div>
                        <div class="relative w-full md:w-64">
                            <input 
                                type="text" 
                                v-model="mcsSearchTerm" 
                                placeholder="Search..." 
                                class="border border-indigo-200 rounded-lg py-2 px-4 text-base focus:outline-none focus:ring-2 focus:ring-blue-400 w-full shadow-sm"
                            />
                            <i class="fas fa-search absolute right-4 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    <div class="overflow-x-auto rounded-lg shadow">
                    <table class="min-w-full border border-indigo-200 text-base bg-white rounded-lg overflow-hidden">
                        <thead class="bg-gradient-to-r from-blue-500 via-cyan-500 to-teal-400 text-white">
                            <tr>
                                <th class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold">MC Seq#</th>
                                <th class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold">MC Model</th>
                                <th class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold">MC PD Part#</th>
                                <th class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold">MC PD ED</th>
                                <th class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold">MC PD ID</th>
                                <th class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-indigo-100">
                            <tr v-for="(mcs, index) in filteredMcsData" :key="mcs.seq" 
                                class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-cyan-100 cursor-pointer transition-all duration-150"
                                :class="{ 'bg-gradient-to-r from-blue-100 to-cyan-100': selectedMcs?.seq === mcs.seq }"
                                @click="selectForViewMcs(mcs)"
                                @dblclick="selectMcs(mcs)">
                                <td class="px-4 py-2 border-b border-indigo-100 font-semibold">{{ mcs.seq }}</td>
                                <td class="px-4 py-2 border-b border-indigo-100">{{ mcs.model }}</td>
                                <td class="px-4 py-2 border-b border-indigo-100">{{ mcs.part }}</td>
                                <td class="px-4 py-2 border-b border-indigo-100">{{ mcs.ed }}</td>
                                <td class="px-4 py-2 border-b border-indigo-100">{{ mcs.id }}</td>
                                <td class="px-4 py-2 border-b border-indigo-100">
                                    <span v-if="mcs.status === 'Active'" class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-green-400 to-emerald-500 text-white shadow">Active</span>
                                    <span v-else-if="mcs.status === 'Obsolete'" class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-rose-400 to-red-500 text-white shadow">Obsolete</span>
                                    <span v-else class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-gray-400 to-gray-500 text-white shadow">{{ mcs.status }}</span>
                                </td>
                            </tr>
                            <tr v-if="filteredMcsData.length === 0">
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500 border-b border-indigo-100 bg-white">
                                    No master card records found matching your criteria
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                </div>
                <div class="flex flex-wrap items-center justify-end gap-3 p-5 border-t-2 border-indigo-200 bg-gradient-to-r from-blue-50 via-cyan-50 to-white rounded-b-2xl">
                    <button 
                        class="relative overflow-hidden font-bold py-2 px-5 rounded-xl text-base shadow-lg transition-all duration-200 group flex items-center border-0 focus:outline-none bg-gradient-to-r from-teal-400 via-blue-500 to-indigo-500 text-white before:absolute before:inset-0 before:rounded-xl before:bg-gradient-to-r before:from-cyan-200 before:via-blue-300 before:to-indigo-200 before:opacity-0 group-hover:before:opacity-40 before:blur-sm before:transition-opacity before:duration-300 group-hover:scale-105 group-hover:shadow-2xl"
                    >
                        <span class="relative z-10 flex items-center">
                            <i class="fas fa-cog mr-2 animate-spin-slow"></i> More Options
                        </span>
                    </button>
                    <button 
                        class="bg-gradient-to-r from-blue-400 to-cyan-500 hover:from-blue-500 hover:to-cyan-600 text-white font-bold py-2 px-4 rounded-lg text-base shadow-sm hover:shadow transition-all group relative overflow-hidden flex items-center"
                    >
                        <span class="absolute inset-0 w-full h-full bg-white opacity-0 group-hover:opacity-10 transition-opacity"></span>
                        <i class="fas fa-search-plus mr-2"></i> Zoom
                    </button>
                    <button 
                        @click="selectMcs(selectedMcs)" 
                        :disabled="!selectedMcs"
                        class="bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-bold py-2 px-4 rounded-lg text-base shadow-sm hover:shadow disabled:opacity-50 disabled:cursor-not-allowed transition-all group relative overflow-hidden flex items-center"
                    >
                        <span class="absolute inset-0 w-full h-full bg-white opacity-0 group-hover:opacity-10 transition-opacity"></span>
                        <i class="fas fa-check-circle mr-2"></i> Select
                    </button>
                    <button 
                        @click="showMcsTableModal = false" 
                        class="relative overflow-hidden font-bold py-2 px-5 rounded-xl text-base shadow-lg transition-all duration-200 group flex items-center border-0 focus:outline-none bg-gradient-to-r from-pink-500 via-red-500 to-orange-400 text-white before:absolute before:inset-0 before:rounded-xl before:bg-gradient-to-r before:from-rose-200 before:via-red-200 before:to-orange-100 before:opacity-0 group-hover:before:opacity-40 before:blur-sm before:transition-opacity before:duration-300 group-hover:scale-105 group-hover:shadow-2xl"
                    >
                        <span class="relative z-10 flex items-center">
                            <i class="fas fa-times mr-2"></i> Exit
                        </span>
                    </button>
                </div>
            </div>
        </div>

        <!-- MCS Options Modal To -->
        <div v-if="showMcsOptionsModalTo" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto">
            <!-- Modal backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="showMcsOptionsModalTo = false"></div>
            
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
                    <button type="button" @click="showMcsOptionsModalTo = false" 
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
                        @click="applyMcsFilterTo" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-8 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        OK
                    </button>
                    <button 
                        @click="showMcsOptionsModalTo = false" 
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-8 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2"
                    >
                        Exit
                    </button>
                </div>
            </div>
        </div>

        <!-- MCS Table Modal To (after selecting options) -->
        <div v-if="showMcsTableModalTo" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto">
            <!-- Modal backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="showMcsTableModalTo = false"></div>
            
            <!-- Modal content -->
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-4xl mx-auto z-10 transform transition-all border-2 border-indigo-200">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-5 border-b-2 border-indigo-200 bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 text-white rounded-t-2xl relative overflow-hidden">
                    <!-- Decorative animated circles -->
                    <div class="absolute -top-8 -left-8 w-24 h-24 bg-white opacity-10 rounded-full animate-ping-slow"></div>
                    <div class="absolute -bottom-8 -right-8 w-20 h-20 bg-cyan-300 opacity-10 rounded-full animate-ping-slow animation-delay-500"></div>
                    <div class="absolute top-0 right-0 w-16 h-16 bg-white opacity-5 rounded-full"></div>
                    <h3 class="text-2xl font-bold flex items-center relative z-10 text-shadow">
                        <span class="inline-flex items-center justify-center w-10 h-10 bg-white bg-opacity-20 rounded-full mr-4 shadow-inner">
                            <i class="fas fa-id-card text-white text-xl"></i>
                        </span>
                        Master Card Seq Table
                    </h3>
                    <button type="button" @click="showMcsTableModalTo = false" 
                        class="text-white hover:text-gray-200 focus:outline-none transition-transform hover:scale-110 relative z-10 bg-red-500 bg-opacity-30 hover:bg-opacity-50 rounded-full w-10 h-10 flex items-center justify-center">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="p-6 bg-gradient-to-br from-white via-blue-50 to-cyan-50 rounded-b-2xl overflow-auto" style="max-height: 440px;">
                    <div class="mb-4 flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="text-gray-700 text-base font-medium">
                            <span class="font-bold text-indigo-700">Filter:</span> 
                            <span class="text-gray-600">{{ mcsRecordStatus.active && mcsRecordStatus.obsolete ? 'All Records' : mcsRecordStatus.active ? 'Active Records Only' : mcsRecordStatus.obsolete ? 'Obsolete Records Only' : 'No Records' }}</span>
                        </div>
                        <div class="relative w-full md:w-64">
                            <input 
                                type="text" 
                                v-model="mcsSearchTermTo" 
                                placeholder="Search..." 
                                class="border border-indigo-200 rounded-lg py-2 px-4 text-base focus:outline-none focus:ring-2 focus:ring-blue-400 w-full shadow-sm"
                            />
                            <i class="fas fa-search absolute right-4 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    <div class="overflow-x-auto rounded-lg shadow">
                    <table class="min-w-full border border-indigo-200 text-base bg-white rounded-lg overflow-hidden">
                        <thead class="bg-gradient-to-r from-blue-500 via-cyan-500 to-teal-400 text-white">
                            <tr>
                                <th class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold">MC Seq#</th>
                                <th class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold">MC Model</th>
                                <th class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold">MC PD Part#</th>
                                <th class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold">MC PD ED</th>
                                <th class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold">MC PD ID</th>
                                <th class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-indigo-100">
                            <tr v-for="(mcs, index) in filteredMcsDataTo" :key="mcs.seq" 
                                class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-cyan-100 cursor-pointer transition-all duration-150"
                                :class="{ 'bg-gradient-to-r from-blue-100 to-cyan-100': selectedMcsTo?.seq === mcs.seq }"
                                @click="selectForViewMcsTo(mcs)"
                                @dblclick="selectMcsTo(mcs)">
                                <td class="px-4 py-2 border-b border-indigo-100 font-semibold">{{ mcs.seq }}</td>
                                <td class="px-4 py-2 border-b border-indigo-100">{{ mcs.model }}</td>
                                <td class="px-4 py-2 border-b border-indigo-100">{{ mcs.part }}</td>
                                <td class="px-4 py-2 border-b border-indigo-100">{{ mcs.ed }}</td>
                                <td class="px-4 py-2 border-b border-indigo-100">{{ mcs.id }}</td>
                                <td class="px-4 py-2 border-b border-indigo-100">
                                    <span v-if="mcs.status === 'Active'" class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-green-400 to-emerald-500 text-white shadow">Active</span>
                                    <span v-else-if="mcs.status === 'Obsolete'" class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-rose-400 to-red-500 text-white shadow">Obsolete</span>
                                    <span v-else class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-gray-400 to-gray-500 text-white shadow">{{ mcs.status }}</span>
                                </td>
                            </tr>
                            <tr v-if="filteredMcsDataTo.length === 0">
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500 border-b border-indigo-100 bg-white">
                                    No master card records found matching your criteria
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                </div>
                <div class="flex flex-wrap items-center justify-end gap-3 p-5 border-t-2 border-indigo-200 bg-gradient-to-r from-blue-50 via-cyan-50 to-white rounded-b-2xl">
                    <button 
                        class="relative overflow-hidden font-bold py-2 px-5 rounded-xl text-base shadow-lg transition-all duration-200 group flex items-center border-0 focus:outline-none bg-gradient-to-r from-teal-400 via-blue-500 to-indigo-500 text-white before:absolute before:inset-0 before:rounded-xl before:bg-gradient-to-r before:from-cyan-200 before:via-blue-300 before:to-indigo-200 before:opacity-0 group-hover:before:opacity-40 before:blur-sm before:transition-opacity before:duration-300 group-hover:scale-105 group-hover:shadow-2xl"
                    >
                        <span class="relative z-10 flex items-center">
                            <i class="fas fa-cog mr-2 animate-spin-slow"></i> More Options
                        </span>
                    </button>
                    <button 
                        class="bg-gradient-to-r from-blue-400 to-cyan-500 hover:from-blue-500 hover:to-cyan-600 text-white font-bold py-2 px-4 rounded-lg text-base shadow-sm hover:shadow transition-all group relative overflow-hidden flex items-center"
                    >
                        <span class="absolute inset-0 w-full h-full bg-white opacity-0 group-hover:opacity-10 transition-opacity"></span>
                        <i class="fas fa-search-plus mr-2"></i> Zoom
                    </button>
                    <button 
                        @click="selectMcsTo(selectedMcsTo)" 
                        :disabled="!selectedMcsTo"
                        class="bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-bold py-2 px-4 rounded-lg text-base shadow-sm hover:shadow disabled:opacity-50 disabled:cursor-not-allowed transition-all group relative overflow-hidden flex items-center"
                    >
                        <span class="absolute inset-0 w-full h-full bg-white opacity-0 group-hover:opacity-10 transition-opacity"></span>
                        <i class="fas fa-check-circle mr-2"></i> Select
                    </button>
                    <button 
                        @click="showMcsTableModalTo = false" 
                        class="relative overflow-hidden font-bold py-2 px-5 rounded-xl text-base shadow-lg transition-all duration-200 group flex items-center border-0 focus:outline-none bg-gradient-to-r from-pink-500 via-red-500 to-orange-400 text-white before:absolute before:inset-0 before:rounded-xl before:bg-gradient-to-r before:from-rose-200 before:via-red-200 before:to-orange-100 before:opacity-0 group-hover:before:opacity-40 before:blur-sm before:transition-opacity before:duration-300 group-hover:scale-105 group-hover:shadow-2xl"
                    >
                        <span class="relative z-10 flex items-center">
                            <i class="fas fa-times mr-2"></i> Exit
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import MasterCardModal from '@/Components/master-card-modal.vue';

const props = defineProps({
    masterCards: Array,
    customers: Array,
});

// Define reactive state variables
const sortBy = ref('seq');
const recordStatus = ref('all');
const selectBy = ref('customer');
const selectOption = ref('customer');
const showOptions = ref(false);
const showOptionModal = ref(false);
const showApprovalModal = ref(false);
const showRejectionModal = ref(false);
const rejectionReason = ref('');
const masterCardToAction = ref(null);
const showEditModal = ref(false);
const editingMasterCard = ref(null);
const modalMode = ref('edit');

// Customer Account Modal variables
const showCustomerAccountModal = ref(false);
const showCustomerAccountTable = ref(false);
const sortOption = ref('code');
const tableSearchTerm = ref('');
const selectedCustomer = ref(null);

// MCS Modal variables
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
const mcsInput = ref('');

// MCS Options Modal To variables
const mcsInputTo = ref('');
const showMcsOptionsModalTo = ref(false);
const showMcsTableModalTo = ref(false);
const selectedMcsTo = ref(null);
const mcsSearchTermTo = ref('');

// Customer data (sample data - replace with actual API call)
const customerData = ref([
    { code: 'CUST0001', name: 'PT. Indah Karya', salesperson: 'John Doe', acType: 'Regular', currency: 'IDR', status: 'Active' },
    { code: 'CUST0002', name: 'PT. Maju Bersama', salesperson: 'Jane Smith', acType: 'Premium', currency: 'USD', status: 'Active' },
    { code: 'CUST0003', name: 'CV. Berkah Jaya', salesperson: 'Bob Johnson', acType: 'Regular', currency: 'IDR', status: 'Obsolete' },
    { code: 'CUST0004', name: 'UD. Sukses Mandiri', salesperson: 'Alice Brown', acType: 'Premium', currency: 'USD', status: 'Active' },
    { code: 'CUST0005', name: 'PT. Sejahtera Abadi', salesperson: 'Charlie Wilson', acType: 'Regular', currency: 'IDR', status: 'Active' },
]);

// MCS data (sample data - replace with actual API call)
const mcsData = ref([
    { seq: 'MC001', model: 'Box-Standard', part: 'P123', ed: 'ED001', id: 'ID001', status: 'Active' },
    { seq: 'MC002', model: 'Box-Premium', part: 'P124', ed: 'ED002', id: 'ID002', status: 'Active' },
    { seq: 'MC003', model: 'Container-Small', part: 'P125', ed: 'ED003', id: 'ID003', status: 'Obsolete' },
    { seq: 'MC004', model: 'Container-Medium', part: 'P126', ed: 'ED004', id: 'ID004', status: 'Active' },
    { seq: 'MC005', model: 'Container-Large', part: 'P127', ed: 'ED005', id: 'ID005', status: 'Active' },
]);

// Record status filter
const recordStatusFilter = ref({
    active: true,
    obsolete: true
});

// Replace the hardcoded masterCards with props data
const masterCards = ref(props.masterCards || []);
const customers = ref(props.customers || []);

const selectedMasterCard = ref(null);
const searchTerm = ref('');

// Computed property to filter and sort master cards based on state
const filteredMasterCards = computed(() => {
    let filtered = masterCards.value.filter(mc => {
        const matchesSearch = mc.mc_model.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
                             mc.mc_seq.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
                             mc.customer_name.toLowerCase().includes(searchTerm.value.toLowerCase());
        const matchesStatus = recordStatus.value === 'all' ? true : mc.status === recordStatus.value;
        return matchesSearch && matchesStatus;
    });

    // Sort based on selected criterion
    return filtered.sort((a, b) => {
        if (sortBy.value === 'seq') {
            return a.mc_seq.localeCompare(b.mc_seq);
        } else if (sortBy.value === 'model') {
            return a.mc_model.localeCompare(b.mc_model);
        } else {
            return a.customer_name.localeCompare(b.customer_name);
        }
    });
});

// Customer Account Modal Computed Properties
const filteredCustomers = computed(() => {
    return customerData.value.filter(customer => {
        // Filter by status
        if (recordStatusFilter.value.active && customer.status === 'Active') {
            return true;
        }
        if (recordStatusFilter.value.obsolete && customer.status === 'Obsolete') {
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

// MCS Computed Properties
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

// Add computed filteredMcsDataTo for right modal:
const filteredMcsDataTo = computed(() => {
    let result = [...filteredMcs.value];
    if (mcsSearchTermTo.value) {
        const searchLower = mcsSearchTermTo.value.toLowerCase();
        result = result.filter(mcs => 
            mcs.seq.toLowerCase().includes(searchLower) ||
            mcs.model.toLowerCase().includes(searchLower) ||
            mcs.part.toLowerCase().includes(searchLower) ||
            mcs.ed.toLowerCase().includes(searchLower) ||
            mcs.id.toLowerCase().includes(searchLower)
        );
    }
    result.sort((a, b) => {
        let aValue, bValue;
        switch (mcsSortOption.value) {
            case 'seq': aValue = a.seq; bValue = b.seq; break;
            case 'model': aValue = a.model; bValue = b.model; break;
            case 'part': aValue = a.part; bValue = b.part; break;
            case 'ed': aValue = a.ed; bValue = b.ed; break;
            case 'id': aValue = a.id; bValue = b.id; break;
            default: aValue = a.seq; bValue = b.seq;
        }
        if (mcsSortOrder.value === 'asc') {
            return aValue.localeCompare(bValue);
        } else {
            return bValue.localeCompare(aValue);
        }
    });
    return result;
});

// Action handlers
const handleOK = () => {
    // Handle OK button from options panel
    console.log('OK clicked. Current sort:', sortBy.value, 'Status filter:', recordStatus.value);
    showOptions.value = false;
};

const handleExit = () => {
    // Handle Exit button
    showOptions.value = false;
};

const handleApprove = (mc) => {
    masterCardToAction.value = mc;
    showApprovalModal.value = true;
};

const handleReject = (mc) => {
    masterCardToAction.value = mc;
    rejectionReason.value = ''; // Clear previous rejection reason
    showRejectionModal.value = true;
};

const confirmApproval = async () => {
    try {
        const response = await fetch(`/api/approve-mc/approve/${masterCardToAction.value.id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Update the status locally
            const index = masterCards.value.findIndex(mc => mc.id === masterCardToAction.value.id);
            if (index !== -1) {
                masterCards.value[index].status = 'active';
                masterCards.value[index].approved_by = result.approved_by;
                masterCards.value[index].approved_date = result.approved_date;
            }
            
            // Show success message
            alert('Master Card approved successfully!');
        } else {
            alert('Error approving Master Card: ' + (result.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error approving master card:', error);
        alert('An error occurred during approval');
    }
    
    showApprovalModal.value = false;
};

const confirmRejection = async () => {
    try {
        const response = await fetch(`/api/approve-mc/reject/${masterCardToAction.value.id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ 
                rejection_reason: rejectionReason.value 
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Update the status locally
            const index = masterCards.value.findIndex(mc => mc.id === masterCardToAction.value.id);
            if (index !== -1) {
                masterCards.value[index].status = 'obsolete';
                masterCards.value[index].rejected_by = result.rejected_by;
                masterCards.value[index].rejected_date = result.rejected_date;
                masterCards.value[index].rejection_reason = rejectionReason.value;
            }
            
            // Show success message
            alert('Master Card rejected successfully!');
        } else {
            alert('Error rejecting Master Card: ' + (result.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error rejecting master card:', error);
        alert('An error occurred during rejection');
    }
    
    showRejectionModal.value = false;
};

const handleSelectOption = () => {
    console.log('Selected option:', selectOption.value);
    showOptionModal.value = false;
    
    if (selectOption.value === 'customer') {
        // Implement logic for selecting by customer
        console.log('Show customer selection interface');
    } else {
        // Implement logic for selecting by master card
        console.log('Show master card selection interface');
    }
};

const handleEdit = (mc) => {
    editingMasterCard.value = { ...mc };
    modalMode.value = 'edit';
    showEditModal.value = true;
};

const handleAddNew = () => {
    console.log('Opening add new master card modal');
    // Reset editing master card to a new empty record with good defaults
    editingMasterCard.value = {
        mc_seq: generateUniqueMcSeq(),
        mc_model: '',
        customer_code: '',
        customer_name: '',
        status: 'pending'
    };
    // Set mode to add
    modalMode.value = 'add';
    // Show the modal
    showEditModal.value = true;
    
    console.log('New master card initialized with:', editingMasterCard.value);
};

// Helper function to generate a unique MC sequence (example format: MC-2023-001)
const generateUniqueMcSeq = () => {
    const date = new Date();
    const year = date.getFullYear();
    const existingSeqs = masterCards.value
        .map(mc => mc.mc_seq)
        .filter(seq => seq.includes(`MC-${year}`));
    
    let nextNumber = 1;
    if (existingSeqs.length > 0) {
        // Extract the numeric parts and find the highest
        const numbers = existingSeqs
            .map(seq => {
                const match = seq.match(/MC-\d+-(\d+)/);
                return match ? parseInt(match[1], 10) : 0;
            })
            .filter(num => !isNaN(num));
        
        if (numbers.length > 0) {
            nextNumber = Math.max(...numbers) + 1;
        }
    }
    
    // Format with leading zeros (e.g., 001, 012, etc.)
    const paddedNumber = nextNumber.toString().padStart(3, '0');
    return `MC-${year}-${paddedNumber}`;
};

const closeEditModal = () => {
    console.log('Closing edit modal');
    showEditModal.value = false;
    editingMasterCard.value = null;
};

const handleUpdateMasterCard = async (updatedMasterCardData) => {
    console.log('==== Master Card Update/Add Handler ====');
    console.log('Received response from modal:', updatedMasterCardData);

    // Check if we received valid data from the modal
    if (!updatedMasterCardData) {
        console.error('No master card data received from modal');
        return;
    }

    try {
        // Check if this is a new record (no ID) or an existing one
        if (!updatedMasterCardData.id) {
            console.log('Adding new master card to local state:', updatedMasterCardData);
            
            // Add the new record to the master cards array at the beginning for visibility
            masterCards.value = [updatedMasterCardData, ...masterCards.value];
            
            // Set the new card as selected
            selectedMasterCard.value = updatedMasterCardData;
            
            // Show confirmation to the user
            alert('Master Card added successfully!');
        } else {
            console.log('Updating existing master card in local state');
            
            // Find the index of the existing record
            const index = masterCards.value.findIndex(mc => mc.id === updatedMasterCardData.id);
            
            if (index !== -1) {
                console.log(`Found existing record at index ${index}, updating`);
                
                // Update the record in the array
                masterCards.value.splice(index, 1, updatedMasterCardData);
                
                // If this was the selected record, update the selection
                if (selectedMasterCard.value?.id === updatedMasterCardData.id) {
                    selectedMasterCard.value = updatedMasterCardData;
                }
                
                // Show confirmation to the user
                alert('Master Card updated successfully!');
            } else {
                console.warn('Could not find master card with ID', updatedMasterCardData.id);
                
                // Add it anyway in case it's a valid record
                masterCards.value.push(updatedMasterCardData);
            }
        }

        // Always close the modal after handling the response
        closeEditModal();
    } catch (error) {
        console.error('Error processing master card data:', error);
    }
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
    searchTerm.value = customer.code; // Update the search term with selected customer code
    showCustomerAccountTable.value = false;
};

const selectForView = (customer) => {
    selectedCustomer.value = customer;
};

// MCS Modal functions
const openMcsModal = () => {
    showMcsOptionsModal.value = true;
};

const applyMcsFilter = () => {
    showMcsOptionsModal.value = false;
    showMcsTableModal.value = true;
};

const selectMcs = (mcs) => {
    if (!mcs) return;
    selectedMcs.value = mcs;
    mcsInput.value = mcs.seq;
    showMcsTableModal.value = false;
};

const selectForViewMcs = (mcs) => {
    selectedMcs.value = mcs;
};

// MCS Options Modal To functions
const openMcsModalTo = () => {
    showMcsOptionsModalTo.value = true;
};

const applyMcsFilterTo = () => {
    showMcsOptionsModalTo.value = false;
    showMcsTableModalTo.value = true;
};

const selectMcsTo = (mcs) => {
    if (!mcs) return;
    selectedMcsTo.value = mcs;
    mcsInputTo.value = mcs.seq;
    showMcsTableModalTo.value = false;
};

const selectForViewMcsTo = (mcs) => {
    selectedMcsTo.value = mcs;
};
</script>

<style>
/* Add transition effects */
.fixed.inset-0 {
    animation: modalFadeIn 0.2s ease-out forwards;
}

@keyframes modalFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes modalScaleIn {
    from { transform: scale(0.95); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

.animate-modalScaleIn {
    animation: modalScaleIn 0.3s ease-out forwards;
}

/* Add new animations for ping effects */
@keyframes ping-slow {
    0% {
        transform: scale(1);
        opacity: 1;
    }
    75%, 100% {
        transform: scale(2);
        opacity: 0;
    }
}

.animate-ping-slow {
    animation: ping-slow 3s cubic-bezier(0, 0, 0.2, 1) infinite;
}

.animation-delay-500 {
    animation-delay: 500ms;
}

/* Text shadow for header text */
.text-shadow {
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Add more attractive hover effects */
button:hover svg {
    transform: scale(1.1);
    transition: transform 0.2s ease-in-out;
}

tr.hover\:bg-gray-50:hover td {
    transition: background-color 0.2s ease-in-out;
}

/* Add smooth transitions for form elements */
input[type="radio"],
input[type="checkbox"] {
    transition: all 0.2s ease-in-out;
}

input[type="text"],
textarea {
    transition: all 0.2s ease-in-out;
}
</style>
