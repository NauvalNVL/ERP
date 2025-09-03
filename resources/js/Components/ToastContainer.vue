<template>
  <div class="fixed top-4 right-4 z-50 flex flex-col items-end space-y-2">
    <transition-group name="toast">
      <div
        v-for="toast in toasts"
        :key="toast.id"
        :class="[
          'max-w-sm px-4 py-3 rounded-lg shadow-md transition-all transform-gpu',
          {
            'bg-green-50 text-green-800 border-l-4 border-green-500': toast.type === 'success',
            'bg-red-50 text-red-800 border-l-4 border-red-500': toast.type === 'error',
            'bg-yellow-50 text-yellow-800 border-l-4 border-yellow-500': toast.type === 'warning',
            'bg-blue-50 text-blue-800 border-l-4 border-blue-500': toast.type === 'info',
            'bg-gray-50 text-gray-800 border-l-4 border-gray-500': toast.type === 'loading'
          }
        ]"
      >
        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <!-- Loading spinner for loading type -->
            <div v-if="toast.type === 'loading'" class="mr-2">
              <svg class="animate-spin h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            </div>
            <p class="text-sm font-medium">{{ toast.message }}</p>
          </div>
          <button
            type="button"
            class="ml-4 text-gray-400 hover:text-gray-600"
            @click="removeToast(toast.id)"
          >
            <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </button>
        </div>
      </div>
    </transition-group>
  </div>
</template>

<script setup>
import { useToast } from '@/Composables/useToast';

const { toasts, removeToast } = useToast();
</script>

<style scoped>
.toast-enter-active,
.toast-leave-active {
  transition: all 0.3s ease;
}
.toast-enter-from {
  transform: translateX(30px);
  opacity: 0;
}
.toast-leave-to {
  transform: translateX(30px);
  opacity: 0;
}
</style> 