@extends('layouts.app')

@section('title', 'Define Configuration - ERP System')

@section('content')
<div class="container mx-auto px-4 sm:px-6">
    <div class="bg-white shadow-md rounded-lg p-4 sm:p-6">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">
            <i class="fas fa-cog mr-2 text-blue-500"></i>Define ERP
        </h1>

        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        <form method="POST" action="{{ route('system-configuration.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <!-- Business Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-building mr-2 text-blue-500"></i>
                            Business Name
                        </label>
                        <input type="text" name="business_name" value="{{ $config->business_name }}"
                            class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Registration Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-id-card mr-2 text-blue-500"></i>
                            Registration Number
                        </label>
                        <input type="text" name="registration_number" value="{{ $config->registration_number }}"
                            class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Configuration Version -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-code-branch mr-2 text-blue-500"></i>
                            Configuration Version
                        </label>
                        <div class="flex rounded-md shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                v
                            </span>
                            <input type="text" name="cps_version" value="{{ $config->cps_version }}"
                                class="form-input flex-1 block w-full rounded-none rounded-r-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <!-- Display Registration -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-eye mr-2 text-blue-500"></i>
                            Display Registration
                        </label>
                        <div class="flex space-x-4 mt-1">
                            <label class="inline-flex items-center">
                                <input type="radio" name="display_registration" value="1"
                                    {{ $config->display_registration ? 'checked' : '' }}
                                    class="form-radio h-4 w-4 text-blue-600">
                                <span class="ml-2 text-gray-600">Yes</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="display_registration" value="0"
                                    {{ !$config->display_registration ? 'checked' : '' }}
                                    class="form-radio h-4 w-4 text-blue-600">
                                <span class="ml-2 text-gray-600">No</span>
                            </label>
                        </div>
                    </div>

                    <!-- Logo Appear Time -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-clock mr-2 text-blue-500"></i>
                            Logo Appear Time
                        </label>
                        <select name="logo_appear_time"
                            class="form-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @for($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}" {{ $config->logo_appear_time == $i ? 'selected' : '' }}>
                                {{ $i }} seconds
                                </option>
                                @endfor
                        </select>
                    </div>

                    <!-- Date Validity -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                            Date Validity
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-1">
                            <input type="date" name="date_validity_start" value="{{ $config->date_validity_start }}"
                                class="form-input rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <input type="date" name="date_validity_end" value="{{ $config->date_validity_end }}"
                                class="form-input rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Turbo Loop Engine -->
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    <i class="fas fa-tachometer-alt mr-2 text-blue-500"></i>
                    Turbo Loop Engine
                </label>
                <div class="relative mt-1">
                    <input type="text" name="turbo_loop_engine" value="{{ $config->turbo_loop_engine }}"
                        class="form-input block w-full rounded-md border-gray-300 shadow-sm pr-24 focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Enter value">
                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm text-gray-500">
                        Recommended: 100
                    </span>
                </div>
            </div>

            <!-- Financial System Code -->
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    <i class="fas fa-coins mr-2 text-blue-500"></i>
                    Financial System Code
                </label>
                <input type="text" name="financial_system_code" value="{{ $config->financial_system_code }}"
                    class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="01" pattern="\d{2}">
            </div>

            <!-- Period-End Closing -->
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    <i class="fas fa-calendar-times mr-2 text-blue-500"></i>
                    Period-End Closing
                </label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-1">
                    <div class="p-4 border rounded-md hover:border-blue-500 transition-colors">
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="radio" name="period_end_closing"
                                value="X-Check & Block when new period > system date"
                                {{ $config->period_end_closing == 'X-Check & Block when new period > system date' ? 'checked' : '' }}
                                class="form-radio h-4 w-4 text-blue-600">
                            <div>
                                <span class="bg-red-100 text-red-800 text-xs font-semibold px-2 py-1 rounded">X-Check</span>
                                <p class="mt-1 text-sm text-gray-600">Block when new period > system date</p>
                            </div>
                        </label>
                    </div>
                    <div class="p-4 border rounded-md hover:border-blue-500 transition-colors">
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="radio" name="period_end_closing"
                                value="C-Check & Prompt when new period > system date"
                                {{ $config->period_end_closing == 'C-Check & Prompt when new period > system date' ? 'checked' : '' }}
                                class="form-radio h-4 w-4 text-blue-600">
                            <div>
                                <span class="bg-amber-100 text-amber-800 text-xs font-semibold px-2 py-1 rounded">C-Check</span>
                                <p class="mt-1 text-sm text-gray-600">Prompt when new period > system date</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Additional Configuration -->
            <div class="pt-6 border-t mt-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-cubes mr-2 text-blue-500"></i>
                    Additional Configuration Modules
                </h3>
                @include("components.system.additionalConfiguration")
            </div>
        </form>
    </div>
</div>
@endsection