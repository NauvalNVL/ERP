<template>
  <AppLayout header="Prepare MC SO">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
      <!-- Header with controls -->
      <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
          <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <i class="fas fa-clipboard-list text-2xl text-blue-600"></i>
            <div>
            <h1 class="text-xl font-semibold text-gray-800">Prepare MC SO</h1>
              <p class="text-xs text-gray-500">F2: Customer • F3: Master Card • F4: Calendar • Ctrl+S: Save • F5: Refresh</p>
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
              <button 
              @click="printLog" 
              class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700 transition-colors"
              title="Print SO Log (Ctrl+P)"
              >
              <i class="fas fa-print mr-1"></i>
              Print SO Log
              </button>
              <button 
              @click="printJitTracking" 
              class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700 transition-colors"
              title="Print JIT Tracking"
              >
              <i class="fas fa-print mr-1"></i>
              Print SO JIT Tracking
              </button>
            </div>
          </div>
        </div>

      <!-- Main Form Content -->
      <div class="p-6">
        <!-- Period and Customer Information -->
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
                  </div>
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Update Period:</label>
                  <div class="flex items-center space-x-2">
                    <input 
                      v-model="updatePeriod.month" 
                      type="number" 
                      min="1" 
                      max="12"
                      class="w-16 px-2 py-1 border border-gray-300 rounded text-sm"
                    >
                    <input 
                      v-model="updatePeriod.year" 
                      type="number"
                      class="w-20 px-2 py-1 border border-gray-300 rounded text-sm"
                    >
                    <span class="text-xs text-gray-500">mm/yyyy</span>
                  </div>
                </div>
              </div>
              <div class="grid grid-cols-2 gap-4 mt-3">
                  <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Forward Period:</label>
                    <div class="flex items-center space-x-2">
                      <input 
                      v-model="forwardPeriod" 
                        type="number"
                      min="1" 
                      class="w-16 px-2 py-1 border border-gray-300 rounded text-sm bg-gray-100 text-gray-600"
                      readonly
                      disabled
                      >
                    <span class="text-xs text-gray-500">Months</span>
                    </div>
                  </div>
                  <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Backward Period:</label>
                    <div class="flex items-center space-x-2">
                      <input 
                      v-model="backwardPeriod" 
                        type="number"
                      min="1" 
                      class="w-16 px-2 py-1 border border-gray-300 rounded text-sm bg-gray-100 text-gray-600"
                      readonly
                      disabled
                      >
                    <span class="text-xs text-gray-500">Months</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Last SO Order ID -->
            <div class="bg-gray-50 rounded-lg p-4">
              <h3 class="text-sm font-medium text-gray-700 mb-3">Order Information</h3>
              <div class="flex items-center space-x-2">
                <label class="text-xs font-medium text-gray-600">Last S/Order#:</label>
                <input 
                  v-model="lastSOOrder.prefix" 
                  type="text" 
                  class="w-16 px-2 py-1 border border-gray-300 rounded text-sm bg-gray-100 text-gray-600"
                  readonly
                  disabled
                >
                <input 
                  v-model="lastSOOrder.year" 
                  type="number" 
                  class="w-20 px-2 py-1 border border-gray-300 rounded text-sm bg-gray-100 text-gray-600"
                  readonly
                  disabled
                >
                <input 
                  v-model="lastSOOrder.number" 
                  type="number" 
                  class="w-24 px-2 py-1 border border-gray-300 rounded text-sm bg-gray-100 text-gray-600"
                  readonly
                  disabled
                >
                </div>
              </div>
            </div>

            <!-- Customer Information -->
              <div class="space-y-4">
            <div class="bg-gray-50 rounded-lg p-4">
              <h3 class="text-sm font-medium text-gray-700 mb-3">Customer Information</h3>
              <div class="space-y-3">
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Customer Code:</label>
                  <div class="flex items-center space-x-2">
                    <input 
                      v-model="selectedCustomer.code" 
                      type="text"
                      class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Enter customer code"
                      @blur="validateCustomer"
                    >
                    <button 
                      @click="openCustomerLookup"
                      class="p-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
                      title="Customer Lookup"
                    >
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                  <div v-if="selectedCustomer.name" class="mt-1 text-sm text-gray-600">
                    {{ selectedCustomer.name }}
                  </div>
                </div>
                
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">M/Card Seq#:</label>
                  <div class="flex items-center space-x-2">
                    <input 
                      v-model="selectedMasterCard.seq" 
                      type="text"
                      class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Enter master card sequence"
                      @blur="validateMasterCard"
                    >
                    <button 
                      @click="openMasterCardLookup"
                      class="p-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
                      title="Master Card Lookup"
                    >
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                  <div v-if="selectedMasterCard.model" class="mt-1 text-sm text-gray-600">
                    {{ selectedMasterCard.model }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sales Order Details -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6" v-if="selectedCustomer.code && selectedMasterCard.seq">
          <h3 class="text-sm font-medium text-gray-700 mb-4">Sales Order Details</h3>
          
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Order Information -->
              <div class="space-y-4">
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Order Mode:</label>
                  <select 
                    v-model="orderDetails.orderMode" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm bg-gray-100 text-gray-600"
                    disabled
                  >
                    <option value="0">0-Order by Customer + Deliver & Invoice to Customer</option>
                    <option value="1">1-Order by Customer + Deliver to Customer + Invoice to Different Address</option>
                    <option value="2">2-Order by Customer + Deliver to Different Address + Invoice to Customer</option>
                    <option value="3">3-Order by Customer + Deliver to Different Address + Invoice to Different Address</option>
                  </select>
                </div>
                  <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Product:</label>
                  <div class="flex items-center space-x-2">
                    <input 
                      v-model="orderDetails.product.code" 
                      type="text"
                      class="w-16 px-2 py-1 border border-gray-300 rounded text-sm bg-gray-100 text-gray-600"
                      readonly
                      disabled
                    >
                    <input 
                      v-model="orderDetails.product.name" 
                      type="text"
                      class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm bg-gray-100 text-gray-600"
                      readonly
                      disabled
                    >
                  </div>
                  </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                  <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Salesperson:</label>
                  <div class="flex items-center space-x-2">
                    <input 
                      v-model="orderDetails.salesperson.code" 
                      type="text"
                      class="w-16 px-2 py-1 border border-gray-300 rounded text-sm bg-gray-100 text-gray-600"
                      readonly
                      disabled
                    >
                    <input 
                      v-model="orderDetails.salesperson.name" 
                      type="text" 
                      class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm bg-gray-100 text-gray-600"
                      readonly
                      disabled
                    >
                  </div>
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">A/C Currency:</label>
                  <input 
                    v-model="orderDetails.currency" 
                    type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm bg-gray-100 text-gray-600"
                    readonly
                    disabled
                  >
                </div>
                </div>

              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Exchange Rate:</label>
                  <input 
                    v-model="orderDetails.exchangeRate" 
                    type="number" 
                    step="0.000001" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm bg-gray-100 text-gray-600"
                    readonly
                    disabled
                  >
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Exchange Method:</label>
                  <select 
                    v-model="orderDetails.exchangeMethod" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm bg-gray-100 text-gray-600"
                    disabled
                  >
                    <option value="N/A">N/A</option>
                    <option value="Spot Rate">Spot Rate</option>
                    <option value="Forward Rate">Forward Rate</option>
                    <option value="Fixed Rate">Fixed Rate</option>
                  </select>
                </div>
              </div>

              <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Cust.P/Order#:</label>
                <input 
                  v-model="orderDetails.customerPOrder" 
                  type="text" 
                  class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm bg-gray-100 text-gray-600"
                  readonly
                  disabled
                >
              </div>

              <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">P/Order Date:</label>
                <div class="flex items-center space-x-2">
                    <input 
                    ref="pOrderDateInput"
                    v-model="orderDetails.pOrderDate" 
                      type="date"
                    :class="[
                      'flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm bg-gray-100 text-gray-600',
                      { 'calendar-explicitly-opened': calendarExplicitlyOpened }
                    ]"
                    @change="updateDayOfWeek"
                    @focus="handleDateInputFocus"
                    @blur="handleDateInputBlur"
                    readonly
                    disabled
                    >
                    <button 
                      @click.prevent="openCalendar"
                    :disabled="calendarLoading"
                    class="p-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:bg-gray-400 disabled:cursor-not-allowed calendar-button"
                      title="Open Calendar (F4 or click input field)"
                      type="button"
                    >
                    <i v-if="calendarLoading" class="fas fa-spinner fa-spin"></i>
                    <i v-else class="fas fa-calendar-alt"></i>
                    </button>
                  <span class="text-xs text-gray-500 min-w-[35px]">{{ dayOfWeek }}</span>
                </div>
                  </div>
                </div>

            <!-- Order Configuration -->
            <div class="space-y-4">
                <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Set Quantity:</label>
                <div class="flex items-center space-x-2">
                  <input 
                    v-model="orderDetails.setQuantity" 
                    type="text" 
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Leave blank for loose item order">
                  </div>
                </div>

                <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Order Group:</label>
                <div class="flex items-center space-x-4">
                    <label class="flex items-center">
                    <input 
                      v-model="orderDetails.orderGroup" 
                      type="radio" 
                      value="Sales" 
                      class="mr-2 border-gray-300 text-blue-600 focus:ring-blue-500"
                      @change="handleOrderGroupChange"
                    >
                    <span class="text-sm text-gray-700">Sales</span>
                    </label>
                    <label class="flex items-center">
                    <input 
                      v-model="orderDetails.orderGroup" 
                      type="radio" 
                      value="Non-Sales" 
                      class="mr-2 border-gray-300 text-blue-600 focus:ring-blue-500"
                      @change="handleOrderGroupChange"
                    >
                    <span class="text-sm text-gray-700">Non-Sales</span>
                    </label>
                  </div>
                </div>

                <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Order Type:</label>
                  <select 
                  v-model="orderDetails.orderType" 
                  class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  @change="handleOrderTypeChange"
                >
                  <option 
                    v-for="orderType in availableOrderTypes"
                    :key="orderType.code"
                    :value="orderType.code"
                  >
                    {{ orderType.label }}
                  </option>
                  </select>
                  <div v-if="selectedOrderTypeDescription" class="mt-1 text-xs text-gray-500 italic">
                    {{ selectedOrderTypeDescription }}
                  </div>
                  
                  <!-- Workflow Steps Display -->
                  <div v-if="currentOrderTypeConfig && currentOrderTypeConfig.workflow" class="mt-3 p-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200 shadow-sm">
                    <div class="text-xs font-semibold text-blue-900 mb-2 flex items-center">
                      <i class="fas fa-route mr-1"></i>
                      Workflow Process:
                    </div>
                    <div class="flex items-center space-x-2 text-xs flex-wrap gap-y-1">
                      <template v-for="(step, index) in getWorkflowSteps()" :key="step.code">
                        <div class="flex items-center px-2 py-1 bg-white rounded-md border border-blue-300 text-blue-800 font-medium shadow-sm">
                          <i :class="step.icon + ' mr-1 text-blue-600'"></i>
                          {{ step.name }}
                        </div>
                        <i v-if="index < getWorkflowSteps().length - 1" class="fas fa-chevron-right text-blue-500"></i>
                      </template>
                    </div>
                    
                    <!-- Special Indicators -->
                    <div class="mt-2 flex items-center space-x-3 text-xs">
                      <div v-if="currentOrderTypeConfig.isKanban" class="flex items-center text-orange-600">
                        <i class="fas fa-clock mr-1"></i>
                        <span class="font-medium">JIT/Kanban Mode</span>
                      </div>
                      <div v-if="currentOrderTypeConfig.skipCorrugator" class="flex items-center text-purple-600">
                        <i class="fas fa-forward mr-1"></i>
                        <span class="font-medium">Skip Corrugator</span>
                      </div>
                      <div v-if="currentOrderTypeConfig.corrugatorOnly" class="flex items-center text-green-600">
                        <i class="fas fa-cog mr-1"></i>
                        <span class="font-medium">Corrugator Only</span>
                      </div>
                      <div v-if="currentOrderTypeConfig.requiresInvoice" class="flex items-center text-blue-600">
                        <i class="fas fa-file-invoice-dollar mr-1"></i>
                        <span class="font-medium">With Invoice</span>
                      </div>
                      <div v-else class="flex items-center text-gray-600">
                        <i class="fas fa-ban mr-1"></i>
                        <span class="font-medium">No Invoice</span>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                  <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">
                    Sales Tax:
                    <span class="text-xs text-gray-400 font-normal">Tick for Y-Yes</span>
                  </label>
                  <div class="flex items-center space-x-2">
                    <input 
                      v-model="orderDetails.salesTax" 
                      type="checkbox" 
                      class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                      title="Tick for Y-Yes"
                    >
                    <span class="text-sm text-gray-700">
                      {{ orderDetails.salesTax ? 'Y-Yes' : 'N-No' }}
                    </span>
                  </div>
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Lot Number:</label>
                  <input 
                    v-model="orderDetails.lotNumber" 
                    type="text" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm bg-gray-100 text-gray-600"
                    placeholder="Enter lot number"
                    readonly
                    disabled
                  >
                </div>
              </div>

              <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Remark:</label>
                  <textarea 
                  v-model="orderDetails.remark" 
                    rows="2"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Enter remark"
                  ></textarea>
                </div>

                <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Instruction1:</label>
                  <textarea 
                  v-model="orderDetails.instruction1" 
                    rows="2"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Enter instruction 1"
                  ></textarea>
                </div>

                <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Instruction2:</label>
                  <textarea 
                  v-model="orderDetails.instruction2" 
                    rows="2"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Enter instruction 2"
                  ></textarea>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex justify-center space-x-4 mt-6">
                <button 
                  @click="openProductDesignScreen"
              class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center disabled:bg-gray-400 disabled:cursor-not-allowed"
              :disabled="!canProceed"
                >
              <i class="fas fa-cogs mr-2"></i>
              Continue to Product Design
                </button>
                <button 
              @click="openDeliveryLocation" 
              class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center disabled:bg-gray-400 disabled:cursor-not-allowed"
              :disabled="!canProceed"
            >
              <i class="fas fa-truck mr-2"></i>
              Set Delivery Location
                </button>
                <button 
              @click="createSalesOrder" 
              class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors flex items-center disabled:bg-gray-400 disabled:cursor-not-allowed"
              :disabled="!canProceed"
            >
              <i class="fas fa-save mr-2"></i>
              Create Sales Order
                </button>
              </div>
              
              <!-- Form validation summary -->
              <div v-if="!canProceed" class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex items-center">
                  <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                  <span class="text-sm text-yellow-800">
                    Please complete the required fields to proceed:
                  </span>
                </div>
                <ul class="text-xs text-yellow-700 mt-2 ml-6 list-disc">
                  <li v-if="!selectedCustomer.code">Select a customer account</li>
                  <li v-if="!selectedMasterCard.seq">Select a master card sequence</li>
                </ul>
              </div>
                </div>
              </div>
            </div>

    <!-- Customer Lookup Modal -->
    <CustomerAccountModal 
      :show="showCustomerModal" 
      @close="showCustomerModal = false" 
          @select="selectCustomer"
      :initial-sort-by="'customer_code'"
      :initial-status-filter="['Active']"
    />

    <!-- Master Card Lookup Modal -->
    <MasterCardSearchSelectModal 
      :show="showMasterCardModal" 
      @close="showMasterCardModal = false" 
      @select-mc="selectMasterCard"
      :customer-code="selectedCustomer.code"
      :initial-sort-column="'mc_seq'"
      :initial-filter-status="{ active: true, obsolete: false, pending: false }"
    />

    <!-- Product Design Screen Modal -->
    <ProductDesignScreenModal 
      :show="showProductDesignModal" 
      @close="showProductDesignModal = false" 
      @save="saveProductDesign"
          :master-card="selectedMasterCard"
          :customer="selectedCustomer"
    />

    <!-- Delivery Location Modal -->
    <DeliveryLocationModal 
      :show="showDeliveryLocationModal" 
      @close="showDeliveryLocationModal = false" 
      @save="saveDeliveryLocation"
          :customer="selectedCustomer"
      :order-details="orderDetails"
    />

    <!-- Delivery Schedule Modal -->
    <DeliveryScheduleModal 
      :show="showDeliveryScheduleModal" 
      @close="showDeliveryScheduleModal = false" 
      @save="saveDeliverySchedule"
      :order-details="orderDetails"
    />
  </AppLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted, watch, onUnmounted, nextTick } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import CustomerAccountModal from '@/Components/CustomerAccountModal.vue'
import MasterCardSearchSelectModal from '@/Components/MasterCardSearchSelectModal.vue'
import ProductDesignScreenModal from '@/Components/ProductDesignScreenModal.vue'
import DeliveryScheduleModal from '@/Components/DeliveryScheduleModal.vue'
import DeliveryLocationModal from '@/Components/DeliveryLocationModal.vue'
import { useToast } from '@/Composables/useToast'

const { success, error } = useToast()

// Period Information
const currentPeriod = reactive({
  month: 8,
  year: 2025
})

const updatePeriod = reactive({
  month: 8,
  year: 2025
})

const forwardPeriod = ref(1)
const backwardPeriod = ref(1)

// Last SO Order Information
const lastSOOrder = reactive({
  prefix: '5',
  year: 2019,
  number: 640
})

// Customer Information
const selectedCustomer = reactive({
  code: '',
  name: '',
  address: '',
  salesperson: '',
  currency: 'IDR'
})

// Master Card Information
const selectedMasterCard = reactive({
  seq: '',
  model: '',
  status: '',
  partNo: '',
  compNo: '',
  pDesign: ''
})

// Order Details
const orderDetails = reactive({
  orderMode: '0',
  product: {
    code: '001',
    name: 'BOX'
  },
  salesperson: {
    code: '',
    name: ''
  },
  currency: 'IDR',
  exchangeRate: 0.000000,
  exchangeMethod: 'N/A',
  customerPOrder: '',
  pOrderDate: new Date().toISOString().split('T')[0],
  setQuantity: false,
  orderGroup: 'Sales',
  orderType: 'S1-Sales',
  salesTax: false,
  lotNumber: '',
  remark: '',
  instruction1: '',
  instruction2: ''
})

// Modal visibility
const showCustomerModal = ref(false)
const showMasterCardModal = ref(false)
const showProductDesignModal = ref(false)
const showDeliveryLocationModal = ref(false)
const showDeliveryScheduleModal = ref(false)

// Computed properties
const canProceed = computed(() => {
  return selectedCustomer.code && selectedMasterCard.seq
})

const dayOfWeek = computed(() => {
  if (!orderDetails.pOrderDate) return ''
  
  try {
  const date = new Date(orderDetails.pOrderDate)
    
    // Check if date is valid
    if (isNaN(date.getTime())) {
      return ''
    }
    
  const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
  return days[date.getDay()]
  } catch (err) {
    console.warn('Error calculating day of week:', err)
    return ''
  }
})

// Order Types configuration based on ERP CPS
const orderTypesConfig = {
  Sales: [
    {
      code: 'S1-Sales',
      label: 'S1-Sales (SO-Corr-Conv-FG-DO-IV)',
      description: 'Full Sales Process: Sales Order → Corrugator → Converting → Finished Goods → Delivery Order → Invoice',
      workflow: ['SO', 'Corr', 'Conv', 'FG', 'DO', 'IV'],
      requiresInventory: true,
      requiresProduction: true,
      requiresDelivery: true,
      requiresInvoice: true,
      salesTax: true
    },
    {
      code: 'S2-Sales',
      label: 'S2-Sales (SO-DO-IV Kanban/JIT)',
      description: 'Kanban/JIT Sales: Sales Order → Delivery Order → Invoice (for Just-In-Time production)',
      workflow: ['SO', 'DO', 'IV'],
      requiresInventory: false,
      requiresProduction: false,
      requiresDelivery: true,
      requiresInvoice: true,
      isKanban: true,
      salesTax: true
    },
    {
      code: 'S3-Sales',
      label: 'S3-Sales (SO-Conv-FG-DO-IV)',
      description: 'Sales without Corrugator: Sales Order → Converting → Finished Goods → Delivery Order → Invoice',
      workflow: ['SO', 'Conv', 'FG', 'DO', 'IV'],
      requiresInventory: true,
      requiresProduction: true,
      requiresDelivery: true,
      requiresInvoice: true,
      skipCorrugator: true,
      salesTax: true
    }
  ],
  'Non-Sales': [
    {
      code: 'N1-NonSales',
      label: 'N1-NonSales (SO-Corr-Conv-FG-DO)',
      description: 'Full Production without Invoice: Sales Order → Corrugator → Converting → Finished Goods → Delivery Order',
      workflow: ['SO', 'Corr', 'Conv', 'FG', 'DO'],
      requiresInventory: true,
      requiresProduction: true,
      requiresDelivery: true,
      requiresInvoice: false,
      salesTax: false
    },
    {
      code: 'N2-NonSales',
      label: 'N2-NonSales (SO-Conv-FG)',
      description: 'Converting Only: Sales Order → Converting → Finished Goods',
      workflow: ['SO', 'Conv', 'FG'],
      requiresInventory: true,
      requiresProduction: true,
      requiresDelivery: false,
      requiresInvoice: false,
      skipCorrugator: true,
      salesTax: false
    },
    {
      code: 'N3-NonSales',
      label: 'N3-NonSales (SO-Corr-Conv-FG)',
      description: 'Production without Delivery: Sales Order → Corrugator → Converting → Finished Goods',
      workflow: ['SO', 'Corr', 'Conv', 'FG'],
      requiresInventory: true,
      requiresProduction: true,
      requiresDelivery: false,
      requiresInvoice: false,
      salesTax: false
    },
    {
      code: 'N4-NonSales',
      label: 'N4-NonSales (SO-Corr)',
      description: 'Corrugator Only: Sales Order → Corrugator',
      workflow: ['SO', 'Corr'],
      requiresInventory: false,
      requiresProduction: true,
      requiresDelivery: false,
      requiresInvoice: false,
      corrugatorOnly: true,
      salesTax: false
    }
  ]
}

// Computed property for available order types based on selected group
const availableOrderTypes = computed(() => {
  return orderTypesConfig[orderDetails.orderGroup] || []
})

// Computed property for selected order type description
const selectedOrderTypeDescription = computed(() => {
  const allOrderTypes = [...orderTypesConfig.Sales, ...orderTypesConfig['Non-Sales']]
  const selectedType = allOrderTypes.find(type => type.code === orderDetails.orderType)
  return selectedType?.description || ''
})

// Computed property for current order type configuration
const currentOrderTypeConfig = computed(() => {
  const allOrderTypes = [...orderTypesConfig.Sales, ...orderTypesConfig['Non-Sales']]
  return allOrderTypes.find(type => type.code === orderDetails.orderType) || null
})

// Methods
// Handle Order Group change
const handleOrderGroupChange = () => {
  console.log('Order Group changed to:', orderDetails.orderGroup)
  
  // Reset to first available order type when group changes
  const availableTypes = orderTypesConfig[orderDetails.orderGroup] || []
  if (availableTypes.length > 0) {
    orderDetails.orderType = availableTypes[0].code
    console.log('Auto-selected order type:', orderDetails.orderType)
  }
  
  // Update UI based on order type requirements
  updateOrderTypeUI()
  
  // Show notification
  success(`Order group changed to ${orderDetails.orderGroup}. Order type auto-selected.`)
}

// Handle Order Type change
const handleOrderTypeChange = () => {
  console.log('Order Type changed to:', orderDetails.orderType)
  updateOrderTypeUI()
  
  const typeConfig = currentOrderTypeConfig.value
  if (typeConfig) {
    info(`Selected: ${typeConfig.label}`)
  }
}

// Update UI based on order type requirements
const updateOrderTypeUI = () => {
  const typeConfig = currentOrderTypeConfig.value
  
  if (!typeConfig) return
  
  console.log('Updating UI for order type:', typeConfig.code)
  console.log('Workflow steps:', typeConfig.workflow)
  console.log('Requirements:', {
    inventory: typeConfig.requiresInventory,
    production: typeConfig.requiresProduction,
    delivery: typeConfig.requiresDelivery,
    invoice: typeConfig.requiresInvoice
  })
  
  // Update sales tax based on order type configuration
  orderDetails.salesTax = typeConfig.salesTax || false
  
  // Special handling for Kanban/JIT orders
  if (typeConfig.isKanban) {
    console.log('Kanban/JIT order detected - special handling enabled')
    info('Kanban/JIT mode: Production will be scheduled based on delivery requirements')
  }
  
  // Special handling for corrugator-only orders
  if (typeConfig.corrugatorOnly) {
    console.log('Corrugator-only order detected')
    info('Corrugator-only mode: Order will stop after corrugator process')
  }
  
  // Special handling for orders that skip corrugator
  if (typeConfig.skipCorrugator) {
    console.log('Skip corrugator mode detected')
    info('Direct converting mode: Order will skip corrugator process')
  }
}

// Get order type workflow steps for display
const getWorkflowSteps = () => {
  const typeConfig = currentOrderTypeConfig.value
  if (!typeConfig) return []
  
  return typeConfig.workflow.map(step => {
    const stepNames = {
      'SO': 'Sales Order',
      'Corr': 'Corrugator',
      'Conv': 'Converting', 
      'FG': 'Finished Goods',
      'DO': 'Delivery Order',
      'IV': 'Invoice'
    }
    return {
      code: step,
      name: stepNames[step] || step,
      icon: getWorkflowStepIcon(step)
    }
  })
}

// Get workflow step icons
const getWorkflowStepIcon = (step) => {
  const iconMap = {
    'SO': 'fas fa-file-alt',
    'Corr': 'fas fa-cogs',
    'Conv': 'fas fa-industry',
    'FG': 'fas fa-boxes',
    'DO': 'fas fa-shipping-fast',
    'IV': 'fas fa-file-invoice-dollar'
  }
  return iconMap[step] || 'fas fa-circle'
}

// Check if current order type requires specific workflow step
const requiresStep = (stepCode) => {
  const typeConfig = currentOrderTypeConfig.value
  if (!typeConfig) return false
  return typeConfig.workflow.includes(stepCode)
}

const refreshPage = () => {
  // Reset all form data
  Object.assign(currentPeriod, {
    month: new Date().getMonth() + 1,
    year: new Date().getFullYear()
  })
  Object.assign(updatePeriod, {
    month: new Date().getMonth() + 1,
    year: new Date().getFullYear()
  })
  Object.assign(selectedCustomer, {
    code: '',
    name: '',
    address: '',
    salesperson: '',
    currency: 'IDR'
  })
  Object.assign(selectedMasterCard, {
    seq: '',
    model: '',
    status: '',
    partNo: '',
    compNo: '',
    pDesign: ''
  })
  Object.assign(orderDetails, {
    orderMode: '0',
    product: {
      code: '001',
      name: 'BOX'
    },
    salesperson: {
      code: '',
      name: ''
    },
    currency: 'IDR',
    exchangeRate: 0.000000,
    exchangeMethod: 'N/A',
    customerPOrder: '',
    pOrderDate: new Date().toISOString().split('T')[0],
    setQuantity: false,
    orderGroup: 'Sales',
    orderType: 'S1-Sales',
    salesTax: false,
    lotNumber: '',
    remark: '',
    instruction1: '',
    instruction2: ''
  })
  
  // Update UI after reset
  updateOrderTypeUI()
  
  success('Form reset successfully')
}

const printLog = async () => {
  try {
    const response = await fetch('/api/sales-order/print-log')
    const data = await response.json()
    success('SO Log report generated successfully')
    // You can implement actual PDF download or print functionality here
    console.log('SO Log data:', data)
  } catch (err) {
    error('Error generating SO Log report')
  }
}

const printJitTracking = async () => {
  try {
    const response = await fetch('/api/sales-order/print-jit-tracking')
    const data = await response.json()
    success('JIT Tracking report generated successfully')
    // You can implement actual PDF download or print functionality here
    console.log('JIT Tracking data:', data)
  } catch (err) {
    error('Error generating JIT Tracking report')
  }
}

const openCustomerLookup = () => {
  showCustomerModal.value = true
}

const selectCustomer = (customer) => {
  selectedCustomer.code = customer.customer_code
  selectedCustomer.name = customer.customer_name
  selectedCustomer.address = customer.address || ''
  selectedCustomer.salesperson = customer.salesperson_code || ''
  selectedCustomer.currency = customer.currency_code || 'IDR'
  
  // Update order details
  orderDetails.salesperson.code = customer.salesperson_code || ''
  orderDetails.currency = customer.currency_code || 'IDR'
  
  showCustomerModal.value = false
  success('Customer selected successfully')
}

const validateCustomer = async () => {
  if (!selectedCustomer.code.trim()) return
  
  try {
    const response = await fetch(`/api/sales-order/customer/${selectedCustomer.code}`)
    const data = await response.json()
    
    if (data.success && data.data) {
      const customer = data.data
      selectedCustomer.name = customer.customer_name
      selectedCustomer.address = customer.address || ''
      selectedCustomer.salesperson = customer.salesperson_code || ''
      selectedCustomer.currency = customer.currency_code || 'IDR'
      
      // Update order details with customer info
      orderDetails.salesperson.code = customer.salesperson_code || ''
      orderDetails.currency = customer.currency_code || 'IDR'
      
      // Fetch salesperson name if available
      if (customer.salesperson_code) {
        try {
          const spResponse = await fetch(`/api/sales-order/salesperson/${customer.salesperson_code}`)
          const spData = await spResponse.json()
          if (spData.success && spData.data) {
            orderDetails.salesperson.name = spData.data.name
          }
        } catch (spErr) {
          console.warn('Could not fetch salesperson details:', spErr)
        }
      }
      
      success('Customer validated successfully')
    } else {
      error(data.message || 'Customer not found')
      selectedCustomer.name = ''
      selectedCustomer.address = ''
      selectedCustomer.salesperson = ''
      selectedCustomer.currency = 'IDR'
    }
  } catch (err) {
    console.error('Error validating customer:', err)
    error('Error validating customer: ' + (err.message || 'Network error'))
    selectedCustomer.name = ''
    selectedCustomer.address = ''
    selectedCustomer.salesperson = ''
    selectedCustomer.currency = 'IDR'
  }
}

const openMasterCardLookup = () => {
  if (!selectedCustomer.code) {
    error('Please select a customer first')
    return
  }
  showMasterCardModal.value = true
}

const selectMasterCard = (masterCard) => {
  selectedMasterCard.seq = masterCard.mc_seq
  selectedMasterCard.model = masterCard.mc_model || masterCard.model
  selectedMasterCard.status = masterCard.status
  selectedMasterCard.partNo = masterCard.part_no
  selectedMasterCard.compNo = masterCard.comp_no
  selectedMasterCard.pDesign = masterCard.p_design
  
  showMasterCardModal.value = false
  success('Master card selected successfully')
}

const validateMasterCard = async () => {
  if (!selectedMasterCard.seq.trim()) return
  
  try {
    const response = await fetch(`/api/update-mc/check-mcs/${selectedMasterCard.seq}`)
    const data = await response.json()
    
    if (data.exists && data.data) {
      const masterCard = data.data
      selectedMasterCard.model = masterCard.mc_model || ''
      selectedMasterCard.status = masterCard.status || 'Active'
      selectedMasterCard.partNo = masterCard.part_no || ''
      selectedMasterCard.compNo = masterCard.comp_no || ''
      selectedMasterCard.pDesign = masterCard.p_design || ''
      
      success('Master card validated successfully')
    } else {
      error('Master card not found')
      selectedMasterCard.model = ''
      selectedMasterCard.status = ''
      selectedMasterCard.partNo = ''
      selectedMasterCard.compNo = ''
      selectedMasterCard.pDesign = ''
    }
  } catch (err) {
    console.error('Error validating master card:', err)
    error('Error validating master card: ' + (err.message || 'Network error'))
    selectedMasterCard.model = ''
    selectedMasterCard.status = ''
    selectedMasterCard.partNo = ''
    selectedMasterCard.compNo = ''
    selectedMasterCard.pDesign = ''
  }
}

// Add ref for P/Order Date input
const pOrderDateInput = ref(null)
const calendarLoading = ref(false)
const calendarExplicitlyOpened = ref(false)

const openCalendar = async () => {
  try {
    calendarLoading.value = true
    calendarExplicitlyOpened.value = true
    console.log('Opening calendar...')
    
    // Wait for Vue to update the DOM
    await nextTick()
    
    // Use specific ref instead of generic selector
    const dateInput = pOrderDateInput.value
    console.log('Date input element:', dateInput)
    
    if (!dateInput) {
      console.error('Date input element not found')
      error('Calendar input not available. Please refresh the page.')
      return
    }

    // Check if element is visible and not disabled
    if (dateInput.disabled || dateInput.style.display === 'none') {
      console.warn('Date input is disabled or hidden')
      error('Date input is not available for interaction.')
      return
    }

    try {
      // Check if showPicker is supported (modern browsers)
      if (typeof dateInput.showPicker === 'function') {
        console.log('showPicker() is supported, attempting to use it')
    try {
      // Focus the input first
      dateInput.focus()
      console.log('Date input focused')
      
      // Wait for focus to take effect
      await new Promise(resolve => setTimeout(resolve, 100))
      
          dateInput.showPicker()
          console.log('Calendar opened successfully using showPicker()')
          success('Calendar opened. Select a date.')
        } catch (pickerErr) {
          console.warn('showPicker() failed:', pickerErr.message)
          error('Calendar could not be opened automatically. Please click directly on the date field.')
        }
      } else {
        // Fallback for browsers that don't support showPicker
        console.log('showPicker() not supported, using fallback method')
        
        // Focus the input and show a message to click manually
        dateInput.focus()
        success('Please click on the date field to open the calendar')
      }
      
    } catch (interactionErr) {
      console.error('Calendar interaction error:', interactionErr)
      error('Unable to open calendar. Please click directly on the date field.')
    }
      
  } catch (err) {
    console.error('Error opening calendar:', err)
    error('Error opening calendar: ' + (err.message || 'Unknown error'))
  } finally {
    // Add a small delay to show loading state
    setTimeout(() => {
      calendarLoading.value = false
      // Reset the flag after a delay
      setTimeout(() => {
        calendarExplicitlyOpened.value = false
        console.log('Calendar flag reset - calendar will no longer show automatically')
      }, 2000) // Increased delay to ensure user has time to interact
    }, 200)
  }
}

// Function to update day of week when date changes
const updateDayOfWeek = () => {
  // Trigger computed property recalculation by updating the reactive value
  if (orderDetails.pOrderDate) {
    // Validate the date
    const date = new Date(orderDetails.pOrderDate)
    
    if (isNaN(date.getTime())) {
      error('Invalid date format. Please select a valid date.')
      return
    }
    
    // Check if date is not too far in the past or future
    const today = new Date()
    const oneYearAgo = new Date(today.getFullYear() - 1, today.getMonth(), today.getDate())
    const twoYearsFromNow = new Date(today.getFullYear() + 2, today.getMonth(), today.getDate())
    
    if (date < oneYearAgo) {
      error('Warning: Selected date is more than 1 year in the past')
    } else if (date > twoYearsFromNow) {
      error('Warning: Selected date is more than 2 years in the future')
    }
    
    // The computed property dayOfWeek will automatically update
    console.log('Date changed to:', orderDetails.pOrderDate, 'Day:', dayOfWeek.value)
  }
}

// Handle date input focus - prevent automatic calendar display
const handleDateInputFocus = (event) => {
  // Always prevent calendar from showing on focus unless explicitly opened
  if (!calendarExplicitlyOpened.value) {
    event.preventDefault()
    event.stopPropagation()
    console.log('Date input focused - calendar prevented from showing automatically')
    
    // Force blur to prevent any browser default behavior
    setTimeout(() => {
      if (event.target === document.activeElement) {
        event.target.blur()
      }
    }, 10)
  } else {
    console.log('Date input focused - calendar allowed (explicitly opened)')
  }
}

// Handle date input blur - close any open calendar
const handleDateInputBlur = () => {
  console.log('Date input blurred - closing any open calendar')
  // Reset the flag when input loses focus
  calendarExplicitlyOpened.value = false
  // The browser will automatically close the calendar when input loses focus
}

// Handle date input click - prevent automatic calendar
const handleDateInputClick = (event) => {
  // Only allow calendar to show if it's explicitly triggered by the calendar button
  if (!calendarExplicitlyOpened.value) {
    event.preventDefault()
    event.stopPropagation()
    // Focus the input without showing calendar
    event.target.focus()
    
    // Force blur after a short delay to prevent any browser default behavior
    setTimeout(() => {
      if (event.target === document.activeElement) {
        event.target.blur()
      }
    }, 50)
  }
}

// Handle date input keydown - prevent automatic calendar
const handleDateInputKeydown = (event) => {
  // Only allow calendar to show if it's explicitly opened
  if (!calendarExplicitlyOpened.value) {
    // Prevent all key events that might trigger calendar
    if (event.key === 'Enter' || event.key === ' ' || event.key === 'Tab') {
      event.preventDefault()
      event.stopPropagation()
    }
    
    // Prevent any other key that might trigger calendar
    if (event.key === 'ArrowDown' || event.key === 'ArrowUp' || event.key === 'F4') {
      event.preventDefault()
      event.stopPropagation()
    }
  }
}

// Handle document click - close calendar when clicking outside
const handleDocumentClick = (event) => {
  const dateInput = pOrderDateInput.value
  if (dateInput && event.target !== dateInput && !dateInput.contains(event.target)) {
    // If clicking outside the date input, blur it to close any open calendar
    if (document.activeElement === dateInput) {
      dateInput.blur()
      calendarExplicitlyOpened.value = false
    }
    
    // Also check if the clicked element is not the calendar button
    const calendarButton = event.target.closest('.calendar-button')
    if (!calendarButton) {
      // Reset the flag when clicking anywhere else
      calendarExplicitlyOpened.value = false
    }
  }
}

// Handle mouse events to prevent calendar
const handleDateInputMouseDown = (event) => {
  if (!calendarExplicitlyOpened.value) {
    event.preventDefault()
    event.stopPropagation()
  }
}

const handleDateInputMouseUp = (event) => {
  if (!calendarExplicitlyOpened.value) {
    event.preventDefault()
    event.stopPropagation()
  }
}

// Add date validation helper
const validateDate = (dateString) => {
  if (!dateString) return { valid: false, message: 'Date is required' }
  
  const date = new Date(dateString)
  
  if (isNaN(date.getTime())) {
    return { valid: false, message: 'Invalid date format' }
  }
  
  const today = new Date()
  const oneYearAgo = new Date(today.getFullYear() - 1, today.getMonth(), today.getDate())
  const fiveYearsFromNow = new Date(today.getFullYear() + 5, today.getMonth(), today.getDate())
  
  if (date < oneYearAgo) {
    return { valid: false, message: 'Date cannot be more than 1 year in the past' }
  }
  
  if (date > fiveYearsFromNow) {
    return { valid: false, message: 'Date cannot be more than 5 years in the future' }
  }
  
  return { valid: true, message: 'Valid date' }
}

const openProductDesignScreen = () => {
  if (!canProceed.value) {
    error('Please select customer and master card first')
    return
  }
  showProductDesignModal.value = true
}

const saveProductDesign = async (designData) => {
  try {
    // Validate required data
    if (!selectedMasterCard.seq) {
      error('Master card is required for product design')
      return
    }
    
    const requestData = {
      master_card_seq: selectedMasterCard.seq,
      items: designData.items || [],
      dimensions: designData.dimensions || [],
      total_amount: designData.totalAmount || 0,
      total_gross_kg: designData.totalGrossKg || 0,
      ...designData
    }
    
    const response = await fetch('/api/sales-order/product-design', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      },
      body: JSON.stringify(requestData)
    })
    
    const data = await response.json()
    
    if (data.success) {
  console.log('Product design saved:', designData)
  showProductDesignModal.value = false
  success('Product design saved successfully')
  
  // Open delivery schedule after product design
  setTimeout(() => {
    showDeliveryScheduleModal.value = true
  }, 500)
    } else {
      throw new Error(data.message || 'Failed to save product design')
    }
  } catch (err) {
    console.error('Error saving product design:', err)
    error('Error saving product design: ' + (err.message || 'Network error'))
  }
}

const openDeliveryLocation = () => {
  if (!canProceed.value) {
    error('Please select customer and master card first')
    return
  }
  showDeliveryLocationModal.value = true
}

const saveDeliveryLocation = async (locationData) => {
  try {
    // Store delivery location data locally for now
    // In a real implementation, you might save this to the database
  console.log('Delivery location saved:', locationData)
    
    // Update order details with delivery location if provided
    if (locationData.address) {
      orderDetails.deliveryAddress = locationData.address
    }
    
  showDeliveryLocationModal.value = false
  success('Delivery location saved successfully')
  } catch (err) {
    console.error('Error saving delivery location:', err)
    error('Error saving delivery location: ' + (err.message || 'Network error'))
  }
}

const saveDeliverySchedule = async (scheduleData) => {
  try {
    // Validate required data
    if (!scheduleData.entries || !Array.isArray(scheduleData.entries)) {
      error('Delivery schedule entries are required')
      return
    }
    
    const requestData = {
      customer_code: selectedCustomer.code,
      master_card_seq: selectedMasterCard.seq,
      entries: scheduleData.entries,
      entry_total: scheduleData.entryTotal || 0,
      order_total: scheduleData.orderTotal || 0,
      ...scheduleData
    }
    
    const response = await fetch('/api/sales-order/delivery-schedule', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      },
      body: JSON.stringify(requestData)
    })
    
    const data = await response.json()
    
    if (data.success) {
  console.log('Delivery schedule saved:', scheduleData)
  showDeliveryScheduleModal.value = false
  success('Delivery schedule saved successfully')
      
      // Optionally, you can proceed with final sales order creation here
    } else {
      throw new Error(data.message || 'Failed to save delivery schedule')
    }
  } catch (err) {
    console.error('Error saving delivery schedule:', err)
    error('Error saving delivery schedule: ' + (err.message || 'Network error'))
  }
}

// Create Sales Order function
const createSalesOrder = async () => {
  try {
    // Comprehensive validation
    if (!selectedCustomer.code) {
      error('Customer is required')
      return
    }
    
    if (!selectedMasterCard.seq) {
      error('Master card is required')
      return
    }
    
    if (!orderDetails.pOrderDate) {
      error('Purchase order date is required')
      return
    }
    
    const requestData = {
      customer_code: selectedCustomer.code,
      master_card_seq: selectedMasterCard.seq,
      order_mode: orderDetails.orderMode,
      product_code: orderDetails.product.code,
      salesperson_code: orderDetails.salesperson.code,
      currency: orderDetails.currency,
      exchange_rate: parseFloat(orderDetails.exchangeRate) || 0,
      customer_po_number: orderDetails.customerPOrder,
      po_date: orderDetails.pOrderDate,
      order_group: orderDetails.orderGroup,
      order_type: orderDetails.orderType,
      sales_tax: orderDetails.salesTax,
      lot_number: orderDetails.lotNumber,
      remark: orderDetails.remark,
      instruction1: orderDetails.instruction1,
      instruction2: orderDetails.instruction2,
      set_quantity: orderDetails.setQuantity
    }
    
    const response = await fetch('/api/sales-order', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      },
      body: JSON.stringify(requestData)
    })
    
    const data = await response.json()
    
    if (data.success) {
      success(`Sales Order ${data.data.so_number} created successfully!`)
      
      // Reset form after successful creation
      setTimeout(() => {
        refreshPage()
      }, 2000)
    } else {
      if (data.errors) {
        const errorMessages = Object.values(data.errors).flat().join(', ')
        error('Validation errors: ' + errorMessages)
      } else {
        throw new Error(data.message || 'Failed to create sales order')
      }
    }
  } catch (err) {
    console.error('Error creating sales order:', err)
    error('Error creating sales order: ' + (err.message || 'Network error'))
  }
}

// Enhanced validation functions
const validatePeriod = () => {
  if (currentPeriod.month < 1 || currentPeriod.month > 12) {
    error('Current period month must be between 1-12')
    return false
  }
  if (updatePeriod.month < 1 || updatePeriod.month > 12) {
    error('Update period month must be between 1-12')
    return false
  }
  if (currentPeriod.year < 2000 || currentPeriod.year > 2100) {
    error('Current period year must be between 2000-2100')
    return false
  }
  if (updatePeriod.year < 2000 || updatePeriod.year > 2100) {
    error('Update period year must be between 2000-2100')
    return false
  }
  return true
}

const validateForm = () => {
  if (!validatePeriod()) return false
  
  if (!selectedCustomer.code) {
    error('Please select a customer')
    return false
  }
  
  if (!selectedMasterCard.seq) {
    error('Please select a master card')
    return false
  }
  
  // Validate P/Order Date using the new validation function
  const dateValidation = validateDate(orderDetails.pOrderDate)
  if (!dateValidation.valid) {
    error('P/Order Date: ' + dateValidation.message)
    return false
  }
  
  if (forwardPeriod.value < 1) {
    error('Forward period must be at least 1 month')
    return false
  }
  
  if (backwardPeriod.value < 1) {
    error('Backward period must be at least 1 month')
    return false
  }
  
  return true
}

// Watchers for auto-validation
watch(() => selectedCustomer.code, (newCode) => {
  if (newCode && newCode.length >= 3) {
    // Debounce the validation
    clearTimeout(window.customerValidationTimeout)
    window.customerValidationTimeout = setTimeout(() => {
      validateCustomer()
    }, 500)
  }
})

watch(() => selectedMasterCard.seq, (newSeq) => {
  if (newSeq && newSeq.length >= 3) {
    // Debounce the validation
    clearTimeout(window.mcValidationTimeout)
    window.mcValidationTimeout = setTimeout(() => {
      validateMasterCard()
    }, 500)
  }
})

// Watch for changes in customer to update currency and exchange rate
watch(() => selectedCustomer.currency, (newCurrency) => {
  orderDetails.currency = newCurrency
  // You can add exchange rate lookup here if needed
  if (newCurrency && newCurrency !== 'IDR') {
    orderDetails.exchangeRate = 1.000000 // Default, should be fetched from API
  } else {
    orderDetails.exchangeRate = 0.000000
    orderDetails.exchangeMethod = 'N/A'
  }
})

// Initialize component
onMounted(() => {
  // Set default values
  const today = new Date()
  currentPeriod.month = today.getMonth() + 1
  currentPeriod.year = today.getFullYear()
  updatePeriod.month = today.getMonth() + 1
  updatePeriod.year = today.getFullYear()
  
  // Set CSRF token for all requests
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
  if (csrfToken) {
    // Set default headers for axios if you're using it
    if (window.axios) {
      window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken
    }
  }
  
  // Initialize form validation
  validatePeriod()
  
  // Initialize Order Type UI
  updateOrderTypeUI()
})

// Keyboard shortcuts
const handleKeyDown = (event) => {
  // Ctrl/Cmd + S to create sales order
  if ((event.ctrlKey || event.metaKey) && event.key === 's') {
    event.preventDefault()
    if (canProceed.value) {
      createSalesOrder()
    } else {
      error('Please complete required fields first')
    }
  }
  
  // F2 to open customer lookup
  if (event.key === 'F2') {
    event.preventDefault()
    openCustomerLookup()
  }
  
  // F3 to open master card lookup
  if (event.key === 'F3') {
    event.preventDefault()
    openMasterCardLookup()
  }
  
  // F4 to open calendar
  if (event.key === 'F4') {
    event.preventDefault()
    openCalendar()
  }
  
  // F5 to refresh
  if (event.key === 'F5') {
    event.preventDefault()
    refreshPage()
  }
  
  // Ctrl/Cmd + P to print log
  if ((event.ctrlKey || event.metaKey) && event.key === 'p') {
    event.preventDefault()
    printLog()
  }
}

// Auto-save draft functionality
const saveDraft = () => {
  const draftData = {
    currentPeriod: currentPeriod,
    updatePeriod: updatePeriod,
    forwardPeriod: forwardPeriod.value,
    backwardPeriod: backwardPeriod.value,
    lastSOOrder: lastSOOrder,
    selectedCustomer: selectedCustomer,
    selectedMasterCard: selectedMasterCard,
    orderDetails: orderDetails,
    timestamp: new Date().toISOString()
  }
  
  localStorage.setItem('prepare_mc_so_draft', JSON.stringify(draftData))
}

const loadDraft = () => {
  const savedDraft = localStorage.getItem('prepare_mc_so_draft')
  if (savedDraft) {
    try {
      const draftData = JSON.parse(savedDraft)
      // Check if draft is not too old (e.g., within 24 hours)
      const draftTimestamp = new Date(draftData.timestamp)
      const now = new Date()
      const hoursDiff = (now - draftTimestamp) / (1000 * 60 * 60)
      
      if (hoursDiff < 24) {
        // Ask user if they want to restore draft
        if (confirm('A recent draft was found. Do you want to restore it?')) {
          Object.assign(currentPeriod, draftData.currentPeriod)
          Object.assign(updatePeriod, draftData.updatePeriod)
          forwardPeriod.value = draftData.forwardPeriod
          backwardPeriod.value = draftData.backwardPeriod
          Object.assign(lastSOOrder, draftData.lastSOOrder)
          Object.assign(selectedCustomer, draftData.selectedCustomer)
          Object.assign(selectedMasterCard, draftData.selectedMasterCard)
          Object.assign(orderDetails, draftData.orderDetails)
          success('Draft restored successfully')
        }
      } else {
        // Clear old draft
        localStorage.removeItem('prepare_mc_so_draft')
      }
    } catch (err) {
      console.warn('Failed to load draft:', err)
      localStorage.removeItem('prepare_mc_so_draft')
    }
  }
}

const clearDraft = () => {
  localStorage.removeItem('prepare_mc_so_draft')
}

// Auto-save every 30 seconds
let autoSaveInterval = null

// Initialize component
onMounted(async () => {
  // Set default values
  const today = new Date()
  currentPeriod.month = today.getMonth() + 1
  currentPeriod.year = today.getFullYear()
  updatePeriod.month = today.getMonth() + 1
  updatePeriod.year = today.getFullYear()
  
  // Set CSRF token for all requests
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
  if (csrfToken) {
    // Set default headers for axios if you're using it
    if (window.axios) {
      window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken
    }
  }
  
  // Initialize form validation
  validatePeriod()
  
  // Initialize Order Type UI
  updateOrderTypeUI()
  
  // Load draft if available
  loadDraft()
  
  // Setup keyboard shortcuts
  document.addEventListener('keydown', handleKeyDown)
  
  // Setup auto-save
  autoSaveInterval = setInterval(saveDraft, 30000) // Save every 30 seconds
  
  // Ensure date input is properly initialized
  await nextTick()
  console.log('PrepareMCSO component mounted successfully, date input ref:', pOrderDateInput.value)
  
            // Add event listener to prevent calendar from showing on other elements
      const dateInput = pOrderDateInput.value
      if (dateInput) {
              // Add event listeners to prevent calendar from showing automatically
      dateInput.addEventListener('focus', handleDateInputFocus)
      dateInput.addEventListener('blur', handleDateInputBlur)
      dateInput.addEventListener('click', handleDateInputClick)
      dateInput.addEventListener('keydown', handleDateInputKeydown)
      
      // Add additional event listeners to prevent calendar
      dateInput.addEventListener('mousedown', handleDateInputMouseDown)
      dateInput.addEventListener('mouseup', handleDateInputMouseUp)
      
      // Add document click listener to close calendar when clicking outside
      document.addEventListener('click', handleDocumentClick)
      }
})

// Cleanup on unmount
onUnmounted(() => {
  clearTimeout(window.customerValidationTimeout)
  clearTimeout(window.mcValidationTimeout)
  document.removeEventListener('keydown', handleKeyDown)
  
  // Remove calendar-related event listeners
  const dateInput = pOrderDateInput.value
  if (dateInput) {
    // Remove all event listeners to prevent memory leaks
    dateInput.removeEventListener('focus', handleDateInputFocus)
    dateInput.removeEventListener('blur', handleDateInputBlur)
    dateInput.removeEventListener('click', handleDateInputClick)
    dateInput.removeEventListener('keydown', handleDateInputKeydown)
    
    // Remove additional event listeners
    dateInput.removeEventListener('mousedown', handleDateInputMouseDown)
    dateInput.removeEventListener('mouseup', handleDateInputMouseUp)
  }
  
  // Remove document click listener
  document.removeEventListener('click', handleDocumentClick)
  
  if (autoSaveInterval) {
    clearInterval(autoSaveInterval)
  }
  // Save final draft on unmount
  saveDraft()
})
</script>

<style scoped>
/* Custom input focus styles */
input:focus, select:focus, textarea:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Disabled button styles */
button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

/* Grid responsive adjustments */
@media (max-width: 1024px) {
  .grid-cols-1.lg\:grid-cols-2 {
    grid-template-columns: 1fr;
  }
}

/* Date input styles */
input[type="date"]::-webkit-calendar-picker-indicator {
  background: transparent;
  bottom: 0;
  color: transparent;
  cursor: pointer;
  height: auto;
  left: 0;
  position: absolute;
  right: 0;
  top: 0;
  width: auto;
}

/* Prevent calendar from showing on focus */
input[type="date"]:focus {
  /* Remove any default browser styling that might trigger calendar */
  outline: none;
}

/* Ensure calendar only shows when explicitly triggered */
input[type="date"] {
  /* Prevent automatic calendar display */
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
}

/* Custom calendar button styling */
.calendar-button {
  position: relative;
  z-index: 10;
}

/* Prevent calendar from showing on input focus */
input[type="date"]:focus:not(.calendar-explicitly-opened) {
  /* Remove any default browser styling that might trigger calendar */
  outline: none;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Additional focus prevention */
input[type="date"]:not(.calendar-explicitly-opened):focus {
  /* Prevent any browser default behavior */
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* Additional prevention for calendar display */
input[type="date"]:not(.calendar-explicitly-opened)::-webkit-calendar-picker-indicator {
  display: none !important;
  visibility: hidden !important;
  opacity: 0 !important;
  pointer-events: none !important;
}

/* Additional prevention for Firefox and other browsers */
input[type="date"]:not(.calendar-explicitly-opened)::-moz-calendar-picker-indicator {
  display: none !important;
  visibility: hidden !important;
  opacity: 0 !important;
  pointer-events: none !important;
}

/* Additional prevention for Edge and IE */
input[type="date"]:not(.calendar-explicitly-opened)::-ms-clear,
input[type="date"]:not(.calendar-explicitly-opened)::-ms-expand {
  display: none !important;
  visibility: hidden !important;
  opacity: 0 !important;
  pointer-events: none !important;
}

/* Ensure calendar only shows when explicitly triggered */
input[type="date"].calendar-explicitly-opened {
  /* Allow calendar to show when explicitly opened */
  -webkit-appearance: auto;
  -moz-appearance: auto;
  appearance: auto;
}

/* Prevent calendar from showing on non-explicit focus */
input[type="date"]:not(.calendar-explicitly-opened) {
  /* Completely prevent calendar from showing */
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
}
</style>