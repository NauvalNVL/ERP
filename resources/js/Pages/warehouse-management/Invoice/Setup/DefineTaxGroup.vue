<template>
    <AppLayout :header="'Define Tax Group'">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 p-6 rounded-t-lg shadow-lg overflow-hidden relative">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10"></div>

            <div class="flex items-center">
                <div class="bg-gradient-to-br from-pink-500 to-purple-600 p-3 rounded-lg shadow-inner flex items-center justify-center relative overflow-hidden mr-4">
                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-yellow-300 opacity-30 rounded-full animate-ping-slow"></div>
                    <div class="absolute -bottom-1 -left-1 w-4 h-4 bg-blue-300 opacity-30 rounded-full animate-ping-slow animation-delay-500"></div>
                    <i class="fas fa-percentage text-white text-2xl z-10"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-1 text-shadow">Define Tax Group</h2>
                    <p class="text-blue-100 max-w-2xl">Create and manage tax groups used in invoicing</p>
                </div>
            </div>
        </div>

        <!-- Body -->
        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6 bg-gradient-to-br from-white to-indigo-50">
            <div class="relative">
                <div class="absolute top-0 right-0">
                    <span
                        :class="recordMode === 'new' ? 'bg-blue-500' : (recordMode === 'add' ? 'bg-green-600' : 'bg-orange-500)')"
                        class="text-white px-4 py-2 rounded-lg shadow-md text-sm font-semibold"
                    >
                        Record: {{ recordMode === 'add' ? 'Add' : 'Select' }}
                    </span>
                </div>
            </div>

            <!-- Tax Group Code Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-2">
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full mr-2 shadow-sm">
                            <i class="fas fa-barcode text-white text-xs"></i>
                        </span>
                        Tax Group Code:
                    </label>
                    <div class="relative flex group">
                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                            <i class="fas fa-hashtag"></i>
                        </span>
                        <input
                            id="code"
                            v-model.trim="form.code"
                            @input="handleCodeInput"
                            type="text"
                            class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 form-input"
                            :class="{ filled: !!form.code }"
                        />
                        <button
                            type="button"
                            @click="openTable"
                            class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-r-md transition-all shadow-sm"
                        >
                            <i class="fas fa-table"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- New Tax Group Form (only when code is new) -->
            <div v-if="showNewForm" class="mt-6">
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-emerald-500">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-gradient-to-r from-emerald-500 to-green-600 rounded-lg mr-3 shadow-md">
                            <i class="fas fa-plus text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Add New Tax Group</h3>
                    </div>

                    <form @submit.prevent="saveTaxGroup" class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tax Group Code</label>
                                <input type="text" v-model="form.code" readonly class="block w-full px-3 py-2 rounded-md border border-gray-300 bg-gray-50 form-input" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tax Group Name</label>
                                <input type="text" v-model.trim="form.name" class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 form-input" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sales Tax Applied</label>
                            <div class="flex items-center space-x-6">
                                <label class="inline-flex items-center space-x-2">
                                    <input type="radio" value="Y" v-model="form.sales_tax_applied" />
                                    <span>Y-Yes</span>
                                </label>
                                <label class="inline-flex items-center space-x-2">
                                    <input type="radio" value="N" v-model="form.sales_tax_applied" />
                                    <span>N-No</span>
                                </label>
                            </div>
                        </div>

                        <div class="pt-2 flex justify-end space-x-2">
                            <button type="button" @click="resetForm" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-md">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md shadow">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tax Group Table Modal -->
        <div v-if="showTable" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 animate-fadeIn">
            <div class="bg-white rounded-lg shadow-xl w-3/4 max-w-3xl max-h-[80vh] flex flex-col animate-scaleIn">
                <div class="bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-600 text-white px-6 py-4 rounded-t-lg flex justify-between items-center">
                    <div class="flex items-center space-x-3">
                        <div class="bg-white bg-opacity-20 p-2 rounded-lg shadow-inner"><i class="fas fa-table"></i></div>
                        <h3 class="text-lg font-semibold">Tax Group Table</h3>
                    </div>
                    <button @click="showTable = false" class="text-white hover:text-gray-200"><i class="fas fa-times"></i></button>
                </div>

                <div class="p-4 border-b bg-gradient-to-r from-gray-50 to-white">
                    <input type="text" v-model="search" placeholder="Search by code or name..." class="w-full pl-3 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" />
                </div>

                <div class="flex-1 overflow-y-auto p-4 bg-gradient-to-b from-white to-gray-50">
                    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">Code</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/4">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">Select</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="tg in filteredTaxGroups" :key="tg.code" class="hover:bg-blue-50">
                                    <td class="px-6 py-3 whitespace-nowrap font-medium text-blue-700">{{ tg.code }}</td>
                                    <td class="px-6 py-3 whitespace-nowrap text-gray-700">{{ tg.name }}</td>
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <button @click="selectFromTable(tg)" class="px-3 py-1.5 rounded-md text-white bg-blue-600 hover:bg-blue-700 shadow">
                                            Select
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="!loading && filteredTaxGroups.length === 0">
                                    <td colspan="3" class="px-6 py-6 text-center text-gray-500">No tax groups found</td>
                                </tr>
                                <tr v-if="loading">
                                    <td colspan="3" class="px-6 py-6 text-center text-gray-500">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="p-4 bg-gray-50 border-t border-gray-200 rounded-b-lg flex justify-end">
                    <button @click="showTable = false" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg">Exit</button>
                </div>
            </div>
        </div>
    </AppLayout>
    
</template>

<script setup>
import { ref, computed, onMounted, nextTick } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';
import { useToast } from '@/Composables/useToast';

const toast = useToast();

// UI State
const recordMode = ref('new'); // 'new' | 'add' (for new entry only)
const showTable = ref(false);
const loading = ref(false);
const search = ref('');

// Data
const form = ref({
    code: '',
    name: '',
    sales_tax_applied: 'Y',
});

const taxGroups = ref([]);

const filteredTaxGroups = computed(() => {
    if (!search.value) return taxGroups.value;
    const q = search.value.toLowerCase();
    return taxGroups.value.filter(x =>
        (x.code || '').toLowerCase().includes(q) || (x.name || '').toLowerCase().includes(q)
    );
});

const showNewForm = computed(() => !!form.value.code && recordMode.value === 'add');

const resetForm = () => {
    form.value.name = '';
    form.value.sales_tax_applied = 'Y';
    recordMode.value = 'new';
};

const openTable = async () => {
    showTable.value = true;
    await loadTaxGroups();
};

const selectFromTable = (tg) => {
    if (!tg) return;
    form.value.code = tg.code || '';
    form.value.name = tg.name || '';
    form.value.sales_tax_applied = (tg.sales_tax_applied === 'N' ? 'N' : 'Y');
    recordMode.value = 'new'; // selecting existing behaves like review mode (no add form)
    showTable.value = false;
    toast.success(`Selected Tax Group: ${form.value.code}`);
};

const handleCodeInput = async () => {
    if (!form.value.code) {
        resetForm();
        return;
    }
    // Check if code exists
    try {
        const codeEnc = encodeURIComponent(form.value.code);
        const res = await fetch(`/api/material-management/tax-groups/${codeEnc}`, { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' });
        if (res.ok) {
            const data = await res.json();
            if (data && (data.code || data.name)) {
                // Exists -> do not show add form, just keep values
                form.value.name = data.name || '';
                form.value.sales_tax_applied = data.sales_tax_applied === 'N' ? 'N' : 'Y';
                recordMode.value = 'new';
                return;
            }
        }
        // Not found -> switch to add mode
        form.value.name = '';
        form.value.sales_tax_applied = 'Y';
        recordMode.value = 'add';
    } catch (e) {
        // If API not available, assume new
        recordMode.value = 'add';
    }
};

const loadTaxGroups = async () => {
    loading.value = true;
    try {
        const res = await fetch('/api/material-management/tax-groups', { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' });
        if (!res.ok) throw new Error(`${res.status} ${res.statusText}`);
        const data = await res.json();
        taxGroups.value = Array.isArray(data?.data) ? data.data : (Array.isArray(data) ? data : []);
    } catch (e) {
        taxGroups.value = [];
    } finally {
        loading.value = false;
    }
};

const saveTaxGroup = async () => {
    if (!form.value.code || !form.value.name) {
        toast.error('Please fill Tax Group Code and Name');
        return;
    }
    try {
        const payload = {
            code: form.value.code,
            name: form.value.name,
            sales_tax_applied: form.value.sales_tax_applied,
        };
        const res = await axios.post('/api/material-management/tax-groups', payload, { headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' } });
        if (res.data) {
            toast.success('Tax Group saved');
            recordMode.value = 'new';
            await loadTaxGroups();
        }
    } catch (e) {
        toast.error(e?.response?.data?.message || 'Failed to save Tax Group');
    }
};

onMounted(() => {
    loadTaxGroups();
});
</script>

<style scoped>
.form-input {
    transition: all 0.3s ease;
}
.form-input:focus {
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.3);
    border-color: #6366f1;
}
.form-input.filled { border-left: 4px solid #10b981; }
.animate-fadeIn { animation: fadeIn 0.25s ease-out; }
.animate-scaleIn { animation: scaleIn 0.3s ease-in-out; }
@keyframes fadeIn { from { opacity: 0; transform: scale(0.95);} to { opacity: 1; transform: scale(1);} }
@keyframes scaleIn { from { transform: scale(0.95); opacity: 0;} to { transform: scale(1); opacity: 1;} }
.text-shadow { text-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.animate-ping-slow { animation: ping-slow 3s ease-in-out infinite; }
@keyframes ping-slow { 0% { transform: scale(1); opacity: .5;} 50% { transform: scale(1.8);} 100% { transform: scale(2.2); opacity: 0;} }
.animation-delay-500 { animation-delay: 1.5s; }
</style>


