<template>
  <div v-if="isOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full mx-4 max-h-[90vh] flex flex-col overflow-hidden">
      <!-- Header -->
      <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white px-6 py-4">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-3">
            <div class="p-2 bg-white/20 rounded-lg">
              <i class="fas fa-layer-group text-xl"></i>
            </div>
            <div>
              <h2 class="text-xl font-semibold">Vehicle Class Table</h2>
              <p class="text-blue-100 text-sm">Browse and select a vehicle class</p>
            </div>
          </div>
          <button
            @click="$emit('close')"
            class="text-white hover:text-gray-100 rounded-full p-2 hover:bg-white/10 transition-colors"
          >
            <i class="fas fa-times text-xl"></i>
          </button>
        </div>
      </div>

      <!-- Search Section -->
      <div class="p-5 border-b border-gray-200 bg-slate-50">
        <div class="grid grid-cols-1 gap-4">
          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Vehicle Class Code</label>
            <div class="relative">
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Search by class code or description..."
                class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm text-sm"
              >
              <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Main content -->
      <div class="flex-1 overflow-y-auto bg-white">
        <!-- Loading -->
        <div v-if="loading" class="flex items-center justify-center py-12">
          <div class="text-center">
            <i class="fas fa-spinner fa-spin text-4xl text-blue-600 mb-4"></i>
            <p class="text-gray-600">Loading vehicle classes...</p>
          </div>
        </div>

        <!-- Error -->
        <div v-else-if="errorMessage" class="p-6">
          <div class="bg-red-50 border border-red-200 rounded-xl p-4 shadow-sm">
            <div class="flex items-center">
              <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
              <span class="text-red-800">{{ errorMessage }}</span>
            </div>
          </div>
        </div>

        <!-- Table -->
        <div v-else class="p-5 overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 rounded-xl overflow-hidden">
            <thead class="bg-slate-50">
              <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Class Code</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Description</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="filteredClasses.length === 0">
                <td colspan="2" class="px-4 py-10 text-center text-gray-500">
                  <div class="flex flex-col items-center">
                    <i class="fas fa-layer-group text-4xl mb-2 text-gray-300"></i>
                    <p class="text-sm">No vehicle classes found</p>
                  </div>
                </td>
              </tr>
              <tr
                v-for="cls in filteredClasses"
                :key="cls.id"
                class="hover:bg-gray-50 cursor-pointer"
                :class="{ 'bg-blue-50': selectedClass?.id === cls.id }"
                @click="selectClass(cls)"
              >
                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                  {{ cls.VEHICLE_CLASS_CODE }}
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                  {{ cls.DESCRIPTION }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Footer -->
      <div class="px-6 py-4 border-t border-gray-200 bg-slate-50 flex justify-end space-x-3">
        <button
          @click="$emit('close')"
          class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors text-sm font-medium"
        >
          <span class="flex items-center">
            <i class="fas fa-times mr-2"></i>
            Cancel
          </span>
        </button>
        <button
          @click="confirmSelection"
          :disabled="!selectedClass"
          class="px-4 py-2 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:via-indigo-700 hover:to-purple-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors text-sm font-medium"
        >
          <span class="flex items-center justify-center">
            <i class="fas fa-check-circle mr-2"></i>
            Select Class
          </span>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import axios from 'axios'

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['close', 'select'])

const classes = ref([])
const selectedClass = ref(null)
const loading = ref(false)
const errorMessage = ref('')
const searchQuery = ref('')

const filteredClasses = computed(() => {
  // Only show active vehicle classes (STATUS = 'A') in the modal
  const activeClasses = classes.value.filter(cls => {
    const status = (cls.STATUS || 'A').toString().toUpperCase()
    return status === 'A'
  })

  if (!searchQuery.value) return activeClasses

  const q = searchQuery.value.toLowerCase()
  return activeClasses.filter(cls => {
    const code = (cls.VEHICLE_CLASS_CODE || '').toLowerCase()
    const desc = (cls.DESCRIPTION || '').toLowerCase()
    return code.includes(q) || desc.includes(q)
  })
})

const loadClasses = async () => {
  loading.value = true
  errorMessage.value = ''
  try {
    // Request only active vehicle classes from the API
    const response = await axios.get('/api/vehicle-classes', {
      params: { status: 'A' }
    })
    if (response.data && response.data.success) {
      classes.value = response.data.data || []
    } else {
      errorMessage.value = response.data?.message || 'Failed to load vehicle classes'
      classes.value = []
    }
  } catch (e) {
    console.error('Error loading vehicle classes', e)
    errorMessage.value = 'Error loading vehicle classes. Please try again.'
    classes.value = []
  } finally {
    loading.value = false
  }
}

const selectClass = (cls) => {
  // Extra safety: prevent selecting obsolete classes if they ever appear
  const status = (cls.STATUS || 'A').toString().toUpperCase()
  if (status !== 'A') {
    return
  }

  selectedClass.value = cls
}

const confirmSelection = () => {
  if (selectedClass.value) {
    emit('select', selectedClass.value)
    emit('close')
  }
}

watch(
  () => props.isOpen,
  (isOpen) => {
    if (isOpen) {
      loadClasses()
    } else {
      selectedClass.value = null
      searchQuery.value = ''
      errorMessage.value = ''
    }
  }
)

onMounted(() => {
  if (props.isOpen) {
    loadClasses()
  }
})
</script>

<style scoped>
</style>

