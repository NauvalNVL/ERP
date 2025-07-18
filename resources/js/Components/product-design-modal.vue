<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-6xl">
      <!-- List View -->
      <div v-if="view === 'list'">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
          <div class="flex items-center">
            <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3 shadow-inner">
              <i class="fas fa-drafting-compass"></i>
            </div>
            <div>
              <h3 class="text-xl font-semibold">Product Design Table</h3>
              <p class="text-xs text-blue-100">Select a design, or create a new one.</p>
            </div>
          </div>
          <div class="flex space-x-2">
            <button @click="createNewDesign" class="px-3 py-1 bg-green-500 hover:bg-green-400 text-white rounded-md text-sm flex items-center shadow-sm">
                <i class="fas fa-plus mr-1"></i> Add New
            </button>
            <button @click="$emit('close')" class="text-white hover:text-gray-200 focus:outline-none transform active:translate-y-px bg-red-500 hover:bg-red-600 h-8 w-8 rounded-full flex items-center justify-center">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
        <!-- Modal Content -->
        <div class="p-5">
          <div class="mb-4 bg-gray-50 p-3 rounded-lg border border-gray-200 shadow-sm">
            <div class="flex flex-wrap items-center gap-3">
              <div class="relative flex-grow">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                  <i class="fas fa-search"></i>
                </span>
                <input type="text" v-model="searchQuery" placeholder="Search by design code, name, type or product..."
                  class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm">
              </div>
              <div class="flex space-x-2">
                <button type="button" @click="searchQuery = ''" class="py-2 px-3 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm rounded-lg flex items-center">
                  <i class="fas fa-eraser mr-1"></i> Clear
                </button>
                <button type="button" class="py-2 px-3 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-lg flex items-center">
                  <i class="fas fa-filter mr-1"></i> Filter
                </button>
              </div>
            </div>
          </div>
          <div class="overflow-x-auto rounded-lg border border-gray-200 max-h-96">
            <table class="w-full divide-y divide-gray-200">
              <thead class="bg-gray-50 sticky top-0">
                <tr>
                  <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer min-w-[80px]" @click="sortTable('pd_code')">Design#</th>
                  <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer min-w-[150px]" @click="sortTable('pd_name')">Design Name</th>
                  <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer min-w-[120px]" @click="sortTable('pd_design_type')">Design Type</th>
                  <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer min-w-[80px]" @click="sortTable('idc')">IDC</th>
                  <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer min-w-[80px]" @click="sortTable('product')">Product</th>
                  <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer min-w-[70px]" @click="sortTable('joint')">Joint</th>
                  <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer min-w-[110px]" @click="sortTable('joint_to_print')">Joint to Print</th>
                  <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer min-w-[100px]" @click="sortTable('pcs_to_joint')">PCS to Joint</th>
                  <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer min-w-[70px]" @click="sortTable('score')">Score</th>
                  <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer min-w-[70px]" @click="sortTable('slot')">Slot</th>
                  <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer min-w-[100px]" @click="sortTable('flute_style')">Flute Style</th>
                  <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer min-w-[90px]" @click="sortTable('print_flute')">Print Flute</th>
                  <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer min-w-[110px]" @click="sortTable('input_weight')">Input Weight</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200 text-xs">
                <tr v-for="design in filteredDesigns" :key="design.pd_code"
                  :class="['hover:bg-blue-50 cursor-pointer group', selectedDesign && selectedDesign.pd_code === design.pd_code ? 'bg-blue-100 border-l-4 border-blue-500' : '']"
                  @click="selectRow(design)"
                  @dblclick="handleDoubleClick(design)"
                  title="Double-click to edit this design">
                  <td class="px-3 py-3 whitespace-nowrap font-medium text-gray-900 relative">
                    {{ design.pd_code }}
                    <span class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity text-blue-500">
                      <i class="fas fa-mouse-pointer text-xs"></i>
                    </span>
                  </td>
                  <td class="px-3 py-3 whitespace-nowrap text-gray-700">{{ design.pd_name }}</td>
                  <td class="px-3 py-3 whitespace-nowrap text-gray-700">{{ design.pd_design_type }}</td>
                  <td class="px-3 py-3 whitespace-nowrap text-gray-700">{{ design.idc }}</td>
                  <td class="px-3 py-3 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">{{ design.product }}</span>
                  </td>
                  <td class="px-3 py-3 whitespace-nowrap text-gray-700">{{ design.joint }}</td>
                  <td class="px-3 py-3 whitespace-nowrap text-gray-700">{{ design.joint_to_print }}</td>
                  <td class="px-3 py-3 whitespace-nowrap text-gray-700">{{ design.pcs_to_joint }}</td>
                  <td class="px-3 py-3 whitespace-nowrap text-gray-700">{{ design.score }}</td>
                  <td class="px-3 py-3 whitespace-nowrap text-gray-700">{{ design.slot }}</td>
                  <td class="px-3 py-3 whitespace-nowrap text-gray-700">{{ design.flute_style }}</td>
                  <td class="px-3 py-3 whitespace-nowrap text-gray-700">{{ design.print_flute }}</td>
                  <td class="px-3 py-3 whitespace-nowrap text-gray-700">{{ design.input_weight }}</td>
                </tr>
                <tr v-if="filteredDesigns.length === 0">
                  <td colspan="13" class="px-6 py-4 text-center text-gray-500">No product design data available.</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="mt-4 flex justify-between items-center">
            <div class="flex items-center text-sm text-gray-500">
              <i class="fas fa-info-circle mr-2 text-blue-500"></i>
              <p>{{ filteredDesigns.length }} designs found. Double-click a row to edit.</p>
              <span v-if="selectedDesign" class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                Selected: {{ selectedDesign.pd_code }}
              </span>
            </div>
            <div class="flex space-x-2">
              <div class="dropdown relative">
                <button type="button" class="py-2 px-3 bg-gray-100 border border-gray-300 hover:bg-gray-200 text-sm rounded-lg flex items-center">
                  <i class="fas fa-sort mr-1"></i> Sort By <i class="fas fa-chevron-down ml-1 text-xs"></i>
                </button>
                <div class="absolute right-0 mt-1 bg-white rounded-lg shadow-lg border border-gray-200 hidden hover:block focus:block group-hover:block z-20 w-48">
                  <div class="py-1">
                    <button @click="sortTable('pd_code')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                      <i class="fas fa-sort-alpha-down mr-2"></i> By Design#
                    </button>
                    <button @click="sortByProductAndDesign()" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                      <i class="fas fa-layer-group mr-2"></i> By Product + Design#
                    </button>
                    <button @click="sortByProductName()" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                      <i class="fas fa-font mr-2"></i> By Design Name
                    </button>
                  </div>
                </div>
              </div>
              <button type="button" @click="selectAndClose(selectedDesign)" class="py-2 px-4 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-lg flex items-center shadow-sm" :disabled="!selectedDesign" :class="{'opacity-50 cursor-not-allowed': !selectedDesign}">
                <i class="fas fa-check-circle mr-2"></i>Select Design
              </button>
              <button type="button" @click="$emit('close')" class="py-2 px-4 bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm rounded-lg flex items-center">
                <i class="fas fa-times mr-2"></i>Cancel
              </button>
            </div>
          </div>
        </div>
      </div>
      <!-- Form View -->
      <div v-else>
        <div class="flex items-center justify-between p-3 bg-blue-600 text-white rounded-t-lg">
            <div class="flex items-center">
                <div class="p-2 bg-white bg-opacity-20 rounded-lg mr-3">
                    <i class="fas fa-drafting-compass text-white"></i>
                </div>
                <h3 class="text-xl font-semibold">{{ isCreating ? 'Create Product Design' : 'Edit Product Design' }}</h3>
            </div>
            <div class="flex space-x-2">
                <button type="button" @click="view = 'list'" class="text-white hover:text-gray-200 p-1 ml-2">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        <div class="p-5 bg-gray-50 overflow-y-auto" style="max-height: calc(100vh - 130px);">
            <form @submit.prevent="saveDesignChanges">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Left Column -->
                    <div>
                        <!-- Design Code -->
                        <div class="mb-3">
                            <div class="flex items-center">
                                <div class="w-1/3">
                                    <label class="text-sm font-medium text-gray-700">P/Design Code:</label>
                                </div>
                                <div class="w-2/3">
                                    <input v-model="editForm.pd_code" type="text" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" :class="{ 'bg-gray-100': !isCreating }" :readonly="!isCreating">
                                </div>
                            </div>
                        </div>

                        <!-- Design Name -->
                        <div class="mb-3">
                            <div class="flex items-center">
                                <div class="w-1/3">
                                    <label class="text-sm font-medium text-gray-700">P/Design Name:</label>
                                </div>
                                <div class="w-2/3">
                                    <input v-model="editForm.pd_name" type="text" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                </div>
                            </div>
                        </div>

                        <!-- Design Alt Name -->
                        <div class="mb-3">
                            <div class="flex items-center">
                                <div class="w-1/3">
                                    <label class="text-sm font-medium text-gray-700">P/Design Alt Name:</label>
                                </div>
                                <div class="w-2/3">
                                    <input v-model="editForm.pd_alt_name" type="text" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                </div>
                            </div>
                        </div>

                        <!-- Product -->
                        <div class="mb-3">
                            <div class="flex items-center">
                                <div class="w-1/3">
                                    <label class="text-sm font-medium text-gray-700">Product:</label>
                                </div>
                                <div class="w-2/3 flex">
                                    <input v-model="editForm.product" type="text" class="w-full border-gray-300 rounded-l-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <button type="button" class="px-3 py-2 bg-blue-500 text-white rounded-r-md border border-blue-600" @click="showProductModal = true">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Design Type -->
                        <div class="mb-3">
                            <div class="flex items-center">
                                <div class="w-1/3">
                                    <label class="text-sm font-medium text-gray-700">P/Design Type:</label>
                                </div>
                                <div class="w-2/3">
                                    <select v-model="editForm.pd_design_type" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        <option value="T-Trading">T-Trading</option>
                                        <option value="M-Manufacture">M-Manufacture</option>
                                        <option value="N/A">N/A</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Carton Product Section -->
                        <div class="mt-5 pt-3 border-t border-gray-200">
                            <h4 class="font-medium text-gray-700 mb-3">Carton Product</h4>
                            <div class="flex items-center">
                                <div class="w-1/3">
                                    <label class="text-sm font-medium text-gray-700">IDC:</label>
                                </div>
                                <div class="w-2/3 flex">
                                    <input v-model="editForm.idc" type="text" class="w-full border-gray-300 rounded-l-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <button type="button" class="px-3 py-2 bg-blue-500 text-white rounded-r-md border border-blue-600">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="flex items-center mt-1">
                                <div class="w-1/3"></div>
                                <div class="w-2/3">
                                    <span class="text-xs text-blue-600">Use by Master Card & Work Order</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div>
                        <!-- Joint -->
                        <div class="bg-white p-4 rounded-md border border-gray-200 mb-5">
                            <h4 class="font-medium text-blue-700 mb-3 pb-2 border-b border-gray-200">Joint</h4>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Joint:</label>
                                    <div class="flex space-x-4">
                                        <label class="inline-flex items-center">
                                            <input type="radio" v-model="editForm.joint" value="Yes" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                            <span class="ml-2 text-sm text-gray-700">Y-Yes</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" v-model="editForm.joint" value="No" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                            <span class="ml-2 text-sm text-gray-700">N-No</span>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Joint to Print:</label>
                                    <div class="flex space-x-4">
                                        <label class="inline-flex items-center">
                                            <input type="radio" v-model="editForm.joint_to_print" value="Yes" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                            <span class="ml-2 text-sm text-gray-700">Y-Yes</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" v-model="editForm.joint_to_print" value="No" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                            <span class="ml-2 text-sm text-gray-700">N-No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pieces to Joint:</label>
                                <select v-model="editForm.pcs_to_joint" class="w-20 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                </select>
                                <span class="ml-2 text-xs text-gray-500">Number of pieces to be joined. Eg. HSC 2+1</span>
                            </div>
                        </div>

                        <!-- Score & Slot -->
                        <div class="bg-white p-4 rounded-md border border-gray-200 mb-5">
                            <h4 class="font-medium text-blue-700 mb-3 pb-2 border-b border-gray-200">Score & Slot</h4>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Score:</label>
                                    <div class="flex space-x-4">
                                        <label class="inline-flex items-center">
                                            <input type="radio" v-model="editForm.score" value="Yes" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                            <span class="ml-2 text-sm text-gray-700">Y-Yes</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" v-model="editForm.score" value="No" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                            <span class="ml-2 text-sm text-gray-700">N-No</span>
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Y Print Scoring on CDRi SCH + SB PD</p>
                                    <p class="text-xs text-gray-500 mt-1">N Print Total Scoring on CDRi SCH + SB PD</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Slot:</label>
                                    <div class="flex space-x-4">
                                        <label class="inline-flex items-center">
                                            <input type="radio" v-model="editForm.slot" value="Yes" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                            <span class="ml-2 text-sm text-gray-700">Y-Yes</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" v-model="editForm.slot" value="No" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                            <span class="ml-2 text-sm text-gray-700">N-No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Flute Configuration -->
                        <div class="bg-white p-4 rounded-md border border-gray-200 mb-5">
                            <h4 class="font-medium text-blue-700 mb-3 pb-2 border-b border-gray-200">Flute Configuration</h4>

                            <div class="mb-4">
                                <div class="flex items-center">
                                    <div class="w-1/3">
                                        <label class="text-sm font-medium text-gray-700">Flute Style:</label>
                                    </div>
                                    <div class="w-2/3 flex items-center">
                                        <select v-model="editForm.flute_style" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                            <option value="Blank N/A">Blank N/A</option>
                                            <option value="N-Normal">N-Normal</option>
                                            <option value="R-Reverse/Rotate">R-Reverse/Rotate</option>
                                        </select>
                                        <button type="button" class="ml-2 px-3 py-2 bg-blue-500 text-white rounded-md border border-blue-600 text-sm">
                                            View Flute Style
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Print Flute:</label>
                                <div class="flex space-x-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" v-model="editForm.print_flute" value="Yes" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                        <span class="ml-2 text-sm text-gray-700">Y-Yes</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" v-model="editForm.print_flute" value="No" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                        <span class="ml-2 text-sm text-gray-700">N-No</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Others Section -->
                        <div class="bg-white p-4 rounded-md border border-gray-200 mb-5">
                            <h4 class="font-medium text-blue-700 mb-3 pb-2 border-b border-gray-200">Others</h4>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Input Weight:</label>
                                <div class="ml-4 mb-2">
                                    <div class="flex space-x-6">
                                        <label class="inline-flex items-center">
                                            <input type="radio" v-model="editForm.input_weight" value="Yes" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                            <span class="ml-2 text-sm text-gray-700">Y-Yes</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" v-model="editForm.input_weight" value="No" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                            <span class="ml-2 text-sm text-gray-700">N-No</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <span class="text-xs text-gray-500">To input manual KG and M2 in M/Card.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex justify-end space-x-4">
                    <button type="button" @click="view = 'list'" class="px-5 py-2 bg-gray-100 text-gray-800 rounded border border-gray-300 hover:bg-gray-200 flex items-center font-medium">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </button>
                    <button type="submit" :disabled="saving" class="px-5 py-2 bg-blue-500 text-white rounded border border-blue-600 hover:bg-blue-600 flex items-center font-medium" :class="{'opacity-50 cursor-not-allowed': saving}">
                        <i class="fas fa-save mr-2"></i>{{ saving ? 'Saving...' : 'Save' }}
                    </button>
                    <button v-if="!isCreating" :disabled="saving" type="button" @click="deleteDesign(editForm.pd_code)" class="px-5 py-2 bg-red-500 text-white rounded border border-red-600 hover:bg-red-600 flex items-center font-medium" :class="{'opacity-50 cursor-not-allowed': saving}">
                        <i class="fas fa-trash-alt mr-2"></i>Delete
                    </button>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>
  <ProductModal
    :show="showProductModal"
    :products="products"
    :categories="[]"
    @close="showProductModal = false"
    @select="onProductSelected"
  />
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import ProductModal from '@/Components/product-modal.vue';

const props = defineProps({
  show: Boolean,
  designs: {
    type: Array,
    default: () => []
  },
  products: {
    type: Array,
    default: () => []
  },
  isCreating: {
    type: Boolean,
    default: false,
  },
  designToEdit: {
    type: Object,
    default: null,
  },
  doubleClickAction: {
    type: String,
    default: 'edit' // 'edit' or 'select'
  }
});

const emit = defineEmits(['close', 'select', 'data-changed']);

const view = ref('list'); // 'list' or 'form'
const searchQuery = ref('');
const selectedDesign = ref(null);
const sortKey = ref('pd_code');
const sortAsc = ref(true);

const showProductModal = ref(false);
const editForm = ref({
    pd_code: '',
    pd_name: '',
    pd_alt_name: '',
    pd_design_type: 'T-Trading',
    idc: 'NA',
    product: '',
    joint: 'No',
    joint_to_print: 'No',
    pcs_to_joint: '1',
    score: 'Yes',
    slot: 'No',
    flute_style: 'Blank N/A',
    print_flute: 'No',
    input_weight: 'Yes'
});
const isCreating = ref(false);
const saving = ref(false);

const onProductSelected = (product) => {
    editForm.value.product = product.product_code;
    showProductModal.value = false;
};

// Compute filtered designs based on search query
const filteredDesigns = computed(() => {
  let designs = props.designs;
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase();
    designs = designs.filter(design =>
      (design.pd_code && design.pd_code.toLowerCase().includes(q)) ||
      (design.pd_name && design.pd_name.toLowerCase().includes(q)) ||
      (design.pd_design_type && design.pd_design_type.toLowerCase().includes(q)) ||
      (design.product && design.product.toLowerCase().includes(q)) ||
      (design.idc && design.idc.toLowerCase().includes(q)) ||
      (design.joint && design.joint.toLowerCase().includes(q))
    );
  }

  // Apply sorting
  return [...designs].sort((a, b) => {
    if (sortKey.value === 'pd_code') {
      if (a.pd_code < b.pd_code) return sortAsc.value ? -1 : 1;
      if (a.pd_code > b.pd_code) return sortAsc.value ? 1 : -1;
      return 0;
    }

    // Handle null values for sorting
    const aVal = a[sortKey.value] || '';
    const bVal = b[sortKey.value] || '';

    if (aVal < bVal) return sortAsc.value ? -1 : 1;
    if (aVal > bVal) return sortAsc.value ? 1 : -1;
    return 0;
  });
});

// Select a row
function selectRow(design) {
  selectedDesign.value = design;
}

// Select and close modal
function selectAndClose(design) {
  if (design) {
    emit('select', design);
    emit('close');
  }
}

function handleDoubleClick(design) {
  if (props.doubleClickAction === 'select') {
    selectAndClose(design);
  } else {
    editDesign(design);
  }
}

function editDesign(design) {
    isCreating.value = false;
    editForm.value = {
        pd_code: design.pd_code,
        pd_name: design.pd_name,
        pd_alt_name: design.pd_alt_name || '',
        pd_design_type: design.pd_design_type,
        idc: design.idc || '',
        product: design.product || '',
        joint: design.joint || 'No',
        joint_to_print: design.joint_to_print || 'No',
        pcs_to_joint: design.pcs_to_joint || '1',
        score: design.score || 'Yes',
        slot: design.slot || 'No',
        flute_style: design.flute_style || 'Blank N/A',
        print_flute: design.print_flute || 'No',
        input_weight: design.input_weight || 'Yes'
    };
    view.value = 'form';
}

function createNewDesign() {
    isCreating.value = true;
    editForm.value = {
        pd_code: '',
        pd_name: '',
        pd_alt_name: '',
        pd_design_type: 'T-Trading',
        idc: 'NA',
        product: '',
        joint: 'No',
        joint_to_print: 'No',
        pcs_to_joint: '1',
        score: 'Yes',
        slot: 'No',
        flute_style: 'Blank N/A',
        print_flute: 'No',
        input_weight: 'Yes'
    };
    view.value = 'form';
}

async function saveDesignChanges() {
    saving.value = true;
    try {
        const endpoint = isCreating.value
            ? '/api/product-designs'
            : `/api/product-designs/${editForm.value.pd_code}`;

        const method = isCreating.value ? 'POST' : 'PUT';

        const res = await fetch(endpoint, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(editForm.value)
        });

        if (!res.ok) {
            const errorData = await res.json();
            throw new Error(errorData.message || 'Failed to save product design');
        }

        emit('data-changed');
        view.value = 'list';
    } catch (e) {
        console.error('Error saving product design:', e);
        // You might want to show an error notification here
    } finally {
        saving.value = false;
    }
}

async function deleteDesign(pdCode) {
    if (!confirm(`Are you sure you want to delete product design "${pdCode}"?`)) {
        return;
    }

    saving.value = true;
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        const response = await fetch(`/api/product-designs/${pdCode}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });

        if (response.ok) {
            emit('data-changed');
            view.value = 'list';
        } else {
            const result = await response.json();
            throw new Error(result.message || 'Unknown error');
        }
    } catch (e) {
        console.error('Error deleting product design:', e);
    } finally {
        saving.value = false;
    }
}


// Sort table by the given key
function sortTable(key) {
  if (sortKey.value === key) {
    sortAsc.value = !sortAsc.value;
  } else {
    sortKey.value = key;
    sortAsc.value = true;
  }
}

// Sort by product and design
function sortByProductAndDesign() {
  sortKey.value = 'product';
  sortAsc.value = true;

  filteredDesigns.value = [...props.designs].sort((a, b) => {
    const aProduct = a.product || '';
    const bProduct = b.product || '';

    if (aProduct < bProduct) return -1;
    if (aProduct > bProduct) return 1;

    // If products are the same, sort by pd_code
    const aCode = a.pd_code || '';
    const bCode = b.pd_code || '';

    if (aCode < bCode) return -1;
    if (aCode > bCode) return 1;
    return 0;
  });
}

// Sort by design name
function sortByProductName() {
  sortKey.value = 'pd_name';
  sortAsc.value = true;

  filteredDesigns.value = [...props.designs].sort((a, b) => {
    const aName = a.pd_name || '';
    const bName = b.pd_name || '';

    if (aName < bName) return -1;
    if (aName > bName) return 1;
    return 0;
  });
}

// Reset selection when modal is opened
watch(() => props.show, (val) => {
  if (val) {
    if (props.isCreating) {
      createNewDesign();
    } else if (props.designToEdit) {
      editDesign(props.designToEdit);
    } else {
      selectedDesign.value = null;
      searchQuery.value = '';
      view.value = 'list';
    }
  }
});
</script>

<style scoped>
/* Add custom styles for better table display */
.overflow-x-auto {
  scrollbar-width: thin;
  scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
}

.overflow-x-auto::-webkit-scrollbar {
  height: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track {
  background: transparent;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
  background-color: rgba(156, 163, 175, 0.5);
  border-radius: 20px;
}

/* Enhanced table styling */
table {
  border-collapse: separate;
  border-spacing: 0;
}

thead th {
  background: linear-gradient(to bottom, #f9fafb, #f3f4f6);
  position: sticky;
  top: 0;
  z-index: 10;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
  transition: background-color 0.2s;
}

thead th:hover {
  background: linear-gradient(to bottom, #f3f4f6, #e5e7eb);
}

tbody tr {
  transition: all 0.2s;
}

tbody tr:hover {
  transform: translateY(-1px);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  z-index: 1;
  position: relative;
}

tbody tr.bg-blue-100 {
  box-shadow: 0 0 0 1px rgba(59, 130, 246, 0.5);
}

/* Search input styling */
input[type="text"] {
  transition: all 0.3s;
}

input[type="text"]:focus {
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
  border-color: #3b82f6;
}

/* Button styling */
button {
  transition: all 0.2s;
}

button:hover {
  transform: translateY(-1px);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

button:active {
  transform: translateY(0);
}

.bg-blue-500:hover {
  background-color: #3b82f6;
  box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.4);
}

/* Modal styling */
.fixed.inset-0 {
  backdrop-filter: blur(2px);
}

.bg-white.rounded-lg {
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
  animation: modal-appear 0.3s ease-out forwards;
}

@keyframes modal-appear {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Badge styling */
.rounded-full.bg-blue-100 {
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
  border: 1px solid rgba(59, 130, 246, 0.2);
}
</style>
