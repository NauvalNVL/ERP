<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-6xl">
      <!-- List View -->
      <div v-if="view === 'list'">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-t-lg">
          <div class="flex items-center">
            <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
              <i class="fas fa-drafting-compass"></i>
            </div>
            <h3 class="text-xl font-semibold">Product Design Table</h3>
          </div>
          <button @click="$emit('close')" class="text-white hover:text-gray-200 focus:outline-none transform active:translate-y-px">
            <i class="fas fa-times text-xl"></i>
          </button>
        </div>
        <!-- Modal Content -->
        <div class="p-5 flex flex-col min-h-0">
          <div class="mb-4">
            <div class="relative">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                <i class="fas fa-search"></i>
              </span>
              <input type="text" v-model="searchQuery" placeholder="Search product designs..."
                class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 bg-gray-50">
            </div>
          </div>
          <div class="flex-1 min-h-0">
            <div v-if="loading" class="flex flex-col items-center justify-center flex-1 border border-dashed border-emerald-300 rounded-lg py-10">
              <div class="flex items-center space-x-3 text-emerald-600">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-emerald-500"></div>
                <span class="text-sm font-medium">Loading product design data...</span>
              </div>
              <p class="text-xs text-emerald-500 mt-2">Please wait, fetching the latest records.</p>
            </div>
            <div v-else class="overflow-x-auto overflow-y-auto relative rounded-lg border border-gray-200 max-h-96">
              <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 sticky top-0 z-20">
                  <tr>
                    <th @click="sortTable('pd_code')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      Design Code <i class="fas fa-sort ml-1"></i>
                    </th>
                    <th @click="sortTable('pd_name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      Design Name <i class="fas fa-sort ml-1"></i>
                    </th>
                    <th @click="sortTable('pd_design_type')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      Design Type <i class="fas fa-sort ml-1"></i>
                    </th>
                    <th @click="sortTable('idc')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      IDC <i class="fas fa-sort ml-1"></i>
                    </th>
                    <th @click="sortTable('product')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      Product <i class="fas fa-sort ml-1"></i>
                    </th>
                    <th @click="sortTable('joint')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      Joint <i class="fas fa-sort ml-1"></i>
                    </th>
                    <th @click="sortTable('joint_to_print')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      Joint to Print <i class="fas fa-sort ml-1"></i>
                    </th>
                    <th @click="sortTable('pcs_to_joint')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      PCS to Joint <i class="fas fa-sort ml-1"></i>
                    </th>
                    <th @click="sortTable('score')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      Score <i class="fas fa-sort ml-1"></i>
                    </th>
                    <th @click="sortTable('slot')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      Slot <i class="fas fa-sort ml-1"></i>
                    </th>
                    <th @click="sortTable('flute_style')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      Flute Style <i class="fas fa-sort ml-1"></i>
                    </th>
                    <th @click="sortTable('print_flute')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      Print Flute <i class="fas fa-sort ml-1"></i>
                    </th>
                    <th @click="sortTable('input_weight')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      Input Weight <i class="fas fa-sort ml-1"></i>
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 text-xs">
                  <tr v-for="design in filteredDesigns" :key="design.pd_code"
                    :class="['hover:bg-emerald-50 cursor-pointer', selectedDesign && selectedDesign.pd_code === design.pd_code ? 'bg-emerald-100 border-l-4 border-emerald-500' : '']"
                    @click="selectRow(design)"
                    @dblclick="handleDoubleClick(design)">
                    <td class="px-6 py-3 whitespace-nowrap font-medium text-gray-900">{{ design.pd_code }}</td>
                    <td class="px-6 py-3 whitespace-nowrap text-gray-700 truncate">{{ design.pd_name }}</td>
                    <td class="px-6 py-3 whitespace-nowrap text-gray-700">{{ design.pd_design_type }}</td>
                    <td class="px-6 py-3 whitespace-nowrap text-gray-700">{{ design.idc }}</td>
                    <td class="px-6 py-3 whitespace-nowrap">
                      <span class="px-2 py-1 text-xs font-medium rounded-full bg-emerald-100 text-emerald-800">{{ design.product }}</span>
                    </td>
                    <td class="px-6 py-3 whitespace-nowrap text-gray-700">{{ design.joint }}</td>
                    <td class="px-6 py-3 whitespace-nowrap text-gray-700">{{ design.joint_to_print }}</td>
                    <td class="px-6 py-3 whitespace-nowrap text-gray-700">{{ design.pcs_to_joint }}</td>
                    <td class="px-6 py-3 whitespace-nowrap text-gray-700">{{ design.score }}</td>
                    <td class="px-6 py-3 whitespace-nowrap text-gray-700">{{ design.slot }}</td>
                    <td class="px-6 py-3 whitespace-nowrap text-gray-700">{{ design.flute_style }}</td>
                    <td class="px-6 py-3 whitespace-nowrap text-gray-700">{{ design.print_flute }}</td>
                    <td class="px-6 py-3 whitespace-nowrap text-gray-700">{{ design.input_weight }}</td>
                  </tr>
                  <tr v-if="filteredDesigns.length === 0">
                    <td colspan="13" class="px-6 py-4 text-center text-gray-500">No product design data available.</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="mt-2 text-xs text-gray-500 italic">
            <p>Click on a row to select, double-click to edit the design.</p>
          </div>
          <div class="mt-4 grid grid-cols-5 gap-2">
            <button type="button" @click="sortTable('pd_code')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
              <i class="fas fa-sort mr-1"></i>By Code
            </button>
            <button type="button" @click="sortTable('pd_name')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
              <i class="fas fa-sort mr-1"></i>By Name
            </button>
            <button type="button" @click="sortTable('product')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
              <i class="fas fa-sort mr-1"></i>By Product
            </button>
            <button 
              type="button" 
              @click="props.doubleClickAction === 'select' ? selectAndClose(selectedDesign) : selectAndEdit(selectedDesign)"
              class="py-2 px-3 bg-emerald-500 hover:bg-emerald-600 text-white text-xs rounded-lg transform active:translate-y-px" 
              :disabled="!selectedDesign" 
              :class="{'opacity-50 cursor-not-allowed': !selectedDesign}">
              <i :class="props.doubleClickAction === 'select' ? 'fas fa-check mr-1' : 'fas fa-edit mr-1'"></i>
              {{ props.doubleClickAction === 'select' ? 'Select' : 'Edit' }}
            </button>
            <button type="button" @click="$emit('close')" class="py-2 px-3 bg-gray-300 hover:bg-gray-400 text-gray-800 text-xs rounded-lg transform active:translate-y-px">
              <i class="fas fa-times mr-1"></i>Close
            </button>
          </div>
        </div>
      </div>
      <!-- Form View -->
      <div v-else>
        <div class="flex items-center justify-between p-3 bg-green-600 text-white rounded-t-lg">
            <div class="flex items-center">
                <div class="p-2 bg-white bg-opacity-20 rounded-lg mr-3">
                    <i class="fas fa-drafting-compass text-white"></i>
                </div>
                <h3 class="text-xl font-semibold">{{ isCreating ? 'Create Product Design' : 'Edit Product Design' }}</h3>
            </div>
            <div class="flex space-x-2">
                <button type="button" @click="closeForm" class="text-white hover:text-gray-200 p-1 ml-2">
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
                                <div class="w-2/3">
                                    <input v-model="editForm.idc" type="text" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
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
                                    <div class="w-2/3">
                                        <select v-model="editForm.flute_style" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                            <option value="Blank N/A">Blank N/A</option>
                                            <option value="N-Normal">N-Normal</option>
                                            <option value="R-Reverse/Rotate">R-Reverse/Rotate</option>
                                        </select>
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
                    <button type="submit" :disabled="saving" class="px-5 py-2 bg-emerald-500 text-white rounded border border-emerald-600 hover:bg-emerald-600 flex items-center font-medium" :class="{'opacity-50 cursor-not-allowed': saving}">
                        <i class="fas fa-save mr-2"></i>{{ saving ? 'Saving...' : 'Save' }}
                    </button>
                    <button v-if="!isCreating" :disabled="saving" type="button" @click="obsoleteDesign(editForm.pd_code)" class="px-5 py-2 bg-orange-500 text-white rounded border border-orange-600 hover:bg-orange-600 flex items-center font-medium" :class="{'opacity-50 cursor-not-allowed': saving}">
                        <i class="fas fa-ban mr-2"></i>Obsolete
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
import Swal from 'sweetalert2';

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
  doubleClickAction: {
    type: String,
    default: 'edit' 
  },
  isCreating: {
    type: Boolean,
    default: false
  },
  loading: {
    type: Boolean,
    default: false
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
    id: null,
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

// Select and open edit form
function selectAndEdit(design) {
  if (design) {
    editDesign(design);
  }
}

function handleDoubleClick(design) {
  if (props.doubleClickAction === 'select') {
    selectAndClose(design);
  } else {
    editDesign(design);
  }
}

// Emit selected design to parent and close modal
function selectAndClose(design) {
  if (design) {
    emit('select', design);
    emit('close');
  }
}

function editDesign(design) {
    isCreating.value = false;
    editForm.value = {
        id: design.id,
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
        id: null,
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

function closeForm() {
    // If creating new design, close the modal entirely
    // If editing, go back to the list view
    if (isCreating.value) {
        emit('close');
    } else {
        view.value = 'list';
    }
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
            console.error('API Error Response:', errorData);
            throw new Error(errorData.message || 'Failed to save product design');
        }

        emit('data-changed');
        view.value = 'list';
        emit('close');
    } catch (e) {
        console.error('Error saving product design:', e);
        await Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error: ' + (e?.message || 'Unknown error'),
            confirmButtonText: 'OK',
            allowOutsideClick: false,
        });
    } finally {
        saving.value = false;
    }
}

async function obsoleteDesign(pdCode) {
    const confirmMessage = `Are you sure you want to obsolete product design "${pdCode}"? This will hide it from product design selection.`;
    const confirmRes = await Swal.fire({
        title: 'Confirm Status Change?',
        text: confirmMessage,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        allowOutsideClick: false,
    });

    if (!confirmRes.isConfirmed) return;

    saving.value = true;
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        const response = await fetch(`/api/product-designs/${editForm.value.id}/status`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        const result = await response.json();
        
        if (response.ok && result.success) {
            emit('data-changed');
            view.value = 'list';
            emit('close');
            await Swal.fire({
                icon: 'success',
                title: 'Success',
                text: `Product design "${pdCode}" has been obsoleted successfully.`,
                confirmButtonText: 'OK',
                allowOutsideClick: false,
            });
        } else {
            throw new Error(result.message || 'Failed to obsolete product design');
        }
    } catch (e) {
        console.error('Error obsoleting product design:', e);
        await Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error: ' + (e?.message || 'Unknown error'),
            confirmButtonText: 'OK',
            allowOutsideClick: false,
        });
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
  } else {
    // Reset to list view when modal is closed
    view.value = 'list';
    selectedDesign.value = null;
    searchQuery.value = '';
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
