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
}
