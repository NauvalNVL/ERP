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
                        <div class="space-y-6">
                            <!-- Dashboard -->
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                                <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-gray-200">
                                    <h4 class="font-semibold text-gray-900 flex items-center">
                                        <i class="fas fa-tachometer-alt w-5 h-5 mr-3 text-blue-600"></i>
                                        Dashboard
                                        <span class="ml-auto text-sm text-gray-600">({{ getSelectedCountForCategory('dashboard') }}/{{ getCategoryPermissions('dashboard').length }})</span>
                                    </h4>
                                </div>
                                <div class="p-6">
                                    <label class="flex items-center">
                                        <input type="checkbox" v-model="form.permissions.dashboard" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-3 text-sm text-gray-700">Dashboard Access</span>
                                    </label>
                                </div>
                            </div>

                            <!-- System Manager -->
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                                <button @click="toggleCategory('system_manager')" 
                                        class="w-full bg-gradient-to-r from-indigo-50 to-indigo-100 px-6 py-4 border-b border-gray-200 text-left hover:from-indigo-100 hover:to-indigo-200 transition-all">
                                    <h4 class="font-semibold text-gray-900 flex items-center">
                                        <i class="fas fa-cogs w-5 h-5 mr-3 text-indigo-600"></i>
                                        System Manager
                                        <i :class="['fas ml-2 transition-transform', openCategories.system_manager ? 'fa-chevron-down' : 'fa-chevron-right']"></i>
                                        <span class="ml-auto text-sm text-gray-600">({{ getSelectedCountForCategory('system_manager') }}/{{ getCategoryPermissions('system_manager').length }})</span>
                                    </h4>
                                </button>
                                <div v-show="openCategories.system_manager" class="p-6 space-y-4">
                                    <!-- Main Permission -->
                                    <label class="flex items-center p-3 bg-gray-50 rounded-lg">
                                        <input type="checkbox" v-model="form.permissions.system_manager" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="ml-3 text-sm font-medium text-gray-900">System Manager (Main Access)</span>
                                    </label>
                                    
                                    <!-- Sub Permissions -->
                                    <div class="ml-6 space-y-3">
                                        <h5 class="text-sm font-medium text-gray-700 mb-3">System Security</h5>
                                        <div class="grid grid-cols-1 gap-2">
                                            <label class="flex items-center">
                                                <input type="checkbox" v-model="form.permissions.define_user" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <span class="ml-3 text-sm text-gray-700">Define User</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" v-model="form.permissions.amend_user_password" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <span class="ml-3 text-sm text-gray-700">Amend User Password</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" v-model="form.permissions.define_user_access_permission" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <span class="ml-3 text-sm text-gray-700">Define User Access Permission</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" v-model="form.permissions.copy_paste_user_access_permission" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <span class="ml-3 text-sm text-gray-700">Copy & Paste User Access Permission</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" v-model="form.permissions.view_print_user" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <span class="ml-3 text-sm text-gray-700">View & Print User</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sales Management -->
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                                <button @click="toggleCategory('sales_management')" 
                                        class="w-full bg-gradient-to-r from-green-50 to-green-100 px-6 py-4 border-b border-gray-200 text-left hover:from-green-100 hover:to-green-200 transition-all">
                                    <h4 class="font-semibold text-gray-900 flex items-center">
                                        <i class="fas fa-chart-line w-5 h-5 mr-3 text-green-600"></i>
                                        Sales Management
                                        <i :class="['fas ml-2 transition-transform', openCategories.sales_management ? 'fa-chevron-down' : 'fa-chevron-right']"></i>
                                        <span class="ml-auto text-sm text-gray-600">({{ getSelectedCountForCategory('sales_management') }}/{{ getCategoryPermissions('sales_management').length }})</span>
                                    </h4>
                                </button>
                                <div v-show="openCategories.sales_management" class="p-6 space-y-6 max-h-96 overflow-y-auto">
                                    <!-- Main Permission -->
                                    <label class="flex items-center p-3 bg-gray-50 rounded-lg">
                                        <input type="checkbox" v-model="form.permissions.sales_management" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                        <span class="ml-3 text-sm font-medium text-gray-900">Sales Management (Main Access)</span>
                                    </label>
                                    
                                    <!-- Sales Configuration -->
                                    <div class="border-l-4 border-green-200 pl-4">
                                        <h5 class="text-sm font-medium text-gray-700 mb-3">Sales Configuration</h5>
                                        <label class="flex items-center">
                                            <input type="checkbox" v-model="form.permissions.define_sales_configuration" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                            <span class="ml-3 text-sm text-gray-700">Define Sales Configuration</span>
                                        </label>
                                    </div>

                                    <!-- Standard Requirement -->
                                    <div class="border-l-4 border-green-200 pl-4">
                                        <h5 class="text-sm font-medium text-gray-700 mb-3">Standard Requirement</h5>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 max-h-64 overflow-y-auto">
                                            <!-- Basic Define Permissions -->
                                            <label class="flex items-center">
                                                <input type="checkbox" v-model="form.permissions.define_sales_team" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                <span class="ml-3 text-xs text-gray-700">Define Sales Team</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" v-model="form.permissions.define_salesperson" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                <span class="ml-3 text-xs text-gray-700">Define Salesperson</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" v-model="form.permissions.define_salesperson_team" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                <span class="ml-3 text-xs text-gray-700">Define Salesperson Team</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" v-model="form.permissions.define_industry" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                <span class="ml-3 text-xs text-gray-700">Define Industry</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" v-model="form.permissions.define_geo" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                <span class="ml-3 text-xs text-gray-700">Define Geo</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" v-model="form.permissions.define_product_group" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                <span class="ml-3 text-xs text-gray-700">Define Product Group</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" v-model="form.permissions.define_product" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                <span class="ml-3 text-xs text-gray-700">Define Product</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" v-model="form.permissions.define_product_design" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                <span class="ml-3 text-xs text-gray-700">Define Product Design</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" v-model="form.permissions.define_scoring_tool" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                <span class="ml-3 text-xs text-gray-700">Define Scoring Tool</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" v-model="form.permissions.define_paper_quality" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                <span class="ml-3 text-xs text-gray-700">Define Paper Quality</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" v-model="form.permissions.define_paper_flute" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                <span class="ml-3 text-xs text-gray-700">Define Paper Flute</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" v-model="form.permissions.define_paper_size" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                <span class="ml-3 text-xs text-gray-700">Define Paper Size</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" v-model="form.permissions.define_color_group" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                <span class="ml-3 text-xs text-gray-700">Define Color Group</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" v-model="form.permissions.define_color" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                <span class="ml-3 text-xs text-gray-700">Define Color</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" v-model="form.permissions.define_finishing" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                <span class="ml-3 text-xs text-gray-700">Define Finishing</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" v-model="form.permissions.define_stitch_wire" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                <span class="ml-3 text-xs text-gray-700">Define Stitch Wire</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" v-model="form.permissions.define_chemical_coat" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                <span class="ml-3 text-xs text-gray-700">Define Chemical Coat</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" v-model="form.permissions.define_reinforcement_tape" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                <span class="ml-3 text-xs text-gray-700">Define Reinforcement Tape</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" v-model="form.permissions.define_bundling_string" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                <span class="ml-3 text-xs text-gray-700">Define Bundling String</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" v-model="form.permissions.define_wrapping_material" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                <span class="ml-3 text-xs text-gray-700">Define Wrapping Material</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" v-model="form.permissions.define_glueing_material" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                <span class="ml-3 text-xs text-gray-700">Define Glueing Material</span>
                                            </label>
                                            
                                            <!-- View & Print Permissions -->
                                            <div class="col-span-full">
                                                <div class="border-t border-gray-200 pt-3 mt-3">
                                                    <h6 class="text-xs font-medium text-gray-600 mb-2">View & Print Permissions</h6>
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                                        <label class="flex items-center">
                                                            <input type="checkbox" v-model="form.permissions.view_print_sales_team" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                            <span class="ml-3 text-xs text-gray-600">View & Print Sales Team</span>
                                                        </label>
                                                        <label class="flex items-center">
                                                            <input type="checkbox" v-model="form.permissions.view_print_salesperson" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                            <span class="ml-3 text-xs text-gray-600">View & Print Salesperson</span>
                                                        </label>
                                                        <label class="flex items-center">
                                                            <input type="checkbox" v-model="form.permissions.view_print_product_group" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                            <span class="ml-3 text-xs text-gray-600">View & Print Product Group</span>
                                                        </label>
                                                        <label class="flex items-center">
                                                            <input type="checkbox" v-model="form.permissions.view_print_product" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                            <span class="ml-3 text-xs text-gray-600">View & Print Product</span>
                                                        </label>
                                                        <label class="flex items-center">
                                                            <input type="checkbox" v-model="form.permissions.view_print_stitch_wire" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                            <span class="ml-3 text-xs text-gray-600">View & Print Stitch Wire</span>
                                                        </label>
                                                        <label class="flex items-center">
                                                            <input type="checkbox" v-model="form.permissions.view_print_chemical_coat" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                            <span class="ml-3 text-xs text-gray-600">View & Print Chemical Coat</span>
                                                        </label>
                                                        <label class="flex items-center">
                                                            <input type="checkbox" v-model="form.permissions.view_print_reinforcement_tape" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                            <span class="ml-3 text-xs text-gray-600">View & Print Reinforcement Tape</span>
                                                        </label>
                                                        <label class="flex items-center">
                                                            <input type="checkbox" v-model="form.permissions.view_print_bundling_string" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                            <span class="ml-3 text-xs text-gray-600">View & Print Bundling String</span>
                                                        </label>
                                                        <label class="flex items-center">
                                                            <input type="checkbox" v-model="form.permissions.view_print_wrapping_material" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                            <span class="ml-3 text-xs text-gray-600">View & Print Wrapping Material</span>
                                                        </label>
                                                        <label class="flex items-center">
                                                            <input type="checkbox" v-model="form.permissions.view_print_glueing_material" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                            <span class="ml-3 text-xs text-gray-600">View & Print Glueing Material</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Customer Account -->
                                    <div class="border-l-4 border-green-200 pl-4">
                                        <h5 class="text-sm font-medium text-gray-700 mb-3">Customer Account</h5>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                            <label class="flex items-center">
                                                <input type="checkbox" v-model="form.permissions.define_customer_group" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                <span class="ml-3 text-xs text-gray-700">Define Customer Group</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" v-model="form.permissions.update_customer_account" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                <span class="ml-3 text-xs text-gray-700">Update Customer Account</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" v-model="form.permissions.obsolete_reactive_customer_ac" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                <span class="ml-3 text-xs text-gray-700">Obsolete/Reactive Customer A/C</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" v-model="form.permissions.define_customer_alternate_address" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                <span class="ml-3 text-xs text-gray-700">Define Customer Alternate Address</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" v-model="form.permissions.define_customer_sales_type" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                <span class="ml-3 text-xs text-gray-700">Define Customer Sales Type</span>
                                            </label>
                                        </div>
                                        
                                        <!-- Customer View & Print -->
                                        <div class="border-t border-gray-200 pt-3 mt-3">
                                            <h6 class="text-xs font-medium text-gray-600 mb-2">Customer View & Print</h6>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                                <label class="flex items-center">
                                                    <input type="checkbox" v-model="form.permissions.view_print_customer_group" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                    <span class="ml-3 text-xs text-gray-600">View & Print Customer Group</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="checkbox" v-model="form.permissions.view_print_customer_account" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                    <span class="ml-3 text-xs text-gray-600">View & Print Customer Account</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="checkbox" v-model="form.permissions.view_print_customer_alternate_address" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                    <span class="ml-3 text-xs text-gray-600">View & Print Customer Alt. Address</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="checkbox" v-model="form.permissions.view_print_customer_sales_type" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                    <span class="ml-3 text-xs text-gray-600">View & Print Customer Sales Type</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="checkbox" v-model="form.permissions.view_print_non_active_customer" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                    <span class="ml-3 text-xs text-gray-600">View & Print Non-Active Customer</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Quick Actions for Sales -->
                                    <div class="flex flex-wrap gap-2 pt-4 border-t border-gray-200">
                                        <button @click="selectAllSalesPermissions" 
                                                class="px-3 py-1 text-xs bg-green-100 text-green-700 rounded-md hover:bg-green-200 transition-colors">
                                            Select All Sales
                                        </button>
                                        <button @click="clearAllSalesPermissions" 
                                                class="px-3 py-1 text-xs bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition-colors">
                                            Clear All Sales
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Warehouse Management -->
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                                <button @click="toggleCategory('warehouse_management')" 
                                        class="w-full bg-gradient-to-r from-yellow-50 to-yellow-100 px-6 py-4 border-b border-gray-200 text-left hover:from-yellow-100 hover:to-yellow-200 transition-all">
                                    <h4 class="font-semibold text-gray-900 flex items-center">
                                        <i class="fas fa-warehouse w-5 h-5 mr-3 text-yellow-600"></i>
                                        Warehouse Management
                                        <i :class="['fas ml-2 transition-transform', openCategories.warehouse_management ? 'fa-chevron-down' : 'fa-chevron-right']"></i>
                                        <span class="ml-auto text-sm text-gray-600">({{ getSelectedCountForCategory('warehouse_management') }}/{{ getCategoryPermissions('warehouse_management').length }})</span>
                                    </h4>
                                </button>
                                <div v-show="openCategories.warehouse_management" class="p-6 space-y-4 max-h-96 overflow-y-auto">
                                    <!-- Main Permission -->
                                    <label class="flex items-center p-3 bg-gray-50 rounded-lg">
                                        <input type="checkbox" v-model="form.permissions.warehouse_management" class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                                        <span class="ml-3 text-sm font-medium text-gray-900">Warehouse Management (Main Access)</span>
                                    </label>
                                    
                                    <!-- Warehouse Sub Permissions -->
                                    <div class="space-y-4">
                                        <!-- Delivery Order Processing -->
                                        <div class="border-l-4 border-yellow-200 pl-4">
                                            <h5 class="text-sm font-medium text-gray-700 mb-3">Delivery Order Processing</h5>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                                <label class="flex items-center">
                                                    <input type="checkbox" v-model="form.permissions.prepare_delivery_order_single" class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                                                    <span class="ml-3 text-xs text-gray-700">Prepare DO (Single)</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="checkbox" v-model="form.permissions.prepare_delivery_order_multiple" class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                                                    <span class="ml-3 text-xs text-gray-700">Prepare DO (Multiple)</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="checkbox" v-model="form.permissions.print_delivery_order" class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                                                    <span class="ml-3 text-xs text-gray-700">Print Delivery Order</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="checkbox" v-model="form.permissions.amend_delivery_order" class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                                                    <span class="ml-3 text-xs text-gray-700">Amend Delivery Order</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="checkbox" v-model="form.permissions.cancel_delivery_order" class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                                                    <span class="ml-3 text-xs text-gray-700">Cancel Delivery Order</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="checkbox" v-model="form.permissions.print_do_proforma_invoice" class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                                                    <span class="ml-3 text-xs text-gray-700">Print DO Proforma Invoice</span>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- DORN Processing -->
                                        <div class="border-l-4 border-yellow-200 pl-4">
                                            <h5 class="text-sm font-medium text-gray-700 mb-3">DORN Processing</h5>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                                <label class="flex items-center">
                                                    <input type="checkbox" v-model="form.permissions.issue_dorn" class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                                                    <span class="ml-3 text-xs text-gray-700">Issue DORN</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="checkbox" v-model="form.permissions.print_dorn" class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                                                    <span class="ml-3 text-xs text-gray-700">Print DORN</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="checkbox" v-model="form.permissions.amend_dorn" class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                                                    <span class="ml-3 text-xs text-gray-700">Amend DORN</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="checkbox" v-model="form.permissions.cancel_dorn" class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                                                    <span class="ml-3 text-xs text-gray-700">Cancel DORN</span>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Invoice Processing -->
                                        <div class="border-l-4 border-yellow-200 pl-4">
                                            <h5 class="text-sm font-medium text-gray-700 mb-3">Invoice Processing</h5>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                                <label class="flex items-center">
                                                    <input type="checkbox" v-model="form.permissions.prepare_invoice_by_do_current_period" class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                                                    <span class="ml-3 text-xs text-gray-700">Prepare Invoice (Current)</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="checkbox" v-model="form.permissions.prepare_invoice_by_do_open_period" class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                                                    <span class="ml-3 text-xs text-gray-700">Prepare Invoice (Open)</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="checkbox" v-model="form.permissions.print_invoice" class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                                                    <span class="ml-3 text-xs text-gray-700">Print Invoice</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="checkbox" v-model="form.permissions.amend_invoice" class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                                                    <span class="ml-3 text-xs text-gray-700">Amend Invoice</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="checkbox" v-model="form.permissions.cancel_active_invoice" class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                                                    <span class="ml-3 text-xs text-gray-700">Cancel Active Invoice</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Quick Actions for Warehouse -->
                                    <div class="flex flex-wrap gap-2 pt-4 border-t border-gray-200">
                                        <button @click="selectAllWarehousePermissions" 
                                                class="px-3 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-md hover:bg-yellow-200 transition-colors">
                                            Select All Warehouse
                                        </button>
                                        <button @click="clearAllWarehousePermissions" 
                                                class="px-3 py-1 text-xs bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition-colors">
                                            Clear All Warehouse
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                            <Link href="/user" 
                                class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                                <XIcon class="h-5 w-5 mr-2" />
                                Kembali
                            </Link>
                            <button type="submit" 
                                    :disabled="isSaving"
                                    class="inline-flex justify-center items-center px-6 py-3 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                                <SaveIcon class="h-5 w-5 mr-2" />
                                {{ isSaving ? 'Menyimpan...' : 'Simpan Permissions' }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Flash Messages -->
                <div v-if="$page.props.flash.success" class="mt-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg">
                    <i class="fas fa-check-circle mr-2"></i>{{ $page.props.flash.success }}
                </div>

                <div v-if="$page.props.flash.error" class="mt-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg">
                    <i class="fas fa-exclamation-circle mr-2"></i>{{ $page.props.flash.error }}
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { 
    Switch, 
    SwitchGroup, 
    SwitchLabel 
} from '@headlessui/vue'
import { 
    UserIcon,
    ShieldCheckIcon,
    SearchIcon,
    SaveIcon,
    XIcon,
    CheckCircleIcon,
    ExclamationCircleIcon
} from '@heroicons/vue/outline'

export default {
    components: {
        AppLayout,
        Head,
        Link,
        Switch,
        SwitchGroup,
        SwitchLabel,
        UserIcon,
        ShieldCheckIcon,
        SearchIcon,
        SaveIcon,
        XIcon,
        CheckCircleIcon,
        ExclamationCircleIcon
    },
    data() {
        return {
            searchForm: {
                user_id: ''
            },
            foundUser: null,
            isSearching: false,
            isSaving: false,
            searchMessage: null,
            searchMessageClass: '',
            searchMessageIcon: null,
            selectAllPermissions: false,
            openCategories: {
                system_manager: false,
                sales_management: false,
                warehouse_management: false
            },
            showAllStandardRequirement: false,
            form: {
                permissions: {
                    'dashboard': false,
                    'system_manager': false,
                    'define_user': false,
                    'amend_user_password': false,
                    'define_user_access_permission': false,
                    'copy_paste_user_access_permission': false,
                    'view_print_user': false,
                    'sales_management': false,
                    'define_sales_configuration': false,
                    'define_sales_team': false,
                    'define_salesperson': false,
                    'define_salesperson_team': false,
                    'define_industry': false,
                    'define_geo': false,
                    'define_product_group': false,
                    'define_product': false,
                    'define_product_design': false,
                    'define_scoring_tool': false,
                    'define_paper_quality': false,
                    'define_paper_flute': false,
                    'define_paper_size': false,
                    'define_color_group': false,
                    'define_color': false,
                    'define_finishing': false,
                    'define_stitch_wire': false,
                    'define_chemical_coat': false,
                    'define_reinforcement_tape': false,
                    'define_bundling_string': false,
                    'define_wrapping_material': false,
                    'define_glueing_material': false,
                    'view_print_sales_team': false,
                    'view_print_salesperson': false,
                    'view_print_salesperson_team': false,
                    'view_print_industry': false,
                    'view_print_geo': false,
                    'view_print_product_group': false,
                    'view_print_product': false,
                    'view_print_product_design': false,
                    'view_print_paper_quality': false,
                    'view_print_paper_flute': false,
                    'view_print_paper_size': false,
                    'view_print_scoring_tool': false,
                    'view_print_color_group': false,
                    'view_print_color': false,
                    'view_print_finishing': false,
                    'view_print_stitch_wire': false,
                    'view_print_chemical_coat': false,
                    'view_print_reinforcement_tape': false,
                    'view_print_bundling_string': false,
                    'view_print_wrapping_material': false,
                    'view_print_glueing_material': false,
                    'define_customer_group': false,
                    'update_customer_account': false,
                    'obsolete_reactive_customer_ac': false,
                    'define_customer_alternate_address': false,
                    'define_customer_sales_type': false,
                    'view_print_customer_group': false,
                    'view_print_customer_account': false,
                    'view_print_customer_alternate_address': false,
                    'view_print_customer_sales_type': false,
                    'view_print_non_active_customer': false,
                    'update_mc': false,
                    'approve_mc': false,
                    'release_approved_mc': false,
                    'obsolete_reactive_mc': false,
                    'view_print_mc': false,
                    'view_print_mc_maintenance_log': false,
                    'prepare_mc_so': false,
                    'prepare_sb_so': false,
                    'prepare_jit_so': false,
                    'print_so': false,
                    'cancel_so': false,
                    'amend_so': false,
                    'amend_approved_so': false,
                    'amend_so_price': false,
                    'amend_approved_so_price': false,
                    'close_so': false,
                    'close_so_by_period': false,
                    'unclose_so': false,
                    'resubmit_rejected_so': false,
                    'release_wo_by_so': false,
                    'print_so_log': false,
                    'print_so_jit_tracking': false,
                    'define_so_config': false,
                    'define_so_period': false,
                    'define_so_rough_cut': false,
                    'define_ac_auto_wo': false,
                    'define_mc_auto_wo': false,
                    'print_so_period': false,
                    'print_so_rough_cut': false,
                    'print_ac_auto_wo': false,
                    'print_mc_auto_wo': false,
                    'define_report_format': false,
                    'print_rough_cut_report': false,
                    'print_so_report': false,
                    'print_so_cancel_report': false,
                    'customer_service_dashboard': false,
                    'warehouse_management': false,
                    'define_analysis_code': false,
                    'define_transport_contractor': false,
                    'define_vehicle_class': false,
                    'define_vehicle': false,
                    'define_dorn_code': false,
                    'define_greeting_message': false,
                    'define_alternate_unit': false,
                    'define_master_card_alternate_unit': false,
                    'define_dorder_group': false,
                    'define_users_dorder_group': false,
                    'view_print_analysis_code': false,
                    'view_print_vehicle_class': false,
                    'view_print_vehicle': false,
                    'prepare_delivery_order_single': false,
                    'prepare_delivery_order_multiple': false,
                    'print_delivery_order': false,
                    'print_do_proforma_invoice': false,
                    'print_coa_result_by_wo': false,
                    'print_coa_result_by_so': false,
                    'amend_delivery_order': false,
                    'cancel_delivery_order': false,
                    'reconcile_do_unapplied_fg': false,
                    'view_print_do_log': false,
                    'view_print_do_unapplied_fg': false,
                    'customer_so_delivery_obsolete': false,
                    'sales_order_delivery_schedule': false,
                    'issue_dorn': false,
                    'print_dorn': false,
                    'amend_dorn': false,
                    'cancel_dorn': false,
                    'view_print_dorn_log': false,
                    'activate_manual_configuration': false,
                    'register_manual_numbers': false,
                    'view_print_registered_manual_numbers_log': false,
                    'define_tax_type': false,
                    'define_tax_group': false,
                    'define_customer_sales_tax_index': false,
                    'view_print_tax_type': false,
                    'view_print_tax_group': false,
                    'view_print_customer_sales_tax_index': false,
                    'prepare_invoice_by_do_current_period': false,
                    'prepare_invoice_by_do_open_period': false,
                    'print_invoice': false,
                    'amend_invoice': false,
                    'cancel_active_invoice': false,
                    'view_print_invoice_log': false
                }
            }
        }
    },
    computed: {
        selectedPermissionsCount() {
            return Object.values(this.form.permissions).filter(permission => permission).length;
        },
        totalPermissionsCount() {
            return Object.keys(this.form.permissions).length;
        }
    },
    methods: {
        async searchUser() {
            this.isSearching = true;
            this.searchMessage = null;
            
            try {
                const response = await fetch(`/api/users/search/${this.searchForm.user_id}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': this.$page.props.csrf || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    }
                });

                const data = await response.json();

                if (response.ok && data.user) {
                    this.foundUser = data.user;
                    this.searchMessage = `User ${data.user.official_name} ditemukan.`;
                    this.searchMessageClass = 'bg-green-100 text-green-700 border border-green-200';
                    this.searchMessageIcon = CheckCircleIcon;
                    
                    // Load existing permissions
                    if (data.permissions) {
                        Object.keys(this.form.permissions).forEach(key => {
                            this.form.permissions[key] = data.permissions.includes(key);
                        });
                    }
                } else {
                    this.foundUser = null;
                    this.searchMessage = `User dengan ID "${this.searchForm.user_id}" tidak ditemukan.`;
                    this.searchMessageClass = 'bg-yellow-100 text-yellow-700 border border-yellow-200';
                    this.searchMessageIcon = ExclamationCircleIcon;
                }
            } catch (error) {
                console.error('Search error:', error);
                this.foundUser = null;
                this.searchMessage = 'Terjadi kesalahan saat mencari user.';
                this.searchMessageClass = 'bg-red-100 text-red-700 border border-red-200';
                this.searchMessageIcon = ExclamationCircleIcon;
            } finally {
                this.isSearching = false;
            }
        },
        
        async savePermissions() {
            if (!this.foundUser) return;
            
            this.isSaving = true;
            
            try {
                const response = await fetch(`/user-permissions/${this.foundUser.userID}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': this.$page.props.csrf || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    body: JSON.stringify({
                        permissions: this.form.permissions
                    })
                });

                if (response.ok) {
                    this.searchMessage = 'Permissions berhasil diperbarui!';
                    this.searchMessageClass = 'bg-green-100 text-green-700 border border-green-200';
                    this.searchMessageIcon = CheckCircleIcon;
                } else {
                    const errorData = await response.json();
                    this.searchMessage = errorData.message || 'Terjadi kesalahan saat menyimpan permissions.';
                    this.searchMessageClass = 'bg-red-100 text-red-700 border border-red-200';
                    this.searchMessageIcon = ExclamationCircleIcon;
                }
            } catch (error) {
                console.error('Save error:', error);
                this.searchMessage = 'Terjadi kesalahan saat menyimpan permissions.';
                this.searchMessageClass = 'bg-red-100 text-red-700 border border-red-200';
                this.searchMessageIcon = ExclamationCircleIcon;
            } finally {
                this.isSaving = false;
            }
        },
        
        toggleAllPermissions() {
            const value = this.selectAllPermissions;
            Object.keys(this.form.permissions).forEach(key => {
                this.form.permissions[key] = value;
            });
        },

        formatPermissionName(key) {
            // Convert snake_case to Title Case
            return key
                .split('_')
                .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                .join(' ');
        },

        toggleCategory(category) {
            this.openCategories[category] = !this.openCategories[category];
        },

        getCategoryPermissions(category) {
            const categoryMaps = {
                dashboard: ['dashboard'],
                system_manager: [
                    'system_manager', 'define_user', 'amend_user_password', 
                    'define_user_access_permission', 'copy_paste_user_access_permission', 'view_print_user'
                ],
                sales_management: Object.keys(this.form.permissions).filter(key => 
                    key.includes('sales') || key.includes('customer') || key.includes('product') || 
                    key.includes('mc') || key.includes('so') || key.includes('industry') || 
                    key.includes('geo') || key.includes('color') || key.includes('paper') || 
                    key.includes('finishing') || key.includes('scoring') || key.includes('stitch') ||
                    key.includes('chemical') || key.includes('reinforcement') || key.includes('bundling') ||
                    key.includes('wrapping') || key.includes('glueing')
                ),
                warehouse_management: Object.keys(this.form.permissions).filter(key => 
                    key.includes('delivery') || key.includes('warehouse') || key.includes('dorn') || 
                    key.includes('invoice') || key.includes('analysis') || key.includes('vehicle') || 
                    key.includes('transport') || key.includes('tax')
                )
            };
            return categoryMaps[category] || [];
        },

        getSelectedCountForCategory(category) {
            const permissions = this.getCategoryPermissions(category);
            return permissions.filter(key => this.form.permissions[key]).length;
        },

        selectAllSalesPermissions() {
            const salesPermissions = this.getCategoryPermissions('sales_management');
            salesPermissions.forEach(key => {
                this.form.permissions[key] = true;
            });
        },

        clearAllSalesPermissions() {
            const salesPermissions = this.getCategoryPermissions('sales_management');
            salesPermissions.forEach(key => {
                this.form.permissions[key] = false;
            });
        },

        selectAllWarehousePermissions() {
            const warehousePermissions = this.getCategoryPermissions('warehouse_management');
            warehousePermissions.forEach(key => {
                this.form.permissions[key] = true;
            });
        },

        clearAllWarehousePermissions() {
            const warehousePermissions = this.getCategoryPermissions('warehouse_management');
            warehousePermissions.forEach(key => {
                this.form.permissions[key] = false;
            });
        },

        toggleSalesSubcategory(subcategory) {
            if (subcategory === 'standard_requirement') {
                this.showAllStandardRequirement = !this.showAllStandardRequirement;
            }
        },

        getSalesStandardRequirementPermissions() {
            return Object.keys(this.form.permissions).filter(key => 
                key.includes('define_sales') || key.includes('define_product') || 
                key.includes('define_industry') || key.includes('define_geo') || 
                key.includes('define_color') || key.includes('define_paper') || 
                key.includes('define_finishing') || key.includes('define_scoring') ||
                key.includes('view_print_sales') || key.includes('view_print_product') ||
                key.includes('view_print_industry') || key.includes('view_print_geo') ||
                key.includes('view_print_color') || key.includes('view_print_paper') ||
                key.includes('view_print_finishing') || key.includes('view_print_scoring')
            );
        }
    }
}
</script>

<style scoped>
.permission-group:hover {
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}
</style>
