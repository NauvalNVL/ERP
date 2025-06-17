<template>
  <AppLayout :header="'View & Print MC Maintenance Log'">
    <Head title="View & Print MC Maintenance Log" />

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
                  <div class="bg-gradient-to-br from-pink-500 to-purple-600 p-3 rounded-lg shadow-inner flex items-center justify-center relative overflow-hidden">
                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-yellow-300 opacity-30 rounded-full animate-ping-slow"></div>
                    <div class="absolute -bottom-1 -left-1 w-4 h-4 bg-blue-300 opacity-30 rounded-full animate-ping-slow animation-delay-500"></div>
                    <i class="fas fa-history text-white text-2xl z-10"></i>
                  </div>
                  <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white text-shadow">View & Print MC Maintenance Log</h1>
                    <p class="text-blue-100 max-w-2xl">Track and review all maintenance activities performed on master cards</p>
                  </div>
                </div>
                <div class="flex flex-wrap gap-3">
                  <button @click="printPage" class="bg-gradient-to-r from-green-500 to-teal-400 text-white hover:from-green-600 hover:to-teal-500 px-4 py-2 rounded-lg flex items-center transition-all duration-300 transform hover:scale-105 shadow-md relative overflow-hidden group">
                    <span class="absolute inset-0 bg-white opacity-20 transform scale-x-0 origin-left transition-transform group-hover:scale-x-100"></span>
                    <div class="relative z-10 flex items-center">
                      <div class="bg-white bg-opacity-30 rounded-full p-1 mr-2">
                        <i class="fas fa-print text-white"></i>
                      </div>
                      <span>Print Report</span>
                    </div>
                  </button>
                  <Link href="/sales-management/system-requirement/master-card/obsolate-reactive-mc" class="bg-white bg-opacity-20 text-white border border-white border-opacity-30 hover:bg-opacity-30 px-4 py-2 rounded-lg flex items-center transition-all duration-300 relative overflow-hidden">
                    <span class="absolute inset-0 bg-white opacity-10 transform scale-x-0 origin-left transition-transform hover:scale-x-100"></span>
                    <div class="relative z-10 flex items-center">
                      <div class="bg-white bg-opacity-30 rounded-full p-1.5 mr-2 transition-transform transform group-hover:rotate-12 shadow-inner">
                        <i class="fas fa-arrow-left text-white"></i>
                      </div>
                      <span>Back</span>
                    </div>
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>

                <!-- Search Form -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
                  <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 px-6">
                    <div class="flex items-center">
                      <div class="mr-3 bg-white bg-opacity-20 p-2 rounded-full shadow-inner relative overflow-hidden">
                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-blue-300 opacity-30 rounded-full"></div>
                        <div class="absolute -bottom-1 -left-1 w-3 h-3 bg-yellow-300 opacity-30 rounded-full"></div>
                        <i class="fas fa-filter text-xl relative z-10"></i>
                      </div>
                      <div>
                        <h2 class="text-xl font-bold">Search Parameters</h2>
                        <p class="text-xs text-blue-100 opacity-80">Filter maintenance logs by various criteria</p>
                      </div>
                    </div>
                  </div>
                  
                  <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Date Range -->
                            <div class="col-span-1">
                                <label class="block text-sm font-medium text-indigo-700 mb-2 flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full mr-2 flex items-center justify-center shadow-sm">
                                        <i class="fas fa-calendar-alt text-white text-sm"></i>
                                    </div>
                                    Date Range:
                                </label>
                                <div class="flex space-x-2 ml-10">
                                    <div class="relative flex-grow">
                                        <input 
                                            type="date" 
                                            v-model="startDate"
                                            class="w-full pl-3 pr-10 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                                        />
                                        <div class="absolute inset-y-0 right-0 px-3 flex items-center pointer-events-none">
                                            <i class="fas fa-calendar-alt text-indigo-400"></i>
                                        </div>
                                    </div>
                                    <div class="flex items-center text-gray-700">
                                        to
                                    </div>
                                    <div class="relative flex-grow">
                                        <input 
                                            type="date" 
                                            v-model="endDate"
                                            class="w-full pl-3 pr-10 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                                        />
                                        <div class="absolute inset-y-0 right-0 px-3 flex items-center pointer-events-none">
                                            <i class="fas fa-calendar-alt text-indigo-400"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Time Range -->
                            <div class="col-span-1">
                                <label class="block text-sm font-medium text-indigo-700 mb-2 flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full mr-2 flex items-center justify-center shadow-sm">
                                        <i class="fas fa-clock text-white text-sm"></i>
                                    </div>
                                    Time Range:
                                </label>
                                <div class="flex flex-col space-y-2 ml-10">
                                    <div class="flex items-center space-x-2">
                                        <div class="flex space-x-1">
                                            <select v-model="startTimeHour" class="px-2 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                                                <option v-for="hour in 24" :key="'start-hour-' + hour" :value="hour - 1">{{ (hour - 1).toString().padStart(2, '0') }}</option>
                                            </select>
                                            <span class="text-xl text-indigo-500">:</span>
                                            <select v-model="startTimeMinute" class="px-2 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                                                <option v-for="minute in 60" :key="'start-min-' + minute" :value="minute - 1">{{ (minute - 1).toString().padStart(2, '0') }}</option>
                                            </select>
                                            <span class="text-xl text-indigo-500">:</span>
                                            <select v-model="startTimeSecond" class="px-2 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                                                <option v-for="second in 60" :key="'start-sec-' + second" :value="second - 1">{{ (second - 1).toString().padStart(2, '0') }}</option>
                                            </select>
                                        </div>
                                        <span class="text-gray-700">to</span>
                                        <div class="flex space-x-1">
                                            <select v-model="endTimeHour" class="px-2 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                                                <option v-for="hour in 24" :key="'end-hour-' + hour" :value="hour - 1">{{ (hour - 1).toString().padStart(2, '0') }}</option>
                                            </select>
                                            <span class="text-xl text-indigo-500">:</span>
                                            <select v-model="endTimeMinute" class="px-2 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                                                <option v-for="minute in 60" :key="'end-min-' + minute" :value="minute - 1">{{ (minute - 1).toString().padStart(2, '0') }}</option>
                                            </select>
                                            <span class="text-xl text-indigo-500">:</span>
                                            <select v-model="endTimeSecond" class="px-2 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                                                <option v-for="second in 60" :key="'end-sec-' + second" :value="second - 1">{{ (second - 1).toString().padStart(2, '0') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="text-xs text-gray-500 ml-1">Format: hh:mm:ss</div>
                                </div>
                            </div>

                            <!-- User ID -->
                            <div class="col-span-1">
                                <label class="block text-sm font-medium text-indigo-700 mb-2 flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full mr-2 flex items-center justify-center shadow-sm">
                                        <i class="fas fa-user text-white text-sm"></i>
                                    </div>
                                    User ID:
                                </label>
                                <div class="relative ml-10">
                                    <div class="flex">
                                        <div class="relative flex-grow">
                                            <input 
                                                type="text" 
                                                v-model="userId"
                                                placeholder="Enter User ID"
                                                class="w-full pl-3 pr-10 py-2 border border-gray-300 rounded-l-md focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                                            />
                                            <div class="absolute inset-y-0 right-0 px-3 flex items-center pointer-events-none">
                                                <i class="fas fa-user text-gray-400"></i>
                                            </div>
                                        </div>
                                        <button 
                                            @click="showUserLookup = true" 
                                            type="button" 
                                            class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-3 rounded-r-md hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm"
                                        >
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- A/C# -->
                            <div class="col-span-1">
                                <label class="block text-sm font-medium text-indigo-700 mb-2 flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full mr-2 flex items-center justify-center shadow-sm">
                                        <i class="fas fa-building text-white text-sm"></i>
                                    </div>
                                    AC#:
                                </label>
                                <div class="relative ml-10">
                                    <div class="flex">
                                        <div class="relative flex-grow">
                                            <input 
                                                type="text" 
                                                v-model="accountCode"
                                                placeholder="Enter Account Code"
                                                class="w-full pl-3 pr-10 py-2 border border-gray-300 rounded-l-md focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                                            />
                                            <div class="absolute inset-y-0 right-0 px-3 flex items-center pointer-events-none">
                                                <i class="fas fa-building text-gray-400"></i>
                                            </div>
                                        </div>
                                        <button 
                                            @click="showAccountLookup = true" 
                                            type="button" 
                                            class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-3 rounded-r-md hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm"
                                        >
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- MC S# -->
                            <div class="col-span-1">
                                <label class="block text-sm font-medium text-indigo-700 mb-2 flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full mr-2 flex items-center justify-center shadow-sm">
                                        <i class="fas fa-id-card text-white text-sm"></i>
                                    </div>
                                    MCS#:
                                </label>
                                <div class="relative ml-10">
                                    <div class="flex">
                                        <div class="relative flex-grow">
                                            <input 
                                                type="text" 
                                                v-model="mcSequence"
                                                placeholder="Enter MC Sequence"
                                                class="w-full pl-3 pr-10 py-2 border border-gray-300 rounded-l-md focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                                            />
                                            <div class="absolute inset-y-0 right-0 px-3 flex items-center pointer-events-none">
                                                <i class="fas fa-id-card text-gray-400"></i>
                                            </div>
                                        </div>
                                        <button 
                                            @click="showMcLookup = true" 
                                            type="button" 
                                            class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-3 rounded-r-md hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm"
                                        >
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-4 mt-8">
                            <button 
                                @click="resetFilters"
                                class="px-6 py-2.5 border border-gray-300 rounded-md bg-white hover:bg-gray-50 text-gray-700 transition-all duration-300 shadow-sm flex items-center relative overflow-hidden group"
                            >
                                <span class="absolute inset-0 bg-gray-100 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                                <div class="relative z-10 flex items-center">
                                    <div class="w-5 h-5 mr-2 flex items-center justify-center">
                                        <i class="fas fa-undo text-indigo-500"></i>
                                    </div>
                                    <span>Reset Filters</span>
                                </div>
                            </button>
                            <button 
                                @click="searchLogs"
                                class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-md transition-all duration-300 shadow-md flex items-center relative overflow-hidden group"
                            >
                                <span class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity"></span>
                                <div class="relative z-10 flex items-center">
                                    <div class="w-5 h-5 mr-2 flex items-center justify-center">
                                        <i class="fas fa-search text-white"></i>
                                    </div>
                                    <span>Search</span>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Results Section -->
                <div v-if="showResults" class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="mr-3 bg-white bg-opacity-20 p-2 rounded-full shadow-inner relative overflow-hidden">
                                    <div class="absolute -top-1 -right-1 w-4 h-4 bg-blue-300 opacity-30 rounded-full"></div>
                                    <div class="absolute -bottom-1 -left-1 w-3 h-3 bg-yellow-300 opacity-30 rounded-full"></div>
                                    <i class="fas fa-clipboard-list text-xl relative z-10"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold">Maintenance Log Results</h2>
                                    <p class="text-xs text-blue-100 opacity-80">Found {{ filteredLogs.length }} maintenance log entries</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="relative">
                                    <input 
                                        type="text" 
                                        v-model="tableSearch" 
                                        placeholder="Search results..." 
                                        class="pl-8 pr-4 py-1 rounded-md text-gray-700 text-sm border border-white border-opacity-20 bg-white bg-opacity-20 focus:bg-white focus:text-gray-800 focus:border-white"
                                    />
                                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                        <i class="fas fa-search text-white text-opacity-70 text-sm"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th @click="sortTable('created_at')" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                            <div class="flex items-center">
                                                <span>Date/Time</span>
                                                <i v-if="sortColumn === 'created_at'" :class="['ml-1 fas', sortDirection === 'asc' ? 'fa-sort-up' : 'fa-sort-down']"></i>
                                            </div>
                                        </th>
                                        <th @click="sortTable('user_id')" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                            <div class="flex items-center">
                                                <span>User ID</span>
                                                <i v-if="sortColumn === 'user_id'" :class="['ml-1 fas', sortDirection === 'asc' ? 'fa-sort-up' : 'fa-sort-down']"></i>
                                            </div>
                                        </th>
                                        <th @click="sortTable('customer_name')" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                            <div class="flex items-center">
                                                <span>Customer</span>
                                                <i v-if="sortColumn === 'customer_name'" :class="['ml-1 fas', sortDirection === 'asc' ? 'fa-sort-up' : 'fa-sort-down']"></i>
                                            </div>
                                        </th>
                                        <th @click="sortTable('mc_seq')" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                            <div class="flex items-center">
                                                <span>MC S#</span>
                                                <i v-if="sortColumn === 'mc_seq'" :class="['ml-1 fas', sortDirection === 'asc' ? 'fa-sort-up' : 'fa-sort-down']"></i>
                                            </div>
                                        </th>
                                        <th @click="sortTable('action')" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                            <div class="flex items-center">
                                                <span>Action</span>
                                                <i v-if="sortColumn === 'action'" :class="['ml-1 fas', sortDirection === 'asc' ? 'fa-sort-up' : 'fa-sort-down']"></i>
                                            </div>
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Details
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="(log, index) in filteredLogs" :key="index" class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div class="font-medium">{{ formatDate(log.created_at) }}</div>
                                            <div class="text-gray-500">{{ formatTime(log.created_at) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ log.user_id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ log.customer_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ log.mc_seq }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span :class="{
                                                'px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full': true,
                                                'bg-green-100 text-green-800': log.action === 'create' || log.action === 'approve',
                                                'bg-blue-100 text-blue-800': log.action === 'update',
                                                'bg-yellow-100 text-yellow-800': log.action === 'reactive',
                                                'bg-red-100 text-red-800': log.action === 'obsolate'
                                            }">
                                                {{ capitalize(log.action) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            {{ log.details }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- No Results Feedback -->
                <div v-if="hasSearched && !showResults" class="bg-white rounded-xl p-10 shadow-lg text-center mb-6">
                    <div class="flex flex-col items-center justify-center space-y-4">
                        <div class="w-20 h-20 bg-gradient-to-r from-indigo-100 to-purple-100 rounded-full flex items-center justify-center shadow-inner relative overflow-hidden">
                            <div class="absolute inset-0 bg-white opacity-60 rounded-full transform scale-0.8"></div>
                            <i class="fas fa-search text-indigo-500 text-2xl relative z-10"></i>
                        </div>
                        <h3 class="text-xl font-medium text-gray-800">No Results Found</h3>
                        <p class="text-gray-600 max-w-md">We couldn't find any maintenance logs matching your search criteria. Try adjusting your filters or search terms.</p>
                        <button 
                            @click="resetAllFilters" 
                            class="mt-4 px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-md transition-all duration-300 shadow-md flex items-center relative overflow-hidden group"
                        >
                            <span class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity"></span>
                            <div class="relative z-10 flex items-center">
                                <i class="fas fa-undo mr-2"></i>
                                <span>Reset All Filters</span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

// Form data
const startDate = ref(formattedDate(new Date()));
const endDate = ref(formattedDate(new Date()));
const startTimeHour = ref(0);
const startTimeMinute = ref(0);
const startTimeSecond = ref(0);
const endTimeHour = ref(23);
const endTimeMinute = ref(59);
const endTimeSecond = ref(59);
const userId = ref('');
const accountCode = ref('');
const mcSequence = ref('');

// UI state
const showUserLookup = ref(false);
const showAccountLookup = ref(false);
const showMcLookup = ref(false);
const loading = ref(false);
const showResults = ref(false);
const hasSearched = ref(false);
const tableSearch = ref('');
const actionFilter = ref('all');
const sortColumn = ref('created_at');
const sortDirection = ref('desc');

// Sample data (replace with API call)
const maintenanceLogs = ref([
    {
        created_at: '2023-08-15T09:15:30',
        user_id: 'admin',
        customer_name: 'Acme Corporation',
        mc_seq: 'MC001',
        action: 'create',
        details: 'Created new master card'
    },
    {
        created_at: '2023-08-16T14:22:45',
        user_id: 'manager',
        customer_name: 'Acme Corporation',
        mc_seq: 'MC001',
        action: 'update',
        details: 'Updated customer information'
    },
    {
        created_at: '2023-08-17T11:05:12',
        user_id: 'supervisor',
        customer_name: 'Acme Corporation',
        mc_seq: 'MC001',
        action: 'approve',
        details: 'Approved master card'
    },
    {
        created_at: '2023-09-05T16:45:22',
        user_id: 'admin',
        customer_name: 'Globex Industries',
        mc_seq: 'MC002',
        action: 'create',
        details: 'Created new master card'
    },
    {
        created_at: '2023-09-10T10:35:18',
        user_id: 'supervisor',
        customer_name: 'Globex Industries',
        mc_seq: 'MC002',
        action: 'obsolate',
        details: 'Master card marked as obsolete - Product discontinued'
    },
    {
        created_at: '2023-10-02T09:12:40',
        user_id: 'manager',
        customer_name: 'Globex Industries',
        mc_seq: 'MC002',
        action: 'reactive',
        details: 'Master card reactivated - Product reinstated'
    }
]);

// Computed filtered logs based on search and filters
const filteredLogs = computed(() => {
    let result = [...maintenanceLogs.value];
    
    // Apply action filter
    if (actionFilter.value !== 'all') {
        result = result.filter(log => log.action === actionFilter.value);
    }
    
    // Apply table search
    if (tableSearch.value.trim() !== '') {
        const searchTerm = tableSearch.value.toLowerCase();
        result = result.filter(log => 
            log.user_id.toLowerCase().includes(searchTerm) ||
            log.customer_name.toLowerCase().includes(searchTerm) ||
            log.mc_seq.toLowerCase().includes(searchTerm) ||
            log.details.toLowerCase().includes(searchTerm)
        );
    }
    
    // Apply sorting
    result.sort((a, b) => {
        let valA = a[sortColumn.value];
        let valB = b[sortColumn.value];
        
        if (typeof valA === 'string') valA = valA.toLowerCase();
        if (typeof valB === 'string') valB = valB.toLowerCase();
        
        if (valA < valB) return sortDirection.value === 'asc' ? -1 : 1;
        if (valA > valB) return sortDirection.value === 'asc' ? 1 : -1;
        return 0;
    });
    
    return result;
});

// Helper functions
function formattedDate(date) {
    const year = date.getFullYear();
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const day = date.getDate().toString().padStart(2, '0');
    return `${year}-${month}-${day}`;
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString();
}

function formatTime(dateString) {
    const date = new Date(dateString);
    return date.toLocaleTimeString();
}

function formatDateTime(dateString) {
    const date = new Date(dateString);
    return date.toLocaleString();
}

function capitalize(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

// Sort table function
function sortTable(column) {
    if (sortColumn.value === column) {
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortColumn.value = column;
        sortDirection.value = 'asc';
    }
}

// Event handlers
function resetFilters() {
    startDate.value = formattedDate(new Date());
    endDate.value = formattedDate(new Date());
    startTimeHour.value = 0;
    startTimeMinute.value = 0;
    startTimeSecond.value = 0;
    endTimeHour.value = 23;
    endTimeMinute.value = 59;
    endTimeSecond.value = 59;
    userId.value = '';
    accountCode.value = '';
    mcSequence.value = '';
}

function resetAllFilters() {
    resetFilters();
    tableSearch.value = '';
    actionFilter.value = 'all';
    hasSearched.value = false;
    showResults.value = false;
}

function searchLogs() {
    loading.value = true;
    showResults.value = true;
    hasSearched.value = true;
    
    // Simulate API call
    setTimeout(() => {
        loading.value = false;
        
        // Here you would filter the logs based on the search criteria
        // For now, we're just using the sample data
    }, 1000);
}

function printPage() {
    window.print();
}
</script>

<style scoped>
@media print {
    button {
        display: none;
    }
    
    input, select {
        border: none !important;
        background: white !important;
    }
    
    .bg-gray-100 {
        background-color: white !important;
        padding: 0 !important;
    }
    
    .from-blue-600, .to-indigo-700 {
        background: white !important;
        color: black !important;
    }
    
    .text-white {
        color: black !important;
    }
    
    .shadow-lg, .shadow-xl, .shadow-md {
        box-shadow: none !important;
    }
}

/* Hover animations */
.transition-all {
    transition: all 0.3s ease;
}

/* Pulse effect for certain elements */
@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(147, 51, 234, 0.5);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(147, 51, 234, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(147, 51, 234, 0);
    }
}

/* Custom animation for slow ping effect */
@keyframes ping-slow {
    0% {
        transform: scale(1);
        opacity: 0.5;
    }
    50% {
        transform: scale(1.8);
        opacity: 0.15;
    }
    100% {
        transform: scale(1);
        opacity: 0.5;
    }
}

.animate-ping-slow {
    animation: ping-slow 3s ease-in-out infinite;
}

.animation-delay-500 {
    animation-delay: 1.5s;
}

/* Text shadow for headings */
.text-shadow {
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Subtle hover glow effect */
.hover-glow:hover {
    box-shadow: 0 0 15px rgba(147, 51, 234, 0.3);
}

/* Gradient text effect */
.gradient-text {
    background: linear-gradient(to right, #9333ea, #ec4899);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
</style>
