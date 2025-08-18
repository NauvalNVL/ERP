<?php

namespace App\Http\Controllers;

use App\Models\ForeignCurrency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ForeignCurrencyController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'currency_code' => 'required|string|size:3|unique:foreign_currencies,currency_code',
            'country' => 'required|string|max:100',
            'currency_name' => 'required|string|max:100',
            'exchange_rate' => 'required|numeric|min:0',
            'exchange_method' => ['required', Rule::in([1, 2])],
            'variance_control' => 'required|numeric|between:0,100.00',
            'max_tax_adj' => 'required|numeric|min:0',
        ]);

        try {
            ForeignCurrency::create($validated);
            return redirect()->back()->with('success', 'Foreign currency added successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error adding foreign currency: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ForeignCurrency $foreignCurrency)
    {
        $validated = $request->validate([
            'country' => 'required|string|max:100',
            'currency_name' => 'required|string|max:100',
            'exchange_rate' => 'required|numeric|min:0',
            'exchange_method' => ['required', Rule::in([1, 2])],
            'variance_control' => 'required|numeric|between:0,100.00',
            'max_tax_adj' => 'required|numeric|min:0',
        ]);

        try {
            $foreignCurrency->update($validated);
            return redirect()->back()->with('success', 'Foreign currency updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating foreign currency: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ForeignCurrency $foreignCurrency)
    {
        try {
            $foreignCurrency->delete();
            return redirect()->back()->with('success', 'Foreign currency deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting foreign currency: ' . $e->getMessage());
        }
    }

    /**
     * API method to get all foreign currencies
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiIndex()
    {
        try {
            $currencies = ForeignCurrency::orderBy('currency_code')->get();
            return response()->json($currencies);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch currencies: ' . $e->getMessage()], 500);
        }
    }

    /**
     * API method to store a new foreign currency
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'currency_code' => 'required|string|size:3|unique:foreign_currencies,currency_code',
            'country' => 'required|string|max:100',
            'currency_name' => 'required|string|max:100',
            'exchange_rate' => 'required|numeric|min:0',
            'exchange_method' => ['required', Rule::in([1, 2])],
            'variance_control' => 'required|numeric|between:0,100.00',
            'max_tax_adj' => 'required|numeric|min:0',
        ]);

        try {
            $currency = ForeignCurrency::create($validated);
            return response()->json($currency, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create currency: ' . $e->getMessage()], 500);
        }
    }

    /**
     * API method to get a specific foreign currency
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiShow($id)
    {
        try {
            $currency = ForeignCurrency::findOrFail($id);
            return response()->json($currency);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Currency not found'], 404);
        }
    }

    /**
     * API method to update a foreign currency
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiUpdate(Request $request, $id)
    {
        try {
            $currency = ForeignCurrency::findOrFail($id);
            
            $validated = $request->validate([
                'country' => 'required|string|max:100',
                'currency_name' => 'required|string|max:100',
                'exchange_rate' => 'required|numeric|min:0',
                'exchange_method' => ['required', Rule::in([1, 2])],
                'variance_control' => 'required|numeric|between:0,100.00',
                'max_tax_adj' => 'required|numeric|min:0',
            ]);

            $currency->update($validated);
            return response()->json($currency);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update currency: ' . $e->getMessage()], 500);
        }
    }

    /**
     * API method to delete a foreign currency
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiDestroy($id)
    {
        try {
            $currency = ForeignCurrency::findOrFail($id);
            $currency->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete currency: ' . $e->getMessage()], 500);
        }
    }
}
