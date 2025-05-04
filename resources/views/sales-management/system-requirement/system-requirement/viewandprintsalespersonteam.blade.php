@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-semibold text-gray-700">View & Print Salesperson Team</h1>
        <div class="flex space-x-4">
            <button onclick="window.print()" class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-sm flex items-center">
                <i class="fas fa-print mr-2"></i>
                Print
            </button>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-4">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Salesperson Code</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Salesperson Name</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Team Code</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Team Name</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Position</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($salespersonTeams as $team)
                    <tr>
                        <td class="px-4 py-2 whitespace-nowrap text-gray-700">{{ $team->s_person_code }}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-gray-700">{{ $team->salesperson_name }}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-gray-700">{{ $team->st_code }}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-gray-700">{{ $team->sales_team_name }}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-gray-700">{{ $team->sales_team_position }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-2 text-center text-gray-500">No salesperson teams found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    @media print {
        .container {
            width: 100%;
            max-width: none;
        }
        button {
            display: none;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
        }
        th {
            background-color: #f2f2f2;
            font-weight: 600;
        }
        h1 {
            font-size: 16px;
            margin-bottom: 10px;
        }
    }
</style>
@endsection
