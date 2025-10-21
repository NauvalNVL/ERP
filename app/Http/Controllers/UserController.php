<?php

namespace App\Http\Controllers;

use App\Models\UserCps;
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
            return back()->with('error', 'Terjadi kesalahan saat membuka form create');
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|unique:usercps,userID|max:20',
            'username' => 'required|unique:usercps,userName|max:30',
            'official_name' => 'required|max:100',
            'official_title' => 'required|max:50',
            'mobile_number' => 'required|digits_between:10,15',
            'official_tel' => 'required|digits_between:8,15',
            'password_expiry_date' => 'required|integer|min:0',
            'status' => 'required|in:A,O',
            'amend_expired_password' => 'required|in:Yes,No'
        ]);

        try {
            $user = UserCps::createUser($validated);

            return redirect()->route('vue.system-security.index')
                ->with('success', 'User '.$user->userID.' berhasil dibuat');

        } catch (\Exception $e) {
            Log::error('Error creating user: '.$e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Gagal membuat user: '.$e->getMessage());
        }
    }

    public function edit(UserCps $user)
    {
        try {
            return view('system-security/edit', compact('user'));
        } catch (\Exception $e) {
            Log::error('Error in UserController@edit: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat membuka form edit');
        }
    }

    public function update(Request $request, UserCps $user)
    {
        $rules = [
            'user_id' => ['required','max:20',Rule::unique('usercps', 'userID')->ignore($user->id)],
            'username' => ['required','max:30',Rule::unique('usercps', 'userName')->ignore($user->id)],
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
            $updateData = [
                'user_id' => $validated['user_id'],
                'username' => $validated['username'],
                'official_name' => $validated['official_name'],
                'official_title' => $validated['official_title'],
                'mobile_number' => $validated['mobile_number'],
                'official_tel' => $validated['official_tel'],
                'status' => $validated['status'],
                'password_expiry_date' => $validated['password_expiry_date'],
                'amend_expired_password' => $validated['amend_expired_password'],
                'updated_at' => now()
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            return redirect()->route('vue.system-security.index')
                ->with('success', 'User berhasil diperbarui');

        } catch (\Exception $e) {
            Log::error('Error updating user: '.$e->getMessage());
            return back()->withInput()
                ->with('error', 'Gagal memperbarui user. Silakan coba lagi.');
        }
    }

    public function destroy(UserCps $user)
    {
        try {
            if ($user->userID === Auth::user()->userID) {
                return back()->with('error', 'Tidak dapat menghapus akun sendiri');
            }

            $user->delete();
            return redirect()->route('vue.system-security.index')
                ->with('success', 'User berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting user: '.$e->getMessage());
            return back()->with('error', 'Gagal menghapus user. Silakan coba lagi.');
        }
    }

    public function showAmendForm(Request $request)
    {
        $user = null;
        
        if($request->has('search_user_id')) {
            $user = UserCps::where('userID', $request->search_user_id)->first();
            
            if(!$user) {
                return redirect()->route('users.amend-password')
                    ->withInput()
                    ->with('error', 'User ID tidak ditemukan');
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
                ->with('success', 'Password berhasil diperbarui untuk user: '.$user->userID);
        } catch (\Exception $e) {
            Log::error('Password update error: '.$e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui password: '.$e->getMessage());
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
            'header' => 'Tambah User Baru'
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
        // Implementasi untuk mendapatkan daftar permission user
        // Ini hanya contoh, sesuaikan dengan struktur database Anda
        $permissions = DB::table('user_permissions')
            ->where('user_id', $user->id)
            ->pluck('permission_name')
            ->toArray();
        
        // Jika tabel belum ada, kita berikan array kosong sebagai fallback
        if (empty($permissions)) {
            $permissions = [];
        }
        
        return response()->json($permissions);
    }

    public function updateAccess(Request $request, UserCps $user)
    {
        // Validasi input
        $validated = $request->validate([
            'permissions' => 'required|array'
        ]);
        
        try {
            // Hapus permission lama
            DB::table('user_permissions')
                ->where('user_id', $user->id)
                ->delete();
            
            // Tambahkan permission baru
            foreach ($validated['permissions'] as $permission) {
                DB::table('user_permissions')->insert([
                    'user_id' => $user->id,
                    'permission_name' => $permission,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            
            return redirect()->route('vue.system-security.define-access')
                ->with('success', 'Permissions berhasil diperbarui untuk user: ' . $user->userID);
        } catch (\Exception $e) {
            Log::error('Error updating permissions: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal memperbarui permissions: ' . $e->getMessage());
        }
    }
}