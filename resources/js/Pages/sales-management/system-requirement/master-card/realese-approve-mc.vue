<!-- 
  Release Approved Master Card Component
  This component handles the release of approved master cards in the system
-->
<template>
    <AppLayout :header="'Release Approved MC'">
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
                    <i class="fas fa-unlock-alt text-white text-2xl z-10"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-1 text-shadow">Release Approved Master Card</h2>
                    <p class="text-blue-100 max-w-2xl">Release approved master cards for production use</p>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <!-- Total Cards -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow flex">
                <div class="p-3 rounded-full bg-blue-100 mr-4">
                    <i class="fas fa-clipboard-list text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs uppercase font-medium text-gray-500">Total</p>
                    <p class="text-xl font-semibold text-gray-800">{{ masterCards.length }}</p>
                    <p class="text-xs text-gray-500">Master Cards</p>
                </div>
            </div>
            
            <!-- Released Cards -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow flex">
                <div class="p-3 rounded-full bg-green-100 mr-4">
                    <i class="fas fa-unlock-alt text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs uppercase font-medium text-gray-500">Released</p>
                    <p class="text-xl font-semibold text-gray-800">{{ masterCards.filter(mc => mc.released_date).length }}</p>
                    <p class="text-xs text-gray-500">Master Cards</p>
                </div>
            </div>
            
            <!-- Unreleased Cards -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow flex">
                <div class="p-3 rounded-full bg-yellow-100 mr-4">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs uppercase font-medium text-gray-500">Unreleased</p>
                    <p class="text-xl font-semibold text-gray-800">{{ masterCards.filter(mc => !mc.released_date).length }}</p>
                    <p class="text-xs text-gray-500">Master Cards</p>
                </div>
            </div>
            
            <!-- Active Cards -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow flex">
                <div class="p-3 rounded-full bg-purple-100 mr-4">
                    <i class="fas fa-check-circle text-purple-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs uppercase font-medium text-gray-500">Active</p>
                    <p class="text-xl font-semibold text-gray-800">{{ masterCards.filter(mc => mc.status === 'active').length }}</p>
                    <p class="text-xs text-gray-500">Master Cards</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
            <!-- Form Fields Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- AC# Field -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm transition-all hover:shadow-md">
                    <label for="ac" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        <span class="inline-flex items-center justify-center w-6 h-6 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full mr-2 shadow-sm">
                            <i class="fas fa-building text-white text-xs"></i>
                        </span>
                        <span class="text-base">AC#:</span>
                    </label>
                    <div class="relative flex group">
                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 group-hover:bg-indigo-50 group-hover:text-indigo-500 transition-colors">
                            <i class="fas fa-hashtag"></i>
                        </span>
                        <input 
                            type="text" 
                            id="ac" 
                            v-model="searchTerm"
                            placeholder="Enter customer AC#"
                            class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-all group-hover:border-indigo-300"
                        />
                        <button 
                            type="button"
                            @click="showOptions = !showOptions"
                            class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-r-md transition-all transform active:translate-y-px relative overflow-hidden shadow-sm"
                        >
                            <span class="absolute inset-0 bg-white opacity-0 hover:opacity-20 transition-opacity"></span>
                            <i class="fas fa-search relative z-10"></i>
                        </button>
                    </div>
                </div>

                <!-- MCS# Field -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm transition-all hover:shadow-md">
                    <label for="mcs" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        <span class="inline-flex items-center justify-center w-6 h-6 bg-gradient-to-r from-pink-500 to-red-500 rounded-full mr-2 shadow-sm">
                            <i class="fas fa-barcode text-white text-xs"></i>
                        </span>
                        <span class="text-base">MCS#:</span>
                    </label>
                    <div class="relative flex group">
                        <button
                            type="button"
                            @click="showMcsModal"
                            class="inline-flex items-center justify-center px-3 py-2 rounded-l-md border border-r-0 border-gray-300 bg-red-500 text-white hover:bg-red-600 transition-colors"
                        >
                            <i class="fas fa-barcode text-white"></i>
                        </button>
                        <input 
                            type="text" 
                            id="mcsFrom" 
                            v-model="form.mcsFrom"
                            placeholder="Start MCS#"
                            class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-all group-hover:border-indigo-300"
                        />
                        <span class="inline-flex items-center px-3 border border-l-0 border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                            <i class="fas fa-arrow-right text-xs"></i>
                        </span>
                        <input 
                            type="text" 
                            id="mcsTo"
                            v-model="form.mcsTo"
                            placeholder="End MCS#"
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
            </div>
            
            <!-- Status Checkboxes -->
            <div class="mb-6 flex items-center space-x-6 bg-blue-50 p-4 rounded-lg border border-blue-100 shadow-sm">
                <label class="text-sm font-medium text-gray-700 flex items-center">
                    <span class="inline-flex items-center justify-center w-6 h-6 bg-gradient-to-r from-blue-400 to-blue-500 rounded-full mr-2 shadow-sm">
                        <i class="fas fa-filter text-white text-xs"></i>
                    </span>
                    <span class="text-base">MC Status:</span>
                </label>
                <div class="flex items-center bg-white px-3 py-2 rounded-md shadow-sm border border-gray-200">
                    <input 
                        type="checkbox" 
                        id="status-active" 
                        v-model="releaseStatus" 
                        value="active"
                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                    />
                    <label for="status-active" class="ml-2 block text-sm text-gray-700">
                        <i class="fas fa-check-circle text-green-500 mr-1"></i>
                        Active
                    </label>
                </div>
                <div class="flex items-center bg-white px-3 py-2 rounded-md shadow-sm border border-gray-200">
                    <input 
                        type="checkbox" 
                        id="status-obsolete" 
                        v-model="releaseStatus" 
                        value="obsolete"
                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                    />
                    <label for="status-obsolete" class="ml-2 block text-sm text-gray-700">
                        <i class="fas fa-ban text-red-500 mr-1"></i>
                        Obsolete
                    </label>
                </div>
            </div>

            <!-- Action Button -->
            <div class="flex justify-end">
                <button 
                    type="button" 
                    @click="handleOK"
                    class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-5 py-2 rounded-lg flex items-center space-x-2 transform active:translate-y-px transition-all duration-300 shadow-md relative overflow-hidden group"
                >
                    <span class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity"></span>
                    <div class="bg-white bg-opacity-30 rounded-full p-1.5 mr-2 flex items-center justify-center">
                        <i class="fas fa-play text-white text-xs"></i>
                    </div>
                    <span class="font-medium">Proceed</span>
                </button>
            </div>
        </div>

        <!-- Master Card Table Section -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
            <div class="border-b border-gray-200 bg-gradient-to-r from-purple-50 to-indigo-50 p-4 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <span class="inline-flex items-center justify-center w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full mr-3 shadow-sm">
                        <i class="fas fa-clipboard-check text-white text-sm"></i>
                    </span>
                    Approved Master Cards
                </h3>
                <div class="flex items-center space-x-2 text-sm text-gray-500">
                    <div class="flex items-center">
                        <i class="fas fa-sort text-indigo-500 mr-1"></i>
                        <span>Sort by:</span>
                    </div>
                    <select 
                        v-model="sortBy" 
                        class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                    >
                        <option value="seq">MC Seq#</option>
                        <option value="model">MC Model</option>
                        <option value="customer">Customer</option>
                        <option value="approved_date">Approved Date</option>
                    </select>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <i class="fas fa-hashtag text-indigo-400 mr-2"></i>
                                    MC Seq#
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <i class="fas fa-cube text-indigo-400 mr-2"></i>
                                    MC Model
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <i class="fas fa-building text-indigo-400 mr-2"></i>
                                    Customer
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-check text-indigo-400 mr-2"></i>
                                    Approved Date
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <i class="fas fa-unlock-alt text-indigo-400 mr-2"></i>
                                    Released
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <i class="fas fa-tools text-indigo-400 mr-2"></i>
                                    Actions
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr 
                            v-for="mc in filteredMasterCards"
                            :key="mc.id"
                            class="hover:bg-indigo-50 transition-colors duration-150 cursor-pointer"
                            :class="{ 'bg-indigo-50 border-l-4 border-indigo-500': selectedMasterCard?.id === mc.id }"
                            @click="selectedMasterCard = mc"
                        >
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ mc.mc_seq }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ mc.mc_model }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ mc.customer_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ formatDate(mc.approved_date) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span 
                                    class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full items-center"
                                    :class="{
                                        'bg-green-100 text-green-800': mc.released_date, 
                                        'bg-yellow-100 text-yellow-800': !mc.released_date
                                    }">
                                    <i 
                                        :class="{
                                            'fas fa-check-circle mr-1': mc.released_date,
                                            'fas fa-clock mr-1': !mc.released_date
                                        }"
                                    ></i>
                                    {{ mc.released_date ? 'Released' : 'Not Released' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button 
                                    v-if="!mc.released_date" 
                                    class="bg-indigo-100 hover:bg-indigo-200 text-indigo-800 py-1 px-3 rounded-full mr-2 transition-colors" 
                                    @click.stop="handleRelease(mc)"
                                >
                                    <i class="fas fa-unlock text-indigo-600 mr-1"></i> Release
                                </button>
                                <button 
                                    v-else 
                                    class="bg-red-100 hover:bg-red-200 text-red-800 py-1 px-3 rounded-full transition-colors" 
                                    @click.stop="handleUnrelease(mc)"
                                >
                                    <i class="fas fa-lock text-red-600 mr-1"></i> Unreleased
                                </button>
                            </td>
                        </tr>
                        <tr v-if="filteredMasterCards.length === 0">
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="bg-gray-100 rounded-full p-4 mb-3">
                                        <i class="fas fa-search text-gray-400 text-2xl"></i>
                                    </div>
                                    <p>No approved master cards found.</p> 
                                    <p class="text-sm mt-1">Please adjust your search or filter criteria.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Selected Master Card Details -->
        <div v-if="selectedMasterCard" class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
            <div class="border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50 p-4 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <span class="inline-flex items-center justify-center w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full mr-3 shadow-sm">
                        <i class="fas fa-info-circle text-white text-sm"></i>
                    </span>
                    Selected Master Card Details
                </h3>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Basic Info -->
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-100 shadow-sm">
                        <h4 class="text-sm font-semibold text-blue-800 mb-3 flex items-center">
                            <i class="fas fa-id-card text-blue-500 mr-2"></i>
                            Basic Information
                        </h4>
                        <div class="space-y-2">
                            <div class="flex items-start">
                                <span class="text-xs font-medium text-gray-500 w-24">MC Seq#:</span>
                                <span class="text-sm font-semibold text-gray-900">{{ selectedMasterCard.mc_seq }}</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-xs font-medium text-gray-500 w-24">MC Model:</span>
                                <span class="text-sm text-gray-900">{{ selectedMasterCard.mc_model }}</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-xs font-medium text-gray-500 w-24">Customer:</span>
                                <span class="text-sm text-gray-900">{{ selectedMasterCard.customer_name }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Approval Info -->
                    <div class="bg-green-50 p-4 rounded-lg border border-green-100 shadow-sm">
                        <h4 class="text-sm font-semibold text-green-800 mb-3 flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            Approval Information
                        </h4>
                        <div class="space-y-2">
                            <div class="flex items-start">
                                <span class="text-xs font-medium text-gray-500 w-24">Approved By:</span>
                                <span class="text-sm text-gray-900">{{ selectedMasterCard.approved_by || 'N/A' }}</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-xs font-medium text-gray-500 w-24">Approved Date:</span>
                                <span class="text-sm text-gray-900">{{ formatDate(selectedMasterCard.approved_date) }}</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-xs font-medium text-gray-500 w-24">Status:</span>
                                <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full" :class="{'bg-green-100 text-green-800': selectedMasterCard.status === 'active', 'bg-red-100 text-red-800': selectedMasterCard.status === 'obsolete'}">
                                    {{ selectedMasterCard.status === 'active' ? 'Active' : 'Obsolete' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Release Info -->
                    <div class="bg-purple-50 p-4 rounded-lg border border-purple-100 shadow-sm">
                        <h4 class="text-sm font-semibold text-purple-800 mb-3 flex items-center">
                            <i class="fas fa-unlock-alt text-purple-500 mr-2"></i>
                            Release Information
                        </h4>
                        <div class="space-y-2">
                            <div class="flex items-start">
                                <span class="text-xs font-medium text-gray-500 w-24">Released By:</span>
                                <span class="text-sm text-gray-900">{{ selectedMasterCard.released_by || 'Not released yet' }}</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-xs font-medium text-gray-500 w-24">Released Date:</span>
                                <span class="text-sm text-gray-900">{{ formatDate(selectedMasterCard.released_date) || 'Not released yet' }}</span>
                            </div>
                            <div class="flex items-start mt-3 justify-end">
                                <button 
                                    v-if="!selectedMasterCard.released_date" 
                                    @click="handleRelease(selectedMasterCard)"
                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs leading-4 font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    <i class="fas fa-unlock-alt mr-1.5"></i>
                                    Release This MC
                                </button>
                                <button 
                                    v-else
                                    @click="handleUnrelease(selectedMasterCard)"
                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs leading-4 font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                >
                                    <i class="fas fa-lock mr-1.5"></i>
                                    Unreleased This MC
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
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full animate-modal">
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-4 py-3 sm:px-6 flex items-center">
                        <div class="mr-3 bg-white rounded-full p-2 shadow-inner">
                            <i class="fas fa-unlock-alt text-indigo-600"></i>
                        </div>
                        <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">
                            Release Master Card
                        </h3>
                    </div>
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-key text-indigo-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <div class="mt-2">
                                    <div class="flex items-center mb-3 text-indigo-700 bg-indigo-50 p-3 rounded-lg">
                                        <i class="fas fa-info-circle mr-2 text-lg"></i>
                                        <p class="text-sm">
                                            You are about to release <span class="font-bold">{{ masterCardToAction?.mc_seq }}</span> for production use.
                                        </p>
                                    </div>
                                    <p class="text-sm text-gray-500 mb-2 flex items-center">
                                        <i class="fas fa-clipboard-check mr-2 text-indigo-500"></i>
                                        Add release notes (optional):
                                    </p>
                                    <div class="relative">
                                        <textarea 
                                            v-model="releaseNotes" 
                                            rows="4" 
                                            placeholder="Enter additional information about this release..."
                                            class="mt-1 shadow-sm block w-full focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border border-gray-300 rounded-md"
                                        ></textarea>
                                        <div class="absolute inset-0 border-2 border-transparent group-focus-within:border-indigo-500 rounded-md pointer-events-none"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button 
                            type="button" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-base font-medium text-white hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm items-center" 
                            @click="confirmRelease"
                        >
                            <i class="fas fa-unlock mr-2"></i>
                            Release
                        </button>
                        <button 
                            type="button" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm items-center" 
                            @click="showReleaseModal = false"
                        >
                            <i class="fas fa-times mr-2"></i>
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
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full animate-modal">
                    <div class="bg-gradient-to-r from-red-600 to-pink-600 px-4 py-3 sm:px-6 flex items-center">
                        <div class="mr-3 bg-white rounded-full p-2 shadow-inner">
                            <i class="fas fa-lock text-red-600"></i>
                        </div>
                        <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">
                            Unreleased Master Card
                        </h3>
                    </div>
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-exclamation-triangle text-red-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <div class="mt-2">
                                    <div class="flex items-center mb-3 text-red-700 bg-red-50 p-3 rounded-lg">
                                        <i class="fas fa-info-circle mr-2 text-lg"></i>
                                        <p class="text-sm">
                                            You are about to unreleased <span class="font-bold">{{ masterCardToAction?.mc_seq }}</span>.
                                        </p>
                                    </div>
                                    <p class="text-sm text-gray-500">
                                        This action will revert the release status. The master card will no longer be available for production use.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button 
                            type="button" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gradient-to-r from-red-600 to-pink-600 text-base font-medium text-white hover:from-red-700 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm items-center" 
                            @click="confirmUnrelease"
                        >
                            <i class="fas fa-lock mr-2"></i>
                            Unreleased
                        </button>
                        <button 
                            type="button" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm items-center" 
                            @click="showUnreleaseModal = false"
                        >
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Notification -->
        <div 
            v-if="notification.show" 
            class="fixed bottom-4 right-4 max-w-sm px-6 py-4 rounded-lg shadow-xl z-50 transform transition-all duration-500 translate-y-0 opacity-100 animate-notification"
            :class="{
                'bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500': notification.type === 'success',
                'bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500': notification.type === 'error'
            }"
        >
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div 
                        class="p-2 rounded-full flex items-center justify-center"
                        :class="{
                            'bg-green-200': notification.type === 'success',
                            'bg-red-200': notification.type === 'error'
                        }"
                    >
                        <i 
                            :class="{
                                'fas fa-check-circle text-green-600': notification.type === 'success',
                                'fas fa-exclamation-circle text-red-600': notification.type === 'error'
                            }"
                            class="text-xl"
                        ></i>
                    </div>
                </div>
                <div class="ml-3 flex-1">
                    <p 
                        class="text-sm font-medium"
                        :class="{
                            'text-green-800': notification.type === 'success',
                            'text-red-800': notification.type === 'error'
                        }"
                    >
                        {{ notification.message }}
                    </p>
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button
                        @click="hideNotification"
                        class="rounded-full p-1 inline-flex text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition-colors"
                    >
                        <span class="sr-only">Close</span>
                        <i class="fas fa-times"></i>
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

const props = defineProps({
    masterCards: Array,
    customers: Array,
});

// Define reactive state variables
const sortBy = ref('seq');
const releaseStatus = ref(['active']);
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

// Sample master card data
const masterCards = ref(props.masterCards || [
    { id: 1, mc_seq: 'MC001', mc_model: 'Box-Standard', customer_name: 'ACME Corp', approved_date: '2023-10-15T09:30:00', approved_by: 'John Doe', released_date: null, status: 'active' },
    { id: 2, mc_seq: 'MC002', mc_model: 'Box-Premium', customer_name: 'TechCorp Inc', approved_date: '2023-10-14T14:45:00', approved_by: 'Jane Smith', released_date: '2023-10-16T10:20:00', released_by: 'Admin User', status: 'active' },
    { id: 3, mc_seq: 'MC003', mc_model: 'Container-Small', customer_name: 'Global Shipping', approved_date: '2023-10-13T11:15:00', approved_by: 'Mike Johnson', released_date: null, status: 'active' }
]);

const selectedMasterCard = ref(null);
const searchTerm = ref('');

// Computed property to filter and sort master cards based on state
const filteredMasterCards = computed(() => {
    let filtered = masterCards.value.filter(mc => {
        const matchesSearch = mc.mc_model.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
                             mc.mc_seq.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
                             mc.customer_name.toLowerCase().includes(searchTerm.value.toLowerCase());
        
        // Match status
        const matchesStatus = releaseStatus.value.includes(mc.status);
        
        return matchesSearch && matchesStatus;
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
    // Handle OK button 
    console.log('Proceed clicked.');
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
        // Update the status locally for demo
        const index = masterCards.value.findIndex(mc => mc.id === masterCardToAction.value.id);
        if (index !== -1) {
            masterCards.value[index].released_by = 'Current User';
            masterCards.value[index].released_date = new Date().toISOString();
            masterCards.value[index].release_notes = releaseNotes.value;
        }
        
        // Show success message
        showNotification('Master Card released successfully!');
    } catch (error) {
        console.error('Error releasing master card:', error);
        showNotification('An error occurred during release', 'error');
    }
    
    showReleaseModal.value = false;
};

const confirmUnrelease = async () => {
    try {
        // Update the status locally for demo
        const index = masterCards.value.findIndex(mc => mc.id === masterCardToAction.value.id);
        if (index !== -1) {
            masterCards.value[index].released_by = null;
            masterCards.value[index].released_date = null;
            masterCards.value[index].release_notes = null;
        }
        
        // Show success message
        showNotification('Master Card un-released successfully!');
    } catch (error) {
        console.error('Error un-releasing master card:', error);
        showNotification('An error occurred during un-releasing', 'error');
    }
    
    showUnreleaseModal.value = false;
};
</script>

<style>
/* Add transition effects */
.fixed.inset-0 {
    animation: modalFadeIn 0.2s ease-out forwards;
}

.animate-modal {
    animation: modalScaleIn 0.3s ease-out forwards;
}

.animate-notification {
    animation: slideInRight 0.5s ease-out forwards;
}

@keyframes modalFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes modalScaleIn {
    from { transform: scale(0.95); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

@keyframes slideInRight {
    from { 
        transform: translateX(100%);
        opacity: 0;
    }
    to { 
        transform: translateX(0);
        opacity: 1;
    }
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
</style>
