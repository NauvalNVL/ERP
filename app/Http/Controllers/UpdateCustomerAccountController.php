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

    public function store(Request $request)
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

        // Check if customer with this code already exists
        $existingAccount = UpdateCustomerAccount::where('customer_code', $validated['customer_code'])->first();
        
        if ($existingAccount) {
            // Update existing account
            $existingAccount->update($validated);
            return to_route('vue.update-customer-account.index')->with('message', 'Customer account updated successfully');
        } else {
            // Create new account
            UpdateCustomerAccount::create($validated);
            return to_route('vue.update-customer-account.index')->with('message', 'Customer account created successfully');
        }
    }

    public function apiIndex(Request $request)
    {
        try {
            Log::info('Fetching customer accounts...');
            $query = UpdateCustomerAccount::query();

            // Apply search filter
            if ($request->has('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('customer_code', 'like', '%' . $search . '%')
                      ->orWhere('customer_name', 'like', '%' . $search . '%');
                });
            }

            // Apply sort order
            if ($request->has('sort_by')) {
                $sortBy = $request->input('sort_by');
                $query->orderBy($sortBy);
            } else {
                $query->orderBy('customer_code'); // Default sort
            }

            // Temporarily disable status filters for testing
            // $filterActive = filter_var($request->input('filter_active', false), FILTER_VALIDATE_BOOLEAN);
            // $filterObsolete = filter_var($request->input('filter_obsolete', false), FILTER_VALIDATE_BOOLEAN);

            // if ($filterActive && !$filterObsolete) {
            //     $query->where('status', 'Active');
            // } elseif (!$filterActive && $filterObsolete) {
            //     $query->where('status', 'Obsolete');
            // } elseif (!($filterActive && $filterObsolete)) {
            //     if (!$filterActive && !$filterObsolete) {
            //          return response()->json(['data' => []]);
            //     }
            // }

            $accounts = $query->get();
            Log::info('Found ' . $accounts->count() . ' customer accounts with applied filters');
            
            // Transform data to ensure consistent format with expected fields
            $formatted = $accounts->map(function($account) {
                // Default to Active if status is not set, or map to 'Active'/'Obsolete'
                $status = $account->status ?? 'Active'; // Assuming 'status' column exists
                
                return [
                    'id' => $account->id, // Ensure ID is returned for keying in Vue
                    'customer_code' => $account->customer_code,
                    'customer_name' => $account->customer_name,
                    'short_name' => $account->short_name,
                    'salesperson_code' => $account->salesperson_code, // Use salesperson_code
                    'account_type' => $account->ac_type,
                    'currency_code' => $account->currency_code,
                    'address' => $account->address,
                    'contact_person' => $account->contact_person,
                    'telephone_no' => $account->telephone_no,
                    'co_email' => $account->co_email,
                    'status' => $status, 
                ];
            });
            
            Log::info('API response data structure check:', [
                'sample' => $formatted->first() ? json_encode($formatted->first()) : 'No data',
                'count' => $formatted->count()
            ]);
            
            return response()->json(['data' => $formatted]);
        } catch (\Exception $e) {
            Log::error('Error in CustomerAccount API:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Gagal mengambil data customer account: ' . $e->getMessage()], 500);
        }
    }

    public function apiStore(Request $request)
    {
        try {
            Log::info('API Store Customer Account Request:', ['data' => $request->all()]);
            
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
            
            Log::info('Validated data:', $validated);

            // Check if customer with this code already exists
            $existingAccount = UpdateCustomerAccount::where('customer_code', $validated['customer_code'])->first();
            
            if ($existingAccount) {
                // Update existing account
                Log::info('Updating existing account:', ['id' => $existingAccount->id, 'code' => $existingAccount->customer_code]);
                $existingAccount->update($validated);
                return response()->json([
                    'message' => 'Customer account updated successfully',
                    'data' => $existingAccount
                ]);
            } else {
                // Create new account
                Log::info('Creating new account:', ['code' => $validated['customer_code']]);
                $newAccount = UpdateCustomerAccount::create($validated);
                return response()->json([
                    'message' => 'Customer account created successfully',
                    'data' => $newAccount
                ], 201);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error:', ['errors' => $e->errors()]);
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error saving customer account:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'Failed to save customer account: ' . $e->getMessage()
            ], 500);
        }
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

    /**
     * Get a single customer account by ID
     */
    public function apiShow($id)
    {
        try {
            Log::info('Fetching single customer account', ['id' => $id]);
            $account = UpdateCustomerAccount::findOrFail($id);
            
            Log::info('Found customer account', ['customer_code' => $account->customer_code]);
            
            return response()->json($account);
        } catch (\Exception $e) {
            Log::error('Error fetching customer account', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Customer account not found: ' . $e->getMessage()
            ], 404);
        }
    }

    public function apiIndexAcAutoWoCustomers()
    {
        // For demonstration, returning dummy data
        $dummyCustomers = [
            ['customer_code' => 'CUST001', 'customer_name' => 'Customer A Ltd.', 'status' => 'Active'],
            ['customer_code' => 'CUST002', 'customer_name' => 'Customer B Corp.', 'status' => 'Inactive'],
            ['customer_code' => 'CUST003', 'customer_name' => 'Customer C Inc.', 'status' => 'Active'],
            ['customer_code' => 'CUST004', 'customer_name' => 'Customer D LLC', 'status' => 'Active'],
            ['customer_code' => 'CUST005', 'customer_name' => 'Customer E Group', 'status' => 'Inactive'],
        ];

        return response()->json($dummyCustomers);
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
