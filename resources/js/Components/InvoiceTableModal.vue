<template>
    <!-- Invoice Table Modal -->
    <div v-if="open" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 animate-fadeIn">
        <div class="bg-white rounded-lg shadow-xl w-11/12 max-w-6xl max-h-[85vh] flex flex-col animate-scaleIn">
            <div class="bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-600 text-white px-6 py-4 rounded-t-lg flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <div class="bg-white bg-opacity-20 p-2 rounded-lg shadow-inner"><i class="fas fa-table"></i></div>
                    <h3 class="text-lg font-semibold">Invoice Table</h3>
                </div>
                <button @click="closeModal" class="text-white hover:text-gray-200"><i class="fas fa-times"></i></button>
            </div>

            <div class="flex-1 overflow-y-auto p-4 bg-gradient-to-b from-white to-gray-50">
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">INVOICE#</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">INV DATE</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">AC#</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">TAX</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">MODE</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">PC STATUS</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">POST STATUS</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="row in invoices" :key="row.invoice_no"
                                @click="selectRow(row)"
                                class="hover:bg-blue-50 cursor-pointer"
                                :class="{ 'bg-blue-100': selectedRow?.invoice_no === row.invoice_no }">
                                <td class="px-3 py-2 font-medium text-blue-700">{{ row.invoice_no }}</td>
                                <td class="px-3 py-2">{{ row.invoice_date }}</td>
                                <td class="px-3 py-2">{{ row.customer_code }}</td>
                                <td class="px-3 py-2">{{ row.tax_code }}</td>
                                <td class="px-3 py-2">{{ row.mode }}</td>
                                <td class="px-3 py-2">{{ row.pc_status }}</td>
                                <td class="px-3 py-2">{{ row.post_status }}</td>
                            </tr>
                            <tr v-if="!loading && invoices.length === 0">
                                <td colspan="7" class="px-6 py-8 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <i class="fas fa-inbox text-4xl text-gray-300"></i>
                                        <div>
                                            <div class="text-gray-600 font-medium">No invoices found</div>
                                            <div class="text-sm text-gray-400 mt-1">Try adjusting your search criteria or create a new invoice</div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="loading">
                                <td colspan="7" class="px-6 py-6 text-center text-gray-500">
                                    <i class="fas fa-spinner fa-spin mr-2"></i>
                                    Loading invoices...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Detail Panel - CPS Style -->
                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                    <div>
                        <label class="block text-gray-600">Invoice#</label>
                        <div class="flex gap-2">
                            <input v-model="localQuery.part1" class="w-16 px-2 py-1 border rounded" />
                            <input v-model="localQuery.part2" class="w-16 px-2 py-1 border rounded" />
                            <input v-model="localQuery.part3" class="w-24 px-2 py-1 border rounded" />
                            <button @click="handleSearch" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded">Search</button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-gray-600">Customer Name</label>
                        <input type="text" :value="selectedRow?.customer_name || ''" readonly class="w-full border-gray-200 rounded-md bg-gray-50 px-3 py-2" />
                    </div>
                    <div>
                        <label class="block text-gray-600">Order Mode</label>
                        <input type="text" :value="selectedRow?.order_mode || ''" readonly class="w-full border-gray-200 rounded-md bg-gray-50 px-3 py-2" />
                    </div>
                    <!-- ✅ Audit Trail - Match CPS Layout (with TIME fields) -->
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-gray-600 text-xs">Issued by:</label>
                            <input type="text" :value="selectedRow?.issued_by || ''" readonly class="w-full border-gray-300 rounded-md bg-gray-50 px-2 py-1 text-sm" />
                        </div>
                        <div>
                            <label class="block text-gray-600 text-xs">Date:</label>
                            <input type="text" :value="formatAuditDateTime(selectedRow?.issued_date, selectedRow?.issued_time)" readonly class="w-full border-gray-300 rounded-md bg-gray-50 px-2 py-1 text-sm" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-gray-600 text-xs">Amended by:</label>
                            <input type="text" :value="selectedRow?.amended_by || ''" readonly class="w-full border-gray-300 rounded-md bg-gray-50 px-2 py-1 text-sm" />
                        </div>
                        <div>
                            <label class="block text-gray-600 text-xs">Date:</label>
                            <input type="text" :value="formatAuditDateTime(selectedRow?.amended_date, selectedRow?.amended_time)" readonly class="w-full border-gray-300 rounded-md bg-gray-50 px-2 py-1 text-sm" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-gray-600 text-xs">Cancelled by:</label>
                            <input type="text" :value="selectedRow?.cancelled_by || ''" readonly class="w-full border-gray-300 rounded-md bg-gray-50 px-2 py-1 text-sm" />
                        </div>
                        <div>
                            <label class="block text-gray-600 text-xs">Date:</label>
                            <input type="text" :value="formatAuditDateTime(selectedRow?.cancelled_date, selectedRow?.cancelled_time)" readonly class="w-full border-gray-300 rounded-md bg-gray-50 px-2 py-1 text-sm" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-gray-600 text-xs">Printed by:</label>
                            <input type="text" :value="selectedRow?.printed_by || ''" readonly class="w-full border-gray-300 rounded-md bg-gray-50 px-2 py-1 text-sm" />
                        </div>
                        <div>
                            <label class="block text-gray-600 text-xs">Date:</label>
                            <input type="text" :value="formatAuditDateTime(selectedRow?.printed_date, selectedRow?.printed_time)" readonly class="w-full border-gray-300 rounded-md bg-gray-50 px-2 py-1 text-sm" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-gray-600 text-xs">Posted by:</label>
                            <input type="text" :value="selectedRow?.posted_by || ''" readonly class="w-full border-gray-300 rounded-md bg-gray-50 px-2 py-1 text-sm" />
                        </div>
                        <div>
                            <label class="block text-gray-600 text-xs">Date:</label>
                            <input type="text" :value="formatAuditDateTime(selectedRow?.posted_date, selectedRow?.posted_time)" readonly class="w-full border-gray-300 rounded-md bg-gray-50 px-2 py-1 text-sm" />
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-gray-600">Reason</label>
                        <input type="text" v-model="reason" class="w-full border-gray-200 rounded-md px-3 py-2" />
                    </div>
                </div>
            </div>

            <div class="p-4 bg-gray-50 border-t border-gray-200 rounded-b-lg flex justify-between items-center">
                <div class="flex gap-2">
                    <button @click="handleZoom" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded shadow">Zoom</button>
                    <button @click="handleSelect" :disabled="!selectedRow" class="px-4 py-2 bg-green-600 hover:bg-green-700 disabled:opacity-50 text-white rounded shadow">Select</button>
                </div>
                <button @click="closeModal" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded">Exit</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
// cspell:ignore axios
import axios from 'axios';

const props = defineProps({
    open: {
        type: Boolean,
        default: false
    },
    initialQuery: {
        type: Object,
        default: () => ({ part1: '', part2: '', part3: '' })
    }
});

const emit = defineEmits(['close', 'select', 'zoom']);

// Local state
const invoices = ref([]);
const loading = ref(false);
const selectedRow = ref(null);
const reason = ref('');
const localQuery = ref({
    part1: props.initialQuery.part1 || '',
    part2: props.initialQuery.part2 || '',
    part3: props.initialQuery.part3 || ''
});

// Watch for modal open
watch(() => props.open, (newVal) => {
    if (newVal) {
        // Update local query from props when modal opens
        localQuery.value = {
            part1: props.initialQuery.part1 || '',
            part2: props.initialQuery.part2 || '',
            part3: props.initialQuery.part3 || ''
        };
        // Fetch invoices when modal opens
        fetchInvoices();
    } else {
        // Reset selection when modal closes
        selectedRow.value = null;
        reason.value = '';
    }
});

const fetchInvoices = async () => {
    loading.value = true;
    try {
        const params = new URLSearchParams();
        if (localQuery.value.part1) params.append('mm', localQuery.value.part1);
        if (localQuery.value.part2) params.append('yyyy', localQuery.value.part2);
        if (localQuery.value.part3) params.append('seq', localQuery.value.part3);

        console.log('🔍 Fetching invoices from API:', `/api/invoices?${params.toString()}`);

        const res = await axios.get(`/api/invoices?${params.toString()}`);

        console.log('✅ API Response:', res.data);

        if (res.data && res.data.success) {
            const fetchedInvoices = Array.isArray(res.data.data) ? res.data.data : [];

            if (fetchedInvoices.length > 0) {
                invoices.value = fetchedInvoices;
                console.log(`✅ Loaded ${fetchedInvoices.length} invoices from DATABASE`);
            } else {
                invoices.value = [];
                console.warn('⚠️ No invoices found in database');
            }
        } else {
            console.warn('⚠️ API returned no data');
            invoices.value = [];
        }
    } catch (e) {
        console.error('❌ Error fetching invoices:', e);
        console.error('Error details:', e.response?.data || e.message);
        invoices.value = [];
    } finally {
        loading.value = false;
    }
};

const selectRow = (row) => {
    selectedRow.value = row;
};

const handleSearch = () => {
    fetchInvoices();
};

const handleSelect = () => {
    if (!selectedRow.value) {
        alert('Please select an invoice first');
        return;
    }
    emit('select', selectedRow.value);
};

const handleZoom = () => {
    emit('zoom');
};

const closeModal = () => {
    emit('close');
};

// ✅ Format audit trail date/time (CPS style: DD/MM/YYYY HH:MM)
const formatAuditDateTime = (date, time) => {
    if (!date) return '';
    
    // Combine date and time if both available
    if (time) {
        return `${date} ${time}`;
    }
    
    return date;
};
</script>

<style scoped>
.animate-fadeIn {
    animation: fadeIn 0.2s ease-in;
}

.animate-scaleIn {
    animation: scaleIn 0.2s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes scaleIn {
    from {
        transform: scale(0.95);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}
</style>

