<template>
  <AppLayout :header="'Product Design'">
    <Head title="Define Product Design" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-drafting-compass mr-3"></i> Define Product Design
      </h2>
      <p class="text-blue-100">Manage product designs for your manufacturing process</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-md p-6 mb-6">
      <div class="flex flex-col md:flex-row gap-6">
        <!-- Main Content Area -->
        <div class="flex-1">
          <!-- Action Bar -->
          <div class="mb-6 flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
            <div class="flex-1 w-full">
              <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                  <i class="fas fa-search"></i>
                </span>
                <input type="text" v-model="searchQuery" placeholder="Search by design code, name or product..."
                  class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
              </div>
            </div>
            <div class="flex gap-2 w-full md:w-auto">
              <button @click="newDesign" class="btn-primary flex-1 md:flex-none">
                <i class="fas fa-plus-circle mr-2"></i> New Design
              </button>
              <button @click="refreshData" class="btn-secondary flex-1 md:flex-none">
                <i class="fas fa-sync-alt mr-2"></i> Refresh
              </button>
              <button @click="printDesigns" class="btn-info flex-1 md:flex-none">
                <i class="fas fa-print mr-2"></i> Print
              </button>
            </div>
          </div>

          <!-- Table Section -->
          <div class="bg-white rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th @click="sortBy('pd_code')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Design# <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('pd_name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Design Name <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('product')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Product <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('joint')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Joint <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-if="loading" class="animate-pulse">
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                      <div class="flex justify-center items-center space-x-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>Loading product designs...</span>
                      </div>
                    </td>
                  </tr>
                  <tr v-else-if="filteredDesigns.length === 0" class="hover:bg-gray-50">
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                      No product designs found. Try adjusting your search or create a new design.
                    </td>
                  </tr>
                  <tr v-for="design in filteredDesigns" :key="design.pd_code" 
                      @click="selectDesign(design)" 
                      :class="{'bg-blue-50': selectedDesign && selectedDesign.pd_code === design.pd_code}"
                      class="hover:bg-gray-50 cursor-pointer">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ design.pd_code }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ design.pd_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                      <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ design.product }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                      <span v-if="design.joint === 'Yes'" class="text-green-500"><i class="fas fa-check-circle"></i> Yes</span>
                      <span v-else class="text-red-500"><i class="fas fa-times-circle"></i> No</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                      <button @click.stop="editDesign(design)" class="text-blue-600 hover:text-blue-900 mr-3">
                        <i class="fas fa-edit"></i>
                      </button>
                      <button @click.stop="confirmDelete(design)" class="text-red-600 hover:text-red-900">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Pagination Controls -->
          <div class="mt-4 flex justify-between items-center text-sm text-gray-600">
            <div>
              <span>Showing {{ filteredDesigns.length }} of {{ designs.length }} designs</span>
            </div>
            <div class="flex items-center space-x-2">
              <select v-model="itemsPerPage" class="border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option :value="10">10 per page</option>
                <option :value="20">20 per page</option>
                <option :value="50">50 per page</option>
                <option :value="100">100 per page</option>
              </select>
              <button :disabled="currentPage === 1" @click="currentPage--" class="px-2 py-1 border rounded hover:bg-gray-200 disabled:opacity-50">
                <i class="fas fa-chevron-left"></i>
              </button>
              <span class="px-4">{{ currentPage }} / {{ totalPages }}</span>
              <button :disabled="currentPage === totalPages" @click="currentPage++" class="px-2 py-1 border rounded hover:bg-gray-200 disabled:opacity-50">
                <i class="fas fa-chevron-right"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Side Panel with Details -->
        <div class="w-full md:w-96 bg-gray-50 rounded-lg p-4 border border-gray-200">
          <div v-if="selectedDesign" class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
              <i class="fas fa-info-circle mr-2 text-blue-500"></i> Design Details
            </h3>
            <div class="space-y-3">
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Design Code:</span>
                <span class="font-medium text-gray-900">{{ selectedDesign.pd_code }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Design Name:</span>
                <span class="font-medium text-gray-900">{{ selectedDesign.pd_name }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Design Type:</span>
                <span class="font-medium text-gray-900">{{ selectedDesign.pd_design_type }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">IDC:</span>
                <span class="font-medium text-gray-900">{{ selectedDesign.idc }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Product:</span>
                <span class="font-medium bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">{{ selectedDesign.product }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Joint:</span>
                <span class="font-medium" :class="{'text-green-600': selectedDesign.joint === 'Yes', 'text-red-600': selectedDesign.joint !== 'Yes'}">
                  {{ selectedDesign.joint }}
                </span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Joint to Print:</span>
                <span class="font-medium" :class="{'text-green-600': selectedDesign.joint_to_print === 'Yes', 'text-red-600': selectedDesign.joint_to_print !== 'Yes'}">
                  {{ selectedDesign.joint_to_print }}
                </span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">PCS to Joint:</span>
                <span class="font-medium text-gray-900">{{ selectedDesign.pcs_to_joint }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Score:</span>
                <span class="font-medium" :class="{'text-green-600': selectedDesign.score === 'Yes', 'text-red-600': selectedDesign.score !== 'Yes'}">
                  {{ selectedDesign.score }}
                </span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Slot:</span>
                <span class="font-medium" :class="{'text-green-600': selectedDesign.slot === 'Yes', 'text-red-600': selectedDesign.slot !== 'Yes'}">
                  {{ selectedDesign.slot }}
                </span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Flute Style:</span>
                <span class="font-medium text-gray-900">{{ selectedDesign.flute_style }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Print Flute:</span>
                <span class="font-medium" :class="{'text-green-600': selectedDesign.print_flute === 'Yes', 'text-red-600': selectedDesign.print_flute !== 'Yes'}">
                  {{ selectedDesign.print_flute }}
                </span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Input Weight:</span>
                <span class="font-medium" :class="{'text-green-600': selectedDesign.input_weight === 'Yes', 'text-red-600': selectedDesign.input_weight !== 'Yes'}">
                  {{ selectedDesign.input_weight }}
                </span>
              </div>
            </div>
            <div class="mt-6 flex space-x-2">
              <button @click="editDesign(selectedDesign)" class="flex-1 btn-blue">
                <i class="fas fa-edit mr-1"></i> Edit
              </button>
              <button @click="confirmDelete(selectedDesign)" class="flex-1 btn-danger">
                <i class="fas fa-trash-alt mr-1"></i> Delete
              </button>
            </div>
          </div>
          <div v-else class="flex flex-col items-center justify-center h-64 text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
              <i class="fas fa-drafting-compass text-blue-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No Design Selected</h3>
            <p class="text-gray-500 mb-4">Select a design from the table to view details</p>
            <button @click="newDesign" class="btn-primary">
              <i class="fas fa-plus-circle mr-1"></i> Create New Design
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Product Design Modal -->
    <ProductDesignModal
      :show="showModal"
      :designs="designs"
      @close="showModal = false"
      @select="onModalSelect"
    />

    <!-- Design Form Modal -->
    <div v-if="showFormModal" class="fixed inset-0 flex items-center justify-center z-50">
      <div class="absolute inset-0 bg-black opacity-50" @click="showFormModal = false"></div>
      <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl z-10 relative transform transition-all duration-300 ease-in-out">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-indigo-800 rounded-t-xl">
          <div class="flex items-center">
            <div class="bg-white/20 p-2 rounded-lg mr-3">
              <i class="fas fa-drafting-compass text-white text-xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-white">{{ isEditing ? 'Edit' : 'New' }} Product Design</h3>
          </div>
          <button @click="showFormModal = false" class="bg-white/20 hover:bg-white/30 text-white p-2 rounded-lg transition-all duration-200">
            <i class="fas fa-times"></i>
          </button>
        </div>
        
        <!-- Form Content -->
        <div class="p-6 max-h-[70vh] overflow-y-auto">
          <form @submit.prevent="saveDesign">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
              <!-- Design Code -->
              <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                  <i class="fas fa-hashtag text-blue-500 mr-2"></i>
                  Design Code<span class="text-red-500">*</span>
                </label>
                <input type="text" v-model="formDesign.pd_code" required :disabled="isEditing"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                  placeholder="Enter design code">
                <p class="text-xs text-gray-500 mt-2 italic">Code must be unique and cannot be changed later</p>
              </div>
              
              <!-- Design Name -->
              <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                  <i class="fas fa-tag text-blue-500 mr-2"></i>
                  Design Name<span class="text-red-500">*</span>
                </label>
                <input type="text" v-model="formDesign.pd_name" required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                  placeholder="Enter design name">
              </div>
              
              <!-- Design Type -->
              <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                  <i class="fas fa-cube text-blue-500 mr-2"></i>
                  Design Type<span class="text-red-500">*</span>
                </label>
                <select v-model="formDesign.pd_design_type" required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 appearance-none bg-white bg-no-repeat bg-right pr-10"
                  style="background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2224%22 height=%2224%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%23666%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22><polyline points=%226 9 12 15 18 9%22></polyline></svg>'); background-size: 16px;">
                  <option value="">Select design type</option>
                  <option value="M-Manufacture">M-Manufacture</option>
                  <option value="T-Trading">T-Trading</option>
                </select>
              </div>
              
              <!-- IDC -->
              <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                  <i class="fas fa-barcode text-blue-500 mr-2"></i>
                  IDC
                </label>
                <input type="text" v-model="formDesign.idc"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                  placeholder="Enter IDC">
              </div>
              
              <!-- Product -->
              <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                  <i class="fas fa-box text-blue-500 mr-2"></i>
                  Product<span class="text-red-500">*</span>
                </label>
                <select v-model="formDesign.product" required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 appearance-none bg-white bg-no-repeat bg-right pr-10"
                  style="background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2224%22 height=%2224%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%23666%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22><polyline points=%226 9 12 15 18 9%22></polyline></svg>'); background-size: 16px;">
                  <option value="">Select product</option>
                  <option value="001">001</option>
                  <option value="002">002</option>
                  <option value="003">003</option>
                  <option value="005">005</option>
                  <option value="006">006</option>
                  <option value="013">013</option>
                  <option value="015">015</option>
                </select>
              </div>
              
              <!-- Joint -->
              <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                  <i class="fas fa-link text-blue-500 mr-2"></i>
                  Joint
                </label>
                <div class="flex gap-4">
                  <label class="flex items-center">
                    <input type="radio" v-model="formDesign.joint" value="Yes" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Yes</span>
                  </label>
                  <label class="flex items-center">
                    <input type="radio" v-model="formDesign.joint" value="No" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">No</span>
                  </label>
                </div>
              </div>
              
              <!-- Joint to Print -->
              <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                  <i class="fas fa-print text-blue-500 mr-2"></i>
                  Joint to Print
                </label>
                <div class="flex gap-4">
                  <label class="flex items-center">
                    <input type="radio" v-model="formDesign.joint_to_print" value="Yes" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Yes</span>
                  </label>
                  <label class="flex items-center">
                    <input type="radio" v-model="formDesign.joint_to_print" value="No" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">No</span>
                  </label>
                </div>
              </div>
              
              <!-- PCS to Joint -->
              <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                  <i class="fas fa-puzzle-piece text-blue-500 mr-2"></i>
                  PCS to Joint
                </label>
                <input type="text" v-model="formDesign.pcs_to_joint"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                  placeholder="Enter PCS to Joint">
              </div>
              
              <!-- Score -->
              <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                  <i class="fas fa-chart-line text-blue-500 mr-2"></i>
                  Score
                </label>
                <div class="flex gap-4">
                  <label class="flex items-center">
                    <input type="radio" v-model="formDesign.score" value="Yes" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Yes</span>
                  </label>
                  <label class="flex items-center">
                    <input type="radio" v-model="formDesign.score" value="No" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">No</span>
                  </label>
                </div>
              </div>
              
              <!-- Slot -->
              <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                  <i class="fas fa-columns text-blue-500 mr-2"></i>
                  Slot
                </label>
                <div class="flex gap-4">
                  <label class="flex items-center">
                    <input type="radio" v-model="formDesign.slot" value="Yes" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Yes</span>
                  </label>
                  <label class="flex items-center">
                    <input type="radio" v-model="formDesign.slot" value="No" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">No</span>
                  </label>
                </div>
              </div>
              
              <!-- Flute Style -->
              <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                  <i class="fas fa-layer-group text-blue-500 mr-2"></i>
                  Flute Style
                </label>
                <select v-model="formDesign.flute_style"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 appearance-none bg-white bg-no-repeat bg-right pr-10"
                  style="background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2224%22 height=%2224%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%23666%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22><polyline points=%226 9 12 15 18 9%22></polyline></svg>'); background-size: 16px;">
                  <option value="Normal">Normal</option>
                  <option value="Reverse">Reverse</option>
                  <option value="N/A">N/A</option>
                </select>
              </div>
              
              <!-- Print Flute -->
              <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                  <i class="fas fa-print text-blue-500 mr-2"></i>
                  Print Flute
                </label>
                <div class="flex gap-4">
                  <label class="flex items-center">
                    <input type="radio" v-model="formDesign.print_flute" value="Yes" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Yes</span>
                  </label>
                  <label class="flex items-center">
                    <input type="radio" v-model="formDesign.print_flute" value="No" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">No</span>
                  </label>
                </div>
              </div>
              
              <!-- Input Weight -->
              <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                  <i class="fas fa-weight text-blue-500 mr-2"></i>
                  Input Weight
                </label>
                <div class="flex gap-4">
                  <label class="flex items-center">
                    <input type="radio" v-model="formDesign.input_weight" value="Yes" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Yes</span>
                  </label>
                  <label class="flex items-center">
                    <input type="radio" v-model="formDesign.input_weight" value="No" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">No</span>
                  </label>
                </div>
              </div>
            </div>
            
            <!-- Form Footer with Buttons -->
            <div class="flex justify-end space-x-3 border-t border-gray-200 pt-5 mt-4">
              <button type="button" @click="showFormModal = false" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition-all duration-200 flex items-center">
                <i class="fas fa-times mr-2"></i> Cancel
              </button>
              <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition-all duration-200 flex items-center" :disabled="loading">
                <i class="fas fa-save mr-2"></i> {{ isEditing ? 'Update' : 'Create' }}
                <span v-if="loading" class="ml-2 animate-spin"><i class="fas fa-spinner"></i></span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Confirmation Modal -->
    <div v-if="showConfirmation" class="fixed inset-0 flex items-center justify-center z-50">
      <div class="absolute inset-0 bg-black opacity-50" @click="showConfirmation = false"></div>
      <div class="bg-white rounded-lg shadow-lg max-w-md z-10 w-full">
        <div class="p-6">
          <div class="flex items-center mb-4">
            <div class="bg-red-100 rounded-full p-2 mr-3">
              <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900">Confirm Delete</h3>
          </div>
          <p class="mb-4 text-gray-600">Are you sure you want to delete the product design <span class="font-semibold">{{ designToDelete?.pd_code }}</span>? This action cannot be undone.</p>
          <div class="flex justify-end space-x-3">
            <button @click="showConfirmation = false" class="px-4 py-2 text-gray-700 border border-gray-300 rounded hover:bg-gray-50">
              <i class="fas fa-times mr-1"></i> Cancel
            </button>
            <button @click="deleteDesign" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700" :disabled="loading">
              <i class="fas fa-trash-alt mr-1"></i> Delete
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import axios from 'axios';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import ProductDesignModal from '@/Components/product-design-modal.vue';
import { useToast } from '@/Composables/useToast';

// State variables
const designs = ref([]);
const selectedDesign = ref(null);
const loading = ref(false);
const searchQuery = ref('');
const showModal = ref(false);
const showFormModal = ref(false);
const showConfirmation = ref(false);
const designToDelete = ref(null);
const isEditing = ref(false);
const sortOrder = ref({
  field: 'pd_code',
  direction: 'asc'
});
const toast = useToast();
const currentPage = ref(1);
const itemsPerPage = ref(10);

// Form for creating/editing design
const formDesign = ref({
  pd_code: '',
  pd_name: '',
  pd_design_type: '',
  idc: '',
  product: '',
  joint: 'No',
  joint_to_print: 'No',
  pcs_to_joint: '0',
  score: 'Yes',
  slot: 'No',
  flute_style: 'Normal',
  print_flute: 'No',
  input_weight: 'No'
});

// Reset form to default values
const resetForm = () => {
  formDesign.value = {
    pd_code: '',
    pd_name: '',
    pd_design_type: '',
    idc: '',
    product: '',
    joint: 'No',
    joint_to_print: 'No',
    pcs_to_joint: '0',
    score: 'Yes',
    slot: 'No',
    flute_style: 'Normal',
    print_flute: 'No',
    input_weight: 'No'
  };
  isEditing.value = false;
};

// Computed properties
const filteredDesigns = computed(() => {
  let result = designs.value;

  // Apply search filter
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    result = result.filter(design => (
      (design.pd_code && design.pd_code.toLowerCase().includes(query)) ||
      (design.pd_name && design.pd_name.toLowerCase().includes(query)) ||
      (design.product && design.product.toLowerCase().includes(query))
    ));
  }

  // Apply sorting
  result = [...result].sort((a, b) => {
    const field = sortOrder.value.field;
    const direction = sortOrder.value.direction === 'asc' ? 1 : -1;
    
    if ((a[field] || '') < (b[field] || '')) return -1 * direction;
    if ((a[field] || '') > (b[field] || '')) return 1 * direction;
    return 0;
  });

  return result;
});

// Pagination computed properties
const paginatedDesigns = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return filteredDesigns.value.slice(start, end);
});

const totalPages = computed(() => {
  return Math.ceil(filteredDesigns.value.length / itemsPerPage.value);
});

// Watch for changes in filtered designs to update pagination
watch(filteredDesigns, () => {
  currentPage.value = 1;
});

// Watch for changes in items per page
watch(itemsPerPage, () => {
  currentPage.value = 1;
});

// Function to fetch designs from API
const fetchDesigns = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/product-designs');
    designs.value = response.data;
  } catch (error) {
    console.error('Error fetching designs:', error);
    toast.error('Failed to load product designs');
  } finally {
    loading.value = false;
  }
};

// Function to refresh data
const refreshData = () => {
  selectedDesign.value = null;
  fetchDesigns();
};

// Function to select a design
const selectDesign = (design) => {
  selectedDesign.value = design;
};

// Function to handle design selection from modal
const onModalSelect = (design) => {
  selectedDesign.value = design;
  showModal.value = false;
};

// Function to open the new design form modal
const newDesign = () => {
  resetForm();
  showFormModal.value = true;
};

// Function to open the edit design form modal
const editDesign = (design) => {
  isEditing.value = true;
  formDesign.value = { ...design };
  showFormModal.value = true;
};

// Function to confirm deletion of a design
const confirmDelete = (design) => {
  designToDelete.value = design;
  showConfirmation.value = true;
};

// Function to save design (create or update)
const saveDesign = async () => {
  loading.value = true;
  try {
    let response;
    
    if (isEditing.value) {
      // Update existing design
      response = await axios.put(`/api/product-designs/${formDesign.value.pd_code}`, formDesign.value);
      toast.success('Design updated successfully');
      
      // Update design in the local array
      const index = designs.value.findIndex(d => d.pd_code === formDesign.value.pd_code);
      if (index !== -1) {
        designs.value[index] = response.data.data;
        selectedDesign.value = response.data.data;
      }
    } else {
      // Create new design
      response = await axios.post('/api/product-designs', formDesign.value);
      toast.success('Design created successfully');
      
      // Add new design to the local array
      designs.value.push(response.data.data);
      selectedDesign.value = response.data.data;
    }
    
    showFormModal.value = false;
  } catch (error) {
    console.error('Error saving design:', error);
    toast.error(error.response?.data?.message || 'Failed to save product design');
  } finally {
    loading.value = false;
  }
};

// Function to delete a design
const deleteDesign = async () => {
  if (!designToDelete.value) return;
  
  loading.value = true;
  try {
    await axios.delete(`/api/product-designs/${designToDelete.value.pd_code}`);
    
    toast.success('Design deleted successfully');
    
    // Remove design from the local array
    designs.value = designs.value.filter(d => d.pd_code !== designToDelete.value.pd_code);
    
    // Clear selection if the deleted design was selected
    if (selectedDesign.value?.pd_code === designToDelete.value.pd_code) {
      selectedDesign.value = null;
    }
    
    showConfirmation.value = false;
    designToDelete.value = null;
  } catch (error) {
    console.error('Error deleting design:', error);
    toast.error('Failed to delete product design');
  } finally {
    loading.value = false;
  }
};

// Function to sort table by a given field
const sortBy = (field) => {
  if (sortOrder.value.field === field) {
    // Toggle direction if same field
    sortOrder.value.direction = sortOrder.value.direction === 'asc' ? 'desc' : 'asc';
  } else {
    // Change field and reset direction
    sortOrder.value.field = field;
    sortOrder.value.direction = 'asc';
  }
};

// Function to print designs
const printDesigns = () => {
  window.location.href = '/product-design/view-print';
};

// Load data when component is mounted
onMounted(fetchDesigns);
</script>

<style scoped>
/* Button styles */
.btn-primary {
  @apply bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

.btn-secondary {
  @apply bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

.btn-info {
  @apply bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

.btn-danger {
  @apply bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

.btn-blue {
  @apply bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

/* Table hover animation */
tbody tr {
  transition: all 0.2s;
}

tbody tr:hover {
  transform: translateY(-2px);
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* Scrollbar styling */
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
</style>
