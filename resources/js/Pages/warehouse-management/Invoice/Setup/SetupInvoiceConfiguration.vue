<template>
    <AppLayout :header="'Setup Invoice Configuration'">
        <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <!-- Header -->
                <div class="bg-blue-600 text-white shadow-sm rounded-xl border border-blue-700 mb-4">
                    <div class="px-4 py-3 sm:px-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="h-9 w-9 rounded-full bg-blue-500 flex items-center justify-center">
                                <i class="fas fa-cog text-white text-sm"></i>
                            </div>
                            <div>
                                <h2 class="text-lg sm:text-xl font-semibold leading-tight">
                                    Setup Invoice Configuration
                                </h2>
                                <p class="text-xs sm:text-sm text-blue-100">
                                    Configure overall controls and auto-invoicing behavior.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Body -->
                <form @submit.prevent="save" class="space-y-4">
                    <!-- Overall Controls -->
                    <div class="bg-white shadow-sm rounded-xl border border-gray-200">
                        <div class="px-4 py-3 sm:px-6 border-b border-gray-100 flex items-center">
                            <div class="p-2 bg-blue-500 rounded-lg mr-3 text-white">
                                <i class="fas fa-sliders-h"></i>
                            </div>
                            <div>
                                <h3 class="text-sm sm:text-base font-semibold text-slate-800">Overall Controls</h3>
                                <p class="text-xs text-slate-500">Configure invoice settings and controls.</p>
                            </div>
                        </div>
                        <div class="px-4 py-4 sm:px-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Print Control</label>
                                        <div class="flex items-center space-x-4">
                                            <label class="inline-flex items-center space-x-2 text-sm"><input type="radio" value="Y" v-model="form.print_control" class="text-blue-600" /> <span>Y-Allow</span></label>
                                            <label class="inline-flex items-center space-x-2 text-sm"><input type="radio" value="N" v-model="form.print_control" class="text-blue-600" /> <span>N-No</span></label>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">User/Group Print Control</label>
                                        <div class="flex items-center space-x-4">
                                            <label class="inline-flex items-center space-x-2 text-sm"><input type="radio" value="Y" v-model="form.user_group_print_control" class="text-blue-600" /> <span>Y-Check</span></label>
                                            <label class="inline-flex items-center space-x-2 text-sm"><input type="radio" value="N" v-model="form.user_group_print_control" class="text-blue-600" /> <span>N-No</span></label>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Foreign Currency Control</label>
                                        <div class="flex items-center space-x-4">
                                            <label class="inline-flex items-center space-x-2 text-sm"><input type="radio" value="S" v-model="form.foreign_currency_control" class="text-blue-600" /> <span>S-Standard</span></label>
                                            <label class="inline-flex items-center space-x-2 text-sm"><input type="radio" value="I" v-model="form.foreign_currency_control" class="text-blue-600" /> <span>I-Indonesia</span></label>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Show Invoice Amount</label>
                                        <div class="flex items-center space-x-4">
                                            <label class="inline-flex items-center space-x-2 text-sm"><input type="radio" value="Y" v-model="form.show_invoice_amount" class="text-blue-600" /> <span>Y-Yes</span></label>
                                            <label class="inline-flex items-center space-x-2 text-sm"><input type="radio" value="N" v-model="form.show_invoice_amount" class="text-blue-600" /> <span>N-No</span></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Bank Acceptance Terms (Days)</label>
                                        <input type="number" min="0" v-model.number="form.bank_acceptance_terms" class="block w-40 px-3 py-2 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-sm" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Bank Account#</label>
                                        <input type="text" v-model.trim="form.bank_account" class="block w-full max-w-md px-3 py-2 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-sm" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Auto Invoicing -->
                    <div class="bg-white shadow-sm rounded-xl border border-gray-200">
                        <div class="px-4 py-3 sm:px-6 border-b border-gray-100 flex items-center">
                            <div class="p-2 bg-blue-500 rounded-lg mr-3 text-white">
                                <i class="fas fa-robot"></i>
                            </div>
                            <div>
                                <h3 class="text-sm sm:text-base font-semibold text-slate-800">Auto Invoicing</h3>
                                <p class="text-xs text-slate-500">Configure automatic invoicing behavior.</p>
                            </div>
                        </div>
                        <div class="px-4 py-4 sm:px-6">
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Activate Auto-Invoicing</label>
                                    <div class="flex flex-col space-y-2">
                                        <label class="inline-flex items-center space-x-2 text-sm"><input type="radio" value="Y" v-model="form.activate_auto_invoicing" class="text-blue-600" /> <span>Y-Yes</span></label>
                                        <label class="inline-flex items-center space-x-2 text-sm"><input type="radio" value="N" v-model="form.activate_auto_invoicing" class="text-blue-600" /> <span>N-No</span></label>
                                        <label class="inline-flex items-center space-x-2 text-sm"><input type="radio" value="A" v-model="form.activate_auto_invoicing" class="text-blue-600" /> <span>A-Compulsory</span></label>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Activate Posting to Accounts</label>
                                    <div class="flex items-center space-x-4">
                                        <label class="inline-flex items-center space-x-2 text-sm"><input type="radio" value="Y" v-model="form.activate_posting_to_accounts" class="text-blue-600" /> <span>Y-Yes</span></label>
                                        <label class="inline-flex items-center space-x-2 text-sm"><input type="radio" value="N" v-model="form.activate_posting_to_accounts" class="text-blue-600" /> <span>N-No</span></label>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Escape Key Control</label>
                                    <div class="flex flex-col space-y-2">
                                        <label class="inline-flex items-center space-x-2 text-sm"><input type="radio" value="1" v-model="form.escape_key_control" class="text-blue-600" /> <span>1-Free Escape</span></label>
                                        <label class="inline-flex items-center space-x-2 text-sm"><input type="radio" value="2" v-model="form.escape_key_control" class="text-blue-600" /> <span>2-Escape only when Error</span></label>
                                        <label class="inline-flex items-center space-x-2 text-sm"><input type="radio" value="3" v-model="form.escape_key_control" class="text-blue-600" /> <span>3-Escape not Allowed</span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow text-sm font-medium">Save Configuration</button>
                    </div>
                </form>
            </div>
        </div>
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


