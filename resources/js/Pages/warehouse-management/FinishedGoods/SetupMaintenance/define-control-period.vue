<template>
    <AppLayout :header="'Define Control Period'">
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
                    <i class="fas fa-calendar-alt text-white text-2xl z-10"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-1 text-shadow">Define Control Period</h2>
                    <p class="text-blue-100 max-w-2xl">Manage and define control periods for Finished Goods and Delivery Orders</p>
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
                                <i class="fas fa-cog text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800">Control Period Settings</h3>
                        </div>

                        <!-- Form content -->
                        <form @submit.prevent="saveConfig" class="space-y-6">
                            <!-- Loading indicator -->
                            <div v-if="loading" class="flex justify-center items-center py-6">
                                <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
                                <span class="ml-3 text-gray-600">Loading...</span>
                            </div>

                            <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="current_period" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full mr-2 shadow-sm">
                                            <i class="fas fa-calendar-alt text-white text-xs"></i>
                                        </span>
                                        Current Period:
                                    </label>
                                    <div class="relative flex group">
                                        <input 
                                            type="text" 
                                            id="current_period" 
                                            v-model="form.current_period"
                                            class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 bg-gray-50 text-gray-700 read-only:bg-gray-100"
                                            readonly
                                        />
                                        <button 
                                            type="button"
                                            @click="openPeriodModal"
                                            class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-r-md transition-all transform active:translate-y-px relative overflow-hidden shadow-sm"
                                        >
                                            <span class="absolute inset-0 bg-white opacity-0 hover:opacity-20 transition-opacity"></span>
                                            <i class="fas fa-search relative z-10"></i>
                                        </button>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">mm/yyyy</p>
                                </div>
                            </div>

                            <div v-if="!loading" class="space-y-4">
                                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                    <h4 class="text-sm font-semibold text-blue-800 mb-3">F/Goods Entry Date:</h4>
                                    <div class="flex flex-wrap gap-4">
                                        <div class="flex items-center">
                                            <input type="radio" id="fg_u_date" value="U-Date Under Current Period" v-model="form.fg_entry_date" class="form-radio h-4 w-4 text-blue-600 transition duration-150 ease-in-out">
                                            <label for="fg_u_date" class="ml-2 text-sm text-gray-700">U-Date Under Current Period</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" id="fg_f_date" value="F-Date Under/Forward Current Period" v-model="form.fg_entry_date" class="form-radio h-4 w-4 text-blue-600 transition duration-150 ease-in-out">
                                            <label for="fg_f_date" class="ml-2 text-sm text-gray-700">F-Date Under/Forward Current Period</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" id="fg_b_date" value="B-Date Under/Backward Current Period" v-model="form.fg_entry_date" class="form-radio h-4 w-4 text-blue-600 transition duration-150 ease-in-out">
                                            <label for="fg_b_date" class="ml-2 text-sm text-gray-700">B-Date Under/Backward Current Period</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" id="fg_o_date" value="O-Open Date" v-model="form.fg_entry_date" class="form-radio h-4 w-4 text-blue-600 transition duration-150 ease-in-out">
                                            <label for="fg_o_date" class="ml-2 text-sm text-gray-700">O-Open Date</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                                    <h4 class="text-sm font-semibold text-green-800 mb-3">D/Order Entry Date:</h4>
                                    <div class="flex flex-wrap gap-4">
                                        <div class="flex items-center">
                                            <input type="radio" id="do_u_date" value="U-Date Under Current Period" v-model="form.do_entry_date" class="form-radio h-4 w-4 text-green-600 transition duration-150 ease-in-out">
                                            <label for="do_u_date" class="ml-2 text-sm text-gray-700">U-Date Under Current Period</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" id="do_f_date" value="F-Date Under/Forward Current Period" v-model="form.do_entry_date" class="form-radio h-4 w-4 text-green-600 transition duration-150 ease-in-out">
                                            <label for="do_f_date" class="ml-2 text-sm text-gray-700">F-Date Under/Forward Current Period</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" id="do_b_date" value="B-Date Under/Backward Current Period" v-model="form.do_entry_date" class="form-radio h-4 w-4 text-green-600 transition duration-150 ease-in-out">
                                            <label for="do_b_date" class="ml-2 text-sm text-gray-700">B-Date Under/Backward Current Period</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" id="do_o_date" value="O-Open Date" v-model="form.do_entry_date" class="form-radio h-4 w-4 text-green-600 transition duration-150 ease-in-out">
                                            <label for="do_o_date" class="ml-2 text-sm text-gray-700">O-Open Date</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                                    <h4 class="text-sm font-semibold text-red-800 mb-3">D/Order Rejection Entry Date:</h4>
                                    <div class="flex flex-wrap gap-4">
                                        <div class="flex items-center">
                                            <input type="radio" id="do_rej_u_date" value="U-Date Under Current Period" v-model="form.do_rejection_entry_date" class="form-radio h-4 w-4 text-red-600 transition duration-150 ease-in-out">
                                            <label for="do_rej_u_date" class="ml-2 text-sm text-gray-700">U-Date Under Current Period</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" id="do_rej_f_date" value="F-Date Under/Forward Current Period" v-model="form.do_rejection_entry_date" class="form-radio h-4 w-4 text-red-600 transition duration-150 ease-in-out">
                                            <label for="do_rej_f_date" class="ml-2 text-sm text-gray-700">F-Date Under/Forward Current Period</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" id="do_rej_b_date" value="B-Date Under/Backward Current Period" v-model="form.do_rejection_entry_date" class="form-radio h-4 w-4 text-red-600 transition duration-150 ease-in-out">
                                            <label for="do_rej_b_date" class="ml-2 text-sm text-gray-700">B-Date Under/Backward Current Period</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" id="do_rej_o_date" value="O-Open Date" v-model="form.do_rejection_entry_date" class="form-radio h-4 w-4 text-red-600 transition duration-150 ease-in-out">
                                            <label for="do_rej_o_date" class="ml-2 text-sm text-gray-700">O-Open Date</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex justify-end mt-6">
                                <button 
                                    type="submit" 
                                    class="bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white px-6 py-2 rounded-lg shadow-md flex items-center space-x-2 transform active:translate-y-px transition-all duration-300 relative overflow-hidden group"
                                    :disabled="loading"
                                >
                                    <span class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity"></span>
                                    <i class="fas" :class="loading ? 'fa-spinner fa-spin' : 'fa-save'"></i>
                                    <span class="relative z-10">{{ loading ? 'Saving...' : 'Save Configuration' }}</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column - Quick Info -->
                <div class="lg:col-span-1">
                    <!-- Info Card -->
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-teal-500 mb-6 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 relative overflow-hidden">
                        <div class="absolute -top-16 -right-16 w-32 h-32 bg-teal-50 rounded-full opacity-20"></div>
                        <div class="absolute -bottom-6 -left-6 w-20 h-20 bg-green-50 rounded-full opacity-20"></div>
                        
                        <div class="flex items-center mb-4 pb-2 border-b border-gray-200 relative z-10">
                            <div class="p-2 bg-gradient-to-r from-teal-500 to-green-500 rounded-lg mr-3 shadow-md">
                                <i class="fas fa-info-circle text-white"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Control Period Info</h3>
                        </div>

                        <div class="space-y-4">
                            <div class="p-4 bg-teal-50 rounded-lg">
                                <h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2 flex items-center">
                                    <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-teal-500 to-green-500 rounded-full mr-2 shadow-sm">
                                        <i class="fas fa-book text-white text-xs"></i>
                                    </span>
                                    Instructions
                                </h4>
                                <ul class="text-sm text-gray-600 space-y-2">
                                    <li class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-400 to-indigo-400 rounded-full mr-2 shadow-sm mt-0.5">
                                            <i class="fas fa-calendar-check text-white text-xs"></i>
                                        </span>
                                        <span>Set the current financial period (mm/yyyy)</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full mr-2 shadow-sm mt-0.5">
                                            <i class="fas fa-mouse-pointer text-white text-xs"></i>
                                        </span>
                                        <span>Select the entry date behavior for Finished Goods and Delivery Orders</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-green-400 to-emerald-400 rounded-full mr-2 shadow-sm mt-0.5">
                                            <i class="fas fa-save text-white text-xs"></i>
                                        </span>
                                        <span>Save changes to apply new control period settings</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Period Modal -->
        <ControlPeriodModal 
            :show="showPeriodModal" 
            :currentMonth="selectedMonth" 
            :currentYear="selectedYear" 
            @close="closePeriodModal" 
            @period-selected="handlePeriodSelected"
        />
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, reactive, onMounted } from 'vue';
import axios from 'axios';
import { useToast } from '@/Composables/useToast';
import ControlPeriodModal from '@/Components/ControlPeriodModal.vue'; // Import the new modal component

const toast = useToast();
const form = reactive({
    current_period: '',
    fg_entry_date: '',
    do_entry_date: '',
    do_rejection_entry_date: '',
    prRequisition: {
        currentPeriod: 'Same as P/Order Period',
        forwardPeriod: '0',
        controlDate: '1',
    },
    pOrder: {
        currentPeriodMonth: new Date().getMonth() + 1,
        currentPeriodYear: new Date().getFullYear(),
        forwardPeriod: '1',
        controlDate: '1',
        minAllowPercentage: 0.00,
        maxAllowPercentage: 0.00,
        zeroPO: 'N',
    },
    inventory: {
        currentPeriodMonth: new Date().getMonth() + 1,
        currentPeriodYear: new Date().getFullYear(),
        backwardPeriod: '0',
        controlDate: '1',
        zeroTran: 'Y',
    },
    costing: {
        currentPeriodMonth: new Date().getMonth() + 1,
        currentPeriodYear: new Date().getFullYear(),
        controlDate: '1',
        yAllowAfterPeriod: true,
    }
});

const showPeriodModal = ref(false);
const selectedMonth = ref(null);
const selectedYear = ref(null);
const loading = ref(false);

const openPeriodModal = () => {
    showPeriodModal.value = true;
};

const closePeriodModal = () => {
    showPeriodModal.value = false;
};

const handlePeriodSelected = ({ month, year }) => {
    form.current_period = `${String(month).padStart(2, '0')}/${year}`;
    closePeriodModal();
};

const fetchConfig = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/api/material-management/control-period');
        const config = response.data;
        if (config) {
            form.current_period = config.current_period || '';
            form.fg_entry_date = config.fg_entry_date || '';
            form.do_entry_date = config.do_entry_date || '';
            form.do_rejection_entry_date = config.do_rejection_entry_date || '';
            
            // Update nested objects if they exist in the response
            if (config.prRequisition) {
                form.prRequisition = config.prRequisition;
            }
            
            if (config.pOrder) {
                form.pOrder = config.pOrder;
            }
            
            if (config.inventory) {
                form.inventory = config.inventory;
            }
            
            if (config.costing) {
                form.costing = config.costing;
            }
        }
    } catch (error) {
        console.error('Error fetching control period config:', error);
        toast.error('Failed to fetch configuration. Please try again later.');
    } finally {
        loading.value = false;
    }
};

const saveConfig = async () => {
    loading.value = true;
    try {
        console.log('Sending data:', form);
        const response = await axios.post('/api/material-management/control-period', form);
        console.log('Response:', response.data);
        toast.success('Control period configuration saved successfully!');
    } catch (error) {
        console.error('Error saving control period config:', error);
        console.log('Error response:', error.response?.data);
        toast.error('Failed to save configuration. Please try again later.');
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchConfig();
});
</script>

<style scoped>
/* Add any specific styles here if needed */
.text-shadow {
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
}
.animate-ping-slow {
    animation: ping-slow 3s cubic-bezier(0, 0, 0.2, 1) infinite;
}
@keyframes ping-slow {
    0%, 100% {
        transform: scale(1);
        opacity: 0.7;
    }
    50% {
        transform: scale(1.5);
        opacity: 0;
    }
}
.animation-delay-500 {
    animation-delay: 0.5s;
}
</style>