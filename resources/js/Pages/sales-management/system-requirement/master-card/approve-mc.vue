<template>
    <AppLayout :header="'Approve MC'">
        <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 p-6">
            <div class="max-w-7xl mx-auto">
                <!-- Header -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                <!-- Check Circle Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-white"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-800">Approve Master Card</h1>
                                <p class="text-gray-600">Manage and approve master cards in the system</p>
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            <button 
                                @click="handleAddNew"
                                class="flex items-center space-x-2 px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg"
                            >
                                <!-- Plus Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                <span>Add New</span>
                            </button>
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
                                                        'bg-yellow-100 text-yellow-800': mc.status === 'pending',
                                                        'bg-red-100 text-red-800': mc.status === 'obsolete'
                                                    }">
                                                    {{ mc.status.charAt(0).toUpperCase() + mc.status.slice(1) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex space-x-2">
                                                    <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-150" @click.stop="handleEdit(mc)">
                                                        <!-- Edit Icon -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                    </button>
                                                    <button class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors duration-150" @click.stop="handleApprove(mc)">
                                                        <!-- Check Icon -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                    </button>
                                                    <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-150" @click.stop="handleReject(mc)">
                                                        <!-- X Icon -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
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
                                                value="pending"
                                                v-model="recordStatus"
                                                class="w-4 h-4 text-yellow-600 border-gray-300 focus:ring-yellow-500"
                                            />
                                            <span class="ml-3 text-sm text-gray-700">Pending</span>
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

                                <!-- Action Buttons -->
                                <div class="flex flex-col space-y-3">
                                    <button class="w-full px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center space-x-2" @click="handleOK">
                                        <!-- Check Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        <span>OK</span>
                                    </button>
                                    <button class="w-full px-4 py-2 bg-gradient-to-r from-gray-500 to-gray-600 text-white rounded-lg hover:from-gray-600 hover:to-gray-700 transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center space-x-2" @click="handleExit">
                                        <!-- X Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                        <span>Exit</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Selected Master Card Detail -->
                        <div v-if="selectedMasterCard" class="mt-6 bg-white rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Selected Master Card</h3>
                            <div class="space-y-2">
                                <p class="text-sm"><span class="font-medium text-gray-600">Sequence:</span> {{ selectedMasterCard.mc_seq }}</p>
                                <p class="text-sm"><span class="font-medium text-gray-600">Model:</span> {{ selectedMasterCard.mc_model }}</p>
                                <p class="text-sm"><span class="font-medium text-gray-600">Customer:</span> {{ selectedMasterCard.customer_name }}</p>
                                <p class="text-sm">
                                    <span class="font-medium text-gray-600">Status:</span> 
                                    <span 
                                        class="ml-2 px-2 py-1 rounded text-xs"
                                        :class="{
                                            'bg-green-100 text-green-800': selectedMasterCard.status === 'active', 
                                            'bg-yellow-100 text-yellow-800': selectedMasterCard.status === 'pending',
                                            'bg-red-100 text-red-800': selectedMasterCard.status === 'obsolete'
                                        }">
                                        {{ selectedMasterCard.status.charAt(0).toUpperCase() + selectedMasterCard.status.slice(1) }}
                                    </span>
                                </p>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <button class="w-full px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center space-x-2" @click="handleApprove(selectedMasterCard)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    <span>Approve</span>
                                </button>
                                <button class="mt-2 w-full px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center space-x-2" @click="handleReject(selectedMasterCard)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                    <span>Reject</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approval Confirmation Modal -->
        <div v-if="showApprovalModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showApprovalModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Approve Master Card
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Are you sure you want to approve this master card? This action cannot be undone.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm" @click="confirmApproval">
                            Approve
                        </button>
                        <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" @click="showApprovalModal = false">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rejection Modal -->
        <div v-if="showRejectionModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showRejectionModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Reject Master Card
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Please provide a reason for rejecting this master card:
                                    </p>
                                    <textarea v-model="rejectionReason" rows="4" class="mt-2 shadow-sm block w-full focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border border-gray-300 rounded-md"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm" @click="confirmRejection">
                            Reject
                        </button>
                        <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" @click="showRejectionModal = false">
                            Cancel
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
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                                    Option
                                </h3>
                                <div class="bg-white border border-gray-300 rounded-md shadow-sm p-4 mb-4">
                                    <label class="flex items-center mb-4 cursor-pointer">
                                        <input type="radio" v-model="selectOption" value="customer" class="form-radio h-5 w-5 text-blue-600">
                                        <span class="ml-2 text-gray-800 font-bold">Select by Customer</span>
                                    </label>
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio" v-model="selectOption" value="mastercard" class="form-radio h-5 w-5 text-blue-600">
                                        <span class="ml-2 text-gray-800 font-bold">Select by Master Card</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:justify-center">
                        <button type="button" class="w-24 inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-gray-300 text-base font-medium text-gray-700 hover:bg-gray-400 focus:outline-none sm:text-sm mx-2" @click="handleSelectOption">
                            OK
                        </button>
                        <button type="button" class="w-24 inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-gray-300 text-base font-medium text-gray-700 hover:bg-gray-400 focus:outline-none sm:text-sm mx-2" @click="showOptionModal = false">
                            Exit
                        </button>
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
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import MasterCardModal from '@/components/master-card-modal.vue';

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

// Sample master card data - replace with data fetched from your backend
const masterCards = ref([
    { id: 1, mc_seq: 'MC001', mc_model: 'Box-Standard', customer_name: 'PT. Indah Karya', status: 'pending' },
    { id: 2, mc_seq: 'MC002', mc_model: 'Box-Premium', customer_name: 'PT. Maju Bersama', status: 'active' },
    { id: 3, mc_seq: 'MC003', mc_model: 'Container-Small', customer_name: 'CV. Berkah Jaya', status: 'obsolete' },
    { id: 4, mc_seq: 'MC004', mc_model: 'Container-Medium', customer_name: 'UD. Sukses Mandiri', status: 'active' },
]);

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
    showRejectionModal.value = true;
    rejectionReason.value = ''; // Clear previous rejection reason
};

const confirmApproval = () => {
    // Implement your approval logic here
    console.log('Approved:', masterCardToAction.value);
    if (masterCardToAction.value) {
        masterCardToAction.value.status = 'active';
    }
    showApprovalModal.value = false;
};

const confirmRejection = () => {
    // Implement your rejection logic here
    console.log('Rejected:', masterCardToAction.value, 'Reason:', rejectionReason.value);
    if (masterCardToAction.value) {
        masterCardToAction.value.status = 'obsolete';
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
    editingMasterCard.value = {
        mc_seq: '',
        mc_model: '',
        customer_code: '',
        customer_name: '',
        status: 'pending'
    };
    modalMode.value = 'add';
    showEditModal.value = true;
};

const closeEditModal = () => {
    showEditModal.value = false;
    editingMasterCard.value = null;
};

const handleUpdateMasterCard = (updatedMasterCard) => {
    // Find and update the master card in the list
    const index = masterCards.value.findIndex(mc => mc.id === updatedMasterCard.id);
    if (index !== -1) {
        masterCards.value[index] = updatedMasterCard;
    } else {
        // If not found (new card), add it to the list
        masterCards.value.push(updatedMasterCard);
    }
    
    closeEditModal();
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
</style>
