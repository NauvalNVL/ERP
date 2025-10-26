<template>
  <AppLayout>
    <div class="min-h-screen bg-gray-50 py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-gray-900">Prepare SB SO</h1>
          <p class="mt-2 text-gray-600">Sales Board Sales Order Management</p>
        </div>

        <!-- Main Form -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
           <!-- Form Header -->
           <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
             <div class="flex items-center justify-between">
               <h2 class="text-xl font-semibold text-white">Prepare SB SO</h2>
               <div class="flex space-x-2">
                 <button
                   @click="printSOLog"
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors"
                 >
                   <i class="fas fa-print mr-2"></i>
                   Print SO Log
                 </button>
                 <button
                   @click="printSOJITTracking"
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors"
                 >
                   <i class="fas fa-print mr-2"></i>
                   Print SO JIT Tracking
                 </button>
               </div>
             </div>
           </div>

           <!-- Main Form Content -->
           <div class="p-6">
             <!-- Period and Customer Information -->
             <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
               <div>
                 <label class="block text-sm font-medium text-gray-700 mb-2">Current Period</label>
                 <div class="flex space-x-2">
                   <input
                     v-model="form.current_period"
                     type="number"
                     class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                     placeholder="9"
                   />
                   <input
                     v-model="form.current_year"
                     type="number"
                     class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                     placeholder="2025"
                   />
                 </div>
               </div>

               <div>
                 <label class="block text-sm font-medium text-gray-700 mb-2">Update Period</label>
                 <div class="flex space-x-2">
                   <input
                     v-model="form.update_period"
                     type="number"
                     class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                     placeholder="9"
                   />
                   <input
                     v-model="form.update_year"
                     type="number"
                     class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                     placeholder="2025"
                   />
                   <span class="text-sm text-gray-500 self-center">mm/yyyy</span>
                 </div>
               </div>

               <div>
                 <label class="block text-sm font-medium text-gray-700 mb-2">Forward Period</label>
                 <div class="flex space-x-2">
                   <input
                     v-model="form.forward_period"
                     type="number"
                     class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                     placeholder="1"
                   />
                   <span class="text-sm text-gray-500 self-center">Months</span>
                 </div>
               </div>

               <div>
                 <label class="block text-sm font-medium text-gray-700 mb-2">Backward Period</label>
                 <div class="flex space-x-2">
                   <input
                     v-model="form.backward_period"
                     type="number"
                     class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                     placeholder="1"
                   />
                   <span class="text-sm text-gray-500 self-center">Months</span>
                 </div>
               </div>
             </div>

             <!-- Customer Code -->
             <div class="mb-6">
               <label class="block text-sm font-medium text-gray-700 mb-2">Customer Code</label>
               <div class="flex space-x-2">
                 <input
                   v-model="form.customer_code"
                   type="text"
                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                   placeholder="000211-08"
                   @blur="validateCustomer"
                 />
                 <button
                   @click="openCustomerModal"
                   type="button"
                   class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
                 >
                   <i class="fas fa-search"></i>
                 </button>
               </div>
               <div v-if="customerInfo" class="mt-1 text-sm text-gray-600">
                 {{ customerInfo.customer_name }}
               </div>
             </div>

             <!-- Define Model Format Button -->
             <div class="mb-6">
               <button
                 @click="openDefineModelFormatModal"
                 type="button"
                 class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-medium transition-colors"
               >
                 Define Model Format
               </button>
             </div>

             <!-- Empty State (shown when no customer selected) -->
             <div v-if="!form.customer_code" class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
               <div class="text-gray-500">
                 <i class="fas fa-file-alt text-4xl mb-4"></i>
                 <p class="text-lg font-medium">Sales Board Sales Order Form</p>
                 <p class="text-sm mt-2">Select a customer above to begin creating your sales order</p>
               </div>
             </div>

             <!-- Main Form (shown when customer is selected) -->
             <div v-if="form.customer_code" class="border-t pt-6">
            <form @submit.prevent="saveOrder" class="p-6 space-y-8">
              <!-- Salesperson Information -->
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Salesperson</label>
                  <div class="flex space-x-2">
                    <input
                      v-model="form.salesperson_code"
                      type="text"
                      class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      placeholder="S111"
                      @blur="validateSalesperson"
                    />
                    <button
                      @click="openSalespersonModal"
                      type="button"
                      class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
                    >
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                  <div v-if="salespersonInfo" class="mt-1 text-sm text-gray-600">
                    {{ salespersonInfo.salesperson_name }}
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Cust.P/Order#</label>
                  <input
                    v-model="form.customer_p_order_number"
                    type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="70"
                  />
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">P/Order Date</label>
                  <input
                    v-model="form.p_order_date"
                    type="date"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  />
                </div>
              </div>

            <!-- Product Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Product Design</label>
                <div class="flex space-x-2">
                  <input
                    v-model="form.product_design"
                    type="text"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="CB-P"
                  />
                  <button
                    @click="openProductDesignModal"
                    type="button"
                    class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
                  >
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Flute</label>
                <div class="flex space-x-2">
                  <input
                    v-model="form.flute"
                    type="text"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="AB"
                  />
                  <button
                    @click="openPaperFluteModal"
                    type="button"
                    class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
                  >
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">S/O B/Quality</label>
                <div class="flex space-x-2">
                  <input
                    v-model="form.so_b_quality"
                    type="text"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="AGBK125"
                  />
                  <button
                    @click="openPaperQualityModal"
                    type="button"
                    class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
                  >
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>
            </div>

            <!-- Size Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Board Size</label>
                <div class="flex space-x-2">
                  <input
                    v-model="form.board_size_length"
                    type="text"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="70"
                    @input="calculateDimensions"
                  />
                  <span class="text-sm text-gray-500 self-center">L x</span>
                  <input
                    v-model="form.board_size_width"
                    type="text"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="5"
                    @input="calculateDimensions"
                  />
                  <span class="text-sm text-gray-500 self-center">W</span>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sheet Size</label>
                <div class="flex space-x-2">
                  <input
                    v-model="form.sheet_size_length"
                    type="text"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="70"
                    @input="calculateDimensions"
                  />
                  <span class="text-sm text-gray-500 self-center">L x</span>
                  <input
                    v-model="form.sheet_size_width"
                    type="text"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="5"
                    @input="calculateDimensions"
                  />
                  <span class="text-sm text-gray-500 self-center">W</span>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Paper Size</label>
                <div class="flex space-x-2">
                  <input
                    v-model="form.paper_size"
                    type="text"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="700"
                  />
                  <button
                    @click="openPaperSizeModal"
                    type="button"
                    class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
                  >
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">S/Tool</label>
                <input
                  v-model="form.s_tool"
                  type="text"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="1"
                />
              </div>
            </div>

            <!-- Order Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Order Quantity</label>
                <div class="flex space-x-2">
                  <input
                    v-model="form.order_quantity"
                    type="number"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="70"
                    @input="calculateDimensions"
                  />
                  <span class="text-sm text-gray-500 self-center">Pcs</span>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
                <select
                  v-model="form.currency"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                  <option value="IDR">IDR</option>
                  <option value="USD">USD</option>
                  <option value="EUR">EUR</option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Ex.Rate</label>
                <input
                  v-model="form.exchange_rate"
                  type="number"
                  step="0.0001"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="0.0000"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Lot Number</label>
                <input
                  v-model="form.lot_number"
                  type="text"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="50"
                />
              </div>
            </div>

            <!-- Additional Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Order Group</label>
                <div class="flex space-x-4">
                  <label class="flex items-center">
                    <input
                      v-model="form.order_group"
                      type="radio"
                      value="Sales"
                      class="mr-2"
                    />
                    Sales
                  </label>
                  <label class="flex items-center">
                    <input
                      v-model="form.order_group"
                      type="radio"
                      value="Non-Sales"
                      class="mr-2"
                    />
                    Non-Sales
                  </label>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Order Type</label>
                <select
                  v-model="form.order_type"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                  <option value="">Select Order Type</option>
                  <option value="S1-Sales (SO-Corr-Conv-FG-DO-IV)">S1-Sales (SO-Corr-Conv-FG-DO-IV)</option>
                  <option value="S2-Sales (SO-DO-IV Kanban/JIT)">S2-Sales (SO-DO-IV Kanban/JIT)</option>
                  <option value="S3-Sales (SO-Conv-FG-DO-IV)">S3-Sales (SO-Conv-FG-DO-IV)</option>
                  <option value="N1-NonSales (SO-Corr-Conv-FG-DO)">N1-NonSales (SO-Corr-Conv-FG-DO)</option>
                  <option value="N2-NonSales (SO-DO)">N2-NonSales (SO-DO)</option>
                  <option value="N3-NonSales (SO-Corr-Conv-FG)">N3-NonSales (SO-Corr-Conv-FG)</option>
                  <option value="N4-NonSales (SO-Corr)">N4-NonSales (SO-Corr)</option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
                <select
                  v-model="form.unit"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                  <option value="">Select Unit</option>
                  <option value="P-Price per Piece - Gross M2">P-Price per Piece - Gross M2</option>
                  <option value="Q-Price per Piece - Trimmed M2">Q-Price per Piece - Trimmed M2</option>
                </select>
              </div>
            </div>

            <!-- Text Areas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Remark</label>
                <textarea
                  v-model="form.remark"
                  rows="3"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Enter remarks..."
                ></textarea>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Instruction 1</label>
                <textarea
                  v-model="form.instruction_1"
                  rows="3"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Enter instruction 1..."
                ></textarea>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Instruction 2</label>
                <textarea
                  v-model="form.instruction_2"
                  rows="3"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Enter instruction 2..."
                ></textarea>
              </div>
            </div>

            <!-- Calculated Fields -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Area/Pcs</label>
                <div class="flex space-x-2">
                  <input
                    v-model="form.area_per_pcs"
                    type="number"
                    step="0.0001"
                    readonly
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-gray-50"
                    placeholder="0.0001"
                  />
                  <span class="text-sm text-gray-500 self-center">m²</span>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">L/Meter</label>
                <div class="flex space-x-2">
                  <input
                    v-model="form.l_meter"
                    type="number"
                    step="0.01"
                    readonly
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-gray-50"
                    placeholder="0"
                  />
                  <span class="text-sm text-gray-500 self-center">M</span>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Unit Price</label>
                <input
                  v-model="form.unit_price"
                  type="number"
                  step="0.0001"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="0.0000"
                />
              </div>
            </div>

              <!-- Sales Tax Checkbox -->
              <div class="flex items-center">
                <input
                  v-model="form.sales_tax"
                  type="checkbox"
                  id="sales_tax"
                  class="mr-2"
                />
                <label for="sales_tax" class="text-sm font-medium text-gray-700">
                  Tick for Y-Yes
                </label>
              </div>

              <!-- Form Action Buttons -->
              <div class="flex justify-end space-x-4">
                <button
                  @click="saveOrder"
                  :disabled="loading"
                  class="bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white px-6 py-2 rounded-md font-medium transition-colors"
                >
                  <i class="fas fa-save mr-2"></i>
                  {{ loading ? 'Saving...' : 'Save Order' }}
                </button>
                <button
                  @click="resetForm"
                  type="button"
                  class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-md font-medium transition-colors"
                >
                  <i class="fas fa-undo mr-2"></i>
                  Reset
                </button>
               </div>
             </form>
             </div>
           </div>
        </div>
      </div>
    </div>

    <!-- Modals -->
    <CustomerAccountModal
      :show="showCustomerModal"
      @close="showCustomerModal = false"
      @select="selectCustomer"
    />

    <SalespersonModal
      :show="showSalespersonModal"
      :salespersons="[]"
      @close="showSalespersonModal = false"
      @select="selectSalesperson"
    />

    <ProductDesignModal
      :show="showProductDesignModal"
      :designs="[]"
      @close="showProductDesignModal = false"
      @select="selectProductDesign"
    />

    <PaperFluteModal
      :show="showPaperFluteModal"
      :flutes="[]"
      @close="showPaperFluteModal = false"
      @select="selectPaperFlute"
    />

    <PaperQualityModal
      :show="showPaperQualityModal"
      :qualities="[]"
      @close="showPaperQualityModal = false"
      @select="selectPaperQuality"
    />

    <PaperSizeModal
      :show="showPaperSizeModal"
      :paperSizes="[]"
      @close="showPaperSizeModal = false"
      @select="selectPaperSize"
    />

    <!-- Define Model Format Modal -->
    <div
      v-if="showDefineModelFormatModal"
      class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
      @click="closeDefineModelFormatModal"
    >
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white" @click.stop>
        <div class="mt-3">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">Define Model Format</h3>
            <button
              @click="closeDefineModelFormatModal"
              class="text-gray-400 hover:text-gray-600"
            >
              <i class="fas fa-times"></i>
            </button>
          </div>
          
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Default Model Format:</label>
            <div class="flex items-center space-x-2">
              <input
                v-model="modelFormat.part1"
                type="text"
                class="w-12 px-2 py-1 border border-gray-300 rounded text-center"
                placeholder="1"
              />
              <span class="text-gray-500">+</span>
              <input
                v-model="modelFormat.part2"
                type="text"
                class="w-12 px-2 py-1 border border-gray-300 rounded text-center"
                placeholder="2"
              />
              <span class="text-gray-500">+</span>
              <input
                v-model="modelFormat.part3"
                type="text"
                class="w-12 px-2 py-1 border border-gray-300 rounded text-center"
                placeholder="3"
              />
              <span class="text-gray-500">+</span>
              <input
                v-model="modelFormat.part4"
                type="text"
                class="w-12 px-2 py-1 border border-gray-300 rounded text-center"
                placeholder=""
              />
            </div>
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Options:</label>
            <div class="space-y-2 text-sm">
              <div>1. Product Design</div>
              <div>2. Corrugating Size(Length x P/Size)</div>
              <div>3. B/Quality & Flute</div>
              <div>4. Customer P/Order Ref#</div>
              <div>5. Board Size(Length x Width)</div>
              <div>6. Scoring Width</div>
              <div>7. Blank - Nil</div>
            </div>
          </div>

          <div class="flex justify-end space-x-2">
            <button
              @click="saveModelFormat"
              class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium"
            >
              OK
            </button>
            <button
              @click="closeDefineModelFormatModal"
              class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium"
            >
              Exit
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useToast } from '@/Composables/useToast'
import AppLayout from '@/Layouts/AppLayout.vue'
import CustomerAccountModal from '@/Components/customer-account-modal.vue'
import SalespersonModal from '@/Components/salesperson-modal.vue'
import ProductDesignModal from '@/Components/product-design-modal.vue'
import PaperFluteModal from '@/Components/paper-flute-modal.vue'
import PaperQualityModal from '@/Components/paper-quality-modal.vue'
import PaperSizeModal from '@/Components/paper-size-modal.vue'

const { success, error, loading: toastLoading } = useToast()

// Form data
const form = reactive({
  current_period: 9,
  current_year: 2025,
  update_period: 9,
  update_year: 2025,
  forward_period: 1,
  backward_period: 1,
  customer_code: '',
  salesperson_code: '',
  customer_p_order_number: '',
  p_order_date: '',
  product_design: '',
  flute: '',
  so_b_quality: '',
  wo_b_quality: '',
  board_size_length: '',
  board_size_width: '',
  sheet_size_length: '',
  sheet_size_width: '',
  paper_size: '',
  s_tool: '',
  corr_out: '',
  conv_out: '',
  area_per_pcs: 0,
  l_meter: 0,
  order_quantity: 0,
  currency: 'IDR',
  exchange_rate: 0,
  lot_number: '',
  pcs_per_bundle: '',
  sales_tax: false,
  order_group: 'Sales',
  order_type: '',
  remark: '',
  instruction_1: '',
  instruction_2: '',
  unit: '',
  unit_price: 0
})

// Component state
const loading = ref(false)
const customerInfo = ref(null)
const salespersonInfo = ref(null)

// Modal visibility
const showCustomerModal = ref(false)
const showSalespersonModal = ref(false)
const showProductDesignModal = ref(false)
const showPaperFluteModal = ref(false)
const showPaperQualityModal = ref(false)
const showPaperSizeModal = ref(false)
const showDefineModelFormatModal = ref(false)

// Model format
const modelFormat = reactive({
  part1: '1',
  part2: '2',
  part3: '3',
  part4: ''
})

// Methods
const validateCustomer = async () => {
  if (!form.customer_code) return
  
  try {
    const response = await fetch(`/api/prepare-sb-so/customer-info?customer_code=${form.customer_code}`)
    const data = await response.json()
    
    if (data.success) {
      customerInfo.value = data.data
      success('Customer validated successfully')
    } else {
      error(data.error || 'Customer not found')
      customerInfo.value = null
    }
  } catch (err) {
    console.error('Error validating customer:', err)
    error('Error validating customer')
  }
}

const validateSalesperson = async () => {
  if (!form.salesperson_code) return
  
  try {
    const response = await fetch(`/api/prepare-sb-so/salesperson-info?salesperson_code=${form.salesperson_code}`)
    const data = await response.json()
    
    if (data.success) {
      salespersonInfo.value = data.data
      success('Salesperson validated successfully')
    } else {
      error(data.error || 'Salesperson not found')
      salespersonInfo.value = null
    }
  } catch (err) {
    console.error('Error validating salesperson:', err)
    error('Error validating salesperson')
  }
}

const calculateDimensions = async () => {
  if (!form.board_size_length || !form.board_size_width || !form.order_quantity) return
  
  try {
    const response = await fetch('/api/prepare-sb-so/calculate-dimensions', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      },
      body: JSON.stringify({
        length: parseFloat(form.board_size_length) || 0,
        width: parseFloat(form.board_size_width) || 0,
        quantity: parseInt(form.order_quantity) || 0
      })
    })
    
    const data = await response.json()
    
    if (data.success) {
      form.area_per_pcs = data.data.area_per_pcs
      form.l_meter = data.data.l_meter
    }
  } catch (err) {
    console.error('Error calculating dimensions:', err)
  }
}

const saveOrder = async () => {
  loading.value = true
  
  try {
    const orderData = {
      ...form,
      current_period: parseInt(form.current_period),
      update_period: parseInt(form.update_period),
      forward_period: parseInt(form.forward_period),
      backward_period: parseInt(form.backward_period),
      order_quantity: parseInt(form.order_quantity),
      exchange_rate: parseFloat(form.exchange_rate),
      unit_price: parseFloat(form.unit_price),
      area_per_pcs: parseFloat(form.area_per_pcs),
      l_meter: parseFloat(form.l_meter),
      pcs_per_bundle: form.pcs_per_bundle ? parseInt(form.pcs_per_bundle) : null
    }

    const response = await fetch('/api/prepare-sb-so/orders', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      },
      body: JSON.stringify(orderData)
    })
    
    const data = await response.json()
    
    if (data.success) {
      success('Sales Board Sales Order saved successfully!')
      resetForm()
    } else {
      error(data.error || 'Failed to save order')
    }
  } catch (err) {
    console.error('Error saving order:', err)
    error('Error saving order')
  } finally {
    loading.value = false
  }
}

const resetForm = () => {
  Object.keys(form).forEach(key => {
    if (typeof form[key] === 'boolean') {
      form[key] = false
    } else if (typeof form[key] === 'number') {
      form[key] = 0
    } else {
      form[key] = ''
    }
  })
  
  // Reset to default values
  form.current_period = 9
  form.current_year = 2025
  form.update_period = 9
  form.update_year = 2025
  form.forward_period = 1
  form.backward_period = 1
  form.currency = 'IDR'
  form.order_group = 'Sales'
  form.sales_tax = false
  
  customerInfo.value = null
  salespersonInfo.value = null
}

const printSOLog = async () => {
  try {
    const response = await fetch('/api/prepare-sb-so/print-so-log', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      },
      body: JSON.stringify({ order_id: 1 }) // This would be the actual order ID
    })
    
    const data = await response.json()
    
    if (data.success) {
      success('SO Log generated successfully!')
    } else {
      error(data.error || 'Failed to generate SO Log')
    }
  } catch (err) {
    console.error('Error printing SO log:', err)
    error('Error printing SO log')
  }
}

const printSOJITTracking = async () => {
  try {
    const response = await fetch('/api/prepare-sb-so/print-so-jit-tracking', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      },
      body: JSON.stringify({ order_id: 1 }) // This would be the actual order ID
    })
    
    const data = await response.json()
    
    if (data.success) {
      success('SO JIT Tracking generated successfully!')
    } else {
      error(data.error || 'Failed to generate SO JIT Tracking')
    }
  } catch (err) {
    console.error('Error printing SO JIT tracking:', err)
    error('Error printing SO JIT tracking')
  }
}

// Modal methods
const openCustomerModal = () => {
  showCustomerModal.value = true
}

const openSalespersonModal = () => {
  showSalespersonModal.value = true
}

const openProductDesignModal = () => {
  showProductDesignModal.value = true
}

const openPaperFluteModal = () => {
  showPaperFluteModal.value = true
}

const openPaperQualityModal = () => {
  showPaperQualityModal.value = true
}

const openPaperSizeModal = () => {
  showPaperSizeModal.value = true
}

const selectCustomer = (customer) => {
  form.customer_code = customer.customer_code
  customerInfo.value = customer
  showCustomerModal.value = false
  success('Customer selected successfully')
  
  // Form will automatically appear due to v-if="form.customer_code"
}

const selectSalesperson = (salesperson) => {
  form.salesperson_code = salesperson.salesperson_code
  salespersonInfo.value = salesperson
  showSalespersonModal.value = false
  success('Salesperson selected successfully')
}

const selectProductDesign = (productDesign) => {
  form.product_design = productDesign.product_design_code
  showProductDesignModal.value = false
  success('Product design selected successfully')
}

const selectPaperFlute = (paperFlute) => {
  form.flute = paperFlute.flute_code
  showPaperFluteModal.value = false
  success('Paper flute selected successfully')
}

const selectPaperQuality = (paperQuality) => {
  form.so_b_quality = paperQuality.quality_code
  showPaperQualityModal.value = false
  success('Paper quality selected successfully')
}

const selectPaperSize = (paperSize) => {
  form.paper_size = paperSize.size_code
  showPaperSizeModal.value = false
  success('Paper size selected successfully')
}


// Define Model Format methods
const openDefineModelFormatModal = () => {
  showDefineModelFormatModal.value = true
}

const closeDefineModelFormatModal = () => {
  showDefineModelFormatModal.value = false
}

const saveModelFormat = () => {
  // Here you would typically save the model format configuration
  success('Model format saved successfully!')
  closeDefineModelFormatModal()
}

// Initialize
onMounted(() => {
  // Set default date
  form.p_order_date = new Date().toISOString().split('T')[0]
})
</script>

<style scoped>
/* Custom styles for the form */
.form-section {
  @apply bg-white rounded-lg shadow-sm border border-gray-200 p-6;
}

.form-grid {
  @apply grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6;
}

.input-group {
  @apply flex space-x-2 items-center;
}

.input-label {
  @apply block text-sm font-medium text-gray-700 mb-2;
}

.input-field {
  @apply w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500;
}

.button-primary {
  @apply bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition-colors;
}

.button-secondary {
  @apply bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md font-medium transition-colors;
}

.button-success {
  @apply bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md font-medium transition-colors;
}
</style>
