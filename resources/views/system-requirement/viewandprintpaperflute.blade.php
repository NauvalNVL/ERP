@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6 print:hidden">
        <h1 class="text-2xl font-semibold text-gray-700">View & Print Paper Flutes</h1>
        <button 
            onclick="window.print()" 
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out flex items-center">
            <i class="fas fa-print mr-2"></i> Print
        </button>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-x-auto"> {{-- Add overflow-x-auto for wide tables --}}
        <table class="min-w-full leading-normal">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-3 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Code</th>
                    <th class="px-3 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                    <th class="px-3 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Description</th>
                    <th class="px-3 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">TUR L2B</th>
                    <th class="px-3 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">TUR L3</th>
                    <th class="px-3 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">TUR L1</th>
                    <th class="px-3 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">TUR A/C/E</th>
                    <th class="px-3 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">TUR 2L</th>
                    <th class="px-3 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Height (mm)</th>
                    <th class="px-3 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Starch Cons.</th>
                    <th class="px-3 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Active</th>
                    <th class="px-3 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Created At</th>
                    <th class="px-3 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Updated At</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($paperFlutes as $flute)
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-4 border-b border-gray-200 bg-white text-sm">{{ $flute->code ?? 'N/A' }}</td>
                        <td class="px-3 py-4 border-b border-gray-200 bg-white text-sm">{{ $flute->name ?? 'N/A' }}</td>
                        <td class="px-3 py-4 border-b border-gray-200 bg-white text-sm">{{ $flute->description ?? 'N/A' }}</td>
                        <td class="px-3 py-4 border-b border-gray-200 bg-white text-sm text-right">{{ number_format($flute->tur_l2b ?? 0, 2) }}</td>
                        <td class="px-3 py-4 border-b border-gray-200 bg-white text-sm text-right">{{ number_format($flute->tur_l3 ?? 0, 2) }}</td>
                        <td class="px-3 py-4 border-b border-gray-200 bg-white text-sm text-right">{{ number_format($flute->tur_l1 ?? 0, 2) }}</td>
                        <td class="px-3 py-4 border-b border-gray-200 bg-white text-sm text-right">{{ number_format($flute->tur_ace ?? 0, 2) }}</td>
                        <td class="px-3 py-4 border-b border-gray-200 bg-white text-sm text-right">{{ number_format($flute->tur_2l ?? 0, 2) }}</td>
                        <td class="px-3 py-4 border-b border-gray-200 bg-white text-sm text-right">{{ number_format($flute->flute_height ?? 0, 2) }}</td>
                        <td class="px-3 py-4 border-b border-gray-200 bg-white text-sm text-right">{{ number_format($flute->starch_consumption ?? 0, 2) }}</td>
                        <td class="px-3 py-4 border-b border-gray-200 bg-white text-sm">{{ $flute->is_active ? 'Yes' : 'No' }}</td>
                        <td class="px-3 py-4 border-b border-gray-200 bg-white text-sm">{{ $flute->created_at ? $flute->created_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                        <td class="px-3 py-4 border-b border-gray-200 bg-white text-sm">{{ $flute->updated_at ? $flute->updated_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="13" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center text-gray-500">
                            No paper flutes found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Styling khusus untuk print (sama seperti sebelumnya, mungkin perlu penyesuaian font size lebih kecil lagi jika diperlukan) --}}
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
            padding: 4px; /* Reduce padding for print */
            font-size: 8pt; /* Reduce font size for print */
            color: #000;
            word-break: break-all; /* Help break long words */
        }
        thead {
            background-color: #eee !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        h1 {
             font-size: 12pt; /* Reduce title size */
             margin-bottom: 0.5rem;
        }
        .print\:hidden {
            display: none !important;
        }
    }
</style>
@endsection 