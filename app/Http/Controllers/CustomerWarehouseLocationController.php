<?php

namespace App\Http\Controllers;

use App\Models\CustomerWarehouseLocation;
use App\Models\UpdateCustomerAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerWarehouseLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CustomerWarehouseLocation::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('customer_code', 'like', '%' . $search . '%')
                  ->orWhere('customer_name', 'like', '%' . $search . '%');
        }

        if ($request->has('sort_by')) {
            $sort = $request->input('sort_by');
            $query->orderBy($sort);
        }

        if ($request->has('filter_active') && $request->input('filter_active') == 'true') {
            // Assuming 'status' column in CustomerWarehouseLocation for active/obsolete
            // Or, if linking to UpdateCustomerAccount, need to join
            // For simplicity, let's assume a 'status' on CustomerWarehouseLocation for now.
            // If not, this needs adjustment based on actual schema.
            $query->where('status', 'Active');
        }
        if ($request->has('filter_obsolete') && $request->input('filter_obsolete') == 'true') {
            $query->orWhere('status', 'Obsolete');
        }
        
        $customerWarehouseLocations = $query->paginate(10); // Adjust pagination as needed

        return response()->json([
            'success' => true,
            'data' => $customerWarehouseLocations->items(),
            'pagination' => [
                'total' => $customerWarehouseLocations->total(),
                'per_page' => $customerWarehouseLocations->perPage(),
                'current_page' => $customerWarehouseLocations->currentPage(),
                'last_page' => $customerWarehouseLocations->lastPage(),
                'from' => $customerWarehouseLocations->firstItem(),
                'to' => $customerWarehouseLocations->lastItem(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_code' => 'required|string|max:255|unique:customer_warehouse_locations,customer_code',
            'customer_name' => 'required|string|max:255',
            'lock_customer_location' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $customerWarehouseLocation = CustomerWarehouseLocation::create($request->all());
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Customer Warehouse Location created successfully.',
                'data' => $customerWarehouseLocation
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create Customer Warehouse Location: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $customer_code)
    {
        $customerWarehouseLocation = CustomerWarehouseLocation::where('customer_code', $customer_code)->first();
        if (!$customerWarehouseLocation) {
            return response()->json(['message' => 'Customer Warehouse Location not found.'], 404);
        }
        return response()->json(['success' => true, 'data' => $customerWarehouseLocation]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $customer_code)
    {
        $customerWarehouseLocation = CustomerWarehouseLocation::where('customer_code', $customer_code)->first();

        if (!$customerWarehouseLocation) {
            return response()->json(['message' => 'Customer Warehouse Location not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'lock_customer_location' => 'boolean',
            // customer_code should not be changeable once created, or handle unique constraint carefully
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $customerWarehouseLocation->update($request->all());
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Customer Warehouse Location updated successfully.',
                'data' => $customerWarehouseLocation
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update Customer Warehouse Location: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $customer_code)
    {
        $customerWarehouseLocation = CustomerWarehouseLocation::where('customer_code', $customer_code)->first();

        if (!$customerWarehouseLocation) {
            return response()->json(['message' => 'Customer Warehouse Location not found.'], 404);
        }

        DB::beginTransaction();
        try {
            $customerWarehouseLocation->delete();
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Customer Warehouse Location deleted successfully.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete Customer Warehouse Location: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Check if a customer code exists in CustomerWarehouseLocation and get its name from UpdateCustomerAccount.
     */
    public function check(string $customer_code)
    {
        $existsInWarehouseLocation = CustomerWarehouseLocation::where('customer_code', $customer_code)->exists();
        $customerAccount = UpdateCustomerAccount::where('customer_code', $customer_code)->first();
        
        Log::debug('Customer check:', [
            'customer_code' => $customer_code,
            'exists_in_warehouse' => $existsInWarehouseLocation,
            'customer_account_found' => $customerAccount ? true : false,
            'customer_name' => $customerAccount ? $customerAccount->customer_name : null
        ]);
        
        return response()->json([
            'exists' => $existsInWarehouseLocation,
            'customer_name' => $customerAccount ? $customerAccount->customer_name : '',
        ]);
    }
} 