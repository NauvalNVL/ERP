<template>
  <Head title="Define SO Config" />
  <AppLayout header="Define SO Config">
    <div class="p-5">
      <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
          <div class="flex flex-col md:flex-row justify-between items-center">
            <div>
              <h2 class="text-xl font-bold text-white flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924-1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Define SO Config
              </h2>
              <p class="text-blue-100 mt-1">Configure Sales Order settings</p>
            </div>
          </div>
        </div>
        
        <form @submit.prevent="saveConfiguration" class="p-6">
          <!-- Notification Messages -->
          <transition name="fade">
            <div v-if="successMessage" class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-sm">
              <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ successMessage }}
              </div>
            </div>
          </transition>
          
          <transition name="fade">
            <div v-if="errorMessage" class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow-sm">
              <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                {{ errorMessage }}
              </div>
            </div>
          </transition>
          
          <!-- Tabs Section -->
          <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
              <button
                v-for="tabItem in tabs"
                :key="tabItem.name"
                @click="currentTab = tabItem.name"
                :class="[
                  currentTab === tabItem.name
                    ? 'border-blue-500 text-blue-600'
                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                  'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200'
                ]"
              >
                {{ tabItem.label }}
              </button>
            </nav>
          </div>

          <div class="mt-6">
            <!-- Order Type Tab Content -->
            <div v-if="currentTab === 'orderType'">
              <div class="bg-gray-50 p-5 rounded-lg shadow-sm border border-gray-100">
                <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                  </svg>
                  Activate Order Type
                </h3>
                <div class="space-y-2">
                  <label v-for="(type, index) in orderTypes" :key="index" class="flex items-center cursor-pointer">
                    <input type="checkbox" v-model="form.activateOrderType[type.value]" class="form-checkbox h-5 w-5 text-blue-600 rounded">
                    <span class="ml-2 text-gray-700">{{ type.label }}</span>
                  </label>
                </div>
              </div>

              <div class="bg-gray-50 p-5 rounded-lg shadow-sm border border-gray-100 mt-6">
                <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.104 0 2 .896 2 2s-.896 2-2 2-2-.896-2-2 .896-2 2-2zM20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2H2v-2a6 6 0 016-6h8a6 6 0 016 6v2h-2z" />
                  </svg>
                  Roll Stock Balance Check
                </h3>
                <div class="flex items-center space-x-6 mb-4">
                  <label class="inline-flex items-center cursor-pointer">
                    <input type="radio" v-model="form.checkRollBalance" value="Y-Check & Block" class="form-radio h-5 w-5 text-blue-600">
                    <span class="ml-2 text-gray-700">Y-Check & Block</span>
                  </label>
                  <label class="inline-flex items-center cursor-pointer">
                    <input type="radio" v-model="form.checkRollBalance" value="P-Check & Prompt" class="form-radio h-5 w-5 text-blue-600">
                    <span class="ml-2 text-gray-700">P-Check & Prompt</span>
                  </label>
                  <label class="inline-flex items-center cursor-pointer">
                    <input type="radio" v-model="form.checkRollBalance" value="N-No Check" class="form-radio h-5 w-5 text-blue-600">
                    <span class="ml-2 text-gray-700">N-No Check</span>
                  </label>
                </div>
                
                <div class="space-y-2 mb-4">
                  <label v-for="(type, index) in orderTypes" :key="index" class="flex items-center cursor-pointer">
                    <input type="checkbox" v-model="form.rollStockBalanceCheck[type.value]" class="form-checkbox h-5 w-5 text-blue-600 rounded">
                    <span class="ml-2 text-gray-700">{{ type.label }}</span>
                  </label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label for="compute_roll_stock" class="block text-sm font-medium text-gray-700">Compute Roll Stock Balance:</label>
                    <div class="mt-1 flex items-center">
                      <input type="number" id="compute_roll_stock" v-model="form.computeRollStockBalance" class="block w-20 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                      <span class="ml-2 text-gray-500">[10-99 minutes interval]</span>
                    </div>
                  </div>
                  <div>
                    <label for="compute_wip_sales_orders" class="block text-sm font-medium text-gray-700">Compute WIP Sales Orders:</label>
                    <div class="mt-1 flex items-center">
                      <input type="number" id="compute_wip_sales_orders" v-model="form.computeWIPSalesOrders" class="block w-20 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                      <span class="ml-2 text-gray-500">[10-99 minutes interval]</span>
                    </div>
                  </div>
                </div>

                <button type="button" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">
                  Activate Products & Product Designs
                </button>
              </div>

              <div class="bg-gray-50 p-5 rounded-lg shadow-sm border border-gray-100 mt-6">
                <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 5H7a2 2 0 00-2 2m0 0H2v10a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2h-2m-4 0v4m-4 0v4m-4 0v4" />
                  </svg>
                  Blanket + JIT Order
                </h3>
                <div class="flex items-center space-x-6">
                  <label class="inline-flex items-center cursor-pointer">
                    <input type="radio" v-model="form.jitSOPONo" value="0" class="form-radio h-5 w-5 text-blue-600">
                    <span class="ml-2 text-gray-700">0-Default using Blanket PO# and locked</span>
                  </label>
                  <label class="inline-flex items-center cursor-pointer">
                    <input type="radio" v-model="form.jitSOPONo" value="1" class="form-radio h-5 w-5 text-blue-600">
                    <span class="ml-2 text-gray-700">1-Default using Blankey PO# and allow to amend</span>
                  </label>
                </div>
              </div>
            </div>

            <!-- New SO Tab Content -->
            <div v-else-if="currentTab === 'newSO'">
              <!-- Overall Section -->
              <div class="bg-gray-50 p-5 rounded-lg shadow-sm border border-gray-100 mb-6">
                <h3 class="font-semibold text-gray-800 mb-4">Overall</h3>
                <div class="space-y-4">
                  <div class="flex items-center justify-between">
                    <label class="text-gray-700 w-1/3">Check Duplicate PO:</label>
                    <div class="flex space-x-4 w-2/3">
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.checkDuplicatePO" value="Y-Check & Block" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Y-Check & Block</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.checkDuplicatePO" value="P-Check & Prompt" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">P-Check & Prompt</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.checkDuplicatePO" value="N-No Check" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">N-No Check [For block required 2nd user approval]</span>
                      </label>
                    </div>
                  </div>

                  <div class="flex items-center justify-between">
                    <label class="text-gray-700 w-1/3">Check M/Card MOQ:</label>
                    <div class="flex space-x-4 w-2/3">
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.checkMCardMOQ" value="Y-Check & Block" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Y-Check & Block</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.checkMCardMOQ" value="P-Check & Prompt" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">P-Check & Prompt</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.checkMCardMOQ" value="N-No Check" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">N-No Check [For Order Type = S1/S3/N3/N4]</span>
                      </label>
                    </div>
                  </div>

                  <div class="flex items-center justify-between">
                    <label class="text-gray-700 w-1/3">Check SO Delivery Date:</label>
                    <div class="flex space-x-4 w-2/3">
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.checkSODeliveryDate" value="Y-Check & Block" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Y-Check & Block</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.checkSODeliveryDate" value="P-Check & Prompt" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">P-Check & Prompt</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.checkSODeliveryDate" value="N-No Check" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">N-No Check</span>
                      </label>
                    </div>
                  </div>

                  <div class="flex items-center justify-between">
                    <label class="text-gray-700 w-1/3">Check SO Delivery Remark:</label>
                    <div class="flex space-x-4 w-2/3">
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.checkSODeliveryRemark" value="Y-Check & Block" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Y-Check & Block</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.checkSODeliveryRemark" value="P-Check & Prompt" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">P-Check & Prompt</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.checkSODeliveryRemark" value="N-No Check" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">N-No Check</span>
                      </label>
                    </div>
                  </div>

                  <div class="flex items-center justify-between">
                    <label class="text-gray-700 w-1/3">Check Sales SO Price:</label>
                    <div class="flex space-x-4 w-2/3">
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.checkSalesSOPrice" value="Y-Check & Block" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Y-Check & Block</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.checkSalesSOPrice" value="P-Check & Prompt" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">P-Check & Prompt</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.checkSalesSOPrice" value="N-No Check" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">N-No Check</span>
                      </label>
                    </div>
                  </div>

                  <div class="flex items-center justify-between">
                    <label class="text-gray-700 w-1/3">Check Non-Sales SO Price:</label>
                    <div class="flex space-x-4 w-2/3">
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.checkNonSalesSOPrice" value="Y-Check & Block" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Y-Check & Block</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.checkNonSalesSOPrice" value="P-Check & Prompt" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">P-Check & Prompt</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.checkNonSalesSOPrice" value="N-No Check" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">N-No Check</span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>

              <!-- SO with Order Type = S1, S3, N1, N3 and N4 Section -->
              <div class="bg-gray-50 p-5 rounded-lg shadow-sm border border-gray-100 mb-6">
                <h3 class="font-semibold text-gray-800 mb-4">SO with Order Type = S1, S3, N1, N3 and N4</h3>
                <div class="space-y-4">
                  <div class="flex items-center justify-between">
                    <label class="text-gray-700 w-1/3">Rough-Cut Capacity Mode:</label>
                    <div class="flex space-x-4 w-2/3">
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.roughCutCapacityMode" value="1-Linear Meter" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">1-Linear Meter</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.roughCutCapacityMode" value="2-Piece" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">2-Piece</span>
                      </label>
                    </div>
                  </div>

                  <div class="flex items-center justify-between">
                    <label class="text-gray-700 w-1/3">SO vs Rough-Cut Capacity:</label>
                    <div class="flex space-x-4 w-2/3">
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.soVsRoughCutCapacity" value="Y-Check & Block" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Y-Check & Block</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.soVsRoughCutCapacity" value="P-Check & Prompt" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">P-Check & Prompt</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.soVsRoughCutCapacity" value="N-No Check" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">N-No Check</span>
                      </label>
                    </div>
                  </div>

                  <div class="flex items-center justify-between">
                    <label class="text-gray-700 w-1/3">Check CORR Econ. Run Size:</label>
                    <div class="flex space-x-4 w-2/3">
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.checkCORREconRunSize" value="Y-Check & Block" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Y-Check & Block</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.checkCORREconRunSize" value="P-Check & Prompt" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">P-Check & Prompt</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.checkCORREconRunSize" value="N-No Check" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">N-No Check</span>
                      </label>
                      <input type="text" v-model="form.newSO.minCORRLMOrder" class="ml-2 w-20 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                      <span class="ml-2 text-gray-500">Linear Meters</span>
                    </div>
                  </div>

                  <div class="flex items-center justify-between">
                    <label class="text-gray-700 w-1/3">Check Min. CORR LM Order:</label>
                    <div class="flex space-x-4 w-2/3">
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.checkMinCORRLMOrder" value="Y-Check & Block" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Y-Check & Block</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.checkMinCORRLMOrder" value="P-Check & Prompt" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">P-Check & Prompt</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.checkMinCORRLMOrder" value="N-No Check" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">N-No Check</span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>

              <!-- SO with Order Type = S2 (JIT) Section -->
              <div class="bg-gray-50 p-5 rounded-lg shadow-sm border border-gray-100 mb-6">
                <h3 class="font-semibold text-gray-800 mb-4">SO with Order Type = S2 (JIT)</h3>
                <div class="space-y-4">
                  <div class="flex items-center justify-between">
                    <label class="text-gray-700 w-1/3">JIT Qty more than Blanket:</label>
                    <div class="flex space-x-4 w-2/3">
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.jitQtyMoreThanBlanket" value="Y-Check & Block" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Y-Check & Block</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.jitQtyMoreThanBlanket" value="P-Check & Prompt" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">P-Check & Prompt</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.jitQtyMoreThanBlanket" value="N-No Check" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">N-No Check</span>
                      </label>
                    </div>
                  </div>

                  <div class="flex items-center justify-between">
                    <label class="text-gray-700 w-1/3">JIT Qty more than WO:</label>
                    <div class="flex space-x-4 w-2/3">
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.jitQtyMoreThanWO" value="Y-Check & Block" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Y-Check & Block</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.jitQtyMoreThanWO" value="P-Check & Prompt" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">P-Check & Prompt</span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Master Card SO Section -->
              <div class="bg-gray-50 p-5 rounded-lg shadow-sm border border-gray-100 mb-6">
                <h3 class="font-semibold text-gray-800 mb-4">Master Card SO</h3>
                <div class="space-y-4">
                  <div class="flex items-center justify-between">
                    <label class="text-gray-700 w-1/3">Retain SO Entry Details:</label>
                    <div class="flex space-x-4 w-2/3">
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.retainSOEntryDetailsMC" value="Y-Yes" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Y-Yes</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.retainSOEntryDetailsMC" value="N-No" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">N-No</span>
                      </label>
                    </div>
                  </div>

                  <div class="flex items-center justify-between">
                    <label class="text-gray-700 w-1/3">Check Unapproved M/Card:</label>
                    <div class="flex space-x-4 w-2/3">
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.checkUnapprovedMCard" value="Y-Check & Block" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Y-Check & Block</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.checkUnapprovedMCard" value="P-Check & Prompt" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">P-Check & Prompt</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.checkUnapprovedMCard" value="N-No Check" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">N-No Check</span>
                      </label>
                    </div>
                  </div>

                  <div class="flex items-center justify-between">
                    <label for="default_printer_mc" class="text-gray-700 w-1/3">Default Printer:</label>
                    <div class="flex items-center space-x-2 w-2/3">
                      <input type="text" id="default_printer_mc" v-model="form.newSO.defaultPrinterMC" class="flex-grow rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                      <button type="button" class="px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors duration-200" @click="openPrinterModal('defaultPrinterMC')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                          <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM13 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H13zM13 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H13z" />
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Sheet Board SO Section -->
              <div class="bg-gray-50 p-5 rounded-lg shadow-sm border border-gray-100 mb-6">
                <h3 class="font-semibold text-gray-800 mb-4">Sheet Board SO</h3>
                <div class="space-y-4">
                  <div class="flex items-center justify-between">
                    <label class="text-gray-700 w-1/3">Retain SO Entry Details:</label>
                    <div class="flex space-x-4 w-2/3">
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.retainSOEntryDetailsSB" value="Y-Yes" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Y-Yes</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.retainSOEntryDetailsSB" value="N-No" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">N-No</span>
                      </label>
                    </div>
                  </div>

                  <div class="flex items-center justify-between">
                    <label class="text-gray-700 w-1/3">Setup SB SO MSP:</label>
                    <div class="flex space-x-4 w-2/3">
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.setupSBSOMSP" value="Y-Yes" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Y-Yes</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.setupSBSOMSP" value="N-No" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">N-No</span>
                      </label>
                    </div>
                  </div>

                  <div class="flex items-center justify-between">
                    <label for="default_sb_so_msp_choice" class="text-gray-700 w-1/3">Default SB SO MSP Choice:</label>
                    <div class="flex items-center space-x-2 w-2/3">
                      <input type="text" id="default_sb_so_msp_choice" v-model="form.newSO.defaultSBSOMSPChoice" class="flex-grow rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                      <button type="button" class="px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors duration-200" @click="openMspChoiceModal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                          <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM13 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H13zM13 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H13z" />
                        </svg>
                      </button>
                    </div>
                  </div>

                  <div class="flex items-center justify-between">
                    <label class="text-gray-700 w-1/3">Auto Approve SB M/Card:</label>
                    <div class="flex space-x-4 w-2/3">
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.autoApproveSBMCard" value="Y-Yes" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Y-Yes</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.newSO.autoApproveSBMCard" value="N-No" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">N-No</span>
                      </label>
                    </div>
                  </div>

                  <div class="flex items-center justify-between">
                    <label for="default_printer_sb" class="text-gray-700 w-1/3">Default Printer:</label>
                    <div class="flex items-center space-x-2 w-2/3">
                      <input type="text" id="default_printer_sb" v-model="form.newSO.defaultPrinterSB" class="flex-grow rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                      <button type="button" class="px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors duration-200" @click="openPrinterModal('defaultPrinterSB')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                          <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM13 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H13zM13 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H13z" />
                        </svg>
                      </button>
                    </div>
                  </div>

                  <div class="flex items-center justify-end mt-4">
                    <button 
                      type="button" 
                      @click="openSOOrderHelpModal" 
                      class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200 flex items-center"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9.295a8.01 8.01 0 011.967-1.353m-1.967 1.353l-6.228 4.208a1.32 1.32 0 00.106 2.052l.216.144a1.32 1.32 0 001.884-.131l2.497-3.238m-.944 1.488a8.013 8.013 0 01-1.967-1.353m-1.967 1.353l6.228 4.208a1.32 1.32 0 00.106 2.052l.216.144a1.32 1.32 0 001.884-.131l2.497-3.238" />
                      </svg>
                      SO Order Help
                    </button>
                  </div>

                </div>
              </div>
            </div>

            <!-- Amend SO Tab Content -->
            <div v-else-if="currentTab === 'amendSO'">
              <!-- All Types of SO Section -->
              <div class="bg-gray-50 p-5 rounded-lg shadow-sm border border-gray-100 mb-6">
                <h3 class="font-semibold text-gray-800 mb-4">All Types of SO</h3>
                <div class="space-y-4">
                  <div class="flex items-center justify-between">
                    <label class="text-gray-700 w-1/3">Allow to Increase Qty:</label>
                    <div class="flex space-x-4 w-2/3">
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.amendSO.allowIncreaseQty" value="Y-Allowed" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Y-Allowed</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.amendSO.allowIncreaseQty" value="N-Not Allowed" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">N-Not Allowed</span>
                      </label>
                    </div>
                  </div>

                  <div class="flex items-center justify-between">
                    <label class="text-gray-700 w-1/3">Allow to Decrease Qty:</label>
                    <div class="flex space-x-4 w-2/3">
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.amendSO.allowDecreaseQty" value="Y-Allowed" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Y-Allowed</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.amendSO.allowDecreaseQty" value="N-Not Allowed" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">N-Not Allowed</span>
                      </label>
                    </div>
                  </div>

                  <div class="flex items-center justify-between">
                    <label for="default_analysis_code" class="text-gray-700 w-1/3">Default Analysis Code:</label>
                    <div class="flex items-center space-x-2 w-2/3">
                      <input type="text" id="default_analysis_code" v-model="form.amendSO.defaultAnalysisCode" class="flex-grow rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                      <button type="button" class="px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors duration-200" @click="openAnalysisCodeModal('amend')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                          <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM13 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H13zM13 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H13z" />
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- SO with Order Type = S1, S3, N1, N3 and N4 Section -->
              <div class="bg-gray-50 p-5 rounded-lg shadow-sm border border-gray-100 mb-6">
                <h3 class="font-semibold text-gray-800 mb-4">SO with Order Type = S1, S3, N1, N3 and N4</h3>
                <div class="space-y-4">
                  <div class="flex items-center justify-between">
                    <label class="text-gray-700 w-1/3">WO Qty Less Than SO Qty:</label>
                    <div class="flex space-x-4 w-2/3">
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.amendSO.woQtyLessThanSOQty" value="Y-Check & Block" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Y-Check & Block</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.amendSO.woQtyLessThanSOQty" value="P-Check & Prompt" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">P-Check & Prompt</span>
                      </label>
                    </div>
                  </div>

                  <div class="flex items-center justify-between">
                    <label class="text-gray-700 w-1/3">WO Qty More Than SO Qty:</label>
                    <div class="flex space-x-4 w-2/3">
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.amendSO.woQtyMoreThanSOQty" value="Y-Check & Block" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Y-Check & Block</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.amendSO.woQtyMoreThanSOQty" value="P-Check & Prompt" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">P-Check & Prompt</span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>

              <!-- SO with Order Type = N3 (Blanket) Section -->
              <div class="bg-gray-50 p-5 rounded-lg shadow-sm border border-gray-100 mb-6">
                <h3 class="font-semibold text-gray-800 mb-4">SO with Order Type = N3 (Blanket)</h3>
                <div class="space-y-4">
                  <div class="flex items-center justify-between">
                    <label class="text-gray-700 w-1/3">SO Qty Less Than WO or JIT Qty:</label>
                    <div class="flex space-x-4 w-2/3">
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.amendSO.soQtyLessThanWOJITQty" value="Y-Check & Block" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Y-Check & Block</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.amendSO.soQtyLessThanWOJITQty" value="P-Check & Prompt" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">P-Check & Prompt</span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>

              <!-- SO with Order Type = S2 (JIT) Section -->
              <div class="bg-gray-50 p-5 rounded-lg shadow-sm border border-gray-100 mb-6">
                <h3 class="font-semibold text-gray-800 mb-4">SO with Order Type = S2 (JIT)</h3>
                <div class="space-y-4">
                  <div class="flex items-center justify-between">
                    <label class="text-gray-700 w-1/3">JIT Qty more than Blanket:</label>
                    <div class="flex space-x-4 w-2/3">
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.amendSO.jitQtyMoreThanBlanket" value="Y-Check & Block" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Y-Check & Block</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.amendSO.jitQtyMoreThanBlanket" value="N-Not Allowed" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">N-Not Allowed</span>
                      </label>
                    </div>
                  </div>

                  <div class="flex items-center justify-between">
                    <label class="text-gray-700 w-1/3">JIT Qty more than WO:</label>
                    <div class="flex space-x-4 w-2/3">
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.amendSO.jitQtyMoreWO" value="Y-Allowed" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Y-Allowed</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.amendSO.jitQtyMoreWO" value="N-Not Allowed" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">N-Not Allowed</span>
                      </label>
                    </div>
                  </div>

                  <div class="flex items-center justify-between">
                    <label class="text-gray-700 w-1/3">Amend PO#:</label>
                    <div class="flex space-x-4 w-2/3">
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.amendSO.amendPO" value="Y-Allowed" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Y-Allowed</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.amendSO.amendPO" value="N-Not Allowed" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">N-Not Allowed</span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Cancel SO Tab Content -->
            <div v-else-if="currentTab === 'cancelSO'">
              <!-- All Types of SO Section -->
              <div class="bg-gray-50 p-5 rounded-lg shadow-sm border border-gray-100 mb-6">
                <h3 class="font-semibold text-gray-800 mb-4">All Types of SO</h3>
                <div class="space-y-4">
                  <div class="flex items-center justify-between">
                    <label class="text-gray-700 w-1/3">When FG has Received:</label>
                    <div class="flex space-x-4 w-2/3">
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.cancelSO.whenFGHasReceived" value="Y-Check & Block" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Y-Check & Block</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.cancelSO.whenFGHasReceived" value="P-Check & Prompt" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">P-Check & Prompt</span>
                      </label>
                    </div>
                  </div>

                  <div class="flex items-center justify-between">
                    <label for="cancel_so_default_analysis_code" class="text-gray-700 w-1/3">Default Analysis Code:</label>
                    <div class="flex items-center space-x-2 w-2/3">
                      <input type="text" id="cancel_so_default_analysis_code" v-model="form.cancelSO.defaultAnalysisCode" class="flex-grow rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                      <button type="button" class="px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors duration-200" @click="openAnalysisCodeModal('cancel')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                          <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM13 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H13zM13 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H13z" />
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- SO with Order Type = N3 (Blanket) Section -->
              <div class="bg-gray-50 p-5 rounded-lg shadow-sm border border-gray-100 mb-6">
                <h3 class="font-semibold text-gray-800 mb-4">SO with Order Type = N3 (Blanket)</h3>
                <div class="space-y-4">
                  <div class="flex items-center justify-between">
                    <label class="text-gray-700 w-1/3">When JIT SO Total Not Fulfilled:</label>
                    <div class="flex space-x-4 w-2/3">
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.cancelSO.whenJITSOIncomplete" value="Y-Check & Block" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Y-Check & Block</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.cancelSO.whenJITSOIncomplete" value="P-Check & Prompt" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">P-Check & Prompt</span>
                      </label>
                    </div>
                  </div>

                  <div class="flex items-center justify-between">
                    <label class="text-gray-700 w-1/3">When Active JIT SO Found:</label>
                    <div class="flex space-x-4 w-2/3">
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.cancelSO.whenActiveJITSOFound" value="Y-Check & Block" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Y-Check & Block</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.cancelSO.whenActiveJITSOFound" value="P-Check & Prompt" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">P-Check & Prompt</span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>

              <!-- SO with Order Type = S1, S3, N1, N3 and N4 Section -->
              <div class="bg-gray-50 p-5 rounded-lg shadow-sm border border-gray-100 mb-6">
                <h3 class="font-semibold text-gray-800 mb-4">SO with Order Type = S1, S3, N1, N3 and N4</h3>
                <div class="space-y-4">
                  <div class="flex items-center justify-between">
                    <label class="text-gray-700 w-1/3">When Active WO Found:</label>
                    <div class="flex space-x-4 w-2/3">
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.cancelSO.whenActiveWOFound" value="Y-Check & Block" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Y-Check & Block</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.cancelSO.whenActiveWOFound" value="P-Check & Prompt" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">P-Check & Prompt</span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Close SO Tab Content -->
            <div v-else-if="currentTab === 'closeSO'">
              <!-- All Types of SO Section -->
              <div class="bg-gray-50 p-5 rounded-lg shadow-sm border border-gray-100 mb-6">
                <h3 class="font-semibold text-gray-800 mb-4">All Types of SO</h3>
                <div class="space-y-4">
                  <div class="flex items-center justify-between">
                    <label class="text-gray-700 w-1/3">When FG has Received:</label>
                    <div class="flex space-x-4 w-2/3">
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.closeSO.whenFGHasReceived" value="Y-Check & Block" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Y-Check & Block</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.closeSO.whenFGHasReceived" value="P-Check & Prompt" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">P-Check & Prompt</span>
                      </label>
                    </div>
                  </div>

                  <div class="flex items-center justify-between">
                    <label for="close_so_default_analysis_code" class="text-gray-700 w-1/3">Default Analysis Code:</label>
                    <div class="flex items-center space-x-2 w-2/3">
                      <input type="text" id="close_so_default_analysis_code" v-model="form.closeSO.defaultAnalysisCode" class="flex-grow rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                      <button type="button" class="px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors duration-200" @click="openAnalysisCodeModal('close')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                          <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM13 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H13zM13 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H13z" />
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- SO with Order Type = N3 (Blanket) Section -->
              <div class="bg-gray-50 p-5 rounded-lg shadow-sm border border-gray-100 mb-6">
                <h3 class="font-semibold text-gray-800 mb-4">SO with Order Type = N3 (Blanket)</h3>
                <div class="space-y-4">
                  <div class="flex items-center justify-between">
                    <label class="text-gray-700 w-1/3">When JIT SO Total Not Fulfilled:</label>
                    <div class="flex space-x-4 w-2/3">
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.closeSO.whenJITSOIncomplete" value="Y-Check & Block" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Y-Check & Block</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.closeSO.whenJITSOIncomplete" value="P-Check & Prompt" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">P-Check & Prompt</span>
                      </label>
                    </div>
                  </div>

                  <div class="flex items-center justify-between">
                    <label class="text-gray-700 w-1/3">When Active JIT SO Found:</label>
                    <div class="flex space-x-4 w-2/3">
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.closeSO.whenActiveJITSOFound" value="Y-Check & Block" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Y-Check & Block</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.closeSO.whenActiveJITSOFound" value="P-Check & Prompt" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">P-Check & Prompt</span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>

              <!-- SO with Order Type = S1, S3, N1, N3 and N4 Section -->
              <div class="bg-gray-50 p-5 rounded-lg shadow-sm border border-gray-100 mb-6">
                <h3 class="font-semibold text-gray-800 mb-4">SO with Order Type = S1, S3, N1, N3 and N4</h3>
                <div class="space-y-4">
                  <div class="flex items-center justify-between">
                    <label class="text-gray-700 w-1/3">When Active WO Found:</label>
                    <div class="flex space-x-4 w-2/3">
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.closeSO.whenActiveWOFound" value="Y-Check & Block" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Y-Check & Block</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.closeSO.whenActiveWOFound" value="P-Check & Prompt" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">P-Check & Prompt</span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- SO to DMS Tab Content -->
            <div v-else-if="currentTab === 'soToDMS'">
              <!-- All Types of SO Section -->
              <div class="bg-gray-50 p-5 rounded-lg shadow-sm border border-gray-100 mb-6">
                <h3 class="font-semibold text-gray-800 mb-4">Auto DMS</h3>
                <div class="space-y-4">
                  <div class="flex items-center justify-between">
                    <label class="text-gray-700 w-1/3">Auto DMS:</label>
                    <div class="flex space-x-4 w-2/3">
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.soToDMS.autoDMS" value="Yes" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Yes</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.soToDMS.autoDMS" value="No" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">No</span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Note Section -->
              <div class="bg-gray-50 p-5 rounded-lg shadow-sm border border-gray-100 mb-6">
                <h3 class="font-semibold text-gray-800 mb-4">Note:</h3>
                <p class="text-gray-700 mb-2">Auto DMS is only allowed for New SO with CC approved.</p>
                <p class="text-gray-700">Any SO Amendment or Cancellation, user need to run DMS order Amendment or Remove manually</p>
              </div>
            </div>

            <!-- SO to WO Tab Content -->
            <div v-else-if="currentTab === 'soToWO'">
              <!-- SO with Order Type = S1, S3, N1, N3 and N4 Section -->
              <div class="bg-gray-50 p-5 rounded-lg shadow-sm border border-gray-100 mb-6">
                <h3 class="font-semibold text-gray-800 mb-4">SO with Order Type = S1, S3, N1, N3 and N4</h3>
                <div class="space-y-4">
                  <div class="flex items-center justify-between">
                    <label class="text-gray-700 w-1/3">Auto WO:</label>
                    <div class="flex space-x-4 w-2/3">
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.soToWO.autoWO" value="Y-Yes and Run Size based on PM Configuration" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Y-Yes and Run Size based on PM Configuration</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.soToWO.autoWO" value="X-No WO" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">X-No WO</span>
                      </label>
                    </div>
                  </div>

                  <div class="flex items-center justify-between">
                    <label class="text-gray-700 w-1/3">SO to WO:</label>
                    <div class="flex space-x-4 w-2/3">
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.soToWO.soToWOType" value="Y-Yes 1 SO Delivery Date to 1 WO" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Y-Yes 1 SO Delivery Date to 1 WO</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.soToWO.soToWOType" value="X-Always 1 SO to 1 WO" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">X-Always 1 SO to 1 WO</span>
                      </label>
                    </div>
                  </div>

                  <div class="flex items-center justify-between">
                    <label for="date_interval" class="text-gray-700 w-1/3">Date Interval:</label>
                    <div class="flex items-center space-x-2 w-2/3">
                      <input type="number" id="date_interval" v-model="form.soToWO.dateInterval" class="flex-grow rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                      <span class="ml-2 text-gray-700">Days, Combining Multiple Delivery Dates into 1 WO</span>
                    </div>
                  </div>

                  <div class="flex items-center justify-between">
                    <label class="text-gray-700 w-1/3">Print WO:</label>
                    <div class="flex space-x-4 w-2/3">
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.soToWO.printWO" value="Y-Yes Print Immediately" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Y-Yes Print Immediately</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" v-model="form.soToWO.printWO" value="X-Print Manually" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">X-Print Manually</span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Others Tab Content -->
            <div v-else-if="currentTab === 'others'">
              <div class="bg-gray-50 p-5 rounded-lg shadow-sm border border-gray-100">
                <h3 class="font-semibold text-gray-800 mb-4">Blank Form</h3>
                <div class="space-y-4">
                  <div class="flex items-center justify-between">
                    <label for="so_details" class="text-gray-700 w-1/3">SO Details:</label>
                    <div class="flex items-center space-x-2 w-2/3">
                      <input type="number" id="so_details" v-model="form.others.soDetails" class="w-20 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                      <span class="ml-2 text-gray-700">01-10 copies</span>
                    </div>
                  </div>
                  <div class="flex items-center justify-between">
                    <label for="so_credit" class="text-gray-700 w-1/3">SO Credit:</label>
                    <div class="flex items-center space-x-2 w-2/3">
                      <input type="number" id="so_credit" v-model="form.others.soCredit" class="w-20 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                      <span class="ml-2 text-gray-700">01-10 copies</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div v-else>
              <p class="text-gray-600">Content for {{ currentTab }} tab will go here.</p>
            </div>
          </div>
          
          <!-- Buttons -->
          <div class="flex justify-end gap-3 mt-8 border-t pt-6">
            <button 
              type="button" 
              @click="resetForm"
              class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors duration-200 flex items-center"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
              </svg>
              Reset
            </button>
            <button 
              type="submit" 
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200 flex items-center"
              :disabled="processing"
            >
              <svg v-if="!processing" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              <svg v-else class="animate-spin h-4 w-4 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              {{ processing ? 'Saving...' : 'Save Changes' }}
            </button>
          </div>
        </form>
      </div>
    </div>
    <PrinterListModal v-if="showPrinterModal" @printer-selected="handlePrinterSelected" @close="closePrinterModal" />
    <MSPChoiceModal v-if="showMspChoiceModal" @choice-selected="handleMspChoiceSelected" @close="closeMspChoiceModal" />
    <SOOrderHelpModal v-if="showSOOrderHelpModal" @close="closeSOOrderHelpModal" />
    <AnalysisCodeModal v-if="showAnalysisCodeModal" @analysis-code-selected="handleAnalysisCodeSelected" @close="closeAnalysisCodeModal" :modalType="currentAnalysisCodeModalType" />
  </AppLayout>
</template>

<script>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';
import PrinterListModal from '@/Components/PrinterListModal.vue';
import MSPChoiceModal from '@/Components/MSPChoiceModal.vue';
import SOOrderHelpModal from '@/Components/SOOrderHelpModal.vue';
import AnalysisCodeModal from '@/Components/AnalysisCodeModal.vue';

export default {
  components: {
    Head,
    AppLayout,
    PrinterListModal,
    MSPChoiceModal,
    SOOrderHelpModal,
    AnalysisCodeModal,
  },
  props: {
    configuration: Object,
  },
  data() {
    return {
      processing: false,
      successMessage: '',
      errorMessage: '',
      currentTab: 'orderType',
      showPrinterModal: false,
      showMspChoiceModal: false,
      showSOOrderHelpModal: false,
      showAnalysisCodeModal: false,
      currentAnalysisCodeModalType: '',
      currentPrinterField: '',
      tabs: [
        { name: 'orderType', label: 'Order Type' },
        { name: 'newSO', label: 'New SO' },
        { name: 'amendSO', label: 'Amend SO' },
        { name: 'cancelSO', label: 'Cancel SO' },
        { name: 'closeSO', label: 'Close SO' },
        { name: 'soToDMS', label: 'SO to DMS' },
        { name: 'soToWO', label: 'SO to WO' },
        { name: 'others', label: 'Others' },
      ],
      orderTypes: [
        { label: 'S1-Sales from SO >> WO >> CORR >> CONV >> FG >> DO >> IV', value: 'S1-Sales from SO >> WO >> CORR >> CONV >> FG >> DO >> IV' },
        { label: 'S2-Sales from SO >> DO >> IV (Kanban/JIT)', value: 'S2-Sales from SO >> DO >> IV (Kanban/JIT)' },
        { label: 'S3-Sales from SO >> WO >> CONV >> FG >> DO >> IV', value: 'S3-Sales from SO >> WO >> CONV >> FG >> DO >> IV' },
        { label: 'N1-Non-Sales from SO >> WO >> CORR >> CONV >> FG >> DO', value: 'N1-Non-Sales from SO >> WO >> CORR >> CONV >> FG >> DO' },
        { label: 'N2-Non-Sales from SO >> DO (IFDC)', value: 'N2-Non-Sales from SO >> DO (IFDC)' },
        { label: 'N3-Non-Sales from SO >> WO >> CORR >> CONV >> FG (Blanket)', value: 'N3-Non-Sales from SO >> WO >> CORR >> CONV >> FG (Blanket)' },
        { label: 'N4-Non-Sales from SO >> WO >> CORR (Sheet Stock)', value: 'N4-Non-Sales from SO >> WO >> CORR (Sheet Stock)' }
      ],
      form: {
        activateOrderType: { ...this.configuration.activateOrderType },
        checkRollBalance: this.configuration.checkRollBalance,
        rollStockBalanceCheck: { ...this.configuration.rollStockBalanceCheck },
        computeRollStockBalance: this.configuration.computeRollStockBalance,
        computeWIPSalesOrders: this.configuration.computeWIPSalesOrders,
        jitSOPONo: this.configuration.jitSOPONo,
        newSO: {
          checkDuplicatePO: 'P-Check & Prompt',
          checkMCardMOQ: 'P-Check & Prompt',
          checkSODeliveryDate: 'N-No Check',
          checkSODeliveryRemark: 'N-No Check',
          checkSalesSOPrice: 'P-Check & Prompt',
          checkNonSalesSOPrice: 'P-Check & Prompt',
          roughCutCapacityMode: '1-Linear Meter',
          soVsRoughCutCapacity: 'P-Check & Prompt',
          checkCORREconRunSize: 'P-Check & Prompt',
          minCORRLMOrder: '50',
          checkMinCORRLMOrder: 'P-Check & Prompt',
          jitQtyMoreThanBlanket: 'P-Check & Prompt',
          jitQtyMoreThanWO: 'P-Check & Prompt',
          retainSOEntryDetailsMC: 'Y-Yes',
          checkUnapprovedMCard: 'P-Check & Prompt',
          defaultPrinterMC: 'PRINTER01',
          retainSOEntryDetailsSB: 'Y-Yes',
          setupSBSOMSP: 'Y-Yes',
          defaultSBSOMSPChoice: 'SB',
          autoApproveSBMCard: 'Y-Yes',
          defaultPrinterSB: 'PRINTER01',
        },
        amendSO: {
          allowIncreaseQty: 'Y-Allowed',
          allowDecreaseQty: 'Y-Allowed',
          defaultAnalysisCode: 'AM01',
          woQtyLessThanSOQty: 'P-Check & Prompt',
          woQtyMoreThanSOQty: 'P-Check & Prompt',
          soQtyLessThanWOJITQty: 'P-Check & Prompt',
          jitQtyMoreThanBlanket: 'Y-Allowed',
          jitQtyMoreWO: 'Y-Allowed',
          amendPO: 'Y-Allowed',
        },
        cancelSO: {
          whenFGHasReceived: 'P-Check & Prompt',
          whenJITSOIncomplete: 'P-Check & Prompt',
          whenActiveJITSOFound: 'P-Check & Prompt',
          whenActiveWOFound: 'P-Check & Prompt',
          defaultAnalysisCode: 'CL01',
        },
        closeSO: {
          whenFGHasReceived: 'P-Check & Prompt',
          defaultAnalysisCode: 'CL01',
          whenJITSOIncomplete: 'P-Check & Prompt',
          whenActiveJITSOFound: 'P-Check & Prompt',
          whenActiveWOFound: 'P-Check & Prompt',
        },
        soToDMS: {
          closeSOMode: 'N-No Check',
          whenSOFGQtyLessThan: 0,
          defaultAnalysisCode: 'CM02',
          whenWOStatusIsActive: 'P-Check & Prompt',
          whenJITSOTotalNotFulfilled: 'P-Check & Prompt',
          autoDMS: 'No',
        },
        soToWO: {
          autoWO: 'X-No WO',
          soToWOType: 'X-Always 1 SO to 1 WO',
          dateInterval: 0,
          printWO: 'X-Print Manually',
        },
        others: {
          soDetails: 1,
          soCredit: 1,
        },
      },
    };
  },
  methods: {
    async saveConfiguration() {
      this.processing = true;
      this.successMessage = '';
      this.errorMessage = '';

      try {
        const response = await axios.post('/api/so-config', this.form);
        this.successMessage = response.data.message;
      } catch (error) {
        if (error.response && error.response.data && error.response.data.message) {
          this.errorMessage = error.response.data.message;
        } else {
          this.errorMessage = 'An unexpected error occurred.';
        }
        console.error('Error saving configuration:', error);
      } finally {
        this.processing = false;
        setTimeout(() => {
          this.successMessage = '';
          this.errorMessage = '';
        }, 3000);
      }
    },
    resetForm() {
      this.form = {
        activateOrderType: {
          'S1-Sales from SO >> WO >> CORR >> CONV >> FG >> DO >> IV': false,
          'S2-Sales from SO >> DO >> IV (Kanban/JIT)': false,
          'S3-Sales from SO >> WO >> CONV >> FG >> DO >> IV': false,
          'N1-Non-Sales from SO >> WO >> CORR >> CONV >> FG >> DO': false,
          'N2-Non-Sales from SO >> DO (IFDC)': false,
          'N3-Non-Sales from SO >> WO >> CORR >> CONV >> FG (Blanket)': false,
          'N4-Non-Sales from SO >> WO >> CORR (Sheet Stock)': false
        },
        checkRollBalance: 'N-No Check',
        rollStockBalanceCheck: {
          'S1-Sales from SO >> WO >> CORR >> CONV >> FG >> DO >> IV': false,
          'S2-Sales from SO >> DO >> IV (Kanban/JIT)': false,
          'S3-Sales from SO >> WO >> CONV >> FG >> DO >> IV': false,
          'N1-Non-Sales from SO >> WO >> CORR >> CONV >> FG >> DO': false,
          'N3-Non-Sales from SO >> WO >> CORR >> CONV >> FG (Blanket)': false,
          'N4-Non-Sales from SO >> WO >> CORR (Sheet Stock)': false
        },
        computeRollStockBalance: 10,
        computeWIPSalesOrders: 10,
        jitSOPONo: '0',
        newSO: {
          checkDuplicatePO: 'P-Check & Prompt',
          checkMCardMOQ: 'P-Check & Prompt',
          checkSODeliveryDate: 'N-No Check',
          checkSODeliveryRemark: 'N-No Check',
          checkSalesSOPrice: 'P-Check & Prompt',
          checkNonSalesSOPrice: 'P-Check & Prompt',
          roughCutCapacityMode: '1-Linear Meter',
          soVsRoughCutCapacity: 'P-Check & Prompt',
          checkCORREconRunSize: 'P-Check & Prompt',
          minCORRLMOrder: '50',
          checkMinCORRLMOrder: 'P-Check & Prompt',
          jitQtyMoreThanBlanket: 'P-Check & Prompt',
          jitQtyMoreThanWO: 'P-Check & Prompt',
          retainSOEntryDetailsMC: 'Y-Yes',
          checkUnapprovedMCard: 'P-Check & Prompt',
          defaultPrinterMC: 'PRINTER01',
          retainSOEntryDetailsSB: 'Y-Yes',
          setupSBSOMSP: 'Y-Yes',
          defaultSBSOMSPChoice: 'SB',
          autoApproveSBMCard: 'Y-Yes',
          defaultPrinterSB: 'PRINTER01',
        },
        amendSO: {
          allowIncreaseQty: 'Y-Allowed',
          allowDecreaseQty: 'Y-Allowed',
          defaultAnalysisCode: 'AM01',
          woQtyLessThanSOQty: 'P-Check & Prompt',
          woQtyMoreThanSOQty: 'P-Check & Prompt',
          soQtyLessThanWOJITQty: 'P-Check & Prompt',
          jitQtyMoreThanBlanket: 'Y-Allowed',
          jitQtyMoreWO: 'Y-Allowed',
          amendPO: 'Y-Allowed',
        },
        cancelSO: {
          whenFGHasReceived: 'P-Check & Prompt',
          whenJITSOIncomplete: 'P-Check & Prompt',
          whenActiveJITSOFound: 'P-Check & Prompt',
          whenActiveWOFound: 'P-Check & Prompt',
          defaultAnalysisCode: 'CL01',
        },
        closeSO: {
          whenFGHasReceived: 'P-Check & Prompt',
          defaultAnalysisCode: 'CL01',
          whenJITSOIncomplete: 'P-Check & Prompt',
          whenActiveJITSOFound: 'P-Check & Prompt',
          whenActiveWOFound: 'P-Check & Prompt',
        },
        soToDMS: {
          closeSOMode: 'N-No Check',
          whenSOFGQtyLessThan: 0,
          defaultAnalysisCode: 'CM02',
          whenWOStatusIsActive: 'P-Check & Prompt',
          whenJITSOTotalNotFulfilled: 'P-Check & Prompt',
          autoDMS: 'No',
        },
        soToWO: {
          autoWO: 'X-No WO',
          soToWOType: 'X-Always 1 SO to 1 WO',
          dateInterval: 0,
          printWO: 'X-Print Manually',
        },
        others: {
          soDetails: 1,
          soCredit: 1,
        },
      };
    },
    openPrinterModal(field) {
      this.currentPrinterField = field;
      this.showPrinterModal = true;
    },
    handlePrinterSelected(printerCode) {
      if (this.currentPrinterField === 'defaultPrinterMC') {
        this.form.newSO.defaultPrinterMC = printerCode;
      } else if (this.currentPrinterField === 'defaultPrinterSB') {
        this.form.newSO.defaultPrinterSB = printerCode;
      }
      this.showPrinterModal = false;
    },
    closePrinterModal() {
      this.showPrinterModal = false;
    },
    openMspChoiceModal() {
      this.showMspChoiceModal = true;
    },
    handleMspChoiceSelected(choice) {
      this.form.newSO.defaultSBSOMSPChoice = choice;
      this.showMspChoiceModal = false;
    },
    closeMspChoiceModal() {
      this.showMspChoiceModal = false;
    },
    openSOOrderHelpModal() {
      this.showSOOrderHelpModal = true;
    },
    closeSOOrderHelpModal() {
      this.showSOOrderHelpModal = false;
    },
    openAnalysisCodeModal(type) {
      this.currentAnalysisCodeModalType = type;
      this.showAnalysisCodeModal = true;
    },
    handleAnalysisCodeSelected(code) {
      if (this.currentAnalysisCodeModalType === 'amend') {
        this.form.amendSO.defaultAnalysisCode = code;
      } else if (this.currentAnalysisCodeModalType === 'cancel') {
        this.form.cancelSO.defaultAnalysisCode = code;
      } else if (this.currentAnalysisCodeModalType === 'close') {
        this.form.closeSO.defaultAnalysisCode = code;
      } else if (this.currentAnalysisCodeModalType === 'dms') {
        this.form.soToDMS.defaultAnalysisCode = code;
      }
      this.showAnalysisCodeModal = false;
    },
    closeAnalysisCodeModal() {
      this.showAnalysisCodeModal = false;
    },
  },
  mounted() {
    // Optionally load data when component is mounted, if not already passed via props
    // this.fetchConfiguration();
  }
};
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */ {
  opacity: 0;
}
</style>

