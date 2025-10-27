<template>
    <AppLayout :header="'Update FG Stock-Out by MC'">
        <!-- Header Section with animated elements, adapted from Define Warehouse Location -->
        <div class="bg-gradient-to-r from-pink-600 via-red-600 to-rose-600 p-6 rounded-t-lg shadow-lg overflow-hidden relative">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10"></div>
            <div class="absolute bottom-0 right-0 w-32 h-32 bg-yellow-400 opacity-5 rounded-full translate-y-10 translate-x-10"></div>
            
            <div class="flex items-center">
                <div class="bg-gradient-to-br from-pink-500 to-red-600 p-3 rounded-lg shadow-inner flex items-center justify-center relative overflow-hidden mr-4">
                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-yellow-300 opacity-30 rounded-full animate-ping-slow"></div>
                    <div class="absolute -bottom-1 -left-1 w-4 h-4 bg-blue-300 opacity-30 rounded-full animate-ping-slow animation-delay-500"></div>
                    <i class="fas fa-shipping-fast text-white text-2xl z-10"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-1 text-shadow">Update FG Stock-Out by MC</h2>
                    <p class="text-pink-100 max-w-2xl">Process finished goods stock-out by master card</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6 bg-gradient-to-br from-white to-pink-50">
            <div class="max-w-md mx-auto">
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-red-500 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 relative overflow-hidden">
                    <div class="absolute -top-20 -right-20 w-40 h-40 bg-red-50 rounded-full opacity-20"></div>
                    <div class="absolute -bottom-8 -left-8 w-24 h-24 bg-pink-50 rounded-full opacity-20"></div>
                    
                    <div class="flex items-center mb-6 pb-2 border-b border-gray-200 relative z-10">
                        <div class="p-2 bg-gradient-to-r from-red-500 to-pink-600 rounded-lg mr-3 shadow-md">
                            <i class="fas fa-clipboard-list text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Master Card Stock-Out</h3>
                    </div>

                    <form @submit.prevent="processStockOut" class="space-y-6 relative z-10">
                        <!-- Current Period -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-red-500 to-pink-600 rounded-full mr-2 shadow-sm">
                                    <i class="fas fa-calendar text-white text-xs"></i>
                                </span>
                                Current Period:
                            </label>
                            <input
                                type="text"
                                v-model="currentPeriod"
                                class="w-32 px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500 bg-gray-50 transition-all"
                                readonly
                            />
                            <div class="text-xs text-gray-500 mt-1">mm/yyyy</div>
                        </div>

                        <!-- Customer Code -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-pink-500 to-rose-500 rounded-full mr-2 shadow-sm">
                                    <i class="fas fa-user-tie text-white text-xs"></i>
                                </span>
                                Customer Code:
                            </label>
                            <div class="relative flex group">
                                <input
                                    type="text"
                                    v-model="customerCode"
                                    class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 focus:ring-pink-500 focus:border-pink-500 transition-all group-hover:border-pink-300"
                                    placeholder="Select customer"
                                    readonly
                                />
                                <button 
                                    type="button"
                                    @click="openCustomerModal"
                                    class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white rounded-r-md transition-all transform active:translate-y-px relative overflow-hidden shadow-sm"
                                >
                                    <span class="absolute inset-0 bg-white opacity-0 hover:opacity-20 transition-opacity"></span>
                                    <i class="fas fa-table relative z-10"></i>
                                </button>
                            </div>
                        </div>

                        <!-- M/Card Seq# -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-rose-500 to-red-500 rounded-full mr-2 shadow-sm">
                                    <i class="fas fa-id-card text-white text-xs"></i>
                                </span>
                                M/Card Seq#:
                            </label>
                            <div class="relative flex group">
                                <input
                                    type="text"
                                    v-model="masterCardSeq"
                                    class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 focus:ring-rose-500 focus:border-rose-500 transition-all group-hover:border-rose-300"
                                    placeholder="Select master card"
                                    readonly
                                />
                                <button 
                                    type="button"
                                    @click="openMasterCardModal"
                                    class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-rose-500 to-red-500 hover:from-rose-600 hover:to-red-600 text-white rounded-r-md transition-all transform active:translate-y-px relative overflow-hidden shadow-sm"
                                >
                                    <span class="absolute inset-0 bg-white opacity-0 hover:opacity-20 transition-opacity"></span>
                                    <i class="fas fa-table relative z-10"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Customer Account Modal -->
        <CustomerAccountModal
            :show="showCustomerModal"
            :customerAccounts="customerAccounts"
            @close="closeCustomerModal"
            @select="selectCustomer"
        />

        <!-- Master Card Search Select Modal -->
        <MasterCardSearchSelectModal
            :show="showMasterCardModal"
            :masterCards="masterCards"
            @close="closeMasterCardModal"
            @select="selectMasterCard"
        />
    </AppLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted, nextTick } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import CustomerAccountModal from '@/Components/customer-account-modal.vue';
import MasterCardSearchSelectModal from '@/Components/MasterCardSearchSelectModal.vue';
import axios from 'axios';

// State
const currentPeriod = ref('06/2025');
const customerCode = ref('');
const masterCardSeq = ref('');
const showCustomerModal = ref(false);
const showMasterCardModal = ref(false);
const customerAccounts = ref([]);
const masterCards = ref([]);

// Methods
const openCustomerModal = async () => {
    await loadCustomerAccounts();
    showCustomerModal.value = true;
};

const closeCustomerModal = () => {
    showCustomerModal.value = false;
};

const openMasterCardModal = async () => {
    await loadMasterCards();
    showMasterCardModal.value = true;
};

const closeMasterCardModal = () => {
    showMasterCardModal.value = false;
};

const loadCustomerAccounts = async () => {
    try {
        const response = await axios.get('/api/customer-accounts');
        customerAccounts.value = response.data;
    } catch (error) {
        console.error('Error loading customer accounts:', error);
        // Fallback demo data
        customerAccounts.value = [
            { id: 1, customer_code: '000501', customer_name: 'DIA RUPIAH', status: 'Active' },
            { id: 2, customer_code: '000502', customer_name: 'SINAR MAS', status: 'Active' },
            { id: 3, customer_code: '000503', customer_name: 'INDOFOOD', status: 'Active' },
            { id: 4, customer_code: '000504', customer_name: 'WINGS CORP', status: 'Active' },
            { id: 5, customer_code: '000505', customer_name: 'UNILEVER', status: 'Active' }
        ];
    }
};

const loadMasterCards = async () => {
    try {
        const response = await axios.get('/api/master-cards');
        masterCards.value = response.data;
    } catch (error) {
        console.error('Error loading master cards:', error);
        // Fallback demo data
        masterCards.value = [
            { mc_seq: '1609138', mc_model: 'BOX BASO 4,5 KG', part: 'BOX', p_design: 'B1', status: 'Active' },
            { mc_seq: '1609144', mc_model: 'BOX IKAN HARIMAU 4.5 KG', part: 'BOX', p_design: 'B1', status: 'Active' },
            { mc_seq: '1609145', mc_model: 'BOX SRIKAYA 4.5 KG', part: 'BOX', p_design: 'B1', status: 'Active' },
            { mc_seq: '1609162', mc_model: 'BIHUN FANIA 5 KG', part: 'BOX', p_design: 'B1', status: 'Obsolete' },
            { mc_seq: '1609163', mc_model: 'BIHUN IKAN TUNA 4.5 KG BARU', part: 'BOX', p_design: 'B1', status: 'Active' }
        ];
    }
};

const selectCustomer = (customer) => {
    customerCode.value = customer.customer_code;
    closeCustomerModal();
};

const selectMasterCard = (masterCard) => {
    masterCardSeq.value = masterCard.mc_seq;
    closeMasterCardModal();
};

const processStockOut = () => {
    if (!customerCode.value || !masterCardSeq.value) {
        alert('Please select both customer and master card');
        return;
    }
    
    // Process stock-out logic here
    console.log('Processing stock-out for:', {
        period: currentPeriod.value,
        customer: customerCode.value,
        masterCard: masterCardSeq.value
    });
    
    alert('Stock-out processing initiated!');
};

// Lifecycle
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