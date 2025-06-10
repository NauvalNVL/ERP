<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApproveMC;
use App\Models\UpdateCustomerAccount;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RealeseApproveMcController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Fetch only active master cards (approved ones ready to be released)
            $masterCards = ApproveMC::where('status', 'active')
                ->orderBy('mc_seq')
                ->get();
                
            $customers = UpdateCustomerAccount::orderBy('customer_name')->get();
            
            return Inertia::render('sales-management/system-requirement/master-card/realese-approve-mc', [
                'masterCards' => $masterCards,
                'customers' => $customers
            ]);
        } catch (\Exception $e) {
            return Inertia::render('sales-management/system-requirement/master-card/realese-approve-mc', [
                'error' => 'Failed to load master cards: ' . $e->getMessage(),
                'masterCards' => [],
                'customers' => []
            ]);
        }
    }

    /**
     * Get all releasable master cards for API
     */
    public function apiIndex()
    {
        try {
            $masterCards = ApproveMC::where('status', 'active')
                ->orderBy('mc_seq')
                ->get();
                
            return response()->json([
                'success' => true,
                'data' => $masterCards
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load master cards: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created or update an existing master card.
     * Note: This is similar to ApproveMcController's store method but adapted for the release page.
     */
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validator = Validator::make($request->all(), [
                'mc_seq' => 'required|string|max:50',
                'mc_model' => 'required|string|max:100',
                'customer_code' => 'required|string|max:50',
                'customer_name' => 'required|string|max:255',
                'status' => 'required|string|in:pending,active,obsolete',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Create a new master card
            $masterCard = new ApproveMC();
            $masterCard->mc_seq = $request->mc_seq;
            $masterCard->mc_model = $request->mc_model;
            $masterCard->customer_code = $request->customer_code;
            $masterCard->customer_name = $request->customer_name;
            $masterCard->status = $request->status;
            
            // If we're directly approving from the form (status set to active)
            if ($request->status === 'active') {
                $masterCard->approved_by = Auth::user() ? Auth::user()->user_id : 'SYSTEM';
                $masterCard->approved_date = now();
            }
            
            $masterCard->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Master card created successfully',
                'data' => $masterCard
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to create master card', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create master card: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Release a master card - marks it as released for production/use
     */
    public function release(Request $request, $id)
    {
        try {
            $approveMc = ApproveMC::findOrFail($id);
            
            // Verify that the master card is in the correct state to be released
            if ($approveMc->status !== 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only approved master cards can be released'
                ], 400);
            }
            
            // Update the released_by and released_date fields 
            // Note: You'll need to add these columns to the approve_mcs table
            $approveMc->released_by = Auth::user() ? Auth::user()->user_id : 'SYSTEM';
            $approveMc->released_date = now();
            $approveMc->release_notes = $request->release_notes ?? 'Released for production';
            $approveMc->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Master card released successfully',
                'released_by' => $approveMc->released_by,
                'released_date' => $approveMc->released_date,
                'data' => $approveMc
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to release master card', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to release master card: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Unreleased a previously released master card
     */
    public function unreleased(Request $request, $id)
    {
        try {
            $approveMc = ApproveMC::findOrFail($id);
            
            // Only cards that have been released can be unreleased
            if (!$approveMc->released_date) {
                return response()->json([
                    'success' => false,
                    'message' => 'This master card has not been released yet'
                ], 400);
            }
            
            // Reset the released fields
            $approveMc->released_by = null;
            $approveMc->released_date = null;
            $approveMc->release_notes = null;
            $approveMc->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Master card un-released successfully',
                'data' => $approveMc
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to un-release master card', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to un-release master card: ' . $e->getMessage()
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
                ->where('status', 'active')
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
