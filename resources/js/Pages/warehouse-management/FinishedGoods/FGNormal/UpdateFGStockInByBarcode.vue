<template>
    <AppLayout :header="'Update FG Stock-In by Barcode'">
        <!-- Header Section with animated elements, adapted from Define Warehouse Location -->
        <div class="bg-gradient-to-r from-amber-600 via-orange-600 to-red-600 p-6 rounded-t-lg shadow-lg overflow-hidden relative">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10"></div>
            <div class="absolute bottom-0 right-0 w-32 h-32 bg-yellow-400 opacity-5 rounded-full translate-y-10 translate-x-10"></div>
            
            <div class="flex items-center">
                <div class="bg-gradient-to-br from-amber-500 to-orange-600 p-3 rounded-lg shadow-inner flex items-center justify-center relative overflow-hidden mr-4">
                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-yellow-300 opacity-30 rounded-full animate-ping-slow"></div>
                    <div class="absolute -bottom-1 -left-1 w-4 h-4 bg-orange-300 opacity-30 rounded-full animate-ping-slow animation-delay-500"></div>
                    <i class="fas fa-barcode text-white text-2xl z-10"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-1 text-shadow">Update FG Stock-In by Barcode</h2>
                    <p class="text-amber-100 max-w-2xl">Process finished goods stock-in using barcode scanning</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6 bg-gradient-to-br from-white to-orange-50">
            <div class="max-w-md mx-auto">
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-orange-500 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 relative overflow-hidden">
                    <div class="absolute -top-20 -right-20 w-40 h-40 bg-orange-50 rounded-full opacity-20"></div>
                    <div class="absolute -bottom-8 -left-8 w-24 h-24 bg-amber-50 rounded-full opacity-20"></div>
                    
                    <div class="flex items-center mb-6 pb-2 border-b border-gray-200 relative z-10">
                        <div class="p-2 bg-gradient-to-r from-orange-500 to-amber-600 rounded-lg mr-3 shadow-md">
                            <i class="fas fa-qrcode text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Barcode Scanning</h3>
                    </div>

                    <form @submit.prevent="processBarcode" class="space-y-6 relative z-10">
                        <!-- Current Period -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-orange-500 to-amber-600 rounded-full mr-2 shadow-sm">
                                    <i class="fas fa-calendar text-white text-xs"></i>
                                </span>
                                Current Period:
                            </label>
                            <input
                                type="text"
                                v-model="currentPeriod"
                                class="w-32 px-3 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 bg-gray-50 transition-all"
                                readonly
                            />
                        </div>

                        <!-- Barcode Input -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-amber-500 to-red-500 rounded-full mr-2 shadow-sm">
                                    <i class="fas fa-barcode text-white text-xs"></i>
                                </span>
                                Barcode#:
                            </label>
                            <input
                                type="text"
                                ref="barcodeInput"
                                v-model="barcodeNumber"
                                placeholder="Scan or enter barcode"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500 transition-all"
                                autofocus
                                @keyup.enter="processBarcode"
                            />
                            <div class="text-xs text-gray-500 mt-1">Scan barcode or press Enter</div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Invalid Barcode Format Modal -->
        <div v-if="showInvalidBarcodeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl w-96 max-w-md overflow-hidden border-2 border-red-200">
                <!-- Modal Header -->
                <div class="p-4 bg-gradient-to-r from-red-500 to-pink-500 text-white">
                    <div class="flex items-center">
                        <div class="bg-white bg-opacity-20 p-2 rounded-lg mr-3">
                            <i class="fas fa-exclamation-triangle text-white text-lg"></i>
                        </div>
                        <h3 class="text-lg font-semibold">INVALID BARCODE FORMAT</h3>
                    </div>
                </div>
                
                <!-- Modal Content -->
                <div class="p-6 bg-gray-50">
                    <div class="text-center mb-4">
                        <p class="text-gray-700 font-medium mb-2">This program is for FG Non-GRX.</p>
                        <p class="text-gray-700 mb-4">The barcode format is:</p>
                        
                        <div class="bg-white p-4 rounded-lg border border-gray-200 font-mono text-sm">
                            <div class="text-center text-gray-800">
                                <div class="font-bold text-lg mb-2">01202101318 - 2101-000204 - 135000</div>
                                <div class="flex justify-between text-xs text-gray-600 border-t pt-2">
                                    <span class="font-semibold">WO#</span>
                                    <span class="font-semibold">Pallet#</span>
                                    <span class="font-semibold">Qty</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="p-4 bg-white border-t border-gray-200 flex justify-center">
                    <button
                        @click="closeInvalidBarcodeModal"
                        class="px-8 py-2 bg-gradient-to-r from-gray-500 to-slate-600 hover:from-gray-600 hover:to-slate-700 text-white rounded-lg shadow-md transition-all transform active:scale-95 flex items-center space-x-2"
                    >
                        <i class="fas fa-redo"></i>
                        <span>Kindly Try Again</span>
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted, nextTick } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

// State
const currentPeriod = ref('06/2025');
const barcodeNumber = ref('');
const barcodeInput = ref(null);
const showInvalidBarcodeModal = ref(false);

// Methods
const processBarcode = async () => {
    if (!barcodeNumber.value.trim()) return;
    
    try {
        // Validate barcode format: should contain WO# - Pallet# - Qty format
        const barcodePattern = /^\d{11}\s*-\s*\d{4}-\d{6}\s*-\s*\d+$/;
        
        if (!barcodePattern.test(barcodeNumber.value)) {
            showInvalidBarcodeModal.value = true;
            return;
        }
        
        // Process valid barcode
        console.log('Processing barcode:', barcodeNumber.value);
        
        // Here you would typically:
        // 1. Parse the barcode to extract WO#, Pallet#, and Qty
        // 2. Validate the work order exists
        // 3. Process the stock-in
        
        const parts = barcodeNumber.value.split('-').map(part => part.trim());
        const woNumber = parts[0];
        const palletNumber = parts[1];
        const quantity = parts[2];
        
        console.log('WO#:', woNumber, 'Pallet#:', palletNumber, 'Qty:', quantity);
        
        // For demo - you would implement actual processing here
        alert(`Barcode processed successfully!\nWO#: ${woNumber}\nPallet#: ${palletNumber}\nQty: ${quantity}`);
        
        // Reset form
        barcodeNumber.value = '';
        nextTick(() => {
            barcodeInput.value.focus();
        });
        
    } catch (error) {
        console.error('Error processing barcode:', error);
        showInvalidBarcodeModal.value = true;
    }
};

const closeInvalidBarcodeModal = () => {
    showInvalidBarcodeModal.value = false;
    barcodeNumber.value = '';
    nextTick(() => {
        barcodeInput.value.focus();
    });
};

// Lifecycle
onMounted(() => {
    nextTick(() => {
        if (barcodeInput.value) {
            barcodeInput.value.focus();
        }
    });
});
</script>

<style scoped>
.text-shadow {
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.animate-ping-slow {
    animation: ping 3s cubic-bezier(0, 0, 0.2, 1) infinite;
}

.animation-delay-500 {
    animation-delay: 500ms;
}

@keyframes ping {
    75%, 100% {
        transform: scale(2);
        opacity: 0;
    }
}
</style> 