<template>
    <AppLayout :header="'Update Customer Account'">
    <Head title="Customer Account Management" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 via-cyan-600 to-teal-500 p-6 rounded-t-lg shadow-lg overflow-hidden relative">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20 animate-pulse-slow"></div>
        <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10 animate-pulse-slow animation-delay-500"></div>
        <div class="flex items-center">
            <div class="bg-gradient-to-br from-cyan-500 to-teal-600 p-3 rounded-lg shadow-inner mr-4">
                <i class="fas fa-user-edit text-white text-2xl"></i>
            </div>
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-white text-shadow">Update Customer Account</h2>
                <p class="text-teal-100">Manage and update customer account information</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6 bg-gradient-to-br from-white to-cyan-50">
        <div class="max-w-7xl mx-auto">
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2">
              <div class="relative bg-gradient-to-br from-blue-50 via-cyan-50 to-teal-100 p-8 rounded-2xl shadow-2xl border-t-4 border-cyan-500 overflow-hidden mb-8 animate-fade-in-up">
                <div class="absolute -top-16 -right-16 w-40 h-40 bg-cyan-200 rounded-full opacity-30"></div>
                <div class="absolute -bottom-12 -left-12 w-32 h-32 bg-teal-200 rounded-full opacity-30"></div>
                <div class="flex items-center mb-6 pb-3 border-b border-gray-200 relative z-10">
                    <div class="p-2 bg-gradient-to-r from-cyan-500 to-teal-600 rounded-lg mr-4 shadow-md">
                        <i class="fas fa-search text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">Find Customer</h3>
                </div>

                <!-- Search Section -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 relative z-10">
                    <div class="md:col-span-2">
                      <label for="searchQuery" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        <span class="flex items-center justify-center h-6 w-6 rounded-full bg-gradient-to-br from-cyan-500 to-teal-500 text-white mr-3 shadow-md">
                          <i class="fas fa-search text-xs"></i>
                      </span>
                        Find Customer Account
                      </label>
                      <div class="relative flex group">
                        <input id="searchQuery" type="text" v-model="searchQuery" placeholder="Search by code or name..." class="input-field" @keyup.enter="openCustomerAccountModal">
                        <button type="button" @click="openCustomerAccountModal" class="lookup-button from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600">
                        <i class="fas fa-table"></i>
                      </button>
                    </div>
                  </div>
                    <div class="md:col-span-1 flex flex-col justify-end">
                      <label class="block text-sm font-medium text-gray-700 mb-2">&nbsp;</label>
                      <button type="button" @click="openAddNewCustomerModal" class="primary-button group w-full">
                          <span class="shimmer-effect"></span>
                          <i class="fas fa-plus-circle mr-2 group-hover:rotate-90 transition-transform duration-300"></i>
                          Add New Customer
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Right Column -->
            <div class="flex flex-col space-y-6">
              <!-- Information Card -->
              <div class="bg-white rounded-xl shadow-md border-t-4 border-blue-400 p-6">
                <div class="flex items-center mb-2">
                  <div class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-br from-green-400 to-teal-400 rounded-lg mr-3">
                    <i class="fas fa-info text-white text-2xl"></i>
                  </div>
                  <h3 class="text-xl font-bold text-gray-800">Information</h3>
                </div>
                <hr class="my-2 border-blue-100">

                <div class="text-gray-700 mb-4">
                  Gunakan form ini untuk memperbarui data customer account. Pastikan semua informasi yang dimasukkan sudah benar dan lengkap.
                </div>
                <div class="bg-blue-50 rounded-lg p-4">
                  <div class="font-bold text-blue-700 mb-2">Petunjuk:</div>
                  <ul class="list-disc pl-5 text-blue-700 space-y-1 text-sm">
                    <li>Masukkan kode customer untuk mencari data</li>
                    <li>Klik tombol tabel untuk melihat daftar customer</li>
                    <li>Pilih customer dari modal untuk edit</li>
                    <li>Klik "Add New" untuk membuat customer baru</li>
                    <li>Klik Save untuk menyimpan perubahan</li>
                  </ul>
                </div>
              </div>


              <!-- Quick Links Card -->
              <div class="bg-white rounded-xl shadow-md border-t-4 border-purple-400 p-6">
                <div class="flex items-center mb-2">
                  <div class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-br from-purple-400 to-pink-400 rounded-lg mr-3">
                    <i class="fas fa-link text-white text-2xl"></i>
                  </div>
                  <h3 class="text-xl font-bold text-gray-800">Quick Links</h3>
                </div>
                <hr class="my-2 border-purple-100">
                <div class="space-y-3 mt-4">
                  <Link :href="route('vue.update-customer-account.view-print')" class="flex items-center p-3 rounded-lg bg-green-50 hover:bg-green-100 transition">
                    <span class="inline-flex items-center justify-center w-9 h-9 bg-green-400 rounded-lg mr-3">
                      <i class="fas fa-print text-white text-xl"></i>
                    </span>
                    <div>
                      <div class="font-bold text-green-800">View & Print</div>
                      <div class="text-xs text-green-700">Print customer list</div>
                    </div>
                  </Link>
                  <Link :href="route('vue.customer-group.index')" class="flex items-center p-3 rounded-lg bg-blue-50 hover:bg-blue-100 transition">
                    <span class="inline-flex items-center justify-center w-9 h-9 bg-blue-400 rounded-lg mr-3">
                      <i class="fas fa-users text-white text-xl"></i>
                    </span>
                    <div>
                      <div class="font-bold text-blue-800">Customer Groups</div>
                      <div class="text-xs text-blue-700">Manage customer groups</div>
                    </div>
                  </Link>
                  <Link :href="route('vue.customer-alternate-address.index')" class="flex items-center p-3 rounded-lg bg-purple-50 hover:bg-purple-100 transition">
                    <span class="inline-flex items-center justify-center w-9 h-9 bg-purple-400 rounded-lg mr-3">
                      <i class="fas fa-map-marker-alt text-white text-xl"></i>
                    </span>
                    <div>
                      <div class="font-bold text-purple-800">Alternate Addresses</div>
                      <div class="text-xs text-purple-700">Manage addresses</div>
                    </div>
                  </Link>
                </div>
                </div>
              </div>
            </div>
        </div>
    </div>

    <!-- Customer Account Selection Modal -->
    <CustomerAccountModal
        v-if="showCustomerAccountModal"
        :show="showCustomerAccountModal"
        :customerAccounts="customers"
        :initial-search="searchQuery"
        @close="closeCustomerAccountModal"
        @select="selectCustomerAccount"
    />

    <!-- Add New Customer Modal -->
    <div v-if="showAddNewCustomerModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="closeAddNewCustomerModal"></div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl w-full">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-green-600 to-green-700 text-white">
                    <div class="flex items-center">
                        <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
                            <i class="fas fa-plus"></i>
                        </div>
                        <h3 class="text-xl font-semibold">Add New Customer Account</h3>
                    </div>
                    <button type="button" @click="closeAddNewCustomerModal" class="text-white hover:text-gray-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Modal content -->
                <div class="bg-white px-6 pt-5 pb-6 max-h-[70vh] overflow-y-auto">
                    <!-- New Customer Form -->
                    <div class="space-y-6">
                        <!-- Basic Information -->
                        <div class="border-b border-gray-200 pb-4">
                            <h4 class="text-base font-medium text-gray-800 mb-3">
                                <i class="fas fa-info-circle mr-2 text-green-500"></i>Basic Information
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Customer Code *</label>
                                    <input type="text" v-model="newCustomerForm.customer_code" class="form-input" required>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Customer Name *</label>
                                    <input type="text" v-model="newCustomerForm.customer_name" class="form-input" required>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Short Name</label>
                                    <input type="text" v-model="newCustomerForm.short_name" class="form-input">
                                    <span class="text-xs text-gray-500">For Production</span>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Contact Person</label>
                                    <input type="text" v-model="newCustomerForm.contact_person" class="form-input">
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Address 1</label>
                                    <textarea v-model="newCustomerForm.address" rows="2" class="form-input"></textarea>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Address 2</label>
                                    <textarea v-model="newCustomerForm.address2" rows="2" class="form-input"></textarea>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Address 3</label>
                                    <textarea v-model="newCustomerForm.address3" rows="2" class="form-input"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="border-b border-gray-200 pb-4">
                            <h4 class="text-base font-medium text-gray-800 mb-3">
                                <i class="fas fa-phone mr-2 text-green-500"></i>Contact Information
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Telephone No</label>
                                    <input type="text" v-model="newCustomerForm.telephone_no" class="form-input">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Fax No</label>
                                    <input type="text" v-model="newCustomerForm.fax_no" class="form-input">
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input type="email" v-model="newCustomerForm.co_email" class="form-input">
                                </div>
                            </div>
                        </div>

                        <!-- Financial Settings -->
                        <div class="border-b border-gray-200 pb-4">
                            <h4 class="text-base font-medium text-gray-800 mb-3">
                                <i class="fas fa-credit-card mr-2 text-green-500"></i>Financial Settings
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Credit Limit</label>
                                    <input type="number" v-model="newCustomerForm.credit_limit" class="form-input" min="0" step="0.01">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Credit Terms (Days)</label>
                                    <input type="number" v-model="newCustomerForm.credit_terms" class="form-input" min="0">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Account Type</label>
                                    <select v-model="newCustomerForm.ac_type" class="form-input">
                                        <option value="N-Local">N-Local</option>
                                        <option value="Y-Foreign">Y-Foreign</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Currency Code</label>
                                    <select v-model="newCustomerForm.currency_code" class="form-input">
                                        <option value="IDR">IDR</option>
                                        <option value="USD">USD</option>
                                    </select>
                                    <span class="text-xs text-gray-500">Leave blank if Local Account</span>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">NPWP</label>
                                    <input type="text" v-model="newCustomerForm.npwp" class="form-input" placeholder="Enter NPWP number">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Print AR Aging</label>
                                    <select v-model="newCustomerForm.print_ar_aging" class="form-input">
                                        <option value="N-No">N-No</option>
                                        <option value="Y-Yes">Y-Yes</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Classification -->
                        <div class="border-b border-gray-200 pb-4">
                            <h4 class="text-base font-medium text-gray-800 mb-3">
                                <i class="fas fa-tags mr-2 text-green-500"></i>Classification
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Salesperson Code</label>
                                    <div class="flex">
                                        <input type="text" v-model="newCustomerForm.salesperson_code" class="form-input flex-grow rounded-r-none">
                                        <button type="button" @click="openSalespersonModal($event)" class="px-3 py-2 bg-blue-500 text-white rounded-r-md hover:bg-blue-600 transition-colors">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                    <div v-if="newCustomerForm.salesperson_code && getSalespersonName(newCustomerForm.salesperson_code)" class="mt-1 text-sm text-gray-600">
                                        {{ getSalespersonName(newCustomerForm.salesperson_code) }}
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Industrial Code</label>
                                    <div class="flex">
                                        <input type="text" v-model="newCustomerForm.industrial_code" class="form-input flex-grow rounded-r-none">
                                        <button type="button" @click="openIndustryModal($event)" class="px-3 py-2 bg-blue-500 text-white rounded-r-md hover:bg-blue-600 transition-colors">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                    <div v-if="newCustomerForm.industrial_code && getIndustryName(newCustomerForm.industrial_code)" class="mt-1 text-sm text-gray-600">
                                        {{ getIndustryName(newCustomerForm.industrial_code) }}
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Geographical</label>
                                    <div class="flex">
                                        <input type="text" v-model="newCustomerForm.geographical" class="form-input flex-grow rounded-r-none">
                                        <button type="button" @click="openGeoModal($event)" class="px-3 py-2 bg-blue-500 text-white rounded-r-md hover:bg-blue-600 transition-colors">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                    <div v-if="newCustomerForm.geographical && getGeoLocation(newCustomerForm.geographical)" class="mt-1 text-sm text-gray-600">
                                        {{ getGeoLocation(newCustomerForm.geographical) }}
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Customer Group</label>
                                    <div class="flex">
                                        <input type="text" v-model="newCustomerForm.grouping_code" class="form-input flex-grow rounded-r-none">
                                        <button type="button" @click="openCustomerGroupModal($event)" class="px-3 py-2 bg-blue-500 text-white rounded-r-md hover:bg-blue-600 transition-colors">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                    <div v-if="newCustomerForm.grouping_code && getCustomerGroupName(newCustomerForm.grouping_code)" class="mt-1 text-sm text-gray-600">
                                        {{ getCustomerGroupName(newCustomerForm.grouping_code) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                    <button type="button" @click="closeAddNewCustomerModal" class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Cancel
                    </button>
                    <button
                        type="button"
                        @click="saveNewCustomerAccount"
                        :disabled="isSaving"
                        class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:bg-green-400"
                    >
                        <i v-if="!isSaving" class="fas fa-save mr-2"></i>
                        <div v-else class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full mr-2"></div>
                        {{ isSaving ? 'Creating...' : 'Create Customer' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Support Modals -->
    <SalespersonModal
        v-if="showSalespersonModal"
        :show="showSalespersonModal"
        :salespersons="salespersons"
        @close="closeSalespersonModal"
        @select="selectSalesperson"
        class="modal-lookup"
        style="z-index: 100;"
    />

    <IndustryModal
        v-if="showIndustryModal"
        :show="showIndustryModal"
        :industries="industries"
        @close="closeIndustryModal"
        @select="selectIndustry"
        class="modal-lookup"
        style="z-index: 100;"
    />

    <GeoModal
        v-if="showGeoModal"
        :show="showGeoModal"
        :geos="geos"
        @close="closeGeoModal"
        @select="selectGeo"
        class="modal-lookup"
        style="z-index: 100;"
    />

    <CustomerGroupModal
        v-if="showCustomerGroupModal"
        :show="showCustomerGroupModal"
        :customer-groups="customerGroups"
        @close="closeCustomerGroupModal"
        @select="selectCustomerGroup"
        class="modal-lookup"
        style="z-index: 100;"
    />

    <!-- Customer Edit Modal -->
    <div v-if="showEditModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showEditModal = false"></div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl w-full">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                    <div class="flex items-center">
                        <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <h3 class="text-xl font-semibold">Edit Customer: {{ form.customer_code }} - {{ form.customer_name }}</h3>
                    </div>
                    <button type="button" @click="showEditModal = false" class="text-white hover:text-gray-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Modal content -->
                <div class="bg-white px-6 pt-5 pb-6 max-h-[70vh] overflow-y-auto">
                    <!-- Customer Details Form -->
                    <div class="space-y-6">
                        <!-- Basic Information -->
                        <div class="border-b border-gray-200 pb-4">
                            <h4 class="text-base font-medium text-gray-800 mb-3">
                                <i class="fas fa-info-circle mr-2 text-blue-500"></i>Basic Information
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Customer Code</label>
                                    <input type="text" v-model="form.customer_code" class="form-input bg-gray-100" readonly>
                                    <span class="text-xs text-gray-500">Read-only field</span>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Customer Name</label>
                                    <input type="text" v-model="form.customer_name" class="form-input" required>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Contact Person</label>
                                    <input type="text" v-model="form.contact_person" class="form-input">
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Address 1</label>
                                    <textarea v-model="form.address" rows="2" class="form-input"></textarea>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Address 2</label>
                                    <textarea v-model="form.address2" rows="2" class="form-input"></textarea>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Address 3</label>
                                    <textarea v-model="form.address3" rows="2" class="form-input"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="border-b border-gray-200 pb-4">
                            <h4 class="text-base font-medium text-gray-800 mb-3">
                                <i class="fas fa-phone mr-2 text-blue-500"></i>Contact Information
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Telephone No</label>
                                    <input type="text" v-model="form.telephone_no" class="form-input">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Fax No</label>
                                    <input type="text" v-model="form.fax_no" class="form-input">
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input type="email" v-model="form.co_email" class="form-input">
                                </div>
                            </div>
                        </div>

                        <!-- Financial Information -->
                        <div class="border-b border-gray-200 pb-4">
                            <h4 class="text-base font-medium text-gray-800 mb-3">
                                <i class="fas fa-credit-card mr-2 text-blue-500"></i>Financial Information
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Credit Limit</label>
                                    <input type="number" v-model="form.credit_limit" step="0.01" class="form-input">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Credit Terms (Days)</label>
                                    <input type="number" v-model="form.credit_terms" class="form-input">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Account Type</label>
                                    <select v-model="form.ac_type" class="form-input">
                                        <option value="N-Local">N-Local</option>
                                        <option value="Y-Foreign">Y-Foreign</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Currency Code</label>
                                    <select v-model="form.currency_code" class="form-input">
                                        <option value="IDR">IDR</option>
                                        <option value="USD">USD</option>
                                    </select>
                                    <span class="text-xs text-gray-500">Leave blank if Local Account</span>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">NPWP</label>
                                    <input type="text" v-model="form.npwp" class="form-input" placeholder="Enter NPWP number">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Print AR Aging</label>
                                    <select v-model="form.print_ar_aging" class="form-input">
                                        <option value="N-No">N-No</option>
                                        <option value="Y-Yes">Y-Yes</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Classification Information -->
                        <div>
                            <h4 class="text-base font-medium text-gray-800 mb-3">
                                <i class="fas fa-tags mr-2 text-blue-500"></i>Classification & Codes
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Salesperson Code</label>
                                    <div class="flex">
                                        <input type="text" v-model="form.salesperson_code" class="form-input flex-grow rounded-r-none">
                                        <button type="button" @click="openSalespersonModal($event)" class="px-3 py-2 bg-blue-500 text-white rounded-r-md hover:bg-blue-600 transition-colors">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                    <div v-if="form.salesperson_code && getSalespersonName(form.salesperson_code)" class="mt-1 text-sm text-gray-600">
                                        {{ getSalespersonName(form.salesperson_code) }}
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Industrial Code</label>
                                    <div class="flex">
                                        <input type="text" v-model="form.industrial_code" class="form-input flex-grow rounded-r-none">
                                        <button type="button" @click="openIndustryModal($event)" class="px-3 py-2 bg-blue-500 text-white rounded-r-md hover:bg-blue-600 transition-colors">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                    <div v-if="form.industrial_code && getIndustryName(form.industrial_code)" class="mt-1 text-sm text-gray-600">
                                        {{ getIndustryName(form.industrial_code) }}
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Geographical</label>
                                    <div class="flex">
                                        <input type="text" v-model="form.geographical" class="form-input flex-grow rounded-r-none">
                                        <button type="button" @click="openGeoModal($event)" class="px-3 py-2 bg-blue-500 text-white rounded-r-md hover:bg-blue-600 transition-colors">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                    <div v-if="form.geographical && getGeoLocation(form.geographical)" class="mt-1 text-sm text-gray-600">
                                        {{ getGeoLocation(form.geographical) }}
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Grouping Code</label>
                                    <div class="flex">
                                        <input type="text" v-model="form.grouping_code" class="form-input flex-grow rounded-r-none">
                                        <button type="button" @click="openCustomerGroupModal($event)" class="px-3 py-2 bg-blue-500 text-white rounded-r-md hover:bg-blue-600 transition-colors">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                    <div v-if="form.grouping_code && getCustomerGroupName(form.grouping_code)" class="mt-1 text-sm text-gray-600">
                                        {{ getCustomerGroupName(form.grouping_code) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                    <button type="button" @click="showEditModal = false" class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </button>
                    <button
                        type="button"
                        @click="saveCustomerAccount"
                        :disabled="isSaving"
                        class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:bg-blue-400"
                    >
                        <div v-if="isSaving" class="animate-spin h-5 w-5 mr-2 border-2 border-white border-t-transparent rounded-full"></div>
                        {{ isSaving ? 'Saving...' : 'Save Changes' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div v-if="notification.show" class="fixed bottom-4 right-4 z-50 shadow-lg rounded-md px-6 py-4 transition-all transform"
        :class="{
            'bg-green-500': notification.type === 'success',
            'bg-red-500': notification.type === 'error',
            'bg-yellow-500': notification.type === 'warning',
        }">
        <div class="flex items-center text-white">
            <i class="fas mr-2" :class="{
                'fa-check-circle': notification.type === 'success',
                'fa-exclamation-circle': notification.type === 'error',
                'fa-exclamation-triangle': notification.type === 'warning'
            }"></i>
            <span>{{ notification.message }}</span>
        </div>
    </div>
    </AppLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { Head } from '@inertiajs/vue3'
import { route } from '@/ziggy'
import CustomerAccountModal from '@/Components/customer-account-modal.vue'
import SalespersonModal from '@/Components/salesperson-modal.vue'
import IndustryModal from '@/Components/industry-modal.vue'
import GeoModal from '@/Components/geo-modal.vue'
import CustomerGroupModal from '@/Components/customer-group-modal.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import axios from 'axios'

// Component state
const page = usePage()
const showCustomerAccountModal = ref(false)
const showSalespersonModal = ref(false)
const showIndustryModal = ref(false)
const showGeoModal = ref(false)
const showCustomerGroupModal = ref(false)
const showEditModal = ref(false)
const showAddNewCustomerModal = ref(false)
const notification = ref({ show: false, message: '', type: 'success' })
const customerSelected = ref(false)
const searchQuery = ref('')
const customers = ref([])
const isSaving = ref(false)

// Data from API
const industries = ref([])
const geos = ref([])
const salespersons = ref([])
const customerGroups = ref([])

// Form state
const form = reactive({
    customer_code: '',
    customer_name: '',
    address: '',
    address2: '',
    address3: '',
    contact_person: '',
    telephone_no: '',
    fax_no: '',
    co_email: '',
    credit_limit: 0,
    credit_terms: 0,
    ac_type: 'N-Local', // Default value
    npwp: '',
    salesperson_code: '',
    industrial_code: '',
    geographical: '',
    grouping_code: '',
    print_ar_aging: 'N-No' // Default value
})

// Form state for new customer
const newCustomerForm = reactive({
    customer_code: '',
    customer_name: '',
    address: '',
    address2: '',
    address3: '',
    contact_person: '',
    telephone_no: '',
    fax_no: '',
    co_email: '',
    credit_limit: 0,
    credit_terms: 0,
    ac_type: 'N-Local',
    npwp: '',
    salesperson_code: '',
    industrial_code: '',
    geographical: '',
    grouping_code: '',
    print_ar_aging: 'N-No'
})

// Search for customers - Now handled by modal
const searchCustomers = async () => {
    // This function is now deprecated as search is handled by the modal
    // Opening the modal will automatically filter based on the search query
    openCustomerAccountModal()
}

// Clear search results
const clearSearch = () => {
    searchQuery.value = ''
    customers.value = []
    // Note: The modal will automatically update its search when searchQuery changes
}

// Data loading functions
const loadIndustries = async () => {
    try {
        const response = await axios.get('/api/industry')
        industries.value = response.data
    } catch (error) {
        console.error('Error loading industries:', error)
        showNotification('Failed to load industry data', 'error')
    }
}

const loadGeos = async () => {
    try {
        const response = await axios.get('/api/geos')
        geos.value = response.data
    } catch (error) {
        console.error('Error loading geo data:', error)
        showNotification('Failed to load geographical data', 'error')
    }
}

const loadSalespersons = async () => {
    try {
        const response = await axios.get('/api/salespersons')
        salespersons.value = response.data
    } catch (error) {
        console.error('Error loading salespersons:', error)
        showNotification('Failed to load salesperson data', 'error')
    }
}

const loadCustomerGroups = async () => {
    try {
        const response = await axios.get('/api/customer-groups')
        customerGroups.value = response.data
    } catch (error) {
        console.error('Error loading customer groups:', error)
        showNotification('Failed to load customer group data', 'error')
    }
}



// Helper functions to get names for selected codes
const getIndustryName = (code) => {
    const industry = industries.value.find(i => i.code === code)
    return industry ? industry.name : ''
}

const getGeoLocation = (code) => {
    const geo = geos.value.find(g => g.code === code)
    if (!geo) return ''
    return `${geo.country} - ${geo.state}, ${geo.town}`
}

const getSalespersonName = (code) => {
    const salesperson = salespersons.value.find(s => s.code === code)
    return salesperson ? salesperson.name : ''
}

const getCustomerGroupName = (code) => {
    const group = customerGroups.value.find(g => g.group_code === code || g.code === code)
    return group ? (group.description || group.name || '') : ''
}

// Customer account modal functions
const openCustomerAccountModal = async () => {
    // Always reload data to ensure fresh data
    await loadCustomerAccounts()

    showCustomerAccountModal.value = true
}

const closeCustomerAccountModal = () => {
    showCustomerAccountModal.value = false
}

// Add new customer modal functions
const openAddNewCustomerModal = () => {
    // Reset form to default values
    Object.assign(newCustomerForm, {
        customer_code: '',
        customer_name: '',
        address: '',
        address2: '',
        address3: '',
        contact_person: '',
        telephone_no: '',
        fax_no: '',
        co_email: '',
        credit_limit: 0,
        credit_terms: 0,
        ac_type: 'N-Local',
        npwp: '',
        salesperson_code: '',
        industrial_code: '',
        geographical: '',
        grouping_code: '',
        print_ar_aging: 'N-No'
    })
    showAddNewCustomerModal.value = true
}

const closeAddNewCustomerModal = () => {
    showAddNewCustomerModal.value = false
}

const selectCustomerAccount = (account) => {
    // Make a copy of the account data to avoid direct reference
    const accountData = { ...account }

    // Fill the form with customer data, ensuring all required fields have valid values
    form.customer_code = accountData.customer_code
    form.customer_name = accountData.customer_name || ''
    form.address = accountData.address || ''
    form.address2 = accountData.address2 || ''
    form.address3 = accountData.address3 || ''
    form.contact_person = accountData.contact_person || ''
    form.telephone_no = accountData.telephone_no || ''
    form.fax_no = accountData.fax_no || ''
    form.co_email = accountData.co_email || ''
    form.credit_limit = accountData.credit_limit || 0
    form.credit_terms = accountData.credit_terms || 0
    form.ac_type = accountData.ac_type || accountData.account_type || 'N-Local'

    // Ensure ac_type has a valid value
    if (!['Y-Foreign', 'N-Local'].includes(form.ac_type)) {
        form.ac_type = 'N-Local'
    }

    form.npwp = accountData.npwp || ''
    form.salesperson_code = accountData.salesperson_code || ''
    form.industrial_code = accountData.industrial_code || ''
    form.geographical = accountData.geographical || ''
    form.grouping_code = accountData.grouping_code || ''
    form.print_ar_aging = accountData.print_ar_aging || 'N-No'

    // Ensure print_ar_aging has a valid value
    if (!['Y-Yes', 'N-No'].includes(form.print_ar_aging)) {
        form.print_ar_aging = 'N-No'
    }

    closeCustomerAccountModal()
    customerSelected.value = true
    showEditModal.value = true // Automatically open the edit modal
}

// Salesperson modal functions
const openSalespersonModal = (event) => {
    // Prevent event propagation to avoid closing the edit modal
    if (event) {
        event.stopPropagation();
    }
    showSalespersonModal.value = true;
}

const closeSalespersonModal = () => {
    showSalespersonModal.value = false;
}

const selectSalesperson = (salesperson) => {
    // Check if we're in add new customer modal or edit modal
    if (showAddNewCustomerModal.value) {
        newCustomerForm.salesperson_code = salesperson.code;
    } else {
        form.salesperson_code = salesperson.code;
    }
    closeSalespersonModal();
}

// Industry modal functions
const openIndustryModal = (event) => {
    // Prevent event propagation to avoid closing the edit modal
    if (event) {
        event.stopPropagation();
    }
    showIndustryModal.value = true;
}

const closeIndustryModal = () => {
    showIndustryModal.value = false;
}

const selectIndustry = (industry) => {
    // Check if we're in add new customer modal or edit modal
    if (showAddNewCustomerModal.value) {
        newCustomerForm.industrial_code = industry.code;
    } else {
        form.industrial_code = industry.code;
    }
    closeIndustryModal();
}

// Geo modal functions
const openGeoModal = (event) => {
    // Prevent event propagation to avoid closing the edit modal
    if (event) {
        event.stopPropagation();
    }
    showGeoModal.value = true;
}

const closeGeoModal = () => {
    showGeoModal.value = false;
}

const selectGeo = (geo) => {
    // Check if we're in add new customer modal or edit modal
    if (showAddNewCustomerModal.value) {
        newCustomerForm.geographical = geo.code;
    } else {
        form.geographical = geo.code;
    }
    closeGeoModal();
}

// Customer group modal functions
const openCustomerGroupModal = (event) => {
    // Prevent event propagation to avoid closing the edit modal
    if (event) {
        event.stopPropagation();
    }
    showCustomerGroupModal.value = true;
}

const closeCustomerGroupModal = () => {
    showCustomerGroupModal.value = false;
}

const selectCustomerGroup = (group) => {
    // Check if we're in add new customer modal or edit modal
    if (showAddNewCustomerModal.value) {
        newCustomerForm.grouping_code = group.group_code;
    } else {
        form.grouping_code = group.group_code;
    }
    closeCustomerGroupModal();
}

// Save customer account function
const saveCustomerAccount = async () => {
    if (!form.customer_code) {
        showNotification('Please select a customer first', 'warning')
        return
    }

    // Basic validation
    if (!form.customer_name.trim()) {
        showNotification('Customer name is required', 'warning')
        return
    }

    // Email validation if provided
    if (form.co_email && form.co_email.trim()) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
        if (!emailRegex.test(form.co_email)) {
            showNotification('Please enter a valid email address', 'warning')
            return
        }
    }

    // Ensure required fields have valid values
    if (!form.ac_type || !['Y-Foreign', 'N-Local'].includes(form.ac_type)) {
        form.ac_type = 'N-Local'; // Default to N-Local if not set or invalid
    }

    if (!form.print_ar_aging || !['Y-Yes', 'N-No'].includes(form.print_ar_aging)) {
        form.print_ar_aging = 'N-No'; // Default to N-No if not set or invalid
    }

    isSaving.value = true

    try {
        // Create a copy of the form data to send to the API
        const customerData = { ...form }

        // Ensure numeric fields are properly formatted
        if (customerData.credit_limit) {
            customerData.credit_limit = parseFloat(customerData.credit_limit);
        }

        if (customerData.credit_terms) {
            customerData.credit_terms = parseInt(customerData.credit_terms);
        }

        console.log('Sending customer data:', customerData);

        // Check if the customer already exists
        let response;
        const existingCustomer = customers.value.find(c => c.customer_code === form.customer_code);

        if (existingCustomer && existingCustomer.id) {
            // Update existing customer using PUT - Fix the URL to match the API endpoint
            console.log('Updating existing customer with ID:', existingCustomer.id);
            response = await axios.put(`/api/customer-accounts/${existingCustomer.id}`, customerData);
        } else {
            // Create new customer using POST - Fix the URL to match the API endpoint
            console.log('Creating new customer');
            response = await axios.post('/api/customer-accounts', customerData);
        }

        if (response.data && response.status >= 200 && response.status < 300) {
            showNotification('Customer account saved successfully', 'success')
            showEditModal.value = false

            // Reload customer accounts to get the latest data
            await loadCustomerAccounts()
        } else {
            throw new Error(response.data?.message || 'No data returned from the server')
        }
    } catch (error) {
        console.error('Error saving customer account:', error)
        let errorMessage = 'Failed to save customer account'

        if (error.response) {
            // Handle validation errors
            if (error.response.status === 422 && error.response.data.errors) {
                // Log detailed validation errors for debugging
                console.error('Validation errors:', error.response.data.errors)

                // Format validation errors for display
                const validationErrors = Object.entries(error.response.data.errors).map(([field, messages]) => {
                    return `${field}: ${messages.join(', ')}`;
                });

                errorMessage += ': ' + validationErrors.join(' | ')
            } else if (error.response.data.message) {
                errorMessage += ': ' + error.response.data.message
            }
        } else if (error.message) {
            errorMessage += ': ' + error.message
        }

        showNotification(errorMessage, 'error')
    } finally {
        isSaving.value = false
    }
}

// Save new customer account function
const saveNewCustomerAccount = async () => {
    // Basic validation
    if (!newCustomerForm.customer_code.trim()) {
        showNotification('Customer code is required', 'warning')
        return
    }

    if (!newCustomerForm.customer_name.trim()) {
        showNotification('Customer name is required', 'warning')
        return
    }

    // Email validation if provided
    if (newCustomerForm.co_email && newCustomerForm.co_email.trim()) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
        if (!emailRegex.test(newCustomerForm.co_email)) {
            showNotification('Please enter a valid email address', 'warning')
            return
        }
    }

    // Check if customer code already exists
    const existingCustomer = customers.value.find(c => c.customer_code === newCustomerForm.customer_code.trim())
    if (existingCustomer) {
        showNotification('Customer code already exists. Please use a different code.', 'warning')
        return
    }

    isSaving.value = true

    try {
        // Create a copy of the form data to send to the API
        const customerData = { ...newCustomerForm }

        // Ensure numeric fields are properly formatted
        if (customerData.credit_limit) {
            customerData.credit_limit = parseFloat(customerData.credit_limit);
        }

        if (customerData.credit_terms) {
            customerData.credit_terms = parseInt(customerData.credit_terms);
        }

        console.log('Creating new customer:', customerData);

        const response = await axios.post('/api/customer-accounts', customerData);

        if (response.data && response.status >= 200 && response.status < 300) {
            showNotification('New customer account created successfully', 'success')
            closeAddNewCustomerModal()

            // Reload customer accounts to get the latest data
            await loadCustomerAccounts()

            // Clear the form
            Object.assign(newCustomerForm, {
                customer_code: '',
                customer_name: '',
                address: '',
                address2: '',
                address3: '',
                contact_person: '',
                telephone_no: '',
                fax_no: '',
                co_email: '',
                credit_limit: 0,
                credit_terms: 0,
                ac_type: 'N-Local',
                npwp: '',
                salesperson_code: '',
                industrial_code: '',
                geographical: '',
                grouping_code: '',
                print_ar_aging: 'N-No'
            })
        } else {
            throw new Error(response.data?.message || 'No data returned from the server')
        }
    } catch (error) {
        console.error('Error creating new customer account:', error)
        let errorMessage = 'Failed to create new customer account'

        if (error.response) {
            // Handle validation errors
            if (error.response.status === 422 && error.response.data.errors) {
                // Log detailed validation errors for debugging
                console.error('Validation errors:', error.response.data.errors)

                // Format validation errors for display
                const validationErrors = Object.entries(error.response.data.errors).map(([field, messages]) => {
                    return `${field}: ${messages.join(', ')}`;
                });

                errorMessage += ': ' + validationErrors.join(' | ')
            } else if (error.response.data.message) {
                errorMessage += ': ' + error.response.data.message
            }
        } else if (error.message) {
            errorMessage += ': ' + error.message
        }

        showNotification(errorMessage, 'error')
    } finally {
        isSaving.value = false
    }
}

// Display notification
const showNotification = (message, type = 'success') => {
    notification.value = {
        show: true,
        message,
        type
    }

    setTimeout(() => {
        notification.value.show = false
    }, 3000)
}

// Load customer accounts
const loadCustomerAccounts = async () => {
    try {
        const response = await axios.get('/api/customers-with-status')

        if (response.data && response.data.data) {
            customers.value = response.data.data
        }
    } catch (error) {
        console.error('Error loading customer accounts:', error)
        showNotification('Failed to load customer accounts', 'error')
    }
}

// Initialize component
onMounted(() => {
    // Load data from APIs
    loadCustomerAccounts()
    loadIndustries()
    loadGeos()
    loadSalespersons()
    loadCustomerGroups()

    // Check for flash messages
    if (page.props.flash && page.props.flash.message) {
        showNotification(page.props.flash.message, 'success')
    }
})
</script>

<style scoped>
.text-shadow {
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.input-field {
    @apply flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border-gray-300 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-300 group-hover:border-cyan-400 shadow-sm focus:shadow-md;
}

.lookup-button {
    @apply inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 rounded-r-md transition-all duration-300 bg-gradient-to-r text-white shadow-sm hover:shadow-md transform hover:-translate-y-px;
}

.primary-button {
    @apply h-full items-center justify-center bg-gradient-to-r from-cyan-500 to-blue-500 text-white font-bold py-2 px-6 rounded-md shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex relative overflow-hidden;
}

.secondary-button {
     @apply items-center justify-center bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 font-bold py-2 px-6 rounded-md shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex relative overflow-hidden border border-gray-300;
}

.form-input {
    @apply block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 transition-shadow;
}

.form-radio {
    @apply h-4 w-4 text-cyan-600 border-gray-300 focus:ring-cyan-500;
}

@keyframes shake {
  0%, 100% { transform: translateX(0); }
  25% { transform: translateX(-3px) rotate(-2deg); }
  75% { transform: translateX(3px) rotate(2deg); }
}
.group-hover\:animate-shake:hover {
    animation: shake 0.3s ease-in-out;
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

@keyframes pulse-slow {
    0%, 100% { transform: scale(1); opacity: 0.05; }
    50% { transform: scale(1.1); opacity: 0.08; }
}

.animate-pulse-slow {
    animation: pulse-slow 5s infinite;
}

.animation-delay-500 { 
    animation-delay: 0.5s; 
}

/* Add higher z-index for lookup modals */
:deep(.modal-lookup) {
    z-index: 100 !important; /* Higher than the edit modal (z-50) */
}

:deep(.modal-lookup > div) {
    z-index: 100 !important;
}

/* Ensure the modal backdrop also has higher z-index */
:deep(.modal-lookup .bg-black) {
    z-index: 99 !important;
}

/* Add additional styles to prevent modal stacking issues */
:deep(.modal-lookup .fixed) {
    z-index: 100 !important;
}

:deep(.modal-lookup .bg-opacity-75) {
    z-index: 99 !important;
}
</style>
