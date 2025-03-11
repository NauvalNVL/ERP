@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Define Geo</h1>

    <div class="flex items-center mb-4">
        <label for="geoCode" class="mr-2">Geo Code:</label>
        <input type="text" id="geoCode" class="border border-gray-300 rounded-md p-2 mr-2" placeholder="Enter Geo Code">
        <button class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-200">Select</button>
        <button type="button" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition duration-200 ml-2">Record</button>
    </div>

    <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-200 mb-4" data-toggle="modal" data-target="#geoModal">
        Add Geo
    </button>

    <!-- Modal -->
    <div class="modal fade" id="geoModal" tabindex="-1" role="dialog" aria-labelledby="geoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('geo.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="geoModalLabel">Add Geo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="space-y-4">
                            <div class="form-group">
                                <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                                <input type="text" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" name="country" required>
                            </div>
                            <div class="form-group">
                                <label for="state" class="block text-sm font-medium text-gray-700">State</label>
                                <input type="text" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" name="state" required>
                            </div>
                            <div class="form-group">
                                <label for="town" class="block text-sm font-medium text-gray-700">Town</label>
                                <input type="text" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" name="town" required>
                            </div>
                            <div class="form-group">
                                <label for="town_section" class="block text-sm font-medium text-gray-700">Town Section</label>
                                <input type="text" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" name="town_section" required>
                            </div>
                            <div class="form-group">
                                <label for="area" class="block text-sm font-medium text-gray-700">Area</label>
                                <input type="text" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" name="area" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-200" data-dismiss="modal">Close</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-200">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection