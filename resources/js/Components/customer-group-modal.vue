<template>
  <div v-if="show" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-2/3 lg:w-3/4 max-w-4xl mx-auto">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
        <h3 class="text-xl font-semibold flex items-center">
          <i class="fas fa-list mr-3"></i>Customer Group Table
        </h3>
        <button type="button" @click="$emit('close')" class="text-white hover:text-gray-200 focus:outline-none">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>

      <!-- Modal Body -->
      <div class="p-2 overflow-y-auto" style="max-height: 60vh;">
        <div v-if="loading" class="flex justify-center items-center p-4">
          <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
        </div>
        <div v-else-if="error" class="p-4 text-red-500">
          {{ error }}
        </div>
        <table v-else class="min-w-full text-xs border border-gray-300">
          <thead class="bg-gray-200 sticky top-0">
            <tr>
              <th class="px-2 py-1 border border-gray-300 text-left">Group Code</th>
              <th class="px-2 py-1 border border-gray-300 text-left">Description</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="group in customerGroups" 
                :key="group.group_code"
                class="hover:bg-blue-100 cursor-pointer"
                :class="{ 'bg-blue-200': selectedGroup?.group_code === group.group_code }"
                @click="selectGroup(group)">
              <td class="px-2 py-1 border border-gray-300 font-medium text-gray-900">{{ group.group_code }}</td>
              <td class="px-2 py-1 border border-gray-300">{{ group.description }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Modal Footer -->
      <div class="flex items-center justify-end gap-2 p-2 border-t border-gray-200 bg-gray-100 rounded-b-lg">
        <button type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-3 rounded text-xs">More Options</button>
        <button type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-3 rounded text-xs">Zoom</button>
        <button 
          @click="handleSelect" 
          :disabled="!selectedGroup"
          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-xs disabled:opacity-50 disabled:cursor-not-allowed">
          Select
        </button>
        <button type="button" @click="$emit('close')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-3 rounded text-xs">Exit</button>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import axios from 'axios'

export default {
  props: {
    show: {
      type: Boolean,
      required: true
    }
  },
  emits: ['close', 'select'],
  setup(props, { emit }) {
    const customerGroups = ref([])
    const selectedGroup = ref(null)
    const loading = ref(false)
    const error = ref(null)

    const fetchCustomerGroups = async () => {
      loading.value = true
      error.value = null
      try {
        console.log('Fetching customer groups...')
        const response = await axios.get('/api/customer-groups')
        console.log('Response:', response.data)
        customerGroups.value = response.data
      } catch (error) {
        console.error('Error fetching customer groups:', error)
        error.value = 'Failed to load customer groups. Please try again.'
      } finally {
        loading.value = false
      }
    }

    const selectGroup = (group) => {
      selectedGroup.value = group
    }

    const handleSelect = () => {
      if (selectedGroup.value) {
        emit('select', selectedGroup.value)
      }
    }

    onMounted(() => {
      if (props.show) {
        fetchCustomerGroups()
      }
    })

    return {
      customerGroups,
      selectedGroup,
      loading,
      error,
      selectGroup,
      handleSelect
    }
  }
}
</script> 