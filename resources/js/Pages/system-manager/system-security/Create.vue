<template>
    <AppLayout header="Create User">
        <Head title="Create User" />
        <div class="min-h-screen bg-white md:bg-gradient-to-br md:from-indigo-50 md:via-white md:to-purple-50 py-8 px-4 sm:px-6 lg:px-8 overflow-x-hidden">
            <div class="max-w-6xl w-full mx-auto">
                <!-- Header Card -->
                <div class="bg-white/80 shadow rounded-2xl overflow-hidden border border-white/20 mb-8">
                    <div class="bg-blue-600 md:bg-gradient-to-r md:from-blue-600 md:via-indigo-600 md:to-purple-600 p-4 md:p-8">
                        <div class="flex items-center justify-center">
                            <div class="bg-white/20 rounded-full p-4 mr-4">
                                <UserAddIcon class="h-8 w-8 text-white" />
                            </div>
                            <div class="text-center">
                                <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">Create New User</h1>
                                <p class="text-blue-100">Add new user to the ERP system with modern interface</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Form -->
                <form @submit.prevent="submitForm" class="space-y-8 w-full">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-8 w-full">
                        <!-- User Information -->
                        <div class="bg-white shadow-lg rounded-2xl border border-gray-200 overflow-hidden" style="content-visibility:auto; contain-intrinsic-size: 1px 540px; contain: content;">
                            <div class="bg-blue-600 md:bg-gradient-to-r md:from-blue-600 md:to-cyan-600 p-4 md:p-6">
                                <h3 class="text-xl font-semibold text-white flex items-center">
                                    <div class="bg-white/30 rounded-full p-2 mr-3">
                                        <UserCircleIcon class="h-6 w-6 text-white" />
                                    </div>
                                    User Information
                                </h3>
                                <p class="text-blue-100 text-sm mt-1">Basic user details</p>
                            </div>
                            <div class="p-4 md:p-8 bg-white">
                                <div class="space-y-6 text-gray-900">
                                    <!-- User ID -->
                                    <div>
                                        <label class="flex items-center text-lg font-semibold text-gray-900 mb-3">
                                            <div class="bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full p-2 mr-3">
                                                <IdentificationIcon class="h-5 w-5 text-white" />
                                            </div>
                                            User ID <span class="text-red-500 ml-1">*</span>
                                        </label>
                                        <input type="text"
                                               v-model="form.user_id"
                                               class="block w-full px-6 py-4 border-2 border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-gray-900 bg-white hover:bg-gray-50 text-lg"
                                               placeholder="Example: USER001"
                                               required>
                                    </div>

                                    <!-- Username -->
                                    <div>
                                        <label class="flex items-center text-lg font-semibold text-gray-900 mb-3">
                                            <div class="bg-gradient-to-r from-cyan-500 to-teal-500 rounded-full p-2 mr-3">
                                                <AtSymbolIcon class="h-5 w-5 text-white" />
                                            </div>
                                            Username <span class="text-red-500 ml-1">*</span>
                                        </label>
                                        <input type="text"
                                               v-model="form.username"
                                               class="block w-full px-6 py-4 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all duration-300 text-gray-900 bg-gray-50 hover:bg-white text-lg"
                                               placeholder="Login username"
                                               required>
                                    </div>

                                    <!-- Official Name -->
                                    <div>
                                        <label class="flex items-center text-lg font-semibold text-gray-900 mb-3">
                                            <div class="bg-gradient-to-r from-teal-500 to-green-500 rounded-full p-2 mr-3">
                                                <UserIcon class="h-5 w-5 text-white" />
                                            </div>
                                            Official Name <span class="text-red-500 ml-1">*</span>
                                        </label>
                                        <input type="text"
                                               v-model="form.official_name"
                                               class="block w-full px-6 py-4 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 text-gray-900 bg-gray-50 hover:bg-white text-lg"
                                               placeholder="Full name"
                                               required>
                                    </div>

                                    <!-- Official Title -->
                                    <div>
                                        <label class="flex items-center text-lg font-semibold text-gray-900 mb-3">
                                            <div class="bg-gradient-to-r from-green-500 to-emerald-500 rounded-full p-2 mr-3">
                                                <BriefcaseIcon class="h-5 w-5 text-white" />
                                            </div>
                                            Official Title
                                        </label>
                                        <input type="text"
                                               v-model="form.official_title"
                                               class="block w-full px-6 py-4 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300 text-gray-900 bg-gray-50 hover:bg-white text-lg"
                                               placeholder="Job position">
                                    </div>

                                    <!-- User Status -->
                                    <div>
                                        <label class="flex items-center text-lg font-semibold text-gray-900 mb-3">
                                            <div class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-full p-2 mr-3">
                                                <StatusOnlineIcon class="h-5 w-5 text-white" />
                                            </div>
                                            User Status
                                        </label>
                                        <select v-model="form.user_status" class="block w-full px-6 py-4 border-2 border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-gray-900 bg-white hover:bg-gray-50 text-lg">
                                            <option value="A">A-Active</option>
                                            <option value="O">O-Obsolete</option>
                                        </select>
                                    </div>

                                    <!-- Password Expiry Date -->
                                    <div>
                                        <label class="flex items-center text-lg font-semibold text-gray-900 mb-3">
                                            <div class="bg-gradient-to-r from-red-500 to-pink-500 rounded-full p-2 mr-3">
                                                <ClockIcon class="h-5 w-5 text-white" />
                                            </div>
                                            Password Expiry Date
                                        </label>
                                        <input type="number"
                                               v-model="form.password_expiry_date"
                                               class="block w-full px-6 py-4 border-2 border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-gray-900 bg-white hover:bg-gray-50 text-lg"
                                               placeholder="Set 0 for no expiry date"
                                               value="0"
                                               min="0">
                                        <p class="mt-1 text-sm text-gray-500">
                                            Set 0 for no expiry date
                                        </p>
                                    </div>

                                    <!-- Amend Expired Password -->
                                    <div>
                                        <label class="flex items-center text-lg font-semibold text-gray-900 mb-3">
                                            <div class="bg-gradient-to-r from-pink-500 to-rose-500 rounded-full p-2 mr-3">
                                                <KeyIcon class="h-5 w-5 text-white" />
                                            </div>
                                            Amend Expired Password
                                        </label>
                                        <div class="flex space-x-6">
                                            <label class="inline-flex items-center mr-6">
                                                <input type="radio" v-model="form.amend_password" value="Y" class="form-radio h-5 w-5 text-blue-600">
                                                <span class="ml-2 text-gray-900 font-medium">Y-Yes</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" v-model="form.amend_password" value="N" class="form-radio h-5 w-5 text-blue-600">
                                                <span class="ml-2 text-gray-900 font-medium">N-No</span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- User Printer -->
                                    <div>
                                        <label class="flex items-center text-lg font-semibold text-gray-900 mb-3">
                                            <div class="bg-gradient-to-r from-rose-500 to-red-500 rounded-full p-2 mr-3">
                                                <PrinterIcon class="h-5 w-5 text-white" />
                                            </div>
                                            User Printer
                                        </label>
                                        <input type="text"
                                               v-model="form.user_printer"
                                               class="block w-full px-6 py-4 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-300 text-gray-900 bg-gray-50 hover:bg-white text-lg"
                                               placeholder="Default printer">
                                    </div>

                                    <!-- Print Route -->
                                    <div>
                                        <label class="flex items-center text-lg font-semibold text-gray-900 mb-3">
                                            <div class="bg-gradient-to-r from-indigo-500 to-blue-500 rounded-full p-2 mr-3">
                                                <SwitchHorizontalIcon class="h-5 w-5 text-white" />
                                            </div>
                                            Print Route
                                        </label>
                                        <div class="flex space-x-6">
                                            <label class="inline-flex items-center">
                                                <input type="radio" v-model="form.print_route" value="UF" class="form-radio h-5 w-5 text-blue-600" checked>
                                                <span class="ml-2 text-gray-700">UF-User to Function</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" v-model="form.print_route" value="FU" class="form-radio h-5 w-5 text-blue-600">
                                                <span class="ml-2 text-gray-700">FU-Function to User</span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Menu Type -->
                                    <div>
                                        <label class="flex items-center text-lg font-semibold text-gray-900 mb-3">
                                            <div class="bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full p-2 mr-3">
                                                <MenuAlt2Icon class="h-5 w-5 text-white" />
                                            </div>
                                            Menu Type
                                        </label>
                                        <div class="flex space-x-6">
                                            <label class="inline-flex items-center">
                                                <input type="radio" v-model="form.menu_type" value="W" class="form-radio h-5 w-5 text-blue-600">
                                                <span class="ml-2 text-gray-700">W-Windows</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" v-model="form.menu_type" value="V" class="form-radio h-5 w-5 text-blue-600" checked>
                                                <span class="ml-2 text-gray-700">V-View Tree</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="bg-white shadow-lg rounded-2xl border border-gray-200 overflow-hidden" style="content-visibility:auto; contain-intrinsic-size: 1px 540px;">
                            <div class="bg-emerald-600 md:bg-gradient-to-r md:from-emerald-600 md:to-teal-600 p-4 md:p-6">
                                <h3 class="text-xl font-semibold text-white flex items-center">
                                    <div class="bg-white/30 rounded-full p-2 mr-3">
                                        <PhoneIcon class="h-6 w-6 text-white" />
                                    </div>
                                    Contact Information
                                </h3>
                                <p class="text-emerald-100 text-sm mt-1">Contact details and status</p>
                            </div>
                            <div class="p-4 md:p-8 bg-white">
                                <div class="space-y-6 text-gray-900">
                                    <!-- Mobile Number -->
                                    <div>
                                        <label class="flex items-center text-lg font-semibold text-gray-900 mb-3">
                                            <div class="bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full p-2 mr-3">
                                                <PhoneIcon class="h-5 w-5 text-white" />
                                            </div>
                                            Mobile Number
                                        </label>
                                        <input type="tel"
                                               v-model="form.mobile_number"
                                               class="block w-full px-6 py-4 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-300 text-gray-900 bg-gray-50 hover:bg-white text-lg"
                                               placeholder="08xxxxxxxxxx">
                                    </div>

                                    <!-- Official Tel -->
                                    <div>
                                        <label class="flex items-center text-lg font-semibold text-gray-900 mb-3">
                                            <div class="bg-gradient-to-r from-teal-500 to-cyan-500 rounded-full p-2 mr-3">
                                                <PhoneIcon class="h-5 w-5 text-white" />
                                            </div>
                                            <span class="text-gray-900">Official Telephone</span>
                                        </label>
                                        <input type="tel"
                                               v-model="form.official_tel"
                                               class="block w-full px-6 py-4 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 text-gray-900 bg-gray-50 hover:bg-white text-lg"
                                               placeholder="021xxxxxxx">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Special Access -->
                    <div class="bg-white shadow-lg rounded-2xl border border-gray-200 overflow-hidden" style="content-visibility:auto; contain-intrinsic-size: 1px 520px; contain: content;">
                        <div class="bg-purple-600 md:bg-gradient-to-r md:from-purple-700 md:to-indigo-700 p-4 md:p-6">
                            <h3 class="text-xl font-semibold text-white flex items-center">
                                <div class="bg-white/30 rounded-full p-2 mr-3">
                                    <LockClosedIcon class="h-6 w-6 text-white" />
                                </div>
                                User Special Access
                            </h3>
                            <p class="text-purple-100 text-sm mt-1">Configure special access permissions</p>
                        </div>
                        <div class="p-4 md:p-8">
                            <!-- Sales Management Section -->
                            <div class="mb-8">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <ShoppingCartIcon class="h-5 w-5 text-purple-600 mr-2" />
                                    Sales Management
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    <!-- Access Unit Price -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Access Unit Price</label>
                                        <div class="flex space-x-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" v-model="form.access_unit_price" value="Y" class="form-radio h-4 w-4 text-blue-600">
                                                <span class="ml-2 text-gray-700">Y-Yes</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" v-model="form.access_unit_price" value="N" class="form-radio h-4 w-4 text-blue-600" checked>
                                                <span class="ml-2 text-gray-700">N-No</span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Access Customer A/C -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Access Customer A/C</label>
                                        <div class="flex space-x-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" v-model="form.access_customer_acct" value="Y" class="form-radio h-4 w-4 text-blue-600">
                                                <span class="ml-2 text-gray-700">Y-Yes</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" v-model="form.access_customer_acct" value="N" class="form-radio h-4 w-4 text-blue-600" checked>
                                                <span class="ml-2 text-gray-700">N-No</span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Amend MC -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Amend MC</label>
                                        <div class="flex space-x-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" v-model="form.amend_mc" value="Y" class="form-radio h-4 w-4 text-blue-600">
                                                <span class="ml-2 text-gray-700">Y-Yes</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" v-model="form.amend_mc" value="N" class="form-radio h-4 w-4 text-blue-600" checked>
                                                <span class="ml-2 text-gray-700">N-No</span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Amend MC Price -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Amend MC Price</label>
                                        <div class="flex space-x-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" v-model="form.amend_mc_price" value="Y" class="form-radio h-4 w-4 text-blue-600">
                                                <span class="ml-2 text-gray-700">Y-Yes</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" v-model="form.amend_mc_price" value="N" class="form-radio h-4 w-4 text-blue-600" checked>
                                                <span class="ml-2 text-gray-700">N-No</span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Approve Duplicate PO -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Approve Duplicate PO</label>
                                        <div class="flex space-x-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" v-model="form.approve_duplicate_po" value="Y" class="form-radio h-4 w-4 text-blue-600">
                                                <span class="ml-2 text-gray-700">Y-Yes</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" v-model="form.approve_duplicate_po" value="N" class="form-radio h-4 w-4 text-blue-600" checked>
                                                <span class="ml-2 text-gray-700">N-No</span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Lock to Salesperson -->
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Lock to Salesperson
                                        </label>
                                        <div class="flex">
                                            <div class="relative flex-grow">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <UserIcon class="h-5 w-5 text-gray-400" />
                                                </div>
                                                <input type="text"
                                                       v-model="form.salesperson_code"
                                                       class="block w-full pl-10 pr-3 py-2 border border-r-0 border-gray-300 rounded-l-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                       placeholder="Salesperson Code">
                                            </div>
                                                                            <button type="button"
                                                    @click="openSalespersonModal"
                                                    class="inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 text-sm font-medium rounded-r-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                <MagnifyingGlassIcon class="h-4 w-4 mr-1 text-white" />
                                                Search
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Warehouse Management Section -->
                            <div class="mb-8">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <BuildingStorefrontIcon class="h-5 w-5 text-purple-600 mr-2" />
                                    Warehouse Management
                                </h4>
                                <div class="flex items-center">
                                    <input type="checkbox" v-model="form.wbms_restricted_pass" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label class="ml-2 block text-sm text-gray-700">
                                        WBMS Restricted Pass
                                    </label>
                                </div>
                            </div>

                            <!-- Material Management Section -->
                            <div class="mb-8">
                                <h4 class="text-lg font-semibold text-black mb-4 flex items-center">
                                    <Cog6ToothIcon class="h-5 w-5 text-purple-600 mr-2" />
                                    Material Management (Only for MM2)
                                </h4>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">RC + RT Price</label>
                                        <div class="space-y-2">
                                            <label class="inline-flex items-center">
                                                <input type="radio" v-model="form.rc_rt_price" value="Y" class="form-radio h-4 w-4 text-blue-600">
                                                <span class="ml-2 text-gray-700">Y-Yes</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" v-model="form.rc_rt_price" value="N" class="form-radio h-4 w-4 text-blue-600" checked>
                                                <span class="ml-2 text-gray-700">N-No, Cannot See Price + Tax</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Board Purchase Section -->
                            <div>
                                <h4 class="text-lg font-semibold text-black mb-4 flex items-center">
                                    <ScaleIcon class="h-5 w-5 text-purple-600 mr-2" />
                                    Board Purchase
                                </h4>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Board RC Cost</label>
                                        <div class="space-y-2">
                                            <label class="inline-flex items-center">
                                                <input type="radio" v-model="form.board_rc_cost" value="Y" class="form-radio h-4 w-4 text-blue-600">
                                                <span class="ml-2 text-gray-700">Y-Yes</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" v-model="form.board_rc_cost" value="N" class="form-radio h-4 w-4 text-blue-600" checked>
                                                <span class="ml-2 text-gray-700">N-No, Cannot See Price + Tax</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="bg-white shadow-lg rounded-2xl border border-gray-200 p-4 md:p-8" style="content-visibility:auto; contain-intrinsic-size: 1px 160px; contain: content;">
                        <div class="flex flex-col sm:flex-row gap-4 justify-end">
                            <Link href="/user"
                                class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-3 md:px-8 md:py-4 border-2 border-gray-300 text-lg font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors duration-200">
                                <XIcon class="h-6 w-6 mr-3" />
                                Cancel
                            </Link>
                            <button type="submit"
                                class="flex-1 inline-flex items-center justify-center px-6 py-3 md:px-8 md:py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-lg font-semibold rounded-xl shadow hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-300 transition-colors duration-200">
                                <SaveIcon class="h-6 w-6 mr-3" />
                                Create User
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Salesperson Selection Modal -->
                <SalespersonModal
                    :show="showSalespersonModal"
                    :salespersons="salespersons"
                    @close="showSalespersonModal = false"
                    @select="selectSalesperson"
                />
            </div>
        </div>
    </AppLayout>
</template>

<script>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, defineAsyncComponent } from 'vue';
import axios from 'axios';
import AppLayout from '@/Layouts/AppLayout.vue';
import Swal from 'sweetalert2';

// Import icons from Heroicons v2
import {
    UserPlusIcon as UserAddIcon,
    UserCircleIcon,
    AtSymbolIcon,
    UserIcon,
    BriefcaseIcon,
    PhoneIcon,
    EnvelopeIcon as MailIcon,
    LockClosedIcon,
    CheckCircleIcon,
    XMarkIcon as XIcon,
    SignalIcon as StatusOnlineIcon,
    ClockIcon,
    KeyIcon,
    PrinterIcon,
    ArrowsRightLeftIcon as SwitchHorizontalIcon,
    Bars3BottomLeftIcon as MenuAlt2Icon,
    ShoppingCartIcon,
    BuildingStorefrontIcon,
    Cog6ToothIcon,
    ClipboardDocumentCheckIcon,
    ClipboardDocumentListIcon,
    ScaleIcon,
    BuildingOfficeIcon,
    ShieldCheckIcon,
    CalendarIcon,
    ArrowDownTrayIcon as SaveIcon,
    IdentificationIcon,
    CheckIcon,
    MagnifyingGlassIcon
} from '@heroicons/vue/24/outline';

// Import Headless UI components
import {
    RadioGroup,
    RadioGroupLabel,
    RadioGroupOption,
    Switch,
    SwitchGroup,
    SwitchLabel
} from '@headlessui/vue';

// Import the salesperson modal component as async to reduce initial bundle size
const SalespersonModal = defineAsyncComponent(() => import('@/Components/salesperson-modal.vue'));

export default {
    components: {
        // Core components
        Head,
        Link,
        AppLayout,
        SalespersonModal,

        // Headless UI components
        RadioGroup,
        RadioGroupLabel,
        RadioGroupOption,
        Switch,
        SwitchGroup,
        SwitchLabel,

        // Icons
        UserAddIcon,
        UserCircleIcon,
        AtSymbolIcon,
        UserIcon,
        BriefcaseIcon,
        PhoneIcon,
        MailIcon,
        LockClosedIcon,
        CheckCircleIcon,
        XIcon,
        StatusOnlineIcon,
        ClockIcon,
        KeyIcon,
        PrinterIcon,
        SwitchHorizontalIcon,
        MenuAlt2Icon,
        ShoppingCartIcon,
        BuildingStorefrontIcon,
        Cog6ToothIcon,
        ClipboardDocumentCheckIcon,
        ClipboardDocumentListIcon,
        ScaleIcon,
        BuildingOfficeIcon,
        ShieldCheckIcon,
        CalendarIcon,
        SaveIcon,
        IdentificationIcon,
        CheckIcon,
        MagnifyingGlassIcon
    },
    data() {
        return {
            showSalespersonModal: false,
            salespersons: [],
            form: this.$inertia.form({
                user_id: '',
                username: '',
                official_name: '',
                official_title: '',
                mobile_number: '',
                official_tel: '',
                email: '',
                password: '',
                password_confirmation: '',
                password_expiry_date: 0,
                user_status: 'A',
                amend_password: 'N',
                user_printer: '',
                print_route: 'UF',
                menu_type: 'V',
                // Special Access
                access_unit_price: 'N',
                access_customer_acct: 'N',
                amend_mc: 'N',
                amend_mc_price: 'N',
                approve_duplicate_po: 'N',
                lock_to_salesperson: false,
                salesperson_code: '',
                wbms_restricted_pass: false,
                rc_rt_price: 'N',
                board_rc_cost: 'N'
            }),
        };
    },
    computed: {
        amendPasswordEnabled: {
            get() {
                return this.form.amend_password === 'Y';
            },
            set(value) {
                this.form.amend_password = value ? 'Y' : 'N';
            }
        }
    },
    methods: {
        async openSalespersonModal() {
            try {
                // Fetch salespersons data from the server
                const response = await axios.get('/api/salespersons');
                this.salespersons = response.data;
                this.showSalespersonModal = true;
            } catch (error) {
                console.error('Error fetching salespersons:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load salespersons. Please try again.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#dc2626',
                    background: '#ffffff',
                    color: '#0f172a',
                    backdrop: 'rgba(15,23,42,0.4)',
                    customClass: {
                        popup: 'rounded-2xl shadow-2xl border border-red-100',
                        title: 'text-xl font-semibold',
                        confirmButton: 'px-6 py-2 rounded-lg'
                    },
                });
            }
        },
        selectSalesperson(salesperson) {
            if (salesperson) {
                this.form.salesperson_code = salesperson.code;
                this.showSalespersonModal = false;
            }
        },
        submitForm() {
            const mobile = (this.form.mobile_number || '').trim();
            const officialTel = (this.form.official_tel || '').trim();
            const mobileDigits = mobile.replace(/\D/g, '');
            const officialTelDigits = officialTel.replace(/\D/g, '');

            if (!mobile) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    text: 'Mobile Number is required.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#2563eb',
                    background: '#ffffff',
                    color: '#0f172a',
                    backdrop: 'rgba(15,23,42,0.4)',
                    customClass: {
                        popup: 'rounded-2xl shadow-2xl border border-blue-100',
                        title: 'text-xl font-semibold',
                        confirmButton: 'px-6 py-2 rounded-lg'
                    },
                });
                return;
            }

            if (mobileDigits.length < 8) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    text: 'Mobile Number must be at least 8 digits.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#2563eb',
                    background: '#ffffff',
                    color: '#0f172a',
                    backdrop: 'rgba(15,23,42,0.4)',
                    customClass: {
                        popup: 'rounded-2xl shadow-2xl border border-blue-100',
                        title: 'text-xl font-semibold',
                        confirmButton: 'px-6 py-2 rounded-lg'
                    },
                });
                return;
            }

            if (!officialTel) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    text: 'Official Telephone is required.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#2563eb',
                    background: '#ffffff',
                    color: '#0f172a',
                    backdrop: 'rgba(15,23,42,0.4)',
                    customClass: {
                        popup: 'rounded-2xl shadow-2xl border border-blue-100',
                        title: 'text-xl font-semibold',
                        confirmButton: 'px-6 py-2 rounded-lg'
                    },
                });
                return;
            }

            if (officialTelDigits.length < 8) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    text: 'Official Telephone must be at least 8 digits.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#2563eb',
                    background: '#ffffff',
                    color: '#0f172a',
                    backdrop: 'rgba(15,23,42,0.4)',
                    customClass: {
                        popup: 'rounded-2xl shadow-2xl border border-blue-100',
                        title: 'text-xl font-semibold',
                        confirmButton: 'px-6 py-2 rounded-lg'
                    },
                });
                return;
            }

            const passwordExpiry = Math.max(0, Number(this.form.password_expiry_date) || 0);
            const formData = {
                user_id: this.form.user_id,
                username: this.form.username,
                official_name: this.form.official_name,
                official_title: this.form.official_title,
                mobile_number: this.form.mobile_number,
                official_tel: this.form.official_tel,
                user_printer: this.form.user_printer,
                print_route: this.form.print_route,
                menu_type: this.form.menu_type,
                access_unit_price: this.form.access_unit_price,
                access_customer_acct: this.form.access_customer_acct,
                amend_mc: this.form.amend_mc,
                amend_mc_price: this.form.amend_mc_price,
                salesperson_code: this.form.salesperson_code,
                rc_rt_price: this.form.rc_rt_price,
                board_rc_cost: this.form.board_rc_cost,
                password_expiry_date: passwordExpiry,
                status: this.form.user_status,
                amend_expired_password: this.form.amend_password === 'Y' ? 'Yes' : 'No',
                password: this.form.password,
                password_confirmation: this.form.password_confirmation,
                _token: this.$page.props.csrf
            };

            this.$inertia.post('/user', formData, {
                preserveState: false,
                preserveScroll: true,
                onStart: () => {
                    console.log('Form submission started');
                },
                onSuccess: (page) => {
                    console.log('User created successfully');
                },
                onError: (errors) => {
                    console.error('Form submission errors:', errors);
                },
                onFinish: () => {
                    console.log('Form submission finished');
                }
            });
        }
    }
}
</script>

<style scoped>
/* Modern Animations */
.animate-fadeIn {
    animation: fadeIn 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.animate-slideIn {
    animation: slideIn 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-20px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Glass morphism effect */
.backdrop-blur-sm {
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}

/* Hover effects */
.hover\:scale-105:hover {
    transform: scale(1.05);
}

/* Focus effects */
.focus\:ring-4:focus {
    box-shadow: 0 0 0 4px rgba(var(--ring-color), 0.3);
}

/* Custom gradient backgrounds */
.bg-gradient-to-br {
    background: linear-gradient(135deg, var(--tw-gradient-stops));
}

/* Smooth transitions */
button, a, input, select, textarea, .transition-all, .transition {
    transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 200ms;
}

/* Promote transform animations to compositor for smoother hover effects */
.transform {
    will-change: transform;
}

/* Respect users who prefer reduced motion */
@media (prefers-reduced-motion: reduce) {
    button, a, input, select, textarea, .transition-all, .transition, .animate-fadeIn, .animate-slideIn {
        transition: none !important;
        animation: none !important;
    }
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
}

/* Mobile responsiveness */
@media (max-width: 640px) {
    .max-w-6xl {
        max-width: 100%;
        margin: 0;
        padding: 0 1rem;
    }

    .rounded-2xl {
        border-radius: 1rem;
    }

    .p-8 {
        padding: 1.5rem;
    }

    .text-3xl {
        font-size: 1.875rem;
    }

    .grid-cols-1.lg\:grid-cols-2 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }

    .backdrop-blur-sm {
        backdrop-filter: none;
        -webkit-backdrop-filter: none;
    }

    .shadow-xl, .shadow-2xl, .shadow-lg {
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .bg-white\/80 {
        background-color: rgba(31, 41, 55, 0.8);
    }

    .text-gray-800 {
        color: #f9fafb;
    }

    .border-gray-200 {
        border-color: #374151;
    }
}

/* Form validation styles */
.form-input:invalid {
    border-color: #ef4444;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}

.form-input:valid {
    border-color: #10b981;
}

/* Loading state */
.btn-loading {
    position: relative;
    pointer-events: none;
}

.btn-loading::after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    top: 50%;
    left: 50%;
    margin-left: -10px;
    margin-top: -10px;
    border: 2px solid transparent;
    border-top-color: currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
