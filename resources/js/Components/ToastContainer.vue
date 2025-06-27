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
            'bg-blue-50 text-blue-800 border-l-4 border-blue-500': toast.type === 'info'
          }
        ]"
      >
        <div class="flex items-center justify-between">
          <p class="text-sm font-medium">{{ toast.message }}</p>
          <button
            type="button"
            class="ml-4 text-gray-400 hover:text-gray-600"
            @click="removeToast(toast.id)"
          >
            <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
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