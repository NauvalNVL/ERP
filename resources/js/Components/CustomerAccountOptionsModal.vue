<template>
  <div v-if="show" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-1/2 p-6">
      <h2 class="text-lg font-semibold mb-4">Customer Account Options</h2>
      <p>This is the Customer Account Options modal content.</p>
      <button @click="$emit('close')" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">Close</button>
    </div>
  </div>
</template>

<script setup>
// defineProps and defineEmits are compiler macros and don't need to be imported

const props = defineProps({
  show: Boolean,
});

const emits = defineEmits(['close']);
</script>

<style scoped>
/* Add any specific styles for this modal here */
</style> 