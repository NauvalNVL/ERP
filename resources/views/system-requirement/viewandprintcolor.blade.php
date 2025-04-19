@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6 print:hidden">
        <h1 class="text-2xl font-semibold text-gray-700">View & Print Colors</h1>
        <button 
            onclick="window.print()" 
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out flex items-center">
            <i class="fas fa-print mr-2"></i> Print
        </button>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Color ID</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Color Name</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Origin</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Color Group</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">CG Type</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Created At</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Updated At</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($colors as $color)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">{{ $color->color_id ?? 'N/A' }}</td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">{{ $color->color_name ?? 'N/A' }}</td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">{{ $color->origin ?? 'N/A' }}</td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">{{ $color->colorGroup->cg_name ?? ($color->color_group_id ?? 'N/A') }}</td> {{-- Use relation --}}
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">{{ $color->cg_type ?? 'N/A' }}</td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">{{ $color->created_at ? $color->created_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">{{ $color->updated_at ? $color->updated_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center text-gray-500">
                            No colors found.
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
        body * {
            visibility: hidden;
        }
        .container, .container * {
            visibility: visible;
        }
        .container {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 10pt;
            color: #000;
        }
        thead {
            background-color: #eee !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        h1 {
             font-size: 14pt;
             margin-bottom: 1rem;
        }
        .print\:hidden {
            display: none !important;
        }
    }
</style>
@endsection 