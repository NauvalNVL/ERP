@extends('layouts.app')

@section('title', 'Define Sales Configuration')

@section('header', 'Define Sales Configuration')

@section('content')
<div class="min-h-screen bg-gray-100 py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Define Sales Configuration</h1>
            <p class="mt-1 text-sm text-gray-600">Manage your sales system configuration settings</p>
        </div>

        <form method="POST" action="{{ route('sales-configuration.store') }}">
            @csrf
            <div class="space-y-6">
                <!-- MC Card Regulation & Workflow -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">
                            <i class="fas fa-clipboard-list mr-2 text-blue-500"></i>
                            MC Card Regulation & Workflow
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="space-y-4">
                            <label class="block text-sm font-medium text-gray-700">Printable Paper Quality</label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <label class="relative flex items-center p-3 rounded-lg border border-gray-200 hover:border-blue-500 cursor-pointer transition-colors">
                                    <input type="radio" name="paper_quality" value="y_check" class="h-4 w-4 text-blue-600 focus:ring-blue-500" checked>
                                    <span class="ml-3 text-gray-900">Y-Check & Block</span>
                                </label>
                                <label class="relative flex items-center p-3 rounded-lg border border-gray-200 hover:border-blue-500 cursor-pointer transition-colors">
                                    <input type="radio" name="paper_quality" value="p_check" class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-3 text-gray-900">P-Check & Prompt</span>
                                </label>
                                <label class="relative flex items-center p-3 rounded-lg border border-gray-200 hover:border-blue-500 cursor-pointer transition-colors">
                                    <input type="radio" name="paper_quality" value="no_check" class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-3 text-gray-900">N-No Check</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activate Link to Salesperson -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">
                            <i class="fas fa-link mr-2 text-green-500"></i>
                            Activate Link to Salesperson
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="space-y-4">
                            @foreach ([
                                'Customer Desktop Service' => 'desktop_service',
                                'Customer Account Credit' => 'account_credit',
                                'Customer Sales Order' => 'sales_order'
                            ] as $label => $name)
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-4 rounded-lg bg-gray-50">
                                <span class="text-sm font-medium text-gray-700 mb-2 sm:mb-0">{{ $label }}</span>
                                <div class="flex space-x-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="{{ $name }}" value="yes" class="h-4 w-4 text-blue-600 focus:ring-blue-500" checked>
                                        <span class="ml-2 text-sm text-gray-700">Y-Yes</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="{{ $name }}" value="no" class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-700">N-No</span>
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Master Card -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">
                            <i class="fas fa-id-card mr-2 text-purple-500"></i>
                            Master Card
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6 space-y-6">
                        <!-- Flute Pattern & Scoring Tool -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Flute Pattern</label>
                                <div class="flex space-x-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                    <input type="text" class="w-16 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" maxlength="2">
                                    @endfor
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Scoring Tool</label>
                                <div class="flex space-x-2">
                                    <input type="number" class="w-24 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" min="0">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Radio Button Groups -->
                        <div class="space-y-6">
                            @foreach ([
                                'Check MCP Stage' => 'mcp_stage',
                                'Amount Approved M/Card' => 'amount_approved'
                            ] as $label => $name)
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">{{ $label }}</label>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <label class="relative flex items-center p-3 rounded-lg border border-gray-200 hover:border-blue-500 cursor-pointer transition-colors">
                                        <input type="radio" name="{{ $name }}" value="y_check" class="h-4 w-4 text-blue-600 focus:ring-blue-500" checked>
                                        <span class="ml-3 text-gray-900">Y-Check & Block</span>
                                    </label>
                                    <label class="relative flex items-center p-3 rounded-lg border border-gray-200 hover:border-blue-500 cursor-pointer transition-colors">
                                        <input type="radio" name="{{ $name }}" value="p_check" class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                        <span class="ml-3 text-gray-900">P-Check & Prompt</span>
                                    </label>
                                    <label class="relative flex items-center p-3 rounded-lg border border-gray-200 hover:border-blue-500 cursor-pointer transition-colors">
                                        <input type="radio" name="{{ $name }}" value="no_check" class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                        <span class="ml-3 text-gray-900">N-No Check</span>
                                    </label>
                                </div>
                            </div>
                            @endforeach

                            <!-- Access W/O In-Qualifier -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Access W/O In-Qualifier</label>
                                <div class="flex space-x-4">
                                    <label class="relative flex items-center p-3 rounded-lg border border-gray-200 hover:border-blue-500 cursor-pointer transition-colors">
                                        <input type="radio" name="wo_qualifier" value="yes" class="h-4 w-4 text-blue-600 focus:ring-blue-500" checked>
                                        <span class="ml-3 text-gray-900">Y-Yes</span>
                                    </label>
                                    <label class="relative flex items-center p-3 rounded-lg border border-gray-200 hover:border-blue-500 cursor-pointer transition-colors">
                                        <input type="radio" name="wo_qualifier" value="no" class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                        <span class="ml-3 text-gray-900">N-No</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Copy & Paste Master Card -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">
                            <i class="fas fa-copy mr-2 text-indigo-500"></i>
                            Copy & Paste Master Card
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="space-y-4">
                            @foreach ([
                                'Copy Master Card Price' => 'copy_price',
                                'Copy from Sub-System' => 'copy_subsystem'
                            ] as $label => $name)
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-4 rounded-lg bg-gray-50">
                                <span class="text-sm font-medium text-gray-700 mb-2 sm:mb-0">{{ $label }}</span>
                                <div class="flex space-x-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="{{ $name }}" value="yes" class="h-4 w-4 text-blue-600 focus:ring-blue-500" checked>
                                        <span class="ml-2 text-sm text-gray-700">Y-Yes</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="{{ $name }}" value="no" class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-700">N-No</span>
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Sales Order Delivery Schedule -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">
                            <i class="fas fa-truck mr-2 text-yellow-500"></i>
                            Sales Order Delivery Schedule
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="space-y-4">
                            @foreach ([
                                ['Sheet Board Availability', 'board_availability', ['1-Computer', '2-Sheet Board Reserve']],
                                ['FG Picking List', 'fg_picking', ['1-Lock Down by MCR', '2-Lock Down by SOR']]
                            ] as [$label, $name, $options])
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-4 rounded-lg bg-gray-50">
                                <span class="text-sm font-medium text-gray-700 mb-2 sm:mb-0">{{ $label }}</span>
                                <div class="flex space-x-4">
                                    @foreach ($options as $index => $option)
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="{{ $name }}" value="{{ $index + 1 }}" class="h-4 w-4 text-blue-600 focus:ring-blue-500" {{ $index === 0 ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-700">{{ $option }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Sales Order Sheet Board -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">
                            <i class="fas fa-layer-group mr-2 text-red-500"></i>
                            Sales Order Sheet Board
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Scoring Tool</label>
                            <div class="flex space-x-2">
                                <input type="number" class="w-24 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" min="0">
                                <button type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3">
                    <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        <i class="fas fa-times mr-2"></i>
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i>
                        Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
