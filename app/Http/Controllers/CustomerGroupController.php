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
    
    public function vueViewAndPrint()
    {
        return inertia('sales-management/system-requirement/customer-account/view-and-print-customer-group');
    }

    public function apiIndex()
    {
        try {
            $customerGroups = CustomerGroup::orderBy('group_code')->get();
            Log::info('Customer Groups API Response:', ['data' => $customerGroups->toArray()]);
            return response()->json($customerGroups);
        } catch (\Exception $e) {
            Log::error('Error in CustomerGroup API:', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    // API methods for Vue frontend
    
    private function getUserId()
    {
        Log::info('Getting user ID for database operations');
        
        // Try to get the authenticated user's ID
        if (Auth::check()) {
            // Get the user_id from the authenticated user
            $user = Auth::user();
            Log::info('Authenticated user found', [
                'user_id' => $user->user_id,
                'username' => $user->username
            ]);
            
            // First try: Find the numeric ID of the user using Eloquent
            $userRecord = \App\Models\User::where('username', $user->username)
                ->orWhere('user_id', $user->user_id)
                ->first();
            
            if ($userRecord) {
                Log::info('Found user record', [
                    'id' => $userRecord->id,
                    'user_id' => $userRecord->user_id,
                    'username' => $userRecord->username
                ]);
                
                if (is_numeric($userRecord->id)) {
                    Log::info('Using numeric ID', ['id' => $userRecord->id]);
                    return (int)$userRecord->id;
                } else {
                    Log::warning('User ID is not numeric', ['id' => $userRecord->id]);
                }
            } else {
                Log::warning('Could not find user record using Eloquent');
                
                // Second try: Use direct DB query as fallback
                try {
                    $result = DB::table('users')
                        ->where('username', $user->username)
                        ->orWhere('user_id', $user->user_id)
                        ->select('id')
                        ->first();
                    
                    if ($result && isset($result->id) && is_numeric($result->id)) {
                        Log::info('Found user ID using direct DB query', ['id' => $result->id]);
                        return (int)$result->id;
                    } else {
                        Log::warning('Could not find user ID using direct DB query');
                    }
                } catch (\Exception $e) {
                    Log::error('Error in DB query for user ID', ['error' => $e->getMessage()]);
                }
            }
        } else {
            Log::warning('No authenticated user found');
        }
        
        // Third try: Get the first admin user from the database
        try {
            $adminUser = DB::table('users')
                ->where('username', 'admin')
                ->orWhere('username', 'ADMIN')
                ->orWhere('username', 'administrator')
                ->orWhere('username', 'ADMINISTRATOR')
                ->select('id')
                ->first();
            
            if ($adminUser && isset($adminUser->id) && is_numeric($adminUser->id)) {
                Log::info('Using admin user ID as fallback', ['id' => $adminUser->id]);
                return (int)$adminUser->id;
            }
        } catch (\Exception $e) {
            Log::error('Error finding admin user', ['error' => $e->getMessage()]);
        }
        
        // Last resort: Get the first user from the database
        try {
            $firstUser = DB::table('users')
                ->select('id')
                ->first();
            
            if ($firstUser && isset($firstUser->id) && is_numeric($firstUser->id)) {
                Log::info('Using first user ID as fallback', ['id' => $firstUser->id]);
                return (int)$firstUser->id;
            }
        } catch (\Exception $e) {
            Log::error('Error finding first user', ['error' => $e->getMessage()]);
        }
        
        // Absolute last resort: Default to user ID 1
        Log::info('Using default user ID', ['id' => 1]);
        return 1;
    }

    public function apiStore(Request $request)
    {
        $request->validate([
            'group_code' => 'required|string|max:20|unique:customer_groups',
            'description' => 'required|string|max:100',
        ]);

        DB::beginTransaction();
        try {
            // Get the numeric user ID
            $userId = (int)$this->getUserId();
            
            Log::info('Creating customer group', [
                'group_code' => $request->group_code,
                'description' => $request->description,
                'userId' => $userId,
                'type' => gettype($userId)
            ]);
            
            $customerGroup = CustomerGroup::create([
                'group_code' => strtoupper($request->group_code),
                'description' => $request->description,
                'created_by' => $userId,
                'updated_by' => $userId,
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
            'description' => 'required|string|max:100',
        ]);

        DB::beginTransaction();
        try {
            Log::info('Updating customer group', [
                'group_code' => $group_code,
                'description' => $request->description,
                'auth_user' => Auth::user() ? Auth::user()->username : null
            ]);
            
            // First try to find the customer group
            $customerGroup = CustomerGroup::findOrFail($group_code);
            
            // Get the numeric user ID
            $userId = (int)$this->getUserId();
            
            Log::info('Using user ID for update', [
                'userId' => $userId,
                'type' => gettype($userId)
            ]);
            
            // Try updating using Eloquent first
            try {
                $customerGroup->update([
                    'description' => $request->description,
                    'updated_by' => $userId,
                ]);
                
                Log::info('Updated customer group using Eloquent');
            } catch (\Exception $e) {
                Log::warning('Eloquent update failed, trying direct SQL', ['error' => $e->getMessage()]);
                
                // If Eloquent fails, try direct SQL update
                $affected = DB::table('customer_groups')
                    ->where('group_code', $group_code)
                    ->update([
                        'description' => $request->description,
                        'updated_by' => $userId,
                        'updated_at' => now()
                    ]);
                
                Log::info('Direct SQL update result', ['affected_rows' => $affected]);
                
                if ($affected === 0) {
                    throw new \Exception('No rows were updated');
                }
            }

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
                'trace' => $e->getTraceAsString(),
                'group_code' => $group_code,
                'request_data' => $request->all()
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
            // Get the numeric user ID
            $userId = (int)$this->getUserId();
            
            Log::info('Deleting customer group', [
                'group_code' => $group_code,
                'userId' => $userId,
                'type' => gettype($userId)
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

    public function seed()
    {
        DB::beginTransaction();
        try {
            // Get the numeric user ID
            $userId = (int)$this->getUserId();
            
            Log::info('Seeding customer groups', [
                'userId' => $userId,
                'type' => gettype($userId)
            ]);
            
            // Sample customer groups
            $groups = [
                ['group_code' => '01', 'description' => 'RETAIL'],
                ['group_code' => '02', 'description' => 'WHOLESALE'],
                ['group_code' => '03', 'description' => 'CORPORATE'],
                ['group_code' => '04', 'description' => 'DISTRIBUTOR'],
                ['group_code' => '05', 'description' => 'INTERNATIONAL']
            ];

            foreach ($groups as $group) {
                CustomerGroup::updateOrCreate(
                    ['group_code' => $group['group_code']],
                    [
                        'description' => $group['description'],
                        'created_by' => $userId,
                        'updated_by' => $userId
                    ]
                );
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Customer Group seed data created successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error seeding customer groups: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to seed Customer Groups: ' . $e->getMessage()
            ], 500);
        }
    }
}
