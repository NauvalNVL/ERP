<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\CustomerSalesType;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CustomerSalesTypeController extends Controller
{
    /**
     * Display the customer sales type page
     */
    public function index()
    {
        // Load base customers from legacy CUSTOMER master table
        // Map columns to the structure expected by the Vue page
        $customers = Customer::select([
                'CODE as customer_code',
                'NAME as customer_name',
                'CUST_TYPE as sales_type',
            ])
            ->orderBy('NAME')
            ->get();
        $salesTypes = CustomerSalesType::orderBy('customer_name')->get();

        return Inertia::render('sales-management/system-requirement/customer-account/customer-sales-type', [
            'customers' => $customers,
            'salesTypes' => $salesTypes
        ]);
    }

    /**
     * Display the view and print page for customer sales types
     */
    public function viewAndPrint()
    {
        $salesTypes = CustomerSalesType::orderBy('customer_name')->get();

        return Inertia::render('sales-management/system-requirement/customer-account/view-and-print-customer-sales-type', [
            'salesTypes' => $salesTypes
        ]);
    }

    /**
     * Get all customer sales types for API
     */
    public function apiIndex()
    {
        $salesTypes = CustomerSalesType::orderBy('customer_name')->get();
        return response()->json($salesTypes);
    }

    /**
     * Store a new customer sales type
     */
    public function apiStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_code' => 'required|string|max:20',
            'customer_name' => 'required|string|max:100',
            'sales_type' => 'required|string|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $userId = Auth::id() ?? 1; // Get authenticated user ID or default to 1

        $salesType = CustomerSalesType::create([
            'customer_code' => $request->customer_code,
            'customer_name' => $request->customer_name,
            'sales_type' => $request->sales_type,
            'created_by' => $userId,
            'updated_by' => $userId,
        ]);

        return response()->json($salesType);
    }

    /**
     * Update a customer sales type
     */
    public function apiUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'customer_code' => 'required|string|max:20',
            'customer_name' => 'required|string|max:100',
            'sales_type' => 'required|string|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $userId = Auth::id() ?? 1; // Get authenticated user ID or default to 1

        $salesType = CustomerSalesType::findOrFail($id);
        $salesType->update([
            'customer_code' => $request->customer_code,
            'customer_name' => $request->customer_name,
            'sales_type' => $request->sales_type,
            'updated_by' => $userId,
        ]);

        return response()->json($salesType);
    }

    /**
     * Delete a customer sales type
     */
    public function apiDestroy($id)
    {
        $salesType = CustomerSalesType::findOrFail($id);
        $salesType->delete();

        return response()->json(['message' => 'Customer sales type deleted successfully']);
    }
}
