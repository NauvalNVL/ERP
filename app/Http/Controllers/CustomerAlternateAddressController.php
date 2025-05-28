<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerAlternateAddress;
use App\Models\UpdateCustomerAccount;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CustomerAlternateAddressController extends Controller
{
    public function index()
    {
        try {
            $customerAccounts = UpdateCustomerAccount::orderBy('customer_code')->get();
            Log::info('Mengambil data customer accounts untuk alternate address');
            // return view('sales-management.system-requirement.system-requirement.customer account.customeralternateaddress', compact('customerAccounts'));
            
            // Return Inertia response
            return Inertia::render('sales-management/system-requirement/customer-account/customer-alternate-address', [
                'customerAccounts' => $customerAccounts,
            ]);

        } catch (\Exception $e) {
            Log::error('Error saat mengambil data customer accounts: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengambil data customer accounts');
        }
    }

    public function apiIndex()
    {
        try {
            $customerAccounts = UpdateCustomerAccount::orderBy('customer_code')->get();
            Log::info('Mengambil data customer accounts untuk API');
            return response()->json($customerAccounts);
        } catch (\Exception $e) {
            Log::error('Error saat mengambil data customer accounts: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data customer accounts'], 500);
        }
    }

    // Add other methods like store, update, destroy as needed later
}