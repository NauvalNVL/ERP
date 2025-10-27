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
              <p class="text-xs text-gray-500">F2: Customer • F3: Master Card • F4: Calendar • F6: SO Table • Ctrl+S: Save • F5: Refresh</p>
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
                  
                  <!-- Master Card Approval Status -->
                  <div v-if="selectedMasterCard.seq && approvalStatusMessage" class="mt-2 p-3 rounded" 
                       :class="{
                         'bg-green-100 border border-green-200': approvalStatusMessage.type === 'success',
                         'bg-yellow-100 border border-yellow-200': approvalStatusMessage.type === 'warning',
                         'bg-blue-100 border border-blue-200': approvalStatusMessage.type === 'info'
                       }">
                    <div class="flex items-center">
                      <i :class="{
                        'fas fa-check-circle text-green-600': approvalStatusMessage.type === 'success',
                        'fas fa-exclamation-triangle text-yellow-600': approvalStatusMessage.type === 'warning',
                        'fas fa-info-circle text-blue-600': approvalStatusMessage.type === 'info'
                      }" class="mr-2"></i>
                      <p class="text-sm font-medium" :class="{
                        'text-green-800': approvalStatusMessage.type === 'success',
                        'text-yellow-800': approvalStatusMessage.type === 'warning',
                        'text-blue-800': approvalStatusMessage.type === 'info'
                      }">
                        {{ approvalStatusMessage.message }}
                      </p>
                    </div>
                  </div>
                  
                  <div v-if="!selectedMasterCard.seq" class="mt-2 bg-yellow-100 p-3 rounded">
                    <p class="text-sm font-medium text-yellow-800">No master card data available.</p>
                    <p class="text-xs text-yellow-700 mt-1">Please select a customer and use the master card lookup to find available master cards.</p>
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
                  class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Enter customer PO number"
                >
              </div>

              <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">P/Order Date:</label>
                <div class="flex items-center space-x-2">
                    <input 
                    ref="pOrderDateInput"
                    v-model="orderDetails.pOrderDate" 
                      type="date"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    @change="updateDayOfWeek"
                    >
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
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter lot number"
                  >
                </div>
              </div>

              <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Remark:</label>
                  <textarea 
                  v-model="orderDetails.remark" 
                    rows="2"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder=""
                  ></textarea>
                </div>

                <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Instruction1:</label>
                  <textarea 
                  v-model="orderDetails.instruction1" 
                    rows="2"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder=""
                  ></textarea>
                </div>

                <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Instruction2:</label>
                  <textarea 
                  v-model="orderDetails.instruction2" 
                    rows="2"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder=""
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
              
              <!-- Master Card Approval Warning -->
              <div v-if="canProceed && !isMasterCardApproved" class="mt-4 p-3 bg-orange-50 border border-orange-200 rounded-lg">
                <div class="flex items-center">
                  <i class="fas fa-exclamation-triangle text-orange-600 mr-2"></i>
                  <span class="text-sm text-orange-800 font-medium">
                    Master Card Approval Notice
                  </span>
                </div>
                <p class="text-xs text-orange-700 mt-1">
                  The selected master card is not yet approved. You can proceed with creating the sales order, 
                  but the master card may need to be approved before production can begin.
                </p>
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

    <!-- Master Card Table Modal (replaces legacy search/select) -->
    <UpdateMcModal
      v-if="showMcsTableModal"
      :showErrorModal="false"
      :showSetupMcModal="false"
      :showSetupPdModal="false"
      :showMcsTableModal="showMcsTableModal"
      :formData="{}"
      :mcComponents="[]"
      :zoomOption="'mc_specification'"
      :mcsSortOption="mcsSortOption"
      :mcsSortOrder="mcsSortOrder"
      :mcsStatusFilter="mcsStatusFilter"
      :mcsSearchTerm="mcsSearchTerm"
      :mcsLoading="mcsLoading"
      :mcsError="mcsError"
      :mcsMasterCards="mcsMasterCards"
      :selectedMcs="selectedMcs"
      :mcsCurrentPage="mcsCurrentPage"
      :mcsLastPage="mcsLastPage"
      :productDesigns="[]"
      :paperFlutes="[]"
      @closeErrorModal="() => {}"
      @closeSetupMcModal="() => {}"
      @closeSetupPdModal="() => {}"
      @closeMcsTableModal="showMcsTableModal = false"
      @selectComponent="() => {}"
      @setupPD="() => {}"
      @setupOthers="() => {}"
      @handleZoomChange="handleZoomChange"
      @fetchMcsData="fetchMcsData"
      @selectMcsItem="selectedMcs = $event"
      @selectMcs="selectMcs"
      @goToMcsPage="goToMcsPage"
      @updateSearchTerm="updateSearchTerm"
      @updateSortOption="updateSortOption"
    />

    <!-- Product Design Screen Modal -->
    <ProductDesignScreenModal 
      :show="showProductDesignModal" 
      @close="showProductDesignModal = false" 
      @save="saveProductDesign"
          :master-card="selectedMasterCard"
          :customer="selectedCustomer"
          :initial-quantity="normalizedSetQuantity"
          ref="productDesignModalRef"
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

    <!-- Sales Order Table Modal -->
    <SalesOrderTableModal 
      :is-open="showSalesOrderTableModal" 
      @close="showSalesOrderTableModal = false" 
      @select="selectSalesOrderFromTable"
      :customer-data="selectedCustomer"
    />
  </AppLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted, watch, onUnmounted, nextTick } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import CustomerAccountModal from '@/Components/customer-account-modal.vue'
import UpdateMcModal from '@/Components/UpdateMcModal.vue'
import ProductDesignScreenModal from '@/Components/ProductDesignScreenModal.vue'
import DeliveryScheduleModal from '@/Components/DeliveryScheduleModal.vue'
import DeliveryLocationModal from '@/Components/DeliveryLocationModal.vue'
import SalesOrderTableModal from '@/Components/SalesOrderTableModal.vue'
import { useToast } from '@/Composables/useToast'

const { success, error, info } = useToast()

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
  approval: '',
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
  setQuantity: '',
  orderGroup: 'Sales',
  orderType: 'S1-Sales',
  salesTax: false,
  lotNumber: '',
  remark: '',
  instruction1: '',
  instruction2: ''
  ,
  so_number: ''
})

// Modal visibility
const showCustomerModal = ref(false)
const showMasterCardModal = ref(false) // legacy (unused)
const showProductDesignModal = ref(false)
const showDeliveryLocationModal = ref(false)
const showDeliveryScheduleModal = ref(false)
const showSalesOrderTableModal = ref(false)
// Ref to Product Design modal for live quantity sync
const productDesignModalRef = ref(null)

// Normalize setQuantity to number
const normalizedSetQuantity = computed(() => {
  const raw = orderDetails.setQuantity
  const num = typeof raw === 'string' ? parseFloat(raw) : Number(raw)
  return isNaN(num) ? 0 : num
})

// Sync set quantity to Product Design modal when value changes while modal is open
watch(() => normalizedSetQuantity.value, (qty) => {
  if (showProductDesignModal.value && productDesignModalRef.value?.applyExternalQuantity) {
    productDesignModalRef.value.applyExternalQuantity(qty)
  }
})

// New MCS Table Modal state and handlers
const showMcsTableModal = ref(false)
const mcsSortOption = ref('mc_seq')
const mcsSortOrder = ref('asc')
const mcsStatusFilter = ref('Act')
const mcsSearchTerm = ref('')
const mcsLoading = ref(false)
const mcsError = ref('')
const mcsMasterCards = ref([])
const selectedMcs = ref(null)
const mcsCurrentPage = ref(1)
const mcsLastPage = ref(1)


// Computed properties
const canProceed = computed(() => {
  // Allow proceeding if customer and master card are selected
  // Approval status will be shown as warning but won't block the process
  return selectedCustomer.code && selectedMasterCard.seq
})

// Computed property to check if master card is approved
const isMasterCardApproved = computed(() => {
  return selectedMasterCard.approval === 'Yes'
})

// Computed property to get approval status message
const approvalStatusMessage = computed(() => {
  if (!selectedMasterCard.seq) return ''
  
  switch (selectedMasterCard.approval) {
    case 'Yes':
      return { type: 'success', message: 'Master Card is approved and ready for use' }
    case 'No':
      return { type: 'warning', message: 'Master Card is not approved yet. You can proceed but approval may be required later.' }
    default:
      return { type: 'info', message: 'Master Card approval status is unknown' }
  }
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

// Order Types configuration based on ERP CPS - matching the dropdown options from the image
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
      label: 'N2-NonSales (SO-DO)',
      description: 'Sales Order → Delivery Order (Minimal workflow)',
      workflow: ['SO', 'DO'],
      requiresInventory: false,
      requiresProduction: false,
      requiresDelivery: true,
      requiresInvoice: false,
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
    instruction2: '',
    so_number: ''
  })
  
  // Update UI after reset
  updateOrderTypeUI()
  
  success('Form reset successfully')
}

const printLog = async () => {
  // Open Sales Order Table Modal to view existing sales orders
  openSalesOrderTable()
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

// Open Sales Order Table Modal
const openSalesOrderTable = () => {
  if (!selectedCustomer.code) {
    error('Please select a customer first to view sales orders')
    return
  }
  showSalesOrderTableModal.value = true
}

// Handle selection from Sales Order Table Modal
const selectSalesOrderFromTable = (orderData) => {
  console.log('Sales Order selected from table:', orderData)
  
  // You can use the selected order data to populate the form or navigate to a detail screen
  // For now, we'll just show a success message
  success(`Sales Order ${orderData.soNumber} selected`)
  
  // Optionally, you could navigate to a detail page or populate form fields
  // For example:
  // orderDetails.so_number = orderData.soNumber
  // orderDetails.customerPOrder = orderData.customerPo
  // etc.
}

const openCustomerLookup = () => {
  showCustomerModal.value = true
}

const selectCustomer = async (customer) => {
  selectedCustomer.code = customer.customer_code
  selectedCustomer.name = customer.customer_name
  selectedCustomer.address = customer.address || ''
  selectedCustomer.salesperson = customer.salesperson_code || ''
  selectedCustomer.currency = customer.currency_code || 'IDR'
  
  // Update order details
  orderDetails.salesperson.code = customer.salesperson_code || ''
  orderDetails.currency = customer.currency_code || 'IDR'
  
  // Populate salesperson name immediately if code exists
  try {
    if (orderDetails.salesperson.code) {
      const spResponse = await fetch(`/api/sales-order/salesperson/${orderDetails.salesperson.code}`)
      const spData = await spResponse.json()
      if (spData.success && spData.data) {
        orderDetails.salesperson.name = spData.data.name || ''
      } else {
        orderDetails.salesperson.name = ''
      }
    } else {
      orderDetails.salesperson.name = ''
    }
  } catch (spErr) {
    console.warn('Could not fetch salesperson details on select:', spErr)
    orderDetails.salesperson.name = ''
  }
  
  // Reset previously selected Master Card when customer changes
  Object.assign(selectedMasterCard, {
    seq: '',
    model: '',
    status: '',
    approval: '',
    partNo: '',
    compNo: '',
    pDesign: ''
  })

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
      
      // Reset previously selected Master Card when customer validated
      Object.assign(selectedMasterCard, {
        seq: '',
        model: '',
        status: '',
        approval: '',
        partNo: '',
        compNo: '',
        pDesign: ''
      })

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
  selectedMcs.value = null
  showMcsTableModal.value = true
  fetchMcsData()
}

const selectMcs = (mc) => {
  if (!mc) return
  
  // Normalize possible API field names to match the expected format
  const seq = mc.seq || mc.mc_seq || mc.mc_sequence || ''
  const model = mc.model || mc.mc_model || ''
  const status = mc.status || mc.sts || ''
  const approval = mc.mc_approval || mc.approval || 'No'
  const partNo = mc.part || mc.part_no || mc.part_num || ''
  const compNo = mc.comp || mc.comp_no || mc.component || ''
  const pDesign = mc.p_design || mc.pd || ''
  
  selectedMasterCard.seq = String(seq)
  selectedMasterCard.model = String(model)
  selectedMasterCard.status = String(status)
  selectedMasterCard.approval = String(approval)
  selectedMasterCard.partNo = String(partNo)
  selectedMasterCard.compNo = String(compNo)
  selectedMasterCard.pDesign = String(pDesign)
  
  showMcsTableModal.value = false
  
  // Show appropriate message based on approval status
  if (selectedMasterCard.approval === 'Yes') {
    success('Master card selected successfully - Approved and ready for use')
  } else {
    success('Master card selected successfully - Not yet approved')
  }
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
      selectedMasterCard.approval = masterCard.mc_approval || 'No'
      selectedMasterCard.partNo = masterCard.part_no || ''
      selectedMasterCard.compNo = masterCard.comp_no || ''
      selectedMasterCard.pDesign = masterCard.p_design || ''
      
      // Show appropriate message based on approval status
      if (selectedMasterCard.approval === 'Yes') {
        success('Master card validated successfully - Approved and ready for use')
      } else {
        success('Master card validated successfully - Not yet approved')
      }
    } else {
      error('Master card not found')
      selectedMasterCard.model = ''
      selectedMasterCard.status = ''
      selectedMasterCard.approval = ''
      selectedMasterCard.partNo = ''
      selectedMasterCard.compNo = ''
      selectedMasterCard.pDesign = ''
    }
  } catch (err) {
    console.error('Error validating master card:', err)
    error('Error validating master card: ' + (err.message || 'Network error'))
    selectedMasterCard.model = ''
    selectedMasterCard.status = ''
    selectedMasterCard.approval = ''
    selectedMasterCard.partNo = ''
    selectedMasterCard.compNo = ''
    selectedMasterCard.pDesign = ''
  }
}


const mapMcsRows = (rows) => {
  if (!Array.isArray(rows)) return []
  return rows.map(r => ({
    seq: r.seq ?? r.mc_seq ?? r.mc_sequence ?? '',
    model: r.model ?? r.mc_model ?? '',
    part: r.part ?? r.part_no ?? r.part_num ?? '',
    comp: r.comp ?? r.comp_no ?? r.comp_num ?? r.component ?? '',
    p_design: r.p_design ?? r.pd ?? '',
    ext_dim_1: r.ext_dim_1 ?? r.ed_l ?? r.ext_l ?? '',
    ext_dim_2: r.ext_dim_2 ?? r.ed_w ?? r.ext_w ?? '',
    ext_dim_3: r.ext_dim_3 ?? r.ed_h ?? r.ext_h ?? '',
    int_dim_1: r.int_dim_1 ?? r.id_l ?? '',
    int_dim_2: r.int_dim_2 ?? r.id_w ?? '',
    int_dim_3: r.int_dim_3 ?? r.id_h ?? '',
    status: r.status ?? r.sts ?? '',
    mc_approval: r.mc_approval ?? r.approval ?? 'No',
  }))
}

const fetchMcsData = async (page = 1) => {
  mcsLoading.value = true
  mcsError.value = ''

  // Validate customer account must exist before fetching data
  if (!selectedCustomer.code) {
    mcsError.value = "Please select Customer Account (AC#) first."
    mcsMasterCards.value = []
    mcsLoading.value = false
    return
  }

  try {
    // Build query parameters exactly like Update MC
    let statusQuery = ""
    if (mcsStatusFilter.value === "Act") {
      statusQuery = "&status[]=Act"
    } else if (mcsStatusFilter.value === "Obsolete") {
      statusQuery = "&status[]=Obsolete"
    }

    // Filter by customer account - REQUIRED
    const customerFilter = `&customer_code=${encodeURIComponent(selectedCustomer.code)}`

    // Make API call using the same endpoint as Update MC
    const response = await fetch(
      `/api/update-mc/master-cards?page=${page}&query=${encodeURIComponent(
        mcsSearchTerm.value
      )}&sortBy=${mcsSortOption.value}&sortOrder=${
        mcsSortOrder.value
      }${statusQuery}${customerFilter}`,
      {
        headers: {
          Accept: "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
        credentials: "same-origin",
      }
    )

    if (!response.ok) {
      throw new Error(
        `Server returned ${response.status}: ${response.statusText}`
      )
    }

    const responseData = await response.json()
    console.log("MC data response:", responseData)

    // Process response - handle different response formats
    if (responseData.data && Array.isArray(responseData.data)) {
      mcsMasterCards.value = mapMcsRows(responseData.data)
      mcsCurrentPage.value = responseData.current_page || 1
      mcsLastPage.value = responseData.last_page || 1
    } else if (Array.isArray(responseData)) {
      mcsMasterCards.value = mapMcsRows(responseData)
      mcsCurrentPage.value = 1
      mcsLastPage.value = 1
    } else {
      mcsMasterCards.value = []
      mcsCurrentPage.value = 1
      mcsLastPage.value = 1
    }
  } catch (e) {
    console.error("Error fetching MC data:", e)
    mcsError.value = e?.message || 'Failed to load Master Cards'
    mcsMasterCards.value = []
  } finally {
    mcsLoading.value = false
  }
}

const goToMcsPage = (page) => {
  if (page < 1 || page > mcsLastPage.value) return
  mcsCurrentPage.value = page
  fetchMcsData(page)
}

const updateSearchTerm = (val) => {
  mcsSearchTerm.value = val || ''
}

const updateSortOption = (val) => {
  mcsSortOption.value = val || 'mc_seq'
}

const handleZoomChange = () => {}
const loadMasterCardsForCustomer = async () => {
  // Intentionally left blank to avoid auto-filling MC when customer changes
}

// Add ref for P/Order Date input
const pOrderDateInput = ref(null)

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
    // Close explicit calendar state after selection
    calendarExplicitlyOpened.value = false
    console.log('Date changed to:', orderDetails.pOrderDate, 'Day:', dayOfWeek.value)
  }
}

// Removed aggressive focus/click prevention handlers; rely on native picker

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
    
    // Debug CSRF token
    const csrfToken = (window.getCsrfToken && window.getCsrfToken()) || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
    console.log('CSRF Token:', csrfToken ? 'Found' : 'Missing')
    console.log('CSRF Token Value:', csrfToken.substring(0, 20) + '...')
    console.log('Request URL:', '/api/sales-order/product-design')
    console.log('Request Method:', 'POST')
    
    const response = await fetch('/api/sales-order/product-design', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': csrfToken
      },
      credentials: 'same-origin',
      body: JSON.stringify(requestData)
    })
    
    console.log('Response status:', response.status)
    console.log('Response headers:', Object.fromEntries(response.headers.entries()))
    
    if (!response.ok) {
      const text = await response.text()
      throw new Error(`Request failed (${response.status}). ${text?.slice(0, 120)}`)
    }
    const data = await response.json()
    
    if (data.success) {
  console.log('Product design saved:', designData)

  // Persist key values from design to order details for SO creation
  try {
    const mainItem = Array.isArray(designData.items) ? designData.items[0] : null
    if (mainItem) {
      if (mainItem.unitPrice != null) {
        orderDetails.unitPrice = Number(mainItem.unitPrice) || 0
      }
      if (mainItem.quantity != null) {
        orderDetails.setQuantity = Number(mainItem.quantity) || 0
      }
      if (mainItem.unit) {
        orderDetails.uom = mainItem.unit
      }
    }
  } catch (e) {
    console.warn('Failed to map product design values to order details:', e)
  }

  showProductDesignModal.value = false
  success('Product design saved successfully')
  
  // Fetch customer alternate delivery location data and populate orderDetails
  await fetchCustomerAlternateDeliveryData()
  
  // After saving product design, open Delivery Location modal
  setTimeout(() => {
    showDeliveryLocationModal.value = true
  }, 400)
    } else {
      throw new Error(data.message || 'Failed to save product design')
    }
  } catch (err) {
    console.error('Error saving product design:', err)
    error('Error saving product design: ' + (err.message || 'Network error'))
  }
}

const fetchCustomerAlternateDeliveryData = async () => {
  try {
    const customerCode = selectedCustomer.code
    if (!customerCode) {
      console.log('No customer code available for fetching alternate delivery data')
      return
    }

    console.log('Fetching customer alternate delivery data for:', customerCode)
    
    // First, get the main customer account data
    const customerResponse = await fetch(`/api/sales-order/customer/${customerCode}`)
    const customerData = await customerResponse.json()
    
    let mainCustomerData = null
    if (customerData?.success && customerData.data) {
      mainCustomerData = customerData.data
    }
    
    // Then, get alternate addresses
    const response = await fetch(`/api/customer-alternate-addresses/${customerCode}`)
    const data = await response.json()
    
    if (Array.isArray(data) && data.length > 0) {
      // Use the first alternate address if multiple exist
      const alternateAddress = data[0]
      
      // Populate orderDetails with delivery location data from customer alternate delivery location table
      orderDetails.deliveryLocation = {
        orderBy: {
          name: mainCustomerData?.customer_name || selectedCustomer.name || '',
          address: mainCustomerData?.address || selectedCustomer.address || '',
          email: mainCustomerData?.co_email || mainCustomerData?.email || selectedCustomer.email || '',
          contact: mainCustomerData?.contact_person || mainCustomerData?.contact || selectedCustomer.contact || ''
        },
        billTo: {
          name: alternateAddress.bill_to_name || mainCustomerData?.customer_name || selectedCustomer.name || '',
          address: alternateAddress.bill_to_address || mainCustomerData?.address || selectedCustomer.address || '',
          email: mainCustomerData?.co_email || mainCustomerData?.email || selectedCustomer.email || '',
          contact: mainCustomerData?.contact_person || mainCustomerData?.contact || selectedCustomer.contact || ''
        },
        shipTo: {
          code: alternateAddress.delivery_code || '',
          name: alternateAddress.ship_to_name || mainCustomerData?.customer_name || selectedCustomer.name || '',
          address: alternateAddress.ship_to_address || mainCustomerData?.address || selectedCustomer.address || '',
          email: alternateAddress.email || mainCustomerData?.co_email || mainCustomerData?.email || selectedCustomer.email || '',
          contact: alternateAddress.contact_person || mainCustomerData?.contact_person || mainCustomerData?.contact || selectedCustomer.contact || ''
        }
      }
      
      console.log('Customer alternate delivery data populated:', orderDetails.deliveryLocation)
      success('Customer delivery location data loaded from alternate address table')
    } else {
      // No alternate address found, use main customer data
      orderDetails.deliveryLocation = {
        orderBy: {
          name: mainCustomerData?.customer_name || selectedCustomer.name || '',
          address: mainCustomerData?.address || selectedCustomer.address || '',
          email: mainCustomerData?.co_email || mainCustomerData?.email || selectedCustomer.email || '',
          contact: mainCustomerData?.contact_person || mainCustomerData?.contact || selectedCustomer.contact || ''
        },
        billTo: {
          name: mainCustomerData?.customer_name || selectedCustomer.name || '',
          address: mainCustomerData?.address || selectedCustomer.address || '',
          email: mainCustomerData?.co_email || mainCustomerData?.email || selectedCustomer.email || '',
          contact: mainCustomerData?.contact_person || mainCustomerData?.contact || selectedCustomer.contact || ''
        },
        shipTo: {
          code: '',
          name: mainCustomerData?.customer_name || selectedCustomer.name || '',
          address: mainCustomerData?.address || selectedCustomer.address || '',
          email: mainCustomerData?.co_email || mainCustomerData?.email || selectedCustomer.email || '',
          contact: mainCustomerData?.contact_person || mainCustomerData?.contact || selectedCustomer.contact || ''
        }
      }
      
      console.log('No alternate address found, using main customer data')
    }
  } catch (err) {
    console.error('Error fetching customer alternate delivery data:', err)
    // Fallback to main customer data
    orderDetails.deliveryLocation = {
      orderBy: {
        name: selectedCustomer.name || '',
        address: selectedCustomer.address || '',
        email: selectedCustomer.email || '',
        contact: selectedCustomer.contact || ''
      },
      billTo: {
        name: selectedCustomer.name || '',
        address: selectedCustomer.address || '',
        email: selectedCustomer.email || '',
        contact: selectedCustomer.contact || ''
      },
      shipTo: {
        code: '',
        name: selectedCustomer.name || '',
        address: selectedCustomer.address || '',
        email: selectedCustomer.email || '',
        contact: selectedCustomer.contact || ''
      }
    }
    
    console.log('Error occurred, using fallback customer data')
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
    
    // Persist full structure into orderDetails to be used on SO creation
    orderDetails.deliveryLocation = {
      orderBy: {
        name: locationData.orderBy?.customerName || '',
        address: locationData.orderBy?.address || '',
        email: locationData.orderBy?.email || '',
        contact: locationData.orderBy?.contact || ''
      },
      billTo: {
        name: locationData.billTo?.customerName || '',
        address: locationData.billTo?.address || '',
        email: locationData.billTo?.email || '',
        contact: locationData.billTo?.contact || ''
      },
      shipTo: {
        code: locationData.shipTo?.deliveryCode || '',
        name: locationData.shipTo?.customerName || '',
        address: locationData.shipTo?.address || '',
        email: locationData.shipTo?.email || '',
        contact: locationData.shipTo?.contact || ''
      }
    }
    // Backward compatible single field for quick view
    orderDetails.deliveryAddress = orderDetails.deliveryLocation.shipTo.address || orderDetails.deliveryLocation.billTo.address
    
  showDeliveryLocationModal.value = false
  success('Delivery location saved successfully')

  // Create Sales Order first, then proceed to Delivery Schedule
  try {
    const soResponse = await createSalesOrder()
    if (soResponse?.success) {
      orderDetails.so_number = soResponse.so_number
      success(`Sales Order ${soResponse.so_number} created successfully!`)
      
      // After creating SO, proceed to Delivery Schedule
      setTimeout(() => {
        showDeliveryScheduleModal.value = true
      }, 300)
    } else {
      error('Failed to create Sales Order: ' + (soResponse?.message || 'Unknown error'))
    }
  } catch (err) {
    console.error('Error creating Sales Order:', err)
    error('Error creating Sales Order: ' + (err.message || 'Network error'))
  }
  } catch (err) {
    console.error('Error saving delivery location:', err)
    error('Error saving delivery location: ' + (err.message || 'Network error'))
  }
}

const saveDeliverySchedule = async (scheduleData) => {
  try {
    console.log('saveDeliverySchedule called with data:', scheduleData)
    console.log('scheduleData.entries:', scheduleData.entries)
    console.log('Array.isArray(scheduleData.entries):', Array.isArray(scheduleData.entries))
    
    // Validate required data
    if (!scheduleData.entries || !Array.isArray(scheduleData.entries)) {
      console.error('Validation failed: entries missing or not array')
      error('Delivery schedule entries are required')
      return
    }
    
    // First, we need to create the Sales Order if it doesn't exist
    let soNumber = orderDetails.so_number
    console.log('Current SO number:', soNumber)
    
    if (!soNumber) {
      console.log('No SO number found, creating new Sales Order...')
      // Create the Sales Order first
      const soResponse = await createSalesOrder()
      console.log('SO creation response:', soResponse)
      if (!soResponse.success) {
        error('Failed to create Sales Order: ' + soResponse.message)
        return
      }
      soNumber = soResponse.so_number
      orderDetails.so_number = soNumber
      console.log('New SO number:', soNumber)
    }
    
    // Now save the delivery schedule (entries already validated by modal)
    const requestData = {
      so_number: soNumber,
      entries: scheduleData.entries
    }
    
    // Debug CSRF token for delivery schedule
    const csrfToken = (window.getCsrfToken && window.getCsrfToken()) || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
    console.log('Delivery Schedule - CSRF Token:', csrfToken ? 'Found' : 'Missing')
    console.log('Delivery Schedule - Request data:', requestData)
    
    const response = await fetch('/api/sales-order/delivery-schedule', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': csrfToken
      },
      credentials: 'same-origin',
      body: JSON.stringify(requestData)
    })
    
    console.log('Delivery Schedule - Response status:', response.status)
    
    if (!response.ok) {
      const text = await response.text()
      throw new Error(`Request failed (${response.status}). ${text?.slice(0, 120)}`)
    }
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
    // Note: We allow proceeding even if master card is not approved
    // The approval status is shown as a warning to the user
    if (selectedMasterCard.approval !== 'Yes') {
      info('Warning: Selected Master Card is not approved yet. Proceeding with sales order creation.')
    }
    
    if (!orderDetails.pOrderDate) {
      error('Purchase order date is required')
      return
    }
    
    const requestData = {
      customer_code: selectedCustomer.code,
      master_card_seq: selectedMasterCard.seq,
      master_card_approval: selectedMasterCard.approval || 'No',
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
      set_quantity: (orderDetails.setQuantity !== undefined && orderDetails.setQuantity !== null)
        ? String(orderDetails.setQuantity)
        : '',
      details: [
        {
          line_number: 1,
          item_code: orderDetails.product.code,
          item_description: orderDetails.product.description,
          order_quantity: parseFloat(orderDetails.setQuantity) || 0,
          unit_price: parseFloat(orderDetails.unitPrice) || 0,
          uom: orderDetails.uom,
          remark: orderDetails.remark
        }
      ]
    }
    
    const response = await fetch('/api/sales-order', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': (window.getCsrfToken && window.getCsrfToken()) || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      },
      credentials: 'same-origin',
      body: JSON.stringify(requestData)
    })
    
    if (!response.ok) {
      const text = await response.text()
      throw new Error(`Request failed (${response.status}). ${text?.slice(0, 120)}`)
    }
    const data = await response.json()
    
    if (data.success) {
      // Note: Removed duplicate save to legacy SO table to prevent data duplication
      // The main API endpoint '/api/sales-order' should handle all necessary data storage
      console.log('Sales Order created successfully:', data.data.so_number)
      
      return {
        success: true,
        so_number: data.data.so_number,
        data: data.data
      }
    } else {
      if (data.errors) {
        const errorMessages = Object.values(data.errors).flat().join(', ')
        return {
          success: false,
          message: 'Validation errors: ' + errorMessages
        }
      } else {
        return {
          success: false,
          message: data.message || 'Failed to create sales order'
        }
      }
    }
  } catch (err) {
    console.error('Error creating sales order:', err)
    return {
      success: false,
      message: 'Error creating sales order: ' + (err.message || 'Network error')
    }
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
  
  // F6 to open Sales Order Table
  if (event.key === 'F6') {
    event.preventDefault()
    openSalesOrderTable()
  }
  
  // Ctrl/Cmd + P to print log
  if ((event.ctrlKey || event.metaKey) && event.key === 'p') {
    event.preventDefault()
    printLog()
  }
}


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
  
  // Setup keyboard shortcuts
  document.addEventListener('keydown', handleKeyDown)
  
  // Ensure date input is properly initialized
  await nextTick()
  console.log('PrepareMCSO component mounted successfully, date input ref:', pOrderDateInput.value)
  
            // Add event listener to prevent calendar from showing on other elements
      const dateInput = pOrderDateInput.value
      // We rely on native picker behavior; no extra listeners needed
})

// Cleanup on unmount
onUnmounted(() => {
  clearTimeout(window.customerValidationTimeout)
  clearTimeout(window.mcValidationTimeout)
  document.removeEventListener('keydown', handleKeyDown)
  
  // No calendar-related listeners registered
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

</style>