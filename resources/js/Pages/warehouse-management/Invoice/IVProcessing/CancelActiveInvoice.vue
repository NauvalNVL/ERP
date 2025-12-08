<template>
    <AppLayout :header="'Cancel Active Invoice'">
        <!-- Header -->
        <div class="bg-gradient-to-r from-red-600 via-pink-600 to-rose-600 p-6 rounded-t-lg shadow-lg overflow-hidden relative">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10"></div>
            <div class="flex items-center">
                <div class="bg-gradient-to-br from-red-500 to-red-700 p-3 rounded-lg shadow-inner flex items-center justify-center relative overflow-hidden mr-4">
                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-yellow-300 opacity-30 rounded-full animate-ping-slow"></div>
                    <div class="absolute -bottom-1 -left-1 w-4 h-4 bg-blue-300 opacity-30 rounded-full animate-ping-slow animation-delay-500"></div>
                    <i class="fas fa-times-circle text-white text-2xl z-10"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-1 text-shadow">Cancel Active Invoice</h2>
                    <p class="text-red-100">Cancel existing active invoices with reason</p>
                </div>
            </div>
        </div>

        <!-- Main Form -->
        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6 bg-gradient-to-br from-white to-red-50">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Current Period (Readonly) -->
                <div>
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-calendar-check text-red-600 text-xs"></i>
                        <span>Current Period</span>
                    </label>
                    <div class="flex items-center gap-2">
                        <input v-model="period.month" type="text" readonly class="w-16 px-2 py-1 border border-gray-300 rounded bg-gray-100 text-gray-700 font-medium cursor-not-allowed" placeholder="MM" />
                        <input v-model="period.year" type="text" readonly class="w-24 px-2 py-1 border border-gray-300 rounded bg-gray-100 text-gray-700 font-medium cursor-not-allowed" placeholder="YYYY" />
                    </div>
                </div>

                <!-- Invoice# Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Invoice#</label>
                    <div class="flex items-center gap-2">
                        <input v-model="query.part1" type="text" class="w-16 px-2 py-1 border rounded form-input" placeholder="MM" maxlength="2" />
                        <input v-model="query.part2" type="text" class="w-20 px-2 py-1 border rounded form-input" placeholder="YYYY" maxlength="4" />
                        <input v-model="query.part3" type="text" class="w-24 px-2 py-1 border rounded form-input" placeholder="0" />
                        <button @click="openInvoiceTable" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded shadow flex items-center gap-2">
                            <i class="fas fa-search"></i>
                            <span>Search</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Selected Invoice Details (Show after selection) -->
            <div v-if="selectedInvoice" class="mt-6 border-t-4 border-red-500 bg-gradient-to-br from-gray-50 to-red-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-file-invoice text-red-600"></i>
                    Invoice Details
                </h3>

                <!-- Invoice Information (Readonly) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-600 text-sm mb-1">Invoice#</label>
                        <input type="text" :value="selectedInvoice.invoice_no" readonly class="w-full border-gray-300 rounded-md bg-white px-3 py-2 text-gray-700 font-medium cursor-not-allowed" />
                    </div>
                    <div>
                        <label class="block text-gray-600 text-sm mb-1">Invoice Date</label>
                        <input type="text" :value="selectedInvoice.invoice_date" readonly class="w-full border-gray-300 rounded-md bg-white px-3 py-2 text-gray-700 cursor-not-allowed" />
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-gray-600 text-sm mb-1">Customer</label>
                        <input type="text" :value="selectedInvoice.customer_code + ' - ' + selectedInvoice.customer_name" readonly class="w-full border-gray-300 rounded-md bg-white px-3 py-2 text-gray-700 cursor-not-allowed" />
                    </div>
                    <div>
                        <label class="block text-gray-600 text-sm mb-1">Order Mode</label>
                        <input type="text" :value="selectedInvoice.order_mode || '0-Order by Customer + Deliver & Invoice to Customer'" readonly class="w-full border-gray-300 rounded-md bg-white px-3 py-2 text-gray-700 cursor-not-allowed" />
                    </div>
                    <div>
                        <label class="block text-gray-600 text-sm mb-1">Salesperson</label>
                        <input type="text" :value="selectedInvoice.salesperson || 'S108'" readonly class="w-full border-gray-300 rounded-md bg-white px-3 py-2 text-gray-700 cursor-not-allowed" />
                    </div>
                    <div>
                        <label class="block text-gray-600 text-sm mb-1">Currency</label>
                        <input type="text" :value="selectedInvoice.currency || 'IDR'" readonly class="w-full border-gray-300 rounded-md bg-white px-3 py-2 text-gray-700 cursor-not-allowed" />
                    </div>
                </div>

                <!-- Cancel Reasons (Editable - CPS Style) -->
                <div class="border-t-2 border-red-300 pt-4 mt-4">
                    <h4 class="text-sm font-semibold text-red-700 mb-3 flex items-center gap-2">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                        Cancellation Reasons (Required)
                    </h4>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-gray-700 text-sm mb-1 font-medium">
                                Reason 1: <span class="text-red-600">*</span>
                            </label>
                            <input
                                v-model="form.reason1"
                                type="text"
                                class="w-full border-gray-300 rounded-md px-3 py-2 focus:border-red-500 focus:ring-2 focus:ring-red-200"
                                placeholder="Enter primary cancellation reason"
                                maxlength="250"
                            />
                        </div>
                        <div>
                            <label class="block text-gray-600 text-sm mb-1">Reason 2:</label>
                            <input
                                v-model="form.reason2"
                                type="text"
                                class="w-full border-gray-300 rounded-md px-3 py-2 focus:border-red-500 focus:ring-2 focus:ring-red-200"
                                placeholder="Enter additional reason (optional)"
                                maxlength="250"
                            />
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex justify-end gap-3">
                    <button
                        @click="clearSelection"
                        class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded shadow">
                        <i class="fas fa-times mr-2"></i>Clear
                    </button>
                    <button
                        @click="confirmCancel"
                        :disabled="!form.reason1.trim()"
                        class="px-6 py-2 bg-red-600 hover:bg-red-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white rounded shadow">
                        <i class="fas fa-ban mr-2"></i>Cancel Invoice
                    </button>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="mt-6 text-center py-12">
                <i class="fas fa-search text-gray-300 text-6xl mb-4"></i>
                <p class="text-gray-500 text-lg">Search for an invoice to cancel</p>
                <p class="text-gray-400 text-sm mt-2">Use the search button above to find an active invoice</p>
            </div>
        </div>

        <!-- Invoice Table Modal -->
        <InvoiceTableModal
            :open="showInvoiceTable"
            :initial-query="tableQuery"
            :exclude-cancelled="true"
            @close="showInvoiceTable = false"
            @select="selectInvoice"
            @zoom="handleZoom"
        />

        <!-- Confirmation Modal (CPS Style) -->
        <div v-if="showConfirmation" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl p-6 w-96 animate-scaleIn">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-question-circle text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Confirmation</h3>
                </div>
                <p class="text-gray-700 mb-6">Confirm Saving / Updating ?</p>
                <div class="flex justify-end gap-3">
                    <button
                        @click="executeCancel"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded shadow">
                        OK
                    </button>
                    <button
                        @click="showConfirmation = false"
                        class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded shadow">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import InvoiceTableModal from '@/Components/InvoiceTableModal.vue';
// cspell:ignore axios
import axios from 'axios';

// Toast notification helper
const toast = {
    success: (msg) => {
        alert('✅ ' + msg);
    },
    error: (msg) => {
        alert('❌ ' + msg);
    },
    info: (msg) => {
        alert('ℹ️ ' + msg);
    }
};

// Initialize Current Period with current month/year (readonly)
const now = new Date();
const period = ref({
    month: String(now.getMonth() + 1).padStart(2, '0'),  // 01-12
    year: String(now.getFullYear())  // 2025
});

// Search query
const query = ref({
    part1: period.value.month,
    part2: period.value.year,
    part3: ''
});

const tableQuery = ref({
    part1: '',
    part2: '',
    part3: ''
});

// State
const showInvoiceTable = ref(false);
const showConfirmation = ref(false);
const selectedInvoice = ref(null);
const form = ref({
    reason1: '',
    reason2: ''
});

// Open Invoice Table Modal
const openInvoiceTable = () => {
    // Set query for modal
    tableQuery.value = {
        part1: query.value.part1 || period.value.month,
        part2: query.value.part2 || period.value.year,
        part3: query.value.part3 || ''
    };

    showInvoiceTable.value = true;
};

// Select invoice from table
const selectInvoice = async (invoice) => {
    console.log('📋 Invoice selected:', invoice);

    try {
        // Fetch full invoice details
        const res = await axios.get(`/api/invoices/${encodeURIComponent(invoice.invoice_no)}`);

        if (res.data) {
            // Check if invoice can be cancelled
            if (res.data.status === 'Cancelled') {
                toast.error('This invoice is already cancelled');
                return;
            }

            if (res.data.status === 'Posted') {
                toast.error('Cannot cancel invoice that has been posted to GL');
                return;
            }

            // Set selected invoice
            selectedInvoice.value = {
                invoice_no: res.data.invoice_no,
                invoice_date: res.data.invoice_date,
                customer_code: res.data.customer_code,
                customer_name: res.data.customer_name,
                order_mode: res.data.order_mode,
                salesperson: res.data.salesperson,
                currency: res.data.currency,
                status: res.data.status
            };

            // Clear previous reasons
            form.value.reason1 = '';
            form.value.reason2 = '';

            // Close modal
            showInvoiceTable.value = false;

            toast.success('Invoice loaded successfully');
        }
    } catch (e) {
        console.error('Error loading invoice:', e);
        toast.error('Failed to load invoice details: ' + (e.response?.data?.message || e.message));
    }
};

// Clear selection
const clearSelection = () => {
    selectedInvoice.value = null;
    form.value.reason1 = '';
    form.value.reason2 = '';
};

// Confirm cancellation (show confirmation modal)
const confirmCancel = () => {
    if (!form.value.reason1.trim()) {
        toast.error('Please enter Reason 1 (required)');
        return;
    }

    showConfirmation.value = true;
};

// Execute cancel invoice (after confirmation)
const executeCancel = async () => {
    if (!selectedInvoice.value) {
        toast.error('No invoice selected');
        return;
    }

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        const payload = {
            invoice_number: selectedInvoice.value.invoice_no,
            cancel_reason: form.value.reason1, // CPS uses CANCELLED_REASON_1
            cancel_reason_2: form.value.reason2 // Optional second reason
        };

        console.log('🚀 Cancelling invoice:', payload);

        const res = await axios.post('/api/invoices/cancel', payload, {
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });

        if (res.data.success) {
            toast.success('Invoice cancelled successfully!');

            // Close confirmation modal
            showConfirmation.value = false;

            // Clear form
            clearSelection();

            // Reset search
            query.value.part1 = period.value.month;
            query.value.part2 = period.value.year;
            query.value.part3 = '';
        } else {
            toast.error('Failed to cancel invoice: ' + (res.data.error || 'Unknown error'));
        }

    } catch (e) {
        console.error('❌ Error cancelling invoice:', e);
        toast.error('Failed to cancel invoice: ' + (e.response?.data?.error || e.message));
    }
};

// Handle zoom (placeholder)
const handleZoom = () => {
    toast.info('Zoom feature not implemented yet');
};
</script>

<style scoped>
.form-input {
    transition: all 0.2s ease;
}

.form-input:focus {
    box-shadow: 0 0 0 3px rgba(220,38,38,.3);
    border-color: #dc2626;
}

.text-shadow {
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.animate-ping-slow {
    animation: ping-slow 3s ease-in-out infinite;
}

@keyframes ping-slow {
    0% { transform: scale(1); opacity:.5;}
    50% { transform: scale(1.8);}
    100% { transform: scale(2.2); opacity:0;}
}

.animation-delay-500 {
    animation-delay: 1.5s;
}

.animate-scaleIn {
    animation: scaleIn 0.2s ease-out;
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

