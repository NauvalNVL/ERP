<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    <!-- Tax Data Card -->
    <div
        x-data="{ taxModal: false }"
        class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-purple-100">

        <!-- Card Header -->
        <div class="p-6 cursor-pointer" @click="taxModal = true">
            <div class="flex items-center gap-4">
                <div class="bg-purple-50 p-3 rounded-xl shadow-inner">
                    <i class="fas fa-file-invoice text-2xl text-purple-600"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-800 mb-1">Tax Data</h3>
                    <p class="text-sm text-gray-500">Configure tax settings and financial parameters</p>
                </div>
            </div>
            <div class="mt-4 border-t pt-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Last updated:</span>
                    <span class="text-purple-600 font-medium">2 days ago</span>
                </div>
            </div>
        </div>

        <!-- Tax Data Modal -->
        <div x-show="taxModal"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 bg-black/30 backdrop-blur-sm flex items-center justify-center p-4" x-cloak=>

            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-hidden">
                <!-- Modal Header -->
                <div class="bg-gray-900 text-white px-6 py-4 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-file-invoice text-2xl text-purple-400"></i>
                        <h2 class="text-xl font-semibold">Tax Configuration</h2>
                    </div>
                    <button @click="taxModal = false" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-6">
                    <!-- Form Section -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <!-- Tax Registration -->
                            <div class="form-group">
                                <label class="form-label">Tax Registration No</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-gray-100 border-0">
                                        <i class="fas fa-id-card text-gray-500"></i>
                                    </span>
                                    <input type="text"
                                        class="form-control border-0 bg-gray-50 focus:ring-1 focus:ring-purple-500"
                                        placeholder="TRX-123456789">
                                </div>
                            </div>

                            <!-- Tax Version -->
                            <div class="form-group">
                                <label class="form-label">Tax Report Version</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-gray-100 border-0">
                                        <i class="fas fa-code-branch text-gray-500"></i>
                                    </span>
                                    <input type="text"
                                        class="form-control border-0 bg-gray-50 focus:ring-1 focus:ring-purple-500"
                                        placeholder="v2.1.5">
                                </div>
                            </div>

                            <!-- Financial Year -->
                            <div class="form-group">
                                <label class="form-label">Financial Year</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-gray-100 border-0">
                                        <i class="fas fa-calendar-alt text-gray-500"></i>
                                    </span>
                                    <input type="text"
                                        class="form-control border-0 bg-gray-50 focus:ring-1 focus:ring-purple-500"
                                        placeholder="FY2023/2024">
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <!-- Date Range -->
                            <div class="form-group">
                                <label class="form-label">Financial Period</label>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="input-group">
                                        <span class="input-group-text bg-gray-100 border-0">
                                            <i class="fas fa-calendar-day text-gray-500"></i>
                                        </span>
                                        <input type="date"
                                            class="form-control border-0 bg-gray-50 focus:ring-1 focus:ring-purple-500">
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-text bg-gray-100 border-0">
                                            <i class="fas fa-calendar-week text-gray-500"></i>
                                        </span>
                                        <input type="date"
                                            class="form-control border-0 bg-gray-50 focus:ring-1 focus:ring-purple-500">
                                    </div>
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="form-group">
                                <label class="form-label">Company Address</label>
                                <div class="space-y-4">
                                    <input type="text"
                                        class="form-control border-0 bg-gray-50 focus:ring-1 focus:ring-purple-500"
                                        placeholder="Address Line 1">
                                    <input type="text"
                                        class="form-control border-0 bg-gray-50 focus:ring-1 focus:ring-purple-500"
                                        placeholder="Address Line 2">
                                    <input type="text"
                                        class="form-control border-0 bg-gray-50 focus:ring-1 focus:ring-purple-500"
                                        placeholder="Address Line 3">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Location Details -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="form-group">
                            <label class="form-label">Post Code</label>
                            <input type="text"
                                class="form-control border-0 bg-gray-50 focus:ring-1 focus:ring-purple-500"
                                placeholder="12345">
                        </div>
                        <div class="form-group">
                            <label class="form-label">City</label>
                            <input type="text"
                                class="form-control border-0 bg-gray-50 focus:ring-1 focus:ring-purple-500"
                                placeholder="Jakarta">
                        </div>
                        <div class="form-group">
                            <label class="form-label">State</label>
                            <input type="text"
                                class="form-control border-0 bg-gray-50 focus:ring-1 focus:ring-purple-500"
                                placeholder="DKI Jakarta">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Country</label>
                            <input type="text"
                                class="form-control border-0 bg-gray-50 focus:ring-1 focus:ring-purple-500"
                                placeholder="Indonesia">
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-6 border-t">
                        <div class="flex justify-end gap-3">
                            <button @click="taxModal = false"
                                class="btn btn-outline-secondary px-5 py-2 rounded-lg hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit"
                                class="btn btn-primary px-6 py-2 rounded-lg bg-purple-600 hover:bg-purple-700 text-white">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Base Currency -->
    <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-green-100">
        <div class="p-6">
            <div class="flex items-center gap-4">
                <div class="bg-green-50 p-3 rounded-xl shadow-inner">
                    <i class="fas fa-dollar-sign text-2xl text-green-600"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-800 mb-1">Base Currency</h3>
                    <p class="text-sm text-gray-500">Set default currency and exchange rates</p>
                </div>
            </div>
            <div class="mt-4 border-t pt-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Current Rate:</span>
                    <span class="text-green-600 font-medium">1 USD = 15,600 IDR</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Password Policy -->
    <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-red-100">
        <div class="p-6">
            <div class="flex items-center gap-4">
                <div class="bg-red-50 p-3 rounded-xl shadow-inner">
                    <i class="fas fa-lock text-2xl text-red-600"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-800 mb-1">Password Policy</h3>
                    <p class="text-sm text-gray-500">Configure password requirements</p>
                </div>
            </div>
            <div class="mt-4 border-t pt-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Last Changed:</span>
                    <span class="text-red-600 font-medium">3 days ago</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Policy -->
    <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-blue-100">
        <div class="p-6">
            <div class="flex items-center gap-4">
                <div class="bg-blue-50 p-3 rounded-xl shadow-inner">
                    <i class="fas fa-shield-alt text-2xl text-blue-600"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-800 mb-1">Security Policy</h3>
                    <p class="text-sm text-gray-500">Manage security protocols</p>
                </div>
            </div>
            <div class="mt-4 border-t pt-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Last Audit:</span>
                    <span class="text-blue-600 font-medium">1 week ago</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Email Configuration -->
    <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-cyan-100">
        <div class="p-6">
            <div class="flex items-center gap-4">
                <div class="bg-cyan-50 p-3 rounded-xl shadow-inner">
                    <i class="fas fa-envelope text-2xl text-cyan-600"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-800 mb-1">Email Settings</h3>
                    <p class="text-sm text-gray-500">Configure email server settings</p>
                </div>
            </div>
            <div class="mt-4 border-t pt-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Status:</span>
                    <span class="text-cyan-600 font-medium">SMTP Active</span>
                </div>
            </div>
        </div>
    </div>

    <!-- 3rd Party Software -->
    <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-orange-100">
        <div class="p-6">
            <div class="flex items-center gap-4">
                <div class="bg-orange-50 p-3 rounded-xl shadow-inner">
                    <i class="fas fa-cubes text-2xl text-orange-600"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-800 mb-1">3rd Party Software</h3>
                    <p class="text-sm text-gray-500">Manage integrations</p>
                </div>
            </div>
            <div class="mt-4 border-t pt-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Active Integrations:</span>
                    <span class="text-orange-600 font-medium">3 Services</span>
                </div>
            </div>
        </div>
    </div>
</div>