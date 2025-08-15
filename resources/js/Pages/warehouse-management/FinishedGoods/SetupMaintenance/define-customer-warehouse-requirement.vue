<template>
    <AppLayout :header="'Define Customer Warehouse Requirement'">
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
                    <i class="fas fa-warehouse text-white text-2xl z-10"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-1 text-shadow">Define Customer Warehouse Requirement</h2>
                    <p class="text-blue-100 max-w-2xl">Manage customer warehouse requirements for finished goods</p>
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
                                <i class="fas fa-warehouse text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800">Customer Warehouse Requirement</h3>
                        </div>

                        <!-- Form content -->
                        <form @submit.prevent="saveRequirement" class="space-y-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-2">
                                    <div>
                                        <label for="customer_code" class="block text-sm font-medium text-gray-700 mb-1">
                                            Customer Code:
                                        </label>
                                        <div class="flex">
                                            <input 
                                                type="text" 
                                                id="customer_code" 
                                                v-model="form.customer_code"
                                                readonly
                                                class="w-32 px-3 py-1 border border-gray-300 bg-gray-50 rounded-l-md"
                                            />
                                            <button 
                                                type="button"
                                                @click="openCustomerModal"
                                                class="px-2 py-1 border border-l-0 border-gray-300 bg-blue-100 hover:bg-blue-200 rounded-r-md"
                                            >
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div v-if="form.customer_code" class="ml-2">
                                        <input 
                                            type="text" 
                                            v-model="form.customer_name" 
                                            readonly
                                            class="w-full px-3 py-1 border border-gray-300 bg-gray-50 rounded-md"
                                        />
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Record:
                                    </label>
                                    <button 
                                        type="button"
                                        @click="openCustomerModal"
                                        class="px-3 py-1 bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-md text-sm"
                                    >
                                        Select
                                    </button>
                                </div>
                            </div>
                            
                            <div v-if="form.customer_code" class="space-y-6">
                                <!-- Tab Navigation -->
                                <div class="bg-gray-200 border border-gray-300">
                                    <div class="flex">
                                        <button 
                                            type="button"
                                            @click="activeTab = 'fg'"
                                            class="px-4 py-1 text-sm border-r border-gray-300"
                                            :class="activeTab === 'fg' ? 'bg-white' : 'bg-gray-200 hover:bg-gray-100'"
                                            style="min-width: 60px;"
                                        >
                                            FG
                                        </button>
                                        <button 
                                            type="button"
                                            @click="activeTab = 'do'"
                                            class="px-4 py-1 text-sm border-r border-gray-300"
                                            :class="activeTab === 'do' ? 'bg-white' : 'bg-gray-200 hover:bg-gray-100'"
                                            style="min-width: 60px;"
                                        >
                                            DO
                                        </button>
                                        <button 
                                            type="button"
                                            @click="activeTab = 'do_form'"
                                            class="px-4 py-1 text-sm border-r border-gray-300"
                                            :class="activeTab === 'do_form' ? 'bg-white' : 'bg-gray-200 hover:bg-gray-100'"
                                            style="min-width: 80px;"
                                        >
                                            DO Form
                                        </button>
                                        <button 
                                            type="button"
                                            @click="activeTab = 'do_qty'"
                                            class="px-4 py-1 text-sm border-r border-gray-300"
                                            :class="activeTab === 'do_qty' ? 'bg-white' : 'bg-gray-200 hover:bg-gray-100'"
                                            style="min-width: 80px;"
                                        >
                                            DO QTY
                                        </button>
                                        <button 
                                            type="button"
                                            @click="activeTab = 'reject_qty'"
                                            class="px-4 py-1 text-sm"
                                            :class="activeTab === 'reject_qty' ? 'bg-white' : 'bg-gray-200 hover:bg-gray-100'"
                                            style="min-width: 80px;"
                                        >
                                            Reject Qty
                                        </button>
                                    </div>
                                </div>

                                <!-- Tab Content -->
                                <div class="mt-4">
                                    <!-- FG Tab Content -->
                                    <div v-if="activeTab === 'fg'" class="space-y-6">
                                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                <div>
                                                    <label for="default_warehouse_normal" class="block text-sm font-medium text-gray-700 mb-1">
                                                        Default Warehouse [Normal]:
                                                    </label>
                                                    <div class="flex">
                                                        <input 
                                                            type="text" 
                                                            id="default_warehouse_normal" 
                                                            v-model="form.default_warehouse_normal"
                                                            readonly
                                                            class="w-full px-3 py-1 border border-gray-300 bg-gray-50 rounded-l-md"
                                                        />
                                                        <button 
                                                            type="button"
                                                            @click="openWarehouseModal('normal')"
                                                            class="px-2 py-1 border border-l-0 border-gray-300 bg-blue-100 hover:bg-blue-200 rounded-r-md"
                                                        >
                                                            <i class="fas fa-search"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                
                                                <div>
                                                    <label for="default_warehouse_excess" class="block text-sm font-medium text-gray-700 mb-1">
                                                        Default Warehouse [Excess]:
                                                    </label>
                                                    <div class="flex">
                                                        <input 
                                                            type="text" 
                                                            id="default_warehouse_excess" 
                                                            v-model="form.default_warehouse_excess"
                                                            readonly
                                                            class="w-full px-3 py-1 border border-gray-300 bg-gray-50 rounded-l-md"
                                                        />
                                                        <button 
                                                            type="button"
                                                            @click="openWarehouseModal('excess')"
                                                            class="px-2 py-1 border border-l-0 border-gray-300 bg-blue-100 hover:bg-blue-200 rounded-r-md"
                                                        >
                                                            <i class="fas fa-search"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                
                                                <div>
                                                    <label for="default_warehouse_transit" class="block text-sm font-medium text-gray-700 mb-1">
                                                        Default Warehouse [Transit]:
                                                    </label>
                                                    <div class="flex">
                                                        <input 
                                                            type="text" 
                                                            id="default_warehouse_transit" 
                                                            v-model="form.default_warehouse_transit"
                                                            readonly
                                                            class="w-full px-3 py-1 border border-gray-300 bg-gray-50 rounded-l-md"
                                                        />
                                                        <button 
                                                            type="button"
                                                            @click="openWarehouseModal('transit')"
                                                            class="px-2 py-1 border border-l-0 border-gray-300 bg-blue-100 hover:bg-blue-200 rounded-r-md"
                                                        >
                                                            <i class="fas fa-search"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- DO Tab Content -->
                                    <div v-if="activeTab === 'do'" class="space-y-6">
                                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label for="delivery_order_format" class="block text-sm font-medium text-gray-700 mb-1">
                                                        Delivery Order Format:
                                                    </label>
                                                    <div class="flex">
                                                        <input 
                                                            type="text" 
                                                            id="delivery_order_format" 
                                                            v-model="form.delivery_order_format" 
                                                            readonly
                                                            class="w-full px-3 py-1 border border-gray-300 bg-gray-50 rounded-l-md"
                                                        />
                                                        <button 
                                                            type="button"
                                                            @click="openDeliveryOrderFormatModal"
                                                            class="px-2 py-1 border border-l-0 border-gray-300 bg-blue-100 hover:bg-blue-200 rounded-r-md"
                                                        >
                                                            <i class="fas fa-search"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div>
                                                    <label for="bar_code_sticker" class="block text-sm font-medium text-gray-700 mb-1">
                                                        Bar Code Sticker:
                                                    </label>
                                                    <select 
                                                        id="bar_code_sticker" 
                                                        v-model="form.bar_code_sticker" 
                                                        class="w-full px-3 py-1 border border-gray-300 rounded-md"
                                                    >
                                                        <option value="N-N/Applicable">N-N/Applicable</option>
                                                        <option value="1-Format 1">1-Format 1</option>
                                                        <option value="2-Format 2">2-Format 2</option>
                                                        <option value="N-N/Applicable">N-N/Applicable</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="mt-4">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                    Bundle Format:
                                                </label>
                                                <div class="space-y-2">
                                                    <label class="inline-flex items-center">
                                                        <input type="radio" class="form-radio" name="bundle_format" value="0" v-model="form.bundle_format">
                                                        <span class="ml-2 text-gray-700">0-Not Applicable</span>
                                                    </label>
                                                    <br>
                                                    <label class="inline-flex items-center">
                                                        <input type="radio" class="form-radio" name="bundle_format" value="1" v-model="form.bundle_format">
                                                        <span class="ml-2 text-gray-700">1-[BDLSxdTY]+[QTY]</span>
                                                    </label>
                                                    <br>
                                                    <label class="inline-flex items-center">
                                                        <input type="radio" class="form-radio" name="bundle_format" value="2" v-model="form.bundle_format">
                                                        <span class="ml-2 text-gray-700">2-[BDLSxdTY]+[BDLxdTY]</span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                                <div>
                                                    <label class="flex items-center space-x-2 cursor-pointer">
                                                        <input type="checkbox" v-model="form.destination_change" class="form-checkbox rounded text-indigo-600 focus:ring-indigo-500">
                                                        <span class="text-gray-700">Destination Change</span>
                                                    </label>
                                                    <span class="text-xs text-gray-500 ml-6">*For D/Order Multiple Item</span>
                                                </div>
                                                
                                                <div>
                                                    <label class="flex items-center space-x-2 cursor-pointer">
                                                        <input type="checkbox" v-model="form.multi_line_quantity" class="form-checkbox rounded text-indigo-600 focus:ring-indigo-500">
                                                        <span class="text-gray-700">Multi-Line Quantity</span>
                                                    </label>
                                                </div>
                                                
                                                <div>
                                                    <label class="flex items-center space-x-2 cursor-pointer">
                                                        <input type="checkbox" v-model="form.product_group_tie_up" class="form-checkbox rounded text-indigo-600 focus:ring-indigo-500">
                                                        <span class="text-gray-700">Product Group Tie-Up</span>
                                                    </label>
                                                    <span class="text-xs text-gray-500 ml-6">*Customer Sales Index Profile</span>
                                                </div>
                                                
                                                <div>
                                                    <label class="flex items-center space-x-2 cursor-pointer">
                                                        <input type="checkbox" v-model="form.unapplied_fg_goods" class="form-checkbox rounded text-indigo-600 focus:ring-indigo-500">
                                                        <span class="text-gray-700">Unapplied F/Goods</span>
                                                    </label>
                                                    <span class="text-xs text-gray-500 ml-6">*For D/Order with multiple items</span>
                                                </div>
                                                
                                                <div>
                                                    <label class="flex items-center space-x-2 cursor-pointer">
                                                        <input type="checkbox" v-model="form.amend_do_un_qty" class="form-checkbox rounded text-indigo-600 focus:ring-indigo-500">
                                                        <span class="text-gray-700">Amend D/O Un-Qty</span>
                                                    </label>
                                                    <span class="text-xs text-gray-500 ml-6">*Amend Unapplied Qty before Reconcile</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- DO Form Tab Content -->
                                    <div v-if="activeTab === 'do_form'" class="space-y-6">
                                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                            <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                                                <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full mr-2 shadow-sm">
                                                    <i class="fas fa-file-alt text-white text-xs"></i>
                                                </span>
                                                DO Form Settings
                                            </h4>
                                            
                                            <div class="space-y-4">
                                                <!-- Closed Sales Order -->
                                                <div class="border border-gray-200 p-4 rounded-md">
                                                    <div class="text-sm font-semibold text-gray-700 mb-3">Closed Sales Order:</div>
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" class="form-radio" name="closed_sales_order" value="Y-Allow delivery with no control" v-model="form.closed_sales_order_delivery">
                                                            <span class="ml-2 text-gray-700">Y-Allow delivery with no control</span>
                                                        </label>
                                                        
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" class="form-radio" name="closed_sales_order" value="N-No delivery allowed" v-model="form.closed_sales_order_delivery">
                                                            <span class="ml-2 text-gray-700">N-No delivery allowed</span>
                                                        </label>
                                                        
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" class="form-radio" name="closed_sales_order" value="R-Reject if over-delivered" v-model="form.closed_sales_order_delivery">
                                                            <span class="ml-2 text-gray-700">R-Reject if over-delivered</span>
                                                        </label>
                                                        
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" class="form-radio" name="closed_sales_order" value="P-Prompt when over-delivered" v-model="form.closed_sales_order_delivery">
                                                            <span class="ml-2 text-gray-700">P-Prompt when over-delivered</span>
                                                        </label>
                                                        
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" class="form-radio" name="closed_sales_order" value="D-Reject if over-delivered with allowance" v-model="form.closed_sales_order_delivery">
                                                            <span class="ml-2 text-gray-700">D-Reject if over-delivered with allowance</span>
                                                        </label>
                                                        
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" class="form-radio" name="closed_sales_order" value="A-Prompt when over-delivered with allowance" v-model="form.closed_sales_order_delivery">
                                                            <span class="ml-2 text-gray-700">A-Prompt when over-delivered with allowance</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                
                                                <!-- Completed Sales Order -->
                                                <div class="border border-gray-200 p-4 rounded-md">
                                                    <div class="text-sm font-semibold text-gray-700 mb-3">Completed Sales Order:</div>
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" class="form-radio" name="completed_sales_order" value="Y-Allow delivery with no control" v-model="form.completed_sales_order_delivery">
                                                            <span class="ml-2 text-gray-700">Y-Allow delivery with no control</span>
                                                        </label>
                                                        
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" class="form-radio" name="completed_sales_order" value="N-No delivery allowed" v-model="form.completed_sales_order_delivery">
                                                            <span class="ml-2 text-gray-700">N-No delivery allowed</span>
                                                        </label>
                                                        
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" class="form-radio" name="completed_sales_order" value="D-Reject if over-delivered with allowance" v-model="form.completed_sales_order_delivery">
                                                            <span class="ml-2 text-gray-700">D-Reject if over-delivered with allowance</span>
                                                        </label>
                                                        
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" class="form-radio" name="completed_sales_order" value="A-Prompt when over-delivered with allowance" v-model="form.completed_sales_order_delivery">
                                                            <span class="ml-2 text-gray-700">A-Prompt when over-delivered with allowance</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                
                                                <!-- Outstanding/Partial S/O -->
                                                <div class="border border-gray-200 p-4 rounded-md">
                                                    <div class="text-sm font-semibold text-gray-700 mb-3">Outstanding/Partial S/O:</div>
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" class="form-radio" name="outstanding_partial" value="R-Reject if over-delivered" v-model="form.outstanding_partial_delivery">
                                                            <span class="ml-2 text-gray-700">R-Reject if over-delivered</span>
                                                        </label>
                                                        
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" class="form-radio" name="outstanding_partial" value="P-Prompt when over-delivered" v-model="form.outstanding_partial_delivery">
                                                            <span class="ml-2 text-gray-700">P-Prompt when over-delivered</span>
                                                        </label>
                                                        
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" class="form-radio" name="outstanding_partial" value="D-Reject if over-delivered with allowance" v-model="form.outstanding_partial_delivery">
                                                            <span class="ml-2 text-gray-700">D-Reject if over-delivered with allowance</span>
                                                        </label>
                                                        
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" class="form-radio" name="outstanding_partial" value="A-Prompt when over-delivered with allowance" v-model="form.outstanding_partial_delivery">
                                                            <span class="ml-2 text-gray-700">A-Prompt when over-delivered with allowance</span>
                                                        </label>
                                                        
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" class="form-radio" name="outstanding_partial" value="N-No Control" v-model="form.outstanding_partial_delivery">
                                                            <span class="ml-2 text-gray-700">N-No Control</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- DO QTY Tab Content -->
                                    <div v-if="activeTab === 'do_qty'" class="space-y-6">
                                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                            <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                                                <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full mr-2 shadow-sm">
                                                    <i class="fas fa-calculator text-white text-xs"></i>
                                                </span>
                                                DO QTY Settings
                                            </h4>
                                            
                                            <div class="border border-gray-200 p-4 rounded-md">
                                                <div class="text-sm font-semibold text-gray-700 mb-3">DO + Allowance</div>
                                                <div class="space-y-3">
                                                    <div>
                                                        <label for="allow_qty" class="block text-sm font-medium text-gray-700 mb-1">Allow Qty:</label>
                                                        <input 
                                                            type="number"
                                                            id="allow_qty"
                                                            v-model="form.allow_qty"
                                                            class="w-20 px-3 py-1 border border-gray-300 rounded-md"
                                                        /> Pcs/Roll/Kg
                                                    </div>
                                                    
                                                    <div class="space-y-2">
                                                        <div class="text-sm font-medium text-gray-700 mb-1">Allow Type:</div>
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" class="form-radio" name="allow_type" value="1-Absolute Quantity" v-model="form.allow_type">
                                                            <span class="ml-2 text-gray-700">1-Absolute Quantity</span>
                                                        </label>
                                                        <br>
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" class="form-radio" name="allow_type" value="2-Percentage of Order Qty" v-model="form.allow_type">
                                                            <span class="ml-2 text-gray-700">2-Percentage of Order Qty</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Reject Qty Tab Content -->
                                    <div v-if="activeTab === 'reject_qty'" class="space-y-6">
                                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                            <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                                                <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-red-500 to-rose-500 rounded-full mr-2 shadow-sm">
                                                    <i class="fas fa-ban text-white text-xs"></i>
                                                </span>
                                                Reject Qty Settings
                                            </h4>
                                            
                                            <div class="border border-gray-200 p-4 rounded-md">
                                                <div class="text-sm font-semibold text-gray-700 mb-3">Less from Invoice:</div>
                                                <div class="space-y-3">
                                                    <label class="inline-flex items-center">
                                                        <input type="radio" class="form-radio" name="less_from_invoice" value="Y-Billable Qty = D/O Qty - Rejection Qty" v-model="form.less_from_invoice">
                                                        <span class="ml-2 text-gray-700">Y-Billable Qty = D/O Qty - Rejection Qty</span>
                                                    </label>
                                                    <br>
                                                    <label class="inline-flex items-center">
                                                        <input type="radio" class="form-radio" name="less_from_invoice" value="N-Billable Qty = D/O Qty" v-model="form.less_from_invoice">
                                                        <span class="ml-2 text-gray-700">N-Billable Qty = D/O Qty</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-end pt-4 border-t border-gray-200">
                                    <button type="submit" 
                                        class="px-4 py-1 bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-md text-sm mr-2"
                                    >
                                        {{ isEditMode ? 'Update' : 'Save' }}
                                    </button>
                                    <button v-if="isEditMode" type="button" @click="deleteRequirement" 
                                        class="px-4 py-1 bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-md text-sm"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </form>
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
                            <h3 class="text-lg font-semibold text-gray-800">Quick Tips</h3>
                        </div>

                        <div class="space-y-4">
                            <div class="p-4 bg-teal-50 rounded-lg">
                                <h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2 flex items-center">
                                    <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-400 to-indigo-400 rounded-full mr-2 shadow-sm mt-0.5">
                                        <i class="fas fa-question text-white text-xs"></i>
                                    </span>
                                    How to Use
                                </h4>
                                <ul class="text-sm text-gray-600 space-y-2">
                                    <li class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-400 to-indigo-400 rounded-full mr-2 shadow-sm mt-0.5">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </span>
                                        <span>Click <i class="fas fa-table"></i> to select a customer.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full mr-2 shadow-sm mt-0.5">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </span>
                                        <span>Set warehouse locations for the customer.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-amber-400 to-orange-400 rounded-full mr-2 shadow-sm mt-0.5">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </span>
                                        <span>Configure delivery order settings if needed.</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Account Modal -->
        <CustomerAccountModal
            :show="isCustomerModalOpen"
            :customerAccounts="customerAccounts"
            @close="closeCustomerModal"
            @select="handleCustomerSelect"
        />

        <!-- Warehouse Location Modal -->
        <CustomerWarehouseRequirementModal
            :show="isWarehouseModalOpen"
            :warehouseLocations="warehouseLocations"
            @close="closeWarehouseModal"
            @select-location="handleWarehouseSelect"
            @sort-by-code="sortWarehouseLocations('code')"
            @sort-by-name="sortWarehouseLocations('description')"
        />

        <!-- Delivery Order Format Modal -->
        <DeliveryOrderFormatModal
            :show="isDeliveryOrderFormatModalOpen"
            :deliveryOrderFormats="deliveryOrderFormats"
            @close="closeDeliveryOrderFormatModal"
            @format-selected="handleDeliveryOrderFormatSelect"
            @sort-by-code="sortDeliveryOrderFormats('code')"
            @sort-by-name="sortDeliveryOrderFormats('name')"
        />
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, reactive, onMounted, watch } from 'vue';
import CustomerAccountModal from '@/Components/customer-account-modal.vue';
import CustomerWarehouseRequirementModal from '@/Components/CustomerWarehouseRequirementModal.vue';
import DeliveryOrderFormatModal from '@/Components/DeliveryOrderFormatModal.vue';
import axios from 'axios';
import debounce from 'lodash/debounce';

// State variables
const customerAccounts = ref([]);
const warehouseLocations = ref([]);
const deliveryOrderFormats = ref([]);
const isCustomerModalOpen = ref(false);
const isWarehouseModalOpen = ref(false);
const isDeliveryOrderFormatModalOpen = ref(false);
const selectedWarehouseType = ref('normal'); // 'normal', 'excess', or 'transit'
const loading = ref(false);
const error = ref(null);
const isEditMode = ref(false);
const showDOSettings = ref(false);
const activeTab = ref('fg'); // 'fg', 'do', 'do_form', 'do_qty', 'reject_qty'

// Form state
const form = reactive({
    customer_code: '',
    customer_name: '',
    default_warehouse_normal: '',
    default_warehouse_excess: '',
    default_warehouse_transit: '',
    delivery_order_format: '',
    bar_code_sticker: 'N-N/Applicable',
    bundle_format: '0',
    destination_change: false,
    multi_line_quantity: false,
    product_group_tie_up: false,
    unapplied_fg_goods: false,
    amend_do_un_qty: false,
    closed_sales_order_delivery: 'N-No delivery allowed',
    completed_sales_order_delivery: 'N-No delivery allowed',
    outstanding_partial_delivery: 'D-Reject if over-delivered with allowance',
    allow_qty: 0,
    allow_type: '1-Absolute Quantity',
    less_from_invoice: 'Y-Billable Qty = D/O Qty - Rejection Qty',
    default_copies: 1
});

// Reset form
const resetForm = () => {
    form.customer_code = '';
    form.customer_name = '';
    form.default_warehouse_normal = '';
    form.default_warehouse_excess = '';
    form.default_warehouse_transit = '';
    form.delivery_order_format = '';
    form.bar_code_sticker = 'N-N/Applicable';
    form.bundle_format = '0';
    form.destination_change = false;
    form.multi_line_quantity = false;
    form.product_group_tie_up = false;
    form.unapplied_fg_goods = false;
    form.amend_do_un_qty = false;
    form.closed_sales_order_delivery = 'N-No delivery allowed';
    form.completed_sales_order_delivery = 'N-No delivery allowed';
    form.outstanding_partial_delivery = 'D-Reject if over-delivered with allowance';
    form.allow_qty = 0;
    form.allow_type = '1-Absolute Quantity';
    form.less_from_invoice = 'Y-Billable Qty = D/O Qty - Rejection Qty';
    form.default_copies = 1;
    isEditMode.value = false;
    showDOSettings.value = false;
};

// API calls
const fetchCustomerAccounts = async () => {
    try {
        loading.value = true;
        const response = await axios.get('/api/warehouse-requirements/customers');
        customerAccounts.value = response.data;
        loading.value = false;
    } catch (err) {
        console.error('Error fetching customer accounts:', err);
        error.value = 'Failed to load customer accounts';
        loading.value = false;
    }
};

const fetchWarehouseLocations = async () => {
    try {
        loading.value = true;
        const response = await axios.get('/api/warehouse-requirements/warehouse-locations');
        warehouseLocations.value = response.data;
        loading.value = false;
    } catch (err) {
        console.error('Error fetching warehouse locations:', err);
        error.value = 'Failed to load warehouse locations';
        loading.value = false;
    }
};

const fetchDeliveryOrderFormats = async () => {
    try {
        loading.value = true;
        const response = await axios.get('/api/warehouse-requirements/delivery-order-formats');
        deliveryOrderFormats.value = response.data;
        loading.value = false;
    } catch (err) {
        console.error('Error fetching delivery order formats:', err);
        error.value = 'Failed to load delivery order formats';
        loading.value = false;
    }
};

const fetchCustomerWarehouseRequirement = async (customerCode) => {
    try {
        loading.value = true;
        const response = await axios.get(`/api/customer-warehouse-requirements/${customerCode}`);
        
        if (response.data) {
            // Populate form with data
            Object.assign(form, response.data);
            isEditMode.value = true;
        }
        
        loading.value = false;
    } catch (err) {
        if (err.response && err.response.status === 404) {
            // No requirement found for this customer, that's ok
            isEditMode.value = false;
        } else {
            console.error('Error fetching customer warehouse requirement:', err);
            error.value = 'Failed to load customer warehouse requirement';
        }
        loading.value = false;
    }
};

// Modal controls
const openCustomerModal = () => {
    isCustomerModalOpen.value = true;
};

const closeCustomerModal = () => {
    isCustomerModalOpen.value = false;
};

const openWarehouseModal = (type) => {
    selectedWarehouseType.value = type;
    isWarehouseModalOpen.value = true;
};

const closeWarehouseModal = () => {
    isWarehouseModalOpen.value = false;
};

const openDeliveryOrderFormatModal = () => {
    isDeliveryOrderFormatModalOpen.value = true;
};

const closeDeliveryOrderFormatModal = () => {
    isDeliveryOrderFormatModalOpen.value = false;
};

// Selection handlers
const handleCustomerSelect = (customer) => {
    form.customer_code = customer.customer_code;
    form.customer_name = customer.customer_name;
    
    // Check if this customer already has warehouse requirements
    fetchCustomerWarehouseRequirement(customer.customer_code);
};

const handleWarehouseSelect = (location) => {
    if (selectedWarehouseType.value === 'normal') {
        form.default_warehouse_normal = location.code;
    } else if (selectedWarehouseType.value === 'excess') {
        form.default_warehouse_excess = location.code;
    } else if (selectedWarehouseType.value === 'transit') {
        form.default_warehouse_transit = location.code;
    }
};

const handleDeliveryOrderFormatSelect = (format) => {
    form.delivery_order_format = format.code;
};

const sortWarehouseLocations = (key) => {
    warehouseLocations.value.sort((a, b) => {
        if (a[key] < b[key]) return -1;
        if (a[key] > b[key]) return 1;
        return 0;
    });
};

const sortDeliveryOrderFormats = (key) => {
    deliveryOrderFormats.value.sort((a, b) => {
        if (a[key] < b[key]) return -1;
        if (a[key] > b[key]) return 1;
        return 0;
    });
};

// Form submission
const saveRequirement = async () => {
    if (!form.customer_code) {
        error.value = 'Please select a customer first';
        return;
    }
    
    try {
        loading.value = true;
        
        if (isEditMode.value) {
            // Update existing
            await axios.put(`/api/customer-warehouse-requirements/${form.customer_code}`, form);
            alert('Customer warehouse requirement updated successfully!');
        } else {
            // Create new
            await axios.post('/api/customer-warehouse-requirements', form);
            alert('Customer warehouse requirement created successfully!');
            isEditMode.value = true;
        }
        
        loading.value = false;
    } catch (err) {
        console.error('Error saving customer warehouse requirement:', err);
        error.value = 'Failed to save customer warehouse requirement';
        loading.value = false;
        alert('Error: ' + (err.response?.data?.message || err.message));
    }
};

const deleteRequirement = async () => {
    if (!form.customer_code) {
        error.value = 'No customer selected';
        return;
    }
    
    if (!confirm('Are you sure you want to delete this warehouse requirement?')) {
        return;
    }
    
    try {
        loading.value = true;
        await axios.delete(`/api/customer-warehouse-requirements/${form.customer_code}`);
        alert('Customer warehouse requirement deleted successfully!');
        resetForm();
        loading.value = false;
    } catch (err) {
        console.error('Error deleting customer warehouse requirement:', err);
        error.value = 'Failed to delete customer warehouse requirement';
        loading.value = false;
        alert('Error: ' + (err.response?.data?.message || err.message));
    }
};

const toggleDOSettings = () => {
    showDOSettings.value = !showDOSettings.value;
};

// Lifecycle hooks
onMounted(() => {
    fetchCustomerAccounts();
    fetchWarehouseLocations();
    fetchDeliveryOrderFormats();
});
</script>

<style scoped>
/* Component-specific styles */
.form-radio {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  display: inline-block;
  vertical-align: middle;
  background-origin: border-box;
  user-select: none;
  flex-shrink: 0;
  border-radius: 100%;
  height: 1.25em;
  width: 1.25em;
  color: #4f46e5; /* indigo-600 */
  background-color: #ffffff;
  border-color: #d1d5db; /* gray-300 */
  border-width: 1px;
}

.form-radio:checked {
  background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3ccircle cx='8' cy='8' r='3'/%3e%3c/svg%3e");
  background-size: 100% 100%;
  background-position: center;
  background-repeat: no-repeat;
  background-color: currentColor;
}

.form-radio:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(100, 116, 139, 0.45); /* slate-400 with opacity */
}

.form-checkbox {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  display: inline-block;
  vertical-align: middle;
  background-origin: border-box;
  user-select: none;
  flex-shrink: 0;
  height: 1.25em;
  width: 1.25em;
  color: #4f46e5; /* indigo-600 */
  background-color: #ffffff;
  border-color: #d1d5db; /* gray-300 */
  border-width: 1px;
  border-radius: 0.25rem;
}

.form-checkbox:checked {
  background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3e%3c/svg%3e");
  background-size: 100% 100%;
  background-position: center;
  background-repeat: no-repeat;
  background-color: currentColor;
}

.form-checkbox:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(100, 116, 139, 0.45); /* slate-400 with opacity */
}
</style> 