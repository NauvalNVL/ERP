<div class="flex items-center">
    <input type="checkbox" name="{{ $name }}" value="{{ $value }}" id="{{ $value }}" 
           class="h-5 w-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500"
           {{ $checked ? 'checked' : '' }}>
    <label for="{{ $value }}" class="ml-2 text-sm text-gray-700">{{ $label }}</label>
</div> 