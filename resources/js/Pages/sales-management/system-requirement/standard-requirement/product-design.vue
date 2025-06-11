<template>
    <AppLayout :header="'Product Design'">
    <Head title="Product Design Management" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
        <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
            <i class="fas fa-drafting-compass mr-3"></i> Define Product Design
        </h2>
        <p class="text-cyan-100">Define product designs for specific product categories</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2">
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
                    <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-blue-500 rounded-lg mr-3">
                            <i class="fas fa-edit text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Product Design Management</h3>
                    </div>
                    
                    <!-- Search Section -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Design #:</label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fas fa-drafting-compass"></i>
                                </span>
                                <input type="text" v-model="searchQuery" @dblclick="showModal = true" placeholder="Double-click to browse designs..." class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <button type="button" @click="showModal = true" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md">
                                    <i class="fas fa-table"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Actions:</label>
                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" @click="createNewDesign" class="flex items-center justify-center px-3 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded">
                                    <i class="fas fa-plus-circle mr-1"></i> Add
                                </button>
                                <button type="button" @click="editSelectedRow" class="flex items-center justify-center px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded" :disabled="!selectedRow" :class="{'opacity-50 cursor-not-allowed': !selectedRow}">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Data Status Information -->
                    <div v-if="loading" class="mt-4 bg-yellow-100 p-3 rounded">
                        <div class="flex items-center">
                            <div class="mr-3">
                                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-yellow-700"></div>
                            </div>
                            <p class="text-sm font-medium text-yellow-800">Loading product design data...</p>
                        </div>
                    </div>
                    <div v-else-if="designs.length === 0" class="mt-4 bg-yellow-100 p-3 rounded">
                        <p class="text-sm font-medium text-yellow-800">No product design data available.</p>
                        <p class="text-xs text-yellow-700 mt-1">Make sure the database is properly configured and seeders have been run.</p>
                        <div class="mt-2 flex items-center space-x-3">
                            <button @click="fetchDesigns" class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded">Reload Data</button>
                        </div>
                    </div>
                    <div v-else class="mt-4 bg-green-100 p-3 rounded">
                        <p class="text-sm font-medium text-green-800">Data available: {{ designs.length }} designs found.</p>
                        <p v-if="selectedRow" @click="editSelectedRow" class="text-xs text-green-700 mt-1 cursor-pointer hover:text-green-900 hover:underline flex items-center">
                            Selected: <span class="font-semibold">{{ selectedRow.pd_code }}</span> - {{ selectedRow.pd_name }}
                            <i class="fas fa-edit ml-2"></i>
                        </p>
                    </div>
                </div>
            </div>
            <!-- Right Column - Quick Info -->
            <div class="lg:col-span-1">
                <!-- Product Design Info Card -->
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-teal-500 mb-6">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-teal-500 rounded-lg mr-3">
                            <i class="fas fa-info-circle text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Product Design Info</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="p-4 bg-teal-50 rounded-lg">
                            <h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2">Instructions</h4>
                            <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                                <li>Product design code must be unique</li>
                                <li>Use the <span class="font-medium">search</span> button to select a design</li>
                                <li>Each design is linked to a specific product</li>
                                <li>Dimensions should be specified in millimeters</li>
                            </ul>
                        </div>

                        <div class="p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors" :class="{'cursor-pointer': selectedRow}" @click="selectedRow && editSelectedRow()">
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="text-sm font-semibold text-blue-800 uppercase tracking-wider">Design Information</h4>
                                <button v-if="selectedRow" @click.stop="editSelectedRow" class="text-blue-600 hover:text-blue-800 p-1 rounded-full hover:bg-blue-200">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                            <div class="space-y-2 text-sm">
                                <div v-if="selectedRow" class="grid grid-cols-2 gap-2">
                                    <div class="font-medium text-gray-700">Code:</div>
                                    <div>{{ selectedRow.pd_code }}</div>
                                    <div class="font-medium text-gray-700">Name:</div>
                                    <div>{{ selectedRow.pd_name }}</div>
                                    <div class="font-medium text-gray-700">Product:</div>
                                    <div>{{ selectedRow.product }}</div>
                                    <div class="font-medium text-gray-700">IDC:</div>
                                    <div>{{ selectedRow.idc }}</div>
                                </div>
                                <div v-else class="text-gray-500 italic">
                                    Select a design to view details
                                </div>
                            </div>
                            <div v-if="selectedRow" class="mt-3 text-xs text-blue-600 flex items-center">
                                <i class="fas fa-info-circle mr-1"></i>
                                Click to edit this design
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-purple-500">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-purple-500 rounded-lg mr-3">
                            <i class="fas fa-link text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Quick Links</h3>
                    </div>

                    <div class="grid grid-cols-1 gap-3">
                        <Link href="/product" class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                            <div class="p-2 bg-purple-500 rounded-full mr-3">
                                <i class="fas fa-box text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-purple-900">Products</p>
                                <p class="text-xs text-purple-700">Manage product categories</p>
                            </div>
                        </Link>

                        <a href="/product-group" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <div class="p-2 bg-blue-500 rounded-full mr-3">
                                <i class="fas fa-drafting-compass text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-blue-900">Product Groups</p>
                                <p class="text-xs text-blue-700">Manage product groups</p>
                            </div>
                        </a>

                        <Link href="/product-design/view-print" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            <div class="p-2 bg-green-500 rounded-full mr-3">
                                <i class="fas fa-print text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-green-900">Print Designs</p>
                                <p class="text-xs text-green-700">Print design list</p>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Table -->
    <ProductDesignModal
        :show="showModal"
        :designs="designs"
        :products="products"
        @close="showModal = false"
        @select="onDesignSelected"
    />

    <!-- Edit Modal -->
    <div v-if="showEditModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-3/4 max-w-4xl mx-auto relative">
            <div class="flex items-center justify-between p-3 bg-blue-600 text-white rounded-t-lg">
                <div class="flex items-center">
                    <div class="p-2 bg-white bg-opacity-20 rounded-lg mr-3">
                        <i class="fas fa-drafting-compass text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold">Define Product Design</h3>
                </div>
                <div class="flex space-x-2">
                    <button type="button" class="px-4 py-1 bg-white text-blue-800 rounded-md flex items-center shadow-sm">
                        <i class="fas fa-search mr-1"></i> Record
                    </button>
                    <button type="button" class="px-4 py-1 bg-yellow-500 hover:bg-yellow-600 text-white rounded-md flex items-center shadow-sm">
                        <i class="fas fa-sync-alt mr-1"></i> Review
                    </button>
                    <button type="button" @click="closeEditModal" class="text-white hover:text-gray-200 p-1 ml-2">
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
                                        <p class="text-xs text-gray-500 mt-1">N Print Total Scoring on CDRi SCH + SB PD</p>
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
                                                <option value="A">A</option>
                                                <option value="B">B</option>
                                                <option value="C">C</option>
                                                <option value="E">E</option>
                                                <option value="F">F</option>
                                                <option value="BC">BC</option>
                                                <option value="BE">BE</option>
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
                    <div class="absolute bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 flex justify-end space-x-4 shadow-md" style="border-bottom-left-radius: 0.5rem; border-bottom-right-radius: 0.5rem;">
                        <button type="button" @click="closeEditModal" class="px-5 py-2 bg-gray-100 text-gray-800 rounded border border-gray-300 hover:bg-gray-200 flex items-center font-medium">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </button>
                        <button type="submit" class="px-5 py-2 bg-blue-500 text-white rounded border border-blue-600 hover:bg-blue-600 flex items-center font-medium">
                            <i class="fas fa-save mr-2"></i>Save
                        </button>
                        <button v-if="!isCreating" type="button" @click="deleteDesign(editForm.pd_code)" class="px-5 py-2 bg-red-500 text-white rounded border border-red-600 hover:bg-red-600 flex items-center font-medium">
                            <i class="fas fa-trash-alt mr-2"></i>Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div v-if="saving" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex justify-center items-center">
        <div class="w-12 h-12 border-4 border-solid border-blue-500 border-t-transparent rounded-full animate-spin"></div>
    </div>
    
    <!-- Notification Toast -->
    <div v-if="notification.show" class="fixed bottom-4 right-4 z-50 shadow-xl rounded-lg transition-all duration-300"
         :class="{
             'bg-green-100 border-l-4 border-green-500': notification.type === 'success',
             'bg-red-100 border-l-4 border-red-500': notification.type === 'error',
             'bg-yellow-100 border-l-4 border-yellow-500': notification.type === 'warning'
         }">
        <div class="p-4 flex items-center">
            <div class="mr-3">
                <i v-if="notification.type === 'success'" class="fas fa-check-circle text-green-500 text-xl"></i>
                <i v-else-if="notification.type === 'error'" class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                <i v-else class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
            </div>
            <div>
                <p :class="{
                    'text-green-800': notification.type === 'success',
                    'text-red-800': notification.type === 'error',
                    'text-yellow-800': notification.type === 'warning'
                }">{{ notification.message }}</p>
            </div>
        </div>
    </div>

    <!-- Product Modal for Edit Form -->
    <ProductModal
        :show="showProductModal"
        :products="products"
        :categories="[]"
        :loading="loading"
        @close="showProductModal = false"
        @select="onProductSelected"
    />
    </AppLayout>
</template>

<script setup>
import { ref, onMounted, watch, computed } from 'vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import ProductDesignModal from '@/Components/product-design-modal.vue';
import ProductModal from '@/Components/product-modal.vue';

// Get the header from props
const props = defineProps({
    header: {
        type: String,
        default: 'Product Design Management'
    }
});

const designs = ref([]);
const products = ref([]);
const loading = ref(false);
const saving = ref(false);
const showModal = ref(false);
const showEditModal = ref(false);
const showProductModal = ref(false);
const selectedRow = ref(null);
const searchQuery = ref('');
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
const notification = ref({ show: false, message: '', type: 'success' });

const fetchDesigns = async () => {
    loading.value = true;
    try {
        const res = await fetch('/api/product-designs', { 
            headers: { 
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            } 
        });
        
        if (!res.ok) {
            throw new Error('Network response was not ok');
        }
        
        const data = await res.json();
        
        if (Array.isArray(data)) {
            designs.value = data;
        } else {
            designs.value = [];
            console.error('Unexpected data format:', data);
        }
    } catch (e) {
        console.error('Error fetching product designs:', e);
        designs.value = [];
    } finally {
        loading.value = false;
    }
};

const fetchProducts = async () => {
    try {
        const res = await fetch('/api/products', { 
            headers: { 
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            } 
        });
        
        if (!res.ok) {
            throw new Error('Network response was not ok');
        }
        
        const data = await res.json();
        
        if (Array.isArray(data)) {
            products.value = data;
        } else {
            products.value = [];
            console.error('Unexpected data format:', data);
        }
    } catch (e) {
        console.error('Error fetching products:', e);
        products.value = [];
    }
};

onMounted(() => {
    fetchDesigns();
    fetchProducts();
});

// Watch for changes in search query to filter the data
watch(searchQuery, (newQuery) => {
    if (newQuery && designs.value.length > 0) {
        const foundDesign = designs.value.find(design => 
            design.pd_code.toLowerCase().includes(newQuery.toLowerCase()) ||
            design.pd_name.toLowerCase().includes(newQuery.toLowerCase())
        );
        
        if (foundDesign) {
            selectedRow.value = foundDesign;
        }
    }
});

const onDesignSelected = (design) => {
    selectedRow.value = design;
    searchQuery.value = design.pd_code;
    
    // Populate the edit form with the selected design data
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
    isCreating.value = false;
    
    // Automatically open the edit modal when a design is selected
    showEditModal.value = true;
};

const editSelectedRow = () => {
    if (selectedRow.value) {
        isCreating.value = false;
        editForm.value = { ...selectedRow.value };
        showEditModal.value = true;
    } else {
        showNotification('Please select a product design first', 'error');
    }
};

const createNewDesign = () => {
    // Reset the form for a new design
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
    isCreating.value = true;
    showEditModal.value = true;
};

const closeEditModal = () => {
    showEditModal.value = false;
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
    isCreating.value = false;
};

const saveDesignChanges = async () => {
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
            body: JSON.stringify({
                pd_code: editForm.value.pd_code,
                pd_name: editForm.value.pd_name,
                pd_alt_name: editForm.value.pd_alt_name,
                pd_design_type: editForm.value.pd_design_type,
                idc: editForm.value.idc,
                product: editForm.value.product,
                joint: editForm.value.joint,
                joint_to_print: editForm.value.joint_to_print,
                pcs_to_joint: editForm.value.pcs_to_joint,
                score: editForm.value.score,
                slot: editForm.value.slot,
                flute_style: editForm.value.flute_style,
                print_flute: editForm.value.print_flute,
                input_weight: editForm.value.input_weight
            })
        });
        
        if (!res.ok) {
            const errorData = await res.json();
            throw new Error(errorData.message || 'Failed to save product design');
        }
        
        const data = await res.json();
        
            if (isCreating.value) {
            // Add the new design to the list
            designs.value.push(data.data);
                showNotification('Product design created successfully', 'success');
            } else {
            // Update the existing design in the list
            const index = designs.value.findIndex(d => d.pd_code === editForm.value.pd_code);
            if (index !== -1) {
                designs.value[index] = {
                    ...designs.value[index],
                    pd_name: editForm.value.pd_name,
                    pd_alt_name: editForm.value.pd_alt_name,
                    pd_design_type: editForm.value.pd_design_type,
                    idc: editForm.value.idc,
                    product: editForm.value.product,
                    joint: editForm.value.joint,
                    joint_to_print: editForm.value.joint_to_print,
                    pcs_to_joint: editForm.value.pcs_to_joint,
                    score: editForm.value.score,
                    slot: editForm.value.slot,
                    flute_style: editForm.value.flute_style,
                    print_flute: editForm.value.print_flute,
                    input_weight: editForm.value.input_weight
                };
                
                // Update the selected row if it's the same design
                if (selectedRow.value && selectedRow.value.pd_code === editForm.value.pd_code) {
                    selectedRow.value.pd_name = editForm.value.pd_name;
                    selectedRow.value.pd_alt_name = editForm.value.pd_alt_name;
                    selectedRow.value.pd_design_type = editForm.value.pd_design_type;
                    selectedRow.value.idc = editForm.value.idc;
                    selectedRow.value.product = editForm.value.product;
                    selectedRow.value.joint = editForm.value.joint;
                    selectedRow.value.joint_to_print = editForm.value.joint_to_print;
                    selectedRow.value.pcs_to_joint = editForm.value.pcs_to_joint;
                    selectedRow.value.score = editForm.value.score;
                    selectedRow.value.slot = editForm.value.slot;
                    selectedRow.value.flute_style = editForm.value.flute_style;
                    selectedRow.value.print_flute = editForm.value.print_flute;
                    selectedRow.value.input_weight = editForm.value.input_weight;
                }
                showNotification('Product design updated successfully', 'success');
            }
        }
        
        closeEditModal();
    } catch (e) {
        console.error('Error saving product design:', e);
        showNotification(e.message || 'Failed to save product design', 'error');
    } finally {
        saving.value = false;
    }
};

const deleteDesign = async (pdCode) => {
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
        
        const result = await response.json();
        
        if (response.ok) {
            // Remove the item from the local array
            designs.value = designs.value.filter(design => design.pd_code !== pdCode);
            
            if (selectedRow.value && selectedRow.value.pd_code === pdCode) {
                selectedRow.value = null;
                searchQuery.value = '';
            }
            
            closeEditModal();
            showNotification('Product design deleted successfully', 'success');
        } else {
            showNotification('Error deleting product design: ' + (result.message || 'Unknown error'), 'error');
        }
    } catch (e) {
        console.error('Error deleting product design:', e);
        showNotification('Error deleting product design. Please try again.', 'error');
    } finally {
        saving.value = false;
    }
};

const showNotification = (message, type = 'success') => {
    notification.value = {
        show: true,
        message,
        type
    };
    
    setTimeout(() => {
        notification.value.show = false;
    }, 3000);
};

const onProductSelected = (product) => {
    editForm.value.product = product.product_code;
    showProductModal.value = false;
};
</script>

<style scoped>
/* Custom styling for the edit modal */
.fixed.inset-0.z-50 .bg-white {
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Toolbar button styles */
.px-3.py-1.bg-green-500,
.px-3.py-1.bg-yellow-500 {
  transition: all 0.2s;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.px-3.py-1.bg-green-500:hover,
.px-3.py-1.bg-yellow-500:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Form section styling */
.border.border-gray-200.rounded-lg {
  transition: all 0.3s;
}

.border.border-gray-200.rounded-lg:hover {
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

/* Form heading styles */
.font-medium.text-gray-700.mb-4.pb-2.border-b {
  color: #2563eb;
  font-weight: 600;
}

/* Radio button styling */
.inline-flex.items-center input[type="radio"] {
  cursor: pointer;
}

.inline-flex.items-center input[type="radio"]:checked {
  background-color: #2563eb;
  border-color: #2563eb;
}

/* Input focus effects */
input:focus, select:focus {
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
  transition: all 0.2s;
}

/* Button hover animations */
.px-4.py-2.bg-blue-500,
.px-4.py-2.bg-red-500,
.px-4.py-2.bg-gray-200 {
  transition: all 0.2s;
}

.px-4.py-2.bg-blue-500:hover,
.px-4.py-2.bg-red-500:hover,
.px-4.py-2.bg-gray-200:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Custom scrollbar for the modal */
.bg-gray-50 {
  scrollbar-width: thin;
  scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
}

.bg-gray-50::-webkit-scrollbar {
  width: 8px;
}

.bg-gray-50::-webkit-scrollbar-track {
  background: transparent;
}

.bg-gray-50::-webkit-scrollbar-thumb {
  background-color: rgba(156, 163, 175, 0.5);
  border-radius: 20px;
}

/* Label styling */
.text-sm.font-medium.text-gray-700 {
  font-weight: 600;
  color: #374151;
}

/* Help text styling */
.text-sm.text-gray-500, .text-xs.text-gray-500 {
  font-style: italic;
}

/* Input Weight explanation text */
.input-weight-explanation {
  display: block !important;
  visibility: visible !important;
  opacity: 1 !important;
  color: #4B5563 !important;
  margin-top: 4px;
  font-size: 0.75rem;
  font-style: normal !important;
  position: relative;
  z-index: 50;
}
</style>
