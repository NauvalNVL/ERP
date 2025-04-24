@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6 print:hidden">
        <h1 class="text-2xl font-semibold text-gray-700">View & Print Paper Qualities</h1>
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
                    <th class="px-2 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                    <th class="px-2 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Quality Code</th>
                    <th class="px-2 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Paper Name</th>
                    <th class="px-2 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Weight (kg/m)</th>
                    <th class="px-2 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Comm. Code</th>
                    <th class="px-2 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Wet-End Code</th>
                    <th class="px-2 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">DECC Code</th>
                    <th class="px-2 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-2 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Flute</th>
                    <th class="px-2 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">DB</th>
                    <th class="px-2 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">B</th>
                    <th class="px-2 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">IL</th>
                    <th class="px-2 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">A/C/E</th>
                    <th class="px-2 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">2L</th>
                    <th class="px-2 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Active</th>
                    <th class="px-2 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Created At</th>
                    <th class="px-2 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Updated At</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($paperQualities as $quality)
                    <tr class="hover:bg-gray-50">
                        <td class="px-2 py-4 border-b border-gray-200 bg-white text-sm">{{ $quality->id }}</td>
                        <td class="px-2 py-4 border-b border-gray-200 bg-white text-sm">{{ $quality->paper_quality ?? 'N/A' }}</td>
                        <td class="px-2 py-4 border-b border-gray-200 bg-white text-sm">{{ $quality->paper_name ?? 'N/A' }}</td>
                        <td class="px-2 py-4 border-b border-gray-200 bg-white text-sm text-right">{{ number_format($quality->weight_kg_m ?? 0, 4) }}</td>
                        <td class="px-2 py-4 border-b border-gray-200 bg-white text-sm">{{ $quality->commercial_code ?? 'N/A' }}</td>
                        <td class="px-2 py-4 border-b border-gray-200 bg-white text-sm">{{ $quality->wet_end_code ?? 'N/A' }}</td>
                        <td class="px-2 py-4 border-b border-gray-200 bg-white text-sm">{{ $quality->decc_code ?? 'N/A' }}</td>
                        <td class="px-2 py-4 border-b border-gray-200 bg-white text-sm">{{ $quality->status ?? 'N/A' }}</td>
                        <td class="px-2 py-4 border-b border-gray-200 bg-white text-sm">{{ $quality->flute ?? 'N/A' }}</td>
                        <td class="px-2 py-4 border-b border-gray-200 bg-white text-sm">{{ $quality->db ?? 'N/A' }}</td>
                        <td class="px-2 py-4 border-b border-gray-200 bg-white text-sm">{{ $quality->b ?? 'N/A' }}</td>
                        <td class="px-2 py-4 border-b border-gray-200 bg-white text-sm">{{ $quality->il ?? 'N/A' }}</td>
                        <td class="px-2 py-4 border-b border-gray-200 bg-white text-sm">{{ $quality->a_c_e ?? 'N/A' }}</td>
                        <td class="px-2 py-4 border-b border-gray-200 bg-white text-sm">{{ $quality->{'2l'} ?? 'N/A' }}</td>
                        <td class="px-2 py-4 border-b border-gray-200 bg-white text-sm">{{ $quality->is_active ? 'Yes' : 'No' }}</td>
                        <td class="px-2 py-4 border-b border-gray-200 bg-white text-sm">{{ $quality->created_at ? $quality->created_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                        <td class="px-2 py-4 border-b border-gray-200 bg-white text-sm">{{ $quality->updated_at ? $quality->updated_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="17" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center text-gray-500">
                            No paper qualities found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    @media print {
        body {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
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
            padding: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 3px;
            font-size: 7pt;
            color: #000;
            word-wrap: break-word;
            text-align: left;
        }
        td.text-right {
             text-align: right;
        }
        thead {
            background-color: #eee !important;
            display: table-header-group;
        }
         tr {
            page-break-inside: avoid;
        }
        h1 {
             font-size: 11pt;
             margin-bottom: 0.5rem;
             text-align: center;
        }
        .print\:hidden {
            display: none !important;
        }
    }
</style>
@endsection 