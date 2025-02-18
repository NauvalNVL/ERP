<div class="flex flex-col h-full bg-gray-800 text-white">
    <!-- Header -->
    <div class="px-4 py-3 border-b border-gray-700">
        <h1 class="text-xl font-bold">ERP System</h1>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-grow px-2 py-4 space-y-2 overflow-y-auto hide-scrollbar">
        <!-- System Manager -->
        <div x-data="{ open: false, securityOpen: false }" class="relative group">
            <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                <div class="flex items-center">
                    <i class="fas fa-cogs w-5 h-5 mr-3"></i>
                    <span>System Manager</span>
                </div>
                <i class="fas fa-chevron-down transition-transform" :class="{ 'transform rotate-180': open }"></i>
            </button>

            <!-- System Manager Submenu -->
            <div x-show="open" class="pl-4 mt-2 space-y-1">
                <!-- System Setup dengan Nested Submenu -->
                <div class="relative" x-data="{ setupOpen: false }">
                    <button @click="setupOpen = !setupOpen" class="flex items-center justify-between w-full px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                        <div class="flex items-center">
                            <i class="fas fa-tools w-4 h-4 mr-3"></i>
                            <span>System Setup</span>
                        </div>
                        <i class="fas fa-chevron-right text-xs transition-transform" :class="{ 'transform rotate-90': setupOpen }"></i>
                    </button>

                    <!-- System Setup Nested Submenu -->
                    <div x-show="setupOpen" class="pl-4 mt-1 space-y-1">
                        <a href="{{ route('system-configuration.index') }}" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                            <i class="fas fa-cog w-3 h-3 mr-3"></i>
                            <span>Define Configuration</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                            <i class="fas fa-print w-3 h-3 mr-3"></i>
                            <span>Define Printer</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                            <i class="fas fa-code w-3 h-3 mr-3"></i>
                            <span>Define Customised Program</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                            <i class="fas fa-terminal w-3 h-3 mr-3"></i>
                            <span>Define Program Printer</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                            <i class="fas fa-eye w-3 h-3 mr-3"></i>
                            <span>View & Print Printer</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                            <i class="fas fa-file-code w-3 h-3 mr-3"></i>
                            <span>View & Print Customised Program</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                            <i class="fas fa-file-alt w-3 h-3 mr-3"></i>
                            <span>View & Print Program Printer</span>
                        </a>
                    </div>
                </div>

                <!-- System Security dengan Nested Submenu -->
                <div class="relative">
                    <button @click="securityOpen = !securityOpen" class="flex items-center justify-between w-full px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                        <div class="flex items-center">
                            <i class="fas fa-shield-alt w-4 h-4 mr-3"></i>
                            <span>System Security</span>
                        </div>
                        <i class="fas fa-chevron-right text-xs transition-transform" :class="{ 'transform rotate-90': securityOpen }"></i>
                    </button>

                    <!-- System Security Nested Submenu -->
                    <div x-show="securityOpen" class="pl-4 mt-1 space-y-1">
                        <a href="{{ route('users.index') }}" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                            <i class="fas fa-user-plus w-3 h-3 mr-3"></i>
                            <span>Define User</span>
                        </a>
                        <a href="{{ route('users.amend-password') }}" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                            <i class="fas fa-key w-3 h-3 mr-3"></i>
                            <span>Amend User Password</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                            <i class="fas fa-unlock w-3 h-3 mr-3"></i>
                            <span>Release Locked Password</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                            <i class="fas fa-user-lock w-3 h-3 mr-3"></i>
                            <span>Define User Access Permission</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                            <i class="fas fa-copy w-3 h-3 mr-3"></i>
                            <span>Copy & Paste User Access Permission</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                            <i class="fas fa-trash-alt w-3 h-3 mr-3"></i>
                            <span>Purge User Log-in/out Audit Log</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                            <i class="fas fa-users w-3 h-3 mr-3"></i>
                            <span>View & Print User</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                            <i class="fas fa-list-alt w-3 h-3 mr-3"></i>
                            <span>View & Print User Access Permission</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                            <i class="fas fa-history w-3 h-3 mr-3"></i>
                            <span>View & Print User Log-in/out Audit Log</span>
                        </a>
                    </div>
                </div>

                <!-- System Maintenance dengan Nested Submenu -->
                <div class="relative" x-data="{ maintenanceOpen: false }">
                    <button @click="maintenanceOpen = !maintenanceOpen" class="flex items-center justify-between w-full px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                        <div class="flex items-center">
                            <i class="fas fa-wrench w-4 h-4 mr-3"></i>
                            <span>System Maintenance</span>
                        </div>
                        <i class="fas fa-chevron-right text-xs transition-transform" :class="{ 'transform rotate-90': maintenanceOpen }"></i>
                    </button>

                    <!-- System Maintenance Nested Submenu -->
                    <div x-show="maintenanceOpen" class="pl-4 mt-1 space-y-1">
                        <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                            <i class="fas fa-coins w-3 h-3 mr-3"></i>
                            <span>Define ISO Currency</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                            <i class="fas fa-money-bill-wave w-3 h-3 mr-3"></i>
                            <span>Define Foreign Currency</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                            <i class="fas fa-file-alt w-3 h-3 mr-3"></i>
                            <span>Define Business Form</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                            <i class="fas fa-list w-3 h-3 mr-3"></i>
                            <span>View & Print ISO Currency</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                            <i class="fas fa-list-alt w-3 h-3 mr-3"></i>
                            <span>View & Print Foreign Currency</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                            <i class="fas fa-print w-3 h-3 mr-3"></i>
                            <span>View & Print Business Form</span>
                        </a>
                    </div>
                </div>

                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-user-cog w-4 h-4 mr-3"></i>
                    <span>System Administrator</span>
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-database w-4 h-4 mr-3"></i>
                    <span>DB Integrity Check</span>
                </a>
            </div>
        </div>

        <!-- Sales Management -->
        <div x-data="{ salesOpen: false }" class="relative group">
            <button @click="salesOpen = !salesOpen" class="flex items-center justify-between w-full px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                <div class="flex items-center">
                    <i class="fas fa-chart-line w-5 h-5 mr-3"></i>
                    <span>Sales Management</span>
                </div>
                <i class="fas fa-chevron-down transition-transform" :class="{ 'transform rotate-180': salesOpen }"></i>
            </button>

            <!-- Sales Management Submenu -->
            <div x-show="salesOpen" class="pl-4 mt-2 space-y-1">
                <!-- System Requirement dengan Nested Submenu -->
                <div class="relative" x-data="{ requirementOpen: false }">
                    <button @click="requirementOpen = !requirementOpen" class="flex items-center justify-between w-full px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                        <div class="flex items-center">
                            <i class="fas fa-clipboard-list w-4 h-4 mr-3"></i>
                            <span>System Requirement</span>
                        </div>
                        <i class="fas fa-chevron-right text-xs transition-transform" :class="{ 'transform rotate-90': requirementOpen }"></i>
                    </button>

                    <!-- System Requirement Nested Submenu -->
                    <div x-show="requirementOpen" class="pl-4 mt-1 space-y-1">
                        <!-- Sales Configuration dan Customer Account tetap di dalam System Requirement -->
                        <div class="relative" x-data="{ salesConfigOpen: false }">
                            <button @click="salesConfigOpen = !salesConfigOpen" class="flex items-center justify-between w-full px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                <div class="flex items-center">
                                    <i class="fas fa-cog w-3 h-3 mr-3"></i>
                                    <span>Sales Configuration</span>
                                </div>
                                <i class="fas fa-chevron-right text-xs transition-transform" :class="{ 'transform rotate-90': salesConfigOpen }"></i>
                            </button>

                            <div x-show="salesConfigOpen" class="pl-4 mt-1 space-y-1">
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-sliders-h w-3 h-3 mr-3"></i>
                                    <span>Define Sales Configuration</span>
                                </a>
                            </div>
                        </div>

                        <!-- Standard Requirement -->
                        <div class="relative" x-data="{ stdReqOpen: false }">
                            <button @click="stdReqOpen = !stdReqOpen" class="flex items-center justify-between w-full px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                <div class="flex items-center">
                                    <i class="fas fa-clipboard-check w-3 h-3 mr-3"></i>
                                    <span>Standard Requirement</span>
                                </div>
                                <i class="fas fa-chevron-right text-xs transition-transform" :class="{ 'transform rotate-90': stdReqOpen }"></i>
                            </button>

                            <div x-show="stdReqOpen" class="pl-4 mt-1 space-y-1">
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-users-cog w-3 h-3 mr-3"></i>
                                    <span>Define Sales Team</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-user-tie w-3 h-3 mr-3"></i>
                                    <span>Define Salesperson</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-user-friends w-3 h-3 mr-3"></i>
                                    <span>Define Salesperson Team</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-industry w-3 h-3 mr-3"></i>
                                    <span>Define Industry</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-globe w-3 h-3 mr-3"></i>
                                    <span>Define Geo</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-boxes w-3 h-3 mr-3"></i>
                                    <span>Define Product Group</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-box w-3 h-3 mr-3"></i>
                                    <span>Define Product</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-drafting-compass w-3 h-3 mr-3"></i>
                                    <span>Define Product Design</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-scroll w-3 h-3 mr-3"></i>
                                    <span>Define Paper Quality</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-ban w-3 h-3 mr-3"></i>
                                    <span>Obsolete/Unobsolete Paper Quality</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-layer-group w-3 h-3 mr-3"></i>
                                    <span>Define Paper Flute</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-ruler-combined w-3 h-3 mr-3"></i>
                                    <span>Define Paper Size</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-cut w-3 h-3 mr-3"></i>
                                    <span>Define Scoring Tool</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-palette w-3 h-3 mr-3"></i>
                                    <span>Define Color Group</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-fill-drip w-3 h-3 mr-3"></i>
                                    <span>Define Color</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-paint-roller w-3 h-3 mr-3"></i>
                                    <span>Define Finishing</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-link w-3 h-3 mr-3"></i>
                                    <span>Define Stitch Wire</span>
                                </a>

                                <!-- View & Print Section -->
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-print w-3 h-3 mr-3"></i>
                                    <span>View & Print Geo</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-print w-3 h-3 mr-3"></i>
                                    <span>View & Print Product Group</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-print w-3 h-3 mr-3"></i>
                                    <span>View & Print Product</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-print w-3 h-3 mr-3"></i>
                                    <span>View & Print Product Design</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-print w-3 h-3 mr-3"></i>
                                    <span>View & Print Paper Quality</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-print w-3 h-3 mr-3"></i>
                                    <span>View & Print Paper Flute</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-print w-3 h-3 mr-3"></i>
                                    <span>View & Print Paper Size</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-print w-3 h-3 mr-3"></i>
                                    <span>View & Print Scoring Tool</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-print w-3 h-3 mr-3"></i>
                                    <span>View & Print Color Group</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-print w-3 h-3 mr-3"></i>
                                    <span>View & Print Color</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-print w-3 h-3 mr-3"></i>
                                    <span>View & Print Finishing</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-print w-3 h-3 mr-3"></i>
                                    <span>View & Print Stitch Wire</span>
                                </a>
                            </div>
                        </div>

                        <!-- Customer Account -->
                        <div class="relative" x-data="{ customerAccOpen: false }">
                            <button @click="customerAccOpen = !customerAccOpen" class="flex items-center justify-between w-full px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                <div class="flex items-center">
                                    <i class="fas fa-user-circle w-3 h-3 mr-3"></i>
                                    <span>Customer Account</span>
                                </div>
                                <i class="fas fa-chevron-right text-xs transition-transform" :class="{ 'transform rotate-90': customerAccOpen }"></i>
                            </button>

                            <!-- Customer Account Nested Submenu -->
                            <div x-show="customerAccOpen" class="pl-4 mt-1 space-y-1">
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-users w-3 h-3 mr-3"></i>
                                    <span>Define Customer Group</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-user-edit w-3 h-3 mr-3"></i>
                                    <span>Update Customer Account</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-user-clock w-3 h-3 mr-3"></i>
                                    <span>Obsolete/Reactive Customer A/C</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-map-marked-alt w-3 h-3 mr-3"></i>
                                    <span>Define Customer Alternate Address</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-tags w-3 h-3 mr-3"></i>
                                    <span>Define Customer Sales Type</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-list w-3 h-3 mr-3"></i>
                                    <span>View & Print Customer Group</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-list w-3 h-3 mr-3"></i>
                                    <span>View & Print Customer Account</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-list w-3 h-3 mr-3"></i>
                                    <span>View & Print Customer Alternate Address</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-list w-3 h-3 mr-3"></i>
                                    <span>View & Print Customer Sales Type</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-list w-3 h-3 mr-3"></i>
                                    <span>View & Print Non-Active Customer</span>
                                </a>
                            </div>
                        </div>

                        <!-- Master Card Nested List -->
                        <div class="relative" x-data="{ masterCardOpen: false }">
                            <button @click="masterCardOpen = !masterCardOpen" class="flex items-center justify-between w-full px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                <div class="flex items-center">
                                    <i class="fas fa-id-card w-3 h-3 mr-3"></i>
                                    <span>Master Card</span>
                                </div>
                                <i class="fas fa-chevron-right text-xs transition-transform" :class="{ 'transform rotate-90': masterCardOpen }"></i>
                            </button>

                            <!-- Master Card Submenu -->
                            <div x-show="masterCardOpen" class="pl-4 mt-1 space-y-1">
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-edit w-3 h-3 mr-3"></i>
                                    <span>Update MC</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-check w-3 h-3 mr-3"></i>
                                    <span>Approve MC</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-unlock w-3 h-3 mr-3"></i>
                                    <span>Release Approved MC</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-ban w-3 h-3 mr-3"></i>
                                    <span>Obsolate & Reactive MC</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-print w-3 h-3 mr-3"></i>
                                    <span>View & Print MC</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-file-alt w-3 h-3 mr-3"></i>
                                    <span>View & Print MC Maintenance Log</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-file-alt w-3 h-3 mr-3"></i>
                                    <span>View & Print MC Approval Log</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-file-alt w-3 h-3 mr-3"></i>
                                    <span>View & Print Non-Active MC</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-file-alt w-3 h-3 mr-3"></i>
                                    <span>Initialized MC Maintenance Log</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-file-alt w-3 h-3 mr-3"></i>
                                    <span>View & Print MC Print/DC Block Listing</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-file-alt w-3 h-3 mr-3"></i>
                                    <span>View & Print MC DC Block Matching</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-file-alt w-3 h-3 mr-3"></i>
                                    <span>View & Print MC by Color</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-file-alt w-3 h-3 mr-3"></i>
                                    <span>View & Print MC by P/Size P/Quality</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                                    <i class="fas fa-file-alt w-3 h-3 mr-3"></i>
                                    <span>View & Print MC by Machine</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Standard Costing -->
                <div class="relative" x-data="{ costingOpen: false }">
                    <button @click="costingOpen = !costingOpen" class="flex items-center justify-between w-full px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                        <div class="flex items-center">
                            <i class="fas fa-calculator w-4 h-4 mr-3"></i>
                            <span>Standard Costing</span>
                        </div>
                        <i class="fas fa-chevron-right text-xs transition-transform" :class="{ 'transform rotate-90': costingOpen }"></i>
                    </button>
                </div>

                <!-- Standard Formula -->
                <div class="relative" x-data="{ formulaOpen: false }">
                    <button @click="formulaOpen = !formulaOpen" class="flex items-center justify-between w-full px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                        <div class="flex items-center">
                            <i class="fas fa-flask w-4 h-4 mr-3"></i>
                            <span>Standard Formula</span>
                        </div>
                        <i class="fas fa-chevron-right text-xs transition-transform" :class="{ 'transform rotate-90': formulaOpen }"></i>
                    </button>
                </div>

                <!-- Sales Order -->
                <div class="relative" x-data="{ orderOpen: false }">
                    <button @click="orderOpen = !orderOpen" class="flex items-center justify-between w-full px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                        <div class="flex items-center">
                            <i class="fas fa-shopping-cart w-4 h-4 mr-3"></i>
                            <span>Sales Order</span>
                        </div>
                        <i class="fas fa-chevron-right text-xs transition-transform" :class="{ 'transform rotate-90': orderOpen }"></i>
                    </button>
                </div>

                <!-- Sales Analysis -->
                <div class="relative" x-data="{ analysisOpen: false }">
                    <button @click="analysisOpen = !analysisOpen" class="flex items-center justify-between w-full px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                        <div class="flex items-center">
                            <i class="fas fa-chart-bar w-4 h-4 mr-3"></i>
                            <span>Sales Analysis</span>
                        </div>
                        <i class="fas fa-chevron-right text-xs transition-transform" :class="{ 'transform rotate-90': analysisOpen }"></i>
                    </button>
                </div>

                <!-- Customer Service -->
                <div class="relative" x-data="{ serviceOpen: false }">
                    <button @click="serviceOpen = !serviceOpen" class="flex items-center justify-between w-full px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                        <div class="flex items-center">
                            <i class="fas fa-headset w-4 h-4 mr-3"></i>
                            <span>Customer Service</span>
                        </div>
                        <i class="fas fa-chevron-right text-xs transition-transform" :class="{ 'transform rotate-90': serviceOpen }"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Material Management -->
        <div x-data="{ materialOpen: false }" class="relative group">
            <button @click="materialOpen = !materialOpen" class="flex items-center justify-between w-full px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                <div class="flex items-center">
                    <i class="fas fa-boxes w-5 h-5 mr-3"></i>
                    <span>Material Management</span>
                </div>
                <i class="fas fa-chevron-down transition-transform" :class="{ 'transform rotate-180': materialOpen }"></i>
            </button>

            <!-- Material Management Submenu -->
            <div x-show="materialOpen" class="pl-4 mt-2 space-y-1">
                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-clipboard-list w-4 h-4 mr-3"></i>
                    <span>System Requirement</span>
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-shopping-basket w-4 h-4 mr-3"></i>
                    <span>Purchase Order</span>
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-box-open w-4 h-4 mr-3"></i>
                    <span>Inventory Control</span>
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-calculator w-4 h-4 mr-3"></i>
                    <span>Costing</span>
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-file-invoice-dollar w-4 h-4 mr-3"></i>
                    <span>Account</span>
                </a>
            </div>
        </div>

        <!-- Production Management -->
        <div x-data="{ productionOpen: false }" class="relative group">
            <button @click="productionOpen = !productionOpen" class="flex items-center justify-between w-full px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                <div class="flex items-center">
                    <i class="fas fa-industry w-5 h-5 mr-3"></i>
                    <span>Production Management</span>
                </div>
                <i class="fas fa-chevron-down transition-transform" :class="{ 'transform rotate-180': productionOpen }"></i>
            </button>

            <!-- Production Management Submenu -->
            <div x-show="productionOpen" class="pl-4 mt-2 space-y-1">
                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-sliders-h w-4 h-4 mr-3"></i>
                    <span>Production Configuration</span>
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-clipboard-list w-4 h-4 mr-3"></i>
                    <span>Production Work Order</span>
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-tasks w-4 h-4 mr-3"></i>
                    <span>Production Floor Tracking</span>
                </a>
            </div>
        </div>

        <!-- Warehouse Management -->
        <div x-data="{ warehouseOpen: false }" class="relative group">
            <button @click="warehouseOpen = !warehouseOpen" class="flex items-center justify-between w-full px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                <div class="flex items-center">
                    <i class="fas fa-warehouse w-5 h-5 mr-3"></i>
                    <span>Warehouse Management</span>
                </div>
                <i class="fas fa-chevron-down transition-transform" :class="{ 'transform rotate-180': warehouseOpen }"></i>
            </button>

            <!-- Warehouse Management Submenu -->
            <div x-show="warehouseOpen" class="pl-4 mt-2 space-y-1">
                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-box-open w-4 h-4 mr-3"></i>
                    <span>Finished Goods</span>
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-truck w-4 h-4 mr-3"></i>
                    <span>Delivery Order</span>
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-file-invoice w-4 h-4 mr-3"></i>
                    <span>Invoice</span>
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-file-invoice-dollar w-4 h-4 mr-3"></i>
                    <span>Debit & Credit Note</span>
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-chart-bar w-4 h-4 mr-3"></i>
                    <span>Warehouse Analysis</span>
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-landmark w-4 h-4 mr-3"></i>
                    <span>Custom Indonesia</span>
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-calculator w-4 h-4 mr-3"></i>
                    <span>Accounts</span>
                </a>
            </div>
        </div>

        <!-- Data Mining -->
        <div class="relative group">
            <a href="#" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                <i class="fas fa-database w-5 h-5 mr-3"></i>
                <span>Data Mining</span>
            </a>
        </div>
    </nav>

    <!-- User Profile & Logout - akan selalu di bawah -->
    <div class="border-t border-gray-700 p-4 mt-auto">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full bg-gray-600 flex items-center justify-center">
                    <span class="text-sm font-medium">{{ substr(Auth::user()->name, 0, 1) }}</span>
                </div>
                <span class="ml-3 text-sm">{{ Auth::user()->name }}</span>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-red-400 hover:text-red-300">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Tambahkan Alpine.js untuk fungsi dropdown -->
<script src="//unpkg.com/alpinejs" defer></script>