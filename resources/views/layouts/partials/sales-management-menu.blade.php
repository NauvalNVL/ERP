<!-- System Requirement -->
<div x-data="{ open: $store.sidebar.isOpen('system-requirement') }" class="relative">
    <button @click="open = !open; $store.sidebar.toggle('system-requirement')" 
            class="flex items-center justify-between w-full px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
        <div class="flex items-center">
            <i class="fas fa-clipboard-list w-4 h-4 mr-3"></i>
            <span>System Requirement</span>
        </div>
        <i class="fas fa-chevron-right text-xs transition-transform" :class="{ 'transform rotate-90': open }"></i>
    </button>

    <!-- System Requirement Nested Submenu -->
    <div x-show="open" x-collapse class="pl-4 mt-1 space-y-1">
        <!-- Sales Configuration -->
        <div x-data="{ open: $store.sidebar.isOpen('sales-config') }" class="relative">
            <button @click="open = !open; $store.sidebar.toggle('sales-config')" 
                    class="flex items-center justify-between w-full px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                <div class="flex items-center">
                    <i class="fas fa-cog w-3 h-3 mr-3"></i>
                    <span>Sales Configuration</span>
                </div>
                <i class="fas fa-chevron-right text-xs transition-transform" :class="{ 'transform rotate-90': open }"></i>
            </button>

            <div x-show="open" x-collapse class="pl-4 mt-1 space-y-1">
                <a href="{{ route('sales-configuration.index') }}" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-sliders-h w-3 h-3 mr-3"></i>
                    <span>Define Sales Configuration</span>
                </a>
            </div>
        </div>

        <!-- Standard Requirement -->
        <div x-data="{ open: $store.sidebar.isOpen('standard-requirement') }" class="relative">
            <button @click="open = !open; $store.sidebar.toggle('standard-requirement')" 
                    class="flex items-center justify-between w-full px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                <div class="flex items-center">
                    <i class="fas fa-clipboard-check w-3 h-3 mr-3"></i>
                    <span>Standard Requirement</span>
                </div>
                <i class="fas fa-chevron-right text-xs transition-transform" :class="{ 'transform rotate-90': open }"></i>
            </button>

            <div x-show="open" x-collapse class="pl-4 mt-1 space-y-1">
                <a href="{{ route('sales-team.index') }}" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-users-cog w-3 h-3 mr-3"></i>
                    <span>Define Sales Team</span>
                </a>
                <a href="{{ route('salesperson.index') }}" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-user-tie w-3 h-3 mr-3"></i>
                    <span>Define Salesperson</span>
                </a>
                <a href="{{ route('salesperson-team.index') }}" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-user-friends w-3 h-3 mr-3"></i>
                    <span>Define Salesperson Team</span>
                </a>
                <a href="{{ route('industry.index') }}" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-industry w-3 h-3 mr-3"></i>
                    <span>Define Industry</span>
                </a>
                <a href="{{ route('geo.index') }}" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-globe w-3 h-3 mr-3"></i>
                    <span>Define Geo</span>
                </a>
                <a href="{{ route('product-group.index') }}" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-boxes w-3 h-3 mr-3"></i>
                    <span>Define Product Group</span>
                </a>
                <a href="{{ route('product.index') }}" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-box w-3 h-3 mr-3"></i>
                    <span>Define Product</span>
                </a>
                <a href="{{ route('product-design.index') }}" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-drafting-compass w-3 h-3 mr-3"></i>
                    <span>Define Product Design</span>
                </a>
                <a href="{{ route('scoring-tool.index') }}" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-cut w-3 h-3 mr-3"></i>
                    <span>Define Scoring Tool</span>
                </a>
                <a href="{{ route('paper-quality.index') }}" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-scroll w-3 h-3 mr-3"></i>
                    <span>Define Paper Quality</span>
                </a>
                <a href="{{ route('paper-quality.manage-status') }}" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-ban w-3 h-3 mr-3"></i>
                    <span>Obsolete/Unobsolete Paper Quality</span>
                </a>
                <a href="{{ route('paper-flute.index') }}" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-layer-group w-3 h-3 mr-3"></i>
                    <span>Define Paper Flute</span>
                </a>
                <a href="{{ route('paper-size.index') }}" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-ruler-combined w-3 h-3 mr-3"></i>
                    <span>Define Paper Size</span>
                </a>
                <a href="{{ route('color-group.index') }}" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-palette w-3 h-3 mr-3"></i>
                    <span>Define Color Group</span>
                </a>
                <a href="{{ route('color.index') }}" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-fill-drip w-3 h-3 mr-3"></i>
                    <span>Define Color</span>
                </a>
                <a href="{{ route('finishing.index') }}" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-paint-roller w-3 h-3 mr-3"></i>
                    <span>Define Finishing</span>
                </a>
            </div>
        </div>
    </div>
</div> 