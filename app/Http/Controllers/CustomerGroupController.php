<?php

namespace App\Http\Controllers;

use App\Models\CustomerGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerGroupController extends Controller
{
    public function index()
    {
        try {
            $customerGroups = CustomerGroup::orderBy('Group_ID')->get();
            
            if ($customerGroups->isEmpty()) {
                $this->seedData();
                $customerGroups = CustomerGroup::orderBy('Group_ID')->get();
            }
            
            return view('sales-management.system-requirement.customer-account.customergroup', compact('customerGroups'));
        } catch (\Exception $e) {
            Log::error('Error in CustomerGroup index: ' . $e->getMessage());
            return view('sales-management.system-requirement.customer-account.customergroup', compact('customerGroups'));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'group_code' => 'required|string|max:20|unique:customer_groups',
            'description' => 'required|string|max:100',
        ]);

        DB::beginTransaction();
        try {
            CustomerGroup::create([
                'group_code' => strtoupper($request->group_code),
                'description' => $request->description,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            DB::commit();
            return redirect()->route('customer-group.index')->with('success', 'Customer Group created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to create Customer Group. ' . $e->getMessage());
        }
    }

    public function update(Request $request, $group_code)
    {
        $request->validate([
            'description' => 'required|string|max:100',
        ]);

        DB::beginTransaction();
        try {
            $customerGroup = CustomerGroup::findOrFail($group_code);
            $customerGroup->update([
                'description' => $request->description,
                'updated_by' => Auth::id(),
            ]);

            DB::commit();
            return redirect()->route('customer-group.index')->with('success', 'Customer Group updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to update Customer Group. ' . $e->getMessage());
        }
    }

    public function destroy($group_code)
    {
        DB::beginTransaction();
        try {
            $customerGroup = CustomerGroup::findOrFail($group_code);
            $customerGroup->delete();

            DB::commit();
            return redirect()->route('customer-group.index')->with('success', 'Customer Group deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to delete Customer Group. ' . $e->getMessage());
        }
    }

    public function viewAndPrint()
    {
        $customerGroups = CustomerGroup::orderBy('Group_ID')->get();
        return view('sales-management.system-requirement.customer-account.customergroup-print', compact('customerGroups'));
    }
    
    public function vueViewAndPrint()
    {
        return inertia('sales-management/system-requirement/customer-account/view-and-print-customer-group');
    }

    public function apiIndex()
    {
        try {
            $customerGroups = CustomerGroup::orderBy('Group_ID')->get();
            
            if ($customerGroups->isEmpty()) {
                $this->seedData();
                $customerGroups = CustomerGroup::orderBy('Group_ID')->get();
            }
            
            Log::info('Customer Groups API Response:', ['data' => $customerGroups->toArray()]);
            return response()->json($customerGroups);
        } catch (\Exception $e) {
            Log::error('Error in CustomerGroup API:', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    // API methods for Vue frontend

    public function apiStore(Request $request)
    {
        // Validate with CPS table
        $request->validate([
            'group_code' => 'required|string|max:12|unique:CUST_GROUP,Group_ID',
            'description' => 'required|string|max:50',
        ]);

        DB::beginTransaction();
        try {
            // Auto-generate sequential No
            $maxNo = CustomerGroup::max('No');
            $nextNo = $maxNo ? ((int)$maxNo + 1) : 1;
            
            Log::info('Creating customer group', [
                'group_code' => $request->group_code,
                'description' => $request->description,
                'no' => $nextNo
            ]);
            
            $customerGroup = CustomerGroup::create([
                'No' => (string)$nextNo,
                'Group_ID' => strtoupper($request->group_code),
                'Group_Name' => $request->description,
                'Currency' => null,
                'AC' => null,
                'Name' => null
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Customer Group created successfully',
                'data' => $customerGroup
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating customer group: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create Customer Group: ' . $e->getMessage()
            ], 500);
        }
    }

    public function apiUpdate(Request $request, $group_code)
    {
        $request->validate([
            'description' => 'required|string|max:50',
        ]);

        DB::beginTransaction();
        try {
            Log::info('Updating customer group', [
                'group_code' => $group_code,
                'description' => $request->description
            ]);
            
            // Find by Group_ID
            $customerGroup = CustomerGroup::findOrFail($group_code);
            
            // Update using CPS fields
            $customerGroup->update([
                'Group_Name' => $request->description
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Customer Group updated successfully',
                'data' => $customerGroup
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating customer group', [
                'error' => $e->getMessage(),
                'group_code' => $group_code
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update Customer Group: ' . $e->getMessage()
            ], 500);
        }
    }

    public function apiDestroy($group_code)
    {
        DB::beginTransaction();
        try {
            Log::info('Deleting customer group', [
                'group_code' => $group_code
            ]);
            
            $customerGroup = CustomerGroup::findOrFail($group_code);
            $customerGroup->delete();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Customer Group deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting customer group: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete Customer Group: ' . $e->getMessage()
            ], 500);
        }
    }

    private function seedData()
    {
        try {
            Log::info('Seeding customer groups from CPS');
            
            // Sample customer groups based on CPS images with sequential No
            $groups = [
                ['No' => '1', 'Group_ID' => '01', 'Group_Name' => 'PHUTANG TRADER'],
                ['No' => '2', 'Group_ID' => '02', 'Group_Name' => 'PHUTANG USAHA PENDAPATAN LAIN1'],
                ['No' => '3', 'Group_ID' => '03', 'Group_Name' => 'PHUTANG USAHA PENDAPATAN LAIN2'],
                ['No' => '4', 'Group_ID' => 'NA', 'Group_Name' => 'PHUTANG USAHA']
            ];

            foreach ($groups as $group) {
                CustomerGroup::updateOrCreate(
                    ['Group_ID' => $group['Group_ID']],
                    [
                        'No' => $group['No'],
                        'Group_Name' => $group['Group_Name'],
                        'Currency' => null,
                        'AC' => null,
                        'Name' => null
                    ]
                );
            }
        } catch (\Exception $e) {
            Log::error('Error seeding customer groups: ' . $e->getMessage());
            throw $e;
        }
    }

    public function seed()
    {
        try {
            $this->seedData();
            return response()->json([
                'success' => true,
                'message' => 'Customer Group seed data created successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error seeding customer groups: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to seed Customer Groups: ' . $e->getMessage()
            ], 500);
        }
    }
}
