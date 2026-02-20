<template>
    <AppLayout :header="'Print Invoice'">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 text-white shadow-lg rounded-t-lg border border-purple-700">
            <div class="px-4 py-3 sm:px-6 flex items-center gap-3">
                <div class="h-9 w-9 rounded-full bg-purple-500 flex items-center justify-center">
                    <i class="fas fa-print text-white text-lg"></i>
                </div>
                <div>
                    <h2 class="text-lg sm:text-xl font-semibold leading-tight">Print Invoice</h2>
                    <p class="text-xs sm:text-sm text-purple-100">Print and export invoices with PDF generation</p>
                </div>
            </div>
        </div>

        <!-- Main Form -->
        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6 bg-gradient-to-br from-white to-indigo-50">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Current Period (Readonly) -->
                <div>
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-calendar-check text-purple-600 text-xs"></i>
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
                        <button @click="openInvoiceTable" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded shadow flex items-center gap-2">
                            <i class="fas fa-search"></i>
                            <span>Search</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Selected Invoice Details (Show after selection) -->
            <div v-if="selectedInvoice" class="mt-6 border-t-4 border-purple-500 bg-gradient-to-br from-gray-50 to-indigo-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-file-invoice text-purple-600"></i>
                    Invoice Details
                </h3>

                <!-- Invoice Information (Readonly) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-600 text-sm mb-1">Invoice#</label>
                        <input type="text" :value="selectedInvoice.invoice_no" readonly class="w-full border-gray-300 rounded-md bg-white px-3 py-2 text-gray-700 font-medium cursor-not-allowed" />
                        <div
                            v-if="isSelectedInvoiceCancelled"
                            class="mt-1 inline-flex items-center px-2 py-0.5 rounded-full bg-red-100 text-red-700 text-xs font-semibold uppercase tracking-wide"
                        >
                            CANCEL
                        </div>
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
                        <label class="block text-gray-600 text-sm mb-1">Currency</label>
                        <input type="text" :value="selectedInvoice.currency || 'IDR'" readonly class="w-full border-gray-300 rounded-md bg-white px-3 py-2 text-gray-700 cursor-not-allowed" />
                    </div>
                    <div>
                        <label class="block text-gray-600 text-sm mb-1">Status</label>
                        <input type="text" :value="selectedInvoice.status" readonly class="w-full border-gray-300 rounded-md bg-white px-3 py-2 text-gray-700 cursor-not-allowed" />
                    </div>
                </div>

                <!-- Invoice Amounts -->
                <div class="bg-white border-2 border-purple-200 rounded-lg p-4 mb-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <i class="fas fa-dollar-sign text-purple-600"></i>
                        Invoice Amounts
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-gray-600 text-xs mb-1">Total Amount</label>
                            <input type="text" :value="formatCurrency(selectedInvoice.total_amount)" readonly class="w-full border-gray-200 rounded-md bg-gray-50 px-2 py-1 text-sm text-right font-medium" />
                        </div>
                        <div>
                            <label class="block text-gray-600 text-xs mb-1">Tax Amount</label>
                            <input type="text" :value="formatCurrency(selectedInvoice.tax_amount)" readonly class="w-full border-gray-200 rounded-md bg-gray-50 px-2 py-1 text-sm text-right font-medium" />
                        </div>
                        <div>
                            <label class="block text-gray-600 text-xs mb-1">Net Amount</label>
                            <input type="text" :value="formatCurrency(selectedInvoice.net_amount)" readonly class="w-full border-gray-200 rounded-md bg-purple-100 px-2 py-1 text-sm text-right font-bold text-purple-800" />
                        </div>
                    </div>
                </div>

                <!-- Print Options -->
                <div class="border-t-2 border-purple-300 pt-4 mt-4">
                    <h4 class="text-sm font-semibold text-purple-700 mb-3 flex items-center gap-2">
                        <i class="fas fa-cog text-purple-600"></i>
                        Print Options
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 text-sm mb-1">Paper Size</label>
                            <select v-model="printOptions.paperSize" class="w-full border-gray-300 rounded-md px-3 py-2">
                                <option value="A4">A4 (210mm × 297mm)</option>
                                <option value="Letter">Letter (8.5" × 11")</option>
                                <option value="Legal">Legal (8.5" × 14")</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm mb-1">Orientation</label>
                            <select v-model="printOptions.orientation" class="w-full border-gray-300 rounded-md px-3 py-2">
                                <option value="portrait">Portrait</option>
                                <option value="landscape">Landscape</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" v-model="printOptions.includeDetails" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500" />
                                <span class="text-sm text-gray-700">Include Item Details</span>
                            </label>
                        </div>
                        <div class="md:col-span-2">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" v-model="printOptions.updatePrintAudit" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500" checked />
                                <span class="text-sm text-gray-700">Update Print Audit Trail (PT_UID, PT_DATE, PT_TIME)</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Print History (Audit Trail) -->
                <div v-if="selectedInvoice.printed_by" class="mt-4 bg-blue-50 border-l-4 border-blue-400 p-3 rounded">
                    <div class="flex items-center gap-2 mb-1">
                        <i class="fas fa-info-circle text-blue-600"></i>
                        <span class="text-sm font-semibold text-blue-800">Print History</span>
                    </div>
                    <p class="text-sm text-blue-700">
                        Last printed by <strong>{{ selectedInvoice.printed_by }}</strong> on {{ selectedInvoice.printed_date }}
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex justify-end gap-3">
                    <button
                        @click="clearSelection"
                        class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded shadow">
                        <i class="fas fa-times mr-2"></i>Clear
                    </button>
                    <button
                        @click="confirmPrint"
                        :disabled="isPrinting"
                        class="px-6 py-2 bg-purple-600 hover:bg-purple-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white rounded shadow">
                        <i class="fas fa-file-pdf mr-2"></i>
                        <span v-if="!isPrinting">Export to PDF</span>
                        <span v-else><i class="fas fa-spinner fa-spin"></i> Generating...</span>
                    </button>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="mt-6 text-center py-12">
                <i class="fas fa-search text-gray-300 text-6xl mb-4"></i>
                <p class="text-gray-500 text-lg">Search for an invoice to print</p>
                <p class="text-gray-400 text-sm mt-2">Use the search button above to find an invoice</p>
            </div>
        </div>

        <!-- Invoice Table Modal -->
        <InvoiceTableModal
            :open="showInvoiceTable"
            :initial-query="tableQuery"
            @close="showInvoiceTable = false"
            @select="selectInvoice"
            @zoom="handleZoom"
        />

        <!-- Confirmation Modal (CPS Style) -->
        <div v-if="showConfirmation" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl p-6 w-96 animate-scaleIn">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-purple-100 p-3 rounded-full">
                        <i class="fas fa-file-pdf text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Confirm Export</h3>
                </div>
                <p class="text-gray-700 mb-2">Export invoice <strong>{{ selectedInvoice?.invoice_no }}</strong> to PDF?</p>
                <p class="text-sm text-gray-500 mb-6">
                    <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                    This will update the print audit trail (PT_UID, PT_DATE, PT_TIME)
                </p>
                <div class="flex justify-end gap-3">
                    <button
                        @click="executePrint"
                        :disabled="isPrinting"
                        class="px-6 py-2 bg-purple-600 hover:bg-purple-700 disabled:bg-gray-400 text-white rounded shadow">
                        <span v-if="!isPrinting">OK</span>
                        <span v-else><i class="fas fa-spinner fa-spin"></i></span>
                    </button>
                    <button
                        @click="showConfirmation = false"
                        :disabled="isPrinting"
                        class="px-6 py-2 bg-gray-200 hover:bg-gray-300 disabled:bg-gray-400 text-gray-800 rounded shadow">
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
// cspell:ignore axios jspdf
import axios from 'axios';
import jsPDF from 'jspdf';
import 'jspdf-autotable';
import Swal from 'sweetalert2';

// Toast notification helper
const swalToast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
});

const toast = {
    success: (msg) => swalToast.fire({ icon: 'success', title: msg }),
    error: (msg) => swalToast.fire({ icon: 'error', title: msg }),
    info: (msg) => swalToast.fire({ icon: 'info', title: msg })
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
const isPrinting = ref(false);

// Print options
const printOptions = ref({
    paperSize: 'A4',
    orientation: 'portrait',
    includeDetails: true,
    updatePrintAudit: true
});

// Derived flag: whether selected invoice is cancelled
const isSelectedInvoiceCancelled = computed(() => {
    const inv = selectedInvoice.value;
    if (!inv) return false;

    let rawStatus = String(inv.inv_sts || inv.status || '').trim().toUpperCase();
    if (!rawStatus || rawStatus === '0') {
        const statusCandidate = Object.values(inv || {}).find((v) =>
            typeof v === 'string' && /CANCEL/i.test(v.trim())
        );
        if (statusCandidate) {
            rawStatus = statusCandidate.trim().toUpperCase();
        }
    }

    return rawStatus === 'CANCEL' || rawStatus === 'CANCELLED';
});

// Format currency helper
const formatCurrency = (value) => {
    if (!value) return '0.00';
    return parseFloat(value).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
};

// Format date helper (YYYY-MM-DD -> DD/MM/YYYY)
const formatDateForPrint = (value) => {
    if (!value) return '';
    const str = String(value).trim();
    if (str.includes('/')) {
        return str;
    }
    if (str.length === 10 && str[4] === '-' && str[7] === '-') {
        const [y, m, d] = str.split('-');
        return `${d}/${m}/${y}`;
    }
    return str;
};

// Convert number to Indonesian words (Terbilang)
const numberToWords = (num) => {
    if (!num || num === 0) return 'NOL RUPIAH';

    const units = ['', 'SATU', 'DUA', 'TIGA', 'EMPAT', 'LIMA', 'ENAM', 'TUJUH', 'DELAPAN', 'SEMBILAN'];
    const teens = ['SEPULUH', 'SEBELAS', 'DUA BELAS', 'TIGA BELAS', 'EMPAT BELAS', 'LIMA BELAS',
                   'ENAM BELAS', 'TUJUH BELAS', 'DELAPAN BELAS', 'SEMBILAN BELAS'];

    const convertHundreds = (n) => {
        if (n === 0) return '';
        if (n < 10) return units[n];
        if (n >= 10 && n < 20) return teens[n - 10];
        if (n < 100) {
            const tens = Math.floor(n / 10);
            const ones = n % 10;
            return (tens === 1 ? 'SE' : units[tens]) + ' PULUH' + (ones > 0 ? ' ' + units[ones] : '');
        }

        const hundreds = Math.floor(n / 100);
        const remainder = n % 100;
        let result = (hundreds === 1 ? 'SERATUS' : units[hundreds] + ' RATUS');
        if (remainder > 0) result += ' ' + convertHundreds(remainder);
        return result;
    };

    const convertThousands = (n) => {
        if (n === 0) return '';
        if (n < 1000) return convertHundreds(n);

        const thousands = Math.floor(n / 1000);
        const remainder = n % 1000;
        let result = (thousands === 1 ? 'SERIBU' : convertHundreds(thousands) + ' RIBU');
        if (remainder > 0) result += ' ' + convertHundreds(remainder);
        return result;
    };

    const convertMillions = (n) => {
        if (n === 0) return '';
        if (n < 1000000) return convertThousands(n);

        const millions = Math.floor(n / 1000000);
        const remainder = n % 1000000;
        let result = convertHundreds(millions) + ' JUTA';
        if (remainder > 0) result += ' ' + convertThousands(remainder);
        return result;
    };

    // Round to nearest integer for terbilang
    const rounded = Math.round(parseFloat(num));
    const words = convertMillions(rounded);
    return words + ' RUPIAH';
};

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
        // Fetch full invoice header details
        const res = await axios.get(`/api/invoices/${encodeURIComponent(invoice.invoice_no)}`);

        if (res.data) {
            // Set selected invoice header
            selectedInvoice.value = {
                invoice_no: res.data.invoice_no,
                invoice_date: res.data.invoice_date,
                customer_code: res.data.customer_code,
                customer_name: res.data.customer_name,
                order_mode: res.data.order_mode,
                salesperson: res.data.salesperson,
                currency: res.data.currency,
                exchange_rate: res.data.exchange_rate,
                payment_term: res.data.payment_term,
                due_date: res.data.due_date,
                tax_invoice_no: res.data.tax_invoice_no,
                status: res.data.status,
                inv_sts: res.data.inv_sts,
                total_amount: res.data.total_amount,
                tax_amount: res.data.tax_amount,
                net_amount: res.data.net_amount,
                tax_code: res.data.tax_code,
                tax_percent: res.data.tax_percent,
                remark: res.data.remark,
                second_ref: res.data.second_ref,
                so_number: res.data.so_number,
                do_number: res.data.do_number,
                quantity: res.data.quantity,
                unit_price: res.data.unit_price,
                po_number: res.data.po_number,
                model: res.data.model,
                printed_by: res.data.printed_by,
                printed_date: res.data.printed_date,
                // Detailed items (Main + Fit) for printing, loaded below
                items: []
            };

            // Try to load detailed items (Main + Fit) for this invoice
            try {
                const itemsRes = await axios.get(`/api/invoices/${encodeURIComponent(invoice.invoice_no)}/with-items`);
                if (itemsRes.data && Array.isArray(itemsRes.data.items)) {
                    selectedInvoice.value.items = itemsRes.data.items;
                    console.log('✅ Loaded invoice items for print:', selectedInvoice.value.items.length);
                }
            } catch (err) {
                console.warn('⚠️ Failed to load invoice items for print, using header only:', err);
            }

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
};

// Confirm print (show confirmation modal)
const confirmPrint = () => {
    if (!selectedInvoice.value) {
        toast.error('No invoice selected');
        return;
    }

    showConfirmation.value = true;
};

// Execute print/export (after confirmation)
const executePrint = async () => {
    if (!selectedInvoice.value) {
        toast.error('No invoice selected');
        return;
    }

    isPrinting.value = true;

    try {
        // Generate PDF
        const pdfDoc = generateInvoicePDF(selectedInvoice.value);

        // Update print audit trail if enabled
        if (printOptions.value.updatePrintAudit) {
            await updatePrintAudit(selectedInvoice.value.invoice_no);
        }

        // Download PDF file
        pdfDoc.save(`Invoice_${selectedInvoice.value.invoice_no}.pdf`);

        toast.success('PDF exported successfully!');

        // Close confirmation modal
        showConfirmation.value = false;

        // Reload invoice to show updated print audit
        if (printOptions.value.updatePrintAudit) {
            const res = await axios.get(`/api/invoices/${encodeURIComponent(selectedInvoice.value.invoice_no)}`);
            if (res.data) {
                selectedInvoice.value.printed_by = res.data.printed_by;
                selectedInvoice.value.printed_date = res.data.printed_date;
            }
        }

    } catch (e) {
        console.error('❌ Error exporting PDF:', e);
        toast.error('Failed to export PDF: ' + (e.response?.data?.error || e.message));
    } finally {
        isPrinting.value = false;
    }
};

// Generate Invoice PDF using jsPDF (CPS-Compatible Layout)
const generateInvoicePDF = (invoice) => {
    const doc = new jsPDF({
        orientation: printOptions.value.orientation,
        unit: 'mm',
        format: printOptions.value.paperSize
    });

    // ==== COMPANY HEADER (Top Left) ====
    doc.setFont('times', 'normal')
    doc.setFontSize(20)
    doc.text('PT. MULTIBOX INDAH', 5, 15);

    doc.setFont('courier', 'normal')
    doc.setFontSize(10)
    doc.text('NPWP  : 01.495.224.6-415.000', 5, 20);
    doc.text('', 5, 25); // Empty line
    doc.text('Jl. Raya Cikande - Rangkas Bitung KM. 6', 5, 30);
    doc.text('Desa Kareo Kec. Javilan', 5, 34);
    doc.text('Serang - Banten 42180', 5, 38);
    doc.text('Phone  : 0254-404060', 5, 42);
    doc.text('Fax    : 021-8252690', 5, 46);

    // ==== INVOICE TITLE (Center) ====
    doc.setFont('times', 'bold')
    doc.setFontSize(16)
    doc.text('INVOICE', 105, 60, { align: 'center' });

    // ==== INVOICE INFO (Top Right) ====
    doc.setFont('courier', 'normal')
    doc.setFontSize(10)
    // Shift header info block slightly to the right
    const rightX = 145;

    let rawStatus = String(invoice.inv_sts || invoice.status || '').trim().toUpperCase();
    if (!rawStatus || rawStatus === '0') {
        const statusCandidate = Object.values(invoice || {}).find(v =>
            typeof v === 'string' && /CANCEL/i.test(v.trim())
        );
        if (statusCandidate) {
            rawStatus = statusCandidate.trim().toUpperCase();
        }
    }
    const isCancelled = rawStatus === 'CANCEL' || rawStatus === 'CANCELLED';

    if (isCancelled) {
        doc.setTextColor(255, 0, 0);
        doc.setFontSize(14);
        doc.setFont('courier', 'bold');
        // Keep CANCEL aligned near the right margin while header block shifts right
        doc.text('CANCEL', rightX + 40, 11, { align: 'right' });
        doc.setTextColor(0, 0, 0);
        doc.setFontSize(10);
        doc.setFont('courier', 'normal');
    }

    const paymentTermText =
      invoice.payment_term !== undefined && invoice.payment_term !== null
        ? String(invoice.payment_term)
        : '';
    const exchangeRateText =
      invoice.exchange_rate !== undefined && invoice.exchange_rate !== null
        ? String(invoice.exchange_rate)
        : '';

    doc.text(`No Invoice    : ${invoice.invoice_no || ''}`, rightX, 15);
    doc.text(`Invoice Date  : ${formatDateForPrint(invoice.invoice_date)}`, rightX, 20);
    doc.text(`AR Term       : ${paymentTermText}`, rightX, 25);
    doc.text(`Tanggal JT    : ${formatDateForPrint(invoice.due_date)}`, rightX, 30);
    doc.text(`Halaman       : 01`, rightX, 35);
    doc.text(`Kurs          : ${invoice.currency || 'IDR'}`, rightX, 40);
    doc.text(`Nilai Kurs    : ${exchangeRateText}`, rightX, 45);
    doc.text(`Nomor FP      : ${invoice.tax_invoice_no || ''}`, rightX, 50);

    // ==== CUSTOMER NAME (Below Title) ====
    doc.setFont('courier', 'normal')
    doc.setFontSize(10)
    doc.text(`NAMA CUSTOMER  : ${invoice.customer_name || ''}`, 5, 70);

    // ==== ITEM TABLE HEADER ====
    doc.setFont('courier', 'bold')
    doc.setFontSize(10)

    // Separator line
    doc.setDrawColor(0, 0, 0);
    doc.setLineWidth(0.3);
    doc.line(5, 75, 200, 75);

    // Headers
    const headerY = 80;
    doc.text('Untuk Pembayaran  :', 5, headerY);
    doc.text('Qty', 120, headerY, { align: 'right' });
    doc.text('Harga', 150, headerY, { align: 'right' });
    doc.text('Jumlah', 195, headerY, { align: 'right' });

    doc.text('No.  Deskripsi', 5, headerY + 5);

    doc.line(5, headerY + 7, 200, headerY + 7);

    // ==== ITEM DETAILS (If includeDetails enabled) ====
    let currentY = headerY + 12;

    if (printOptions.value.includeDetails) {
        doc.setFont('courier', 'normal');

        // Y untuk baris Qty/Harga/Jumlah akan diselaraskan dengan baris 'Main :'
        let mainLineY = currentY;

        // Persiapan data Main & Fit dari invoice.items (jika ada)
        const rawItems = Array.isArray(invoice.items) ? invoice.items : [];

        const mainItem = rawItems.find((item) => {
            const comp = (item.comp || '').toString().trim().toLowerCase();
            return comp === 'main' || comp === '';
        });

        // Deskripsi main: PRIORITAS PART (part number), lalu PRODUCT (item_code), lalu item_desc, terakhir 'BOX'
        let mainDesc = 'BOX';
        if (mainItem) {
            const mainPart = (mainItem.part || '').toString().trim();
            const mainCode = (mainItem.item_code || '').toString().trim();

            if (mainPart) {
                mainDesc = mainPart; // contoh: 'BOX'
            } else if (mainCode) {
                mainDesc = mainCode; // contoh: 'OX BASO 4.5 KG'
            } else if (mainItem.item_desc) {
                mainDesc = mainItem.item_desc;
            }
        }

        const fitItems = rawItems
            .filter((item) => {
                const comp = (item.comp || '').toString().trim().toLowerCase();
                return comp.startsWith('fit');
            })
            .sort((a, b) => (a.line_no || 0) - (b.line_no || 0));

        // Baris 1: nomor + SO# dan PO# pada baris yang sama
        const soText = invoice.so_number || '';
        const poText = invoice.po_number || '';
        doc.text(`1  SO#  : ${soText};  PO# : ${poText}`, 5, currentY);

        // Baris berikutnya: Model (jika ada)
        const modelText = invoice.model || '';
        if (modelText) {
            currentY += 4;
            doc.text('   Model: ' + modelText, 5, currentY);
        }

        // Baris selanjutnya: Main
        currentY += 4;
        doc.text('   Main : ' + mainDesc, 5, currentY);
        // Set posisi kolom Qty/Harga/Jumlah sejajar dengan baris Main
        mainLineY = currentY;

        // Baris berikutnya: Fit1..Fit9 (jika ada)
        fitItems.forEach((item) => {
            if (currentY >= 220) return; // jangan nabrak area total

            currentY += 4;
            const compLabel = (item.comp || '').toString().trim() || 'Fit';

            const itemCode = (item.item_code || '').toString().trim();
            const itemPart = (item.part || '').toString().trim();

            let desc = '';
            if (itemPart) {
                desc = itemPart; // contoh: 'Box B'
            } else if (itemCode) {
                desc = itemCode;
            } else {
                desc = item.item_desc || '';
            }

            doc.text(`   ${compLabel}: ` + desc, 5, currentY);
        });

        // Baris terakhir: DO# (hanya dari nomor DO yang sudah di-resolve, tanpa fallback second_ref)
        currentY += 4;
        doc.text('   DO#  : ' + (invoice.do_number || ''), 5, currentY);

        // Qty/Harga/Jumlah hanya di baris nomor 1 (Main)
        const qtyNumber =
          invoice.quantity !== undefined && invoice.quantity !== null
            ? Number(invoice.quantity)
            : 0;
        const qty = qtyNumber.toLocaleString('en-US');
        const unitPrice = formatCurrency(invoice.unit_price ?? 0);
        const amount = formatCurrency(invoice.total_amount ?? 0);

        doc.text(qty + 'Pcs', 126, mainLineY, { align: 'right' });
        doc.text(unitPrice, 154, mainLineY, { align: 'right' });
        doc.text(amount, 203, mainLineY, { align: 'right' });
    }

    // ==== TOTALS SECTION ====
    const totalsY = 230;

    // Separator line before totals
    doc.setLineWidth(0.3);
    doc.line(110, totalsY - 5, 200, totalsY - 5);

    // Subtotal row
    doc.setFont('courier', 'normal')
    doc.setFontSize(10)
    doc.text('Subtotal', 130, totalsY);
    doc.text(':', 155, totalsY);
    doc.text(formatCurrency(invoice.total_amount || 0), 190, totalsY, { align: 'right' });

    // PPn row
    doc.text('PPn%', 130, totalsY + 5);
    doc.text(':', 155, totalsY + 5);
    doc.text(formatCurrency(invoice.tax_amount || 0), 190, totalsY + 5, { align: 'right' });

    // Total row (bold)
    doc.setFont('courier', 'bold')
    doc.text('Total', 130, totalsY + 10);
    doc.text(':', 155, totalsY + 10);
    doc.text(formatCurrency(invoice.net_amount || 0), 190, totalsY + 10, { align: 'right' });

    // ==== PAYMENT INFO (Amount in words, neatly below totals) ====
    const paymentY = totalsY + 18; // Push below totals block like CPS layout

    doc.setFont('courier', 'bold')
    doc.setFontSize(10)
    doc.text('JUMLAH:', 5, paymentY);

    // Convert net amount to words (Terbilang) and render on lines under the label
    doc.setFont('courier', 'normal')
    const amountInWords = numberToWords(invoice.net_amount || 0);

    // Wrap text within the lower area width
    const maxWidth = 175; // Safe full width below totals
    const lines = doc.splitTextToSize(amountInWords, maxWidth);

    let lineY = paymentY + 4; // Start a bit under the JUMLAH label
    lines.forEach((line) => {
        doc.text(line, 5, lineY);
        lineY += 4;
    });

    // Payment instructions
    const paymentInfoY = lineY + 6; // Start after terbilang text
    doc.setFont('courier', 'normal')
    doc.setFontSize(10)
    doc.text('Pembayaran dengan CHEQUE/BILYET GIRO', 5, paymentInfoY);
    doc.text('harap dicantumkan atas nama PT. MULTIBOX INDAH', 5, paymentInfoY + 5);

    // Bank accounts
    doc.setFontSize(10)
    doc.text('Bank BCA No. Rekening      : 0068584488', 5, paymentInfoY + 12);
    doc.text('Bank MANDIRI No. Rekening  : 118.00.0489970.3', 5, paymentInfoY + 17);
    doc.text('Bank HSBC No. Rekening     : 900.025487075', 5, paymentInfoY + 22);

    // ==== SIGNATURE SECTION (Right side) ====
    const sigY = 260;
    doc.setFont('courier', 'normal')
    doc.setFontSize(10)
    const signDate = invoice.invoice_date
        ? formatDateForPrint(invoice.invoice_date)
        : new Date().toLocaleDateString('id-ID');
    doc.text('Banten, ' + signDate, 140, sigY);
    doc.text('PT. MULTIBOX INDAH', 140, sigY + 5);

    // Signature name (fixed as EVA KENPI to match CPS layout) – sejajar dengan PT. MULTIBOX INDAH
    doc.setFont('courier', 'bold')
    const signerName = 'EVA KENPI';
    // Gunakan X yang sama (140) tanpa align center agar kiri teks sejajar
    doc.text(signerName.toUpperCase(), 150, sigY + 34);

    // ==== FOOTER ====
    doc.setFont('courier', 'normal')
    doc.setFontSize(10)
    doc.text('Akhir dari halaman', 5, 288);
    doc.text('Tanggal Print  : ' + new Date().toLocaleDateString('id-ID'), 5, 293);

    // Page number
    doc.text('1 of 1', 200, 290, { align: 'right' });

    return doc;
};

// Update print audit trail
const updatePrintAudit = async (invoiceNo) => {
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        const res = await axios.post(`/api/invoices/${encodeURIComponent(invoiceNo)}/print`, {}, {
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });

        console.log('✅ Print audit updated:', res.data);
    } catch (e) {
        console.warn('⚠️ Failed to update print audit:', e);
        // Don't throw - print can still succeed even if audit fails
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
    box-shadow: 0 0 0 3px rgba(147, 51, 234, .3);
    border-color: #9333ea;
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

