<template>
  <div v-if="show" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-96 p-6">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-2 border-b border-gray-200 bg-gray-100 rounded-t-lg">
        <h2 class="text-lg font-semibold">Second Password Access</h2>
        <button @click="$emit('close')" class="text-gray-500 hover:text-gray-700">&times;</button>
      </div>

      <!-- Modal Body -->
      <div class="p-4">
        <div class="mb-4">
          <label for="userId" class="block text-sm font-medium text-gray-700">User ID:</label>
          <div class="mt-1 flex rounded-md shadow-sm">
            <input type="text" id="userId" v-model="userId" class="focus:ring-blue-500 focus:border-blue-500 flex-1 block w-full rounded-none rounded-l-md sm:text-sm border-gray-300" />
            <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
              <i class="fas fa-id-badge"></i>
            </span>
          </div>
        </div>
        <div class="mb-4">
          <label for="password" class="block text-sm font-medium text-gray-700">Password:</label>
          <input type="password" id="password" v-model="password" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="flex justify-end gap-2 p-2 border-t border-gray-200 bg-gray-100 rounded-b-lg">
        <button @click="handleSelect" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Select</button>
        <button @click="$emit('close')" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">Exit</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
// defineProps and defineEmits are compiler macros and don't need to be imported

const props = defineProps({
  show: Boolean,
});

const emit = defineEmits(['close', 'select']);

const userId = ref('');
const password = ref('');

const handleSelect = () => {
  // For now, just emit the values. Backend validation would be done elsewhere.
  emit('select', { userId: userId.value, password: password.value });
  emit('close');
};
</script> 