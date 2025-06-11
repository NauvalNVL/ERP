<template>
  <Head title="Define SO Rough Cut" />
  <AppLayout header="Define SO Rough Cut">
    <div class="p-5">
      <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
          <div class="flex flex-col md:flex-row justify-between items-center">
            <div>
              <h2 class="text-xl font-bold text-white flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Define SO Rough Cut
              </h2>
              <p class="text-blue-100 mt-1">Configure Sales Order Rough Cut settings</p>
            </div>
          </div>
        </div>
        
        <div class="p-6">
          <!-- Period Section -->
          <div class="bg-gray-50 p-5 rounded-lg shadow-sm border border-gray-100 mb-6">
            <div class="flex items-center space-x-4">
              <label for="period_month" class="text-gray-700">Period:</label>
              <input type="number" id="period_month" v-model="form.periodMonth" class="w-20 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
              <input type="number" id="period_year" v-model="form.periodYear" class="w-20 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
            </div>
          </div>

          <!-- Target Screen Section -->
          <div class="bg-gray-50 p-5 rounded-lg shadow-sm border border-gray-100 relative">
            <h3 class="font-semibold text-gray-800 mb-4">Target Screen</h3>
            <div class="absolute top-5 right-5 flex space-x-2">
              <button type="button" class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">
                <i class="fas fa-power-off"></i>
              </button>
              <button type="button" class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">
                <i class="fas fa-redo-alt"></i>
              </button>
              <button type="button" class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">
                <i class="fas fa-save"></i>
              </button>
              <button type="button" class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">
                <i class="fas fa-file-alt"></i>
              </button>
            </div>

            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Day</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Target LM</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loaded LM</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Target Pcs</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loaded Pcs</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="(item, index) in tableData" :key="index">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ item.day }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      <input type="number" v-model="item.targetLM" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 font-bold">{{ item.loadedLM.toLocaleString() }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      <input type="number" v-model="item.targetPcs" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 font-bold">{{ item.loadedPcs.toLocaleString() }}</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="mt-4 text-right text-sm text-gray-600">
              Record: <span class="font-semibold">Add</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

export default {
  components: {
    Head,
    AppLayout,
  },
  data() {
    return {
      form: {
        periodMonth: 2,
        periodYear: 2021,
      },
      tableData: [
        { day: '01 Mon', targetLM: 0, loadedLM: 208394, targetPcs: 0, loadedPcs: 616637 },
        { day: '02 Tue', targetLM: 0, loadedLM: 358731, targetPcs: 0, loadedPcs: 919447 },
        { day: '03 Wed', targetLM: 0, loadedLM: 316846, targetPcs: 0, loadedPcs: 851448 },
        { day: '04 Thu', targetLM: 0, loadedLM: 251915, targetPcs: 0, loadedPcs: 905479 },
        { day: '05 Fri', targetLM: 0, loadedLM: 240199, targetPcs: 0, loadedPcs: 780537 },
        { day: '06 Sat', targetLM: 0, loadedLM: 124456, targetPcs: 0, loadedPcs: 413947 },
        { day: '07 Sun', targetLM: 0, loadedLM: 30160, targetPcs: 0, loadedPcs: 180000 },
        { day: '08 Mon', targetLM: 0, loadedLM: 305086, targetPcs: 0, loadedPcs: 963677 },
        { day: '09 Tue', targetLM: 0, loadedLM: 285170, targetPcs: 0, loadedPcs: 921595 },
        { day: '10 Wed', targetLM: 0, loadedLM: 261767, targetPcs: 0, loadedPcs: 796103 },
        { day: '11 Thu', targetLM: 0, loadedLM: 336814, targetPcs: 0, loadedPcs: 1067593 },
        { day: '12 Fri', targetLM: 0, loadedLM: 79888, targetPcs: 0, loadedPcs: 241548 },
      ],
    };
  },
  methods: {
    // You would typically have methods to load/save data from an API
    // For now, we'll keep it simple
  },
};
</script>

<style scoped>
/* Add any specific styles for this component here */
</style> 