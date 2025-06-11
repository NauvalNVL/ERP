<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApproveMC;
use App\Models\UpdateCustomerAccount;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApproveMcController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Fetch master cards data with proper error handling
            $masterCards = ApproveMC::orderBy('mc_seq')->get();
            $customers = UpdateCustomerAccount::orderBy('customer_name')->get();
            
            return Inertia::render('sales-management/system-requirement/master-card/approve-mc', [
                'masterCards' => $masterCards,
                'customers' => $customers
            ]);
        } catch (\Exception $e) {
            return Inertia::render('sales-management/system-requirement/master-card/approve-mc', [
                'error' => 'Failed to load master cards: ' . $e->getMessage(),
                'masterCards' => [],
                'customers' => []
            ]);
        }
    }

    /**
     * Get all master cards for API
     */
    public function apiIndex()
    {
        try {
            $masterCards = ApproveMC::orderBy('mc_seq')->get();
            return response()->json($masterCards);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load master cards: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info('Creating new master card - debug details', [
            'raw_data' => $request->getContent(),
            'all_data' => $request->all(),
            'request_status' => $request->status,
            'headers' => collect($request->headers->all())->map(fn($item) => $item[0])->toArray(),
            'has_csrf' => $request->header('X-CSRF-TOKEN') ? 'yes' : 'no',
        ]);
        
        try {
            // Enhanced validation with better error messages
            $validator = Validator::make($request->all(), [
                'mc_seq' => 'required|unique:approve_mcs,mc_seq',
                'mc_model' => 'required',
                'customer_code' => 'required',
                'customer_name' => 'required',
                'status' => 'required|in:pending,active,obsolete'
            ], [
                'mc_seq.required' => 'The MC Sequence field is required.',
                'mc_seq.unique' => 'This MC Sequence already exists.',
                'mc_model.required' => 'The MC Model field is required.',
                'customer_code.required' => 'The Customer Code field is required.',
                'customer_name.required' => 'The Customer Name field is required.',
                'status.required' => 'The Status field is required.',
                'status.in' => 'The selected Status is invalid. Choose from: pending, active, or obsolete.'
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed for new master card', ['errors' => $validator->errors()->toArray()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Create new master card with request data
            $approveMc = new ApproveMC;
            $approveMc->mc_seq = $request->mc_seq;
            $approveMc->mc_model = $request->mc_model;
            $approveMc->customer_code = $request->customer_code;
            $approveMc->customer_name = $request->customer_name;
            $approveMc->status = $request->status;
            
            // Set additional fields based on status
            if ($request->status === 'active') {
                $approveMc->approved_by = Auth::user() ? Auth::user()->user_id : 'SYSTEM';
                $approveMc->approved_date = now();
            } elseif ($request->status === 'obsolete') {
                $approveMc->rejected_by = Auth::user() ? Auth::user()->user_id : 'SYSTEM';
                $approveMc->rejected_date = now();
                $approveMc->rejection_reason = $request->rejection_reason ?? 'Marked obsolete during creation';
            }
            
            $approveMc->save();
            
            // Make sure we have the full record with ID
            $approveMc = ApproveMC::find($approveMc->id);
            
            Log::info('Master card created successfully', [
                'id' => $approveMc->id, 
                'status' => $approveMc->status,
                'mc_seq' => $approveMc->mc_seq,
                'customer' => $approveMc->customer_name
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Master card created successfully',
                'data' => $approveMc
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create master card', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create master card: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ApproveMC $approveMC)
    {
        return response()->json($approveMC);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ApproveMC $approveMC)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        Log::info('Updating master card', [
            'id' => $id, 
            'data' => $request->all(),
            'request_status' => $request->status
        ]);
        
        $approveMc = ApproveMC::findOrFail($id);
        
        Log::info('Current master card status', [
            'id' => $id,
            'current_status' => $approveMc->status
        ]);
        
        $validator = Validator::make($request->all(), [
            'mc_seq' => 'required|unique:approve_mcs,mc_seq,' . $id,
            'mc_model' => 'required',
            'customer_code' => 'required',
            'customer_name' => 'required',
            'status' => 'required|in:pending,active,obsolete'
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Handle the status change separately if needed
            $oldStatus = $approveMc->status;
            $newStatus = $request->status;
            $statusChanged = $newStatus !== $oldStatus;
            
            Log::info('Status change analysis', [
                'previous' => $oldStatus,
                'new' => $newStatus,
                'changed' => $statusChanged
            ]);
            
            // Update basic fields first
            $approveMc->mc_seq = $request->mc_seq;
            $approveMc->mc_model = $request->mc_model;
            $approveMc->customer_code = $request->customer_code;
            $approveMc->customer_name = $request->customer_name;
            
            // Handle status specifically
            $approveMc->status = $newStatus;
            
            // If status is changing, update relevant fields
            if ($statusChanged) {
                if ($newStatus === 'active') {
                    $approveMc->approved_by = Auth::user() ? Auth::user()->user_id : 'SYSTEM';
                    $approveMc->approved_date = now();
                    // Clear rejection fields if previously rejected
                    $approveMc->rejected_by = null;
                    $approveMc->rejected_date = null;
                    $approveMc->rejection_reason = null;
                    
                    Log::info('Master card activated', ['id' => $id, 'by' => $approveMc->approved_by]);
                } 
                elseif ($newStatus === 'obsolete') {
                    // In case of direct obsolete status change without going through reject API
                    if (!$request->has('rejection_reason')) {
                        $approveMc->rejection_reason = 'Set to obsolete during edit';
                    }
                    $approveMc->rejected_by = Auth::user() ? Auth::user()->user_id : 'SYSTEM';
                    $approveMc->rejected_date = now();
                    // Clear approval fields if previously approved
                    $approveMc->approved_by = null;
                    $approveMc->approved_date = null;
                    
                    Log::info('Master card marked obsolete', ['id' => $id, 'by' => $approveMc->rejected_by]);
                }
                elseif ($newStatus === 'pending') {
                    // Reset both approval and rejection fields
                    $approveMc->approved_by = null;
                    $approveMc->approved_date = null; 
                    $approveMc->rejected_by = null;
                    $approveMc->rejected_date = null;
                    $approveMc->rejection_reason = null;
                    
                    Log::info('Master card reset to pending', ['id' => $id]);
                }
            }
            
            $approveMc->save();
            
            Log::info('Master card updated successfully', [
                'id' => $id,
                'new_status' => $approveMc->status
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Master card updated successfully',
                'data' => $approveMc
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update master card', ['id' => $id, 'error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update master card: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $approveMc = ApproveMC::findOrFail($id);
        $approveMc->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Master card deleted successfully'
        ]);
    }
    
    /**
     * Approve a master card
     */
    public function approve($id)
    {
        try {
            $approveMc = ApproveMC::findOrFail($id);
            
            // Set approval fields
            $approveMc->status = 'active';
            $approveMc->approved_by = Auth::user() ? Auth::user()->user_id : 'SYSTEM';
            $approveMc->approved_date = now();
            $approveMc->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Master card approved successfully',
                'approved_by' => $approveMc->approved_by,
                'approved_date' => $approveMc->approved_date,
                'data' => $approveMc
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve master card: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Reject a master card
     */
    public function reject(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'rejection_reason' => 'required|string|min:3'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: A rejection reason is required',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            $approveMc = ApproveMC::findOrFail($id);
            
            // Set rejection fields
            $approveMc->status = 'obsolete';
            $approveMc->rejected_by = Auth::user() ? Auth::user()->user_id : 'SYSTEM';
            $approveMc->rejected_date = now();
            $approveMc->rejection_reason = $request->rejection_reason;
            $approveMc->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Master card rejected successfully',
                'rejected_by' => $approveMc->rejected_by,
                'rejected_date' => $approveMc->rejected_date,
                'data' => $approveMc
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject master card: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get master cards by customer
     */
    public function getByCustomer($customerId)
    {
        try {
            $masterCards = ApproveMC::where('customer_code', $customerId)
                ->orderBy('mc_seq')
                ->get();
                
            return response()->json([
                'success' => true,
                'data' => $masterCards
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch master cards: ' . $e->getMessage()
            ], 500);
        }
    }
}
