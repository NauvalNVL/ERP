<!-- 
  Obsolate and Reactive Master Card Component
  This component handles obsolating and reactivating master cards in the system
-->
<template>
    <AppLayout :header="'Obsolete & Reactive MC'">
        <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 p-6">
            <div class="max-w-7xl mfx-auto">
                <!-- Header -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                <!-- Refresh/Power Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-white"><path d="M23 4v6h-6"></path><path d="M1 20v-6h6"></path><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-800">Obsolete & Reactive Master Card</h1>
                                <p class="text-gray-600">Manage active and obsolete status of master cards</p>
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            <button 
                                @click="showOptionModal = true"
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

                <div class="grid grid-cols-1 gap-6">
                    <!-- Master Card List -->
                    <div class="col-span-1">
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
                </div>
            </div>
        </div>

        <!-- Options Modal -->
        <TransitionRoot appear :show="showOptionModal" as="template">
            <Dialog as="div" class="fixed z-10 inset-0 overflow-y-auto" @close="showOptionModal = false">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <TransitionChild
                        as="template"
                        enter="ease-out duration-300"
                        enter-from="opacity-0"
                        enter-to="opacity-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100"
                        leave-to="opacity-0"
                    >
                        <DialogOverlay class="fixed inset-0 bg-black bg-opacity-30 transition-opacity" />
                    </TransitionChild>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <TransitionChild
                        as="template"
                        enter="ease-out duration-300"
                        enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        enter-to="opacity-100 translate-y-0 sm:scale-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100 translate-y-0 sm:scale-100"
                        leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    >
                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                            <!-- Header with blue background -->
                            <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-4 py-3 flex justify-between items-center">
                                <h3 class="text-lg font-medium text-white flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Options
                                </h3>
                                <button @click="showOptionModal = false" class="text-white hover:text-gray-200 transition-colors duration-150 focus:outline-none">
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            
                            <div class="bg-white px-6 pt-5 pb-6">
                                <!-- Sort by section -->
                                <div class="mb-6">
                                    <div class="flex items-center mb-3 border-l-4 border-blue-500 pl-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                        </svg>
                                        <span class="text-sm font-medium text-gray-700">Sort by:</span>
                                    </div>
                                    <div class="space-y-3 ml-7">
                                        <label class="flex items-center transition-colors duration-150 p-2 rounded-md hover:bg-blue-50">
                                            <input 
                                                type="radio" 
                                                name="sortBy" 
                                                value="customerCode" 
                                                v-model="sortBy"
                                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                            />
                                            <span class="ml-3 text-sm text-gray-700">Customer Code</span>
                                        </label>
                                        <label class="flex items-center transition-colors duration-150 p-2 rounded-md hover:bg-blue-50">
                                            <input 
                                                type="radio" 
                                                name="sortBy" 
                                                value="customerName" 
                                                v-model="sortBy"
                                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                            />
                                            <span class="ml-3 text-sm text-gray-700">Customer Name</span>
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Record Status section -->
                                <div class="mb-6">
                                    <div class="flex items-center mb-3 border-l-4 border-blue-500 pl-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        <span class="text-sm font-medium text-gray-700">Record Status:</span>
                                    </div>
                                    <div class="space-y-3 ml-7">
                                        <label class="flex items-center transition-colors duration-150 p-2 rounded-md hover:bg-blue-50">
                                            <input 
                                                type="checkbox" 
                                                v-model="isActiveSelected"
                                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                            />
                                            <span class="ml-3 text-sm text-gray-700 flex items-center">
                                                <span class="inline-block w-3 h-3 rounded-full bg-green-500 mr-2"></span>
                                                Active
                                            </span>
                                        </label>
                                        <label class="flex items-center transition-colors duration-150 p-2 rounded-md hover:bg-blue-50">
                                            <input 
                                                type="checkbox" 
                                                v-model="isObsoleteSelected"
                                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                            />
                                            <span class="ml-3 text-sm text-gray-700 flex items-center">
                                                <span class="inline-block w-3 h-3 rounded-full bg-red-500 mr-2"></span>
                                                Obsolete
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-gradient-to-b from-gray-50 to-gray-100 px-6 py-4 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button 
                                    type="button"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-700 text-base font-medium text-white hover:from-blue-600 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform transition-all duration-150 hover:scale-105 sm:ml-3 sm:w-auto sm:text-sm"
                                    @click="applyOptions"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    OK
                                </button>
                                <button 
                                    type="button"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform transition-all duration-150 hover:scale-105 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                                    @click="showOptionModal = false"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Exit
                                </button>
                            </div>
                        </div>
                    </TransitionChild>
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- Obsolate Modal -->
        <TransitionRoot appear :show="showObsolateModal" as="template">
            <Dialog as="div" class="fixed z-10 inset-0 overflow-y-auto" @close="showObsolateModal = false">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <TransitionChild
                        as="template"
                        enter="ease-out duration-300"
                        enter-from="opacity-0"
                        enter-to="opacity-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100"
                        leave-to="opacity-0"
                    >
                        <DialogOverlay class="fixed inset-0 bg-black bg-opacity-30 transition-opacity" />
                    </TransitionChild>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <TransitionChild
                        as="template"
                        enter="ease-out duration-300"
                        enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        enter-to="opacity-100 translate-y-0 sm:scale-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100 translate-y-0 sm:scale-100"
                        leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    >
                        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-0">
                            <!-- Header with red gradient background -->
                            <div class="bg-gradient-to-r from-red-600 to-red-800 px-4 py-3 flex justify-between items-center rounded-t-lg">
                                <h3 class="text-lg font-medium text-white flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    Obsolate Master Card
                                </h3>
                                <button @click="showObsolateModal = false" class="text-white hover:text-gray-200 transition-colors duration-150 focus:outline-none">
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            
                            <div class="px-6 pt-5 pb-6 bg-white">
                                <div class="sm:flex sm:items-start">
                                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10 pulse-animation">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6 text-red-600"><circle cx="12" cy="12" r="10"></circle><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line></svg>
                                    </div>
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-600 border-l-4 border-red-500 pl-3 py-2 bg-red-50 rounded-r-md">
                                                Are you sure you want to obsolate the master card? This will mark it as no longer in use.
                                            </p>
                                        </div>
                                        <div class="mt-4">
                                            <label for="obsolate-reason" class="block text-sm font-medium text-gray-700">
                                                Reason for Obsolating:
                                            </label>
                                            <textarea
                                                id="obsolate-reason"
                                                v-model="obsolateReason"
                                                rows="3"
                                                class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 transition-all duration-200"
                                                placeholder="Please provide a reason..."
                                            ></textarea>
                                            <p class="mt-1 text-xs text-gray-500">Please provide a detailed reason why this master card should be obsolated.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-gradient-to-b from-gray-50 to-gray-100 px-6 py-4 rounded-b-lg sm:px-6 sm:flex sm:flex-row-reverse">
                                <button
                                    type="button"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gradient-to-r from-red-500 to-red-700 text-base font-medium text-white hover:from-red-600 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transform hover:scale-105 transition-all duration-150 sm:ml-3 sm:w-auto sm:text-sm"
                                    @click="confirmObsolate"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Obsolate
                                </button>
                                <button
                                    type="button"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transform hover:scale-105 transition-all duration-150 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                                    @click="showObsolateModal = false"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </TransitionChild>
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- Reactive Modal -->
        <TransitionRoot appear :show="showReactiveModal" as="template">
            <Dialog as="div" class="fixed z-10 inset-0 overflow-y-auto" @close="showReactiveModal = false">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <TransitionChild
                        as="template"
                        enter="ease-out duration-300"
                        enter-from="opacity-0"
                        enter-to="opacity-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100"
                        leave-to="opacity-0"
                    >
                        <DialogOverlay class="fixed inset-0 bg-black bg-opacity-30 transition-opacity" />
                    </TransitionChild>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <TransitionChild
                        as="template"
                        enter="ease-out duration-300"
                        enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        enter-to="opacity-100 translate-y-0 sm:scale-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100 translate-y-0 sm:scale-100"
                        leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    >
                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-0">
                            <!-- Header with green gradient background -->
                            <div class="bg-gradient-to-r from-green-600 to-green-800 px-4 py-3 flex justify-between items-center rounded-t-lg">
                                <h3 class="text-lg font-medium text-white flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Reactive Master Card
                                </h3>
                                <button @click="showReactiveModal = false" class="text-white hover:text-gray-200 transition-colors duration-150 focus:outline-none">
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            
                            <div class="px-6 pt-5 pb-6 bg-white">
                                <div class="sm:flex sm:items-start">
                                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6 text-green-600"><path d="M23 4v6h-6"></path><path d="M1 20v-6h6"></path><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                                    </div>
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-600 border-l-4 border-green-500 pl-3 py-2 bg-green-50 rounded-r-md">
                                                Are you sure you want to reactive this master card? This will make it active again.
                                            </p>
                                        </div>
                                        <div class="mt-4">
                                            <label for="reactive-reason" class="block text-sm font-medium text-gray-700">
                                                Reason for Reactivating:
                                            </label>
                                            <textarea
                                                id="reactive-reason"
                                                v-model="reactiveReason"
                                                rows="3"
                                                class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition-all duration-200"
                                                placeholder="Please provide a reason..."
                                            ></textarea>
                                            <p class="mt-1 text-xs text-gray-500">Please provide a detailed reason why this master card should be reactivated.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-gradient-to-b from-gray-50 to-gray-100 px-6 py-4 rounded-b-lg sm:px-6 sm:flex sm:flex-row-reverse">
                                <button
                                    type="button"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gradient-to-r from-green-500 to-green-700 text-base font-medium text-white hover:from-green-600 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transform hover:scale-105 transition-all duration-150 sm:ml-3 sm:w-auto sm:text-sm"
                                    @click="confirmReactive"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Reactivate
                                </button>
                                <button
                                    type="button"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transform hover:scale-105 transition-all duration-150 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                                    @click="showReactiveModal = false"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </TransitionChild>
                </div>
            </Dialog>
        </TransitionRoot>
    </AppLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { Dialog, DialogOverlay, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useToast } from '@/Composables/useToast';
import axios from 'axios';

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
const showOptionModal = ref(false);
const showObsolateModal = ref(false);
const showReactiveModal = ref(false);
const showDetailsModal = ref(false);
const obsolateReason = ref('');
const reactiveReason = ref('');
const selectedMasterCard = ref(null);
const masterCardsList = ref(props.masterCards || []);
const isActiveSelected = ref(true);
const isObsoleteSelected = ref(false);

// Toast
const { showToast } = useToast();

// Apply options from modal
const applyOptions = () => {
    // Update recordStatus based on checkboxes
    if (isActiveSelected.value && isObsoleteSelected.value) {
        recordStatus.value = 'all';
    } else if (isActiveSelected.value) {
        recordStatus.value = 'active';
    } else if (isObsoleteSelected.value) {
        recordStatus.value = 'obsolete';
    } else {
        recordStatus.value = 'all'; // Default to all if none selected
    }
    
    // Update sort
    if (sortBy.value === 'customerCode') {
        sortBy.value = 'seq';
    } else if (sortBy.value === 'customerName') {
        sortBy.value = 'customer';
    }
    
    showOptionModal.value = false;
    showToast('Options applied', 'success');
};

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

.pulse-animation {
    animation: pulse-light 2s infinite;
}

@keyframes pulse-light {
    0% {
        transform: scale(0.95);
        box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4);
    }
    
    70% {
        transform: scale(1);
        box-shadow: 0 0 0 6px rgba(239, 68, 68, 0);
    }
    
    100% {
        transform: scale(0.95);
        box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
    }
}

/* Button hover effects */
button.transform:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Modal transition effects */
.modal-enter-active, .modal-leave-active {
    transition: opacity 0.3s ease, transform 0.3s ease;
}

.modal-enter-from, .modal-leave-to {
    opacity: 0;
    transform: translateY(-20px);
}
</style>
