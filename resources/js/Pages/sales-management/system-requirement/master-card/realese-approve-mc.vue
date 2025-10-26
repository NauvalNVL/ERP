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
                                    <button type="button" @click="showCustomerAccountTable = true" class="inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 rounded-r-md transition-all duration-300 bg-gradient-to-r from-indigo-500 to-purple-500 text-white hover:from-indigo-600 hover:to-purple-600 shadow-sm hover:shadow-md transform hover:-translate-y-px">
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
                    MCS#:
                </label>
                <div class="flex items-center">
                    <div class="relative flex group">
                        <input type="text" id="mcsFrom" v-model="form.mcsFrom" placeholder="MCS#" class="input-field" style="min-width:220px;max-width:340px;width:100%;" />
                        <button type="button" @click="openMcsTableDirect()" class="inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 rounded-r-md transition-all duration-300 bg-gradient-to-r from-pink-500 to-orange-500 text-white hover:from-pink-600 hover:to-orange-600 shadow-sm hover:shadow-md transform hover:-translate-y-px">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            
            </div>
            
        
            </div>
            
                <!-- Right/Info Column -->
                <div class="lg:col-span-1 animate-fade-in-up animation-delay-300">
                    <!-- Quick Info Panel -->
                    <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-indigo-500 transition-all duration-300 hover:shadow-xl relative overflow-hidden mb-6">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-50 rounded-full opacity-50"></div>
                        <div class="absolute -bottom-12 -left-12 w-36 h-36 bg-purple-50 rounded-full opacity-50"></div>
                        <div>
                            <div class="flex items-center mb-4">
                                <span class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg text-white shadow mr-3">
                                    <i class="fas fa-unlock-alt"></i>
                                </span>
                                <h3 class="text-xl font-semibold text-gray-800">Deskripsi Menu Release Approved MC</h3>
                                </div>
                            <p class="text-gray-700 leading-relaxed mb-4">
                                Menu ini digunakan untuk melakukan proses <span class="font-semibold">release</span> Master Card yang sudah
                                berstatus <span class="font-semibold">approved</span> agar dapat digunakan pada proses produksi.
                                Anda dapat memilih customer, menentukan MCS#, lalu mengeksekusi release sesuai kebutuhan.
                            </p>
                            <div class="grid grid-cols-1 gap-3">
                                <div class="flex items-start bg-white border-l-4 border-indigo-500 rounded-md p-3 shadow-sm">
                                    <span class="text-indigo-600 mr-3"><i class="fas fa-user-check"></i></span>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">Pilih Customer</p>
                                        <p class="text-sm text-gray-700">Cari dan pilih AC# customer untuk memfilter Master Card.</p>
                                </div>
                                </div>
                                <div class="flex items-start bg-white border-l-4 border-blue-500 rounded-md p-3 shadow-sm">
                                    <span class="text-blue-600 mr-3"><i class="fas fa-barcode"></i></span>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">Tetapkan MCS#</p>
                                        <p class="text-sm text-gray-700">Masukkan atau cari nomor MCS yang akan direlease.</p>
                                </div>
                                </div>
                                <div class="flex items-start bg-white border-l-4 border-emerald-500 rounded-md p-3 shadow-sm">
                                    <span class="text-emerald-600 mr-3"><i class="fas fa-key"></i></span>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">Proses Release</p>
                                        <p class="text-sm text-gray-700">Konfirmasi release dan simpan catatan release bila diperlukan.</p>
                                </div>
                                    </div>
                                </div>
                            <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-3 flex items-start">
                                <span class="text-yellow-600 mr-3"><i class="fas fa-info-circle"></i></span>
                                <p class="text-sm text-yellow-800">
                                    Pastikan data Master Card sudah benar sebelum melakukan release. Tindakan ini akan
                                    membuka akses penggunaan MC di proses berikutnya.
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
            <div v-if="false && showOptions" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30">
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
            <div v-if="false && showOptionsFromCustomer" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30">
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
            <div v-if="false && showMcsOptions" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30">
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













        <!-- ReleaseApprovedMCModal -->
        <ReleaseApprovedMCModal
          :show="showReleaseApprovedMCModal"
          :masterCards="masterCards"
          :customerCode="searchTerm"
          :customerName="selectedCustomer ? selectedCustomer.name : ''"
          :mcsFrom="form.mcsFrom"
          @close="showReleaseApprovedMCModal = false"
        />

        <!-- Customer Account Modal -->
        <CustomerAccountModal
          :show="showCustomerAccountTable"
          @close="showCustomerAccountTable = false"
          @select="selectCustomerFromModal"
        />

        <!-- UpdateMcModal for MCS Table -->
        <UpdateMcModal
            v-if="showMcsTableModal"
            :showErrorModal="false"
            :showSetupMcModal="false"
            :showSetupPdModal="false"
            :showMcsTableModal="showMcsTableModal"
            :formData="{}"
            :mcComponents="[]"
            :zoomOption="'mc_specification'"
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
            :productDesigns="[]"
            :paperFlutes="[]"
            @closeErrorModal="() => {}"
            @closeSetupMcModal="() => {}"
            @closeSetupPdModal="() => {}"
            @closeMcsTableModal="showMcsTableModal = false"
            @selectComponent="() => {}"
            @setupPD="() => {}"
            @setupOthers="() => {}"
            @handleZoomChange="handleZoomChange"
            @fetchMcsData="fetchMcsData"
            @selectMcsItem="selectedMcs = $event"
            @selectMcs="selectMcs"
            @goToMcsPage="goToMcsPage"
            @updateSearchTerm="mcsSearchTerm = $event"
            @updateSortOption="mcsSortOption = $event"
            @productDesignSelected="() => {}"
            @paperFluteSelected="() => {}"
        />

    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';
import ReleaseApprovedMCModal from '@/Components/ReleaseApprovedMCModal.vue';
import CustomerAccountModal from '@/Components/customer-account-modal.vue';
import UpdateMcModal from '@/Components/UpdateMcModal.vue';

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
    mcsFrom: ''
});
const loading = ref(false);
const error = ref(null);
const showCustomerAccountModal = ref(false); // New state for customer account modal
const showCustomerAccountTable = ref(false); // New state for CustomerAccountModal component
const showMasterCardOptionsModal = ref(false); // New state for Master Card Options modal
const showReleaseApprovedMCModal = ref(false); // New state for Release Approved MC Modal

// UpdateMcModal state variables
const showMcsTableModal = ref(false);
const mcsSortOption = ref('mc_seq');
const mcsSortOrder = ref('asc');
const mcsStatusFilter = ref('Act');
const mcsSearchTerm = ref('');
const mcsInput = ref('');
const currentMcsModalType = ref(''); // To store which MC modal called the options
const mcsMasterCards = ref([]);
const mcsLoading = ref(false);
const mcsError = ref(null);
const mcsCurrentPage = ref(1);
const mcsLastPage = ref(1);
const selectedMcs = ref(null);

// Legacy variables (kept for backward compatibility)
const mcsOptionSortBy = ref('seq');
const mcsOptionSortOrder = ref('asc');
const mcsOptionStatus = ref(['active', 'obsolete']);

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

// Auto-open modal when AC# and MCS# are both filled
watch([searchTerm, () => form.value.mcsFrom], ([ac, mcs]) => {
    if (ac && mcs) {
        showReleaseApprovedMCModal.value = true;
    }
});

// Action handlers
const handleOK = () => {
    // Handle OK button 
    console.log('Proceed clicked.');
    if (searchTerm.value && form.value.mcsFrom) {
      showReleaseApprovedMCModal.value = true;
    } else {
      showNotification('Please fill in Customer AC# and MCS#.', 'error');
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
    if (!form.value.mcsFrom) {
        showNotification('Please provide MCS#', 'error');
        return;
    }
    
    console.log(`Searching for MCS: ${form.value.mcsFrom}`);
    // This would typically filter the masterCards by MCS
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
const mcsStatus = ref(['active']);
function applyMcsOptions(fromOK = false) {
    showMcsOptions.value = false;
    if (fromOK) {
        // Map legacy sort values to new UpdateMcModal sort options
        const sortMapping = {
            'seq': 'mc_seq',
            'model': 'mc_model', 
            'part': 'part_no',
            'ed': 'ext_dim_1',
            'id': 'int_dim_1'
        };
        
        mcsSortOption.value = sortMapping[mcsSortBy.value] || 'mc_seq';
        mcsSortOrder.value = 'asc';
        mcsStatusFilter.value = mcsStatus.value.includes('active') ? 'Act' : 'all';
        
        // Open the UpdateMcModal
        showMcsTableModal.value = true;
        mcsCurrentPage.value = 1;
        fetchMcsData(1);
    }
}

// Open Master Card table directly (skip options modal)
function openMcsTableDirect() {
    // Map default options
    mcsSortOption.value = 'mc_seq';
    mcsSortOrder.value = 'asc';
    mcsStatusFilter.value = 'Act';
    // Open the table and load data
    showMcsTableModal.value = true;
    mcsCurrentPage.value = 1;
    fetchMcsData(1);
}

const selectedCustomer = ref(null); // New ref for selected customer

// Function to handle row click and select customer
const selectCustomer = (customer) => {
    selectedCustomer.value = customer;
};

// Function to handle customer selection from CustomerAccountModal
const selectCustomerFromModal = (customer) => {
    console.log('Customer selected from modal:', customer);
    searchTerm.value = customer.customer_code || customer.code;
    selectedCustomer.value = customer;
};

// UpdateMcModal event handlers
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
        if (searchTerm.value) {
            customerFilter = `&customer_code=${searchTerm.value}`;
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

const handleZoomChange = () => {
    if (!selectedMcs.value) {
        alert('Please select a Master Card first.');
        return;
    }
    // For Release Approve MC, we can implement zoom functionality if needed
    console.log('Zoom changed for selected MC:', selectedMcs.value);
};

const selectMcs = (mcs) => {
    if (mcs) {
        form.value.mcsFrom = mcs.seq;
        showMcsTableModal.value = false;
        console.log('MCS selected:', mcs);
    }
};

const goToMcsPage = (page) => {
    if (page >= 1 && page <= mcsLastPage.value) {
        mcsCurrentPage.value = page;
        fetchMcsData(page);
    }
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
