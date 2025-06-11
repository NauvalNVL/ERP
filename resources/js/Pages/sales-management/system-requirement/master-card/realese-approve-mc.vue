<!-- 
  Release Approved Master Card Component
  This component handles the release of approved master cards in the system
-->
<template>
    <AppLayout :header="'Release Approved MC'">
        <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 p-6">
            <div class="max-w-7xl mx-auto">
                <!-- Success Notification -->
                <div v-if="notification.show" 
                     class="mb-6 p-4 rounded-lg shadow-md transition-all duration-300 transform" 
                     :class="{
                        'bg-green-100 border-l-4 border-green-500 text-green-700': notification.type === 'success',
                        'bg-red-100 border-l-4 border-red-500 text-red-700': notification.type === 'error',
                        'translate-y-0 opacity-100': notification.show,
                        'translate-y-2 opacity-0': !notification.show
                     }">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <!-- Success Icon -->
                            <svg v-if="notification.type === 'success'" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <!-- Error Icon -->
                            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm">{{ notification.message }}</p>
                        </div>
                        <div class="ml-auto pl-3">
                            <div class="-mx-1.5 -my-1.5">
                                <button @click="hideNotification" class="inline-flex bg-gray-50 bg-opacity-30 rounded-md p-1.5 text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-green-50 focus:ring-green-600">
                                    <span class="sr-only">Dismiss</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Header -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                                <!-- Unlock Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-white"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 9.9-1"></path></svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-800">Release Approved Master Card</h1>
                                <p class="text-gray-600">Release approved master cards for production use</p>
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            <button 
                                @click="showAddNewModal = true"
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
                            class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                        />
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <!-- Master Card List -->
                    <div class="lg:col-span-3">
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                            <div class="p-6 border-b border-gray-200">
                                <h2 class="text-lg font-semibold text-gray-800">Approved Master Cards</h2>
                            </div>
                            
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">MC Seq#</th>
                                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">MC Model</th>
                                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Customer</th>
                                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Approved Date</th>
                                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Released</th>
                                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr 
                                            v-for="mc in filteredMasterCards"
                                            :key="mc.id"
                                            class="hover:bg-green-50 transition-colors duration-150 cursor-pointer"
                                            :class="{ 'bg-green-50 border-l-4 border-green-500': selectedMasterCard?.id === mc.id }"
                                            @click="selectedMasterCard = mc"
                                        >
                                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ mc.mc_seq }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-700">{{ mc.mc_model }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-700">{{ mc.customer_name }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-700">{{ formatDate(mc.approved_date) }}</td>
                                            <td class="px-6 py-4">
                                                <span 
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                                    :class="{
                                                        'bg-green-100 text-green-800': mc.released_date, 
                                                        'bg-yellow-100 text-yellow-800': !mc.released_date
                                                    }">
                                                    {{ mc.released_date ? 'Released' : 'Not Released' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex space-x-2">
                                                    <button 
                                                        v-if="!mc.released_date" 
                                                        class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors duration-150" 
                                                        @click.stop="handleRelease(mc)"
                                                    >
                                                        <!-- Unlock Icon -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 9.9-1"></path></svg>
                                                    </button>
                                                    <button 
                                                        v-if="mc.released_date" 
                                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-150" 
                                                        @click.stop="handleUnrelease(mc)"
                                                    >
                                                        <!-- Lock Icon -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-if="filteredMasterCards.length === 0">
                                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                                No approved master cards found. Please adjust your search or filter criteria.
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
                                                class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500"
                                            />
                                            <span class="ml-3 text-sm text-gray-700">MC Sequence</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input
                                                type="radio"
                                                name="sortBy"
                                                value="model"
                                                v-model="sortBy"
                                                class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500"
                                            />
                                            <span class="ml-3 text-sm text-gray-700">MC Model</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input
                                                type="radio"
                                                name="sortBy"
                                                value="customer"
                                                v-model="sortBy"
                                                class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500"
                                            />
                                            <span class="ml-3 text-sm text-gray-700">Customer</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input
                                                type="radio"
                                                name="sortBy"
                                                value="approved_date"
                                                v-model="sortBy"
                                                class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500"
                                            />
                                            <span class="ml-3 text-sm text-gray-700">Approved Date</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Filter By Release Status -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Release Status:</label>
                                    <div class="space-y-3">
                                        <label class="flex items-center">
                                            <input
                                                type="radio"
                                                name="releaseStatus"
                                                value="all"
                                                v-model="releaseStatus"
                                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                            />
                                            <span class="ml-3 text-sm text-gray-700">All</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input
                                                type="radio"
                                                name="releaseStatus"
                                                value="released"
                                                v-model="releaseStatus"
                                                class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500"
                                            />
                                            <span class="ml-3 text-sm text-gray-700">Released</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input
                                                type="radio"
                                                name="releaseStatus"
                                                value="notReleased"
                                                v-model="releaseStatus"
                                                class="w-4 h-4 text-yellow-600 border-gray-300 focus:ring-yellow-500"
                                            />
                                            <span class="ml-3 text-sm text-gray-700">Not Released</span>
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
                                <p class="text-sm"><span class="font-medium text-gray-600">Approved Date:</span> {{ formatDate(selectedMasterCard.approved_date) }}</p>
                                <p class="text-sm"><span class="font-medium text-gray-600">Approved By:</span> {{ selectedMasterCard.approved_by }}</p>
                                <p class="text-sm">
                                    <span class="font-medium text-gray-600">Released Status:</span> 
                                    <span 
                                        class="ml-2 px-2 py-1 rounded text-xs"
                                        :class="{
                                            'bg-green-100 text-green-800': selectedMasterCard.released_date, 
                                            'bg-yellow-100 text-yellow-800': !selectedMasterCard.released_date
                                        }">
                                        {{ selectedMasterCard.released_date ? 'Released' : 'Not Released' }}
                                    </span>
                                </p>
                                <div v-if="selectedMasterCard.released_date" class="pt-1">
                                    <p class="text-sm"><span class="font-medium text-gray-600">Released Date:</span> {{ formatDate(selectedMasterCard.released_date) }}</p>
                                    <p class="text-sm"><span class="font-medium text-gray-600">Released By:</span> {{ selectedMasterCard.released_by }}</p>
                                </div>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <button 
                                    v-if="!selectedMasterCard.released_date"
                                    class="w-full px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center space-x-2" 
                                    @click="handleRelease(selectedMasterCard)"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 9.9-1"></path></svg>
                                    <span>Release</span>
                                </button>
                                <button 
                                    v-else
                                    class="w-full px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center space-x-2" 
                                    @click="handleUnrelease(selectedMasterCard)"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                    <span>Unreleased</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Release Notes Modal -->
        <div v-if="showReleaseModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showReleaseModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 9.9-1"></path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Release Master Card
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Please add any notes for releasing this master card:
                                    </p>
                                    <textarea v-model="releaseNotes" rows="4" class="mt-2 shadow-sm block w-full focus:ring-green-500 focus:border-green-500 sm:text-sm border border-gray-300 rounded-md"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm" @click="confirmRelease">
                            Release
                        </button>
                        <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" @click="showReleaseModal = false">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unreleased Confirmation Modal -->
        <div v-if="showUnreleaseModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showUnreleaseModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Unreleased Master Card
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Are you sure you want to unreleased this master card? This action will revert the release status.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm" @click="confirmUnrelease">
                            Unreleased
                        </button>
                        <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" @click="showUnreleaseModal = false">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Master Card Modal for Adding New -->
        <MasterCardModal 
            v-if="showAddNewModal" 
            :show="showAddNewModal"
            :customers="customers"
            mode="add"
            apiEndpoint="/api/realese-approve-mc"
            @close="showAddNewModal = false"
            @master-card-added="handleMasterCardAdded"
        />
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import MasterCardModal from '@/components/master-card-modal.vue';

const props = defineProps({
    masterCards: Array,
    customers: Array,
});

// Define reactive state variables
const sortBy = ref('seq');
const releaseStatus = ref('all');
const showOptions = ref(false);
const showReleaseModal = ref(false);
const showUnreleaseModal = ref(false);
const releaseNotes = ref('');
const masterCardToAction = ref(null);

// Notification system
const notification = ref({
    show: false,
    message: '',
    type: 'success', // or 'error'
    timeout: null
});

const showNotification = (message, type = 'success') => {
    // Clear any existing timeout
    if (notification.value.timeout) {
        clearTimeout(notification.value.timeout);
    }
    
    // Set notification
    notification.value = {
        show: true,
        message,
        type,
        timeout: setTimeout(() => {
            hideNotification();
        }, 5000) // Auto-hide after 5 seconds
    };
};

const hideNotification = () => {
    notification.value.show = false;
    if (notification.value.timeout) {
        clearTimeout(notification.value.timeout);
    }
};

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
        // Only approved master cards are shown on this page
        const matchesStatus = mc.status === 'active';
        
        let matchesReleaseStatus = true;
        if (releaseStatus.value === 'released') {
            matchesReleaseStatus = !!mc.released_date;
        } else if (releaseStatus.value === 'notReleased') {
            matchesReleaseStatus = !mc.released_date;
        }
        
        return matchesSearch && matchesStatus && matchesReleaseStatus;
    });

    // Sort based on selected criterion
    return filtered.sort((a, b) => {
        if (sortBy.value === 'seq') {
            return a.mc_seq.localeCompare(b.mc_seq);
        } else if (sortBy.value === 'model') {
            return a.mc_model.localeCompare(b.mc_model);
        } else if (sortBy.value === 'approved_date') {
            return new Date(b.approved_date) - new Date(a.approved_date);
        } else {
            return a.customer_name.localeCompare(b.customer_name);
        }
    });
});

// Helper function to format dates
const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('en-US', {
        year: 'numeric', 
        month: 'short', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    }).format(date);
};

// Action handlers
const handleOK = () => {
    // Handle OK button from options panel
    console.log('OK clicked. Current sort:', sortBy.value, 'Release filter:', releaseStatus.value);
    showOptions.value = false;
};

const handleExit = () => {
    // Navigate back to dashboard or previous page
    router.get('/dashboard');
};

const handleRelease = (mc) => {
    masterCardToAction.value = mc;
    releaseNotes.value = '';
    showReleaseModal.value = true;
};

const handleUnrelease = (mc) => {
    masterCardToAction.value = mc;
    showUnreleaseModal.value = true;
};

const confirmRelease = async () => {
    try {
        const response = await fetch(`/api/realese-approve-mc/release/${masterCardToAction.value.id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                release_notes: releaseNotes.value
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Update the status locally
            const index = masterCards.value.findIndex(mc => mc.id === masterCardToAction.value.id);
            if (index !== -1) {
                masterCards.value[index].released_by = result.released_by;
                masterCards.value[index].released_date = result.released_date;
                masterCards.value[index].release_notes = releaseNotes.value;
            }
            
            // Show success message
            showNotification('Master Card released successfully!');
        } else {
            showNotification('Error releasing Master Card: ' + (result.message || 'Unknown error'), 'error');
        }
    } catch (error) {
        console.error('Error releasing master card:', error);
        showNotification('An error occurred during release', 'error');
    }
    
    showReleaseModal.value = false;
};

const confirmUnrelease = async () => {
    try {
        const response = await fetch(`/api/realese-approve-mc/unreleased/${masterCardToAction.value.id}`, {
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
                masterCards.value[index].released_by = null;
                masterCards.value[index].released_date = null;
                masterCards.value[index].release_notes = null;
            }
            
            // Show success message
            showNotification('Master Card un-released successfully!');
        } else {
            showNotification('Error un-releasing Master Card: ' + (result.message || 'Unknown error'), 'error');
        }
    } catch (error) {
        console.error('Error un-releasing master card:', error);
        showNotification('An error occurred during un-releasing', 'error');
    }
    
    showUnreleaseModal.value = false;
};

// Modal for adding new master card
const showAddNewModal = ref(false);

const handleMasterCardAdded = (newMasterCard) => {
    // First close the modal
    showAddNewModal.value = false;
    
    // Check if the new master card is already in our list
    const existingIndex = masterCards.value.findIndex(mc => mc.id === newMasterCard.id);
    
    if (existingIndex !== -1) {
        // Update the existing record
        masterCards.value[existingIndex] = newMasterCard;
        showNotification(`Master card "${newMasterCard.mc_model}" was updated successfully.`);
    } else {
        // Add the new master card to our list
        masterCards.value.push(newMasterCard);
        showNotification(`Master card "${newMasterCard.mc_model}" was added successfully.`);
    }
    
    // Select the new master card
    selectedMasterCard.value = newMasterCard;
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
