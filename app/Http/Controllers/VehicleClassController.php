<?php

namespace App\Http\Controllers;

use App\Models\VehicleClass;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;

class VehicleClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehicleClasses = VehicleClass::orderBy('VEHICLE_CLASS_CODE')->get();
        
        return Inertia::render('warehouse-management/DeliveryOrder/Setup/VehicleClass', [
            'vehicleClasses' => $vehicleClasses
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('warehouse-management/DeliveryOrder/Setup/VehicleClass');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'VEHICLE_CLASS_CODE' => 'required|string|max:50|unique:vehicleclass,VEHICLE_CLASS_CODE',
            'DESCRIPTION' => 'required|string|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $vehicleClass = VehicleClass::create([
                'NO_' => VehicleClass::count() + 1,
                'VEHICLE_CLASS_CODE' => $request->VEHICLE_CLASS_CODE,
                'DESCRIPTION' => $request->DESCRIPTION,
                'STANDART_CLASS_CODE' => '', // Default empty value
                'VOLUME_M3' => 0.0, // Default value
                'CAPACITY_WGT_MT' => 0.0 // Default value
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Vehicle class created successfully',
                'data' => $vehicleClass
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating vehicle class: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(VehicleClass $vehicleClass)
    {
        return response()->json([
            'success' => true,
            'data' => $vehicleClass
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VehicleClass $vehicleClass)
    {
        $validator = Validator::make($request->all(), [
            'DESCRIPTION' => 'required|string|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $vehicleClass->update([
                'DESCRIPTION' => $request->DESCRIPTION
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Vehicle class updated successfully',
                'data' => $vehicleClass
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating vehicle class: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleClass $vehicleClass)
    {
        try {
            $vehicleClass->delete();

            return response()->json([
                'success' => true,
                'message' => 'Vehicle class deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting vehicle class: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API method to get all vehicle classes
     */
    public function apiIndex(Request $request)
    {
        $query = VehicleClass::query();

        if ($request->has('search') && $request->search) {
            $query->search($request->search);
        }

        $vehicleClasses = $query->orderBy('VEHICLE_CLASS_CODE')->get();

        return response()->json([
            'success' => true,
            'data' => $vehicleClasses
        ]);
    }

    /**
     * API method to store vehicle class
     */
    public function apiStore(Request $request)
    {
        return $this->store($request);
    }

    /**
     * API method to update vehicle class
     */
    public function apiUpdate(Request $request, VehicleClass $vehicleClass)
    {
        return $this->update($request, $vehicleClass);
    }

    /**
     * API method to delete vehicle class
     */
    public function apiDestroy(VehicleClass $vehicleClass)
    {
        return $this->destroy($vehicleClass);
    }

    /**
     * View and print vehicle classes
     */
    public function viewPrint()
    {
        $vehicleClasses = VehicleClass::orderBy('VEHICLE_CLASS_CODE')->get();
        
        return Inertia::render('warehouse-management/DeliveryOrder/Setup/ViewPrintVehicleClass', [
            'vehicleClasses' => $vehicleClasses
        ]);
    }
}
