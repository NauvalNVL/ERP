<?php

namespace App\Http\Controllers;

use App\Models\CustomerGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerGroupController extends Controller
{
    public function index()
    {
        $customerGroups = CustomerGroup::orderBy('group_code')->get();
        return view('sales-management.system-requirement.customer-account.customergroup', compact('customerGroups'));
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
        $customerGroups = CustomerGroup::orderBy('group_code')->get();
        return view('sales-management.system-requirement.customer-account.customergroup-print', compact('customerGroups'));
    }
}
