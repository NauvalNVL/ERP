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
    <div
        x-data="{ baseCurrencyModal: false }"
        class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-green-100">
        <div class="p-6 cursor-pointer" @click="baseCurrencyModal = true">
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

        <!-- Base Currency Modal -->
        <div x-show="baseCurrencyModal"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 bg-black/30 backdrop-blur-sm flex items-center justify-center p-4" x-cloak>

            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-hidden">
                <!-- Modal Header -->
                <div class="bg-gray-900 text-white px-6 py-4 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-dollar-sign text-2xl text-green-400"></i>
                        <h2 class="text-xl font-semibold">Base Currency Configuration</h2>
                    </div>
                    <button @click="baseCurrencyModal = false" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-6">
                    <!-- Operation System -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800">Operation System</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="form-group">
                                <label class="form-label">Currency Code</label>
                                <select class="form-control border-0 bg-gray-50 focus:ring-1 focus:ring-green-500">
                                    <option value="IDR">IDR</option>
                                    <option value="USD">USD</option>
                                    <option value="SGD">SGD</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Currency</label>
                                <input type="text"
                                    class="form-control border-0 bg-gray-50 focus:ring-1 focus:ring-green-500"
                                    placeholder="Indonesian Rupiah">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Country</label>
                                <input type="text"
                                    class="form-control border-0 bg-gray-50 focus:ring-1 focus:ring-green-500"
                                    placeholder="Indonesia">
                            </div>
                        </div>
                    </div>

                    <!-- Tax Computation -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800">Tax Computation</h3>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <div class="space-y-2">
                                <label class="form-label">Decimal Point</label>
                                <div class="space-y-2">
                                    <label class="flex items-center gap-2">
                                        <input type="radio" name="decimal" class="form-radio text-green-500">
                                        <span class="text-sm">0 Decimal rounded-up</span>
                                    </label>
                                    <label class="flex items-center gap-2">
                                        <input type="radio" name="decimal" class="form-radio text-green-500">
                                        <span class="text-sm">0 Decimal no rounding</span>
                                    </label>
                                    <label class="flex items-center gap-2">
                                        <input type="radio" name="decimal" class="form-radio text-green-500">
                                        <span class="text-sm">2 Decimal</span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Country</label>
                                <select class="form-control border-0 bg-gray-50 focus:ring-1 focus:ring-green-500">
                                    <option>Vietnam</option>
                                    <option>Indonesia</option>
                                    <option>International</option>
                                </select>
                            </div>
                            <div class="form-group" x-data="{ guideOpen: false }">
                                <div class="flex justify-between items-center mb-2">
                                    <label class="form-label">Example</label>
                                    <button @click="guideOpen = true" 
                                            class="text-green-600 hover:text-green-700 text-sm flex items-center gap-1 transition-colors">
                                        <i class="fas fa-info-circle"></i>
                                        Guide
                                    </button>
                                </div>

                                <!-- Guide Dialog -->
                                <div x-show="guideOpen" 
                                    x-transition:enter="ease-out duration-300"
                                    x-transition:enter-start="opacity-0"
                                    x-transition:enter-end="opacity-100"
                                    x-transition:leave="ease-in duration-200"
                                    x-transition:leave-start="opacity-100"
                                    x-transition:leave-end="opacity-0"
                                    class="fixed inset-0 z-50 bg-black/30 backdrop-blur-sm flex items-center justify-center p-4" 
                                    x-cloak>
                                    
                                    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-hidden">
                                        <!-- Dialog Header -->
                                        <div class="bg-gray-900 text-white px-6 py-4 flex justify-between items-center">
                                            <h2 class="text-xl font-semibold">Decimal Point Guide</h2>
                                            <button @click="guideOpen = false" class="text-gray-400 hover:text-white transition-colors">
                                                <i class="fas fa-times text-xl"></i>
                                            </button>
                                        </div>
                                        
                                        <!-- Dialog Body -->
                                        <div class="p-6">
                                            <div class="text-sm bg-gray-50 p-3 rounded-lg overflow-x-auto">
                                                <table class="w-full">
                                                    <thead class="bg-gray-100">
                                                        <tr>
                                                            <th class="text-left py-2 px-3 border-b">Case</th>
                                                            <th class="text-left py-2 px-3 border-b">Customer</th>
                                                            <th class="text-left py-2 px-3 border-b">AP Transaction</th>
                                                            <th class="text-left py-2 px-3 border-b">GL Transaction</th>
                                                            <th class="text-left py-2 px-3 border-b">Remarks</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="py-2 px-3 border-b">1</td>
                                                            <td class="py-2 px-3 border-b">IDR</td>
                                                            <td class="py-2 px-3 border-b">15.57 becomes 15.00</td>
                                                            <td class="py-2 px-3 border-b">15.00</td>
                                                            <td class="py-2 px-3 border-b">Amount will be rounded to 0 decimal when prepare PO</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="py-2 px-3 border-b">2</td>
                                                            <td class="py-2 px-3 border-b">USD</td>
                                                            <td class="py-2 px-3 border-b">15.57 becomes 15.57</td>
                                                            <td class="py-2 px-3 border-b">15.57</td>
                                                            <td class="py-2 px-3 border-b">Maintain original decimal points</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Max +/- Tax Adjustment</label>
                            <input type="text"
                                class="form-control border-0 bg-gray-50 focus:ring-1 focus:ring-green-500 w-1/3"
                                placeholder="Enter value">
                        </div>
                    </div>

                    <!-- Financial System -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800">Financial System</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="form-group">
                                <label class="form-label">Currency Code</label>
                                <select class="form-control border-0 bg-gray-50 focus:ring-1 focus:ring-green-500">
                                    <option value="IDR">IDR</option>
                                    <option value="USD">USD</option>
                                    <option value="SGD">SGD</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Currency</label>
                                <input type="text"
                                    class="form-control border-0 bg-gray-50 focus:ring-1 focus:ring-green-500"
                                    placeholder="Indonesian Rupiah">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Country</label>
                                <input type="text"
                                    class="form-control border-0 bg-gray-50 focus:ring-1 focus:ring-green-500"
                                    placeholder="Indonesia">
                            </div>
                        </div>
                        <p class="text-sm text-red-500 italic mt-2">
                            *You are not recommended to change the base currency once you started the system
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-6 border-t">
                        <div class="flex justify-end gap-3">
                            <button @click="baseCurrencyModal = false"
                                class="btn btn-outline-secondary px-5 py-2 rounded-lg hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit"
                                class="btn btn-primary px-6 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white">
                                OK
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Password Policy -->
    <div
        x-data="{ passwordPolicyModal: false }"
        class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-red-100">
        <div class="p-6 cursor-pointer" @click="passwordPolicyModal = true">
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

        <!-- Password Policy Modal -->
        <div x-show="passwordPolicyModal"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 bg-black/30 backdrop-blur-sm flex items-center justify-center p-4" x-cloak>

            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-hidden">
                <!-- Modal Header -->
                <div class="bg-gray-900 text-white px-6 py-4 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-lock text-2xl text-red-400"></i>
                        <h2 class="text-xl font-semibold">Password Policy Configuration</h2>
                    </div>
                    <button @click="passwordPolicyModal = false" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-6">
                    <!-- Lock Settings -->
                    <div class="space-y-4">
                        <div class="form-group">
                            <label class="form-label">Lock Password After</label>
                            <div class="flex items-center gap-4">
                                <select class="form-control border-0 bg-gray-50 focus:ring-1 focus:ring-red-500 w-32">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select>
                                <div class="text-sm">
                                    <div>Unsuccessful login attempts: 3</div>
                                    <div>Successful login attempts: 12</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Release Locked Password</label>
                            <div class="space-y-2">
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="release" class="form-radio text-red-500">
                                    <span>1 - By System Administrator</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="release" class="form-radio text-red-500">
                                    <div class="flex items-center gap-2">
                                        <span>2 - By itself after</span>
                                        <input type="number"
                                            class="form-control border-0 bg-gray-50 focus:ring-1 focus:ring-red-500 w-20"
                                            placeholder="Minutes">
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Password Length -->
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label">Password Min Length</label>
                                <input type="number"
                                    class="form-control border-0 bg-gray-50 focus:ring-1 focus:ring-red-500"
                                    placeholder="0-6">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Password Max Length</label>
                                <input type="number"
                                    class="form-control border-0 bg-gray-50 focus:ring-1 focus:ring-red-500"
                                    placeholder="0-6">
                            </div>
                        </div>

                        <p class="text-sm text-red-500 italic">
                            *Length is number of alphanumeric, cannot be all numeric. When min and max length are zeros,
                            there is no length check and null password is allowed
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-6 border-t">
                        <div class="flex justify-end gap-3">
                            <button @click="passwordPolicyModal = false"
                                class="btn btn-outline-secondary px-5 py-2 rounded-lg hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit"
                                class="btn btn-primary px-6 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white">
                                OK
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Policy -->
    <div
        x-data="{ securityPolicyModal: false }"
        class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-blue-100">
        <div class="p-6 cursor-pointer" @click="securityPolicyModal = true">
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

        <!-- Security Policy Modal -->
        <div x-show="securityPolicyModal"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 bg-black/30 backdrop-blur-sm flex items-center justify-center p-4" x-cloak>

            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-hidden">
                <!-- Modal Header -->
                <div class="bg-gray-900 text-white px-6 py-4 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-shield-alt text-2xl text-blue-400"></i>
                        <h2 class="text-xl font-semibold">Security Policy Configuration</h2>
                    </div>
                    <button @click="securityPolicyModal = false" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-6">
                    <!-- User Settings -->
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="form-group">
                                <label class="form-label">Super User</label>
                                <input type="text"
                                    class="form-control border-0 bg-gray-50 focus:ring-1 focus:ring-blue-500"
                                    value="root"
                                    readonly>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Check Active User</label>
                                <input type="text"
                                    class="form-control border-0 bg-gray-50 focus:ring-1 focus:ring-blue-500"
                                    value="Yes"
                                    readonly>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Check User Permission</label>
                                <input type="text"
                                    class="form-control border-0 bg-gray-50 focus:ring-1 focus:ring-blue-500"
                                    value="Yes"
                                    readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Set All Users to Active</label>
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-2">
                                    <input type="radio" name="forceActive" id="forceYes" class="form-radio text-blue-500">
                                    <label for="forceYes" class="text-sm">Yes</label>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="radio" name="forceActive" id="forceNo" class="form-radio text-blue-500">
                                    <label for="forceNo" class="text-sm">No</label>
                                </div>
                            </div>
                            <div class="text-sm text-gray-600 mt-2 space-y-1">
                                <div>N-No, system will run normally using check active user and check user permission</div>
                                <div>Y-Yes, system will force all users to active so that they cannot log-in. So that you can housekeeping by super user</div>
                            </div>
                        </div>
                    </div>

                    <!-- System Security -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800">System Security</h3>
                        <div class="space-y-4">
                            <div class="form-group">
                                <label class="form-label">ACU Folder</label>
                                <input type="text"
                                    class="form-control border-0 bg-gray-50 focus:ring-1 focus:ring-blue-500"
                                    placeholder="Enter ACU folder path">
                            </div>
                            <div class="form-group">
                                <label class="form-label">IP Address</label>
                                <input type="text"
                                    class="form-control border-0 bg-gray-50 focus:ring-1 focus:ring-blue-500"
                                    placeholder="Enter IP address">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Port #</label>
                                <input type="text"
                                    class="form-control border-0 bg-gray-50 focus:ring-1 focus:ring-blue-500"
                                    placeholder="Enter port number">
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-6 border-t">
                        <div class="flex justify-end gap-3">
                            <button @click="securityPolicyModal = false"
                                class="btn btn-outline-secondary px-5 py-2 rounded-lg hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit"
                                class="btn btn-primary px-6 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white">
                                OK
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Email Configuration -->
    <div 
        x-data="{ emailConfigModal: false }"
        class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-cyan-100">
        <div class="p-6 cursor-pointer" @click="emailConfigModal = true">
            <div class="flex items-center gap-4">
                <div class="bg-cyan-50 p-3 rounded-xl shadow-inner">
                    <i class="fas fa-envelope text-2xl text-cyan-600"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-800 mb-1">Email</h3>
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

        <!-- Email Configuration Modal -->
        <div x-show="emailConfigModal" 
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 bg-black/30 backdrop-blur-sm flex items-center justify-center p-4" x-cloak>
            
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-hidden">
                <!-- Modal Header -->
                <div class="bg-gray-900 text-white px-6 py-4 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-envelope text-2xl text-cyan-400"></i>
                        <h2 class="text-xl font-semibold">Email Configuration</h2>
                    </div>
                    <button @click="emailConfigModal = false" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-6">
                    <div class="space-y-4">
                        <div class="form-group">
                            <label class="form-label">Email Server</label>
                            <input type="text" 
                                class="form-control border-0 bg-gray-50 focus:ring-1 focus:ring-cyan-500"
                                placeholder="Enter email server address">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Email Port</label>
                            <input type="number" 
                                class="form-control border-0 bg-gray-50 focus:ring-1 focus:ring-cyan-500"
                                placeholder="Enter port number">
                        </div>
                        
                        <div class="form-group">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" class="form-checkbox text-cyan-500">
                                <span>TLS/SSL</span>
                            </label>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Note</label>
                            <textarea 
                                class="form-control border-0 bg-gray-50 focus:ring-1 focus:ring-cyan-500 h-24"
                                placeholder="Enter additional notes"></textarea>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-6 border-t">
                        <div class="flex justify-end gap-3">
                            <button @click="emailConfigModal = false"
                                class="btn btn-outline-secondary px-5 py-2 rounded-lg hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit"
                                class="btn btn-primary px-6 py-2 rounded-lg bg-cyan-600 hover:bg-cyan-700 text-white">
                                OK
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3rd Party Software -->
    <div 
        x-data="{ thirdPartyModal: false }"
        class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-orange-100">
        <div class="p-6 cursor-pointer" @click="thirdPartyModal = true">
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

        <!-- 3rd Party Software Modal -->
        <div x-show="thirdPartyModal" 
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 bg-black/30 backdrop-blur-sm flex items-center justify-center p-4" x-cloak>
            
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-hidden">
                <!-- Modal Header -->
                <div class="bg-gray-900 text-white px-6 py-4 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-cubes text-2xl text-orange-400"></i>
                        <h2 class="text-xl font-semibold">3rd Party Software Integration</h2>
                    </div>
                    <button @click="thirdPartyModal = false" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-6">
                    <div class="space-y-4">
                        <div class="form-group">
                            <label class="form-label">Integrate to SAP B1</label>
                            <input type="text" 
                                class="form-control border-0 bg-gray-50 focus:ring-1 focus:ring-orange-500 w-12 text-center uppercase"
                                placeholder="Y/N"
                                maxlength="1"
                                oninput="this.value = this.value.toUpperCase()">
                        </div>

                        <div class="text-sm text-red-500 italic space-y-2">
                            <div>*Note if set Yes to integrate with SAP B1:</div>
                            <div class="ml-4">- ERP will run SAP B1 credit check program</div>
                            <div class="ml-4">- If SAP B1 rejects and pending the sales order, you can run ERP outright approval to accept the sales order</div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-6 border-t">
                        <div class="flex justify-end gap-3">
                            <button @click="thirdPartyModal = false"
                                class="btn btn-outline-secondary px-5 py-2 rounded-lg hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit"
                                class="btn btn-primary px-6 py-2 rounded-lg bg-orange-600 hover:bg-orange-700 text-white">
                                OK
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>