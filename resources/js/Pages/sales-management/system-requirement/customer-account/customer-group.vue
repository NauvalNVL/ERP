<template>
  <div>
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg mb-0">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-users mr-3"></i> Define Customer Group
      </h2>
      <p class="text-cyan-100">Definisikan kelompok pelanggan untuk pengelompokan customer di sistem</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Main Content -->
        <div class="lg:col-span-2">
          <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
            <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
              <div class="p-2 bg-blue-500 rounded-lg mr-3">
                <i class="fas fa-edit text-white"></i>
              </div>
              <h3 class="text-xl font-semibold text-gray-800">Customer Group Management</h3>
            </div>

            <!-- Header with navigation buttons -->
            <div class="flex items-center space-x-2 mb-6">
              <button type="button" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px" @click="goToDashboard">
                <i class="fas fa-power-off"></i>
              </button>
              <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px">
                <i class="fas fa-arrow-right"></i>
              </button>
              <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px">
                <i class="fas fa-arrow-left"></i>
              </button>
              <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px">
                <i class="fas fa-search"></i>
              </button>
              <button type="button" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px" @click="openModal">
                <i class="fas fa-plus"></i>
              </button>
              <button type="button" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px" @click="deleteSelected">
                <i class="fas fa-trash"></i>
              </button>
            </div>

            <!-- Form Section -->
            <form @submit.prevent="handleSubmit" class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label for="group_code" class="block text-sm font-medium text-gray-700 mb-1">Grouping Code</label>
                  <div class="relative flex">
                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                      <i class="fas fa-users"></i>
                    </span>
                    <input type="text" v-model="form.group_code" id="group_code" required maxlength="20"
                      class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <button type="button" @click="openModal" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md transition-colors transform active:translate-y-px">
                      <i class="fas fa-table"></i>
                    </button>
                  </div>
                  <p v-if="errors.group_code" class="mt-1 text-sm text-red-600">{{ errors.group_code }}</p>
                </div>
                <div>
                  <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                  <input type="text" v-model="form.description" id="description" required maxlength="100"
                    class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                  <p v-if="errors.description" class="mt-1 text-sm text-red-600">{{ errors.description }}</p>
                </div>
              </div>
              <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                  Save
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Right Column - Info/Help -->
        <div class="lg:col-span-1">
          <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-teal-500 mb-6">
            <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
              <div class="p-2 bg-teal-500 rounded-lg mr-3">
                <i class="fas fa-info-circle text-white"></i>
              </div>
              <h3 class="text-lg font-semibold text-gray-800">Info Customer Group</h3>
            </div>
            <div class="p-4 bg-teal-50 rounded-lg">
              <h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2">Petunjuk</h4>
              <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                <li>Kode customer group harus unik dan tidak berubah</li>
                <li>Gunakan tombol <span class="font-medium">search</span> untuk memilih customer group</li>
                <li>Perubahan apa pun harus disimpan</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Customer Group Modal Component -->
    <CustomerGroupModal
      v-if="showModal"
      :show="showModal"
      @close="closeModal"
      @select="handleSelect"
    />
  </div>
</template>

<script>
import { ref, reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import CustomerGroupModal from '@/Components/customer-group-modal.vue'

export default {
  components: {
    CustomerGroupModal
  },
  setup() {
    const showModal = ref(false)
    const form = reactive({
      group_code: '',
      description: ''
    })
    const errors = reactive({})

    const openModal = () => {
      showModal.value = true
    }

    const closeModal = () => {
      showModal.value = false
    }

    const handleSelect = (selected) => {
      form.group_code = selected.group_code
      form.description = selected.description
      closeModal()
    }

    const handleSubmit = async () => {
      try {
        await router.post(route('customer-group.store'), form)
        form.group_code = ''
        form.description = ''
        errors.value = {}
      } catch (e) {
        if (e.response?.data?.errors) {
          errors.value = e.response.data.errors
        }
      }
    }

    const goToDashboard = () => {
      router.visit(route('dashboard'))
    }

    const deleteSelected = () => {
      if (form.group_code) {
        if (confirm('Apakah Anda yakin ingin menghapus customer group ini?')) {
          router.delete(route('customer-group.destroy', form.group_code))
        }
      }
    }

    return {
      showModal,
      form,
      errors,
      openModal,
      closeModal,
      handleSelect,
      handleSubmit,
      goToDashboard,
      deleteSelected
    }
  }
}
</script>