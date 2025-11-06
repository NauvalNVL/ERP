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
                            <input type="text" :value="formData.mc_model" readonly class="px-3 py-1 border border-gray-300 rounded text-sm bg-white flex-1">
                        </div>
                    </div>
                </div>
                <!-- Component Table -->
                <div class="mb-6">
                    <table class="min-w-full text-sm border border-gray-300">
                        <thead class="bg-gray-200 text-gray-800">
                            <tr>
                                <th class="px-3 py-2 border border-gray-300 text-left">NO</th>
                                <th class="px-3 py-2 border border-gray-300 text-left">C#</th>
                                <th class="px-3 py-2 border border-gray-300 text-left">PD</th>
                                <th class="px-3 py-2 border border-gray-300 text-left">PCS/SET</th>
                                <th class="px-3 py-2 border border-gray-300 text-left">PART#</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            <tr v-for="(component, index) in localComponents" :key="index"
                                class="hover:bg-gray-100 cursor-pointer"
                                :class="{ 'bg-yellow-200': selectedComponentIndex === index }"
                                @click="onSelectComponent(component, index)">
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
                        <button type="button" @click="openSetupPd()" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors">
                            Setup PD
                        </button>
                        <button type="button" @click="$emit('setupOthers')" class="px-4 py-2 bg-purple-500 text-white rounded hover:bg-purple-600 transition-colors">
                            Setup Others
                        </button>
                    </div>
                    <div class="text-xs text-gray-600 self-center" v-if="selectedComponentIndex !== null">
                        Editing component: <span class="font-semibold">{{ localComponents[selectedComponentIndex]?.c_num }}</span>
                    </div>
                </div>
            </div>
            <!-- Modal Footer -->
            <div class="flex items-center justify-end gap-2 p-3 border-t border-gray-200 bg-gray-100 rounded-b-lg flex-shrink-0">
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
                        <select
                            :value="mcsSortOrder"
                            @input="$emit('update:mcsSortOrder', $event.target.value); $emit('fetchMcsData')"
                            class="bg-blue-700 text-white border border-blue-500 rounded px-1 py-0.5 text-xs"
                        >
                            <option value="asc">Asc</option>
                            <option value="desc">Desc</option>
                        </select>
                    </div>
                    <div class="text-white text-sm">
                        <span class="mr-2">Status:</span>
                        <select
                            :value="mcsStatusFilter"
                            @input="$emit('update:mcsStatusFilter', $event.target.value); $emit('fetchMcsData')"
                            class="bg-blue-700 text-white border border-blue-500 rounded px-1 py-0.5 text-xs"
                        >
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
                                    <input type="text" v-model="selectedProductDesign" class="w-32 px-2 py-1 border border-gray-400 text-xs" readonly>
                                    <button @click="showProductDesignModal = true" class="ml-1 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                                <div class="flex items-center">
                                    <label class="text-xs font-medium w-12">Flute:</label>
                                    <input type="text" v-model="selectedPaperFlute" class="w-20 px-2 py-1 border border-gray-400 text-xs" readonly>
                                    <button @click="showPaperFluteModal = true" class="ml-1 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                                <div class="flex items-center">
                                    <label class="text-xs font-medium w-16">Part No:</label>
                                    <input type="text" v-model="partNo" class="w-40 px-2 py-1 border border-gray-400 text-xs">
                                </div>
                                <div class="flex items-center">
                                    <button class="px-3 py-1 bg-blue-200 border border-gray-400 text-xs font-medium" @click="showMoreDescriptionModal = true">Description</button>
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

                        <!-- Column Headers -->
                        <div class="flex items-center mb-1">
                            <div class="flex items-center p-2 flex-1">
                                <div class="flex items-center space-x-2">
                                    <div class="w-16"></div> <!-- Spacer for label column -->
                                    <div class="w-12 text-xs text-center font-medium text-gray-600">DB</div>
                                    <div class="w-12 text-xs text-center font-medium text-gray-600">B</div>
                                    <div class="w-12 text-xs text-center font-medium text-gray-600">!L</div>
                                    <div class="w-16 text-xs text-center font-medium text-gray-600">A/C/E</div>
                                    <div class="w-12 text-xs text-center font-medium text-gray-600">2L</div>
                                </div>
                            </div>
                            <!-- Right side column spacer -->
                            <div class="w-64"></div>
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



                        <!-- Row 3: SO -->
                        <div class="flex items-center mb-2">
                            <div class="flex items-center p-2 rounded flex-1">
                                <div class="flex items-center justify-between w-full">
                                    <div class="flex items-center space-x-1">
                                        <label class="text-xs font-bold w-16">SO:</label>
                                        <input type="text" :value="getSoDisplay(0)" readonly class="w-12 px-1 py-1 border border-gray-400 text-xs text-center bg-gray-50">
                                        <button @click="emit('openPaperQualityModal', 0)" class="px-1 py-1 bg-blue-500 border border-blue-600 text-xs hover:bg-blue-600 text-white" title="Select Paper Quality">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <input type="text" :value="getSoDisplay(1)" readonly class="w-12 px-1 py-1 border border-gray-400 text-xs text-center bg-gray-50">
                                        <button @click="emit('openPaperQualityModal', 1)" class="px-1 py-1 bg-blue-500 border border-blue-600 text-xs hover:bg-blue-600 text-white" title="Select Paper Quality">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <input type="text" :value="getSoDisplay(2)" readonly class="w-12 px-1 py-1 border border-gray-400 text-xs text-center bg-gray-50">
                                        <button @click="emit('openPaperQualityModal', 2)" class="px-1 py-1 bg-blue-500 border border-blue-600 text-xs hover:bg-blue-600 text-white" title="Select Paper Quality">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <input type="text" :value="getSoDisplay(3)" readonly class="w-16 px-1 py-1 border border-gray-400 text-xs text-center bg-gray-50">
                                        <button @click="emit('openPaperQualityModal', 3)" class="px-1 py-1 bg-blue-500 border border-blue-600 text-xs hover:bg-blue-600 text-white" title="Select Paper Quality">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <input type="text" :value="getSoDisplay(4)" readonly class="w-12 px-1 py-1 border border-gray-400 text-xs text-center bg-gray-50">
                                        <button @click="emit('openPaperQualityModal', 4)" class="px-1 py-1 bg-blue-500 border border-blue-600 text-xs hover:bg-blue-600 text-white" title="Select Paper Quality">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
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

                        <!-- Row 4: WO -->
                        <div class="flex items-center mb-2">
                            <div class="flex items-center p-2 rounded flex-1">
                                <div class="flex items-center justify-between w-full">
                                    <div class="flex items-center space-x-1">
                                        <label class="text-xs font-bold w-16">WO:</label>
                                        <input type="text" :value="getWoDisplay(0)" readonly class="w-12 px-1 py-1 border border-gray-400 text-xs text-center bg-gray-50">
                                        <button @click="emit('openWoPaperQualityModal', 0)" class="px-1 py-1 bg-blue-500 border border-blue-600 text-xs hover:bg-blue-600 text-white" title="Select Paper Quality">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <input type="text" :value="getWoDisplay(1)" readonly class="w-12 px-1 py-1 border border-gray-400 text-xs text-center bg-gray-50">
                                        <button @click="emit('openWoPaperQualityModal', 1)" class="px-1 py-1 bg-blue-500 border border-blue-600 text-xs hover:bg-blue-600 text-white" title="Select Paper Quality">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <input type="text" :value="getWoDisplay(2)" readonly class="w-12 px-1 py-1 border border-gray-400 text-xs text-center bg-gray-50">
                                        <button @click="emit('openWoPaperQualityModal', 2)" class="px-1 py-1 bg-blue-500 border border-blue-600 text-xs hover:bg-blue-600 text-white" title="Select Paper Quality">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <input type="text" :value="getWoDisplay(3)" readonly class="w-16 px-1 py-1 border border-gray-400 text-xs text-center bg-gray-50">
                                        <button @click="emit('openWoPaperQualityModal', 3)" class="px-1 py-1 bg-blue-500 border border-blue-600 text-xs hover:bg-blue-600 text-white" title="Select Paper Quality">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <input type="text" :value="getWoDisplay(4)" readonly class="w-12 px-1 py-1 border border-gray-400 text-xs text-center bg-gray-50">
                                        <button @click="emit('openWoPaperQualityModal', 4)" class="px-1 py-1 bg-blue-500 border border-blue-600 text-xs hover:bg-blue-600 text-white" title="Select Paper Quality">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
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
                                    <input type="text" v-model="idL" class="w-16 px-2 py-1 border border-gray-400 text-xs">
                                    <span class="text-xs ml-1">L</span>
                                    <input type="text" v-model="idW" class="w-16 px-2 py-1 border border-gray-400 text-xs ml-2">
                                    <span class="text-xs ml-1">W</span>
                                    <input type="text" v-model="idH" class="w-16 px-2 py-1 border border-gray-400 text-xs ml-2">
                                    <span class="text-xs ml-1">H</span>
                                    <span class="ml-2 px-2 py-1 bg-red-200 border border-red-400 text-xs">t2e</span>
                                </div>
                                <div class="flex items-center">
                                    <label class="text-xs font-medium w-16">Pcs/Set:</label>
                                    <input type="text" v-model="pcsPerSet" class="w-16 px-2 py-1 border border-gray-400 text-xs">
                                </div>
                                <div class="flex items-center">
                                    <label class="text-xs font-medium w-16">Crease:</label>
                                    <input type="text" v-model="creaseValue" class="w-16 px-2 py-1 border border-gray-400 text-xs">
                                </div>
                                <div class="flex items-center">
                                    <label class="text-xs font-medium w-16">Chem Coat:</label>
                                    <input type="text" :value="selectedChemicalCoat" readonly class="w-24 px-2 py-1 border border-gray-400 text-xs bg-gray-50">
                                    <button class="ml-1 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300" @click="openChemicalCoatModal" title="Select Chemical Coat">
                                        <i class="fas fa-search"></i>
                                    </button>
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
                                    <input type="text" v-model="edL" class="w-16 px-2 py-1 border border-gray-400 text-xs">
                                    <span class="text-xs ml-1">L</span>
                                    <input type="text" v-model="edW" class="w-16 px-2 py-1 border border-gray-400 text-xs ml-2">
                                    <span class="text-xs ml-1">W</span>
                                    <input type="text" v-model="edH" class="w-16 px-2 py-1 border border-gray-400 text-xs ml-2">
                                    <span class="text-xs ml-1">H</span>
                                    <span class="ml-2 px-2 py-1 bg-red-200 border border-red-400 text-xs">e2t</span>
                                </div>
                                <div class="flex items-center">
                                    <label class="text-xs font-medium w-16">Nest/Slot:</label>
                                    <input type="text" v-model="nestSlot" class="w-16 px-2 py-1 border border-gray-400 text-xs">
                                </div>
                                <div class="flex items-center">
                                    <label class="text-xs font-medium w-16">R/F Tape:</label>
                                    <input type="text" :value="selectedReinforcementTape" readonly class="w-28 px-2 py-1 border border-gray-400 text-xs bg-gray-50">
                                    <button class="ml-1 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300" @click="openReinforcementTapeModal" title="Select Reinforcement Tape">
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
                            <div class="flex items-center space-x-1">
                                <template v-for="i in 10" :key="'scoreL'+i">
                                    <input type="text" v-model.number="scoreL[i-1]" class="w-12 px-1 py-1 border border-gray-400 text-xs text-center">
                                    <span v-if="i < 10" class="text-xs text-gray-500 font-bold">+</span>
                                </template>
                            </div>
                            <span class="mx-2 text-xs font-bold">=</span>
                            <input type="text" :value="scoreLTotal" readonly class="w-16 px-2 py-1 border border-gray-400 text-xs text-right bg-gray-50">
                            <button class="ml-2 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300" @click="calculateScoreL">
                                <i class="fas fa-calculator"></i>
                            </button>
                            <div class="ml-auto flex items-center">
                                <label class="text-xs font-medium w-20">Sheet Length:</label>
                                <input type="text" v-model="sheetLength" class="w-16 px-2 py-1 border border-gray-400 text-xs">
                                <button class="ml-1 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300">
                                    <i class="fas fa-search"></i>
                                </button>
                                <span class="text-xs ml-1">mm</span>
                            </div>
                        </div>

                        <!-- Score W Row -->
                        <div class="flex items-center mb-2">
                            <label class="text-xs font-medium w-16">Score W:</label>
                            <div class="flex items-center space-x-1">
                                <template v-for="i in 10" :key="'scoreW'+i">
                                    <input type="text" v-model.number="scoreW[i-1]" class="w-12 px-1 py-1 border border-gray-400 text-xs text-center">
                                    <span v-if="i < 10" class="text-xs text-gray-500 font-bold">+</span>
                                </template>
                            </div>
                            <span class="mx-2 text-xs font-bold">=</span>
                            <input type="text" :value="scoreWTotal" readonly class="w-16 px-2 py-1 border border-gray-400 text-xs text-right bg-gray-50">
                            <button class="ml-2 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300" @click="calculateScoreW">
                                <i class="fas fa-calculator"></i>
                            </button>
                            <div class="ml-auto flex items-center">
                                <label class="text-xs font-medium w-20">Sheet Width:</label>
                                <input type="text" v-model="sheetWidth" class="w-16 px-2 py-1 border border-gray-400 text-xs">
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
                                <input type="text" :value="selectedPaperSize" readonly class="w-20 px-2 py-1 border border-gray-400 text-xs bg-gray-50">
                                <button class="ml-1 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300" @click="showPaperSizeModal = true" title="Select Paper Size">
                                    <i class="fas fa-search"></i>
                                </button>
                                <span class="text-xs ml-1">mm</span>
                            </div>
                            <div class="flex items-center">
                                <label class="text-xs font-medium w-16">Con. Out:</label>
                                <input type="text" v-model="conOut" class="w-16 px-2 py-1 border border-gray-400 text-xs">
                                <button class="ml-1 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <div class="flex items-center">
                                <label class="text-xs font-medium w-24">Conv. Out 1 x 2:</label>
                                <input type="text" v-model="convDuctX2A" class="w-12 px-2 py-1 border border-gray-400 text-xs text-center">
                                <span class="mx-1 text-xs font-bold">x</span>
                                <input type="text" v-model="convDuctX2B" class="w-12 px-2 py-1 border border-gray-400 text-xs text-center">
                            </div>
                            <div class="flex items-center">
                                <label class="text-xs font-medium w-20">Pcs-to-Joint:</label>
                                <input type="text" v-model="pcsToJoint" class="w-16 px-2 py-1 border border-gray-400 text-xs">
                            </div>
                            <div class="flex items-center">
                                <label class="text-xs font-medium w-12">S/Tool:</label>
                                <input type="text" :value="selectedScoringToolCode" readonly class="w-8 px-2 py-1 border border-gray-400 text-xs bg-gray-50">
                                <button class="ml-1 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300" @click="openScoringToolModal" title="Select Scoring Tool">
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
                                <div v-for="i in 7" :key="'color'+i" class="flex items-center space-x-1">
                                    <input type="text" :value="printColorCodes[i-1]" readonly class="w-12 px-1 py-1 border border-gray-400 text-xs text-center bg-gray-50">
                                    <button class="w-8 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300" @click="openColorModal(i-1)" title="Select Color">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Print Area Row -->
                        <div class="flex items-center mb-2">
                            <div class="flex items-center w-80">
                                <label class="text-xs font-medium w-20">Print Area(%):</label>
                                <div class="flex space-x-1">
                                    <input type="text" v-model="colorAreaPercents[i-1]" class="w-12 px-1 py-1 border border-gray-400 text-xs text-center" v-for="i in 7" :key="'area'+i">
                                </div>
                            </div>
                            <div class="w-64"></div>
                            <div class="flex items-center w-64">
                                <label class="text-xs font-medium w-24">Prt Block#:</label>
                                <input type="text" v-model="pitBlockNo" class="w-24 px-2 py-1 border border-gray-400 text-xs">
                            </div>
                        </div>

                        <!-- D/Cut Sheet Row -->
                        <div class="flex items-center mb-2 space-x-4">
                            <div class="flex items-center w-80">
                                <label class="text-xs font-medium w-20">D/Cut Sheet:</label>
                                <input type="text" v-model="dcutSheetL" class="w-16 px-2 py-1 border border-gray-400 text-xs">
                                <span class="text-xs ml-1">L</span>
                                <input type="text" v-model="dcutSheetW" class="w-16 px-2 py-1 border border-gray-400 text-xs ml-2">
                                <span class="text-xs ml-1">W</span>
                                <button class="ml-1 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <div class="flex items-center w-64">
                                <label class="text-xs font-medium w-24">D/Cut Block#:</label>
                                <input type="text" v-model="dcutBlockNo" class="w-24 px-2 py-1 border border-gray-400 text-xs">
                            </div>
                            <div class="flex items-center">
                                <label class="text-xs font-medium w-16">Glueing:</label>
                                <input type="text" :value="selectedGlueingCode" readonly class="w-20 px-2 py-1 border border-gray-400 text-xs bg-gray-50">
                                <button class="ml-1 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300" @click="openGlueingModal" title="Select Glueing Material">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <div class="ml-auto flex items-center space-x-2">
                                <div class="flex items-center">
                                    <input type="checkbox" id="handHole" v-model="handHole" class="mr-1">
                                    <label for="handHole" class="text-xs">Hand Hole:</label>
                                </div>
                            </div>
                        </div>

                        <!-- D/Cut Mould Row -->
                        <div class="flex items-center mb-2 space-x-4">
                            <div class="flex items-center w-80">
                                <label class="text-xs font-medium w-20">D/Cut Mould:</label>
                                <input type="text" v-model="dcutMouldL" class="w-16 px-2 py-1 border border-gray-400 text-xs">
                                <span class="text-xs ml-1">L</span>
                                <input type="text" v-model="dcutMouldW" class="w-16 px-2 py-1 border border-gray-400 text-xs ml-2">
                                <span class="text-xs ml-1">W</span>
                                <button class="ml-1 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <div class="flex items-center w-64">
                                <label class="text-xs font-medium w-24">Stitch Wire Pcs:</label>
                                <input type="text" :value="selectedStitchWireCode" readonly class="w-16 px-2 py-1 border border-gray-400 text-xs bg-gray-50">
                                <button class="ml-1 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300" @click="openStitchWireModal" title="Select Stitch Wire">
                                    <i class="fas fa-search"></i>
                                </button>
                                <input type="text" v-model="stitchWirePieces" class="ml-2 w-16 px-2 py-1 border border-gray-400 text-xs text-right" placeholder="pcs">
                                <button class="ml-1 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300">
                                    <i class="fas fa-calculator"></i>
                                </button>
                            </div>
                            <div class="flex items-center">
                                <label class="text-xs font-medium w-16">Wrapping:</label>
                                <input type="text" :value="selectedWrappingCode" readonly class="w-20 px-2 py-1 border border-gray-400 text-xs bg-gray-50">
                                <button class="ml-1 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300" @click="openWrappingModal" title="Select Wrapping Material">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <div class="ml-auto flex items-center space-x-2">
                                <div class="flex items-center">
                                    <input type="checkbox" id="rotaryDCut" v-model="rotaryDCut" class="mr-1">
                                    <label for="rotaryDCut" class="text-xs">Rotary D/Cut:</label>
                                </div>
                            </div>
                        </div>

                        <!-- Finishing Row -->
                        <div class="flex items-center mb-2 space-x-4">
                            <div class="flex items-center w-80">
                                <label class="text-xs font-medium w-16">Finishing:</label>
                                <input type="text" :value="selectedFinishingCode" readonly class="w-32 px-2 py-1 border border-gray-400 text-xs bg-gray-50">
                                <button class="ml-1 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300" @click="openFinishingModal" title="Select Finishing">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <div class="flex items-center w-64">
                                <label class="text-xs font-medium w-24">Bundle String Qty:</label>
                                <input type="text" :value="selectedBundlingStringCode" readonly class="w-16 px-2 py-1 border border-gray-400 text-xs bg-gray-50">
                                <button class="ml-1 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300" @click="openBundlingStringModal" title="Select Bundling String">
                                    <i class="fas fa-search"></i>
                                </button>
                                <input type="text" v-model="bundlingStringQty" class="ml-2 w-16 px-2 py-1 border border-gray-400 text-xs text-right" placeholder="qty">
                                <button class="ml-1 px-2 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300">
                                    <i class="fas fa-calculator"></i>
                                </button>
                            </div>
                            <div class="flex items-center">
                                <label class="text-xs font-medium w-16">Bdl/Pallet:</label>
                                <input type="text" v-model="bdlPerPallet" class="w-12 px-2 py-1 border border-gray-400 text-xs">
                            </div>
                            <div class="ml-auto flex items-center space-x-2">
                                <div class="flex items-center">
                                    <input type="checkbox" id="fullBlockPrint" v-model="fullBlockPrint" class="mr-1">
                                    <label for="fullBlockPrint" class="text-xs">Full Block Print:</label>
                                </div>
                            </div>
                        </div>

                        <!-- Item Remark Row -->
                        <div class="flex items-center">
                            <div class="flex items-center w-80">
                                <label class="text-xs font-medium w-20">Item Remark:</label>
                                <input type="text" v-model="itemRemark" class="w-40 px-2 py-1 border border-gray-400 text-xs">
                            </div>
                            <div class="w-64"></div>
                            <div class="flex items-center w-64">
                                <label class="text-xs font-medium w-24">Peel Off%:</label>
                                <input type="text" v-model="peelOffPercent" class="w-24 px-2 py-1 border border-gray-400 text-xs text-right">
                            </div>
                            <div class="ml-auto flex items-center space-x-2">
                                <button class="px-3 py-1 bg-gray-200 border border-gray-400 text-xs font-bold cursor-default" disabled>MSP</button>
                                <button class="px-3 py-1 bg-gray-200 border border-gray-400 text-xs hover:bg-gray-300" @click="openSubMaterialModal">Sub-Material</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end gap-2 p-3 border-t border-gray-200 bg-gray-100 rounded-b-lg flex-shrink-0">
                <button
                    type="button"
                    @click="$emit('saveMasterCard', buildPdSetupPayload())"
                    class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded">
                    Save MasterCard
                </button>
                <button
                    type="button"
                    @click="$emit('closeSetupPdModal')"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- Product Design Modal -->
    <ProductDesignModal
        :show="showProductDesignModal"
        :designs="productDesigns"
        :products="[]"
        :doubleClickAction="'select'"
        @close="showProductDesignModal = false"
        @select="onProductDesignSelected"
    />

    <!-- Paper Flute Modal -->
    <PaperFluteModal
        :show="showPaperFluteModal"
        :flutes="paperFlutes"
        @close="showPaperFluteModal = false"
        @select="onPaperFluteSelected"
    />

    <!-- Chemical Coat Modal -->
    <ChemicalCoatModal
        :show="showChemicalCoatModal"
        :items="chemicalCoatRows"
        :loading="chemicalCoatLoading"
        @close="showChemicalCoatModal = false"
        @select="(coat) => { selectedChemicalCoat = coat?.code || ''; showChemicalCoatModal = false; }"
    />

    <!-- Reinforcement Tape Modal -->
    <ReinforcementTapeModal
        :show="showReinforcementTapeModal"
        :rows="reinforcementTapeRows"
        @close="showReinforcementTapeModal = false"
        @select="(row) => { selectedReinforcementTape = row?.code || ''; showReinforcementTapeModal = false; }"
    />

    <!-- Paper Size Modal -->
    <PaperSizeModal
        :show="showPaperSizeModal"
        :paperSizes="paperSizeRows"
        @close="showPaperSizeModal = false"
        @select="(size) => { selectedPaperSize = size?.millimeter || ''; showPaperSizeModal = false; }"
    />
    <ColorModal
        :show="showColorModal"
        :colors="colors"
        :color-groups="colorGroups"
        @close="showColorModal = false"
        @select="onColorSelected"
    />
    <FinishingModal
        :show="showFinishingModal"
        :finishings="finishings"
        @close="showFinishingModal = false"
        @select="onFinishingSelected"
    />
    <ScoringToolModal
        :show="showScoringToolModal"
        :scoring-tools="scoringTools"
        @close="showScoringToolModal = false"
        @select="onScoringToolSelected"
    />
    <StitchWireModal
        :show="showStitchWireModal"
        @close="showStitchWireModal = false"
        @select="onStitchWireSelected"
    />
    <BundlingStringModal
        :show="showBundlingStringModal"
        :items="bundlingStringItems"
        @close="showBundlingStringModal = false"
        @select="onBundlingStringSelected"
    />
    <GlueingMaterialModal
        :show="showGlueingModal"
        :items="glueingItems"
        @close="showGlueingModal = false"
        @select="onGlueingSelected"
    />
    <MoreDescriptionModal
        :show="showMoreDescriptionModal"
        :value="moreDescriptions"
        @update:value="(v) => { moreDescriptions = v }"
        @close="showMoreDescriptionModal = false"
    />
    <WrappingMaterialModal
        :show="showWrappingModal"
        :items="wrappingItems"
        @close="showWrappingModal = false"
        @select="onWrappingSelected"
    />
    <SubMaterialModal
        :show="showSubMaterialModal"
        :value="subMaterials"
        @update:value="(v) => { subMaterials = v }"
        @close="showSubMaterialModal = false"
    />
</template>

<script setup>
// Convert stored objects (like paper quality object) into display strings
const normalizeDisplay = (vals) => {
    const toStringVal = (v) => {
        if (v && typeof v === 'object') {
            return v.paper_quality || v.paper_name || v.name || v.code || '';
        }
        return v ?? '';
    };
    const arr = Array.isArray(vals) ? vals : [];
    return [0,1,2,3,4].map(i => toStringVal(arr[i]));
};

// Helper: normalize any array-like into fixed 5-length string array
const normalizeFive = (arr) => {
    const base = Array.isArray(arr) ? arr : [];
    const asStrings = base.map(v => (v ?? '') + '');
    return asStrings.concat(['', '', '', '', '']).slice(0, 5);
};

// Display helpers for SO/WO values shown in the UI: prefer component state, fallback to props
const displaySoValues = computed(() => {
    try {
        const idx = selectedComponentIndex.value ?? 0;
        const vals = componentForms.value[idx]?.soValues ?? props.soValues;
        return normalizeFive(vals);
    } catch (e) {
        return ['', '', '', '', ''];
    }
});

const displayWoValues = computed(() => {
    try {
        const idx = selectedComponentIndex.value ?? 0;
        const vals = componentForms.value[idx]?.woValues ?? props.woValues;
        return normalizeFive(vals);
    } catch (e) {
        return ['', '', '', '', ''];
    }
});

// Helper getters for template bindings to ensure immediate reflection from local state
const getSoDisplay = (index) => {
    try {
        const idx = selectedComponentIndex.value ?? 0;
        const arr = componentForms.value[idx]?.soValues ?? props.soValues ?? [];
        return ((Array.isArray(arr) ? arr[index] : '') ?? '') + '';
    } catch (e) {
        return '';
    }
};

const getWoDisplay = (index) => {
    try {
        const idx = selectedComponentIndex.value ?? 0;
        const arr = componentForms.value[idx]?.woValues ?? props.woValues ?? [];
        return ((Array.isArray(arr) ? arr[index] : '') ?? '') + '';
    } catch (e) {
        return '';
    }
};
import { ref, computed, watch, onMounted } from 'vue';
// defineProps and defineEmits are compiler macros and don't need to be imported
import ProductDesignModal from '@/Components/product-design-modal.vue';
import PaperFluteModal from '@/Components/paper-flute-selector-modal.vue';
import ScoringToolModal from '@/Components/scoring-tool-modal.vue';
import ColorModal from '@/Components/color-modal.vue';
import FinishingModal from '@/Components/finishing-modal.vue';
import StitchWireModal from '@/Components/stitch-wire-modal.vue';
import BundlingStringModal from '@/Components/bundling-string-modal.vue';
import GlueingMaterialModal from '@/Components/glueing-material-modal.vue';
import SubMaterialModal from '@/Components/sub-material-modal.vue';
import WrappingMaterialModal from '@/Components/wrapping-material-modal.vue';
import MoreDescriptionModal from '@/Components/more-description-modal.vue';
import ChemicalCoatModal from '@/Components/chemical-coat-modal.vue';
import ReinforcementTapeModal from '@/Components/reinforcement-tape-modal.vue';
import PaperSizeModal from '@/Components/paper-size-modal.vue';

// Product Design Modal
const showProductDesignModal = ref(false);
const selectedProductDesign = ref('');

// Paper Flute Modal
const showPaperFluteModal = ref(false);
const selectedPaperFlute = ref('');

// Chemical Coat Modal
const showChemicalCoatModal = ref(false);
const selectedChemicalCoat = ref('');
const chemicalCoatRows = ref([]);
const chemicalCoatLoading = ref(false);

// Fetch chemical coats from API
const fetchChemicalCoats = async () => {
    try {
        chemicalCoatLoading.value = true;
        const response = await fetch('/api/chemical-coats', {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (response.ok) {
            const data = await response.json();
            chemicalCoatRows.value = Array.isArray(data) ? data : (Array.isArray(data?.data) ? data.data : []);
        } else {
            chemicalCoatRows.value = [];
        }
    } catch (e) {
        console.error('Error fetching chemical coats:', e);
        chemicalCoatRows.value = [];
    } finally {
        chemicalCoatLoading.value = false;
    }
};

// Open chemical coat modal and fetch data
const openChemicalCoatModal = async () => {
    if (chemicalCoatRows.value.length === 0) {
        await fetchChemicalCoats();
    }
    showChemicalCoatModal.value = true;
};

// Helper functions removed - now displaying codes directly

// Reinforcement Tape Modal
const showReinforcementTapeModal = ref(false);
const selectedReinforcementTape = ref('');
const reinforcementTapeRows = ref([]);

// Fetch reinforcement tapes from API
const fetchReinforcementTapes = async () => {
    try {
        const response = await fetch('/api/reinforcement-tapes', {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (response.ok) {
            const data = await response.json();
            reinforcementTapeRows.value = Array.isArray(data) ? data : (Array.isArray(data?.data) ? data.data : []);
        } else {
            reinforcementTapeRows.value = [];
        }
    } catch (e) {
        console.error('Error fetching reinforcement tapes:', e);
        reinforcementTapeRows.value = [];
    }
};

// Open reinforcement tape modal and fetch data
const openReinforcementTapeModal = async () => {
    if (reinforcementTapeRows.value.length === 0) {
        await fetchReinforcementTapes();
    }
    showReinforcementTapeModal.value = true;
};

// Paper Size Modal
const showPaperSizeModal = ref(false);
const selectedPaperSize = ref('');
const paperSizeRows = ref([
    { id: 1, millimeter: '210.00', inches: '8.27', description: 'A4 Paper Size' },
    { id: 2, millimeter: '297.00', inches: '11.69', description: 'A4 Paper Size' },
    { id: 3, millimeter: '148.00', inches: '5.83', description: 'A5 Paper Size' },
    { id: 4, millimeter: '105.00', inches: '4.13', description: 'A6 Paper Size' },
    { id: 5, millimeter: '74.00', inches: '2.91', description: 'A7 Paper Size' }
]);

// Scoring Tool Modal
const showScoringToolModal = ref(false);
const selectedScoringToolCode = ref('');
const scoringTools = ref([]);

const openScoringToolModal = async () => {
    if (scoringTools.value.length === 0) {
        try {
            const response = await fetch('/api/scoring-tools', {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (response.ok) {
                const data = await response.json();
                scoringTools.value = Array.isArray(data) ? data : (Array.isArray(data?.data) ? data.data : []);
            } else {
                scoringTools.value = [];
            }
        } catch (e) {
            scoringTools.value = [];
        }
    }
    showScoringToolModal.value = true;
};

const onScoringToolSelected = (tool) => {
    selectedScoringToolCode.value = tool?.code || '';
    showScoringToolModal.value = false;
};

// Color Modal for Print Colors
const showColorModal = ref(false);
const colors = ref([]);
const colorGroups = ref([]);
const activePrintColorIndex = ref(0);
const printColorCodes = ref(['', '', '', '', '', '', '']);

const openColorModal = async (index) => {
    activePrintColorIndex.value = index;
    if (colors.value.length === 0) {
        try {
            const res = await fetch('/api/colors', { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
            if (res.ok) {
                const data = await res.json();
                // API returns an object: { colors: [...], color_groups: [...] }
                colors.value = Array.isArray(data?.colors) ? data.colors : [];
            }
        } catch (e) {}
    }
    if (colorGroups.value.length === 0) {
        try {
            // Reuse the same endpoint which already includes color_groups
            const res = await fetch('/api/colors', { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
            if (res.ok) {
                const data = await res.json();
                colorGroups.value = Array.isArray(data?.color_groups) ? data.color_groups : [];
            }
        } catch (e) {}
    }
    showColorModal.value = true;
};

const onColorSelected = (color) => {
    if (color && typeof activePrintColorIndex.value === 'number') {
        const next = [...printColorCodes.value];
        next[activePrintColorIndex.value] = color.color_id || '';
        printColorCodes.value = next;
    }
    showColorModal.value = false;
};

// Score L/W state and calculators
const scoreL = ref(Array(10).fill(''));
const scoreW = ref(Array(10).fill(''));
const scoreLTotal = ref('');
const scoreWTotal = ref('');

const calculateScore = (arr) => {
    return arr
        .map(v => parseFloat(String(v).toString().replace(/,/g, '.')))
        .filter(v => !isNaN(v))
        .reduce((sum, v) => sum + v, 0)
        .toFixed(2);
};

const calculateScoreL = () => {
    scoreLTotal.value = calculateScore(scoreL.value);
};

const calculateScoreW = () => {
    scoreWTotal.value = calculateScore(scoreW.value);
};

// Finishing Modal
const showFinishingModal = ref(false);
const finishings = ref([]);
const selectedFinishingCode = ref('');

const openFinishingModal = async () => {
    if (finishings.value.length === 0) {
        try {
            const res = await fetch('/api/finishings', { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
            if (res.ok) {
                const data = await res.json();
                finishings.value = Array.isArray(data) ? data : (Array.isArray(data?.data) ? data.data : []);
            }
        } catch (e) {}
    }
    showFinishingModal.value = true;
};

const onFinishingSelected = (finishing) => {
    selectedFinishingCode.value = finishing?.code || '';
    showFinishingModal.value = false;
};

// Stitch Wire Modal
const showStitchWireModal = ref(false);
const selectedStitchWireCode = ref('');
const stitchWirePieces = ref('');

const openStitchWireModal = async () => {
    showStitchWireModal.value = true;
};

const onStitchWireSelected = (item) => {
    selectedStitchWireCode.value = item?.code || '';
    showStitchWireModal.value = false;
};

// Bundling String Modal
const showBundlingStringModal = ref(false);
const selectedBundlingStringCode = ref('');
const bundlingStringItems = ref([]);
const bundlingStringQty = ref('');

// Fetch bundling strings from API
const fetchBundlingStrings = async () => {
    try {
        const response = await fetch('/api/bundling-strings', {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (response.ok) {
            const data = await response.json();
            bundlingStringItems.value = Array.isArray(data) ? data : (Array.isArray(data?.data) ? data.data : []);
        } else {
            bundlingStringItems.value = [];
        }
    } catch (e) {
        console.error('Error fetching bundling strings:', e);
        bundlingStringItems.value = [];
    }
};

const openBundlingStringModal = async () => {
    if (bundlingStringItems.value.length === 0) {
        await fetchBundlingStrings();
    }
    showBundlingStringModal.value = true;
};

const onBundlingStringSelected = (item) => {
    selectedBundlingStringCode.value = item?.code || '';
    showBundlingStringModal.value = false;
};

// Sub-Material Modal
const showSubMaterialModal = ref(false);
let subMaterials = ref([]);
const openSubMaterialModal = () => { showSubMaterialModal.value = true; };

// Glueing Material Modal
const showGlueingModal = ref(false);
const selectedGlueingCode = ref('');
const glueingItems = ref([]);

// Fetch glueing materials from API
const fetchGlueingMaterials = async () => {
    try {
        const response = await fetch('/api/glueing-materials', {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (response.ok) {
            const data = await response.json();
            glueingItems.value = Array.isArray(data) ? data : (Array.isArray(data?.data) ? data.data : []);
        } else {
            glueingItems.value = [];
        }
    } catch (e) {
        console.error('Error fetching glueing materials:', e);
        glueingItems.value = [];
    }
};

const openGlueingModal = async () => {
    if (glueingItems.value.length === 0) {
        await fetchGlueingMaterials();
    }
    showGlueingModal.value = true;
};

const onGlueingSelected = (item) => {
    selectedGlueingCode.value = item?.code || '';
    showGlueingModal.value = false;
};

// Wrapping Material Modal
const showWrappingModal = ref(false);
const selectedWrappingCode = ref('');
const wrappingItems = ref([]);

// Fetch wrapping materials from API
const fetchWrappingMaterials = async () => {
    try {
        const response = await fetch('/api/wrapping-materials', {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (response.ok) {
            const data = await response.json();
            wrappingItems.value = Array.isArray(data) ? data : (Array.isArray(data?.data) ? data.data : []);
        } else {
            wrappingItems.value = [];
        }
    } catch (e) {
        console.error('Error fetching wrapping materials:', e);
        wrappingItems.value = [];
    }
};

const openWrappingModal = async () => {
    if (wrappingItems.value.length === 0) {
        await fetchWrappingMaterials();
    }
    showWrappingModal.value = true;
};

const onWrappingSelected = (item) => {
    selectedWrappingCode.value = item?.code || '';
    showWrappingModal.value = false;
};

// More Description Modal
const showMoreDescriptionModal = ref(false);
let moreDescriptions = ref([]);

// Additional PD state bindings
const partNo = ref('');
const idL = ref('');
const idW = ref('');
const idH = ref('');
const pcsPerSet = ref('');
const creaseValue = ref('');
const edL = ref('');
const edW = ref('');
const edH = ref('');
const nestSlot = ref('');
const sheetLength = ref('');
const sheetWidth = ref('');
const conOut = ref('');
const convDuctX2 = ref('');
const pcsToJoint = ref('');
const dcutSheetL = ref('');
const dcutSheetW = ref('');
const dcutMouldL = ref('');
const dcutMouldW = ref('');
const dcutBlockNo = ref('');
const pitBlockNo = ref('');
const bdlPerPallet = ref('');
const peelOffPercent = ref('');
const itemRemark = ref('');
const handHole = ref(false);
const rotaryDCut = ref(false);
const fullBlockPrint = ref(false);
// Conv. Out 1x2 split inputs
const convDuctX2A = ref('');
const convDuctX2B = ref('');

// Computed properties for automatic M2 calculations
const mcGrossM2PerPcs = computed(() => {
    // Gross M2 Pcs = ((Sheet Length * P/Size * joint) / (con out * conv out 1 * conv out 2)) / 1000000
    const sheetLen = parseFloat(sheetLength.value) || 0;
    const paperSize = parseFloat(selectedPaperSize.value) || 0;
    const joint = parseFloat(pcsToJoint.value) || 1; // Default to 1 to avoid division by zero
    const conOutVal = parseFloat(conOut.value) || 1;
    const convOut1 = parseFloat(convDuctX2A.value) || 1;
    const convOut2 = parseFloat(convDuctX2B.value) || 1;
    
    if (sheetLen === 0 || paperSize === 0) return 0;
    
    const numerator = sheetLen * paperSize * joint;
    const denominator = conOutVal * convOut1 * convOut2;
    
    if (denominator === 0) return 0;
    
    return (numerator / denominator) / 1000000;
});

const mcNetM2PerPcs = computed(() => {
    // Net M2 Pcs = (Sheet Length * Sheet Width) / 1000000 / (conv out 1 * conv out 2)
    const sheetLen = parseFloat(sheetLength.value) || 0;
    const sheetWid = parseFloat(sheetWidth.value) || 0;
    const convOut1 = parseFloat(convDuctX2A.value) || 1;
    const convOut2 = parseFloat(convDuctX2B.value) || 1;
    
    if (sheetLen === 0 || sheetWid === 0) return 0;
    
    const numerator = sheetLen * sheetWid;
    const denominator = convOut1 * convOut2;
    
    if (denominator === 0) return 0;
    
    return (numerator / 1000000) / denominator;
});

const handleSortOptionChange = (event) => {
    const newSortOption = event.target.value;
    emit('updateSortOption', newSortOption);
    emit('fetchMcsData');
};

const onProductDesignSelected = (design) => {
    selectedProductDesign.value = design.pd_code;
    showProductDesignModal.value = false;
    emit('productDesignSelected', design);
};

const onPaperFluteSelected = (flute) => {
    selectedPaperFlute.value = flute.code;
    showPaperFluteModal.value = false;
    emit('paperFluteSelected', flute);
};

const props = defineProps({
    showErrorModal: Boolean,
    showSetupMcModal: Boolean,
    showSetupPdModal: Boolean,
    showMcsTableModal: Boolean,
    formData: Object,
    mcComponents: Array,
    mcLoaded: {
        type: Object,
        default: null
    },
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
    mcsLastPage: Number,
    productDesigns: {
        type: Array,
        default: () => []
    },
    paperFlutes: {
        type: Array,
        default: () => []
    },
    soValues: {
        type: Array,
        default: () => ['', '', '', '', '']
    },
    woValues: {
        type: Array,
        default: () => ['', '', '', '', '']
    }
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
    'updateSortOption',
    'productDesignSelected',
    'paperFluteSelected',
    'openPaperQualityModal',
    'openWoPaperQualityModal',
    'saveMasterCard',
    'requestClearSoWo',
    'requestSetSoWo'
]);

// 7-slot array for print color area percent inputs (must be declared before use)
const colorAreaPercents = ref(['', '', '', '', '', '', '']);

// Clear all PD form fields
const clearPdFields = () => {
    partNo.value = '';
    selectedProductDesign.value = '';
    selectedPaperFlute.value = '';
    selectedChemicalCoat.value = '';
    selectedReinforcementTape.value = '';
    selectedPaperSize.value = '';
    selectedScoringToolCode.value = '';
    printColorCodes.value = ['', '', '', '', '', '', ''];
    // Color area percents (7 slots)
    colorAreaPercents.value = ['','', '', '', '', '', ''];
    scoreL.value = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
    scoreW.value = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
    sheetLength.value = '';
    sheetWidth.value = '';
    conOut.value = '';
    convDuctX2.value = '';
    convDuctX2A.value = '';
    convDuctX2B.value = '';
    pcsToJoint.value = '';
    idL.value = '';
    idW.value = '';
    idH.value = '';
    edL.value = '';
    edW.value = '';
    edH.value = '';
    pcsPerSet.value = '';
    creaseValue.value = '';
    nestSlot.value = '';
    dcutSheetL.value = '';
    dcutSheetW.value = '';
    dcutMouldL.value = '';
    dcutMouldW.value = '';
    dcutBlockNo.value = '';
    pitBlockNo.value = '';
    stitchWirePieces.value = '';
    bdlPerPallet.value = '';
    peelOffPercent.value = '';
    itemRemark.value = '';
    handHole.value = false;
    rotaryDCut.value = false;
    fullBlockPrint.value = false;
    selectedFinishingCode.value = '';
    selectedStitchWireCode.value = '';
    stitchWirePieces.value = '';
    selectedBundlingStringCode.value = '';
    bundlingStringQty.value = '';
    selectedGlueingCode.value = '';
    selectedWrappingCode.value = '';
    moreDescriptions.value = [];
    subMaterials.value = [];
};

// Display helper: trim trailing zeros in decimals; keep fraction if non-zero
const formatTrimZeros = (value) => {
    if (value === null || value === undefined) return '';
    const s = String(value).replace(/,/g, '.').trim();
    if (s === '') return '';
    if (!s.includes('.')) return s; // no decimal part
    // remove trailing zeros in fractional part; remove dot if becomes integer
    const trimmed = s.replace(/(\.\d*?[1-9])0+$/, '$1').replace(/\.0+$/, '');
    return trimmed;
};

// Helper to coerce Yes/No strings into booleans
const toBool = (v) => {
    if (v === true || v === false) return !!v;
    if (v === null || v === undefined) return false;
    const s = String(v).toLowerCase();
    return s === 'y' || s === 'yes' || s === 'true' || s === '1';
};

// When mcLoaded is provided, hydrate PD form fields
const hydratePdFromLoaded = () => {
    const loaded = props.mcLoaded || {};
    const pd = loaded?.pd_setup || null;

    hydratePdFromObject(pd, loaded);
};

const hydratePdFromObject = (pd, loaded) => {
    if (!pd) {
        // Fallback: hydrate directly from MC table columns
        // Clear first
        clearPdFields();
        // Map core PD fields
        partNo.value = loaded.PART_NO || loaded.part_no || '';
        selectedProductDesign.value = loaded.P_DESIGN || loaded.p_design || '';
        pcsPerSet.value = formatTrimZeros(loaded.PCS_SET ?? loaded.pcs_set ?? '');
        // Paper flute / scoring / coat / tape
        selectedPaperFlute.value = loaded.FLUTE || '';
        selectedScoringToolCode.value = loaded.S_TOOL || '';
        selectedChemicalCoat.value = loaded.COAT || '';
        selectedReinforcementTape.value = loaded.TAPE || '';
        // Sheet dims
        sheetLength.value = formatTrimZeros(loaded.SHEET_LENGTH ?? '');
        sheetWidth.value = formatTrimZeros(loaded.SHEET_WIDTH ?? '');
        // Paper size (store as string for UI)
        selectedPaperSize.value = formatTrimZeros(loaded.PAPER_SIZE ?? '');
        // Score arrays SL1..SL8 and SW1..SW8
        scoreL.value = [loaded.SL1, loaded.SL2, loaded.SL3, loaded.SL4, loaded.SL5, loaded.SL6, loaded.SL7, loaded.SL8]
            .map(v => formatTrimZeros(v ?? ''));
        scoreW.value = [loaded.SW1, loaded.SW2, loaded.SW3, loaded.SW4, loaded.SW5, loaded.SW6, loaded.SW7, loaded.SW8]
            .map(v => formatTrimZeros(v ?? ''));
        // ID / ED
        idL.value = formatTrimZeros(loaded.INT_LENGTH ?? '');
        idW.value = formatTrimZeros(loaded.INT_WIDTH ?? '');
        idH.value = formatTrimZeros(loaded.INT_HEIGHT ?? '');
        edL.value = formatTrimZeros(loaded.EXT_LENGTH ?? '');
        edW.value = formatTrimZeros(loaded.EXT_WIDTH ?? '');
        edH.value = formatTrimZeros(loaded.EXT_HEIGHT ?? '');
        // Diecut
        dcutSheetL.value = formatTrimZeros(loaded.DC_SHT_L ?? '');
        dcutSheetW.value = formatTrimZeros(loaded.DC_SHT_W ?? '');
        dcutMouldL.value = formatTrimZeros(loaded.DC_MOULD_L ?? '');
        dcutMouldW.value = formatTrimZeros(loaded.DC_MOULD_W ?? '');
        // Conv. Out 1x2: map to SLIT_OUT and DIE_OUT as A x B
        convDuctX2A.value = formatTrimZeros(loaded.SLIT_OUT ?? '');
        convDuctX2B.value = formatTrimZeros(loaded.DIE_OUT ?? '');
        // Con. Out
        conOut.value = formatTrimZeros(loaded.CORR_OUT ?? '');
        // Pcs-to-Joint
        pcsToJoint.value = formatTrimZeros(loaded.JOIN_ ?? '');
        // Finishing/Glueing/Wrapping codes or Yes/No
        selectedFinishingCode.value = (loaded.FSH ?? '') + '';
        selectedGlueingCode.value = (loaded.GLUEING ?? '') + '';
        selectedWrappingCode.value = (loaded.WRAPPING ?? '') + '';
        // Item remark
        itemRemark.value = (loaded.ITEM_REMARK ?? '') + '';
        // Blocks
        dcutBlockNo.value = (loaded.DIECUT_MOULD_NO ?? '') + '';
        pitBlockNo.value = (loaded.PRINTING_BLOCK_NO ?? '') + '';
        // Stitch wire code and pieces
        selectedStitchWireCode.value = (loaded.SWIRE ?? '') + '';
        stitchWirePieces.value = formatTrimZeros(loaded.SWIRE_PCS ?? '');
        // Bundle string type
        selectedBundlingStringCode.value = (loaded.STRING_TYPE ?? '') + '';
        bundlingStringQty.value = formatTrimZeros(loaded.PCS_PER_BLD ?? '');
        // Packs and process flags
        bdlPerPallet.value = (loaded.BLD_PER_PLD ?? '') + '';
        peelOffPercent.value = formatTrimZeros(loaded.PEEL_OFF_PERCENT ?? '');
        handHole.value = toBool(loaded.HAND_HOLE);
        rotaryDCut.value = toBool(loaded.ROTARY_DC);
        fullBlockPrint.value = toBool(loaded.FB_PRINTING);
        // Colors
        const colors = [loaded.COLOR1, loaded.COLOR2, loaded.COLOR3, loaded.COLOR4, loaded.COLOR5, loaded.COLOR6, loaded.COLOR7]
            .map(v => (v ?? ''));
        printColorCodes.value = colors;
        // Color area percents (map to 7 inputs)
        colorAreaPercents.value = [
            loaded.COLOR1_AREA_PERCENT,
            loaded.COLOR2_AREA_PERCENT,
            loaded.COLOR3_AREA_PERCENT,
            loaded.COLOR4_AREA_PERCENT,
            loaded.COLOR5_AREA_PERCENT,
            loaded.COLOR6_AREA_PERCENT,
            loaded.COLOR7_AREA_PERCENT,
        ].map(v => formatTrimZeros(v ?? ''));
        // SO/WO root values from MC to selected component (Main)
        try {
            const cf0 = componentForms.value[0] || makeEmptyPdState();
            const soArr = [loaded.SO_PQ1, loaded.SO_PQ2, loaded.SO_PQ3, loaded.SO_PQ4, loaded.SO_PQ5].map(v => (v ?? '') + '');
            const woArr = [loaded.WO_PQ1, loaded.WO_PQ2, loaded.WO_PQ3, loaded.WO_PQ4, loaded.WO_PQ5].map(v => (v ?? '') + '');
            cf0.soValues = soArr;
            cf0.woValues = woArr;
            componentForms.value[0] = { ...makeEmptyPdState(), ...cf0 };
            // Reflect SO/WO to parent so global buttons display values
            emit('requestSetSoWo', {
                so: soArr,
                wo: woArr,
            });
        } catch (e) {}
        // Optional fields
        creaseValue.value = formatTrimZeros(loaded.CREASE ?? '');
        nestSlot.value = formatTrimZeros(loaded.NEST_SLOT ?? '');
        // Done
        return;
    }

    // Default behavior when pd_setup exists (with fallback to MC loaded fields if missing)
    partNo.value = pd.partNo || loaded.PART_NO || loaded.part_no || '';
    selectedProductDesign.value = pd.selectedProductDesign || loaded.P_DESIGN || loaded.p_design || '';
    selectedPaperFlute.value = pd.selectedPaperFlute || loaded.FLUTE || '';
    selectedChemicalCoat.value = pd.selectedChemicalCoat || loaded.COAT || '';
    selectedReinforcementTape.value = pd.selectedReinforcementTape || loaded.TAPE || '';
    selectedPaperSize.value = (pd.selectedPaperSize || (loaded.PAPER_SIZE ?? '')) + '';
    selectedScoringToolCode.value = pd.selectedScoringToolCode || loaded.S_TOOL || '';
    printColorCodes.value = Array.isArray(pd.printColorCodes) ? pd.printColorCodes : printColorCodes.value;
    colorAreaPercents.value = Array.isArray(pd.colorAreaPercents) ? pd.colorAreaPercents : colorAreaPercents.value;
    scoreL.value = Array.isArray(pd.scoreL) ? pd.scoreL : scoreL.value;
    scoreW.value = Array.isArray(pd.scoreW) ? pd.scoreW : scoreW.value;
    sheetLength.value = pd.sheetLength || (loaded.SHEET_LENGTH ?? '') + '';
    sheetWidth.value = pd.sheetWidth || (loaded.SHEET_WIDTH ?? '') + '';
    conOut.value = pd.conOut || (loaded.CORR_OUT ?? '') + '';
    convDuctX2.value = pd.convDuctX2 || '';
    // If pd_setup provides explicit slit/die parts, prefer them
    convDuctX2A.value = (pd.slitOut ?? convDuctX2A.value ?? '') + '';
    convDuctX2B.value = (pd.dieOut ?? convDuctX2B.value ?? '') + '';
    pcsToJoint.value = pd.pcsToJoint || (loaded.JOIN_ ?? '') + '';
    idL.value = (pd.id?.L || (loaded.INT_LENGTH ?? '')) + '';
    idW.value = (pd.id?.W || (loaded.INT_WIDTH ?? '')) + '';
    idH.value = (pd.id?.H || (loaded.INT_HEIGHT ?? '')) + '';
    edL.value = (pd.ed?.L || (loaded.EXT_LENGTH ?? '')) + '';
    edW.value = (pd.ed?.W || (loaded.EXT_WIDTH ?? '')) + '';
    edH.value = (pd.ed?.H || (loaded.EXT_HEIGHT ?? '')) + '';
    pcsPerSet.value = pd.pcsPerSet || '';
    creaseValue.value = pd.creaseValue || '';
    nestSlot.value = pd.nestSlot || '';
    dcutSheetL.value = pd.dcutSheet?.L || '';
    dcutSheetW.value = pd.dcutSheet?.W || '';
    dcutMouldL.value = pd.dcutMould?.L || '';
    dcutMouldW.value = pd.dcutMould?.W || '';
    dcutBlockNo.value = pd.dcutBlockNo || loaded.DIECUT_MOULD_NO || '';
    pitBlockNo.value = pd.pitBlockNo || loaded.PRINTING_BLOCK_NO || '';
    stitchWirePieces.value = formatTrimZeros(pd.stitchWirePieces || (loaded.SWIRE_PCS ?? ''));
    bdlPerPallet.value = pd.bdlPerPallet || '';
    peelOffPercent.value = formatTrimZeros(pd.peelOffPercent || '');
    itemRemark.value = pd.itemRemark || loaded.ITEM_REMARK || '';
    handHole.value = !!pd.handHole;
    rotaryDCut.value = !!pd.rotaryDCut;
    fullBlockPrint.value = !!pd.fullBlockPrint;
    selectedFinishingCode.value = pd.selectedFinishingCode || '';
    selectedStitchWireCode.value = pd.selectedStitchWireCode || loaded.SWIRE || '';
    stitchWirePieces.value = pd.stitchWirePieces || '';
    selectedBundlingStringCode.value = pd.selectedBundlingStringCode || loaded.STRING_TYPE || '';
    bundlingStringQty.value = formatTrimZeros(pd.bundlingStringQty || (loaded.PCS_PER_BLD ?? ''));
    selectedGlueingCode.value = pd.selectedGlueingCode || '';
    selectedWrappingCode.value = pd.selectedWrappingCode || '';
    moreDescriptions.value = Array.isArray(pd.moreDescriptions) ? pd.moreDescriptions : [];
    subMaterials.value = Array.isArray(pd.subMaterials) ? pd.subMaterials : [];
};

// (moved below after refs are declared to avoid early access issues)

// Clear PD fields when Setup PD modal is opened for new MC
watch(() => props.showSetupPdModal, (newVal) => {
    if (newVal && !props.mcLoaded) {
        // If opening Setup PD modal and no MC is loaded (new MC), clear all fields
        clearPdFields();
    }
    // Option B: SO/WO are global at root pd_setup; do not change parent's SO/WO on modal open
});
// Local editable 10-row components list and selection state
const selectedComponentIndex = ref(0);
const localComponents = ref([]);

// Per-component PD state (now includes per-component SO/WO and Print Colors)
const makeEmptyPdState = () => ({
    partNo: '',
    selectedProductDesign: '',
    pcsPerSet: '',
    soValues: ['', '', '', '', ''],
    woValues: ['', '', '', '', ''],
    printColorCodes: ['', '', '', '', '', '', ''],
});
const componentForms = ref(Array.from({ length: 10 }, () => makeEmptyPdState()));
// Now safe to hydrate PD from loaded MC/PD data
watch(() => props.mcLoaded, (newValue) => {
    if (newValue) {
        hydratePdFromLoaded();
    }
}, { immediate: true });

// Prefer loaded components when available, else fallback to props.mcComponents
// Always render exactly 10 rows with C# labels: Main, Fit1..Fit9
const mcComponentsToRender = computed(() => {
    const desiredLabels = ['Main', 'Fit1', 'Fit2', 'Fit3', 'Fit4', 'Fit5', 'Fit6', 'Fit7', 'Fit8', 'Fit9'];

    // Source components: loaded first, otherwise props.mcComponents
    let source = [];
    const fromLoaded = props.mcLoaded?.pd_setup?.components;
    if (Array.isArray(fromLoaded) && fromLoaded.length > 0) {
        source = fromLoaded.map((c) => ({
            c_num: c.c_num || c.comp || '',
            pd: c.pd || c.p_design || '',
            pcs_set: c.pcs_set || c.pcs || '',
            part_num: c.part_num || c.part || '',
        }));
    } else if (Array.isArray(props.mcComponents)) {
        source = props.mcComponents.map((c) => ({
            c_num: c.c_num || '',
            pd: c.pd || '',
            pcs_set: c.pcs_set || '',
            part_num: c.part_num || '',
        }));
    }

    // Build exactly 10 rows, normalizing labels and padding missing entries
    const rows = [];
    for (let i = 0; i < 10; i++) {
        const base = source[i] || {};
        rows.push({
            c_num: desiredLabels[i],
            pd: base.pd || '',
            pcs_set: base.pcs_set || '',
            part_num: base.part_num || '',
            selected: false,
            index: i,
        });
    }
    // Fallback: if no components provided, derive Main row from MC table columns
    try {
        const loaded = props.mcLoaded || {};
        const hasNoComponents = !Array.isArray(fromLoaded) || fromLoaded.length === 0;
        if (hasNoComponents && rows[0]) {
            const mcPd = loaded.P_DESIGN || loaded.p_design || '';
            const mcPcsSet = (loaded.PCS_SET ?? loaded.pcs_set ?? '');
            const mcPart = loaded.PART_NO || loaded.part_no || '';
            if (!rows[0].pd && mcPd) rows[0].pd = mcPd;
            if (!rows[0].pcs_set && (mcPcsSet !== '')) rows[0].pcs_set = mcPcsSet;
            if (!rows[0].part_num && mcPart) rows[0].part_num = mcPart;
        }
    } catch (e) {}
    return rows;
});

// Initialize and keep localComponents in sync with computed default rows
watch(mcComponentsToRender, (rows) => {
    // Preserve existing edits by merging on index
    const next = rows.map((row, idx) => ({
        ...(localComponents.value[idx] || {}),
        ...row,
        c_num: rows[idx].c_num,
    }));
    localComponents.value = next;
}, { immediate: true });

// Sync incoming SO/WO props into current component form so textboxes reflect selections
watch(() => props.soValues, (vals) => {
    try {
        const idx = selectedComponentIndex.value ?? 0;
        const current = (componentForms.value[idx] || makeEmptyPdState());
        const base = Array.isArray(current.soValues) ? current.soValues.slice(0, 5) : ['', '', '', '', ''];
        const incoming = Array.isArray(vals) ? vals : [];
        const merged = base.map((v, i) => {
            const nv = incoming[i];
            return (nv !== undefined && nv !== null && nv !== '') ? (nv + '') : (v ?? '');
        });
        componentForms.value[idx] = { ...current, soValues: merged };
    } catch (e) {}
}, { deep: true });

watch(() => props.woValues, (vals) => {
    try {
        const idx = selectedComponentIndex.value ?? 0;
        const current = (componentForms.value[idx] || makeEmptyPdState());
        const base = Array.isArray(current.woValues) ? current.woValues.slice(0, 5) : ['', '', '', '', ''];
        const incoming = Array.isArray(vals) ? vals : [];
        const merged = base.map((v, i) => {
            const nv = incoming[i];
            return (nv !== undefined && nv !== null && nv !== '') ? (nv + '') : (v ?? '');
        });
        componentForms.value[idx] = { ...current, woValues: merged };
    } catch (e) {}
}, { deep: true });

// Reflect current component SO/WO to parent whenever they change so UI stays in sync
watch(
    () => {
        const idx = selectedComponentIndex.value ?? 0;
        return {
            so: componentForms.value[idx]?.soValues || [],
            wo: componentForms.value[idx]?.woValues || [],
        };
    },
    (val) => {
        try {
            const so = normalizeFive(val.so);
            const wo = normalizeFive(val.wo);
            emit('requestSetSoWo', { so, wo });
        } catch (e) {}
    },
    { deep: true }
);

// Initialize componentForms basic fields from loaded components (pd/pcs_set/part_num)
watch(() => props.mcLoaded, (loaded) => {
    try { console.debug('[UpdateMcModal] mcLoaded changed, pd_setup snapshot:', JSON.parse(JSON.stringify(loaded?.pd_setup || null))); } catch (e) {}
    // Utilities (SO/WO extraction kept only for legacy root fallback)
    const desiredLabels = ['Main', 'Fit1', 'Fit2', 'Fit3', 'Fit4', 'Fit5', 'Fit6', 'Fit7', 'Fit8', 'Fit9'];
    const fromNumberedKeys = (obj, baseKey) => {
        if (!obj || typeof obj !== 'object') return [];
        const entries = [];
        for (let i = 1; i <= 5; i++) {
            const variants = [
                `${baseKey}${i}`,
                `${baseKey}_${i}`,
                `${baseKey}${i}`.toUpperCase(),
                `${baseKey}_${i}`.toUpperCase(),
            ];
            let found = '';
            for (const k of Object.keys(obj)) {
                if (variants.includes(k)) {
                    found = obj[k] ?? '';
                    break;
                }
            }
            entries.push(found);
        }
        return entries;
    };
    const normalize5 = (arr) => {
        const base = Array.isArray(arr) ? arr.slice(0, 5) : [];
        return base.concat(['', '', '', '', '']).slice(0, 5);
    };
    // Option B: drop per-component extraction

// Hydrate per-component PD basics and per-component SO/WO/Print Colors
    if (loaded?.pd_setup) {
        const pd = loaded.pd_setup;
        for (let idx = 0; idx < componentForms.value.length; idx++) {
            const cf = componentForms.value[idx] || makeEmptyPdState();
            // Basic fields from components when available
            const compSrc = Array.isArray(pd.components)
                ? (pd.components[idx] || null)
                : (pd.components && typeof pd.components === 'object' ? pd.components[desiredLabels[idx]] : null);
            if (compSrc) {
                cf.selectedProductDesign = compSrc.pd || compSrc.p_design || cf.selectedProductDesign;
                cf.pcsPerSet = compSrc.pcs_set || compSrc.pcs || cf.pcsPerSet;
                cf.partNo = compSrc.part_num || compSrc.part || cf.partNo;
            }
            // Hydrate per-component SO/WO and print colors when present
            const soArr = compSrc ? (Array.isArray(compSrc.soValues) ? compSrc.soValues : (Array.isArray(compSrc.so) ? compSrc.so : fromNumberedKeys(compSrc, 'so'))) : [];
            const woArr = compSrc ? (Array.isArray(compSrc.woValues) ? compSrc.woValues : (Array.isArray(compSrc.wo) ? compSrc.wo : fromNumberedKeys(compSrc, 'wo'))) : [];
            const pcArr = compSrc ? (Array.isArray(compSrc.printColorCodes) ? compSrc.printColorCodes : []) : [];
            cf.soValues = Array.isArray(cf.soValues) && cf.soValues.some(v => v) ? cf.soValues : normalize5(soArr);
            cf.woValues = Array.isArray(cf.woValues) && cf.woValues.some(v => v) ? cf.woValues : normalize5(woArr);
            cf.printColorCodes = Array.isArray(cf.printColorCodes) && cf.printColorCodes.some(v => v) ? cf.printColorCodes : (Array.isArray(pcArr) ? pcArr.slice(0,7).concat(['','','','','','']).slice(0,7) : ['', '', '', '', '', '', '']);
            componentForms.value[idx] = { ...makeEmptyPdState(), ...cf };
        }
    }
    // Fallback hydration from MC table fields for Main row when no components exist
    try {
        const noComp = !loaded?.pd_setup || !Array.isArray(loaded.pd_setup?.components) || loaded.pd_setup.components.length === 0;
        if (noComp) {
            const cf0 = componentForms.value[0] || makeEmptyPdState();
            cf0.selectedProductDesign = cf0.selectedProductDesign || loaded.P_DESIGN || loaded.p_design || '';
            cf0.pcsPerSet = cf0.pcsPerSet || (loaded.PCS_SET ?? loaded.pcs_set ?? '');
            cf0.partNo = cf0.partNo || loaded.PART_NO || loaded.part_no || '';
            componentForms.value[0] = { ...makeEmptyPdState(), ...cf0 };
        }
    } catch (e) {}
    // Reflect selected component SO/WO into parent UI so paper quality buttons/editors work per C#
    try {
        const cf = componentForms.value[selectedComponentIndex.value] || makeEmptyPdState();
            emit('requestSetSoWo', {
                so: Array.isArray(cf.soValues) ? cf.soValues : ['', '', '', '', ''],
                wo: Array.isArray(cf.woValues) ? cf.woValues : ['', '', '', '', ''],
            });
    } catch (e) {}
}, { immediate: true });

const onSelectComponent = (component, index) => {
    selectedComponentIndex.value = index;
    // When selecting a component, reflect its SO/WO into parent UI state
    try {
        const idx = selectedComponentIndex.value;
        const cf = componentForms.value[idx] || makeEmptyPdState();
        if ((!cf.soValues?.some(v => v)) && (!cf.woValues?.some(v => v))) {
            emit('requestClearSoWo');
        } else {
            emit('requestSetSoWo', {
                so: Array.isArray(cf.soValues) ? cf.soValues : ['', '', '', '', ''],
                wo: Array.isArray(cf.woValues) ? cf.woValues : ['', '', '', '', ''],
            });
        }
    } catch (e) {}
};

const openSetupPd = () => {
    // Ensure a component is selected; default to index 0 (Main)
    if (selectedComponentIndex.value === null) selectedComponentIndex.value = 0;
    // If the selected component row is empty (no PD, PCS/SET, PART#), clear the PD form
    try {
        const idx = selectedComponentIndex.value;
        const row = Array.isArray(localComponents.value) ? localComponents.value[idx] : null;
        const isRowEmpty = row && !row.pd && !row.pcs_set && !row.part_num;
        if (isRowEmpty) {
            clearPdFields();
            // Also request parent to clear SO/WO paper quality values
            emit('requestClearSoWo');
        } else if (row) {
            // If row has saved values, hydrate PD key fields from the selected component
            // Preserve already-loaded global PD (from mcLoaded), but override component-specific fields
            const cf = componentForms.value[selectedComponentIndex.value] || makeEmptyPdState();
            partNo.value = cf.partNo || row.part_num || partNo.value || '';
            selectedProductDesign.value = cf.selectedProductDesign || row.pd || selectedProductDesign.value || '';
            pcsPerSet.value = cf.pcsPerSet || row.pcs_set || pcsPerSet.value || '';
            // Option B: no per-component SO/WO hydration/emits
        }
    } catch (e) {}
    emit('setupPD');
};

// Persist PD fields into the current component form so they don't affect others
watch([partNo, selectedProductDesign, pcsPerSet], () => {
    if (selectedComponentIndex.value === null) return;
    const idx = selectedComponentIndex.value;
    const next = { ...(componentForms.value[idx] || makeEmptyPdState()) };
    next.partNo = partNo.value;
    next.selectedProductDesign = selectedProductDesign.value;
    next.pcsPerSet = pcsPerSet.value;
    componentForms.value[idx] = next;
});

// Option B: stop syncing parent SO/WO into per-component forms

// Apply current PD form fields to the selected component row
const applyPdToSelectedComponent = () => {
    if (selectedComponentIndex.value === null) return;
    const idx = selectedComponentIndex.value;
    const next = [...localComponents.value];
    next[idx] = {
        ...next[idx],
        // Keep C# label fixed
        c_num: next[idx]?.c_num || ['Main','Fit1','Fit2','Fit3','Fit4','Fit5','Fit6','Fit7','Fit8','Fit9'][idx],
        // Map PD fields to row
        pd: selectedProductDesign.value || next[idx]?.pd || '',
        pcs_set: pcsPerSet.value || next[idx]?.pcs_set || '',
        part_num: partNo.value || next[idx]?.part_num || '',
    };
    localComponents.value = next;
};

// Build payload for PD setup values to be saved with MasterCard
const buildPdSetupPayload = () => {
    // Ensure current PD inputs are applied to the currently selected component row
    try { applyPdToSelectedComponent(); } catch (e) {}
    // Root SO/WO: persist Main component SO/WO; fallback to props; fallback to mcLoaded (SO_PQ/WO_PQ)
    const mainIdx = 0;
    const soFromMain = componentForms.value[mainIdx]?.soValues || [];
    const woFromMain = componentForms.value[mainIdx]?.woValues || [];
    const soFromProps = props.soValues || [];
    const woFromProps = props.woValues || [];
    const loaded = props.mcLoaded || {};
    const soFromMc = [loaded.SO_PQ1, loaded.SO_PQ2, loaded.SO_PQ3, loaded.SO_PQ4, loaded.SO_PQ5];
    const woFromMc = [loaded.WO_PQ1, loaded.WO_PQ2, loaded.WO_PQ3, loaded.WO_PQ4, loaded.WO_PQ5];
    const rootSo = normalizeFive((soFromMain && soFromMain.some(v => v)) ? soFromMain : (soFromProps && soFromProps.some?.(v => v)) ? soFromProps : soFromMc);
    const rootWo = normalizeFive((woFromMain && woFromMain.some(v => v)) ? woFromMain : (woFromProps && woFromProps.some?.(v => v)) ? woFromProps : woFromMc);
    return {
        partNo: partNo.value,
        selectedProductDesign: selectedProductDesign.value,
        selectedPaperFlute: selectedPaperFlute.value,
        selectedChemicalCoat: selectedChemicalCoat.value,
        selectedReinforcementTape: selectedReinforcementTape.value,
        selectedPaperSize: selectedPaperSize.value,
        selectedScoringToolCode: selectedScoringToolCode.value,
        // Root printColorCodes kept as a summary of selected component for compatibility
        printColorCodes: printColorCodes.value,
        colorAreaPercents: colorAreaPercents.value,
        scoreL: scoreL.value,
        scoreW: scoreW.value,
        sheetLength: sheetLength.value,
        sheetWidth: sheetWidth.value,
        conOut: conOut.value,
        convDuctX2: convDuctX2.value,
        slitOut: convDuctX2A.value,
        dieOut: convDuctX2B.value,
        pcsToJoint: pcsToJoint.value,
        // Provide top-level ID/ED so backend numeric map can persist them
        int_dim_1: idL.value,
        int_dim_2: idW.value,
        int_dim_3: idH.value,
        ext_dim_1: edL.value,
        ext_dim_2: edW.value,
        ext_dim_3: edH.value,
        // Provide top-level part_no for MC.PART_NO persistence
        part_no: partNo.value,
        id: { L: idL.value, W: idW.value, H: idH.value },
        ed: { L: edL.value, W: edW.value, H: edH.value },
        pcsPerSet: pcsPerSet.value,
        creaseValue: creaseValue.value,
        nestSlot: nestSlot.value,
        dcutSheet: { L: dcutSheetL.value, W: dcutSheetW.value },
        dcutMould: { L: dcutMouldL.value, W: dcutMouldW.value },
        dcutBlockNo: dcutBlockNo.value,
        pitBlockNo: pitBlockNo.value,
        stitchWirePieces: stitchWirePieces.value,
        bdlPerPallet: bdlPerPallet.value,
        peelOffPercent: peelOffPercent.value,
        itemRemark: itemRemark.value,
        handHole: handHole.value,
        rotaryDCut: rotaryDCut.value,
        fullBlockPrint: fullBlockPrint.value,
        selectedFinishingCode: selectedFinishingCode.value,
        selectedStitchWireCode: selectedStitchWireCode.value,
        stitchWirePieces: stitchWirePieces.value,
        selectedBundlingStringCode: selectedBundlingStringCode.value,
        bundlingStringQty: bundlingStringQty.value,
        selectedGlueingCode: selectedGlueingCode.value,
        selectedWrappingCode: selectedWrappingCode.value,
        moreDescriptions: moreDescriptions.value,
        subMaterials: subMaterials.value,
        // Calculated M2 values
        mcGrossM2PerPcs: mcGrossM2PerPcs.value,
        mcNetM2PerPcs: mcNetM2PerPcs.value,
        // Legacy root arrays for controller mapping
        soValues: rootSo,
        woValues: rootWo,
        // No global SO/WO; store them per component instead
        components: (localComponents?.value || []).map((c, idx) => ({
            c_num: c.c_num,
            pd: (componentForms.value[idx]?.selectedProductDesign) || c.pd,
            pcs_set: (componentForms.value[idx]?.pcsPerSet) || c.pcs_set,
            part_num: (componentForms.value[idx]?.partNo) || c.part_num,
            soValues: (componentForms.value[idx]?.soValues) || ['', '', '', '', ''],
            woValues: (componentForms.value[idx]?.woValues) || ['', '', '', '', ''],
            printColorCodes: (componentForms.value[idx]?.printColorCodes) || ['', '', '', '', '', '', ''],
        })),
    };
};
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
