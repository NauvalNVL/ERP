<?php

namespace App\Http\Controllers\MaterialManagement\SystemRequirement;

use App\Http\Controllers\Controller;
use App\Models\SkuPrice;
use App\Models\MmSku;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class MmSkuPriceController extends Controller
{
    /**
     * Display the SKU Price management page
     */
    public function index()
    {
        return Inertia::render('material-management/system-requirement/inventory-setup/SkuPrice');
    }

    /**
     * Get SKU prices with filters
     */
    public function search(Request $request)
    {
        try {
            $query = SkuPrice::with('sku')->newQuery();

            // Search by SKU code
            if ($request->filled('sku_code')) {
                $query->where('sku_code', 'like', '%' . $request->input('sku_code') . '%');
            }

            // Filter by active status
            if ($request->filled('is_active')) {
                $query->where('is_active', $request->boolean('is_active'));
            }

            // Filter by effective date range
            if ($request->filled('from_date')) {
                $query->where('effective_date', '>=', $request->input('from_date'));
            }

            if ($request->filled('to_date')) {
                $query->where('effective_date', '<=', $request->input('to_date'));
            }

            $skuPrices = $query->orderBy('effective_date', 'desc')
                              ->orderBy('sku_code')
                              ->paginate(50);

            return response()->json($skuPrices);
        } catch (\Exception $e) {
            Log::error('Error in MmSkuPriceController@search: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to search SKU prices'], 500);
        }
    }

    /**
     * Store a new SKU price
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku_code' => ['required', 'string', 'max:20', Rule::exists('mm_skus', 'sku')],
            'price' => ['required', 'numeric', 'min:0'],
            'effective_date' => ['required', 'date'],
            'is_active' => ['boolean'],
        ]);

        // Check for duplicate effective date for the same SKU
        $existingPrice = SkuPrice::where('sku_code', $validated['sku_code'])
                                ->where('effective_date', $validated['effective_date'])
                                ->first();

        if ($existingPrice) {
            return response()->json([
                'message' => 'A price already exists for this SKU on the specified date.',
                'error' => 'duplicate_date'
            ], 422);
        }

        DB::beginTransaction();
        try {
            // If this is set as active, deactivate other prices for this SKU
            if ($validated['is_active'] ?? false) {
                SkuPrice::where('sku_code', $validated['sku_code'])
                        ->where('is_active', true)
                        ->update(['is_active' => false]);
            }

            $skuPrice = SkuPrice::create($validated);
            
            DB::commit();
            
            return response()->json([
                'message' => 'SKU price created successfully',
                'data' => $skuPrice->load('sku')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating SKU price: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to create SKU price.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update an existing SKU price
     */
    public function update(Request $request, $id)
    {
        $skuPrice = SkuPrice::findOrFail($id);

        $validated = $request->validate([
            'price' => ['required', 'numeric', 'min:0'],
            'effective_date' => ['required', 'date'],
            'is_active' => ['boolean'],
        ]);

        // Check for duplicate effective date for the same SKU (excluding current record)
        $existingPrice = SkuPrice::where('sku_code', $skuPrice->sku_code)
                                ->where('effective_date', $validated['effective_date'])
                                ->where('id', '!=', $id)
                                ->first();

        if ($existingPrice) {
            return response()->json([
                'message' => 'A price already exists for this SKU on the specified date.',
                'error' => 'duplicate_date'
            ], 422);
        }

        DB::beginTransaction();
        try {
            // If this is set as active, deactivate other prices for this SKU
            if ($validated['is_active'] ?? false) {
                SkuPrice::where('sku_code', $skuPrice->sku_code)
                        ->where('is_active', true)
                        ->where('id', '!=', $id)
                        ->update(['is_active' => false]);
            }
            
            $skuPrice->update($validated);
            
            DB::commit();
            
            return response()->json([
                'message' => 'SKU price updated successfully',
                'data' => $skuPrice->load('sku')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating SKU price: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to update SKU price.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete an SKU price
     */
    public function destroy($id)
    {
        try {
            $skuPrice = SkuPrice::findOrFail($id);
            $skuPrice->delete();
            
            return response()->json([
                'message' => 'SKU price deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting SKU price: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to delete SKU price.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get current price for a specific SKU
     */
    public function getCurrentPrice($skuCode)
    {
        try {
            $sku = MmSku::where('sku', $skuCode)->firstOrFail();
            $currentPrice = $sku->currentPrice()->first();
            
            if (!$currentPrice) {
                return response()->json(['message' => 'No active price found for this SKU.'], 404);
            }

            return response()->json($currentPrice);
        } catch (\Exception $e) {
            Log::error('Error getting current price: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to get current price'], 500);
        }
    }

    /**
     * Display the View & Print SKU Price page
     */
    public function viewPrint()
    {
        return Inertia::render('material-management/system-requirement/inventory-setup/ViewPrintSkuPrice');
    }

    /**
     * Get SKU prices for printing/viewing
     */
    public function getSkuPricesForPrint(Request $request)
    {
        try {
            $query = SkuPrice::with(['sku', 'sku.category'])->newQuery();

            // Apply filters if provided
            if ($request->filled('sku_code')) {
                $query->where('sku_code', 'like', '%' . $request->input('sku_code') . '%');
            }

            if ($request->filled('category_code')) {
                $query->whereHas('sku', function ($q) use ($request) {
                    $q->where('category_code', $request->input('category_code'));
                });
            }

            if ($request->filled('is_active')) {
                $query->where('is_active', $request->boolean('is_active'));
            }

            if ($request->filled('from_date')) {
                $query->where('effective_date', '>=', $request->input('from_date'));
            }

            if ($request->filled('to_date')) {
                $query->where('effective_date', '<=', $request->input('to_date'));
            }

            $skuPrices = $query->orderBy('sku_code')
                              ->orderBy('effective_date', 'desc')
                              ->get();

            return response()->json($skuPrices);
        } catch (\Exception $e) {
            Log::error('Error in getSkuPricesForPrint: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch SKU prices for print'], 500);
        }
    }

    /**
     * Validate SKU exists for price creation
     */
    public function validateSku(Request $request)
    {
        $request->validate([
            'sku_code' => 'required|string'
        ]);

        try {
            $sku = MmSku::where('sku', $request->sku_code)->first();
            
            if (!$sku) {
                return response()->json([
                    'valid' => false,
                    'message' => 'SKU not found'
                ], 404);
            }

            return response()->json([
                'valid' => true,
                'sku' => $sku,
                'message' => 'SKU found'
            ]);
        } catch (\Exception $e) {
            Log::error('Error validating SKU: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to validate SKU'], 500);
        }
    }
}