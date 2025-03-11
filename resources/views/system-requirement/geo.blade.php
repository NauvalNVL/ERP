@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Define Geo</h1>

    <div class="flex items-center mb-4">
        <label for="geoCode" class="mr-2">Geo Code:</label>
        <input type="text" id="geoCode" class="border border-gray-300 rounded-md p-2 mr-2" placeholder="Enter Geo Code">
        <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-200" onclick="toggleModal('geoModal')">
            Show Details
        </button>
    </div>

    <!-- Modal -->
    <div id="geoModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="modal-overlay absolute inset-0 bg-gray-500 opacity-50"></div>
        <div class="modal-content bg-white rounded-lg shadow-lg z-10 w-3/4">
            <div class="modal-header p-4 border-b">
                <h5 class="modal-title text-lg font-bold">Geo Area Table</h5>
                <button type="button" class="close text-gray-500" onclick="toggleModal('geoModal')">
                    &times;
                </button>
            </div>
            <div class="modal-body p-4">
                <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">Code</th>
                            <th class="py-3 px-6 text-left">Country</th>
                            <th class="py-3 px-6 text-left">State</th>
                            <th class="py-3 px-6 text-left">Town</th>
                            <th class="py-3 px-6 text-left">Town Section</th>
                            <th class="py-3 px-6 text-left">Area</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        @foreach($geoData as $geo)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6">{{ $geo->id }}</td>
                            <td class="py-3 px-6">{{ $geo->country }}</td>
                            <td class="py-3 px-6">{{ $geo->state }}</td>
                            <td class="py-3 px-6">{{ $geo->town }}</td>
                            <td class="py-3 px-6">{{ $geo->town_section }}</td>
                            <td class="py-3 px-6">{{ $geo->area }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer p-4 border-t">
                <button type="button" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-200" onclick="toggleModal('geoModal')">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.toggle('hidden');
    }
</script>
@endsection