<template>
  <AppLayout :header="'Define Customer Alternate Address'">
        <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 via-cyan-600 to-teal-500 p-6 rounded-t-lg shadow-lg overflow-hidden relative mb-6">
      <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20 animate-pulse-slow"></div>
      <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10 animate-pulse-slow animation-delay-500"></div>
      <div class="flex items-center">
        <div class="bg-gradient-to-br from-cyan-500 to-teal-600 p-3 rounded-lg shadow-inner flex items-center justify-center mr-4">
          <i class="fas fa-address-book text-white text-2xl z-10"></i>
              </div>
              <div>
          <h2 class="text-2xl md:text-3xl font-bold text-white mb-1 text-shadow">Define Customer Alternate Address</h2>
          <p class="text-cyan-100">Kelola alamat alternatif customer dengan mudah</p>
              </div>
            </div>
          </div>
          
    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Left: Main Container -->
      <div class="lg:col-span-2">
        <div class="relative bg-gradient-to-br from-blue-50 via-cyan-50 to-teal-100 rounded-2xl shadow-2xl border-t-4 border-cyan-500 p-8 animate-fade-in-up overflow-hidden">
          <div class="flex items-center mb-6">
            <div class="bg-gradient-to-br from-cyan-500 to-blue-600 p-3 rounded-lg mr-3">
              <i class="fas fa-map-marker-alt text-white text-2xl"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-800">Customer Alternate Address Management</h2>
          </div>
          <form @submit.prevent="handleSave">
            <div class="mb-6">
              <label class="flex items-center text-gray-700 font-semibold mb-2">
                <span class="inline-flex items-center justify-center w-7 h-7 bg-gradient-to-br from-cyan-400 to-blue-500 text-white rounded-lg mr-2">
                  <i class="fas fa-id-card"></i>
                        </span>
                Customer Code
              </label>
              <div class="flex">
                <input type="text" class="flex-1 border border-gray-300 rounded-l-lg px-3 py-2 focus:ring-2 focus:ring-cyan-400" v-model="customerCode" />
                <button type="button" class="bg-gradient-to-br from-cyan-400 to-blue-500 text-white px-3 rounded-r-lg hover:from-cyan-500 hover:to-blue-600 transition" @click="openCustomerLookup">
                  <i class="fas fa-search"></i>
                          </button>
              </div>
            </div>
                <div class="mb-6">
              <label class="flex items-center text-gray-700 font-semibold mb-2">
                <span class="inline-flex items-center justify-center w-7 h-7 bg-gradient-to-br from-pink-400 to-purple-500 text-white rounded-lg mr-2">
                  <i class="fas fa-truck"></i>
                </span>
                Delivery Code
              </label>
              <div class="flex">
                <input type="text" class="flex-1 border border-gray-300 rounded-l-lg px-3 py-2 focus:ring-2 focus:ring-pink-400" v-model="deliveryCode" />
                <button type="button" class="bg-gradient-to-br from-pink-400 to-purple-500 text-white px-3 rounded-r-lg hover:from-pink-500 hover:to-purple-600 transition" @click="openDeliveryLookup">
                  <i class="fas fa-search"></i>
                </button>
              </div>
              <!-- New Delivery Code input and button below -->
              <div class="flex mt-3">
                <input type="text" class="flex-1 border border-gray-300 rounded-l-lg px-3 py-2 focus:ring-2 focus:ring-yellow-400" v-model="newDeliveryCode" placeholder="Enter new delivery code..." />
                <button type="button" class="bg-gradient-to-br from-yellow-400 to-orange-400 text-white px-3 rounded-r-lg hover:from-yellow-500 hover:to-orange-500 transition" @click="handleAddDeliveryCode">
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </div>
                <!-- Action Buttons -->
            <div class="flex space-x-3 mt-8 justify-end">
              <button type="button" class="action-btn bg-gradient-to-r from-red-500 to-pink-500" @click="handlePower">
                <i class="fas fa-power-off"></i>
              </button>
              <button type="button" class="action-btn bg-gradient-to-r from-blue-500 to-cyan-500" @click="handleNext">
                <i class="fas fa-arrow-right"></i>
                  </button>
              <button type="submit" class="action-btn bg-gradient-to-r from-green-500 to-teal-500">
                <i class="fas fa-save"></i>
                  </button>
            </div>
          </form>
                </div>
              </div>
      <!-- Right: Info & Quick Links -->
      <div class="flex flex-col space-y-6">
        <!-- Information Card -->
        <div class="bg-white rounded-xl shadow-md border-t-4 border-blue-400 p-6">
          <div class="flex items-center mb-2">
            <div class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-br from-green-400 to-teal-400 rounded-lg mr-3">
              <i class="fas fa-info text-white text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Information</h3>
          </div>
          <hr class="my-2 border-blue-100">
          <div class="text-gray-700 mb-4">
            Gunakan form ini untuk memperbarui data akun customer. Pastikan semua informasi yang dimasukkan sudah benar dan lengkap.
          </div>
          <div class="bg-blue-50 rounded-lg p-4">
            <div class="font-bold text-blue-700 mb-2">Petunjuk:</div>
            <ul class="list-disc pl-5 text-blue-700 space-y-1 text-sm">
              <li>Masukkan kode customer untuk mencari data</li>
              <li>Klik tombol tabel untuk melihat daftar customer</li>
              <li>Isi semua field yang diperlukan</li>
              <li>Klik Save untuk menyimpan perubahan</li>
            </ul>
          </div>
        </div>
        <!-- Quick Links Card -->
        <div class="bg-white rounded-xl shadow-md border-t-4 border-purple-400 p-6">
          <div class="flex items-center mb-2">
            <div class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-br from-purple-400 to-pink-400 rounded-lg mr-3">
              <i class="fas fa-link text-white text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Quick Links</h3>
          </div>
          <hr class="my-2 border-purple-100">
          <div class="space-y-3 mt-4">
            <a href="#" class="flex items-center p-3 rounded-lg bg-green-50 hover:bg-green-100 transition">
              <span class="inline-flex items-center justify-center w-9 h-9 bg-green-400 rounded-lg mr-3">
                <i class="fas fa-print text-white text-xl"></i>
              </span>
              <div>
                <div class="font-bold text-green-800">View & Print</div>
                <div class="text-xs text-green-700">Print customer list</div>
              </div>
            </a>
            <a href="#" class="flex items-center p-3 rounded-lg bg-blue-50 hover:bg-blue-100 transition">
              <span class="inline-flex items-center justify-center w-9 h-9 bg-blue-400 rounded-lg mr-3">
                <i class="fas fa-users text-white text-xl"></i>
              </span>
              <div>
                <div class="font-bold text-blue-800">Customer Groups</div>
                <div class="text-xs text-blue-700">Manage customer groups</div>
              </div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const customerCode = ref('');
const deliveryCode = ref('');
const newDeliveryCode = ref("");

function openCustomerLookup() {
  // TODO: Open customer lookup modal
}
function openDeliveryLookup() {
  // TODO: Open delivery code lookup modal
}
function handlePower() {
  // TODO: Power action
}
function handleNext() {
  // TODO: Next action
}
function handleSave() {
  // TODO: Save action
}
function handleAddDeliveryCode() {
  // TODO: Add delivery code action
}
</script>

<style scoped>
.action-btn {
  @apply text-white px-4 py-2 rounded-lg shadow-md text-lg flex items-center justify-center transition-all duration-200 hover:scale-105 hover:shadow-xl;
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