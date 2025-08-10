<template>
    <AppLayout :header="'Update FG Stock-In by WO'">
        <!-- Header Section with animated elements, adapted from Define Warehouse Location -->
        <div class="bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 p-6 rounded-t-lg shadow-lg overflow-hidden relative">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10"></div>
            <div class="absolute bottom-0 right-0 w-32 h-32 bg-yellow-400 opacity-5 rounded-full translate-y-10 translate-x-10"></div>
            
            <div class="flex items-center">
                <div class="bg-gradient-to-br from-pink-500 to-purple-600 p-3 rounded-lg shadow-inner flex items-center justify-center relative overflow-hidden mr-4">
                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-yellow-300 opacity-30 rounded-full animate-ping-slow"></div>
                    <div class="absolute -bottom-1 -left-1 w-4 h-4 bg-blue-300 opacity-30 rounded-full animate-ping-slow animation-delay-500"></div>
                    <i class="fas fa-industry text-white text-2xl z-10"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-1 text-shadow">Update FG Stock-In by WO</h2>
                    <p class="text-blue-100 max-w-2xl">Process finished goods stock-in based on work order</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6 bg-gradient-to-br from-white to-indigo-50">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Main Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-indigo-500 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 relative overflow-hidden">
                        <div class="absolute -top-20 -right-20 w-40 h-40 bg-indigo-50 rounded-full opacity-20"></div>
                        <div class="absolute -bottom-8 -left-8 w-24 h-24 bg-purple-50 rounded-full opacity-20"></div>
                        
                        <div class="flex items-center mb-6 pb-2 border-b border-gray-200 relative z-10">
                            <div class="p-2 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg mr-3 shadow-md">
                                <i class="fas fa-clipboard-list text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800">Work Order Stock-In Management</h3>
                        </div>

                        <!-- Current Period -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full mr-2 shadow-sm">
                                    <i class="fas fa-calendar text-white text-xs"></i>
                                </span>
                                Current Period:
                            </label>
                            <input
                                type="text"
                                v-model="currentPeriod"
                                class="w-32 px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50 transition-all"
                                readonly
                            />
                        </div>

                        <!-- WO Number Search -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full mr-2 shadow-sm">
                                    <i class="fas fa-hashtag text-white text-xs"></i>
                                </span>
                                WO#:
                            </label>
                            <div class="flex items-center space-x-2 group">
                                <input
                                    type="text"
                                    v-model="woPrefix"
                                    placeholder="06"
                                    class="w-16 px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-blue-500 focus:border-blue-500 transition-all group-hover:border-blue-300"
                                    maxlength="2"
                                />
                                <span class="text-gray-500 font-medium">-</span>
                                <input
                                    type="text"
                                    v-model="woMiddle"
                                    placeholder="2025"
                                    class="w-20 px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-blue-500 focus:border-blue-500 transition-all group-hover:border-blue-300"
                                    maxlength="4"
                                />
                                <span class="text-gray-500 font-medium">-</span>
                                <input
                                    type="text"
                                    v-model="woSuffix"
                                    placeholder="01706"
                                    class="w-24 px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-blue-500 focus:border-blue-500 transition-all group-hover:border-blue-300"
                                    maxlength="5"
                                />
                                <button
                                    @click="openWorkOrderModal"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 bg-gradient-to-r from-blue-500 to-cyan-600 hover:from-blue-600 hover:to-cyan-700 text-white rounded-md transition-all transform active:translate-y-px relative overflow-hidden shadow-sm"
                                >
                                    <span class="absolute inset-0 bg-white opacity-0 hover:opacity-20 transition-opacity"></span>
                                    <i class="fas fa-search mr-2 relative z-10"></i>
                                    <span class="relative z-10">Search</span>
                                </button>
                            </div>
                            <div class="text-xs text-gray-500 mt-1">Format: mm-yyyy-ccccc</div>
                        </div>

                        <!-- Work Order Details (shown after selection) -->
                        <div v-if="selectedWorkOrder" class="border-t pt-6 space-y-6">
                            <!-- Work Order Info Grid -->
                            <div class="bg-gradient-to-r from-gray-50 to-blue-50 p-4 rounded-lg border-l-4 border-blue-500">
                                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                    Work Order Details
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                    <!-- Row 1 -->
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">WO#:</label>
                                        <div class="px-3 py-2 bg-white border border-gray-200 rounded text-sm font-medium">
                                            {{ selectedWorkOrder.wo_number }}
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">WO STS:</label>
                                        <div class="px-3 py-2 bg-white border border-gray-200 rounded text-sm">
                                            <span 
                                                class="px-2 py-1 rounded text-xs font-medium"
                                                :class="getStatusClass(selectedWorkOrder.wo_status)"
                                            >
                                                {{ selectedWorkOrder.wo_status }}
                                            </span>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">SO#:</label>
                                        <div class="px-3 py-2 bg-white border border-gray-200 rounded text-sm font-medium">
                                            {{ selectedWorkOrder.so_number }}
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">SO STS:</label>
                                        <div class="px-3 py-2 bg-white border border-gray-200 rounded text-sm">
                                            <span 
                                                class="px-2 py-1 rounded text-xs font-medium"
                                                :class="getStatusClass(selectedWorkOrder.so_status)"
                                            >
                                                {{ selectedWorkOrder.so_status }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Row 2 -->
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">ACR#:</label>
                                        <div class="px-3 py-2 bg-white border border-gray-200 rounded text-sm">
                                            {{ selectedWorkOrder.acr_number }}
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Name:</label>
                                        <div class="px-3 py-2 bg-white border border-gray-200 rounded text-sm font-medium">
                                            {{ selectedWorkOrder.customer_name }}
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">MCS#:</label>
                                        <div class="px-3 py-2 bg-white border border-gray-200 rounded text-sm">
                                            {{ selectedWorkOrder.mcs_number }}
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Model:</label>
                                        <div class="px-3 py-2 bg-white border border-gray-200 rounded text-sm">
                                            {{ selectedWorkOrder.model }}
                                        </div>
                                    </div>

                                    <!-- Row 3 -->
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Comp#:</label>
                                        <div class="px-3 py-2 bg-white border border-gray-200 rounded text-sm">
                                            {{ selectedWorkOrder.comp_number }}
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Part#:</label>
                                        <div class="px-3 py-2 bg-white border border-gray-200 rounded text-sm">
                                            {{ selectedWorkOrder.part_number }}
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">PD:</label>
                                        <div class="px-3 py-2 bg-white border border-gray-200 rounded text-sm">
                                            {{ selectedWorkOrder.pd }}
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Pcs/Unit:</label>
                                        <div class="px-3 py-2 bg-white border border-gray-200 rounded text-sm">
                                            {{ selectedWorkOrder.pcs_per_unit }}
                                        </div>
                                    </div>

                                    <!-- Row 4 -->
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">IED:</label>
                                        <div class="flex space-x-1">
                                            <div class="px-2 py-2 bg-white border border-gray-200 rounded text-sm text-center flex-1">
                                                {{ selectedWorkOrder.ied_x }}
                                            </div>
                                            <span class="flex items-center text-gray-400 px-1">X</span>
                                            <div class="px-2 py-2 bg-white border border-gray-200 rounded text-sm text-center flex-1">
                                                {{ selectedWorkOrder.ied_y }}
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Plan Qty:</label>
                                        <div class="px-3 py-2 bg-white border border-gray-200 rounded text-sm font-bold text-blue-600">
                                            {{ selectedWorkOrder.plan_qty?.toLocaleString() }}
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Due Date:</label>
                                        <div class="px-3 py-2 bg-white border border-gray-200 rounded text-sm">
                                            {{ selectedWorkOrder.due_date }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Entry Form -->
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 rounded-lg border-l-4 border-green-500">
                                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                    <i class="fas fa-plus-circle text-green-500 mr-2"></i>
                                    Stock-In Entry
                                </h4>
                                
                                <form @submit.prevent="saveStockIn" class="space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                                <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full mr-2 shadow-sm">
                                                    <i class="fas fa-file-alt text-white text-xs"></i>
                                                </span>
                                                Entry Ref#:
                                            </label>
                                            <input
                                                type="text"
                                                v-model="stockInForm.entry_ref"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 transition-all"
                                                required
                                            />
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                                <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full mr-2 shadow-sm">
                                                    <i class="fas fa-sort-numeric-up text-white text-xs"></i>
                                                </span>
                                                Entry Qty:
                                            </label>
                                            <input
                                                type="number"
                                                v-model="stockInForm.entry_qty"
                                                min="1"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 transition-all"
                                                required
                                            />
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                                <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full mr-2 shadow-sm">
                                                    <i class="fas fa-calendar-alt text-white text-xs"></i>
                                                </span>
                                                Entry Date:
                                            </label>
                                            <input
                                                type="date"
                                                v-model="stockInForm.entry_date"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500 transition-all"
                                                required
                                            />
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                                <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-orange-500 to-red-500 rounded-full mr-2 shadow-sm">
                                                    <i class="fas fa-warehouse text-white text-xs"></i>
                                                </span>
                                                WHSE#:
                                            </label>
                                            <div class="relative flex group">
                                                <input
                                                    type="text"
                                                    v-model="stockInForm.warehouse"
                                                    class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 focus:ring-orange-500 focus:border-orange-500 transition-all group-hover:border-orange-300"
                                                    placeholder="Select warehouse"
                                                    readonly
                                                    required
                                                />
                                                <button 
                                                    type="button"
                                                    @click="openWarehouseModal"
                                                    class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white rounded-r-md transition-all transform active:translate-y-px relative overflow-hidden shadow-sm"
                                                >
                                                    <span class="absolute inset-0 bg-white opacity-0 hover:opacity-20 transition-opacity"></span>
                                                    <i class="fas fa-table relative z-10"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                                <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-teal-500 to-cyan-500 rounded-full mr-2 shadow-sm">
                                                    <i class="fas fa-chart-pie text-white text-xs"></i>
                                                </span>
                                                Analysis#:
                                            </label>
                                            <div class="relative flex group">
                                                <input
                                                    type="text"
                                                    v-model="stockInForm.analysis"
                                                    class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 focus:ring-teal-500 focus:border-teal-500 transition-all group-hover:border-teal-300"
                                                    placeholder="Select analysis"
                                                    readonly
                                                />
                                                <button 
                                                    type="button"
                                                    @click="openAnalysisModal"
                                                    class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-teal-500 to-cyan-500 hover:from-teal-600 hover:to-cyan-600 text-white rounded-r-md transition-all transform active:translate-y-px relative overflow-hidden shadow-sm"
                                                >
                                                    <span class="absolute inset-0 bg-white opacity-0 hover:opacity-20 transition-opacity"></span>
                                                    <i class="fas fa-table relative z-10"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                            <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-gray-500 to-slate-500 rounded-full mr-2 shadow-sm">
                                                <i class="fas fa-comment text-white text-xs"></i>
                                            </span>
                                            Remark:
                                        </label>
                                        <textarea
                                            v-model="stockInForm.remark"
                                            rows="3"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-gray-500 focus:border-gray-500 transition-all"
                                            placeholder="Enter any remarks or notes..."
                                        ></textarea>
                                    </div>

                                    <!-- Save Button -->
                                    <div class="flex justify-end pt-4 border-t border-gray-200">
                                        <button
                                            type="submit"
                                            :disabled="!canSave"
                                            :class="{
                                                'bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700': canSave,
                                                'bg-gray-400 cursor-not-allowed': !canSave
                                            }"
                                            class="text-white px-6 py-2 rounded-lg shadow-md flex items-center space-x-2 transition-all duration-300 transform active:scale-95"
                                        >
                                            <i class="fas fa-save"></i>
                                            <span>SAVE</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Quick Info -->
                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-teal-500 mb-6 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 relative overflow-hidden">
                        <div class="absolute -top-16 -right-16 w-32 h-32 bg-teal-50 rounded-full opacity-20"></div>
                        <div class="absolute -bottom-6 -left-6 w-20 h-20 bg-green-50 rounded-full opacity-20"></div>
                        
                        <div class="flex items-center mb-4 pb-2 border-b border-gray-200 relative z-10">
                            <div class="p-2 bg-gradient-to-r from-teal-500 to-green-500 rounded-lg mr-3 shadow-md">
                                <i class="fas fa-lightbulb text-white"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Quick Guide</h3>
                        </div>

                        <div class="space-y-4">
                            <div class="p-4 bg-teal-50 rounded-lg">
                                <h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2 flex items-center">
                                    <span class="inline-flex items-center justify-center w-5 h-5 bg-teal-600 rounded-full mr-2">
                                        <i class="fas fa-play text-white text-xs"></i>
                                    </span>
                                    How to Use
                                </h4>
                                <ul class="text-sm text-teal-700 space-y-2">
                                    <li class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-emerald-400 to-teal-400 rounded-full mr-2 shadow-sm mt-0.5">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </span>
                                        <span>Enter WO number or click search to find work orders.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-400 to-indigo-400 rounded-full mr-2 shadow-sm mt-0.5">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </span>
                                        <span>Select work order from the modal table.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full mr-2 shadow-sm mt-0.5">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </span>
                                        <span>Fill in stock-in details and save.</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="p-4 bg-blue-50 rounded-lg">
                                <h4 class="text-sm font-semibold text-blue-800 uppercase tracking-wider mb-2 flex items-center">
                                    <span class="inline-flex items-center justify-center w-5 h-5 bg-blue-600 rounded-full mr-2">
                                        <i class="fas fa-info text-white text-xs"></i>
                                    </span>
                                    Important Notes
                                </h4>
                                <ul class="text-sm text-blue-700 space-y-2">
                                    <li class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-amber-400 to-orange-400 rounded-full mr-2 shadow-sm mt-0.5">
                                            <i class="fas fa-exclamation text-white text-xs"></i>
                                        </span>
                                        <span>Entry quantity cannot exceed remaining quantity.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-green-400 to-emerald-400 rounded-full mr-2 shadow-sm mt-0.5">
                                            <i class="fas fa-lock text-white text-xs"></i>
                                        </span>
                                        <span>Work order status updates automatically when completed.</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Work Order Stats (if selected) -->
                    <div v-if="selectedWorkOrder" class="bg-white p-6 rounded-lg shadow-md border-t-4 border-purple-500 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 relative overflow-hidden">
                        <div class="absolute -top-16 -right-16 w-32 h-32 bg-purple-50 rounded-full opacity-20"></div>
                        
                        <div class="flex items-center mb-4 pb-2 border-b border-gray-200 relative z-10">
                            <div class="p-2 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-lg mr-3 shadow-md">
                                <i class="fas fa-chart-bar text-white"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Production Stats</h3>
                        </div>

                        <div class="space-y-4">
                            <div class="bg-gradient-to-r from-indigo-50 to-blue-50 p-4 rounded-lg border border-indigo-100">
                                <div class="text-sm text-indigo-800 font-medium mb-1">Planned Quantity</div>
                                <div class="text-2xl font-bold text-indigo-700">{{ selectedWorkOrder.plan_qty?.toLocaleString() || 0 }}</div>
                            </div>
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 rounded-lg border border-green-100">
                                <div class="text-sm text-green-800 font-medium mb-1">Total Received</div>
                                <div class="text-2xl font-bold text-green-700">{{ selectedWorkOrder.total_received?.toLocaleString() || 0 }}</div>
                            </div>
                            <div class="bg-gradient-to-r from-orange-50 to-red-50 p-4 rounded-lg border border-orange-100">
                                <div class="text-sm text-orange-800 font-medium mb-1">Remaining</div>
                                <div class="text-2xl font-bold text-orange-700">{{ selectedWorkOrder.remaining_qty?.toLocaleString() || 0 }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Work Order Modal -->
        <div v-if="showWorkOrderModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl w-11/12 max-w-6xl max-h-[90vh] overflow-hidden">
                <div class="p-4 border-b bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold flex items-center">
                            <i class="fas fa-table mr-2"></i>
                            Work Order Selection
                        </h3>
                        <button
                            @click="closeWorkOrderModal"
                            class="text-white hover:text-gray-200 transition-colors"
                        >
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>
                
                <div class="p-4 overflow-auto max-h-[70vh]">
                    <table class="w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gradient-to-r from-gray-100 to-gray-200">
                                <th class="border border-gray-300 px-4 py-3 text-left text-sm font-semibold text-gray-700">WORK ORDER#</th>
                                <th class="border border-gray-300 px-4 py-3 text-left text-sm font-semibold text-gray-700">SO#</th>
                                <th class="border border-gray-300 px-4 py-3 text-left text-sm font-semibold text-gray-700">CUSTOMER NAME</th>
                                <th class="border border-gray-300 px-4 py-3 text-left text-sm font-semibold text-gray-700">PLAN QTY</th>
                                <th class="border border-gray-300 px-4 py-3 text-left text-sm font-semibold text-gray-700">DUE DATE</th>
                                <th class="border border-gray-300 px-4 py-3 text-left text-sm font-semibold text-gray-700">STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="wo in workOrders"
                                :key="wo.id"
                                @click="selectWorkOrder(wo)"
                                class="hover:bg-blue-50 cursor-pointer transition-colors"
                                :class="{ 'bg-blue-100 border-blue-300': selectedWorkOrder?.id === wo.id }"
                            >
                                <td class="border border-gray-300 px-4 py-3 text-sm font-medium">{{ wo.wo_number }}</td>
                                <td class="border border-gray-300 px-4 py-3 text-sm">{{ wo.so_number }}</td>
                                <td class="border border-gray-300 px-4 py-3 text-sm">{{ wo.customer_name }}</td>
                                <td class="border border-gray-300 px-4 py-3 text-sm font-bold text-blue-600">{{ wo.plan_qty?.toLocaleString() }}</td>
                                <td class="border border-gray-300 px-4 py-3 text-sm">{{ wo.due_date }}</td>
                                <td class="border border-gray-300 px-4 py-3 text-sm">
                                    <span 
                                        class="px-2 py-1 rounded text-xs font-medium"
                                        :class="getStatusClass(wo.status)"
                                    >
                                        {{ wo.status }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Warehouse Location Modal -->
        <div v-if="showWarehouseModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl w-11/12 max-w-5xl max-h-[90vh] overflow-hidden">
                <div class="p-4 border-b bg-gradient-to-r from-orange-500 to-red-500 text-white">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold flex items-center">
                            <i class="fas fa-warehouse mr-2"></i>
                            Warehouse Location Table
                        </h3>
                        <button
                            @click="closeWarehouseModal"
                            class="text-white hover:text-gray-200 transition-colors"
                        >
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>
                
                <div class="p-4 overflow-auto max-h-[70vh]">
                    <table class="w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gradient-to-r from-gray-100 to-gray-200">
                                <th class="border border-gray-300 px-4 py-3 text-left text-sm font-semibold text-gray-700">Code</th>
                                <th class="border border-gray-300 px-4 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
                                <th class="border border-gray-300 px-4 py-3 text-left text-sm font-semibold text-gray-700">Type</th>
                                <th class="border border-gray-300 px-4 py-3 text-center text-sm font-semibold text-gray-700">DO</th>
                                <th class="border border-gray-300 px-4 py-3 text-center text-sm font-semibold text-gray-700">SO</th>
                                <th class="border border-gray-300 px-4 py-3 text-center text-sm font-semibold text-gray-700">WT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="location in warehouseLocations"
                                :key="location.id"
                                @click="selectWarehouse(location)"
                                class="hover:bg-orange-50 cursor-pointer transition-colors"
                                :class="{ 'bg-orange-100 border-orange-300': stockInForm.warehouse === location.code }"
                            >
                                <td class="border border-gray-300 px-4 py-3 text-sm font-medium text-blue-600">{{ location.code }}</td>
                                <td class="border border-gray-300 px-4 py-3 text-sm">{{ location.description }}</td>
                                <td class="border border-gray-300 px-4 py-3 text-sm">{{ location.type }}</td>
                                <td class="border border-gray-300 px-4 py-3 text-center text-sm">
                                    <span :class="location.to_lock_delivery_order ? 'text-green-600 font-bold' : 'text-red-600'">
                                        {{ location.to_lock_delivery_order ? 'Y-Yes' : 'N-No' }}
                                    </span>
                                </td>
                                <td class="border border-gray-300 px-4 py-3 text-center text-sm">
                                    <span :class="location.to_lock_stock_out_adjustment ? 'text-green-600 font-bold' : 'text-red-600'">
                                        {{ location.to_lock_stock_out_adjustment ? 'Y-Yes' : 'N-No' }}
                                    </span>
                                </td>
                                <td class="border border-gray-300 px-4 py-3 text-center text-sm">
                                    <span :class="location.to_lock_warehouse_transfer ? 'text-green-600 font-bold' : 'text-red-600'">
                                        {{ location.to_lock_warehouse_transfer ? 'Y-Yes' : 'N-No' }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="p-4 border-t bg-gray-50 flex justify-center space-x-4">
                    <button
                        @click="selectWarehouse(null)"
                        class="px-6 py-2 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white rounded-lg shadow-md transition-all"
                    >
                        Select
                    </button>
                    <button
                        @click="sortWarehouseByCode"
                        class="px-6 py-2 bg-gradient-to-r from-blue-500 to-indigo-500 hover:from-blue-600 hover:to-indigo-600 text-white rounded-lg shadow-md transition-all"
                    >
                        Sort by Code
                    </button>
                    <button
                        @click="sortWarehouseByName"
                        class="px-6 py-2 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white rounded-lg shadow-md transition-all"
                    >
                        Sort by Name
                    </button>
                    <button
                        @click="closeWarehouseModal"
                        class="px-6 py-2 bg-gradient-to-r from-gray-500 to-slate-500 hover:from-gray-600 hover:to-slate-600 text-white rounded-lg shadow-md transition-all"
                    >
                        Exit
                    </button>
                </div>
            </div>
        </div>

        <!-- Analysis Code Modal -->
        <div v-if="showAnalysisModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl w-11/12 max-w-4xl max-h-[90vh] overflow-hidden">
                <div class="p-4 border-b bg-gradient-to-r from-teal-500 to-cyan-500 text-white">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold flex items-center">
                            <i class="fas fa-chart-pie mr-2"></i>
                            Analysis Code Table
                        </h3>
                        <button
                            @click="closeAnalysisModal"
                            class="text-white hover:text-gray-200 transition-colors"
                        >
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>
                
                <div class="p-4 overflow-auto max-h-[70vh]">
                    <table class="w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gradient-to-r from-gray-100 to-gray-200">
                                <th class="border border-gray-300 px-4 py-3 text-left text-sm font-semibold text-gray-700">Code</th>
                                <th class="border border-gray-300 px-4 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
                                <th class="border border-gray-300 px-4 py-3 text-left text-sm font-semibold text-gray-700">Grouping</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="analysis in analysisCodes"
                                :key="analysis.id"
                                @click="selectAnalysis(analysis)"
                                class="hover:bg-teal-50 cursor-pointer transition-colors"
                                :class="{ 'bg-teal-100 border-teal-300': stockInForm.analysis === analysis.code }"
                            >
                                <td class="border border-gray-300 px-4 py-3 text-sm font-medium text-blue-600">{{ analysis.code }}</td>
                                <td class="border border-gray-300 px-4 py-3 text-sm">{{ analysis.name }}</td>
                                <td class="border border-gray-300 px-4 py-3 text-sm">{{ analysis.grouping }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="p-4 border-t bg-gray-50 flex justify-center space-x-4">
                    <button
                        @click="selectAnalysis(null)"
                        class="px-6 py-2 bg-gradient-to-r from-teal-500 to-cyan-500 hover:from-teal-600 hover:to-cyan-600 text-white rounded-lg shadow-md transition-all"
                    >
                        Select
                    </button>
                    <button
                        @click="closeAnalysisModal"
                        class="px-6 py-2 bg-gradient-to-r from-gray-500 to-slate-500 hover:from-gray-600 hover:to-slate-600 text-white rounded-lg shadow-md transition-all"
                    >
                        Exit
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

// State
const currentPeriod = ref('05/2025');
const woPrefix = ref('');
const woMiddle = ref('');
const woSuffix = ref('');
const showWorkOrderModal = ref(false);
const showWarehouseModal = ref(false);
const showAnalysisModal = ref(false);
const selectedWorkOrder = ref(null);
const workOrders = ref([]);
const warehouseLocations = ref([]);
const analysisCodes = ref([]);

const stockInForm = reactive({
    entry_ref: '',
    entry_qty: '',
    entry_date: new Date().toISOString().slice(0, 10),
    warehouse: '',
    warehouse_name: '',
    analysis: '',
    analysis_name: '',
    remark: ''
});

// Computed
const canSave = computed(() => {
    return selectedWorkOrder.value &&
           stockInForm.entry_ref &&
           stockInForm.entry_qty &&
           stockInForm.entry_date &&
           stockInForm.warehouse;
});

// Methods
const openWorkOrderModal = () => {
    loadWorkOrders();
    showWorkOrderModal.value = true;
};

const closeWorkOrderModal = () => {
    showWorkOrderModal.value = false;
};

const openWarehouseModal = async () => {
    await loadWarehouseLocations();
    showWarehouseModal.value = true;
};

const closeWarehouseModal = () => {
    showWarehouseModal.value = false;
};

const openAnalysisModal = async () => {
    await loadAnalysisCodes();
    showAnalysisModal.value = true;
};

const closeAnalysisModal = () => {
    showAnalysisModal.value = false;
};

const loadWorkOrders = async () => {
    try {
        const response = await axios.get('/api/work-orders');
        workOrders.value = response.data;
    } catch (error) {
        console.error('Error loading work orders:', error);
        // Fallback demo data
        workOrders.value = [
            {
                id: 1,
                wo_number: '06-2025-01706',
                so_number: '06-2025-02264',
                customer_name: 'DIA RUPIAH',
                plan_qty: 50000,
                due_date: '30/06/2025',
                status: 'Active',
                wo_status: 'Active',
                so_status: 'Outstanding',
                acr_number: '000506',
                mcs_number: '0063879',
                model: '22819',
                comp_number: 'Main',
                part_number: 'BOX',
                pd: 'PUNCH',
                pcs_per_unit: '1/Pcs',
                ied_x: '0',
                ied_y: '0',
                total_received: 0,
                remaining_qty: 50000
            },
            {
                id: 2,
                wo_number: '06-2025-01703',
                so_number: '06-2025-02253',
                customer_name: 'DIA RUPIAH',
                plan_qty: 50000,
                due_date: '28/06/2025',
                status: 'Active',
                wo_status: 'Active',
                so_status: 'Outstanding',
                acr_number: '000506',
                mcs_number: '0063879',
                model: '22819',
                comp_number: 'Main',
                part_number: 'BOX',
                pd: 'PUNCH',
                pcs_per_unit: '1/Pcs',
                ied_x: '0',
                ied_y: '0',
                total_received: 0,
                remaining_qty: 50000
            }
        ];
    }
};

const loadWarehouseLocations = async () => {
    try {
        const response = await axios.get('/api/warehouse-locations');
        warehouseLocations.value = response.data;
    } catch (error) {
        console.error('Error loading warehouse locations:', error);
        // Fallback demo data
        warehouseLocations.value = [
            { id: 1, code: 'A01', description: 'A01.1', type: '1-Normal', to_lock_delivery_order: false, to_lock_stock_out_adjustment: false, to_lock_warehouse_transfer: false },
            { id: 2, code: 'A02', description: 'A01.2', type: '1-Normal', to_lock_delivery_order: false, to_lock_stock_out_adjustment: false, to_lock_warehouse_transfer: false },
            { id: 3, code: 'A03', description: 'A02.1', type: '1-Normal', to_lock_delivery_order: false, to_lock_stock_out_adjustment: false, to_lock_warehouse_transfer: false },
            { id: 4, code: 'A04', description: 'A02.1', type: '1-Normal', to_lock_delivery_order: false, to_lock_stock_out_adjustment: false, to_lock_warehouse_transfer: false },
            { id: 5, code: 'A05', description: 'A02.2', type: '1-Normal', to_lock_delivery_order: false, to_lock_stock_out_adjustment: false, to_lock_warehouse_transfer: false },
            { id: 6, code: 'A06', description: 'A02.3', type: '1-Normal', to_lock_delivery_order: false, to_lock_stock_out_adjustment: false, to_lock_warehouse_transfer: false },
            { id: 7, code: 'A07', description: 'A03.1', type: '1-Normal', to_lock_delivery_order: false, to_lock_stock_out_adjustment: false, to_lock_warehouse_transfer: false },
            { id: 8, code: 'A08', description: 'A03.2', type: '1-Normal', to_lock_delivery_order: false, to_lock_stock_out_adjustment: false, to_lock_warehouse_transfer: false },
            { id: 9, code: 'A09', description: 'A03.3', type: '1-Normal', to_lock_delivery_order: false, to_lock_stock_out_adjustment: false, to_lock_warehouse_transfer: false },
            { id: 10, code: 'A10', description: 'A04.1', type: '1-Normal', to_lock_delivery_order: false, to_lock_stock_out_adjustment: false, to_lock_warehouse_transfer: false }
        ];
    }
};

const loadAnalysisCodes = async () => {
    try {
        const response = await axios.get('/api/material-management/analysis-codes');
        analysisCodes.value = response.data.map(item => ({
            id: item.code,
            code: item.code,
            name: item.name,
            grouping: item.group || item.group2 || 'N/A'
        }));
    } catch (error) {
        console.error('Error loading analysis codes:', error);
        // Fallback demo data
        analysisCodes.value = [
            { id: 1, code: 'S101', name: 'FG STOCK IN', grouping: 'FGSI' }
        ];
    }
};

const selectWorkOrder = (wo) => {
    selectedWorkOrder.value = wo;
    woPrefix.value = wo.wo_number.split('-')[0];
    woMiddle.value = wo.wo_number.split('-')[1];
    woSuffix.value = wo.wo_number.split('-')[2];
    closeWorkOrderModal();
};

const selectWarehouse = (location) => {
    if (location) {
        stockInForm.warehouse = location.code;
        stockInForm.warehouse_name = location.description;
    }
    closeWarehouseModal();
};

const selectAnalysis = (analysis) => {
    if (analysis) {
        stockInForm.analysis = analysis.code;
        stockInForm.analysis_name = analysis.name;
    }
    closeAnalysisModal();
};

const sortWarehouseByCode = () => {
    warehouseLocations.value.sort((a, b) => a.code.localeCompare(b.code));
};

const sortWarehouseByName = () => {
    warehouseLocations.value.sort((a, b) => a.description.localeCompare(b.description));
};

const getStatusClass = (status) => {
    switch (status.toLowerCase()) {
        case 'active':
            return 'bg-green-100 text-green-800';
        case 'outstanding':
            return 'bg-orange-100 text-orange-800';
        case 'completed':
            return 'bg-blue-100 text-blue-800';
        case 'cancelled':
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
};

const saveStockIn = async () => {
    try {
        const data = {
            work_order_id: selectedWorkOrder.value.id,
            wo_number: selectedWorkOrder.value.wo_number,
            entry_ref: stockInForm.entry_ref,
            entry_qty: stockInForm.entry_qty,
            entry_date: stockInForm.entry_date,
            warehouse: stockInForm.warehouse,
            analysis: stockInForm.analysis,
            remark: stockInForm.remark
        };

        const response = await axios.post('/api/fg-stock-in-wo', data);
        
        if (response.data.success) {
            alert('Stock-in saved successfully!');
            resetForm();
        }
    } catch (error) {
        console.error('Error saving stock-in:', error);
        alert('Error saving stock-in: ' + (error.response?.data?.message || error.message));
    }
};

const resetForm = () => {
    Object.keys(stockInForm).forEach(key => {
        if (key === 'entry_date') {
            stockInForm[key] = new Date().toISOString().slice(0, 10);
        } else {
            stockInForm[key] = '';
        }
    });
    selectedWorkOrder.value = null;
    woPrefix.value = '';
    woMiddle.value = '';
    woSuffix.value = '';
};

onMounted(() => {
    // Initialize
});
</script>

<style scoped>
.text-shadow {
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.animate-ping-slow {
    animation: ping 3s cubic-bezier(0, 0, 0.2, 1) infinite;
}

.animation-delay-500 {
    animation-delay: 500ms;
}

@keyframes ping {
    75%, 100% {
        transform: scale(2);
        opacity: 0;
    }
}
</style> 