<template>
  <AppLayout :header="header">
    <Head :title="title" />
    
    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow-lg p-6 mb-6">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-2xl font-bold text-white">Prepare RC Posting Batch</h1>
              <p class="text-blue-100 mt-1">Prepare new RC posting batches for processing</p>
            </div>
            <div class="text-right text-white">
              <div class="text-sm opacity-75">Current Period</div>
              <div class="text-lg font-semibold">{{ currentPeriod }}</div>
            </div>
          </div>
        </div>

        <!-- Status Bar -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
              <div class="flex items-center">
                <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                <span class="text-sm text-gray-600">System Ready</span>
              </div>
              <div class="flex items-center">
                <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                <span class="text-sm text-gray-600">{{ availableRcNotes.length }} RC Notes Available</span>
              </div>
            </div>
            <div class="text-sm text-gray-500">
              Last Updated: {{ new Date().toLocaleString() }}
            </div>
          </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <!-- Batch Preparation Form -->
          <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
              <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Batch Configuration</h3>
              </div>
              
              <div class="p-6">
                <form @submit.prevent="prepareBatch">
                  <!-- Period Information -->
                  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">Current IC Period</label>
                      <input
                        v-model="form.current_ic_period"
                        type="text"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        readonly
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">Current AP Period</label>
                      <input
                        v-model="form.current_ap_period"
                        type="text"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        readonly
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">Current GL Period</label>
                      <input
                        v-model="form.current_gl_period"
                        type="text"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        readonly
                      />
                    </div>
                  </div>

                  <!-- Note Range Selection -->
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">Start Note Number</label>
                      <div class="flex space-x-2">
                        <input
                          v-model="form.start_note_number"
                          type="text"
                          class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Enter start note number"
                        />
                        <button
                          type="button"
                          @click="showRcNoteModal = true"
                          class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600"
                        >
                          <i class="fas fa-search"></i>
                        </button>
                      </div>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">End Note Number</label>
                      <div class="flex space-x-2">
                        <input
                          v-model="form.end_note_number"
                          type="text"
                          class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Enter end note number"
                        />
                        <button
                          type="button"
                          @click="showRcNoteModal = true"
                          class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600"
                        >
                          <i class="fas fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>

                  <!-- Action Buttons -->
                  <div class="flex justify-end space-x-3">
                    <button
                      type="button"
                      @click="clearForm"
                      class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                      Clear
                    </button>
                    <button
                      type="submit"
                      :disabled="loading"
                      class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-50"
                    >
                      <i v-if="loading" class="fas fa-spinner fa-spin mr-2"></i>
                      Prepare Batch
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <!-- Summary Card -->
          <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
              <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Summary</h3>
              </div>
              
              <div class="p-6">
                <div class="space-y-4">
                  <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Available RC Notes</span>
                    <span class="text-lg font-semibold text-gray-900">{{ availableRcNotes.length }}</span>
                  </div>
                  <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Max Batch Size</span>
                    <span class="text-lg font-semibold text-blue-600">300</span>
                  </div>
                  <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Current Period</span>
                    <span class="text-lg font-semibold text-green-600">{{ currentPeriod }}</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Notes Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mt-6">
              <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Important Notes</h3>
              </div>
              
              <div class="p-6">
                <div class="space-y-3 text-sm text-gray-600">
                  <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-1 mr-2"></i>
                    <span>This program is built with rollback control.</span>
                  </div>
                  <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-yellow-500 mt-1 mr-2"></i>
                    <span>This program only allowed 300 transaction per batch.</span>
                  </div>
                  <div class="flex items-start">
                    <i class="fas fa-exclamation-circle text-red-500 mt-1 mr-2"></i>
                    <span>Post Accrued is not activated. Those RC with blank invoice# will be skip to batch.</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- RC Notes Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mt-6">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Available RC Notes</h3>
          </div>
          
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">RC Note Number</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">RC Date</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">P/Order Number</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor Code</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Post Status</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="note in availableRcNotes" :key="note.rc_note_number" class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ note.rc_note_number }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ note.rc_date }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ note.p_order_number }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ note.vendor_code }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                      {{ note.status }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                      {{ note.post_status }}
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- RC Note Selection Modal -->
    <div v-if="showRcNoteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">Select RC Note</h3>
            <button @click="showRcNoteModal = false" class="text-gray-400 hover:text-gray-600">
              <i class="fas fa-times"></i>
            </button>
          </div>
          
          <div class="max-h-96 overflow-y-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50 sticky top-0">
                <tr>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">RC Note Number</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">RC Date</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="note in availableRcNotes" :key="note.rc_note_number" class="hover:bg-gray-50">
                  <td class="px-4 py-2 text-sm text-gray-900">{{ note.rc_note_number }}</td>
                  <td class="px-4 py-2 text-sm text-gray-500">{{ note.rc_date }}</td>
                  <td class="px-4 py-2">
                    <button
                      @click="selectRcNote(note.rc_note_number)"
                      class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                    >
                      Select
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { useToast } from '@/Composables/useToast'

const toast = useToast()
const header = 'Prepare RC Posting Batch'
const title = 'Prepare RC Posting Batch - ERP System'

const loading = ref(false)
const showRcNoteModal = ref(false)
const availableRcNotes = ref([])
const currentPeriod = ref('')

const form = reactive({
  start_note_number: '',
  end_note_number: '',
  current_ic_period: '',
  current_ap_period: '',
  current_gl_period: ''
})

const fetchCurrentPeriods = async () => {
  try {
    const response = await fetch('/api/material-management/accounts/rc-posting-batch/current-periods')
    const data = await response.json()
    
    form.current_ic_period = data.current_ic_period
    form.current_ap_period = data.current_ap_period
    form.current_gl_period = data.current_gl_period
    currentPeriod.value = data.current_ic_period
  } catch (error) {
    console.error('Error fetching current periods:', error)
    toast.error('Failed to load current periods')
  }
}

const fetchAvailableRcNotes = async () => {
  try {
    const response = await fetch('/api/material-management/accounts/rc-posting-batch/available-rc-notes')
    const data = await response.json()
    availableRcNotes.value = data
  } catch (error) {
    console.error('Error fetching RC notes:', error)
    toast.error('Failed to load RC notes')
  }
}

const prepareBatch = async () => {
  if (!form.start_note_number || !form.end_note_number) {
    toast.error('Please fill in all required fields')
    return
  }

  loading.value = true
  try {
    const response = await fetch('/api/material-management/accounts/rc-posting-batch/prepare', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify(form)
    })

    const data = await response.json()
    
    if (data.success) {
      toast.success(data.message)
      clearForm()
      await fetchAvailableRcNotes()
    } else {
      toast.error(data.message || 'Failed to prepare batch')
    }
  } catch (error) {
    console.error('Error preparing batch:', error)
    toast.error('Failed to prepare batch')
  } finally {
    loading.value = false
  }
}

const selectRcNote = (noteNumber) => {
  if (!form.start_note_number) {
    form.start_note_number = noteNumber
  } else {
    form.end_note_number = noteNumber
  }
  showRcNoteModal.value = false
}

const clearForm = () => {
  form.start_note_number = ''
  form.end_note_number = ''
}

onMounted(async () => {
  await fetchCurrentPeriods()
  await fetchAvailableRcNotes()
})
</script>
