{{-- resources/views/partials/alert-messages.blade.php --}}

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 shadow-sm" role="alert">
        <strong class="font-bold"><i class="fas fa-check-circle mr-2"></i>Success!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none';">
            <span class="text-green-500 hover:text-green-700">&times;</span>
        </button>
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 shadow-sm" role="alert">
        <strong class="font-bold"><i class="fas fa-exclamation-triangle mr-2"></i>Error!</strong>
        <span class="block sm:inline">{{ session('error') }}</span>
         <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none';">
            <span class="text-red-500 hover:text-red-700">&times;</span>
        </button>
    </div>
@endif

@if (session('warning'))
    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4 shadow-sm" role="alert">
        <strong class="font-bold"><i class="fas fa-exclamation-circle mr-2"></i>Warning!</strong>
        <span class="block sm:inline">{{ session('warning') }}</span>
         <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none';">
            <span class="text-yellow-500 hover:text-yellow-700">&times;</span>
        </button>
    </div>
@endif

@if (session('info'))
    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4 shadow-sm" role="alert">
        <strong class="font-bold"><i class="fas fa-info-circle mr-2"></i>Info!</strong>
        <span class="block sm:inline">{{ session('info') }}</span>
         <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none';">
            <span class="text-blue-500 hover:text-blue-700">&times;</span>
        </button>
    </div>
@endif

{{-- Display Validation Errors --}}
@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 shadow-sm" role="alert">
        <strong class="font-bold"><i class="fas fa-times-circle mr-2"></i>Validation Errors!</strong>
        <ul class="mt-2 list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none';">
            <span class="text-red-500 hover:text-red-700">&times;</span>
        </button>
    </div>
@endif 