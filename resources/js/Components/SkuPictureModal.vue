<template>
  <div v-if="show" class="fixed inset-0 flex items-center justify-center z-50 p-4">
    <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm" @click="close"></div>
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl z-10 relative transform transition-all duration-300 ease-in-out max-h-[90vh] overflow-hidden">
    <div class="p-6">
      <!-- Header -->
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-bold text-gray-900">
          <i class="fas fa-image mr-2 text-blue-500"></i>
          Setup SKU Picture
        </h3>
        <button @click="close" class="text-gray-400 hover:text-gray-500 transition-colors">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>

      <!-- Form -->
      <div class="space-y-6">
        <!-- SKU Info -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">SKU#</label>
            <input 
              type="text" 
              v-model="form.sku"
              readonly
              class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-500"
            >
          </div>
        </div>

        <!-- System Filename -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">System Filename</label>
          <input 
            type="text" 
            v-model="form.systemFilename"
            placeholder="jpg"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
          >
        </div>

        <!-- Path -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Path</label>
          <div class="flex">
            <input 
              type="text" 
              v-model="form.path"
              readonly
              class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md bg-gray-50"
              placeholder="Select file path..."
            >
            <button 
              type="button"
              @click="showFileBrowser = true"
              class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white border border-blue-600 rounded-r-md transition-colors"
            >
              Browse
            </button>
          </div>
        </div>

        <!-- Notes -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
          <h4 class="font-medium text-yellow-800 mb-2">Notes</h4>
          <div class="text-sm text-yellow-700 space-y-1">
            <p><strong>JPEG Filename:</strong> SKU#.jpg</p>
            <p><strong>Recommend Size:</strong> 70mm W x 50mm H (265 x 189 px)</p>
            <p>It must be JPEG image Filename or Blank filename if you do not need picture.</p>
            <p><strong>Path Maximum Length:</strong> 60 characters and cannot contain space.</p>
          </div>
        </div>

        <!-- Current Picture Preview -->
        <div v-if="form.path" class="border rounded-lg p-4">
          <h4 class="font-medium text-gray-800 mb-3">Preview</h4>
          <div class="flex items-center justify-center bg-gray-100 rounded border h-48">
            <img 
              v-if="imagePreview"
              :src="imagePreview" 
              :alt="form.sku"
              class="max-h-full max-w-full object-contain"
              @error="handleImageError"
            >
            <div v-else class="text-gray-500 text-center">
              <i class="fas fa-image text-4xl mb-2"></i>
              <p>No image preview available</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="flex justify-end space-x-3 mt-6">
        <button 
          @click="close" 
          class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
        >
          Exit
        </button>
        <button 
          @click="updatePicture"
          class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
        >
          Update
        </button>
      </div>
    </div>
    </div>
  </div>

    <!-- File Browser Modal -->
    <Modal :show="showFileBrowser" @close="showFileBrowser = false" :max-width="'5xl'">
      <div class="p-6">
        <div class="flex items-center justify-between mb-6">
          <h4 class="text-lg font-bold text-gray-900">
            <i class="fas fa-folder-open mr-2 text-blue-500"></i>
            Browse
          </h4>
          <button @click="showFileBrowser = false" class="text-gray-400 hover:text-gray-500">
            <i class="fas fa-times"></i>
          </button>
        </div>

        <!-- File Browser Content -->
        <div class="space-y-4">
          <!-- Current Path -->
          <div class="flex items-center space-x-2 bg-gray-50 p-3 rounded">
            <i class="fas fa-folder text-yellow-500"></i>
            <span class="text-sm text-gray-600">{{ currentPath }}</span>
          </div>

          <!-- Navigation -->
          <div class="flex items-center space-x-2">
            <button 
              @click="navigateUp"
              :disabled="currentPath === '/'"
              class="px-3 py-1 text-sm bg-gray-200 hover:bg-gray-300 rounded disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <i class="fas fa-arrow-up mr-1"></i> Up
            </button>
            <button 
              @click="refreshFiles"
              class="px-3 py-1 text-sm bg-blue-200 hover:bg-blue-300 rounded"
            >
              <i class="fas fa-sync-alt mr-1"></i> Refresh
            </button>
          </div>

          <!-- File List -->
          <div class="border rounded-lg overflow-hidden max-h-96 overflow-y-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50 sticky top-0">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Name
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Status
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Date Modified
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-if="loadingFiles" class="animate-pulse">
                  <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                    <div class="flex justify-center items-center space-x-2">
                      <i class="fas fa-spinner fa-spin"></i>
                      <span>Loading files...</span>
                    </div>
                  </td>
                </tr>
                <tr v-else-if="files.length === 0">
                  <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                    No files found
                  </td>
                </tr>
                <tr v-for="file in files" 
                    :key="file.name" 
                    :class="{'bg-blue-50': selectedFile && selectedFile.name === file.name}"
                    @click="selectFile(file)"
                    @dblclick="file.isDirectory ? navigateToFolder(file.name) : confirmFileSelection(file)"
                    class="hover:bg-gray-50 cursor-pointer">
                  <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <div class="flex items-center">
                      <i :class="file.isDirectory ? 'fas fa-folder text-yellow-500' : 'fas fa-file-image text-blue-500'" class="mr-2"></i>
                      {{ file.name }}
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ file.status || '—' }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ file.dateModified || '—' }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- File Selection Info -->
          <div class="bg-gray-50 p-3 rounded">
            <div class="flex items-center justify-between">
              <div>
                <span class="text-sm text-gray-600">File name:</span>
                <input 
                  type="text" 
                  v-model="selectedFileName"
                  class="ml-2 px-2 py-1 text-sm border border-gray-300 rounded"
                  placeholder="Select a file"
                >
              </div>
              <div>
                <select 
                  v-model="fileFilter"
                  class="px-2 py-1 text-sm border border-gray-300 rounded"
                >
                  <option value="*.jpg">JPG files (*.jpg)</option>
                  <option value="*.*">All files (*.*)</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-end space-x-3 mt-6">
          <button 
            @click="showFileBrowser = false" 
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200"
          >
            Cancel
          </button>
          <button 
            @click="confirmFileSelection(selectedFile)"
            :disabled="!selectedFile || selectedFile.isDirectory"
            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Open
          </button>
                </div>
      </div>
    </Modal>
  </template>

<script setup>
import { ref, onMounted, watch, computed } from 'vue'
import Modal from '@/Components/Modal.vue'


const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  sku: {
    type: Object,
    default: () => ({})
  }
})

const emit = defineEmits(['close', 'pictureUpdated'])

// State
const showFileBrowser = ref(false)
const loadingFiles = ref(false)
const files = ref([])
const selectedFile = ref(null)
const selectedFileName = ref('')
const fileFilter = ref('*.jpg')
const currentPath = ref('/')

const form = ref({
  sku: '',
  systemFilename: 'jpg',
  path: ''
})

// Computed
const imagePreview = computed(() => {
  if (!form.value.path) return null
  
  // In a real application, you would construct the actual image URL
  // For now, we'll just return a placeholder or the path itself
  return form.value.path
})

// Methods
const fetchFiles = async (path = '/') => {
  loadingFiles.value = true
  try {
    // Simulate file system - replace with actual API
    await new Promise(resolve => setTimeout(resolve, 500))
    
    // Mock file system data
    const mockFiles = [
      { name: 'Documents', isDirectory: true, status: '', dateModified: '12/08/2025 16:27' },
      { name: 'Pictures', isDirectory: true, status: '', dateModified: '12/08/2025 16:27' },
      { name: 'sku001.jpg', isDirectory: false, status: '', dateModified: '12/08/2025 16:27' },
      { name: 'sku002.jpg', isDirectory: false, status: '', dateModified: '12/08/2025 16:27' },
      { name: 'product_image.jpg', isDirectory: false, status: '', dateModified: '12/08/2025 16:27' }
    ]
    
    files.value = mockFiles.filter(file => {
      if (fileFilter.value === '*.jpg') {
        return file.isDirectory || file.name.toLowerCase().endsWith('.jpg')
      }
      return true
    })
  } catch (error) {
    console.error('Error fetching files:', error)
  } finally {
    loadingFiles.value = false
  }
}

const selectFile = (file) => {
  selectedFile.value = file
  if (!file.isDirectory) {
    selectedFileName.value = file.name
  }
}

const navigateToFolder = (folderName) => {
  currentPath.value = currentPath.value === '/' ? `/${folderName}` : `${currentPath.value}/${folderName}`
  fetchFiles(currentPath.value)
}

const navigateUp = () => {
  const pathParts = currentPath.value.split('/').filter(p => p)
  pathParts.pop()
  currentPath.value = pathParts.length === 0 ? '/' : '/' + pathParts.join('/')
  fetchFiles(currentPath.value)
}

const refreshFiles = () => {
  fetchFiles(currentPath.value)
}

const confirmFileSelection = (file) => {
  if (file && !file.isDirectory) {
    form.value.path = `${currentPath.value}/${file.name}`.replace('//', '/')
    showFileBrowser.value = false
    selectedFile.value = null
    selectedFileName.value = ''
  }
}

const updatePicture = () => {
  emit('pictureUpdated', {
    sku: form.value.sku,
    systemFilename: form.value.systemFilename,
    path: form.value.path
  })
  close()
}

const handleImageError = () => {
  console.log('Error loading image preview')
}

const close = () => {
  emit('close')
}

// Watchers
watch(() => props.sku, (newSku) => {
  if (newSku && newSku.sku) {
    form.value.sku = newSku.sku
    form.value.path = newSku.sku_picture_path || ''
  }
}, { immediate: true })

watch(() => showFileBrowser.value, (newVal) => {
  if (newVal) {
    fetchFiles()
  }
})

watch(() => fileFilter.value, () => {
  fetchFiles(currentPath.value)
})

onMounted(() => {
  if (props.show) {
    // Initialize component
  }
})
</script>

<style scoped>
/* Custom styling if needed */
</style>
