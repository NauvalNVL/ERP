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
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-calendar-check text-indigo-600 text-xs"></i>
                        <span>Current Period</span>
                        <span class="text-xs text-gray-500 font-normal"></span>
                    </label>
                    <div class="flex items-center gap-2">
                        <input v-model="period.month" type="text" readonly class="w-16 px-2 py-1 border border-gray-300 rounded bg-gray-100 text-gray-700 font-medium cursor-not-allowed" placeholder="MM" />
                        <input v-model="period.year" type="text" readonly class="w-24 px-2 py-1 border border-gray-300 rounded bg-gray-100 text-gray-700 font-medium cursor-not-allowed" placeholder="YYYY" />
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
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-edit text-emerald-600"></i>
                Edit Invoice Details
            </h3>

            <!-- Readonly Information Section -->
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 border-2 border-gray-300 rounded-lg p-4 mb-6">
                <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <i class="fas fa-lock text-gray-500"></i>
                    Invoice Information (Read Only)
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <label class="flex items-center gap-1 text-gray-600 mb-1">
                            <i class="fas fa-calendar-alt text-xs"></i>
                            Current Period
                        </label>
                        <input type="text" :value="formatPeriod(selectedInvoice.invoice_no)" readonly
                               class="w-full border-gray-300 rounded-md bg-white px-3 py-2 text-gray-700 font-medium cursor-not-allowed" />
                    </div>
                    <div class="md:col-span-2">
                        <label class="flex items-center gap-1 text-gray-600 mb-1">
                            <i class="fas fa-file-invoice text-xs"></i>
                            Invoice#
                        </label>
                        <input type="text" :value="selectedInvoice.invoice_no || ''" readonly
                               class="w-full border-gray-300 rounded-md bg-white px-3 py-2 text-gray-700 font-medium cursor-not-allowed" />
                    </div>
                </div>
                <div class="mt-3">
                    <label class="flex items-center gap-1 text-gray-600 mb-1">
                        <i class="fas fa-user-tie text-xs"></i>
                        Customer
                    </label>
                    <input type="text" :value="selectedInvoice.customer_code + ' - ' + selectedInvoice.customer_name" readonly
                           class="w-full border-gray-300 rounded-md bg-white px-3 py-2 text-gray-700 font-medium cursor-not-allowed" />
                </div>
            </div>

            <!-- Additional Readonly Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mb-4">
                <div>
                    <label class="block text-gray-600 mb-1">Order Mode</label>
                    <input type="text" :value="selectedInvoice.order_mode" readonly class="w-full border-gray-300 rounded-md bg-gray-50 px-3 py-2 text-gray-700 cursor-not-allowed" />
                </div>
                <div>
                    <label class="block text-gray-600 mb-1">Salesperson</label>
                    <input type="text" :value="selectedInvoice.salesperson" readonly class="w-full border-gray-300 rounded-md bg-gray-50 px-3 py-2 text-gray-700 cursor-not-allowed" />
                </div>
                <div>
                    <label class="block text-gray-600 mb-1">A/C Currency</label>
                    <input type="text" :value="selectedInvoice.currency" readonly class="w-full border-gray-300 rounded-md bg-gray-50 px-3 py-2 text-gray-700 cursor-not-allowed" />
                </div>
                <div>
                    <label class="block text-gray-600 mb-1">Exchange Rate</label>
                    <input type="text" :value="selectedInvoice.exchange_rate" readonly class="w-full border-gray-300 rounded-md bg-gray-50 px-3 py-2 text-gray-700 cursor-not-allowed" />
                </div>
            </div>

            <!-- Editable Fields Section -->
            <h4 class="text-sm font-semibold text-emerald-700 mb-3 flex items-center gap-2">
                <i class="fas fa-edit text-emerald-600"></i>
                Editable Fields (CPS Compatible)
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div class="md:col-span-2">
                    <label class="block text-gray-600 mb-1">Exchange Method</label>
                    <div class="flex items-center gap-4 mt-2">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" value="1" v-model="selectedInvoice.exchange_method" class="text-blue-600" />
                            <span>1-Multiply</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" value="2" v-model="selectedInvoice.exchange_method" class="text-blue-600" />
                            <span>2-Divide</span>
                        </label>
                    </div>
                </div>
                <div>
                    <label class="block text-gray-600">Tax Index No.</label>
                    <div class="flex gap-2">
                        <input type="text" v-model="selectedInvoice.tax_index_no" class="flex-1 border-gray-200 rounded-md px-3 py-2" />
                        <button @click="openTaxModal" class="px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded"><i class="fas fa-search"></i></button>
                    </div>
                </div>
                <div>
                    <label class="block text-gray-600">Invoice Date</label>
                    <div class="flex gap-2">
                        <input type="text" :value="formatDisplayDate(selectedInvoice.invoice_date)" readonly class="flex-1 border-gray-200 rounded-md px-3 py-2" />
                        <button @click="openDatePicker" class="px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded"><i class="fas fa-calendar"></i></button>
                    </div>
                </div>
                <div>
                    <label class="block text-gray-600">2nd Reference#</label>
                    <input type="text" v-model="selectedInvoice.ref2" class="w-full border-gray-200 rounded-md px-3 py-2" />
                </div>
                <div class="md:col-span-2">
                    <label class="block text-gray-600">Remark</label>
                    <textarea v-model="selectedInvoice.remark" rows="3" class="w-full border-blue-300 rounded-md px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"></textarea>
                </div>
            </div>

            <!-- Invoice Status (Readonly) -->
            <div class="mt-4">
                <label class="block text-gray-600 text-sm mb-1">Invoice Status</label>
                <input type="text" :value="selectedInvoice.status" readonly class="w-full md:w-1/2 border-gray-300 rounded-md bg-gray-50 px-3 py-2 text-gray-700 cursor-not-allowed" />
            </div>

            <!-- ✅ CPS Style: Audit Trail Section -->
            <div class="mt-6 bg-gray-50 border-2 border-gray-300 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <i class="fas fa-history text-gray-500"></i>
                    Audit Trail
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <!-- Issued By -->
                    <div class="flex gap-2">
                        <div class="flex-1">
                            <label class="block text-gray-600 text-xs mb-1">Issued by:</label>
                            <input type="text" :value="selectedInvoice?.issued_by || ''" readonly 
                                   class="w-full border-gray-300 rounded-md bg-white px-2 py-1 text-sm" />
                        </div>
                        <div class="flex-1">
                            <label class="block text-gray-600 text-xs mb-1">Date:</label>
                            <input type="text" :value="formatAuditDateTime(selectedInvoice?.issued_date, selectedInvoice?.issued_time)" readonly 
                                   class="w-full border-gray-300 rounded-md bg-white px-2 py-1 text-sm" />
                        </div>
                    </div>
                    <!-- Amended By -->
                    <div class="flex gap-2">
                        <div class="flex-1">
                            <label class="block text-gray-600 text-xs mb-1">Amended by:</label>
                            <input type="text" :value="selectedInvoice?.amended_by || ''" readonly 
                                   class="w-full border-gray-300 rounded-md bg-white px-2 py-1 text-sm" />
                        </div>
                        <div class="flex-1">
                            <label class="block text-gray-600 text-xs mb-1">Date:</label>
                            <input type="text" :value="formatAuditDateTime(selectedInvoice?.amended_date, selectedInvoice?.amended_time)" readonly 
                                   class="w-full border-gray-300 rounded-md bg-white px-2 py-1 text-sm" />
                        </div>
                    </div>
                    <!-- Printed By -->
                    <div class="flex gap-2">
                        <div class="flex-1">
                            <label class="block text-gray-600 text-xs mb-1">Printed by:</label>
                            <input type="text" :value="selectedInvoice?.printed_by || ''" readonly 
                                   class="w-full border-gray-300 rounded-md bg-white px-2 py-1 text-sm" />
                        </div>
                        <div class="flex-1">
                            <label class="block text-gray-600 text-xs mb-1">Date:</label>
                            <input type="text" :value="formatAuditDateTime(selectedInvoice?.printed_date, selectedInvoice?.printed_time)" readonly 
                                   class="w-full border-gray-300 rounded-md bg-white px-2 py-1 text-sm" />
                        </div>
                    </div>
                    <!-- Posted By -->
                    <div class="flex gap-2">
                        <div class="flex-1">
                            <label class="block text-gray-600 text-xs mb-1">Posted by:</label>
                            <input type="text" :value="selectedInvoice?.posted_by || ''" readonly 
                                   class="w-full border-gray-300 rounded-md bg-white px-2 py-1 text-sm" />
                        </div>
                        <div class="flex-1">
                            <label class="block text-gray-600 text-xs mb-1">Date:</label>
                            <input type="text" :value="formatAuditDateTime(selectedInvoice?.posted_date, selectedInvoice?.posted_time)" readonly 
                                   class="w-full border-gray-300 rounded-md bg-white px-2 py-1 text-sm" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- CPS Style: Only Next button -->
            <div class="mt-6 flex justify-end">
                <button @click="openFinalScreen" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded shadow-lg">
                    <i class="fas fa-arrow-right mr-2"></i>Next
                </button>
            </div>
        </div>

        <!-- Invoice Table Modal -->
        <InvoiceTableModal
            :open="showTable"
            :initial-query="tableQuery"
            @close="showTable = false"
            @select="selectForEdit"
            @zoom="zoom"
        />

        <!-- Customer Sales Tax Modal -->
        <CustomerSalesTaxorSalesTaxExemptionTable
            :open="showTaxModal"
            :customer-code="selectedInvoice?.customer_code"
            @close="showTaxModal = false"
            @select="onTaxSelect"
        />

        <!-- Date Picker Modal (Image 5) -->
        <div v-if="showDatePicker" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl p-6 w-96">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Date Table</h3>
                    <button @click="showDatePicker = false" class="text-gray-500 hover:text-gray-700"><i class="fas fa-times"></i></button>
                </div>

                <!-- Month/Year Navigation -->
                <div class="flex justify-between items-center mb-4">
                    <button @click="prevMonth" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded"><i class="fas fa-chevron-left"></i></button>
                    <div class="text-center">
                        <div class="font-semibold">{{ calendarMonthName }} {{ calendarYear }}</div>
                    </div>
                    <button @click="nextMonth" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded"><i class="fas fa-chevron-right"></i></button>
                </div>

                <!-- Calendar Grid -->
                <div class="grid grid-cols-7 gap-1">
                    <div v-for="day in ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']" :key="day"
                         class="text-center text-xs font-semibold py-2"
                         :class="day === 'Sat' || day === 'Sun' ? 'bg-yellow-100' : 'bg-gray-100'">
                        {{ day }}
                    </div>
                    <div v-for="(date, idx) in calendarDates" :key="idx"
                         @click="selectDate(date)"
                         class="text-center py-2 cursor-pointer hover:bg-blue-100 rounded"
                         :class="{
                             'bg-blue-500 text-white': date === selectedDate,
                             'text-red-500': isWeekend(date),
                             'text-gray-400': !date
                         }">
                        {{ date }}
                    </div>
                </div>

                <div class="mt-4 flex justify-end gap-2">
                    <button @click="confirmDate" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">OK</button>
                    <button @click="showDatePicker = false" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded">Cancel</button>
                </div>
            </div>
        </div>

        <!-- Final Screen Modal (Image 6) -->
        <FinalScreen
            :open="showFinalScreen"
            :totalAmount="calculatedTotal"
            :taxCode="String(selectedInvoice?.tax_code || selectedInvoice?.tax_index_no || '')"
            :taxOptions="taxOptions"
            :customerCode="selectedInvoice?.customer_code || ''"
            :customerName="selectedInvoice?.customer_name || ''"
            :doNumber="selectedInvoice?.do_number || selectedInvoice?.second_ref || ''"
            :doDate="selectedInvoice?.invoice_date || ''"
            @close="showFinalScreen = false"
            @confirm="onFinalConfirm"
        />
    </AppLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import CustomerSalesTaxorSalesTaxExemptionTable from '@/Components/CustomerSalesTaxorSalesTaxExemptionTable.vue';
import FinalScreen from '@/Components/FinalScreen.vue';
import InvoiceTableModal from '@/Components/InvoiceTableModal.vue';
import axios from 'axios';

// Toast notification helper
const toast = {
    success: (msg) => alert(msg),
    error: (msg) => alert(msg),
    info: (msg) => alert(msg)
};

// Initialize Current Period with current month/year (readonly)
const now = new Date();
const period = ref({
    month: String(now.getMonth() + 1).padStart(2, '0'),  // 01-12
    year: String(now.getFullYear())  // 2025
});

const query = ref({ part1: '', part2: '', part3: '' });
const tableQuery = ref({ part1: '', part2: '', part3: '' });

const showTable = ref(false);
const selectedInvoice = ref(null);

// Modal states
const showTaxModal = ref(false);
const showDatePicker = ref(false);
const showFinalScreen = ref(false);

// Tax options
const taxOptions = ref([]);

// Calendar state
const calendarMonth = ref(new Date().getMonth());
const calendarYear = ref(new Date().getFullYear());
const selectedDate = ref(null);

// Calculated values
const calculatedTotal = ref(0);

const openTable = async () => {
    // CPS Behavior: If full invoice# entered, load directly without table
    if (query.value.part1 && query.value.part2 && query.value.part3) {
        const fullInvoiceNo = `${query.value.part1}-${query.value.part2}-${query.value.part3}`;
        console.log('🔍 Direct search for invoice:', fullInvoiceNo);

        try {
            const res = await axios.get(`/api/invoices/${encodeURIComponent(fullInvoiceNo)}`);
            if (res.data) {
                // Convert numeric fields from string to number, ensure tax fields are strings
                selectedInvoice.value = {
                    ...res.data,
                    exchange_rate: parseFloat(res.data.exchange_rate) || 0,
                    total_amount: parseFloat(res.data.total_amount) || 0,
                    tax_amount: parseFloat(res.data.tax_amount) || 0,
                    net_amount: parseFloat(res.data.net_amount) || 0,
                    tax_percent: parseFloat(res.data.tax_percent) || 0,
                    // Ensure tax fields are strings to prevent type errors
                    tax_code: String(res.data.tax_code || ''),
                    tax_index_no: String(res.data.tax_index_no || '')
                };
                toast.success(`Invoice ${fullInvoiceNo} loaded`);
                return; // Skip table modal
            }
        } catch (e) {
            console.error('Invoice not found:', e);
            toast.error('Invoice not found');
        }
    }

    // Otherwise, open table with filter
    tableQuery.value.part1 = query.value.part1 || period.value.month;
    tableQuery.value.part2 = query.value.part2 || period.value.year;
    tableQuery.value.part3 = query.value.part3 || '';

    showTable.value = true;
};

const selectForEdit = async (selectedRow) => {
    if (!selectedRow) {
        toast.error('Please select an invoice first');
        return;
    }

    try {
        const res = await axios.get(`/api/invoices/${encodeURIComponent(selectedRow.invoice_no)}`);

        if (res.data) {
            // Convert numeric fields from string to number, ensure tax fields are strings
            selectedInvoice.value = {
                ...res.data,
                exchange_rate: parseFloat(res.data.exchange_rate) || 0,
                total_amount: parseFloat(res.data.total_amount) || 0,
                tax_amount: parseFloat(res.data.tax_amount) || 0,
                net_amount: parseFloat(res.data.net_amount) || 0,
                tax_percent: parseFloat(res.data.tax_percent) || 0,
                // Ensure tax fields are strings to prevent type errors
                tax_code: String(res.data.tax_code || ''),
                tax_index_no: String(res.data.tax_index_no || '')
            };
            showTable.value = false;
            return;
        }
    } catch (e) {
        console.error('Error fetching invoice details:', e);
        toast.error('Failed to load invoice details');
    }
};

const saveInvoice = async () => {
    if (!selectedInvoice.value) {
        toast.error('No invoice selected');
        return;
    }

    // ✅ CPS Business Rules: Check if invoice can be amended
    if (selectedInvoice.value.printed_by && selectedInvoice.value.printed_by.trim() !== '') {
        toast.error(`Cannot amend invoice that has been printed by ${selectedInvoice.value.printed_by}`);
        return;
    }

    if (selectedInvoice.value.status === 'Cancelled') {
        toast.error('Cannot amend cancelled invoice');
        return;
    }

    if (selectedInvoice.value.status === 'Posted') {
        toast.error('Cannot amend invoice that has been posted to GL');
        return;
    }

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        console.log('💾 Saving invoice amendment:', {
            invoice_no: selectedInvoice.value.invoice_no,
            tax_code: selectedInvoice.value.tax_code,
            tax_percent: selectedInvoice.value.tax_percent,
            tax_amount: selectedInvoice.value.tax_amount,
            total_amount: selectedInvoice.value.total_amount,
            net_amount: selectedInvoice.value.net_amount,
            remark: selectedInvoice.value.remark
        });

        const res = await axios.put(
            `/api/invoices/${encodeURIComponent(selectedInvoice.value.invoice_no)}`,
            {
                exchange_method: selectedInvoice.value.exchange_method,
                tax_index_no: selectedInvoice.value.tax_index_no,
                tax_code: selectedInvoice.value.tax_code,
                tax_percent: selectedInvoice.value.tax_percent,
                invoice_date: selectedInvoice.value.invoice_date,
                ref2: selectedInvoice.value.ref2,
                remark: selectedInvoice.value.remark,
                total_amount: selectedInvoice.value.total_amount,
                tax_amount: selectedInvoice.value.tax_amount,
                net_amount: selectedInvoice.value.net_amount
            },
            {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            }
        );

        if (res.data && res.data.success) {
            console.log('✅ Invoice amended successfully:', res.data);
            toast.success(`Invoice ${selectedInvoice.value.invoice_no} amended successfully`);
            
            // Reload invoice details to show updated audit trail
            const reloadRes = await axios.get(`/api/invoices/${encodeURIComponent(selectedInvoice.value.invoice_no)}`);
            if (reloadRes.data) {
                selectedInvoice.value = {
                    ...reloadRes.data,
                    exchange_rate: parseFloat(reloadRes.data.exchange_rate) || 0,
                    total_amount: parseFloat(reloadRes.data.total_amount) || 0,
                    tax_amount: parseFloat(reloadRes.data.tax_amount) || 0,
                    net_amount: parseFloat(reloadRes.data.net_amount) || 0,
                    tax_percent: parseFloat(reloadRes.data.tax_percent) || 0,
                    tax_code: String(reloadRes.data.tax_code || ''),
                    tax_index_no: String(reloadRes.data.tax_index_no || '')
                };
                console.log('✅ Invoice details reloaded with updated audit trail');
            }
        }
    } catch (e) {
        console.error('❌ Error saving invoice:', e);
        const errorMsg = e?.response?.data?.error || e?.response?.data?.message || 'Failed to update invoice';
        toast.error(errorMsg);
    }
};

const zoom = () => {
    // placeholder for additional modal/zoom features
    toast.info('Zoom not implemented yet');
};

// Tax Modal Functions
const openTaxModal = () => {
    if (!selectedInvoice.value) {
        toast.error('Please select an invoice first');
        return;
    }

    if (!selectedInvoice.value.customer_code) {
        toast.error('Customer code not found for this invoice');
        return;
    }

    console.log('🔍 Opening Tax Modal for customer:', selectedInvoice.value.customer_code);
    showTaxModal.value = true;
};

const onTaxSelect = async (taxData) => {
    if (!selectedInvoice.value) return;

    console.log('✅ Tax selected:', taxData);

    // Convert to string to prevent type errors
    selectedInvoice.value.tax_index_no = String(taxData.index_number || '');

    // Fetch tax details from tax group to get tax code and rate
    try {
        if (taxData.tax_group_code) {
            const res = await axios.get(`/api/invoices/tax-groups/${taxData.tax_group_code}/tax-items`);
            console.log('Tax Group Details:', res.data);

            if (res.data && res.data.length > 0) {
                // Use first tax item from group (or could show another modal to select)
                const firstTax = res.data[0];
                selectedInvoice.value.tax_code = String(firstTax.code || firstTax.tax_code || '');
                selectedInvoice.value.tax_percent = parseFloat(firstTax.rate || firstTax.tax_rate || 0);

                console.log('✅ Tax details set:', {
                    tax_index_no: selectedInvoice.value.tax_index_no,
                    tax_code: selectedInvoice.value.tax_code,
                    tax_percent: selectedInvoice.value.tax_percent
                });

                toast.success(`Tax Index ${taxData.index_number} selected`);
            }
        }
    } catch (e) {
        console.error('Error fetching tax group details:', e);
        // Set fallback values
        selectedInvoice.value.tax_code = String(taxData.tax_group_code || '');
        selectedInvoice.value.tax_percent = 0;
    }

    showTaxModal.value = false;
};

// Date Picker Functions
const openDatePicker = () => {
    // Initialize calendar to current invoice date or today
    const currentDate = selectedInvoice.value?.invoice_date
        ? new Date(selectedInvoice.value.invoice_date)
        : new Date();

    calendarMonth.value = currentDate.getMonth();
    calendarYear.value = currentDate.getFullYear();
    selectedDate.value = currentDate.getDate();

    showDatePicker.value = true;
};

const calendarMonthName = computed(() => {
    const months = ['January', 'February', 'March', 'April', 'May', 'June',
                   'July', 'August', 'September', 'October', 'November', 'December'];
    return months[calendarMonth.value];
});

const calendarDates = computed(() => {
    const firstDay = new Date(calendarYear.value, calendarMonth.value, 1);
    const lastDay = new Date(calendarYear.value, calendarMonth.value + 1, 0);
    const daysInMonth = lastDay.getDate();
    const startDay = firstDay.getDay(); // 0 = Sunday

    // Adjust to Monday start (CPS style)
    const adjustedStart = startDay === 0 ? 6 : startDay - 1;

    const dates = [];
    // Add empty cells for days before month starts
    for (let i = 0; i < adjustedStart; i++) {
        dates.push(null);
    }
    // Add actual dates
    for (let i = 1; i <= daysInMonth; i++) {
        dates.push(i);
    }

    return dates;
});

const isWeekend = (date) => {
    if (!date) return false;
    const day = new Date(calendarYear.value, calendarMonth.value, date).getDay();
    return day === 0 || day === 6; // Sunday or Saturday
};

const prevMonth = () => {
    if (calendarMonth.value === 0) {
        calendarMonth.value = 11;
        calendarYear.value--;
    } else {
        calendarMonth.value--;
    }
};

const nextMonth = () => {
    if (calendarMonth.value === 11) {
        calendarMonth.value = 0;
        calendarYear.value++;
    } else {
        calendarMonth.value++;
    }
};

const selectDate = (date) => {
    if (date) {
        selectedDate.value = date;
    }
};

const confirmDate = () => {
    if (selectedDate.value && selectedInvoice.value) {
        const year = calendarYear.value;
        const month = String(calendarMonth.value + 1).padStart(2, '0');
        const day = String(selectedDate.value).padStart(2, '0');
        selectedInvoice.value.invoice_date = `${year}-${month}-${day}`;
    }
    showDatePicker.value = false;
};

const formatDisplayDate = (dateStr) => {
    if (!dateStr) return '';

    // Handle different date formats from database
    // Format 1: YYYY-MM-DD (e.g., 2025-11-05)
    // Format 2: DD/MM/YYYY (e.g., 05/11/2025)

    let date;

    // Try parsing YYYY-MM-DD format
    if (dateStr.includes('-')) {
        date = new Date(dateStr);
    }
    // Try parsing DD/MM/YYYY format
    else if (dateStr.includes('/')) {
        const parts = dateStr.split('/');
        if (parts.length === 3) {
            // DD/MM/YYYY -> new Date(YYYY, MM-1, DD)
            date = new Date(parts[2], parts[1] - 1, parts[0]);
        }
    }

    if (!date || isNaN(date)) return dateStr;

    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();

    return `${day}/${month}/${year}`; // CPS format: DD/MM/YYYY
};

const formatPeriod = (invoiceNo) => {
    if (!invoiceNo) return '';

    // Handle various invoice formats:
    // Format 1: MM-YYYY-NNNN (e.g., 10-2025-97042)
    // Format 2: IV-YYYYMMDD-NNNN (e.g., IV-2025114-0001)
    // Format 3: YYYY-MM-NNNN (e.g., 2025-11-0001)

    const parts = invoiceNo.split('-');

    if (parts.length >= 3) {
        // Check if first part is "IV" prefix
        if (parts[0] === 'IV' && parts[1].length === 7) {
            // Format: IV-YYYYMMDD-NNNN → extract YYYY and MM
            const yearMonth = parts[1]; // e.g., "2025114"
            const year = yearMonth.substring(0, 4); // "2025"
            const month = yearMonth.substring(4, 6); // "11"
            return `${month}/${year}`;
        }
        // Check if first part is 2-digit (MM-YYYY-NNNN)
        else if (parts[0].length === 2) {
            return `${parts[0]}/${parts[1]}`; // MM/YYYY
        }
        // Check if first part is 4-digit year (YYYY-MM-NNNN)
        else if (parts[0].length === 4) {
            return `${parts[1]}/${parts[0]}`; // MM/YYYY
        }
    }

    return invoiceNo; // Return as-is if format unknown
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

// Final Screen Functions
const openFinalScreen = async () => {
    if (!selectedInvoice.value) {
        toast.error('No invoice selected');
        return;
    }

    console.log('🎬 Opening Final Screen for invoice:', selectedInvoice.value.invoice_no);

    // CPS-Compatible: Calculate total amount (Priority order matches Prepare Invoice flow)
    let total = 0;

    try {
        // Priority 1: Get from invoice header (IV_TRAN_AMT) if available
        const invoiceHeaderTotal = parseFloat(selectedInvoice.value?.total_amount) || 0;

        if (invoiceHeaderTotal > 0) {
            total = invoiceHeaderTotal;
            console.log('✅ Using total from invoice header (IV_TRAN_AMT):', total);
        } else {
            console.log('⚠️ Invoice header total is 0, trying alternative methods...');

            // Priority 2: Try to get invoice with items from INVDET table
            try {
                const resWithItems = await axios.get(`/api/invoices/${encodeURIComponent(selectedInvoice.value.invoice_no)}/with-items`);

                if (resWithItems.data && resWithItems.data.items && resWithItems.data.items.length > 0) {
                    // Calculate from invoice items
                    total = resWithItems.data.items.reduce((sum, item) => {
                        const itemTotal = parseFloat(item.amount || item.total_amount || 0);
                        return sum + itemTotal;
                    }, 0);
                    console.log('✅ Calculated from invoice items:', resWithItems.data.items.length, 'items, total:', total);
                }
            } catch (e) {
                console.warn('⚠️ Cannot fetch invoice items:', e.message);
            }

            // Priority 3: If still 0, calculate from related DO (CPS Flow - same as Prepare Invoice)
            if (total === 0 && selectedInvoice.value.invoice_no) {
                console.log('⚠️ Total still 0, attempting to calculate from related DO...');

                try {
                    // Get invoice details to find related DO/SO
                    const invDetail = await axios.get(`/api/invoices/${encodeURIComponent(selectedInvoice.value.invoice_no)}`);

                    if (invDetail.data && invDetail.data.invoice_no) {
                        // Try to extract DO number from 2nd Reference or SO Number
                        const doNumber = invDetail.data.ref2 || invDetail.data.do_number || invDetail.data.second_ref;
                        const soNumber = invDetail.data.so_number;

                        console.log('📦 Related DO/SO found:', { doNumber, soNumber });

                        // If we have SO number, calculate from DO table
                        if (soNumber) {
                            try {
                                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                                const doRes = await axios.post(`/api/invoices/calculate-total`, {
                                    so_number: soNumber
                                }, {
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken
                                    }
                                });

                                if (doRes.data && doRes.data.total_amount > 0) {
                                    total = doRes.data.total_amount;
                                    console.log('✅ Calculated from related DO via SO:', total);
                                }
                            } catch (e) {
                                console.warn('⚠️ Cannot calculate from SO:', e.message);
                            }
                        }

                        // If we have DO number, try direct DO lookup
                        if (total === 0 && doNumber) {
                            try {
                                const doDetailRes = await axios.get(`/api/invoices/do-items?do_number=${encodeURIComponent(doNumber)}`);

                                if (doDetailRes.data && doDetailRes.data.total_amount > 0) {
                                    total = doDetailRes.data.total_amount;
                                    console.log('✅ Calculated from related DO directly:', total);
                                }
                            } catch (e) {
                                console.warn('⚠️ Cannot fetch DO details:', e.message);
                            }
                        }
                    }
                } catch (e) {
                    console.warn('⚠️ Cannot fetch invoice details for DO lookup:', e.message);
                }
            }

            // Priority 4: Use net amount if available (fallback)
            if (total === 0) {
                const netAmount = parseFloat(selectedInvoice.value?.net_amount) || 0;
                if (netAmount > 0) {
                    // Reverse calculate: net_amount = total + tax
                    // If tax is known, we can get total back
                    const taxPercent = parseFloat(selectedInvoice.value?.tax_percent) || 0;
                    if (taxPercent > 0) {
                        total = netAmount / (1 + (taxPercent / 100));
                        console.log('✅ Reverse calculated from net amount and tax:', total);
                    } else {
                        // Assume net = total (no tax)
                        total = netAmount;
                        console.log('✅ Using net amount as total (no tax):', total);
                    }
                }
            }
        }

        // Set the calculated total
        calculatedTotal.value = total;
        console.log('💰 Final total amount:', total, typeof total);

    } catch (e) {
        console.error('❌ Error calculating total amount:', e);
        // Last resort fallback
        calculatedTotal.value = parseFloat(selectedInvoice.value?.total_amount) || 0;
    }

    // Fetch tax options - with better fallback
    if (taxOptions.value.length === 0) {
        try {
            const res = await axios.get('/api/invoices/tax-groups');
            if (res.data && Array.isArray(res.data)) {
                taxOptions.value = res.data.map(tax => ({
                    code: tax.code || tax.tax_code,
                    name: tax.name || tax.tax_name,
                    rate: parseFloat(tax.rate || tax.tax_rate) || 0,
                    apply: true,
                    include: false
                }));
                console.log('✅ Tax options loaded:', taxOptions.value.length);
            } else {
                throw new Error('Invalid response format');
            }
        } catch (e) {
            console.warn('Using fallback tax options:', e.message);
            // Fallback: common Indonesian tax options
            taxOptions.value = [
                { code: 'NIL', name: 'Non-Taxable', rate: 0, apply: true, include: false },
                { code: 'PPN10', name: 'PPN 10%', rate: 10, apply: true, include: false },
                { code: 'PPN11', name: 'PPN 11%', rate: 11, apply: true, include: false },
                { code: 'PPN115', name: 'PPN 11.5%', rate: 11.5, apply: true, include: false }
            ];
            console.log('✅ Using fallback tax options:', taxOptions.value.length);
        }
    }

    // Get tax code from invoice (use tax_index_no if tax_code is empty)
    const taxCodeToUse = selectedInvoice.value.tax_code || selectedInvoice.value.tax_index_no || '';

    // Log all data being sent to Final Screen
    console.log('═══════════════════════════════════════════════════════');
    console.log('🎬 Opening Final Screen (Amend Invoice)');
    console.log('═══════════════════════════════════════════════════════');
    console.log('Invoice Details:');
    console.log('  Invoice No:', selectedInvoice.value.invoice_no);
    console.log('  Customer:', selectedInvoice.value.customer_code, '-', selectedInvoice.value.customer_name);
    console.log('  SO Number:', selectedInvoice.value.so_number);
    console.log('  DO Number:', selectedInvoice.value.do_number || selectedInvoice.value.second_ref);
    console.log('');
    console.log('Financial Data:');
    console.log('  Total Amount:', calculatedTotal.value, typeof calculatedTotal.value);
    console.log('  Tax Code (from invoice):', selectedInvoice.value.tax_code);
    console.log('  Tax Index No:', selectedInvoice.value.tax_index_no);
    console.log('  Tax Code (to use):', taxCodeToUse);
    console.log('  Tax Percent:', selectedInvoice.value.tax_percent);
    console.log('');
    console.log('Tax Options Available:', taxOptions.value.length);
    if (taxOptions.value.length > 0) {
        console.log('  Options:', taxOptions.value.map(t => `${t.code} (${t.rate}%)`).join(', '));
    }
    console.log('═══════════════════════════════════════════════════════');

    showFinalScreen.value = true;
};

const onFinalConfirm = async (finalData) => {
    if (!selectedInvoice.value) return;

    console.log('═══════════════════════════════════════════════════════');
    console.log('✅ Final Screen Confirmed (Amend Invoice)');
    console.log('═══════════════════════════════════════════════════════');
    console.log('Data from Final Screen:');
    console.log('  Tax Code:', finalData.taxCode);
    console.log('  Tax Percent:', finalData.taxPercent);
    console.log('  Total Amount:', finalData.totalAmount);
    console.log('  Tax Amount:', finalData.taxAmount);
    console.log('  Net Amount:', finalData.netAmount);
    console.log('');

    // Update invoice data from final screen
    selectedInvoice.value.total_amount = finalData.totalAmount;
    selectedInvoice.value.tax_amount = finalData.taxAmount;
    selectedInvoice.value.net_amount = finalData.netAmount;
    selectedInvoice.value.tax_code = finalData.taxCode;
    selectedInvoice.value.tax_percent = finalData.taxPercent;

    console.log('Updated Invoice Data:');
    console.log('  Invoice No:', selectedInvoice.value.invoice_no);
    console.log('  Total Amount:', selectedInvoice.value.total_amount);
    console.log('  Tax Code:', selectedInvoice.value.tax_code);
    console.log('  Tax Percent:', selectedInvoice.value.tax_percent);
    console.log('  Tax Amount:', selectedInvoice.value.tax_amount);
    console.log('  Net Amount:', selectedInvoice.value.net_amount);
    console.log('═══════════════════════════════════════════════════════');

    // Close final screen
    showFinalScreen.value = false;

    // CPS Flow: Auto-save after Final Screen OK
    await saveInvoice();
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


