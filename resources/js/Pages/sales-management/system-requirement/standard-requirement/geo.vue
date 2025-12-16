
<template>
	<AppLayout header="Geo">
		<Head title="Geo Management" />

		<!-- Header Section -->
		<div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
			<div class="max-w-6xl mx-auto">
				<!-- Header Section -->
				<div class="bg-emerald-600 text-white shadow-sm rounded-xl border border-emerald-700 mb-4">
					<div class="px-4 py-3 sm:px-6 flex items-center gap-3">
						<div class="h-9 w-9 rounded-full bg-emerald-500 flex items-center justify-center">
							<i class="fas fa-globe-americas text-white text-lg"></i>
						</div>
						<div>
							<h2 class="text-lg sm:text-xl font-semibold leading-tight">Define Geo</h2>
							<p class="text-xs sm:text-sm text-emerald-100">Define geographical data for specific locations and areas. Use geo codes to group and manage customer locations.</p>
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
										<i class="fas fa-map-marker-alt text-white"></i>
									</div>
									<h3 class="text-xl font-semibold text-gray-800">Geo Management</h3>
								</div>

								<!-- Search Section -->
								<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
									<div class="col-span-2">
										<label class="block text-sm font-medium text-gray-700 mb-1">Geo Code:</label>
										<div class="relative flex">
											<span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
												<i class="fas fa-globe"></i>
											</span>
											<input
												type="text"
												v-model="geoCode"
												@input="searchGeoCode"
												class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
												placeholder="Search or type geo code"
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
												@click="createNewGeo"
												class="w-full flex items-center justify-center px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white rounded transition-colors transform active:translate-y-px"
										>
											<i class="fas fa-plus-circle mr-2"></i> Add New
										</button>
									</div>
								</div>

								<!-- Data Status Information -->
								<div v-if="searchResult" class="mt-4" v-html="searchResult"></div>
								<div v-if="loading" class="mt-4 bg-yellow-100 p-3 rounded">
									<div class="flex items-center">
										<div class="mr-3">
											<div class="animate-spin rounded-full h-6 w-6 border-b-2 border-yellow-700"></div>
										</div>
										<p class="text-sm font-medium text-yellow-800">Loading geo data...</p>
									</div>
								</div>
								<div v-else-if="geos.length === 0" class="mt-4 bg-yellow-100 p-3 rounded">
									<p class="text-sm font-medium text-yellow-800">No geo data available.</p>
									<p class="text-xs text-yellow-700 mt-1">Make sure the database is properly configured and seeders have been run.</p>
									<div class="mt-2">
										<button @click="fetchGeos" class="inline-flex items-center px-3 py-1 bg-emerald-500 hover:bg-emerald-600 text-white text-xs rounded transition-colors transform active:translate-y-px">Reload Data</button>
									</div>
								</div>
								<div v-else class="mt-4 bg-green-100 p-3 rounded">
									<p class="text-sm font-medium text-green-800">Data available: {{ geos.length }} geo locations found.</p>
									<p v-if="selectedGeo" class="text-xs text-green-700 mt-1">
										Selected: <span class="font-semibold">{{ selectedGeo.code }}</span> - {{ selectedGeo.area }} ({{ selectedGeo.country }})
									</p>
								</div>
							</div>
						</div>
						<!-- Right Column - Quick Info -->
						<div class="lg:col-span-1">
							<!-- Geo Info Card -->
							<div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border-t-4 border-teal-500 mb-6">
								<div class="flex items-center mb-4 pb-2 border-b border-gray-200">
									<div class="p-2 bg-teal-500 rounded-lg mr-3">
										<i class="fas fa-info-circle text-white"></i>
									</div>
									<h3 class="text-lg font-semibold text-gray-800">Geo Information</h3>
								</div>

								<div class="space-y-4">
									<div class="p-4 bg-teal-50 rounded-lg">
										<h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2">Instructions</h4>
										<ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
											<li>Geo code must be unique</li>
											<li>Use the <span class="font-medium">search</span> button to select a geo location</li>
											<li>Area determines the geographical characteristics</li>
											<li>Any changes must be saved</li>
										</ul>
									</div>

									<div class="p-4 bg-blue-50 rounded-lg">
										<h4 class="text-sm font-semibold text-blue-800 uppercase tracking-wider mb-2">Geo Areas</h4>
										<div class="grid grid-cols-2 gap-2 text-sm">
											<div class="flex items-center">
												<span class="w-6 h-6 flex items-center justify-center bg-blue-600 text-white rounded-full font-bold mr-2">ID</span>
												<span>Indonesia</span>
											</div>
											<div class="flex items-center">
												<span class="w-6 h-6 flex items-center justify-center bg-red-500 text-white rounded-full font-bold mr-2">MY</span>
												<span>Malaysia</span>
											</div>
											<div class="flex items-center">
												<span class="w-6 h-6 flex items-center justify-center bg-green-500 text-white rounded-full font-bold mr-2">SG</span>
												<span>Singapore</span>
											</div>
											<div class="flex items-center">
												<span class="w-6 h-6 flex items-center justify-center bg-yellow-500 text-white rounded-full font-bold mr-2">TH</span>
												<span>Thailand</span>
											</div>
											<div class="flex items-center">
												<span class="w-6 h-6 flex items-center justify-center bg-purple-500 text-white rounded-full font-bold mr-2">VN</span>
												<span>Vietnam</span>
											</div>
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
		<GeoModal
			:show="showModal"
			:geos="geos"
			@close="showModal = false"
			@select="onGeoSelected"
		/>

		<!-- Edit Modal -->
		<div v-if="showEditModal" class="fixed inset-0 z-50 bg-black bg-opacity-30 flex items-center justify-center">
			<div class="bg-white rounded-xl shadow-lg border border-gray-200 w-11/12 md:w-2/5 max-w-md mx-auto">
				<div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 bg-emerald-600 text-white rounded-t-xl">
					<div class="flex items-center">
						<div class="p-2 bg-white bg-opacity-20 rounded-lg mr-3">
							<i class="fas fa-globe-americas"></i>
						</div>
						<h3 class="text-sm font-semibold">{{ isCreating ? 'Create Geo' : 'Edit Geo' }}</h3>
					</div>
					<button type="button" @click="closeEditModal" class="text-white hover:text-gray-200">
						<i class="fas fa-times text-lg"></i>
					</button>
				</div>
				<div class="p-5">
					<form @submit.prevent="reviewGeoData" class="space-y-4">
						<div class="grid grid-cols-1 gap-4">
							<div>
								<label class="block text-sm font-medium text-gray-700 mb-1">Geo Code:</label>
								<input v-model="editForm.code" type="text" class="block w-full rounded-md border-gray-300 shadow-sm text-sm" :class="{ 'bg-gray-100': !isCreating }" :readonly="!isCreating" required>
							</div>
							<div>
								<label class="block text-sm font-medium text-gray-700 mb-1">Country:</label>
								<input v-model="editForm.country" type="text" class="block w-full rounded-md border-gray-300 shadow-sm text-sm" required>
							</div>
							<div>
								<label class="block text-sm font-medium text-gray-700 mb-1">State:</label>
								<input v-model="editForm.state" type="text" class="block w-full rounded-md border-gray-300 shadow-sm text-sm" required>
							</div>
							<div>
								<label class="block text-sm font-medium text-gray-700 mb-1">Town:</label>
								<input v-model="editForm.town" type="text" class="block w-full rounded-md border-gray-300 shadow-sm text-sm" required>
							</div>
							<div>
								<label class="block text-sm font-medium text-gray-700 mb-1">Town Section:</label>
								<input v-model="editForm.town_section" type="text" class="block w-full rounded-md border-gray-300 shadow-sm text-sm" required>
							</div>
							<div>
								<label class="block text-sm font-medium text-gray-700 mb-1">Area:</label>
								<input v-model="editForm.area" type="text" class="block w-full rounded-md border-gray-300 shadow-sm text-sm" required>
							</div>
							<div>
								<label class="block text-sm font-medium text-gray-700 mb-1">Note:</label>
								<div class="border border-gray-200 rounded-md p-3 bg-gray-50 h-16 overflow-auto text-xs text-gray-600">
									<p>Geo Area must be 2-4 characters and specific to a country/region</p>
								</div>
							</div>
						</div>
						<div class="flex justify-between mt-5 pt-4 border-t border-gray-200">
							<button type="button" v-if="!isCreating" @click="obsoleteGeo" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 text-sm font-medium">
								<i class="fas fa-ban mr-2"></i>Obsolete
							</button>
							<div v-else class="w-24"></div>
							<div class="flex space-x-3">
								<button type="button" @click="closeEditModal" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 text-sm font-medium">Cancel</button>
								<button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 text-sm font-medium">Review</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>

		<!-- Review Modal -->
		<div v-if="showReviewModal" class="fixed inset-0 z-50 bg-black bg-opacity-30 flex items-center justify-center">
			<div class="bg-white rounded-xl shadow-lg border border-gray-200 w-11/12 md:w-2/5 max-w-md mx-auto">
				<div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 bg-emerald-600 text-white rounded-t-xl">
					<div class="flex items-center">
						<div class="p-2 bg-white bg-opacity-20 rounded-lg mr-3">
							<i class="fas fa-check-circle"></i>
						</div>
						<h3 class="text-sm font-semibold">Review Geo Data</h3>
					</div>
					<button type="button" @click="showReviewModal = false" class="text-white hover:text-gray-200">
						<i class="fas fa-times text-lg"></i>
					</button>
				</div>
				<div class="p-5">
					<div class="mb-4 p-4 bg-gray-50 rounded-lg">
						<div class="grid grid-cols-2 gap-y-3 text-sm">
							<div class="font-medium text-gray-500">Geo Code:</div>
							<div class="font-semibold text-gray-900">{{ editForm.code }}</div>
							
							<div class="font-medium text-gray-500">Country:</div>
							<div class="font-semibold text-gray-900">{{ editForm.country }}</div>
							
							<div class="font-medium text-gray-500">State:</div>
							<div class="font-semibold text-gray-900">{{ editForm.state }}</div>
							
							<div class="font-medium text-gray-500">Town:</div>
							<div class="font-semibold text-gray-900">{{ editForm.town }}</div>
							
							<div class="font-medium text-gray-500">Town Section:</div>
							<div class="font-semibold text-gray-900">{{ editForm.town_section }}</div>
							
							<div class="font-medium text-gray-500">Area:</div>
							<div class="font-semibold text-gray-900">{{ editForm.area }}</div>
						</div>
					</div>
					
					<p class="text-sm text-gray-600 mb-4">Confirm the above data to save changes.</p>
						
					<div class="flex justify-end space-x-3 mt-5 pt-4 border-t border-gray-200">
						<button type="button" @click="showReviewModal = false" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 text-sm font-medium">
							<i class="fas fa-arrow-left mr-2"></i>Back
						</button>
						<button type="button" @click="saveGeoData" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 text-sm font-medium">
							<i class="fas fa-save mr-2"></i>Confirm
						</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Loading Overlay -->
		<div v-if="saving" class="fixed inset-0 z-50 bg-black bg-opacity-30 flex justify-center items-center">
			<div class="w-10 h-10 border-4 border-solid border-emerald-500 border-t-transparent rounded-full animate-spin"></div>
		</div>
		
		<!-- Notification Toast -->
		<div v-if="notification.show" class="fixed bottom-4 right-4 z-50 shadow-lg rounded-lg transition-all duration-300"
			 :class="{
				 'bg-green-100 border-l-4 border-green-500': notification.type === 'success',
				 'bg-red-100 border-l-4 border-red-500': notification.type === 'error',
				 'bg-yellow-100 border-l-4 border-yellow-500': notification.type === 'warning'
			 }">
			<div class="p-4 flex items-center">
				<div class="mr-3">
					<i v-if="notification.type === 'success'" class="fas fa-check-circle text-green-500 text-xl"></i>
					<i v-else-if="notification.type === 'error'" class="fas fa-exclamation-circle text-red-500 text-xl"></i>
					<i v-else class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
				</div>
				<div>
					<p :class="{
						'text-green-800': notification.type === 'success',
						'text-red-800': notification.type === 'error',
						'text-yellow-800': notification.type === 'warning'
					}">{{ notification.message }}</p>
				</div>
			</div>
		</div>
	</AppLayout>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import GeoModal from '@/Components/geo-modal.vue';

// Get the header from props
const props = defineProps({
    header: {
        type: String,
        default: 'Geo Management'
    },
    geos: {
        type: Array,
        default: () => []
    }
});

const geos = ref(props.geos || []);
const loading = ref(false);
const saving = ref(false);
const showModal = ref(false);
const showEditModal = ref(false);
const showReviewModal = ref(false);
const selectedGeo = ref(null);
const geoCode = ref('');
const searchResult = ref('');
const editForm = ref({ 
    code: '', 
    country: '',
    state: '',
    town: '',
    town_section: '',
    area: ''
});
const isCreating = ref(false);
const notification = ref({ show: false, message: '', type: 'success' });

// Fetch geos from API
const fetchGeos = async () => {
    loading.value = true;
    try {
        const res = await fetch('/api/geo', { 
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
            geos.value = data;
        } else {
            geos.value = [];
            console.error('Unexpected data format:', data);
        }
    } catch (e) {
        console.error('Error fetching geo data:', e);
        geos.value = [];
    } finally {
        loading.value = false;
    }
};

// Watch for props changes
watch(() => props.geos, (newGeos) => {
    if (newGeos && newGeos.length > 0) {
        geos.value = newGeos;
    }
}, { immediate: true });

onMounted(() => {
    if (geos.value.length === 0) {
        fetchGeos();
    }
});

// Search for geo code
const searchGeoCode = () => {
    if (!geoCode.value) {
        searchResult.value = '';
        return;
    }
    
    const matches = geos.value.filter(geo => 
        geo.code.toLowerCase().includes(geoCode.value.toLowerCase()) ||
        geo.country.toLowerCase().includes(geoCode.value.toLowerCase()) ||
        geo.area.toLowerCase().includes(geoCode.value.toLowerCase())
    );
    
    if (matches.length > 0) {
        if (matches.length === 1 && matches[0].code.toLowerCase() === geoCode.value.toLowerCase()) {
            // Exact match - show details and edit button
            searchResult.value = `
                <div class="p-3 bg-green-100 rounded-lg mt-2">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm text-green-800">Data found: <span class="font-semibold">${matches[0].code}</span> - ${matches[0].area}</p>
                            <p class="text-xs text-green-700">${matches[0].country}, ${matches[0].state}, ${matches[0].town}</p>
                        </div>
                    </div>
                </div>
            `;
            selectedGeo.value = matches[0];
        } else if (matches.length <= 5) {
            // Multiple matches (up to 5) - show list
            let html = `<div class="p-3 bg-blue-100 rounded-lg mt-2">
                <p class="text-sm text-blue-800 mb-2">Multiple codes found:</p>
                <ul class="space-y-1">`;
            
            matches.forEach(match => {
                html += `<li class="flex justify-between items-center">
                    <button class="text-sm text-blue-700 hover:text-blue-900 hover:underline focus:outline-none">
                        ${match.code} - ${match.area} (${match.country})
                    </button>
                </li>`;
            });
            
            html += `</ul></div>`;
            searchResult.value = html;
        } else {
            // Too many matches
            searchResult.value = `
                <div class="p-3 bg-yellow-100 rounded-lg mt-2">
                    <p class="text-sm text-yellow-800">Found ${matches.length} matching codes. Please be more specific or use the search button.</p>
                </div>
            `;
        }
    } else {
        // No matches - show create option
        searchResult.value = `
            <div class="p-3 bg-red-100 rounded-lg mt-2">
                <div class="flex justify-between items-start">
                    <p class="text-sm text-red-800">No geo code "${geoCode.value}" found. Check the code or create new data.</p>
                </div>
            </div>
        `;
    }
};

const onGeoSelected = (geo) => {
    selectedGeo.value = geo;
    geoCode.value = geo.code;
    showModal.value = false;
    
    // Update search result
    searchResult.value = `
        <div class="p-3 bg-green-100 rounded-lg mt-2">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-green-800">Data found: <span class="font-semibold">${geo.code}</span> - ${geo.area}</p>
                    <p class="text-xs text-green-700">${geo.country}, ${geo.state}, ${geo.town}</p>
                </div>
            </div>
        </div>
    `;
    
    // Automatically open the edit modal for the selected row
    isCreating.value = false;
    editForm.value = { ...geo };
    showEditModal.value = true;
};

const editSelectedGeo = () => {
    if (selectedGeo.value) {
        isCreating.value = false;
        editForm.value = { ...selectedGeo.value };
        showEditModal.value = true;
    } else {
        showNotification('Please select a geo location first', 'error');
    }
};

const createNewGeo = () => {
    isCreating.value = true;
    editForm.value = { 
        code: '', 
        country: '',
        state: '',
        town: '',
        town_section: '',
        area: ''
    };
    showEditModal.value = true;
};

const closeEditModal = () => {
    showEditModal.value = false;
    isCreating.value = false;
};

const reviewGeoData = () => {
    // Validate the form fields first
    if (!editForm.value.code || !editForm.value.country || !editForm.value.area) {
        showNotification('Please fill in all required fields', 'error');
        return;
    }
    
    showEditModal.value = false;
    showReviewModal.value = true;
};

const saveGeoData = async () => {
    saving.value = true;
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        
        if (!csrfToken) {
            throw new Error('CSRF token not found');
        }
        
        // Different API call for create vs update
        let url = isCreating.value ? '/api/geo' : `/api/geo/${editForm.value.code}`;
        let method = isCreating.value ? 'POST' : 'PUT';
        
        console.log('Making API request to:', url, 'with method:', method);
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                code: editForm.value.code,
                country: editForm.value.country,
                state: editForm.value.state,
                town: editForm.value.town,
                town_section: editForm.value.town_section,
                area: editForm.value.area
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Update the local data
            if (isCreating.value) {
                // Add new geo to the list
                geos.value.push({...editForm.value});
                showNotification('Geo data created successfully', 'success');
            } else {
                // Update existing geo in the list
                const index = geos.value.findIndex(g => g.code === editForm.value.code);
                if (index !== -1) {
                    geos.value[index] = {...editForm.value};
                }
                showNotification('Geo data updated successfully', 'success');
            }
            
            // Set the code in the input field
            geoCode.value = editForm.value.code;
            
            // Update selected geo
            selectedGeo.value = {...editForm.value};
            
            // Update search result
            searchResult.value = `
                <div class="p-3 bg-green-100 rounded-lg mt-2">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm text-green-800">Data ${isCreating.value ? 'created' : 'updated'}: <span class="font-semibold">${editForm.value.code}</span> - ${editForm.value.area}</p>
                            <p class="text-xs text-green-700">${editForm.value.country}, ${editForm.value.state}, ${editForm.value.town}</p>
                        </div>
                    </div>
                </div>
            `;
            
            // Close modal
            showReviewModal.value = false;
        } else {
            showNotification('Error: ' + (result.message || 'Unknown error'), 'error');
        }
    } catch (e) {
        console.error('Error saving geo data:', e);
        showNotification('Error saving geo data. Please try again.', 'error');
    } finally {
        saving.value = false;
    }
};

const obsoleteGeo = async () => {
    if (!confirm(`Are you sure you want to obsolete geo "${editForm.value.code}"? This will mark it as inactive and it will no longer appear in selection lists.`)) {
        return;
    }
    
    saving.value = true;
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        
        if (!csrfToken) {
            throw new Error('CSRF token not found');
        }
        
        // Update status to 'Obs' (Obsolete) instead of deleting
        const response = await fetch(`/api/geo/${editForm.value.code}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                status: 'Obs'
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Remove the item from the local array (since obsoleted items shouldn't appear)
            geos.value = geos.value.filter(geo => geo.code !== editForm.value.code);
            
            if (selectedGeo.value && selectedGeo.value.code === editForm.value.code) {
                selectedGeo.value = null;
                geoCode.value = '';
                searchResult.value = '';
            }
            
            // Close modals
            closeEditModal();
            showReviewModal.value = false;
            
            showNotification(`Geo "${editForm.value.code}" has been obsoleted successfully. Use Obsolete/Unobsolete Geo menu to reactivate.`, 'success');
        } else {
            showNotification('Error obsoleting geo data: ' + (result.message || 'Unknown error'), 'error');
        }
    } catch (e) {
        console.error('Error obsoleting geo data:', e);
        showNotification('Error obsoleting geo data. Please try again.', 'error');
    } finally {
        saving.value = false;
    }
};

const showNotification = (message, type = 'success') => {
    notification.value = {
        show: true,
        message,
        type
    };
    
    setTimeout(() => {
        notification.value.show = false;
    }, 3000);
};
</script>
