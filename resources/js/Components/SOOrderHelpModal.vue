<template>
  <transition name="modal-fade">
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white rounded-lg shadow-xl overflow-hidden w-11/12 md:w-1/2 lg:w-1/3 max-h-[90vh] flex flex-col">
        <!-- Modal Header -->
        <div class="px-6 py-4 bg-gray-100 border-b border-gray-200 flex items-center justify-between">
          <h3 class="text-lg font-semibold text-gray-800">SO Order Help</h3>
          <button @click="close" class="text-gray-500 hover:text-gray-700">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <!-- Modal Body - Notes List -->
        <div class="flex-grow overflow-auto p-4">
          <p class="font-semibold text-gray-800 mb-2">Note</p>
          <ul class="list-disc list-inside space-y-1 text-gray-700">
            <li v-for="(note, index) in notes" :key="index">{{ note }}</li>
          </ul>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 bg-gray-100 border-t border-gray-200 flex justify-end">
          <button 
            @click="close" 
            class="px-5 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors duration-200"
          >
            Exit
          </button>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  data() {
    return {
      notes: [
        'S1-Sales from SO >> WO >> CORR >> CONV >> FG >> DO >> IV',
        'S2-Sales from SO >> DO >> IV (Kanban/JIT)',
        'S3-Sales from SO >> WO >> CONV >> FG >> DO >> IV',
        'N1-Non-Sales from SO >> WO >> CORR >> CONV >> FG >> DO',
        'N2-Non-Sales from SO >> DO (FOC)',
        'N3-Non-Sales from SO >> WO >> CORR >> CONV >> FG (Blanket)',
        'N4-Non-Sales from SO >> WO >> CORR (Sheet Stock)',
      ],
    };
  },
  methods: {
    close() {
      this.$emit('close');
    },
  },
};
</script>

<style scoped>
.modal-fade-enter-active, .modal-fade-leave-active {
  transition: opacity 0.3s ease;
}
.modal-fade-enter, .modal-fade-leave-to {
  opacity: 0;
}
</style> 