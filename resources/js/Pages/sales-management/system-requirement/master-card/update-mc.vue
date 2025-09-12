<template>
    <AppLayout :header="'Update MC'">
        <!-- Header Section with animated elements -->
        <div
            class="bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 p-6 rounded-t-lg shadow-lg overflow-hidden relative"
        >
            <!-- Decorative Elements -->
            <div
                class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20"
            ></div>
            <div
                class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10"
            ></div>
            <div
                class="absolute bottom-0 right-0 w-32 h-32 bg-yellow-400 opacity-5 rounded-full translate-y-10 translate-x-10"
            ></div>

            <div class="flex items-center">
                <div
                    class="bg-gradient-to-br from-pink-500 to-purple-600 p-3 rounded-lg shadow-inner flex items-center justify-center relative overflow-hidden mr-4"
                >
                    <div
                        class="absolute -top-1 -right-1 w-6 h-6 bg-yellow-300 opacity-30 rounded-full animate-ping-slow"
                    ></div>
                    <div
                        class="absolute -bottom-1 -left-1 w-4 h-4 bg-blue-300 opacity-30 rounded-full animate-ping-slow animation-delay-500"
                    ></div>
                    <i class="fas fa-id-card text-white text-2xl z-10"></i>
                </div>
                <div>
                    <h2
                        class="text-2xl md:text-3xl font-bold text-white mb-1 text-shadow"
                    >
                        Update Master Card
                    </h2>
                    <p class="text-blue-100 max-w-2xl">
                        Manage and update master card information in the system
                    </p>
                </div>
            </div>
        </div>

        <div
            class="bg-white rounded-b-lg shadow-lg p-6 mb-6 bg-gradient-to-br from-white to-indigo-50"
        >
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Main Content -->
                <div class="lg:col-span-2">
                    <div
                        class="bg-white p-6 rounded-lg shadow-md border-t-4 border-indigo-500 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 relative overflow-hidden"
                    >
                        <div
                            class="absolute -top-20 -right-20 w-40 h-40 bg-indigo-50 rounded-full opacity-20"
                        ></div>
                        <div
                            class="absolute -bottom-8 -left-8 w-24 h-24 bg-purple-50 rounded-full opacity-20"
                        ></div>

                        <div
                            class="flex items-center mb-6 pb-2 border-b border-gray-200 relative z-10"
                        >
                            <div
                                class="p-2 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg mr-3 shadow-md"
                            >
                                <i class="fas fa-edit text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800">
                                Master Card Management
                            </h3>
                        </div>

                        <!-- Form content -->
                        <form @submit.prevent="saveRecord" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label
                                        for="ac"
                                        class="block text-sm font-medium text-gray-700 mb-1 flex items-center"
                                    >
                                        <span
                                            class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full mr-2 shadow-sm"
                                        >
                                            <i
                                                class="fas fa-building text-white text-xs"
                                            ></i>
                                        </span>
                                        AC#:
                                    </label>
                                    <div class="relative flex group">
                                        <span
                                            class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 group-hover:bg-indigo-50 group-hover:text-indigo-500 transition-colors"
                                        >
                                            <i class="fas fa-hashtag"></i>
                                        </span>
                                        <input
                                            type="text"
                                            id="ac"
                                            v-model="form.ac"
                                            @input="handleAcInput"
                                            class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-all group-hover:border-indigo-300 form-input"
                                            :class="{ filled: form.ac }"
                                        />
                                        <button
                                            type="button"
                                            @click="openCustomerAccountModal"
                                            class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-r-md transition-all transform active:translate-y-px relative overflow-hidden shadow-sm"
                                        >
                                            <span
                                                class="absolute inset-0 bg-white opacity-0 hover:opacity-20 transition-opacity"
                                            ></span>
                                            <i
                                                class="fas fa-search relative z-10"
                                            ></i>
                                        </button>
                                    </div>
                                </div>

                                <div
                                    class="flex items-end"
                                    v-if="!showDetailedMcInfo"
                                >
                                    <!-- Remove Add New button as per the screenshots -->
                                </div>
                            </div>

                            <div
                                class="absolute top-6 right-6"
                                v-if="showDetailedMcInfo"
                            >
                                <button
                                    type="button"
                                    :class="
                                        recordMode === 'existing'
                                            ? 'bg-orange-500 hover:bg-orange-600'
                                            : 'bg-blue-500 hover:bg-blue-600'
                                    "
                                    class="text-white px-4 py-2 rounded-lg shadow-md transition-colors text-sm font-semibold"
                                >
                                    Record:
                                    {{
                                        recordMode === "existing"
                                            ? "Review"
                                            : "Add"
                                    }}
                                </button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label
                                        for="mcs"
                                        class="block text-sm font-medium text-gray-700 mb-1 flex items-center"
                                    >
                                        <span
                                            class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-pink-500 to-red-500 rounded-full mr-2 shadow-sm"
                                        >
                                            <i
                                                class="fas fa-barcode text-white text-xs"
                                            ></i>
                                        </span>
                                        MCS#:
                                    </label>
                                    <div class="relative flex group">
                                        <span
                                            class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 group-hover:bg-indigo-50 group-hover:text-indigo-500 transition-colors"
                                        >
                                            <i class="fas fa-barcode"></i>
                                        </span>
                                        <input
                                            type="text"
                                            id="mcs"
                                            v-model="form.mcs"
                                            @input="handleMcsInput"
                                            class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-all group-hover:border-indigo-300 form-input"
                                            :class="{ filled: form.mcs }"
                                        />
                                        <button
                                            type="button"
                                            @click="searchMcs"
                                            class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-pink-500 to-red-500 hover:from-pink-600 hover:to-red-600 text-white rounded-r-md transition-all transform active:translate-y-px relative overflow-hidden shadow-sm"
                                        >
                                            <span
                                                class="absolute inset-0 bg-white opacity-0 hover:opacity-20 transition-opacity"
                                            ></span>
                                            <i
                                                class="fas fa-search relative z-10"
                                            ></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="flex items-end">
                                    <button
                                        type="button"
                                        @click="handleMcsProceed"
                                        class="bg-gradient-to-r from-blue-500 to-teal-600 hover:from-blue-600 hover:to-teal-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transform active:translate-y-px transition-all duration-300 shadow-md relative overflow-hidden group animate-float"
                                    >
                                        <span
                                            class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity"
                                        ></span>
                                        <div
                                            class="bg-white bg-opacity-30 rounded-full p-1.5 mr-2 flex items-center justify-center"
                                        >
                                            <i
                                                class="fas fa-play text-white text-xs"
                                            ></i>
                                        </div>
                                        <span>Proceed</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Fields yang hanya muncul setelah proceed -->
                            <div
                                v-if="showDetailedMcInfo"
                                class="grid grid-cols-1 md:grid-cols-3 gap-6"
                            >
                                <div>
                                    <label
                                        for="customer_name"
                                        class="block text-sm font-medium text-gray-700 mb-1 flex items-center"
                                    >
                                        <span
                                            class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full mr-2 shadow-sm"
                                        >
                                            <i
                                                class="fas fa-user text-white text-xs"
                                            ></i>
                                        </span>
                                        AC Name:
                                    </label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                                        >
                                            <i
                                                class="fas fa-user-circle text-gray-400"
                                            ></i>
                                        </div>
                                        <input
                                            type="text"
                                            id="customer_name"
                                            v-model="form.customer_name"
                                            readonly
                                            class="block w-full pl-10 pr-3 py-2 rounded-md border border-gray-300 bg-gray-50 form-input"
                                            :class="{
                                                filled: form.customer_name,
                                            }"
                                        />
                                    </div>
                                </div>
                                <div>
                                    <label
                                        for="mc_model"
                                        class="block text-sm font-medium text-gray-700 mb-1 flex items-center"
                                    >
                                        <span
                                            class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full mr-2 shadow-sm"
                                        >
                                            <i
                                                class="fas fa-box text-white text-xs"
                                            ></i>
                                        </span>
                                        MC Model:
                                    </label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                                        >
                                            <i
                                                class="fas fa-box text-gray-400"
                                            ></i>
                                        </div>
                                        <input
                                            type="text"
                                            id="mc_model"
                                            v-model="form.mc_model"
                                            :readonly="
                                                recordMode === 'existing'
                                            "
                                            :class="{
                                                'bg-gray-50':
                                                    recordMode === 'existing',
                                                'bg-white':
                                                    recordMode !== 'existing',
                                                filled: form.mc_model,
                                                empty:
                                                    !form.mc_model &&
                                                    showDetailedMcInfo,
                                            }"
                                            class="block w-full pl-10 pr-3 py-2 rounded-md border border-gray-300 focus:ring-amber-500 focus:border-amber-500 form-input"
                                        />
                                    </div>
                                </div>
                                <div>
                                    <label
                                        for="mc_short_model"
                                        class="block text-sm font-medium text-gray-700 mb-1 flex items-center"
                                    >
                                        <span
                                            class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full mr-2 shadow-sm"
                                        >
                                            <i
                                                class="fas fa-tag text-white text-xs"
                                            ></i>
                                        </span>
                                        MC Short Model:
                                    </label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                                        >
                                            <i
                                                class="fas fa-tag text-gray-400"
                                            ></i>
                                        </div>
                                        <input
                                            type="text"
                                            id="mc_short_model"
                                            v-model="form.mc_short_model"
                                            :readonly="
                                                recordMode === 'existing'
                                            "
                                            :class="{
                                                'bg-gray-50':
                                                    recordMode === 'existing',
                                                'bg-white':
                                                    recordMode !== 'existing',
                                                filled: form.mc_short_model,
                                                empty:
                                                    !form.mc_short_model &&
                                                    showDetailedMcInfo,
                                            }"
                                            class="block w-full pl-10 pr-3 py-2 rounded-md border border-gray-300 focus:ring-green-500 focus:border-green-500 form-input"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Status fields yang hanya muncul setelah proceed -->
                            <div
                                v-if="showDetailedMcInfo"
                                class="grid grid-cols-1 md:grid-cols-2 gap-6"
                            >
                                <div>
                                    <label
                                        for="mc_status"
                                        class="block text-sm font-medium text-gray-700 mb-1 flex items-center"
                                    >
                                        <span
                                            class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full mr-2 shadow-sm"
                                        >
                                            <i
                                                class="fas fa-toggle-on text-white text-xs"
                                            ></i>
                                        </span>
                                        MC Status:
                                    </label>
                                    <div class="relative flex group">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                                        >
                                            <i
                                                class="fas fa-info-circle text-gray-400"
                                            ></i>
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
                                            class="inline-flex items-center px-3 py-2 border border-gray-300 bg-purple-500 text-white rounded-r-md shadow-md hover:bg-purple-600 transition-colors text-sm"
                                        >
                                            Status Log
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label
                                        for="mc_approval"
                                        class="block text-sm font-medium text-gray-700 mb-1 flex items-center"
                                    >
                                        <span
                                            class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full mr-2 shadow-sm"
                                        >
                                            <i
                                                class="fas fa-check-circle text-white text-xs"
                                            ></i>
                                        </span>
                                        MC Approval:
                                    </label>
                                    <div class="relative flex group">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                                        >
                                            <i
                                                class="fas fa-check text-gray-400"
                                            ></i>
                                        </div>
                                        <input
                                            type="text"
                                            id="mc_approval"
                                            v-model="form.mc_approval"
                                            readonly
                                            :class="
                                                form.mc_approval === 'Yes'
                                                    ? 'bg-green-100 text-green-800'
                                                    : 'bg-red-100 text-red-800'
                                            "
                                            class="flex-1 min-w-0 block w-full pl-10 pr-3 py-2 rounded-l-md border border-r-0 border-gray-300 font-semibold"
                                        />
                                        <button
                                            type="button"
                                            class="inline-flex items-center px-3 py-2 border border-gray-300 bg-blue-500 text-white rounded-r-md shadow-md hover:bg-blue-600 transition-colors text-sm"
                                        >
                                            Approval Log
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Detailed MC Info Section -->
                    <div
                        v-if="showDetailedMcInfo"
                        class="mt-6 bg-white p-6 rounded-lg shadow-md border-t-4 border-emerald-500 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 relative overflow-hidden"
                    >
                        <div
                            class="absolute -top-20 -right-20 w-40 h-40 bg-emerald-50 rounded-full opacity-20"
                        ></div>
                        <div
                            class="absolute -bottom-8 -left-8 w-24 h-24 bg-green-50 rounded-full opacity-20"
                        ></div>

                        <div
                            class="flex items-center mb-6 pb-2 border-b border-gray-200 relative z-10"
                        >
                            <div
                                class="p-2 bg-gradient-to-r from-emerald-500 to-green-600 rounded-lg mr-3 shadow-md"
                            >
                                <i class="fas fa-info-circle text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800">
                                Detailed Master Card Information
                            </h3>
                        </div>

                        <div
                            class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm"
                        >
                            <div class="space-y-2">
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block text-gray-600"
                                            >Last MCS#:</label
                                        >
                                        <input
                                            type="text"
                                            :value="mcDetails.last_mcs"
                                            readonly
                                            class="block w-full border-gray-200 rounded-md bg-gray-50 px-3 py-2"
                                        />
                                    </div>
                                    <div>
                                        <label class="block text-gray-600"
                                            >Last Updated Seq#:</label
                                        >
                                        <input
                                            type="text"
                                            :value="mcDetails.last_updated_seq"
                                            readonly
                                            class="block w-full border-gray-200 rounded-md bg-gray-50 px-3 py-2"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex flex-wrap gap-4 justify-between">
                            <button
                                type="button"
                                @click="showMaintenanceLogModal = true"
                                class="px-4 py-2 bg-indigo-500 text-white rounded-md shadow-md hover:bg-indigo-600 transition-colors"
                            >
                                Maintenance Log
                            </button>
                            <button
                                type="button"
                                @click="handleNextSetup"
                                class="ml-auto px-4 py-2 bg-green-500 text-white rounded-md shadow-md hover:bg-green-600 transition-colors"
                            >
                                Next Setup
                            </button>
                        </div>
                    </div>

                    <div class="mt-6 text-center" v-if="recordSelected">
                        <button
                            type="button"
                            class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded-full shadow-lg transition-all transform hover:scale-105"
                        >
                            View & Print MC by Machine
                        </button>
                    </div>
                </div>

                <!-- Right Column - Quick Info -->
                <div class="lg:col-span-1">
                    <!-- Info Card -->
                    <div
                        class="bg-white p-6 rounded-lg shadow-md border-t-4 border-teal-500 mb-6 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 relative overflow-hidden"
                    >
                        <div
                            class="absolute -top-16 -right-16 w-32 h-32 bg-teal-50 rounded-full opacity-20"
                        ></div>
                        <div
                            class="absolute -bottom-6 -left-6 w-20 h-20 bg-green-50 rounded-full opacity-20"
                        ></div>

                        <div
                            class="flex items-center mb-4 pb-2 border-b border-gray-200 relative z-10"
                        >
                            <div
                                class="p-2 bg-gradient-to-r from-teal-500 to-green-500 rounded-lg mr-3 shadow-md"
                            >
                                <i class="fas fa-info-circle text-white"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">
                                Master Card Info
                            </h3>

                            <!-- Loading indicator for small actions -->
                            <span
                                v-if="isProcessing"
                                class="inline-flex items-center ml-2 animate-pulse"
                            >
                                <span
                                    class="h-2 w-2 bg-teal-500 rounded-full mr-1"
                                ></span>
                                <span
                                    class="h-2 w-2 bg-teal-500 rounded-full mr-1 animation-delay-100"
                                ></span>
                                <span
                                    class="h-2 w-2 bg-teal-500 rounded-full animation-delay-200"
                                ></span>
                                <span class="text-sm text-teal-500 ml-1"
                                    >Processing</span
                                >
                            </span>
                        </div>

                        <div class="space-y-4">
                            <div class="p-4 bg-teal-50 rounded-lg">
                                <h4
                                    class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2 flex items-center"
                                >
                                    <span
                                        class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-teal-500 to-green-500 rounded-full mr-2 shadow-sm"
                                    >
                                        <i
                                            class="fas fa-book text-white text-xs"
                                        ></i>
                                    </span>
                                    Instructions
                                </h4>
                                <ul class="text-sm text-gray-600 space-y-2">
                                    <li class="flex items-start">
                                        <span
                                            class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-400 to-indigo-400 rounded-full mr-2 shadow-sm mt-0.5"
                                        >
                                            <i
                                                class="fas fa-building text-white text-xs"
                                            ></i>
                                        </span>
                                        <span
                                            ><strong>Step 1:</strong> Select
                                            Customer Account (AC#) first</span
                                        >
                                    </li>
                                    <li class="flex items-start">
                                        <span
                                            class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full mr-2 shadow-sm mt-0.5"
                                        >
                                            <i
                                                class="fas fa-search text-white text-xs"
                                            ></i>
                                        </span>
                                        <span
                                            ><strong>Step 2:</strong> Search
                                            existing MCS or enter new
                                            number</span
                                        >
                                    </li>
                                    <li class="flex items-start">
                                        <span
                                            class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-green-400 to-emerald-400 rounded-full mr-2 shadow-sm mt-0.5"
                                        >
                                            <i
                                                class="fas fa-play text-white text-xs"
                                            ></i>
                                        </span>
                                        <span
                                            ><strong>Step 3:</strong> Click
                                            "Proceed" to continue</span
                                        >
                                    </li>
                                    <li
                                        v-if="showDetailedMcInfo"
                                        class="flex items-start"
                                    >
                                        <span
                                            class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-amber-400 to-orange-400 rounded-full mr-2 shadow-sm mt-0.5"
                                        >
                                            <i
                                                class="fas fa-edit text-white text-xs"
                                            ></i>
                                        </span>
                                        <span
                                            ><strong>Step 4:</strong> Fill MC
                                            Model for new records</span
                                        >
                                    </li>
                                    <li
                                        v-if="showDetailedMcInfo"
                                        class="flex items-start"
                                    >
                                        <span
                                            class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-red-400 to-rose-400 rounded-full mr-2 shadow-sm mt-0.5"
                                        >
                                            <i
                                                class="fas fa-cogs text-white text-xs"
                                            ></i>
                                        </span>
                                        <span
                                            ><strong>Step 5:</strong> Use Next
                                            Setup to configure components</span
                                        >
                                    </li>
                                </ul>
                                <div
                                    class="mt-3 p-2 bg-yellow-50 border border-yellow-200 rounded-md"
                                >
                                    <p
                                        class="text-xs text-yellow-800 font-medium"
                                    >
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Master Cards are linked to specific
                                        Customer Accounts. Only MCs belonging to
                                        the selected customer will be shown.
                                    </p>
                                </div>
                            </div>

                            <div class="p-4 bg-blue-50 rounded-lg">
                                <h4
                                    class="text-sm font-semibold text-blue-800 uppercase tracking-wider mb-2 flex items-center"
                                >
                                    <span
                                        class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full mr-2 shadow-sm"
                                    >
                                        <i
                                            class="fas fa-info-circle text-white text-xs"
                                        ></i>
                                    </span>
                                    Status
                                </h4>
                                <div class="space-y-3">
                                    <div class="flex items-start">
                                        <span
                                            class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-gray-400 to-slate-500 rounded-full mr-2 shadow-sm mt-0.5 flex-shrink-0"
                                        >
                                            <i
                                                class="fas fa-info-circle text-white text-xs"
                                            ></i>
                                        </span>
                                        <div>
                                            <p
                                                v-if="!showDetailedMcInfo"
                                                class="text-xs text-gray-600"
                                            >
                                                Ready to start
                                            </p>
                                            <p
                                                v-else
                                                class="text-xs text-gray-600"
                                            >
                                                {{
                                                    recordMode === "existing"
                                                        ? "Existing MC - Approved"
                                                        : "New MC - Pending Approval"
                                                }}
                                            </p>
                                            <p
                                                v-if="!showDetailedMcInfo"
                                                class="text-xs text-gray-400 mt-1"
                                            >
                                                Enter AC# and MCS# to proceed
                                            </p>
                                            <p
                                                v-else
                                                class="text-xs text-gray-400 mt-1"
                                            >
                                                {{
                                                    recordMode === "existing"
                                                        ? "Ready for modifications"
                                                        : "Requires approval workflow"
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div
                        class="bg-white p-6 rounded-lg shadow-md border-t-4 border-purple-500 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 relative overflow-hidden"
                    >
                        <div
                            class="absolute -top-16 -right-16 w-32 h-32 bg-purple-50 rounded-full opacity-20"
                        ></div>
                        <div
                            class="absolute -bottom-6 -left-6 w-20 h-20 bg-pink-50 rounded-full opacity-20"
                        ></div>

                        <div
                            class="flex items-center mb-4 pb-2 border-b border-gray-200 relative z-10"
                        >
                            <div
                                class="p-2 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg mr-3 shadow-md"
                            >
                                <i class="fas fa-link text-white"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">
                                Quick Links
                            </h3>
                        </div>

                        <div class="space-y-2">
                            <a
                                href="#"
                                class="group block p-2 hover:bg-gray-50 rounded-md text-sm text-gray-700 hover:text-blue-600 transition-colors"
                            >
                                <span class="inline-flex items-center">
                                    <span
                                        class="inline-flex items-center justify-center w-6 h-6 bg-gradient-to-r from-green-400 to-teal-500 rounded-full mr-2 shadow-sm transition-all duration-300 group-hover:scale-110"
                                    >
                                        <i
                                            class="fas fa-check-circle text-white text-xs"
                                        ></i>
                                    </span>
                                    <span
                                        class="transition-all duration-300 group-hover:translate-x-1"
                                        >Approve Master Card</span
                                    >
                                </span>
                            </a>
                            <a
                                href="#"
                                class="group block p-2 hover:bg-gray-50 rounded-md text-sm text-gray-700 hover:text-blue-600 transition-colors"
                            >
                                <span class="inline-flex items-center">
                                    <span
                                        class="inline-flex items-center justify-center w-6 h-6 bg-gradient-to-r from-amber-400 to-orange-500 rounded-full mr-2 shadow-sm transition-all duration-300 group-hover:scale-110"
                                    >
                                        <i
                                            class="fas fa-history text-white text-xs"
                                        ></i>
                                    </span>
                                    <span
                                        class="transition-all duration-300 group-hover:translate-x-1"
                                        >MC Maintenance Log</span
                                    >
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
            :mcLoaded="recordMode === 'existing' ? selectedMcsFull : null"
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
            :soValues="soValues"
            :woValues="woValues"
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
            @openPaperQualityModal="openPaperQualityModal"
            @openWoPaperQualityModal="openWoPaperQualityModal"
            @saveMasterCard="saveMasterCardFromModal"
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

        <!-- Paper Quality Modal -->
        <PaperQualityModal
            :show="showPaperQualityModal"
            :qualities="paperQualities"
            @close="showPaperQualityModal = false"
            @select="onPaperQualitySelected"
        />

        <!-- Chemical Coat Modal (parent copy for consistency; selection handled in child for now) -->
        <ChemicalCoatModal
            :show="showChemicalCoatModal"
            :coats="chemicalCoats"
            @close="showChemicalCoatModal = false"
            @select="
                () => {
                    showChemicalCoatModal = false;
                }
            "
        />

        <!-- Customer Account Modal with Modern Design -->
        <div
            v-if="showCustomerAccountModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 animate-fadeIn"
        >
            <div
                class="bg-white rounded-lg shadow-xl w-3/4 max-w-4xl max-h-[80vh] flex flex-col transform transition-all duration-300 animate-scaleIn"
            >
                <!-- Modal Header with Gradient Background -->
                <div
                    class="bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-600 text-white px-6 py-4 rounded-t-lg flex justify-between items-center relative overflow-hidden"
                >
                    <!-- Decorative Elements -->
                    <div
                        class="absolute top-0 right-0 w-20 h-20 bg-white opacity-5 rounded-full -translate-y-10 translate-x-10 animate-pulse-slow"
                    ></div>
                    <div
                        class="absolute bottom-0 left-0 w-16 h-16 bg-white opacity-5 rounded-full translate-y-8 -translate-x-8 animate-pulse-slow animation-delay-500"
                    ></div>

                    <div class="flex items-center space-x-3 relative z-10">
                        <div
                            class="bg-white bg-opacity-20 p-2 rounded-lg shadow-inner transform transition-transform hover:scale-110"
                        >
                            <i class="fas fa-users text-xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-shadow">
                            Customer Account Table
                        </h3>
                    </div>
                    <button
                        @click="showCustomerAccountModal = false"
                        class="text-white hover:text-gray-200 transition-colors transform hover:scale-110 focus:outline-none"
                    >
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <!-- Search and Filter Bar with Animation -->
                <div
                    class="p-4 border-b bg-gradient-to-r from-gray-50 to-white"
                >
                    <div class="flex flex-col md:flex-row gap-4">
                        <!-- Search Input -->
                        <div
                            class="relative flex-grow transform transition-all duration-300 hover:scale-[1.01]"
                        >
                            <input
                                type="text"
                                v-model="searchTerm"
                                placeholder="Search by customer code or name..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent shadow-sm transition-shadow duration-300 hover:shadow-md"
                            />
                            <div
                                class="absolute left-3 top-2.5 text-gray-400 transition-colors duration-300 group-hover:text-indigo-500"
                            >
                                <i class="fas fa-search"></i>
                            </div>
                        </div>

                        <!-- Sort Options -->
                        <div class="flex space-x-2">
                            <button
                                @click="
                                    customerSortOption = 'customer_code';
                                    loadCustomerAccounts();
                                "
                                class="px-3 py-2 rounded-lg border transition-all duration-200 flex items-center space-x-1"
                                :class="
                                    customerSortOption === 'customer_code'
                                        ? 'bg-indigo-100 border-indigo-300 text-indigo-700'
                                        : 'border-gray-300 hover:bg-gray-50'
                                "
                            >
                                <i
                                    class="fas fa-sort-numeric-down text-xs"
                                    :class="
                                        customerSortOption === 'customer_code'
                                            ? 'text-indigo-500'
                                            : 'text-gray-500'
                                    "
                                ></i>
                                <span class="text-sm">Sort by Code</span>
                            </button>

                            <button
                                @click="
                                    customerSortOption = 'customer_name';
                                    loadCustomerAccounts();
                                "
                                class="px-3 py-2 rounded-lg border transition-all duration-200 flex items-center space-x-1"
                                :class="
                                    customerSortOption === 'customer_name'
                                        ? 'bg-indigo-100 border-indigo-300 text-indigo-700'
                                        : 'border-gray-300 hover:bg-gray-50'
                                "
                            >
                                <i
                                    class="fas fa-sort-alpha-down text-xs"
                                    :class="
                                        customerSortOption === 'customer_name'
                                            ? 'text-indigo-500'
                                            : 'text-gray-500'
                                    "
                                ></i>
                                <span class="text-sm">Sort by Name</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Customer List with Enhanced Styling -->
                <div
                    class="flex-1 overflow-y-auto p-4 bg-gradient-to-b from-white to-gray-50"
                >
                    <!-- Loading State -->
                    <div
                        v-if="isLoadingCustomers"
                        class="flex justify-center items-center h-full"
                    >
                        <div class="flex flex-col items-center space-y-3">
                            <div
                                class="w-10 h-10 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin"
                            ></div>
                            <p class="text-indigo-600 font-medium">
                                Loading customers...
                            </p>
                        </div>
                    </div>

                    <!-- Customer Table -->
                    <div
                        v-else
                        class="bg-white rounded-lg border border-gray-200 overflow-hidden"
                    >
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4"
                                    >
                                        <div
                                            class="flex items-center space-x-1"
                                        >
                                            <i
                                                class="fas fa-id-card text-blue-500 mr-1"
                                            ></i>
                                            <span>Customer Code</span>
                                        </div>
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/2"
                                    >
                                        <div
                                            class="flex items-center space-x-1"
                                        >
                                            <i
                                                class="fas fa-user text-blue-500 mr-1"
                                            ></i>
                                            <span>Customer Name</span>
                                        </div>
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4"
                                    >
                                        <div
                                            class="flex items-center space-x-1"
                                        >
                                            <i
                                                class="fas fa-hand-pointer text-blue-500 mr-1"
                                            ></i>
                                            <span>Select</span>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr
                                    v-for="(
                                        customer, index
                                    ) in filteredCustomers"
                                    :key="customer.customer_code"
                                    class="hover:bg-blue-50 cursor-pointer transition-colors animate-fadeIn"
                                    :class="{
                                        'bg-blue-100':
                                            selectedCustomer?.customer_code ===
                                            customer.customer_code,
                                        'animation-delay-100': index % 5 === 0,
                                        'animation-delay-200': index % 5 === 1,
                                        'animation-delay-300': index % 5 === 2,
                                        'animation-delay-200': index % 5 === 3,
                                        'animation-delay-100': index % 5 === 4,
                                    }"
                                    @click="selectCustomer(customer)"
                                >
                                    <td
                                        class="px-6 py-3 whitespace-nowrap font-medium text-blue-700"
                                    >
                                        {{ customer.customer_code }}
                                    </td>
                                    <td
                                        class="px-6 py-3 whitespace-nowrap text-gray-700"
                                    >
                                        {{ customer.customer_name }}
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <button
                                            @click.stop="
                                                selectCustomer(customer)
                                            "
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:scale-105 transition-transform"
                                        >
                                            <i
                                                class="fas fa-check mr-1.5 animate-bounce-gentle"
                                            ></i>
                                            Select
                                        </button>
                                    </td>
                                </tr>
                                <tr
                                    v-if="
                                        filteredCustomers.length === 0 &&
                                        !isLoadingCustomers
                                    "
                                >
                                    <td
                                        colspan="3"
                                        class="px-6 py-8 text-center"
                                    >
                                        <div
                                            class="flex flex-col items-center justify-center space-y-2 text-gray-500"
                                        >
                                            <i
                                                class="fas fa-search text-4xl text-gray-400"
                                            ></i>
                                            <p>
                                                No customers found matching your
                                                search criteria
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Footer with Options -->
                <div
                    class="p-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center rounded-b-lg"
                >
                    <div class="text-sm text-gray-500 animate-fadeIn">
                        <span class="animate-slideInLeft animation-delay-100"
                            >{{ filteredCustomers.length }} customers
                            found</span
                        >
                    </div>
                    <div class="flex space-x-2">
                        <button
                            @click="showCustomerAccountModal = false"
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors"
                        >
                            Cancel
                        </button>
                        <button
                            @click="loadCustomerAccounts"
                            class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white rounded-lg transition-colors flex items-center space-x-1"
                        >
                            <i class="fas fa-sync-alt mr-1"></i>
                            <span>Refresh</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, nextTick } from 'vue';

const selectedMcsFull = ref(null);
const saveMasterCardFromModal = async (pdSetup = null) => {
    try {
        const payload = {
            mc_seq: form.value.mcs,
            customer_code: form.value.ac,
            mc_model: form.value.mc_model || '',
            mc_short_model: form.value.mc_short_model || '',
            status: form.value.mc_status || 'Active',
            mc_approval: form.value.mc_approval || 'No',
            part_no: '',
            comp_no: '',
            p_design: '',
            ext_dim_1: mcDetails.value.ext_dim_1 || '',
            ext_dim_2: mcDetails.value.ext_dim_2 || '',
            ext_dim_3: mcDetails.value.ext_dim_3 || '',
            int_dim_1: mcDetails.value.int_dim_1 || '',
            int_dim_2: mcDetails.value.int_dim_2 || '',
            int_dim_3: mcDetails.value.int_dim_3 || '',
            detailed_master_card: {
                mc_details: mcDetails.value,
            },
            pd_setup: pdSetup ? { 
                ...pdSetup,
                soValues: Array.isArray(soValues.value) ? soValues.value : [],
                woValues: Array.isArray(woValues.value) ? woValues.value : [],
            } : null,
        };

        const res = await fetch('/api/update-mc/master-cards', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            credentials: 'same-origin',
            body: JSON.stringify(payload),
        });

        if (!res.ok) {
            const err = await res.json().catch(() => ({}));
            throw new Error(err.message || `Failed to save: ${res.status}`);
        }

        toast.success('Master Card saved');
        showSetupMcModal.value = false;
        showSetupPdModal.value = false;
    } catch (e) {
        toast.error(e.message || 'Failed to save Master Card');
    }
};
import { Link } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import axios from "axios";
import { useToast } from "@/Composables/useToast";
import CustomerAccountModal from "@/Components/CustomerAccountModal.vue";
import UpdateMcModal from "@/Components/UpdateMcModal.vue";
import MasterCardMaintenanceLogModal from "@/Components/MasterCardMaintenanceLogModal.vue";
import MasterCardZoomModal from "@/Components/MasterCardZoomModal.vue";
import MasterCardCurrentPriceModal from "@/Components/MasterCardCurrentPriceModal.vue";
import MasterCardStandByPriceModal from "@/Components/MasterCardStandByPriceModal.vue";
import SecondPasswordAccessModal from "@/Components/SecondPasswordAccessModal.vue";
import PaperQualityModal from "@/Components/paper-quality-modal.vue";
import ChemicalCoatModal from "@/Components/chemical-coat-modal.vue";

const toast = useToast();

// Form data
const form = ref({
    ac: "",
    mcs: "",
    customer_name: "",
    mc_model: "",
    mc_short_model: "",
    mc_status: "Active",
    mc_approval: "No",
});

// Loading state variables
const isLoading = ref(false);
const isProcessing = ref(false);

// Flag to prevent input handlers from clearing data during programmatic updates
const isProgrammaticUpdate = ref(false);

// Customer Account State
const customersList = ref([]);
const searchTerm = ref("");
const showCustomerAccountModal = ref(false);
const isLoadingCustomers = ref(false);
const customerSortOption = ref("customer_code"); // Default sort by customer code

// Computed Properties for Customer Account
const filteredCustomers = computed(() => {
    if (!searchTerm.value) {
        return customersList.value;
    }
    const searchLower = searchTerm.value.toLowerCase();
    return customersList.value.filter(
        (customer) =>
            customer.customer_code.toLowerCase().includes(searchLower) ||
            customer.customer_name.toLowerCase().includes(searchLower)
    );
});

// Methods for Customer Account
const loadCustomerAccounts = async () => {
    isLoadingCustomers.value = true;
    try {
        // Using fetch instead of axios for more direct control over response
        const response = await fetch("/api/customer-accounts", {
            headers: {
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
            credentials: "same-origin",
        });

        if (!response.ok) {
            throw new Error(
                `Server returned ${response.status}: ${response.statusText}`
            );
        }

        const rawData = await response.json();
        console.log(
            "Customer accounts data type:",
            typeof rawData,
            "Is array:",
            Array.isArray(rawData),
            "Length:",
            Array.isArray(rawData) ? rawData.length : "N/A"
        );

        // Handle different response formats
        if (Array.isArray(rawData)) {
            customersList.value = rawData;
            console.log(`Loaded ${rawData.length} customer accounts`);
        } else if (rawData.data && Array.isArray(rawData.data)) {
            customersList.value = rawData.data;
            console.log(
                `Loaded ${rawData.data.length} customer accounts from data property`
            );
        } else {
            customersList.value = [];
            console.error("Unexpected data format:", rawData);
            throw new Error("Invalid data format returned from server");
        }

        // Make sure customersList is an array before sorting
        if (
            Array.isArray(customersList.value) &&
            customersList.value.length > 0
        ) {
            // Sort customers by default
            if (customerSortOption.value === "customer_code") {
                customersList.value.sort((a, b) => {
                    const aCode = a.customer_code?.toLowerCase() || "";
                    const bCode = b.customer_code?.toLowerCase() || "";
                    return aCode.localeCompare(bCode);
                });
            } else {
                customersList.value.sort((a, b) => {
                    const aName = a.customer_name?.toLowerCase() || "";
                    const bName = b.customer_name?.toLowerCase() || "";
                    return aName.localeCompare(bName);
                });
            }

            toast.success(
                `Loaded ${customersList.value.length} customer accounts successfully`
            );
        } else {
            toast.warning("No customer accounts found");
        }
    } catch (error) {
        console.error("Error loading customer accounts:", error);
        toast.error(`Failed to load customer accounts: ${error.message}`);
        customersList.value = []; // Ensure it's an empty array if there's an error
    } finally {
        isLoadingCustomers.value = false;
    }
};

// This is the main selectCustomer function that will be used
const selectCustomer = async (customer) => {
    if (!customer) return;

    // Show processing state
    isProcessing.value = true;

    // Update the selected customer
    selectedCustomer.value = customer;

    // Set the form fields with customer information
    isProgrammaticUpdate.value = true; // Prevent input handlers from clearing data
    
    // Use nextTick to ensure DOM updates
    await nextTick();
    form.value.ac = customer.customer_code;
    form.value.customer_name = customer.customer_name;
    
    // Force reactivity update
    await nextTick();
    isProgrammaticUpdate.value = false; // Re-enable input handlers
    
    console.log('Customer selected:', {
        ac: form.value.ac,
        customer_name: form.value.customer_name,
        selectedCustomer: customer
    });

    // Close the modal
    showCustomerAccountModal.value = false;

    // Reset Master Card data when customer changes
    isProgrammaticUpdate.value = true; // Prevent input handlers from clearing data
    form.value.mcs = "";
    isProgrammaticUpdate.value = false; // Re-enable input handlers
    selectedMcs.value = null;
    mcsMasterCards.value = [];
    showDetailedMcInfo.value = false; // Don't show details until MC is selected or MCS number is entered
    recordMode.value = "new";

    // Prepare suggested values based on customer data
    const suggestedModel = customer.default_product || "";
    const suggestedShortModel =
        customer.short_name || customer.customer_code || "";

    // Update form fields first to ensure they're visible in the UI
    form.value.mc_model = suggestedModel;
    form.value.mc_short_model = suggestedShortModel;
    form.value.mc_status = "Active";
    form.value.mc_approval = "No";

    // Then update mcDetails to keep everything in sync
    mcDetails.value = {
        ac_name: customer.customer_name,
        mc_model: suggestedModel,
        mc_short_model: suggestedShortModel,
        mc_status: "Active",
        mc_approval: "No",
        last_mcs: "",
        last_updated_seq: "",
        ext_dim_1: "",
        ext_dim_2: "",
        ext_dim_3: "",
        int_dim_1: "",
        int_dim_2: "",
        int_dim_3: "",
    };

    // Show toast notification for feedback
    toast.success(
        `Selected customer: ${customer.customer_code} - ${customer.customer_name}`
    );

    // Set record as selected and close any open tables
    recordSelected.value = true;
    showCustomerAccountTable.value = false;

    // Animate the selection with a highlight effect
    highlightSelectedFields();

    // Focus on the MCS# field after customer selection for better UX
    setTimeout(() => {
        const mcsInput = document.getElementById("mcs");
        if (mcsInput) {
            mcsInput.focus();
        }
        // Clear the processing state after a short delay
        isProcessing.value = false;
    }, 800); // Show processing for a bit to give feedback to user
};

// Function to highlight fields after selection for better UX
const highlightSelectedFields = () => {
    const fields = ["ac", "customer_name", "mc_model", "mc_short_model"];

    fields.forEach((field) => {
        const element = document.getElementById(field);
        if (element) {
            // Add a highlight class
            element.classList.add("highlight-field");

            // Remove it after animation completes
            setTimeout(() => {
                element.classList.remove("highlight-field");
            }, 1500);
        }
    });
};

const openCustomerAccountModal = () => {
    // Similar approach as obsolete-reactive-customer-ac.vue
    if (customersList.value.length === 0) {
        // Use .then instead of await to better handle errors
        loadCustomerAccounts()
            .then(() => {
                showCustomerAccountModal.value = true;
            })
            .catch((error) => {
                console.error("Failed to load customer accounts:", error);
                // Still show the modal, just with empty data
                showCustomerAccountModal.value = true;
            });
    } else {
        showCustomerAccountModal.value = true;
    }
};

// UI state
const recordSelected = ref(false);
const showCustomerAccountTable = ref(false);
const sortOption = ref("code");
const recordStatus = ref({
    active: true,
    obsolete: false,
});
const tableSearchTerm = ref("");
const selectedCustomer = ref(null);
const recordMode = ref("new"); // 'new' or 'existing'

// MCS Modal state
const showMcsTableModal = ref(false);
const mcsSortOption = ref("mc_seq");
const mcsSortOrder = ref("asc");
const mcsRecordStatus = ref({
    active: true,
    obsolete: false,
});
const mcsSearchTerm = ref("");
const selectedMcs = ref(null);
const mcsMasterCards = ref([]);
const mcsLoading = ref(false);
const mcsError = ref(null);
const mcsCurrentPage = ref(1);
const mcsLastPage = ref(1);
const mcsStatusFilter = ref("Act");

// New state for Maintenance Log Modal
const showMaintenanceLogModal = ref(false);

// New states for Zoom Dropdown
const zoomOption = ref("");
const showMasterCardSpecModal = ref(false);
const showMasterCardCurrentPriceModal = ref(false);
const showMasterCardStandByPriceModal = ref(false);

// New state for Second Password Access Modal
const showSecondPasswordAccessModal = ref(false);

// New state for Paper Quality Modal
const showPaperQualityModal = ref(false);
const paperQualities = ref([]);
const paperQualityLoading = ref(false);
const selectedPaperQuality = ref(null);
const activeSoIndex = ref(null); // Track which SO button was clicked
const activeWoIndex = ref(null); // Track which WO button was clicked

// SO values storage
const soValues = ref(["", "", "", "", ""]); // Array to store 5 SO values
// WO values storage
const woValues = ref(["", "", "", "", ""]); // Array to store 5 WO values

// Error modal state
const showErrorModal = ref(false);

// Chemical coat modal state (available for future parent usage if needed)
const showChemicalCoatModal = ref(false);
const chemicalCoats = ref([]);
const chemicalCoatLoading = ref(false);

// Setup MC Component Modal state
const showSetupMcModal = ref(false);
const showSetupPdModal = ref(false);
const mcComponents = ref([
    { c_num: "Main", pd: "", pcs_set: "", part_num: "", selected: true },
    { c_num: "Fit1", pd: "", pcs_set: "", part_num: "", selected: false },
    { c_num: "Fit2", pd: "", pcs_set: "", part_num: "", selected: false },
    { c_num: "Fit3", pd: "", pcs_set: "", part_num: "", selected: false },
    { c_num: "Fit4", pd: "", pcs_set: "", part_num: "", selected: false },
    { c_num: "Fit5", pd: "", pcs_set: "", part_num: "", selected: false },
    { c_num: "Fit6", pd: "", pcs_set: "", part_num: "", selected: false },
    { c_num: "Fit7", pd: "", pcs_set: "", part_num: "", selected: false },
    { c_num: "Fit8", pd: "", pcs_set: "", part_num: "", selected: false },
    { c_num: "Fit9", pd: "", pcs_set: "", part_num: "", selected: false },
]);

// Computed property for filtered and sorted MCS data
const filteredMcsData = computed(() => mcsMasterCards.value);

// New state for the detailed MC info section
const showDetailedMcInfo = ref(false);
const mcDetails = ref({
    ac_name: "",
    mc_model: "",
    mc_short_model: "",
    mc_status: "Active",
    mc_approval: "No",
    last_mcs: "",
    last_updated_seq: "",
    ext_dim_1: "",
    ext_dim_2: "",
    ext_dim_3: "",
    int_dim_1: "",
    int_dim_2: "",
    int_dim_3: "",
});

// Product Design data - sample data matching the image format
const productDesigns = ref([
    {
        pd_code: "APR",
        pd_name: "APR",
        pd_design_type: "T-Trading",
        idc: "NA",
        product: "005",
        joint: "No",
        joint_to_print: "No",
        pcs_to_joint: "1",
        score: "Yes",
        slot: "No",
        flute_style: "Blank N/A",
        print_flute: "No",
        input_weight: "Yes",
    },
    {
        pd_code: "B0",
        pd_name: "B0/B0",
        pd_design_type: "M-Manufacture",
        idc: "0510",
        product: "001",
        joint: "No",
        joint_to_print: "No",
        pcs_to_joint: "1",
        score: "Yes",
        slot: "No",
        flute_style: "N-Normal",
        print_flute: "No",
        input_weight: "Yes",
    },
    {
        pd_code: "B0 DJ",
        pd_name: "B0/B0 DOUBLE JOINT",
        pd_design_type: "M-Manufacture",
        idc: "0511u2",
        product: "001",
        joint: "Yes",
        joint_to_print: "Yes",
        pcs_to_joint: "2",
        score: "Yes",
        slot: "No",
        flute_style: "N-Normal",
        print_flute: "No",
        input_weight: "Yes",
    },
    {
        pd_code: "B0/B1",
        pd_name: "B0/B1 FLEP+FLAP",
        pd_design_type: "M-Manufacture",
        idc: "0200B",
        product: "001",
        joint: "No",
        joint_to_print: "No",
        pcs_to_joint: "1",
        score: "Yes",
        slot: "No",
        flute_style: "N-Normal",
        print_flute: "No",
        input_weight: "Yes",
    },
    {
        pd_code: "B0/B1 4J",
        pd_name: "B0/B1 4 JOINT",
        pd_design_type: "M-Manufacture",
        idc: "0200+B",
        product: "001",
        joint: "Yes",
        joint_to_print: "Yes",
        pcs_to_joint: "4",
        score: "Yes",
        slot: "No",
        flute_style: "N-Normal",
        print_flute: "No",
        input_weight: "Yes",
    },
    {
        pd_code: "B0/B1 DJ",
        pd_name: "B0/B1 DOUBLE JOINT",
        pd_design_type: "M-Manufacture",
        idc: "0201+B",
        product: "001",
        joint: "Yes",
        joint_to_print: "Yes",
        pcs_to_joint: "2",
        score: "Yes",
        slot: "No",
        flute_style: "N-Normal",
        print_flute: "No",
        input_weight: "Yes",
    },
    {
        pd_code: "B1",
        pd_name: "REGULAR BOX",
        pd_design_type: "M-Manufacture",
        idc: "0201",
        product: "001",
        joint: "No",
        joint_to_print: "No",
        pcs_to_joint: "1",
        score: "Yes",
        slot: "No",
        flute_style: "N-Normal",
        print_flute: "No",
        input_weight: "Yes",
    },
    {
        pd_code: "B1 4J",
        pd_name: "B1 4 JOINT",
        pd_design_type: "M-Manufacture",
        idc: "0201+a",
        product: "001",
        joint: "Yes",
        joint_to_print: "Yes",
        pcs_to_joint: "4",
        score: "Yes",
        slot: "No",
        flute_style: "N-Normal",
        print_flute: "No",
        input_weight: "Yes",
    },
    {
        pd_code: "B1 4J 2C",
        pd_name: "B1 4 JOINT 2 CREASING",
        pd_design_type: "M-Manufacture",
        idc: "0201+",
        product: "001",
        joint: "Yes",
        joint_to_print: "Yes",
        pcs_to_joint: "4",
        score: "Yes",
        slot: "No",
        flute_style: "N-Normal",
        print_flute: "No",
        input_weight: "Yes",
    },
    {
        pd_code: "B1 DJ",
        pd_name: "B1 DOUBLE JOINT + CREASING",
        pd_design_type: "M-Manufacture",
        idc: "0201J2",
        product: "001",
        joint: "Yes",
        joint_to_print: "Yes",
        pcs_to_joint: "2",
        score: "Yes",
        slot: "No",
        flute_style: "N-Normal",
        print_flute: "No",
        input_weight: "Yes",
    },
    {
        pd_code: "B1+1CRJ",
        pd_name: "B1+1CREASING JOINT",
        pd_design_type: "M-Manufacture",
        idc: "0201+",
        product: "001",
        joint: "Yes",
        joint_to_print: "Yes",
        pcs_to_joint: "1",
        score: "Yes",
        slot: "No",
        flute_style: "N-Normal",
        print_flute: "No",
        input_weight: "Yes",
    },
    {
        pd_code: "B1+1CRDJ",
        pd_name: "B1+1CREASING DOUBLE JOINT",
        pd_design_type: "M-Manufacture",
        idc: "0201+2",
        product: "001",
        joint: "Yes",
        joint_to_print: "Yes",
        pcs_to_joint: "2",
        score: "Yes",
        slot: "No",
        flute_style: "N-Normal",
        print_flute: "No",
        input_weight: "Yes",
    },
    {
        pd_code: "B1+2CRJ",
        pd_name: "B1+2CREASING",
        pd_design_type: "M-Manufacture",
        idc: "0201+2",
        product: "001",
        joint: "Yes",
        joint_to_print: "Yes",
        pcs_to_joint: "1",
        score: "Yes",
        slot: "No",
        flute_style: "N-Normal",
        print_flute: "No",
        input_weight: "Yes",
    },
    {
        pd_code: "B1-FB",
        pd_name: "REGULAR BOX FLUTE REVERSE",
        pd_design_type: "M-Manufacture",
        idc: "0201R",
        product: "001",
        joint: "No",
        joint_to_print: "No",
        pcs_to_joint: "1",
        score: "Yes",
        slot: "No",
        flute_style: "R-Reverse/Rotate",
        print_flute: "Yes",
        input_weight: "Yes",
    },
    {
        pd_code: "B1/B0",
        pd_name: "B1/B0 FLEP FLAP",
        pd_design_type: "M-Manufacture",
        idc: "0200T",
        product: "001",
        joint: "No",
        joint_to_print: "No",
        pcs_to_joint: "1",
        score: "Yes",
        slot: "No",
        flute_style: "N-Normal",
        print_flute: "No",
        input_weight: "Yes",
    },
    {
        pd_code: "B1/B0 4J",
        pd_name: "B1/B0 4 JOINT",
        pd_design_type: "M-Manufacture",
        idc: "0200+T",
        product: "001",
        joint: "Yes",
        joint_to_print: "Yes",
        pcs_to_joint: "4",
        score: "Yes",
        slot: "No",
        flute_style: "N-Normal",
        print_flute: "No",
        input_weight: "Yes",
    },
    {
        pd_code: "B1/B0 DJ",
        pd_name: "B1/B0 DOUBLE JOINT",
        pd_design_type: "M-Manufacture",
        idc: "0201+T",
        product: "001",
        joint: "Yes",
        joint_to_print: "Yes",
        pcs_to_joint: "2",
        score: "Yes",
        slot: "No",
        flute_style: "N-Normal",
        print_flute: "No",
        input_weight: "Yes",
    },
    {
        pd_code: "B1/B4",
        pd_name: "B1/B4",
        pd_design_type: "M-Manufacture",
        idc: "NA",
        product: "001",
        joint: "No",
        joint_to_print: "No",
        pcs_to_joint: "1",
        score: "Yes",
        slot: "No",
        flute_style: "N-Normal",
        print_flute: "No",
        input_weight: "Yes",
    },
]);

const onProductDesignSelected = (design) => {
    // Update the P/Design field in the form or wherever it's needed
    console.log("Product Design Selected:", design);
    // You can add logic here to update form fields based on the selected design
};

// Paper Flute data - will be fetched from API
const paperFlutes = ref([]);
const paperFluteLoading = ref(false);

// Fetch paper flutes from API
const fetchPaperFlutes = async () => {
    paperFluteLoading.value = true;
    try {
        const res = await fetch("/api/paper-flutes", {
            headers: {
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
        });

        if (!res.ok) {
            throw new Error("Network response was not ok");
        }

        const data = await res.json();
        if (Array.isArray(data)) {
            console.log("Paper Flutes data:", data.slice(0, 3)); // Debug: show first 3 items
            paperFlutes.value = data;
        } else {
            paperFlutes.value = [];
            console.error("Unexpected data format:", data);
        }
    } catch (e) {
        console.error("Error fetching paper flutes:", e);
        paperFlutes.value = [];
    } finally {
        paperFluteLoading.value = false;
    }
};

const onPaperFluteSelected = (flute) => {
    // Update the Flute field in the form or wherever it's needed
    console.log("Paper Flute Selected:", flute);
    // You can add logic here to update form fields based on the selected flute
};

// Fetch paper qualities from API
const fetchPaperQualities = async () => {
    paperQualityLoading.value = true;
    try {
        const res = await fetch("/api/paper-qualities", {
            headers: {
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
        });

        if (!res.ok) {
            throw new Error("Network response was not ok");
        }

        const data = await res.json();
        if (Array.isArray(data)) {
            console.log("Paper Qualities data:", data.slice(0, 3)); // Debug: show first 3 items
            paperQualities.value = data;
        } else {
            paperQualities.value = [];
            console.error("Unexpected data format:", data);
        }
    } catch (e) {
        console.error("Error fetching paper qualities:", e);
        paperQualities.value = [];
    } finally {
        paperQualityLoading.value = false;
    }
};

// Handle SO button click to open paper quality modal
const openPaperQualityModal = (soIndex) => {
    activeSoIndex.value = soIndex;
    activeWoIndex.value = null;
    showPaperQualityModal.value = true;
    fetchPaperQualities();
};

// Handle WO button click to open paper quality modal
const openWoPaperQualityModal = (woIndex) => {
    activeWoIndex.value = woIndex;
    activeSoIndex.value = null;
    showPaperQualityModal.value = true;
    fetchPaperQualities();
};

// Handle paper quality selection
const onPaperQualitySelected = (quality) => {
    if (!quality) return;
    selectedPaperQuality.value = quality;
    const value = quality.paper_quality || quality.paper_name || "";
    if (activeSoIndex.value !== null) {
        soValues.value[activeSoIndex.value] = value;
        console.log(
            `Paper Quality Selected for SO ${activeSoIndex.value + 1}:`,
            quality
        );
        console.log(
            `SO ${activeSoIndex.value + 1} updated to:`,
            soValues.value[activeSoIndex.value]
        );
    } else if (activeWoIndex.value !== null) {
        woValues.value[activeWoIndex.value] = value;
        console.log(
            `Paper Quality Selected for WO ${activeWoIndex.value + 1}:`,
            quality
        );
        console.log(
            `WO ${activeWoIndex.value + 1} updated to:`,
            woValues.value[activeWoIndex.value]
        );
    }
    showPaperQualityModal.value = false;
    activeSoIndex.value = null;
    activeWoIndex.value = null;
};

// Initial load
onMounted(() => {
    // Load paper flutes - not critical so don't need to block or wait
    try {
        const paperFlutesPromise = fetchPaperFlutes();
        if (
            paperFlutesPromise &&
            typeof paperFlutesPromise.catch === "function"
        ) {
            paperFlutesPromise.catch((error) => {
                console.error("Error loading paper flutes:", error);
            });
        }
    } catch (error) {
        console.error("Error starting paper flutes fetch:", error);
    }

    // Preload customer accounts for better UX - using then/catch instead of async/await
    // to match the pattern in obsolete-reactive-customer-ac.vue
    loadCustomerAccounts()
        .then(() => {
            console.log("Initial customer accounts loaded successfully");
        })
        .catch((error) => {
            console.error("Error loading initial customer data:", error);
            // Still continue with app initialization
        })
        .finally(() => {
            // Initialize with welcome toast - show this regardless of success/failure
            toast.info(
                "Welcome to Master Card Update. Select a customer account to begin."
            );
        });
});

const handleMcsInput = () => {
    // Skip if this is a programmatic update
    if (isProgrammaticUpdate.value) {
        return;
    }

    // Reset record mode when user manually changes MCS input
    if (form.value.mcs && !selectedMcs.value) {
        recordMode.value = "new";
        form.value.mc_approval = "No";
        // Show detailed MC info when user enters MCS number
        showDetailedMcInfo.value = true;
    } else if (!form.value.mcs) {
        // Hide detailed MC info when MCS input is cleared
        showDetailedMcInfo.value = false;
        recordMode.value = "new";
    }
};

const handleAcInput = () => {
    // Skip if this is a programmatic update
    if (isProgrammaticUpdate.value) {
        return;
    }

    // Reset MCS data when AC# is manually changed
    if (!form.value.ac || form.value.ac !== (selectedCustomer.value?.customer_code || "")) {
        selectedMcs.value = null;
        mcsMasterCards.value = [];
        form.value.mcs = "";
        form.value.customer_name = "";
        showDetailedMcInfo.value = false;
        recordMode.value = "new";

        // Reset MC details
        mcDetails.value = {
            ac_name: "",
            mc_model: "",
            mc_short_model: "",
            mc_status: "Active",
            mc_approval: "No",
            last_mcs: "",
            last_updated_seq: "",
            ext_dim_1: "",
            ext_dim_2: "",
            ext_dim_3: "",
            int_dim_1: "",
            int_dim_2: "",
            int_dim_3: "",
        };
    }
};

const addNewRecord = () => {
    recordMode.value = "new";
    form.value.mc_approval = "No";
    recordSelected.value = true;
    showDetailedMcInfo.value = true; // Show detailed MC info for new record
};

const searchAc = async () => {
    try {
        const response = await axios.post("/api/update-mc/search-ac", {
            ac: form.value.ac,
        });
        console.log(response.data);
        mcDetails.value.ac_name = "Sample AC Name from API";
        recordSelected.value = true;
    } catch (error) {
        console.error("Error searching AC:", error);
    }
};

const searchMcs = () => {
    // Validate customer account must be selected first
    if (!form.value.ac) {
        toast.error(
            "Please select Customer Account (AC#) first before searching Master Cards"
        );
        openCustomerAccountModal();
        return;
    }

    showMcsTableModal.value = true;
    fetchMcsData();
};

const selectRecord = () => {
    console.log("Record select clicked");
    recordSelected.value = true;
    isProgrammaticUpdate.value = true; // Prevent input handlers from clearing data
    form.value.customer_name = "Selected Customer";
    isProgrammaticUpdate.value = false; // Re-enable input handlers
};

const saveRecord = () => {
    console.log("Save record clicked");
    if (!recordSelected.value) {
        alert("No record selected to save");
        return;
    }

    console.log(`Saving record: ${JSON.stringify(form)}`);
};

const deleteRecord = () => {
    console.log("Delete record clicked");
    if (!recordSelected.value) {
        alert("No record selected to delete");
        return;
    }

    if (confirm("Are you sure you want to delete this record?")) {
        console.log(`Deleting record: ${form.value.mcs}`);
        isProgrammaticUpdate.value = true; // Prevent input handlers from clearing data
        form.value.ac = "";
        form.value.mcs = "";
        form.value.customer_name = "";
        form.value.mc_model = "";
        form.value.mc_short_model = "";
        form.value.mc_status = "Active";
        form.value.mc_approval = "No";
        isProgrammaticUpdate.value = false; // Re-enable input handlers
        recordSelected.value = false;
        showDetailedMcInfo.value = false;
    }
};

const printRecord = () => {
    console.log("Print record clicked");
    if (!recordSelected.value) {
        alert("No record selected to print");
        return;
    }

    console.log(`Printing record: ${form.value.mcs}`);
};

const closeRecord = () => {
    console.log("Close record clicked");
};

// This function is now removed as it's a duplicate of the async version above

const applyFilter = () => {
    showCustomerAccountTable.value = true;
};

// This duplicate has been removed as we're using the first implementation

const selectMcs = async (mcs) => {
    if (!mcs) return;

    // Show processing state
    isProcessing.value = true;

    selectedMcs.value = mcs;
    recordMode.value = "existing";

    // Extract values to make sure we're consistent
    const mcsSeq = mcs.seq || mcs.mc_seq || "";
    const mcModel = mcs.model || mcs.mc_model || "";
    const mcShortModel = mcs.short_model || mcs.mc_short_model || "";
    const mcStatus = mcs.status || "Active";
    const mcApproval = mcs.approval === "No" ? "No" : "Yes";

    // Populate form fields (UI values)
    isProgrammaticUpdate.value = true; // Prevent input handlers from clearing data
    
    // Use nextTick to ensure DOM updates
    await nextTick();
    form.value.mcs = mcsSeq;
    form.value.mc_model = mcModel;
    form.value.mc_short_model = mcShortModel;
    form.value.mc_status = mcStatus;
    form.value.mc_approval = mcApproval;
    
    // Force reactivity update
    await nextTick();
    isProgrammaticUpdate.value = false; // Re-enable input handlers
    
    console.log('Master Card selected:', {
        mcs: form.value.mcs,
        mc_model: form.value.mc_model,
        mc_short_model: form.value.mc_short_model,
        selectedMcs: mcs
    });

    // Update MC details in sync with form
    mcDetails.value = {
        ac_name: form.value.customer_name,
        mc_model: mcModel,
        mc_short_model: mcShortModel,
        mc_status: mcStatus,
        mc_approval: mcApproval,
        last_mcs: mcs.last_mcs || mcsSeq,
        last_updated_seq: mcs.last_updated_seq || mcsSeq,
        ext_dim_1: mcs.ext_dim_1 || "",
        ext_dim_2: mcs.ext_dim_2 || "",
        ext_dim_3: mcs.ext_dim_3 || "",
        int_dim_1: mcs.int_dim_1 || "",
        int_dim_2: mcs.int_dim_2 || "",
        int_dim_3: mcs.int_dim_3 || "",
    };

    // Fetch full MC with details + PD setup to hydrate modals
    try {
        const res = await fetch(`/api/update-mc/master-cards/${encodeURIComponent(mcsSeq)}?customer_code=${encodeURIComponent(form.value.ac)}`, {
            headers: { 'Accept': 'application/json' },
            credentials: 'same-origin'
        });
        if (res.ok) {
            const full = await res.json();
            // Keep a local copy to pass into child modals
            selectedMcsFull.value = full;
            // Hydrate SO/WO values from saved pd_setup if available
            const pd = full?.pd_setup || {};
            if (Array.isArray(pd.soValues)) {
                soValues.value = pd.soValues;
            }
            if (Array.isArray(pd.woValues)) {
                woValues.value = pd.woValues;
            }
        }
    } catch (e) {}

    // Show confirmation toast
    toast.success(`Selected Master Card: ${mcModel}`);

    // Show detailed MC info after selection
    showDetailedMcInfo.value = true;
    recordSelected.value = true;
    showMcsTableModal.value = false;

    // Add highlight animation for better UX
    setTimeout(() => {
        highlightSelectedFields();
        isProcessing.value = false;
    }, 300);
};

const fetchMcsData = async (page = 1) => {
    mcsLoading.value = true;
    mcsError.value = null;

    // Validate customer account must exist before fetching data
    if (!form.value.ac) {
        mcsError.value = "Please select Customer Account (AC#) first.";
        mcsMasterCards.value = [];
        mcsLoading.value = false;

        // Auto-open customer account modal
        openCustomerAccountModal();
        return;
    }

    try {
        // Build query parameters
        let statusQuery = "";
        if (mcsStatusFilter.value === "Act") {
            statusQuery = "&status[]=Act";
        } else if (mcsStatusFilter.value === "Obsolete") {
            statusQuery = "&status[]=Obsolete";
        }

        // Filter by customer account - REQUIRED
        const customerFilter = `&customer_code=${encodeURIComponent(form.value.ac)}`;

        // Make API call using fetch for more control
        const response = await fetch(
            `/api/update-mc/master-cards?page=${page}&query=${encodeURIComponent(
                mcsSearchTerm.value
            )}&sortBy=${mcsSortOption.value}&sortOrder=${
                mcsSortOrder.value
            }${statusQuery}${customerFilter}`,
            {
                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
                credentials: "same-origin",
            }
        );

        if (!response.ok) {
            throw new Error(
                `Server returned ${response.status}: ${response.statusText}`
            );
        }

        const responseData = await response.json();
        console.log("MC data response:", responseData);

        // Process response - handle different response formats
        if (responseData.data && Array.isArray(responseData.data)) {
            mcsMasterCards.value = responseData.data;
            mcsCurrentPage.value = responseData.current_page || 1;
            mcsLastPage.value = responseData.last_page || 1;
        } else if (Array.isArray(responseData)) {
            mcsMasterCards.value = responseData;
            mcsCurrentPage.value = 1;
            mcsLastPage.value = 1;
        } else {
            mcsMasterCards.value = [];
            console.error("Unexpected MC data format:", responseData);
        }

        // Set UI feedback if no data found
        if (mcsMasterCards.value.length === 0) {
            if (mcsSearchTerm.value) {
                mcsError.value = `No Master Cards found matching "${mcsSearchTerm.value}" for Customer: ${form.value.customer_name}`;
            } else {
                mcsError.value = `No Master Cards found for Customer: ${form.value.customer_name} (${form.value.ac})`;
            }

            // If no master cards, inform user they can create a new one
            toast.info(
                "No existing Master Cards found. You can create a new one."
            );
        } else {
            toast.success(
                `Found ${mcsMasterCards.value.length} master card(s) for ${form.value.customer_name}`
            );
        }
    } catch (error) {
        console.error("Error fetching MCS data:", error);
        mcsError.value = `Failed to load master cards: ${error.message}`;
        mcsMasterCards.value = [];
        toast.error(`Failed to load master cards: ${error.message}`);
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
    console.log("Proceed button clicked");

    if (!form.value.ac) {
        toast.error("Please select customer account (AC#) first");
        openCustomerAccountModal();
        return;
    }

    if (!form.value.mcs) {
        toast.error("Please enter MCS# to proceed");
        return;
    }

    // Show loading states
    isLoading.value = true;
    isProcessing.value = true;
    const loadingToast = toast.loading("Processing master card data...");

    // Check if MCS already exists for this specific customer
    try {
        // Use fetch instead of axios for better error handling
        const response = await fetch(
            `/api/update-mc/check-mcs/${
                form.value.mcs
            }?customer_code=${encodeURIComponent(form.value.ac)}`,
            {
                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
                credentials: "same-origin",
            }
        );

        if (!response.ok) {
            throw new Error(
                `Server returned ${response.status}: ${response.statusText}`
            );
        }

        const responseData = await response.json();
        console.log("MC check response:", responseData);

        if (responseData.exists) {
            // Existing MC - load data and set as approved
            const existingMc = responseData.data;

            // Validate if this MC belongs to the selected customer
            if (!existingMc || existingMc.customer_code !== form.value.ac) {
                toast.error(
                    `MCS# ${form.value.mcs} exists but belongs to a different customer. Please check your data.`
                );
                toast.dismiss(loadingToast);
                return;
            }

            recordMode.value = "existing";

            // Extract values from existing MC data
            const mcModel = existingMc.mc_model || "";
            const mcShortModel = existingMc.mc_short_model || "";
            const mcStatus = existingMc.status || "Active";

            // Update form with existing MC data - UI values
            isProgrammaticUpdate.value = true; // Prevent input handlers from clearing data
            form.value.mc_model = mcModel;
            form.value.mc_short_model = mcShortModel;
            form.value.mc_status = mcStatus;
            form.value.mc_approval = "Yes";
            isProgrammaticUpdate.value = false; // Re-enable input handlers

            // Update MC details - synchronized
            mcDetails.value = {
                ac_name: form.value.customer_name,
                mc_model: mcModel,
                mc_short_model: mcShortModel,
                mc_status: mcStatus,
                mc_approval: "Yes",
                last_mcs: existingMc.mc_seq || form.value.mcs,
                last_updated_seq: existingMc.mc_seq || form.value.mcs,
                ext_dim_1: existingMc.ext_dim_1 || "",
                ext_dim_2: existingMc.ext_dim_2 || "",
                ext_dim_3: existingMc.ext_dim_3 || "",
                int_dim_1: existingMc.int_dim_1 || "",
                int_dim_2: existingMc.int_dim_2 || "",
                int_dim_3: existingMc.int_dim_3 || "",
            };

            toast.dismiss(loadingToast);
            toast.success("Existing master card loaded successfully");
        } else {
            // New MC - set as not approved
            recordMode.value = "new";

            // Update form values first (UI)
            isProgrammaticUpdate.value = true; // Prevent input handlers from clearing data
            form.value.mc_model = ""; // Clear any previous values
            form.value.mc_short_model = "";
            form.value.mc_approval = "No";
            form.value.mc_status = "Active";
            isProgrammaticUpdate.value = false; // Re-enable input handlers

            // Update MC details for new record (keep in sync)
            mcDetails.value = {
                ac_name: form.value.customer_name,
                mc_model: "",
                mc_short_model: "",
                mc_status: "Active",
                mc_approval: "No",
                last_mcs: "",
                last_updated_seq: "",
                ext_dim_1: "",
                ext_dim_2: "",
                ext_dim_3: "",
                int_dim_1: "",
                int_dim_2: "",
                int_dim_3: "",
            };

            toast.dismiss(loadingToast);
            toast.success("Ready to create new master card");
        }

        showDetailedMcInfo.value = true;
        recordSelected.value = true;

        // Clear loading states after successful operation
        setTimeout(() => {
            isLoading.value = false;
            isProcessing.value = false;

            // Highlight the updated fields
            highlightSelectedFields();
        }, 500); // Small delay for visual feedback
    } catch (error) {
        console.error("Error checking MCS:", error);
        toast.dismiss(loadingToast);
        toast.error(
            `Error checking master card data: ${error.message}. Creating new record instead.`
        );

        // Fallback to treating as new MC
        recordMode.value = "new";

        // Update form values (UI) - explicitly set each field
        isProgrammaticUpdate.value = true; // Prevent input handlers from clearing data
        form.value.mc_model = ""; // Clear any previous values
        form.value.mc_short_model = "";
        form.value.mc_approval = "No";
        form.value.mc_status = "Active";
        isProgrammaticUpdate.value = false; // Re-enable input handlers

        // Clear loading states
        isLoading.value = false;
        isProcessing.value = false;

        // Update MC details (keep in sync)
        mcDetails.value = {
            ac_name: form.value.customer_name,
            mc_model: "",
            mc_short_model: "",
            mc_status: "Active",
            mc_approval: "No",
            last_mcs: "",
            last_updated_seq: "",
            ext_dim_1: "",
            ext_dim_2: "",
            ext_dim_3: "",
            int_dim_1: "",
            int_dim_2: "",
            int_dim_3: "",
        };

        showDetailedMcInfo.value = true;
        recordSelected.value = true;
    }
};

const handleNextSetup = () => {
    // Validate MC Model is filled for new records
    if (recordMode.value === "new" && !form.value.mc_model.trim()) {
        showErrorModal.value = true;
        return;
    }

    // Show Setup MC Component modal
    showSetupMcModal.value = true;
};

const selectComponent = (component, index) => {
    // Reset all selections
    mcComponents.value.forEach((comp) => (comp.selected = false));
    // Select the clicked component
    component.selected = true;
};

const setupPD = () => {
    console.log("Setup PD clicked");
    // Ensure PD modal opens fresh when creating a new MC
    if (recordMode.value === 'new') {
        selectedMcsFull.value = null;
    }
    showSetupPdModal.value = true;
};

const setupOthers = () => {
    console.log("Setup Others clicked");
    // Implementation for Setup Others functionality
};

const handleZoomChange = () => {
    if (!selectedMcs.value) {
        alert("Please select a Master Card first.");
        zoomOption.value = "";
        return;
    }
    switch (zoomOption.value) {
        case "mc_specification":
            showMasterCardSpecModal.value = true;
            break;
        case "current_price":
            showMasterCardCurrentPriceModal.value = true;
            break;
        case "stand_by_price":
            showMasterCardStandByPriceModal.value = true;
            break;
    }
    zoomOption.value = "";
};

const handleSecondPasswordSelect = ({ userId, password }) => {
    console.log("Second password access granted for:", userId);
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
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes modalScaleIn {
    from {
        transform: scale(0.95);
        opacity: 0;
    }
    to {
        transform: scale(1);
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
    }
    100% {
        transform: scale(2.2);
        opacity: 0;
    }
}

.animate-scaleIn {
    animation: scaleIn 0.3s ease-in-out;
}

.animate-fadeIn {
    animation: fadeIn 0.3s ease-in-out;
}

.animate-slideInRight {
    animation: slideInRight 0.5s ease-out;
}

.animate-slideInLeft {
    animation: slideInLeft 0.5s ease-out;
}

.animate-slideInUp {
    animation: slideInUp 0.5s ease-out;
}

.animate-bounce-gentle {
    animation: bounceGentle 1s ease infinite;
}

.animate-float {
    animation: float 3s ease-in-out infinite;
}

.animation-delay-100 {
    animation-delay: 0.1s;
}

.animation-delay-200 {
    animation-delay: 0.2s;
}

.animation-delay-300 {
    animation-delay: 0.3s;
}

.animation-delay-500 {
    animation-delay: 0.5s;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes scaleIn {
    from {
        transform: scale(0.95);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

@keyframes slideInRight {
    from {
        transform: translateX(30px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideInLeft {
    from {
        transform: translateX(-30px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideInUp {
    from {
        transform: translateY(20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes bounceGentle {
    0%,
    100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-5px);
    }
}

@keyframes float {
    0% {
        transform: translateY(0px) rotate(0deg);
    }
    50% {
        transform: translateY(-6px) rotate(2deg);
    }
    100% {
        transform: translateY(0px) rotate(0deg);
    }
}

@keyframes highlight-pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.4);
        border-color: #6366f1;
        background-color: rgba(79, 70, 229, 0.05);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(79, 70, 229, 0);
        border-color: #818cf8;
        background-color: rgba(79, 70, 229, 0.1);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(79, 70, 229, 0);
        border-color: #d1d5db;
        background-color: transparent;
    }
}

.highlight-field {
    animation: highlight-pulse 1.5s ease;
}

/* Enhanced input fields */
.form-input {
    transition: all 0.3s ease;
}

.form-input:focus {
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.3);
    border-color: #6366f1;
}

.form-input.filled {
    border-left: 4px solid #10b981;
}

.form-input.empty {
    border-left: 4px solid #f59e0b;
}

@keyframes scaleIn {
    from {
        transform: scale(0.95);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

.animation-delay-500 {
    animation-delay: 0.5s;
}

.animate-pulse-slow {
    animation: pulse-slow 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse-slow {
    0%,
    100% {
        opacity: 0.5;
    }
    50% {
        opacity: 0.2;
    }
}

.text-shadow {
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
}

/* Hover effects */
.group:hover .group-hover\:text-indigo-600 {
    color: #4f46e5;
}

.group:hover .group-hover\:text-purple-600 {
    color: #9333ea;
}

.group:hover .group-hover\:text-blue-600 {
    color: #2563eb;
}

.group:hover .group-hover\:opacity-20 {
    opacity: 0.2;
}

@keyframes pulse-fade {
    0% {
        opacity: 0.5;
    }
    50% {
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
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
.animate-spin-slow {
    animation: spin-slow 2.5s linear infinite;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}
.animate-fadeIn {
    animation: fadeIn 0.25s ease-out;
}
</style>
