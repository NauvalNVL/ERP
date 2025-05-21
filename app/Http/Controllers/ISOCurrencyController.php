<?php

namespace App\Http\Controllers;

use App\Models\ISOCurrency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class ISOCurrencyController extends Controller
{
    /**
     * Display a listing of the ISO currencies.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $isoCurrencies = ISOCurrency::orderBy('iso_code')->get();
        return view('system-manager.system-maintenance.defineisocurrency', compact('isoCurrencies'));
    }

    /**
     * Store a newly created ISO currency in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'iso_code' => 'required|string|max:3|unique:iso_currencies,iso_code',
            'currency_name' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'numeric_code' => 'required|string|max:3',
            'minor_unit' => 'required|integer|min:0|max:4',
            'symbol' => 'nullable|string|max:10',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        ISOCurrency::create($request->all());

        return back()->with('success', 'ISO Currency created successfully.');
    }

    /**
     * Show the form for editing the specified ISO currency.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $isoCurrency = ISOCurrency::findOrFail($id);
        $isoCurrencies = ISOCurrency::orderBy('iso_code')->get();
        
        return view('system-manager.system-maintenance.defineisocurrency', compact('isoCurrency', 'isoCurrencies'));
    }

    /**
     * Update the specified ISO currency in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $isoCurrency = ISOCurrency::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'iso_code' => 'required|string|max:3|unique:iso_currencies,iso_code,' . $id,
            'currency_name' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'numeric_code' => 'required|string|max:3',
            'minor_unit' => 'required|integer|min:0|max:4',
            'symbol' => 'nullable|string|max:10',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $isoCurrency->update($request->all());

        return back()->with('success', 'ISO Currency updated successfully.');
    }

    /**
     * Remove the specified ISO currency from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $isoCurrency = ISOCurrency::findOrFail($id);
        $isoCurrency->delete();

        return back()->with('success', 'ISO Currency deleted successfully.');
    }

    /**
     * API endpoint to get all ISO currencies.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiIndex()
    {
        $isoCurrencies = ISOCurrency::orderBy('iso_code')->get();
        return response()->json($isoCurrencies);
    }

    /**
     * API endpoint to get a specific ISO currency.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiShow($id)
    {
        $isoCurrency = ISOCurrency::findOrFail($id);
        return response()->json($isoCurrency);
    }

    /**
     * API endpoint to create a new ISO currency.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'iso_code' => 'required|string|max:3|unique:iso_currencies,iso_code',
            'currency_name' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'numeric_code' => 'required|string|max:3',
            'minor_unit' => 'required|integer|min:0|max:4',
            'symbol' => 'nullable|string|max:10',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $isoCurrency = ISOCurrency::create($request->all());

        return response()->json($isoCurrency, 201);
    }

    /**
     * API endpoint to update an ISO currency.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiUpdate(Request $request, $id)
    {
        $isoCurrency = ISOCurrency::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'iso_code' => 'required|string|max:3|unique:iso_currencies,iso_code,' . $id,
            'currency_name' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'numeric_code' => 'required|string|max:3',
            'minor_unit' => 'required|integer|min:0|max:4',
            'symbol' => 'nullable|string|max:10',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $isoCurrency->update($request->all());

        return response()->json($isoCurrency);
    }

    /**
     * API endpoint to delete an ISO currency.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiDestroy($id)
    {
        $isoCurrency = ISOCurrency::findOrFail($id);
        $isoCurrency->delete();

        return response()->json(null, 204);
    }
} 