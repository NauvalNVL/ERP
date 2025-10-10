<template>
    <AppLayout :header="'Setup Invoice Configuration'">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 p-6 rounded-t-lg shadow-lg overflow-hidden relative">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10"></div>
            <div class="flex items-center">
                <div class="bg-gradient-to-br from-pink-500 to-purple-600 p-3 rounded-lg shadow-inner flex items-center justify-center relative overflow-hidden mr-4">
                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-yellow-300 opacity-30 rounded-full animate-ping-slow"></div>
                    <div class="absolute -bottom-1 -left-1 w-4 h-4 bg-blue-300 opacity-30 rounded-full animate-ping-slow animation-delay-500"></div>
                    <i class="fas fa-cog text-white text-2xl z-10"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-1 text-shadow">Setup Invoice Configuration</h2>
                    <p class="text-blue-100 max-w-2xl">Configure overall controls and auto-invoicing behavior</p>
                </div>
            </div>
        </div>

        <!-- Body -->
        <form @submit.prevent="save" class="bg-white rounded-b-lg shadow-lg p-6 mb-6 bg-gradient-to-br from-white to-indigo-50 space-y-6">
            <!-- Overall Controls -->
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-indigo-500">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-sliders-h mr-2 text-indigo-500"></i> Overall Controls
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Print Control</label>
                            <div class="flex items-center space-x-6">
                                <label class="inline-flex items-center space-x-2"><input type="radio" value="Y" v-model="form.print_control" /> <span>Y-Allow to Print after Preparation</span></label>
                                <label class="inline-flex items-center space-x-2"><input type="radio" value="N" v-model="form.print_control" /> <span>N-No</span></label>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">User/Group Print Control</label>
                            <div class="flex items-center space-x-6">
                                <label class="inline-flex items-center space-x-2"><input type="radio" value="Y" v-model="form.user_group_print_control" /> <span>Y-Check User/Group Print Permission</span></label>
                                <label class="inline-flex items-center space-x-2"><input type="radio" value="N" v-model="form.user_group_print_control" /> <span>N-No</span></label>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Foreign Currency Control</label>
                            <div class="flex items-center space-x-6">
                                <label class="inline-flex items-center space-x-2"><input type="radio" value="S" v-model="form.foreign_currency_control" /> <span>S-Standard</span></label>
                                <label class="inline-flex items-center space-x-2"><input type="radio" value="I" v-model="form.foreign_currency_control" /> <span>I-Indonesia Faktur</span></label>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Show Invoice Amount</label>
                            <div class="flex items-center space-x-6">
                                <label class="inline-flex items-center space-x-2"><input type="radio" value="Y" v-model="form.show_invoice_amount" /> <span>Y-Yes</span></label>
                                <label class="inline-flex items-center space-x-2"><input type="radio" value="N" v-model="form.show_invoice_amount" /> <span>N-No</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bank Acceptance Terms (Days)</label>
                            <input type="number" min="0" v-model.number="form.bank_acceptance_terms" class="block w-40 px-3 py-2 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 form-input" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bank Account#</label>
                            <input type="text" v-model.trim="form.bank_account" class="block w-full max-w-md px-3 py-2 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 form-input" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Auto Invoicing -->
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-emerald-500">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-robot mr-2 text-emerald-500"></i> Auto Invoicing
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Activate Auto-Invoicing</label>
                            <div class="flex items-center space-x-6">
                                <label class="inline-flex items-center space-x-2"><input type="radio" value="Y" v-model="form.activate_auto_invoicing" /> <span>Y-Yes</span></label>
                                <label class="inline-flex items-center space-x-2"><input type="radio" value="N" v-model="form.activate_auto_invoicing" /> <span>N-No</span></label>
                                <label class="inline-flex items-center space-x-2"><input type="radio" value="A" v-model="form.activate_auto_invoicing" /> <span>A-Compulsory for all and D/Order printing is disable during issuing</span></label>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Activate Posting to Accounts</label>
                            <div class="flex items-center space-x-6">
                                <label class="inline-flex items-center space-x-2"><input type="radio" value="Y" v-model="form.activate_posting_to_accounts" /> <span>Y-Yes</span></label>
                                <label class="inline-flex items-center space-x-2"><input type="radio" value="N" v-model="form.activate_posting_to_accounts" /> <span>N-No (Must set to N-No)</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Escape Key Control</label>
                            <div class="flex items-center space-x-6">
                                <label class="inline-flex items-center space-x-2"><input type="radio" value="1" v-model="form.escape_key_control" /> <span>1-Free Escape</span></label>
                                <label class="inline-flex items-center space-x-2"><input type="radio" value="2" v-model="form.escape_key_control" /> <span>2-Escape only when Error</span></label>
                                <label class="inline-flex items-center space-x-2"><input type="radio" value="3" v-model="form.escape_key_control" /> <span>3-Escape not Allowed</span></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md shadow">Save</button>
            </div>
        </form>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';
import { useToast } from '@/Composables/useToast';

const toast = useToast();

const form = ref({
    print_control: 'Y',
    user_group_print_control: 'N',
    foreign_currency_control: 'S',
    show_invoice_amount: 'Y',
    bank_acceptance_terms: 0,
    bank_account: '',
    activate_auto_invoicing: 'N',
    activate_posting_to_accounts: 'N',
    escape_key_control: '1',
});

const load = async () => {
    try {
        const res = await fetch('/api/invoice/configuration', { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' });
        if (res.ok) {
            const data = await res.json();
            form.value = { ...form.value, ...(data || {}) };
        }
    } catch (e) {}
};

const save = async () => {
    try {
        await axios.post('/api/invoice/configuration', form.value, { headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' } });
        toast.success('Invoice configuration saved');
    } catch (e) {
        toast.error(e?.response?.data?.message || 'Failed to save configuration');
    }
};

onMounted(load);
</script>

<style scoped>
.form-input { transition: all 0.3s ease; }
.form-input:focus { box-shadow: 0 0 0 3px rgba(79,70,229,.3); border-color: #6366f1; }
.text-shadow { text-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.animate-ping-slow { animation: ping-slow 3s ease-in-out infinite; }
@keyframes ping-slow { 0% { transform: scale(1); opacity:.5;} 50% { transform: scale(1.8);} 100% { transform: scale(2.2); opacity:0;} }
.animation-delay-500 { animation-delay: 1.5s; }
</style>


