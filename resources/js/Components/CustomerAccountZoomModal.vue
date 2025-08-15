<template>
  <div v-if="show" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-1/2 p-6">
      <h2 class="text-lg font-semibold mb-4">Customer Account Zoom</h2>
      <p>This is the Customer Account Zoom modal content.</p>
      <div v-if="customerAccountData">
        <p><strong>Customer Code:</strong> {{ customerAccountData.customer_code }}</p>
        <p><strong>Customer Name:</strong> {{ customerAccountData.customer_name }}</p>
        <!-- Add more details as needed -->
      </div>
      <button @click="$emit('close')" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">Close</button>
    </div>
  </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
  show: Boolean,
  customerAccountData: Object, // Prop to receive selected account data
});

const emits = defineEmits(['close']);
</script> 