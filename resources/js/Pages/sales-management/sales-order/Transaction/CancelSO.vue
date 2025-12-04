<template>
  <AppLayout header="Cancel SO">
    <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
      <div class="max-w-5xl mx-auto bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
      <!-- Header with controls -->
      <div class="bg-blue-600 px-6 py-4 border-b border-blue-700 text-white">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <i class="fas fa-times-circle text-2xl text-red-600"></i>
            <div>
              <h1 class="text-xl font-semibold text-white">Cancel SO</h1>
              <p class="text-xs text-blue-100">Cancel existing sales orders</p>
            </div>
          </div>
          <div class="flex items-center space-x-2">
            <button
              @click="refreshPage"
              class="p-2 text-blue-600 hover:bg-blue-100 rounded-full transition-colors"
              title="Refresh (F5)"
            >
              <i class="fas fa-sync-alt"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Main Form Content -->
      <div class="p-6">
        <!-- Period and Sales Order Information -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
          <!-- Period Information -->
          <div class="space-y-4">
            <div class="bg-gray-50 rounded-lg p-4">
              <h3 class="text-sm font-medium text-gray-700 mb-3">Period Information</h3>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Current Period:</label>
                  <div class="flex items-center space-x-2">
                    <input
                      v-model="currentPeriod.month"
                      type="number"
                      min="1"
                      max="12"
                      class="w-16 px-2 py-1 border border-gray-300 rounded text-sm bg-gray-100 text-gray-600"
                      readonly
                      disabled
                    >
                    <input
                      v-model="currentPeriod.year"
                      type="number"
                      class="w-20 px-2 py-1 border border-gray-300 rounded text-sm bg-gray-100 text-gray-600"
                      readonly
                      disabled
                    >
                    <span class="text-xs text-gray-500">mm/yyyy</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Sales Order Search -->
          <div class="space-y-4">
            <div class="bg-gray-50 rounded-lg p-4">
              <h3 class="text-sm font-medium text-gray-700 mb-3">Sales Order Search</h3>
              <div class="space-y-3">
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Sales Order#:</label>
                  <div class="flex items-center space-x-2">
                    <input
                      v-model="searchForm.soNumber"
                      type="text"
                      placeholder="Enter SO Number"
                      class="flex-1 px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      @keyup.enter="searchSalesOrder"
                    >
                    <button
                      @click="openSalesOrderModal"
                      class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors"
                      title="Search SO"
                    >
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sales Order Details -->
        <div v-if="selectedSO" class="bg-gray-50 rounded-lg p-6 mb-6">
          <h3 class="text-sm font-medium text-gray-700 mb-4">Sales Order Details</h3>

          <!-- Form Layout matching the image -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-4">
              <!-- Current Period -->
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Current Period:</label>
                  <div class="flex items-center space-x-2">
                    <input
                      v-model="currentPeriod.month"
                      type="number"
                      class="w-16 px-2 py-1 border border-gray-300 rounded text-sm bg-gray-100 text-gray-600"
                      readonly
                    >
                    <input
                      v-model="currentPeriod.year"
                      type="number"
                      class="w-20 px-2 py-1 border border-gray-300 rounded text-sm bg-gray-100 text-gray-600"
                      readonly
                    >
                    <span class="text-xs text-gray-500">mm/yyyy</span>
                  </div>
                </div>
              </div>

              <!-- Sales Order# -->
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Sales Order#:</label>
                  <input
                    v-model="selectedSO.soNumber"
                    type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded text-sm bg-gray-100"
                    readonly
                  >
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Seq:</label>
                  <input
                    v-model="selectedSO.seq"
                    type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded text-sm bg-gray-100"
                    readonly
                  >
                </div>
              </div>

              <!-- Customer -->
              <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Customer:</label>
                <input
                  v-model="selectedSO.customerName"
                  type="text"
                  class="w-full px-3 py-2 border border-gray-300 rounded text-sm bg-gray-100"
                  readonly
                >
              </div>

              <!-- M/Card Seq# -->
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">M/Card Seq#:</label>
                  <input
                    v-model="selectedSO.mcardSeq"
                    type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded text-sm bg-gray-100"
                    readonly
                  >
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Sheet Breadth:</label>
                  <input
                    v-model="selectedSO.sheetBreadth"
                    type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded text-sm bg-gray-100"
                    readonly
                  >
                </div>
              </div>

              <!-- Order Mode -->
              <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Order Mode:</label>
                <input
                  v-model="selectedSO.orderMode"
                  type="text"
                  class="w-full px-3 py-2 border border-gray-300 rounded text-sm bg-gray-100"
                  readonly
                >
              </div>

              <!-- Salesperson -->
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Salesperson:</label>
                  <input
                    v-model="selectedSO.salesperson"
                    type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded text-sm bg-gray-100"
                    readonly
                  >
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Name:</label>
                  <input
                    v-model="selectedSO.salespersonName"
                    type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded text-sm bg-gray-100"
                    readonly
                  >
                </div>
              </div>

              <!-- Product -->
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Product:</label>
                  <input
                    v-model="selectedSO.product"
                    type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded text-sm bg-gray-100"
                    readonly
                  >
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Description:</label>
                  <input
                    v-model="selectedSO.productDescription"
                    type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded text-sm bg-gray-100"
                    readonly
                  >
                </div>
              </div>

              <!-- Order Status -->
              <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Order Status:</label>
                <input
                  v-model="selectedSO.orderStatus"
                  type="text"
                  class="w-full px-3 py-2 border border-gray-300 rounded text-sm bg-gray-100"
                  readonly
                >
              </div>

              <!-- Cust P/Order# -->
              <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Cust P/Order#:</label>
                <input
                  v-model="selectedSO.customerPO"
                  type="text"
                  class="w-full px-3 py-2 border border-gray-300 rounded text-sm bg-gray-100"
                  readonly
                >
              </div>

              <!-- P/Order Date -->
              <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">P/Order Date:</label>
                <input
                  v-model="selectedSO.porderDate"
                  type="text"
                  class="w-full px-3 py-2 border border-gray-300 rounded text-sm bg-gray-100"
                  readonly
                >
              </div>
            </div>

            <!-- Right Column - Cancel Information -->
            <div class="space-y-4">
              <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-red-700 mb-3">Cancellation Information</h4>

                <!-- Cancel Date -->
                <div class="mb-4">
                  <label class="block text-xs font-medium text-gray-600 mb-1">Cancel Date:</label>
                  <input
                    v-model="cancelDate"
                    type="date"
                    class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500"
                  >
                </div>

                <!-- Cancelled By -->
                <div class="mb-4">
                  <label class="block text-xs font-medium text-gray-600 mb-1">Cancelled By:</label>
                  <input
                    v-model="cancelledBy"
                    type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded text-sm bg-gray-100"
                    readonly
                  >
                </div>

                <!-- Status Information -->
                <div class="mb-4">
                  <label class="block text-xs font-medium text-gray-600 mb-1">Current Status:</label>
                  <div class="px-3 py-2 bg-white border border-gray-300 rounded text-sm">
                    <span :class="getStatusClass(selectedSO.status)">{{ selectedSO.status }}</span>
                  </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-3 mt-6">
                  <button
                    @click="clearSelection"
                    class="px-4 py-2 bg-gray-500 text-white text-sm rounded hover:bg-gray-600 transition-colors"
                  >
                    <i class="fas fa-times mr-1"></i>
                    Clear
                  </button>
                  <button
                    @click="cancelSalesOrder"
                    class="px-4 py-2 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition-colors"
                    :disabled="selectedSO.status === 'Cancelled'"
                  >
                    <i class="fas fa-ban mr-1"></i>
                    Cancel SO
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- No Results Message -->
        <div v-else-if="searchPerformed && !selectedSO" class="text-center py-8">
          <div class="text-gray-500">
            <i class="fas fa-search text-3xl mb-2"></i>
            <p>No sales order found with the specified number.</p>
          </div>
        </div>
      </div>
      </div>
    </div>

    <!-- Sales Order Table Modal -->
    <SalesOrderTableModal
      :is-open="showSalesOrderTableModal"
      :customer-data="{ code: 'ALL' }"
      @close="showSalesOrderTableModal = false"
      @select="handleSalesOrderSelect"
    />
  </AppLayout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import SalesOrderTableModal from '@/Components/SalesOrderTableModal.vue';

export default {
    name: 'CancelSO',
    components: {
        AppLayout,
        SalesOrderTableModal
    },
    data() {
        return {
            currentPeriod: {
                month: new Date().getMonth() + 1,
                year: new Date().getFullYear()
            },
            searchForm: {
                soNumber: ''
            },
            selectedSO: null,
            cancelDate: new Date().toISOString().split('T')[0],
            cancelledBy: 'SYSTEM', // This should be populated from auth user
            searchPerformed: false,
            showSalesOrderTableModal: false,
            selectedRowIndex: -1,
            salesOrderList: [
                {
                    id: 1,
                    SO_Num: '11-2025-00145',
                    PO_Num: 'PO/ABC/2025/11/00234',
                    AC_Num: '000125',
                    AC_NAME: 'PT ABADI CEMERLANG MANDIRI',
                    MCS_Num: 'MC450123',
                    STS: 'Outstanding',
                    D_LOC_Num: 'WH01',
                    SO_DMY: '03/11/2025',
                    PO_DATE: '01/11/2025',
                    MODEL: 'SINGLE FACE E-275',
                    PRODUCT: 'CORRUGATED BOX',
                    // Legacy field mapping for compatibility
                    soNumber: '11-2025-00145',
                    customerPO: 'PO/ABC/2025/11/00234',
                    acNumber: '000125',
                    mcNumber: 'MC450123',
                    status: 'Outstanding',
                    location: 'WH01'
                },
                {
                    id: 2,
                    SO_Num: '11-2025-00144',
                    PO_Num: 'PO/XYZ/2025/10/00891',
                    AC_Num: '000234',
                    AC_NAME: 'PT XAVIER YUDHA ZENTRAL',
                    MCS_Num: 'MC450124',
                    STS: 'Outstanding',
                    D_LOC_Num: 'WH02',
                    SO_DMY: '02/11/2025',
                    PO_DATE: '30/10/2025',
                    MODEL: 'DOUBLE WALL B-150',
                    PRODUCT: 'PACKAGING BOX',
                    // Legacy field mapping for compatibility
                    soNumber: '11-2025-00144',
                    customerPO: 'PO/XYZ/2025/10/00891',
                    acNumber: '000234',
                    mcNumber: 'MC450124',
                    status: 'Outstanding',
                    location: 'WH02'
                },
                {
                    id: 3,
                    SO_Num: '11-2025-00143',
                    PO_Num: 'PO/DEF/2025/10/00567',
                    AC_Num: '000345',
                    AC_NAME: 'PT DELTA ENGINEERING FABRIKASI',
                    MCS_Num: 'MC450125',
                    STS: 'Confirmed',
                    D_LOC_Num: 'WH01',
                    SO_DMY: '01/11/2025',
                    PO_DATE: '28/10/2025',
                    MODEL: 'TRIPLE WALL TC-200',
                    PRODUCT: 'HEAVY DUTY BOX',
                    // Legacy field mapping for compatibility
                    soNumber: '11-2025-00143',
                    customerPO: 'PO/DEF/2025/10/00567',
                    acNumber: '000345',
                    mcNumber: 'MC450125',
                    status: 'Confirmed',
                    location: 'WH01'
                },
                {
                    id: 4,
                    SO_Num: '11-2025-00142',
                    PO_Num: 'PO/GHI/2025/10/00789',
                    AC_Num: '000456',
                    AC_NAME: 'PT GLOBAL HARMONI INDUSTRI',
                    MCS_Num: 'MC450126',
                    STS: 'Outstanding',
                    D_LOC_Num: 'WH03',
                    SO_DMY: '31/10/2025',
                    PO_DATE: '29/10/2025',
                    MODEL: 'SINGLE FACE E-125',
                    PRODUCT: 'DISPLAY BOX',
                    // Legacy field mapping for compatibility
                    soNumber: '11-2025-00142',
                    customerPO: 'PO/GHI/2025/10/00789',
                    acNumber: '000456',
                    mcNumber: 'MC450126',
                    status: 'Outstanding',
                    location: 'WH03'
                },
                {
                    id: 5,
                    SO_Num: '11-2025-00141',
                    PO_Num: 'PO/JKL/2025/10/00123',
                    AC_Num: '000567',
                    AC_NAME: 'PT JAYA KARYA LOGISTIK',
                    MCS_Num: 'MC450127',
                    STS: 'Confirmed',
                    D_LOC_Num: 'WH02',
                    SO_DMY: '30/10/2025',
                    PO_DATE: '27/10/2025',
                    MODEL: 'DOUBLE WALL BC-175',
                    PRODUCT: 'SHIPPING BOX',
                    // Legacy field mapping for compatibility
                    soNumber: '11-2025-00141',
                    customerPO: 'PO/JKL/2025/10/00123',
                    acNumber: '000567',
                    mcNumber: 'MC450127',
                    status: 'Confirmed',
                    location: 'WH02'
                }
            ],
            customerInfo: {
                name: 'NUGRAHAA ATRIA SADJANA, PT',
                model: 'SINGLE FACE E-275 / M150 - L 1200 CF',
                orderMode: 'D-Order by Customer + Delivery & Invoice to Customer',
                salesperson: 'S103',
                orderGroup: 'Sales',
                orderType: 'S1'
            },
            orderItems: [
                {
                    name: 'PCS',
                    main: 'LEF',
                    f1: '',
                    f2: '',
                    f3: '',
                    f4: '',
                    f5: '',
                    f6: '',
                    f7: '',
                    f8: '',
                    f9: ''
                },
                {
                    name: 'UNIT',
                    main: '',
                    f1: '',
                    f2: '',
                    f3: '',
                    f4: '',
                    f5: '',
                    f6: '',
                    f7: '',
                    f8: '',
                    f9: ''
                },
                {
                    name: 'ORDER',
                    main: '1,100',
                    f1: '',
                    f2: '',
                    f3: '',
                    f4: '',
                    f5: '',
                    f6: '',
                    f7: '',
                    f8: '',
                    f9: ''
                },
                {
                    name: 'NET DELIVERY',
                    main: '',
                    f1: '',
                    f2: '',
                    f3: '',
                    f4: '',
                    f5: '',
                    f6: '',
                    f7: '',
                    f8: '',
                    f9: ''
                },
                {
                    name: 'BALANCE',
                    main: '1,100',
                    f1: '',
                    f2: '',
                    f3: '',
                    f4: '',
                    f5: '',
                    f6: '',
                    f7: '',
                    f8: '',
                    f9: ''
                }
            ]
        }
    },
    methods: {
        openSalesOrderModal() {
            this.showSalesOrderTableModal = true;
        },

        async handleSalesOrderSelect(selectedOrder) {
            try {
                // Fetch complete SO data from detail API
                const response = await fetch(`/api/sales-order/${selectedOrder.soNumber}/detail`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (response.ok) {
                    const result = await response.json();
                    if (result.success) {
                        const data = result.data;

                        // Map API data to form fields
                        this.selectedSO = {
                            // Basic SO info
                            soNumber: data.so_number || selectedOrder.soNumber,
                            seq: data.so_number ? data.so_number.split('-').pop() : '',
                            customerCode: selectedOrder.acNumber,
                            customerName: data.order_info.customer_name || selectedOrder.customerName,
                            mcardSeq: data.master_card_seq || selectedOrder.mcsNumber,
                            orderMode: data.order_info.order_mode || 'D-Order by Customer + Delivery & Invoice to Customer',

                            // Salesperson info
                            salesperson: data.order_info.salesperson_code || '',
                            salespersonName: data.order_info.salesperson_name || '',

                            // Product info
                            product: data.item_details.pd || '',
                            productDescription: data.order_info.model || '',

                            // Status and dates
                            orderStatus: data.order_info.so_status || 'Outstanding',
                            customerPO: data.order_info.customer_po_number || selectedOrder.customerPo,
                            porderDate: data.order_info.po_date || '',
                            soDate: data.order_info.so_date || new Date().toLocaleDateString(),
                            status: data.order_info.so_status || 'Active',

                            // Store original data
                            _originalData: data
                        };

                        this.searchForm.soNumber = selectedOrder.soNumber;
                        this.searchPerformed = true;
                        this.showSalesOrderTableModal = false;
                    } else {
                        alert('Failed to load sales order details: ' + result.message);
                    }
                } else {
                    alert('Failed to fetch sales order details');
                }
            } catch (error) {
                console.error('Error fetching SO details:', error);
                alert('Error loading sales order details');
            }
        },
        async searchSalesOrder() {
            if (!this.searchForm.soNumber.trim()) {
                alert('Please enter a Sales Order number.');
                return;
            }

            try {
                // Simulate API call - replace with actual API endpoint
                const response = await fetch(`/api/sales-orders/${this.searchForm.soNumber}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    this.selectedSO = data.data || null;
                } else {
                    this.selectedSO = null;
                    // For demo purposes, create a mock SO if not found
                    if (this.searchForm.soNumber) {
                        this.selectedSO = {
                            soNumber: this.searchForm.soNumber,
                            customerCode: 'CUST001',
                            customerName: 'Sample Customer Ltd.',
                            soDate: new Date().toLocaleDateString(),
                            status: 'Active',
                            totalAmount: 150000
                        };
                    }
                }
                this.searchPerformed = true;
            } catch (error) {
                console.error('Error searching sales order:', error);
                this.selectedSO = null;
                this.searchPerformed = true;
            }
        },

        async cancelSalesOrder() {
            if (!this.selectedSO || !this.selectedSO.soNumber) {
                alert('No sales order selected.');
                return;
            }

            if (confirm(`Are you sure you want to cancel Sales Order ${this.selectedSO.soNumber}?`)) {
                try {
                    const response = await fetch(`/api/sales-order/${this.selectedSO.soNumber}/cancel`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            cancel_reason: '',
                            cancel_date: this.cancelDate,
                            cancelled_by: this.cancelledBy
                        })
                    });

                    if (response.ok) {
                        const result = await response.json();
                        if (result.success) {
                            this.selectedSO.status = 'Cancelled';
                            this.selectedSO.orderStatus = 'Cancelled';
                            alert(`Sales Order ${this.selectedSO.soNumber} has been cancelled successfully.`);
                            this.clearSelection();
                        } else {
                            alert('Failed to cancel sales order: ' + result.message);
                        }
                    } else {
                        const errorText = await response.text();
                        alert('Error cancelling sales order: ' + errorText);
                    }
                } catch (error) {
                    console.error('Error cancelling sales order:', error);
                    alert('Error cancelling sales order: ' + error.message);
                }
            }
        },

        clearSelection() {
            this.searchForm.soNumber = '';
            this.selectedSO = null;
            this.searchPerformed = false;
        },

        refreshPage() {
            this.clearSelection();
        },

        getStatusClass(status) {
            switch (status) {
                case 'Outstanding':
                    return 'text-orange-600 font-medium';
                case 'Confirmed':
                    return 'text-green-600 font-medium';
                case 'Active':
                    return 'text-green-600 font-medium';
                case 'Cancel':
                case 'Cancelled':
                    return 'text-red-600 font-medium';
                case 'Data':
                    return 'text-blue-600 font-medium';
                case 'Completed':
                    return 'text-green-600 font-medium';
                case 'Processing':
                    return 'text-purple-600 font-medium';
                default:
                    return 'text-gray-600';
            }
        },

        formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(amount);
        }
    },

    mounted() {
        // Set up keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if (e.key === 'F5') {
                e.preventDefault();
                this.refreshPage();
            }
        });
    }
}
</script>

<style scoped>
/* Component uses Tailwind CSS classes for styling */
</style>
