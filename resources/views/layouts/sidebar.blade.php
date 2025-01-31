<div class="bg-gray-800 text-white w-64 py-4 flex flex-col">
    <div class="px-4 py-3 border-b border-gray-700">
        <h1 class="text-xl font-bold">ERP System</h1>
    </div>
    
    <!-- Sidebar Menu -->
    <nav class="flex-1 px-2 py-4 space-y-2">
        <!-- System Manager dengan Submenu -->
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
                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-tools w-4 h-4 mr-3"></i>
                    <span>System Setup</span>
                </a>
                
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
                    </div>
                </div>
                
                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-wrench w-4 h-4 mr-3"></i>
                    <span>System Maintenance</span>
                </a>
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
        <div class="relative group">
            <a href="#" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                <i class="fas fa-chart-line w-5 h-5 mr-3"></i>
                <span>Sales Management</span>
            </a>
        </div>

        <!-- Material Management -->
        <div class="relative group">
            <a href="#" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                <i class="fas fa-boxes w-5 h-5 mr-3"></i>
                <span>Material Management</span>
            </a>
        </div>

        <!-- Production Management dengan Submenu Baru -->
        <div x-data="{ open: false }" class="relative group">
            <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                <div class="flex items-center">
                    <i class="fas fa-industry w-5 h-5 mr-3"></i>
                    <span>Production Management</span>
                </div>
                <i class="fas fa-chevron-down transition-transform" :class="{ 'transform rotate-180': open }"></i>
            </button>
            
            <!-- Production Management Submenu -->
            <div x-show="open" class="pl-4 mt-2 space-y-1">
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

        <!-- Warehouse Management dengan Submenu -->
        <div x-data="{ open: false }" class="relative group">
            <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                <div class="flex items-center">
                    <i class="fas fa-warehouse w-5 h-5 mr-3"></i>
                    <span>Warehouse Management</span>
                </div>
                <i class="fas fa-chevron-down transition-transform" :class="{ 'transform rotate-180': open }"></i>
            </button>
            
            <!-- Warehouse Management Submenu -->
            <div x-show="open" class="pl-4 mt-2 space-y-1">
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

    <!-- User Profile & Logout -->
    <div class="border-t border-gray-700 p-4">
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