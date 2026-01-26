<?php

namespace App\Http\Controllers;

use App\Models\UserCps;
use App\Models\UserPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $users = UserCps::paginate(10);
        return view('system-security/index', compact('users'));
    }

    public function create()
    {
        try {
            return view('system-security/create');
        } catch (\Exception $e) {
            Log::error('Error in UserController@create: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while opening the create form.');
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|unique:usercps,userID|max:20',
            'username' => 'required|unique:usercps,userName|max:30',
            'official_name' => 'required|max:100',
            'official_title' => 'nullable|max:50',
            'mobile_number' => 'nullable|digits_between:10,15',
            'official_tel' => 'nullable|digits_between:8,15',
            'password_expiry_date' => 'required|integer|min:0',
            'status' => 'required|in:A,O',
            'amend_expired_password' => 'required|in:Yes,No',
            'user_printer' => 'nullable|string|max:50',
            'print_route' => 'required|string|in:UF,FU',
            'menu_type' => 'required|string|in:W,V',
            'access_unit_price' => 'required|string|in:Y,N',
            'access_customer_acct' => 'required|string|in:Y,N',
            'amend_mc' => 'required|string|in:Y,N',
            'amend_mc_price' => 'required|string|in:Y,N',
            'salesperson_code' => 'nullable|string|max:50',
            'rc_rt_price' => 'required|string|in:Y,N',
            'board_rc_cost' => 'required|string|in:Y,N',
            'permissions' => 'nullable|array'
        ]);

        try {
            DB::beginTransaction();

            $user = UserCps::createUser($validated);

            // User created without any permissions
            // Permissions will be set separately via Define User Access Permission menu

            DB::commit();

            return redirect()->route('vue.system-security.index')
                ->with('success', 'User '.$user->userID.' created successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating user: '.$e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Failed to create user: '.$e->getMessage());
        }
    }

    public function edit(UserCps $user)
    {
        try {
            return view('system-security/edit', compact('user'));
        } catch (\Exception $e) {
            Log::error('Error in UserController@edit: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while opening the edit form.');
        }
    }

    public function update(Request $request, UserCps $user)
    {
        $rules = [
            'user_id' => [
                'required',
                'max:20',
                Rule::unique('usercps', 'userID')->ignore($user->userID, 'userID'),
            ],
            'username' => [
                'required',
                'max:30',
                Rule::unique('usercps', 'userName')->ignore($user->userID, 'userID'),
            ],
            'official_name' => 'required|max:100',
            'official_title' => 'nullable|max:50',
            'mobile_number' => 'nullable|digits_between:10,15',
            'official_tel' => 'nullable|digits_between:8,15',
            'status' => 'required|in:A,O',
            'password_expiry_date' => 'required|integer|min:0',
            'amend_expired_password' => 'required|in:Yes,No'
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        }

        $validated = $request->validate($rules);

        try {
            // Map validated fields to actual usercps table columns
            $updateData = [
                'userID' => $validated['user_id'],
                'userName' => $validated['username'],
                'OFFICIAL_NAME' => $validated['official_name'],
                'OFFICIAL_TITLE' => $validated['official_title'],
                'MOBILE' => $validated['mobile_number'],
                'TEL_' => $validated['official_tel'],
                // Convert A/O status back to original STS values
                'STS' => $validated['status'] === 'A' ? 'Active' : 'Inactive',
                // Recalculate absolute expiry date based on number of days
                'EXPIRY_DATE' => now()->addDays($validated['password_expiry_date'])->format('Y-m-d'),
                'EXPIRED' => $validated['amend_expired_password'],
                // Optional printer & menu settings
                'PRINTER' => $request->input('user_printer', $user->PRINTER),
                'ROUTE' => $request->input('print_route', $user->ROUTE),
                'TYPE' => $request->input('menu_type', $user->TYPE),
                // Special access flags & salesperson lock
                'U_PRICE' => $request->input('access_unit_price', $user->U_PRICE),
                'AC' => $request->input('access_customer_acct', $user->AC),
                'MC' => $request->input('amend_mc', $user->MC),
                'MC_PRICE' => $request->input('amend_mc_price', $user->MC_PRICE),
                'SM' => $request->input('salesperson_code', $user->SM),
                // Price & cost visibility flags
                'PRICE' => $request->input('rc_rt_price', $user->PRICE),
                'COST' => $request->input('board_rc_cost', $user->COST),
                'updated_at' => now(),
            ];

            if ($request->filled('password')) {
                $updateData['PASS'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            return redirect()->route('vue.system-security.index')
                ->with('success', 'User updated successfully.');

        } catch (\Exception $e) {
            Log::error('Error updating user: '.$e->getMessage());
            return back()->withInput()
                ->with('error', 'Failed to update user. Please try again.');
        }
    }

    public function destroy(UserCps $user)
    {
        // Delete user is no longer allowed. Status should be managed via Reactive/Unobsolete User menu.
        return redirect()->route('vue.system-security.index')
            ->with('error', 'Delete user function is no longer available. Please use the Reactive/Unobsolete User menu.');
    }

    public function showAmendForm(Request $request)
    {
        $user = null;

        if($request->has('search_user_id')) {
            $user = UserCps::where('userID', $request->search_user_id)->first();

            if(!$user) {
                return redirect()->route('users.amend-password')
                    ->withInput()
                    ->with('error', 'User ID not found.');
            }
        }

        return view('system-security/amend', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:usercps,userID',
            'new_password' => 'required|min:8|confirmed',
        ]);

        try {
            $user = UserCps::where('userID', $request->user_id)->firstOrFail();

            // Update password menggunakan method dari model
            $user->updatePassword($request->new_password, 90);

            return redirect()->route('vue.system-security.amend-password')
                ->with('success', 'Password successfully updated for user: '.$user->userID);
        } catch (\Exception $e) {
            Log::error('Password update error: '.$e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Failed to update password: '.$e->getMessage());
        }
    }

    public function vueIndex()
    {
        $users = UserCps::paginate(10);
        return Inertia::render('system-manager/system-security/Index', [
            'users' => $users,
            'header' => 'Define User'
        ]);
    }

    public function vueCreate()
    {
        return Inertia::render('system-manager/system-security/Create', [
            'header' => 'Tambah User Baru',
            'timestamp' => now()->timestamp
        ]);
    }

    public function vueEdit(UserCps $user)
    {
        return Inertia::render('system-manager/system-security/Edit', [
            'user' => $user,
            'header' => 'Edit User'
        ]);
    }

    public function vueAmendPassword()
    {
        $user = null;

        if(request()->has('search_user_id')) {
            $user = UserCps::where('userID', request()->search_user_id)->first();
        }

        return Inertia::render('system-manager/system-security/AmendPassword', [
            'user' => $user,
            'header' => 'Amend Password'
        ]);
    }

    public function vueDefineAccess()
    {
        return Inertia::render('system-manager/system-security/DefineAccess', [
            'header' => 'Define User Access'
        ]);
    }

    public function vueCopyPasteAccess()
    {
        return Inertia::render('system-manager/system-security/CopyPasteUserAccessPermission', [
            'header' => 'Copy & Paste User Access Permission'
        ]);
    }

    public function vueViewPrintUser()
    {
        return Inertia::render('system-manager/system-security/ViewPrintUser', [
            'header' => 'View & Print User'
        ]);
    }

    /**
     * API: Get list of users for Vue pages (View & Print User, Reactive/Unobsolete User, etc.)
     */
    public function apiIndex(Request $request)
    {
        try {
            $query = UserCps::query();

            $search = $request->query('search');
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('userID', 'like', '%' . $search . '%')
                        ->orWhere('userName', 'like', '%' . $search . '%')
                        ->orWhere('OFFICIAL_NAME', 'like', '%' . $search . '%');
                });
            }

            $users = $query->orderBy('userID')->get();

            // Rely on model accessors & hidden attributes to shape JSON
            return response()->json($users);
        } catch (\Exception $e) {
            Log::error('Error fetching users for API: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error fetching users: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function searchUsers(Request $request)
    {
        $search = $request->query('search');

        $users = UserCps::query();

        if (!empty($search)) {
            $users->where('userID', 'like', '%' . $search . '%')
                  ->orWhere('userName', 'like', '%' . $search . '%');
        }

        return response()->json($users->get());
    }

    public function getUserPermissions(UserCps $user)
    {
        try {
            // Get user permissions from user_permissions table
            $permissions = UserPermission::where('user_id', $user->userID)
                ->get(['menu_key', 'menu_name', 'menu_route', 'menu_category', 'menu_parent', 'can_access'])
                ->toArray();

            return response()->json($permissions);
        } catch (\Exception $e) {
            Log::error('Error getting user permissions: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }

    public function updateAccess(Request $request, UserCps $user)
    {
        // Validasi input
        $validated = $request->validate([
            'permissions' => 'nullable|array'
        ]);

        try {
            DB::beginTransaction();

            // Delete existing permissions for this user
            UserPermission::where('user_id', $user->userID)->delete();

            // Create new permissions based on the copied permissions
            foreach (($validated['permissions'] ?? []) as $permission) {
                UserPermission::create([
                    'user_id' => $user->userID,
                    'menu_key' => $permission['menu_key'],
                    'menu_name' => $permission['menu_name'],
                    'menu_route' => $permission['menu_route'],
                    'menu_category' => $permission['menu_category'],
                    'menu_parent' => $permission['menu_parent'],
                    'can_access' => isset($permission['can_access']) ? (bool) $permission['can_access'] : true
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Permissions copied successfully for user: ' . $user->userID
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error copying permissions: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to copy permissions: ' . $e->getMessage()
            ], 500);
        }
    }

    public function copyPermissions(Request $request)
    {
        $validated = $request->validate([
            'from_user_id' => 'required|string|exists:usercps,userID',
            'to_user_id'   => 'required|string|different:from_user_id|exists:usercps,userID',
        ]);

        try {
            DB::beginTransaction();

            // Ambil semua permissions dari user sumber langsung dari tabel user_permissions
            $sourcePermissions = UserPermission::where('user_id', $validated['from_user_id'])->get();

            // Hapus semua permissions lama milik user target
            UserPermission::where('user_id', $validated['to_user_id'])->delete();

            // Salin persis setiap permission (termasuk nilai can_access)
            foreach ($sourcePermissions as $permission) {
                UserPermission::create([
                    'user_id'       => $validated['to_user_id'],
                    'menu_key'      => $permission->menu_key,
                    'menu_name'     => $permission->menu_name,
                    'menu_route'    => $permission->menu_route,
                    'menu_category' => $permission->menu_category,
                    'menu_parent'   => $permission->menu_parent,
                    'can_access'    => (bool) $permission->can_access,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Permissions copied from ' . $validated['from_user_id'] . ' to ' . $validated['to_user_id'],
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error copying permissions via copyPermissions: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to copy permissions: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function defineAccess()
    {
        return inertia('system-manager/system-security/DefineAccess');
    }

    public function searchUser($userId)
    {
        try {
            $user = UserCps::where('userID', $userId)->first();

            if (!$user) {
                return response()->json(['user' => null, 'permissions' => []], 404);
            }

            if ($user->status !== 'A') {
                return response()->json([
                    'user' => $user,
                    'permissions' => [],
                    'message' => 'This user is inactive/obsolete and cannot access Define User Access Permission.'
                ], 403);
            }

            // Get user's current permissions
            $userPermissions = UserPermission::where('user_id', $userId)
                ->where('can_access', true)
                ->pluck('menu_key')
                ->toArray();

            return response()->json([
                'user' => $user,
                'permissions' => $userPermissions
            ]);

        } catch (\Exception $e) {
            Log::error('Error searching user: ' . $e->getMessage());
            return response()->json(['error' => 'User search failed'], 500);
        }
    }

    public function updateUserPermissions(Request $request, $userId)
    {
        $validated = $request->validate([
            'permissions' => 'required|array'
        ]);

        try {
            DB::beginTransaction();

            $user = UserCps::where('userID', $userId)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.'
                ], 404);
            }

            if ($user->status !== 'A') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot update permissions for inactive or obsolete users.'
                ], 422);
            }

            // Delete existing permissions
            UserPermission::where('user_id', $userId)->delete();

            // Create new permissions
            $menuItems = UserPermission::getAllMenuItems();

            // Remove duplicates based on menu_key
            $uniqueMenuItems = [];
            $seenKeys = [];

            foreach ($menuItems as $item) {
                if (!in_array($item['key'], $seenKeys)) {
                    $uniqueMenuItems[] = $item;
                    $seenKeys[] = $item['key'];
                }
            }

            foreach ($uniqueMenuItems as $item) {
                $hasPermission = isset($validated['permissions'][$item['key']]) && $validated['permissions'][$item['key']];

                UserPermission::create([
                    'user_id' => $userId,
                    'menu_key' => $item['key'],
                    'menu_name' => $item['name'],
                    'menu_route' => $item['route'],
                    'menu_category' => $item['category'],
                    'menu_parent' => $item['parent'],
                    'can_access' => $hasPermission
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Permissions updated successfully for user: ' . $userId
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating user permissions: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update permissions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Toggle user status Active / Obsolete for Reactive/Unobsolete User page
     */
    public function apiToggleStatus(Request $request, $userId)
    {
        try {
            $user = UserCps::where('userID', $userId)->firstOrFail();

            $request->validate([
                'status' => 'nullable|in:A,O,Active,Inactive',
            ]);

            // Determine target status: use provided status if any, otherwise toggle
            if ($request->filled('status')) {
                $statusInput = $request->input('status');
                $shouldBeActive = in_array($statusInput, ['A', 'Active'], true);
            } else {
                $currentSts = $user->STS ?? 'Inactive';
                $shouldBeActive = $currentSts !== 'Active';
            }

            $user->STS = $shouldBeActive ? 'Active' : 'Inactive';
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'User status updated successfully',
                'user' => $user,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error toggling user status: ' . $e->getMessage(), [
                'userID' => $userId,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error updating user status: ' . $e->getMessage(),
            ], 500);
        }
    }
}
