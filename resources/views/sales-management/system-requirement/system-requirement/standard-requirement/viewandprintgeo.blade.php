@extends('layouts.app')

@section('title', 'View and Print Geo Data')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-globe-americas mr-2"></i>Geo Data List
            </h1>
            <button onclick="window.print()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg print:hidden">
                <i class="fas fa-print mr-2"></i>Print
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="py-3 px-4 border-b text-left">No</th>
                        <th class="py-3 px-4 border-b text-left">Code</th>
                        <th class="py-3 px-4 border-b text-left">Country</th>
                        <th class="py-3 px-4 border-b text-left">State</th>
                        <th class="py-3 px-4 border-b text-left">Town</th>
                        <th class="py-3 px-4 border-b text-left">Town Section</th>
                        <th class="py-3 px-4 border-b text-left">Area</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($geos as $index => $geo)
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 px-4 border-b">{{ $index + 1 }}</td>
                            <td class="py-2 px-4 border-b font-medium">{{ $geo->code }}</td>
                            <td class="py-2 px-4 border-b">{{ $geo->country }}</td>
                            <td class="py-2 px-4 border-b">{{ $geo->state }}</td>
                            <td class="py-2 px-4 border-b">{{ $geo->town }}</td>
                            <td class="py-2 px-4 border-b">{{ $geo->town_section }}</td>
                            <td class="py-2 px-4 border-b">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                    {{ $geo->area }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-4 px-4 text-center text-gray-500">No geo data available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

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
        }
        .print\:hidden {
            display: none;
        }
    }
</style>
@endsection 