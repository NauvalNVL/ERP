<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerAlternateAddress;
use App\Models\Customer;
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
        // Use CUSTOMER table via Customer model to avoid relying on a non-existent update_customer_accounts table
        $customers = Customer::query()
            ->select(['CODE as customer_code', 'NAME as customer_name', 'AC_STS as status'])
            ->orderBy('NAME')
            ->limit(20)
            ->get();

        return [
            'customers' => $customers,
        ];
    }

    public function apiIndex()
    {
        try {
            $addresses = CustomerAlternateAddress::with('customerAccount')->orderBy('customer_code')->get();

            // Map data to include customer_name from alternate-address row and status from related CUSTOMER row
            $formattedAddresses = $addresses->map(function ($address) {
                $customer = $address->customerAccount; // Related Customer model (CUSTOMER table)

                // Customer name: use bill_to_name from customer_alternate_addresses first,
                // fallback ke CUSTOMER.NAME jika bill_to_name kosong, lalu N/A jika tetap tidak ada.
                if (!empty($address->bill_to_name)) {
                    $customerName = (string) $address->bill_to_name;
                } elseif ($customer && isset($customer->NAME)) {
                    $customerName = (string) $customer->NAME;
                } else {
                    $customerName = 'N/A';
                }

                // Status from CUSTOMER.AC_STS (A=Active, others=Obsolete), fallback N/A
                $statusLabel = 'N/A';
                if ($customer && isset($customer->AC_STS)) {
                    $code = (string) $customer->AC_STS;
                    if (strtoupper($code) === 'A') {
                        $statusLabel = 'Active';
                    } elseif (strtoupper($code) === 'O') {
                        $statusLabel = 'Obsolete';
                    } else {
                        $statusLabel = $code; // show raw code if unexpected
                    }
                }

                return [
                    'id' => $address->id,
                    'customer_code' => $address->customer_code,
                    'delivery_code' => $address->delivery_code,
                    'country' => $address->country,
                    'state' => $address->state,
                    'town' => $address->town,
                    'town_section' => $address->town_section,
                    'bill_to_name' => $address->bill_to_name,
                    'bill_to_address' => $address->bill_to_address,
                    'ship_to_name' => $address->ship_to_name,
                    'ship_to_address' => $address->ship_to_address,
                    'contact_person' => $address->contact_person,
                    'tel_no' => $address->tel_no,
                    'fax_no' => $address->fax_no,
                    'email' => $address->email,
                    // Backwards-compatible alias
                    'customer_name' => $customerName,
                    'status' => $statusLabel,
                ];
            });

            Log::info('Fetched all alternate addresses for API');
            return response()->json($formattedAddresses);
        } catch (\Exception $e) {
            Log::error('Error fetching all alternate addresses: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch alternate addresses'], 500);
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
            'delivery_code' => 'required|string|max:255',
            'country' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'town' => 'nullable|string|max:100',
            'town_section' => 'nullable|string|max:100',
            'bill_to_name' => 'nullable|string|max:255',
            'bill_to_address' => 'nullable|string',
            'ship_to_name' => 'nullable|string|max:255',
            'ship_to_address' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'tel_no' => 'nullable|string|max:20',
            'fax_no' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
        ]);

        DB::beginTransaction();
        try {
            $address = CustomerAlternateAddress::create($request->all());

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
            'customer_code' => 'required|string|max:20',
            'delivery_code' => 'required|string|max:255',
            'country' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'town' => 'nullable|string|max:100',
            'town_section' => 'nullable|string|max:100',
            'bill_to_name' => 'nullable|string|max:255',
            'bill_to_address' => 'nullable|string',
            'ship_to_name' => 'nullable|string|max:255',
            'ship_to_address' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'tel_no' => 'nullable|string|max:20',
            'fax_no' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
        ]);

        DB::beginTransaction();
        try {
            $address = CustomerAlternateAddress::findOrFail($id);

            $address->update($request->all());

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

    /**
     * Run the CustomerAlternateAddressSeeder
     */
    public function seed()
    {
        try {
            // Clear existing records
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            CustomerAlternateAddress::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            // Run the seeder
            $seeder = new \Database\Seeders\CustomerAlternateAddressSeeder();
            $seeder->run();

            Log::info('Customer alternate address data seeded successfully');

            return response()->json([
                'success' => true,
                'message' => 'Customer alternate address data seeded successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error seeding customer alternate address data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to seed customer alternate address data: ' . $e->getMessage()
            ], 500);
        }
    }
}
