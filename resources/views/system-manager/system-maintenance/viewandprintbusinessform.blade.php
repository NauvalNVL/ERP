@extends('layouts.app')

@section('title', 'View & Print Business Form')

@push('styles')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #printSection, #printSection * {
            visibility: visible;
        }
        #printSection {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        /* Sembunyikan elemen yang tidak perlu saat print */
        .no-print {
            display: none !important;
        }
        /* Atur ulang margin/padding jika perlu */
        #printSection .p-6 {
            padding: 1rem; /* Kurangi padding saat print jika perlu */
        }
        table {
             width: 100%;
             border-collapse: collapse;
             font-size: 10pt; /* Ukuran font lebih kecil untuk print */
         }
         th, td {
             border: 1px solid #ccc;
             padding: 4px 6px;
             text-align: left;
         }
         th {
             background-color: #f2f2f2;
         }
    }
</style>
@endpush

@section('content')
{{-- Header Section --}}
<div class="bg-gradient-to-r from-blue-700 to-indigo-600 p-6 rounded-t-lg shadow-lg mb-6 no-print">
    <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-print mr-3"></i> View & Print Business Form
    </h2>
    <p class="text-indigo-100">View, search, and print business form records.</p>
</div>

<div class="bg-white rounded-b-lg shadow-lg p-6" id="printSection">
    <div class="no-print">
        @include('partials.alert-messages')

        {{-- Search and Action Form --}}
        <form action="{{ route('business-form.view-print') }}" method="GET" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                {{-- Search Fields --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <div class="flex space-x-2">
                        <div class="flex-1">
                             <label for="search_group" class="block text-xs font-medium text-gray-500">Group</label>
                            <input type="text" name="search_group" id="search_group" value="{{ $search_group ?? '' }}" placeholder="Filter by Group..."
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                        </div>
                        <div class="flex-1">
                            <label for="search_code" class="block text-xs font-medium text-gray-500">Code</label>
                            <input type="text" name="search_code" id="search_code" value="{{ $search_code ?? '' }}" placeholder="Filter by Code..."
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                        </div>
                        <div class="self-end">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                                <i class="fas fa-search mr-1"></i> Search
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Print Button --}}
                <div class="text-right">
                    <button type="button" onclick="window.print();" title="Print Results"
                            class="inline-flex items-center px-4 py-2 bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-800 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                        <i class="fas fa-print mr-2"></i> Print
                    </button>
                </div>
            </div>

            {{-- Placeholder Check/Approve Fields (seperti di gambar) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2 mt-4 border-t pt-4">
                 <div>
                    <label for="view_check_name" class="block text-sm font-medium text-gray-700">CHECK NAME:</label>
                    <input type="text" id="view_check_name" readonly class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm text-sm p-1">
                </div>
                 <div>
                    <label for="view_approve_name" class="block text-sm font-medium text-gray-700">APPROVE NAME:</label>
                    <input type="text" id="view_approve_name" readonly class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm text-sm p-1">
                </div>
                 <div>
                    <label for="view_check_title" class="block text-sm font-medium text-gray-700">CHECK TITLE:</label>
                    <input type="text" id="view_check_title" readonly class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm text-sm p-1">
                </div>
                 <div>
                    <label for="view_approve_title" class="block text-sm font-medium text-gray-700">APPROVE TITLE:</label>
                    <input type="text" id="view_approve_title" readonly class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm text-sm p-1">
                </div>
            </div>
        </form>
    </div>

    {{-- Business Form Table --}}
    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm mt-6">
        <table class="min-w-full divide-y divide-gray-200 bg-white">
            <thead class="bg-gray-100">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Group</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ISO</th>
                    {{-- Tambahkan header Check/Approve jika datanya ingin ditampilkan juga di tabel --}}
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($businessForms as $form)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $form->bf_code }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $form->bf_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $form->bf_group }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $form->bf_iso ?? 'N/A' }}</td>
                         {{-- Tambahkan data Check/Approve jika ingin ditampilkan --}}
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No business forms found matching your criteria.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Tampilkan pagination jika menggunakan paginate() di controller --}}
    {{-- @if ($businessForms instanceof \Illuminate\Pagination\LengthAwarePaginator && $businessForms->hasPages())
        <div class="mt-4 no-print">
            {{ $businessForms->appends(request()->query())->links() }} 
        </div>
    @endif --}}

</div>
@endsection
