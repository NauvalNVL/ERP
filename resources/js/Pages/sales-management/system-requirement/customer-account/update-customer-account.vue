<template>
    <AppLayout :header="'Update Customer Account'">
    <div>
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg mb-0">
            <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
                <i class="fas fa-user-edit mr-3"></i> Update Customer Account
            </h2>
            <p class="text-cyan-100">Perbarui data akun customer di sistem</p>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Main Content -->
                <div class="lg:col-span-2">
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
                        <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                            <div class="p-2 bg-blue-500 rounded-lg mr-3">
                                <i class="fas fa-edit text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800">Customer Account Management</h3>
                        </div>

                        <!-- Header with navigation buttons -->
                        <div class="flex items-center space-x-2 mb-6">
                            <button type="button" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px" @click="goToDashboard">
                                <i class="fas fa-power-off"></i>
                            </button>
                            <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px">
                                <i class="fas fa-arrow-right"></i>
                            </button>
                            <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px">
                                <i class="fas fa-arrow-left"></i>
                            </button>
                            <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px">
                                <i class="fas fa-search"></i>
                            </button>
                            <button type="button" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px" @click="saveCustomerAccount">
                                <i class="fas fa-save"></i>
                            </button>
                        </div>

                        <!-- Form Section -->
                        <form @submit.prevent="saveCustomerAccount" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="customer_code" class="block text-sm font-medium text-gray-700 mb-1">Customer Code</label>
                                    <div class="relative flex">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                            <i class="fas fa-id-card"></i>
                                        </span>
                                        <input type="text" v-model="form.customer_code" id="customer_code" required maxlength="20"
                                            class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        <button type="button" @click="openCustomerAccountModal" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md transition-colors transform active:translate-y-px">
                                            <i class="fas fa-table"></i>
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Customer Name</label>
                                    <input type="text" v-model="form.customer_name" id="customer_name" maxlength="100"
                                        class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>
                            </div>

                            <!-- Additional Fields -->
                            <div v-if="showAdditionalFields" class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="short_name" class="block text-sm font-medium text-gray-700 mb-1">Short Name</label>
                                        <input type="text" v-model="form.short_name" id="short_name" maxlength="50"
                                            class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    </div>
                                    <div>
                                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                                        <textarea v-model="form.address" id="address" rows="3"
                                            class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors"></textarea>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-1">Contact Person</label>
                                        <input type="text" v-model="form.contact_person" id="contact_person" maxlength="100"
                                            class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    </div>
                                    <div>
                                        <label for="telephone_no" class="block text-sm font-medium text-gray-700 mb-1">Telephone No</label>
                                        <input type="text" v-model="form.telephone_no" id="telephone_no" maxlength="30"
                                            class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="fax_no" class="block text-sm font-medium text-gray-700 mb-1">Fax No</label>
                                        <input type="text" v-model="form.fax_no" id="fax_no" maxlength="30"
                                            class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    </div>
                                    <div>
                                        <label for="co_email" class="block text-sm font-medium text-gray-700 mb-1">Co. Email</label>
                                        <input type="email" v-model="form.co_email" id="co_email" maxlength="100"
                                            class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                                    <div>
                                        <label for="credit_limit" class="block text-sm font-medium text-gray-700 mb-1">Credit Limit</label>
                                        <input type="number" v-model="form.credit_limit" id="credit_limit" step="0.01"
                                            class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    </div>
                                    <div class="flex items-center">
                                        <label for="credit_terms" class="block text-sm font-medium text-gray-700 mr-2">Credit Terms:</label>
                                        <input type="number" v-model="form.credit_terms" id="credit_terms" class="w-20 px-3 py-2 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        <span class="ml-2 text-sm text-gray-700">Days</span>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <label class="block text-sm font-medium text-gray-700">Account Type:</label>
                                        <div class="flex items-center">
                                            <input type="radio" v-model="form.ac_type" id="ac_type_foreign" value="Y-Foreign" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                            <label for="ac_type_foreign" class="ml-2 block text-sm text-gray-900">Y-Foreign</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" v-model="form.ac_type" id="ac_type_local" value="N-Local" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                            <label for="ac_type_local" class="ml-2 block text-sm text-gray-900">N-Local</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                                    <div class="flex items-center">
                                        <label for="currency_code" class="block text-sm font-medium text-gray-700 mr-2">Currency Code:</label>
                                        <input type="text" v-model="form.currency_code" id="currency_code" maxlength="10"
                                            class="w-24 px-3 py-2 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        <span class="ml-4 text-sm text-gray-700">Leave Blank if Local Account</span>
                                    </div>
                                    <div>
                                        <label for="salesperson_code" class="block text-sm font-medium text-gray-700 mb-1">Salesperson Code:</label>
                                        <div class="relative flex">
                                            <input type="text" v-model="form.salesperson_code" id="salesperson_code" maxlength="20"
                                                class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                            <button type="button" @click="openSalespersonModal" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md transition-colors transform active:translate-y-px">
                                                <i class="fas fa-table"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                                    <div>
                                        <label for="industrial_code" class="block text-sm font-medium text-gray-700 mb-1">Industrial Code:</label>
                                        <div class="relative flex">
                                            <input type="text" v-model="form.industrial_code" id="industrial_code" maxlength="20"
                                                class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                            <button type="button" @click="openIndustryModal" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md transition-colors transform active:translate-y-px">
                                                <i class="fas fa-table"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="geographical" class="block text-sm font-medium text-gray-700 mb-1">Geographical:</label>
                                        <div class="relative flex">
                                            <input type="text" v-model="form.geographical" id="geographical" maxlength="20"
                                                class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                            <button type="button" @click="openGeoModal" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md transition-colors transform active:translate-y-px">
                                                <i class="fas fa-table"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="grouping_code" class="block text-sm font-medium text-gray-700 mb-1">Grouping Code:</label>
                                        <div class="relative flex">
                                            <input type="text" v-model="form.grouping_code" id="grouping_code" maxlength="20"
                                                class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                            <button type="button" @click="openCustomerGroupModal" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md transition-colors transform active:translate-y-px">
                                                <i class="fas fa-table"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-4">
                                    <label class="block text-sm font-medium text-gray-700">Print AR Aging:</label>
                                    <div class="flex items-center">
                                        <input type="radio" v-model="form.print_ar_aging" id="print_ar_aging_yes" value="Y-Yes" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                        <label for="print_ar_aging_yes" class="ml-2 block text-sm text-gray-900">Y-Yes</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" v-model="form.print_ar_aging" id="print_ar_aging_no" value="N-No" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                        <label for="print_ar_aging_no" class="ml-2 block text-sm text-gray-900">N-No</label>
                                    </div>
                                    <span class="ml-4 text-sm text-gray-700">[For Sales Order]</span>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column - Information -->
                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500 mb-6">
                        <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                            <div class="p-2 bg-blue-500 rounded-lg mr-3">
                                <i class="fas fa-info-circle text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800">Information</h3>
                        </div>
                        <div class="space-y-4">
                            <p class="text-gray-600">
                                Gunakan form ini untuk memperbarui data akun customer. Pastikan semua informasi yang dimasukkan sudah benar dan lengkap.
                            </p>
                            <div class="bg-blue-50 p-4 rounded-md">
                                <h4 class="font-semibold text-blue-800 mb-2">Petunjuk:</h4>
                                <ul class="list-disc list-inside text-blue-700 space-y-1">
                                    <li>Masukkan kode customer untuk mencari data</li>
                                    <li>Klik tombol tabel untuk melihat daftar customer</li>
                                    <li>Isi semua field yang diperlukan</li>
                                    <li>Klik Save untuk menyimpan perubahan</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-purple-500">
                        <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                            <div class="p-2 bg-purple-500 rounded-lg mr-3">
                                <i class="fas fa-link text-white"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Quick Links</h3>
                        </div>

                        <div class="grid grid-cols-1 gap-3">
                            <Link href="/update-customer-account/view-print" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                                <div class="p-2 bg-green-500 rounded-full mr-3">
                                    <i class="fas fa-print text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-green-900">View & Print</p>
                                    <p class="text-xs text-green-700">Print customer list</p>
                                </div>
                            </Link>
                            
                            <Link href="/customer-group" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                <div class="p-2 bg-blue-500 rounded-full mr-3">
                                    <i class="fas fa-users text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-blue-900">Customer Groups</p>
                                    <p class="text-xs text-blue-700">Manage customer groups</p>
                                </div>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modals -->
        <CustomerAccountModal 
            v-if="showCustomerAccountModal"
            :show="showCustomerAccountModal"
            @close="closeCustomerAccountModal"
            @select="selectCustomerAccount"
        />

        <SalespersonModal
            v-if="showSalespersonModal"
            :show="showSalespersonModal"
            @close="closeSalespersonModal"
            @select="selectSalesperson"
        />

        <IndustryModal
            v-if="showIndustryModal"
            :show="showIndustryModal"
            @close="closeIndustryModal"
            @select="selectIndustry"
        />

        <GeoModal
            v-if="showGeoModal"
            :show="showGeoModal"
            @close="closeGeoModal"
            @select="selectGeo"
        />

        <CustomerGroupModal
            v-if="showCustomerGroupModal"
            :show="showCustomerGroupModal"
            @close="closeCustomerGroupModal"
            @select="selectCustomerGroup"
        />
    </div>
    </AppLayout>
</template>

<script>
import { ref, reactive } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import CustomerAccountModal from '@/Components/customer-account-modal.vue'
import SalespersonModal from '@/Components/salesperson-modal.vue'
import IndustryModal from '@/Components/industry-modal.vue'
import GeoModal from '@/Components/geo-modal.vue'
import CustomerGroupModal from '@/Components/customer-group-modal.vue'
import AppLayout from '@/Layouts/AppLayout.vue'

export default {
    components: {
        CustomerAccountModal,
        SalespersonModal,
        IndustryModal,
        GeoModal,
        CustomerGroupModal,
        Link,
        AppLayout
    },
    setup() {
        const showAdditionalFields = ref(false)
        const showCustomerAccountModal = ref(false)
        const showSalespersonModal = ref(false)
        const showIndustryModal = ref(false)
        const showGeoModal = ref(false)
        const showCustomerGroupModal = ref(false)

        const form = reactive({
            customer_code: '',
            customer_name: '',
            short_name: '',
            address: '',
            contact_person: '',
            telephone_no: '',
            fax_no: '',
            co_email: '',
            credit_limit: 0,
            credit_terms: 0,
            ac_type: 'N-Local',
            currency_code: '',
            salesperson_code: '',
            industrial_code: '',
            geographical: '',
            grouping_code: '',
            print_ar_aging: 'N-No'
        })

        const goToDashboard = () => {
            router.visit(route('dashboard'))
        }

        const openCustomerAccountModal = () => {
            showCustomerAccountModal.value = true
        }

        const closeCustomerAccountModal = () => {
            showCustomerAccountModal.value = false
        }

        const selectCustomerAccount = (account) => {
            form.customer_code = account.customer_code
            form.customer_name = account.customer_name
            form.short_name = account.short_name
            form.address = account.address
            form.contact_person = account.contact_person
            form.telephone_no = account.telephone_no
            form.fax_no = account.fax_no
            form.co_email = account.co_email
            form.credit_limit = account.credit_limit
            form.credit_terms = account.credit_terms
            form.ac_type = account.ac_type
            form.currency_code = account.currency_code
            form.salesperson_code = account.salesperson_code
            form.industrial_code = account.industrial_code
            form.geographical = account.geographical
            form.grouping_code = account.grouping_code
            form.print_ar_aging = account.print_ar_aging
            showAdditionalFields.value = true
            closeCustomerAccountModal()
        }

        const openSalespersonModal = () => {
            showSalespersonModal.value = true
        }

        const closeSalespersonModal = () => {
            showSalespersonModal.value = false
        }

        const selectSalesperson = (salesperson) => {
            form.salesperson_code = salesperson.code
            closeSalespersonModal()
        }

        const openIndustryModal = () => {
            showIndustryModal.value = true
        }

        const closeIndustryModal = () => {
            showIndustryModal.value = false
        }

        const selectIndustry = (industry) => {
            form.industrial_code = industry.code
            closeIndustryModal()
        }

        const openGeoModal = () => {
            showGeoModal.value = true
        }

        const closeGeoModal = () => {
            showGeoModal.value = false
        }

        const selectGeo = (geo) => {
            form.geographical = geo.code
            closeGeoModal()
        }

        const openCustomerGroupModal = () => {
            showCustomerGroupModal.value = true
        }

        const closeCustomerGroupModal = () => {
            showCustomerGroupModal.value = false
        }

        const selectCustomerGroup = (group) => {
            form.grouping_code = group.group_code
            closeCustomerGroupModal()
        }

        const saveCustomerAccount = async () => {
            try {
                await router.post(route('update-customer-account.store'), form)
            } catch (error) {
                console.error('Error saving customer account:', error)
            }
        }

        return {
            showAdditionalFields,
            showCustomerAccountModal,
            showSalespersonModal,
            showIndustryModal,
            showGeoModal,
            showCustomerGroupModal,
            form,
            goToDashboard,
            openCustomerAccountModal,
            closeCustomerAccountModal,
            selectCustomerAccount,
            openSalespersonModal,
            closeSalespersonModal,
            selectSalesperson,
            openIndustryModal,
            closeIndustryModal,
            selectIndustry,
            openGeoModal,
            closeGeoModal,
            selectGeo,
            openCustomerGroupModal,
            closeCustomerGroupModal,
            selectCustomerGroup,
            saveCustomerAccount
        }
    }
}
</script>