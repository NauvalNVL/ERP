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
            return response()->json($accounts);
        } catch (\Exception $e) {
            Log::error('Error in CustomerAccount API:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Gagal mengambil data customer account'], 500);
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
}
