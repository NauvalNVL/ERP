<template>
    <Modal :show="show" :max-width="maxWidth" :closeable="true" @close="close">
        <div class="p-8 bg-white rounded-lg shadow-xl relative animate-fade-in-up">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-blue-600 via-cyan-600 to-teal-500 p-4 rounded-t-lg shadow-md -mx-8 -mt-8 mb-6 flex items-center justify-between">
                <h2 class="text-xl md:text-2xl font-bold text-white text-shadow flex items-center">
                    <i class="fas fa-map-marked-alt text-white mr-3"></i>
                    Customer Alternate Delivery Location Preview
                </h2>
                <button @click="close" class="text-white hover:text-gray-200 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Delivery Code & Ship To Section (Mimicking Table Header) -->
            <div class="mb-6 p-4 border border-blue-200 rounded-lg bg-blue-50">
                <div class="grid grid-cols-2 gap-4 font-semibold text-blue-800 mb-2">
                    <div>Delivery Code</div>
                    <div>Ship To</div>
                </div>
                <div class="grid grid-cols-2 gap-4 text-gray-800 py-2 border-t border-blue-100">
                    <div>{{ deliveryCode || 'N/A' }}</div>
                    <div>{{ shipTo.name || 'N/A' }}</div>
                </div>
            </div>

            <!-- Details Section -->
            <div class="space-y-4 text-gray-700 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1">Country:</label>
                        <p class="bg-gray-100 p-2 rounded-md border border-gray-200">{{ country || 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1">Town:</label>
                        <p class="bg-gray-100 p-2 rounded-md border border-gray-200">{{ town || 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1">State:</label>
                        <p class="bg-gray-100 p-2 rounded-md border border-gray-200">{{ state || 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1">Section:</label>
                        <p class="bg-gray-100 p-2 rounded-md border border-gray-200">{{ townSection || 'N/A' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 font-semibold mb-1">Address:</label>
                        <p class="bg-gray-100 p-2 rounded-md border border-gray-200 h-20 overflow-y-auto">{{ shipTo.address || 'N/A' }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 pt-4 border-t border-gray-200">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1">Contact:</label>
                        <p class="bg-gray-100 p-2 rounded-md border border-gray-200">{{ shipTo.contactPerson || 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1">Tel No:</label>
                        <p class="bg-gray-100 p-2 rounded-md border border-gray-200">{{ shipTo.telNo || 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1">Fax No:</label>
                        <p class="bg-gray-100 p-2 rounded-md border border-gray-200">{{ shipTo.faxNo || 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1">Email:</label>
                        <p class="bg-gray-100 p-2 rounded-md border border-gray-200">{{ shipTo.email || 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="flex justify-end space-x-4 mt-6">
                <button @click="close" class="bg-gradient-to-br from-red-500 to-red-700 text-white px-6 py-2 rounded-lg shadow-md hover:from-red-600 hover:to-red-800 transition transform hover:scale-105">
                    Exit
                </button>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    show: Boolean,
    maxWidth: { type: String, default: '2xl' },
    deliveryCode: String,
    country: String,
    state: String,
    town: String,
    townSection: String,
    billTo: Object,
    shipTo: Object,
});

const emit = defineEmits(['close']);

const close = () => {
    emit('close');
};
</script>

<style scoped>
.text-shadow {
    text-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
}
.animate-fade-in-up {
  animation: fadeInUp 0.7s cubic-bezier(0.23, 1, 0.32, 1);
}
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(40px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style> 