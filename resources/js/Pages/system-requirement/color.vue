<template>
  <div class="flex justify-center">
    <div class="max-w-6xl w-full">
      <!-- Header Section -->
      <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
        <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
          <i class="fas fa-palette mr-3"></i> Define Color
        </h2>
        <p class="text-cyan-100">Definisikan warna-warna untuk kategori dan kelompok yang spesifik</p>
      </div>

      <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Left Column - Main Content -->
          <div class="lg:col-span-2">
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
              <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                <div class="p-2 bg-blue-500 rounded-lg mr-3">
                  <i class="fas fa-edit text-white"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800">Color Management</h3>
              </div>

              <!-- Header with navigation buttons -->
              <div class="flex items-center space-x-2 mb-6">
                <button type="button" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px">
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
                <button type="button" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px">
                  <i class="fas fa-save"></i>
                </button>
              </div>

              <!-- Search Section -->
              <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                <div class="col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Color#:</label>
                  <div class="relative flex">
                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                      <i class="fas fa-palette"></i>
                    </span>
                    <input type="text" id="selectedColorId" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <button @click="openColorModal" type="button" id="showColorTableBtn" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md transition-colors transform active:translate-y-px">
                      <i class="fas fa-table"></i>
                    </button>
                  </div>
                </div>
                
                <div class="col-span-1">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Record:</label>
                  <button type="button" class="w-full flex items-center justify-center px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded transition-colors transform active:translate-y-px">
                    <i class="fas fa-list-ul mr-2"></i> Select Record
                  </button>
                </div>
              </div>

              <!-- Debug Information -->
              <div v-if="!colors || colors.length === 0" class="mt-4 bg-yellow-100 p-3 rounded">
                <p class="text-sm font-medium text-yellow-800">Tidak ada data warna yang tersedia.</p>
                <p class="text-xs text-yellow-700 mt-1">Pastikan database telah diatur dengan benar dan data seeder telah dijalankan.</p>
                <div class="mt-2 flex items-center space-x-3">
                  <a href="/run-color-seeder" class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded transform active:translate-y-px">
                    Run Color Seeder (DB)
                  </a>
                  <button @click="loadSeedData" class="bg-green-500 hover:bg-green-600 text-white text-xs px-3 py-1 rounded transform active:translate-y-px">
                    Load Color Seeder Data (JS)
                  </button>
                  <span class="text-xs text-yellow-700">atau jalankan: <code class="bg-yellow-200 px-1 py-0.5 rounded"></code></span>
                </div>
              </div>

              <div v-else class="mt-4 bg-green-100 p-3 rounded">
                <p class="text-sm font-medium text-green-800">Data tersedia: {{ colors.length }} warna ditemukan.</p>
              </div>
            </div>
          </div>

          <!-- Right Column - Quick Info -->
          <div class="lg:col-span-1">
            <!-- Color Info Card -->
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-teal-500 mb-6">
              <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                <div class="p-2 bg-teal-500 rounded-lg mr-3">
                  <i class="fas fa-info-circle text-white"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Info Warna</h3>
              </div>

              <div class="space-y-4">
                <div class="p-4 bg-teal-50 rounded-lg">
                  <h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2">Petunjuk</h4>
                  <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                    <li>Kode warna harus unik dan tidak berubah</li>
                    <li>Gunakan tombol <span class="font-medium">search</span> untuk memilih warna</li>
                    <li>Tipe CG menentukan karakteristik warna</li>
                    <li>Perubahan apa pun harus disimpan</li>
                  </ul>
                </div>

                <div class="p-4 bg-blue-50 rounded-lg">
                  <h4 class="text-sm font-semibold text-blue-800 uppercase tracking-wider mb-2">Color Groups</h4>
                  <div class="grid grid-cols-2 gap-2 text-sm">
                    <div class="flex items-center">
                      <span class="w-6 h-6 flex items-center justify-center bg-black text-white rounded-full font-bold mr-2">01</span>
                      <span>Black</span>
                    </div>
                    <div class="flex items-center">
                      <span class="w-6 h-6 flex items-center justify-center bg-gray-200 text-gray-800 rounded-full font-bold mr-2">02</span>
                      <span>White</span>
                    </div>
                    <div class="flex items-center">
                      <span class="w-6 h-6 flex items-center justify-center bg-red-500 text-white rounded-full font-bold mr-2">03</span>
                      <span>Red</span>
                    </div>
                    <div class="flex items-center">
                      <span class="w-6 h-6 flex items-center justify-center bg-blue-500 text-white rounded-full font-bold mr-2">04</span>
                      <span>Blue</span>
                    </div>
                    <div class="flex items-center">
                      <span class="w-6 h-6 flex items-center justify-center bg-green-500 text-white rounded-full font-bold mr-2">05</span>
                      <span>Green</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Quick Links -->
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-purple-500">
              <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                <div class="p-2 bg-purple-500 rounded-lg mr-3">
                  <i class="fas fa-link text-white"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Tautan Cepat</h3>
              </div>

              <div class="grid grid-cols-1 gap-3">
                <a href="#" class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                  <div class="p-2 bg-purple-500 rounded-full mr-3">
                    <i class="fas fa-layer-group text-white text-sm"></i>
                  </div>
                  <div>
                    <p class="font-medium text-purple-900">Color Groups</p>
                    <p class="text-xs text-purple-700">Kelola grup warna</p>
                  </div>
                </a>

                <a href="#" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                  <div class="p-2 bg-blue-500 rounded-full mr-3">
                    <i class="fas fa-th-list text-white text-sm"></i>
                  </div>
                  <div>
                    <p class="font-medium text-blue-900">Color Types</p>
                    <p class="text-xs text-blue-700">Lihat tipe warna</p>
                  </div>
                </a>

                <a href="#" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                  <div class="p-2 bg-green-500 rounded-full mr-3">
                    <i class="fas fa-print text-white text-sm"></i>
                  </div>
                  <div>
                    <p class="font-medium text-green-900">Cetak Daftar</p>
                    <p class="text-xs text-green-700">Cetak daftar warna</p>
                  </div>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Color Table Window -->
    <div id="colorTableWindow" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
      <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-3/4 lg:w-2/3 max-w-5xl mx-auto transform transition-transform duration-300" style="max-height: 80vh;">
        <!-- Modal Header - Title Bar -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
          <div class="flex items-center">
            <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
              <i class="fas fa-palette"></i>
            </div>
            <h3 class="text-xl font-semibold">Color Table</h3>
          </div>
          <button type="button" @click="closeColorModal" class="text-white hover:text-gray-200 focus:outline-none transform active:translate-y-px">
            <i class="fas fa-times text-xl"></i>
          </button>
        </div>

        <!-- Table Content -->
        <div class="p-5 overflow-auto" style="max-height: calc(80vh - 130px);">
          <div class="mb-4">
            <div class="relative">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                <i class="fas fa-search"></i>
              </span>
              <input type="text" id="searchColorInput" placeholder="Search colors..."
                  class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
            </div>
          </div>
          
          <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200" id="colorDataTable">
              <thead class="bg-gray-50 sticky top-0">
                <tr>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Color#</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Color Name</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Origin</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CG#</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CG Name</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CG Type</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200 text-xs">
                <tr v-if="!colors || colors.length === 0">
                  <td colspan="6" class="px-4 py-4 text-center text-gray-500">Tidak ada data warna yang tersedia.</td>
                </tr>
                <tr v-else v-for="color in colors" :key="color.color_id" 
                  class="hover:bg-blue-50 cursor-pointer"
                  :data-color-id="color.color_id"
                  :data-color-name="color.color_name"
                  :data-origin="color.origin"
                  :data-cg-id="color.color_group_id"
                  :data-cg-type="color.cg_type || ''"
                  @click="selectRow($event.target.closest('tr'))"
                  @dblclick="openEditColorModal($event.target.closest('tr'))">
                  <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">{{ color.color_id }}</td>
                  <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ color.color_name }}</td>
                  <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ color.origin }}</td>
                  <td class="px-4 py-3 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">{{ color.color_group_id }}</span>
                  </td>
                  <td class="px-4 py-3 whitespace-nowrap text-gray-700">
                    {{ getCGName(color.color_group_id) }}
                  </td>
                  <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ color.cg_type || '' }}</td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Bottom Buttons -->
          <div class="mt-4 grid grid-cols-5 gap-2">
            <button type="button" @click="sortTableDirectly(0)" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
              <i class="fas fa-sort mr-1"></i>By Color#
            </button>
            <button type="button" @click="sortTableDirectly(3)" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
              <i class="fas fa-sort mr-1"></i>By CG# + Color#
            </button>
            <button type="button" @click="sortTableDirectly(5)" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
              <i class="fas fa-sort mr-1"></i>By CG Type + Color#
            </button>
            <button type="button" @click="editSelectedRow" class="py-2 px-3 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded-lg transform active:translate-y-px">
              <i class="fas fa-edit mr-1"></i>Select
            </button>
            <button type="button" @click="closeColorModal" class="py-2 px-3 bg-gray-300 hover:bg-gray-400 text-gray-800 text-xs rounded-lg transform active:translate-y-px">
              <i class="fas fa-times mr-1"></i>Exit
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 hidden">
      <div class="w-12 h-12 border-4 border-solid border-blue-500 border-t-transparent rounded-full animate-spin"></div>
    </div>
  </div>
</template>

<script>
import { Head } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

// Define the color group names mapping
const cgNames = {
  '01': 'BLACK',
  '02': 'WHITE',
  '03': 'RED',
  '04': 'BLUE',
  '05': 'GREEN',
  '06': 'CYAN',
  '07': 'MAGENTA',
  '08': 'PINK'
};

export default {
  components: {
    Head
  },
  layout: null,
  props: {
    colors: {
      type: Array,
      default: () => []
    },
    colorGroups: {
      type: Array,
      default: () => []
    },
    error: String
  },
  setup(props) {
    const selectedColor = ref(null);
    const colorTableVisible = ref(false);
    const editModalVisible = ref(false);

    const seedColors = [
      { color_id: 'C001', color_name: 'Black Standard', origin: '1', color_group_id: '01', cg_type: 'X-Flexo' },
      { color_id: 'C002', color_name: 'White Premium', origin: '2', color_group_id: '02', cg_type: 'X-Flexo' },
      { color_id: 'C003', color_name: 'Red Bright', origin: '1', color_group_id: '03', cg_type: 'X-Flexo' },
      { color_id: 'C004', color_name: 'Blue Ocean', origin: '3', color_group_id: '04', cg_type: 'X-Flexo' },
      { color_id: 'C005', color_name: 'Green Forest', origin: '2', color_group_id: '05', cg_type: 'X-Flexo' }
    ];

    onMounted(() => {
      console.log('Color component mounted');
      console.log('Props received:', props);
      
      // Load the color.js script for modal functionality
      const script = document.createElement('script');
      script.src = '/js/color.js';
      script.async = true;
      document.head.appendChild(script);
    });

    // Method to get color group name from ID
    const getCGName = (cgId) => {
      return cgNames[cgId] || '';
    };

    const openColorModal = () => {
      console.log('Opening color modal');
      colorTableVisible.value = true;
      const modal = document.getElementById('colorTableWindow');
      if (modal) {
        modal.style.display = 'block';
        modal.classList.remove('hidden');
      }
    };

    const closeColorModal = () => {
      console.log('Closing color modal');
      const modal = document.getElementById('colorTableWindow');
      if (modal) {
        modal.style.display = 'none';
        modal.classList.add('hidden');
      }
    };

    const selectRow = (row) => {
      // Remove highlight from all rows
      const allRows = document.querySelectorAll('#colorDataTable tbody tr');
      allRows.forEach((r) => {
        r.classList.remove('bg-blue-600', 'text-white');
      });
      
      // Add highlighting to selected row
      if (row) {
        row.classList.add('bg-blue-600', 'text-white');
      }
    };

    const sortTableDirectly = (columnIndex) => {
      console.log('Sorting by column:', columnIndex);
      // This is just a stub - the actual implementation will use the existing JS functions
      if (typeof window.sortTableDirectly === 'function') {
        window.sortTableDirectly(columnIndex);
      }
    };

    const editSelectedRow = () => {
      console.log('Editing selected row');
      if (typeof window.editSelectedRow === 'function') {
        window.editSelectedRow();
      }
    };

    const loadSeedData = () => {
      console.log('Loading seed data');
      if (typeof window.loadSeedData === 'function') {
        window.loadSeedData();
      }
    };

    return {
      selectedColor,
      colorTableVisible,
      editModalVisible,
      openColorModal,
      closeColorModal,
      loadSeedData,
      selectRow,
      sortTableDirectly,
      editSelectedRow,
      getCGName,
      seedColors
    };
  }
};
</script>
