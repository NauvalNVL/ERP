<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApproveMC;
use App\Models\MasterCard;
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
            Log::info('Approve MC Request', [
                'id' => $id,
                'user_id' => Auth::check() ? (Auth::user()->userID ?? Auth::user()->user_id ?? Auth::user()->name ?? 'unknown') : null
            ]);
            
            // Try resolving by primary key id first
            $approveMc = ApproveMC::find($id);
            
            // If not found, fall back to mc_seq match
            if (!$approveMc) {
                Log::info('Not found by ID, trying mc_seq', ['id' => $id]);
                $approveMc = ApproveMC::where('mc_seq', $id)->first();
            }

            // If still not found, try to seed from MasterCard and approve directly
            if (!$approveMc) {
                Log::info('Not found in ApproveMC, trying MasterCard', ['id' => $id]);
                // Try using MCS_Num column instead of mc_seq
                $master = MasterCard::where('MCS_Num', $id)->first();
                if (!$master) {
                    Log::error('MC not found anywhere', ['id' => $id]);
                    return response()->json([
                        'success' => false,
                        'message' => 'No ApproveMC or MasterCard record found for identifier: ' . $id,
                    ], 404);
                }
                
                // Validate required fields before creating ApproveMC
                $mcSeq = $master->MCS_Num ?? $id;
                $mcModel = $master->MODEL ?? 'N/A';
                $customerCode = $master->AC_NUM ?? 'N/A';
                $customerName = $master->AC_NAME ?? ($master->AC_NUM ?? 'N/A');
                
                if (empty($mcModel) || $mcModel === '') {
                    $mcModel = 'Model-' . $mcSeq; // Generate a model name if empty
                }
                
                Log::info('Creating ApproveMC from MasterCard', [
                    'mc_seq' => $mcSeq,
                    'mc_model' => $mcModel,
                    'customer_code' => $customerCode,
                    'customer_name' => $customerName
                ]);
                
                $approveMc = new ApproveMC();
                $approveMc->mc_seq = $mcSeq;
                $approveMc->mc_model = $mcModel;
                $approveMc->customer_code = $customerCode;
                $approveMc->customer_name = $customerName;
                $approveMc->status = 'pending';
                $approveMc->save();
                Log::info('Created new ApproveMC from MasterCard', ['mc_seq' => $mcSeq]);
            }

            // Get authenticated user ID
            $userId = 'SYSTEM';
            if (Auth::check()) {
                $user = Auth::user();
                if (isset($user->userID)) {
                    $userId = $user->userID;
                } elseif (isset($user->user_id)) {
                    $userId = $user->user_id;
                } elseif (isset($user->name)) {
                    $userId = $user->name;
                } elseif (isset($user->username)) {
                    $userId = $user->username;
                }
            }

            Log::info('Approving MC', [
                'mc_id' => $approveMc->id,
                'mc_seq' => $approveMc->mc_seq,
                'approved_by' => $userId
            ]);

            // Set approval fields
            $approveMc->status = 'active';
            $approveMc->approved_by = $userId;
            $approveMc->approved_date = now();
            $approveMc->save();
            
            // IMPORTANT: Also update the MC table's mc_approval field
            try {
                $mcRecord = MasterCard::where('MCS_Num', $approveMc->mc_seq)->first();
                if ($mcRecord) {
                    // Update the MC table to reflect approval status
                    MasterCard::where('MCS_Num', $approveMc->mc_seq)
                        ->update(['mc_approval' => 'Yes']);
                    Log::info('Updated MC table approval status', ['mc_seq' => $approveMc->mc_seq]);
                } else {
                    Log::warning('MC record not found in MC table for approval sync', ['mc_seq' => $approveMc->mc_seq]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to update MC table approval status', [
                    'mc_seq' => $approveMc->mc_seq,
                    'error' => $e->getMessage()
                ]);
                // Don't fail the approval if MC table update fails
            }
            
            Log::info('MC approved successfully', [
                'mc_id' => $approveMc->id,
                'status' => $approveMc->status
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Master card approved successfully',
                'approved_by' => $approveMc->approved_by,
                'approved_date' => $approveMc->approved_date,
                'data' => $approveMc
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to approve MC', [
                'id' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve master card: ' . $e->getMessage(),
                'error_details' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ], 500);
        }
    }
    
    /**
     * Reject a master card
     */
    public function reject(Request $request, $id)
    {
        try {
            Log::info('Reject MC Request', [
                'id' => $id,
                'user_id' => Auth::check() ? (Auth::user()->userID ?? Auth::user()->user_id ?? Auth::user()->name ?? 'unknown') : null,
                'reason' => $request->rejection_reason
            ]);
            
            // Resolve ApproveMC record by id, then mc_seq, or seed from MasterCard
            $approveMc = ApproveMC::find($id);
            if (!$approveMc) {
                Log::info('Not found by ID, trying mc_seq', ['id' => $id]);
                $approveMc = ApproveMC::where('mc_seq', $id)->first();
            }
            if (!$approveMc) {
                Log::info('Not found in ApproveMC, trying MasterCard', ['id' => $id]);
                // Try using MCS_Num column instead of mc_seq
                $master = MasterCard::where('MCS_Num', $id)->first();
                if (!$master) {
                    Log::error('MC not found anywhere for rejection', ['id' => $id]);
                    return response()->json([
                        'success' => false,
                        'message' => 'No ApproveMC or MasterCard record found for identifier: ' . $id,
                    ], 404);
                }
                
                // Validate required fields before creating ApproveMC
                $mcSeq = $master->MCS_Num ?? $id;
                $mcModel = $master->MODEL ?? 'N/A';
                $customerCode = $master->AC_NUM ?? 'N/A';
                $customerName = $master->AC_NAME ?? ($master->AC_NUM ?? 'N/A');
                
                if (empty($mcModel) || $mcModel === '') {
                    $mcModel = 'Model-' . $mcSeq; // Generate a model name if empty
                }
                
                Log::info('Creating ApproveMC from MasterCard for rejection', [
                    'mc_seq' => $mcSeq,
                    'mc_model' => $mcModel,
                    'customer_code' => $customerCode,
                    'customer_name' => $customerName
                ]);
                
                $approveMc = new ApproveMC();
                $approveMc->mc_seq = $mcSeq;
                $approveMc->mc_model = $mcModel;
                $approveMc->customer_code = $customerCode;
                $approveMc->customer_name = $customerName;
                $approveMc->status = 'pending';
                $approveMc->save();
                Log::info('Created new ApproveMC from MasterCard for rejection', ['mc_seq' => $mcSeq]);
            }

            // Allow rejecting from any status except already obsolete
            if ($approveMc->status === 'obsolete') {
                Log::warning('Attempted to reject already obsolete MC', ['id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Master card is already obsolete.'
                ], 400);
            }
            
            $validator = Validator::make($request->all(), [
                'rejection_reason' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed for MC rejection', ['errors' => $validator->errors()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Rejection reason is required.',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Get authenticated user ID
            $userId = 'SYSTEM';
            if (Auth::check()) {
                $user = Auth::user();
                if (isset($user->userID)) {
                    $userId = $user->userID;
                } elseif (isset($user->user_id)) {
                    $userId = $user->user_id;
                } elseif (isset($user->name)) {
                    $userId = $user->name;
                } elseif (isset($user->username)) {
                    $userId = $user->username;
                }
            }

            Log::info('Rejecting MC', [
                'mc_id' => $approveMc->id,
                'mc_seq' => $approveMc->mc_seq,
                'rejected_by' => $userId,
                'reason' => $request->rejection_reason
            ]);

            $approveMc->status = 'obsolete';
            $approveMc->rejected_by = $userId;
            $approveMc->rejected_date = now();
            $approveMc->rejection_reason = $request->rejection_reason;
            $approveMc->save();

            Log::info('MC rejected successfully', [
                'mc_id' => $approveMc->id,
                'status' => $approveMc->status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Master card has been rejected and marked as obsolete.',
                'data' => $approveMc
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to reject MC', [
                'id' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject master card: ' . $e->getMessage(),
                'error_details' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ], 500);
        }
    }

    /**
     * Get master cards by customer
     */
    public function getByCustomer($customerId)
    {
        try {
            // Validate that the customerId exists
            if (!UpdateCustomerAccount::where('customer_code', $customerId)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => "Customer with code {$customerId} not found."
                ], 404);
            }
            
            // Fetch master cards for the given customer, ordered by a valid column
            $masterCards = ApproveMC::where('customer_code', $customerId)
                ->orderBy('mc_seq', 'asc')
                ->take(50)
                ->get();
            
            return response()->json($masterCards);
        } catch (\Exception $e) {
            Log::error("Failed to get master cards for customer {$customerId}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching master cards.'
            ], 500);
        }
    }

    public function apiIndexMcAutoWoNotReleasing()
    {
        // For demonstration, returning dummy data for M/Card for Not Auto Releasing W/Order
        $dummyMcs = [
            ['id' => 1, 'customer_code' => 'CUST001', 'mc_seq' => 'MC001', 'model' => 'Model X', 'status' => 'Active', 'ignore' => false],
            ['id' => 2, 'customer_code' => 'CUST002', 'mc_seq' => 'MC002', 'model' => 'Model Y', 'status' => 'Inactive', 'ignore' => true],
            ['id' => 3, 'customer_code' => 'CUST001', 'mc_seq' => 'MC003', 'model' => 'Model Z', 'status' => 'Active', 'ignore' => false],
            ['id' => 4, 'customer_code' => 'CUST003', 'mc_seq' => 'MC004', 'model' => 'Model A', 'status' => 'Active', 'ignore' => false],
            ['id' => 5, 'customer_code' => 'CUST002', 'mc_seq' => 'MC005', 'model' => 'Model B', 'status' => 'Inactive', 'ignore' => false],
        ];

        return response()->json($dummyMcs);
    }
}
