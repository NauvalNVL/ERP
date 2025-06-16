<template>
  <AppLayout title="View & Print Master Card Approval Log">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        View & Print Master Card Approval Log
      </h2>
    </template>

    <!-- Main Container with vibrant gradient background -->
    <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 p-4 md:p-6">
      <div class="max-w-7xl mx-auto">
        <!-- Header Section with animated elements -->
        <div class="bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 rounded-xl shadow-lg overflow-hidden mb-6 transform transition-all duration-500 hover:shadow-xl">
          <div class="relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10"></div>
            <div class="absolute bottom-0 right-0 w-32 h-32 bg-yellow-400 opacity-5 rounded-full translate-y-10 translate-x-10"></div>
            
            <div class="p-6 md:p-8 relative z-10">
              <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex items-start md:items-center space-x-4 mb-4 md:mb-0">
                  <div class="bg-gradient-to-br from-pink-500 to-purple-600 p-3 rounded-lg shadow-inner flex items-center justify-center">
                    <i class="fas fa-print text-white text-2xl"></i>
                  </div>
                  <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white text-shadow">View & Print MC Approval Log</h1>
                    <p class="text-blue-100 max-w-2xl">Preview and print master card approval log data</p>
                  </div>
                </div>
                <div class="flex flex-wrap gap-3">
                  <button @click="printReport" class="bg-gradient-to-r from-green-500 to-teal-400 text-white hover:from-green-600 hover:to-teal-500 px-4 py-2 rounded-lg flex items-center transition-all duration-300 transform hover:scale-105 shadow-md">
                    <i class="fas fa-print mr-2"></i> Print Report
                  </button>
                  <button @click="$router.go(-1)" class="bg-white bg-opacity-20 text-white border border-white border-opacity-30 hover:bg-opacity-30 px-4 py-2 rounded-lg flex items-center transition-all duration-300">
                    <i class="fas fa-arrow-left mr-2"></i> Back
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-gradient-to-br from-white to-purple-50 rounded-xl shadow-lg mb-6 transform transition-all duration-500 hover:shadow-xl overflow-hidden">
          <div class="border-b border-purple-100">
            <div class="flex items-center p-5">
              <div class="bg-gradient-to-r from-indigo-500 to-purple-500 text-white p-2 rounded-lg mr-3 shadow-md">
                <i class="fas fa-filter"></i>
              </div>
              <h2 class="text-xl font-semibold text-indigo-800">Search & Filter</h2>
            </div>
          </div>

          <div class="p-5">
            <!-- Actions Bar -->
            <div class="flex flex-wrap items-center justify-between mb-6">
              <div class="flex items-center space-x-4 mb-3 sm:mb-0">
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-search text-purple-400"></i>
                  </div>
                  <input 
                    type="text" 
                    v-model="searchQuery" 
                    class="pl-10 pr-4 py-2 border border-purple-200 rounded-lg focus:ring-purple-500 focus:border-purple-500 bg-white"
                    placeholder="Search by MCS#..."
                  >
                </div>
                <button 
                  @click="openFilterModal"
                  class="bg-gradient-to-r from-purple-500 to-indigo-500 text-white px-4 py-2 rounded-lg flex items-center transition-all duration-300 transform hover:scale-105 shadow-md"
                >
                  <i class="fas fa-sliders-h mr-2"></i> Filter Options
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Table Section -->
        <div class="bg-gradient-to-br from-white to-purple-50 rounded-xl shadow-lg overflow-hidden">
          <div class="overflow-x-auto">
            <div id="printableTable" class="min-w-full bg-white rounded-lg overflow-hidden">
              <!-- Table Header -->
              <div class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-4 px-6 flex items-center">
                <div class="flex items-center">
                  <div class="mr-4">
                    <i class="fas fa-id-card text-3xl"></i>
                  </div>
                  <div>
                    <h2 class="text-xl font-bold">MASTER CARD APPROVAL LOG</h2>
                    <p class="text-sm opacity-80">View approval status history for master cards</p>
                  </div>
                </div>
              </div>

              <!-- Table Content -->
              <div class="max-h-[400px] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gradient-to-r from-purple-100 to-indigo-100">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider">
                        A/C#
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider">
                        MCS#
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider">
                        M/Card Status
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider">
                        Approval Status
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="loading" class="hover:bg-purple-50">
                      <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                        <div class="flex justify-center">
                          <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-purple-500"></div>
                        </div>
                        <p class="mt-2">Loading approval log data...</p>
                      </td>
                    </tr>
                    <tr v-else-if="filteredRecords.length === 0" class="hover:bg-purple-50">
                      <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                        No records found. 
                        <template v-if="searchQuery">
                          <p class="mt-2">No results match your search query: "{{ searchQuery }}"</p>
                          <button @click="searchQuery = ''" class="mt-2 text-purple-500 hover:underline">Clear search</button>
                        </template>
                      </td>
                    </tr>
                    <tr 
                      v-for="(record, index) in filteredRecords" 
                      :key="record.id"
                      @click="selectRecord(record)"
                      :class="{'bg-purple-50': index % 2 === 0, 'bg-indigo-50 border-l-4 border-indigo-500': selectedRecord && selectedRecord.id === record.id}" 
                      class="hover:bg-indigo-50 transition-colors duration-150 cursor-pointer"
                    >
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ record.customer_code }}</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ record.mc_seq }}</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <span 
                          class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium"
                          :class="{
                            'bg-green-100 text-green-800': record.is_active,
                            'bg-red-100 text-red-800': !record.is_active
                          }">
                          {{ record.is_active ? 'Active' : 'Obsolete' }}
                        </span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <span 
                          class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium"
                          :class="{
                            'bg-green-100 text-green-800': record.is_released,
                            'bg-blue-100 text-blue-800': record.is_approved && !record.is_released,
                            'bg-yellow-100 text-yellow-800': !record.is_approved
                          }">
                          {{ getApprovalStatus(record) }}
                        </span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <!-- Table Footer -->
              <div class="bg-gradient-to-r from-purple-50 to-indigo-50 px-6 py-3 border-t border-purple-100 text-sm text-indigo-700">
                <div class="flex items-center justify-between">
                  <div>Total Records: {{ filteredRecords.length }}</div>
                  <div class="text-xs text-indigo-400">Generated: {{ currentDate }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Customer and Model info -->
        <div class="mt-6 bg-gradient-to-br from-white to-purple-50 rounded-xl shadow-lg p-6">
          <div class="border-b border-purple-100 mb-4 pb-3">
            <div class="flex items-center">
              <div class="bg-gradient-to-r from-indigo-500 to-purple-500 text-white p-2 rounded-lg mr-3 shadow-md">
                <i class="fas fa-info-circle"></i>
              </div>
              <h2 class="text-xl font-semibold text-indigo-800">Selected Record Details</h2>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
              <div class="relative">
                <label class="block text-sm font-medium text-indigo-700 mb-1">Customer</label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-building text-purple-400"></i>
                  </div>
                  <input 
                    type="text" 
                    v-model="selectedCustomer" 
                    class="w-full pl-10 border border-purple-200 rounded-lg px-4 py-2.5 focus:ring-purple-500 focus:border-purple-500 bg-white shadow-sm"
                    readonly
                  />
                </div>
              </div>
              
              <div class="relative">
                <label class="block text-sm font-medium text-indigo-700 mb-1">Model</label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-box text-purple-400"></i>
                  </div>
                  <input 
                    type="text" 
                    v-model="selectedModel" 
                    class="w-full pl-10 border border-purple-200 rounded-lg px-4 py-2.5 focus:ring-purple-500 focus:border-purple-500 bg-white shadow-sm"
                    readonly
                  />
                </div>
              </div>
            </div>
            
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-indigo-700 mb-3">Last Maintenance</label>
                <div class="grid grid-cols-3 gap-3">
                  <div class="relative">
                    <input 
                      type="text" 
                      v-model="lastMaintenance.user" 
                      class="w-full pl-9 border border-purple-200 rounded-lg py-2 focus:ring-purple-500 focus:border-purple-500 bg-white shadow-sm"
                      readonly
                    />
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                      <i class="fas fa-user text-purple-400"></i>
                    </div>
                  </div>
                  <div class="relative">
                    <input 
                      type="text" 
                      v-model="lastMaintenance.date" 
                      class="w-full pl-9 border border-purple-200 rounded-lg py-2 focus:ring-purple-500 focus:border-purple-500 bg-white shadow-sm"
                      readonly
                    />
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                      <i class="fas fa-calendar text-purple-400"></i>
                    </div>
                  </div>
                  <div class="relative">
                    <input 
                      type="text" 
                      v-model="lastMaintenance.time" 
                      class="w-full pl-9 border border-purple-200 rounded-lg py-2 focus:ring-purple-500 focus:border-purple-500 bg-white shadow-sm"
                      readonly
                    />
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                      <i class="fas fa-clock text-purple-400"></i>
                    </div>
                  </div>
                </div>
                <div class="mt-3 relative">
                  <label class="block text-sm font-medium text-indigo-700 mb-1">Process</label>
                  <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                      <i class="fas fa-cog text-purple-400"></i>
                    </div>
                    <input 
                      type="text" 
                      v-model="lastMaintenance.process" 
                      class="w-full pl-10 border border-purple-200 rounded-lg py-2 focus:ring-purple-500 focus:border-purple-500 bg-white shadow-sm"
                      readonly
                    />
                  </div>
                </div>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-indigo-700 mb-3">Last Approval</label>
                <div class="grid grid-cols-3 gap-3">
                  <div class="relative">
                    <input 
                      type="text" 
                      v-model="lastApproval.user" 
                      class="w-full pl-9 border border-purple-200 rounded-lg py-2 focus:ring-purple-500 focus:border-purple-500 bg-white shadow-sm"
                      readonly
                    />
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                      <i class="fas fa-user text-purple-400"></i>
                    </div>
                  </div>
                  <div class="relative">
                    <input 
                      type="text" 
                      v-model="lastApproval.date" 
                      class="w-full pl-9 border border-purple-200 rounded-lg py-2 focus:ring-purple-500 focus:border-purple-500 bg-white shadow-sm"
                      readonly
                    />
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                      <i class="fas fa-calendar text-purple-400"></i>
                    </div>
                  </div>
                  <div class="relative">
                    <input 
                      type="text" 
                      v-model="lastApproval.time" 
                      class="w-full pl-9 border border-purple-200 rounded-lg py-2 focus:ring-purple-500 focus:border-purple-500 bg-white shadow-sm"
                      readonly
                    />
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                      <i class="fas fa-clock text-purple-400"></i>
                    </div>
                  </div>
                </div>
                <div class="mt-3 relative">
                  <label class="block text-sm font-medium text-indigo-700 mb-1">Process</label>
                  <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                      <i class="fas fa-check-circle text-purple-400"></i>
                    </div>
                    <input 
                      type="text" 
                      v-model="lastApproval.process" 
                      class="w-full pl-10 border border-purple-200 rounded-lg py-2 focus:ring-purple-500 focus:border-purple-500 bg-white shadow-sm"
                      readonly
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Print Instructions -->
        <div class="mt-6 bg-gradient-to-r from-purple-50 to-pink-50 p-4 rounded-lg border border-purple-100 shadow-sm">
          <h3 class="font-semibold text-indigo-800 mb-2 flex items-center">
            <i class="fas fa-info-circle mr-2 text-purple-500"></i> Print Instructions
          </h3>
          <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
            <li>Click the "Print Report" button above to print this approval log</li>
            <li>Use landscape orientation for better results</li>
            <li>You can search or filter data before printing</li>
            <li>Only the table will be included in the print output</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Filter Modal -->
    <div 
      v-if="showFilterModal" 
      class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300"
      @click.self="closeFilterModal"
    >
      <div 
        class="bg-white rounded-xl shadow-xl p-6 w-96 max-w-md transform transition-all duration-300 ease-out"
        :class="{'scale-100 opacity-100': showFilterModal, 'scale-95 opacity-0': !showFilterModal}"
      >
        <div class="flex justify-between items-center mb-4 border-b border-purple-100 pb-3">
          <h3 class="text-lg font-medium text-indigo-800 flex items-center">
            <i class="fas fa-filter text-purple-600 mr-2"></i>
            Filter Options
          </h3>
          <button @click="closeFilterModal" class="text-gray-400 hover:text-gray-500 transition-colors">
            <i class="fas fa-times"></i>
          </button>
        </div>
        
        <div class="bg-gradient-to-br from-white to-purple-50 rounded-lg p-5 mb-5 border border-purple-100">
          <div class="mb-5">
            <div class="font-medium text-indigo-700 mb-3">M/Card Status:</div>
            <div class="grid grid-cols-2 gap-3">
              <div class="flex items-center space-x-2">
                <input 
                  type="checkbox" 
                  id="active" 
                  v-model="filters.status.active" 
                  class="h-4 w-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500"
                >
                <label for="active" class="text-gray-700">
                  <span class="inline-flex items-center bg-green-100 text-green-800 px-2.5 py-1 rounded-full text-xs font-medium">
                    Active
                  </span>
                </label>
              </div>
              <div class="flex items-center space-x-2">
                <input 
                  type="checkbox" 
                  id="obsolete" 
                  v-model="filters.status.obsolete" 
                  class="h-4 w-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500"
                >
                <label for="obsolete" class="text-gray-700">
                  <span class="inline-flex items-center bg-red-100 text-red-800 px-2.5 py-1 rounded-full text-xs font-medium">
                    Obsolete
                  </span>
                </label>
              </div>
            </div>
          </div>
          
          <div>
            <div class="font-medium text-indigo-700 mb-3">Approval Status:</div>
            <div class="grid grid-cols-2 gap-3 mb-3">
              <div class="flex items-center space-x-2">
                <input 
                  type="checkbox" 
                  id="approved" 
                  v-model="filters.approval.approved" 
                  class="h-4 w-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500"
                >
                <label for="approved" class="text-gray-700">
                  <span class="inline-flex items-center bg-blue-100 text-blue-800 px-2.5 py-1 rounded-full text-xs font-medium">
                    Approved
                  </span>
                </label>
              </div>
              <div class="flex items-center space-x-2">
                <input 
                  type="checkbox" 
                  id="notApproved" 
                  v-model="filters.approval.notApproved" 
                  class="h-4 w-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500"
                >
                <label for="notApproved" class="text-gray-700">
                  <span class="inline-flex items-center bg-yellow-100 text-yellow-800 px-2.5 py-1 rounded-full text-xs font-medium">
                    Not Approve
                  </span>
                </label>
              </div>
            </div>
            <div class="flex items-center space-x-2">
              <input 
                type="checkbox" 
                id="released" 
                v-model="filters.approval.released" 
                class="h-4 w-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500"
              >
              <label for="released" class="text-gray-700">
                <span class="inline-flex items-center bg-green-100 text-green-800 px-2.5 py-1 rounded-full text-xs font-medium">
                  Released
                </span>
              </label>
            </div>
          </div>
        </div>
        
        <div class="flex justify-center space-x-4">
          <button 
            @click="applyFilters" 
            class="bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-medium py-2 px-6 rounded-lg transition-colors duration-200 shadow-md"
          >
            <i class="fas fa-check mr-2"></i>Apply Filters
          </button>
          <button 
            @click="closeFilterModal" 
            class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-6 rounded-lg transition-colors duration-200"
          >
            Cancel
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import { ref, computed, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

export default {
  components: {
    AppLayout,
    Head,
    Link
  },
  setup() {
    const loading = ref(true);
    const records = ref([]);
    const showFilterModal = ref(false);
    const selectedCustomer = ref('MULTIBOX INDAH, PT (OIA)');
    const selectedModel = ref('CB-P : 664 X 2400 mm ML11/ML11/ML11 BF');
    const selectedRecord = ref(null);
    const searchQuery = ref('');
    const currentDate = new Date().toLocaleString();
    
    const lastMaintenance = ref({
      user: 'ppic10',
      date: '10/02/2016',
      time: '15:10:15',
      process: 'New'
    });
    
    const lastApproval = ref({
      user: 'ppic10',
      date: '10/02/2016',
      time: '15:10:15',
      process: 'Approved'
    });
    
    const filters = ref({
      status: {
        active: true,
        obsolete: true
      },
      approval: {
        approved: true,
        notApproved: true,
        released: true
      }
    });

    const fetchRecords = async () => {
      loading.value = true;
      try {
        // In a real app, replace this with actual API call
        const response = await axios.get('/api/approve-mc');
        records.value = response.data;
      } catch (error) {
        console.error('Error fetching data:', error);
        // Fallback to mock data if API fails
        records.value = [
          { id: 1, customer_code: '000000', mc_seq: '2016020723', is_active: false, is_approved: true, is_released: false },
          { id: 2, customer_code: '000000', mc_seq: '2016020169', is_active: false, is_approved: true, is_released: false },
          { id: 3, customer_code: '000000', mc_seq: '2016020349', is_active: false, is_approved: true, is_released: false },
          { id: 4, customer_code: '000000', mc_seq: '2016020351', is_active: false, is_approved: true, is_released: false },
          { id: 5, customer_code: '000000', mc_seq: '2016020431', is_active: false, is_approved: true, is_released: false },
          { id: 6, customer_code: '000000', mc_seq: '2016020591', is_active: false, is_approved: true, is_released: false },
          { id: 7, customer_code: '000000', mc_seq: '2016020592', is_active: false, is_approved: true, is_released: false },
          { id: 8, customer_code: '000000', mc_seq: '2016020773', is_active: false, is_approved: true, is_released: false },
          { id: 9, customer_code: '000000', mc_seq: '2016030053', is_active: false, is_approved: true, is_released: false },
          { id: 10, customer_code: '000000', mc_seq: '2016030142', is_active: false, is_approved: true, is_released: false },
          { id: 11, customer_code: '000000', mc_seq: '2016030175', is_active: false, is_approved: true, is_released: false },
          { id: 12, customer_code: '000000', mc_seq: '2016030488', is_active: false, is_approved: true, is_released: false },
          { id: 13, customer_code: '000000', mc_seq: '2016030590', is_active: false, is_approved: true, is_released: false },
          { id: 14, customer_code: '000000', mc_seq: '2016030593', is_active: false, is_approved: true, is_released: false },
          { id: 15, customer_code: '000000', mc_seq: '2016030664', is_active: false, is_approved: true, is_released: false },
          { id: 16, customer_code: '000000', mc_seq: '2016030667', is_active: false, is_approved: true, is_released: false },
          { id: 17, customer_code: '000000', mc_seq: '2016030928', is_active: false, is_approved: true, is_released: false },
          { id: 18, customer_code: '000000', mc_seq: '2016030929', is_active: false, is_approved: true, is_released: false },
          { id: 19, customer_code: '000000', mc_seq: '2016030475', is_active: false, is_approved: true, is_released: false },
          { id: 20, customer_code: '000000', mc_seq: '2016030477', is_active: false, is_approved: true, is_released: false },
          { id: 21, customer_code: '000000', mc_seq: '2016030479', is_active: false, is_approved: true, is_released: false },
          { id: 22, customer_code: '000000', mc_seq: '2016030481', is_active: false, is_approved: true, is_released: false },
          { id: 23, customer_code: '000000', mc_seq: '2016030483', is_active: false, is_approved: true, is_released: false },
        ];
      } finally {
        loading.value = false;
        
        // Default selection to first record if available
        if (records.value.length > 0) {
          selectRecord(records.value[0]);
        }
      }
    };

    const filteredRecords = computed(() => {
      return records.value.filter(record => {
        // Filter by search query
        if (searchQuery.value && !record.mc_seq.toLowerCase().includes(searchQuery.value.toLowerCase())) {
          return false;
        }
        
        // Filter by M/Card Status
        if (record.is_active && !filters.value.status.active) return false;
        if (!record.is_active && !filters.value.status.obsolete) return false;

        // Filter by Approval Status
        if (record.is_approved && !filters.value.approval.approved) return false;
        if (!record.is_approved && !filters.value.approval.notApproved) return false;
        if (record.is_released && !filters.value.approval.released) return false;

        return true;
      });
    });

    const getApprovalStatus = (record) => {
      if (record.is_released) return 'Released';
      if (record.is_approved) return 'Approved';
      return 'Not Approved';
    };

    const openFilterModal = () => {
      showFilterModal.value = true;
    };

    const closeFilterModal = () => {
      showFilterModal.value = false;
    };

    const applyFilters = () => {
      closeFilterModal();
    };

    const selectRecord = (record) => {
      selectedRecord.value = record;
      // Maintain the same values for demo purposes
      selectedCustomer.value = 'MULTIBOX INDAH, PT (OIA)';
      selectedModel.value = 'CB-P : 664 X 2400 mm ML11/ML11/ML11 BF';
    };

    const printReport = () => {
      const printContents = document.getElementById('printableTable').innerHTML;
      const originalContents = document.body.innerHTML;
      
      document.body.innerHTML = `
          <div style="padding: 20px;">
              <h1 style="text-align: center; margin-bottom: 20px; color: #6d28d9;">Master Card Approval Log</h1>
              ${printContents}
              <div style="text-align: right; margin-top: 20px; font-size: 12px; color: #7c3aed;">
                  Printed on: ${new Date().toLocaleString()}
              </div>
          </div>
      `;
      
      window.print();
      document.body.innerHTML = originalContents;
      
      // Reinitialize Vue app after printing
      location.reload();
    };

    onMounted(() => {
      fetchRecords();
    });

    return {
      loading,
      records,
      filteredRecords,
      showFilterModal,
      filters,
      selectedCustomer,
      selectedModel,
      selectedRecord,
      searchQuery,
      currentDate,
      lastMaintenance,
      lastApproval,
      openFilterModal,
      closeFilterModal,
      applyFilters,
      selectRecord,
      printReport,
      getApprovalStatus
    };
  }
};
</script>

<style scoped>
@media print {
  body * {
    visibility: hidden;
  }
  
  #printableTable, #printableTable * {
    visibility: visible;
  }
  
  #printableTable {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
  }
  
  .from-purple-600, .to-indigo-600 {
    background: white !important;
    color: black !important;
  }
  
  .text-white {
    color: black !important;
  }
  
  button, 
  .filter-section,
  input[type="text"]::placeholder {
    display: none;
  }
  
  .shadow-lg,
  .shadow-sm,
  .shadow {
    box-shadow: none !important;
  }
  
  .border {
    border-color: #ddd !important;
  }
  
  @page {
    size: A4 landscape;
    margin: 1cm;
  }
}

/* Custom scrollbar for Webkit browsers */
.scrollbar-thin::-webkit-scrollbar {
  width: 6px;
}

.scrollbar-thin::-webkit-scrollbar-track {
  background: #f1f1f1;
}

.scrollbar-thin::-webkit-scrollbar-thumb {
  background: #cbd5e0;
  border-radius: 3px;
}

.scrollbar-thin::-webkit-scrollbar-thumb:hover {
  background: #a0aec0;
}

/* For Firefox */
.scrollbar-thin {
  scrollbar-width: thin;
  scrollbar-color: #cbd5e0 #f1f1f1;
}

/* Animation for selected row */
@keyframes highlight {
  0% { background-color: #dbeafe; }
  100% { background-color: #eff6ff; }
}

.bg-purple-50 {
  animation: highlight 1s ease-in-out;
}

/* Text shadow for headings */
.text-shadow {
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Transition effects */
.transition-all {
  transition: all 0.3s ease;
}
</style>
