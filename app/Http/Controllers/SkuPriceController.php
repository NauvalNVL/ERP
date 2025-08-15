<?php

namespace App\Http\Controllers;

use App\Models\SkuPrice;
use App\Models\MmSku;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class SkuPriceController extends Controller
{
    public function index(Request $request)
    {
        $query = SkuPrice::query();

        if ($request->has('sku_code')) {
            $query->where('sku_code', $request->input('sku_code'));
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->input('is_active'));
        }

        $skuPrices = $query->orderBy('effective_date', 'desc')->get();
        return response()->json($skuPrices);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku_code' => ['required', 'string', 'max:20', Rule::exists('mm_skus', 'sku')],
            'price' => ['required', 'numeric', 'min:0'],
            'effective_date' => ['required', 'date', 'unique:sku_prices,effective_date,NULL,id,sku_code,' . $request->sku_code],
            'is_active' => ['boolean'],
        ]);

        DB::beginTransaction();
        try {
            // Deactivate any existing active price for this SKU on or after the new effective date
            SkuPrice::where('sku_code', $validated['sku_code'])
                      ->where('effective_date', '>=', $validated['effective_date'])
                      ->update(['is_active' => false]);

            $skuPrice = SkuPrice::create($validated);
            DB::commit();
            return response()->json($skuPrice, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to store SKU price.', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $skuPrice = SkuPrice::findOrFail($id);
        return response()->json($skuPrice);
    }

    public function update(Request $request, $id)
    {
        $skuPrice = SkuPrice::findOrFail($id);

        $validated = $request->validate([
            'price' => ['required', 'numeric', 'min:0'],
            'effective_date' => ['required', 'date', Rule::unique('sku_prices')->ignore($skuPrice->id)->where(function ($query) use ($request) {
                return $query->where('sku_code', $request->sku_code);
            })],
            'is_active' => ['boolean'],
        ]);

        DB::beginTransaction();
        try {
            // If effective_date is changed and becomes newer, deactivate existing prices
            if ($skuPrice->effective_date != $validated['effective_date']) {
                 SkuPrice::where('sku_code', $skuPrice->sku_code)
                           ->where('effective_date', '>=', $validated['effective_date'])
                           ->where('id', '!=', $skuPrice->id)
                           ->update(['is_active' => false]);
            }
            
            $skuPrice->update($validated);
            DB::commit();
            return response()->json($skuPrice);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update SKU price.', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $skuPrice = SkuPrice::findOrFail($id);
        $skuPrice->delete();
        return response()->json(null, 204);
    }

    public function getCurrentPrice($skuCode)
    {
        $sku = MmSku::where('sku', $skuCode)->firstOrFail();
        $currentPrice = $sku->currentPrice()->first();
        
        if (!$currentPrice) {
            return response()->json(['message' => 'No active price found for this SKU.'], 404);
        }

        return response()->json($currentPrice);
    }

    public function seedSampleData()
    {
        // This method can be implemented later if sample data seeding is required.
        return response()->json(['message' => 'SKU Price sample data seeding not yet implemented.'], 200);
    }

    /**
     * Get SKU prices with comprehensive filtering for View & Print
     */
    public function getSkuPricesForPrint(Request $request)
    {
        try {
            $query = SkuPrice::with(['sku', 'currency']);

            // Apply filters
            if ($request->has('sku_from') && $request->sku_from) {
                $query->whereHas('sku', function ($q) use ($request) {
                    $q->where('sku', '>=', $request->sku_from);
                });
            }

            if ($request->has('sku_to') && $request->sku_to) {
                $query->whereHas('sku', function ($q) use ($request) {
                    $q->where('sku', '<=', $request->sku_to);
                });
            }

            if ($request->has('category_from') && $request->category_from) {
                $query->whereHas('sku', function ($q) use ($request) {
                    $q->where('category_code', '>=', $request->category_from);
                });
            }

            if ($request->has('category_to') && $request->category_to) {
                $query->whereHas('sku', function ($q) use ($request) {
                    $q->where('category_code', '<=', $request->category_to);
                });
            }

            if ($request->has('currency') && $request->currency) {
                $query->where('currency_code', $request->currency);
            }

            if ($request->has('price_date_from') && $request->price_date_from) {
                $query->where('effective_date', '>=', $request->price_date_from);
            }

            if ($request->has('price_date_to') && $request->price_date_to) {
                $query->where('effective_date', '<=', $request->price_date_to);
            }

            if ($request->has('sku_types') && is_array($request->sku_types)) {
                $query->whereHas('sku', function ($q) use ($request) {
                    $q->whereIn('type', $request->sku_types);
                });
            }

            if ($request->has('sku_status') && is_array($request->sku_status)) {
                $query->whereHas('sku', function ($q) use ($request) {
                    $q->whereIn('is_active', $request->sku_status === 'A' ? [true] : [false]);
                });
            }

            if ($request->has('price_history') && is_array($request->price_history)) {
                $query->whereIn('price_type', $request->price_history);
            }

            if ($request->has('po_status') && is_array($request->po_status)) {
                $query->whereIn('po_status', $request->po_status);
            }

            // Apply sorting
            $sortBy = $request->input('sort_by', 'category_sku_currency');
            
            if ($sortBy === 'category_sku_currency') {
                $query->join('mm_skus', 'sku_prices.sku_code', '=', 'mm_skus.sku')
                      ->orderBy('mm_skus.category_code')
                      ->orderBy('mm_skus.sku')
                      ->orderBy('sku_prices.currency_code')
                      ->select('sku_prices.*');
            } else {
                $query->join('mm_skus', 'sku_prices.sku_code', '=', 'mm_skus.sku')
                      ->orderBy('mm_skus.sku')
                      ->orderBy('sku_prices.currency_code')
                      ->select('sku_prices.*');
            }

            $skuPrices = $query->get();

            // Transform data to include SKU and currency information
            $result = $skuPrices->map(function ($price) {
                return [
                    'id' => $price->id,
                    'sku' => $price->sku->sku ?? $price->sku_code,
                    'sku_name' => $price->sku->sku_name ?? '',
                    'category_code' => $price->sku->category_code ?? '',
                    'type' => $price->sku->type ?? '',
                    'uom' => $price->sku->uom ?? '',
                    'boh' => $price->sku->boh ?? 0,
                    'fpo' => $price->sku->fpo ?? 0,
                    'rol' => $price->sku->rol ?? 0,
                    'price' => $price->price,
                    'currency_code' => $price->currency_code,
                    'currency_name' => $price->currency->currency_name ?? '',
                    'effective_date' => $price->effective_date,
                    'is_active' => $price->is_active,
                    'price_type' => $price->price_type,
                    'po_status' => $price->po_status,
                    'notes' => $price->notes,
                ];
            });

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch SKU prices: ' . $e->getMessage()], 500);
        }
    }
}
