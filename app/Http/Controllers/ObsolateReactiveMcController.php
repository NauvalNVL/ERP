<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ObsolateReactiveMC;
use App\Models\UpdateCustomerAccount;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ObsolateReactiveMcController extends Controller
{
    /**
     * Display the Obsolate Reactive MC page
     */
    public function index()
    {
        $masterCards = ObsolateReactiveMC::orderBy('mc_seq')->get();
        $customers = UpdateCustomerAccount::orderBy('customer_name')->get();
        
        return Inertia::render('sales-management/system-requirement/master-card/obsolete-reactive-mc', [
            'masterCards' => $masterCards,
            'customers' => $customers
        ]);
    }

    /**
     * Get all master cards for API
     */
    public function apiIndex()
    {
        $masterCards = ObsolateReactiveMC::orderBy('mc_seq')->get();
        return response()->json($masterCards);
    }

    /**
     * Store a new master card
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mc_seq' => 'required|string|max:50|unique:obsolate_reactive_mcs',
            'mc_model' => 'required|string|max:100',
            'customer_id' => 'required|integer',
            'customer_name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $userId = Auth::id() ?? 1; // Get authenticated user ID or default to 1

        $masterCard = ObsolateReactiveMC::create([
            'mc_seq' => $request->mc_seq,
            'mc_model' => $request->mc_model,
            'customer_id' => $request->customer_id,
            'customer_name' => $request->customer_name,
            'description' => $request->description,
            'status' => 'active', // Default status
            'created_by' => $userId,
            'updated_by' => $userId,
        ]);

        return response()->json($masterCard);
    }

    /**
     * Update a master card
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'mc_seq' => 'required|string|max:50|unique:obsolate_reactive_mcs,mc_seq,'.$id,
            'mc_model' => 'required|string|max:100',
            'customer_id' => 'required|integer',
            'customer_name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $userId = Auth::id() ?? 1; // Get authenticated user ID or default to 1
        
        $masterCard = ObsolateReactiveMC::findOrFail($id);
        $masterCard->update([
            'mc_seq' => $request->mc_seq,
            'mc_model' => $request->mc_model,
            'customer_id' => $request->customer_id,
            'customer_name' => $request->customer_name,
            'description' => $request->description,
            'updated_by' => $userId,
        ]);

        return response()->json($masterCard);
    }

    /**
     * Obsolate a master card
     */
    public function obsolate(Request $request, $id)
    {
        $userId = Auth::id() ?? 1; // Get authenticated user ID or default to 1
        
        $masterCard = ObsolateReactiveMC::findOrFail($id);
        $masterCard->status = 'obsolete';
        $masterCard->obsolate_date = now();
        $masterCard->obsolate_by = $userId;
        $masterCard->obsolate_reason = $request->reason;
        $masterCard->save();

        return response()->json($masterCard);
    }

    /**
     * Reactive a master card
     */
    public function reactive(Request $request, $id)
    {
        $userId = Auth::id() ?? 1; // Get authenticated user ID or default to 1
        
        $masterCard = ObsolateReactiveMC::findOrFail($id);
        $masterCard->status = 'active';
        $masterCard->reactive_date = now();
        $masterCard->reactive_by = $userId;
        $masterCard->reactive_reason = $request->reason;
        $masterCard->save();

        return response()->json($masterCard);
    }

    /**
     * Bulk obsolete master cards.
     */
    public function bulkObsolete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:obsolate_reactive_mcs,id',
            'reason' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $userId = Auth::id() ?? 1;
        $ids = $request->input('ids');
        $reason = $request->input('reason');

        ObsolateReactiveMC::whereIn('id', $ids)->update([
            'status' => 'obsolete',
            'obsolate_date' => now(),
            'obsolate_by' => $userId,
            'obsolate_reason' => $reason,
        ]);

        $updatedMasterCards = ObsolateReactiveMC::whereIn('id', $ids)->get();

        return response()->json([
            'message' => 'Master cards have been obsoleted successfully.',
            'count' => count($updatedMasterCards),
            'data' => $updatedMasterCards,
        ]);
    }

    /**
     * Bulk reactivate master cards.
     */
    public function bulkReactivate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:obsolate_reactive_mcs,id',
            'reason' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $userId = Auth::id() ?? 1;
        $ids = $request->input('ids');
        $reason = $request->input('reason');

        ObsolateReactiveMC::whereIn('id', $ids)->update([
            'status' => 'active',
            'reactive_date' => now(),
            'reactive_by' => $userId,
            'reactive_reason' => $reason,
        ]);

        $updatedMasterCards = ObsolateReactiveMC::whereIn('id', $ids)->get();

        return response()->json([
            'message' => 'Master cards have been reactivated successfully.',
            'count' => count($updatedMasterCards),
            'data' => $updatedMasterCards,
        ]);
    }

    /**
     * Get master cards by customer
     */
    public function getByCustomer($customerId)
    {
        $masterCards = ObsolateReactiveMC::where('customer_id', $customerId)->get();
        return response()->json($masterCards);
    }
    
    /**
     * Display the View and Print Master Cards page
     */
    public function viewAndPrint()
    {
        return Inertia::render('sales-management/system-requirement/master-card/view-and-print-MC');
    }
    
    /**
     * Display the View and Print MC Maintenance Log page
     */
    public function viewAndPrintMcMaintenanceLog()
    {
        return Inertia::render('sales-management/system-requirement/master-card/view-and-print-mc-maintenance-log');
    }
}
