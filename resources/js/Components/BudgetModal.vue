<template>
  <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="$emit('close')"></div>
    
    <!-- Modal -->
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative w-full max-w-3xl bg-white rounded-lg shadow-xl">
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">
            <i class="fas fa-money-bill-wave mr-2 text-green-600"></i>
            Select Budget Code (AP AC#)
          </h3>
          <button @click="$emit('close')" class="text-gray-400 hover:text-gray-500">
            <i class="fas fa-times text-xl"></i>
          </button>
        </div>

        <!-- Search -->
        <div class="p-4 border-b border-gray-200">
          <div class="relative">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search budget codes..."
              class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
            >
            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
          </div>
        </div>

        <!-- Budget List -->
        <div class="max-h-96 overflow-y-auto">
          <div v-if="loading" class="p-6 text-center">
            <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
            <p class="mt-2 text-gray-500">Loading budget codes...</p>
          </div>
          
          <div v-else-if="filteredBudgets.length === 0" class="p-6 text-center">
            <i class="fas fa-inbox text-2xl text-gray-400"></i>
            <p class="mt-2 text-gray-500">No budget codes found</p>
          </div>
          
          <div v-else>
            <div
              v-for="budget in filteredBudgets"
              :key="budget.code"
              @click="selectBudget(budget)"
              class="p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-colors"
            >
              <div class="flex items-center justify-between">
                <div class="flex-1">
                  <div class="flex items-center space-x-4">
                    <div>
                      <h4 class="font-medium text-gray-900">{{ budget.code }}</h4>
                      <p class="text-sm text-gray-600">{{ budget.description }}</p>
                    </div>
                    <div class="text-right">
                      <p class="text-sm font-medium text-gray-900">{{ formatCurrency(budget.allocated_amount) }}</p>
                      <p class="text-xs text-gray-500">Allocated</p>
                    </div>
                    <div class="text-right">
                      <p class="text-sm font-medium" :class="getRemainingClass(budget.remaining_amount)">
                        {{ formatCurrency(budget.remaining_amount) }}
                      </p>
                      <p class="text-xs text-gray-500">Remaining</p>
                    </div>
                    <div class="text-center">
                      <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                            :class="getStatusClass(budget.status)">
                        {{ budget.status }}
                      </span>
                    </div>
                  </div>
                </div>
                <i class="fas fa-chevron-right text-gray-400 ml-4"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="p-4 border-t border-gray-200 bg-gray-50 rounded-b-lg">
          <div class="flex justify-end">
            <button @click="$emit('close')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['close', 'select'])

// State
const loading = ref(false)
const searchQuery = ref('')

// Sample budget codes data
const budgets = ref([
  {
    code: 'BGT-IT-2025',
    description: 'IT Department Budget 2025',
    allocated_amount: 500000000,
    remaining_amount: 350000000,
    status: 'Active'
  },
  {
    code: 'BGT-HR-2025',
    description: 'Human Resources Budget 2025',
    allocated_amount: 200000000,
    remaining_amount: 150000000,
    status: 'Active'
  },
  {
    code: 'BGT-OPS-2025',
    description: 'Operations Budget 2025',
    allocated_amount: 1000000000,
    remaining_amount: 800000000,
    status: 'Active'
  },
  {
    code: 'BGT-MKT-2025',
    description: 'Marketing Budget 2025',
    allocated_amount: 300000000,
    remaining_amount: 100000000,
    status: 'Active'
  },
  {
    code: 'BGT-FIN-2025',
    description: 'Finance Budget 2025',
    allocated_amount: 150000000,
    remaining_amount: 120000000,
    status: 'Active'
  },
  {
    code: 'BGT-EMRG-2025',
    description: 'Emergency Fund 2025',
    allocated_amount: 100000000,
    remaining_amount: 85000000,
    status: 'Active'
  },
  {
    code: 'BGT-CAP-2025',
    description: 'Capital Expenditure 2025',
    allocated_amount: 2000000000,
    remaining_amount: 1500000000,
    status: 'Active'
  },
  {
    code: 'BGT-MAINT-2025',
    description: 'Maintenance Budget 2025',
    allocated_amount: 250000000,
    remaining_amount: 180000000,
    status: 'Active'
  },
  {
    code: 'BGT-TRAIN-2025',
    description: 'Training & Development 2025',
    allocated_amount: 100000000,
    remaining_amount: 75000000,
    status: 'Active'
  },
  {
    code: 'BGT-MISC-2025',
    description: 'Miscellaneous Expenses 2025',
    allocated_amount: 50000000,
    remaining_amount: 30000000,
    status: 'Active'
  }
])

// Computed
const filteredBudgets = computed(() => {
  if (!searchQuery.value) {
    return budgets.value
  }
  
  const query = searchQuery.value.toLowerCase()
  return budgets.value.filter(budget => 
    budget.code.toLowerCase().includes(query) ||
    budget.description.toLowerCase().includes(query)
  )
})

// Methods
const selectBudget = (budget) => {
  emit('select', budget)
}

const formatCurrency = (value) => {
  if (!value) return 'Rp 0'
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(value)
}

const getRemainingClass = (amount) => {
  if (amount > 100000000) return 'text-green-600'
  if (amount > 50000000) return 'text-yellow-600'
  return 'text-red-600'
}

const getStatusClass = (status) => {
  const classes = {
    'Active': 'bg-green-100 text-green-800',
    'Inactive': 'bg-gray-100 text-gray-800',
    'Depleted': 'bg-red-100 text-red-800',
    'Frozen': 'bg-blue-100 text-blue-800'
  }
  return classes[status] || classes['Active']
}

// Watchers
watch(() => props.show, (newVal) => {
  if (newVal) {
    searchQuery.value = ''
  }
})
</script>
