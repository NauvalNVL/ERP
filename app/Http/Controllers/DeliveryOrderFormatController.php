<?php

namespace App\Http\Controllers;

use App\Models\DeliveryOrderFormat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class DeliveryOrderFormatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('warehouse-management/FinishedGoods/SetupMaintenance/define-delivery-order-format');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required|string|max:255|unique:delivery_order_formats',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'printer' => 'nullable|string|max:255',
        ]);

        $format = DeliveryOrderFormat::create($validatedData);
        return response()->json($format, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($code)
    {
        $format = DeliveryOrderFormat::where('code', $code)->firstOrFail();
        return response()->json($format);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $code)
    {
        $format = DeliveryOrderFormat::where('code', $code)->firstOrFail();

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'printer' => 'nullable|string|max:255',
        ]);

        $format->update($validatedData);
        return response()->json($format);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($code)
    {
        $format = DeliveryOrderFormat::where('code', $code)->firstOrFail();
        $format->delete();
        return response()->json(null, 204);
    }

    /**
     * Get delivery order formats for JSON response (for modal).
     */
    public function getFormatsJson(Request $request)
    {
        $query = DeliveryOrderFormat::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', '%' . $search . '%')
                  ->orWhere('name', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('sort_by')) {
            $sortBy = $request->input('sort_by');
            $query->orderBy($sortBy);
        } else {
            $query->orderBy('code'); // Default sort by code
        }

        $formats = $query->get();
        
        // Log the number of formats being returned for debugging
        Log::info('Returning ' . $formats->count() . ' delivery order formats');
        
        return response()->json($formats);
    }
} 