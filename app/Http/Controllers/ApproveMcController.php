<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApproveMC;
use App\Models\UpdateCustomerAccount;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ApproveMcController extends Controller
{
    public function index()
    {
        try {
            // Fetch master cards data
            $masterCards = ApproveMC::orderBy('mc_seq')->get();
            $customers = UpdateCustomerAccount::orderBy('customer_name')->get();
            
            Log::info('Retrieving master cards data for approval page');
            
            // Return Inertia response
            return Inertia::render('sales-management/system-requirement/master-card/approve-mc', [
                'masterCards' => $masterCards,
                'customers' => $customers
            ]);

        } catch (\Exception $e) {
            Log::error('Error retrieving master cards data: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while retrieving master card data');
        }
    }

    public function apiIndex()
    {
        try {
            $masterCards = ApproveMC::orderBy('mc_seq')->get();
            Log::info('Retrieving master cards data for API');
            return response()->json($masterCards);
        } catch (\Exception $e) {
            Log::error('Error retrieving master cards data: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve master card data'], 500);
        }
    }
    
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'mc_seq' => 'required|string|max:255|unique:approve_mcs',
                'mc_model' => 'required|string|max:255',
                'customer_code' => 'required|string|max:255',
                'customer_name' => 'required|string|max:255',
                'status' => 'required|in:pending,active,obsolete',
            ]);
            
            $masterCard = ApproveMC::create($validated);
            
            Log::info('New master card created: ' . $masterCard->id);
            return response()->json($masterCard, 201);
        } catch (\Exception $e) {
            Log::error('Error creating master card: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create master card: ' . $e->getMessage()], 500);
        }
    }
    
    public function update(Request $request, $id)
    {
        try {
            $masterCard = ApproveMC::findOrFail($id);
            
            $validated = $request->validate([
                'mc_seq' => 'required|string|max:255|unique:approve_mcs,mc_seq,' . $id,
                'mc_model' => 'required|string|max:255',
                'customer_code' => 'required|string|max:255',
                'customer_name' => 'required|string|max:255',
                'status' => 'required|in:pending,active,obsolete',
            ]);
            
            $masterCard->update($validated);
            
            Log::info('Master card updated: ' . $id);
            return response()->json($masterCard);
        } catch (\Exception $e) {
            Log::error('Error updating master card: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update master card: ' . $e->getMessage()], 500);
        }
    }

    public function approve(Request $request, $id)
    {
        try {
            $masterCard = ApproveMC::findOrFail($id);
            $masterCard->status = 'active';
            $masterCard->approved_by = auth()->user()->user_id;
            $masterCard->approved_date = now();
            $masterCard->save();

            Log::info('Master card approved: ' . $id);
            return response()->json(['message' => 'Master card approved successfully']);
        } catch (\Exception $e) {
            Log::error('Error approving master card: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to approve master card'], 500);
        }
    }

    public function reject(Request $request, $id)
    {
        try {
            $request->validate([
                'rejection_reason' => 'required|string|max:255',
            ]);

            $masterCard = ApproveMC::findOrFail($id);
            $masterCard->status = 'obsolete';
            $masterCard->rejected_by = auth()->user()->user_id;
            $masterCard->rejected_date = now();
            $masterCard->rejection_reason = $request->rejection_reason;
            $masterCard->save();

            Log::info('Master card rejected: ' . $id);
            return response()->json(['message' => 'Master card rejected successfully']);
        } catch (\Exception $e) {
            Log::error('Error rejecting master card: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to reject master card'], 500);
        }
    }

    public function getByCustomer($customerId)
    {
        try {
            $masterCards = ApproveMC::where('customer_code', $customerId)
                ->orderBy('mc_seq')
                ->get();
            
            return response()->json($masterCards);
        } catch (\Exception $e) {
            Log::error('Error retrieving master cards by customer: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve master cards'], 500);
        }
    }
}
