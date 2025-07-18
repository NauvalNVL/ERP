<template>
  <AppLayout :header="'View and Print Units'">
    <Head title="View and Print Units" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-print mr-3"></i> View and Print Units
      </h2>
      <p class="text-blue-100">Print unit lists for reference</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-md p-6 mb-6">
      <!-- Action Buttons -->
      <div class="mb-6 flex justify-between items-center">
        <div class="flex items-center space-x-3">
          <Link 
            href="/material-management/system-requirement/inventory-setup/unit"
            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
          >
            <i class="fas fa-arrow-left mr-2"></i> Back to Units
          </Link>

          <button 
            @click="printReport" 
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700"
          >
            <i class="fas fa-print mr-2"></i> Print
          </button>
        </div>
      </div>

      <!-- Print Area -->
      <div id="printable-area" class="bg-white p-6 border border-gray-200 rounded-lg">
        <div class="text-center mb-6">
          <h1 class="text-2xl font-bold">Unit Master List</h1>
          <p class="text-gray-500">Date Generated: {{ new Date().toLocaleDateString() }}</p>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  No.
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Unit Code
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Short Name
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Full Name
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Status
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="loading">
                <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                  Loading units...
                </td>
              </tr>
              <tr v-else-if="units.length === 0">
                <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                  No units found.
                </td>
              </tr>
              <tr v-for="(unit, index) in units" :key="unit.code">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ index + 1 }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  {{ unit.code }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ unit.short_name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ unit.full_name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  <span 
                    :class="unit.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                  >
                    {{ unit.is_active ? 'Active' : 'Inactive' }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        
        <!-- Footer -->
        <div class="mt-8 text-sm text-gray-500 text-center">
          <p>-- End of Report --</p>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

const units = ref([]);
const loading = ref(true);

const fetchUnits = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/material-management/units');
    units.value = response.data;
  } catch (error) {
    console.error('Error fetching units:', error);
  } finally {
    loading.value = false;
  }
};

const printReport = () => {
  window.print();
};

onMounted(() => {
  fetchUnits();
});
</script>

<style>
@media print {
  body * {
    visibility: hidden;
  }
  
  #printable-area, #printable-area * {
    visibility: visible;
  }
  
  #printable-area {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
  }
  
  button, .hidden-print {
    display: none !important;
  }
}
</style> 