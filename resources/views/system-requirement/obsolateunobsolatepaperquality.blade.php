@extends('layouts.app')

@section('title', 'Manage Paper Quality Status')

@section('content')
{{-- Header Section --}}
<div class="bg-gradient-to-r from-blue-700 to-indigo-600 p-6 rounded-t-lg shadow-lg mb-6">
    <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-sync-alt mr-3"></i> Manage Paper Quality Status (Obsolate/Unobsolate)
    </h2>
    <p class="text-indigo-100">Toggle the active status of paper qualities.</p>
</div>

<div class="bg-white rounded-b-lg shadow-lg p-6">
    {{-- Display Success/Error Messages --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Paper Qualities Table --}}
    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 bg-white">
            <thead class="bg-gray-100">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grammage (g/m²)</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($paperQualities as $quality)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $quality->paper_quality }}</td> {{-- Use paper_quality --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $quality->paper_name }}</td> {{-- Use paper_name --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $quality->weight_kg_m }}</td> {{-- Use weight_kg_m --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $quality->type ?? 'N/A' }}</td> {{-- Use type, provide default --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                            @if($quality->is_active)
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i> Active
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i> Obsolete
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                            <form action="{{ route('paper-quality.toggle-status', $quality->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to change the status for {{ $quality->paper_quality }}?');"> {{-- Use paper_quality in confirm --}}
                                @csrf
                                @method('PATCH')
                                @if($quality->is_active)
                                    <button type="submit" class="text-red-600 hover:text-red-900 transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 px-3 py-1 rounded bg-red-100 hover:bg-red-200 text-xs font-semibold flex items-center justify-center">
                                        <i class="fas fa-toggle-off mr-1"></i> Mark Obsolete
                                    </button>
                                @else
                                    <button type="submit" class="text-green-600 hover:text-green-900 transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 px-3 py-1 rounded bg-green-100 hover:bg-green-200 text-xs font-semibold flex items-center justify-center">
                                         <i class="fas fa-toggle-on mr-1"></i> Mark Active
                                    </button>
                                @endif
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No paper qualities found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

     {{-- Pagination Links --}}
    @if ($paperQualities instanceof \Illuminate\Pagination\LengthAwarePaginator && $paperQualities->hasPages())
        <div class="mt-6">
            {{ $paperQualities->links() }} {{-- Render pagination links --}}
        </div>
    @endif

</div>
@endsection 