<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);
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
            'user_id' => 'required|unique:users|max:20',
            'username' => 'required|unique:users|max:30',
            'official_name' => 'required|max:100',
            'password_expiry_date' => 'required|integer|min:0',
            'status' => 'required|in:A,O',
            'amend_expired_password' => 'required|in:Yes,No'
        ]);

        try {
            $user = User::create([
                'user_id' => $validated['user_id'],
                'username' => $validated['username'],
                'official_name' => $validated['official_name'],
                'official_title' => $request->official_title,
                'mobile_number' => $request->mobile_number,
                'official_tel' => $request->official_tel,
                'password' => bcrypt('temporary_password'),
                'status' => $validated['status'],
                'password_expiry_date' => $validated['password_expiry_date'],
                'amend_expired_password' => $validated['amend_expired_password']
            ]);

            return redirect()->route('users.index')
                ->with('success', 'User '.$user->user_id.' berhasil dibuat');

        } catch (\Exception $e) {
            Log::error('Error creating user: '.$e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal membuat user: '.$e->getMessage());
        }
    }

    public function edit(User $user)
    {
        try {
            return view('system-security/edit', compact('user'));
        } catch (\Exception $e) {
            Log::error('Error in UserController@edit: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat membuka form edit');
        }
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'user_id' => ['required','max:20',Rule::unique('users')->ignore($user->id)],
            'username' => ['required','max:30',Rule::unique('users')->ignore($user->id)],
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
                'amend_expired_password' => $validated['amend_expired_password']
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            return redirect()->route('users.index')
                ->with('success', 'User berhasil diperbarui');

        } catch (\Exception $e) {
            Log::error('Error updating user: '.$e->getMessage());
            return back()->withInput()
                ->with('error', 'Gagal memperbarui user. Silakan coba lagi.');
        }
    }

    public function destroy(User $user)
    {
        try {
            if ($user->user_id === Auth::user()->user_id) {
                return back()->with('error', 'Tidak dapat menghapus akun sendiri');
            }

            $user->delete();
            return redirect()->route('users.index')
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
            $user = User::where('user_id', $request->search_user_id)->first();
            
            if(!$user) {
                return redirect()->route('system-security/amend-password')
                    ->with('error', 'User ID tidak ditemukan');
            }
        }
        
        return view('system-security/amend', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = User::where('user_id', $request->user_id)->first();
        $user->update([
            'password' => bcrypt($request->new_password)
        ]);

        return redirect()->route('system-security/amend-password')
            ->with('success', 'Password berhasil diperbarui untuk user: '.$user->user_id);
    }
}