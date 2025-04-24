@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6 print:hidden">
        <h1 class="text-2xl font-semibold text-gray-700">View & Print Industries</h1>
        <button 
            onclick="window.print()" 
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out flex items-center">
            <i class="fas fa-print mr-2"></i> Print
        </button>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-x-auto">
        <table class="min-w-full leading-normal">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Code</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Created At</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Updated At</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($industries as $industry)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">{{ $industry->id }}</td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">{{ $industry->code ?? 'N/A' }}</td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">{{ $industry->name ?? 'N/A' }}</td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">{{ $industry->created_at ? $industry->created_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">{{ $industry->updated_at ? $industry->updated_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center text-gray-500">
                            No industries found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Styling khusus untuk print --}}
<style>
    @media print {
        body * { visibility: hidden; }
        .container, .container * { visibility: visible; }
        .container { position: absolute; left: 0; top: 0; width: 100%; margin: 0; padding: 5px; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { border: 1px solid #ccc; padding: 4px; font-size: 8pt; color: #000; word-wrap: break-word; }
        thead { background-color: #eee !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; display: table-header-group; }
        h1 { font-size: 12pt; margin-bottom: 0.5rem; text-align: center; }
        .print\:hidden { display: none !important; }
    }
</style>
@endsection 