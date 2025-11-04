<template>
    <AppLayout header="Define User Access Permission">
        <Head title="Define User Access Permission" />
        <div class="container mx-auto px-4 py-8">
            <div class="bg-white rounded-xl shadow-lg p-8">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                            <ShieldCheckIcon class="h-8 w-8 text-blue-600 mr-3" />
                            Define User Access Permission
                        </h1>
                        <p class="text-gray-600 mt-2">Atur permission untuk user yang sudah terdaftar</p>
                    </div>
                </div>

                <!-- Search User Section -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-xl mb-8 border border-blue-200">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <UserIcon class="h-6 w-6 text-blue-600 mr-2" />
                        Cari User
                    </h2>
                    <form @submit.prevent="searchUser" class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                User ID
                                <span class="text-xs text-gray-500">(Contoh: ADMIN001, USER001)</span>
                            </label>
                            <input type="text" 
                                   v-model="searchForm.user_id" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                   placeholder="Masukkan User ID untuk dicari..."
                                   required>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" 
                                    :disabled="isSearching"
                                    class="bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white px-6 py-3 rounded-lg font-medium transition-all flex items-center">
                                <SearchIcon class="h-5 w-5 mr-2" />
                                {{ isSearching ? 'Mencari...' : 'Cari User' }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Search Results -->
                <div v-if="searchMessage" class="mb-6 p-4 rounded-lg" :class="searchMessageClass">
                    <div class="flex items-center">
                        <component :is="searchMessageIcon" class="h-5 w-5 mr-2" />
                        {{ searchMessage }}
                    </div>
                </div>

                <!-- User Permission Form -->
                <div v-if="foundUser" class="bg-gradient-to-br from-gray-50 to-blue-50 p-8 rounded-xl border border-gray-200">
                    <!-- User Info Header -->
                    <div class="bg-white p-6 rounded-xl mb-8 shadow-sm border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-xl mr-4">
                                    {{ foundUser.official_name.charAt(0).toUpperCase() }}
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900">{{ foundUser.official_name }}</h3>
                                    <p class="text-gray-600">{{ foundUser.user_id }} • {{ foundUser.official_title || 'No Title' }}</p>
                                    <p class="text-sm text-gray-500 mt-1">Status: <span class="font-medium" :class="foundUser.status === 'A' ? 'text-green-600' : 'text-red-600'">{{ foundUser.status === 'A' ? 'Active' : 'Inactive' }}</span></p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Total Permissions</p>
                                <p class="text-3xl font-bold text-blue-600">{{ selectedPermissionsCount }}</p>
                                <p class="text-xs text-gray-400">dari {{ totalPermissionsCount }}</p>
                            </div>
                        </div>
                    </div>

                    <form @submit.prevent="savePermissions" class="space-y-8">

                        <!-- Select All Toggle -->
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-8">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                                    <p class="text-sm text-gray-600">Pilih semua atau hapus semua permission</p>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <SwitchGroup>
                                        <div class="flex items-center">
                                            <SwitchLabel class="mr-3 text-sm font-medium text-gray-700">Select All</SwitchLabel>
                                            <Switch v-model="selectAllPermissions" @click="toggleAllPermissions"
                                                :class="[selectAllPermissions ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2']">
                                                <span :class="[selectAllPermissions ? 'translate-x-6' : 'translate-x-1', 'inline-block h-4 w-4 transform rounded-full bg-white transition-transform']" />
                                            </Switch>
                                        </div>
                                    </SwitchGroup>
                                </div>
                            </div>
                        </div>

                        <!-- Permission Categories -->
                        <div class="space-y-8">
                            <!-- Dashboard -->
                            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                                <h4 class="font-medium text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-tachometer-alt w-5 h-5 mr-2 text-blue-500"></i>
                                    Dashboard
                                </h4>
                                <div class="space-y-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" v-model="form.permissions.dashboard" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700">Dashboard</span>
                                    </label>
                                </div>
                            </div>

                            <!-- System Manager -->
                            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                                <h4 class="font-medium text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-cogs w-5 h-5 mr-2 text-blue-500"></i>
                                    System Manager
                                </h4>
                                <div class="space-y-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" v-model="form.permissions.system_manager" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700 font-medium">System Manager (Main)</span>
                                    </label>
                                    <div class="ml-6 space-y-2">
                                        <label class="flex items-center">
                                            <input type="checkbox" v-model="form.permissions.define_user" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-600">Define User</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" v-model="form.permissions.amend_user_password" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-600">Amend User Password</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" v-model="form.permissions.define_user_access_permission" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-600">Define User Access Permission</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" v-model="form.permissions.copy_paste_user_access_permission" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-600">Copy & Paste User Access Permission</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" v-model="form.permissions.view_print_user" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-600">View & Print User</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Sales Management -->
                            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                                <h4 class="font-medium text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-chart-line w-5 h-5 mr-2 text-green-500"></i>
                                    Sales Management
                                </h4>
                                <div class="space-y-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" v-model="form.permissions.sales_management" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700 font-medium">Sales Management (Main)</span>
                                    </label>
                                    <div class="ml-6 space-y-4 max-h-80 overflow-y-auto">
                                        <!-- Sales Configuration -->
                                        <label class="flex items-center">
                                            <input type="checkbox" v-model="form.permissions.define_sales_configuration" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-600">Define Sales Configuration</span>
                                        </label>
                                        
                                        <!-- Standard Requirement -->
                                        <div class="border-l-2 border-gray-200 pl-3 ml-2">
                                            <p class="text-xs font-medium text-gray-500 mb-2">Standard Requirement</p>
                                            <div class="space-y-1 max-h-40 overflow-y-auto">
                                                <label class="flex items-center">
                                                    <input type="checkbox" v-model="form.permissions.define_sales_team" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                    <span class="ml-2 text-xs text-gray-600">Define Sales Team</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="checkbox" v-model="form.permissions.define_salesperson" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                    <span class="ml-2 text-xs text-gray-600">Define Salesperson</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="checkbox" v-model="form.permissions.define_product_group" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                    <span class="ml-2 text-xs text-gray-600">Define Product Group</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="checkbox" v-model="form.permissions.define_product" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                    <span class="ml-2 text-xs text-gray-600">Define Product</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="checkbox" v-model="form.permissions.view_print_sales_team" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                    <span class="ml-2 text-xs text-gray-600">View & Print Sales Team</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="checkbox" v-model="form.permissions.view_print_product_group" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                    <span class="ml-2 text-xs text-gray-600">View & Print Product Group</span>
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <!-- Customer Account -->
                                        <div class="border-l-2 border-gray-200 pl-3 ml-2">
                                            <p class="text-xs font-medium text-gray-500 mb-2">Customer Account</p>
                                            <div class="space-y-1">
                                                <label class="flex items-center">
                                                    <input type="checkbox" v-model="form.permissions.define_customer_group" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                    <span class="ml-2 text-xs text-gray-600">Define Customer Group</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="checkbox" v-model="form.permissions.update_customer_account" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                    <span class="ml-2 text-xs text-gray-600">Update Customer Account</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="checkbox" v-model="form.permissions.obsolete_reactive_customer_ac" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                    <span class="ml-2 text-xs text-gray-600">Obsolete/Reactive Customer A/C</span>
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <!-- Master Card -->
                                        <div class="border-l-2 border-gray-200 pl-3 ml-2">
                                            <p class="text-xs font-medium text-gray-500 mb-2">Master Card</p>
                                            <div class="space-y-1">
                                                <label class="flex items-center">
                                                    <input type="checkbox" v-model="form.permissions.update_mc" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                    <span class="ml-2 text-xs text-gray-600">Update MC</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="checkbox" v-model="form.permissions.approve_mc" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                    <span class="ml-2 text-xs text-gray-600">Approve MC</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="checkbox" v-model="form.permissions.view_print_mc" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                    <span class="ml-2 text-xs text-gray-600">View & Print MC</span>
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <!-- Sales Order -->
                                        <div class="border-l-2 border-gray-200 pl-3 ml-2">
                                            <p class="text-xs font-medium text-gray-500 mb-2">Sales Order</p>
                                            <div class="space-y-1">
                                                <label class="flex items-center">
                                                    <input type="checkbox" v-model="form.permissions.prepare_mc_so" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                    <span class="ml-2 text-xs text-gray-600">Prepare MC SO</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="checkbox" v-model="form.permissions.print_so" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                    <span class="ml-2 text-xs text-gray-600">Print SO</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="checkbox" v-model="form.permissions.define_so_period" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                    <span class="ml-2 text-xs text-gray-600">Define SO Period</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="checkbox" v-model="form.permissions.print_so_report" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                    <span class="ml-2 text-xs text-gray-600">Print SO Report</span>
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <!-- Customer Service -->
                                        <div class="border-l-2 border-gray-200 pl-3 ml-2">
                                            <p class="text-xs font-medium text-gray-500 mb-2">Customer Service</p>
                                            <div class="space-y-1">
                                                <label class="flex items-center">
                                                    <input type="checkbox" v-model="form.permissions.customer_service_dashboard" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                    <span class="ml-2 text-xs text-gray-600">Customer Service Dashboard</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Material Management -->
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                <h4 class="font-semibold text-lg mb-4 text-purple-600">
                                    <i class="fas fa-pallet mr-2"></i>Material Management
                                </h4>
                                <div class="space-y-2">
                                    <div class="flex items-center">
                                        <input type="checkbox" v-model="permissions" value="material_management_full" 
                                               id="material_management_full" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                        <label for="material_management_full" class="ml-2 block text-sm text-gray-900 font-bold">
                                            Akses Penuh Material Management
                                        </label>
                                    </div>
                                    <hr class="my-2">
                                    <div class="ml-4 space-y-2">
                                    <div class="flex items-center">
                                        <input type="checkbox" v-model="permissions" value="material_inventory" 
                                               id="material_inventory" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                        <label for="material_inventory" class="ml-2 block text-sm text-gray-900">
                                                Inventory Management
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" v-model="permissions" value="material_orders" 
                                               id="material_orders" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                        <label for="material_orders" class="ml-2 block text-sm text-gray-900">
                                                Purchase Orders
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" v-model="permissions" value="material_suppliers" 
                                                id="material_suppliers" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                            <label for="material_suppliers" class="ml-2 block text-sm text-gray-900">
                                                Supplier Management
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" v-model="permissions" value="material_report" 
                                                id="material_report" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                            <label for="material_report" class="ml-2 block text-sm text-gray-900">
                                                Material Reports
                                        </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Production Management -->
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                <h4 class="font-semibold text-lg mb-4 text-red-600">
                                    <i class="fas fa-industry mr-2"></i>Production Management
                                </h4>
                                <div class="space-y-2">
                                    <div class="flex items-center">
                                        <input type="checkbox" v-model="permissions" value="production_management_full" 
                                               id="production_management_full" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                        <label for="production_management_full" class="ml-2 block text-sm text-gray-900 font-bold">
                                            Akses Penuh Production Management
                                        </label>
                                    </div>
                                    <hr class="my-2">
                                    <div class="ml-4 space-y-2">
                                    <div class="flex items-center">
                                        <input type="checkbox" v-model="permissions" value="production_schedule" 
                                               id="production_schedule" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                        <label for="production_schedule" class="ml-2 block text-sm text-gray-900">
                                                Production Schedule
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" v-model="permissions" value="production_workorders" 
                                                id="production_workorders" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                            <label for="production_workorders" class="ml-2 block text-sm text-gray-900">
                                                Work Orders
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" v-model="permissions" value="production_quality" 
                                                id="production_quality" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                            <label for="production_quality" class="ml-2 block text-sm text-gray-900">
                                                Quality Control
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" v-model="permissions" value="production_report" 
                                                id="production_report" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                            <label for="production_report" class="ml-2 block text-sm text-gray-900">
                                                Production Reports
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Warehouse Management -->
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                <h4 class="font-semibold text-lg mb-4 text-yellow-600">
                                    <i class="fas fa-warehouse mr-2"></i>Warehouse Management
                                </h4>
                                <div class="space-y-2">
                                    <div class="flex items-center">
                                        <input type="checkbox" v-model="permissions" value="warehouse_management_full" 
                                               id="warehouse_management_full" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                        <label for="warehouse_management_full" class="ml-2 block text-sm text-gray-900 font-bold">
                                            Akses Penuh Warehouse Management
                                        </label>
                                    </div>
                                    <hr class="my-2">
                                    <div class="ml-4 space-y-2">
                                        <div class="flex items-center">
                                            <input type="checkbox" v-model="permissions" value="warehouse_inventory" 
                                                id="warehouse_inventory" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                            <label for="warehouse_inventory" class="ml-2 block text-sm text-gray-900">
                                                Warehouse Inventory
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" v-model="permissions" value="warehouse_receiving" 
                                                id="warehouse_receiving" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                            <label for="warehouse_receiving" class="ml-2 block text-sm text-gray-900">
                                                Receiving & Putaway
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" v-model="permissions" value="warehouse_shipping" 
                                                id="warehouse_shipping" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                            <label for="warehouse_shipping" class="ml-2 block text-sm text-gray-900">
                                                Shipping & Fulfillment
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" v-model="permissions" value="warehouse_report" 
                                                id="warehouse_report" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                            <label for="warehouse_report" class="ml-2 block text-sm text-gray-900">
                                                Warehouse Reports
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Data Mining -->
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                <h4 class="font-semibold text-lg mb-4 text-indigo-600">
                                    <i class="fas fa-chart-pie mr-2"></i>Data Mining
                                </h4>
                                <div class="space-y-2">
                                    <div class="flex items-center">
                                        <input type="checkbox" v-model="permissions" value="data_mining_full" 
                                               id="data_mining_full" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                        <label for="data_mining_full" class="ml-2 block text-sm text-gray-900 font-bold">
                                            Akses Penuh Data Mining
                                        </label>
                                    </div>
                                    <hr class="my-2">
                                    <div class="ml-4 space-y-2">
                                        <div class="flex items-center">
                                            <input type="checkbox" v-model="permissions" value="data_analytics" 
                                                id="data_analytics" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                            <label for="data_analytics" class="ml-2 block text-sm text-gray-900">
                                                Analytics Dashboard
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" v-model="permissions" value="data_reports" 
                                                id="data_reports" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                            <label for="data_reports" class="ml-2 block text-sm text-gray-900">
                                                Custom Reports
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" v-model="permissions" value="data_export" 
                                                id="data_export" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                            <label for="data_export" class="ml-2 block text-sm text-gray-900">
                                                Data Export
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" v-model="permissions" value="data_forecasts" 
                                                id="data_forecasts" class="h-5 w-5 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500">
                                            <label for="data_forecasts" class="ml-2 block text-sm text-gray-900">
                                                Forecasting Tools
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-8 flex justify-end">
                            <button type="submit" 
                                    class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-semibold transition-all flex items-center">
                                <i class="fas fa-save mr-2"></i>Simpan Permissions
                            </button>
                        </div>
                    </form>
                </div>

                <div v-if="searchMessage" class="mt-6 py-4 px-6 rounded-lg" :class="searchMessageClass">
                    <i :class="searchMessageIcon + ' mr-2'"></i>{{ searchMessage }}
                </div>

                <div v-if="$page.props.flash.success" class="mt-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg">
                    <i class="fas fa-check-circle mr-2"></i>{{ $page.props.flash.success }}
                </div>

                <div v-if="$page.props.flash.error" class="mt-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg">
                    <i class="fas fa-exclamation-circle mr-2"></i>{{ $page.props.flash.error }}
                </div>

                <!-- Kembali ke Daftar User -->
                <div class="mt-6 text-center">
                    <Link href="/user" class="text-blue-600 hover:text-blue-800 cursor-pointer">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar User
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

export default {
    components: {
        AppLayout,
        Head,
        Link
    },
    props: {
        user: Object
    },
    data() {
        return {
            form: {
                user_id: '',
            },
            foundUser: null,
            permissions: [],
            searchMessage: null,
            searchMessageClass: '',
            searchMessageIcon: ''
        }
    },
    mounted() {
        if (this.user) {
            this.foundUser = this.user;
            this.form.user_id = this.user.user_id;
            this.loadUserPermissions();
        }
    },
    methods: {
        searchUser() {
            this.searchMessage = null;
            
            // Pencarian user berdasarkan user_id
            axios.get(`/api/users?search=${this.form.user_id}`)
                .then(response => {
                    if (response.data && response.data.length > 0) {
                        this.foundUser = response.data[0];
                        this.loadUserPermissions();
                        this.searchMessage = `User ${this.foundUser.official_name} ditemukan.`;
                        this.searchMessageClass = 'bg-green-100 text-green-700';
                        this.searchMessageIcon = 'fas fa-check-circle';
                    } else {
                        this.foundUser = null;
                        this.searchMessage = `User dengan ID "${this.form.user_id}" tidak ditemukan.`;
                        this.searchMessageClass = 'bg-yellow-100 text-yellow-700';
                        this.searchMessageIcon = 'fas fa-exclamation-triangle';
                    }
                })
                .catch(error => {
                    console.error('Error searching user:', error);
                    this.foundUser = null;
                    this.searchMessage = 'Terjadi kesalahan saat mencari user.';
                    this.searchMessageClass = 'bg-red-100 text-red-700';
                    this.searchMessageIcon = 'fas fa-times-circle';
                });
        },
        loadUserPermissions() {
            // Load permissions pengguna
            if (!this.foundUser) return;
            
            axios.get(`/api/users/${this.foundUser.id}/permissions`)
                .then(response => {
                    this.permissions = response.data;
                })
                .catch(error => {
                    console.error('Error loading permissions:', error);
                    this.searchMessage = 'Terjadi kesalahan saat mengambil data permission.';
                    this.searchMessageClass = 'bg-red-100 text-red-700';
                    this.searchMessageIcon = 'fas fa-times-circle';
                });
        },
        savePermissions() {
            if (!this.foundUser) return;
            
            this.$inertia.post(`/api/users/${this.foundUser.id}/permissions`, {
                permissions: this.permissions
            }, {
                onSuccess: () => {
                    this.searchMessage = 'Permissions berhasil diperbarui.';
                    this.searchMessageClass = 'bg-green-100 text-green-700';
                    this.searchMessageIcon = 'fas fa-check-circle';
                },
                onError: () => {
                    this.searchMessage = 'Terjadi kesalahan saat menyimpan permissions.';
                    this.searchMessageClass = 'bg-red-100 text-red-700';
                    this.searchMessageIcon = 'fas fa-times-circle';
                }
            });
        },
        selectAllInCategory(category) {
            // This is a function that could be implemented to select all permissions in a category
            const categoryPermissions = this.getPermissionsByCategory(category);
            categoryPermissions.forEach(permission => {
                if (!this.permissions.includes(permission)) {
                    this.permissions.push(permission);
                }
            });
        },
        getPermissionsByCategory(category) {
            // This would return all permissions that belong to a specific category
            // Implementation would depend on how you structure your permissions
            return [];
        }
    }
}
</script> 

<style scoped>
.permission-group:hover {
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}
</style> 