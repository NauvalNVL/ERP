<template>
  <AppLayout header="Amend SO">
    <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
      <div class="max-w-5xl mx-auto bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
      <!-- Header with controls -->
      <div class="bg-blue-600 px-6 py-4 border-b border-blue-700 text-white">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <i class="fas fa-edit text-2xl text-orange-600"></i>
            <div>
              <h1 class="text-xl font-semibold text-white">Amend SO</h1>
              <p class="text-xs text-blue-100">Modify existing sales orders</p>
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
                      readonly
                    >
                    <button
                      @click="openSalesOrderTableModal"
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
          <h3 class="text-sm font-medium text-gray-700 mb-4">Sales Order Amendment</h3>

          <!-- Detailed Form Layout matching the image -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-3">
              <!-- Current Period -->
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Current Period:</label>
                  <input
                    :value="currentPeriod.month + '/' + currentPeriod.year"
                    type="text"
                    class="w-full px-2 py-1 border border-gray-300 rounded text-sm bg-gray-100"
                    readonly
                  >
                </div>
              </div>

              <!-- Sales Order# -->
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Sales Order#:</label>
                  <input
                    v-model="selectedSO.soNumber"
                    type="text"
                    class="w-full px-2 py-1 border border-gray-300 rounded text-sm bg-gray-100"
                    readonly
                  >
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Seq:</label>
                  <input
                    v-model="selectedSO.seq"
                    type="text"
                    class="w-full px-2 py-1 border border-gray-300 rounded text-sm bg-gray-100"
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
                  class="w-full px-2 py-1 border border-gray-300 rounded text-sm bg-gray-100"
                  readonly
                >
              </div>

              <!-- M/Card Seq# -->
              <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">M/Card Seq#:</label>
                <input
                  v-model="selectedSO.mcardSeq"
                  type="text"
                  class="w-full px-2 py-1 border border-gray-300 rounded text-sm bg-gray-100"
                  readonly
                >
              </div>

              <!-- Order Mode -->
              <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Order Mode:</label>
                <input
                  v-model="selectedSO.orderMode"
                  type="text"
                  class="w-full px-2 py-1 border border-gray-300 rounded text-sm bg-gray-100"
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
                    class="w-full px-2 py-1 border border-gray-300 rounded text-sm bg-gray-100"
                    readonly
                  >
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Name:</label>
                  <input
                    v-model="selectedSO.salespersonName"
                    type="text"
                    class="w-full px-2 py-1 border border-gray-300 rounded text-sm bg-gray-100"
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
                    class="w-full px-2 py-1 border border-gray-300 rounded text-sm bg-gray-100"
                    readonly
                  >
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Description:</label>
                  <input
                    v-model="selectedSO.productDescription"
                    type="text"
                    class="w-full px-2 py-1 border border-gray-300 rounded text-sm bg-gray-100"
                    readonly
                  >
                </div>
              </div>

              <!-- A/C Currency -->
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">A/C Currency:</label>
                  <input
                    v-model="selectedSO.currency"
                    type="text"
                    class="w-full px-2 py-1 border border-gray-300 rounded text-sm bg-gray-100"
                    readonly
                  >
                </div>
              </div>

              <!-- Exchange Rate -->
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Exchange Rate:</label>
                  <input
                    v-model="selectedSO.exchangeRate"
                    type="text"
                    class="w-full px-2 py-1 border border-gray-300 rounded text-sm bg-gray-100"
                    readonly
                  >
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Exchange Method:</label>
                  <input
                    v-model="selectedSO.exchangeMethod"
                    type="text"
                    class="w-full px-2 py-1 border border-gray-300 rounded text-sm bg-gray-100"
                    readonly
                  >
                </div>
              </div>
              <!-- Order Status -->
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Order Status:</label>
                  <input
                    v-model="selectedSO.orderStatus"
                    type="text"
                    class="w-full px-2 py-1 border border-gray-300 rounded text-sm bg-gray-100"
                    readonly
                  >
                </div>
              </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-3">
              <!-- Cust P/Order# -->
              <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Cust P/Order#:</label>
                <input
                  v-model="selectedSO.customerPO"
                  type="text"
                  class="w-full px-2 py-1 border border-gray-300 rounded text-sm bg-gray-100"
                  readonly
                >
              </div>

              <!-- P/Order Date -->
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">P/Order Date:</label>
                  <input
                    v-model="selectedSO.porderDate"
                    type="date"
                    class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                  >
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Mon:</label>
                  <input
                    v-model="selectedSO.month"
                    type="text"
                    class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                  >
                </div>
              </div>

              <!-- Set Quantity -->
              <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Set Quantity:</label>
                <div class="flex items-center space-x-2">
                  <input
                    v-model="selectedSO.setQuantity"
                    type="text"
                    class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                  >
                  <span class="text-xs text-gray-500">Leave blank for loose item order</span>
                </div>
              </div>

              <!-- Order Group -->
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Order Group:</label>
                  <div class="flex items-center space-x-2">
                    <input type="radio" name="orderGroup" value="Sales" v-model="selectedSO.orderGroup">
                    <label class="text-xs">Sales</label>
                    <input type="radio" name="orderGroup" value="Non-Sales" v-model="selectedSO.orderGroup">
                    <label class="text-xs">Non-Sales</label>
                  </div>
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Order Type:</label>
                  <select
                    v-model="selectedSO.orderType"
                    class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                  >
                    <option value="S1">S1-Sales (SO-Cust-Ener-FG-DU-IV)</option>
                  </select>
                </div>
              </div>

              <!-- Lot Number -->
              <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Lot Number:</label>
                <input
                  v-model="selectedSO.lotNumber"
                  type="text"
                  class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                >
              </div>

              <!-- Sales Tax -->
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Sales Tax:</label>
                  <div class="flex items-center space-x-2">
                    <input type="checkbox" v-model="selectedSO.salesTax">
                    <label class="text-xs">Tax for Y-Yes</label>
                  </div>
                </div>
              </div>

              <!-- Remark -->
              <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Remark:</label>
                <input
                  v-model="selectedSO.remark"
                  type="text"
                  class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                >
              </div>

              <!-- Instructions -->
              <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Instruction1:</label>
                <input
                  v-model="selectedSO.instruction1"
                  type="text"
                  class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                >
              </div>

              <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Instruction2:</label>
                <input
                  v-model="selectedSO.instruction2"
                  type="text"
                  class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                  placeholder="Enter instruction 2..."
                >
              </div>

              <!-- Amendment Buttons -->
              <div class="flex items-center justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                <button
                  @click="clearSelection"
                  class="px-4 py-2 bg-gray-500 text-white text-sm rounded hover:bg-gray-600 transition-colors"
                >
                  <i class="fas fa-times mr-1"></i>
                  Clear
                </button>
                <button
                  @click="openProductDesignScreen"
                  class="px-4 py-2 bg-orange-600 text-white text-sm rounded hover:bg-orange-700 transition-colors"
                >
                  <i class="fas fa-cogs mr-1"></i>
                  Product Design Screen
                </button>
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

    <!-- Product Design Screen Modal -->
    <ProductDesignScreenModal
      :show="showProductDesignModal"
      :initial-quantity="selectedSO ? Number(selectedSO.setQuantity) : 0"
      :master-card="selectedSO ? { model: selectedSO.product } : null"
      :mc-components="mcComponentsForDesign"
      :read-only-quantity="selectedSO ? Number(selectedSO.setQuantity) > 0 : false"
      :existing-component-data="productDesignComponentData"
      @close="showProductDesignModal = false"
      @save="handleProductDesignSave"
    />

    <!-- Delivery Location Modal -->
    <DeliveryLocationModal
      :show="showDeliveryLocationModal"
      :customer="selectedSO ? {
        customer_code: selectedSO.customerCode,
        customer_name: selectedSO.customerName
      } : null"
      :order-details="selectedSO && selectedSO._originalData ? {
        deliveryLocation: {
          orderBy: {
            name: selectedSO.customerName
          },
          billTo: {
            name: selectedSO.customerName,
            address: selectedSO._originalData.order_info.customer_address || ''
          },
          shipTo: {
            code: selectedSO._originalData.order_info.delivery_code || '',
            name: selectedSO._originalData.order_info.delivery_to || selectedSO.customerName,
            address: [
              selectedSO._originalData.order_info.delivery_address_1,
              selectedSO._originalData.order_info.delivery_address_2
            ].filter(Boolean).join('\n') || selectedSO._originalData.order_info.customer_address || ''
          }
        }
      } : null"
      @close="showDeliveryLocationModal = false"
      @save="handleDeliveryLocationSave"
    />

    <!-- Delivery Schedule Modal -->
    <DeliveryScheduleModal
      :show="showDeliveryScheduleModal"
      :order-details="selectedSO ? {
        so_number: selectedSO.soNumber,
        customer_name: selectedSO.customerName,
        model: selectedSO.product,
        setQuantity: selectedSO.setQuantity,
        mainQuantity: selectedSO.mainQuantity || selectedSO.setQuantity,
        productDesignQuantities: selectedSO.productDesignQuantities || {},
        isProductDesignQuantity: selectedSO.isProductDesignQuantity || false
      } : null"
      :mc-components="mcComponentsForDesign"
      @close="showDeliveryScheduleModal = false"
      @save="handleDeliveryScheduleSave"
    />
  </AppLayout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import SalesOrderTableModal from '@/Components/SalesOrderTableModal.vue';
import ProductDesignScreenModal from '@/Components/ProductDesignScreenModal.vue';
import DeliveryLocationModal from '@/Components/DeliveryLocationModal.vue';
import DeliveryScheduleModal from '@/Components/DeliveryScheduleModal.vue';

const normalizeComponentKey = (label = '') =>
  label.toString().replace(/\s+/g, '').toLowerCase();

export default {
    name: 'AmendSO',
    components: {
        AppLayout,
        SalesOrderTableModal,
        ProductDesignScreenModal,
        DeliveryLocationModal,
        DeliveryScheduleModal
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
            amendForm: {
                deliveryDate: '',
                priority: 'Normal',
                reason: '',
                amendmentDate: new Date().toISOString().split('T')[0]
            },
            amendedBy: 'Current User', // This should be populated from auth user
            searchPerformed: false,
            showSalesOrderTableModal: false,
            showProductDesignModal: false,
            showDeliveryLocationModal: false,
            showDeliveryScheduleModal: false,
            selectedRowIndex: -1,
            mcComponentsForDesign: [],
            productDesignComponentData: {}
        }
    },
    methods: {
        assignComponentDataEntry(map, name, payload) {
            if (!name) return;
            map[name] = payload;
            map[normalizeComponentKey(name)] = payload;
        },
        buildProductDesignComponentMap(soDetail) {
            const map = {};
            if (soDetail?.item_details) {
                this.assignComponentDataEntry(map, 'Main', {
                    unit: soDetail.item_details.unit || '',
                    unitPrice: soDetail.item_details.unit_price ?? null
                });
            }
            if (Array.isArray(soDetail?.fittings)) {
                soDetail.fittings.forEach((fit, idx) => {
                    if (idx > 8) return;
                    const key = `Fit ${idx + 1}`;
                    this.assignComponentDataEntry(map, key, {
                        unit: fit.unit || '',
                        unitPrice: fit.unit_price ?? null
                    });
                });
            }
            return map;
        },
        openSalesOrderTableModal() {
            this.showSalesOrderTableModal = true;
            this.selectedRowIndex = -1;
        },

        async fetchMcComponentsForDesign() {
            try {
                const so = this.selectedSO;
                const mcSeq = so && so.mcardSeq ? so.mcardSeq : null;
                const customerCode = so && so.customerCode ? so.customerCode : null;

                if (!mcSeq || !customerCode) {
                    console.warn('Cannot fetch MC components for AmendSO Product Design Screen: missing mcardSeq or customerCode', {
                        mcardSeq: mcSeq,
                        customerCode: customerCode
                    });
                    this.mcComponentsForDesign = [];
                    return;
                }

                const mcsSeqEnc = encodeURIComponent(mcSeq);
                const custEnc = encodeURIComponent(customerCode);

                console.log('Fetching MC components for AmendSO Product Design Screen:', {
                    mcSeq,
                    customerCode
                });

                const response = await fetch(`/api/update-mc/master-cards/${mcsSeqEnc}/components?customer_code=${custEnc}`, {
                    headers: {
                        Accept: 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    console.warn('Failed to fetch MC components for AmendSO design:', response.statusText);
                    this.mcComponentsForDesign = [];
                    return;
                }

                const data = await response.json();
                console.log('MC components for AmendSO design fetched:', data);

                this.mcComponentsForDesign = Array.isArray(data) ? data : [];
            } catch (error) {
                console.error('Error fetching MC components for AmendSO design:', error);
                this.mcComponentsForDesign = [];
            }
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

                            // Currency info - use data from API
                            currency: data.order_info.currency || 'IDR',
                            exchangeRate: data.order_info.exchange_rate || 1,
                            exchangeMethod: 'N/A',

                            // Analysis and status
                            orderStatus: data.order_info.so_status || 'Outstanding',

                            // Editable fields - use data from API
                            customerPO: data.order_info.customer_po_number || selectedOrder.customerPo,
                            porderDate: data.order_info.po_date || '',
                            month: data.order_info.po_date ? new Date(data.order_info.po_date).toLocaleString('default', { month: 'short' }) : '',
                            setQuantity: data.order_info.set_quantity || data.item_details.order_qty || '',
                            orderGroup: data.order_info.order_group || 'Sales',
                            orderType: data.order_info.order_type || 'S1',
                            lotNumber: data.order_info.lot_number || '',
                            salesTax: data.order_info.sales_tax || false,
                            remark: data.order_info.remark || '',
                            instruction1: data.order_info.instruction1 || '',
                            instruction2: data.order_info.instruction2 || '',

                            // Additional info
                            soDate: data.order_info.so_date || new Date().toLocaleDateString(),
                            status: data.order_info.so_status || 'Active',
                            totalAmount: 0,

                            // Product design and quantity mapping (used by DeliveryScheduleModal)
                            productDesignQuantities: {},
                            isProductDesignQuantity: false,
                            mainQuantity: 0,

                            // Store original data for update
                            _originalData: data
                        };

                        this.productDesignComponentData = this.buildProductDesignComponentMap(data);

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

        async openProductDesignScreen() {
            if (!this.selectedSO) {
                alert('Please select a sales order first.');
                return;
            }

            await this.fetchMcComponentsForDesign();
            this.showProductDesignModal = true;
        },

        handleProductDesignSave(data) {
            // Handle product design data and move to next step
            try {
                console.log('Product design data (AmendSO):', data);

                const componentQtyMap = {};
                if (Array.isArray(data.items)) {
                    data.items.forEach((item) => {
                        const qty = Number(item.quantity) || 0;
                        const name = (item.name || '').toString();
                        if (!name || qty <= 0) {
                            return;
                        }
                        componentQtyMap[name] = qty;
                    });
                }

                const totalDesignQty = Array.isArray(data.items)
                    ? data.items.reduce((sum, item) => sum + (Number(item.quantity) || 0), 0)
                    : 0;

                if (!this.selectedSO) {
                    this.selectedSO = {};
                }

                this.selectedSO.productDesignQuantities = componentQtyMap;

                // Persist latest unit/unit price selections so reopening modal shows current data
                if (Array.isArray(data.items)) {
                    const updatedMap = { ...this.productDesignComponentData };
                    data.items.forEach((item) => {
                        if (!item?.name) return;
                        this.assignComponentDataEntry(updatedMap, item.name, {
                            unit: item.unit || '',
                            unitPrice: item.unitPrice ?? null
                        });
                    });
                    this.productDesignComponentData = updatedMap;
                }

                if (totalDesignQty > 0) {
                    this.selectedSO.mainQuantity = totalDesignQty;
                    this.selectedSO.isProductDesignQuantity = true;
                } else {
                    this.selectedSO.mainQuantity = this.selectedSO.setQuantity;
                    this.selectedSO.isProductDesignQuantity = false;
                }
            } catch (error) {
                console.warn('Failed to map product design values in AmendSO:', error);
            }

            this.showProductDesignModal = false;
            this.showDeliveryLocationModal = true;
        },

        handleDeliveryLocationSave(data) {
            // Handle delivery location data and move to next step
            console.log('Delivery location data:', data);
            this.showDeliveryLocationModal = false;
            this.showDeliveryScheduleModal = true;
        },

        async handleDeliveryScheduleSave(scheduleData) {
            try {
                console.log('handleDeliveryScheduleSave called with data:', scheduleData);

                // Validate required data
                if (!scheduleData.entries || !Array.isArray(scheduleData.entries)) {
                    console.error('Validation failed: entries missing or not array');
                    alert('Delivery schedule entries are required');
                    return;
                }

                const soNumber = this.selectedSO.soNumber;

                if (!soNumber) {
                    alert('Sales Order number is required');
                    return;
                }

                // First, update the SO with amended data
                const updateData = {
                    so_number: soNumber,
                    po_date: this.selectedSO.porderDate,
                    set_quantity: this.selectedSO.setQuantity,
                    order_group: this.selectedSO.orderGroup,
                    order_type: this.selectedSO.orderType,
                    lot_number: this.selectedSO.lotNumber,
                    // Note: sales_tax field skipped - TAX column does not exist in SO table
                    // sales_tax: this.selectedSO.salesTax ? 'Y' : 'N',
                    remark: this.selectedSO.remark,
                    instruction1: this.selectedSO.instruction1,
                    instruction2: this.selectedSO.instruction2
                };

                const updateResponse = await fetch(`/api/sales-order/${soNumber}/update`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(updateData)
                });

                if (!updateResponse.ok) {
                    const errorText = await updateResponse.text();
                    throw new Error(`Failed to update SO: ${errorText}`);
                }

                const updateResult = await updateResponse.json();
                if (!updateResult.success) {
                    throw new Error(updateResult.message || 'Failed to update SO');
                }

                console.log('SO updated successfully, now saving delivery schedule...');

                // Now save the delivery schedule
                const scheduleRequestData = {
                    so_number: soNumber,
                    entries: scheduleData.entries
                };

                const scheduleResponse = await fetch('/api/sales-order/delivery-schedule', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify(scheduleRequestData)
                });

                if (!scheduleResponse.ok) {
                    const errorText = await scheduleResponse.text();
                    throw new Error(`Failed to save delivery schedule (${scheduleResponse.status}): ${errorText.slice(0, 120)}`);
                }

                const scheduleResult = await scheduleResponse.json();

                if (scheduleResult.success) {
                    console.log('Delivery schedule saved successfully');
                    alert('Sales Order amendments and delivery schedule saved successfully!');
                    this.showDeliveryScheduleModal = false;
                    this.clearSelection();
                } else {
                    throw new Error(scheduleResult.message || 'Failed to save delivery schedule');
                }
            } catch (error) {
                console.error('Error saving SO amendments:', error);
                alert('Error saving amendments: ' + (error.message || 'Unknown error'));
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

        // Removed amendSalesOrder method as it's replaced by the modal flow

        clearSelection() {
            this.searchForm.soNumber = '';
            this.selectedSO = null;
            this.amendForm = {
                deliveryDate: '',
                priority: 'Normal',
                reason: '',
                amendmentDate: new Date().toISOString().split('T')[0]
            };
            this.searchPerformed = false;
            // Close any open modals
            this.showSalesOrderTableModal = false;
            this.showProductDesignModal = false;
            this.showDeliveryLocationModal = false;
            this.showDeliveryScheduleModal = false;
        },

        refreshPage() {
            this.clearSelection();
        },

        getStatusClass(status) {
            switch (status) {
                case 'Active':
                    return 'text-green-600 font-medium';
                case 'Cancel':
                case 'Cancelled':
                    return 'text-red-600 font-medium';
                case 'Data':
                    return 'text-blue-600 font-medium';
                case 'Amended':
                    return 'text-orange-600 font-medium';
                case 'Completed':
                    return 'text-green-600 font-medium';
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
