<template>
  <AppLayout title="View & Print Roll Trim By Corrugator">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        View & Print Roll Trim By Corrugator
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <!-- Filter and Print Controls -->
          <div class="flex flex-col md:flex-row justify-between mb-6 space-y-4 md:space-y-0">
            <div class="flex flex-col md:flex-row md:space-x-4 space-y-4 md:space-y-0">
              <!-- Corrugator Filter -->
              <div>
                <label for="corrugator-filter" class="block text-sm font-medium text-gray-700">Corrugator</label>
                <select
                  id="corrugator-filter"
                  v-model="filters.corrugator"
                  class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                >
                  <option value="">All Corrugators</option>
                  <option v-for="corrugator in corrugators" :key="corrugator" :value="corrugator">
                    {{ corrugator }}
                  </option>
                </select>
              </div>
              
              <!-- Flute Filter -->
              <div>
                <label for="flute-filter" class="block text-sm font-medium text-gray-700">Flute Type</label>
                <select
                  id="flute-filter"
                  v-model="filters.fluteCode"
                  class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                >
                  <option value="">All Flutes</option>
                  <option v-for="flute in flutes" :key="flute.code" :value="flute.code">
                    {{ flute.name }} ({{ flute.code }})
                  </option>
                </select>
              </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex space-x-2">
              <button
                @click="printReport"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-600 transition"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print
              </button>
              <Link
                :href="route('vue.standard-formula.setup-roll-trim-by-corrugator')"
                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-200 active:bg-gray-600 transition"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back
              </Link>
            </div>
          </div>

          <!-- Loading Indicator -->
          <div v-if="loading" class="flex justify-center items-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-500"></div>
          </div>

          <!-- Error Message -->
          <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ error }}</span>
          </div>

          <!-- Printable Report -->
          <div id="printable-content" class="overflow-x-auto">
            <div class="mb-6 print:mb-8">
              <h1 class="text-2xl font-bold text-center print:text-3xl">Roll Trim By Corrugator Report</h1>
              <p class="text-center text-gray-600 print:text-lg">Generated on {{ formattedDate }}</p>
            </div>

            <!-- Group by Corrugator -->
            <div v-for="corrugator in groupedData" :key="corrugator.name" class="mb-8 print:mb-10">
              <h2 class="text-xl font-semibold mb-4 bg-gray-100 p-2 print:bg-gray-200">
                Corrugator: {{ corrugator.name }}
              </h2>
              
              <table class="min-w-full divide-y divide-gray-200 border">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">
                      Flute Code
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">
                      Flute Name
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">
                      Trim Value (cm)
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="trim in corrugator.trims" :key="trim.flute_code" class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap border">
                      {{ trim.flute_code }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap border">
                      {{ trim.paper_flute ? trim.paper_flute.name : 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap border">
                      {{ trim.trim_value }}
                    </td>
                  </tr>
                  <tr v-if="corrugator.trims.length === 0">
                    <td colspan="3" class="px-6 py-4 text-center text-gray-500 border">
                      No roll trim data found for this corrugator.
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div v-if="Object.keys(groupedData).length === 0" class="text-center py-8 text-gray-500">
              No roll trim data found matching your filters.
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import { defineComponent, ref, computed, onMounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import axios from 'axios'

export default defineComponent({
  components: {
    AppLayout,
    Link
  },
  
  setup() {
    // Data
    const rollTrims = ref([])
    const flutes = ref([])
    const loading = ref(true)
    const error = ref(null)
    
    // Fixed list of corrugators
    const corrugators = ['BHS', 'FOSBER', 'MHI']
    
    // Filters
    const filters = ref({
      corrugator: '',
      fluteCode: ''
    })
    
    // Computed
    const filteredRollTrims = computed(() => {
      let filtered = [...rollTrims.value]
      
      if (filters.value.corrugator) {
        filtered = filtered.filter(trim => trim.corrugator_name === filters.value.corrugator)
      }
      
      if (filters.value.fluteCode) {
        filtered = filtered.filter(trim => trim.flute_code === filters.value.fluteCode)
      }
      
      return filtered
    })
    
    // Group data by corrugator
    const groupedData = computed(() => {
      const grouped = {}
      
      // Initialize groups for all corrugators
      corrugators.forEach(corrugator => {
        grouped[corrugator] = {
          name: corrugator,
          trims: []
        }
      })
      
      // Populate groups with filtered data
      filteredRollTrims.value.forEach(trim => {
        if (grouped[trim.corrugator_name]) {
          grouped[trim.corrugator_name].trims.push(trim)
        }
      })
      
      // Filter out empty groups if a specific corrugator is not selected
      if (!filters.value.corrugator) {
        Object.keys(grouped).forEach(key => {
          if (grouped[key].trims.length === 0) {
            delete grouped[key]
          }
        })
      }
      
      return grouped
    })
    
    // Formatted current date
    const formattedDate = computed(() => {
      const now = new Date()
      return new Intl.DateTimeFormat('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      }).format(now)
    })
    
    // Methods
    const fetchData = async () => {
      loading.value = true
      error.value = null
      
      try {
        // Fetch roll trims
        const rollTrimsResponse = await axios.get('/api/roll-trim-by-corrugator')
        if (rollTrimsResponse.data.status === 'success') {
          rollTrims.value = rollTrimsResponse.data.data
        } else {
          throw new Error(rollTrimsResponse.data.message || 'Failed to fetch roll trim data')
        }
        
        // Fetch flutes
        const flutesResponse = await axios.get('/api/roll-trim-by-corrugator/flutes')
        if (flutesResponse.data.status === 'success') {
          flutes.value = flutesResponse.data.data
        } else {
          throw new Error(flutesResponse.data.message || 'Failed to fetch flute data')
        }
      } catch (err) {
        error.value = err.message || 'An error occurred while fetching data'
        console.error('Error fetching data:', err)
      } finally {
        loading.value = false
      }
    }
    
    const printReport = () => {
      window.print()
    }
    
    // Initialize
    onMounted(() => {
      fetchData()
    })
    
    return {
      rollTrims,
      flutes,
      corrugators,
      loading,
      error,
      filters,
      filteredRollTrims,
      groupedData,
      formattedDate,
      printReport
    }
  }
})
</script>

<style>
@media print {
  @page {
    size: A4;
    margin: 1cm;
  }
  
  body {
    font-size: 12pt;
  }
  
  /* Hide non-printable elements */
  button, select, .print:hidden {
    display: none !important;
  }
  
  /* Ensure tables fit on page */
  table {
    page-break-inside: avoid;
    width: 100%;
  }
  
  /* Add page breaks between corrugator sections */
  .print\:mb-10 {
    page-break-after: always;
  }
  
  /* Last section should not have a page break */
  .print\:mb-10:last-child {
    page-break-after: avoid;
  }
}
</style> 