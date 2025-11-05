<template>
    <AppLayout :header="'Amend Invoice'">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 p-6 rounded-t-lg shadow-lg overflow-hidden relative">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10"></div>
            <div class="flex items-center">
                <div class="bg-gradient-to-br from-pink-500 to-purple-600 p-3 rounded-lg shadow-inner flex items-center justify-center relative overflow-hidden mr-4">
                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-yellow-300 opacity-30 rounded-full animate-ping-slow"></div>
                    <div class="absolute -bottom-1 -left-1 w-4 h-4 bg-blue-300 opacity-30 rounded-full animate-ping-slow animation-delay-500"></div>
                    <i class="fas fa-file-invoice text-white text-2xl z-10"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-1 text-shadow">Amend Invoice</h2>
                    <p class="text-blue-100">Find and amend existing invoices</p>
                </div>
            </div>
        </div>

        <!-- Toolbar / Search -->
        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6 bg-gradient-to-br from-white to-indigo-50">
            <div class="flex items-end gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Period</label>
                    <div class="flex items-center gap-2">
                        <input v-model="period.month" type="number" min="1" max="12" class="w-16 px-2 py-1 border rounded form-input" />
                        <input v-model="period.year" type="number" min="1990" class="w-24 px-2 py-1 border rounded form-input" />
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Invoice#</label>
                    <div class="flex items-center gap-2">
                        <input v-model="query.part1" type="text" class="w-16 px-2 py-1 border rounded form-input" />
                        <input v-model="query.part2" type="text" class="w-16 px-2 py-1 border rounded form-input" />
                        <input v-model="query.part3" type="text" class="w-24 px-2 py-1 border rounded form-input" />
                        <button @click="openTable" class="px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded shadow"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Panel -->
        <div v-if="selectedInvoice" class="bg-white p-6 rounded-lg shadow-md border-t-4 border-emerald-500">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div class="md:col-span-2">
                    <label class="block text-gray-600">Invoice#</label>
                    <input type="text" :value="selectedInvoice.invoice_no || ''" readonly class="w-full border-gray-200 rounded-md bg-gray-50 px-3 py-2" />
                </div>
                <div>
                    <label class="block text-gray-600">Customer:</label>
                    <input type="text" :value="selectedInvoice.customer_code + ' - ' + selectedInvoice.customer_name" readonly class="w-full border-gray-200 rounded-md bg-gray-50 px-3 py-2" />
                </div>
                <div>
                    <label class="block text-gray-600">Order Mode:</label>
                    <input type="text" v-model="selectedInvoice.order_mode" class="w-full border-gray-200 rounded-md px-3 py-2" />
                </div>
                <div>
                    <label class="block text-gray-600">Salesperson:</label>
                    <input type="text" v-model="selectedInvoice.salesperson" class="w-full border-gray-200 rounded-md px-3 py-2" />
                </div>
                <div>
                    <label class="block text-gray-600">A/C Currency:</label>
                    <input type="text" v-model="selectedInvoice.currency" class="w-full border-gray-200 rounded-md px-3 py-2" />
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <div>
                        <label class="block text-gray-600">Exch. Rate</label>
                        <input type="number" step="0.0001" v-model.number="selectedInvoice.exchange_rate" class="w-full border-gray-200 rounded-md px-3 py-2" />
                    </div>
                    <div class="col-span-2">
                        <label class="block text-gray-600">Exchange Method</label>
                        <div class="flex items-center gap-4 mt-2">
                            <label class="inline-flex items-center gap-2"><input type="radio" value="1" v-model="selectedInvoice.exchange_method" /> <span>1-Multiply</span></label>
                            <label class="inline-flex items-center gap-2"><input type="radio" value="2" v-model="selectedInvoice.exchange_method" /> <span>2-Divide</span></label>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-gray-600">Tax Index No.</label>
                    <input type="text" v-model="selectedInvoice.tax_index_no" class="w-full border-gray-200 rounded-md px-3 py-2" />
                </div>
                <div>
                    <label class="block text-gray-600">Invoice Date</label>
                    <input type="date" v-model="selectedInvoice.invoice_date" class="w-full border-gray-200 rounded-md px-3 py-2" />
                </div>
                <div>
                    <label class="block text-gray-600">2nd Reference#</label>
                    <input type="text" v-model="selectedInvoice.ref2" class="w-full border-gray-200 rounded-md px-3 py-2" />
                </div>
                <div class="md:col-span-2">
                    <label class="block text-gray-600">Remark</label>
                    <input type="text" v-model="selectedInvoice.remark" class="w-full border-gray-200 rounded-md px-3 py-2" />
                </div>
                <div>
                    <label class="block text-gray-600">Invoice Status</label>
                    <input type="text" v-model="selectedInvoice.status" class="w-full border-gray-200 rounded-md px-3 py-2" />
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button @click="saveInvoice" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded shadow"><i class="fas fa-save mr-1"></i> Save</button>
                <button @click="selectedInvoice=null" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded">Cancel</button>
            </div>
        </div>

        <!-- Invoice Table Modal -->
        <div v-if="showTable" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 animate-fadeIn">
            <div class="bg-white rounded-lg shadow-xl w-11/12 max-w-6xl max-h-[85vh] flex flex-col animate-scaleIn">
                <div class="bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-600 text-white px-6 py-4 rounded-t-lg flex justify-between items-center">
                    <div class="flex items-center space-x-3">
                        <div class="bg-white bg-opacity-20 p-2 rounded-lg shadow-inner"><i class="fas fa-table"></i></div>
                        <h3 class="text-lg font-semibold">Invoice Table</h3>
                    </div>
                    <button @click="showTable = false" class="text-white hover:text-gray-200"><i class="fas fa-times"></i></button>
                </div>

                <!-- Removed top search bar per request -->

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
                                <tr v-for="(row, idx) in invoices" :key="row.invoice_no" @click="selectRow(row)" class="hover:bg-blue-50 cursor-pointer">
                                    <td class="px-3 py-2 font-medium text-blue-700">{{ row.invoice_no }}</td>
                                    <td class="px-3 py-2">{{ row.invoice_date }}</td>
                                    <td class="px-3 py-2">{{ row.customer_code }}</td>
                                    <td class="px-3 py-2">{{ row.tax_code }}</td>
                                    <td class="px-3 py-2">{{ row.mode }}</td>
                                    <td class="px-3 py-2">{{ row.pc_status }}</td>
                                    <td class="px-3 py-2">{{ row.post_status }}</td>
                                </tr>
                                <tr v-if="!loading && invoices.length === 0">
                                    <td colspan="7" class="px-6 py-6 text-center text-gray-500">No invoices found</td>
                                </tr>
                                <tr v-if="loading">
                                    <td colspan="7" class="px-6 py-6 text-center text-gray-500">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Detail Panel like reference image -->
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                        <div>
                            <label class="block text-gray-600">Invoice#</label>
                            <div class="flex gap-2">
                                <input v-model="tableQuery.part1" class="w-16 px-2 py-1 border rounded" />
                                <input v-model="tableQuery.part2" class="w-16 px-2 py-1 border rounded" />
                                <input v-model="tableQuery.part3" class="w-24 px-2 py-1 border rounded" />
                                <button @click="fetchInvoices" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded">Search</button>
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
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-gray-600">Issued by:</label>
                                <input type="text" :value="selectedRow?.issued_by || ''" readonly class="w-full border-gray-200 rounded-md bg-gray-50 px-3 py-2" />
                            </div>
                            <div>
                                <label class="block text-gray-600">Date:</label>
                                <input type="text" :value="selectedRow?.issued_date || ''" readonly class="w-full border-gray-200 rounded-md bg-gray-50 px-3 py-2" />
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-gray-600">Printed by:</label>
                                <input type="text" :value="selectedRow?.printed_by || ''" readonly class="w-full border-gray-200 rounded-md bg-gray-50 px-3 py-2" />
                            </div>
                            <div>
                                <label class="block text-gray-600">Date:</label>
                                <input type="text" :value="selectedRow?.printed_date || ''" readonly class="w-full border-gray-200 rounded-md bg-gray-50 px-3 py-2" />
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-gray-600">Posted by:</label>
                                <input type="text" :value="selectedRow?.posted_by || ''" readonly class="w-full border-gray-200 rounded-md bg-gray-50 px-3 py-2" />
                            </div>
                            <div>
                                <label class="block text-gray-600">Date:</label>
                                <input type="text" :value="selectedRow?.posted_date || ''" readonly class="w-full border-gray-200 rounded-md bg-gray-50 px-3 py-2" />
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
                        <button @click="zoom" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded shadow">Zoom</button>
                        <button @click="selectForEdit" :disabled="!selectedRow" class="px-4 py-2 bg-green-600 hover:bg-green-700 disabled:opacity-50 text-white rounded shadow">Select</button>
                    </div>
                    <button @click="showTable=false" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded">Exit</button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

// Toast notification helper
const toast = {
    success: (msg) => alert(msg),
    error: (msg) => alert(msg),
    info: (msg) => alert(msg)
};

const period = ref({ month: 10, year: 2025 });
const query = ref({ part1: '', part2: '', part3: '' });
const tableQuery = ref({ part1: '', part2: '', part3: '' });

const showTable = ref(false);
const invoices = ref([]);
const loading = ref(false);
const selectedRow = ref(null);
const selectedInvoice = ref(null);
const reason = ref('');

// Demo data (fallback when API is unavailable)
const demoInvoices = [
    { invoice_no: '10-2025-97009', invoice_date: '10/10/2025', customer_code: '000150-01', customer_name: 'MULTI MAKMUR INDAH INDUSTRI, PT', tax_code: 'PPN11', mode: 'Manual', pc_status: '0', post_status: 'UnPost', order_mode: '0-Order by Customer + Deliver & Invoice to Customer', issued_by: 'fin03', issued_date: '10/10/2025', printed_by: '', printed_date: '', posted_by: '', posted_date: '' },
    { invoice_no: '10-2025-97008', invoice_date: '10/10/2025', customer_code: '000150-01', customer_name: 'MULTI MAKMUR INDAH INDUSTRI, PT', tax_code: 'PPN11', mode: 'Manual', pc_status: '0', post_status: 'UnPost', order_mode: '0-Order by Customer + Deliver & Invoice to Customer', issued_by: 'fin03', issued_date: '10/10/2025', printed_by: '', printed_date: '', posted_by: '', posted_date: '' },
    { invoice_no: '10-2025-97007', invoice_date: '10/10/2025', customer_code: '000271-01', customer_name: 'SAMPLE CUSTOMER A', tax_code: 'PPN11', mode: 'Manual', pc_status: '0', post_status: 'UnPost', order_mode: '0-Order by Customer + Deliver & Invoice to Customer', issued_by: 'fin03', issued_date: '10/10/2025', printed_by: '', printed_date: '', posted_by: '', posted_date: '' },
    { invoice_no: '10-2025-97006', invoice_date: '10/10/2025', customer_code: '000271-01', customer_name: 'SAMPLE CUSTOMER A', tax_code: 'PPN11', mode: 'Manual', pc_status: '0', post_status: 'UnPost', order_mode: '0-Order by Customer + Deliver & Invoice to Customer', issued_by: 'fin03', issued_date: '10/10/2025', printed_by: '', printed_date: '', posted_by: '', posted_date: '' },
    { invoice_no: '10-2025-97005', invoice_date: '10/10/2025', customer_code: '000272-01', customer_name: 'SAMPLE CUSTOMER B', tax_code: 'PPN11', mode: 'Manual', pc_status: '0', post_status: 'UnPost', order_mode: '0-Order by Customer + Deliver & Invoice to Customer', issued_by: 'fin03', issued_date: '10/10/2025', printed_by: '', printed_date: '', posted_by: '', posted_date: '' },
];

const demoDetailsByNo = {
    '10-2025-97009': {
        invoice_no: '10-2025-97009',
        customer_code: '000150-01',
        customer_name: 'MULTI MAKMUR INDAH INDUSTRI, PT',
        order_mode: '0-Order by Customer + Deliver & Invoice to Customer',
        salesperson: 'S108',
        currency: 'IDR',
        exchange_rate: 0.0,
        exchange_method: '1',
        tax_index_no: '2',
        invoice_date: '2025-10-10',
        ref2: '',
        remark: 'EVA KENPI',
        status: 'New',
    },
};

const openTable = () => {
    showTable.value = true;
    fetchInvoices();
};

const fetchInvoices = async () => {
    loading.value = true;
    try {
        const params = new URLSearchParams();
        if (tableQuery.value.part1) params.append('mm', tableQuery.value.part1);
        if (tableQuery.value.part2) params.append('yyyy', tableQuery.value.part2);
        if (tableQuery.value.part3) params.append('seq', tableQuery.value.part3);
        
        const res = await axios.get(`/api/invoices?${params.toString()}`);
        
        if (res.data) {
            invoices.value = Array.isArray(res.data.data) ? res.data.data : (Array.isArray(res.data) ? res.data : []);
        } else {
            invoices.value = [...demoInvoices];
        }
    } catch (e) {
        console.error('Error fetching invoices:', e);
        invoices.value = [...demoInvoices];
    } finally {
        loading.value = false;
    }
};

const selectRow = (row) => {
    selectedRow.value = row;
};

const selectForEdit = async () => {
    if (!selectedRow.value) {
        toast.error('Please select an invoice first');
        return;
    }
    
    try {
        const res = await axios.get(`/api/invoices/${encodeURIComponent(selectedRow.value.invoice_no)}`);
        
        if (res.data) {
            selectedInvoice.value = res.data;
            showTable.value = false;
            return;
        }
    } catch (e) {
        console.error('Error fetching invoice details:', e);
    }
    
    // Fallback to demo detail if API unavailable
    const demo = demoDetailsByNo[selectedRow.value.invoice_no] || {
        invoice_no: selectedRow.value.invoice_no,
        customer_code: selectedRow.value.customer_code || '',
        customer_name: selectedRow.value.customer_name || '',
        order_mode: selectedRow.value.order_mode || '0-Order by Customer + Deliver & Invoice to Customer',
        salesperson: 'S108',
        currency: 'IDR',
        exchange_rate: 0,
        exchange_method: '1',
        tax_index_no: '2',
        invoice_date: '2025-10-10',
        ref2: '',
        remark: '',
        status: 'New',
    };
    selectedInvoice.value = { ...demo };
    showTable.value = false;
};

const saveInvoice = async () => {
    if (!selectedInvoice.value) {
        toast.error('No invoice selected');
        return;
    }
    
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        const res = await axios.put(
            `/api/invoices/${encodeURIComponent(selectedInvoice.value.invoice_no)}`, 
            selectedInvoice.value,
            { 
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                } 
            }
        );
        
        if (res.data) {
            toast.success('Invoice updated successfully');
            selectedInvoice.value = null;
        }
    } catch (e) {
        console.error('Error saving invoice:', e);
        toast.error(e?.response?.data?.message || 'Failed to update invoice');
    }
};

const zoom = () => {
    // placeholder for additional modal/zoom features
    toast.info('Zoom not implemented yet');
};
</script>

<style scoped>
.form-input { transition: all 0.2s ease; }
.form-input:focus { box-shadow: 0 0 0 3px rgba(79,70,229,.3); border-color: #6366f1; }
.text-shadow { text-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.animate-ping-slow { animation: ping-slow 3s ease-in-out infinite; }
@keyframes ping-slow { 0% { transform: scale(1); opacity:.5;} 50% { transform: scale(1.8);} 100% { transform: scale(2.2); opacity:0;} }
.animation-delay-500 { animation-delay: 1.5s; }
</style>


