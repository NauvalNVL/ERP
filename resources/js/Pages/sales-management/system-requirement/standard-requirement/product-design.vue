<template>
	<AppLayout header="Product Design">
		<Head title="Product Design Management" />

		<!-- Header & Main Layout -->
		<div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
			<div class="max-w-6xl mx-auto">
				<!-- Header Section -->
				<div class="bg-gradient-to-r from-green-600 to-green-700 text-white shadow-sm rounded-xl border border-green-700 mb-4">
					<div class="px-4 py-3 sm:px-6 flex items-center gap-3">
						<div class="h-9 w-9 rounded-full bg-green-500 flex items-center justify-center">
							<i class="fas fa-drafting-compass text-white text-lg"></i>
						</div>
						<div>
							<h2 class="text-lg sm:text-xl font-semibold leading-tight">Define Product Design</h2>
							<p class="text-xs sm:text-sm text-green-100">Define product designs for product categorization</p>
						</div>
					</div>
				</div>

				<div class="bg-white shadow-sm rounded-xl border border-gray-200 p-4 sm:p-6 mb-6">
					<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
						<!-- Left Column -->
						<div class="lg:col-span-2">
							<div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border-t-4 border-emerald-500">
								<div class="flex items-center mb-6 pb-2 border-b border-gray-200">
									<div class="p-2 bg-emerald-500 rounded-lg mr-3">
										<i class="fas fa-edit text-white"></i>
									</div>
									<h3 class="text-xl font-semibold text-gray-800">Product Design Management</h3>
								</div>

								<!-- Search Section -->
								<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
									<div class="col-span-2">
										<label class="block text-sm font-medium text-gray-700 mb-1">Design Code:</label>
										<div class="relative flex">
											<span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
												<i class="fas fa-drafting-compass"></i>
											</span>
											<input
												type="text"
												v-model="searchQuery"
												class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
											/>
											<button
												type="button"
												@click="showModal = true"
												class="inline-flex items-center px-3 py-2 border border-l-0 border-emerald-500 bg-emerald-500 hover:bg-emerald-600 text-white rounded-r-md transition-colors transform active:translate-y-px"
											>
												<i class="fas fa-table"></i>
											</button>
										</div>
									</div>
									<div class="col-span-1">
										<label class="block text-sm font-medium text-gray-700 mb-1">Action:</label>
										<button
											type="button"
											@click="openModalForCreate"
											class="w-full flex items-center justify-center px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white rounded transition-colors transform active:translate-y-px"
										>
											<i class="fas fa-plus-circle mr-2"></i> Add New
										</button>
									</div>
								</div>

								<!-- Data Status Information -->
								<div v-if="loading" class="mt-4 bg-yellow-100 p-3 rounded">
									<div class="flex items-center">
										<div class="mr-3">
											<div class="animate-spin rounded-full h-6 w-6 border-b-2 border-yellow-700"></div>
										</div>
										<p class="text-sm font-medium text-yellow-800">Loading product design data...</p>
									</div>
								</div>
								<div v-else-if="designs.length === 0" class="mt-4 bg-yellow-100 p-3 rounded">
									<p class="text-sm font-medium text-yellow-800">No product design data available.</p>
									<p class="text-xs text-yellow-700 mt-1">Make sure the database is properly configured and seeders have been run.</p>
								</div>
								<div v-else class="mt-4 bg-green-100 p-3 rounded">
									<p class="text-sm font-medium text-green-800">Data available: {{ designs.length }} designs found.</p>
									<p v-if="selectedRow" class="text-xs text-green-700 mt-1">
										Selected: <span class="font-semibold">{{ selectedRow.pd_code }}</span> - {{ selectedRow.pd_name }}
									</p>
								</div>
							</div>
						</div>

						<!-- Right Column - Quick Info -->
						<div class="lg:col-span-1">
							<!-- Product Design Info Card -->
							<div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border-t-4 border-teal-500 mb-6">
								<div class="flex items-center mb-4 pb-2 border-b border-gray-200">
									<div class="p-2 bg-teal-500 rounded-lg mr-3">
										<i class="fas fa-info-circle text-white"></i>
									</div>
									<h3 class="text-lg font-semibold text-gray-800">Product Design Info</h3>
								</div>

								<div class="space-y-4">
									<div class="p-4 bg-teal-50 rounded-lg">
										<h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2">Instructions</h4>
										<ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
											<li>Product design code must be unique</li>
											<li>Use the <span class="font-medium">search</span> button to select a design</li>
											<li>Each design is linked to a specific product</li>
											<li>Dimensions should be specified in millimeters</li>
										</ul>
									</div>

									<div
											class="p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors"
											:class="{ 'cursor-pointer': selectedRow }"
											@click="selectedRow ? showModal = true : null"
										>
											<div class="flex justify-between items-center mb-2">
												<h4 class="text-sm font-semibold text-blue-800 uppercase tracking-wider">Design Information</h4>
												<button
													v-if="selectedRow"
													@click.stop="showModal = true"
													class="text-blue-600 hover:text-blue-800 p-1 rounded-full hover:bg-blue-200"
												>
													<i class="fas fa-edit"></i>
												</button>
											</div>
											<div class="space-y-2 text-sm">
												<div v-if="selectedRow" class="grid grid-cols-2 gap-2">
													<div class="font-medium text-gray-700">Code:</div>
													<div>{{ selectedRow.pd_code }}</div>
													<div class="font-medium text-gray-700">Name:</div>
													<div>{{ selectedRow.pd_name }}</div>
													<div class="font-medium text-gray-700">Product:</div>
													<div>{{ selectedRow.product }}</div>
													<div class="font-medium text-gray-700">IDC:</div>
													<div>{{ selectedRow.idc }}</div>
												</div>
												<div v-else class="text-gray-500 italic">
													Select a design to view details
												</div>
											</div>
											<div v-if="selectedRow" class="mt-3 text-xs text-blue-600 flex items-center">
												<i class="fas fa-info-circle mr-1"></i>
												Click the browse button to edit this design
											</div>
									</div>
								</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		</div>

		<!-- Modal Table -->
		<ProductDesignModal
			:show="showModal"
			:designs="designs"
			:products="products"
			:doubleClickAction="'edit'"
			:isCreating="isCreatingNewDesign"
			@close="closeModal"
			@select="onDesignSelected"
			@data-changed="fetchDesigns"
		/>
	</AppLayout>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import ProductDesignModal from '@/Components/product-design-modal.vue';

// Get the header from props
const props = defineProps({
    header: {
        type: String,
        default: 'Product Design Management'
    }
});

const designs = ref([]);
const products = ref([]);
const loading = ref(false);
const showModal = ref(false);
const selectedRow = ref(null);
const searchQuery = ref('');
const isCreatingNewDesign = ref(false);

const fetchDesigns = async () => {
    loading.value = true;
    try {
        const res = await fetch('/api/product-designs', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!res.ok) {
            throw new Error('Network response was not ok');
        }

        const data = await res.json();

        if (Array.isArray(data)) {
            designs.value = data;
        } else {
            designs.value = [];
            console.error('Unexpected data format:', data);
        }
    } catch (e) {
        console.error('Error fetching product designs:', e);
        designs.value = [];
    } finally {
        loading.value = false;
    }
};

const fetchProducts = async () => {
    try {
        const res = await fetch('/api/products', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!res.ok) {
            throw new Error('Network response was not ok');
        }

        const data = await res.json();

        if (Array.isArray(data)) {
            products.value = data;
        } else {
            products.value = [];
            console.error('Unexpected data format:', data);
        }
    } catch (e) {
        console.error('Error fetching products:', e);
        products.value = [];
    }
};

onMounted(() => {
    fetchDesigns();
    fetchProducts();
});

// Watch for changes in search query to filter the data
watch(searchQuery, (newQuery) => {
    if (newQuery && designs.value.length > 0) {
        const foundDesign = designs.value.find(design =>
            design.pd_code.toLowerCase().includes(newQuery.toLowerCase()) ||
            design.pd_name.toLowerCase().includes(newQuery.toLowerCase())
        );

        if (foundDesign) {
            selectedRow.value = foundDesign;
        }
    }
});

const onDesignSelected = (design) => {
    selectedRow.value = design;
    searchQuery.value = design.pd_code;
};

const openModalForCreate = () => {
    isCreatingNewDesign.value = true;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    isCreatingNewDesign.value = false;
};
</script>

<style scoped>
/* Custom styling for the edit modal */
.fixed.inset-0.z-50 .bg-white {
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Toolbar button styles */
.px-3.py-1.bg-green-500,
.px-3.py-1.bg-yellow-500 {
  transition: all 0.2s;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.px-3.py-1.bg-green-500:hover,
.px-3.py-1.bg-yellow-500:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Form section styling */
.border.border-gray-200.rounded-lg {
  transition: all 0.3s;
}

.border.border-gray-200.rounded-lg:hover {
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

/* Form heading styles */
.font-medium.text-gray-700.mb-4.pb-2.border-b {
  color: #059669; /* emerald-600 */
  font-weight: 600;
}

/* Radio button styling */
.inline-flex.items-center input[type="radio"] {
  cursor: pointer;
}


.inline-flex.items-center input[type="radio"]:checked {
  background-color: #059669; /* emerald-600 */
  border-color: #059669;
}

/* Input focus effects */
input:focus, select:focus {
  box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.25); /* emerald-500 */
  transition: all 0.2s;
}

/* Button hover animations */
.px-4.py-2.bg-blue-500,
.px-4.py-2.bg-red-500,
.px-4.py-2.bg-gray-200 {
  transition: all 0.2s;
}

.px-4.py-2.bg-blue-500:hover,
.px-4.py-2.bg-red-500:hover,
.px-4.py-2.bg-gray-200:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Custom scrollbar for the modal */
.bg-gray-50 {
  scrollbar-width: thin;
  scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
}

.bg-gray-50::-webkit-scrollbar {
  width: 8px;
}

.bg-gray-50::-webkit-scrollbar-track {
  background: transparent;
}

.bg-gray-50::-webkit-scrollbar-thumb {
  background-color: rgba(156, 163, 175, 0.5);
  border-radius: 20px;
}

/* Label styling */
.text-sm.font-medium.text-gray-700 {
  font-weight: 600;
  color: #374151;
}

/* Help text styling */
.text-sm.text-gray-500, .text-xs.text-gray-500 {
  font-style: italic;
}

/* Input Weight explanation text */
.input-weight-explanation {
  display: block !important;
  visibility: visible !important;
  opacity: 1 !important;
  color: #4B5563 !important;
  margin-top: 4px;
  font-size: 0.75rem;
  font-style: normal !important;
  position: relative;
  z-index: 50;
}
</style>
