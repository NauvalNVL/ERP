<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UpdateCustomerAccount;
use App\Models\Industry;
use App\Models\Geo;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class UpdateCustomerAccountController extends Controller
{
    public function index()
    {
        $accounts = \App\Models\UpdateCustomerAccount::all();
        $industries = Industry::all();
        $geoData = Geo::all();
        return view('sales-management.system-requirement.system-requirement.customer account.updatecustomeraccount', compact('accounts', 'industries', 'geoData'));
    }

    public function apiIndex()
    {
        try {
            Log::info('Fetching customer accounts...');
            $accounts = UpdateCustomerAccount::orderBy('customer_code')->get();
            Log::info('Found ' . $accounts->count() . ' customer accounts');
            
            // Transform data to ensure consistent format with expected fields
            $formatted = $accounts->map(function($account) {
                // Default to Active if status is not set
                $status = $account->status ?? 'Active';
                
                return [
                    'customer_code' => $account->customer_code,
                    'customer_name' => $account->customer_name,
                    'short_name' => $account->short_name,
                    'salesperson' => $account->salesperson_code,
                    'ac_type' => $account->ac_type,
                    'currency' => $account->currency_code,
                    'address' => $account->address,
                    'contact_person' => $account->contact_person,
                    'telephone_no' => $account->telephone_no,
                    'co_email' => $account->co_email,
                    'status' => $status, // Use the default or actual status
                ];
            });
            
            Log::info('API response data structure check:', [
                'sample' => $formatted->first() ? json_encode($formatted->first()) : 'No data',
                'count' => $formatted->count()
            ]);
            
            return response()->json($formatted);
        } catch (\Exception $e) {
            Log::error('Error in CustomerAccount API:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Gagal mengambil data customer account: ' . $e->getMessage()], 500);
        }
    }

    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'customer_code' => 'required|string|max:20',
            'customer_name' => 'required|string|max:100',
            'short_name' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'contact_person' => 'nullable|string|max:100',
            'telephone_no' => 'nullable|string|max:30',
            'fax_no' => 'nullable|string|max:30',
            'co_email' => 'nullable|email|max:100',
            'credit_limit' => 'nullable|numeric',
            'credit_terms' => 'nullable|integer',
            'ac_type' => 'required|string|in:Y-Foreign,N-Local',
            'currency_code' => 'nullable|string|max:10',
            'salesperson_code' => 'nullable|string|max:20',
            'industrial_code' => 'nullable|string|max:20',
            'geographical' => 'nullable|string|max:20',
            'grouping_code' => 'nullable|string|max:20',
            'print_ar_aging' => 'required|string|in:Y-Yes,N-No'
        ]);

        $account = UpdateCustomerAccount::create($validated);
        return response()->json($account, 201);
    }

    public function apiUpdate(Request $request, $id)
    {
        $account = UpdateCustomerAccount::findOrFail($id);

        $validated = $request->validate([
            'customer_code' => 'required|string|max:20',
            'customer_name' => 'required|string|max:100',
            'short_name' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'contact_person' => 'nullable|string|max:100',
            'telephone_no' => 'nullable|string|max:30',
            'fax_no' => 'nullable|string|max:30',
            'co_email' => 'nullable|email|max:100',
            'credit_limit' => 'nullable|numeric',
            'credit_terms' => 'nullable|integer',
            'ac_type' => 'required|string|in:Y-Foreign,N-Local',
            'currency_code' => 'nullable|string|max:10',
            'salesperson_code' => 'nullable|string|max:20',
            'industrial_code' => 'nullable|string|max:20',
            'geographical' => 'nullable|string|max:20',
            'grouping_code' => 'nullable|string|max:20',
            'print_ar_aging' => 'required|string|in:Y-Yes,N-No'
        ]);

        $account->update($validated);
        return response()->json($account);
    }

    public function updateStatus(Request $request, $customer_code)
    {
        try {
            $request->validate([
                'active' => 'required|in:Y,N',
                'reason' => 'required|string|max:255'
            ]);

            $customerAccount = UpdateCustomerAccount::where('customer_code', $customer_code)->first();
            
            if (!$customerAccount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer account not found'
                ], 404);
            }

            // Get current user name, or use 'System'
            $username = 'System';
            if (request()->user()) {
                $username = request()->user()->name;
            }
            
            // Update status only
            $customerAccount->status = $request->active === 'Y' ? 'Active' : 'Inactive';
            $customerAccount->save();
            
            // Log status change with reason
            Log::info('Customer account status changed', [
                'customer_code' => $customer_code,
                'customer_name' => $customerAccount->customer_name,
                'new_status' => $request->active === 'Y' ? 'Active' : 'Inactive',
                'reason' => $request->reason,
                'changed_by' => $username
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Customer account status updated successfully',
                'customer' => $customerAccount
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating customer account status', [
                'customer_code' => $customer_code,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error updating customer account status: ' . $e->getMessage()
            ], 500);
        }
    }
}
