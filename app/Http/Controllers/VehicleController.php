<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleClass;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;

class VehicleController extends Controller
{
    /**
     * Display the vehicle management page
     */
    public function index()
    {
        return Inertia::render('warehouse-management/DeliveryOrder/Setup/Vehicle');
    }

    /**
     * Display the view and print page
     */
    public function viewPrint()
    {
        return Inertia::render('warehouse-management/DeliveryOrder/Setup/ViewPrintVehicle');
    }

    /**
     * Get all vehicles for API
     */
    public function apiIndex(Request $request)
    {
        $query = Vehicle::with('vehicleClass');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('VEHICLE_NO', 'like', "%{$search}%")
                  ->orWhere('VEHICLE_CLASS', 'like', "%{$search}%")
                  ->orWhere('VEHICLE_COMPANY', 'like', "%{$search}%")
                  ->orWhere('DRIVER_NAME', 'like', "%{$search}%")
                  ->orWhere('DRIVER_CODE', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status')) {
            $status = trim((string) $request->status);
            if ($status !== '' && strcasecmp($status, 'all') !== 0 && strcasecmp($status, 'all status') !== 0) {
                $query->where('VEHICLE_STATUS', $status);
            }
        }

        // Filter by company
        if ($request->has('company')) {
            $company = trim((string) $request->company);
            if ($company !== '' && strcasecmp($company, 'all') !== 0 && strcasecmp($company, 'all companies') !== 0) {
                $query->where('VEHICLE_COMPANY', $company);
            }
        }

        // Support both plain and paginated responses; always include a consistent shape
        if ($request->boolean('plain')) {
            $rows = $query->orderBy('VEHICLE_NO')->get();
            return response()->json([
                'success' => true,
                'rows' => $rows,
                'pagination' => [
                    'current_page' => 1,
                    'last_page' => 1,
                    'from' => $rows->count() > 0 ? 1 : 0,
                    'to' => $rows->count(),
                    'total' => $rows->count(),
                ],
                'vehicle_classes' => VehicleClass::orderBy('VEHICLE_CLASS_CODE')->get(),
                'companies' => Vehicle::select('VEHICLE_COMPANY')->distinct()->orderBy('VEHICLE_COMPANY')->pluck('VEHICLE_COMPANY')->values(),
            ]);
        }

        $paginator = $query->orderBy('VEHICLE_NO')->paginate(20);
        $rows = $paginator->items();

        return response()->json([
            'success' => true,
            'data' => [
                'data' => $rows,
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'from' => $paginator->firstItem() ?? 0,
                'to' => $paginator->lastItem() ?? 0,
                'total' => $paginator->total(),
            ],
            'rows' => $rows,
            'vehicle_classes' => VehicleClass::orderBy('VEHICLE_CLASS_CODE')->get(),
            'companies' => Vehicle::select('VEHICLE_COMPANY')->distinct()->orderBy('VEHICLE_COMPANY')->pluck('VEHICLE_COMPANY')->values(),
        ]);
    }

    /**
     * Store a new vehicle
     */
    public function apiStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'VEHICLE_NO' => 'required|string|max:50|unique:vehicle,VEHICLE_NO',
            'VEHICLE_STATUS' => 'required|string|in:A,O',
            'VEHICLE_CLASS' => 'required|string|max:50',
            'VEHICLE_COMPANY' => 'required|string|max:50',
            'DRIVER_CODE' => 'required|string|max:50',
            'DRIVER_NAME' => 'required|string|max:50',
            'DRIVER_ID' => 'required|string|max:50',
            'DRIVER_PHONE' => 'required|string|max:50',
            'NOTE' => 'nullable|string|max:50',
            'VEHICLE_DESCRIPTION' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $validated = $validator->validated();
            // Ensure required DB columns are present
            $nextNo = (int) (Vehicle::max('NO_') ?? 0) + 1;
            $data = array_merge($validated, [
                'NO_' => str_pad((string) $nextNo, 3, '0', STR_PAD_LEFT),
                'VEHICLE_DESCRIPTION' => $request->input('VEHICLE_DESCRIPTION', $validated['VEHICLE_NO']),
                'COMPANY' => $validated['VEHICLE_COMPANY'],
                'STATUS' => $validated['VEHICLE_STATUS'],
                'NOTE' => $request->input('NOTE', ''),
            ]);

            $vehicle = Vehicle::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Vehicle created successfully',
                'data' => $vehicle->load('vehicleClass')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating vehicle: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show a specific vehicle
     */
    public function show(Vehicle $vehicle)
    {
        return response()->json([
            'success' => true,
            'data' => $vehicle->load('vehicleClass')
        ]);
    }

    /**
     * Update a vehicle
     */
    public function apiUpdate(Request $request, Vehicle $vehicle)
    {
        $validator = Validator::make($request->all(), [
            'VEHICLE_NO' => 'required|string|max:50|unique:vehicle,VEHICLE_NO,' . $vehicle->id,
            'VEHICLE_STATUS' => 'required|string|in:A,O',
            'VEHICLE_CLASS' => 'required|string|max:50',
            'VEHICLE_COMPANY' => 'required|string|max:50',
            'DRIVER_CODE' => 'required|string|max:50',
            'DRIVER_NAME' => 'required|string|max:50',
            'DRIVER_ID' => 'required|string|max:50',
            'DRIVER_PHONE' => 'required|string|max:50',
            'NOTE' => 'nullable|string|max:50',
            'VEHICLE_DESCRIPTION' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $validated = $validator->validated();
            // Keep invariants and required DB columns
            $data = array_merge($validated, [
                'VEHICLE_DESCRIPTION' => $request->input('VEHICLE_DESCRIPTION', $validated['VEHICLE_NO']),
                'COMPANY' => $validated['VEHICLE_COMPANY'],
                'STATUS' => $validated['VEHICLE_STATUS'],
                'NOTE' => $request->input('NOTE', $vehicle->NOTE ?? ''),
            ]);

            $vehicle->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Vehicle updated successfully',
                'data' => $vehicle->load('vehicleClass')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating vehicle: ' . $e->getMessage()
            ], 500);
        }
    }

    public function apiUpdateStatus(Request $request, Vehicle $vehicle)
    {
        $validator = Validator::make($request->all(), [
            'VEHICLE_STATUS' => 'required|string|in:A,O',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $status = $validator->validated()['VEHICLE_STATUS'];

            $vehicle->update([
                'VEHICLE_STATUS' => $status,
                'STATUS' => $status,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Vehicle status updated successfully',
                'data' => $vehicle->fresh()->load('vehicleClass'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating vehicle status: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a vehicle
     */
    public function apiDestroy(Vehicle $vehicle)
    {
        try {
            $vehicle->delete();

            return response()->json([
                'success' => true,
                'message' => 'Vehicle deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting vehicle: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get vehicle classes for dropdown
     */
    public function getVehicleClasses()
    {
        return response()->json([
            'success' => true,
            'data' => VehicleClass::all()
        ]);
    }

    /**
     * Get companies for dropdown
     */
    public function getCompanies()
    {
        return response()->json([
            'success' => true,
            'data' => ['KIM', 'CUSTOMER', 'MBI']
        ]);
    }

    /**
     * Get driver name by vehicle number
     */
    public function getDriverByVehicle($vehicleNo)
    {
        try {
            $vehicle = Vehicle::where('VEHICLE_NO', $vehicleNo)->first();
            
            if (!$vehicle) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vehicle not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'driver_name' => $vehicle->DRIVER_NAME,
                'driver_code' => $vehicle->DRIVER_CODE,
                'vehicle_no' => $vehicle->VEHICLE_NO
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching driver: ' . $e->getMessage()
            ], 500);
        }
    }
}
