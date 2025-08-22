<template>
    <!-- Error Modal for MC Model validation -->
    <div v-if="showErrorModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-96 mx-auto">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-red-600 text-white rounded-t-lg">
                <h3 class="text-lg font-semibold flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Error
                </h3>
            </div>
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0 w-10 h-10 mx-auto bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-times text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">
                            <strong>Code: 200015</strong><br>
                            <strong>Type: Please enter Name/Description/Reference/Reason</strong>
                        </p>
                    </div>
                </div>
            </div>
            <div class="flex justify-end p-4 border-t border-gray-200">
                <button 
                    @click="$emit('closeErrorModal')"
                    class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- Setup MC Component Modal -->
    <div v-if="showSetupMcModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-4/5 lg:w-3/4 max-w-6xl mx-auto flex flex-col max-h-[90vh]">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-t-lg">
                <h3 class="text-xl font-semibold flex items-center">
                    <i class="fas fa-cogs mr-3"></i>Setup MC, Component
                </h3>
                <button type="button" @click="$emit('closeSetupMcModal')" class="text-white hover:text-gray-200 focus:outline-none">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="p-6 overflow-y-auto flex-grow">
                <!-- Info Section Above Table -->
                <div class="mb-6 bg-gray-50 p-4 rounded-lg border">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="flex items-center">
                            <label class="text-sm font-medium text-gray-700 w-16">AC#:</label>
                            <input type="text" :value="formData.ac" readonly class="ml-2 px-3 py-1 border border-gray-300 rounded text-sm bg-white flex-1">
                        </div>
                        <div class="flex items-center">
                            <input type="text" :value="formData.customer_name" readonly class="px-3 py-1 border border-gray-300 rounded text-sm bg-white flex-1">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <label class="text-sm font-medium text-gray-700 w-16">MCS#:</label>
                            <input type="text" :value="formData.mcs" readonly class="ml-2 px-3 py-1 border border-gray-300 rounded text-sm bg-white flex-1">
                        </div>
                        <div class="flex items-center">
                            <input type="text" :value="formData.mcs" readonly class="px-3 py-1 border border-gray-300 rounded text-sm bg-white flex-1">
                        </div>
                    </div>
                </div>

                <!-- Field Descriptions -->
                <div class="mb-4 text-sm text-gray-600">
                    <p><strong>Field Descriptions:</strong></p>
                    <ul class="list-disc list-inside mt-2 space-y-1">
                        <li><strong>NO</strong> - Component Number (Sequential numbering)</li>
                        <li><strong>C#</strong> - Component Code (Main, Fit1, Fit2, etc.)</li>
                        <li><strong>PD</strong> - Product Description</li>
                        <li><strong>PCS/SET</strong> - Pieces per Set</li>
                        <li><strong>PART#</strong> - Part Number</li>
                    </ul>
                </div>

                <!-- Component Table -->
                <div class="mb-6">
                    <table class="min-w-full text-sm border border-gray-300">
                        <thead class="bg-teal-600 text-white">
                            <tr>
                                <th class="px-3 py-2 border border-gray-300 text-left">NO</th>
                                <th class="px-3 py-2 border border-gray-300 text-left">C#</th>
                                <th class="px-3 py-2 border border-gray-300 text-left">PD</th>
                                <th class="px-3 py-2 border border-gray-300 text-left">PCS/SET</th>
                                <th class="px-3 py-2 border border-gray-300 text-left">PART#</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            <tr v-for="(component, index) in mcComponents" :key="index"
                                class="hover:bg-gray-100 cursor-pointer"
                                :class="{ 'bg-yellow-200': component.selected }"
                                @click="$emit('selectComponent', component, index)">
                                <td class="px-3 py-2 border border-gray-300">{{ String(index + 1).padStart(2, '0') }}</td>
                                <td class="px-3 py-2 border border-gray-300">{{ component.c_num }}</td>
                                <td class="px-3 py-2 border border-gray-300">{{ component.pd }}</td>
                                <td class="px-3 py-2 border border-gray-300">{{ component.pcs_set }}</td>
                                <td class="px-3 py-2 border border-gray-300">{{ component.part_num }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between">
                    <div class="space-x-2">
                        <button type="button" @click="$emit('setupPD')" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors">
                            Setup PD
                        </button>
                        <button type="button" @click="$emit('setupOthers')" class="px-4 py-2 bg-purple-500 text-white rounded hover:bg-purple-600 transition-colors">
                            Setup Others
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MCS Table Modal -->
    <div v-if="showMcsTableModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
        <!-- Modal content -->
        <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-2/3 lg:w-3/4 max-w-4xl mx-auto flex flex-col max-h-[90vh]">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
                <h3 class="text-xl font-semibold flex items-center">
                    <i class="fas fa-id-card mr-3"></i>Master Card Table
                </h3>
                <div class="flex space-x-3 items-center">
                    <div class="text-white text-sm mr-2">
                        <span class="mr-2">Zoom:</span>
                        <select :value="zoomOption" @change="$emit('handleZoomChange')" class="bg-blue-700 text-white border border-blue-500 rounded px-1 py-0.5 text-xs">
                            <option value="mc_specification">M/card specification</option>
                            <option value="current_price">Current price</option>
                            <option value="stand_by_price">Stand by price</option>
                        </select>
                    </div>
                    <div class="text-white text-sm mr-2">
                        <span class="mr-2">Sort:</span>
                        <select :value="mcsSortOption" @change="handleSortOptionChange" class="bg-blue-700 text-white border border-blue-500 rounded px-1 py-0.5 text-xs">
                            <option value="mc_seq">MC Seq#</option>
                            <option value="mc_model">MC Model</option>
                            <option value="part_no">MC PD Part#</option>
                            <option value="ext_dim_1">MC PD ED</option>
                            <option value="int_dim_1">MC PD ID</option>
                        </select>
                    </div>
                    <div class="text-white text-sm">
                        <span class="mr-2">Order:</span>
                        <select :value="mcsSortOrder" @change="$emit('fetchMcsData')" class="bg-blue-700 text-white border border-blue-500 rounded px-1 py-0.5 text-xs">
                            <option value="asc">Asc</option>
                            <option value="desc">Desc</option>
                        </select>
                    </div>
                    <div class="text-white text-sm">
                        <span class="mr-2">Status:</span>
                        <select @change="$emit('fetchMcsData')" :value="mcsStatusFilter" class="bg-blue-700 text-white border border-blue-500 rounded px-1 py-0.5 text-xs">
                            <option value="Act">Active</option>
                            <option value="Obsolete">Obsolete</option>
                            <option value="all">All</option>
                        </select>
                    </div>
                    <button type="button" @click="$emit('closeMcsTableModal')" class="text-white hover:text-gray-200 focus:outline-none">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-2 overflow-y-auto flex-grow" style="max-height: 60vh;">
                <div class="mb-4 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="relative w-full md:w-64">
                        <input 
                            type="text" 
                            :value="mcsSearchTerm" 
                            placeholder="Search..." 
                            @input="$emit('updateSearchTerm', $event.target.value)"
                            @keyup.enter="$emit('fetchMcsData')"
                            class="border border-gray-300 rounded-md py-1 px-2 text-xs focus:ring-blue-500 focus:border-blue-500 w-full shadow-sm"
                        />
                        <i class="fas fa-search absolute right-2 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
                
                <div v-if="mcsLoading" class="flex justify-center items-center p-4">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                    <span class="ml-2 text-gray-600">Loading data...</span>
                </div>
            
                <div v-else-if="mcsError" class="p-4 text-red-500 bg-red-50 rounded border border-red-200">
                    <div class="font-bold mb-1">Error:</div>
                    <div>{{ mcsError }}</div>
                    <button @click="$emit('fetchMcsData')" class="mt-2 px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">
                        Try Again
                    </button>
                </div>

                <div v-else-if="mcsMasterCards.length === 0" class="p-4 text-amber-700 bg-amber-50 rounded border border-amber-200">
                    No master card records found. Please adjust your filter criteria.
                </div>
            
                <table v-else class="min-w-full text-xs border border-gray-300">
                    <thead class="bg-gray-200 sticky top-0">
                        <tr v-if="mcsSortOption === 'mc_model'">
                            <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Model</th>
                            <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">M/Card Seq#</th>
                            <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                        <tr v-else-if="mcsSortOption === 'part_no'">
                            <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Part No</th>
                            <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MC Seq#</th>
                            <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comp#</th>
                            <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">P/Design</th>
                            <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                        <tr v-else-if="mcsSortOption === 'ext_dim_1'">
                            <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ext. Dimension</th>
                            <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MC Seq#</th>
                            <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comp#</th>
                            <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">P/Design</th>
                            <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Model</th>
                            <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                        <tr v-else-if="mcsSortOption === 'int_dim_1'">
                            <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Int. Dimension</th>
                            <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MC Seq#</th>
                            <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comp#</th>
                            <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">P/Design</th>
                            <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Model</th>
                            <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                        <tr v-else>
                            <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">M/Card Seq#</th>
                            <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Model</th>
                            <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Part</th>
                            <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comp#</th>
                            <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="mcs in mcsMasterCards" :key="mcs.seq" 
                            class="hover:bg-blue-100 cursor-pointer"
                            :class="{ 'bg-blue-200': selectedMcs?.seq === mcs.seq }"
                            @click="$emit('selectMcsItem', mcs)"
                            @dblclick="$emit('selectMcs', mcs)">
                            <template v-if="mcsSortOption === 'mc_model'">
                                <td class="px-2 py-1 border border-gray-300">{{ mcs.model }}</td>
                                <td class="px-2 py-1 border border-gray-300">{{ mcs.seq }}</td>
                                <td class="px-2 py-1 border border-gray-300">
                                    <span
                                        :class="(mcs.status === 'Act' || mcs.status === 'Active') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                        class="px-2 py-0.5 rounded-full text-xs">
                                        {{ mcs.status }}
                                    </span>
                                </td>
                            </template>
                            <template v-else-if="mcsSortOption === 'part_no'">
                                <td class="px-2 py-1 border border-gray-300">{{ mcs.part }}</td>
                                <td class="px-2 py-1 border border-gray-300">{{ mcs.seq }}</td>
                                <td class="px-2 py-1 border border-gray-300">{{ mcs.comp }}</td>
                                <td class="px-2 py-1 border border-gray-300">{{ mcs.p_design }}</td>
                                <td class="px-2 py-1 border border-gray-300">
                                    <span
                                        :class="(mcs.status === 'Act' || mcs.status === 'Active') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                        class="px-2 py-0.5 rounded-full text-xs">
                                        {{ mcs.status }}
                                    </span>
                                </td>
                            </template>
                            <template v-else-if="mcsSortOption === 'ext_dim_1'">
                                <td class="px-2 py-1 border border-gray-300">{{ mcs.ext_dim_1 }}x{{ mcs.ext_dim_2 }}x{{ mcs.ext_dim_3 }}</td>
                                <td class="px-2 py-1 border border-gray-300">{{ mcs.seq }}</td>
                                <td class="px-2 py-1 border border-gray-300">{{ mcs.comp }}</td>
                                <td class="px-2 py-1 border border-gray-300">{{ mcs.p_design }}</td>
                                <td class="px-2 py-1 border border-gray-300">{{ mcs.model }}</td>
                                <td class="px-2 py-1 border border-gray-300">
                                    <span
                                        :class="(mcs.status === 'Act' || mcs.status === 'Active') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                        class="px-2 py-0.5 rounded-full text-xs">
                                        {{ mcs.status }}
                                    </span>
                                </td>
                            </template>
                            <template v-else-if="mcsSortOption === 'int_dim_1'">
                                <td class="px-2 py-1 border border-gray-300">{{ mcs.int_dim_1 }}x{{ mcs.int_dim_2 }}x{{ mcs.int_dim_3 }}</td>
                                <td class="px-2 py-1 border border-gray-300">{{ mcs.seq }}</td>
                                <td class="px-2 py-1 border border-gray-300">{{ mcs.comp }}</td>
                                <td class="px-2 py-1 border border-gray-300">{{ mcs.p_design }}</td>
                                <td class="px-2 py-1 border border-gray-300">{{ mcs.model }}</td>
                                <td class="px-2 py-1 border border-gray-300">
                                    <span
                                        :class="(mcs.status === 'Act' || mcs.status === 'Active') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                        class="px-2 py-0.5 rounded-full text-xs">
                                        {{ mcs.status }}
                                    </span>
                                </td>
                            </template>
                            <template v-else>
                                <td class="px-2 py-1 border border-gray-300">{{ mcs.seq }}</td>
                                <td class="px-2 py-1 border border-gray-300">{{ mcs.model }}</td>
                                <td class="px-2 py-1 border border-gray-300">{{ mcs.part }}</td>
                                <td class="px-2 py-1 border border-gray-300">{{ mcs.comp }}</td>
                                <td class="px-2 py-1 border border-gray-300">
                                    <span
                                        :class="(mcs.status === 'Act' || mcs.status === 'Active') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                        class="px-2 py-0.5 rounded-full text-xs">
                                        {{ mcs.status }}
                                    </span>
                                </td>
                            </template>
                        </tr>
                    </tbody>
                </table>

                <!-- Detail Fields for MC PD Part# Sort Option -->
                <div v-if="mcsSortOption === 'part_no'" class="mt-4 border border-gray-400 rounded bg-white">
                    <div class="bg-blue-200 px-3 py-1 border-b border-gray-400">
                        <h4 class="text-sm font-bold text-blue-900">MASTER CARD DETAILS</h4>
                    </div>
                    <div class="p-3">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="flex items-center">
                                <label class="text-xs font-medium w-16">Model:</label>
                                <input 
                                    type="text" 
                                    :value="selectedMcs?.model || ''" 
                                    readonly 
                                    class="flex-1 px-2 py-1 border border-gray-400 text-xs bg-white"
                                />
                            </div>
                            <div class="flex items-center">
                                <label class="text-xs font-medium w-16">Ext. Dim:</label>
                                <div class="flex items-center space-x-1 flex-1">
                                    <input 
                                        type="text" 
                                        :value="selectedMcs?.ext_dim_1 || ''" 
                                        readonly 
                                        class="w-16 px-1 py-1 border border-gray-400 text-xs text-center bg-white"
                                    />
                                    <span class="text-xs text-gray-600">x</span>
                                    <input 
                                        type="text" 
                                        :value="selectedMcs?.ext_dim_2 || ''" 
                                        readonly 
                                        class="w-16 px-1 py-1 border border-gray-400 text-xs text-center bg-white"
                                    />
                                    <span class="text-xs text-gray-600">x</span>
                                    <input 
                                        type="text" 
                                        :value="selectedMcs?.ext_dim_3 || ''" 
                                        readonly 
                                        class="w-16 px-1 py-1 border border-gray-400 text-xs text-center bg-white"
                                    />
                                </div>
                            </div>
                            <div class="flex items-center">
                                <label class="text-xs font-medium w-16">Int. Dim:</label>
                                <div class="flex items-center space-x-1 flex-1">
                                    <input 
                                        type="text" 
                                        :value="selectedMcs?.int_dim_1 || ''" 
                                        readonly 
                                        class="w-16 px-1 py-1 border border-gray-400 text-xs text-center bg-white"
                                    />
                                    <span class="text-xs text-gray-600">x</span>
                                    <input 
                                        type="text" 
                                        :value="selectedMcs?.int_dim_2 || ''" 
                                        readonly 
                                        class="w-16 px-1 py-1 border border-gray-400 text-xs text-center bg-white"
                                    />
                                    <span class="text-xs text-gray-600">x</span>
                                    <input 
                                        type="text" 
                                        :value="selectedMcs?.int_dim_3 || ''" 
                                        readonly 
                                        class="w-16 px-1 py-1 border border-gray-400 text-xs text-center bg-white"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Fields for MC PD ED Sort Option -->
                <div v-if="mcsSortOption === 'ext_dim_1'" class="mt-4 border border-gray-400 rounded bg-white">
                    <div class="bg-blue-200 px-3 py-1 border-b border-gray-400">
                        <h4 class="text-sm font-bold text-blue-900">MASTER CARD DETAILS</h4>
                    </div>
                    <div class="p-3">
                        <div class="flex items-center">
                            <label class="text-xs font-medium w-16">Model:</label>
                            <input 
                                type="text" 
                                :value="selectedMcs?.model || ''" 
                                readonly 
                                class="flex-1 px-2 py-1 border border-gray-400 text-xs bg-white"
                            />
                        </div>
                    </div>
                </div>

                <!-- Detail Fields for MC PD ID Sort Option -->
                <div v-if="mcsSortOption === 'int_dim_1'" class="mt-4 border border-gray-400 rounded bg-white">
                    <div class="bg-blue-200 px-3 py-1 border-b border-gray-400">
                        <h4 class="text-sm font-bold text-blue-900">MASTER CARD DETAILS</h4>
                    </div>
                    <div class="p-3">
                        <div class="flex items-center">
                            <label class="text-xs font-medium w-16">Model:</label>
                            <input 
                                type="text" 
                                :value="selectedMcs?.model || ''" 
                                readonly 
                                class="flex-1 px-2 py-1 border border-gray-400 text-xs bg-white"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end gap-2 p-2 border-t border-gray-200 bg-gray-100 rounded-b-lg flex-shrink-0">
                <div class="text-xs text-gray-500 mr-auto" v-if="mcsMasterCards.length > 0">
                    {{ mcsMasterCards.length }} records found
                </div>
                <!-- Pagination -->
                <button @click="$emit('goToMcsPage', mcsCurrentPage - 1)" :disabled="mcsCurrentPage === 1" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-3 rounded text-xs disabled:opacity-50 disabled:cursor-not-allowed"><i class="fas fa-chevron-left"></i> Previous</button>
                <span class="text-gray-600 text-xs">Page {{ mcsCurrentPage }} of {{ mcsLastPage }}</span>
                <button @click="$emit('goToMcsPage', mcsCurrentPage + 1)" :disabled="mcsCurrentPage === mcsLastPage" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-3 rounded text-xs disabled:opacity-50 disabled:cursor-not-allowed">Next <i class="fas fa-chevron-right"></i></button>
                
                <button 
                    @click="$emit('selectMcs', selectedMcs)" 
                    :disabled="!selectedMcs"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-xs disabled:opacity-50 disabled:cursor-not-allowed">
                    Select
                </button>
                <button type="button" @click="$emit('closeMcsTableModal')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-3 rounded text-xs">Exit</button>
            </div>
        </div>
    </div>

    <!-- Setup PD Modal -->
    <div v-if="showSetupPdModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-5/6 lg:w-11/12 max-w-7xl mx-auto flex flex-col max-h-[95vh]">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-3 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
                <h3 class="text-lg font-semibold flex items-center">
                    <i class="fas fa-cogs mr-2"></i>Setup MC, PD
                </h3>
                <div class="flex space-x-2">
                    <button type="button" class="text-white hover:text-red-300 focus:outline-none">
                        <i class="fas fa-power-off text-lg"></i>
                    </button>
                    <button type="button" class="text-white hover:text-green-300 focus:outline-none">
                        <i class="fas fa-file text-lg"></i>
                    </button>
                    <button type="button" class="text-white hover:text-yellow-300 focus:outline-none">
                        <i class="fas fa-save text-lg"></i>
                    </button>
                    <button type="button" @click="$emit('closeSetupPdModal')" class="text-white hover:text-gray-200 focus:outline-none">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-4 overflow-y-auto flex-grow bg-gray-50">
                <!-- PRODUCT Section -->
                <div class="border border-gray-400 rounded mb-4 bg-white">
                    <div class="bg-blue-200 px-3 py-1 border-b border-gray-400">
                        <h4 class="text-sm font-bold text-blue-900">PRODUCT</h4>
                    </div>
                    <div class="p-3">
                        <!-- Row 1: P/Design, Flute, Part No -->
                        <div class="flex items-center mb-2">
                            <div class="flex items-center space-x-4 flex-1">
                                <div class="flex items-center">
                                    <label class="text-xs font-medium w-16">P/Design:</label>
                                    <input type="text" class="w-32 px-2 py-1 border border-gray-400 text-xs">
                                    <button class="ml-1 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                                <div class="flex items-center">
                                    <label class="text-xs font-medium w-12">Flute:</label>
                                    <input type="text" class="w-20 px-2 py-1 border border-gray-400 text-xs">
                                    <button class="ml-1 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                                <div class="flex items-center">
                                    <label class="text-xs font-medium w-16">Part No:</label>
                                    <input type="text" class="w-40 px-2 py-1 border border-gray-400 text-xs">
                                </div>
                                <div class="flex items-center">
                                    <button class="px-3 py-1 bg-blue-200 border border-gray-400 text-xs font-medium">Description</button>
                                </div>
                            </div>
                            <!-- Right side column for measurements -->
                            <div class="w-64 flex flex-col space-y-1">
                                <div class="flex items-center justify-between">
                                    <label class="text-xs font-medium">System Gross Area:</label>
                                    <div class="flex items-center">
                                        <input type="text" value="0.0000" class="w-16 px-2 py-1 border border-gray-400 text-xs text-right">
                                        <span class="text-xs ml-1">m2</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Row 2: B/Quality (Yellow background) -->
                        <div class="flex items-center mb-2">
                            <div class="flex items-center bg-yellow-200 p-2 rounded flex-1">
                                <div class="flex items-center space-x-2">
                                    <label class="text-xs font-bold w-16">B/Quality:</label>
                                    <input type="text" value="DB" class="w-12 px-1 py-1 border border-gray-400 text-xs text-center bg-yellow-100">
                                    <input type="text" value="B" class="w-12 px-1 py-1 border border-gray-400 text-xs text-center bg-yellow-100">
                                    <input type="text" value="1L" class="w-12 px-1 py-1 border border-gray-400 text-xs text-center bg-yellow-100">
                                    <input type="text" value="A/C/E" class="w-16 px-1 py-1 border border-gray-400 text-xs text-center bg-yellow-100">
                                    <input type="text" value="2L" class="w-12 px-1 py-1 border border-gray-400 text-xs text-center bg-yellow-100">
                                </div>
                            </div>
                            <!-- Right side column for measurements -->
                            <div class="w-64 flex flex-col space-y-1">
                                <div class="flex items-center justify-between">
                                    <label class="text-xs font-medium">System Gross Weight:</label>
                                    <div class="flex items-center">
                                        <input type="text" value="0.0000" class="w-16 px-2 py-1 border border-gray-400 text-xs text-right">
                                        <span class="text-xs ml-1">kg</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Row 3: SQ -->
                        <div class="flex items-center mb-2">
                            <div class="flex items-center space-x-4 flex-1">
                                <div class="flex items-center">
                                    <label class="text-xs font-medium w-8">SQ:</label>
                                    <input type="text" class="w-32 px-2 py-1 border border-gray-400 text-xs">
                                    <button class="ml-1 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                                <div class="flex items-center">
                                    <input type="text" class="w-32 px-2 py-1 border border-gray-400 text-xs">
                                    <button class="ml-1 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- Right side column for measurements -->
                            <div class="w-64 flex flex-col space-y-1">
                                <div class="flex items-center justify-between">
                                    <label class="text-xs font-medium">Input Gross Area:</label>
                                    <div class="flex items-center">
                                        <input type="text" class="w-16 px-2 py-1 border border-gray-400 text-xs text-right">
                                        <span class="text-xs ml-1">m2</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Row 4: WQ -->
                        <div class="flex items-center mb-2">
                            <div class="flex items-center space-x-4 flex-1">
                                <div class="flex items-center">
                                    <label class="text-xs font-medium w-8">WQ:</label>
                                    <input type="text" class="w-32 px-2 py-1 border border-gray-400 text-xs">
                                    <button class="ml-1 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                                <div class="flex items-center">
                                    <input type="text" class="w-32 px-2 py-1 border border-gray-400 text-xs">
                                    <button class="ml-1 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- Right side column for measurements -->
                            <div class="w-64 flex flex-col space-y-1">
                                <div class="flex items-center justify-between">
                                    <label class="text-xs font-medium">Input Gross Weight:</label>
                                    <div class="flex items-center">
                                        <input type="text" class="w-16 px-2 py-1 border border-gray-400 text-xs text-right">
                                        <span class="text-xs ml-1">kg</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Row 5: ID -->
                        <div class="flex items-center mb-2">
                            <div class="flex items-center space-x-4 flex-1">
                                <div class="flex items-center">
                                    <label class="text-xs font-medium w-8">ID:</label>
                                    <input type="text" class="w-16 px-2 py-1 border border-gray-400 text-xs">
                                    <span class="text-xs ml-1">L</span>
                                    <input type="text" class="w-16 px-2 py-1 border border-gray-400 text-xs ml-2">
                                    <span class="text-xs ml-1">W</span>
                                    <input type="text" class="w-16 px-2 py-1 border border-gray-400 text-xs ml-2">
                                    <span class="text-xs ml-1">H</span>
                                    <span class="ml-2 px-2 py-1 bg-red-200 border border-red-400 text-xs">t2e</span>
                                </div>
                                <div class="flex items-center">
                                    <label class="text-xs font-medium w-16">Pcs/Set:</label>
                                    <input type="text" class="w-16 px-2 py-1 border border-gray-400 text-xs">
                                </div>
                                <div class="flex items-center">
                                    <label class="text-xs font-medium w-16">Crease:</label>
                                    <input type="text" class="w-16 px-2 py-1 border border-gray-400 text-xs">
                                </div>
                                <div class="flex items-center">
                                    <label class="text-xs font-medium w-16">Chem Coat:</label>
                                    <input type="text" class="w-16 px-2 py-1 border border-gray-400 text-xs">
                                </div>
                            </div>
                            <!-- Right side column for measurements -->
                            <div class="w-64 flex flex-col space-y-1">
                                <div class="flex items-center justify-between">
                                    <label class="text-xs font-medium">Input Net Area:</label>
                                    <div class="flex items-center">
                                        <input type="text" class="w-16 px-2 py-1 border border-gray-400 text-xs text-right">
                                        <span class="text-xs ml-1">m2</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Row 6: ED -->
                        <div class="flex items-center mb-2">
                            <div class="flex items-center space-x-4 flex-1">
                                <div class="flex items-center">
                                    <label class="text-xs font-medium w-8">ED:</label>
                                    <input type="text" class="w-16 px-2 py-1 border border-gray-400 text-xs">
                                    <span class="text-xs ml-1">L</span>
                                    <input type="text" class="w-16 px-2 py-1 border border-gray-400 text-xs ml-2">
                                    <span class="text-xs ml-1">W</span>
                                    <input type="text" class="w-16 px-2 py-1 border border-gray-400 text-xs ml-2">
                                    <span class="text-xs ml-1">H</span>
                                    <span class="ml-2 px-2 py-1 bg-red-200 border border-red-400 text-xs">e2t</span>
                                </div>
                                <div class="flex items-center">
                                    <label class="text-xs font-medium w-16">Nest/Slot:</label>
                                    <input type="text" class="w-16 px-2 py-1 border border-gray-400 text-xs">
                                </div>
                                <div class="flex items-center">
                                    <label class="text-xs font-medium w-16">R/F Tape:</label>
                                    <input type="text" class="w-16 px-2 py-1 border border-gray-400 text-xs">
                                    <button class="ml-1 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- Right side column for measurements -->
                            <div class="w-64 flex flex-col space-y-1">
                                <div class="flex items-center justify-between">
                                    <label class="text-xs font-medium">Input Net Weight:</label>
                                    <div class="flex items-center">
                                        <input type="text" class="w-16 px-2 py-1 border border-gray-400 text-xs text-right">
                                        <span class="text-xs ml-1">kg</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CORRUGATING Section -->
                <div class="border border-gray-400 rounded mb-4 bg-white">
                    <div class="bg-blue-200 px-3 py-1 border-b border-gray-400">
                        <h4 class="text-sm font-bold text-blue-900">CORRUGATING</h4>
                    </div>
                    <div class="p-3">
                        <!-- Score L Row -->
                        <div class="flex items-center mb-2">
                            <label class="text-xs font-medium w-16">Score L:</label>
                            <div class="flex space-x-1">
                                <input type="text" class="w-12 px-1 py-1 border border-gray-400 text-xs text-center" v-for="i in 10" :key="'scoreL'+i">
                            </div>
                            <button class="ml-2 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300">
                                <i class="fas fa-calculator"></i>
                            </button>
                            <div class="ml-auto flex items-center">
                                <label class="text-xs font-medium w-20">Sheet Length:</label>
                                <input type="text" class="w-16 px-2 py-1 border border-gray-400 text-xs">
                                <button class="ml-1 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300">
                                    <i class="fas fa-search"></i>
                                </button>
                                <span class="text-xs ml-1">mm</span>
                            </div>
                        </div>

                        <!-- Score W Row -->
                        <div class="flex items-center mb-2">
                            <label class="text-xs font-medium w-16">Score W:</label>
                            <div class="flex space-x-1">
                                <input type="text" class="w-12 px-1 py-1 border border-gray-400 text-xs text-center" v-for="i in 10" :key="'scoreW'+i">
                            </div>
                            <button class="ml-2 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300">
                                <i class="fas fa-calculator"></i>
                            </button>
                            <div class="ml-auto flex items-center">
                                <label class="text-xs font-medium w-20">Sheet Width:</label>
                                <input type="text" class="w-16 px-2 py-1 border border-gray-400 text-xs">
                                <span class="text-xs ml-1">mm</span>
                                <div class="ml-4 bg-gray-200 border border-gray-400 px-3 py-1 text-xs font-bold">
                                    IDC/CAD
                                </div>
                            </div>
                        </div>

                        <!-- P/Size Row -->
                        <div class="flex items-center mb-2 space-x-4">
                            <div class="flex items-center">
                                <label class="text-xs font-medium w-12">P/Size:</label>
                                <input type="text" class="w-16 px-2 py-1 border border-gray-400 text-xs">
                                <button class="ml-1 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300">
                                    <i class="fas fa-search"></i>
                                </button>
                                <span class="text-xs ml-1">mm</span>
                            </div>
                            <div class="flex items-center">
                                <label class="text-xs font-medium w-16">Con. Out:</label>
                                <input type="text" class="w-16 px-2 py-1 border border-gray-400 text-xs">
                                <button class="ml-1 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <div class="flex items-center">
                                <label class="text-xs font-medium w-20">Conv. Duct x 2:</label>
                                <input type="text" class="w-16 px-2 py-1 border border-gray-400 text-xs">
                            </div>
                            <div class="flex items-center">
                                <label class="text-xs font-medium w-20">Pcs-to-Joint:</label>
                                <input type="text" class="w-16 px-2 py-1 border border-gray-400 text-xs">
                            </div>
                            <div class="flex items-center">
                                <label class="text-xs font-medium w-12">S/Tool:</label>
                                <input type="text" value="1" class="w-8 px-2 py-1 border border-gray-400 text-xs">
                                <button class="ml-1 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CONVERTING Section -->
                <div class="border border-gray-400 rounded mb-4 bg-white">
                    <div class="bg-blue-200 px-3 py-1 border-b border-gray-400">
                        <h4 class="text-sm font-bold text-blue-900">CONVERTING</h4>
                    </div>
                    <div class="p-3">
                        <!-- Print Color Row -->
                        <div class="flex items-center mb-2">
                            <label class="text-xs font-medium w-20">Print Color:</label>
                            <div class="flex space-x-1">
                                <button class="w-8 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300" v-for="i in 7" :key="'color'+i">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Print Area Row -->
                        <div class="flex items-center mb-2">
                            <label class="text-xs font-medium w-20">Print Area(%):</label>
                            <div class="flex space-x-1">
                                <input type="text" value="0.00" class="w-12 px-1 py-1 border border-gray-400 text-xs text-center" v-for="i in 7" :key="'area'+i">
                            </div>
                            <div class="ml-auto flex items-center">
                                <label class="text-xs font-medium w-16">Pit Block#:</label>
                                <input type="text" class="w-32 px-2 py-1 border border-gray-400 text-xs">
                            </div>
                        </div>

                        <!-- D/Cut Sheet Row -->
                        <div class="flex items-center mb-2 space-x-4">
                            <div class="flex items-center">
                                <label class="text-xs font-medium w-20">D/Cut Sheet:</label>
                                <input type="text" class="w-16 px-2 py-1 border border-gray-400 text-xs">
                                <span class="text-xs ml-1">L</span>
                                <input type="text" class="w-16 px-2 py-1 border border-gray-400 text-xs ml-2">
                                <span class="text-xs ml-1">W</span>
                                <button class="ml-1 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <div class="flex items-center">
                                <label class="text-xs font-medium w-20">D/Cut Block#:</label>
                                <input type="text" class="w-24 px-2 py-1 border border-gray-400 text-xs">
                            </div>
                            <div class="flex items-center">
                                <label class="text-xs font-medium w-16">Glueing:</label>
                                <input type="text" class="w-20 px-2 py-1 border border-gray-400 text-xs">
                                <button class="ml-1 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <div class="ml-auto flex items-center space-x-2">
                                <div class="flex items-center">
                                    <input type="checkbox" id="handHole" class="mr-1">
                                    <label for="handHole" class="text-xs">Hand Hole:</label>
                                </div>
                            </div>
                        </div>

                        <!-- D/Cut Mould Row -->
                        <div class="flex items-center mb-2 space-x-4">
                            <div class="flex items-center">
                                <label class="text-xs font-medium w-20">D/Cut Mould:</label>
                                <input type="text" class="w-16 px-2 py-1 border border-gray-400 text-xs">
                                <span class="text-xs ml-1">L</span>
                                <input type="text" class="w-16 px-2 py-1 border border-gray-400 text-xs ml-2">
                                <span class="text-xs ml-1">W</span>
                                <button class="ml-1 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <div class="flex items-center">
                                <label class="text-xs font-medium w-24">Stitch Wire Pcs:</label>
                                <input type="text" class="w-16 px-2 py-1 border border-gray-400 text-xs">
                                <button class="ml-1 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300">
                                    <i class="fas fa-search"></i>
                                </button>
                                <button class="ml-1 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300">
                                    <i class="fas fa-calculator"></i>
                                </button>
                            </div>
                            <div class="flex items-center">
                                <label class="text-xs font-medium w-16">Wrapping:</label>
                                <input type="text" class="w-20 px-2 py-1 border border-gray-400 text-xs">
                                <button class="ml-1 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <div class="ml-auto flex items-center space-x-2">
                                <div class="flex items-center">
                                    <input type="checkbox" id="rotaryDCut" class="mr-1">
                                    <label for="rotaryDCut" class="text-xs">Rotary D/Cut:</label>
                                </div>
                            </div>
                        </div>

                        <!-- Finishing Row -->
                        <div class="flex items-center mb-2 space-x-4">
                            <div class="flex items-center">
                                <label class="text-xs font-medium w-16">Finishing:</label>
                                <input type="text" class="w-32 px-2 py-1 border border-gray-400 text-xs">
                                <button class="ml-1 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <div class="flex items-center">
                                <label class="text-xs font-medium w-24">Bundle String Qty:</label>
                                <input type="text" class="w-16 px-2 py-1 border border-gray-400 text-xs">
                                <button class="ml-1 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300">
                                    <i class="fas fa-search"></i>
                                </button>
                                <button class="ml-1 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300">
                                    <i class="fas fa-calculator"></i>
                                </button>
                            </div>
                            <div class="flex items-center">
                                <label class="text-xs font-medium w-16">Bdl/Pallet:</label>
                                <input type="text" value="0" class="w-12 px-2 py-1 border border-gray-400 text-xs">
                            </div>
                            <div class="ml-auto flex items-center space-x-2">
                                <div class="flex items-center">
                                    <input type="checkbox" id="fullBlockPrint" class="mr-1">
                                    <label for="fullBlockPrint" class="text-xs">Full Block Print:</label>
                                </div>
                            </div>
                        </div>

                        <!-- Item Remark Row -->
                        <div class="flex items-center">
                            <div class="flex items-center">
                                <label class="text-xs font-medium w-20">Item Remark:</label>
                                <input type="text" class="w-40 px-2 py-1 border border-gray-400 text-xs">
                            </div>
                            <div class="ml-auto flex items-center space-x-4">
                                <div class="flex items-center">
                                    <label class="text-xs font-medium w-16">Peel Off%:</label>
                                    <input type="text" value="0.00" class="w-16 px-2 py-1 border border-gray-400 text-xs text-right">
                                </div>
                                <div class="bg-gray-200 border border-gray-400 px-3 py-1 text-center">
                                    <div class="text-xs font-bold">MSP</div>
                                    <div class="text-xs">Sub Material</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineEmits, defineProps } from 'vue';

const handleSortOptionChange = (event) => {
    const newSortOption = event.target.value;
    emit('updateSortOption', newSortOption);
    emit('fetchMcsData');
};

const props = defineProps({
    showErrorModal: Boolean,
    showSetupMcModal: Boolean,
    showSetupPdModal: Boolean,
    showMcsTableModal: Boolean,
    formData: Object,
    mcComponents: Array,
    zoomOption: String,
    mcsSortOption: String,
    mcsSortOrder: String,
    mcsStatusFilter: String,
    mcsSearchTerm: String,
    mcsLoading: Boolean,
    mcsError: String,
    mcsMasterCards: Array,
    selectedMcs: Object,
    mcsCurrentPage: Number,
    mcsLastPage: Number
});

const emit = defineEmits([
    'closeErrorModal',
    'closeSetupMcModal',
    'closeSetupPdModal',
    'closeMcsTableModal',
    'selectComponent',
    'setupPD',
    'setupOthers',
    'handleZoomChange',
    'fetchMcsData',
    'selectMcsItem',
    'selectMcs',
    'goToMcsPage',
    'updateSearchTerm',
    'updateSortOption'
]);
</script>

<style scoped>
/* Add transition effects */
.transform {
    transition-property: transform, opacity;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}

/* Add modal animations */
@keyframes modalFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes modalScaleIn {
    from { transform: scale(0.95); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

.fixed.inset-0 {
    animation: modalFadeIn 0.2s ease-out forwards;
}

.fixed.inset-0 > div {
    animation: modalScaleIn 0.3s ease-out forwards;
}

/* Table styling */
table {
    border-collapse: collapse;
}
</style> 