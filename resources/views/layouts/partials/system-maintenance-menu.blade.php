<div x-data="{ open: $store.sidebar.isOpen('system-maintenance') }" class="relative">
    <button @click="open = !open; $store.sidebar.toggle('system-maintenance')" 
            class="flex items-center justify-between w-full px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
        <div class="flex items-center">
            <i class="fas fa-wrench w-4 h-4 mr-3"></i>
            <span>System Maintenance</span>
        </div>
        <i class="fas fa-chevron-right text-xs transition-transform" :class="{ 'transform rotate-90': open }"></i>
    </button>

    <!-- System Maintenance Nested Submenu -->
    <div x-show="open" x-collapse class="pl-4 mt-1 space-y-1">
        <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
            <i class="fas fa-coins w-3 h-3 mr-3"></i>
            <span>Define ISO Currency</span>
        </a>
        <a href="{{ route('foreign-currency.index') }}" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
            <i class="fas fa-money-bill-wave w-3 h-3 mr-3"></i>
            <span>Define Foreign Currency</span>
        </a>
        <a href="{{ route('business-form.index') }}" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
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
        <a href="{{ route('business-form.view-print') }}" class="flex items-center px-4 py-2 text-xs text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
            <i class="fas fa-print w-3 h-3 mr-3"></i>
            <span>View & Print Business Form</span>
        </a>
    </div>
</div> 