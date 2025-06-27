<?php

namespace App\Http\Controllers;

use App\Models\WarehouseLocation;
use Illuminate\Http\Request;

class WarehouseLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $warehouseLocations = WarehouseLocation::all();
        return response()->json($warehouseLocations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:warehouse_locations,code',
            'description' => 'nullable|string',
            'type' => 'required|string',
            'to_lock_delivery_order' => 'boolean',
            'to_lock_stock_out_adjustment' => 'boolean',
            'to_lock_warehouse_transfer' => 'boolean',
        ]);

        $warehouseLocation = WarehouseLocation::create($request->all());
        return response()->json($warehouseLocation, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $code)
    {
        $warehouseLocation = WarehouseLocation::where('code', $code)->firstOrFail();
        return response()->json($warehouseLocation);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $code)
    {
        $warehouseLocation = WarehouseLocation::where('code', $code)->firstOrFail();

        $request->validate([
            'description' => 'nullable|string',
            'type' => 'required|string',
            'to_lock_delivery_order' => 'boolean',
            'to_lock_stock_out_adjustment' => 'boolean',
            'to_lock_warehouse_transfer' => 'boolean',
        ]);

        $warehouseLocation->update($request->all());
        return response()->json($warehouseLocation);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $code)
    {
        $warehouseLocation = WarehouseLocation::where('code', $code)->firstOrFail();
        $warehouseLocation->delete();
        return response()->json(null, 204);
    }

    public function getWarehouseLocationsJson()
    {
        $query = request()->get('query');

        if ($query) {
            $locations = WarehouseLocation::where('code', 'like', '%' . $query . '%')
                                ->orWhere('description', 'like', '%' . $query . '%')
                                ->get();
        } else {
            $locations = WarehouseLocation::all();
        }
        
        return response()->json($locations);
    }
} 