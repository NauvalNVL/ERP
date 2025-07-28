<?php

namespace App\Http\Controllers;

use App\Models\CustomerWarehouseRequirement;
use App\Models\UpdateCustomerAccount;
use App\Models\DeliveryOrderFormat;
use App\Models\WarehouseLocation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerWarehouseRequirementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('warehouse-management/FinishedGoods/SetupMaintenance/define-customer-warehouse-requirement');
    }

    /**
     * Get all customer warehouse requirements.
     */
    public function getAllRequirements()
    {
        $requirements = CustomerWarehouseRequirement::with(['customer', 'deliveryOrderFormat'])->get();
        return response()->json($requirements);
    }

    /**
     * Get customer warehouse requirement by customer code.
     */
    public function getByCustomerCode($customerCode)
    {
        $requirement = CustomerWarehouseRequirement::where('customer_code', $customerCode)->first();
        
        if (!$requirement) {
            return response()->json(['message' => 'Customer warehouse requirement not found'], 404);
        }
        
        return response()->json($requirement);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_code' => 'required|string|exists:update_customer_accounts,customer_code',
            'customer_name' => 'required|string',
            'default_warehouse_normal' => 'nullable|string',
            'default_warehouse_excess' => 'nullable|string',
            'default_warehouse_transit' => 'nullable|string',
            'delivery_order_format' => 'nullable|string',
            'bar_code_sticker' => 'nullable|string',
            'bundle_format' => 'nullable|string',
            'destination_change' => 'boolean',
            'multi_line_quantity' => 'boolean',
            'product_group_tie_up' => 'boolean',
            'unapplied_fg_goods' => 'boolean',
            'amend_do_un_qty' => 'boolean',
            'closed_sales_order_delivery' => 'nullable|string',
            'completed_sales_order_delivery' => 'nullable|string',
            'outstanding_partial_delivery' => 'nullable|string',
            'allow_qty' => 'integer',
            'allow_type' => 'nullable|string',
            'less_from_invoice' => 'nullable|string',
            'default_copies' => 'integer',
        ]);

        $requirement = CustomerWarehouseRequirement::create($validated);
        
        return response()->json($requirement, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $customerCode)
    {
        $requirement = CustomerWarehouseRequirement::where('customer_code', $customerCode)->first();
        
        if (!$requirement) {
            return response()->json(['message' => 'Customer warehouse requirement not found'], 404);
        }
        
        $validated = $request->validate([
            'customer_name' => 'sometimes|string',
            'default_warehouse_normal' => 'nullable|string',
            'default_warehouse_excess' => 'nullable|string',
            'default_warehouse_transit' => 'nullable|string',
            'delivery_order_format' => 'nullable|string',
            'bar_code_sticker' => 'nullable|string',
            'bundle_format' => 'nullable|string',
            'destination_change' => 'boolean',
            'multi_line_quantity' => 'boolean',
            'product_group_tie_up' => 'boolean',
            'unapplied_fg_goods' => 'boolean',
            'amend_do_un_qty' => 'boolean',
            'closed_sales_order_delivery' => 'nullable|string',
            'completed_sales_order_delivery' => 'nullable|string',
            'outstanding_partial_delivery' => 'nullable|string',
            'allow_qty' => 'integer',
            'allow_type' => 'nullable|string',
            'less_from_invoice' => 'nullable|string',
            'default_copies' => 'integer',
        ]);

        $requirement->update($validated);
        
        return response()->json($requirement);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($customerCode)
    {
        $requirement = CustomerWarehouseRequirement::where('customer_code', $customerCode)->first();
        
        if (!$requirement) {
            return response()->json(['message' => 'Customer warehouse requirement not found'], 404);
        }
        
        $requirement->delete();
        
        return response()->json(['message' => 'Customer warehouse requirement deleted successfully']);
    }

    /**
     * Get all customers.
     */
    public function getCustomers()
    {
        $customers = UpdateCustomerAccount::where('status', 'Active')->get();
        return response()->json($customers);
    }

    /**
     * Get all warehouse locations.
     */
    public function getWarehouseLocations()
    {
        $locations = WarehouseLocation::all();
        return response()->json($locations);
    }

    /**
     * Get all delivery order formats.
     */
    public function getDeliveryOrderFormats()
    {
        $formats = DeliveryOrderFormat::all();
        return response()->json($formats);
    }
} 