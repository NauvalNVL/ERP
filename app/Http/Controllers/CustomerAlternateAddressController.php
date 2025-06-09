<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerAlternateAddress;
use App\Models\UpdateCustomerAccount;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CustomerAlternateAddressController extends Controller
{
    public function index()
    {
        try {
            // Return Inertia response
            return Inertia::render('sales-management/system-requirement/customer-account/customer-alternate-address', [
                'initialData' => $this->getInitialData(),
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading customer alternate address page: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while loading the page');
        }
    }

    private function getInitialData()
    {
        return [
            'customers' => UpdateCustomerAccount::select('id', 'customer_code', 'customer_name', 'status', 'currency', 'currency_code', 'salesperson_code')
                ->orderBy('customer_name')
                ->get(),
        ];
    }

    public function apiIndex()
    {
        try {
            $customers = UpdateCustomerAccount::select('id', 'customer_code', 'customer_name', 'status', 'currency', 'currency_code', 'salesperson_code')
                ->orderBy('customer_name')
                ->get();
            Log::info('Fetched customer data for API');
            return response()->json($customers);
        } catch (\Exception $e) {
            Log::error('Error fetching customer data: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch customer data'], 500);
        }
    }

    public function apiGetCustomerAddresses($customerCode)
    {
        try {
            $addresses = CustomerAlternateAddress::where('customer_code', $customerCode)
                ->orderBy('id')
                ->get();
            
            Log::info('Fetched alternate addresses for customer: ' . $customerCode);
            return response()->json($addresses);
        } catch (\Exception $e) {
            Log::error('Error fetching alternate addresses: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch alternate addresses'], 500);
        }
    }

    public function apiStore(Request $request)
    {
        $request->validate([
            'customer_code' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:100',
        ]);

        DB::beginTransaction();
        try {
            // Get customer details
            $customer = UpdateCustomerAccount::where('customer_code', $request->customer_code)->first();
            
            if (!$customer) {
                return response()->json(['error' => 'Customer not found'], 404);
            }

            $address = CustomerAlternateAddress::create([
                'customer_name' => $customer->customer_name,
                'customer_code' => $request->customer_code,
                'salesperson_type' => $customer->salesperson_code ?? 'S000',
                'currency' => $customer->currency ?? 'Local',
                'currency_code' => $customer->currency_code ?? 'IDR',
                'status' => $customer->status ?? 'Active',
                'address' => $request->address,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'phone' => $request->phone,
                'email' => $request->email,
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Alternate address created successfully',
                'data' => $address
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating alternate address: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create alternate address: ' . $e->getMessage()
            ], 500);
        }
    }

    public function apiUpdate(Request $request, $id)
    {
        $request->validate([
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:100',
        ]);

        DB::beginTransaction();
        try {
            $address = CustomerAlternateAddress::findOrFail($id);
            
            $address->update([
                'address' => $request->address,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'phone' => $request->phone,
                'email' => $request->email,
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Alternate address updated successfully',
                'data' => $address
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating alternate address: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update alternate address: ' . $e->getMessage()
            ], 500);
        }
    }

    public function apiDestroy($id)
    {
        DB::beginTransaction();
        try {
            $address = CustomerAlternateAddress::findOrFail($id);
            $address->delete();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Alternate address deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting alternate address: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete alternate address: ' . $e->getMessage()
            ], 500);
        }
    }
}