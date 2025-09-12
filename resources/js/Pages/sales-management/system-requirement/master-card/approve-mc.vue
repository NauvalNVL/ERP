<!-- 
  Approve Master Card Component
  This component handles the approval of master cards in the system
-->
<template>
    <AppLayout :header="'Approve MC'">
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
                    <i class="fas fa-check-circle text-white text-2xl z-10"></i>
                </div>
                <div>
                    <h2
                        class="text-2xl md:text-3xl font-bold text-white mb-1 text-shadow"
                    >
                        Approve Master Card
                    </h2>
                    <p class="text-blue-100 max-w-2xl">
                        Review and approve master cards submitted by users
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
                                Select Master Card
                            </h3>
                        </div>

                        <!-- Form content -->
                        <div class="space-y-6">
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
                                        Customer A/C#:
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
                                            v-model="searchTerm"
                                            class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                                        />
                                        <button 
                                            type="button"
                                            @click="openCustomerAccountModal"
                                            class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-200 text-gray-700 rounded-r-md"
                                        >
                                            <i class="fas fa-table"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="flex items-end"></div>
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
                                    M/Card Seq#:
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
                                            v-model="mcsInput"
                                            class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                                        />
                                        <button 
                                            type="button"
                                            @click="openMcsModal"
                                            class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-200 text-gray-700 rounded-r-md"
                                        >
                                            <i class="fas fa-table"></i>
                                        </button>
                                    </div>
                                    </div>
                                    
                                <div class="flex items-end">
                                    <!-- Empty space to maintain grid alignment -->
                                </div>
                            </div>

                            <!-- Minimal footer omitted to match legacy UI -->
                        </div>
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
                                                class="fas fa-search text-white text-xs"
                                            ></i>
                                        </span>
                                        <span
                                            >Enter AC# or MCS# to search for
                                            existing records</span
                                        >
                                    </li>
                                    <li class="flex items-start">
                                        <span
                                            class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full mr-2 shadow-sm mt-0.5"
                                        >
                                            <i
                                                class="fas fa-mouse-pointer text-white text-xs"
                                            ></i>
                                        </span>
                                        <span
                                            >Click on a record to view details
                                            and approve/reject</span
                                        >
                                    </li>
                                    <li class="flex items-start">
                                        <span
                                            class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-green-400 to-emerald-400 rounded-full mr-2 shadow-sm mt-0.5"
                                        >
                                            <i
                                                class="fas fa-check-circle text-white text-xs"
                                            ></i>
                                        </span>
                                        <span
                                            >Use the action buttons to approve
                                            or reject master cards</span
                                        >
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Selected Master Card Detail -->
                        <div v-if="selectedMasterCard" class="mt-6">
                            <div
                                class="p-4 bg-indigo-50 rounded-lg border border-indigo-100"
                            >
                                <h4
                                    class="text-sm font-semibold text-indigo-800 uppercase tracking-wider mb-3 flex items-center"
                                >
                                    <span
                                        class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full mr-2 shadow-sm"
                                    >
                                        <i
                                            class="fas fa-id-card text-white text-xs"
                                        ></i>
                                    </span>
                                    Selected Master Card
                                </h4>
                                
                                <div class="flex items-center mb-3">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-indigo-500 to-purple-600 text-white rounded-lg flex items-center justify-center mr-3 shadow-md"
                                    >
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <div>
                                        <h5
                                            class="text-sm font-medium text-gray-900"
                                        >
                                            {{ selectedMasterCard.mc_model }}
                                        </h5>
                                        <p
                                            class="text-xs text-indigo-600 font-mono"
                                        >
                                            {{ selectedMasterCard.mc_seq }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="space-y-2 text-sm">
                                    <div
                                        class="flex justify-between items-center"
                                    >
                                        <span class="text-gray-500"
                                            >Customer:</span
                                        >
                                        <span
                                            class="font-medium text-gray-900"
                                            >{{
                                                selectedMasterCard.customer_name
                                            }}</span
                                        >
                                    </div>
                                    <div
                                        class="flex justify-between items-center"
                                    >
                                        <span class="text-gray-500"
                                            >Status:</span
                                        >
                                        <span 
                                            class="px-2 py-1 rounded-full text-xs font-medium"
                                            :class="{
                                                'bg-gradient-to-r from-green-400 to-green-500 text-white':
                                                    selectedMasterCard.status ===
                                                    'active',
                                                'bg-gradient-to-r from-yellow-400 to-yellow-500 text-white':
                                                    selectedMasterCard.status ===
                                                    'pending',
                                                'bg-gradient-to-r from-red-400 to-red-500 text-white':
                                                    selectedMasterCard.status ===
                                                    'obsolete',
                                            }"
                                        >
                                            {{
                                                selectedMasterCard.status
                                                    .charAt(0)
                                                    .toUpperCase() +
                                                selectedMasterCard.status.slice(
                                                    1
                                                )
                                            }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="mt-4 grid grid-cols-2 gap-2">
                                    <button 
                                        @click="
                                            handleApprove(selectedMasterCard)
                                        "
                                        class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-3 py-1.5 rounded-lg flex items-center justify-center space-x-1 transform active:translate-y-px transition-all duration-300 shadow-md text-sm"
                                    >
                                        <i class="fas fa-check text-xs"></i>
                                        <span>Approve</span>
                                    </button>
                                    <button 
                                        @click="
                                            handleReject(selectedMasterCard)
                                        "
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
                            <h4
                                class="text-sm font-semibold text-gray-800 uppercase tracking-wider mb-3 flex items-center"
                            >
                                <span
                                    class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-full mr-2 shadow-sm"
                                >
                                    <i
                                        class="fas fa-filter text-white text-xs"
                                    ></i>
                                </span>
                                Filter Options
                            </h4>
                            
                            <div class="space-y-3">
                                <div>
                                    <label
                                        class="text-xs font-medium text-gray-700 block mb-1"
                                        >Status:</label
                                    >
                                    <div class="flex space-x-2">
                                        <label
                                            class="inline-flex items-center px-2 py-1 bg-gray-50 border border-gray-200 rounded-md text-xs cursor-pointer hover:bg-white transition-colors"
                                        >
                                            <input
                                                type="radio"
                                                name="recordStatus"
                                                value="all"
                                                v-model="recordStatus"
                                                class="h-3 w-3 text-blue-600 mr-1"
                                            />
                                            <span>All</span>
                                        </label>
                                        <label
                                            class="inline-flex items-center px-2 py-1 bg-green-50 border border-green-200 rounded-md text-xs cursor-pointer hover:bg-white transition-colors"
                                        >
                                            <input
                                                type="radio"
                                                name="recordStatus"
                                                value="active"
                                                v-model="recordStatus"
                                                class="h-3 w-3 text-green-600 mr-1"
                                            />
                                            <span>Active</span>
                                        </label>
                                        <label
                                            class="inline-flex items-center px-2 py-1 bg-yellow-50 border border-yellow-200 rounded-md text-xs cursor-pointer hover:bg-white transition-colors"
                                        >
                                            <input
                                                type="radio"
                                                name="recordStatus"
                                                value="pending"
                                                v-model="recordStatus"
                                                class="h-3 w-3 text-yellow-600 mr-1"
                                            />
                                            <span>Pending</span>
                                        </label>
                                    </div>
                                </div>
                                
                                <div>
                                    <label
                                        class="text-xs font-medium text-gray-700 block mb-1"
                                        >Sort by:</label
                                    >
                                    <select 
                                        v-model="sortBy" 
                                        class="block w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                    >
                                        <option value="seq">MC Sequence</option>
                                        <option value="model">MC Model</option>
                                        <option value="customer">
                                            Customer
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Table & Log Panel (Layout Rapi) -->
        <div
            v-if="showMainPanel"
            class="rounded-xl shadow-2xl p-6 mt-4 bg-gradient-to-br from-blue-50 via-cyan-50 to-purple-100 border-2 border-indigo-200"
        >
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                <!-- Left: Table & Search -->
                <div class="lg:col-span-2 flex flex-col gap-4">
                    <div
                        class="flex flex-col md:flex-row md:items-center gap-3 mb-2"
                    >
                        <label
                            class="font-bold text-lg text-indigo-800 flex-shrink-0"
                            >AC#:</label
                        >
                        <input
                            type="text"
                            :value="searchTerm"
                            readonly
                            class="border-2 border-indigo-200 rounded-lg px-3 py-1 w-40 bg-white font-mono font-bold text-indigo-700 shadow-sm"
                        />
                        <input
                            type="text"
                            :value="
                                selectedCustomer?.name ||
                                selectedCustomer?.customer_name ||
                                ''
                            "
                            readonly
                            class="border-2 border-indigo-200 rounded-lg px-3 py-1 flex-1 bg-white font-semibold text-gray-700 shadow-sm"
                        />
                    </div>

                    <!-- Customer Info Alert -->
                    <div
                        v-if="loadingCustomers"
                        class="mb-4 bg-yellow-100 p-3 rounded-lg border border-yellow-200 flex items-center"
                    >
                        <div
                            class="mr-3 animate-spin rounded-full h-6 w-6 border-b-2 border-yellow-700"
                        ></div>
                        <p class="text-sm font-medium text-yellow-800">
                            Loading customer data...
                        </p>
                    </div>
                    <div
                        v-else-if="!selectedCustomer"
                        class="mb-4 bg-blue-50 p-3 rounded-lg border border-blue-200"
                    >
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-500 rounded-full mr-3">
                                <i
                                    class="fas fa-info-circle text-white text-sm"
                                ></i>
                            </div>
                            <div>
                                <p class="font-medium text-blue-900">
                                    Select Customer Account
                                </p>
                                <p class="text-xs text-blue-700">
                                    Please search and select a customer account
                                    to view master cards
                                </p>
                            </div>
                        </div>
                    </div>
                    <div
                        v-else
                        class="mb-4 bg-green-50 p-3 rounded-lg border border-green-200"
                    >
                        <div class="flex items-center">
                            <div class="p-2 bg-green-500 rounded-full mr-3">
                                <i
                                    class="fas fa-user-check text-white text-sm"
                                ></i>
                            </div>
                            <div>
                                <p class="font-medium text-green-900">
                                    Customer Selected:
                                    {{
                                        selectedCustomer.name ||
                                        selectedCustomer.customer_name
                                    }}
                                </p>
                                <p class="text-xs text-green-700">
                                    Code:
                                    {{
                                        selectedCustomer.code ||
                                        selectedCustomer.customer_code
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div
                        class="rounded-xl overflow-hidden shadow-lg border-2 border-cyan-200 bg-gradient-to-br from-white via-blue-50 to-cyan-100 flex-1 flex flex-col min-h-[200px]"
                    >
                        <table class="min-w-full text-sm">
                            <thead
                                class="bg-gradient-to-r from-cyan-600 via-blue-600 to-indigo-600 text-white"
                            >
                                <tr>
                                    <th
                                        class="px-3 py-2 border-r border-cyan-100 font-bold"
                                    >
                                        MCS#
                                    </th>
                                    <th
                                        class="px-3 py-2 border-r border-cyan-100 font-bold"
                                    >
                                        Model
                                    </th>
                                    <th
                                        class="px-3 py-2 border-r border-cyan-100 font-bold"
                                    >
                                        Sts
                                    </th>
                                    <th class="px-3 py-2 font-bold">
                                        To Approve
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="mc in filteredMasterCards"
                                    :key="mc.mc_seq"
                                    class="hover:bg-gradient-to-r hover:from-blue-100 hover:to-cyan-100 transition-all"
                                >
                                    <td
                                        class="border-r border-cyan-100 px-3 py-2"
                                    >
                                        {{ mc.mc_seq }}
                                    </td>
                                    <td
                                        class="border-r border-cyan-100 px-3 py-2"
                                    >
                                        {{ mc.mc_model }}
                                    </td>
                                    <td
                                        class="border-r border-cyan-100 px-3 py-2"
                                    >
                                        <span
                                            :class="{
                                                'bg-gradient-to-r from-green-400 to-emerald-500 text-white px-2 py-1 rounded-full text-xs font-bold':
                                                    mc.status === 'Active',
                                                'bg-gradient-to-r from-rose-400 to-red-500 text-white px-2 py-1 rounded-full text-xs font-bold':
                                                    mc.status === 'Obsolete',
                                                'bg-gradient-to-r from-gray-400 to-gray-500 text-white px-2 py-1 rounded-full text-xs font-bold':
                                                    mc.status !== 'Active' &&
                                                    mc.status !== 'Obsolete',
                                            }"
                                            >{{ mc.status }}</span
                                        >
                                    </td>
                                    <td class="text-center px-3 py-2">
                                        <input
                                            type="checkbox"
                                            class="accent-indigo-500 scale-125"
                                        />
                                    </td>
                                </tr>
                                <tr v-if="filteredMasterCards.length === 0">
                                    <td
                                        colspan="4"
                                        class="text-center text-gray-400 py-8"
                                    >
                                        No data found
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div
                        class="flex flex-col md:flex-row md:items-center gap-3 mt-2 justify-between"
                    >
                        <div class="flex items-center gap-2">
                            <label class="font-semibold text-gray-700"
                                >Search:</label
                            >
                            <input
                                type="text"
                                class="border-2 border-cyan-200 rounded-lg px-3 py-1 w-40 bg-white shadow-sm"
                                style="max-width: 180px"
                            />
                            <button
                                class="bg-gradient-to-r from-blue-400 to-cyan-500 text-white font-bold px-4 py-1 rounded-lg shadow hover:from-blue-500 hover:to-cyan-600 transition"
                            >
                                Search
                            </button>
                            <span class="ml-2 font-semibold text-gray-700"
                                >MCS#</span
                            >
                        </div>
                        <button
                            class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-bold px-4 py-1 rounded-lg shadow hover:from-indigo-600 hover:to-purple-700 transition"
                        >
                            Zoom MC
                        </button>
                    </div>
                </div>
                <!-- Right: Log Cards -->
                <div class="flex flex-col gap-6 w-full">
                    <!-- Log Card: Maintenance -->
                    <div
                        class="rounded-xl border-2 border-green-200 bg-gradient-to-br from-green-50 via-white to-emerald-100 p-4 shadow-md"
                    >
                        <div
                            class="font-bold text-base text-green-800 mb-2 flex items-center gap-2"
                        >
                            <i class="fas fa-tools"></i> Last Maintenance Log
                        </div>
                        <div
                            class="grid grid-cols-2 gap-x-4 gap-y-2 items-center mb-1"
                        >
                            <label class="text-sm font-medium text-green-900"
                                >Process:</label
                            >
                            <input
                                type="text"
                                class="border border-green-200 rounded px-2 py-1 text-sm bg-white"
                            />
                            <label class="text-sm font-medium text-green-900"
                                >User ID:</label
                            >
                            <input
                                type="text"
                                class="border border-green-200 rounded px-2 py-1 text-sm bg-white"
                            />
                            <label class="text-sm font-medium text-green-900"
                                >Date:</label
                            >
                            <input
                                type="text"
                                class="border border-green-200 rounded px-2 py-1 text-sm bg-white"
                            />
                            <label class="text-sm font-medium text-green-900"
                                >Time:</label
                            >
                            <input
                                type="text"
                                class="border border-green-200 rounded px-2 py-1 text-sm bg-white"
                            />
                        </div>
                    </div>
                    <!-- Log Card: Approval -->
                    <div
                        class="rounded-xl border-2 border-blue-200 bg-gradient-to-br from-blue-50 via-white to-cyan-100 p-4 shadow-md mt-6"
                    >
                        <div
                            class="font-bold text-base text-blue-800 mb-2 flex items-center gap-2"
                        >
                            <i class="fas fa-user-check"></i> Last Approval Log
                        </div>
                        <div
                            class="grid grid-cols-2 gap-x-4 gap-y-2 items-center mb-1"
                        >
                            <label class="text-sm font-medium text-blue-900"
                                >User ID:</label
                            >
                            <input
                                type="text"
                                class="border border-blue-200 rounded px-2 py-1 text-sm bg-white"
                            />
                            <label class="text-sm font-medium text-blue-900"
                                >Date:</label
                            >
                            <input
                                type="text"
                                class="border border-blue-200 rounded px-2 py-1 text-sm bg-white"
                            />
                            <label class="text-sm font-medium text-blue-900"
                                >Time:</label
                            >
                            <input
                                type="text"
                                class="border border-blue-200 rounded px-2 py-1 text-sm bg-white"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approval Confirmation Modal -->
        <div
            v-if="showApprovalModal"
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title"
            role="dialog"
            aria-modal="true"
        >
            <div
                class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
            >
                <div
                    class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
                    aria-hidden="true"
                    @click="showApprovalModal = false"
                ></div>
                <span
                    class="hidden sm:inline-block sm:align-middle sm:h-screen"
                    aria-hidden="true"
                    >&#8203;</span
                >
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full animate-modalScaleIn"
                >
                    <!-- Modal Header with gradient -->
                    <div
                        class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4 relative overflow-hidden"
                    >
                        <div
                            class="absolute -top-8 -left-8 w-16 h-16 bg-white opacity-10 rounded-full"
                        ></div>
                        <div
                            class="absolute -bottom-8 -right-8 w-16 h-16 bg-white opacity-10 rounded-full"
                        ></div>
                        
                        <div class="flex items-center">
                            <div
                                class="bg-white bg-opacity-20 p-2 rounded-full mr-3"
                            >
                                <i class="fas fa-check text-white text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white">
                                Approve Master Card
                            </h3>
                        </div>
                    </div>
                    
                    <div class="bg-white px-6 py-5">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="bg-gradient-to-br from-green-400 to-green-500 p-3 rounded-lg shadow-md flex-shrink-0 mr-4"
                            >
                                <i
                                    class="fas fa-file-alt text-white text-lg"
                                ></i>
                            </div>
                            <div>
                                <p class="text-gray-600 mb-3">
                                    Are you sure you want to approve this master
                                    card? This action cannot be undone.
                                </p>
                                <div
                                    class="bg-green-50 border border-green-200 rounded-lg p-3 mb-3"
                                >
                                    <div class="flex items-center mb-1">
                                        <i
                                            class="fas fa-info-circle text-green-600 mr-2"
                                        ></i>
                                        <span class="font-medium text-green-900"
                                            >Master Card Details</span
                                        >
                                    </div>
                                    <p class="text-sm text-green-800">
                                        <span class="font-medium"
                                            >MC Seq#:</span
                                        >
                                        {{ masterCardToAction?.mc_seq }}
                                    </p>
                                    <p class="text-sm text-green-800">
                                        <span class="font-medium">Model:</span>
                                        {{ masterCardToAction?.mc_model }}
                                    </p>
                                    <p class="text-sm text-green-800">
                                        <span class="font-medium"
                                            >Customer:</span
                                        >
                                        {{ masterCardToAction?.customer_name }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div
                        class="bg-gray-50 px-6 py-4 flex justify-end space-x-3"
                    >
                        <button 
                            type="button" 
                            class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-5 py-2 rounded-lg flex items-center space-x-2 transform active:translate-y-px transition-all duration-300 shadow-md relative overflow-hidden group"
                            @click="confirmApproval"
                        >
                            <span
                                class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity"
                            ></span>
                            <i class="fas fa-check text-sm mr-1"></i>
                            <span>Approve</span>
                        </button>
                        <button 
                            type="button" 
                            class="bg-gradient-to-r from-gray-200 to-gray-300 hover:from-gray-300 hover:to-gray-400 text-gray-700 px-5 py-2 rounded-lg flex items-center space-x-2 transform active:translate-y-px transition-all duration-300 shadow-md relative overflow-hidden group"
                            @click="showApprovalModal = false"
                        >
                            <span
                                class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity"
                            ></span>
                            <i class="fas fa-times text-sm mr-1"></i>
                            <span>Cancel</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rejection Modal -->
        <!-- Notification Toast -->
        <div
            v-if="notification.show"
            class="fixed bottom-4 right-4 z-50 shadow-xl rounded-lg transition-all duration-300"
            :class="{
                'bg-green-100 border-l-4 border-green-500':
                    notification.type === 'success',
                'bg-red-100 border-l-4 border-red-500':
                    notification.type === 'error',
                'bg-yellow-100 border-l-4 border-yellow-500':
                    notification.type === 'warning',
            }"
        >
            <div class="p-4 flex items-center">
                <div class="mr-3">
                    <i
                        v-if="notification.type === 'success'"
                        class="fas fa-check-circle text-green-500 text-xl"
                    ></i>
                    <i
                        v-else-if="notification.type === 'error'"
                        class="fas fa-exclamation-circle text-red-500 text-xl"
                    ></i>
                    <i
                        v-else
                        class="fas fa-exclamation-triangle text-yellow-500 text-xl"
                    ></i>
                </div>
                <div>
                    <p
                        :class="{
                            'text-green-800': notification.type === 'success',
                            'text-red-800': notification.type === 'error',
                            'text-yellow-800': notification.type === 'warning',
                        }"
                    >
                        {{ notification.message }}
                    </p>
                </div>
            </div>
        </div>

        <div
            v-if="showRejectionModal"
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title"
            role="dialog"
            aria-modal="true"
        >
            <div
                class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
            >
                <div
                    class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
                    aria-hidden="true"
                    @click="showRejectionModal = false"
                ></div>
                <span
                    class="hidden sm:inline-block sm:align-middle sm:h-screen"
                    aria-hidden="true"
                    >&#8203;</span
                >
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full animate-modalScaleIn"
                >
                    <!-- Modal Header with gradient -->
                    <div
                        class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4 relative overflow-hidden"
                    >
                        <div
                            class="absolute -top-8 -left-8 w-16 h-16 bg-white opacity-10 rounded-full"
                        ></div>
                        <div
                            class="absolute -bottom-8 -right-8 w-16 h-16 bg-white opacity-10 rounded-full"
                        ></div>
                        
                        <div class="flex items-center">
                            <div
                                class="bg-white bg-opacity-20 p-2 rounded-full mr-3"
                            >
                                <i class="fas fa-times text-white text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white">
                                Reject Master Card
                            </h3>
                        </div>
                    </div>
                    
                    <div class="bg-white px-6 py-5">
                        <div class="sm:flex sm:items-start mb-4">
                            <div
                                class="bg-gradient-to-br from-red-400 to-red-500 p-3 rounded-lg shadow-md flex-shrink-0 mr-4"
                            >
                                <i
                                    class="fas fa-file-alt text-white text-lg"
                                ></i>
                            </div>
                            <div>
                                <div
                                    class="bg-red-50 border border-red-200 rounded-lg p-3 mb-3"
                                >
                                    <div class="flex items-center mb-1">
                                        <i
                                            class="fas fa-info-circle text-red-600 mr-2"
                                        ></i>
                                        <span class="font-medium text-red-900"
                                            >Master Card Details</span
                                        >
                                    </div>
                                    <p class="text-sm text-red-800">
                                        <span class="font-medium"
                                            >MC Seq#:</span
                                        >
                                        {{ masterCardToAction?.mc_seq }}
                                    </p>
                                    <p class="text-sm text-red-800">
                                        <span class="font-medium">Model:</span>
                                        {{ masterCardToAction?.mc_model }}
                                    </p>
                                    <p class="text-sm text-red-800">
                                        <span class="font-medium"
                                            >Customer:</span
                                        >
                                        {{ masterCardToAction?.customer_name }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2 flex items-center"
                            >
                                <span
                                    class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-red-500 to-red-600 rounded-full mr-2 shadow-sm"
                                >
                                    <i
                                        class="fas fa-comment-alt text-white text-xs"
                                    ></i>
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
                    
                    <div
                        class="bg-gray-50 px-6 py-4 flex justify-end space-x-3"
                    >
                        <button 
                            type="button" 
                            class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-5 py-2 rounded-lg flex items-center space-x-2 transform active:translate-y-px transition-all duration-300 shadow-md relative overflow-hidden group"
                            @click="confirmRejection"
                        >
                            <span
                                class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity"
                            ></span>
                            <i class="fas fa-times text-sm mr-1"></i>
                            <span>Reject</span>
                        </button>
                        <button 
                            type="button" 
                            class="bg-gradient-to-r from-gray-200 to-gray-300 hover:from-gray-300 hover:to-gray-400 text-gray-700 px-5 py-2 rounded-lg flex items-center space-x-2 transform active:translate-y-px transition-all duration-300 shadow-md relative overflow-hidden group"
                            @click="showRejectionModal = false"
                        >
                            <span
                                class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity"
                            ></span>
                            <i class="fas fa-arrow-left text-sm mr-1"></i>
                            <span>Cancel</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Option Modal -->
        <div
            v-if="showOptionModal"
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title"
            role="dialog"
            aria-modal="true"
        >
            <div
                class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
            >
                <div
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                    aria-hidden="true"
                    @click="showOptionModal = false"
                ></div>
                <span
                    class="hidden sm:inline-block sm:align-middle sm:h-screen"
                    aria-hidden="true"
                    >&#8203;</span
                >
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full animate-modalScaleIn"
                >
                    <div
                        class="bg-gradient-to-r from-blue-600 to-indigo-700 px-4 py-3"
                    >
                        <div class="flex items-center justify-between">
                            <h3
                                class="text-lg leading-6 font-medium text-white"
                                id="modal-title"
                            >
                                Select Option
                            </h3>
                            <button 
                                @click="showOptionModal = false" 
                                class="text-white opacity-70 hover:opacity-100 focus:outline-none"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="bg-white p-6">
                        <div
                            class="bg-gray-50 border border-gray-200 rounded-md p-4 mb-6"
                        >
                            <div class="space-y-3">
                                <label
                                    class="flex items-center cursor-pointer p-2 hover:bg-gray-100 rounded-md transition-colors"
                                >
                                    <input
                                        type="radio"
                                        v-model="selectOption"
                                        value="customer"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                    />
                                    <div class="ml-3">
                                        <span
                                            class="font-medium text-gray-900 block"
                                            >Select by Customer</span
                                        >
                                        <span class="text-xs text-gray-500"
                                            >Filter master cards by customer
                                            information</span
                                        >
                                    </div>
                                </label>
                                <label
                                    class="flex items-center cursor-pointer p-2 hover:bg-gray-100 rounded-md transition-colors"
                                >
                                    <input
                                        type="radio"
                                        v-model="selectOption"
                                        value="mastercard"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                    />
                                    <div class="ml-3">
                                        <span
                                            class="font-medium text-gray-900 block"
                                            >Select by Master Card</span
                                        >
                                        <span class="text-xs text-gray-500"
                                            >Filter by master card
                                            attributes</span
                                        >
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
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 mr-1.5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                                Apply
                            </button>
                            <button 
                                type="button" 
                                class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" 
                                @click="showOptionModal = false"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 mr-1.5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
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
        <CustomerAccountModal 
            v-if="showCustomerAccountTable"
            :show="showCustomerAccountTable"
            :customer-accounts="
                customerAccounts.value.length > 0
                    ? customerAccounts
                    : customerData
            "
            :loading="loadingCustomers"
            @close="showCustomerAccountTable = false"
            @select="selectCustomer"
            @sort="handleSortConfirm"
        />

        <!-- Customer Account Options Modal -->
        <div
            v-if="showCustomerAccountOptionsModal"
            class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto"
        >
            <!-- Modal backdrop -->
            <div
                class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
                @click="showCustomerAccountOptionsModal = false"
            ></div>
            
            <!-- Modal content -->
            <div
                class="relative bg-white rounded-lg shadow-xl w-96 mx-auto max-w-lg z-10 transform transition-all"
            >
                <!-- Modal header -->
                <div
                    class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-t-lg relative overflow-hidden"
                >
                    <div
                        class="absolute -top-8 -left-8 w-16 h-16 bg-white opacity-10 rounded-full"
                    ></div>
                    <div
                        class="absolute -bottom-8 -right-8 w-16 h-16 bg-white opacity-10 rounded-full"
                    ></div>

                    <h3
                        class="text-xl font-semibold flex items-center relative z-10"
                    >
                        <span
                            class="inline-flex items-center justify-center w-8 h-8 bg-white bg-opacity-20 rounded-full mr-3 shadow-inner"
                        >
                            <i class="fas fa-filter text-white"></i>
                        </span>
                        Customer Account Options
                    </h3>
                    <button
                        type="button"
                        @click="showCustomerAccountOptionsModal = false"
                        class="text-white hover:text-gray-200 focus:outline-none transition-transform hover:scale-110 relative z-10 bg-red-500 bg-opacity-30 hover:bg-opacity-50 rounded-full w-8 h-8 flex items-center justify-center"
                    >
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="p-6 space-y-6">
                    <!-- Sort By Options -->
                    <div
                        class="bg-white border border-gray-300 rounded-md shadow-sm p-4"
                    >
                        <h4
                            class="font-semibold mb-3 text-gray-700 flex items-center"
                        >
                            <i class="fas fa-sort mr-2 text-blue-500"></i>Sort
                            by:
                        </h4>
                        <div class="space-y-2 ml-2">
                            <label
                                class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md"
                            >
                                <input
                                    type="radio"
                                    v-model="customerOptionSortBy"
                                    value="code"
                                    class="form-radio h-4 w-4 text-blue-600"
                                />
                                <span class="ml-2 text-gray-700"
                                    >Customer Code</span
                                >
                            </label>
                            <label
                                class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md"
                            >
                                <input
                                    type="radio"
                                    v-model="customerOptionSortBy"
                                    value="name"
                                    class="form-radio h-4 w-4 text-blue-600"
                                />
                                <span class="ml-2 text-gray-700"
                                    >Customer Name</span
                                >
                            </label>
                        </div>
                    </div>
                    
                    <!-- Record Status Options -->
                    <div
                        class="bg-white border border-gray-300 rounded-md shadow-sm p-4"
                    >
                        <h4
                            class="font-semibold mb-3 text-gray-700 flex items-center"
                        >
                            <i class="fas fa-tag mr-2 text-blue-500"></i>Record
                            Status:
                        </h4>
                        <div class="flex items-center space-x-6 ml-2">
                            <label
                                class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md"
                            >
                                <input
                                    type="checkbox"
                                    v-model="customerOptionRecordStatus.active"
                                    class="form-checkbox h-4 w-4 text-blue-600 rounded"
                                />
                                <span class="ml-2 text-gray-700">Active</span>
                            </label>
                            <label
                                class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md"
                            >
                                <input
                                    type="checkbox"
                                    v-model="
                                        customerOptionRecordStatus.obsolete
                                    "
                                    class="form-checkbox h-4 w-4 text-blue-600 rounded"
                                />
                                <span class="ml-2 text-gray-700">Obsolete</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div
                    class="flex items-center justify-center space-x-4 p-4 border-t border-gray-200 bg-gray-50 rounded-b-lg"
                >
                    <button 
                        @click="applyOptionsFromCustomerTable" 
                        class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-medium py-2 px-8 rounded-md transition-all focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-md hover:shadow-lg relative overflow-hidden group"
                    >
                        <span
                            class="absolute inset-0 w-full h-full bg-white opacity-0 group-hover:opacity-10 transition-opacity"
                        ></span>
                        <span class="inline-flex items-center">
                            <i class="fas fa-check mr-2"></i>
                        OK
                        </span>
                    </button>
                    <button 
                        @click="showCustomerAccountOptionsModal = false" 
                        class="bg-gradient-to-r from-gray-300 to-gray-400 hover:from-gray-400 hover:to-gray-500 text-gray-800 font-medium py-2 px-8 rounded-md transition-all focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 shadow-md hover:shadow-lg relative overflow-hidden group"
                    >
                        <span
                            class="absolute inset-0 w-full h-full bg-white opacity-0 group-hover:opacity-10 transition-opacity"
                        ></span>
                        <span class="inline-flex items-center">
                            <i class="fas fa-times mr-2"></i> Exit
                        </span>
                    </button>
                </div>
            </div>
        </div>

        <!-- MCS Options Modal (legacy - disabled) -->
        <div
            v-if="false && showMcsOptionsModal"
            class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto"
        >
            <!-- Modal backdrop -->
            <div
                class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
                @click="showMcsOptionsModal = false"
            ></div>
            
            <!-- Modal content -->
            <div
                class="relative bg-white rounded-lg shadow-xl w-96 mx-auto max-w-lg z-10 transform transition-all"
            >
                <!-- Modal header -->
                <div
                    class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-green-600 to-teal-600 text-white rounded-t-lg relative overflow-hidden"
                >
                    <div
                        class="absolute -top-8 -left-8 w-16 h-16 bg-white opacity-10 rounded-full"
                    ></div>
                    <div
                        class="absolute -bottom-8 -right-8 w-16 h-16 bg-white opacity-10 rounded-full"
                    ></div>

                    <h3
                        class="text-xl font-semibold flex items-center relative z-10"
                    >
                        <span
                            class="inline-flex items-center justify-center w-8 h-8 bg-white bg-opacity-20 rounded-full mr-3 shadow-inner"
                        >
                            <i class="fas fa-filter text-white"></i>
                        </span>
                        Options
                    </h3>
                    <button
                        type="button"
                        @click="showMcsOptionsModal = false"
                        class="text-white hover:text-gray-200 focus:outline-none transition-transform hover:scale-110 relative z-10 bg-red-500 bg-opacity-30 hover:bg-opacity-50 rounded-full w-8 h-8 flex items-center justify-center"
                    >
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="p-6 space-y-6">
                    <!-- Sort By Options -->
                    <div
                        class="bg-white border border-gray-300 rounded-md shadow-sm p-4"
                    >
                        <h4
                            class="font-semibold mb-3 text-gray-700 flex items-center"
                        >
                            <i class="fas fa-sort mr-2 text-blue-500"></i>Sort
                            by:
                        </h4>
                        <div class="space-y-2 ml-2">
                            <label
                                class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md"
                            >
                                <input
                                    type="radio"
                                    v-model="mcsSortOption"
                                    value="seq"
                                    class="form-radio h-4 w-4 text-blue-600"
                                />
                                <span class="ml-2 text-gray-700">MC Seq#</span>
                            </label>
                            <label
                                class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md"
                            >
                                <input
                                    type="radio"
                                    v-model="mcsSortOption"
                                    value="model"
                                    class="form-radio h-4 w-4 text-blue-600"
                                />
                                <span class="ml-2 text-gray-700">MC Model</span>
                            </label>
                            <label
                                class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md"
                            >
                                <input
                                    type="radio"
                                    v-model="mcsSortOption"
                                    value="part"
                                    class="form-radio h-4 w-4 text-blue-600"
                                />
                                <span class="ml-2 text-gray-700"
                                    >MC PD Part#</span
                                >
                            </label>
                            <label
                                class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md"
                            >
                                <input
                                    type="radio"
                                    v-model="mcsSortOption"
                                    value="ed"
                                    class="form-radio h-4 w-4 text-blue-600"
                                />
                                <span class="ml-2 text-gray-700">MC PD ED</span>
                            </label>
                            <label
                                class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md"
                            >
                                <input
                                    type="radio"
                                    v-model="mcsSortOption"
                                    value="id"
                                    class="form-radio h-4 w-4 text-blue-600"
                                />
                                <span class="ml-2 text-gray-700">MC PD ID</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Sort Order Options -->
                    <div
                        class="bg-white border border-gray-300 rounded-md shadow-sm p-4"
                    >
                        <h4
                            class="font-semibold mb-3 text-gray-700 flex items-center"
                        >
                            <i
                                class="fas fa-arrow-up-long mr-2 text-blue-500"
                            ></i
                            >Sort Order:
                        </h4>
                        <div class="flex items-center space-x-6 ml-2">
                            <label
                                class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md"
                            >
                                <input
                                    type="radio"
                                    v-model="mcsSortOrder"
                                    value="asc"
                                    class="form-radio h-4 w-4 text-blue-600"
                                />
                                <span class="ml-2 text-gray-700"
                                    >Ascending</span
                                >
                            </label>
                            <label
                                class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md"
                            >
                                <input
                                    type="radio"
                                    v-model="mcsSortOrder"
                                    value="desc"
                                    class="form-radio h-4 w-4 text-blue-600"
                                />
                                <span class="ml-2 text-gray-700"
                                    >Descending</span
                                >
                            </label>
                        </div>
                    </div>
                    
                    <!-- MC Status Options -->
                    <div
                        class="bg-white border border-gray-300 rounded-md shadow-sm p-4"
                    >
                        <h4
                            class="font-semibold mb-3 text-gray-700 flex items-center"
                        >
                            <i class="fas fa-tag mr-2 text-blue-500"></i>MC
                            Status:
                        </h4>
                        <div class="flex items-center space-x-6 ml-2">
                            <label
                                class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md"
                            >
                                <input
                                    type="checkbox"
                                    v-model="mcsRecordStatus.active"
                                    class="form-checkbox h-4 w-4 text-blue-600 rounded"
                                />
                                <span class="ml-2 text-gray-700">Active</span>
                            </label>
                            <label
                                class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md"
                            >
                                <input
                                    type="checkbox"
                                    v-model="mcsRecordStatus.obsolete"
                                    class="form-checkbox h-4 w-4 text-blue-600 rounded"
                                />
                                <span class="ml-2 text-gray-700">Obsolete</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div
                    class="flex items-center justify-center space-x-4 p-4 border-t border-gray-200 bg-gray-50 rounded-b-lg"
                >
                    <button 
                        @click="applyMcsFilter" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-8 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        <i class="fas fa-check mr-2"></i>OK
                    </button>
                    <button 
                        @click="showMcsOptionsModal = false" 
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-8 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2"
                    >
                        <i class="fas fa-times mr-2"></i>Exit
                    </button>
                </div>
            </div>
        </div>

        <!-- MCS Table Modal (UpdateMcModal version) -->
        <UpdateMcModal
            v-if="showMcsTableModal"
            :showMcsTableModal="showMcsTableModal"
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
            @closeMcsTableModal="showMcsTableModal = false"
            @handleZoomChange="handleZoomChange"
            @fetchMcsData="fetchMcsData"
            @selectMcsItem="selectedMcs = $event"
            @selectMcs="selectMcs"
            @goToMcsPage="goToMcsPage"
            @updateSearchTerm="updateSearchTerm"
            @updateSortOption="updateSortOption"
        />

        <!-- Legacy inline modal (disabled) -->
        <div
            v-if="false && showMcsTableModal"
            class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto"
        >
            <!-- Modal backdrop -->
            <div
                class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
                @click="showMcsTableModal = false"
            ></div>
            <!-- Modal content -->
            <div
                class="relative bg-white rounded-2xl shadow-2xl w-full max-w-4xl mx-auto z-10 transform transition-all border-2 border-indigo-200"
            >
                <!-- Modal header -->
                <div
                    class="flex items-center justify-between p-5 border-b-2 border-indigo-200 bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 text-white rounded-t-2xl relative overflow-hidden"
                >
                    <!-- Decorative animated circles -->
                    <div
                        class="absolute -top-8 -left-8 w-24 h-24 bg-white opacity-10 rounded-full animate-ping-slow"
                    ></div>
                    <div
                        class="absolute -bottom-8 -right-8 w-20 h-20 bg-cyan-300 opacity-10 rounded-full animate-ping-slow animation-delay-500"
                    ></div>
                    <div
                        class="absolute top-0 right-0 w-16 h-16 bg-white opacity-5 rounded-full"
                    ></div>
                    <h3
                        class="text-2xl font-bold flex items-center relative z-10 text-shadow"
                    >
                        <span
                            class="inline-flex items-center justify-center w-10 h-10 bg-white bg-opacity-20 rounded-full mr-4 shadow-inner"
                        >
                            <i class="fas fa-id-card text-white text-xl"></i>
                        </span>
                        Master Card Seq Table
                    </h3>
                    <button
                        type="button"
                        @click="showMcsTableModal = false"
                        class="text-white hover:text-gray-200 focus:outline-none transition-transform hover:scale-110 relative z-10 bg-red-500 bg-opacity-30 hover:bg-opacity-50 rounded-full w-10 h-10 flex items-center justify-center"
                    >
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div
                    class="p-6 bg-gradient-to-br from-white via-blue-50 to-cyan-50 rounded-b-2xl overflow-auto"
                    style="max-height: 440px"
                >
                    <div
                        class="mb-4 flex flex-col md:flex-row justify-between items-center gap-4"
                    >
                        <div class="text-gray-700 text-base font-medium">
                            <span class="font-bold text-indigo-700"
                                >Filter:</span
                            >
                            <span class="text-gray-600">{{
                                mcsStatusFilter === "all"
                                    ? "All Records"
                                    : mcsStatusFilter === "Act"
                                    ? "Active Records Only"
                                    : "Obsolete Records Only"
                            }}</span>
                        </div>
                        <div class="relative w-full md:w-64">
                            <input 
                                type="text" 
                                v-model="mcsSearchTerm" 
                                placeholder="Search..." 
                                class="border border-indigo-200 rounded-lg py-2 px-4 text-base focus:outline-none focus:ring-2 focus:ring-blue-400 w-full shadow-sm"
                            />
                            <i
                                class="fas fa-search absolute right-4 top-3 text-gray-400"
                            ></i>
                        </div>
                    </div>
                    <div class="overflow-x-auto rounded-lg shadow">
                        <table
                            class="min-w-full border border-indigo-200 text-base bg-white rounded-lg overflow-hidden"
                        >
                            <thead
                                class="bg-gradient-to-r from-blue-500 via-cyan-500 to-teal-400 text-white"
                            >
                            <tr v-if="mcsSortOption === 'part'">
                                    <th
                                        class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold"
                                    >
                                        Part No
                                    </th>
                                    <th
                                        class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold"
                                    >
                                        MC Seq#
                                    </th>
                                    <th
                                        class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold"
                                    >
                                        Comp#
                                    </th>
                                    <th
                                        class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold"
                                    >
                                        P/Design
                                    </th>
                                    <th
                                        class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold"
                                    >
                                        Status
                                    </th>
                            </tr>
                                <tr
                                    v-else-if="
                                        ['seq', 'model'].includes(mcsSortOption)
                                    "
                                >
                                    <th
                                        v-if="mcsSortOption !== 'model'"
                                        class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold"
                                    >
                                        MC Seq#
                                    </th>
                                    <th
                                        class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold"
                                    >
                                        MC Model
                                    </th>
                                    <th
                                        v-if="mcsSortOption === 'model'"
                                        class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold"
                                    >
                                        MC Seq#
                                    </th>
                                    <th
                                        class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold"
                                    >
                                        Status
                                    </th>
                            </tr>
                            <tr v-else-if="mcsSortOption === 'ed'">
                                    <th
                                        class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold"
                                    >
                                        Ext. Dimension
                                    </th>
                                    <th
                                        class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold"
                                    >
                                        MC Seq#
                                    </th>
                                    <th
                                        class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold"
                                    >
                                        Comp#
                                    </th>
                                    <th
                                        class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold"
                                    >
                                        P/Design
                                    </th>
                                    <th
                                        class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold"
                                    >
                                        Model
                                    </th>
                                    <th
                                        class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold"
                                    >
                                        Status
                                    </th>
                            </tr>
                            <tr v-else-if="mcsSortOption === 'id'">
                                    <th
                                        class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold"
                                    >
                                        Int. Dimension
                                    </th>
                                    <th
                                        class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold"
                                    >
                                        MC Seq#
                                    </th>
                                    <th
                                        class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold"
                                    >
                                        Comp#
                                    </th>
                                    <th
                                        class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold"
                                    >
                                        P/Design
                                    </th>
                                    <th
                                        class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold"
                                    >
                                        Model
                                    </th>
                                    <th
                                        class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold"
                                    >
                                        Status
                                    </th>
                            </tr>
                            <tr v-else>
                                    <th
                                        class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold"
                                    >
                                        MC Seq#
                                    </th>
                                    <th
                                        class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold"
                                    >
                                        MC Model
                                    </th>
                                    <th
                                        class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold"
                                    >
                                        MC PD Part#
                                    </th>
                                    <th
                                        class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold"
                                    >
                                        MC PD ED
                                    </th>
                                    <th
                                        class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold"
                                    >
                                        MC PD ID
                                    </th>
                                    <th
                                        class="px-4 py-3 border-b-2 border-indigo-200 text-left font-bold"
                                    >
                                        Status
                                    </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-indigo-100">
                            <template v-if="mcsSortOption === 'part'">
                                    <tr
                                        v-for="(mcs, index) in filteredMcsData"
                                        :key="mcs.seq + '-part'"
                                    class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-cyan-100 cursor-pointer transition-all duration-150"
                                        :class="{
                                            'bg-gradient-to-r from-blue-100 to-cyan-100':
                                                selectedMcs?.seq === mcs.seq,
                                        }"
                                    @click="selectForViewMcs(mcs)"
                                        @dblclick="selectMcs(mcs)"
                                    >
                                        <td
                                            class="px-4 py-2 border-b border-indigo-100 align-middle text-left font-normal"
                                        >
                                            {{ mcs.part }}
                                        </td>
                                        <td
                                            class="px-4 py-2 border-b border-indigo-100 align-middle text-left font-semibold"
                                        >
                                            {{ mcs.seq }}
                                        </td>
                                        <td
                                            class="px-4 py-2 border-b border-indigo-100 align-middle text-left font-normal"
                                        >
                                            {{ mcs.comp }}
                                        </td>
                                        <td
                                            class="px-4 py-2 border-b border-indigo-100 align-middle text-left font-normal"
                                        >
                                            {{ mcs.p_design }}
                                        </td>
                                        <td
                                            class="px-4 py-2 border-b border-indigo-100 align-middle text-left font-normal"
                                        >
                                            <span
                                                v-if="mcs.status === 'Active'"
                                                class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-green-400 to-emerald-500 text-white shadow"
                                                >Active</span
                                            >
                                            <span
                                                v-else-if="
                                                    mcs.status === 'Obsolete'
                                                "
                                                class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-rose-400 to-red-500 text-white shadow"
                                                >Obsolete</span
                                            >
                                            <span
                                                v-else
                                                class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-gray-400 to-gray-500 text-white shadow"
                                                >{{ mcs.status }}</span
                                            >
                                    </td>
                                </tr>
                            </template>
                                <template
                                    v-else-if="
                                        ['seq', 'model'].includes(mcsSortOption)
                                    "
                                >
                                    <tr
                                        v-for="(mcs, index) in filteredMcsData"
                                        :key="mcs.seq + '-seqmodel'"
                                    class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-cyan-100 cursor-pointer transition-all duration-150"
                                        :class="{
                                            'bg-gradient-to-r from-blue-100 to-cyan-100':
                                                selectedMcs?.seq === mcs.seq,
                                        }"
                                    @click="selectForViewMcs(mcs)"
                                        @dblclick="selectMcs(mcs)"
                                    >
                                        <td
                                            v-if="mcsSortOption !== 'model'"
                                            class="px-4 py-2 border-b border-indigo-100 align-middle text-left font-semibold"
                                        >
                                            {{ mcs.seq }}
                                        </td>
                                        <td
                                            class="px-4 py-2 border-b border-indigo-100 align-middle text-left font-normal"
                                        >
                                            {{ mcs.model }}
                                        </td>
                                        <td
                                            v-if="mcsSortOption === 'model'"
                                            class="px-4 py-2 border-b border-indigo-100 align-middle text-left font-semibold"
                                        >
                                            {{ mcs.seq }}
                                        </td>
                                        <td
                                            class="px-4 py-2 border-b border-indigo-100 align-middle text-left font-normal"
                                        >
                                            <span
                                                v-if="mcs.status === 'Active'"
                                                class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-green-400 to-emerald-500 text-white shadow"
                                                >Active</span
                                            >
                                            <span
                                                v-else-if="
                                                    mcs.status === 'Obsolete'
                                                "
                                                class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-rose-400 to-red-500 text-white shadow"
                                                >Obsolete</span
                                            >
                                            <span
                                                v-else
                                                class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-gray-400 to-gray-500 text-white shadow"
                                                >{{ mcs.status }}</span
                                            >
                                    </td>
                                </tr>
                            </template>
                            <template v-else-if="mcsSortOption === 'ed'">
                                    <tr
                                        v-for="(mcs, index) in filteredMcsData"
                                        :key="mcs.seq + '-ed'"
                                    class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-cyan-100 cursor-pointer transition-all duration-150"
                                        :class="{
                                            'bg-gradient-to-r from-blue-100 to-cyan-100':
                                                selectedMcs?.seq === mcs.seq,
                                        }"
                                    @click="selectForViewMcs(mcs)"
                                        @dblclick="selectMcs(mcs)"
                                    >
                                        <td
                                            class="px-4 py-2 border-b border-indigo-100 align-middle text-left font-normal"
                                        >
                                            {{ mcs.ed }}
                                        </td>
                                        <td
                                            class="px-4 py-2 border-b border-indigo-100 align-middle text-left font-semibold"
                                        >
                                            {{ mcs.seq }}
                                        </td>
                                        <td
                                            class="px-4 py-2 border-b border-indigo-100 align-middle text-left font-normal"
                                        >
                                            {{ mcs.comp }}
                                        </td>
                                        <td
                                            class="px-4 py-2 border-b border-indigo-100 align-middle text-left font-normal"
                                        >
                                            {{ mcs.p_design }}
                                        </td>
                                        <td
                                            class="px-4 py-2 border-b border-indigo-100 align-middle text-left font-normal"
                                        >
                                            {{ mcs.model }}
                                        </td>
                                        <td
                                            class="px-4 py-2 border-b border-indigo-100 align-middle text-left font-normal"
                                        >
                                            <span
                                                v-if="mcs.status === 'Active'"
                                                class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-green-400 to-emerald-500 text-white shadow"
                                                >Active</span
                                            >
                                            <span
                                                v-else-if="
                                                    mcs.status === 'Obsolete'
                                                "
                                                class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-rose-400 to-red-500 text-white shadow"
                                                >Obsolete</span
                                            >
                                            <span
                                                v-else
                                                class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-gray-400 to-gray-500 text-white shadow"
                                                >{{ mcs.status }}</span
                                            >
                                    </td>
                                </tr>
                            </template>
                            <template v-else-if="mcsSortOption === 'id'">
                                    <tr
                                        v-for="(mcs, index) in filteredMcsData"
                                        :key="mcs.seq + '-id'"
                                    class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-cyan-100 cursor-pointer transition-all duration-150"
                                        :class="{
                                            'bg-gradient-to-r from-blue-100 to-cyan-100':
                                                selectedMcs?.seq === mcs.seq,
                                        }"
                                    @click="selectForViewMcs(mcs)"
                                        @dblclick="selectMcs(mcs)"
                                    >
                                        <td
                                            class="px-4 py-2 border-b border-indigo-100 align-middle text-left font-normal"
                                        >
                                            {{ mcs.id }}
                                        </td>
                                        <td
                                            class="px-4 py-2 border-b border-indigo-100 align-middle text-left font-semibold"
                                        >
                                            {{ mcs.seq }}
                                        </td>
                                        <td
                                            class="px-4 py-2 border-b border-indigo-100 align-middle text-left font-normal"
                                        >
                                            {{ mcs.comp }}
                                        </td>
                                        <td
                                            class="px-4 py-2 border-b border-indigo-100 align-middle text-left font-normal"
                                        >
                                            {{ mcs.p_design }}
                                        </td>
                                        <td
                                            class="px-4 py-2 border-b border-indigo-100 align-middle text-left font-normal"
                                        >
                                            {{ mcs.model }}
                                        </td>
                                        <td
                                            class="px-4 py-2 border-b border-indigo-100 align-middle text-left font-normal"
                                        >
                                            <span
                                                v-if="mcs.status === 'Active'"
                                                class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-green-400 to-emerald-500 text-white shadow"
                                                >Active</span
                                            >
                                            <span
                                                v-else-if="
                                                    mcs.status === 'Obsolete'
                                                "
                                                class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-rose-400 to-red-500 text-white shadow"
                                                >Obsolete</span
                                            >
                                            <span
                                                v-else
                                                class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-gray-400 to-gray-500 text-white shadow"
                                                >{{ mcs.status }}</span
                                            >
                                    </td>
                                </tr>
                            </template>
                            <template v-else>
                                    <tr
                                        v-for="(mcs, index) in filteredMcsData"
                                        :key="mcs.seq + '-other'"
                                    class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-cyan-100 cursor-pointer transition-all duration-150"
                                        :class="{
                                            'bg-gradient-to-r from-blue-100 to-cyan-100':
                                                selectedMcs?.seq === mcs.seq,
                                        }"
                                    @click="selectForViewMcs(mcs)"
                                        @dblclick="selectMcs(mcs)"
                                    >
                                        <td
                                            class="px-4 py-2 border-b border-indigo-100 align-middle text-left font-semibold"
                                        >
                                            {{ mcs.seq }}
                                        </td>
                                        <td
                                            class="px-4 py-2 border-b border-indigo-100 align-middle text-left font-normal"
                                        >
                                            {{ mcs.model }}
                                        </td>
                                        <td
                                            class="px-4 py-2 border-b border-indigo-100 align-middle text-left font-normal"
                                        >
                                            {{ mcs.part }}
                                        </td>
                                        <td
                                            class="px-4 py-2 border-b border-indigo-100 align-middle text-left font-normal"
                                        >
                                            {{ mcs.ed }}
                                        </td>
                                        <td
                                            class="px-4 py-2 border-b border-indigo-100 align-middle text-left font-normal"
                                        >
                                            {{ mcs.id }}
                                        </td>
                                        <td
                                            class="px-4 py-2 border-b border-indigo-100 align-middle text-left font-normal"
                                        >
                                            <span
                                                v-if="mcs.status === 'Active'"
                                                class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-green-400 to-emerald-500 text-white shadow"
                                                >Active</span
                                            >
                                            <span
                                                v-else-if="
                                                    mcs.status === 'Obsolete'
                                                "
                                                class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-rose-400 to-red-500 text-white shadow"
                                                >Obsolete</span
                                            >
                                            <span
                                                v-else
                                                class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-gray-400 to-gray-500 text-white shadow"
                                                >{{ mcs.status }}</span
                                            >
                                    </td>
                                </tr>
                            </template>
                            <tr v-if="filteredMcsData.length === 0">
                                    <td
                                        :colspan="
                                            mcsSortOption === 'part'
                                                ? 5
                                                : ['seq', 'model'].includes(
                                                      mcsSortOption
                                                  )
                                                ? 4
                                                : ['ed', 'id'].includes(
                                                      mcsSortOption
                                                  )
                                                ? 6
                                                : 6
                                        "
                                        class="px-4 py-8 text-center text-gray-500 border-b border-indigo-100 bg-white"
                                    >
                                        No master card records found matching
                                        your criteria
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                    <!-- Tambahkan textbox Model, Ext. Dim, Int. Dim hanya saat sort by MC PD Part# -->
                    <div
                        v-if="mcsSortOption === 'part'"
                        class="mt-4 p-4 border border-indigo-200 rounded-lg bg-white"
                    >
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center"
                        >
                            <!-- Model -->
                            <div class="flex items-center gap-2">
                                <label for="mcs-model" class="font-bold w-24"
                                    >Model:</label
                                >
                                <input
                                    type="text"
                                    id="mcs-model"
                                    :value="selectedMcs?.model || ''"
                                    readonly
                                    class="flex-grow bg-gray-100 border-2 border-gray-300 rounded-md py-1 px-2 focus:outline-none focus:border-blue-500 transition"
                                />
                            </div>
                            <div></div>
                            <!-- Spacer -->
                            <!-- External Dimensions -->
                            <div class="flex items-center gap-2">
                                <label class="font-bold w-24">Ext. Dim:</label>
                                <div class="flex items-center gap-1">
                                    <input
                                        type="text"
                                        :value="selectedMcs?.ext_dim_1 || ''"
                                        readonly
                                        class="w-20 bg-gray-100 border-2 border-gray-300 rounded-md py-1 px-2 text-center focus:outline-none focus:border-blue-500 transition"
                                    />
                                    <span>x</span>
                                    <input
                                        type="text"
                                        :value="selectedMcs?.ext_dim_2 || ''"
                                        readonly
                                        class="w-20 bg-gray-100 border-2 border-gray-300 rounded-md py-1 px-2 text-center focus:outline-none focus:border-blue-500 transition"
                                    />
                                    <span>x</span>
                                    <input
                                        type="text"
                                        :value="selectedMcs?.ext_dim_3 || ''"
                                        readonly
                                        class="w-20 bg-gray-100 border-2 border-gray-300 rounded-md py-1 px-2 text-center focus:outline-none focus:border-blue-500 transition"
                                    />
                                </div>
                            </div>
                            <!-- Internal Dimensions -->
                            <div class="flex items-center gap-2">
                                <label class="font-bold w-24">Int. Dim:</label>
                                <div class="flex items-center gap-1">
                                    <input
                                        type="text"
                                        :value="selectedMcs?.int_dim_1 || ''"
                                        readonly
                                        class="w-20 bg-gray-100 border-2 border-gray-300 rounded-md py-1 px-2 text-center focus:outline-none focus:border-blue-500 transition"
                                    />
                                    <span>x</span>
                                    <input
                                        type="text"
                                        :value="selectedMcs?.int_dim_2 || ''"
                                        readonly
                                        class="w-20 bg-gray-100 border-2 border-gray-300 rounded-md py-1 px-2 text-center focus:outline-none focus:border-blue-500 transition"
                                    />
                                    <span>x</span>
                                    <input
                                        type="text"
                                        :value="selectedMcs?.int_dim_3 || ''"
                                        readonly
                                        class="w-20 bg-gray-100 border-2 border-gray-300 rounded-md py-1 px-2 text-center focus:outline-none focus:border-blue-500 transition"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END Tambah textbox -->
                    <!-- Tambahkan textbox Model khusus untuk sort by MC PD ED -->
                    <div
                        v-if="mcsSortOption === 'ed'"
                        class="mt-4 flex items-center gap-2"
                    >
                        <label for="mcs-model-ed" class="font-bold w-20"
                            >Model:</label
                        >
                        <input
                            type="text"
                            id="mcs-model-ed"
                            :value="selectedMcs?.model || ''"
                            readonly
                            class="flex-grow bg-gray-100 border-2 border-gray-400 rounded-sm py-1 px-2 font-bold text-black focus:outline-none"
                        />
                    </div>
                    <!-- Tambahkan textbox Model khusus untuk sort by MC PD ID -->
                    <div
                        v-if="mcsSortOption === 'id'"
                        class="mt-4 flex items-center gap-2"
                    >
                        <label for="mcs-model-id" class="font-bold w-20"
                            >Model:</label
                        >
                        <input
                            type="text"
                            id="mcs-model-id"
                            :value="selectedMcs?.model || ''"
                            readonly
                            class="flex-grow bg-gray-100 border-2 border-gray-400 rounded-sm py-1 px-2 font-bold text-black focus:outline-none"
                        />
                    </div>
                    <!-- END Tambah textbox -->
                </div>
                <div
                    class="flex flex-wrap items-center justify-end gap-3 p-5 border-t-2 border-indigo-200 bg-gradient-to-r from-blue-50 via-cyan-50 to-white rounded-b-2xl"
                >
                    <!-- BUTTONS: More Option, Zoom, Select, Exit (JANGAN DIUBAH POSISINYA) -->
                    <button 
                        @click="showMcsOptionsFromTableModal('from')"
                        class="relative overflow-hidden font-bold py-2 px-5 rounded-xl text-base shadow-lg transition-all duration-200 group flex items-center border-0 focus:outline-none bg-gradient-to-r from-teal-400 via-blue-500 to-indigo-500 text-white before:absolute before:inset-0 before:rounded-xl before:bg-gradient-to-r before:from-cyan-200 before:via-blue-300 before:to-indigo-200 before:opacity-0 group-hover:before:opacity-40 before:blur-sm before:transition-opacity before:duration-300 group-hover:scale-105 group-hover:shadow-2xl"
                    >
                        <span class="relative z-10 flex items-center">
                            <i class="fas fa-cog mr-2 animate-spin-slow"></i>
                            More Options
                        </span>
                    </button>
                    <button 
                        class="bg-gradient-to-r from-blue-400 to-cyan-500 hover:from-blue-500 hover:to-cyan-600 text-white font-bold py-2 px-4 rounded-lg text-base shadow-sm hover:shadow transition-all group relative overflow-hidden flex items-center"
                    >
                        <span
                            class="absolute inset-0 w-full h-full bg-white opacity-0 group-hover:opacity-10 transition-opacity"
                        ></span>
                        <i class="fas fa-search-plus mr-2"></i> Zoom
                    </button>
                    <button 
                        @click="selectMcs(selectedMcs)" 
                        :disabled="!selectedMcs"
                        class="bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-bold py-2 px-4 rounded-lg text-base shadow-sm hover:shadow disabled:opacity-50 disabled:cursor-not-allowed transition-all group relative overflow-hidden flex items-center"
                    >
                        <span
                            class="absolute inset-0 w-full h-full bg-white opacity-0 group-hover:opacity-10 transition-opacity"
                        ></span>
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

        <!-- MCS Options Modal To (legacy - disabled) -->
        <div
            v-if="false && showMcsOptionsModalTo"
            class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto"
        >
            <!-- Modal backdrop -->
            <div
                class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
                @click="showMcsOptionsModalTo = false"
            ></div>
            
            <!-- Modal content -->
            <div
                class="relative bg-white rounded-lg shadow-xl w-96 mx-auto max-w-lg z-10 transform transition-all"
            >
                <!-- Modal header -->
                <div
                    class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-green-600 to-teal-600 text-white rounded-t-lg relative overflow-hidden"
                >
                    <div
                        class="absolute -top-8 -left-8 w-16 h-16 bg-white opacity-10 rounded-full"
                    ></div>
                    <div
                        class="absolute -bottom-8 -right-8 w-16 h-16 bg-white opacity-10 rounded-full"
                    ></div>

                    <h3
                        class="text-xl font-semibold flex items-center relative z-10"
                    >
                        <span
                            class="inline-flex items-center justify-center w-8 h-8 bg-white bg-opacity-20 rounded-full mr-3 shadow-inner"
                        >
                            <i class="fas fa-filter text-white"></i>
                        </span>
                        Options
                    </h3>
                    <button
                        type="button"
                        @click="showMcsOptionsModalTo = false"
                        class="text-white hover:text-gray-200 focus:outline-none transition-transform hover:scale-110 relative z-10 bg-red-500 bg-opacity-30 hover:bg-opacity-50 rounded-full w-8 h-8 flex items-center justify-center"
                    >
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="p-6 space-y-6">
                    <!-- Sort By Options -->
                    <div
                        class="bg-white border border-gray-300 rounded-md shadow-sm p-4"
                    >
                        <h4
                            class="font-semibold mb-3 text-gray-700 flex items-center"
                        >
                            <i class="fas fa-sort mr-2 text-blue-500"></i>Sort
                            by:
                        </h4>
                        <div class="space-y-2 ml-2">
                            <label
                                class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md"
                            >
                                <input
                                    type="radio"
                                    v-model="mcsSortOption"
                                    value="seq"
                                    class="form-radio h-4 w-4 text-blue-600"
                                />
                                <span class="ml-2 text-gray-700">MC Seq#</span>
                            </label>
                            <label
                                class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md"
                            >
                                <input
                                    type="radio"
                                    v-model="mcsSortOption"
                                    value="model"
                                    class="form-radio h-4 w-4 text-blue-600"
                                />
                                <span class="ml-2 text-gray-700">MC Model</span>
                            </label>
                            <label
                                class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md"
                            >
                                <input
                                    type="radio"
                                    v-model="mcsSortOption"
                                    value="part"
                                    class="form-radio h-4 w-4 text-blue-600"
                                />
                                <span class="ml-2 text-gray-700"
                                    >MC PD Part#</span
                                >
                            </label>
                            <label
                                class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md"
                            >
                                <input
                                    type="radio"
                                    v-model="mcsSortOption"
                                    value="ed"
                                    class="form-radio h-4 w-4 text-blue-600"
                                />
                                <span class="ml-2 text-gray-700">MC PD ED</span>
                            </label>
                            <label
                                class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md"
                            >
                                <input
                                    type="radio"
                                    v-model="mcsSortOption"
                                    value="id"
                                    class="form-radio h-4 w-4 text-blue-600"
                                />
                                <span class="ml-2 text-gray-700">MC PD ID</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Sort Order Options -->
                    <div
                        class="bg-white border border-gray-300 rounded-md shadow-sm p-4"
                    >
                        <h4
                            class="font-semibold mb-3 text-gray-700 flex items-center"
                        >
                            <i
                                class="fas fa-arrow-up-long mr-2 text-blue-500"
                            ></i
                            >Sort Order:
                        </h4>
                        <div class="flex items-center space-x-6 ml-2">
                            <label
                                class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md"
                            >
                                <input
                                    type="radio"
                                    v-model="mcsSortOrder"
                                    value="asc"
                                    class="form-radio h-4 w-4 text-blue-600"
                                />
                                <span class="ml-2 text-gray-700"
                                    >Ascending</span
                                >
                            </label>
                            <label
                                class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md"
                            >
                                <input
                                    type="radio"
                                    v-model="mcsSortOrder"
                                    value="desc"
                                    class="form-radio h-4 w-4 text-blue-600"
                                />
                                <span class="ml-2 text-gray-700"
                                    >Descending</span
                                >
                            </label>
                        </div>
                    </div>
                    
                    <!-- MC Status Options -->
                    <div
                        class="bg-white border border-gray-300 rounded-md shadow-sm p-4"
                    >
                        <h4
                            class="font-semibold mb-3 text-gray-700 flex items-center"
                        >
                            <i class="fas fa-tag mr-2 text-blue-500"></i>MC
                            Status:
                        </h4>
                        <div class="flex items-center space-x-6 ml-2">
                            <label
                                class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md"
                            >
                                <input
                                    type="checkbox"
                                    v-model="mcsRecordStatus.active"
                                    class="form-checkbox h-4 w-4 text-blue-600 rounded"
                                />
                                <span class="ml-2 text-gray-700">Active</span>
                            </label>
                            <label
                                class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded-md"
                            >
                                <input
                                    type="checkbox"
                                    v-model="mcsRecordStatus.obsolete"
                                    class="form-checkbox h-4 w-4 text-blue-600 rounded"
                                />
                                <span class="ml-2 text-gray-700">Obsolete</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div
                    class="flex items-center justify-center space-x-4 p-4 border-t border-gray-200 bg-gray-50 rounded-b-lg"
                >
                    <button 
                        @click="applyMcsFilterTo" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-8 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        <i class="fas fa-check mr-2"></i>OK
                    </button>
                    <button 
                        @click="showMcsOptionsModalTo = false" 
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-8 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2"
                    >
                        <i class="fas fa-times mr-2"></i>Exit
                    </button>
                </div>
            </div>
        </div>

        <!-- MCS Table Modal To (after selecting options) (legacy - disabled) -->
        <div
            v-if="false && showMcsTableModalTo"
            class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center"
        >
          <!-- Modal content -->
            <div
                class="bg-white rounded-lg shadow-xl w-11/12 md:w-2/3 lg:w-3/4 max-w-4xl mx-auto flex flex-col max-h-[90vh]"
            >
            <!-- Modal header -->
                <div
                    class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg"
                >
              <h3 class="text-xl font-semibold flex items-center">
                <i class="fas fa-id-card mr-3"></i>Master Card Table
              </h3>
              <div class="flex space-x-3 items-center">
                <div class="text-white text-sm mr-2">
                  <span class="mr-2">Zoom:</span>
                            <select
                                class="bg-blue-700 text-white border border-blue-500 rounded px-1 py-0.5 text-xs"
                            >
                                <option value="mc_specification">
                                    M/card specification
                                </option>
                                <option value="current_price">
                                    Current price
                                </option>
                                <option value="stand_by_price">
                                    Stand by price
                                </option>
                  </select>
                </div>
                <div class="text-white text-sm mr-2">
                  <span class="mr-2">Sort:</span>
                            <select
                                v-model="mcsSortOption"
                                class="bg-blue-700 text-white border border-blue-500 rounded px-1 py-0.5 text-xs"
                            >
                    <option value="seq">MC Seq#</option>
                    <option value="model">MC Model</option>
                    <option value="part">MC PD Part#</option>
                    <option value="ed">MC PD ED</option>
                    <option value="id">MC PD ID</option>
                  </select>
                </div>
                <div class="text-white text-sm mr-2">
                  <span class="mr-2">Order:</span>
                            <select
                                v-model="mcsSortOrder"
                                class="bg-blue-700 text-white border border-blue-500 rounded px-1 py-0.5 text-xs"
                            >
                    <option value="asc">Asc</option>
                    <option value="desc">Desc</option>
                  </select>
                </div>
                <div class="text-white text-sm">
                  <span class="mr-2">Status:</span>
                            <select
                                v-model="mcsRecordStatus.active"
                                class="bg-blue-700 text-white border border-blue-500 rounded px-1 py-0.5 text-xs"
                            >
                    <option :value="true">Active</option>
                    <option :value="false">Obsolete</option>
                  </select>
                </div>
                        <button
                            type="button"
                            @click="showMcsTableModalTo = false"
                            class="text-white hover:text-gray-200 focus:outline-none"
                        >
                <i class="fas fa-times text-xl"></i>
              </button>
            </div>
                </div>
            <!-- Modal Body -->
                <div
                    class="p-2 overflow-y-auto flex-grow"
                    style="max-height: 60vh"
                >
                    <div
                        class="mb-4 flex flex-col md:flex-row justify-between items-center gap-4"
                    >
                <div class="relative w-full md:w-64">
                  <input 
                    type="text" 
                    v-model="mcsSearchTermTo" 
                    placeholder="Search..." 
                    class="border border-gray-300 rounded-md py-1 px-2 text-xs focus:ring-blue-500 focus:border-blue-500 w-full shadow-sm"
                  />
                            <i
                                class="fas fa-search absolute right-2 top-1/2 -translate-y-1/2 text-gray-400"
                            ></i>
                </div>
              </div>
              
              <div class="overflow-x-auto rounded-lg shadow">
                        <table
                            class="min-w-full text-xs border border-gray-300"
                        >
                  <thead class="bg-gray-200 sticky top-0">
                    <tr v-if="mcsSortOption === 'part'">
                                    <th
                                        class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Part No
                                    </th>
                                    <th
                                        class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        MC Seq#
                                    </th>
                                    <th
                                        class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Comp#
                                    </th>
                                    <th
                                        class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        P/Design
                                    </th>
                                    <th
                                        class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Status
                                    </th>
                    </tr>
                                <tr
                                    v-else-if="
                                        ['seq', 'model'].includes(mcsSortOption)
                                    "
                                >
                                    <th
                                        v-if="mcsSortOption !== 'model'"
                                        class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        MC Seq#
                                    </th>
                                    <th
                                        class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        MC Model
                                    </th>
                                    <th
                                        v-if="mcsSortOption === 'model'"
                                        class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        MC Seq#
                                    </th>
                                    <th
                                        class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Status
                                    </th>
                    </tr>
                    <tr v-else-if="mcsSortOption === 'ed'">
                                    <th
                                        class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Ext. Dimension
                                    </th>
                                    <th
                                        class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        MC Seq#
                                    </th>
                                    <th
                                        class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Comp#
                                    </th>
                                    <th
                                        class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        P/Design
                                    </th>
                                    <th
                                        class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Model
                                    </th>
                                    <th
                                        class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Status
                                    </th>
                    </tr>
                    <tr v-else-if="mcsSortOption === 'id'">
                                    <th
                                        class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Int. Dimension
                                    </th>
                                    <th
                                        class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        MC Seq#
                                    </th>
                                    <th
                                        class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Comp#
                                    </th>
                                    <th
                                        class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        P/Design
                                    </th>
                                    <th
                                        class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Model
                                    </th>
                                    <th
                                        class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Status
                                    </th>
                    </tr>
                    <tr v-else>
                                    <th
                                        class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        MC Seq#
                                    </th>
                                    <th
                                        class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        MC Model
                                    </th>
                                    <th
                                        class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Part
                                    </th>
                                    <th
                                        class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Comp#
                                    </th>
                                    <th
                                        class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Status
                                    </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <template v-if="mcsSortOption === 'part'">
                                    <tr
                                        v-for="(
                                            mcs, index
                                        ) in filteredMcsDataTo"
                                        :key="mcs.seq + '-part'"
                        class="hover:bg-blue-100 cursor-pointer"
                                        :class="{
                                            'bg-blue-200':
                                                selectedMcsTo?.seq === mcs.seq,
                                        }"
                        @click="selectForViewMcsTo(mcs)"
                                        @dblclick="selectMcsTo(mcs)"
                                    >
                                        <td
                                            class="px-2 py-1 border border-gray-300"
                                        >
                                            {{ mcs.part }}
                                        </td>
                                        <td
                                            class="px-2 py-1 border border-gray-300"
                                        >
                                            {{ mcs.seq }}
                                        </td>
                                        <td
                                            class="px-2 py-1 border border-gray-300"
                                        >
                                            {{ mcs.comp }}
                                        </td>
                                        <td
                                            class="px-2 py-1 border border-gray-300"
                                        >
                                            {{ mcs.p_design }}
                                        </td>
                                        <td
                                            class="px-2 py-1 border border-gray-300"
                                        >
                          <span
                                                :class="
                                                    mcs.status === 'Act' ||
                                                    mcs.status === 'Active'
                                                        ? 'bg-green-100 text-green-800'
                                                        : 'bg-red-100 text-red-800'
                                                "
                                                class="px-2 py-0.5 rounded-full text-xs"
                                            >
                            {{ mcs.status }}
                          </span>
                        </td>
                      </tr>
                    </template>
                                <template
                                    v-else-if="
                                        ['seq', 'model'].includes(mcsSortOption)
                                    "
                                >
                                    <tr
                                        v-for="(
                                            mcs, index
                                        ) in filteredMcsDataTo"
                                        :key="mcs.seq + '-seqmodel'"
                        class="hover:bg-blue-100 cursor-pointer"
                                        :class="{
                                            'bg-blue-200':
                                                selectedMcsTo?.seq === mcs.seq,
                                        }"
                        @click="selectForViewMcsTo(mcs)"
                                        @dblclick="selectMcsTo(mcs)"
                                    >
                                        <td
                                            v-if="mcsSortOption !== 'model'"
                                            class="px-2 py-1 border border-gray-300"
                                        >
                                            {{ mcs.seq }}
                                        </td>
                                        <td
                                            class="px-2 py-1 border border-gray-300"
                                        >
                                            {{ mcs.model }}
                                        </td>
                                        <td
                                            v-if="mcsSortOption === 'model'"
                                            class="px-2 py-1 border border-gray-300"
                                        >
                                            {{ mcs.seq }}
                                        </td>
                                        <td
                                            class="px-2 py-1 border border-gray-300"
                                        >
                          <span
                                                :class="
                                                    mcs.status === 'Act' ||
                                                    mcs.status === 'Active'
                                                        ? 'bg-green-100 text-green-800'
                                                        : 'bg-red-100 text-red-800'
                                                "
                                                class="px-2 py-0.5 rounded-full text-xs"
                                            >
                            {{ mcs.status }}
                          </span>
                        </td>
                      </tr>
                    </template>
                    <template v-else-if="mcsSortOption === 'ed'">
                                    <tr
                                        v-for="(
                                            mcs, index
                                        ) in filteredMcsDataTo"
                                        :key="mcs.seq + '-ed'"
                        class="hover:bg-blue-100 cursor-pointer"
                                        :class="{
                                            'bg-blue-200':
                                                selectedMcsTo?.seq === mcs.seq,
                                        }"
                        @click="selectForViewMcsTo(mcs)"
                                        @dblclick="selectMcsTo(mcs)"
                                    >
                                        <td
                                            class="px-2 py-1 border border-gray-300"
                                        >
                                            {{ mcs.ed }}
                                        </td>
                                        <td
                                            class="px-2 py-1 border border-gray-300"
                                        >
                                            {{ mcs.seq }}
                                        </td>
                                        <td
                                            class="px-2 py-1 border border-gray-300"
                                        >
                                            {{ mcs.comp }}
                                        </td>
                                        <td
                                            class="px-2 py-1 border border-gray-300"
                                        >
                                            {{ mcs.p_design }}
                                        </td>
                                        <td
                                            class="px-2 py-1 border border-gray-300"
                                        >
                                            {{ mcs.model }}
                                        </td>
                                        <td
                                            class="px-2 py-1 border border-gray-300"
                                        >
                          <span
                                                :class="
                                                    mcs.status === 'Act' ||
                                                    mcs.status === 'Active'
                                                        ? 'bg-green-100 text-green-800'
                                                        : 'bg-red-100 text-red-800'
                                                "
                                                class="px-2 py-0.5 rounded-full text-xs"
                                            >
                            {{ mcs.status }}
                          </span>
                        </td>
                      </tr>
                    </template>
                    <template v-else-if="mcsSortOption === 'id'">
                                    <tr
                                        v-for="(
                                            mcs, index
                                        ) in filteredMcsDataTo"
                                        :key="mcs.seq + '-id'"
                        class="hover:bg-blue-100 cursor-pointer"
                                        :class="{
                                            'bg-blue-200':
                                                selectedMcsTo?.seq === mcs.seq,
                                        }"
                        @click="selectForViewMcsTo(mcs)"
                                        @dblclick="selectMcsTo(mcs)"
                                    >
                                        <td
                                            class="px-2 py-1 border border-gray-300"
                                        >
                                            {{ mcs.id }}
                                        </td>
                                        <td
                                            class="px-2 py-1 border border-gray-300"
                                        >
                                            {{ mcs.seq }}
                                        </td>
                                        <td
                                            class="px-2 py-1 border border-gray-300"
                                        >
                                            {{ mcs.comp }}
                                        </td>
                                        <td
                                            class="px-2 py-1 border border-gray-300"
                                        >
                                            {{ mcs.p_design }}
                                        </td>
                                        <td
                                            class="px-2 py-1 border border-gray-300"
                                        >
                                            {{ mcs.model }}
                                        </td>
                                        <td
                                            class="px-2 py-1 border border-gray-300"
                                        >
                          <span
                                                :class="
                                                    mcs.status === 'Act' ||
                                                    mcs.status === 'Active'
                                                        ? 'bg-green-100 text-green-800'
                                                        : 'bg-red-100 text-red-800'
                                                "
                                                class="px-2 py-0.5 rounded-full text-xs"
                                            >
                            {{ mcs.status }}
                          </span>
                        </td>
                      </tr>
                    </template>
                    <template v-else>
                                    <tr
                                        v-for="(
                                            mcs, index
                                        ) in filteredMcsDataTo"
                                        :key="mcs.seq + '-other'"
                        class="hover:bg-blue-100 cursor-pointer"
                                        :class="{
                                            'bg-blue-200':
                                                selectedMcsTo?.seq === mcs.seq,
                                        }"
                        @click="selectForViewMcsTo(mcs)"
                                        @dblclick="selectMcsTo(mcs)"
                                    >
                                        <td
                                            class="px-2 py-1 border border-gray-300"
                                        >
                                            {{ mcs.seq }}
                                        </td>
                                        <td
                                            class="px-2 py-1 border border-gray-300"
                                        >
                                            {{ mcs.model }}
                                        </td>
                                        <td
                                            class="px-2 py-1 border border-gray-300"
                                        >
                                            {{ mcs.part }}
                                        </td>
                                        <td
                                            class="px-2 py-1 border border-gray-300"
                                        >
                                            {{ mcs.comp }}
                                        </td>
                                        <td
                                            class="px-2 py-1 border border-gray-300"
                                        >
                          <span
                                                :class="
                                                    mcs.status === 'Act' ||
                                                    mcs.status === 'Active'
                                                        ? 'bg-green-100 text-green-800'
                                                        : 'bg-red-100 text-red-800'
                                                "
                                                class="px-2 py-0.5 rounded-full text-xs"
                                            >
                            {{ mcs.status }}
                          </span>
                        </td>
                      </tr>
                    </template>
                    <tr v-if="filteredMcsDataTo.length === 0">
                                    <td
                                        :colspan="
                                            mcsSortOption === 'part'
                                                ? 5
                                                : ['seq', 'model'].includes(
                                                      mcsSortOption
                                                  )
                                                ? 4
                                                : ['ed', 'id'].includes(
                                                      mcsSortOption
                                                  )
                                                ? 6
                                                : 5
                                        "
                                        class="px-4 py-8 text-center text-gray-500 border-b border-gray-300 bg-white"
                                    >
                                        No master card records found matching
                                        your criteria
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- Tambahkan textbox Model, Ext. Dim, Int. Dim hanya saat sort by MC PD Part# -->
                    <div
                        v-if="mcsSortOption === 'part'"
                        class="mt-4 p-4 border border-indigo-200 rounded-lg bg-white"
                    >
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center"
                        >
                  <!-- Model -->
                  <div class="flex items-center gap-2">
                                <label for="mcs-model-to" class="font-bold w-24"
                                    >Model:</label
                                >
                                <input
                                    type="text"
                                    id="mcs-model-to"
                                    :value="selectedMcsTo?.model || ''"
                                    readonly
                                    class="flex-grow bg-gray-100 border-2 border-gray-300 rounded-md py-1 px-2 focus:outline-none focus:border-blue-500 transition"
                                />
                  </div>
                            <div></div>
                            <!-- Spacer -->
                  <!-- External Dimensions -->
                  <div class="flex items-center gap-2">
                    <label class="font-bold w-24">Ext. Dim:</label>
                    <div class="flex items-center gap-1">
                                    <input
                                        type="text"
                                        :value="selectedMcsTo?.ext_dim_1 || ''"
                                        readonly
                                        class="w-20 bg-gray-100 border-2 border-gray-300 rounded-md py-1 px-2 text-center focus:outline-none focus:border-blue-500 transition"
                                    />
                      <span>x</span>
                                    <input
                                        type="text"
                                        :value="selectedMcsTo?.ext_dim_2 || ''"
                                        readonly
                                        class="w-20 bg-gray-100 border-2 border-gray-300 rounded-md py-1 px-2 text-center focus:outline-none focus:border-blue-500 transition"
                                    />
                      <span>x</span>
                                    <input
                                        type="text"
                                        :value="selectedMcsTo?.ext_dim_3 || ''"
                                        readonly
                                        class="w-20 bg-gray-100 border-2 border-gray-300 rounded-md py-1 px-2 text-center focus:outline-none focus:border-blue-500 transition"
                                    />
                    </div>
                  </div>
                  <!-- Internal Dimensions -->
                  <div class="flex items-center gap-2">
                    <label class="font-bold w-24">Int. Dim:</label>
                    <div class="flex items-center gap-1">
                                    <input
                                        type="text"
                                        :value="selectedMcsTo?.int_dim_1 || ''"
                                        readonly
                                        class="w-20 bg-gray-100 border-2 border-gray-300 rounded-md py-1 px-2 text-center focus:outline-none focus:border-blue-500 transition"
                                    />
                      <span>x</span>
                                    <input
                                        type="text"
                                        :value="selectedMcsTo?.int_dim_2 || ''"
                                        readonly
                                        class="w-20 bg-gray-100 border-2 border-gray-300 rounded-md py-1 px-2 text-center focus:outline-none focus:border-blue-500 transition"
                                    />
                      <span>x</span>
                                    <input
                                        type="text"
                                        :value="selectedMcsTo?.int_dim_3 || ''"
                                        readonly
                                        class="w-20 bg-gray-100 border-2 border-gray-300 rounded-md py-1 px-2 text-center focus:outline-none focus:border-blue-500 transition"
                                    />
                    </div>
                  </div>
                </div>
              </div>
              <!-- END Tambah textbox -->
              <!-- Tambahkan textbox Model khusus untuk sort by MC PD ED pada Table To -->
                    <div
                        v-if="mcsSortOption === 'ed'"
                        class="mt-4 flex items-center gap-2"
                    >
                        <label for="mcs-model-ed-to" class="font-bold w-20"
                            >Model:</label
                        >
                        <input
                            type="text"
                            id="mcs-model-ed-to"
                            :value="selectedMcsTo?.model || ''"
                            readonly
                            class="flex-grow bg-gray-100 border-2 border-gray-400 rounded-sm py-1 px-2 font-bold text-black focus:outline-none"
                        />
              </div>
              <!-- Tambahkan textbox Model khusus untuk sort by MC PD ID pada Table To -->
                    <div
                        v-if="mcsSortOption === 'id'"
                        class="mt-4 flex items-center gap-2"
                    >
                        <label for="mcs-model-id-to" class="font-bold w-20"
                            >Model:</label
                        >
                        <input
                            type="text"
                            id="mcs-model-id-to"
                            :value="selectedMcsTo?.model || ''"
                            readonly
                            class="flex-grow bg-gray-100 border-2 border-gray-400 rounded-sm py-1 px-2 font-bold text-black focus:outline-none"
                        />
              </div>
              <!-- END Tambah textbox -->
            </div>
            <!-- Modal Footer -->
                <div
                    class="flex items-center justify-end space-x-3 p-4 border-t border-gray-200 bg-gray-50 rounded-b-lg"
                >
              <button 
                @click="selectMcsTo(selectedMcsTo)" 
                :disabled="!selectedMcsTo"
                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <i class="fas fa-check mr-2"></i> Select
              </button>
              <button 
                @click="showMcsTableModalTo = false" 
                class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2"
              >
                  <i class="fas fa-times mr-2"></i> Exit
              </button>
            </div>
          </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import MasterCardModal from "@/Components/master-card-modal.vue";
import CustomerAccountModal from "@/Components/CustomerAccountModal.vue";
import UpdateMcModal from "@/Components/UpdateMcModal.vue";

const props = defineProps({
    masterCards: Array,
    customers: Array,
});

// Define reactive state variables
const sortBy = ref("seq");
const recordStatus = ref("all");
const selectBy = ref("customer");
const selectOption = ref("customer");
const showOptions = ref(false);
const showOptionModal = ref(false);
const showApprovalModal = ref(false);
const showRejectionModal = ref(false);
const rejectionReason = ref("");
const masterCardToAction = ref(null);
const showEditModal = ref(false);
const editingMasterCard = ref(null);
const modalMode = ref("edit");

// Customer Account Modal variables
const showCustomerAccountTable = ref(false);
const selectedCustomer = ref(null);
const customerAccounts = ref([]);
const loadingCustomers = ref(false);
const notification = ref({ show: false, message: "", type: "success" });

// New Customer Account Options Modal state
const showCustomerAccountOptionsModal = ref(false);
const customerOptionSortBy = ref("code");
const customerOptionRecordStatus = ref({
    active: true,
    obsolete: true,
});

// Sort configuration for customer accounts
const sortConfig = ref({
    sortBy: "customer_code",
    status: "active",
});

// MCS Table (reuse UpdateMcModal) state
const showMcsOptionsModal = ref(false);
const showMcsTableModal = ref(false);
const mcsSortOption = ref("mc_seq");
const mcsSortOrder = ref("asc");
const mcsStatusFilter = ref("Act"); // 'Act' | 'Obsolete' | 'all'
const selectedMcs = ref(null);
const mcsSearchTerm = ref("");
const mcsInput = ref("");
const currentMcsModalType = ref("");
const mcsMasterCards = ref([]);
const mcsLoading = ref(false);
const mcsError = ref("");
const mcsCurrentPage = ref(1);
const mcsLastPage = ref(1);

// Customer data (sample data - replace with actual API call)
const customerData = ref([
    {
        name: "ABDULLAH, BPK",
        code: "000211-08",
        salesperson: "S111",
        acType: "Local",
        currency: "IDR",
        status: "Active",
    },
    {
        name: "ACEP SUNANDAR, BPK",
        code: "000680-06",
        salesperson: "S140",
        acType: "Local",
        currency: "IDR",
        status: "Active",
    },
    {
        name: "ANWAR, BPK",
        code: "000681-06",
        salesperson: "S140",
        acType: "Local",
        currency: "IDR",
        status: "Active",
    },
    {
        name: "AGUNG, BPK",
        code: "000682-06",
        salesperson: "S141",
        acType: "Local",
        currency: "IDR",
        status: "Active",
    },
    {
        name: "BUDI, BPK",
        code: "000683-06",
        salesperson: "S142",
        acType: "Local",
        currency: "IDR",
        status: "Obsolete",
    },
    {
        name: "CITRA, IBU",
        code: "000684-06",
        salesperson: "S143",
        acType: "Export",
        currency: "USD",
        status: "Active",
    },
    {
        name: "DEDI, BPK",
        code: "000685-06",
        salesperson: "S140",
        acType: "Local",
        currency: "IDR",
        status: "Active",
    },
    {
        name: "EKA, IBU",
        code: "000686-06",
        salesperson: "S141",
        acType: "Local",
        currency: "IDR",
        status: "Obsolete",
    },
    {
        name: "FIRMAN, BPK",
        code: "000687-06",
        salesperson: "S142",
        acType: "Local",
        currency: "IDR",
        status: "Active",
    },
    {
        name: "GITA, IBU",
        code: "000688-06",
        salesperson: "S143",
        acType: "Export",
        currency: "USD",
        status: "Active",
    },
    {
        name: "HADI, BPK",
        code: "000689-06",
        salesperson: "S140",
        acType: "Local",
        currency: "IDR",
        status: "Active",
    },
    {
        name: "IDA, IBU",
        code: "000690-06",
        salesperson: "S141",
        acType: "Local",
        currency: "IDR",
        status: "Active",
    },
    {
        name: "JOKO, BPK",
        code: "000691-06",
        salesperson: "S142",
        acType: "Local",
        currency: "IDR",
        status: "Obsolete",
    },
    {
        name: "KARTIKA, IBU",
        code: "000692-06",
        salesperson: "S143",
        acType: "Export",
        currency: "USD",
        status: "Active",
    },
    {
        name: "LUKMAN, BPK",
        code: "000693-06",
        salesperson: "S140",
        acType: "Local",
        currency: "IDR",
        status: "Active",
    },
]);

// Helper to map local sort to API columns (already using API names by default)
const mapSortToApi = (value) => value;

// Initialize component
onMounted(() => {
    // Pre-fetch customer accounts in the background
    fetchCustomerAccounts();
});

// Record status filter
const recordStatusFilter = ref({
    active: true,
    obsolete: true,
});

// Replace the hardcoded masterCards with props data
const masterCards = ref(props.masterCards || []);
const customers = ref(props.customers || []);

const selectedMasterCard = ref(null);
const searchTerm = ref("");

// Watch for changes in search query to find matching customer
watch(searchTerm, (newQuery) => {
    if (newQuery && customerAccounts.value.length > 0) {
        const foundAccount = customerAccounts.value.find(
            (account) =>
                (account.customer_code &&
                    account.customer_code
                        .toLowerCase()
                        .includes(newQuery.toLowerCase())) ||
                (account.code &&
                    account.code
                        .toLowerCase()
                        .includes(newQuery.toLowerCase())) ||
                (account.customer_name &&
                    account.customer_name
                        .toLowerCase()
                        .includes(newQuery.toLowerCase())) ||
                (account.name &&
                    account.name.toLowerCase().includes(newQuery.toLowerCase()))
        );

        if (foundAccount) {
            selectedCustomer.value = foundAccount;
            console.log("Found matching customer:", foundAccount);
        }
    }
});

// Computed property to filter and sort master cards based on state
const filteredMasterCards = computed(() => {
    let filtered = masterCards.value.filter((mc) => {
        // Filter by selected customer if one is selected
        const matchesCustomer = selectedCustomer.value
            ? mc.customer_code ===
                  (selectedCustomer.value.code ||
                      selectedCustomer.value.customer_code) ||
              (mc.ac_code &&
                  mc.ac_code ===
                      (selectedCustomer.value.code ||
                          selectedCustomer.value.customer_code))
            : true;

        const matchesSearch = !searchTerm.value
            ? true
            : (mc.mc_model &&
                  mc.mc_model
                      .toLowerCase()
                      .includes(searchTerm.value.toLowerCase())) ||
              (mc.mc_seq &&
                  mc.mc_seq
                      .toLowerCase()
                      .includes(searchTerm.value.toLowerCase())) ||
              (mc.customer_name &&
                  mc.customer_name
                      .toLowerCase()
                      .includes(searchTerm.value.toLowerCase()));

        const matchesStatus =
            recordStatus.value === "all"
                ? true
                : mc.status === recordStatus.value;
        return matchesCustomer && matchesSearch && matchesStatus;
    });

    // Sort based on selected criterion
    return filtered.sort((a, b) => {
        if (sortBy.value === "seq") {
            return a.mc_seq.localeCompare(b.mc_seq);
        } else if (sortBy.value === "model") {
            return a.mc_model.localeCompare(b.mc_model);
        } else {
            return a.customer_name.localeCompare(b.customer_name);
        }
    });
});

// Customer Account Modal Computed Properties
const filteredCustomers = computed(() => {
    return customerData.value.filter((customer) => {
        // Filter by status
        if (recordStatusFilter.value.active && customer.status === "Active") {
            return true;
        }
        if (
            recordStatusFilter.value.obsolete &&
            customer.status === "Obsolete"
        ) {
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
        result = result.filter(
            (customer) =>
            customer.code.toLowerCase().includes(searchLower) ||
            customer.name.toLowerCase().includes(searchLower)
        );
    }
    
    // Apply sorting
    if (sortOption.value === "code") {
        result.sort((a, b) => a.code.localeCompare(b.code));
    } else if (sortOption.value === "name") {
        result.sort((a, b) => a.name.localeCompare(b.name));
    }
    
    return result;
});

// Fetch MCS data from backend (same as Update MC)
const fetchMcsData = async () => {
    try {
        mcsLoading.value = true;
        mcsError.value = "";
        const params = new URLSearchParams();
        if (mcsSearchTerm.value) params.set("query", mcsSearchTerm.value);
        params.set("sortBy", mapSortToApi(mcsSortOption.value));
        params.set("sortOrder", mcsSortOrder.value);
        const statuses =
            mcsStatusFilter.value === "all"
                ? ["Act", "Obsolete"]
                : [mcsStatusFilter.value];
        statuses.forEach((s) => params.append("status[]", s));
        params.set("per_page", "10");
        params.set("page", String(mcsCurrentPage.value));
        const res = await fetch(
            `/update-mc/master-cards?${params.toString()}`,
            { headers: { Accept: "application/json" } }
        );
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        const json = await res.json();
        mcsMasterCards.value = json.data || [];
        mcsCurrentPage.value = json.current_page || 1;
        mcsLastPage.value = json.last_page || 1;
    } catch (e) {
        mcsError.value = "Failed to load Master Card data";
        console.error(e);
    } finally {
        mcsLoading.value = false;
    }
};

// Secondary list will reuse same API but separate search term/page if needed later

// Action handlers
const handleOK = () => {
    // Handle OK button from options panel
    console.log(
        "OK clicked. Current sort:",
        sortBy.value,
        "Status filter:",
        recordStatus.value
    );
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
    rejectionReason.value = ""; // Clear previous rejection reason
    showRejectionModal.value = true;
};

const confirmApproval = async () => {
    try {
        const response = await fetch(
            `/api/approve-mc/approve/${masterCardToAction.value.id}`,
            {
                method: "POST",
            headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                    Accept: "application/json",
                },
            }
        );
        
        const result = await response.json();
        
        if (result.success) {
            // Update the status locally
            const index = masterCards.value.findIndex(
                (mc) => mc.id === masterCardToAction.value.id
            );
            if (index !== -1) {
                masterCards.value[index].status = "active";
                masterCards.value[index].approved_by = result.approved_by;
                masterCards.value[index].approved_date = result.approved_date;
            }
            
            // Show success message
            alert("Master Card approved successfully!");
        } else {
            alert(
                "Error approving Master Card: " +
                    (result.message || "Unknown error")
            );
        }
    } catch (error) {
        console.error("Error approving master card:", error);
        alert("An error occurred during approval");
    }
    
    showApprovalModal.value = false;
};

const confirmRejection = async () => {
    try {
        const response = await fetch(
            `/api/approve-mc/reject/${masterCardToAction.value.id}`,
            {
                method: "POST",
            headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                    Accept: "application/json",
            },
            body: JSON.stringify({ 
                    rejection_reason: rejectionReason.value,
                }),
            }
        );
        
        const result = await response.json();
        
        if (result.success) {
            // Update the status locally
            const index = masterCards.value.findIndex(
                (mc) => mc.id === masterCardToAction.value.id
            );
            if (index !== -1) {
                masterCards.value[index].status = "obsolete";
                masterCards.value[index].rejected_by = result.rejected_by;
                masterCards.value[index].rejected_date = result.rejected_date;
                masterCards.value[index].rejection_reason =
                    rejectionReason.value;
            }
            
            // Show success message
            alert("Master Card rejected successfully!");
        } else {
            alert(
                "Error rejecting Master Card: " +
                    (result.message || "Unknown error")
            );
        }
    } catch (error) {
        console.error("Error rejecting master card:", error);
        alert("An error occurred during rejection");
    }
    
    showRejectionModal.value = false;
};

const handleSelectOption = () => {
    console.log("Selected option:", selectOption.value);
    showOptionModal.value = false;
    
    if (selectOption.value === "customer") {
        // Implement logic for selecting by customer
        console.log("Show customer selection interface");
    } else {
        // Implement logic for selecting by master card
        console.log("Show master card selection interface");
    }
};

const handleEdit = (mc) => {
    editingMasterCard.value = { ...mc };
    modalMode.value = "edit";
    showEditModal.value = true;
};

const handleAddNew = () => {
    console.log("Opening add new master card modal");
    // Reset editing master card to a new empty record with good defaults
    editingMasterCard.value = {
        mc_seq: generateUniqueMcSeq(),
        mc_model: "",
        customer_code: "",
        customer_name: "",
        status: "pending",
    };
    // Set mode to add
    modalMode.value = "add";
    // Show the modal
    showEditModal.value = true;
    
    console.log("New master card initialized with:", editingMasterCard.value);
};

// Helper function to generate a unique MC sequence (example format: MC-2023-001)
const generateUniqueMcSeq = () => {
    const date = new Date();
    const year = date.getFullYear();
    const existingSeqs = masterCards.value
        .map((mc) => mc.mc_seq)
        .filter((seq) => seq.includes(`MC-${year}`));
    
    let nextNumber = 1;
    if (existingSeqs.length > 0) {
        // Extract the numeric parts and find the highest
        const numbers = existingSeqs
            .map((seq) => {
                const match = seq.match(/MC-\d+-(\d+)/);
                return match ? parseInt(match[1], 10) : 0;
            })
            .filter((num) => !isNaN(num));
        
        if (numbers.length > 0) {
            nextNumber = Math.max(...numbers) + 1;
        }
    }
    
    // Format with leading zeros (e.g., 001, 012, etc.)
    const paddedNumber = nextNumber.toString().padStart(3, "0");
    return `MC-${year}-${paddedNumber}`;
};

const closeEditModal = () => {
    console.log("Closing edit modal");
    showEditModal.value = false;
    editingMasterCard.value = null;
};

const handleUpdateMasterCard = async (updatedMasterCardData) => {
    console.log("==== Master Card Update/Add Handler ====");
    console.log("Received response from modal:", updatedMasterCardData);

    // Check if we received valid data from the modal
    if (!updatedMasterCardData) {
        console.error("No master card data received from modal");
        return;
    }

    try {
        // Check if this is a new record (no ID) or an existing one
        if (!updatedMasterCardData.id) {
            console.log(
                "Adding new master card to local state:",
                updatedMasterCardData
            );
            
            // Add the new record to the master cards array at the beginning for visibility
            masterCards.value = [updatedMasterCardData, ...masterCards.value];
            
            // Set the new card as selected
            selectedMasterCard.value = updatedMasterCardData;
            
            // Show confirmation to the user
            alert("Master Card added successfully!");
        } else {
            console.log("Updating existing master card in local state");
            
            // Find the index of the existing record
            const index = masterCards.value.findIndex(
                (mc) => mc.id === updatedMasterCardData.id
            );
            
            if (index !== -1) {
                console.log(
                    `Found existing record at index ${index}, updating`
                );
                
                // Update the record in the array
                masterCards.value.splice(index, 1, updatedMasterCardData);
                
                // If this was the selected record, update the selection
                if (selectedMasterCard.value?.id === updatedMasterCardData.id) {
                    selectedMasterCard.value = updatedMasterCardData;
                }
                
                // Show confirmation to the user
                alert("Master Card updated successfully!");
            } else {
                console.warn(
                    "Could not find master card with ID",
                    updatedMasterCardData.id
                );
                
                // Add it anyway in case it's a valid record
                masterCards.value.push(updatedMasterCardData);
            }
        }

        // Always close the modal after handling the response
        closeEditModal();
    } catch (error) {
        console.error("Error processing master card data:", error);
    }
};

// Function to fetch customer accounts from API
const fetchCustomerAccounts = async () => {
    loadingCustomers.value = true;
    try {
        console.log("Fetching customer accounts...");
        const response = await fetch("/api/customer-accounts", {
            headers: {
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
            credentials: "same-origin",
        });

        console.log("API response status:", response.status);

        if (!response.ok) {
            throw new Error(
                `Server returned ${response.status}: ${response.statusText}`
            );
        }

        const rawData = await response.json();
        console.log("Customer accounts data received");

        if (rawData.error) {
            throw new Error(rawData.error);
        }

        if (Array.isArray(rawData)) {
            customerAccounts.value = rawData;
            console.log(`Loaded ${rawData.length} customer accounts`);
        } else if (rawData.data && Array.isArray(rawData.data)) {
            customerAccounts.value = rawData.data;
            console.log(
                `Loaded ${rawData.data.length} customer accounts from data property`
            );
        } else {
            // If API is not yet implemented, use sample data
            customerAccounts.value = customerData.value;
            console.log("Using sample customer data");
        }
    } catch (error) {
        console.error("Error fetching customer accounts:", error);
        showNotification(
            `Failed to load customer data: ${error.message}`,
            "error"
        );
        // Fallback to sample data
        customerAccounts.value = customerData.value;
    } finally {
        loadingCustomers.value = false;
    }
};

const showNotification = (message, type = "success") => {
    notification.value = {
        show: true,
        message,
        type,
    };

    setTimeout(() => {
        notification.value.show = false;
    }, 3000);
};

const openCustomerAccountModal = () => {
    if (customerAccounts.value.length === 0) {
        fetchCustomerAccounts().then(() => {
            showCustomerAccountTable.value = true;
        });
    } else {
        showCustomerAccountTable.value = true;
    }
};

const selectCustomer = (customer) => {
    if (!customer) return;

    selectedCustomer.value = customer;
    searchTerm.value = customer.code || customer.customer_code; // Update the search term with selected customer code
    showCustomerAccountTable.value = false;
    
    // Filter master cards for the selected customer
    if (masterCards.value && masterCards.value.length > 0) {
        const customerCode = customer.code || customer.customer_code;
        const filteredCards = masterCards.value.filter(
            (mc) =>
                mc.customer_code === customerCode ||
                (mc.ac_code && mc.ac_code === customerCode)
        );

        if (filteredCards.length > 0) {
            console.log(
                `Found ${filteredCards.length} master cards for customer ${customerCode}`
            );
        } else {
            console.log(`No master cards found for customer ${customerCode}`);
            showNotification(
                "No master cards found for this customer",
                "warning"
            );
        }
    }
};

const selectForView = (customer) => {
    selectedCustomer.value = customer;
};

// MCS Modal functions
const openMcsModal = () => {
    showMcsTableModal.value = true;
    mcsCurrentPage.value = 1;
    fetchMcsData();
};

const showMcsOptionsFromTableModal = (modalType) => {
    if (modalType === "from") {
        showMcsTableModal.value = false;
    showMcsOptionsModal.value = true;
    } else if (modalType === "to") {
        showMcsTableModalTo.value = false;
        showMcsOptionsModalTo.value = true;
    }
    currentMcsModalType.value = modalType;
};

const applyMcsFilter = () => {
    showMcsOptionsModal.value = false;
    showMcsTableModal.value = true;
    mcsCurrentPage.value = 1;
    fetchMcsData();
};

const selectMcs = (mcs) => {
    if (!mcs) return;
    selectedMcs.value = mcs;
    mcsInput.value = mcs.seq || mcs.mc_seq;
    showMcsTableModal.value = false;
};
const goToMcsPage = (page) => {
    if (page < 1 || page > mcsLastPage.value) return;
    mcsCurrentPage.value = page;
    fetchMcsData();
};

const handleZoomChange = () => {};

const updateSearchTerm = (val) => {
    mcsSearchTerm.value = val;
};

const updateSortOption = (val) => {
    mcsSortOption.value = val;
};

const selectForViewMcs = (mcs) => {
    selectedMcs.value = mcs;
};

// MCS Options Modal To functions

const showMainPanel = computed(() => {
    return searchTerm.value && mcsInput.value;
});

const showMoreOptionsFromCustomerTable = () => {
    showCustomerAccountTable.value = false;
    showCustomerAccountOptionsModal.value = true;
    // Initialize options with current table filters (if any)
    // customerOptionSortBy.value = sortOption.value;
    // customerOptionRecordStatus.value = { ...recordStatusFilter.value };
};

const applyOptionsFromCustomerTable = () => {
    // Apply selected options to the main customer table logic
    sortOption.value = customerOptionSortBy.value;
    recordStatusFilter.value = { ...customerOptionRecordStatus.value };

    showCustomerAccountOptionsModal.value = false;
    showCustomerAccountTable.value = true;
    // Optionally re-fetch data or re-apply filters if needed
};

const handleSortConfirm = (config) => {
    sortConfig.value = config;
    console.log("Sort config updated:", config);
};
</script>

<style>
/* Add transition effects */
.fixed.inset-0 {
    animation: modalFadeIn 0.2s ease-out forwards;
}

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

.animate-modalScaleIn {
    animation: modalScaleIn 0.3s ease-out forwards;
}

/* Add new animations for ping effects */
@keyframes ping-slow {
    0% {
        transform: scale(1);
        opacity: 1;
    }
    75%,
    100% {
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
