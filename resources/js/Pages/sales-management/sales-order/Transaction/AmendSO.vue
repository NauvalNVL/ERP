<template>
  <AppLayout header="Amend SO">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
      <!-- Header with controls -->
      <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <i class="fas fa-edit text-2xl text-orange-600"></i>
            <div>
              <h1 class="text-xl font-semibold text-gray-800">Amend SO</h1>
              <p class="text-xs text-gray-500">Modify existing sales orders</p>
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

              <!-- Analysis Code -->
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Analysis Code:</label>
                  <input 
                    v-model="selectedSO.analysisCode" 
                    type="text" 
                    class="w-full px-2 py-1 border border-gray-300 rounded text-sm bg-blue-100"
                    readonly
                  >
                </div>
                <div class="flex items-end">
                  <button 
                    @click="openAnalysisCodeModal" 
                    class="px-3 py-2 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition-colors"
                    title="Search Analysis Code"
                  >
                    <i class="fas fa-search"></i>
                  </button>
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
                    class="w-full px-2 py-1 border border-gray-300 rounded text-sm bg-gray-100"
                    readonly
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
                    class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm bg-gray-100"
                    readonly
                  >
                  <span class="text-xs text-gray-500">Leave blank for loose item order</span>
                </div>
              </div>

              <!-- Order Group -->
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Order Group:</label>
                  <div class="flex items-center space-x-2">
                    <input type="radio" name="orderGroup" value="Sales" v-model="selectedSO.orderGroup" disabled>
                    <label class="text-xs">Sales</label>
                    <input type="radio" name="orderGroup" value="Non-Sales" v-model="selectedSO.orderGroup" disabled>
                    <label class="text-xs">Non-Sales</label>
                  </div>
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Order Type:</label>
                  <select 
                    v-model="selectedSO.orderType" 
                    class="w-full px-2 py-1 border border-gray-300 rounded text-sm bg-gray-100"
                    disabled
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
                  class="w-full px-2 py-1 border border-gray-300 rounded text-sm bg-gray-100"
                  readonly
                >
              </div>

              <!-- Sales Tax -->
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Sales Tax:</label>
                  <div class="flex items-center space-x-2">
                    <input type="checkbox" v-model="selectedSO.salesTax" disabled>
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
                  class="w-full px-2 py-1 border border-gray-300 rounded text-sm bg-gray-100"
                  readonly
                >
              </div>

              <!-- Instructions -->
              <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Instruction1:</label>
                <input 
                  v-model="selectedSO.instruction1" 
                  type="text" 
                  class="w-full px-2 py-1 border border-gray-300 rounded text-sm bg-gray-100"
                  readonly
                >
              </div>

              <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Instruction2:</label>
                <input 
                  v-model="selectedSO.instruction2" 
                  type="text" 
                  class="w-full px-2 py-1 border border-gray-300 rounded text-sm"
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
                  @click="amendSalesOrder" 
                  class="px-4 py-2 bg-orange-600 text-white text-sm rounded hover:bg-orange-700 transition-colors"
                >
                  <i class="fas fa-edit mr-1"></i>
                  Amend SO
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

    <!-- Sales Order Search Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-xl w-11/12 max-w-6xl max-h-[90vh] overflow-hidden">
        <!-- Modal Header -->
        <div class="bg-blue-600 text-white px-6 py-4 flex items-center justify-between">
          <h3 class="text-lg font-semibold">Sales Order Table (Sorted by S/Order#)</h3>
          <button @click="closeModal" class="text-white hover:text-gray-200">
            <i class="fas fa-times text-xl"></i>
          </button>
        </div>

        <!-- Modal Content -->
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
          <!-- Sales Order Table -->
          <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300 text-sm">
              <thead>
                <tr class="bg-blue-100">
                  <th class="border border-gray-300 px-2 py-1 text-left font-semibold">SO#</th>
                  <th class="border border-gray-300 px-2 py-1 text-left font-semibold">CUSTOMER PO#</th>
                  <th class="border border-gray-300 px-2 py-1 text-left font-semibold">AC#</th>
                  <th class="border border-gray-300 px-2 py-1 text-left font-semibold">MC#</th>
                  <th class="border border-gray-300 px-2 py-1 text-left font-semibold">STATUS</th>
                  <th class="border border-gray-300 px-2 py-1 text-left font-semibold">D/LOCATION</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(order, index) in salesOrderList" :key="index" 
                    :class="selectedRowIndex === index ? 'bg-blue-200' : 'hover:bg-gray-50'"
                    @click="selectRow(index)"
                    class="cursor-pointer">
                  <td class="border border-gray-300 px-2 py-1">{{ order.soNumber }}</td>
                  <td class="border border-gray-300 px-2 py-1">{{ order.customerPO }}</td>
                  <td class="border border-gray-300 px-2 py-1">{{ order.acNumber }}</td>
                  <td class="border border-gray-300 px-2 py-1">{{ order.mcNumber }}</td>
                  <td class="border border-gray-300 px-2 py-1">
                    <span :class="getStatusClass(order.status)">{{ order.status }}</span>
                  </td>
                  <td class="border border-gray-300 px-2 py-1">{{ order.location }}</td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Customer Information Section -->
          <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <div class="mb-2">
                <label class="block text-xs font-medium text-gray-600 mb-1">Customer Name:</label>
                <input v-model="customerInfo.name" type="text" class="w-full px-2 py-1 border border-gray-300 rounded text-sm bg-gray-50" readonly>
              </div>
              <div class="mb-2">
                <label class="block text-xs font-medium text-gray-600 mb-1">Model:</label>
                <input v-model="customerInfo.model" type="text" class="w-full px-2 py-1 border border-gray-300 rounded text-sm bg-gray-50" readonly>
              </div>
              <div class="mb-2">
                <label class="block text-xs font-medium text-gray-600 mb-1">Order Mode:</label>
                <input v-model="customerInfo.orderMode" type="text" class="w-full px-2 py-1 border border-gray-300 rounded text-sm bg-gray-50" readonly>
              </div>
            </div>
            <div>
              <div class="mb-2">
                <label class="block text-xs font-medium text-gray-600 mb-1">Salesperson:</label>
                <input v-model="customerInfo.salesperson" type="text" class="w-full px-2 py-1 border border-gray-300 rounded text-sm bg-gray-50" readonly>
              </div>
              <div class="mb-2">
                <label class="block text-xs font-medium text-gray-600 mb-1">Order Group:</label>
                <input v-model="customerInfo.orderGroup" type="text" class="w-full px-2 py-1 border border-gray-300 rounded text-sm bg-gray-50" readonly>
              </div>
              <div class="mb-2">
                <label class="block text-xs font-medium text-gray-600 mb-1">Order Type:</label>
                <input v-model="customerInfo.orderType" type="text" class="w-full px-2 py-1 border border-gray-300 rounded text-sm bg-gray-50" readonly>
              </div>
            </div>
          </div>

          <!-- Order Items Table -->
          <div class="mt-6">
            <h4 class="text-sm font-semibold text-gray-700 mb-2">Order Items</h4>
            <div class="overflow-x-auto">
              <table class="w-full border-collapse border border-gray-300 text-sm">
                <thead>
                  <tr class="bg-blue-100">
                    <th class="border border-gray-300 px-2 py-1 text-left font-semibold">ITEM</th>
                    <th class="border border-gray-300 px-2 py-1 text-left font-semibold">MAIN</th>
                    <th class="border border-gray-300 px-2 py-1 text-left font-semibold">F1</th>
                    <th class="border border-gray-300 px-2 py-1 text-left font-semibold">F2</th>
                    <th class="border border-gray-300 px-2 py-1 text-left font-semibold">F3</th>
                    <th class="border border-gray-300 px-2 py-1 text-left font-semibold">F4</th>
                    <th class="border border-gray-300 px-2 py-1 text-left font-semibold">F5</th>
                    <th class="border border-gray-300 px-2 py-1 text-left font-semibold">F6</th>
                    <th class="border border-gray-300 px-2 py-1 text-left font-semibold">F7</th>
                    <th class="border border-gray-300 px-2 py-1 text-left font-semibold">F8</th>
                    <th class="border border-gray-300 px-2 py-1 text-left font-semibold">F9</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in orderItems" :key="index" class="hover:bg-gray-50">
                    <td class="border border-gray-300 px-2 py-1">{{ item.name }}</td>
                    <td class="border border-gray-300 px-2 py-1">{{ item.main }}</td>
                    <td class="border border-gray-300 px-2 py-1">{{ item.f1 }}</td>
                    <td class="border border-gray-300 px-2 py-1">{{ item.f2 }}</td>
                    <td class="border border-gray-300 px-2 py-1">{{ item.f3 }}</td>
                    <td class="border border-gray-300 px-2 py-1">{{ item.f4 }}</td>
                    <td class="border border-gray-300 px-2 py-1">{{ item.f5 }}</td>
                    <td class="border border-gray-300 px-2 py-1">{{ item.f6 }}</td>
                    <td class="border border-gray-300 px-2 py-1">{{ item.f7 }}</td>
                    <td class="border border-gray-300 px-2 py-1">{{ item.f8 }}</td>
                    <td class="border border-gray-300 px-2 py-1">{{ item.f9 }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3">
          <button @click="selectSalesOrder" class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors">
            Select
          </button>
          <button @click="closeModal" class="px-4 py-2 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition-colors">
            Exit
          </button>
        </div>
      </div>
    </div>

    <!-- Analysis Code Modal -->
    <div v-if="showAnalysisCodeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-xl w-11/12 max-w-4xl max-h-[80vh] overflow-hidden">
        <!-- Modal Header -->
        <div class="bg-blue-600 text-white px-6 py-3 flex items-center justify-between">
          <h3 class="text-lg font-semibold">Analysis Code Table</h3>
          <button @click="closeAnalysisCodeModal" class="text-white hover:text-gray-200">
            <i class="fas fa-times text-xl"></i>
          </button>
        </div>

        <!-- Modal Content -->
        <div class="p-6">
          <!-- Analysis Code Table -->
          <div class="overflow-x-auto mb-4">
            <table class="w-full border-collapse border border-gray-300 text-sm">
              <thead>
                <tr class="bg-blue-100">
                  <th class="border border-gray-300 px-3 py-2 text-left font-semibold">CODE</th>
                  <th class="border border-gray-300 px-3 py-2 text-left font-semibold">NAME</th>
                  <th class="border border-gray-300 px-3 py-2 text-left font-semibold">GROUP</th>
                  <th class="border border-gray-300 px-3 py-2 text-left font-semibold">GROUP2</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(code, index) in analysisCodeList" :key="index" 
                    :class="selectedAnalysisCodeIndex === index ? 'bg-blue-200' : 'hover:bg-gray-50'"
                    @click="selectAnalysisCodeRow(index)"
                    class="cursor-pointer">
                  <td class="border border-gray-300 px-3 py-2">{{ code.code }}</td>
                  <td class="border border-gray-300 px-3 py-2">{{ code.name }}</td>
                  <td class="border border-gray-300 px-3 py-2">{{ code.group }}</td>
                  <td class="border border-gray-300 px-3 py-2">{{ code.group2 }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3">
          <button 
            @click="selectAnalysisCode" 
            class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors"
          >
            Select
          </button>
          <button 
            @click="closeAnalysisCodeModal" 
            class="px-4 py-2 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition-colors"
          >
            Exit
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';

export default {
    name: 'AmendSO',
    components: {
        AppLayout
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
            showModal: false,
            selectedRowIndex: -1,
            showAnalysisCodeModal: false,
            selectedAnalysisCodeIndex: -1,
            analysisCodeList: [
                {
                    code: 'AMND',
                    name: 'EDIT HARGA SO MISS TRIM',
                    group: 'SH',
                    group2: 'AM'
                },
                {
                    code: 'AM03',
                    name: 'EDIT HARGA',
                    group: 'SO',
                    group2: 'AM'
                },
                {
                    code: 'DLVY',
                    name: 'DELIVERY DATE CHANGE',
                    group: 'SO',
                    group2: 'DL'
                },
                {
                    code: 'PRIO',
                    name: 'PRIORITY CHANGE',
                    group: 'SO',
                    group2: 'PR'
                },
                {
                    code: 'SPEC',
                    name: 'SPECIFICATION CHANGE',
                    group: 'SO',
                    group2: 'SP'
                }
            ],
            salesOrderList: [
                {
                    soNumber: '11-2025-00133',
                    customerPO: 'PO/MAS/2025/10/01763',
                    acNumber: '000552',
                    mcNumber: '301438',
                    status: 'Data',
                    location: 'Same'
                },
                {
                    soNumber: '11-2025-00132',
                    customerPO: 'PO/MAS/2025/10/01763',
                    acNumber: '000552',
                    mcNumber: '301438',
                    status: 'Data',
                    location: 'Same'
                },
                {
                    soNumber: '11-2025-00131',
                    customerPO: 'C387/PD/ISP/X/2025',
                    acNumber: '000754',
                    mcNumber: '278744',
                    status: 'Data',
                    location: 'J217'
                },
                {
                    soNumber: '11-2025-00130',
                    customerPO: 'C387/PD/ISP/X/2025',
                    acNumber: '000754',
                    mcNumber: '278744',
                    status: 'Data',
                    location: 'Same'
                },
                {
                    soNumber: '11-2025-00129',
                    customerPO: 'KDK125100/IB/2943268',
                    acNumber: '000553',
                    mcNumber: '000636/4',
                    status: 'Data',
                    location: 'Same'
                },
                {
                    soNumber: '11-2025-00128',
                    customerPO: 'KDK125100/IB/2943268',
                    acNumber: '000553',
                    mcNumber: '000636/4',
                    status: 'Data',
                    location: 'Same'
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
            this.showModal = true;
            this.selectedRowIndex = -1;
        },

        closeModal() {
            this.showModal = false;
            this.selectedRowIndex = -1;
        },

        selectRow(index) {
            this.selectedRowIndex = index;
        },

        selectSalesOrder() {
            if (this.selectedRowIndex >= 0) {
                const selectedOrder = this.salesOrderList[this.selectedRowIndex];
                this.selectedSO = {
                    soNumber: selectedOrder.soNumber,
                    seq: '133',
                    customerCode: selectedOrder.acNumber,
                    customerName: 'CMC GLOBAL SPORT, PT',
                    mcardSeq: selectedOrder.mcNumber + '-4',
                    orderMode: 'D-Order by Customer + Delivery & Invoice to Customer',
                    salesperson: 'S129',
                    salespersonName: 'MULTI NATIONAL COMPANY OIA',
                    product: '001',
                    productDescription: 'BOX',
                    currency: 'IDR',
                    exchangeRate: '0.000000',
                    exchangeMethod: 'N/A',
                    analysisCode: 'AMND',
                    orderStatus: 'Outstanding',
                    customerPO: selectedOrder.customerPO,
                    porderDate: '2025-10-27',
                    month: 'Mon',
                    setQuantity: '9600',
                    orderGroup: 'Sales',
                    orderType: 'S1',
                    lotNumber: '3-2851324',
                    salesTax: false,
                    remark: '2933268',
                    instruction1: '2933268',
                    instruction2: '',
                    soDate: new Date().toLocaleDateString(),
                    status: 'Active',
                    totalAmount: 150000
                };
                this.searchForm.soNumber = selectedOrder.soNumber;
                this.searchPerformed = true;
                this.closeModal();
            } else {
                alert('Please select a sales order from the list.');
            }
        },

        openAnalysisCodeModal() {
            this.showAnalysisCodeModal = true;
            this.selectedAnalysisCodeIndex = -1;
        },

        closeAnalysisCodeModal() {
            this.showAnalysisCodeModal = false;
            this.selectedAnalysisCodeIndex = -1;
        },

        selectAnalysisCodeRow(index) {
            this.selectedAnalysisCodeIndex = index;
        },

        selectAnalysisCode() {
            if (this.selectedAnalysisCodeIndex >= 0) {
                const selectedCode = this.analysisCodeList[this.selectedAnalysisCodeIndex];
                if (this.selectedSO) {
                    this.selectedSO.analysisCode = selectedCode.code;
                }
                this.closeAnalysisCodeModal();
            } else {
                alert('Please select an analysis code from the list.');
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

        async amendSalesOrder() {
            if (!this.amendForm.reason.trim()) {
                alert('Please enter a reason for amendment.');
                return;
            }

            if (confirm(`Are you sure you want to amend Sales Order ${this.selectedSO.soNumber}?\n\nReason: ${this.amendForm.reason}`)) {
                try {
                    // Simulate API call for amendment
                    const response = await fetch(`/api/sales-orders/${this.selectedSO.soNumber}/amend`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            reason: this.amendForm.reason,
                            deliveryDate: this.amendForm.deliveryDate,
                            priority: this.amendForm.priority,
                            amendmentDate: this.amendForm.amendmentDate
                        })
                    });
                    
                    if (response.ok) {
                        // Update status locally
                        this.selectedSO.status = 'Amended';
                        alert(`Sales Order ${this.selectedSO.soNumber} has been amended successfully.`);
                        this.clearSelection();
                    } else {
                        alert('Error amending sales order. Please try again.');
                    }
                } catch (error) {
                    console.error('Error amending sales order:', error);
                    alert('Error amending sales order. Please try again.');
                }
            }
        },

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
