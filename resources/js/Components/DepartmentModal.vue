<template>
  <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="$emit('close')"></div>
    
    <!-- Modal -->
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative w-full max-w-2xl bg-white rounded-lg shadow-xl">
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">
            <i class="fas fa-building mr-2 text-blue-600"></i>
            Select Department
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
              placeholder="Search departments..."
              class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
            >
            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
          </div>
        </div>

        <!-- Department List -->
        <div class="max-h-96 overflow-y-auto">
          <div v-if="loading" class="p-6 text-center">
            <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
            <p class="mt-2 text-gray-500">Loading departments...</p>
          </div>
          
          <div v-else-if="filteredDepartments.length === 0" class="p-6 text-center">
            <i class="fas fa-inbox text-2xl text-gray-400"></i>
            <p class="mt-2 text-gray-500">No departments found</p>
          </div>
          
          <div v-else>
            <div
              v-for="department in filteredDepartments"
              :key="department.code"
              @click="selectDepartment(department)"
              class="p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-colors"
            >
              <div class="flex items-center justify-between">
                <div>
                  <h4 class="font-medium text-gray-900">{{ department.name }}</h4>
                  <p class="text-sm text-gray-500">Code: {{ department.code }}</p>
                </div>
                <i class="fas fa-chevron-right text-gray-400"></i>
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

// Sample departments data
const departments = ref([
  { code: 'IT', name: 'Information Technology' },
  { code: 'HR', name: 'Human Resources' },
  { code: 'FIN', name: 'Finance' },
  { code: 'OPS', name: 'Operations' },
  { code: 'MKT', name: 'Marketing' },
  { code: 'SALES', name: 'Sales' },
  { code: 'PROD', name: 'Production' },
  { code: 'QC', name: 'Quality Control' },
  { code: 'LOG', name: 'Logistics' },
  { code: 'R&D', name: 'Research & Development' },
  { code: 'LEGAL', name: 'Legal' },
  { code: 'ADMIN', name: 'Administration' },
])

// Computed
const filteredDepartments = computed(() => {
  if (!searchQuery.value) {
    return departments.value
  }
  
  const query = searchQuery.value.toLowerCase()
  return departments.value.filter(dept => 
    dept.code.toLowerCase().includes(query) ||
    dept.name.toLowerCase().includes(query)
  )
})

// Methods
const selectDepartment = (department) => {
  emit('select', department)
}

// Watchers
watch(() => props.show, (newVal) => {
  if (newVal) {
    searchQuery.value = ''
  }
})
</script>
