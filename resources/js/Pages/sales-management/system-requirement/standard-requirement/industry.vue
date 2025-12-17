<template>
	<AppLayout header="Industry">
		<Head title="Industry Management" />

		<!-- Page Container -->
		<div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
			<div class="max-w-6xl mx-auto">
				<!-- Header Section -->
				<div class="bg-emerald-600 text-white shadow-sm rounded-xl border border-emerald-700 mb-4">
					<div class="px-4 py-3 sm:px-6 flex items-center gap-3">
						<div class="h-9 w-9 rounded-full bg-emerald-500 flex items-center justify-center">
							<i class="fas fa-industry text-white text-lg"></i>
						</div>
						<div>
							<h2 class="text-lg sm:text-xl font-semibold leading-tight">Define Industry</h2>
							<p class="text-xs sm:text-sm text-emerald-100">Define industries for customer and product categorization. Use industry codes (max 4 chars) to group customers and products.</p>
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
										<i class="fas fa-cogs text-white"></i>
									</div>
									<h3 class="text-xl font-semibold text-gray-800">Industry Management</h3>
								</div>

								<!-- Search Section -->
								<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
									<div class="col-span-2">
										<label class="block text-sm font-medium text-gray-700 mb-1">Industry Code:</label>
										<div class="relative flex">
											<span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
												<i class="fas fa-industry"></i>
											</span>
											<input
												type="text"
												v-model="industryCode"
												@input="searchIndustryCode"
												@keyup.enter="handleSearchAndCreate"
												class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
												placeholder="Enter industry code"
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
										<label class="block text-sm font-medium text-gray-700 mb-1">Record:</label>
										<button
												type="button"
												@click="createNewIndustry"
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
										<p class="text-sm font-medium text-yellow-800">Loading industry data...</p>
									</div>
								</div>
								<div v-else-if="industries.length === 0" class="mt-4 bg-yellow-100 p-3 rounded">
									<p class="text-sm font-medium text-yellow-800">No industry data available.</p>
									<p class="text-xs text-yellow-700 mt-1">Data will be automatically loaded when available.</p>
								</div>
								<div v-else class="mt-4 bg-green-100 p-3 rounded">
									<p class="text-sm font-medium text-green-800">Data available: {{ industries.length }} industries found.</p>
									<p v-if="selectedIndustry" class="text-xs text-green-700 mt-1">
										Selected: <span class="font-semibold">{{ selectedIndustry.code }}</span> - {{ selectedIndustry.name }}
									</p>
								</div>
							</div>
						</div>
						<!-- Right Column - Quick Info -->
						<div class="lg:col-span-1">
							<!-- Industry Info Card -->
							<div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border-t-4 border-teal-500 mb-6">
								<div class="flex items-center mb-4 pb-2 border-b border-gray-200">
									<div class="p-2 bg-teal-500 rounded-lg mr-3">
										<i class="fas fa-info-circle text-white"></i>
									</div>
									<h3 class="text-lg font-semibold text-gray-800">Industry Information</h3>
								</div>

								<div class="space-y-4">
									<div class="p-4 bg-teal-50 rounded-lg">
										<h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2">Instructions</h4>
										<ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
											<li>Industry code must be unique and maximum 4 characters</li>
											<li>Use the <span class="font-medium">Add New</span> button to add a new industry</li>
											<li>Industry name should be descriptive and specific</li>
											<li>Industries are used for customer categorization</li>
										</ul>
									</div>

									<div class="p-4 bg-blue-50 rounded-lg">
										<h4 class="text-sm font-semibold text-blue-800 uppercase tracking-wider mb-2">Common Industries</h4>
										<div class="grid grid-cols-2 gap-2 text-sm">
											<div class="flex items-center">
												<span class="w-6 h-6 flex items-center justify-center bg-emerald-600 text-white rounded-full font-bold mr-2">F</span>
												<span>Food</span>
											</div>
											<div class="flex items-center">
												<span class="w-6 h-6 flex items-center justify-center bg-red-500 text-white rounded-full font-bold mr-2">E</span>
												<span>Electronics</span>
											</div>
											<div class="flex items-center">
												<span class="w-6 h-6 flex items-center justify-center bg-green-500 text-white rounded-full font-bold mr-2">P</span>
												<span>Pharmaceuticals</span>
											</div>
											<div class="flex items-center">
												<span class="w-6 h-6 flex items-center justify-center bg-yellow-500 text-white rounded-full font-bold mr-2">A</span>
												<span>Automotive</span>
											</div>
											<div class="flex items-center">
												<span class="w-6 h-6 flex items-center justify-center bg-purple-500 text-white rounded-full font-bold mr-2">R</span>
												<span>Retail</span>
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
		<IndustryModal
			:show="showModal"
			:industries="industries"
			@close="showModal = false"
			@select="onIndustrySelected"
			@edit="onIndustryEdit"
		/>

		<!-- Edit/Create Modal -->
		<div v-if="showEditModal" class="fixed inset-0 z-50 bg-black bg-opacity-30 flex items-center justify-center">
			<div class="bg-white rounded-xl shadow-lg border border-gray-200 w-11/12 md:w-2/5 max-w-md mx-auto">
				<div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 bg-emerald-600 text-white rounded-t-xl">
					<div class="flex items-center">
						<div class="p-2 bg-white bg-opacity-20 rounded-lg mr-3">
							<i class="fas fa-industry"></i>
						</div>
						<h3 class="text-sm font-semibold">{{ isCreating ? 'Add Industry' : 'Edit Industry' }}</h3>
					</div>
					<button type="button" @click="closeEditModal" class="text-white hover:text-gray-200">
						<i class="fas fa-times text-lg"></i>
					</button>
				</div>
				<div class="p-5">
					<div v-if="!isCreating" class="mb-4 text-sm text-emerald-700 bg-emerald-50 border border-emerald-100 p-3 rounded-lg">
						<p><i class="fas fa-info-circle mr-2"></i> You are editing industry <span class="font-bold">{{ editForm.code }}</span></p>
					</div>
					<form @submit.prevent="saveIndustryChanges" class="space-y-4">
						<div class="grid grid-cols-1 gap-4">
							<div>
								<label class="block text-sm font-medium text-gray-700 mb-1">Industry Code:</label>
								<div class="relative">
									<span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
										<i class="fas fa-hashtag"></i>
									</span>
									<input
										v-model="editForm.code"
										type="text"
										maxlength="4"
										class="pl-10 block w-full rounded-md border-gray-300 shadow-sm text-sm"
										:class="{ 'bg-gray-100': !isCreating }"
										:readonly="!isCreating"
										required
									/>
								</div>
							</div>
							<div>
								<label class="block text-sm font-medium text-gray-700 mb-1">Industry Name:</label>
								<div class="relative">
									<span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
										<i class="fas fa-font"></i>
									</span>
									<input
										v-model="editForm.name"
										type="text"
										maxlength="100"
										class="pl-10 block w-full rounded-md border-gray-300 shadow-sm text-sm"
										required
									/>
								</div>
							</div>
							<div>
								<label class="block text-sm font-medium text-gray-700 mb-1">Note:</label>
								<div class="border border-gray-200 rounded-md p-3 bg-gray-50 h-16 overflow-auto text-xs text-gray-600">
									<p>Industry code maximum 4 characters. Industry name maximum 100 characters.</p>
								</div>
							</div>
						</div>
						<div class="flex justify-between mt-5 pt-4 border-t border-gray-200">
							<button
									type="button"
									v-if="!isCreating"
									@click="deleteIndustry"
									class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 text-sm font-medium flex items-center"
							>
								<i class="fas fa-ban mr-2"></i>
								Obsolete
							</button>
							<div v-else class="w-24"></div>
							<div class="flex space-x-3">
								<button
										type="button"
										@click="closeEditModal"
										class="px-4 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 text-sm font-medium"
									>
										Cancel
									</button>
									<button
										type="submit"
										class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium"
									>
										Save
									</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>

		<!-- Loading Overlay -->
		<div v-if="saving" class="fixed inset-0 z-50 bg-black bg-opacity-30 flex justify-center items-center">
			<div class="w-10 h-10 border-4 border-solid border-emerald-500 border-t-transparent rounded-full animate-spin"></div>
		</div>

		<!-- Notification Toast -->
		<div
			v-if="notification.show"
			class="fixed bottom-4 right-4 z-50 shadow-lg rounded-lg transition-all duration-300"
			:class="{
				'bg-green-100 border-l-4 border-green-500': notification.type === 'success',
				'bg-red-100 border-l-4 border-red-500': notification.type === 'error',
				'bg-yellow-100 border-l-4 border-yellow-500': notification.type === 'warning'
			}"
		>
			<div class="p-4 flex items-center">
				<div class="mr-3">
					<i v-if="notification.type === 'success'" class="fas fa-check-circle text-green-500 text-xl"></i>
					<i v-else-if="notification.type === 'error'" class="fas fa-exclamation-circle text-red-500 text-xl"></i>
					<i v-else class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
				</div>
				<div>
					<p
						:class="{
							'text-green-800': notification.type === 'success',
							'text-red-800': notification.type === 'error',
							'text-yellow-800': notification.type === 'warning'
						}"
					>
						{{ notification.message }}
					</p>
				</div>
			</div>
		</div>
	</AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import IndustryModal from '@/Components/industry-modal.vue';

// Get the header from props
const props = defineProps({
    header: {
        type: String,
        default: 'Industry Management'
    },
    industries: {
        type: Array,
        default: () => []
    }
});

const isActiveIndustry = (industry) => {
    const status = industry?.status ?? industry?.STATUS;
    if (status === undefined || status === null || String(status).trim() === '') return true;

    const s = String(status).trim().toLowerCase();
    if (s === 'act' || s === 'active' || s === 'a' || s === 'y' || s === '1' || s === 'true') return true;
    if (s === 'obs' || s === 'obsolete' || s === 'inactive' || s === 'i' || s === 'n' || s === '0' || s === 'false') return false;

    return true;
};

const industries = ref((props.industries || []).filter(isActiveIndustry));
const loading = ref(false);
const saving = ref(false);
const showModal = ref(false);
const showEditModal = ref(false);
const selectedIndustry = ref(null);
const industryCode = ref('');
const searchResult = ref('');
const editForm = ref({
    code: '',
    name: ''
});
const isCreating = ref(false);
const notification = ref({ show: false, message: '', type: 'success' });

// Fetch industries from API
const fetchIndustries = async () => {
    loading.value = true;
    try {
        console.log('Fetching industry data from API...');

        // Using fetch API
        const res = await fetch('/api/industry', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });

        if (!res.ok) {
            throw new Error('Network response was not ok');
        }

        const data = await res.json();

        if (Array.isArray(data)) {
            industries.value = data.filter(isActiveIndustry);
        } else {
            industries.value = [];
            console.error('Unexpected data format:', data);
        }

        if (industries.value.length === 0) {
            selectedIndustry.value = null;
            industryCode.value = '';
            searchResult.value = '';
        } else if (selectedIndustry.value) {
            const stillExists = industries.value.some(i => String(i.code || '').toUpperCase() === String(selectedIndustry.value?.code || '').toUpperCase());
            if (!stillExists) {
                selectedIndustry.value = null;
                if (industryCode.value) {
                    industryCode.value = '';
                }
                searchResult.value = '';
            }
        }
    } catch (e) {
        console.error('Error fetching industries:', e);
        industries.value = [];
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    if (industries.value.length === 0) {
        fetchIndustries();
    }
});

// Search for industry code
const searchIndustryCode = () => {
    const input = String(industryCode.value || '').trim();

    if (!input) {
        searchResult.value = '';
        selectedIndustry.value = null;
        return;
    }

    const normalized = input.toUpperCase();

    // Auto-detect EXACT code match (active only). Do not overwrite the textbox.
    const exact = industries.value.find(ind =>
        String(ind.code || '').toUpperCase() === normalized &&
        (!ind.status || ind.status === 'Act')
    );

    if (exact) {
        selectedIndustry.value = exact;
        searchResult.value = `
            <div class="p-3 bg-green-100 rounded-lg mt-2">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-green-800">Data found: <span class="font-semibold">${exact.code}</span> - ${exact.name}</p>
                    </div>
                </div>
            </div>
        `;
        return;
    }

    // Search matches (active only)
    const matches = industries.value.filter(industry =>
        (!industry.status || industry.status === 'Act') &&
        (
            (industry.code && industry.code.toLowerCase().includes(input.toLowerCase())) ||
            (industry.name && industry.name.toLowerCase().includes(input.toLowerCase()))
        )
    );

    if (matches.length > 0) {
        if (matches.length <= 5) {
            // Multiple matches (up to 5) - show list
            let html = `<div class="p-3 bg-blue-100 rounded-lg mt-2">
                <p class="text-sm text-blue-800 mb-2">Multiple codes found:</p>
                <ul class="space-y-1">`;

            matches.forEach(match => {
                html += `<li class="flex justify-between items-center">
                    <button class="text-sm text-blue-700 hover:text-blue-900 hover:underline focus:outline-none">
                        ${match.code} - ${match.name}
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
                    <p class="text-sm text-red-800">No industry code "${input}" found. Check the code or create new data.</p>
                </div>
            </div>
        `;
    }
};

// Handle search and create functionality when pressing Enter on industry code input
const handleSearchAndCreate = async () => {
    const input = String(industryCode.value || '').trim();
    if (!input) return;

    saving.value = true;
    try {
        const res = await fetch(`/api/industry/search/${input.toUpperCase()}`, {
            headers: {
                'Accept': 'application/json'
            }
        });

        const data = await res.json();

        if (data.exists && data.status === 'Obs') {
            searchResult.value = `
                <div class="p-3 bg-yellow-100 rounded-lg mt-2">
                    <p class="text-sm text-yellow-800">Industry code "${input.toUpperCase()}" exists but is obsolete. Please activate it in Manage Industry Status.</p>
                </div>
            `;
            return;
        }

        if (data.exists) {
            // Industry exists and is active (or treated as active)
            const industry = industries.value.find(
                i => i.code.toUpperCase() === input.toUpperCase() && (!i.status || i.status === 'Act')
            );

            if (industry) {
                selectedIndustry.value = industry;
                searchResult.value = `
                    <div class="p-3 bg-green-100 rounded-lg mt-2">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm text-green-800">Data found: <span class="font-semibold">${industry.code}</span> - ${industry.name}</p>
                            </div>
                        </div>
                    </div>
                `;
            }
        } else {
            // Industry doesn't exist, show create form
            isCreating.value = true;
            editForm.value = {
                code: input.toUpperCase(),
                name: ''
            };
            showEditModal.value = true;
        }
    } catch (e) {
        console.error('Error searching industry:', e);
        showNotification('Error searching industry. Please try again.', 'error');
    } finally {
        saving.value = false;
    }
};

const onIndustrySelected = (industry) => {
    selectedIndustry.value = industry;
    industryCode.value = industry.code;
    showModal.value = false;

    // Open edit modal automatically
    isCreating.value = false;
    editForm.value = { ...industry };
    showEditModal.value = true;

    // Update search result
    searchResult.value = `
        <div class="p-3 bg-green-100 rounded-lg mt-2">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-green-800">Editing: <span class="font-semibold">${industry.code}</span> - ${industry.name}</p>
                </div>
            </div>
        </div>
    `;
};

const onIndustryEdit = (industry) => {
    selectedIndustry.value = industry;
    industryCode.value = industry.code;

    // Open the edit modal for the selected industry
    isCreating.value = false;
    editForm.value = { ...industry };
    showEditModal.value = true;

    // Update search result
    searchResult.value = `
        <div class="p-3 bg-green-100 rounded-lg mt-2">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-green-800">Editing: <span class="font-semibold">${industry.code}</span> - ${industry.name}</p>
                </div>
            </div>
        </div>
    `;
};

const editSelectedIndustry = () => {
    if (selectedIndustry.value) {
        isCreating.value = false;
        editForm.value = { ...selectedIndustry.value };
        showEditModal.value = true;
    } else {
        showNotification('Please select an industry first', 'error');
    }
};

const createNewIndustry = () => {
    isCreating.value = true;
    editForm.value = {
        code: industryCode.value.toUpperCase() || '',
        name: ''
    };
    showEditModal.value = true;
};

const closeEditModal = () => {
    showEditModal.value = false;
    isCreating.value = false;
};

const saveIndustryChanges = async () => {
    saving.value = true;
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        if (!csrfToken) {
            throw new Error('CSRF token not found');
        }

        // Different API call for create vs update
        let url = isCreating.value ? '/api/industry' : `/api/industry/${editForm.value.code}`;
        let method = isCreating.value ? 'POST' : 'PUT';

        console.log('Making API request to:', url, 'with method:', method);

        const formData = new FormData();
        formData.append('code', editForm.value.code.toUpperCase());
        formData.append('name', editForm.value.name);

        if (!isCreating.value) {
            formData.append('_method', 'PUT');
        }

        const response = await fetch(url, {
            method: isCreating.value ? 'POST' : 'POST', // Always POST with FormData for method spoofing
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });

        if (!response.ok) {
            const result = await response.json();
            throw new Error(result.message || 'Error saving industry');
        }

        // Refresh the industry list
        await fetchIndustries();

        // Update the industry code field with the saved code
        industryCode.value = editForm.value.code.toUpperCase();

        // Find the saved industry in the refreshed list
        const savedIndustry = industries.value.find(
            i => i.code.toUpperCase() === editForm.value.code.toUpperCase()
        );

        if (savedIndustry) {
            selectedIndustry.value = savedIndustry;
            searchResult.value = `
                <div class="p-3 bg-green-100 rounded-lg mt-2">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm text-green-800">Industry ${isCreating.value ? 'created' : 'updated'}: <span class="font-semibold">${savedIndustry.code}</span> - ${savedIndustry.name}</p>
                        </div>
                    </div>
                </div>
            `;
        }

        showNotification(`Industry ${isCreating.value ? 'created' : 'updated'} successfully`, 'success');
        closeEditModal();
    } catch (e) {
        console.error('Error saving industry:', e);
        console.error('Error details:', e.message);
        showNotification(e.message || 'Error saving industry. Please try again.', 'error');
    } finally {
        saving.value = false;
    }
};

const deleteIndustry = async () => {
    if (!selectedIndustry.value) {
        showNotification('No industry selected', 'error');
        return;
    }

    if (!confirm(`Are you sure you want to mark industry "${selectedIndustry.value.code}" as obsolete?`)) {
        return;
    }

    saving.value = true;
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        if (!csrfToken) {
            throw new Error('CSRF token not found');
        }

        const formData = new FormData();
        formData.append('_method', 'DELETE');

        const response = await fetch(`/api/industry/${selectedIndustry.value.code}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        });

        if (!response.ok) {
            const result = await response.json();
            throw new Error(result.message || 'Error obsoleting industry');
        }

        // Get response data
        const result = await response.json();

        // Immediately remove the obsoleted item from local array for instant UI update
        const obsoletedCode = selectedIndustry.value.code;
        industries.value = industries.value.filter(ind => ind.code !== obsoletedCode);

        // Clear selection and search
        selectedIndustry.value = null;
        industryCode.value = '';
        searchResult.value = '';

        showNotification(result.message || 'Industry marked as obsolete successfully', 'success');
        closeEditModal();

        // Sync with server in background to ensure data consistency
        fetchIndustries();
    } catch (e) {
        console.error('Error obsoleting industry:', e);
        showNotification(e.message || 'Error obsoleting industry. Please try again.', 'error');
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
