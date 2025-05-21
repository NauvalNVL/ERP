<?php

namespace App\Http\Controllers;

use App\Models\ForeignCurrency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ForeignCurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currencies = ForeignCurrency::orderBy('currency_code')->get();
        return view('system-manager.system-maintenance.defineforeigncurrency', compact('currencies'));
    }

    /**
     * Show the form for creating a new resource.
     * This can often be handled within the index view for simpler interfaces.
     */
    public function create()
    {
        // Typically not needed if using a single page for list and form
        return redirect()->route('foreign-currency.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'currency_code' => 'required|string|size:3|unique:foreign_currencies,currency_code',
            'country' => 'required|string|max:100',
            'currency_name' => 'required|string|max:100',
            'exchange_rate' => 'required|numeric|min:0',
            'exchange_method' => ['required', Rule::in([1, 2])],
            'variance_control' => 'required|numeric|between:0,100.00',
            'max_tax_adj' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->route('foreign-currency.index')
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validation failed. Please check the form for errors.');
        }

        try {
            ForeignCurrency::create($validator->validated());
            return redirect()->route('foreign-currency.index')
                ->with('success', 'Foreign currency added successfully.');
        } catch (\Exception $e) {
            return redirect()->route('foreign-currency.index')
                ->with('error', 'Error adding foreign currency: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     * Often not needed for a simple CRUD page like this.
     */
    public function show(ForeignCurrency $foreignCurrency)
    {
        return redirect()->route('foreign-currency.index');
    }

    /**
     * Show the form for editing the specified resource.
     * This will pass the specific currency to the index view for editing.
     */
    public function edit(ForeignCurrency $foreignCurrency)
    {
        $currencies = ForeignCurrency::orderBy('currency_code')->get();
        return view('system-manager.system-maintenance.defineforeigncurrency', [
            'currencies' => $currencies,
            'editCurrency' => $foreignCurrency // Pass the currency to be edited
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ForeignCurrency $foreignCurrency)
    {
        $validator = Validator::make($request->all(), [
            // Currency code is typically not updatable, but if it is:
            // 'currency_code' => ['required', 'string', 'size:3', Rule::unique('foreign_currencies')->ignore($foreignCurrency->id)],
            'country' => 'required|string|max:100',
            'currency_name' => 'required|string|max:100',
            'exchange_rate' => 'required|numeric|min:0',
            'exchange_method' => ['required', Rule::in([1, 2])],
            'variance_control' => 'required|numeric|between:0,100.00',
            'max_tax_adj' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->route('foreign-currency.edit', $foreignCurrency->id) // Redirect back to edit form
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validation failed. Please check the form for errors.');
        }

        try {
            $foreignCurrency->update($validator->validated());
            return redirect()->route('foreign-currency.index')
                ->with('success', 'Foreign currency updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('foreign-currency.edit', $foreignCurrency->id)
                ->with('error', 'Error updating foreign currency: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ForeignCurrency $foreignCurrency)
    {
        try {
            $foreignCurrency->delete();
            return redirect()->route('foreign-currency.index')
                ->with('success', 'Foreign currency deleted successfully.');
        } catch (\Exception $e) {
             // You might want to check for foreign key constraints before deleting
            return redirect()->route('foreign-currency.index')
                ->with('error', 'Error deleting foreign currency: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of the resource for printing.
     */
    public function viewAndPrint()
    {
        $currencies = ForeignCurrency::orderBy('currency_code')->get();
        // Assuming a view exists at: system-manager.system-maintenance.viewandprintforeigncurrency
        return view('system-manager.system-maintenance.viewandprintforeigncurrency', compact('currencies'));
    }

    /**
     * API method to get all foreign currencies
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiIndex()
    {
        try {
            $currencies = ForeignCurrency::all();
            return response()->json($currencies);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch currencies: ' . $e->getMessage()], 500);
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
}
