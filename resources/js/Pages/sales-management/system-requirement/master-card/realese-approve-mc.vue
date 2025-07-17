<!-- 
  Release Approved Master Card Component
  This component handles the release of approved master cards in the system
-->
<template>
    <AppLayout :header="'Release Approved MC'">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 p-6 rounded-t-lg shadow-lg overflow-hidden relative">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20 animate-pulse-slow"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10 animate-pulse-slow animation-delay-500"></div>
            <div class="flex items-center">
                <div class="bg-gradient-to-br from-pink-500 to-purple-600 p-3 rounded-lg shadow-inner mr-4">
                    <i class="fas fa-unlock-alt text-white text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-white text-shadow">Release Approved Master Card</h2>
                    <p class="text-blue-100">Release approved master cards for production use</p>
                </div>
            </div>
        </div>

        <!-- Main Content Section -->
        <div class="rounded-b-lg shadow-lg p-6 mb-6 bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left/Main Column -->
                <div class="lg:col-span-2 animate-fade-in-up">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <!-- Total Cards -->
                        <div class="bg-white p-4 rounded-lg shadow-sm border-t-4 border-blue-500 hover:shadow-md transition-shadow flex">
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
                        <div class="bg-white p-4 rounded-lg shadow-sm border-t-4 border-green-500 hover:shadow-md transition-shadow flex">
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
                        <div class="bg-white p-4 rounded-lg shadow-sm border-t-4 border-yellow-500 hover:shadow-md transition-shadow flex">
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
                        <div class="bg-white p-4 rounded-lg shadow-sm border-t-4 border-purple-500 hover:shadow-md transition-shadow flex">
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

            <!-- Form Fields Section -->
                    <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-indigo-500 transition-all duration-300 hover:shadow-xl relative overflow-hidden mb-6">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-50 rounded-full opacity-50"></div>
                        <div class="absolute -bottom-12 -left-12 w-36 h-36 bg-purple-50 rounded-full opacity-50"></div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- AC# Field -->
                            <div>
                    <label for="ac" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <span class="flex items-center justify-center h-6 w-6 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white mr-3 shadow-md transition-transform group-hover:scale-110">
                                        <i class="fas fa-building text-xs"></i>
                        </span>
                                    AC#:
                    </label>
                    <div class="relative flex group">
                                    <input type="text" id="ac" v-model="searchTerm" placeholder="Enter customer AC#" class="input-field">
                                    <button type="button" @click="showOptions = true" class="inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 rounded-r-md transition-all duration-300 bg-gradient-to-r from-indigo-500 to-purple-500 text-white hover:from-indigo-600 hover:to-purple-600 shadow-sm hover:shadow-md transform hover:-translate-y-px">
                                        <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
            <!-- MCS# Range Field as separate block below AC# -->
            <div class="mb-6">
                <label for="mcs" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                    <span class="flex items-center justify-center h-6 w-6 rounded-full bg-gradient-to-br from-pink-500 to-orange-500 text-white mr-3 shadow-md transition-transform group-hover:scale-110">
                        <i class="fas fa-barcode text-xs"></i>
                    </span>
                    MCS# Range:
                </label>
                <div class="flex items-center gap-4">
                    <div class="relative flex group">
                        <input type="text" id="mcsFrom" v-model="form.mcsFrom" placeholder="Start MCS#" class="input-field" style="min-width:220px;max-width:340px;width:100%;" />
                        <button type="button" @click="mcsInputActive = 'from'; showMcsOptions = true" class="inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 rounded-r-md transition-all duration-300 bg-gradient-to-r from-pink-500 to-orange-500 text-white hover:from-pink-600 hover:to-orange-600 shadow-sm hover:shadow-md transform hover:-translate-y-px">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <span class="text-gray-500 font-medium">TO</span>
                    <div class="relative flex group">
                        <input type="text" id="mcsTo" v-model="form.mcsTo" placeholder="End MCS#" class="input-field" style="min-width:220px;max-width:340px;width:100%;" />
                        <button type="button" @click="mcsInputActive = 'to'; showMcsOptions = true" class="inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 rounded-r-md transition-all duration-300 bg-gradient-to-r from-pink-500 to-orange-500 text-white hover:from-pink-600 hover:to-orange-600 shadow-sm hover:shadow-md transform hover:-translate-y-px">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Status Checkboxes -->
            <div class="mb-6">
                <div class="flex flex-col md:flex-row items-center gap-4 p-4 rounded-xl shadow-lg border-t-4 border-blue-400 bg-gradient-to-r from-blue-100 via-indigo-100 to-purple-100 relative overflow-hidden animate-fade-in-up">
                    <div class="flex items-center mb-2 md:mb-0">
                        <span class="inline-flex items-center justify-center w-8 h-8 bg-gradient-to-r from-blue-400 to-blue-600 rounded-full mr-3 shadow-md">
                            <i class="fas fa-filter text-white text-lg"></i>
                        </span>
                        <span class="text-lg font-semibold text-blue-800 tracking-wide">MC Status:</span>
                    </div>
                    <div class="flex flex-1 flex-col sm:flex-row gap-3 w-full">
                        <label class="flex items-center flex-1 cursor-pointer bg-white/80 hover:bg-blue-50 border border-blue-200 rounded-lg px-4 py-2 shadow-sm transition-all duration-200 group">
                            <input type="checkbox" id="status-active" v-model="releaseStatus" value="active" class="h-5 w-5 text-green-500 border-green-300 rounded focus:ring-green-400 transition-all mr-3" />
                            <span class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 text-xl mr-2 group-hover:scale-110 transition-transform"></i>
                                <span class="font-medium text-green-800 text-base">Active</span>
                            </span>
                        </label>
                        <label class="flex items-center flex-1 cursor-pointer bg-white/80 hover:bg-pink-50 border border-pink-200 rounded-lg px-4 py-2 shadow-sm transition-all duration-200 group">
                            <input type="checkbox" id="status-obsolete" v-model="releaseStatus" value="obsolete" class="h-5 w-5 text-pink-500 border-pink-300 rounded focus:ring-pink-400 transition-all mr-3" />
                            <span class="flex items-center">
                                <i class="fas fa-ban text-pink-500 text-xl mr-2 group-hover:scale-110 transition-transform"></i>
                                <span class="font-medium text-pink-800 text-base">Obsolete</span>
                            </span>
                        </label>
                    </div>
                </div>
            </div>
            <!-- Action Button -->
                        <div class="pt-6 text-center border-t border-gray-200">
                            <button type="button" @click="handleOK" class="process-button group">
                                <span class="shimmer-effect"></span>
                                <i class="fas fa-play mr-3 text-xl group-hover:animate-spin"></i>
                                Proceed
                </button>
            </div>
        </div>

        <!-- Master Card Table Section -->
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6 animate-fade-in-up animation-delay-300">
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
                                <select v-model="sortBy" class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="seq">MC Seq#</option>
                        <option value="model">MC Model</option>
                        <option value="customer">Customer</option>
                        <option value="approved_date">Approved Date</option>
                    </select>
                </div>
            </div>
            
            <!-- Error message if there's an error -->
            <div v-if="error" class="p-6 text-center">
                <div class="bg-red-50 p-4 rounded-lg border border-red-200 inline-block">
                    <div class="flex items-center mb-3">
                        <div class="p-2 bg-red-100 rounded-full mr-3">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <h4 class="text-red-800 font-medium">Error Loading Data</h4>
                    </div>
                    <p class="text-red-700 text-sm">{{ error }}</p>
                    <button 
                        @click="fetchData"
                        class="mt-3 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                    >
                        <i class="fas fa-redo mr-2"></i> Try Again
                    </button>
                </div>
            </div>
            
            <!-- Loading indicator -->
            <div v-else-if="loading" class="p-10 text-center">
                <div class="inline-block animate-spin rounded-full h-10 w-10 border-t-2 border-b-2 border-indigo-500 mb-4"></div>
                <p class="text-gray-500">Loading master cards...</p>
            </div>
            
            <!-- Table content when data is loaded -->
            <div v-else class="overflow-x-auto">
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
                </div>

                <!-- Right/Info Column -->
                <div class="lg:col-span-1 animate-fade-in-up animation-delay-300">
                    <!-- Quick Info Panel -->
                    <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-indigo-500 transition-all duration-300 hover:shadow-xl relative overflow-hidden mb-6">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-50 rounded-full opacity-50"></div>
                        <div class="absolute -bottom-12 -left-12 w-36 h-36 bg-purple-50 rounded-full opacity-50"></div>
                        <div class="flex items-center justify-center h-full">
                            <div class="text-center">
                                <h3 class="text-xl font-semibold text-gray-800 mb-4">Quick Info</h3>
                                <p class="text-base text-gray-700">
                                    This section provides quick information about the master cards.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
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

        <!-- Modal Options -->
        <transition name="fade">
            <div v-if="showOptions" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30">
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-auto relative animate-fade-in-up">
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-500 rounded-t-xl px-6 py-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center justify-center w-10 h-10 bg-white/20 rounded-full"><i class="fas fa-filter text-white text-2xl"></i></span>
                            <span class="text-white text-xl font-bold">Options</span>
                        </div>
                        <button @click="showOptions = false" class="text-white text-2xl hover:bg-white/20 rounded-full w-10 h-10 flex items-center justify-center"><i class="fas fa-times"></i></button>
                    </div>
                    <!-- Modal Body -->
                    <div class="p-6 space-y-6">
                        <div class="border rounded-xl p-4 bg-gray-50">
                            <div class="flex items-center gap-2 mb-2"><i class="fas fa-sort text-indigo-500"></i><span class="font-semibold text-lg">Sort by:</span></div>
                            <div class="flex flex-col gap-2 mt-2">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="radio" v-model="optionSortBy" value="code" class="accent-indigo-500 h-5 w-5" />
                                    <span class="text-gray-700 text-base">Customer Code</span>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="radio" v-model="optionSortBy" value="name" class="accent-indigo-500 h-5 w-5" />
                                    <span class="text-gray-700 text-base">Customer Name</span>
                                </label>
                            </div>
                        </div>
                        <div class="border rounded-xl p-4 bg-gray-50">
                            <div class="flex items-center gap-2 mb-2"><i class="fas fa-tag text-blue-500"></i><span class="font-semibold text-lg">Record Status:</span></div>
                            <div class="flex items-center gap-6 mt-2">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" v-model="optionStatus" value="active" class="accent-blue-500 h-5 w-5" />
                                    <span class="text-blue-800 font-medium">Active</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" v-model="optionStatus" value="obsolete" class="accent-blue-500 h-5 w-5" />
                                    <span class="text-blue-800 font-medium">Obsolete</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- Modal Footer -->
                    <div class="flex justify-center gap-4 px-6 pb-6">
                        <button @click="applyOptions" class="bg-gradient-to-r from-blue-500 to-purple-500 text-white font-bold px-8 py-2 rounded-lg shadow hover:from-blue-600 hover:to-purple-600 flex items-center gap-2"><i class="fas fa-check"></i> OK</button>
                        <button @click="showOptions = false" class="bg-gray-200 text-gray-700 font-bold px-8 py-2 rounded-lg shadow flex items-center gap-2"><i class="fas fa-times"></i> Exit</button>
                    </div>
                </div>
            </div>
        </transition>

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

        <!-- Options Modal from Customer Account -->
        <transition name="fade">
            <div v-if="showOptionsFromCustomer" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30">
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-auto relative animate-fade-in-up">
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-500 rounded-t-xl px-6 py-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center justify-center w-10 h-10 bg-white/20 rounded-full"><i class="fas fa-filter text-white text-2xl"></i></span>
                            <span class="text-white text-xl font-bold">Options</span>
                        </div>
                        <button @click="showOptionsFromCustomer = false" class="text-white text-2xl hover:bg-white/20 rounded-full w-10 h-10 flex items-center justify-center"><i class="fas fa-times"></i></button>
                    </div>
                    <!-- Modal Body -->
                    <div class="p-6 space-y-6">
                        <div class="border rounded-xl p-4 bg-gray-50">
                            <div class="flex items-center gap-2 mb-2"><i class="fas fa-sort text-indigo-500"></i><span class="font-semibold text-lg">Sort by:</span></div>
                            <div class="flex flex-col gap-2 mt-2">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="radio" v-model="optionSortBy" value="code" class="accent-indigo-500 h-5 w-5" />
                                    <span class="text-gray-700 text-base">Customer Code</span>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="radio" v-model="optionSortBy" value="name" class="accent-indigo-500 h-5 w-5" />
                                    <span class="text-gray-700 text-base">Customer Name</span>
                                </label>
                            </div>
                        </div>
                        <div class="border rounded-xl p-4 bg-gray-50">
                            <div class="flex items-center gap-2 mb-2"><i class="fas fa-tag text-blue-500"></i><span class="font-semibold text-lg">Record Status:</span></div>
                            <div class="flex items-center gap-6 mt-2">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" v-model="optionStatus" value="active" class="accent-blue-500 h-5 w-5" />
                                    <span class="text-blue-800 font-medium">Active</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" v-model="optionStatus" value="obsolete" class="accent-blue-500 h-5 w-5" />
                                    <span class="text-blue-800 font-medium">Obsolete</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- Modal Footer -->
                    <div class="flex justify-center gap-4 px-6 pb-6">
                        <button @click="applyOptionsFromCustomer" class="bg-gradient-to-r from-blue-500 to-purple-500 text-white font-bold px-8 py-2 rounded-lg shadow hover:from-blue-600 hover:to-purple-600 flex items-center gap-2"><i class="fas fa-check"></i> OK</button>
                        <button @click="showOptionsFromCustomer = false" class="bg-gray-200 text-gray-700 font-bold px-8 py-2 rounded-lg shadow flex items-center gap-2"><i class="fas fa-times"></i> Exit</button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Modal MCS Options -->
        <transition name="fade">
            <div v-if="showMcsOptions" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30">
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-auto relative animate-fade-in-up">
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-green-500 to-teal-500 rounded-t-xl px-6 py-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center justify-center w-10 h-10 bg-white/20 rounded-full"><i class="fas fa-filter text-white text-2xl"></i></span>
                            <span class="text-white text-xl font-bold">Options</span>
                        </div>
                        <button @click="showMcsOptions = false" class="text-white text-2xl hover:bg-white/20 rounded-full w-10 h-10 flex items-center justify-center"><i class="fas fa-times"></i></button>
                    </div>
                    <!-- Modal Body -->
                    <div class="p-6 space-y-6">
                        <div class="border rounded-xl p-4 bg-gray-50">
                            <div class="flex items-center gap-2 mb-2"><i class="fas fa-sort text-blue-500"></i><span class="font-semibold text-lg">Sort by:</span></div>
                            <div class="flex flex-col gap-2 mt-2">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="radio" v-model="mcsSortBy" value="seq" class="accent-blue-600 h-5 w-5" />
                                    <span class="text-gray-700 text-base">MC Seq#</span>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="radio" v-model="mcsSortBy" value="model" class="accent-blue-600 h-5 w-5" />
                                    <span class="text-gray-700 text-base">MC Model</span>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="radio" v-model="mcsSortBy" value="part" class="accent-blue-600 h-5 w-5" />
                                    <span class="text-gray-700 text-base">MC PD Part#</span>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="radio" v-model="mcsSortBy" value="ed" class="accent-blue-600 h-5 w-5" />
                                    <span class="text-gray-700 text-base">MC PD ED</span>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="radio" v-model="mcsSortBy" value="id" class="accent-blue-600 h-5 w-5" />
                                    <span class="text-gray-700 text-base">MC PD ID</span>
                                </label>
                            </div>
                        </div>
                        <div class="border rounded-xl p-4 bg-gray-50">
                            <div class="flex items-center gap-2 mb-2"><i class="fas fa-arrow-up text-blue-500"></i><span class="font-semibold text-lg">Sort Order:</span></div>
                            <div class="flex items-center gap-6 mt-2">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" v-model="mcsSortOrder" value="asc" class="accent-blue-600 h-5 w-5" />
                                    <span class="text-gray-700 font-medium">Ascending</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" v-model="mcsSortOrder" value="desc" class="accent-blue-600 h-5 w-5" />
                                    <span class="text-gray-700 font-medium">Descending</span>
                                </label>
                            </div>
                        </div>
                        <div class="border rounded-xl p-4 bg-gray-50">
                            <div class="flex items-center gap-2 mb-2"><i class="fas fa-tag text-blue-500"></i><span class="font-semibold text-lg">MC Status:</span></div>
                            <div class="flex items-center gap-6 mt-2">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" v-model="mcsStatus" value="active" class="accent-blue-600 h-5 w-5" />
                                    <span class="text-blue-800 font-medium">Active</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" v-model="mcsStatus" value="obsolete" class="accent-blue-600 h-5 w-5" />
                                    <span class="text-blue-800 font-medium">Obsolete</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- Modal Footer -->
                    <div class="flex justify-center gap-4 px-6 pb-6">
                        <button @click="applyMcsOptions(true)" class="bg-blue-600 text-white font-bold px-8 py-2 rounded-lg shadow hover:bg-blue-700 flex items-center gap-2"><i class="fas fa-check"></i> OK</button>
                        <button @click="showMcsOptions = false" class="bg-gray-200 text-gray-700 font-bold px-8 py-2 rounded-lg shadow flex items-center gap-2"><i class="fas fa-times"></i> Exit</button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Customer Account Table Modal (Colorful Modern Style) -->
        <transition name="fade">
            <div v-if="showCustomerAccountModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30">
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl mx-auto relative animate-fade-in-up transform transition-all duration-300 scale-95 opacity-0" :class="{'scale-100 opacity-100': showCustomerAccountModal}">
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 p-6 rounded-t-xl shadow-lg flex items-center justify-between relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -translate-y-16 translate-x-16"></div>
                        <div class="flex items-center gap-4">
                            <span class="inline-flex items-center justify-center w-12 h-12 bg-white/20 rounded-full flex-shrink-0 backdrop-blur-sm">
                                <i class="fas fa-th-large text-white text-2xl"></i>
                            </span>
                            <span class="text-white text-2xl font-bold z-10">Customer Account Table</span>
                        </div>
                        <button @click="showCustomerAccountModal = false" class="text-white text-3xl hover:bg-white/20 rounded-full w-10 h-10 flex items-center justify-center transition-all duration-300 transform hover:rotate-90 z-10">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-6 bg-white overflow-hidden">
                        <div class="flex flex-col md:flex-row justify-between items-center mb-6 bg-blue-50 p-4 rounded-lg border border-blue-100 shadow-sm">
                            <div class="mb-4 md:mb-0 flex items-center">
                                <span class="font-bold text-gray-700 mr-2">Filter:</span>
                                <span class="text-green-600 font-medium text-sm px-3 py-1 bg-green-100 rounded-full shadow-inner">Active Records Only</span>
                            </div>
                            <div class="relative w-full md:w-auto flex items-center">
                                <input type="text" v-model="customerSearchTerm" placeholder="Search customer..." class="w-full md:w-64 pl-4 pr-10 py-2 border border-gray-300 rounded-full focus:ring-indigo-500 focus:border-indigo-500 transition-all shadow-md text-gray-700 placeholder-gray-400 focus:shadow-lg">
                                <button class="absolute right-3 text-gray-500 hover:text-indigo-600 transition-colors duration-200">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Customer Table -->
                        <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-lg mb-6 max-h-96 custom-scrollbar" style="max-height: 50vh;">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-blue-200 to-indigo-200 sticky top-0 z-10">
                                    <tr v-if="optionSortBy === 'code'">
                                        <th class="px-6 py-3 text-left text-xs font-bold text-blue-900 uppercase tracking-wider whitespace-nowrap">Customer Code</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-blue-900 uppercase tracking-wider whitespace-nowrap">Customer Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-blue-900 uppercase tracking-wider whitespace-nowrap">S/person</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-blue-900 uppercase tracking-wider whitespace-nowrap">AC Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-blue-900 uppercase tracking-wider whitespace-nowrap">Currency</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-blue-900 uppercase tracking-wider whitespace-nowrap">Status</th>
                                    </tr>
                                    <tr v-else-if="optionSortBy === 'name'">
                                        <th class="px-6 py-3 text-left text-xs font-bold text-blue-900 uppercase tracking-wider whitespace-nowrap">Customer Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-blue-900 uppercase tracking-wider whitespace-nowrap">Customer Code</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-blue-900 uppercase tracking-wider whitespace-nowrap">S/person</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-blue-900 uppercase tracking-wider whitespace-nowrap">AC Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-blue-900 uppercase tracking-wider whitespace-nowrap">Currency</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-blue-900 uppercase tracking-wider whitespace-nowrap">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    <tr 
                                        v-for="customer in filteredCustomers"
                                        :key="customer.code"
                                        @click="selectCustomer(customer)"
                                        class="hover:bg-blue-50 cursor-pointer transition-colors duration-150"
                                        :class="{'bg-blue-100 border-l-4 border-blue-500': selectedCustomer?.code === customer.code}"
                                    >
                                        <template v-if="optionSortBy === 'code'">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ customer.code }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ customer.name }}</td>
                                        </template>
                                        <template v-else-if="optionSortBy === 'name'">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ customer.name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ customer.code }}</td>
                                        </template>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ customer.salesperson }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ customer.ac_type }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ customer.currency }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span 
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                :class="{'bg-green-100 text-green-800': customer.status === 'Active'}"
                                            >
                                                {{ customer.status }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr v-if="filteredCustomers.length === 0">
                                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <i class="fas fa-box-open text-4xl text-gray-300 mb-3"></i>
                                                <p>No matching customers found.</p> 
                                                <p class="text-sm mt-1 text-gray-400">Try adjusting your search filters.</p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Modal Footer Buttons -->
                    <div class="flex justify-center gap-4 px-6 py-4 bg-gradient-to-r from-gray-100 to-gray-200 rounded-b-xl border-t border-gray-300 shadow-inner">
                        <button @click="showMoreOptionsFromCustomer" class="modern-button bg-gradient-to-r from-purple-500 to-indigo-500 hover:from-purple-600 hover:to-indigo-600">
                            <i class="fas fa-cogs mr-2"></i> More Options
                        </button>
                        <button class="modern-button bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600">
                            <i class="fas fa-search-plus mr-2"></i> Zoom
                        </button>
                        <button @click="selectCustomerAndClose" class="modern-button bg-gradient-to-r from-green-500 to-teal-500 hover:from-green-600 hover:to-teal-600">
                            <i class="fas fa-check-circle mr-2"></i> Select
                        </button>
                        <button @click="showCustomerAccountModal = false" class="modern-button bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600">
                            <i class="fas fa-times mr-2"></i> Exit
                        </button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Master Card Table Modal (Colorful Modern Style for MC Seq#) -->
        <transition name="fade">
            <div v-if="showMasterCardTableModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30">
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-auto relative animate-fade-in-up transform transition-all duration-300 scale-95 opacity-0" :class="{'scale-100 opacity-100': showMasterCardTableModal}">
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-green-500 via-teal-400 to-green-700 p-6 rounded-t-xl shadow-lg flex items-center justify-between relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-white opacity-10 rounded-full -translate-y-10 translate-x-10"></div>
                        <div class="flex items-center gap-4">
                            <span class="inline-flex items-center justify-center w-10 h-10 bg-white/20 rounded-full flex-shrink-0 backdrop-blur-sm">
                                <i class="fas fa-th-list text-white text-xl"></i>
                            </span>
                            <span class="text-white text-xl font-bold z-10">Master Card Table</span>
                        </div>
                        <button @click="showMasterCardTableModal = false" class="text-white text-2xl hover:bg-white/20 rounded-full w-10 h-10 flex items-center justify-center transition-all duration-300 transform hover:rotate-90 z-10">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <!-- Modal Body -->
                    <div class="p-6 bg-white overflow-hidden">
                        <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-lg mb-6 max-h-80 custom-scrollbar" style="max-height: 45vh;">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-green-200 to-teal-200 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-green-900 uppercase tracking-wider whitespace-nowrap">M/Card Seq#</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-green-900 uppercase tracking-wider whitespace-nowrap">Model</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-green-900 uppercase tracking-wider whitespace-nowrap">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    <tr 
                                        v-for="mc in filteredMasterCardsSeq"
                                        :key="mc.seq"
                                        @click="selectMasterCard(mc)"
                                        class="hover:bg-blue-50 cursor-pointer transition-colors duration-150"
                                        :class="{'bg-blue-100 border-l-4 border-blue-500': selectedMasterCardSeq?.seq === mc.seq}"
                                    >
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ mc.seq }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ mc.model }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">{{ mc.status }}</span>
                                        </td>
                                    </tr>
                                    <tr v-if="filteredMasterCardsSeq.length === 0">
                                        <td colspan="3" class="px-6 py-10 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <i class="fas fa-box-open text-4xl text-gray-300 mb-3"></i>
                                                <p>No matching master cards found.</p> 
                                                <p class="text-sm mt-1 text-gray-400">Try adjusting your search filters.</p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Modal Footer Buttons -->
                    <div class="flex justify-center gap-4 px-6 py-4 bg-gradient-to-r from-gray-100 to-gray-200 rounded-b-xl border-t border-gray-300 shadow-inner">
                        <button @click="showMoreOptionsFromMcsTable('seq')" class="modern-button bg-gradient-to-r from-green-500 to-teal-500 hover:from-green-600 hover:to-teal-600">
                            <i class="fas fa-cogs mr-2"></i> More Options
                        </button>
                        <button class="modern-button bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600">
                            <i class="fas fa-search-plus mr-2"></i> Zoom
                        </button>
                        <button @click="selectMasterCardAndClose" class="modern-button bg-gradient-to-r from-purple-500 to-indigo-500 hover:from-purple-600 hover:to-indigo-600">
                            <i class="fas fa-check-circle mr-2"></i> Select
                        </button>
                        <button @click="showMasterCardTableModal = false" class="modern-button bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600">
                            <i class="fas fa-times mr-2"></i> Exit
                        </button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Master Card Table Modal (Colorful Modern Style for MC Model) -->
        <transition name="fade">
            <div v-if="showMasterCardModelTableModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30">
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-auto relative animate-fade-in-up transform transition-all duration-300 scale-95 opacity-0" :class="{'scale-100 opacity-100': showMasterCardModelTableModal}">
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-green-500 via-teal-400 to-green-700 p-6 rounded-t-xl shadow-lg flex items-center justify-between relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-white opacity-10 rounded-full -translate-y-10 translate-x-10"></div>
                        <div class="flex items-center gap-4">
                            <span class="inline-flex items-center justify-center w-10 h-10 bg-white/20 rounded-full flex-shrink-0 backdrop-blur-sm">
                                <i class="fas fa-th-list text-white text-xl"></i>
                            </span>
                            <span class="text-white text-xl font-bold z-10">Master Card Table</span>
                        </div>
                        <button @click="showMasterCardModelTableModal = false" class="text-white text-2xl hover:bg-white/20 rounded-full w-10 h-10 flex items-center justify-center transition-all duration-300 transform hover:rotate-90 z-10">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <!-- Modal Body -->
                    <div class="p-6 bg-white overflow-hidden">
                        <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-lg mb-6 max-h-80 custom-scrollbar" style="max-height: 45vh;">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-blue-200 to-indigo-200 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-blue-900 uppercase tracking-wider whitespace-nowrap">Model</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-blue-900 uppercase tracking-wider whitespace-nowrap">M/Card Seq#</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-blue-900 uppercase tracking-wider whitespace-nowrap">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    <tr 
                                        v-for="mc in filteredMasterCardsModel"
                                        :key="mc.seq"
                                        @click="selectMasterCardModel(mc)"
                                        class="hover:bg-blue-50 cursor-pointer transition-colors duration-150"
                                        :class="{'bg-blue-100 border-l-4 border-blue-500': selectedMasterCardModel?.seq === mc.seq}"
                                    >
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ mc.model }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ mc.seq }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">{{ mc.status }}</span>
                                        </td>
                                    </tr>
                                    <tr v-if="filteredMasterCardsModel.length === 0">
                                        <td colspan="3" class="px-6 py-10 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <i class="fas fa-box-open text-4xl text-gray-300 mb-3"></i>
                                                <p>No matching master cards found.</p> 
                                                <p class="text-sm mt-1 text-gray-400">Try adjusting your search filters.</p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Modal Footer Buttons -->
                    <div class="flex justify-center gap-4 px-6 py-4 bg-gradient-to-r from-gray-100 to-gray-200 rounded-b-xl border-t border-gray-300 shadow-inner">
                        <button @click="showMoreOptionsFromMcsTable('model')" class="modern-button bg-gradient-to-r from-blue-500 to-indigo-500 hover:from-blue-600 hover:to-indigo-600">
                            <i class="fas fa-cogs mr-2"></i> More Options
                        </button>
                        <button class="modern-button bg-gradient-to-r from-cyan-500 to-blue-400 hover:from-cyan-600 hover:to-blue-500">
                            <i class="fas fa-search-plus mr-2"></i> Zoom
                        </button>
                        <button @click="selectMasterCardModelAndClose" class="modern-button bg-gradient-to-r from-purple-500 to-indigo-500 hover:from-purple-600 hover:to-indigo-600">
                            <i class="fas fa-check-circle mr-2"></i> Select
                        </button>
                        <button @click="showMasterCardModelTableModal = false" class="modern-button bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600">
                            <i class="fas fa-times mr-2"></i> Exit
                        </button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Master Card Table Modal (Modern Colorful Style for MC PD Part#) -->
        <transition name="fade">
            <div v-if="showMasterCardPartTableModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30">
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-3xl mx-auto relative animate-fade-in-up border border-gray-200 transform transition-all duration-300 scale-95 opacity-0" :class="{'scale-100 opacity-100': showMasterCardPartTableModal}">
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-green-500 via-teal-400 to-green-700 p-6 rounded-t-xl shadow-lg flex items-center justify-between relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-white opacity-10 rounded-full -translate-y-10 translate-x-10"></div>
                        <div class="flex items-center gap-4">
                            <span class="inline-flex items-center justify-center w-10 h-10 bg-white/20 rounded-full flex-shrink-0 backdrop-blur-sm">
                                <i class="fas fa-th-list text-white text-xl"></i>
                            </span>
                            <span class="text-white text-xl font-bold z-10">Master Card Table</span>
                        </div>
                        <button @click="showMasterCardPartTableModal = false" class="text-white text-2xl hover:bg-white/20 rounded-full w-10 h-10 flex items-center justify-center transition-all duration-300 transform hover:rotate-90 z-10">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <!-- Modal Body -->
                    <div class="p-6 bg-white overflow-hidden">
                        <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-lg mb-6 max-h-56 custom-scrollbar" style="max-height: 220px;">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-green-200 to-teal-200 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-bold text-green-900 uppercase tracking-wider whitespace-nowrap">Part No</th>
                                        <th class="px-3 py-2 text-left text-xs font-bold text-green-900 uppercase tracking-wider whitespace-nowrap">MC Seq#</th>
                                        <th class="px-3 py-2 text-left text-xs font-bold text-green-900 uppercase tracking-wider whitespace-nowrap">Comp#</th>
                                        <th class="px-3 py-2 text-left text-xs font-bold text-green-900 uppercase tracking-wider whitespace-nowrap">P/Design</th>
                                        <th class="px-3 py-2 text-left text-xs font-bold text-green-900 uppercase tracking-wider whitespace-nowrap">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    <tr v-for="mc in filteredMasterCardsPart" :key="mc.seq" @click="selectMasterCardPart(mc)" class="hover:bg-blue-50 cursor-pointer transition-colors duration-150" :class="{'bg-blue-100 border-l-4 border-blue-500': selectedMasterCardPart?.seq === mc.seq}">
                                        <td class="px-3 py-1">{{ mc.part }}</td>
                                        <td class="px-3 py-1">{{ mc.seq }}</td>
                                        <td class="px-3 py-1">{{ mc.comp }}</td>
                                        <td class="px-3 py-1">{{ mc.pdesign }}</td>
                                        <td class="px-3 py-1">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">{{ mc.status }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Model & Dimensi -->
                        <div class="flex flex-col md:flex-row gap-3 items-center mb-3">
                            <div class="flex-1 flex items-center">
                                <span class="font-semibold mr-2">Model:</span>
                                <input type="text" :value="selectedMasterCardPart?.model || ''" readonly class="border border-gray-300 rounded px-2 py-1 w-full bg-gray-50" />
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row gap-3 items-center mb-3">
                            <div class="flex-1 flex items-center">
                                <span class="font-semibold mr-2">Ext. Dim:</span>
                                <input type="text" :value="selectedMasterCardPart ? selectedMasterCardPart.ext[0] : ''" readonly class="border border-gray-300 rounded px-1 py-1 w-16 text-center bg-gray-50" />
                                <span class="mx-1">x</span>
                                <input type="text" :value="selectedMasterCardPart ? selectedMasterCardPart.ext[1] : ''" readonly class="border border-gray-300 rounded px-1 py-1 w-16 text-center bg-gray-50" />
                                <span class="mx-1">x</span>
                                <input type="text" :value="selectedMasterCardPart ? selectedMasterCardPart.ext[2] : ''" readonly class="border border-gray-300 rounded px-1 py-1 w-16 text-center bg-gray-50" />
                            </div>
                            <div class="flex-1 flex items-center">
                                <span class="font-semibold mr-2">Int. Dim:</span>
                                <input type="text" :value="selectedMasterCardPart ? selectedMasterCardPart.int[0] : ''" readonly class="border border-gray-300 rounded px-1 py-1 w-16 text-center bg-gray-50" />
                                <span class="mx-1">x</span>
                                <input type="text" :value="selectedMasterCardPart ? selectedMasterCardPart.int[1] : ''" readonly class="border border-gray-300 rounded px-1 py-1 w-16 text-center bg-gray-50" />
                                <span class="mx-1">x</span>
                                <input type="text" :value="selectedMasterCardPart ? selectedMasterCardPart.int[2] : ''" readonly class="border border-gray-300 rounded px-1 py-1 w-16 text-center bg-gray-50" />
                            </div>
                        </div>
                    </div>
                    <!-- Modal Footer Buttons -->
                    <div class="flex justify-center gap-4 px-6 py-4 bg-gradient-to-r from-gray-100 to-gray-200 rounded-b-xl border-t border-gray-300 shadow-inner">
                        <button @click="showMoreOptionsFromMcsTable('part')" class="modern-button bg-gradient-to-r from-green-500 to-teal-500 hover:from-green-600 hover:to-teal-600">
                            <i class="fas fa-cogs mr-2"></i> More Options
                        </button>
                        <button class="modern-button bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600">
                            <i class="fas fa-search-plus mr-2"></i> Zoom
                        </button>
                        <button @click="selectMasterCardPartAndClose" class="modern-button bg-gradient-to-r from-purple-500 to-indigo-500 hover:from-purple-600 hover:to-indigo-600">
                            <i class="fas fa-check-circle mr-2"></i> Select
                        </button>
                        <button @click="showMasterCardPartTableModal = false" class="modern-button bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600">
                            <i class="fas fa-times mr-2"></i> Exit
                        </button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Master Card Table Modal (Modern Colorful Style for MC PD ED) -->
        <transition name="fade">
            <div v-if="showMasterCardEdTableModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30">
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl mx-auto relative animate-fade-in-up border border-gray-200 transform transition-all duration-300 scale-95 opacity-0" :class="{'scale-100 opacity-100': showMasterCardEdTableModal}">
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-green-500 via-teal-400 to-green-700 p-6 rounded-t-xl shadow-lg flex items-center justify-between relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-white opacity-10 rounded-full -translate-y-10 translate-x-10"></div>
                        <div class="flex items-center gap-4">
                            <span class="inline-flex items-center justify-center w-10 h-10 bg-white/20 rounded-full flex-shrink-0 backdrop-blur-sm">
                                <i class="fas fa-th-list text-white text-xl"></i>
                            </span>
                            <span class="text-white text-xl font-bold z-10">Master Card Table</span>
                        </div>
                        <button @click="showMasterCardEdTableModal = false" class="text-white text-2xl hover:bg-white/20 rounded-full w-10 h-10 flex items-center justify-center transition-all duration-300 transform hover:rotate-90 z-10">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <!-- Modal Body -->
                    <div class="p-6 bg-white overflow-hidden">
                        <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-lg mb-6 max-h-56 custom-scrollbar" style="max-height: 220px;">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-blue-200 to-indigo-200 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-bold text-blue-900 uppercase tracking-wider whitespace-nowrap">Ext. Dimension</th>
                                        <th class="px-3 py-2 text-left text-xs font-bold text-blue-900 uppercase tracking-wider whitespace-nowrap">MC Seq#</th>
                                        <th class="px-3 py-2 text-left text-xs font-bold text-blue-900 uppercase tracking-wider whitespace-nowrap">Comp#</th>
                                        <th class="px-3 py-2 text-left text-xs font-bold text-blue-900 uppercase tracking-wider whitespace-nowrap">P/Design</th>
                                        <th class="px-3 py-2 text-left text-xs font-bold text-blue-900 uppercase tracking-wider whitespace-nowrap">Model</th>
                                        <th class="px-3 py-2 text-left text-xs font-bold text-blue-900 uppercase tracking-wider whitespace-nowrap">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    <tr v-for="mc in filteredMasterCardsEd" :key="mc.seq" @click="selectMasterCardEd(mc)" class="hover:bg-blue-50 cursor-pointer transition-colors duration-150" :class="{'bg-blue-100 border-l-4 border-blue-500': selectedMasterCardEd?.seq === mc.seq}">
                                        <td class="px-3 py-1">{{ mc.ext }}</td>
                                        <td class="px-3 py-1">{{ mc.seq }}</td>
                                        <td class="px-3 py-1">{{ mc.comp }}</td>
                                        <td class="px-3 py-1">{{ mc.pdesign }}</td>
                                        <td class="px-3 py-1">{{ mc.model }}</td>
                                        <td class="px-3 py-1">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">{{ mc.status }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Model -->
                        <div class="flex flex-col md:flex-row gap-3 items-center mb-3">
                            <div class="flex-1 flex items-center">
                                <span class="font-semibold mr-2">Model:</span>
                                <input type="text" :value="selectedMasterCardEd?.model || ''" readonly class="border border-gray-300 rounded px-2 py-1 w-full bg-gray-50" />
                            </div>
                        </div>
                    </div>
                    <!-- Modal Footer Buttons -->
                    <div class="flex justify-center gap-4 px-6 py-4 bg-gradient-to-r from-gray-100 to-gray-200 rounded-b-xl border-t border-gray-300 shadow-inner">
                        <button @click="showMoreOptionsFromMcsTable('ed')" class="modern-button bg-gradient-to-r from-green-500 to-teal-500 hover:from-green-600 hover:to-teal-600">
                            <i class="fas fa-cogs mr-2"></i> More Options
                        </button>
                        <button class="modern-button bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600">
                            <i class="fas fa-search-plus mr-2"></i> Zoom
                        </button>
                        <button @click="selectMasterCardEdAndClose" class="modern-button bg-gradient-to-r from-purple-500 to-indigo-500 hover:from-purple-600 hover:to-indigo-600">
                            <i class="fas fa-check-circle mr-2"></i> Select
                        </button>
                        <button @click="showMasterCardEdTableModal = false" class="modern-button bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600">
                            <i class="fas fa-times mr-2"></i> Exit
                        </button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Master Card Table Modal (Modern Colorful Style for MC PD ID) -->
        <transition name="fade">
            <div v-if="showMasterCardIdTableModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30">
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl mx-auto relative animate-fade-in-up border border-gray-200 transform transition-all duration-300 scale-95 opacity-0" :class="{'scale-100 opacity-100': showMasterCardIdTableModal}">
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-green-500 via-teal-400 to-green-700 p-6 rounded-t-xl shadow-lg flex items-center justify-between relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-white opacity-10 rounded-full -translate-y-10 translate-x-10"></div>
                        <div class="flex items-center gap-4">
                            <span class="inline-flex items-center justify-center w-10 h-10 bg-white/20 rounded-full flex-shrink-0 backdrop-blur-sm">
                                <i class="fas fa-th-list text-white text-xl"></i>
                            </span>
                            <span class="text-white text-xl font-bold z-10">Master Card Table</span>
                        </div>
                        <button @click="showMasterCardIdTableModal = false" class="text-white text-2xl hover:bg-white/20 rounded-full w-10 h-10 flex items-center justify-center transition-all duration-300 transform hover:rotate-90 z-10">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <!-- Modal Body -->
                    <div class="p-6 bg-white overflow-hidden">
                        <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-lg mb-6 max-h-56 custom-scrollbar" style="max-height: 220px;">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-blue-200 to-indigo-200 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-bold text-blue-900 uppercase tracking-wider whitespace-nowrap">Int. Dimension</th>
                                        <th class="px-3 py-2 text-left text-xs font-bold text-blue-900 uppercase tracking-wider whitespace-nowrap">MC Seq#</th>
                                        <th class="px-3 py-2 text-left text-xs font-bold text-blue-900 uppercase tracking-wider whitespace-nowrap">Comp#</th>
                                        <th class="px-3 py-2 text-left text-xs font-bold text-blue-900 uppercase tracking-wider whitespace-nowrap">P/Design</th>
                                        <th class="px-3 py-2 text-left text-xs font-bold text-blue-900 uppercase tracking-wider whitespace-nowrap">Model</th>
                                        <th class="px-3 py-2 text-left text-xs font-bold text-blue-900 uppercase tracking-wider whitespace-nowrap">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    <tr v-for="mc in filteredMasterCardsId" :key="mc.seq" @click="selectMasterCardId(mc)" class="hover:bg-blue-50 cursor-pointer transition-colors duration-150" :class="{'bg-blue-100 border-l-4 border-blue-500': selectedMasterCardId?.seq === mc.seq}">
                                        <td class="px-3 py-1">{{ mc.int }}</td>
                                        <td class="px-3 py-1">{{ mc.seq }}</td>
                                        <td class="px-3 py-1">{{ mc.comp }}</td>
                                        <td class="px-3 py-1">{{ mc.pdesign }}</td>
                                        <td class="px-3 py-1">{{ mc.model }}</td>
                                        <td class="px-3 py-1">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">{{ mc.status }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Model -->
                        <div class="flex flex-col md:flex-row gap-3 items-center mb-3">
                            <div class="flex-1 flex items-center">
                                <span class="font-semibold mr-2">Model:</span>
                                <input type="text" :value="selectedMasterCardId?.model || ''" readonly class="border border-gray-300 rounded px-2 py-1 w-full bg-gray-50" />
                            </div>
                        </div>
                    </div>
                    <!-- Modal Footer Buttons -->
                    <div class="flex justify-center gap-4 px-6 py-4 bg-gradient-to-r from-gray-100 to-gray-200 rounded-b-xl border-t border-gray-300 shadow-inner">
                        <button @click="showMoreOptionsFromMcsTable('id')" class="modern-button bg-gradient-to-r from-green-500 to-teal-500 hover:from-green-600 hover:to-teal-600">
                            <i class="fas fa-cogs mr-2"></i> More Options
                        </button>
                        <button class="modern-button bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600">
                            <i class="fas fa-search-plus mr-2"></i> Zoom
                        </button>
                        <button @click="selectMasterCardIdAndClose" class="modern-button bg-gradient-to-r from-purple-500 to-indigo-500 hover:from-purple-600 hover:to-indigo-600">
                            <i class="fas fa-check-circle mr-2"></i> Select
                        </button>
                        <button @click="showMasterCardIdTableModal = false" class="modern-button bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600">
                            <i class="fas fa-times mr-2"></i> Exit
                        </button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Master Card Options Modal -->
        <transition name="fade">
            <div v-if="showMasterCardOptionsModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30">
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-auto relative animate-fade-in-up">
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-green-500 to-teal-500 rounded-t-xl px-6 py-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center justify-center w-10 h-10 bg-white/20 rounded-full"><i class="fas fa-filter text-white text-2xl"></i></span>
                            <span class="text-white text-xl font-bold">Options</span>
                        </div>
                        <button @click="showMasterCardOptionsModal = false" class="text-white text-2xl hover:bg-white/20 rounded-full w-10 h-10 flex items-center justify-center"><i class="fas fa-times"></i></button>
                    </div>
                    <!-- Modal Body -->
                    <div class="p-6 space-y-6">
                        <div class="border rounded-xl p-4 bg-gray-50">
                            <div class="flex items-center gap-2 mb-2"><i class="fas fa-sort text-blue-500"></i><span class="font-semibold text-lg">Sort by:</span></div>
                            <div class="flex flex-col gap-2 mt-2">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="radio" v-model="mcsOptionSortBy" value="seq" class="accent-blue-600 h-5 w-5" />
                                    <span class="text-gray-700 text-base">MC Seq#</span>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="radio" v-model="mcsOptionSortBy" value="model" class="accent-blue-600 h-5 w-5" />
                                    <span class="text-gray-700 text-base">MC Model</span>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="radio" v-model="mcsOptionSortBy" value="part" class="accent-blue-600 h-5 w-5" />
                                    <span class="text-gray-700 text-base">MC PD Part#</span>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="radio" v-model="mcsOptionSortBy" value="ed" class="accent-blue-600 h-5 w-5" />
                                    <span class="text-gray-700 text-base">MC PD ED</span>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="radio" v-model="mcsOptionSortBy" value="id" class="accent-blue-600 h-5 w-5" />
                                    <span class="text-gray-700 text-base">MC PD ID</span>
                                </label>
                            </div>
                        </div>
                        <div class="border rounded-xl p-4 bg-gray-50">
                            <div class="flex items-center gap-2 mb-2"><i class="fas fa-arrow-up text-blue-500"></i><span class="font-semibold text-lg">Sort Order:</span></div>
                            <div class="flex items-center gap-6 mt-2">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" v-model="mcsOptionSortOrder" value="asc" class="accent-blue-600 h-5 w-5" />
                                    <span class="text-gray-700 font-medium">Ascending</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" v-model="mcsOptionSortOrder" value="desc" class="accent-blue-600 h-5 w-5" />
                                    <span class="text-gray-700 font-medium">Descending</span>
                                </label>
                            </div>
                        </div>
                        <div class="border rounded-xl p-4 bg-gray-50">
                            <div class="flex items-center gap-2 mb-2"><i class="fas fa-tag text-blue-500"></i><span class="font-semibold text-lg">MC Status:</span></div>
                            <div class="flex items-center gap-6 mt-2">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" v-model="mcsOptionStatus" value="active" class="accent-blue-600 h-5 w-5" />
                                    <span class="text-blue-800 font-medium">Active</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" v-model="mcsOptionStatus" value="obsolete" class="accent-blue-600 h-5 w-5" />
                                    <span class="text-blue-800 font-medium">Obsolete</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- Modal Footer -->
                    <div class="flex justify-center gap-4 px-6 pb-6">
                        <button @click="applyOptionsFromMcsTable" class="bg-blue-600 text-white font-bold px-8 py-2 rounded-lg shadow hover:bg-blue-700 flex items-center gap-2"><i class="fas fa-check"></i> OK</button>
                        <button @click="showMasterCardOptionsModal = false" class="bg-gray-200 text-gray-700 font-bold px-8 py-2 rounded-lg shadow flex items-center gap-2"><i class="fas fa-times"></i> Exit</button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- ReleaseApprovedMCModal -->
        <ReleaseApprovedMCModal
          :show="showReleaseApprovedMCModal"
          :masterCards="masterCards"
          :customerCode="searchTerm"
          :customerName="selectedCustomer ? selectedCustomer.name : ''"
          :mcsFrom="form.mcsFrom"
          :mcsTo="form.mcsTo"
          @close="showReleaseApprovedMCModal = false"
        />

    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';
import ReleaseApprovedMCModal from '@/Components/ReleaseApprovedMCModal.vue';

// Debug information
console.log("Component loaded");

const props = defineProps({
    masterCards: Array,
    customers: Array,
});

// Debug props received
console.log("Props received:", props);

// Initialize with API call if props are empty
onMounted(async () => {
    console.log("Component mounted");
    
    if (!props.masterCards || props.masterCards.length === 0) {
        fetchData();
    }
});

// Define reactive state variables
const sortBy = ref('seq');
const releaseStatus = ref(['active']);
const showOptions = ref(false);
const showOptionsFromCustomer = ref(false); // New state variable for options from customer modal
const showReleaseModal = ref(false);
const showUnreleaseModal = ref(false);
const releaseNotes = ref('');
const masterCardToAction = ref(null);
const form = ref({
    mcsFrom: '',
    mcsTo: ''
});
const loading = ref(false);
const error = ref(null);
const showCustomerAccountModal = ref(false); // New state for customer account modal
const showMasterCardOptionsModal = ref(false); // New state for Master Card Options modal
const showReleaseApprovedMCModal = ref(false); // New state for Release Approved MC Modal
const mcsOptionSortBy = ref('seq');
const mcsOptionSortOrder = ref('asc');
const mcsOptionStatus = ref(['active', 'obsolete']);
const currentMcsModalType = ref(''); // To store which MC modal called the options

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
const masterCards = ref(props.masterCards && props.masterCards.length ? props.masterCards : [
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
    if (searchTerm.value && form.value.mcsFrom && form.value.mcsTo) {
      showReleaseApprovedMCModal.value = true;
    } else {
      showNotification('Please fill in Customer AC#, MCS# From, and MCS# To.', 'error');
    }
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

// Confirm release action with API call
const confirmRelease = async () => {
    try {
        // Make API call to release the master card
        const response = await axios.post(`/api/realese-approve-mc/release/${masterCardToAction.value.id}`, {
            release_notes: releaseNotes.value
        });
        
        if (response.data.success) {
            // Update the card in local state
            const index = masterCards.value.findIndex(mc => mc.id === masterCardToAction.value.id);
            if (index !== -1) {
                masterCards.value[index].released_by = response.data.released_by || 'Current User';
                masterCards.value[index].released_date = response.data.released_date || new Date().toISOString();
                masterCards.value[index].release_notes = releaseNotes.value;
            }
            
            // Show success message
            showNotification('Master Card released successfully!');
        } else {
            throw new Error(response.data.message || 'Failed to release master card');
        }
    } catch (error) {
        console.error('Error releasing master card:', error);
        showNotification(error.message || 'An error occurred during release', 'error');
    }
    
    showReleaseModal.value = false;
};

// Confirm unreleased action with API call
const confirmUnrelease = async () => {
    try {
        // Make API call to unreleased the master card
        const response = await axios.post(`/api/realese-approve-mc/unreleased/${masterCardToAction.value.id}`);
        
        if (response.data.success) {
            // Update the card in local state
            const index = masterCards.value.findIndex(mc => mc.id === masterCardToAction.value.id);
            if (index !== -1) {
                masterCards.value[index].released_by = null;
                masterCards.value[index].released_date = null;
                masterCards.value[index].release_notes = null;
            }
            
            // Show success message
            showNotification('Master Card un-released successfully!');
        } else {
            throw new Error(response.data.message || 'Failed to unreleased master card');
        }
    } catch (error) {
        console.error('Error un-releasing master card:', error);
        showNotification(error.message || 'An error occurred during un-releasing', 'error');
    }
    
    showUnreleaseModal.value = false;
};

// Added functions for MCS handling
const showMcsModal = () => {
    console.log("Show MCS modal");
    // This would typically open a modal dialog for selecting MCS numbers
    // For now just log the action
};

const searchMcs = () => {
    if (!form.value.mcsFrom || !form.value.mcsTo) {
        showNotification('Please provide both Start and End MCS#', 'error');
        return;
    }
    
    console.log(`Searching for MCS from ${form.value.mcsFrom} to ${form.value.mcsTo}`);
    // This would typically filter the masterCards by MCS range
    // For now just log the search action
};

// Function to fetch data from API
const fetchData = async () => {
    loading.value = true;
    error.value = null;
    
    try {
        console.log("Fetching data from API");
        const response = await axios.get('/api/realese-approve-mc');
        if (response.data.success) {
            masterCards.value = response.data.data;
            console.log("Data fetched successfully:", masterCards.value);
        } else {
            console.error("API returned error:", response.data);
            error.value = response.data.message || "Failed to load master cards";
            showNotification(error.value, "error");
        }
    } catch (error) {
        console.error("Error fetching data:", error);
        error.value = "Failed to connect to server";
        showNotification(error.value, "error");
    } finally {
        loading.value = false;
    }
};

const optionSortBy = ref('code');
const optionStatus = ref(['active']);

function applyOptions() {
    console.log('applyOptions triggered');
    console.log('optionSortBy before setting showOptions:', optionSortBy.value);
    showOptions.value = false;
    if (optionSortBy.value === 'code' || optionSortBy.value === 'name') {
        showCustomerAccountModal.value = true;
        console.log('showCustomerAccountModal set to true for:', optionSortBy.value);
    }
    console.log('showCustomerAccountModal after applyOptions:', showCustomerAccountModal.value);
}

const showMcsOptions = ref(false);
const mcsSortBy = ref('seq');
const mcsSortOrder = ref('asc');
const mcsStatus = ref(['active']);
function applyMcsOptions(fromOK = false) {
    showMcsOptions.value = false;
    if (fromOK) {
        // Jika dari tombol OK, baru buka modal data sesuai sort by
        if (mcsSortBy.value === 'seq') {
            showMasterCardTableModal.value = true;
        } else if (mcsSortBy.value === 'model') {
            showMasterCardModelTableModal.value = true;
        } else if (mcsSortBy.value === 'part') {
            showMasterCardPartTableModal.value = true;
        } else if (mcsSortBy.value === 'ed') {
            showMasterCardEdTableModal.value = true;
        } else if (mcsSortBy.value === 'id') {
            showMasterCardIdTableModal.value = true;
        }
    }
}

const customerSearchTerm = ref(''); // New ref for customer search
const selectedCustomer = ref(null); // New ref for selected customer

// Dummy customer data (replace with actual API fetch later)
const customerAccounts = ref([
    { name: 'ABDULLAH, BPK', code: '000211-08', salesperson: 'S111', ac_type: 'Local', currency: 'IDR', status: 'Active' },
    { name: 'ACEP SUNANDAR, BPK', code: '000680-06', salesperson: 'S140', ac_type: 'Local', currency: 'IDR', status: 'Active' },
    { name: 'ACHMAD JAMAL', code: '000585-01', salesperson: 'S102', ac_type: 'Local', currency: 'IDR', status: 'Active' },
    { name: 'ACOSTA SUPER FOOD, PT', code: '000283', salesperson: 'S143', ac_type: 'Local', currency: 'IDR', status: 'Active' },
    { name: 'ADHITYA SERAYAKORITA, PT', code: '000903', salesperson: 'S103', ac_type: 'Local', currency: 'IDR', status: 'Active' },
    { name: 'ADIKARYA GEMILANG', code: '000507', salesperson: 'S140', ac_type: 'Local', currency: 'IDR', status: 'Active' },
    { name: 'AGEL LANGGENG, PT', code: '000581', salesperson: 'S143', ac_type: 'Local', currency: 'IDR', status: 'Active' },
    { name: 'AGILITY INTERNATIONAL, PT', code: '000004', salesperson: 'S118', ac_type: 'Local', currency: 'IDR', status: 'Active' },
    { name: 'AGRINDO MAJU LESTARI, PT', code: '000676', salesperson: 'S142', ac_type: 'Local', currency: 'IDR', status: 'Active' },
    { name: 'AGRO MEGA PERKASA, PT', code: '000839', salesperson: 'S123', ac_type: 'Local', currency: 'IDR', status: 'Active' },
    { name: 'AGUNG KEMUNING WIJAYA, PT', code: '000767', salesperson: 'S123', ac_type: 'Local', currency: 'IDR', status: 'Active' },
    { name: 'AGUS', code: '000212-24', salesperson: 'S111', ac_type: 'Local', currency: 'IDR', status: 'Active' },
    { name: 'AGUS IMAM MAKRUF', code: '000138-01', salesperson: 'S123', ac_type: 'Local', currency: 'IDR', status: 'Active' },
    { name: 'AGUS, BPK', code: '000138-01', salesperson: 'S123', ac_type: 'Local', currency: 'IDR', status: 'Active' },
    { name: 'AGUSTIN WULANDARI', code: '000930-05', salesperson: 'S143', ac_type: 'Local', currency: 'IDR', status: 'Active' },
    { name: 'AGUSTINA INDRAWATI', code: '000701', salesperson: 'S108', ac_type: 'Local', currency: 'IDR', status: 'Active' },
    { name: 'AHMAD SURYADI, BPK', code: '000211-07', salesperson: 'S140', ac_type: 'Local', currency: 'IDR', status: 'Active' },
    { name: 'AKITA RAYA INDONESIA, PT', code: '000701', salesperson: 'S108', ac_type: 'Local', currency: 'IDR', status: 'Active' },
    { name: 'AKROM KHASANI', code: '000729', salesperson: 'S143', ac_type: 'Local', currency: 'IDR', status: 'Active' },
    { name: 'ALAM PANGAN SENTOSA, PT', code: '000648', salesperson: 'S143', ac_type: 'Local', currency: 'IDR', status: 'Active' },
]);

const filteredCustomers = computed(() => {
    if (!customerSearchTerm.value) {
        return customerAccounts.value.filter(c => c.status === 'Active');
    }
    const search = customerSearchTerm.value.toLowerCase();
    return customerAccounts.value.filter(customer => 
        (customer.name.toLowerCase().includes(search) ||
        customer.code.toLowerCase().includes(search)) &&
        customer.status === 'Active'
    );
});

// Function to handle row click and select customer
const selectCustomer = (customer) => {
    selectedCustomer.value = customer;
};

// Function to handle "Select" button click
const selectCustomerAndClose = () => {
    if (selectedCustomer.value) {
        searchTerm.value = selectedCustomer.value.code; // Populate AC# field
    }
    showCustomerAccountModal.value = false;
};

// Function to handle "More Options" button click in customer account modal
const showMoreOptionsFromCustomer = () => {
    showCustomerAccountModal.value = false; // Close customer account modal
    showOptionsFromCustomer.value = true; // Open options modal
};

// Function to apply options from customer modal
const applyOptionsFromCustomer = () => {
    showOptionsFromCustomer.value = false;
    showCustomerAccountModal.value = true; // Reopen customer account modal with new options applied
};

// Data MC Seq# (dummy) diisi sesuai gambar
const masterCardSeqList = ref([
    { seq: '1609138', model: 'BOX BASO 4.5 KG', status: 'Act' },
    { seq: '1609144', model: 'BOX IKAN HARIMAU 4.5 KG', status: 'Act' },
    { seq: '1609145', model: 'BOX SRIKAYA 4.5 KG', status: 'Act' },
    { seq: '1609162', model: 'BIHUN FANIA 5 KG', status: 'Act' },
    { seq: '1609163', model: 'BIHUN IKAN TUNA 4.5 KG BARU', status: 'Act' },
    { seq: '1609166', model: 'BIHUN PIRING MAS 5 KG', status: 'Act' },
    { seq: '1609173', model: 'BOX JAGUNG SRIKAYA 5 KG', status: 'Act' },
    { seq: '1609181', model: 'BIHUN POHON KOPI 5 KG', status: 'Act' },
    { seq: '1609185', model: 'POLOS UK 506 X 356 X 407', status: 'Act' },
    { seq: '1609186', model: 'POLOS 480 X 410 X 401', status: 'Act' },
]);
const showMasterCardTableModal = ref(false);
const selectedMasterCardSeq = ref(null);
const filteredMasterCardsSeq = computed(() => masterCardSeqList.value); // Bisa ditambah filter jika perlu
function selectMasterCard(mc) { selectedMasterCardSeq.value = mc; }
function selectMasterCardAndClose() {
    if (selectedMasterCardSeq.value) {
        if (mcsInputActive.value === 'from') {
            form.value.mcsFrom = selectedMasterCardSeq.value.seq;
        } else {
            form.value.mcsTo = selectedMasterCardSeq.value.seq;
        }
    }
    showMasterCardTableModal.value = false;
}
// Removed watcher that automatically opens MC Seq# modal

// Data MC Model (dummy) diisi sesuai gambar
const masterCardModelList = ref([
    { model: 'BIHUN DELLIS 5 KG', seq: '1609182', status: 'Act' },
    { model: 'BIHUN FANIA 5 KG', seq: '1609162', status: 'Act' },
    { model: 'BIHUN IKAN TUNA 4.5 KG BARU', seq: '1609163', status: 'Act' },
    { model: 'BIHUN IKAN TUNA 5 KG BARU', seq: '1609164', status: 'Act' },
    { model: 'BIHUN PIRING MAS 5 KG', seq: '1609166', status: 'Act' },
    { model: 'BIHUN POHON KOPI 5 KG', seq: '1609181', status: 'Act' },
    { model: 'BOX BASO 4.5 KG', seq: '1609138', status: 'Act' },
    { model: 'BOX IKAN HARIMAU 4.5 KG', seq: '1609144', status: 'Act' },
    { model: 'BOX JAGUNG SRIKAYA 5 KG', seq: '1609173', status: 'Act' },
    { model: 'BOX SRIKAYA 4.5 KG', seq: '1609145', status: 'Act' },
    { model: 'POLOS 480 X 410 X 401', seq: '1609186', status: 'Act' },
    { model: 'POLOS UK 506 X 356 X 407', seq: '1609185', status: 'Act' },
]);
const showMasterCardModelTableModal = ref(false);
const selectedMasterCardModel = ref(null);
const filteredMasterCardsModel = computed(() => masterCardModelList.value); // Bisa ditambah filter jika perlu
function selectMasterCardModel(mc) { selectedMasterCardModel.value = mc; }
function selectMasterCardModelAndClose() {
    if (selectedMasterCardModel.value) {
        if (mcsInputActive.value === 'from') {
            form.value.mcsFrom = selectedMasterCardModel.value.seq;
        } else {
            form.value.mcsTo = selectedMasterCardModel.value.seq;
        }
    }
    showMasterCardModelTableModal.value = false;
}
// Removed watcher that automatically opens MC Model modal

// Data MC PD Part# (dummy, sesuai screenshot)
const masterCardPartList = ref([
    { part: 'BOX', seq: '1609138', comp: 'Main', pdesign: 'B1', status: 'Active', model: 'BOX BASO 4,5 KG', ext: [396,243,297], int: [393,240,292] },
    { part: 'BOX', seq: '1609144', comp: 'Main', pdesign: 'B1', status: 'Active', model: 'BOX IKAN HARIMAU 4,5 KG', ext: [396,243,297], int: [393,240,292] },
    { part: 'BOX', seq: '1609145', comp: 'Main', pdesign: 'B1', status: 'Active', model: 'BOX SRIKAYA 4,5 KG', ext: [396,243,297], int: [393,240,292] },
    { part: 'BOX', seq: '1609162', comp: 'Main', pdesign: 'B1', status: 'Active', model: 'BIHUN FANIA 5 KG', ext: [396,243,297], int: [393,240,292] },
    { part: 'BOX', seq: '1609163', comp: 'Main', pdesign: 'B1', status: 'Active', model: 'BIHUN IKAN TUNA 4,5 KG BARU', ext: [396,243,297], int: [393,240,292] },
    { part: 'BOX', seq: '1609166', comp: 'Main', pdesign: 'B1', status: 'Active', model: 'BIHUN PIRING MAS 5 KG', ext: [396,243,297], int: [393,240,292] },
    { part: 'BOX', seq: '1609173', comp: 'Main', pdesign: 'B1', status: 'Active', model: 'BOX JAGUNG SRIKAYA 5 KG', ext: [396,243,297], int: [393,240,292] },
    { part: 'BOX', seq: '1609181', comp: 'Main', pdesign: 'B1', status: 'Active', model: 'BIHUN POHON KOPI 5 KG', ext: [396,243,297], int: [393,240,292] },
    { part: 'BOX', seq: '1609182', comp: 'Main', pdesign: 'B1', status: 'Active', model: 'BIHUN DELLIS 5 KG', ext: [396,243,297], int: [393,240,292] },
    { part: 'BOX', seq: '1609185', comp: 'Main', pdesign: 'B1', status: 'Active', model: 'POLOS UK 506 X 356 X 407', ext: [396,243,297], int: [393,240,292] },
    { part: 'BOX', seq: '1609186', comp: 'Main', pdesign: 'B1', status: 'Active', model: 'POLOS 480 X 410 X 401', ext: [396,243,297], int: [393,240,292] },
]);
const showMasterCardPartTableModal = ref(false);
const selectedMasterCardPart = ref(null);
const filteredMasterCardsPart = computed(() => masterCardPartList.value); // Bisa difilter jika perlu
function selectMasterCardPart(mc) { selectedMasterCardPart.value = mc; }
function selectMasterCardPartAndClose() {
    if (selectedMasterCardPart.value) {
        if (mcsInputActive.value === 'from') {
            form.value.mcsFrom = selectedMasterCardPart.value.seq;
        } else {
            form.value.mcsTo = selectedMasterCardPart.value.seq;
        }
    }
    showMasterCardPartTableModal.value = false;
}
// Removed watcher that automatically opens MC PD Part# modal

// Data MC PD ED (dummy, sesuai screenshot)
const masterCardEdList = ref([
    { ext: '396x243x297', seq: '1609138', comp: 'Main', pdesign: 'B1', model: 'BOX BASO 4,5 KG', status: 'Act' },
    { ext: '396x243x297', seq: '1609144', comp: 'Main', pdesign: 'B1', model: 'BOX IKAN HARIMAU 4,5 KG', status: 'Act' },
    { ext: '396x243x297', seq: '1609145', comp: 'Main', pdesign: 'B1', model: 'BOX SRIKAYA 4,5 KG', status: 'Act' },
    { ext: '396x243x297', seq: '1609163', comp: 'Main', pdesign: 'B1', model: 'BIHUN IKAN TUNA 4,5 KG BARU', status: 'Act' },
    { ext: '421x243x307', seq: '1609162', comp: 'Main', pdesign: 'B1', model: 'BIHUN FANIA 5 KG', status: 'Act' },
    { ext: '421x243x307', seq: '1609164', comp: 'Main', pdesign: 'B1', model: 'BIHUN IKAN TUNA 5 KG BARU', status: 'Act' },
    { ext: '421x243x307', seq: '1609166', comp: 'Main', pdesign: 'B1', model: 'BIHUN PIRING MAS 5 KG', status: 'Act' },
    { ext: '421x243x307', seq: '1609173', comp: 'Main', pdesign: 'B1', model: 'BOX JAGUNG SRIKAYA 5 KG', status: 'Act' },
    { ext: '421x243x307', seq: '1609181', comp: 'Main', pdesign: 'B1', model: 'BIHUN POHON KOPI 5 KG', status: 'Act' },
    { ext: '421x243x307', seq: '1609182', comp: 'Main', pdesign: 'B1', model: 'BIHUN DELLIS 5 KG', status: 'Act' },
    { ext: '483x413x406', seq: '1609186', comp: 'Main', pdesign: 'B1', model: 'POLOS 480 X 410 X 401', status: 'Act' },
    { ext: '510x360x414', seq: '1609185', comp: 'Main', pdesign: 'B1', model: 'POLOS UK 506 X 356 X 407', status: 'Act' },
]);
const showMasterCardEdTableModal = ref(false);
const selectedMasterCardEd = ref(null);
const filteredMasterCardsEd = computed(() => masterCardEdList.value);
function selectMasterCardEd(mc) { selectedMasterCardEd.value = mc; }
function selectMasterCardEdAndClose() {
    if (selectedMasterCardEd.value) {
        if (mcsInputActive.value === 'from') {
            form.value.mcsFrom = selectedMasterCardEd.value.seq;
        } else {
            form.value.mcsTo = selectedMasterCardEd.value.seq;
        }
    }
    showMasterCardEdTableModal.value = false;
}
// Removed watcher that automatically opens MC PD ED modal

// Data MC PD ID (dummy, sesuai screenshot)
const masterCardIdList = ref([
    { int: '393x240x292', seq: '1609138', comp: 'Main', pdesign: 'B1', model: 'BOX BASO 4,5 KG', status: 'Act' },
    { int: '393x240x292', seq: '1609144', comp: 'Main', pdesign: 'B1', model: 'BOX IKAN HARIMAU 4,5 KG', status: 'Act' },
    { int: '393x240x292', seq: '1609145', comp: 'Main', pdesign: 'B1', model: 'BOX SRIKAYA 4,5 KG', status: 'Act' },
    { int: '393x240x292', seq: '1609163', comp: 'Main', pdesign: 'B1', model: 'BIHUN IKAN TUNA 4,5 KG BARU', status: 'Act' },
    { int: '418x240x302', seq: '1609162', comp: 'Main', pdesign: 'B1', model: 'BIHUN FANIA 5 KG', status: 'Act' },
    { int: '418x240x302', seq: '1609164', comp: 'Main', pdesign: 'B1', model: 'BIHUN IKAN TUNA 5 KG BARU', status: 'Act' },
    { int: '418x240x302', seq: '1609166', comp: 'Main', pdesign: 'B1', model: 'BIHUN PIRING MAS 5 KG', status: 'Act' },
    { int: '418x240x302', seq: '1609173', comp: 'Main', pdesign: 'B1', model: 'BOX JAGUNG SRIKAYA 5 KG', status: 'Act' },
    { int: '418x240x302', seq: '1609181', comp: 'Main', pdesign: 'B1', model: 'BIHUN POHON KOPI 5 KG', status: 'Act' },
    { int: '418x240x302', seq: '1609182', comp: 'Main', pdesign: 'B1', model: 'BIHUN DELLIS 5 KG', status: 'Act' },
    { int: '480x410x401', seq: '1609186', comp: 'Main', pdesign: 'B1', model: 'POLOS 480 X 410 X 401', status: 'Act' },
    { int: '506x356x407', seq: '1609185', comp: 'Main', pdesign: 'B1', model: 'POLOS UK 506 X 356 X 407', status: 'Act' },
]);
const showMasterCardIdTableModal = ref(false);
const selectedMasterCardId = ref(null);
const filteredMasterCardsId = computed(() => masterCardIdList.value);
function selectMasterCardId(mc) { selectedMasterCardId.value = mc; }
function selectMasterCardIdAndClose() {
    if (selectedMasterCardId.value) {
        if (mcsInputActive.value === 'from') {
            form.value.mcsFrom = selectedMasterCardId.value.seq;
        } else {
            form.value.mcsTo = selectedMasterCardId.value.seq;
        }
    }
    showMasterCardIdTableModal.value = false;
}
// Removed watcher that automatically opens MC PD ID modal

// Tambahkan state untuk membedakan input MCS# mana yang sedang aktif
const mcsInputActive = ref('from'); // 'from' atau 'to'

// New function to open Master Card Options modal
const showMoreOptionsFromMcsTable = (modalType) => {
    currentMcsModalType.value = modalType;
    // Close all current MC table modals
    showMasterCardTableModal.value = false;
    showMasterCardModelTableModal.value = false;
    showMasterCardPartTableModal.value = false;
    showMasterCardEdTableModal.value = false;
    showMasterCardIdTableModal.value = false;
    // Open the new options modal
    showMasterCardOptionsModal.value = true;
};

// New function to apply options from Master Card Options modal and re-open previous modal
const applyOptionsFromMcsTable = () => {
    showMasterCardOptionsModal.value = false;
    // Here you would apply the sorting and status filters to your data
    // For now, let's just re-open the correct modal
    if (currentMcsModalType.value === 'seq') {
        showMasterCardTableModal.value = true;
    } else if (currentMcsModalType.value === 'model') {
        showMasterCardModelTableModal.value = true;
    } else if (currentMcsModalType.value === 'part') {
        showMasterCardPartTableModal.value = true;
    } else if (currentMcsModalType.value === 'ed') {
        showMasterCardEdTableModal.value = true;
    } else if (currentMcsModalType.value === 'id') {
        showMasterCardIdTableModal.value = true;
    }
    // You might also want to trigger a data fetch or re-filter here
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
.process-button {
    @apply w-full md:w-auto bg-gradient-to-r from-green-500 to-blue-500 text-white font-bold py-3 px-12 rounded-lg shadow-lg hover:shadow-2xl transform hover:scale-105 transition-all duration-300 flex items-center justify-center mx-auto relative overflow-hidden;
}
.shimmer-effect {
    @apply absolute top-0 -left-[150%] h-full w-[50%] skew-x-[-25deg] bg-white/20;
    animation: shimmer 2.5s infinite;
}
@keyframes shimmer {
    100% {
        left: 150%;
    }
}
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
</style>

<style scoped>
/* Add a custom scrollbar for better aesthetics */
.custom-scrollbar {
    scrollbar-width: thin; /* For Firefox */
    scrollbar-color: #a78bfa #e0e7ff; /* thumb and track color for Firefox */
}

/* For Chrome, Safari, and Opera */
.custom-scrollbar::-webkit-scrollbar {
    width: 8px; /* width of the scrollbar */
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #e0e7ff; /* color of the track */
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #a78bfa; /* color of the scroll thumb */
    border-radius: 10px; /* roundness of the scroll thumb */
    border: 2px solid #e0e7ff; /* creates padding around scroll thumb */
}

.modern-button {
    @apply text-white font-bold px-6 py-2 rounded-lg shadow-lg flex items-center gap-2 transition-all transform hover:scale-105;
}
</style>
