<template>
  <AppLayout title="View & Print Report Group">
    <Head title="View & Print Report Group" />

    <div class="container mx-auto p-4">
      <!-- Header -->
      <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-t-lg shadow-md">
        <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
          <i class="fas fa-print mr-3"></i> View & Print Report Group
        </h2>
        <p class="text-blue-100">View and print report group data</p>
      </div>

      <!-- Controls -->
      <div class="bg-white rounded-b-lg shadow-md p-6 mb-6">
        <div class="flex flex-col lg:flex-row gap-4 mb-6">
          <div class="flex-1">
            <div class="relative">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                <i class="fas fa-search"></i>
              </span>
              <input type="text" v-model="search" placeholder="Search by code or name..."
                class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>
          </div>
          <div class="flex gap-2">
            <button @click="printData" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow flex items-center">
              <i class="fas fa-print mr-2"></i> Print
            </button>
            <button @click="goToManagement" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg shadow flex items-center">
              <i class="fas fa-arrow-left mr-2"></i> Back
            </button>
          </div>
        </div>

        <!-- Report Group Table -->
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th @click="sortTable('code')" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                    Code <i class="fas fa-sort ml-1"></i>
                  </th>
                  <th @click="sortTable('name')" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                    Name <i class="fas fa-sort ml-1"></i>
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-if="loading" class="animate-pulse">
                  <td colspan="2" class="px-6 py-4 text-center text-gray-500">
                    <div class="flex justify-center items-center space-x-2">
                      <i class="fas fa-spinner fa-spin"></i>
                      <span>Loading report groups...</span>
                    </div>
                  </td>
                </tr>
                <tr v-else-if="filteredReportGroups.length === 0" class="hover:bg-gray-50">
                  <td colspan="2" class="px-6 py-4 text-center text-gray-500">
                    No report groups found. Try adjusting your search.
                  </td>
                </tr>
                <tr v-for="group in filteredReportGroups" :key="group.id" class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ group.code }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ group.name }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between mt-4 text-sm text-gray-600">
          <div>
            <p>Showing {{ filteredReportGroups.length }} of {{ reportGroups.length }} report groups</p>
          </div>
          <div class="flex items-center space-x-2">
            <button v-if="isPrintable" @click="printData" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded flex items-center space-x-1">
              <i class="fas fa-print"></i>
              <span>Print</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Print Preview Section (hidden in normal view, visible when printing) -->
      <div id="printSection" class="hidden print:block">
        <div class="text-center mb-8">
          <h1 class="text-2xl font-bold">Report Group List</h1>
          <p class="text-gray-600">Printed on: {{ new Date().toLocaleString() }}</p>
        </div>

        <table class="w-full border-collapse">
          <thead>
            <tr>
              <th class="border border-gray-300 px-4 py-2 bg-gray-100 text-left">Code</th>
              <th class="border border-gray-300 px-4 py-2 bg-gray-100 text-left">Name</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="group in filteredReportGroups" :key="group.id">
              <td class="border border-gray-300 px-4 py-2">{{ group.code }}</td>
              <td class="border border-gray-300 px-4 py-2">{{ group.name }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import { ref, computed, onMounted } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

export default {
  components: {
    AppLayout,
    Head
  },
  setup() {
    const reportGroups = ref([]);
    const loading = ref(true);
    const search = ref('');
    const sortField = ref('code');
    const sortDirection = ref('asc');
    const isPrintable = ref(true);

    // Fetch report groups on mount
    const fetchReportGroups = async () => {
      try {
        const response = await fetch('/material-management/system-requirement/report-groups/list');
        const result = await response.json();
        
        if (result.status === 'success') {
          reportGroups.value = result.data;
        } else {
          console.error('Failed to load report groups');
        }
      } catch (error) {
        console.error('Error fetching report groups:', error);
      } finally {
        loading.value = false;
      }
    };

    // Filtered report groups based on search
    const filteredReportGroups = computed(() => {
      if (!reportGroups.value) return [];
      
      let filtered = reportGroups.value;
      
      // Apply search filter
      if (search.value) {
        const searchTerm = search.value.toLowerCase();
        filtered = filtered.filter(group => 
          group.code.toLowerCase().includes(searchTerm) || 
          group.name.toLowerCase().includes(searchTerm)
        );
      }
      
      // Apply sorting
      return [...filtered].sort((a, b) => {
        const aValue = a[sortField.value]?.toLowerCase() || '';
        const bValue = b[sortField.value]?.toLowerCase() || '';
        
        if (sortDirection.value === 'asc') {
          return aValue.localeCompare(bValue);
        } else {
          return bValue.localeCompare(aValue);
        }
      });
    });

    // Sort table by field
    const sortTable = (field) => {
      if (sortField.value === field) {
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
      } else {
        sortField.value = field;
        sortDirection.value = 'asc';
      }
    };

    // Go back to management page
    const goToManagement = () => {
      router.get('/material-management/system-requirement/inventory-setup/report-group');
    };

    // Print the data
    const printData = () => {
      window.print();
    };

    onMounted(fetchReportGroups);

    return {
      reportGroups,
      loading,
      search,
      filteredReportGroups,
      sortField,
      sortDirection,
      sortTable,
      goToManagement,
      printData,
      isPrintable
    };
  }
};
</script>

<style scoped>
/* Print styles */
@media print {
  button, input, .print\:hidden {
    display: none !important;
  }
}

/* Hover animation */
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
  width: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track {
  background: transparent;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
  background-color: rgba(156, 163, 175, 0.5);
  border-radius: 20px;
}
</style> 